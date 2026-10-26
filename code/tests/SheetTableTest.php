<?php

use PHPUnit\Framework\TestCase;

/**
 * Reading the plan straight from the Google Sheet: the values the API returns have to map
 * to the same rows the uploaded workbook produces, and keep the sheet row they came from so
 * the numbers can be written back.
 */
final class SheetTableTest extends TestCase
{
    private function fields(): array
    {
        return array(
            'PROJECT'   => 'project',
            'BRIEFNAME' => 'project_file',
            'NOTE'      => 'note',
            'QT NO.'    => 'quotation_no',
            'PERIOD'    => 'period_text',
            'BUDGET ALL' => 'budget_all',
        );
    }

    /** Values as the API returns them: row 1 first, headers on row 4 like the PO Status file. */
    private function values(): array
    {
        return array(
            array('', '', 'รอ PO', 'ส่งงานแล้ว'),
            array('', '', '95,000.00', '10,584,874.25'),
            array('', '', '', '', '', ''),
            array('Project', 'BriefName', 'Note', 'QT No.', 'Period', 'Budget All'),
            array('Pleno Petchkasem 91', 'ทาวน์โฮม และ บ้านแฝด โซน เพชรเกษม-บางแค_Pleno Petchkasem 91', '40144/70122', '', '7 - 17 Sep 2026', 10000),
            array('Apitown Buriram', 'Apitown Buriram_ภาพบรรยากาศงาน VIP DAY', '20071', '2026/1683', '28 Sep - 1 Oct 2026', 10000),
            array('', '', '', '', '', ''),
        );
    }

    public function test_rows_are_read_from_wherever_the_headers_are(): void
    {
        $table = SheetTable::read($this->values(), $this->fields());

        $this->assertSame(4, $table['header_row']);
        $this->assertSame(array(), $table['missing']);
        $this->assertCount(2, $table['rows']);

        $first = $table['rows'][0];
        $this->assertSame(5, $first['_row'], 'the sheet row is what the numbers get written back to');
        $this->assertSame('Pleno Petchkasem 91', $first['project']);
        $this->assertSame('40144/70122', $first['note']);
        $this->assertSame('7 - 17 Sep 2026', $first['period_text']);

        $second = $table['rows'][1];
        $this->assertSame(6, $second['_row']);
        $this->assertSame('2026/1683', $second['quotation_no']);

        // The blank row after the data ends the table.
        $this->assertCount(2, $table['rows']);
    }

    public function test_a_missing_optional_column_is_tolerated(): void
    {
        $values = $this->values();
        array_pop($values[3]);              // no Budget All header
        foreach ($values as $index => $row) {
            if ($index >= 3) {
                $values[$index] = array_slice($row, 0, 5);
            }
        }

        $table = SheetTable::read($values, $this->fields(), array('BUDGET ALL'));

        $this->assertNotContains('BUDGET ALL', $table['missing']);
        $this->assertArrayNotHasKey('budget_all', $table['rows'][0]);
    }

    public function test_a_missing_required_column_is_reported(): void
    {
        $fields = $this->fields();
        $table = SheetTable::read($this->values(), $fields, array('PERIOD', 'BUDGET ALL'));
        $this->assertSame(array(), $table['missing']);

        unset($fields['PROJECT']);
        $fields['PROJECT X'] = 'project';
        $table = SheetTable::read($this->values(), $fields, array('PERIOD', 'BUDGET ALL'));
        $this->assertSame(array('PROJECT X'), $table['missing']);
    }

    public function test_the_first_row_is_used_when_no_headers_are_recognised(): void
    {
        $values = array(
            array('Project', 'BriefName', 'Note', 'QT No.', 'Period', 'Budget All'),
            array('Aspire Onnut Station', 'Aspire Onnut Station_ถึงเวลาช้อน', '40135', '2026/1539', '1 - 11 Sep 2026', 10000),
        );

        $table = SheetTable::read($values, $this->fields());

        $this->assertSame(1, $table['header_row']);
        $this->assertSame(2, $table['rows'][0]['_row']);
        $this->assertSame('2026/1539', $table['rows'][0]['quotation_no']);
    }

    public function test_the_write_back_range_points_at_one_column(): void
    {
        // A tab named 0926 has to be quoted, and the number of columns grows past Z.
        $this->assertSame("'0926'!D5:D6", SheetTable::columnRange('0926', 3, 5, 6));
        $this->assertSame("'1026'!AA12:AA12", SheetTable::columnRange('1026', 26, 12, 12));
    }

    public function test_the_assertion_payload_names_the_service_account(): void
    {
        $account = array('client_email' => 'apqt@project.iam.gserviceaccount.com');
        $claims = GoogleSheetsClient::claims($account, 1000);

        $this->assertSame('apqt@project.iam.gserviceaccount.com', $claims['iss']);
        $this->assertSame('https://oauth2.googleapis.com/token', $claims['aud']);
        $this->assertSame('https://www.googleapis.com/auth/spreadsheets', $claims['scope']);
        $this->assertSame(1000, $claims['iat']);
        $this->assertSame(4600, $claims['exp']);
    }

    public function test_the_jwt_segments_are_url_safe_and_unpadded(): void
    {
        $this->assertSame('aGk', GoogleSheetsClient::base64Url('hi'));
        $this->assertStringNotContainsString('=', GoogleSheetsClient::base64Url('a?b>c'));
        $this->assertStringNotContainsString('+', GoogleSheetsClient::base64Url("\xfb\xff"));
    }
}
