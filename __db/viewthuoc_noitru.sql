
CREATE
    /*[ALGORITHM = {UNDEFINED | MERGE | TEMPTABLE}]
    [DEFINER = { user | CURRENT_USER }]
    [SQL SECURITY { DEFINER | INVOKER }]*/
    VIEW `histudb`.`viewthuoc_noitru`
    AS
(SELECT
  `iss`.`enc_nr` AS `enc_nr`,
  SUM((`iss`.`number` * `prs`.`cost`)) AS `tongthuoc`
FROM (`care_pharma_prescription_issue` `iss`
   JOIN `care_pharma_prescription` `prs`)
WHERE ((`prs`.`prescription_id` = `iss`.`pres_id`)
       AND (`prs`.`product_encoder` = `iss`.`product_encoder`))
GROUP BY `iss`.`enc_nr`);