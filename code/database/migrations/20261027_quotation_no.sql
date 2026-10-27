-- ============================================================
-- AP Quotation Generator — normalise quotation numbers, type production_id
-- 2026-10-27
--
-- Why
--   quotations.quotation_no held four different shapes ('2019/747',
--   'QT2020/129', 'Q-0002', '750') plus 'N/A' and blanks, so no report or
--   lookup could trust the column. production_id was varchar(60) while
--   production.production_id is int, which made the join a string comparison
--   and blocked a foreign key.
--
-- What this does
--   1. Maps the handful of hand-typed numbers onto the YYYY/N shape the
--      generator (QuotationBatch::consecutiveNumbers) already emits.
--   2. Turns 'N/A' variants and blanks into NULL — "no number yet" is the
--      honest value, and NULL sorts/filters predictably.
--   3. Converts production_id to INT and adds the missing foreign key.
--
-- Historical numbers are NOT re-padded to four digits: 2019/747 is already
-- printed on documents that went to customers. Renumbering would break the
-- link between the database row and the paper copy.
-- ============================================================

START TRANSACTION;

UPDATE quotations SET quotation_no = '2019/0002' WHERE quotation_no = 'Q-0002';
UPDATE quotations SET quotation_no = '2019/750'  WHERE quotation_no = '750';
UPDATE quotations SET quotation_no = REPLACE(quotation_no, 'QT', '')
  WHERE quotation_no LIKE 'QT2020/%';

UPDATE quotations SET quotation_no = NULL
  WHERE quotation_no IS NOT NULL
    AND (TRIM(quotation_no) = '' OR TRIM(quotation_no) REGEXP '^[[:space:]]*[ื์]?N/?A$');

UPDATE quotations SET production_id = NULL
  WHERE production_id IS NOT NULL AND TRIM(production_id) = '';

COMMIT;

-- 1) convert the column, 2) constrain it
ALTER TABLE quotations MODIFY production_id INT NULL;

ALTER TABLE quotations
  ADD CONSTRAINT fk_quotations_production FOREIGN KEY (production_id)
      REFERENCES production(production_id) ON DELETE SET NULL;
