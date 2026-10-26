<?php

use PHPUnit\Framework\TestCase;

/**
 * How one media-plan row becomes documents.
 *
 * The team mostly issues **several quotations out of a single budget line** (equal
 * shares), and now and then **one quotation holding several items**. The reference for
 * the split shape is the September plan: Grande Pleno Pinklao - Charun 2 appears three
 * times (2026/1658, 1659, 1660) for 5,000 each — one 15,000 entry, three documents.
 */
final class QuotationBatchTest extends TestCase
{
    private function row(array $overrides = array()): array
    {
        return array_merge(array(
            'quotation_date'  => '2026-09-25',
            'quotation_no'    => '2026/1542',
            'job_no'          => '260902AP',
            'contact_name'    => ' คุณสุวิมล ศีลพิพัฒน์ ',
            'company_title'   => 'บริษัท เอพี เอ็มอี 18 จำกัด (สำนักงานใหญ่)',
            'company_address' => '170/57 อาคารโอเชี่ยนทาวเวอร์ 1 ชั้น 18 ถนนรัชดาภิเษกตัดใหม่ แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
            'project'         => 'Grande Pleno Pinklao - Charun 2',
            'project_file'    => 'Grande-Pleno-Pinklao-Charun-2_9-9',
            'ad_format'       => 'Boostpost',
            'period_text'     => '1 - 11 Sep 2026',
            'media_cost'      => 0,
            'budget_all'      => 15000,
            'agency_fee'      => true,
        ), $overrides);
    }

    private function batch(): QuotationBatch
    {
        return new QuotationBatch(new QuotationExporter(TEMPLATE_PATH, IMAGE_DIR));
    }

    private function outDir(): string
    {
        $dir = sys_get_temp_dir() . '/apqt-batch-' . bin2hex(random_bytes(4));
        mkdir($dir, 0700, true);

        return $dir;
    }

    private static function pdfText(string $pdf): string
    {
        return (string) shell_exec('pdftotext -layout ' . escapeshellarg($pdf) . ' - 2>/dev/null');
    }

    /** The number the customer reads on the grand-total line. */
    private static function grandTotal(string $pdf): float
    {
        $text = self::pdfText($pdf);
        self::assertMatchesRegularExpression('/รวมทั้งสิ้น \(Grand Total\)\s+([\d,]+\.\d\d)/u', $text);

        preg_match('/รวมทั้งสิ้น \(Grand Total\)\s+([\d,]+\.\d\d)/u', $text, $m);

        return (float) str_replace(',', '', $m[1]);
    }

    // ---------------------------------------------------------------- no split

    public function test_a_row_without_a_split_renders_one_document(): void
    {
        $docs = $this->batch()->export($this->row(array('budget_all' => 5000)), $this->outDir());

        $this->assertCount(1, $docs);
        $this->assertFileExists($docs[0]['pdf']);
        $this->assertSame(5000.00, self::grandTotal($docs[0]['pdf']));
    }

