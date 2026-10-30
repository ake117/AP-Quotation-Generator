<?php

require_once __DIR__ . '/QuotationExporter.php';
require_once __DIR__ . '/ProjectDirectory.php';

/**
 * Turns one media-plan row into the documents the team actually issues.
 *
 * Two shapes reach the customer, and the row itself says which one it is:
 *
 *  - **One document holding several items (merge)** — the row carries an `items` array.
 *    Every item becomes two lines on the single quotation.
 *  - **Several documents out of one row (split)** — the note column (E) lists one or
 *    more ProjectIDs. One document is issued per ID, each carrying that project's name
 *    and an equal share of the budget. This is the usual case.
 *
 * The document count, in order:
 *
 *   1. `items`                       — a merged row is always one document
 *   2. count of `split_amounts`      — explicit money per document also fixes the count
 *   3. count of ProjectIDs in `note` — comma, slash or line break separated
 *   4. `split_into`
 *   5. one document
 *
 * Money: shares keep **six decimals**; the cells display two. A third of 10,000 is
 * 3,333.333333, and rounding the share to satang before writing the document loses a
 * satang per document — three documents would add up to 9,999.99 while the customer was
 * charged 10,000. The quotation prints 3,333.33 (the cell format does that) and the three
 * add back to exactly 10,000.
 */
final class QuotationBatch
{
    /** @var QuotationExporter */
    private $exporter;

    /** @var array<string, string>|null ProjectID => name; null falls back to ProjectDirectory */
    private $names;

    /**
     * @param array<string, string>|null $names ProjectID => project name, when the caller
     *                                           has a fresher source than the bundled
     *                                           ProjectDirectory.
     */
    public function __construct(QuotationExporter $exporter, ?array $names = null)
    {
        $this->exporter = $exporter;
        $this->names = $names;
    }

    /**
     * @return array<int, array{xlsx: string, pdf: string}> one entry per document
     */
    public function export(array $row, string $outDir, bool $convertPdf = true): array
    {
        $ids = self::projectIds((string) ($row['note'] ?? ''));
        $count = $this->documentCount($row);

        if ($count === 1) {
            // No split: the row keeps its own money path (PURE MEDIA is searched so the
            // total prints round) and only borrows the name of its ProjectID.
            return array($this->exporter->export($this->withProjectName($row, $ids[0] ?? null), $outDir, $convertPdf));
        }

        $amounts = $this->shares($row, $count);

        $documents = array();
        foreach ($amounts as $index => $amount) {
            $documents[] = $this->exporter->export(
                $this->documentFor($row, $index, $amount, $ids[$index] ?? null),
                $outDir,
                $convertPdf
            );
        }

        return $documents;
    }

    /**
     * A merged row is always one document: the items are the point, and splitting a
     * merged quotation would break the subtotal the customer reads.
     */
    public function documentCount(array $row): int
    {
        if (!empty($row['items']) && is_array($row['items'])) {
            return 1;
        }

        if (!empty($row['split_amounts']) && is_array($row['split_amounts'])) {
            return count($row['split_amounts']);
        }

        $ids = self::projectIds((string) ($row['note'] ?? ''));
        if ($ids) {
            return count($ids);
        }

        return max(1, (int) ($row['split_into'] ?? 1));
    }

    /**
     * ProjectIDs out of the note column: "40194, 40112", "40194/40112" and one ID per
     * line all mean two documents.
     *
     * An ID is at most three letters followed by at least two digits, optionally with
     * more letters or digits after them — 40194, AP0001, AC0003. Commas, slashes, line
     * breaks and spaces separate them; any other word in the note ("Boost post") simply
     * does not look like an ID and is ignored. IDs compare case-insensitively, so the
     * same ID written twice is still one document.
     *
     * @return array<int, string>
     */
    public static function projectIds(string $note): array
    {
        preg_match_all('/[A-Za-z]{0,3}[0-9]{2,}[A-Za-z0-9]*/', $note, $matches);

        $ids = array();
        foreach ($matches[0] as $candidate) {
            $candidate = strtoupper(trim($candidate));
            if ($candidate !== '' && !in_array($candidate, $ids, true)) {
                $ids[] = $candidate;
            }
        }

        return $ids;
    }

