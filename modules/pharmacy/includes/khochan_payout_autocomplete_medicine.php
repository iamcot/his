<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$search=$_GET["search"];
$encoder=$_GET["encoder"];
$response="";

switch($mode){
	case 'auto': 
				$sql="SELECT khochan.*, main_sub.*, main_sub.price AS cost, donvi.unit_name_of_medicine     
						FROM care_pharma_products_main AS khochan, care_pharma_products_main_sub AS main_sub, care_pharma_unit_of_medicine AS donvi  
						WHERE donvi.unit_of_medicine=khochan.unit_of_medicine 
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
							$medicine=$result->FetchRow();
							$expdate= formatDate2Local($medicine['exp_date'],'dd/mm/yyyy');
							
							echo '<li id="'.$k.'@#'.$medicine["product_encoder"].'">';
							echo '<div><font color="#FF0000">'.$medicine["product_name"].'</font></div>';
							echo '<span>-- '.$medicine["lotid"].'-- '.$expdate.'<br>-- '.$medicine["cost"].' vnd/'.$medicine["unit_name_of_medicine"].'-- '.$medicine["number"].'<br>&nbsp;</span></li>';

						}
						echo '</ul>';
						//$response = $item_id.'@'.$item_value;
					}
				}
				break;
			
	case 'filldata': 
				$sql="SELECT khochan.*, main_sub.*, main_sub.price AS cost, donvi.unit_name_of_medicine     
					FROM care_pharma_products_main AS khochan, care_pharma_products_main_sub AS main_sub, care_pharma_unit_of_medicine AS donvi  
					WHERE donvi.unit_of_medicine=khochan.unit_of_medicine 	
					AND khochan.product_encoder=main_sub.product_encoder 
					AND product_name='".$search."'  ";
				//echo $sql;
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					if ($n){
						$medicine=$result->FetchRow();
						$expdate= formatDate2Local($medicine['exp_date'],'dd/mm/yyyy');
						
						$response=$medicine["product_encoder"].'@#'.$medicine["cost"].'@#'.$medicine["unit_name_of_medicine"].'@#'.$medicine["number"].'@#'.$medicine["lotid"].'@#'.$expdate;
					}
				}
				echo $response;
				break;
				
	default: $response="";
}
		

?>