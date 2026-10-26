<?php

use PHPUnit\Framework\TestCase;

/**
 * The gate for the quotation pipeline.
 *
 * Expectations come from the documents a human already approved (the thirteen
 * quotations in `exported_from original system_20260925`), not from the code. When a
 * test here disagrees with a reference, the difference is the finding — the reference
 * is not edited to make the test green.
 */
final class QuotationExporterTest extends TestCase
{
    /** QT 2026/1539 — Aspire Onnut Station, the reference used throughout. */
    private function referenceRow(): array
    {
        return array(
            'quotation_date'  => '2026-09-25',
            'quotation_no'    => '2026/1539',
            'job_no'          => '260902AP',
            'contact_name'    => ' คุณสุวิมล ศีลพิพัฒน์ ',
            'company_title'   => 'บริษัท เอพี เอ็มอี 18 จำกัด (สำนักงานใหญ่)',
            'company_address' => '170/57 อาคารโอเชี่ยนทาวเวอร์ 1 ชั้น 18 ถนนรัชดาภิเษกตัดใหม่ แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
            'project'         => 'Aspire Onnut Station',
            'project_file'    => 'Aspire-Onnut-Station-ถึงเวลาช้อน',
            'ad_format'       => 'Boostpost',
            'media_cost'      => 9345.79,
            'budget'          => 9345.79,
            'agency_fee'      => false,
        );
    }

    private function exporter(): QuotationExporter
    {
        return new QuotationExporter(TEMPLATE_PATH, IMAGE_DIR);
    }

    private function outDir(): string
    {
        $dir = sys_get_temp_dir() . '/apqt-test-' . bin2hex(random_bytes(4));
        mkdir($dir, 0700, true);

        return $dir;
    }

    private static function pdfText(string $pdf): string
    {
        $cmd = 'pdftotext -layout ' . escapeshellarg($pdf) . ' - 2>/dev/null';

        return (string) shell_exec($cmd);
    }

    public function test_customer_block_matches_the_reference(): void
    {
        $sheet = $this->exporter()->fill($this->referenceRow())->getActiveSheet();

        // The reference keeps the padding the original system wrote.
        $this->assertSame(' คุณสุวิมล ศีลพิพัฒน์ ', $sheet->getCell('E10')->getValue());
        $this->assertSame(
            'บริษัท เอพี เอ็มอี 18 จำกัด (สำนักงานใหญ่)',
            $sheet->getCell('E11')->getValue()
        );
        $this->assertStringContainsString(
            'อาคารโอเชี่ยนทาวเวอร์ 1',
            (string) $sheet->getCell('E12')->getValue()
        );
    }

    public function test_identity_and_job_cells_match_the_reference(): void
    {
        $sheet = $this->exporter()->fill($this->referenceRow())->getActiveSheet();

        $this->assertSame('2026/1539', $sheet->getCell('K12')->getValue());
        $this->assertSame('260902AP', $sheet->getCell('K14')->getValue());
        $this->assertSame('Aspire Onnut Station', $sheet->getCell('E23')->getValue());
        // K10 is an Excel serial; the reference cell holds 46290 for 2026-09-25.
        $this->assertEqualsWithDelta(46290, $sheet->getCell('K10')->getValue(), 0.001);
    }

    public function test_media_cost_lands_in_k24(): void
    {
        $sheet = $this->exporter()->fill($this->referenceRow())->getActiveSheet();

        $this->assertEqualsWithDelta(9345.79, (float) $sheet->getCell('K24')->getValue(), 0.001);
    }

    /**
     * K24 must carry PURE MEDIA (the media plan's N column), not the gross budget:
     * that is what makes the fee and the VAT add back to the approved round number.
     */
    public function test_k24_carries_pure_media_from_the_plan(): void
    {
        $row = $this->referenceRow();
        $row['media_cost'] = 8381.87;   // PURE MEDIA for a 10,000 budget
        $row['budget_all'] = 10000;

        $sheet = $this->exporter()->fill($row)->getActiveSheet();

        $this->assertEqualsWithDelta(8381.87, (float) $sheet->getCell('K24')->getValue(), 0.001);
    }

