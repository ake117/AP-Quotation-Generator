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
        // Without a period the line reads just "Boost post": the ad format alone is
        // already implied by the wording, and "Boost post Boostpost" reads like a typo.
        $detail_sub = ($period_text !== '')
            ? 'Boost post ' . $period_text
            : 'Boost post';

        // The 11.5% agency fee is part of the media plan's own arithmetic
        // (BUDGET ALL -> BUDGET EX VAT -> PURE MEDIA), so it is on unless a row says
        // otherwise. A row that carries PURE MEDIA already has the fee removed.
        $agencyFee = array_key_exists('agency_fee', $row) ? (bool) $row['agency_fee'] : true;

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

        // One media item per two rows: a name row (D = number, E = project) and a
        // detail row (E = description, J = qty, K = unit price, L = amount).
        $items = (isset($row['items']) && is_array($row['items']) && $row['items']) ? $row['items'] : null;
        if ($items === null) {
            $sheet->setCellValue('E23', $row['project']);
            $sheet->setCellValue('E24', $detail_sub);
        } else {
            $nameRow = 23;
            foreach (array_values($items) as $index => $item) {
                $detailRow = $nameRow + 1;
                $sheet->setCellValue('D' . $nameRow, $index + 1);
                $sheet->setCellValue('E' . $nameRow, $item['project']);
                $sheet->setCellValue('E' . $detailRow, $item['detail']);
                $sheet->setCellValue('J' . $detailRow, $item['qty']);
                $sheet->setCellValue('K' . $detailRow, $item['unit_price']);
                $sheet->setCellValue('L' . $detailRow, '=K' . $detailRow . '*J' . $detailRow);
                $nameRow += 2;
            }
        }

        // K24 carries PURE MEDIA — the net-of-everything media cost. The media plan
        // computes it so the chain lands on the round budget the client approved:
        //   Budget All -> /1.07 = Budget Ex VAT -> /1.115 = Pure media (N column)
        // then  Pure media + 11.5% fee + 7% VAT  =  Budget All  (a round number)
        if ($items === null) {
            // A single-item row carries the whole media cost in K24; with several
            // items the subtotal is the sum of the line amounts, and writing K24
            // would overwrite the first item's unit price.
            $pureMedia = self::pureMedia($row, $agencyFee);
            $sheet->setCellValue('K24', $pureMedia);
        }

        $feeFont = array(
            'font' => array(
                'name'   => 'CordiaUPC',
                'size'   => 14,
                'color'  => array('rgb' => '000000'),
                'italic' => false,
            ),
        );
        if ($agencyFee) {
            $sheet->setCellValue('J47', ' Agency Fee 11.5%');
            $sheet->getStyle('J47')->applyFromArray($feeFont);
            // The fee is rounded to satang before it is added: otherwise the net
            // carries 9,345.78505 and the printed lines (9,345.79 + 654.21) do not
            // add up to the printed total.
            $sheet->setCellValue('L47', '=ROUND(L46*11.5/100,2)');
            $sheet->getStyle('L47')->applyFromArray($feeFont);
            $sheet->setCellValue('L48', '=L46+L47');
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

    /**
     * The amount that belongs in K24.
     *
     * PURE MEDIA comes from the media plan (column N). When a row does not carry it,
     * derive it from the round budget so the document still totals a clean number:
     *
     *   pure = budget_all / (1 + fee/100) / 1.07
     *
     * 10,000 with the standard 11.5% fee gives 8,381.87 -> +963.91 fee -> 9,345.79
     * net -> +654.21 VAT -> 10,000.00.
     */
    public static function pureMedia(array $row, bool $agencyFee = true): float
    {
        $media = (float) ($row['media_cost'] ?? 0);
        if ($media > 0) {
            return round($media, 2);
        }

        $target = (float) ($row['budget_all'] ?? 0);
        if ($target <= 0) {
            $target = (float) ($row['budget'] ?? 0);
        }
        if ($target <= 0) {
            return 0.0;
        }

        $factor = $agencyFee ? 1.115 : 1.0;
        $base = $target / (1.07 * $factor);

        // Rounding is not neutral here: 10,000 / 1.19305 = 8,381.8753..., and
        // rounding up gives a document that totals 10,000.01. Pick the two-decimal
        // value whose whole chain (net -> VAT -> total) actually lands on the target.
        foreach (array(0, -0.01, 0.01, -0.02, 0.02, -0.03, 0.03) as $delta) {
            $candidate = round($base + $delta, 2);
            // Exactly what the sheet computes: fee rounded to satang, net = pure +
            // fee, VAT rounded to satang, total = net + VAT.
            $fee = $agencyFee ? round($candidate * 0.115, 2) : 0.0;
            $net = round($candidate + $fee, 2);
            $vat = round($net * 0.07, 2);
            if (abs($net + $vat - $target) < 0.005) {
                return $candidate;
            }
        }

        return round($base, 2);
    }

    /** What the sheet prints as the total for a given pure media figure. */
    public static function totalFor(float $pureMedia, bool $agencyFee = true): float
    {
        $fee = $agencyFee ? round($pureMedia * 0.115, 2) : 0.0;
        $net = round($pureMedia + $fee, 2);

        return round($net + round($net * 0.07, 2), 2);
    }

    /**
     * The nearest total the chain can actually print.
     *
     * Not every gross is reachable: the fee and VAT are each rounded to satang, so
     * 3,333.33 cannot be produced (the closest are 3,333.32 and 3,333.34). The search
     * takes the target itself, then walks outwards preferring the amount above it, so a
     * share never lands lower than the figure that was promised. Splitting a budget into
     * equal documents has to know this, otherwise the documents add up to a total the
     * customer never sees.
     */
    public static function reachableTotal(float $target, bool $agencyFee = true): float
    {
        for ($step = 0; $step <= 200; $step++) {
            foreach ($step === 0 ? array(0) : array($step, -$step) as $sign) {
                $gross = round($target + $sign / 100, 2);
                if ($gross <= 0) {
                    continue;
                }
                $pure = self::pureMedia(array('budget_all' => $gross, 'media_cost' => 0), $agencyFee);
                if (abs(self::totalFor($pure, $agencyFee) - $gross) < 0.005) {
                    return $gross;
                }
            }
        }

        return round($target, 2);
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
