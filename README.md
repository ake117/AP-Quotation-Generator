# AP Quotation Generator (AP_Quotation_by_Chat)

ระบบสร้าง/จัดการใบเสนอราคา (Quotation) สำหรับงานโฆษณา Boost Post ของ AP (Thailand) — พัฒนาต่อยอดบนแอดมินแพเนล CodeIgniter (wpanel) โดยโฟกัสเฉพาะโมดูล **Quotation AP**

---

## ข้อมูลโปรเจกต์

| รายการ | รายละเอียด |
|---|---|
| Repo | `ake117/AP-Quotation-Generator` (private, branch `main`; เดิมชื่อ `AP_Quotation_by_Chat`) |
| Local path | `/home/ake/AI_Projects/AP_QT_Generator` |
| Framework | CodeIgniter **3.1.13** + Smarty template + AdminLTE 2.4 (skin ต่อ account) + PhpSpreadsheet 5.10 (ผ่าน composer) |
| Database | MySQL/MariaDB — schema `production` |
| ต้นทาง DB dump | `code/database/production.sql` (Navicat dump จาก production.ewitewit.com, MySQL 5.7, 23/09/2026) |
| Dev server | `php -S 0.0.0.0:8091 -t code/public_html` |
| Adminer | `php -S 127.0.0.1:8090 index.php` ที่ `~/.local/share/adminer/` |

## การเข้าใช้งาน (local)

```
URL      : http://127.0.0.1:8091/admin/login
Email    : ake@ewitewit.com   (ROOT)
Password : APqt2026!local
```

- หลัง login จะเข้าหน้า **Quotation AP** ทันที (แก้ที่ `Logon.php` — `redirect('admin/quotationAP')`)
- Theme: AdminLTE skin เก็บต่อ account ใน `cms_accounts.extra_data.skin` (ปัจจุบัน `blue-light`)
- รหัสผ่านเก็บแบบ **MD5** (`md5(password + salt)`, salt ว่าง) — แก้ได้ผ่าน `UPDATE cms_accounts SET password=MD5('...')`

## โครงสร้างโฟลเดอร์

```
AP_QT_Generator/
├── backups/                    # DB backups (full + clean)
├── example_data_source/        # ตัวอย่าง input จริง (media plan, company reference, synonym, หัวบริษัท)
├── exported_from original system_20260925/   # ใบเสนอราคา 13 ใบจากระบบเดิม = golden reference
├── code/
│   ├── tests/                  # PHPUnit spec + gate runner (run)
│   ├── tools/                  # phpunit.phar + install script
│   ├── application/            # CI app
│   │   ├── config/database.php # DB credentials (user: productionmy)
│   │   ├── config/auth.php     # ระบบ auth: attempts, autoban, white-list
│   │   ├── models/Auth_model.php
│   │   ├── libraries/Auth.php  # login/logout/hash (md5)
│   │   └── modules/admin/
│   │       ├── controllers/    # Dashboard, Quotation, QuotationAP, Members, Teams, Projects, Logon ...
│   │       ├── models/         # QuotationAP_model, Quotation_model, Estimated_model, ...
│   │       └── views/          # Smarty .tpl (_layout/header.tpl = เมนู sidebar)
│   ├── public_html/            # docroot (index.php, lib/, assets/)
│   ├── system/                 # CI core (ห้ามแก้)
│   └── database/production.sql # original dump (2.2MB, schema+data)
```

## โมดูลที่ใช้งานจริง

เมนูที่เหลือ: **Dashboard · Quotation AP · Members · Teams · Projects · Users · Configurations**

- **Quotation AP** (`QuotationAP.php` + `QuotationAP_model.php`) — โมดูลหลัก: parse/นำเข้าข้อมูลใบเสนอราคา AP (Boost Post), จัดการเลข running ต่อเดือน/ปี, export
  - ใช้ AJAX กลับไปที่ endpoints ของ controller `Quotation.php` เก่า: `load`, `load_estimated`, `load_ajax_estimated` (ดึงข้อมูล production มาเติมฟอร์ม) → **ห้ามลบ controller นี้**
- **Members / Teams / Projects** — ข้อมูลประกอบ
- **User accounts / Configurations** — ระบบ auth + config ของ framework

## โครงสร้างตาราง (22 ตาราง)

