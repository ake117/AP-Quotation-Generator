# AP Quotation Generator (AP_Quotation_by_Chat)

ระบบสร้าง/จัดการใบเสนอราคา (Quotation) สำหรับงานโฆษณา Boost Post ของ AP (Thailand) — พัฒนาต่อยอดบนแอดมินแพanel CodeIgniter (wpanel) โดยโฟกัสเฉพาะโมดูล **Quotation AP**

---

## ข้อมูลโปรเจกต์

| รายการ | รายละเอียด |
|---|---|
| Repo | `ake117/AP_Quotation_by_Chat` (private, branch `main`) |
| Local path | `/home/ake/AI_Projects/AP_QT_Generator` |
| Framework | CodeIgniter 3.1.9 + Smarty template + AdminLTE 2.4 (skin ต่อ account) |
| Database | MySQL/MariaDB — schema `production` |
| ต้นทาง DB dump | `code/database/production.sql` (Navicat dump จาก production.ewitewit.com, MySQL 5.7, 23/09/2026) |
| Dev server | `php -S 0.0.0.0:8091 -t code/public_html` |
| Adminer | `php -S 127.0.0.1:8090 index.php` ที่ `~/.local/share/adminer/` |

## การเข้าใช้งาน (local)

```
URL      : http://127.0.0.1:8091/admin/login
Email    : ake@ewitewit.com   (ROOT)  หรือ akekapon@ewitewit.com (ROOT)
Password : APqt2026!local
```

- หลัง login จะเข้าหน้า **Quotation AP** ทันที (แก้ที่ `Logon.php` — `redirect('admin/quotationAP')`)
- Theme: AdminLTE skin เก็บต่อ account ใน `cms_accounts.extra_data.skin` (ปัจจุบัน `blue-light`)
- รหัสผ่านเก็บแบบ **MD5** (`md5(password + salt)`, salt ว่าง) — แก้ได้ผ่าน `UPDATE cms_accounts SET password=MD5('...')`

## โครงสร้างโฟลเดอร์

```
AP_QT_Generator/
├── backups/                    # DB backups (full + clean)
├── code/
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

## สิ่งที่ถูกถอดออก (cleanup เมื่อ 25/09/2026)

- **DB**: ตาราง legacy (`newsletter_email`, `videos`, `frontend_language`, `migrations`) + โมดูลในตาราง `modules` 11 รายการ (Posts, Floor, Room type, Banners, Gallery, Project rooms, Menu Manager, Report, Agents, Estimated, Production Cost) พร้อม actions/permissions
- **ไฟล์**: controllers/views ของ Estimated + Production Cost ย้ายไป `modules/admin/_removed_quotation_module/` (รอลบถาวร)
- **Frontend CMS เก่า** (Booking, Agents, Magazine, Signup ฯลฯ) ยังอยู่ใน `application/controllers/` — ไม่ได้ใช้ แต่ยังไม่ได้ลบ

## Backups

```
backups/production_full_backup_2026-09-25.sql      # dump ก่อน cleanup ครั้งแรก (1.7M)
backups/production_clean_15tables_2026-09-25.sql.gz # หลัง cleanup รอบแรก (204K)
```

## ปัญหาที่ทราบ / TODO

- [ ] รหัสผ่าน MD5 — ควร migrate เป็น password_hash()
- [ ] ไม่มี FOREIGN KEY ใดๆ (integrity อยู่ที่ app layer)
- [ ] `quotations.quotation_no` ไม่ normalize (N/A, format ปนกัน), `production_id` เป็น varchar
- [ ] Charset `utf8` (3-byte) — เก็บ emoji ไม่ได้ ควรรื้อเป็น utf8mb4 ทั้ง schema
- [ ] `'0000-00-00 00:00:00'` ค้างในข้อมูลบางแถว — ควรแปลงเป็น NULL
- [ ] MySQL 5.7 (prod) vs 8.4 (local) — รอะไรขึ้นต้องเช็ค sql_mode เสมอ
- [ ] ลบไฟล์ใน `_removed_quotation_module/` + frontend controllers เก่าเมื่อยืนยันแล้ว