    public function test_k24_is_derived_from_the_budget_when_pure_media_is_missing(): void
    {
        $row = $this->referenceRow();
        $row['media_cost'] = 0;
        $row['budget_all'] = 10000;
        $row['budget'] = 10000;
        $row['agency_fee'] = true;   // the standard media plan carries the 11.5% fee

        $sheet = $this->exporter()->fill($row)->getActiveSheet();

        // 10,000 / 1.07 / 1.115 = 8,381.87 — the media plan's own PURE MEDIA value
        $this->assertEqualsWithDelta(8381.87, (float) $sheet->getCell('K24')->getValue(), 0.01);
    }

    /**
     * The reason PURE MEDIA is used at all: the customer approved a round budget, and
     * the document has to add back to it exactly — not to 10,000.01.
     */
    public function test_the_chain_lands_on_the_round_budget(): void
    {
        foreach (array(5000, 10000, 12345, 25000, 100000) as $target) {
            $row = $this->referenceRow();
            $row['media_cost'] = 0;
            $row['budget_all'] = $target;
            $row['budget'] = $target;
            $row['agency_fee'] = true;

            $sheet = $this->exporter()->fill($row)->getActiveSheet();
            $total = (float) $sheet->getCell('L50')->getCalculatedValue();

            $this->assertEqualsWithDelta(
                $target,
                $total,
                0.01,
                "a {$target} budget must come back out as the grand total"
            );
        }
    }

    /**
     * The item line reads "Boost post <period>" when the media plan gives a period,
     * and falls back to the ad format when it does not. Confirmed against the current
     * media plan (the September 2025 exports predate the period column).
     */
    public function test_the_item_description_uses_the_period_when_present(): void
    {
        $row = $this->referenceRow();
        $row['period_text'] = '1 - 11 Sep 2026';

        $sheet = $this->exporter()->fill($row)->getActiveSheet();

        $this->assertSame('Boost post 1 - 11 Sep 2026', $sheet->getCell('E24')->getValue());
    }

    public function test_the_item_description_falls_back_to_the_ad_format(): void
    {
        $row = $this->referenceRow();
        unset($row['period_text']);

        $sheet = $this->exporter()->fill($row)->getActiveSheet();

        $this->assertSame('Boost post Boostpost', $sheet->getCell('E24')->getValue());
    }

    /**
     * All thirteen references have no agency-fee line and carry net = subtotal.
     * This is the layout the pipeline must reproduce for a normal job.
     */
    public function test_a_job_without_agency_fee_matches_the_reference_layout(): void
    {
        $sheet = $this->exporter()->fill($this->referenceRow())->getActiveSheet();

        $this->assertNull($sheet->getCell('J47')->getValue(), 'J47 must stay empty');
        $this->assertNull($sheet->getCell('L47')->getValue(), 'L47 must stay empty');
        $this->assertSame('=L46', $sheet->getCell('L48')->getValue(), 'net = subtotal');
        $this->assertSame('=L48*7/100', $sheet->getCell('L49')->getValue(), 'VAT on net');
    }

    /**
     * Production passes rows straight from the database, where the flag does not
     * exist — so this is what a real job gets today. Pinned separately from the
     * reference layout so neither behaviour can drift unnoticed.
     */
    public function test_a_real_database_row_applies_the_agency_fee_by_default(): void
    {
        $row = $this->referenceRow();
        unset($row['agency_fee']);

        $sheet = $this->exporter()->fill($row)->getActiveSheet();

        $this->assertSame(' Agency Fee 11.5%', $sheet->getCell('J47')->getValue());
        $this->assertSame('=L46+L47', $sheet->getCell('L48')->getValue());
    }

