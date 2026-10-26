<?php

require_once __DIR__ . '/QuotationExporter.php';

/**
 * Turns one media-plan row into the documents the team actually issues.
 *
 * Two shapes reach the customer, and a row says which one it is:
 *
 *  - **One document holding several items (merge)** — the row carries an `items` array.
 *    Every item becomes two lines on the single quotation. Common when a campaign buys
 *    several packages at once.
 *  - **Several documents out of one row (split)** — the row carries `split_into` > 1.
 *    The budget is divided equally, each document gets its own number and its own
 *    copy of the row, and the customer receives N quotations. This is the usual case:
 *    one budget line, several documents (different billing entity, different period).
 *
 * The renderer stays a single-document class (`QuotationExporter`); this class only
 * decides how many documents a row becomes and what each one carries. Keeping the
 * decision out of the renderer is what lets both paths be tested without CodeIgniter.
 *
 * Row keys this class reads, on top of the exporter's:
 *
 *   split_into          int    documents to issue (1 = no split)
 *   split_quotation_nos array  quotation number per document; when absent the base
 *                              number gets a "-<n>" suffix so files never collide
 *   split_periods       array  media period per document (K column); when absent every
 *                              document keeps the row's own period
 */
final class QuotationBatch
{
    /** @var QuotationExporter */
    private $exporter;

    public function __construct(QuotationExporter $exporter)
    {
        $this->exporter = $exporter;
    }

    /**
     * @return array<int, array{xlsx: string, pdf: string}> one entry per document
     */
    public function export(array $row, string $outDir): array
    {
        $count = $this->documentCount($row);

        if ($count === 1) {
            return array($this->exporter->export($row, $outDir));
        }

        $budgets = self::splitBudget(self::budgetOf($row), $count, $this->agencyFee($row));
        $documents = array();

        foreach ($budgets as $index => $budget) {
            $documents[] = $this->exporter->export($this->documentFor($row, $index, $budget), $outDir);
        }

        return $documents;
    }

    /**
     * A merged row (several items) is always one document: the items are the point,
     * and splitting a merged quotation would break the subtotal the customer sees.
     */
    public function documentCount(array $row): int
    {
        if (!empty($row['items']) && is_array($row['items'])) {
            return 1;
        }

        return max(1, (int) ($row['split_into'] ?? 1));
    }

    /**
     * Equal shares, with the odd satang on the last document — so the parts always add
     * back to the approved total.
     *
     * A share is nudged to the nearest amount the fee/VAT rounding can actually print
     * (3,333.33 is impossible; 3,333.34 is the closest above it), which is why 10,000
     * over 3 comes out as 3,333.34 + 3,333.34 + 3,333.32 — every document closes on its
     * own total instead of drifting from the one the customer was promised.
     *
     * @return array<int, float>
     */
    public static function splitBudget(float $total, int $count, bool $agencyFee = true): array
    {
        $count = max(1, $count);
        $total = round($total, 2);

        if ($count === 1) {
            return array($total);
        }

        $shares = array();
        $remaining = $total;

        for ($index = 0; $index < $count - 1; $index++) {
            $target = round($remaining / ($count - $index), 2);
            $share = QuotationExporter::reachableTotal($target, $agencyFee);
            if ($share > $remaining) {
                $share = $target;
            }
            $shares[] = $share;
            $remaining = round($remaining - $share, 2);
        }

        $shares[] = $remaining;

        return $shares;
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
     * One document's copy of the row: its own number, its own budget and its own media
     * period. `media_cost` is cleared so the exporter derives PURE MEDIA from this
     * document's budget instead of the row's full figure.
     */
    private function documentFor(array $row, int $index, float $budget): array
    {
        $doc = $row;
        $doc['budget_all'] = $budget;
        $doc['budget'] = $budget;
        $doc['media_cost'] = 0;
        $doc['quotation_no'] = self::quotationNo($row, $index);

        if (!empty($row['split_periods'][$index])) {
            $doc['period_text'] = (string) $row['split_periods'][$index];
        }

        return $doc;
    }

    private static function quotationNo(array $row, int $index): string
    {
        if (!empty($row['split_quotation_nos'][$index])) {
            return (string) $row['split_quotation_nos'][$index];
        }

        return (string) ($row['quotation_no'] ?? '') . '-' . ($index + 1);
    }
}
