<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (stristr ( 'inc_products_search_mod_datenbank.php', $_SERVER['SCRIPT_NAME'] ))
	die ( '<meta http-equiv="refresh" content="0; url=../">' );
	/*------end------*/
///$db->debug = true;
/**
 * CARE 2002 Integrated Hospital Information System
 * GNU General Public License
 * Copyright 2002 Elpidio Latorilla
 * elpidio@care2x.org, 
 *
 * See the file "copy_notice.txt" for the licence notice
 */
if ($cat == 'pharma') {
	$dbtable = 'care_pharma_products_main';
	$dbtableger= 'care_pharma_generic_drug';
	$dbtablesub = 'care_pharma_products_main_sub';
} else {		//cat = medipot.... 
	$dbtable = 'care_med_products_main';
	$dbtablesub = 'care_med_products_main_sub';
}
// clean input data
$keyword = addslashes ( trim ( $keyword ) );

// this is the search module
if ((($mode == 'search') || $update) && ($keyword != '')) {
	
	if ($update) {
		$sql = "SELECT  main.*, ger.*, grp.*, unit.unit_name_of_medicine , type.type_name_of_medicine   
				FROM $dbtable AS main, care_pharma_generic_drug AS ger, care_pharma_group AS grp, care_pharma_unit_of_medicine AS unit, care_pharma_type_of_medicine AS type 
				WHERE  main.product_encoder='$keyword' 
				AND main.pharma_generic_drug_id = ger.pharma_generic_drug_id
				AND ger.pharma_group_id = grp.pharma_group_id
				AND main.unit_of_medicine = unit.unit_of_medicine 
				AND main.type_of_medicine = type.type_of_medicine";
		$ergebnis = $db->Execute ( $sql );
		$linecount = $ergebnis->RecordCount ();
	} else {
		$sql = "SELECT $dbtable.*, sum($dbtablesub.number) AS qty, ger.*, grp.*, unit.unit_name_of_medicine, type.type_name_of_medicine   
				FROM $dbtable, $dbtablesub, care_pharma_generic_drug AS ger, care_pharma_group AS grp, care_pharma_unit_of_medicine AS unit, care_pharma_type_of_medicine AS type   
				WHERE  $dbtablesub.product_encoder = $dbtable.product_encoder 
				AND $dbtable.product_encoder='$keyword'
				GROUP BY $dbtable.product_encoder
				AND $dbtable.pharma_generic_drug_id = ger.pharma_generic_drug_id
				AND ger.pharma_group_id = grp.pharma_group_id
				AND $dbtable.unit_of_medicine = unit.unit_of_medicine 
				AND $dbtable.type_of_medicine = type.type_of_medicine";

		if ($ergebnis = $db->Execute ( $sql ))
			if (!$linecount = $ergebnis->RecordCount ()) {
				$sql = "SELECT * FROM $dbtable WHERE  product_encoder $sql_LIKE '$keyword%'";
				$ergebnis = $db->Execute ( $sql );
				$linecount = $ergebnis->RecordCount ();
			}
	} //end of if $update else
	//if parent is order catalog
	if (($linecount == 1) && $bcat) {
		$ttl = $ergebnis->FetchRow ();
		$ergebnis->MoveFirst ();
		$title_art = $ttl ['product_name'];
	}
}

?>
