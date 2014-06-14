alter table `histudb`.`care_test_request_dientim_sub`
   add column `item_bill_code` varchar(11) CHARSET utf8 COLLATE utf8_general_ci NULL after `encounter_nr`,
   add column `item_bill_name` text CHARSET utf8 COLLATE utf8_general_ci NULL after `item_bill_code`