**Quotation AP (5):**
- `quotation_ap_data` — ผล parse: project, company_title/address/taxid, quotation_no, job_no, ad_format, budget/vat/budget_all
- `quotation_ap_doc` — log เอกสารที่ ingest (doc_type: data/reference/synonym)
- `quotation_ap_reference` (475) — ฐานบริษัท/โครงการอ้างอิง
- `quotation_ap_reference_synonym` (2,084) — ชื่อเรียกพ้องสำหรับ fuzzy match
- `quotation_ap_running` — ตัวนับเลขเอกสารต่อ year/month (unique key)

**Quotation/Production (6):**
- `quotations`, `quotations_detail` (ใบเสนอราคา + รายการ), `production`, `production_task`, `production_cost`, `projects`

**Master (4):** `customers`, `members`, `teams`, `job_title`

**CMS auth (7):** `cms_accounts` (MD5 password), `modules`, `modules_actions`, `permissions`, `log_access`, `ip_attempts`, `ip_banned`

## DB accounts (local)

| User | Password | ไว้ใช้ |
|---|---|---|
| `apqt` | `apqt_local_2026` | admin/CLI |
| `productionmy` | `eedMhuW34X4HsMYU6k7L` | ตัวแอป (ตาม `config/database.php` ไม่ต้องแก้) |

Import ต้องใช้ `sql_mode='NO_AUTO_VALUE_ON_ZERO,ALLOW_INVALID_DATES'` เพราะ dump มี `'0000-00-00 00:00:00'`

## Features ที่พัฒนาแล้ว (อัปเดต 27/10/2026)

- **Excel engine ใหม่** — PHPExcel → PhpSpreadsheet 5.10 ผ่าน compatibility bridge (`libraries/Excel.php`) โค้ด controller เดิมรันได้ไม่ต้องแก้; ติดตั้งด้วย `composer require phpoffice/phpspreadsheet`
- **รับเฉพาะ .xlsx** — upload validation ปฏิเสธไฟล์อื่น, writer ใช้ Xlsx เท่านั้น
- **แก้ contact name ได้** — ช่อง "Contact Name" ในหน้า Quotation AP (Load/Save ผ่าน AJAX) → เขียนลง cell **E10** ของไฟล์ export; เก็บใน `quotation_ap_data.contact_name`; endpoint: `GET/POST /admin/quotationAP/edit_contact`
- **Export เป็น 2 zip** — ① `*.zip` = quotation xlsx ทุกใบ + `Mediaplan.xlsx` (แผนที่เติมเลข QT/โครงการ/ยอดแบ่งแล้ว พร้อม paste กลับ PO Status) ② `*_pdf.zip` = PDF ทุกใบ
- **PDF อัตโนมัติ** — ทุก quotation xlsx ถูก convert เป็น PDF ด้วย LibreOffice headless (`soffice --convert-to pdf`) ระหว่าง export
- **ชื่อไฟล์ ≤ 74 ตัวอักษร** (ไม่รวมนามสกุล) — `sanitize_text()` ใช้ mb_substr นับ Thai chars ถูกต้อง
- **Quotation render แยกเป็นคลาส** — `libraries/QuotationExporter.php` ทำ template → xlsx → PDF โดยไม่ต้องพึ่ง CodeIgniter/DB/HTTP; controller เหลือแค่เรียกใช้ (โค้ดชุดเดียวกับที่เทสต์ตรวจ)
- **1 แถว → หลายใบ (split)** — ProjectID ใน column E (Note) กำหนดจำนวนใบ แต่ละ ID ถูก lookup ชื่อโครงการจาก `ProjectDirectory` แบ่งเงินเท่ากัน (เก็บ 6 ตำแหน่ง แสดง 2) หรือระบุ `split_amounts` เมื่อแบ่งไม่เท่า — กรณีที่ใช้บ่อยที่สุด
- **หลาย row → 1 ใบ (merge)** — ส่ง `items` array เข้ามา แต่ละ item ใช้ 2 แถวจากแถว 23 ลงไป (แถวชื่อ: `D` เลขที่, `E` โครงการ · แถวรายละเอียด: `E` รายการ, `J` จำนวน, `K` ราคา/หน่วย, `L` สูตรจำนวนเงิน); เมื่อมี `items` ต้องไม่เขียน `K24` เพราะจะทับราคาของรายการแรก — **ถ้ามี `items` จะไม่ split** เพราะจะทำให้ยอดรวมของใบเพี้ยน
- **K24 = PURE MEDIA + Agency Fee + VAT ที่ลงตัวเป็นเลขกลม** — K24 ใช้ค่า PURE MEDIA จาก media plan (คอลัมน์ N) หรือคำนวณจาก `BUDGET ALL` เป็นทศนิยม 6 ตำแหน่ง (ไม่ปัดเป็นสตางค์ก่อน เพราะจะทำให้ยอดรวมเกิน/ขาด 1 สตางค์) แล้ว fee 11.5% + VAT 7% จึงกลับมาเป็นยอดที่ลูกค้าอนุมัติพอดี (ทดสอบ 5,000 / 10,000 / 12,345 / 25,000 / 100,000) — งานที่ไม่มี fee ส่ง `agency_fee => false`
- **E24** — `Boost post <period>` เมื่อมี period และเหลือแค่ `Boost post` เมื่อไม่มี (ไม่ต่อท้ายด้วย ad format)

