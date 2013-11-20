<?php	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','pharma.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

	if(!isset($name_med))
		$name_med=$_GET["name_med"];
	if(!isset($name_ger_med))
		$name_ger_med=$_GET["name_ger_med"];
	if(!isset($group_med))
		$group_med=$_GET["group_med"];
	if(!isset($supplier))
		$supplier=$_GET["supplier"];
    if(!isset($typeput))
        $typeput=$_GET["typeput"];		
	$condition='';
	if ($name_med!=''){
		$temp = str_replace(' ', '%', $name_med);
		$condition = " AND ((med.product_name LIKE '".$temp."%') OR (med.product_name LIKE '% ".$temp."%') ) ";
	}
	if ($name_ger_med!=''){
		$temp = str_replace(' ', '%', $name_ger_med);
		$condition = $condition." AND (ger.generic_drug LIKE '".$temp."%' OR ger.generic_drug LIKE '% ".$temp."%' ) ";
	}
	if ($group_med!=''){
		$temp = str_replace(' ', '%', $group_med);
		$condition = $condition." AND (grp.pharma_group_name LIKE '".$temp."%' OR grp.pharma_group_name LIKE '% ".$temp."%' ) ";
	}
	if ($supplier!=''){
		$temp = str_replace(' ', '%', $supplier);
		$condition = $condition." AND (sup.supplier LIKE '".$temp."%' OR sup.supplier LIKE '% ".$temp."%' OR sup.supplier_name LIKE '".$temp."%' OR sup.supplier_name LIKE '% ".$temp."%') ";
	}
	if ($typeput!=''){
		$condition = $condition." AND main_sub.typeput='".$typeput."' ";
	}		
	$sql="SELECT med.product_encoder, med.product_name, main_sub.*, 
				ger.generic_drug, grp.pharma_group_name, sup.supplier, sup.supplier_name, unit.unit_name_of_medicine  
		FROM care_pharma_products_main AS med, care_pharma_products_main_sub AS main_sub, care_pharma_generic_drug AS ger, care_pharma_group AS grp, care_supplier AS sup, care_pharma_unit_of_medicine AS unit 
		WHERE med.care_supplier=sup.supplier
		AND med.product_encoder=main_sub.product_encoder 
		AND med.unit_of_medicine=unit.unit_of_medicine
		AND med.pharma_generic_drug_id=ger.pharma_generic_drug_id AND ger.pharma_group_id=grp.pharma_group_id 
		".$condition." 
		UNION
		SELECT med.product_encoder, med.product_name, main_sub.*, 
				ger.generic_drug, grp.pharma_group_name, sup.supplier, sup.supplier_name, unit.unit_name_of_medicine 
		FROM care_pharma_products_main AS med, care_pharma_products_main_sub AS main_sub, care_supplier AS sup, care_pharma_generic_vn AS ger, care_pharma_group_vn AS grp, care_pharma_unit_of_medicine AS unit 
		WHERE med.care_supplier=sup.supplier 
		AND med.product_encoder=main_sub.product_encoder 
		AND med.pharma_type='4' 
		AND med.unit_of_medicine=unit.unit_of_medicine
		AND med.pharma_generic_drug_id=ger.pharma_generic_drug_id AND ger.pharma_group_id=grp.pharma_group_id
		".$condition."  
		ORDER BY product_name";
	if($ergebnis=$db->Execute($sql))
	{
		echo '<tr align="center">
				<td width="15%"><font color="#5f88be"><b>'.$LDMedicineID1.'</b></td>
				<td width="20%"><font color="#5f88be"><b>'.$LDNameMedicine.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDNumber1.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDLotID1.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDExpDate1.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDNameGeneralMedicine.'</b></td>
				<td width="25%"><font color="#5f88be"><b>'.$LDGroupMedicine.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDSupplier.'</b></td>
				<td width="5%"><font color="#5f88be"><b>'.$LDSelect.'</b></td>
			</tr>';
		$n=$ergebnis->RecordCount();
		$toggle=1;
		for($i=0; $i<$n; $i++)
		{
     		if($toggle) $bgc='#f3f3f3';
			else $bgc='#fefefe';
			$toggle=!$toggle;
			
			$item=$ergebnis->FetchRow();
			$expdate= formatDate2Local($item['exp_date'],'dd/mm/yyyy');
			
			echo '<tr bgcolor="'.$bgc.'"><td>'.$item['product_encoder'].'</td>';
			echo '<td><b>'.$item['product_name'].'</b></td>';
			echo '<td align="center">'.$item['number'].'</td>';
			echo '<td align="center">'.$item['lotid'].'</td>';
			echo '<td align="center">'.$item['exp_date'].'</td>';
			echo '<td>'.$item['generic_drug'].'</td>';
			echo '<td>'.$item['pharma_group_name'].'</td>';
			echo '<td>'.$item['supplier'].'</td>';
			echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['product_name'].'\',\''.$item['product_encoder'].'_'.$item['price'].'_'.$item['lotid'].'_'.$expdate.'\',\''.$item['unit_name_of_medicine'].'\');"></td></tr>';
		}
	}
	
	
	
		
?>