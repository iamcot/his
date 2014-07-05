/* them cot lot_id cho phieu VTYT */
ALTER TABLE `care_med_issue_paper`
   ADD COLUMN `lot_id` VARCHAR(20) NOT NULL COMMENT 'Lotid' AFTER `note`

ALTER TABLE `care_med_issue_paper`
   ADD COLUMN `Cost` VARCHAR(20) NOT NULL COMMENT 'Cost' AFTER `note`