### ภาพในใบ quotation
แอปแทรกภาพ 4 จุด (โลโก้ / ตรา / ลายเซ็น 2 จุด) จากไฟล์ใน
`code/public_html/upload/quotation/ap/img/` ทุกครั้งที่ export และล้างภาพเดิมที่ฝังใน template ออก
— ถ้าจะเปลี่ยนภาพ ให้เปลี่ยนไฟล์ใน `img/` ไม่ใช่ใน `template.xlsx` (ภาพใน template จะถูกทับ)

## สิ่งที่ถูกถอดออก (cleanup เมื่อ 25/09/2026)

- **DB**: ตาราง legacy (`newsletter_email`, `videos`, `frontend_language`, `migrations`) + โมดูลในตาราง `modules` 11 รายการ (Posts, Floor, Room type, Banners, Gallery, Project rooms, Menu Manager, Report, Agents, Estimated, Production Cost) พร้อม actions/permissions
- **ไฟล์**: controllers/views ของ Estimated + Production Cost ย้ายไป `modules/admin/_removed_quotation_module/` (รอลบถาวร)
- **Frontend CMS เก่า** (Booking, Agents, Magazine, Signup ฯลฯ) ยังอยู่ใน `application/controllers/` — ไม่ได้ใช้ แต่ยังไม่ได้ลบ

## ไฟล์ต้นทาง 2 ไฟล์

| ไฟล์ | ใช้ทำอะไร | ชีต |
|---|---|---|
| `Ewit_AP Online Media Plan 2024-2026` | media plan ที่ใช้งานจริงของเดือน | 1 เดือน = 1 tab |
| `AP Quotation - PO Status 2026` | ออกใบ Quotation + ติดตามสถานะบน AP Webvendor | **tab แรก = ข้อมูล media plan** (ชื่อ tab เปลี่ยนทุกเดือน เช่น `0926` → `1026`) + tab **ProjectID** (ใช้ทำ `ProjectDirectory`) |

- ชื่อ tab ที่ import ต้องตรงกับ `date_format($quotation_date, "my")` เช่น กันยายน 2026 = `0926`
- **หัวตารางอยู่ row 4** ในไฟล์ PO Status (เหนือขึ้นไปเป็นแถวสรุปยอด) — `excel_search_col()` จึงสแกน
  8 แถวแรกแล้วเลือกแถวที่ตรงชื่อคอลัมน์มากที่สุด (`QuotationBatch::headerRow`) ไม่ต้องแก้โค้ดเวลาทีมย้ายหัวตาราง
- แถวข้อมูลเริ่มที่ `header_row + 1`

## การแบ่งหนึ่งแถวเป็นหลายใบ (split)

`QuotationBatch::export()` รับหนึ่ง row แล้วคืนรายการเอกสาร — **จำนวนใบมาจาก column E (Note)**
ซึ่งเก็บ ProjectID ไว้ (คั่นด้วย comma, slash หรือขึ้นบรรทัดใหม่). **ProjectID อาจมีตัวอักษรได้**
เช่น `AP0001`, `AC0003` (รูปแบบ: ตัวอักษรไม่เกิน 3 ตัว + ตัวเลขอย่างน้อย 2 หลัก) และเทียบแบบ
ไม่สนตัวพิมพ์เล็ก/ใหญ่:

```
E = "40194/40112/70105"  + Budget All 10,000
  → 3 ใบ: Grande Pleno Pinklao - Charun 2 | Grande Pleno Pinklao - Kanchana
           | Pleno Town Ladkrabang - Chalongkrung
```

