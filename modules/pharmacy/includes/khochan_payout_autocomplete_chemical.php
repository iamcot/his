<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$search=$_GET["search"];
$encoder=$_GET["encoder"];
$lotid=$_GET["lotid"];
$response="";

switch($mode){
	case 'auto': 
//            $sql="SELECT khochan.*, donvi.unit_name_of_chemical, nhap.lotid, nhap.exp_date, nhap.number 
//                            FROM care_chemical_products_main AS khochan, care_chemical_products_main_sub AS main_sub , care_chemical_unit_of_medicine AS donvi  
//                            WHERE donvi.unit_of_chemical=khochan.unit_of_chemical 
//                            AND nhap.product_encoder=khochan.product_encoder
//                            AND (khochan.product_name LIKE '".$search."%' or khochan.product_name LIKE '%".$search."%') 
//                            ORDER BY product_name LIMIT 15 ";
            $sql="SELECT khochan.*, main_sub.*, main_sub.price AS cost, donvi.unit_name_of_chemical     
                    FROM care_chemical_products_main AS khochan, care_chemical_products_main_sub AS main_sub, care_chemical_unit_of_medicine AS donvi  
                    WHERE donvi.unit_of_chemical=khochan.unit_of_chemical 
                    AND khochan.product_encoder=main_sub.product_encoder 
                    AND (khochan.product_name LIKE '".$search."%' or khochan.product_name LIKE '% ".$search."%') 
                    ORDER BY product_name LIMIT 15 ";
            if($result = $db->Execute($sql)){
                    $n=$result->RecordCount();
                    $item_id=""; $item_value=""; 
                    if ($n){
                            echo '<ul>';
                            for ($i=0;$i<$n;$i++)
                            {
                                    $chemical=$result->FetchRow();
                                    $expdate= formatDate2Local($chemical['exp_date'],'dd/mm/yyyy');

                                    echo '<li id="'.$k.'@#'.$chemical["product_encoder"].'">';
                                    echo '<div><font color="#FF0000">'.$chemical["product_name"].'</font></div>';
                                    echo '<span>-- '.$chemical["lotid"].'-- '.$expdate.'<br>-- '.$chemical["price"].' vnd/'.$chemical["unit_name_of_chemical"].'-- '.$chemical["number"].'<br>&nbsp;</span></li>';
                                    
                            }
                            echo '</ul>';
                            //$response = $item_id.'@'.$item_value;
                    }
            }
            break;
			
	case 'filldata': 
//            $sql="SELECT khochan.*, donvi.unit_name_of_chemical,nhap.lotid,nhap.exp_date   
//                    FROM care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi, care_chemical_put_in AS nhap  
//                    WHERE donvi.unit_of_chemical=khochan.unit_of_chemical 
//                    AND nhap.product_encoder=khochan.product_encoder
//                    AND product_name='".$search."'";
            $sql="SELECT khochan.*, main_sub.*, main_sub.price AS cost, donvi.unit_name_of_chemical     
                    FROM care_chemical_products_main AS khochan, care_chemical_products_main_sub AS main_sub, care_chemical_unit_of_medicine AS donvi   
                    WHERE donvi.unit_of_chemical=khochan.unit_of_chemical 	
                    AND khochan.product_encoder=main_sub.product_encoder 
                    AND main_sub.lotid='".$lotid."'
                    AND product_name='".$search."'  ";
            if($result = $db->Execute($sql)){
                    $n=$result->RecordCount();
                    if ($n){
                            $chemical=$result->FetchRow();
                            $expdate= formatDate2Local($chemical['exp_date'],'dd/mm/yyyy');
                            $response=$chemical["product_encoder"].'@#'.$chemical["cost"].'@#'.$chemical["unit_name_of_chemical"].'@#'.$chemical["number"].'@#'.$chemical["lotid"].'@#'.$expdate;
                            //$response=$chemical["product_encoder"].'@#'.$chemical["price"].'@#'.$chemical["unit_name_of_chemical"].'@#'.$chemical["number"].'@#'.$chemical["lotid"].'@#'.$chemical["exp_date"];//$exp_date;
                    }
            }
            echo $response;
            break;
				
	default: $response="";
}
		

?>