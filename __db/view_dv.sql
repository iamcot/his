
CREATE
    /*[ALGORITHM = {UNDEFINED | MERGE | TEMPTABLE}]
    [DEFINER = { user | CURRENT_USER }]
    [SQL SECURITY { DEFINER | INVOKER }]*/
    VIEW `histudb`.`view_dv`
    AS
(SELECT
  `bill`.`bill_item_encounter_nr` AS `bill_item_encounter_nr`,
  (CASE WHEN (`serv`.`item_group_nr` <= 25) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `xet_nghiem`,
  (CASE WHEN ((`serv`.`item_group_nr` = 26) OR (`serv`.`item_group_nr` = 28) OR (`serv`.`item_group_nr` = 39)) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `cdha`,
  (CASE WHEN ((`serv`.`item_group_nr` = 27) OR (`serv`.`item_group_nr` = 29)) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `sieu_am`,
  (CASE WHEN ((`serv`.`item_group_nr` = 33) OR (`serv`.`item_group_nr` = 34)) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `tt_pt`,
  (CASE WHEN (`serv`.`item_group_nr` = 38) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `ddt`,
  (CASE WHEN ((`serv`.`item_group_nr` >= 30) AND (`serv`.`item_group_nr` <= 32)) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `mau`,
  (CASE WHEN (`serv`.`item_group_nr` = 35) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `giuong`,
  (CASE WHEN (`serv`.`item_group_nr` = 37) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `thuoc`,
  (CASE WHEN (`serv`.`item_group_nr` = 42) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `vtyt`,
  (CASE WHEN (`serv`.`item_group_nr` = 43) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `hoachat`,
  (CASE WHEN (`serv`.`item_group_nr` = 41) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `chuyenvien`,
  (CASE WHEN (`serv`.`item_group_nr` = 40) THEN (`bill`.`bill_item_unit_cost` * `bill`.`bill_item_units`) END) AS `khamchuabenh`
FROM ((`care_billing_bill_item` `bill`
    JOIN `care_billing_item` `serv`)
   JOIN `care_billing_item_group` `group`)
WHERE ((`bill`.`bill_item_code` = `serv`.`item_code`)
       AND (`serv`.`item_group_nr` = `group`.`nr`)));