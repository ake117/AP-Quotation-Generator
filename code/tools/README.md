# Test tools

The quotation pipeline ends at a PDF, so its gate runs the real render: template →
`.xlsx` → LibreOffice → `.pdf`, then asserts on the PDF text.

## Requirements

| Tool | Why | This machine |
|---|---|---|
| PHP ≥ 8.1 | PhpSpreadsheet 5.x | 8.5.4 |
| PHPUnit 12 (phar) | the runner — not committed (6 MB binary) | `tools/phpunit.phar` |
| `soffice` (LibreOffice) | `.xlsx` → `.pdf` | 26.2.6.3 |
| `pdftotext` (poppler-utils) | read the PDF back | installed |

## Run the gate

```bash
cd code
./tools/install-phpunit.sh          # once, downloads the phar
php tools/phpunit.phar -c phpunit.xml --no-coverage
```

Exit code 0 means every quotation assertion holds. That is the command a DaCode trial
is gated on.

## What the tests assert

`tests/QuotationExporterTest.php` renders QT **2026/1539** (Aspire Onnut Station) and
compares it against the document a human approved, which lives in
`../exported_from original system_20260925/excel/`:

- customer, billing entity, address, quotation no., job no., project, media cost
- net = subtotal, VAT 7 %, grand total 10,000.00
- the PDF carries those strings, and the total in words (หนึ่งหมื่นบาทถ้วน)
- the file name is filesystem-safe

When a test disagrees with the reference, **the reference wins** — a difference is a
finding, never a reason to edit the reference or loosen the assertion.

## Note on the agency fee

Every reference quotation has no agency-fee line and carries `net = subtotal`. The
exporter supports the 11.5 % block behind a `agency_fee` flag in the row data; the
default keeps the behaviour the controller had (`true`). Changing the default is a
product decision, not a test decision.
