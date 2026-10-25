# Example data sources

Sample inputs for the quotation pipeline. The pipeline runs from a media plan to a
finished quotation and **ends with the Excel → PDF conversion**; these four files are
what that path consumes.

| File | Role | Sheets |
|---|---|---|
| `AP_Media_Plan_092026_v1.xlsx` | The brief being quoted: one row per media item (`No.`, `Assign Date`, `Project`, `BriefName`, `Note`, `QT No.`, `Objective`, `Ad Format`, `BG`, `Page`) | `0926` |
| `1_AP Company Reference_20260929.xlsx` | Project → billing entity: `Project`, `หัวบริษัท (TH)`, `หัวบริษัท (EN)`, `ที่อยู่ (TH)`, `ที่อยู่ (EN)`, `เลขประจำตัวผู้เสียภาษี`, `Project ID`, `Cost Center`, `AddressLong` | `หัวบริษัทฯ`, `project id`, `addr`, `tax id` |
| `2_AP Project Name Synonym 20260929.xlsx` | Normalises the many spellings of a project name (`Index`, `Synonym`) so a media-plan row resolves to one project | `Sheet1`, `Pivot Table 1` |
| `หัวบริษัท.xlsx` | Letterhead master: legal name and address in Thai and English per entity, plus tax id (carries the logo images, hence 4.2 MB) | `Address`, `Name`, `Sheet1`, `Note`, … |

## How they fit together

```
media plan row
   └─ project name ──(synonym table)──> canonical project
        └─(company reference)──> billing entity + address + tax id
             └─(letterhead master)──> the header block on the quotation
                  └─ Excel export ──> PDF
```

## Notes

- These are **real business documents** with real entity names, addresses and tax ids.
  The repository is private; do not copy them into a public place or into a test
  fixture that gets published.
- They are inputs for tests and for manual comparison, not fixtures that the app reads
  at runtime.
- Sizes: media plan 121 KB · company reference 72 KB · synonyms 231 KB · letterhead
  4.2 MB (images).
