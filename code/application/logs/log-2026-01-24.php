<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-01-24 00:42:42 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:43:50 --> Query error: Expression #7 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'production.pc.cost_rate' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT
p.production_id,
j.job_id,
j.job_title,
j.job_rate,
j.job_discount,
j.job_status,
ifnull(pc.cost_rate,j.job_rate) AS n_cost_rate,
ifnull(pc.cost_discount,j.job_discount) AS n_cost_discount,
SUM(pt.work_hours) AS n_work_hours,
 (ifnull(pc.cost_rate,j.job_rate) * ( 1 - ((ifnull(pc.cost_discount,j.job_discount))/100))) * Sum(pt.work_hours) AS n_cost_amount,
p.job_no
FROM
members AS m
 JOIN job_title AS j ON m.job_id = j.job_id
 JOIN production_task AS pt ON pt.member_id = m.member_id and pt.is_active = 'Y' AND pt.is_delete = 'N'
 JOIN production AS p ON p.production_id = pt.production_id AND p.production_id = 288
 left JOIN production_cost AS pc ON pc.production_id = p.production_id  and  pc.job_id = j.job_id
 GROUP BY j.job_id               
                
ERROR - 2026-01-24 00:43:50 --> Severity: error --> Exception: Call to a member function result_array() on boolean /home/production/application/modules/admin/models/Production_cost_model.php 34
ERROR - 2026-01-24 00:44:31 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:44:42 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:45:24 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:46:27 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:47:49 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:47:49 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:47:49 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:47:55 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:04 --> The upload destination folder does not appear to be writable.
ERROR - 2026-01-24 00:48:04 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:04 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:04 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:11 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:16 --> The upload destination folder does not appear to be writable.
ERROR - 2026-01-24 00:48:16 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:16 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:16 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:23 --> 404 Page Not Found: /index
ERROR - 2026-01-24 00:48:23 --> 404 Page Not Found: /index
ERROR - 2026-01-24 19:28:46 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:46 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:46 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:46 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:46 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 19:28:47 --> 404 Page Not Found: ../modules/admin/controllers//index
ERROR - 2026-01-24 21:21:54 --> 404 Page Not Found: /index
