 ALTER TABLE `care_med_available_department` ADD `update_at` TIMESTAMP  NULL  AFTER `typeput`;
/* 2:52:02 PM localhost */ ALTER TABLE `care_med_available_department` ADD `create_at` TIMESTAMP  NULL  AFTER `update_at`;
/* 3:06:17 PM localhost */
ALTER TABLE `care_med_available_department` CHANGE `update_at` `update_at` TIMESTAMP  NOT NULL  ON UPDATE CURRENT_TIMESTAMP;
/* 3:12:18 PM localhost */ ALTER TABLE `care_med_available_department` CHANGE `typeput` `typeput` TINYINT(2)  NULL  DEFAULT '0'  COMMENT '0:bh, 1:sn, 2:cbtc';

ALTER TABLE `care_med_department_archive`
   ADD COLUMN `Cost` VARCHAR(20) NOT NULL COMMENT 'Cost' AFTER `number`