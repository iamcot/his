<?php	
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    $root_path='../../../';
    require($root_path.'include/core/inc_environment_global.php');
    $lang='vi';
    define('NO_CHAIN',1);
    define('LANG_FILE','pharma.php');
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    if(!isset($name_chemical))
            $name_chemical=$_GET["name_chemical"];
    if(!isset($group_chemical))
            $group_med=$_GET["group_chemical"];
    if(!isset($supplier))
            $supplier=$_GET["supplier"];
    if(!isset($typeput))
        $typeput=$_GET["typeput"];
		
    $condition='';
    if ($name_chemical!=''){
            $temp = str_replace(' ', '%', $name_chemical);
            $condition = " AND (chemical.product_name LIKE '%".$temp."%' ) ";
    }
    if ($group_chemical!=''){
            $temp = str_replace(' ', '%', $group_chemical);
            $condition = $condition." AND (grp.chemical_group_name LIKE '%".$temp."%' ) ";
    }
    if ($supplier!=''){
            $temp = str_replace(' ', '%', $supplier);
            $condition = $condition." AND (sup.supplier LIKE '%".$temp."%' OR sup.supplier_name LIKE '%".$temp."%') ";
    }
	if ($typeput!=''){
		$condition = $condition." AND khole.typeput='".$typeput."' ";
	}	
    $sql="SELECT chemical.product_encoder, chemical.product_name, khole.available_number, khole.product_lot_id,
                grp.chemical_group_name, sup.supplier, sup.supplier_name
            FROM care_chemical_products_main AS chemical, care_chemical_group AS grp, care_supplier AS sup, care_chemical_available_product AS khole
            WHERE chemical.care_supplier=sup.supplier
            AND chemical.product_encoder=khole.product_encoder
            AND chemical.chemical_generic_drug_id=grp.chemical_group_id
            ".$condition." 
            ORDER BY product_name";
//    echo $sql;
	if($ergebnis=$db->Execute($sql))
	{
		echo '<tr align="center">
				<td width="15%"><font color="#5f88be"><b>'.$LDChemicalID.'</b></td>
				<td width="20%"><font color="#5f88be"><b>'.$LDChemicalName1.'</b></td>
				<td width="10%"><font color="#5f88be">'.$LDLotID.'</td>
				<td width="10%"><font color="#5f88be"><b>'.$LDInventory.'</b></td>
				<td width="15%"><font color="#5f88be"><b>'.$LDGroupChemical.'</b></td>
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
			echo '<td>'.$item['product_lot_id'].'</td>';
			echo '<td align="center">'.$item['available_number'].'</td>';
			echo '<td>'.$item['chemical_group_name'].'</td>';
			echo '<td>'.$item['supplier'].'</td>';
			echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['product_name'].'\',\''.$item['product_encoder'].'\');"></td></tr>';
		}
	}
	
	
	
		
?>