    public function test_a_merged_row_is_one_document_holding_every_item(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'project_file' => 'Apitown-Multi',
            'budget_all'   => 10000,
            'items'        => array(
                array('project' => 'Apitown Chonburi - Bangsaen', 'detail' => 'Boost post 1 - 11 Sep 2026', 'qty' => 2, 'unit_price' => 2500.00),
                array('project' => 'Pleno Rama 9 - Krungthepkreetha 4', 'detail' => 'Boost post 1 - 11 Sep 2026', 'qty' => 1, 'unit_price' => 3381.87),
            ),
            // A merged row wins over a split: splitting would break its subtotal.
            'split_into' => 3,
        )), $this->outDir());

        $this->assertCount(1, $docs);
        $text = self::pdfText($docs[0]['pdf']);
        $this->assertStringContainsString('Apitown Chonburi - Bangsaen', $text);
        $this->assertStringContainsString('Pleno Rama 9 - Krungthepkreetha 4', $text);
        $this->assertSame(10000.00, self::grandTotal($docs[0]['pdf']));
    }

    // ---------------------------------------------------------------- split

    public function test_one_row_can_become_several_equal_documents(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'split_into' => 3,
            'split_quotation_nos' => array('2026/1658', '2026/1659', '2026/1660'),
            'budget_all' => 15000,
        )), $this->outDir());

        $this->assertCount(3, $docs);
        $total = 0.0;
        foreach ($docs as $doc) {
            $this->assertFileExists($doc['pdf']);
            $total += self::grandTotal($doc['pdf']);
        }

        $this->assertSame(5000.00, self::grandTotal($docs[0]['pdf']));
        $this->assertSame(5000.00, self::grandTotal($docs[1]['pdf']));
        $this->assertSame(5000.00, self::grandTotal($docs[2]['pdf']));
        $this->assertSame(15000.00, round($total, 2), 'the documents must add back to the approved budget');
    }

    public function test_the_remainder_lands_on_the_last_document(): void
    {
        $docs = $this->batch()->export($this->row(array('split_into' => 3, 'budget_all' => 10000)), $this->outDir());

        $this->assertCount(3, $docs);
        // 3,333.33 cannot be printed: the fee and VAT are rounded to satang, so the
        // nearest printable totals are 3,333.32 and 3,333.34. The shares climb to
        // 3,333.34 and the last document absorbs the difference.
        $this->assertSame(3333.34, self::grandTotal($docs[0]['pdf']));
        $this->assertSame(3333.34, self::grandTotal($docs[1]['pdf']));
        $this->assertSame(3333.32, self::grandTotal($docs[2]['pdf']));
        $this->assertSame(10000.00, round(array_sum(array(
            self::grandTotal($docs[0]['pdf']),
            self::grandTotal($docs[1]['pdf']),
            self::grandTotal($docs[2]['pdf']),
        )), 2));
    }

    public function test_a_share_the_chain_cannot_print_is_nudged_to_one_it_can(): void
    {
        // 3,333.33 is unreachable; the search prefers the target, then the amount above
        // it, so the share never comes out lower than the one that was promised.
        $this->assertSame(3333.34, QuotationExporter::reachableTotal(3333.33));
        $this->assertSame(3333.32, QuotationExporter::reachableTotal(3333.32));
        $this->assertSame(5000.00, QuotationExporter::reachableTotal(5000.00));
    }

    public function test_each_document_gets_its_own_number_and_media_period(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'split_into' => 2,
            'budget_all' => 10000,
            'split_quotation_nos' => array('2026/1701', '2026/1702'),
            'split_periods' => array('1 - 11 Sep 2026', '12 - 22 Sep 2026'),
        )), $this->outDir());

        $this->assertCount(2, $docs);
        $this->assertNotSame($docs[0]['pdf'], $docs[1]['pdf']);

        $first = self::pdfText($docs[0]['pdf']);
        $second = self::pdfText($docs[1]['pdf']);
        $this->assertStringContainsString('2026/1701', $first);
        $this->assertStringContainsString('Boost post 1 - 11 Sep 2026', $first);
        $this->assertStringContainsString('2026/1702', $second);
        $this->assertStringContainsString('Boost post 12 - 22 Sep 2026', $second);
    }

    public function test_a_split_without_numbers_still_gets_unique_file_names(): void
    {
        $docs = $this->batch()->export($this->row(array('split_into' => 2, 'budget_all' => 8000)), $this->outDir());

        $this->assertCount(2, $docs);
        $this->assertNotSame($docs[0]['xlsx'], $docs[1]['xlsx']);
        $this->assertStringContainsString('2026-1542-1', basename($docs[0]['xlsx']));
        $this->assertStringContainsString('2026-1542-2', basename($docs[1]['xlsx']));
    }

    // ---------------------------------------------------------------- the arithmetic

    public function test_the_shares_add_up_to_the_total(): void
    {
        foreach (array(5000.0, 10000.0, 12345.0, 25000.0, 100000.0, 3333.33) as $total) {
            foreach (array(2, 3, 4, 7) as $count) {
                $shares = QuotationBatch::splitBudget($total, $count);
                $this->assertCount($count, $shares);
                $this->assertSame(round($total, 2), round(array_sum($shares), 2), "{$total} over {$count}");
            }
        }
    }
}
