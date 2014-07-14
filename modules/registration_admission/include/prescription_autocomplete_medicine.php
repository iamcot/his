<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
//require_once($root_path.'include/care_api_classes/class_product.php');

$search=$_GET["search"];
$response="";
$typeput=$_GET["typeput"];
switch($mode){
	case 'auto': 
				/*$sql=" SELECT khole.product_encoder, khochan.product_name, khochan.unit_of_medicine, khole.price, khochan.caution, khochan.content, khochan.component, donvi.unit_name_of_medicine, khole.available_product_id, khole.product_lot_id, khole.exp_date, sum(khole.available_number) AS allocation_temp, thuocgoc.generic_drug, thuocgoc.using_type
						FROM care_pharma_available_product AS khole, care_pharma_generic_drug AS thuocgoc,
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  
						WHERE (khochan.product_name LIKE '".$search."%' or khochan.product_name LIKE '% ".$search."%') 	 
						AND khochan.product_encoder=khole.product_encoder 
						AND khochan.pharma_generic_drug_id=thuocgoc.pharma_generic_drug_id 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND khochan.pharma_type IN (1,3,4) AND khole.available_number>0
						GROUP BY khole.product_encoder, khole.price  
						ORDER BY khochan.product_name ASC, khochan.price DESC  
						LIMIT 20 ";   */
                //nang
				  $sql="SELECT khole.product_encoder, khochan.product_name, khochan.unit_of_medicine, khole.price, khochan.caution, khochan.content, khochan.component, donvi.unit_name_of_medicine, khole.available_product_id, khole.product_lot_id, khole.exp_date, SUM(khole.available_number) AS allocation_temp, thuocgoc.generic_drug, thuocgoc.using_type
						FROM care_pharma_available_product AS khole, care_pharma_generic_drug AS thuocgoc,
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
						WHERE (khochan.product_name LIKE '".$search."%' OR khochan.product_name LIKE '%".$search."%')
						AND khochan.product_encoder=khole.product_encoder
						AND khochan.pharma_generic_drug_id=thuocgoc.pharma_generic_drug_id
						AND donvi.unit_of_medicine=khochan.unit_of_medicine
						AND khochan.pharma_type IN (1,3,4) AND khole.available_number>0
                        AND khole.typeput=$typeput
						GROUP BY khole.product_encoder, khole.price
						ORDER BY khochan.product_name ASC, khochan.price DESC,  khole.exp_date ASC
						LIMIT 20";
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					$item_id=""; $item_value="";
					if ($n){
						echo '<ul>';
						for ($i=0;$i<$n;$i++)
						{
							$medicine=$result->FetchRow();
							echo '<li id="'.$k.'@#'.$medicine["product_encoder"].'@#'.$medicine["available_product_id"].'">';
							echo '<div><font color="#FF0000">'.$medicine["product_name"].'</font></div>';
							echo '<span>-- '.$medicine["price"].' vnd/'.$medicine["unit_name_of_medicine"].'-- '.$medicine["allocation_temp"].'-- '.$medicine["generic_drug"].'-- '.$medicine["content"].'-- '.$medicine["using_type"].'<br>&nbsp;</span></li>';
						}
						echo '</ul>';
					}
				}
				break;
			
	case 'filldata': 
				$avai_id =$_GET["avai_id"];
				$sql="	SELECT khole.product_encoder, khochan.product_name, khochan.unit_of_medicine, khole.price, khochan.caution, khochan.component, donvi.unit_name_of_medicine, khole.product_lot_id, khole.exp_date, khole.available_number AS allocation_temp   
						FROM care_pharma_available_product AS khole, 
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  				 
						WHERE khole.available_product_id='".$avai_id."' 
						AND khochan.product_encoder=khole.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine"; 
				
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					if ($n){
						$medicine=$result->FetchRow();
						if ($medicine["allocation_temp"]=="")
							$medicine["allocation_temp"]="0";
						$response=$medicine["product_encoder"].'@#'.$medicine["allocation_temp"].'@#'.$medicine["unit_name_of_medicine"].'@#'.$medicine["price"].'@#'.$medicine["component"].'@#'.$medicine["caution"];	
					}else{
						//$response='@#0@#viên@#0@#@#';
					}
				}
				echo $response;
				break;
				
	default: $response="";
}
		
//echo $response;
?>