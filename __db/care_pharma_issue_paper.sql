/* 1:03:21 PM localhost */
ALTER TABLE `care_pharma_issue_paper` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `note`;

/* 1:26:27 PM localhost */
ALTER TABLE `care_pharma_department_archive` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `exp_date`;



