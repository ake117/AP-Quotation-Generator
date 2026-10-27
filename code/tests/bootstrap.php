<?php
/**
 * Test bootstrap — PHP only. No CodeIgniter, no database, no HTTP.
 *
 * The exporter is deliberately CI-free, so a unit test needs nothing more than the
 * Composer autoloader (PhpSpreadsheet) and the paths to the template and images.
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../application/libraries/QuotationExporter.php';
require_once __DIR__ . '/../application/libraries/QuotationBatch.php';
require_once __DIR__ . '/../application/libraries/MediaPlanWriter.php';
require_once __DIR__ . '/../application/libraries/SheetTable.php';
require_once __DIR__ . '/../application/libraries/GoogleSheetsClient.php';

define('CODEPATH', dirname(__DIR__));
define('PUBLICPATH', CODEPATH . '/public_html');
define('TEMPLATE_PATH', PUBLICPATH . '/upload/quotation/ap/template/template.xlsx');
define('IMAGE_DIR', PUBLICPATH . '/upload/quotation/ap/img/');
define('REPO_ROOT', dirname(CODEPATH));
define('REFERENCE_DIR', REPO_ROOT . '/exported_from original system_20260925/excel');
