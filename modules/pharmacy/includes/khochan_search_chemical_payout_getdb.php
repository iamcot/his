<?php	
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    $root_path='../../../';
    require($root_path.'include/core/inc_environment_global.php');
    $lang='vi';
    define('NO_CHAIN',1);
    define('LANG_FILE','pharma.php');
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');

    if(!isset($name_chemical))
            $name_chemical=$_GET["name_chemical"];
    if(!isset($group_chemical))
            $group_chemical=$_GET["group_chemical"];
    if(!isset($supplier))
            $supplier=$_GET["supplier"];
    if(!isset($typeput))
            $typeput=$_GET["typeput"];			
		
    $condition='';
    if ($name_chemical!=''){
            $temp = str_replace(' ', '%', $name_chemical);
            $condition = " AND ((chemical.product_name LIKE '".$temp."%') OR (chemical.product_name LIKE '% ".$temp."%') ) ";
    }
    if ($group_chemical!=''){
            $temp = str_replace(' ', '%', $group_chemical);
            $condition = $condition." AND (grp.chemical_group_name LIKE '".$temp."%' OR grp.chemical_group_name LIKE '% ".$temp."%' ) ";
    }
    if ($supplier!=''){
            $temp = str_replace(' ', '%', $supplier);
            $condition = $condition." AND (sup.supplier LIKE '".$temp."%' OR sup.supplier LIKE '% ".$temp."%' OR sup.supplier_name LIKE '".$temp."%' OR sup.supplier_name LIKE '% ".$temp."%') ";
    }
	if ($typeput!=''){
		$condition = $condition." AND main_sub.typeput='".$typeput."' ";
	}		
//    $sql="SELECT chemical.product_encoder, chemical.product_name, chemical.available_number, 
//                grp.chemical_group_name, sup.supplier, sup.supplier_name
//            FROM care_chemical_products_main AS chemical, care_chemical_products_main_sub AS main_sub, care_chemical_group AS grp, care_supplier AS sup 
//            WHERE chemical.care_supplier=sup.supplier AND chemical.chemical_generic_drug_id=grp.chemical_group_id
//            ".$condition."";
    $sql="SELECT main_sub.*,chemical.product_name, chemical.available_number, 
                grp.chemical_group_name, sup.supplier, sup.supplier_name 
		FROM care_chemical_products_main AS chemical, care_chemical_products_main_sub AS main_sub, care_chemical_group AS grp, care_supplier AS sup
		WHERE chemical.care_supplier=sup.supplier
		AND chemical.product_encoder=main_sub.product_encoder 
		AND chemical.chemical_generic_drug_id=grp.chemical_group_id 
		".$condition."";
//    echo $sql;
	if($ergebnis=$db->Execute($sql))
	{
            echo '<tr align="center">
                    <th width="5%"><font color="#5f88be">'.$LDChemicalID.'</td>
                    <th width="20%"><font color="#5f88be">'.$LDChemicalName.'</td>
                    <td width="10%"><font color="#5f88be"><b>'.$LDNumber1.'</b></td>
                    <td width="10%"><font color="#5f88be"><b>'.$LDLotID1.'</b></td>
                    <td width="10%"><font color="#5f88be"><b>'.$LDExpDate1.'</b></td>
                    <td width="25%"><font color="#5f88be"><b>'.$LDGroupChemical.'</b></td>
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
                echo '<td align="center">'.formatDate2Local($item['exp_date'],'dd/mm/yyyy').'</td>';
                echo '<td>'.$item['chemical_group_name'].'</td>';
                echo '<td>'.$item['supplier'].'</td>';
                echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['product_name'].'\',\''.$item['product_encoder'].'\',\''.$item['lotid'].'\');"></td></tr>';
            }
	}
	
	
	
		
?>