    public function test_the_agency_fee_block_is_still_available_when_asked_for(): void
    {
        $row = $this->referenceRow();
        $row['agency_fee'] = true;

        $sheet = $this->exporter()->fill($row)->getActiveSheet();

        $this->assertSame(' Agency Fee 11.5%', $sheet->getCell('J47')->getValue());
        $this->assertSame('=ROUND(L46*11.5/100,2)', $sheet->getCell('L47')->getValue());
        $this->assertSame('=L46+L47', $sheet->getCell('L48')->getValue());
    }

    /**
     * 9,345.79 + 7% = 10,000.00 — the reference grand total, and the reason the
     * media cost is quoted net of VAT in the first place.
     */
    public function test_totals_equal_the_reference_grand_total(): void
    {
        $sheet = $this->exporter()->fill($this->referenceRow())->getActiveSheet();

        $this->assertEqualsWithDelta(9345.79, (float) $sheet->getCell('L46')->getCalculatedValue(), 0.01);
        $this->assertEqualsWithDelta(654.21, (float) $sheet->getCell('L49')->getCalculatedValue(), 0.01);
        $this->assertEqualsWithDelta(10000.00, (float) $sheet->getCell('L50')->getCalculatedValue(), 0.01);
    }

    public function test_export_writes_an_xlsx_and_a_pdf(): void
    {
        $dir = $this->outDir();
        $row = $this->referenceRow();

        $files = $this->exporter()->export($row, $dir);

        $this->assertFileExists($files['xlsx']);
        $this->assertNotNull($files['pdf'], 'LibreOffice conversion must produce a PDF');
        $this->assertFileExists($files['pdf']);
        $this->assertSame('2026-1539_Aspire-Onnut-Station-ถึงเวลาช้อน', basename($files['xlsx'], '.xlsx'));
    }

    public function test_the_pdf_carries_the_document_identity(): void
    {
        $dir = $this->outDir();
        $files = $this->exporter()->export($this->referenceRow(), $dir);
        $text = self::pdfText($files['pdf']);

        $this->assertStringContainsString('QUOTATION', $text);
        $this->assertStringContainsString('2026/1539', $text);
        $this->assertStringContainsString('Aspire Onnut Station', $text);
        $this->assertStringContainsString('บริษัท เอพี เอ็มอี 18', $text);
        $this->assertStringContainsString('10,000.00', $text);
    }

    public function test_the_pdf_states_the_total_in_words(): void
    {
        $dir = $this->outDir();
        $files = $this->exporter()->export($this->referenceRow(), $dir);
        $text = self::pdfText($files['pdf']);

        $this->assertStringContainsString('หนึ่งหมื่นบาทถ้วน', $text);
    }

    public function test_the_pdf_of_a_fee_bearing_job_totals_the_round_budget(): void
    {
        $row = $this->referenceRow();
        $row['media_cost'] = 0;
        $row['budget_all'] = 10000;
        $row['budget'] = 10000;
        $row['agency_fee'] = true;

        $dir = $this->outDir();
        $files = $this->exporter()->export($row, $dir);
        $text = self::pdfText($files['pdf']);

        $this->assertStringContainsString('8,381.87', $text, 'pure media in the line item');
        $this->assertStringContainsString('963.92', $text, 'agency fee 11.5%');
        $this->assertStringContainsString('9,345.79', $text, 'net');
        $this->assertStringContainsString('654.21', $text, 'VAT 7%');
        $this->assertStringContainsString('10,000.00', $text, 'grand total');
    }

    public function test_file_names_are_filesystem_safe(): void
    {
        $this->assertSame(
            '2026-1539_Aspire-Onnut-Station',
            QuotationExporter::fileName(array(
                'quotation_no' => '2026/1539',
                'project_file' => 'Aspire-Onnut-Station',
            ))
        );
        $this->assertStringNotContainsString('/', QuotationExporter::fileName($this->referenceRow()));
    }
}
