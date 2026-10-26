# Test tools

The quotation pipeline ends at a PDF, so its gate runs the real render: template →
`.xlsx` → LibreOffice → `.pdf`, then asserts on the PDF text.

## Requirements

| Tool | Why | This machine |
|---|---|---|
| PHP ≥ 8.1 | PhpSpreadsheet 5.x | 8.5.4 |
| PHPUnit 12 (phar) | the runner — committed on purpose (see below) | `tools/phpunit.phar` |
| `soffice` (LibreOffice) | `.xlsx` → `.pdf` | 26.2.6.3 |
| `pdftotext` (poppler-utils) | read the PDF back | installed |

## Run the gate

```bash
code/tests/run                 # from the repository root
code/tests/run --testdox       # any PHPUnit flag
```

The 6 MB phar is committed even though binaries usually do not belong in git: the gate
runs inside a DaCode sandbox where `$HOME` is hidden and the network is off, and a
worktree only contains tracked files. Committing it keeps the gate runnable everywhere
with no setup. `tools/install-phpunit.sh` re-downloads it if it is ever missing.

Exit code 0 means every quotation assertion holds. That is the command a DaCode trial
is gated on.

## What the tests assert

`tests/QuotationExporterTest.php` renders QT **2026/1539** (Aspire Onnut Station) and
compares it against the document a human approved, which lives in
`../exported_from original system_20260925/excel/`:

- customer, billing entity, address, quotation no., job no., project, media cost
- net = subtotal, VAT 7 %, grand total 10,000.00
- the PDF carries those strings, and the total in words (หนึ่งหมื่นบาทถ้วน)
- the item line reads `Boost post <period>`, or just `Boost post` without a period
- the file name is filesystem-safe

`tests/MultiItemQuotationTest.php` states the layout for several items before the
implementation existed — two rows per item from 23 down, K24 untouched, rows below the
last item empty, and the PDF listing every line.

When a test disagrees with the reference, **the reference wins** — a difference is a
finding, never a reason to edit the reference or loosen the assertion.

## Several media items in one quotation

Pass the items in the row:

```php
'items' => [
    ['project' => 'Apitown Chonburi - Bangsaen', 'detail' => 'Boost post 1 - 11 Sep 2026', 'qty' => 2, 'unit_price' => 2500.00],
    ['project' => 'Pleno Rama 9 - Krungthepkreetha 4', 'detail' => 'Boost post 1 - 11 Sep 2026', 'qty' => 1, 'unit_price' => 3381.87],
],
```

Each item takes two rows from 23 down — a name row (`D` = number, `E` = project) and a
detail row (`E` = description, `J` = qty, `K` = unit price, `L` = `=K<row>*J<row>`).
Two rules the tests pin:

- **K24 must stay untouched when items are present.** The subtotal is the sum of the
  line amounts; writing K24 would overwrite the first item's unit price.
- **Rows below the last item stay empty.**

Without `items` the exporter renders the single-item layout exactly as before.

## K24 carries PURE MEDIA, not the budget

The media plan already does the arithmetic — the columns are formulas:

```
Budget All  --/1.07-->  Budget Ex VAT  --/1.115-->  Pure media   (column N)
```

so a document can put the media cost back and land on the number the client approved:

```
Pure media 8,381.87  +  Agency Fee 11.5%   963.92
= Net 9,345.79       +  VAT 7%             654.21
= Grand Total                            10,000.00
```

- K24 takes PURE MEDIA from the row (`media_cost`, mapped from the plan's N column).
- When the row has none, the exporter derives it from BUDGET ALL and checks that the
  whole chain closes: 10,000 / 1.19305 = 8,381.8753, and rounding that up gives a
  document totalling 10,000.01. The derivation picks the satang that closes instead.
- The fee is rounded before it is added (`=ROUND(L46*11.5/100,2)`, then `=L46+L47`),
  otherwise the printed lines do not add up to the printed total.
- `agency_fee => false` in a row turns the block off: the older reference quotations
  (September 2025 style) have no fee line and carry `net = subtotal`.

`test_the_chain_lands_on_the_round_budget` pins 5,000 / 10,000 / 12,345 / 25,000 /
100,000 — every one has to come back out as the grand total.
