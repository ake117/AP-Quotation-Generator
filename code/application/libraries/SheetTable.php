<?php

require_once __DIR__ . '/QuotationBatch.php';

/**
 * The values a Google Sheet returns, turned into the rows the importer expects.
 *
 * Same header names, same optional columns, same "stop at the first empty row" rule as the
 * uploaded workbook — the only difference is where the values came from. Each row keeps the
 * sheet row it belongs to (`_row`), because the numbers have to be written back to exactly
 * that row.
 */
final class SheetTable
{
    /**
     * @param array<int, array<int, mixed>> $values  rows as the API returns them (row 1 first)
     * @param array<string, string> $fields          header => row key (the importer's $coldata['field'])
     * @param array<int, string> $optional           headers that may be missing
     * @return array{rows: array<int, array<string, mixed>>, header_row: int, positions: array<string, int>, missing: array<int, string>}
     */
    public static function read(array $values, array $fields, array $optional = array()): array
    {
        $headerRow = QuotationBatch::headerRow(self::withRowNumbers($values), array_keys($fields));

        $positions = array();
        foreach (array_keys($fields) as $header) {
            $positions[$header] = 999;
        }

        foreach ($values[$headerRow - 1] ?? array() as $index => $cell) {
            $name = strtoupper(trim((string) $cell));
            if (array_key_exists($name, $fields)) {
                $positions[$name] = $index;
            }
        }

        $missing = array();
        foreach ($positions as $header => $position) {
            if ($position === 999 && !in_array($header, $optional, true)) {
                $missing[] = $header;
            }
        }

        $rows = array();
        $firstField = reset($fields);

        for ($index = $headerRow; $index < count($values); $index++) {
            $row = array('_row' => $index + 1);

            foreach ($positions as $header => $position) {
                if ($position === 999) {
                    continue;
                }
                $value = isset($values[$index][$position]) ? $values[$index][$position] : '';
                $row[$fields[$header]] = is_scalar($value) ? trim((string) $value) : '';
            }

            // A blank first column ends the table, exactly like the uploaded file.
            if ((string) ($row[$firstField] ?? '') === '') {
                break;
            }

            $rows[] = $row;
        }

        return array(
            'rows'       => $rows,
            'header_row' => $headerRow,
            'positions'  => $positions,
            'missing'    => $missing,
        );
    }

    /**
     * A1 range for one column of a tab: '0926'!D5:D6. Tabs named like 0926 have to be
     * quoted, and the column is given as the 0-based index the API returned.
     */
    public static function columnRange(string $tab, int $columnIndex, int $fromRow, int $toRow): string
    {
        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);

        return "'" . $tab . "'!" . $column . $fromRow . ":" . $column . $toRow;
    }

    /**
     * QuotationBatch::headerRow() wants 1-based row numbers as keys; the API hands back a
     * plain list. This bridges the two.
     *
     * @param array<int, array<int, mixed>> $values
     * @return array<int, array<int, mixed>>
     */
    private static function withRowNumbers(array $values): array
    {
        $numbered = array();
        foreach ($values as $index => $row) {
            $numbered[$index + 1] = $row;
        }

        return $numbered;
    }
}
