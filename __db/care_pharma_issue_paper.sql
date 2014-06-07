/* them cot lot_id cho phieu linh thuoc */
alter table `care_pharma_issue_paper`
   add column `lot_id` varchar(20) NOT NULL COMMENT 'Lotid' after `note`