    /**
     * Equal shares in six decimals, with the odd fraction on the last document.
     *
     * @return array<int, float>
     */
    public static function splitBudget(float $total, int $count): array
    {
        $count = max(1, $count);
        $total = round($total, 6);

        if ($count === 1) {
            return array($total);
        }

        $share = round($total / $count, 6);
        $shares = array_fill(0, $count, $share);
        $shares[$count - 1] = round($total - $share * ($count - 1), 6);

        return $shares;
    }

    /**
     * The row that carries the column headers.
     *
     * The PO Status file keeps its headers on row 4 (above it sit the counters), while an
     * older media plan keeps them on row 1. Pick the row that names the most columns we
     * know, so neither layout has to be told apart by hand.
     *
     * @param array<int, array<int, mixed>> $rows 1-based: [rowNumber => [colIndex => value]]
     * @param array<int, string> $known the header names we are looking for
     */
    public static function headerRow(array $rows, array $known): int
    {
        $known = array_map('strtoupper', $known);
        $best = 1;
        $bestHits = -1;

        foreach ($rows as $rowNumber => $cells) {
            $hits = 0;
            foreach ($cells as $cell) {
                $cell = strtoupper(trim((string) $cell));
                if ($cell !== '' && in_array($cell, $known, true)) {
                    $hits++;
                }
            }

            if ($hits > $bestHits) {
                $bestHits = $hits;
                $best = (int) $rowNumber;
            }
        }

        return $bestHits > 0 ? $best : 1;
    }

    /**
     * One source row -> the rows its documents are.
     *
     * A plan row that names several ProjectIDs is written the way the team writes it by
     * hand: one row per document, each with its own number, its own project and its own
     * share of the budget (rows 49/49 of the September sheet, 2026/1591 and 2026/1592).
     *
     * @param array<int, string> $numbers one number per document, already reserved
     * @return array<int, array<string, mixed>>
     */
    public static function splitRows(array $row, array $numbers): array
    {
        $ids = self::projectIds((string) ($row['note'] ?? ''));
        $count = max(1, count($numbers));
        $amounts = (!empty($row['split_amounts']) && is_array($row['split_amounts']))
            ? array_map('floatval', array_values($row['split_amounts']))
            : self::splitBudget(self::budgetOf($row), $count);

        $agencyFee = !isset($row['agency_fee']) || (bool) $row['agency_fee'];
        $rows = array();

        foreach (array_slice($amounts, 0, $count) as $index => $amount) {
            $doc = $row;
            $projectId = $ids[$index] ?? null;

            if ($projectId !== null) {
                $doc['project'] = self::nameOf($projectId, $row);
                $doc['project_file'] = self::withProject(
                    (string) ($row['project_file'] ?? ''),
                    (string) ($row['project'] ?? ''),
                    $doc['project']
                );
                // Each row stands for one document, so it names one ID — otherwise the
                // export would split it again.
                $doc['note'] = $projectId;
            }

            $doc['quotation_no'] = (string) ($numbers[$index] ?? $row['quotation_no'] ?? '');
            $doc['budget_all'] = $amount;
            $doc['budget'] = $amount;
            $doc['media_cost'] = round($amount / (1.07 * ($agencyFee ? 1.115 : 1.0)), 6);
            $rows[] = $doc;
        }

        return $rows;
    }

    /** Name for an ID from the directory, falling back to the row's own project. */
    private static function nameOf(string $projectId, array $row): string
    {
        return ProjectDirectory::name($projectId) ?? (string) ($row['project'] ?? '');
    }

    /**
     * The numbers a split takes, run on from the last one used: 2026/1591, 2026/1592, …
     *
     * Same shape the import writes (year + four digits, growing past 9999 without
     * complaint), so a document issued from a split looks like every other document.
     *
     * @return array{numbers: array<int, string>, last: int}
     */
    public static function consecutiveNumbers(int $year, int $lastRunning, int $count): array
    {
        $numbers = array();
        $running = max(0, $lastRunning);

        for ($index = 0; $index < max(0, $count); $index++) {
            $running++;
            $numbers[] = $year . '/' . str_pad((string) $running, 4, '0', STR_PAD_LEFT);
        }

        return array('numbers' => $numbers, 'last' => $running);
    }

    /**
     * The highest number any of these quotation numbers already uses, 0 when none do.
     *
     * "2026/1541" -> 1541. A document must never be issued a number that is already on
     * file, so an export starts from the higher of the counter and this floor.
     *
     * @param array<int, mixed> $quotationNos
     */
    public static function highestNumber(array $quotationNos): int
    {
        $highest = 0;

        foreach ($quotationNos as $quotationNo) {
            $quotationNo = (string) $quotationNo;
            $position = strrpos($quotationNo, '/');
            $digits = $position === false ? $quotationNo : substr($quotationNo, $position + 1);
            $digits = preg_replace('/[^0-9]/', '', $digits);

            if ($digits !== '') {
                $highest = max($highest, (int) $digits);
            }
        }

        return $highest;
    }

