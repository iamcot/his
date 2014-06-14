alter table `care_test_request_dientim_sub`
   add column `item_bill_code` varchar(50) NULL after `encounter_nr`,
   add column `item_bill_name` varchar(50) NULL after `item_bill_code`