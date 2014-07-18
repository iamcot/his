<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
//require_once($root_path.'include/care_api_classes/class_product.php');

$typeput=$_GET["typeput"];
$search=$_GET["search"];
$response="";
if($typeput==0)
    $finding="AND khole.typeput=$typeput";
else $finding="";
switch($mode){
	case 'auto':
				$sql=" SELECT khochan.product_encoder, khochan.product_name, khochan.unit_of_medicine, khole.price, khochan.caution, donvi.unit_name_of_medicine, khole.lotid, khole.exp_date, SUM(khole.number) AS allocation_temp
						FROM care_med_products_main_sub1 AS khole,
						care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
						WHERE (khochan.product_name LIKE '".$search."%' or khochan.product_name LIKE '% ".$search."%')
						AND khochan.product_encoder=khole.product_encoder
						AND donvi.unit_of_medicine=khochan.unit_of_medicine
						AND khochan.pharma_type IN (5,6,7)
                        ".$finding."
						GROUP BY khole.product_encoder
						ORDER BY khochan.product_name LIMIT 15 ";

				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					$item_id=""; $item_value="";
					if ($n){
						echo '<ul>';
						for ($i=0;$i<$n;$i++)
						{
							$medicine=$result->FetchRow();
							echo '<li id="'.$k.'@#'.$medicine["product_encoder"].'">';
							echo '<div><font color="#FF0000">'.$medicine["product_name"].'</font></div>';
							echo '<span>-- '.$medicine["price"].' vnd/'.$medicine["unit_name_of_medicine"].'-- '.$medicine["allocation_temp"].'-- '.$medicine["using_type"].'<br>&nbsp;</span></li>';
						}
						echo '</ul>';
					}
				}
				break;
			
	case 'filldata':
                $avai_id =$_GET["avai_id"];
				$sql="	SELECT khochan.product_encoder, khochan.product_name, khochan.unit_of_medicine, khochan.price, khochan.caution, donvi.unit_name_of_medicine, khole.lotid, khole.exp_date, SUM(khole.number) AS allocation_temp
						FROM care_med_products_main_sub1 AS khole,
						care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi  				 
						WHERE khole.id='".$avai_id."'
						AND khochan.product_encoder=khole.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine
						GROUP BY khole.product_encoder"; 
				
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					if ($n){
						$medicine=$result->FetchRow();
						if ($medicine["allocation_temp"]=="")
							$medicine["allocation_temp"]="0";
						$response=$medicine["product_encoder"].'@#'.$medicine["allocation_temp"].'@#'.$medicine["unit_name_of_medicine"].'@#'.$medicine["price"].'@#'.$medicine["component"].'@#'.$medicine["caution"];	
					}
				}
				echo $response;
				break;
				
	default: $response="";
}
		
//echo $response;
?>