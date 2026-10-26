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
    public function export(array $row, string $outDir): array
    {
        $ids = self::projectIds((string) ($row['note'] ?? ''));
        $amounts = $this->shares($row, $this->documentCount($row));

        $documents = array();
        foreach ($amounts as $index => $amount) {
            $documents[] = $this->exporter->export(
                $this->documentFor($row, $index, $amount, $ids[$index] ?? null),
                $outDir
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
     * line all mean two documents. Everything that is not a digit counts as a separator,
     * which also absorbs stray spaces and trailing slashes.
     *
     * @return array<int, string>
     */
    public static function projectIds(string $note): array
    {
        $ids = array();

        foreach (preg_split('/[^0-9]+/', $note) as $candidate) {
            $candidate = trim($candidate);
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
            $doc['project_file'] = QuotationExporter::sanitizeText($doc['project'], '-');
        }

        $doc['quotation_no'] = self::quotationNo($row, $index);
        $doc['budget_all'] = $amount;
        $doc['budget'] = $amount;
        // PURE MEDIA comes straight from the share: routing through pureMedia() would
        // hunt for a two-decimal value, and a split document must keep the fraction.
        $doc['media_cost'] = round($amount / (1.07 * ($agencyFee ? 1.115 : 1.0)), 6);
        // Six decimals only when the share actually carries a fraction: a document that
        // lands on a whole satang must keep the two-decimal fee, or its printed total
        // drifts off the round figure the customer approved.
        $doc['fee_precision'] = (round($amount, 2) == $amount) ? 2 : 6;

        if (!empty($row['split_periods'][$index])) {
            $doc['period_text'] = (string) $row['split_periods'][$index];
        }

        return $doc;
    }

    private function projectName(string $projectId, array $row): string
    {
        $name = $this->names[$projectId] ?? ProjectDirectory::name($projectId);

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
