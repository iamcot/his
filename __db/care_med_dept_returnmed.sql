ALTER TABLE `care_med_dept_returnmed` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `note`;

ALTER TABLE `care_pharma_dept_returnmed` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `note`;

ALTER TABLE `care_med_dept_destroymed` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `note`;
ALTER TABLE `care_pharma_dept_destroymed` ADD `available_product_id` BIGINT(99)  NOT NULL  AFTER `note`;