    /**
     * Explicit amounts win — unequal splits are supported — otherwise the budget is
     * shared equally.
     *
     * @return array<int, float>
     */
    private function shares(array $row, int $count): array
    {
        if (!empty($row['split_amounts']) && is_array($row['split_amounts'])) {
            return array_map('floatval', array_values($row['split_amounts']));
        }

        return self::splitBudget($this->budgetOf($row), $count);
    }

    private function agencyFee(array $row): bool
    {
        return !isset($row['agency_fee']) || (bool) $row['agency_fee'];
    }

    private static function budgetOf(array $row): float
    {
        $total = (float) ($row['budget_all'] ?? 0);

        if ($total <= 0) {
            $total = (float) ($row['budget'] ?? 0);
        }

        return $total;
    }

    /**
     * One document's copy of the row: its own project, number, amount and media period.
     */
    private function documentFor(array $row, int $index, float $amount, ?string $projectId): array
    {
        $doc = $row;
        $agencyFee = $this->agencyFee($row);

        if ($projectId !== null) {
            $doc['project'] = $this->projectName($projectId, $row);
            // The document is named the way the team names it by hand: the brief from
            // column D with this document's project (column C of its ID) on the end.
            $doc['project_file'] = self::withProject(
                (string) ($row['project_file'] ?? ''),
                (string) ($row['project'] ?? ''),
                $doc['project']
            );
        }

        $doc['quotation_no'] = self::quotationNo($row, $index);
        $doc['budget_all'] = $amount;
        $doc['budget'] = $amount;
        // PURE MEDIA comes straight from the share: routing through pureMedia() would
        // hunt for a two-decimal value, and a split document must keep the fraction.
        $doc['media_cost'] = round($amount / (1.07 * ($agencyFee ? 1.115 : 1.0)), 6);
        if (!empty($row['split_periods'][$index])) {
            $doc['period_text'] = (string) $row['split_periods'][$index];
        }

        return $doc;
    }

    /**
     * Brief name + the document's own project.
     *
     * The September plan shows the shape the team wants: rows 51 and 52 both read
     * "ทาวน์โฮม และ บ้านแฝด โซน เพชรเกษม-บางแค_<project>", differing only in the project the
     * row belongs to. So when the brief already names the row's project, that project is
     * swapped for this document's; when the brief carries no project, one is appended.
     */
    public static function withProject(string $brief, string $rowProject, string $project): string
    {
        $brief = trim($brief);
        $project = trim($project);

        if ($project === '') {
            return $brief;
        }
        if ($brief === '') {
            return $project;
        }

        $rowProject = trim($rowProject);
        if ($rowProject !== '' && $rowProject !== $project) {
            $position = mb_strpos($brief, $rowProject);
            if ($position !== false) {
                return mb_substr($brief, 0, $position) . $project
                    . mb_substr($brief, $position + mb_strlen($rowProject));
            }
        }

        return mb_strpos($brief, $project) !== false ? $brief : $brief . '_' . $project;
    }

    /** The row with the project name its ProjectID gives it, when the ID is known. */
    private function withProjectName(array $row, ?string $projectId): array
    {
        if ($projectId === null) {
            return $row;
        }

        $name = $this->projectName($projectId, $row);
        if ($name === (string) $row['project']) {
            return $row;
        }

        $row['project'] = $name;
        $row['project_file'] = QuotationExporter::sanitizeText($name, '-');

        return $row;
    }

    private function projectName(string $projectId, array $row): string
    {
        $name = $this->names[strtoupper($projectId)] ?? ProjectDirectory::name($projectId);

        // An unknown ID is a finding, not a reason to invent a name: keep the row's own
        // project so the mistake is visible instead of quietly quoting another project.
        return $name ?? (string) $row['project'];
    }

    private static function quotationNo(array $row, int $index): string
    {
        if (!empty($row['split_quotation_nos'][$index])) {
            return (string) $row['split_quotation_nos'][$index];
        }

        return (string) ($row['quotation_no'] ?? '') . '-' . ($index + 1);
    }
}
