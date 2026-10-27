-- ============================================================
-- AP Quotation Generator — database integrity migration
-- 2026-10-27
--
-- What this does
--   1. Clears zero-dates ('0000-00-00 ...') that block ALTER TABLE
--      under sql_mode NO_ZERO_DATE / NO_ZERO_IN_DATE.
--   2. Aligns module/permission id columns to INT UNSIGNED so they
--      can carry foreign keys against modules.id / cms_accounts.id.
--   3. Removes orphan rows left behind by the old application layer
--      (integrity used to live only in PHP).
--   4. Adds the foreign keys that were missing from the schema.
--
-- Run once against the production database, after a backup:
--   mysql -u productionmy -p production < 20261027_integrity.sql
--
-- NOTE: step 3 deletes rows. Review the counts in your own database
-- before running this on production (see README, section "ปัญหาที่ทราบ / TODO").
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SESSION sql_mode = '';

-- ------------------------------------------------------------
-- 1. zero dates
-- ------------------------------------------------------------
UPDATE quotations SET quotation_date = '2026-01-01 00:00:00' WHERE CAST(quotation_date AS CHAR) LIKE '0000-00-00%';
UPDATE quotations SET production_start = NULL WHERE CAST(production_start AS CHAR) LIKE '0000-00-00%';
UPDATE quotations SET production_end   = NULL WHERE CAST(production_end AS CHAR) LIKE '0000-00-00%';
UPDATE quotations SET create_date      = NULL WHERE CAST(create_date AS CHAR) LIKE '0000-00-00%';
UPDATE quotations SET update_date      = NULL WHERE CAST(update_date AS CHAR) LIKE '0000-00-00%';
UPDATE quotations SET delete_date      = NULL WHERE CAST(delete_date AS CHAR) LIKE '0000-00-00%';

UPDATE production SET production_start = '2026-01-01 00:00:00' WHERE CAST(production_start AS CHAR) LIKE '0000-00-00%';
UPDATE production SET production_end   = NULL WHERE CAST(production_end AS CHAR) LIKE '0000-00-00%';
UPDATE production SET create_date      = NULL WHERE CAST(create_date AS CHAR) LIKE '0000-00-00%';
UPDATE production SET update_date      = NULL WHERE CAST(update_date AS CHAR) LIKE '0000-00-00%';
UPDATE production SET delete_date      = NULL WHERE CAST(delete_date AS CHAR) LIKE '0000-00-00%';

UPDATE production_cost SET create_date = NULL WHERE CAST(create_date AS CHAR) LIKE '0000-00-00%';
UPDATE production_cost SET update_date = NULL WHERE CAST(update_date AS CHAR) LIKE '0000-00-00%';
UPDATE production_cost SET delete_date = NULL WHERE CAST(delete_date AS CHAR) LIKE '0000-00-00%';

UPDATE production_task SET create_date = NULL WHERE CAST(create_date AS CHAR) LIKE '0000-00-00%';
UPDATE production_task SET update_date = NULL WHERE CAST(update_date AS CHAR) LIKE '0000-00-00%';
UPDATE production_task SET delete_date = NULL WHERE CAST(delete_date AS CHAR) LIKE '0000-00-00%';

UPDATE quotations_detail SET create_date = NULL WHERE CAST(create_date AS CHAR) LIKE '0000-00-00%';
UPDATE quotations_detail SET update_date = NULL WHERE CAST(update_date AS CHAR) LIKE '0000-00-00%';
UPDATE quotations_detail SET delete_date = NULL WHERE CAST(delete_date AS CHAR) LIKE '0000-00-00%';

SET SESSION sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- ------------------------------------------------------------
-- 2. id columns must match the type they point at
-- ------------------------------------------------------------
ALTER TABLE modules_actions MODIFY module_id INT UNSIGNED NOT NULL;
ALTER TABLE permissions MODIFY module_id INT UNSIGNED NOT NULL,
                         MODIFY module_action_id INT UNSIGNED NOT NULL,
                         MODIFY account_id INT UNSIGNED NOT NULL;

