# Reference outputs from the original system (2026-09-25)

Thirteen quotations exported by the **old system** (the one this CodeIgniter rewrite
replaced). They are kept as **golden references**: the rewritten pipeline has to keep
producing the same document, so these are what a generated quotation is compared
against.

```
excel/2026-1530_Grande-Pleno-Bangna-Kingkaew_....xlsx
excel/2026-1531_Pleno-รามอินทรา_....xlsx
...
excel/2026-1541_Aspire-Itsaraphap-Station_....xlsx
```

- 13 files, ~790 KB, one sheet each, named `<QT No.>_<project>_<brief>.xlsx`.
- Issued by **EWIT CO., LTD.** (ใบเสนอราคา / QUOTATION), so they carry real customer
  names and addresses.

## Why they live in the repository

The acceptance test for this project is *"the quotation PDF is correct"*. A text
comparison against a human-approved document is worth more than an assertion written
from the code, which only proves the code agrees with itself.

They are ignored by the generic `exported_*/` rule and re-included explicitly in
`.gitignore`.

## Handling

- **Private repository only.** These are real business documents with customer PII.
  Never copy them into a public repo, a public issue, or an outside audit pack.
- Read-only: do not edit them to make a test pass. If the new output differs from a
  reference, the difference is the finding.
- If you need a fixture without PII, derive a redacted copy and keep it clearly named
  as such.
