-- Columns required by the Google-Sheet / file importer (QuotationAP):
-- 'BG' => bg_key, 'PURE MEDIA' => media_cost, 'PERIOD' => period_text, 'NOTE' => note
-- Upstream merge added the code but no migration; first import failed with
-- "Unknown column 'bg_key' in 'field list'". Applied to local DB 30/10/2026.

ALTER TABLE `quotation_ap_data`
  ADD COLUMN `media_cost` DECIMAL(10,2) NULL AFTER `budget_all`,
  ADD COLUMN `bg_key` VARCHAR(20) NULL AFTER `media_cost`,
  ADD COLUMN `period_text` VARCHAR(200) NULL AFTER `bg_key`,
  ADD COLUMN `note` TEXT NULL AFTER `period_text`;