| ลำดับการตัดสินจำนวนใบ | ผล |
|---|---|
| มี `items` | 1 ใบ (merge ชนะ split) |
| `split_amounts` | จำนวนตามที่ระบุยอดมา (แบ่งไม่เท่ากัน) |
| ProjectID ใน `note` | 1 ID = 1 ใบ |
| `split_into` | N ใบ |
| ไม่มีอะไรเลย | 1 ใบ |

**เงิน**
- แบ่งเท่าเป็น default: `budget_all / N` เก็บ **6 ตำแหน่ง** แต่ cell แสดง 2 ตำแหน่ง
  (10,000 ÷ 3 → 3,333.333333 ต่อใบ ลูกค้าเห็น 3,333.33 และสามใบรวมได้ 10,000.00 พอดี)
- **ทุกใบที่ split ใช้ทศนิยม 6 ตำแหน่งเหมือนกันหมด** แม้ยอดจะลงตัวเป็นสตางค์ (15,000 ÷ 3 =
  5,000.000000) — ไม่มี logic แยกกรณี
- **PURE MEDIA เก็บ 6 ตำแหน่งเสมอ** — `10,000 / 1.19305 = 8,381.878379` พิมพ์เป็น
  8,381.88 ถ้าปัดเป็น 2 ตำแหน่งแล้วคิด fee ต่อ จะได้ยอดรวม **10,000.01** (เกินไป 1 สตางค์)
- fee ปัดตามความละเอียดของฐาน: ฐานที่ลงตัวเป็นสตางค์ (pure media จาก plan หรือ subtotal
  ของใบที่รวมหลายรายการ) ใช้ 2 ตำแหน่งเพื่อให้ยอดรวมกลับมาเป็นเลขกลม; ฐานที่เราคำนวณเอง
  หรือแบ่งมาใช้ 6 ตำแหน่ง
- แบ่งไม่เท่าได้: ส่ง `split_amounts => [6000, 4000]`

**ชื่อโครงการ** — ProjectID ถูก lookup ผ่าน `libraries/ProjectDirectory.php` (141 คู่
จากชีต ProjectID/ProjectName ของทีมการตลาด) ถ้าไม่พบ ID จะใช้ชื่อโครงการเดิมของ row
(ตั้งใจให้เห็นว่าผิด ไม่ใช่เดาชื่อใหม่)

**ชื่อเอกสาร (ชื่อไฟล์)** — ใช้ **BriefName (D) + Project (C) ของใบนั้น**: ถ้า D มีชื่อโครงการ
ของ row อยู่แล้ว จะถูกแทนที่ด้วยชื่อโครงการของใบนั้น ถ้า D ยังไม่มีชื่อโครงการ จะต่อท้ายให้
(ตัวอย่างจริง: No. 49 ของแผน ก.ย. 26 = QT 1591/1592 ซึ่งเป็นบรรทัดเดียวกันแต่คนละโครงการ)

```
D = ทาวน์โฮม และ บ้านแฝด โซน เพชรเกษม-บางแค_Pleno Petchkasem 91
note = 40144/70122   →  2 ใบ
  2026-1591_ทาวน์โฮม-และ-บ้านแฝด-โซน-เพชรเกษม-บางแค_Pleno-Petchkasem-91
  2026-1592_ทาวน์โฮม-และ-บ้านแฝด-โซน-เพชรเกษม-บางแค_Pleno-Town-Petchkasem-81
```

**ตอน import แถวเดียวจะถูกแตกเป็นหลายแถว** — 1 ใบ = 1 แถว + เลขของตัวเอง (แบบเดียวกับที่ทีมทำมือ:
แถว 49 สองบรรทัด 2026/1591 กับ 2026/1592) โดย `QuotationBatch::splitRows()` สร้างแถวใหม่
ครบทุกช่อง (โครงการจาก lookup, ชื่อไฟล์แบบ D+C, ยอดแบ่ง 6 ตำแหน่ง, `note` เหลือ ID เดียว
เพื่อไม่ให้ export แตกซ้ำ) — ทำให้ export เป็นการ render 1:1

**เลขใบ** — ระบบ **ออกรันต่อจากตัวนับ `quotation_ap_running` เอง**: ตัวนับเก็บ "เลขสุดท้ายที่ใช้"
ดังนั้นถ้า counter = 1590 ใบถัดไปคือ **1591** (เพิ่มก่อนใช้) ใบที่ split ออก N ใบจะได้เลขต่อกัน
N เลข และบันทึกตัวนับครั้งเดียวหลัง export จบ (ถ้าพังกลางทาง เลขที่ยังไม่ออกจะไม่ถูกเผา)

