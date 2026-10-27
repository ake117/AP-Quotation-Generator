<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PHPUnit\Framework\TestCase;

/**
 * The plan the team gets back: one row per document, carrying its own QT number.
 *
 * The workbook is pasted back into the PO Status file, so the numbers have to be in the
 * sheet itself — not only in the quotation files.
 */
final class MediaPlanWriterTest extends TestCase
{
    private function sheetWithOneEntry(): array
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers on row 4, as the PO Status workbook keeps them.
        $sheet->fromArray(array('Project', 'BriefName', 'Note', 'QT No.', 'Period'), null, 'A4');
        $sheet->fromArray(array(
            'Pleno Petchkasem 91',
            'ทาวน์โฮม และ บ้านแฝด โซน เพชรเกษม-บางแค_Pleno Petchkasem 91',
            '40144/70122',
            null,
            '7 - 17 Sep 2026',
        ), null, 'A5');

        $positions = array('PROJECT' => 0, 'BRIEFNAME' => 1, 'NOTE' => 2, 'QT NO.' => 3, 'PERIOD' => 4);
        $fields = array(
            'PROJECT'   => 'project',
            'BRIEFNAME' => 'project_file',
            'NOTE'      => 'note',
            'QT NO.'    => 'quotation_no',
            'PERIOD'    => 'period_text',
        );

        return array($sheet, $positions, $fields);
    }

    public function test_a_split_entry_becomes_one_row_per_document(): void
    {
        list($sheet, $positions, $fields) = $this->sheetWithOneEntry();

        $documents = array(
            array(
                'project'      => 'Pleno Petchkasem 91',
                'project_file' => 'ทาวน์โฮม และ บ้านแฝด โซน เพชรเกษม-บางแค_Pleno Petchkasem 91',
                'note'         => '40144',
                'quotation_no' => '2026/1591',
                'period_text'  => '7 - 17 Sep 2026',
            ),
            array(
                'project'      => 'Pleno Town Petchkasem 81',
                'project_file' => 'ทาวน์โฮม และ บ้านแฝด โซน เพชรเกษม-บางแค_Pleno Town Petchkasem 81',
                'note'         => '70122',
                'quotation_no' => '2026/1592',
                'period_text'  => '7 - 17 Sep 2026',
            ),
        );

        $written = MediaPlanWriter::writeRow($sheet, 5, $documents, $positions, $fields);

        $this->assertSame(2, $written);
        $this->assertSame('2026/1591', $sheet->getCell('D5')->getValue());
        $this->assertSame('Pleno Petchkasem 91', $sheet->getCell('A5')->getValue());
        // A numeric ID is stored as a number by the sheet, which is how the plan carries it.
        $this->assertSame('40144', (string) $sheet->getCell('C5')->getValue());

        $this->assertSame('2026/1592', $sheet->getCell('D6')->getValue());
        $this->assertSame('Pleno Town Petchkasem 81', $sheet->getCell('A6')->getValue());
        $this->assertSame('70122', (string) $sheet->getCell('C6')->getValue());
        // Everything that belongs to the entry rather than the document is carried down.
        $this->assertSame('7 - 17 Sep 2026', $sheet->getCell('E6')->getValue());

        // The header row is untouched.
        $this->assertSame('QT No.', $sheet->getCell('D4')->getValue());
    }

    public function test_a_single_document_only_fills_its_own_row(): void
    {
        list($sheet, $positions, $fields) = $this->sheetWithOneEntry();

        $written = MediaPlanWriter::writeRow($sheet, 5, array(array(
            'project'      => 'Pleno Petchkasem 91',
            'project_file' => 'ทาวน์โฮม และ บ้านแฝด โซน เพชรเกษม-บางแค_Pleno Petchkasem 91',
            'note'         => '40144',
            'quotation_no' => '2026/1591',
            'period_text'  => '7 - 17 Sep 2026',
        )), $positions, $fields);

        $this->assertSame(1, $written);
        $this->assertSame('2026/1591', $sheet->getCell('D5')->getValue());
        $this->assertNull($sheet->getCell('D6')->getValue());
    }

    public function test_an_entry_below_grows_without_losing_the_one_after_it(): void
    {
        list($sheet, $positions, $fields) = $this->sheetWithOneEntry();

        // A second entry, the one that must not be shifted away by the insertion.
        $sheet->fromArray(array('Pleno Town Bangna', 'Pleno Town Bangna_โชคทันใจ', '40172', null, '1 - 11 Sep 2026'), null, 'A6');

        MediaPlanWriter::writeRow($sheet, 5, array(
            array('project' => 'A', 'project_file' => 'A_brief', 'note' => '40144', 'quotation_no' => '2026/1591'),
            array('project' => 'B', 'project_file' => 'B_brief', 'note' => '70122', 'quotation_no' => '2026/1592'),
        ), $positions, $fields);

        $this->assertSame('2026/1591', $sheet->getCell('D5')->getValue());
        $this->assertSame('2026/1592', $sheet->getCell('D6')->getValue());
        // The entry that sat on row 6 has moved to row 7, unharmed.
        $this->assertSame('Pleno Town Bangna', $sheet->getCell('A7')->getValue());
        $this->assertSame('40172', (string) $sheet->getCell('C7')->getValue());
    }
}
