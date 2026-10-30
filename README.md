# AP Quotation Generator

ระบบสร้างและจัดการใบเสนอราคา (Quotation) สำหรับงานโฆษณา Boost Post ของ AP (Thailand) พัฒนาต่อยอดบน CodeIgniter 3 รองรับการทำงานร่วมกับ Google Sheets, คำนวณภาษี/ส่วนแบ่งโครงการอัตโนมัติ และแปลงเอกสารเป็น Excel/PDF พร้อมใช้งานทันที

[![Release](https://img.shields.io/badge/Release-v1.26--production--ready-brightgreen.svg)](https://github.com/ake117/AP-Quotation-Generator/releases/tag/v1.26)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://www.php.net/)
[![PhpSpreadsheet](https://img.shields.io/badge/PhpSpreadsheet-5.10-orange.svg)](https://phpspreadsheet.readthedocs.io/)
[![Database](https://img.shields.io/badge/MySQL-8.0%20(utf8mb4)-blue.svg)](https://www.mysql.com/)

---

## 📌 สรุปข้อมูลโปรเจกต์ (Project Overview)

| รายการ | รายละเอียด |
|---|---|
| **Repository** | `ake117/AP-Quotation-Generator` (Branch: `main`) |
| **Local Path** | `C:\_quotation_script\AP_QT_Generator` |
| **Current Version** | `v1.26 - Production Ready (ใช้งานจริงได้แล้ว)` |
| **Core Framework** | CodeIgniter 3.1.13 + Smarty Template Engine + AdminLTE 2.4 |
| **Spreadsheet Engine**| PhpSpreadsheet 5.10 (ทำงานผ่าน Compatibility Bridge `libraries/Excel.php`) |
| **Database** | MySQL 8.0 (Charset: `utf8mb4`, Schema: `production`) |
| **PDF Conversion** | LibreOffice Headless (`soffice --convert-to pdf`) |

---

## 🚀 การติดตั้งและการเริ่มต้นใช้งาน (Getting Started)

### 1. ข้อมูลการเข้าสู่ระบบ (Local)

- **URL:** [http://127.0.0.1:8091/admin/login](http://127.0.0.1:8091/admin/login)
- **Email:** `ake@ewitewit.com` (ROOT)
- **Password:** `APqt2026!local`
- *เมื่อ Login สำเร็จ ระบบจะ Redirect เข้าสู่หน้าหลัก **Quotation AP** ทันที*

### 2. การตั้งค่า Environment (`.env`)

คัดลอกไฟล์ตัวอย่าง `code/.env.example` เป็น `code/.env`:

```bash
cp code/.env.example code/.env
```

กำหนดค่าคอนฟิกที่จำเป็น เช่น ชื่อผู้ติดต่อตาม Business Group (BG):
```env
# Contact Name สำหรับใส่ใน Cell E10 แยกตาม Business Group (BG)
BG_CONTACT_CD="คุณพาณินท์ บุญธรรม"
BG_CONTACT_SDH="คุณพาณินท์ บุญธรรม"
BG_CONTACT_TH="คุณณัฐวรรธน์ โต๊ะดอนทอง"

# หรือกำหนดเป็น JSON Dictionary (ทางเลือก)
# BG_CONTACTS='{"CD":"คุณพาณินท์ บุญธรรม","SDH":"คุณพาณินท์ บุญธรรม","TH":"คุณณัฐวรรธน์ โต๊ะดอนทอง"}'
```
> [!NOTE] 
> หากมี BG ใหม่เข้ามา สามารถเพิ่ม `BG_CONTACT_<BG>="คุณ..."` ใน `.env` ได้ทันทีโดยไม่ต้องแก้ไขโค้ดและไม่ต้องแก้ฐานข้อมูล

### 3. การรันระบบผ่าน Docker บน Windows

```bash
docker run -d --name qt_app --add-host=host.docker.internal:host-gateway \
  -p 8091:8091 -e DB_HOST=host.docker.internal \
  -v C:/_quotation_script/AP_QT_Generator/code:/app -w /app \
  qt_app_img php -S 0.0.0.0:8091 -t public_html

# ควบคุมการทำงาน:
docker stop qt_app
docker start qt_app
```

- **Extension ที่จำเป็น:** `mysqli`, `zip` (ต้องมี `libzip-dev`), `gd`
- **ฐานข้อมูล:** ชี้ไปที่ MySQL ใน WSL ด้วย `DB_HOST=host.docker.internal` (Database account สำหรับแอป: `productionmy` ตาม `config/database.php`)

---

## ⚙️ คุณสมบัติและการทำงานหลัก (Core Features & Business Rules)

### 1. ระบบจัดการชื่อผู้ติดต่อ (Contact Name ใน Cell E10)
- โปรแกรมเติมชื่อผู้ติดต่อในช่อง E10 อัตโนมัติจาก `.env` โดยดูจากคอลัมน์ `BG` ของโครงการ
- หากในเอกสารไม่มีการระบุชื่อและไม่พบ BG ในระบบ โปรแกรมจะปฏิเสธการประมวลผลทันที เพื่อป้องกันการออกเอกสารผิดพลาด

### 2. การแบ่ง 1 แถวเป็นหลายใบเสนอราคา (Split Documents)
- จำนวนใบเสนอราคาถูกกำหนดอัตโนมัติจาก **ProjectID ในคอลัมน์ Note (E)** ของ Media Plan (เช่น `40144/70122` แตกเป็น 2 ใบ)
- รองรับ ProjectID ที่มีตัวอักษรนำหน้า เช่น `AP0001`, `AC0003`
- ระบบจะ Lookup ชื่อโครงการจาก `libraries/ProjectDirectory.php` เพื่อนำมาใส่ในชื่อไฟล์และเนื้อหาใบเสนอราคา

### 3. สูตรคำนวณเงินและเศษทศนิยม (Pure Media + Fee + VAT)
- **PURE MEDIA เก็บละเอียด 6 ตำแหน่ง:** คำนวณย้อนกลับจาก `BUDGET ALL` ด้วยสูตร `BUDGET ALL / 1.19305` เพื่อให้เมื่อคำนวณ Agency Fee 11.5% และ VAT 7% แล้ว ยอดรวมจะกลับมาเป็นตัวเลขกลมตามงบที่ได้รับอนุมัติพอดี (ไม่คลาดเคลื่อน 1 สตางค์)
- **การแบ่งงบเท่ากัน:** ยอด `budget_all / N` คำนวณที่ 6 ตำแหน่ง และจัด Format การแสดงผลเป็น 2 ตำแหน่งในใบเสนอราคา

### 4. กฎการตั้งชื่อไฟล์และคำนำหน้า (Quotation_ Prefix)
- **ไฟล์ใบเสนอราคา:** ตั้งชื่อตามรูปแบบ `Quotation_<QuotationNo>_<BriefName>-<ProjectName>.xlsx` (ความยาวชื่อไม่เกิน 74 ตัวอักษร)
- **ไฟล์ ZIP สำหรับดาวน์โหลด:** มีคำนำหน้า `Quotation_` นำหน้าเวลาเสมอ:
  - `Quotation_<timestamp>.zip` : รวบรวมไฟล์ Excel ทั้งหมด + `Mediaplan.xlsx` ที่ใส่เลข QT แล้ว
  - `Quotation_<timestamp}_pdf.zip` : รวบรวมไฟล์ PDF ทุกใบ

### 5. การแทรกรูปภาพ ตราประทับ และลายเซ็น
- โปรแกรมจะแทรกรูปภาพ 4 จุด (โลโก้บริษัท, ตรายาง, ลายเซ็น 2 จุด) ลงในเทมเพลตอัตโนมัติจากโฟลเดอร์ `code/public_html/upload/quotation/ap/img/` ทุกครั้งที่มีการ Export

---

## 📂 โครงสร้างการจัดเก็บไฟล์ (Temp & Archive Storage)

```
upload/quotation/ap/
├── temp/{timestamp}/                   # โฟลเดอร์ทำงานชั่วคราวระหว่างสร้างเอกสาร (ล้างอัตโนมัติเมื่อเสร็จ)
└── archive/                            # จัดเก็บไฟล์ ZIP ผลลัพธ์
    ├── Quotation_{timestamp}.zip       # รวบรวมไฟล์ Excel + Mediaplan.xlsx
    │   ├── Quotation_2026-1591_...xlsx
    │   ├── Quotation_2026-1592_...xlsx
    │   └── Mediaplan.xlsx              # Media Plan ที่เติมเลข QT พร้อม Paste กลับ PO Status
    └── Quotation_{timestamp}_pdf.zip   # รวบรวมเฉพาะไฟล์ PDF ของทุกใบ
```

- **URL สำหรับดาวน์โหลดผ่าน Web Interface:**
  - Excel ZIP: `/upload/quotation/ap/archive/Quotation_{timestamp}.zip`
  - PDF ZIP: `/upload/quotation/ap/archive/Quotation_{timestamp}_pdf.zip`

---

## ☁️ การเชื่อมต่อกับ Google Sheets API (Direct Sync)

ระบบรองรับการดึงข้อมูลจาก Google Sheets ของทีม Media Plan ได้โดยตรงโดยไม่ต้องอัปโหลดไฟล์:
- **Endpoint:** `POST /admin/quotationAP/import_from_google` (ระบุ `quotation_date=YYYY-MM-DD`)
- **การทำงาน:** อ่านข้อมูล Tab ประจำเดือน (`0926`, `1026`, ...) → Split ข้อมูลและออกเลข QT → **เขียนเลข QT กลับเข้าสู่ Google Sheet ทันที** → สร้างเอกสารใบเสนอราคา

### การตั้งค่า Google Sheets API:
1. สร้าง Service Account ใน Google Cloud Platform และดาวน์โหลด JSON Credentials
2. นำไฟล์ไปวางที่ `code/application/config/google-service-account.json` (หรือระบุ path ใน ENV `APQT_GOOGLE_SA`)
3. กด Share สเปรดชีตให้กับ Email ของ Service Account โดยกำหนดสิทธิ์เป็น **Editor**

---

## 🧪 การทดสอบระบบ (Automated Tests & Quality Gate)

ระบบมีชุดทดสอบครอบคลุมทั้ง Business Logic และการ Render เอกสารจริง:

```bash
# รันชุดทดสอบทั้งหมด
code/tests/run

# รันพร้อมแสดงรายละเอียดข้อทดสอบ
code/tests/run --testdox
```

- **ความครอบคลุม:** 52 tests, 269 assertions (ครอบคลุมการคำนวณเงิน, เลขรัน, การ Split/Merge, การจัด Format, การดึง Contact Name จาก `.env` และการแปลงเป็น PDF)
- **Golden References:** ยึดความถูกต้องตามตัวอย่างเอกสาร 13 ใบใน `exported_from original system_20260925/`

---

## 📋 ประวัติเวอร์ชันที่สำคัญ (Release History)

| Version | รายละเอียดการเปลี่ยนแปลง |
|---|---|
| **v1.26** *(Latest)* | **Production Ready (ใช้งานจริงได้แล้ว):**<br>- รองรับ Contact Name (cell E10) ตาม BG จาก `code/.env`<br>- เติมคำนำหน้า `Quotation_` สำหรับไฟล์เอกสารและไฟล์ ZIP<br>- แยกดาวน์โหลด ZIP เป็น Excel และ PDF พร้อมอัปเดต UI แสดง Log<br>- แก้ปัญหา DB unknown column ด้วย Safe Auto-filter |
| **v1.25** | เพิ่มบันทึกข้อเสนอแนะเกี่ยวกับ UI Theme (Tabler) ลงในเอกสาร |
| **v1.20 - v1.24** | Modern Theme Overrides, แก้ไขปัญหา Admin 500, จัดระเบียบ Sidebar และ Header |
| **v1.17 - v1.18** | ปรับปรุง Database เป็น `utf8mb4` เต็มรูปแบบ และแก้ไข Invalid Zero-Dates เป็น NULL |
| **v1.16** | ยกเลิกกล่องกรอกชื่อผู้ติดต่อแบบ Manual ในหน้าเว็บเพื่อเปลี่ยนมาใช้ระบบอัตโนมัติ |
| **v1.14** | รองรับการ Import และ Write-back ข้อมูลผ่าน Google Sheets API โดยตรง |
| **v1.12 - v1.13** | ระบบ Split แถวตาม ProjectID อัตโนมัติ พร้อมส่งออก `Mediaplan.xlsx` กลับมาใน ZIP |
| **v1.03 - v1.08** | ระบบคำนวณ Pure Media ทศนิยม 6 ตำแหน่ง, การรวมใบ (Multi-item), และ PHPUnit PDF Gate |

---

## 📝 รายการที่รอดำเนินการ (Future Plan / TODO)

- [x] เพิ่ม Option บนหน้าเว็บสำหรับเลือกว่าต้องการสร้างเฉพาะไฟล์ Excel หรือต้องการแปลงไฟล์ PDF ด้วย
- [ ] พิจารณาปรับดีไซน์ UI ของหน้าเว็บให้มีความทันสมัยยิ่งขึ้น (เช่น ธีมสไตล์ Tabler หรือการปรับแต่ง CSS Override)
