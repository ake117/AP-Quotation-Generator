<?php

use PHPUnit\Framework\TestCase;

/**
 * SPEC — a quotation may carry more than one media item.
 *
 * This file is the specification, written before the implementation. It is expected to
 * be red until QuotationExporter renders `items`; the implementation must not touch it
 * (the DaCode trial that implements it is gated on these assertions).
 *
 * Layout follows the document that is already in use: one item occupies two rows —
 * a name row and a detail row — and the quantities live on the detail row.
 *
 *   row 23: D=1  E=<project 1>
 *   row 24:      E=<detail 1>   J=qty  K=unit price  L==K24*J24
 *   row 25: D=2  E=<project 2>
 *   row 26:      E=<detail 2>   J=qty  K=unit price  L==K26*J26
 *
 * The template already merges E:H on rows 23–45 and sums L23:L45 in L46.
 */
final class MultiItemQuotationTest extends TestCase
{
    private function row(array $items): array
    {
        return array(
            'quotation_date'  => '2026-09-25',
            'quotation_no'    => '2026/1542',
            'job_no'          => '260902AP',
            'contact_name'    => ' คุณสุวิมล ศีลพิพัฒน์ ',
            'company_title'   => 'บริษัท เอพี เอ็มอี 18 จำกัด (สำนักงานใหญ่)',
            'company_address' => '170/57 อาคารโอเชี่ยนทาวเวอร์ 1',
            'project'         => $items[0]['project'],
            'project_file'    => 'Apitown-Chonburi',
            'ad_format'       => 'Boostpost',
            'period_text'     => '1 - 11 Sep 2026',
            'media_cost'      => 0,
            'budget_all'      => 10000,
            'budget'          => 10000,
            'agency_fee'      => true,
            'items'           => $items,
        );
    }

    /** Two items: 2 × 2,500.00 + 1 × 3,381.87 = 8,381.87 of pure media. */
    private function twoItems(): array
    {
        return array(
            array(
                'project'    => 'Apitown Chonburi - Bangsaen',
                'detail'     => 'Boost post 1 - 11 Sep 2026',
                'qty'        => 2,
                'unit_price' => 2500.00,
            ),
            array(
                'project'    => 'Pleno Rama 9 - Krungthepkreetha 4',
                'detail'     => 'Boost post 1 - 11 Sep 2026',
                'qty'        => 1,
                'unit_price' => 3381.87,
            ),
        );
    }

    private function exporter(): QuotationExporter
    {
        return new QuotationExporter(TEMPLATE_PATH, IMAGE_DIR);
    }

    private function outDir(): string
    {
        $dir = sys_get_temp_dir() . '/apqt-multi-' . bin2hex(random_bytes(4));
        mkdir($dir, 0700, true);

        return $dir;
    }

    public function test_each_item_occupies_its_own_two_rows(): void
    {
        $sheet = $this->exporter()->fill($this->row($this->twoItems()))->getActiveSheet();

        // first item
        $this->assertSame(1, (int) $sheet->getCell('D23')->getValue());
        $this->assertSame('Apitown Chonburi - Bangsaen', $sheet->getCell('E23')->getValue());
        $this->assertSame('Boost post 1 - 11 Sep 2026', $sheet->getCell('E24')->getValue());
        $this->assertEqualsWithDelta(2, (float) $sheet->getCell('J24')->getValue(), 0.001);
        $this->assertEqualsWithDelta(2500.00, (float) $sheet->getCell('K24')->getValue(), 0.001);
        $this->assertSame('=K24*J24', $sheet->getCell('L24')->getValue());

        // second item
        $this->assertSame(2, (int) $sheet->getCell('D25')->getValue());
        $this->assertSame('Pleno Rama 9 - Krungthepkreetha 4', $sheet->getCell('E25')->getValue());
        $this->assertSame('Boost post 1 - 11 Sep 2026', $sheet->getCell('E26')->getValue());
        $this->assertEqualsWithDelta(1, (float) $sheet->getCell('J26')->getValue(), 0.001);
        $this->assertEqualsWithDelta(3381.87, (float) $sheet->getCell('K26')->getValue(), 0.001);
        $this->assertSame('=K26*J26', $sheet->getCell('L26')->getValue());
    }

    public function test_the_subtotal_adds_up_every_item(): void
    {
        $sheet = $this->exporter()->fill($this->row($this->twoItems()))->getActiveSheet();

        // 2 × 2,500.00 + 1 × 3,381.87
        $this->assertEqualsWithDelta(8381.87, (float) $sheet->getCell('L46')->getCalculatedValue(), 0.01);
    }

    public function test_the_grand_total_still_closes_on_the_round_budget(): void
    {
        $sheet = $this->exporter()->fill($this->row($this->twoItems()))->getActiveSheet();

        $this->assertEqualsWithDelta(9345.79, (float) $sheet->getCell('L48')->getCalculatedValue(), 0.01);
        $this->assertEqualsWithDelta(10000.00, (float) $sheet->getCell('L50')->getCalculatedValue(), 0.01);
    }

    public function test_rows_below_the_last_item_stay_empty(): void
    {
        $sheet = $this->exporter()->fill($this->row($this->twoItems()))->getActiveSheet();

        foreach (array('D27', 'E27', 'J27', 'K27', 'L27', 'D28', 'E28') as $cell) {
            $this->assertNull($sheet->getCell($cell)->getValue(), "{$cell} must stay empty");
        }
    }

    public function test_the_pdf_lists_every_item(): void
    {
        $dir = $this->outDir();
        $files = $this->exporter()->export($this->row($this->twoItems()), $dir);
        $text = (string) shell_exec('pdftotext -layout ' . escapeshellarg($files['pdf']) . ' - 2>/dev/null');

        $this->assertStringContainsString('Apitown Chonburi - Bangsaen', $text);
        $this->assertStringContainsString('Pleno Rama 9 - Krungthepkreetha 4', $text);
        $this->assertStringContainsString('5,000.00', $text, 'first line amount (2 x 2,500.00)');
        $this->assertStringContainsString('3,381.87', $text, 'second line amount');
        $this->assertStringContainsString('8,381.87', $text, 'subtotal');
        $this->assertStringContainsString('10,000.00', $text, 'grand total');
    }

    public function test_a_row_without_items_keeps_the_single_item_layout(): void
    {
        $row = $this->row($this->twoItems());
        unset($row['items']);

        $sheet = $this->exporter()->fill($row)->getActiveSheet();

        $this->assertSame('Apitown Chonburi - Bangsaen', $sheet->getCell('E23')->getValue());
        $this->assertSame('Boost post 1 - 11 Sep 2026', $sheet->getCell('E24')->getValue());
        $this->assertNull($sheet->getCell('E25')->getValue());
    }
}