การหาเลขเริ่มต้นใช้ค่าสูงสุดจาก 3 ทาง:
1. counter ของ **เดือนเดียวกับใบเสนอราคา** (`get_quotationAP_running_exact`)
2. ถ้าเดือนนั้นยังไม่มี ใช้ counter ของเดือนก่อนหน้า (พฤติกรรมเดิมของการ import)
3. **floor จากเลขที่มีอยู่แล้วใน `quotation_ap_data`** — กันออกเลขซ้ำเมื่อ counter ตกหลัง

`split_quotation_nos` ยังส่งเข้ามาเองได้ถ้าต้องการกำหนดเลขตายตัว และ `split_periods` ใช้แยก
ช่วงเวลาต่อใบ

**การเก็บเลขที่ออกแล้ว** — ตอน import แถวของ media plan ถูกแตกเป็น 1 ใบ = 1 แถว
(`QuotationBatch::splitRows`) พร้อม `quotation_no` ของแต่ละใบที่ออกจาก running counter
แล้วบันทึกครั้งเดียวหลังจบการ import — export จึง render 1:1 ตามแถวในฐานข้อมูล
และเลขที่ออกไปแล้วถูกเก็บใน `quotation_ap_data.quotation_no` ตั้งแต่ตอน import

**การนำเข้า** — คอลัมน์ `Note` (E) ถูก map เป็น `note` ใน `get_data()` แล้ว (เพิ่มใน
`$coldata['field']`) จำนวนใบจึงมาจากข้อมูลจริงได้โดยไม่ต้องกรอกมือ

> **หมายเหตุ:** ไม่มีช่องกรอกจำนวนใบใน UI โดยตั้งใจ — จำนวนใบอ่านจาก Note ที่มากับ row
> และตอน import จะแตกแถวพร้อมออกรันต่อเนื่องจาก `quotation_ap_running` แล้ว

## ดึงข้อมูลจาก Google Sheet โดยตรง (ไม่ต้อง download/upload)

`POST /admin/quotationAP/import_from_google`  (form: `quotation_date=2026-09-25`)

อ่าน tab ของเดือนนั้น (`0926`, `1026`, …) ผ่าน Sheets API → split ตาม ProjectID ใน Note →
insert ลง `quotation_ap_data` พร้อมเลขรัน → **เขียนเลขกลับลงชีตให้เลย** (แทรกแถวให้ครบทุกใบ)
แล้วค่อยเรียก export เพื่อสร้างไฟล์ xlsx/PDF ตามปกติ

**ตั้งค่าครั้งเดียว**

1. Google Cloud → เปิด **Google Sheets API** → สร้าง **Service Account** → ดาวน์โหลด JSON key
2. วางไฟล์เป็น `code/application/config/google-service-account.json`
   (หรือตั้ง env `APQT_GOOGLE_SA=/path/to/key.json`) — ไฟล์นี้อยู่ใน `.gitignore`
3. แชร์สเปรดชีตให้ e-mail ของ service account (`...@....iam.gserviceaccount.com`)
   สิทธิ์ **Editor** (ถ้าให้ Viewer จะ import ได้แต่เขียนเลขกลับไม่ได้)
4. `config/google.php` ตั้ง spreadsheet id ไว้แล้ว; ปิดการเขียนกลับได้ด้วย
   `$config['google_write_back'] = false;`

**โครงสร้างโค้ด**

| ไฟล์ | หน้าที่ |
|---|---|
| `libraries/GoogleSheetsClient.php` | service-account JWT (openssl) → access token, `getValues`, `updateValues`, `insertRows` — ใช้ curl ล้วน ไม่เพิ่ม dependency |
| `libraries/SheetTable.php` | แปลง values matrix → rows ตามหัวตาราง (หา header row เอง), เก็บ `_row` ไว้เขียนกลับ, สร้าง A1 range |
| `QuotationAP::import_from_google()` | ต่อทุกอย่างเข้าด้วยกัน + เขียนเลขกลับจากล่างขึ้นบน |

## ไฟล์ที่ได้กลับมาหลัง import + export

```
upload/quotation/ap/archive/{folder}.zip
    ├─ 2026-1591_<brief>.xlsx      ← ใบ quotation
    ├─ 2026-1592_<brief>.xlsx
    └─ Mediaplan.xlsx              ← plan ที่เติมเลข QT แล้ว (1 แถว = 1 ใบ)
upload/quotation/ap/archive/{folder}_pdf.zip
    └─ ทุกใบเป็น PDF
```

