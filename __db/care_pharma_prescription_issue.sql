alter table `care_pharma_prescription_issue`
   add column `status_bill` bigint(20) DEFAULT '0' NOT NULL after `available_product_id`