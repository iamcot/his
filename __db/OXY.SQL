UPDATE care_billing_item
SET item_unit_cost=30
WHERE item_code='HSCC01' OR item_code='HSCC02' OR item_code='HSCC03'OR item_code='HSCC04'OR item_code='HSCC05'

UPDATE care_billing_bill_item
SET bill_item_unit_cost=30
WHERE bill_item_code='HSCC01' OR bill_item_code='HSCC02' OR bill_item_code='HSCC03'OR bill_item_code='HSCC04'OR bill_item_code='HSCC05'