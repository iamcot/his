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
		$type_med=$_GET["type_med"];
	if(!isset($group_med))
		$group_med=$_GET["group_med"];
	if(!isset($supplier))
		$supplier=$_GET["supplier"];
		
	$condition='';
	if ($name_med!=''){
		$temp = str_replace(' ', '%', $name_med);
		$condition = " AND (khochan.product_name LIKE '%".$temp."%' ) ";
	}
	if ($type_med!=''){
		$temp = str_replace(' ', '%', $type_med);
		$condition = $condition." AND (typemed.type_name_of_med LIKE '%".$temp."%' ) ";
	}
	if ($group_med!=''){
		$temp = str_replace(' ', '%', $group_med);
		$condition = $condition." AND (grp.name_sub LIKE '%".$temp."%' ) ";
	}
	if ($supplier!=''){
		$temp = str_replace(' ', '%', $supplier);
		$condition = $condition." AND (sup.supplier LIKE '%".$temp."%' OR sup.supplier_name LIKE '%".$temp."%') ";
	}

			
	$sql="SELECT khochan.product_encoder, khochan.product_name, donvi.unit_name_of_medicine, tatcakhoa.lotid, tatcakhoa.exp_date, tatcakhoa.typeput,
			tatcakhoa.number, typemed.type_name_of_med, grp.name_sub, sup.supplier, sup.supplier_name 
		  FROM  care_med_products_main_sub1 AS tatcakhoa, 
			care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi,
			care_med_type_of_medicine AS typemed, care_med_products_main_sub AS grp, care_supplier AS sup 
		  WHERE  khochan.product_encoder=tatcakhoa.product_encoder 
			AND donvi.unit_of_medicine=khochan.unit_of_medicine 
			AND grp.type_of_med=typemed.type_of_med 
			AND khochan.id_sub=grp.id 
			AND khochan.care_supplier=sup.supplier  
			".$condition."  
			ORDER BY khochan.product_name ";
	
	//echo $sql;
	
	if($ergebnis=$db->Execute($sql))
	{
		echo '<tr align="center">
				<th width="15%"><font color="#5f88be">'.$LDMedipotID.'</td>
				<th width="20%"><font color="#5f88be">'.$LDMedipotName.'</td>
				<th width="10%"><font color="#5f88be">'.$LDLotID.'</td>
				<th width="10%"><font color="#5f88be">'.$LDInventoryKC.'</td>
				<td width="10%"><font color="#5f88be"><b>'.$LDUseFor.'</b></td>		
				<th width="15%"><font color="#5f88be">'.$LDGroupMedipot.'</td>
				<th width="15%"><font color="#5f88be">'.$LDSupplier.'</td>
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
			
			switch($item['typeput']){
				case '0': $dang = 'BHYT'; break;
				case '1': $dang = 'KP'; break;
				case '2': $dang = 'CBTC'; break;
			}
			/*if($item['ward_nr']==0)
				$temp_ward_name=$LDAllWard;
			else{
				require_once($root_path.'include/care_api_classes/class_ward.php');
				$Ward = new Ward;
				$wardinfo = $Ward->getWardInfo($item['ward_nr']);
				$temp_ward_name=$wardinfo["name"];
			}*/
			
			echo '<tr bgcolor="'.$bgc.'"><td>'.$item['product_encoder'].'</td>';
			echo '<td><b>'.$item['product_name'].'</b></td>';
			echo '<td>'.$item['lotid'].'</td>';
			echo '<td align="center">'.$item['number'].'</td>';
			echo '<td>'.$dang.'</td>';
			echo '<td>'.$item['name_sub'].'</td>';
			echo '<td>'.$item['supplier'].'</td>';
			echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['product_name'].'\',\''.$item['product_lot_id'].'\');"></td></tr>';
		}
	}
	
	
	
		
?>