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
	if(!isset($dept_nr))
		$dept_nr=$_GET["dept_nr"];
	if(!isset($ward_nr))
		$ward_nr=$_GET["ward_nr"];
		
	$dept_ward='';
	if ($ward_nr!='')
		$dept_ward .= " AND taikhoa.ward_nr='".$ward_nr."' ";
	if ($dept_nr!='')
		$dept_ward .= " AND taikhoa.department='".$dept_nr."' ";
		
	$condition='';
	if ($name_med!=''){
		$temp = str_replace(' ', '%', $name_med);
		$condition = " AND ((khochan.product_name LIKE '".$temp."%') OR (khochan.product_name LIKE '% ".$temp."%')) ";
	}
	if ($name_ger_med!=''){
		$temp = str_replace(' ', '%', $name_ger_med);
		$condition = $condition." AND ((ger.generic_drug LIKE '".$temp."%') OR (ger.generic_drug LIKE '% ".$temp."%') ) ";
	}
	if ($group_med!=''){
		$temp = str_replace(' ', '%', $group_med);
		$condition = $condition." AND ((grp.pharma_group_name LIKE '".$temp."%') OR (grp.pharma_group_name LIKE '% ".$temp."%')) ";
	}
	if ($supplier!=''){
		$temp = str_replace(' ', '%', $supplier);
		$condition = $condition." AND (sup.supplier LIKE '".$temp."%' OR sup.supplier LIKE '% ".$temp."%' OR sup.supplier_name LIKE '".$temp."%' OR sup.supplier_name LIKE '% ".$temp."%') ";
	}

			
	$sql="SELECT khochan.product_encoder, khochan.product_name, donvi.unit_name_of_medicine, tatcakhoa.product_lot_id, tatcakhoa.exp_date, 
			taikhoa.* ,ger.generic_drug, grp.pharma_group_name, sup.supplier, sup.supplier_name 
			FROM care_pharma_available_department AS taikhoa, care_pharma_available_product AS tatcakhoa, 
			care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi,
			care_pharma_generic_drug AS ger, care_pharma_group AS grp, care_supplier AS sup 
			WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
			".$dept_ward." 
			AND khochan.product_encoder=tatcakhoa.product_encoder 
			AND donvi.unit_of_medicine=khochan.unit_of_medicine 
			AND khochan.pharma_generic_drug_id=ger.pharma_generic_drug_id 
			AND ger.pharma_group_id=grp.pharma_group_id 
			AND khochan.care_supplier=sup.supplier 
			AND taikhoa.available_number>0 
			".$condition."  
			ORDER BY khochan.product_name ";
	
	//echo $sql;
	
	if($ergebnis=$db->Execute($sql))
	{
		echo '<tr align="center">
				<th width="5%"><font color="#5f88be">'.$LDMedicineID1.'</td>
				<th width="20%"><font color="#5f88be">'.$LDNameMedicine.'</td>
				<th width="10%"><font color="#5f88be">'.$LDLotID.'</td>
				<th width="10%"><font color="#5f88be">'.$LDCabinetMedicineSum.'</td>
				<td width="10%"><font color="#5f88be"><b>'.$LDUseFor.'</b></td>
				<th width="15%"><font color="#5f88be">'.$LDNameGeneralMedicine.'</td>
				<th width="10%"><font color="#5f88be">'.$LDSupplier.'</td>
				<th width="15%"><font color="#5f88be">'.$LDWard.'</td>
				<th width="5%"><font color="#5f88be">'.$LDSelect.'</td>
			</tr>';
		$n=$ergebnis->RecordCount();
		$toggle=1;
		for($i=0; $i<$n; $i++)
		{
     		if($toggle) $bgc='#f3f3f3';
			else $bgc='#fefefe';
			$toggle=!$toggle;
			
			$item=$ergebnis->FetchRow();
			if($item['ward_nr']==0)
				$temp_ward_name=$LDAllWard;
			else{
				require_once($root_path.'include/care_api_classes/class_ward.php');
				$Ward = new Ward;
				$wardinfo = $Ward->getWardInfo($item['ward_nr']);
				$temp_ward_name=$wardinfo["name"];
			}
			switch($item['typeput']){
				case '0': $dang = 'BHYT'; break;
				case '1': $dang = 'KP'; break;
				case '2': $dang = 'CBTC'; break;
			}
			
			echo '<tr bgcolor="'.$bgc.'"><td>'.$item['product_encoder'].'</td>';
			echo '<td><b>'.$item['product_name'].'</b></td>';
			echo '<td>'.$item['product_lot_id'].'</td>';
			echo '<td align="center">'.$item['available_number'].'</td>';
			echo '<td>'.$dang.'</td>';
			echo '<td>'.$item['generic_drug'].'</td>';
			echo '<td>'.$item['supplier'].'</td>';
			echo '<td>'.$temp_ward_name.'</td>';
			echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['product_name'].'\',\''.$item['product_lot_id'].'\');"></td></tr>';
		}
	}
	
	
	
		
?>