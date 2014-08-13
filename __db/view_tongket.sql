
CREATE
    /*[ALGORITHM = {UNDEFINED | MERGE | TEMPTABLE}]
    [DEFINER = { user | CURRENT_USER }]
    [SQL SECURITY { DEFINER | INVOKER }]*/
    VIEW `histudb`.`view_tongket`
    AS
(SELECT
  `care_billing_bill`.`bill_encounter_nr` AS `bill_encounter_nr`,
  SUM(`care_billing_bill`.`bill_amount`)  AS `tongchi`,
  SUM(`care_billing_bill`.`bill_discount`) AS `bhct`,
  SUM(`care_billing_bill`.`bill_outstanding`) AS `bnct`
FROM `care_billing_bill`
GROUP BY `care_billing_bill`.`bill_encounter_nr`);