alter table `care_test_request_other` drop column `urgent`,
   add column `urgent` int NOT NULL after `process_time`