<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
//require_once($root_path.'include/care_api_classes/class_product.php');

$search=$_GET["search"];
$response="";

switch($mode){
	case 'auto': 
				$sql="SELECT khochan.product_encoder, khochan.product_name, khochan.unit_of_medicine, khole.price, khochan.caution, donvi.unit_name_of_medicine, khole.product_lot_id, khole.exp_date, SUM(khole.available_number) AS sumnumber, khole.typeput    
						FROM care_pharma_available_product AS khole, 
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  
						WHERE (khochan.product_name LIKE '".$search."%' or khochan.product_name LIKE '% ".$search."%') 	 
						AND khochan.product_encoder=khole.product_encoder AND khole.available_number>0
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND khochan.pharma_type IN (1,3,4) 
						GROUP BY khole.product_encoder, khole.typeput 
						ORDER BY khochan.product_name LIMIT 15";

				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					$item_id=""; $item_value=""; 
					if ($n){
						echo '<ul>';
						for ($i=0;$i<$n;$i++)
						{
							$medicine=$result->FetchRow();
							$medicine["exp_date"] = formatDate2Local($medicine["exp_date"],'dd/mm/yyyy');
							switch($medicine["typeput"]){
								case 0: $dang='BHYT';break;
								case 1: $dang='KP';break;
								case 2: $dang='CBTC';break;
							}
							echo '<li id="'.$k.'@#'.$medicine["product_encoder"].'">';
							echo '<div><font color="#FF0000">'.$medicine["product_name"].'</font></div>';
							echo '<span>-- '.$medicine["product_lot_id"].'-- '.$medicine["exp_date"].'<br>-- '.$medicine["price"].' vnd/'.$medicine["unit_name_of_medicine"].'-- '.$medicine["sumnumber"].'-- '.$dang.'<br>&nbsp;</span></li>';

						}
						echo '</ul>';
						//$response = $item_id.'@'.$item_value;
					}
				}
				break;
			
	case 'filldata': 
				$sql="SELECT khochan.product_encoder, khochan.product_name, khochan.unit_of_medicine, khole.price, khochan.caution, donvi.unit_name_of_medicine, khole.product_lot_id, khole.exp_date, SUM(khole.available_number) AS sumnumber   
						FROM care_pharma_available_product AS khole, 
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  				 
						WHERE khochan.product_name='".$search."' 
						AND khochan.product_encoder=khole.product_encoder khole.available_number>0
						AND donvi.unit_of_medicine=khochan.unit_of_medicine
						GROUP BY khole.product_encoder, khole.typeput";
				//echo $sql;
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					if ($n){
						$medicine=$result->FetchRow();
						if ($medicine["available_number"]=="")
							$medicine["available_number"]="0";
						$medicine["exp_date"] = formatDate2Local($medicine["exp_date"],'dd/mm/yyyy');
						
						$response=$medicine["product_encoder"].'@#'.$medicine["sumnumber"].'@#'.$medicine["unit_name_of_medicine"].'@#'.$medicine["price"].'@#'.$medicine["product_lot_id"].'@#'.$medicine["exp_date"];	
					}
				}
				echo $response;
				break;
				
	default: $response="";
}

?>