CREATE OR REPLACE VIEW `dfck_bttv_view` AS
SELECT `bt`.`vncode` AS `vncode`,UCASE(`e`.`referrer_diagnosis_code`) AS `eicd10`,
`e`.`encounter_date` AS `encounter_date`,`e`.`encounter_nr` AS `encounter_nr`,
`bt`.`info` AS `info`,`bt`.`icd10` AS `icd10`,`bt`.`icd10more` AS `icd10more`,
`bt`.`sname` AS `sname`,`e`.`encounter_class_nr` AS `encounter_class_nr`,
`e`.`current_dept_nr` AS `current_dept_nr`,`p`.`sex` AS `sex`,`p`.`death_date` AS `death_date`,
`p`.`date_birth` AS `birthyear`
FROM `care_encounter` `e`
LEFT JOIN `dfck_icd10_group_bttv` `bt` ON(LOCATE(UCASE(SUBSTR(`e`.`referrer_diagnosis_code`,1,3)),`bt`.`icd10detail`) > 0)
AND `e`.`referrer_diagnosis_code` <> _utf8''
LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
ORDER BY `bt`.`vncode`
