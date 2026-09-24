<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel library — PhpSpreadsheet bridge (migrated from legacy PHPExcel, Sep 2026)
 *
 * Usage in controllers stays the same:
 *   $this->load->library('excel');
 *   $objPHPExcel = new PHPExcel();                      // still works (class_alias below)
 *   $objPHPExcel = PHPExcel_IOFactory::load($path);     // still works
 *   $objWriter    = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 *
 * Legacy -> modern class mapping is done via class_alias so existing code
 * (QuotationAP.php, Quotation.php) runs unchanged on PhpSpreadsheet 5.x.
 */

require_once FCPATH . '../vendor/autoload.php';

class Excel {

    public function __construct()
    {
        // PHPExcel -> PhpSpreadsheet class aliases
        $map = array(
            'PHPExcel'                       => 'PhpOffice\PhpSpreadsheet\Spreadsheet',
            'PHPExcel_IOFactory'             => 'PhpOffice\PhpSpreadsheet\IOFactory',
            'PHPExcel_Cell'                  => 'PhpOffice\PhpSpreadsheet\Cell\Coordinate',
            'PHPExcel_Style_Alignment'       => 'PhpOffice\PhpSpreadsheet\Style\Alignment',
            'PHPExcel_Style_Border'          => 'PhpOffice\PhpSpreadsheet\Style\Border',
            'PHPExcel_Style_Fill'            => 'PhpOffice\PhpSpreadsheet\Style\Fill',
            'PHPExcel_Style_Font'            => 'PhpOffice\PhpSpreadsheet\Style\Font',
            'PHPExcel_Style_NumberFormat'    => 'PhpOffice\PhpSpreadsheet\Style\NumberFormat',
            'PHPExcel_Worksheet'             => 'PhpOffice\PhpSpreadsheet\Worksheet\Worksheet',
            'PHPExcel_Cell_DataType'         => 'PhpOffice\PhpSpreadsheet\Cell\DataType',
            'PHPExcel_Writer_Excel2007'      => 'PhpOffice\PhpSpreadsheet\Writer\Xlsx',
            'PHPExcel_Writer_Excel5'         => 'PhpOffice\PhpSpreadsheet\Writer\Xls',
            'PHPExcel_Reader_Excel2007'      => 'PhpOffice\PhpSpreadsheet\Reader\Xlsx',
            'PHPExcel_Reader_Excel5'         => 'PhpOffice\PhpSpreadsheet\Reader\Xls',
            'PHPExcel_Shared_Font'           => 'PhpOffice\PhpSpreadsheet\Shared\Font',
            'PHPExcel_Shared_Date'           => 'PhpOffice\PhpSpreadsheet\Shared\Date',
            'PHPExcel_Calculation'           => 'PhpOffice\PhpSpreadsheet\Calculation\Calculation',
            'PHPExcel_DataValidation'        => 'PhpOffice\PhpSpreadsheet\Cell\DataValidation',
            'PHPExcel_Chart'                 => 'PhpOffice\PhpSpreadsheet\Chart\Chart',
            'PHPExcel_Comment'               => 'PhpOffice\PhpSpreadsheet\Comment',
            'PHPExcel_RichText'              => 'PhpOffice\PhpSpreadsheet\RichText\RichText',
            'PHPExcel_Worksheet_Drawing'     => 'PhpOffice\\PhpSpreadsheet\\Worksheet\\Drawing',
            'PHPExcel_Worksheet_PageSetup'   => 'PhpOffice\\PhpSpreadsheet\\Worksheet\\PageSetup',
        );
        foreach ($map as $legacy => $modern) {
            if (!class_exists($legacy, FALSE) && class_exists($modern)) {
                class_alias($modern, $legacy);
            }
        }

        // Legacy writer type names used with IOFactory::createWriter()
        // 'Excel2007' -> 'Xlsx', 'Excel5' -> 'Xls' (handled in the helper below)
    }
}

/**
 * Backward-compatible IOFactory wrapper.
 * Code calling PHPExcel_IOFactory::createWriter($wb,'Excel2007') still works
 * because we alias PHPExcel_IOFactory to a subclass that translates types.
 */
class PHPExcel_IOFactory_Bridge
{
    public static function createWriter($spreadsheet, $writerType = 'Xlsx')
    {
        $writerType = str_replace(array('Excel2007', 'Excel5'), array('Xlsx', 'Xls'), $writerType);
        return \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $writerType);
    }

    public static function createReader($readerType = 'Xlsx')
    {
        $readerType = str_replace(array('Excel2007', 'Excel5'), array('Xlsx', 'Xls'), $readerType);
        return \PhpOffice\PhpSpreadsheet\IOFactory::createReader($readerType);
    }

    public static function load(...$args)
    {
        return \PhpOffice\PhpSpreadsheet\IOFactory::load(...$args);
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([\PhpOffice\PhpSpreadsheet\IOFactory::class, $name], $arguments);
    }
}

// Alias AFTER the bridge class exists
if (!class_exists('PHPExcel_IOFactory', FALSE)) {
    class_alias('PHPExcel_IOFactory_Bridge', 'PHPExcel_IOFactory');
}

/* End of file Excel.php */
/* Location: ./application/libraries/Excel.php */