`Mediaplan.xlsx` คือไฟล์ที่ paste กลับเข้า PO Status ได้เลย:
- แถวที่ split จะถูก **แทรกแถวใหม่** ให้ครบตามจำนวนใบ (`MediaPlanWriter::writeRow`)
- เติม `QT No.` ต่อใบ, `Project` จาก lookup, `BriefName` แบบ D+C, `Note` เหลือ ID เดียว
- คอลัมน์ที่เป็นของทั้ง entry (วันที่, objective, ad format, period, …) ถูกคัดลอกลงมาทุกแถว
- แถวที่อยู่ถัดลงไปไม่ถูกทับ (เขียนจากล่างขึ้นบน)

## Tests (gate)

ใบเสนอราคาต้องจบที่ PDF ที่ถูกต้อง เทสต์จึงรัน render จริงแล้วตรวจไฟล์ที่ได้:

```bash
code/tests/run            # จาก root ของ repo (~30 วินาที; 52 tests, 269 assertions)
code/tests/run --testdox  # รับ flag ของ PHPUnit ตามปกติ
```

- ครอบ: ข้อมูลลูกค้า, เลขที่ใบ/Job No, PURE MEDIA, Agency Fee, VAT, ยอดรวมเลขกลม, ข้อความใน PDF, จำนวนเงินเป็นตัวอักษร, ชื่อไฟล์, รายการ E24, หลายรายการในใบเดียว (merge), แตกแถวตาม ProjectID (comma/slash/ขึ้นบรรทัดใหม่, มีตัวอักษรได้), lookup ชื่อโครงการ, แบ่งเงินเท่า/ไม่เท่า, เศษ 6 ตำแหน่งที่ยังรวมได้เต็ม
- Golden reference: 13 ใบใน `exported_from original system_20260925/` — ถ้าเทสต์ขัดกับ reference ถือว่า reference ถูก
- รันใน sandbox ได้ (ไม่พึ่ง `$HOME` หรือเน็ต) เพราะ `phpunit.phar` ถูก commit ไว้ใน `code/tools/`

## Backups

```
backups/production_full_backup_2026-09-25.sql      # dump ก่อน cleanup ครั้งแรก (1.7M)
backups/production_clean_15tables_2026-09-25.sql.gz # หลัง cleanup รอบแรก (204K)
```

## ปัญหาที่ทราบ / TODO

- [x] ~~รหัสผ่าน MD5~~ → ใช้ `password_hash()` (bcrypt) แล้ว; legacy MD5 ถูก upgrade อัตโนมัติตอน login ครั้งแรก (เหลือ 2 บัญชีที่ยังเป็น MD5 จนกว่าจะ login)
- [x] ~~PHPExcel ล้าสมัย~~ → แก้แล้วด้วย PhpSpreadsheet (25/09/2026)
- [x] ~~ไม่มี FOREIGN KEY ใดๆ~~ → เพิ่ม FK ให้ความสัมพันธ์หลักแล้ว (27/10/2026 — ดู `code/database/migrations/20261027_integrity.sql`; คอลัมน์ audit `create_by_id`/`update_by_id` ยังไม่ผูก FK เพราะมี legacy user id ที่ถูกลบไปแล้วเป็นพันแถว)
- [x] ~~`quotations.quotation_no` ไม่ normalize (N/A, format ปนกัน), `production_id` เป็น varchar~~ → แก้แล้ว 27/10/2026 (`migrations/20261027_quotation_no.sql` + guard ใน `Quotation.php`): N/A/ช่องว่าง → NULL, QT/Q- prefix ถูกตัด, `production_id` เป็น INT พร้อม FK
- [ ] Charset `utf8` (3-byte) — เก็บ emoji ไม่ได้ ควรรื้อเป็น utf8mb4 ทั้ง schema
- [ ] `'0000-00-00 00:00:00'` ค้างในข้อมูลบางแถว — ควรแปลงเป็น NULL
- [ ] MySQL 5.7 (prod) vs 8.4 (local) — อะไรขึ้นต้องเช็ค sql_mode เสมอ
- [ ] ลบไฟล์ใน `_removed_quotation_module/` + frontend controllers เก่าเมื่อยืนยันแล้ว
- [ ] `agency_fee` ยัง default = เปิด (ตาม media plan ใหม่) — ถ้ามีงานที่ไม่มี fee ต้องส่ง flag ปิดมาจาก importer
