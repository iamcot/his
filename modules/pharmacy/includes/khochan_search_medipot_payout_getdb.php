<?php	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','pharma.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');

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
	$sql="SELECT khochan.product_encoder, khochan.product_name, khochan.available_number, donvi.unit_name_of_medicine, main_sub.*, main_sub.price AS cost, 
			typemed.type_name_of_med, grp.name_sub, sup.supplier, sup.supplier_name 
		  FROM  care_med_products_main AS khochan, care_med_products_main_sub1 AS main_sub, care_med_unit_of_medipot AS donvi,
			care_med_type_of_medicine AS typemed, care_med_products_main_sub AS grp, care_supplier AS sup 
		  WHERE donvi.unit_of_medicine=khochan.unit_of_medicine 
			AND khochan.product_encoder=main_sub.product_encoder 
			AND grp.type_of_med=typemed.type_of_med 
			AND khochan.id_sub=grp.id 
			AND khochan.care_supplier=sup.supplier  
			".$condition."  
			ORDER BY khochan.product_name ";
	if($ergebnis=$db->Execute($sql))
	{
		echo '<tr align="center">
				<td width="5%"><font color="#5f88be"><b>'.$LDMedipotID.'</b></td>
				<td width="20%"><font color="#5f88be"><b>'.$LDMedipotName.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDNumber1.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDLotID1.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDExpDate1.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDGroupMedipot.'</b></td>
				<td width="10%"><font color="#5f88be"><b>'.$LDTypeMedipot.'</b></td>
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
			echo '<tr bgcolor="'.$bgc.'"><td>'.$item['product_encoder'].'</td>';
			echo '<td><b>'.$item['product_name'].'</b></td>';
			echo '<td align="center">'.$item['number'].'</td>';
			echo '<td align="center">'.$item['lotid'].'</td>';
			echo '<td align="center">'.$item['exp_date'].'</td>';
			echo '<td>'.$item['name_sub'].'</td>';
			echo '<td>'.$item['type_name_of_med'].'</td>';
			echo '<td>'.$item['supplier'].'</td>';
			echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['product_name'].'\',\''.$item['product_encoder'].'\');"></td></tr>';
		}
	}
	
	
	
		
?>