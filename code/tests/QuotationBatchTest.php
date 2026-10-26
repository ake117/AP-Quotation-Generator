<?php

use PHPUnit\Framework\TestCase;

/**
 * How one media-plan row becomes documents.
 *
 * The team mostly issues **several quotations out of a single budget line**: the note
 * column (E) lists the ProjectIDs the money is split across, one document each, equal
 * shares. Now and then **one quotation holds several items** instead.
 *
 * Reference for the split shape: the September plan issues Grande Pleno Pinklao -
 * Charun 2 as 2026/1658, 1659 and 1660 at 5,000 each — one 15,000 entry, three documents.
 */
final class QuotationBatchTest extends TestCase
{
    private function row(array $overrides = array()): array
    {
        return array_merge(array(
            'quotation_date'  => '2026-09-25',
            'quotation_no'    => '2026/1658',
            'job_no'          => '260902AP',
            'contact_name'    => ' คุณสุวิมล ศีลพิพัฒน์ ',
            'company_title'   => 'บริษัท เอพี เอ็มอี 18 จำกัด (สำนักงานใหญ่)',
            'company_address' => '170/57 อาคารโอเชี่ยนทาวเวอร์ 1 ชั้น 18 ถนนรัชดาภิเษกตัดใหม่ แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
            'project'         => 'Grande Pleno Pinklao - Charun 2',
            'project_file'    => 'Grande-Pleno-Pinklao-Charun-2_9-9',
            'ad_format'       => 'Boostpost',
            'period_text'     => '14 - 17 Sep 2026',
            'note'            => '40194',
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

    /** The total as the customer reads it — two decimals, because the cell format says so. */
    private static function printedTotal(string $pdf): float
    {
        $text = self::pdfText($pdf);
        self::assertMatchesRegularExpression('/รวมทั้งสิ้น \(Grand Total\)\s+([\d,]+\.\d\d)/u', $text);
        preg_match('/รวมทั้งสิ้น \(Grand Total\)\s+([\d,]+\.\d\d)/u', $text, $m);

        return (float) str_replace(',', '', $m[1]);
    }

    /** The total the file actually carries, before the two-decimal display rounds it. */
    private static function carriedTotal(string $xlsx): float
    {
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($xlsx)->getSheet(0);

        return (float) $sheet->getCell('L50')->getCalculatedValue();
    }

    // ------------------------------------------------------- the note decides the count

    public function test_a_note_with_one_project_id_is_one_document(): void
    {
        $docs = $this->batch()->export($this->row(array('budget_all' => 5000)), $this->outDir());

        $this->assertCount(1, $docs);
        $this->assertSame(5000.00, self::printedTotal($docs[0]['pdf']));
        $this->assertStringContainsString('Grande Pleno Pinklao - Charun 2', self::pdfText($docs[0]['pdf']));
    }

    public function test_every_project_id_in_the_note_becomes_its_own_document(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'note'       => '40194/40112',
            'budget_all' => 10000,
        )), $this->outDir());

        $this->assertCount(2, $docs);
        $this->assertStringContainsString('Grande Pleno Pinklao - Charun 2', self::pdfText($docs[0]['pdf']));
        $this->assertStringContainsString('Grande Pleno Pinklao - Kanchana', self::pdfText($docs[1]['pdf']));
        $this->assertSame(5000.00, self::printedTotal($docs[0]['pdf']));
        $this->assertSame(5000.00, self::printedTotal($docs[1]['pdf']));
    }

    public function test_ids_can_be_separated_by_commas_slashes_or_line_breaks(): void
    {
        $expected = array('40194', '40112');

        $this->assertSame($expected, QuotationBatch::projectIds('40194, 40112'));
        $this->assertSame($expected, QuotationBatch::projectIds('40194/40112'));
        $this->assertSame($expected, QuotationBatch::projectIds("40194\r\n40112"));
        $this->assertSame($expected, QuotationBatch::projectIds(" 40194 , 40112 /"));
        // The same ID twice is one document, not two.
        $this->assertSame($expected, QuotationBatch::projectIds('40194, 40112, 40194'));
        $this->assertSame(array(), QuotationBatch::projectIds(''));
    }

