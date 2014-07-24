UPDATE care_billing_item SET item_unit_cost='35000' WHERE item_code='HSM29'
UPDATE care_billing_item SET item_unit_cost='60000' WHERE item_code='HSM27'
UPDATE care_billing_item SET item_unit_cost='75000' WHERE item_code='HSM26'
UPDATE care_billing_item SET item_unit_cost='15000' WHERE item_code='HSM19'
UPDATE care_billing_item SET item_unit_cost='25000' WHERE item_code='HSM22'
UPDATE care_billing_item SET item_unit_cost='25000' WHERE item_code='HSM23'
UPDATE care_billing_item SET item_unit_cost='25000' WHERE item_code='HSM20'
UPDATE care_billing_item SET item_unit_cost='45000' WHERE item_code='HSM30'
UPDATE care_billing_item SET item_unit_cost='45000' WHERE item_code='HSM31'
UPDATE care_billing_item SET item_unit_cost='90000' WHERE item_code='HSM21'
UPDATE care_billing_item SET item_unit_cost='90000' WHERE item_code='HSM37'

create table `histudb`.`TableName1`(
   `id` int(11) NOT NULL AUTO_INCREMENT ,
   `nhap` int(11) NOT NULL DEFAULT '0' ,
   `xuat` int(11) NOT NULL DEFAULT '0' ,
   `pgyear` int(11) NOT NULL DEFAULT '0' ,
   PRIMARY KEY (`id`)
 )
