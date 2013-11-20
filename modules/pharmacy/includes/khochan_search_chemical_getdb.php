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
//    if(!isset($name_ger_chemical))
//            $type_chemical=$_GET["type_chemical"];
    if(!isset($group_chemical))
            $group_chemical=$_GET["group_chemical"];
    if(!isset($supplier))
            $supplier=$_GET["supplier"];
		
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
    $sql="SELECT chemical.product_encoder, chemical.product_name, chemical.available_number, 
                grp.chemical_group_name, sup.supplier, sup.supplier_name
            FROM care_chemical_products_main AS chemical, care_chemical_group AS grp, care_supplier AS sup 
            WHERE chemical.care_supplier=sup.supplier AND chemical.chemical_generic_drug_id=grp.chemical_group_id
            ".$condition."";
	
    if($ergebnis=$db->Execute($sql))
    {
        echo '<tr align="center">
                <th width="5%"><font color="#5f88be">'.$LDChemicalID.'</td>
                <th width="20%"><font color="#5f88be">'.$LDChemicalName1.'</td>
                <th width="10%"><font color="#5f88be">'.$LDCabinetChemicalSum.'</td>
                <th width="15%"><font color="#5f88be">'.$LDGroupChemical.'</td>
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

            echo '<tr bgcolor="'.$bgc.'"><td>'.$item['product_encoder'].'</td>';
            echo '<td><b>'.$item['product_name'].'</b></td>';
            echo '<td align="center">'.$item['available_number'].'</td>';
            echo '<td>'.$item['chemical_group_name'].'</td>';
            echo '<td>'.$item['supplier'].'</td>';
            echo '<td><input type="button" class="mybutton" onClick="sendValue(\''.$item['product_name'].'\',\''.$item['product_encoder'].'\');"></td></tr>';
        }
    }
		
?>