    public function test_an_unknown_id_keeps_the_rows_project_instead_of_inventing_one(): void
    {
        $docs = $this->batch()->export($this->row(array('note' => '99999', 'budget_all' => 5000)), $this->outDir());

        $this->assertCount(1, $docs);
        $this->assertStringContainsString('Grande Pleno Pinklao - Charun 2', self::pdfText($docs[0]['pdf']));
    }

    // ------------------------------------------------------- the money

    public function test_the_share_keeps_six_decimals_while_the_cell_shows_two(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'note'       => '40194/40112/70105',
            'budget_all' => 10000,
            'project_file' => 'split-three',
        )), $this->outDir());

        $this->assertCount(3, $docs);

        $carried = 0.0;
        foreach ($docs as $doc) {
            // The customer reads two decimals…
            $this->assertSame(3333.33, self::printedTotal($doc['pdf']));
            // …while the document keeps the third of a satang, so the three add up.
            $carried += self::carriedTotal($doc['xlsx']);
        }

        $this->assertEqualsWithDelta(10000.00, $carried, 0.005, 'the three documents must carry the whole budget');
    }

    public function test_unequal_shares_can_be_given(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'note'          => '40194/40112',
            'budget_all'    => 10000,
            'split_amounts' => array(6000, 4000),
        )), $this->outDir());

        $this->assertCount(2, $docs);
        $this->assertSame(6000.00, self::printedTotal($docs[0]['pdf']));
        $this->assertSame(4000.00, self::printedTotal($docs[1]['pdf']));
    }

    public function test_the_shares_add_up_to_the_total(): void
    {
        foreach (array(5000.0, 10000.0, 12345.0, 25000.0, 100000.0, 3333.33) as $total) {
            foreach (array(2, 3, 4, 7) as $count) {
                $shares = QuotationBatch::splitBudget($total, $count);
                $this->assertCount($count, $shares);
                $this->assertEqualsWithDelta($total, array_sum($shares), 0.0000005, "{$total} over {$count}");
            }
        }
    }

    // ------------------------------------------------------- merge still wins

    public function test_a_merged_row_is_one_document_holding_every_item(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'note'         => '40194/40112',
            'project_file' => 'Apitown-Multi',
            'budget_all'   => 10000,
            'items'        => array(
                array('project' => 'Apitown Chonburi - Bangsaen', 'detail' => 'Boost post 1 - 11 Sep 2026', 'qty' => 2, 'unit_price' => 2500.00),
                array('project' => 'Pleno Rama 9 - Krungthepkreetha 4', 'detail' => 'Boost post 1 - 11 Sep 2026', 'qty' => 1, 'unit_price' => 3381.87),
            ),
        )), $this->outDir());

        $this->assertCount(1, $docs);
        $text = self::pdfText($docs[0]['pdf']);
        $this->assertStringContainsString('Apitown Chonburi - Bangsaen', $text);
        $this->assertStringContainsString('Pleno Rama 9 - Krungthepkreetha 4', $text);
        $this->assertSame(10000.00, self::printedTotal($docs[0]['pdf']));
    }

    // ------------------------------------------------------- identity of each document

    public function test_each_document_gets_its_own_number_and_media_period(): void
    {
        $docs = $this->batch()->export($this->row(array(
            'note'                => '40194/40112',
            'budget_all'          => 10000,
            'split_quotation_nos' => array('2026/1701', '2026/1702'),
            'split_periods'       => array('1 - 11 Sep 2026', '12 - 22 Sep 2026'),
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
        $docs = $this->batch()->export($this->row(array(
            'note'       => '40194/40112',
            'budget_all' => 8000,
        )), $this->outDir());

        $this->assertCount(2, $docs);
        $this->assertNotSame($docs[0]['xlsx'], $docs[1]['xlsx']);
        $this->assertStringContainsString('2026-1658-1', basename($docs[0]['xlsx']));
        $this->assertStringContainsString('2026-1658-2', basename($docs[1]['xlsx']));
    }
}