-- ------------------------------------------------------------
-- 3. orphan cleanup (the old app layer left these behind)
--    delete children first, then detach the remaining rows
-- ------------------------------------------------------------
DELETE FROM production_cost  WHERE production_id NOT IN (SELECT production_id FROM production);
DELETE FROM production_task  WHERE production_id NOT IN (SELECT production_id FROM production);
DELETE FROM quotations_detail WHERE quotation_id NOT IN (SELECT quotation_id FROM quotations);

UPDATE quotations SET customer_id = NULL WHERE customer_id IS NOT NULL AND customer_id NOT IN (SELECT customer_id FROM customers);
UPDATE quotations SET project_id  = NULL WHERE project_id  IS NOT NULL AND project_id  NOT IN (SELECT project_id  FROM projects);
UPDATE projects   SET customer_id = NULL WHERE customer_id IS NOT NULL AND customer_id NOT IN (SELECT customer_id FROM customers);
UPDATE production SET pm_member_id = NULL WHERE pm_member_id IS NOT NULL AND pm_member_id NOT IN (SELECT member_id FROM members);

-- ------------------------------------------------------------
-- 4. foreign keys
-- ------------------------------------------------------------
ALTER TABLE quotations
  ADD CONSTRAINT fk_quotations_customer FOREIGN KEY (customer_id)     REFERENCES customers(customer_id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_quotations_project  FOREIGN KEY (project_id)      REFERENCES projects(project_id)   ON DELETE SET NULL,
  ADD CONSTRAINT fk_quotations_member   FOREIGN KEY (issue_member_id) REFERENCES members(member_id)     ON DELETE RESTRICT;

ALTER TABLE quotations_detail
  ADD CONSTRAINT fk_qdetail_quotation FOREIGN KEY (quotation_id) REFERENCES quotations(quotation_id) ON DELETE CASCADE;

ALTER TABLE projects
  ADD CONSTRAINT fk_projects_customer FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL;

ALTER TABLE production
  ADD CONSTRAINT fk_production_project FOREIGN KEY (project_id)   REFERENCES projects(project_id) ON DELETE RESTRICT,
  ADD CONSTRAINT fk_production_pm      FOREIGN KEY (pm_member_id) REFERENCES members(member_id)   ON DELETE SET NULL;

ALTER TABLE production_cost
  ADD CONSTRAINT fk_pcost_production FOREIGN KEY (production_id) REFERENCES production(production_id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_pcost_job        FOREIGN KEY (job_id)        REFERENCES job_title(job_id)         ON DELETE RESTRICT;

ALTER TABLE production_task
  ADD CONSTRAINT fk_ptask_production FOREIGN KEY (production_id) REFERENCES production(production_id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_ptask_member     FOREIGN KEY (member_id)     REFERENCES members(member_id)        ON DELETE RESTRICT;

ALTER TABLE members
  ADD CONSTRAINT fk_members_team FOREIGN KEY (team_id) REFERENCES teams(team_id)     ON DELETE RESTRICT,
  ADD CONSTRAINT fk_members_job  FOREIGN KEY (job_id)  REFERENCES job_title(job_id)  ON DELETE RESTRICT;

ALTER TABLE modules_actions
  ADD CONSTRAINT fk_mactions_module FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE RESTRICT;

ALTER TABLE permissions
  ADD CONSTRAINT fk_perm_module FOREIGN KEY (module_id)        REFERENCES modules(id)         ON DELETE RESTRICT,
  ADD CONSTRAINT fk_perm_action FOREIGN KEY (module_action_id) REFERENCES modules_actions(id) ON DELETE RESTRICT,
  ADD CONSTRAINT fk_perm_account FOREIGN KEY (account_id)      REFERENCES cms_accounts(id)    ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
