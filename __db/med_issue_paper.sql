ALTER TABLE `care_med_issue_paper` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `note`;

/* 1:26:27 PM localhost */
ALTER TABLE `care_med_department_archive` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `user`;