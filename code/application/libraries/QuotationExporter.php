<?php
/**
 * QuotationExporter — renders one quotation row into .xlsx and .pdf.
 *
 * Extracted verbatim from QuotationAP::export_quotationAP() so the render can be
 * exercised without CodeIgniter, a database or an HTTP request. The controller keeps
 * its own methods as thin wrappers, so behaviour (cells, formulas, fonts, images,
 * LibreOffice conversion) is unchanged.
 *
 * Deliberately free of CI dependencies: only PhpSpreadsheet and the filesystem.
 */

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class QuotationExporter
{
    /** @var array<int,array{file:string,width:float,height:float,anchor:string}> */
    private const IMAGES = array(
        array('file' => 'ewit_resizeto_075x110in-and_140x100in.png', 'width' => 71.25, 'height' => 104.5, 'anchor' => 'A2'),
        array('file' => 'signature_or_resizeto_126x070in.png',       'width' => 113.4, 'height' => 63.0,  'anchor' => 'G55'),
        array('file' => 'ewit_logo-stamp_2025v2_075x116in.png',      'width' => 75.0,  'height' => 116.4, 'anchor' => 'I53'),
        array('file' => 'signature_ake_resizeto_137x079in.png',      'width' => 123.3, 'height' => 71.1,  'anchor' => 'K55'),
    );

    private string $templatePath;
    private string $imageDir;

    /**
     * @param string $templatePath absolute path to template.xlsx
     * @param string $imageDir     absolute path to the folder holding the four
     *                             standard images (may be missing — images are optional)
     */
    public function __construct(string $templatePath, string $imageDir)
    {
        $this->templatePath = $templatePath;
        $this->imageDir = rtrim($imageDir, '/') . '/';
    }

    /**
     * Fill the template with one row and write .xlsx next to (then) a .pdf.
     *
     * @param array  $row    quotation fields (see README of the module / controller)
     * @param string $outDir directory for the output files, created when missing
     * @return array{xlsx:string,pdf:?string}
     */
    public function export(array $row, string $outDir): array
    {
        if (!is_dir($outDir)) {
            mkdir($outDir, 0777, true);
        }

        $spreadsheet = $this->fill($row);

        $name = self::fileName($row);
        $xlsx = rtrim($outDir, '/') . '/' . $name . '.xlsx';

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setPreCalculateFormulas(true);
        $writer->save($xlsx);

        $pdf = $this->convertToPdf($xlsx);

        return array('xlsx' => $xlsx, 'pdf' => $pdf);
    }

    /**
     * Apply one row to a fresh copy of the template. Public so a test can assert the
     * cells without writing a file.
     */
    public function fill(array $row): Spreadsheet
    {
        $quotation_date = date_create($row['quotation_date']);
        $detail = 'Media Cost (' . date_format($quotation_date, 'F Y') . ')';
        $period_text = isset($row['period_text']) ? trim($row['period_text']) : '';
        $detail_sub = ($period_text !== '')
            ? 'Boost post ' . $period_text
            : 'Boost post ' . trim($row['ad_format']);

        $budget = (float) $row['budget'];
        $media_cost = (float) $row['media_cost'];

        $spreadsheet = IOFactory::load($this->templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('K10', ExcelDate::PHPToExcel($quotation_date));
        $sheet->getStyle('K10')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);

        $sheet->setCellValue('K12', $row['quotation_no']);
        $sheet->setCellValue('K14', $row['job_no']);
        $sheet->setCellValue('E10', $row['contact_name']);
        $sheet->setCellValue('E11', $row['company_title']);
        $sheet->setCellValue('E12', $row['company_address']);

        $sheet->setCellValue('E22', $detail);
        $sheet->setCellValue('E23', $row['project']);
        $sheet->setCellValue('E24', $detail_sub);

        // Pure media from the media plan (K24) + Agency Fee 11.5% block + BAHTTEXT
        $sheet->setCellValue('K24', ($media_cost > 0) ? $media_cost : $budget);

        $feeFont = array(
            'font' => array(
                'name'   => 'CordiaUPC',
                'size'   => 14,
                'color'  => array('rgb' => '000000'),
                'italic' => false,
            ),
        );
        // The 11.5% agency fee is a product decision, not a constant: every reference
        // quotation in exported_from original system_20260925 has no fee line and
        // L48 = L46. The flag makes both cases expressible; the default keeps the
        // behaviour the controller had before the extraction.
        $agencyFee = array_key_exists('agency_fee', $row) ? (bool) $row['agency_fee'] : true;
        if ($agencyFee) {
            $sheet->setCellValue('J47', ' Agency Fee 11.5%');
            $sheet->getStyle('J47')->applyFromArray($feeFont);
            $sheet->setCellValue('L47', '=L46*11.5/100');
            $sheet->getStyle('L47')->applyFromArray($feeFont);
            $sheet->setCellValue('L48', '=L46*(1+11.5/100)');
        } else {
            $sheet->setCellValue('J47', null);
            $sheet->setCellValue('L47', null);
            $sheet->setCellValue('L48', '=L46');
        }
        $sheet->setCellValue('D50', '=_xlfn.BAHTTEXT(L50)');

        $this->insertImages($spreadsheet);

        return $spreadsheet;
    }

    /** Convert an .xlsx to PDF next to it, using LibreOffice headless. */
    public function convertToPdf(string $xlsxPath): ?string
    {
        if (!file_exists($xlsxPath)) {
            return null;
        }

        $dir = dirname($xlsxPath);
        $cmd = 'soffice --headless --norestore --convert-to pdf --outdir '
             . escapeshellarg($dir) . ' ' . escapeshellarg($xlsxPath)
             . ' 2>&1';

        exec($cmd, $output, $exitCode);

        $pdf = substr($xlsxPath, 0, -5) . '.pdf';

        return ($exitCode === 0 && file_exists($pdf)) ? $pdf : null;
    }

    /** Replace the template's drawings with the four standard images. */
    public function insertImages(Spreadsheet $spreadsheet): void
    {
        if (!is_dir($this->imageDir)) {
            return;
        }

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getDrawingCollection()->exchangeArray(array());

        foreach (self::IMAGES as $img) {
            $file = $this->imageDir . $img['file'];
            if (!file_exists($file)) {
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName(pathinfo($file, PATHINFO_FILENAME));
            $drawing->setPath($file);
            $drawing->setHeight($img['height']);
            $drawing->setWidth($img['width']);
            $drawing->setCoordinates($img['anchor']);
            $drawing->setWorksheet($sheet);
        }
    }

    /** `<quotation_no>_<project>`, filesystem-safe. */
    public static function fileName(array $row): string
    {
        return self::sanitizeText($row['quotation_no'] . '_' . $row['project_file'], '-');
    }

    public static function sanitizeText(string $text, string $sep = '-', int $len = 74): string
    {
        return rtrim(
            trim(mb_substr(
                preg_replace('/[^_a-zA-Z0-9ก-ฮั็่้๊๋าำิีึืุูะเแโใไๅๆ๐-๘ฯ์]+/iu', $sep, strip_tags(trim($text))),
                0,
                $len,
                'UTF-8'
            )),
            $sep
        );
    }
}
