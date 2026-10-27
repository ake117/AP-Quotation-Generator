<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Writes the documents of one media-plan row back into the plan itself.
 *
 * The plan arrives with one row per entry. After the import, that entry may stand for
 * several quotations, and the sheet the team pastes back into the PO Status file has to
 * show them: one row per document, each with its own QT number, project and brief. Rows
 * are inserted for the extra documents, and every other cell of the source row (date,
 * objective, ad format, period, …) is carried down so the new rows read like the original.
 *
 * Only the fields that differ per document are overwritten; everything else in the row is
 * the source row's own value.
 */
final class MediaPlanWriter
{
    /** Row keys that belong to one document, not to the entry. */
    const PER_DOCUMENT = array(
        'quotation_no',
        'project',
        'project_file',
        'note',
        'budget_all',
        'media_cost',
    );

    /**
     * @param mixed $sheet       PhpSpreadsheet worksheet
     * @param int   $row         1-based row of the source entry
     * @param array $documents   rows from QuotationBatch::splitRows()
     * @param array $positions   header => column index (0-based), as excel_search_col found it
     * @param array $fields      header => row key
     * @return int how many rows the entry now occupies
     */
    public static function writeRow($sheet, $row, array $documents, array $positions, array $fields)
    {
        $documents = array_values($documents);
        $count = max(1, count($documents));

        if ($count > 1) {
            $sheet->insertNewRowBefore($row + 1, $count - 1);
        }

        // What the entry currently says, per mapped column, before anything is overwritten.
        $source = array();
        foreach ($positions as $header => $column) {
            $cell = Coordinate::stringFromColumnIndex($column + 1) . $row;
            $source[$header] = array(
                'value' => $sheet->getCell($cell)->getValue(),
                'style' => $sheet->getStyle($cell),
            );
        }

        foreach ($documents as $index => $document) {
            $targetRow = $row + $index;

            foreach ($positions as $header => $column) {
                $field = isset($fields[$header]) ? $fields[$header] : null;
                $value = ($field !== null && in_array($field, self::PER_DOCUMENT, true))
                    ? (isset($document[$field]) ? $document[$field] : null)
                    : $source[$header]['value'];

                $address = Coordinate::stringFromColumnIndex($column + 1) . $targetRow;
                $sheet->setCellValue($address, $value);

                if ($index > 0) {
                    $sheet->duplicateStyle($source[$header]['style'], $address);
                }
            }
        }

        return $count;
    }
}
