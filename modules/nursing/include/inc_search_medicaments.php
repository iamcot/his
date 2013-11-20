<?php
$root_path = '../../../';
require($root_path.'/include/core/inc_environment_global.php');
global $db;

$dbtable='care_pharma_products_main';
$dbtablejoin='care_pharma_products_main_sub';	
extract($_POST);

/*if(!isset($tmpBestellNum))
	$tmpBestellNum = $_POST['tmpBestellNum'];*/
	
if($tmpBestellNum!='') {
	$tmpBestellNum = str_replace("_", "','", $tmpBestellNum);
	$tmpBestellNum .= "'";
	$tmpBestellNum = substr($tmpBestellNum, 2); 
}
//echo $tmpBestellNum;

# clean input data
$search=$_POST['search'];
///$db->debug=true;

switch($mode){
	case 'auto': 
				$sql=" SELECT khochan.product_encoder, khochan.product_name, khochan.unit_of_medicine, khochan.price, khochan.caution, khochan.content, khochan.component, donvi.unit_name_of_medicine, khole.product_lot_id, khole.exp_date, SUM(khole.available_number) AS allocation_temp, thuocgoc.generic_drug, thuocgoc.using_type    
						FROM care_pharma_available_product AS khole, care_pharma_generic_drug AS thuocgoc,
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  
						WHERE (khochan.product_name LIKE '".$search."%' or khochan.product_name LIKE '% ".$search."%') 	 
						AND khochan.product_encoder=khole.product_encoder 
						AND khochan.pharma_generic_drug_id=thuocgoc.pharma_generic_drug_id 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND khochan.pharma_type IN (1,3,4) ";
				if($tmpBestellNum)
					$sql .= " AND khole.product_encoder NOT IN (".$tmpBestellNum.") ";
					
				$sql .="GROUP BY khole.product_encoder 
						ORDER BY khochan.product_name LIMIT 15 "; 
				
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					$item_id=""; $item_value="";
					if ($n){
						echo '<ul>';
						for ($i=0;$i<$n;$i++)
						{
							$medicine=$result->FetchRow();
							echo '<li id="'.$medicine["product_encoder"].'">';
							echo '<div><font color="#FF0000">'.$medicine["product_name"].'</font></div>';
							echo '<span>-- '.$medicine["generic_drug"].'-- '.$medicine["content"].'-- '.$medicine["using_type"].'-- '.$medicine["allocation_temp"].'-- '.$medicine["product_lot_id"].'-- '.$medicine["price"].' vnd/'.$medicine["unit_name_of_medicine"].'<br>&nbsp;</span></li>';
						}
						echo '</ul>';
					}
				}
				break;
			
	case 'filldata': 
				$sql="	SELECT khochan.product_encoder, khochan.product_name, khochan.unit_of_medicine, khochan.price, khochan.caution, khochan.component, donvi.unit_name_of_medicine, khole.product_lot_id, khole.exp_date, SUM(khole.available_number) AS allocation_temp   
						FROM care_pharma_available_product AS khole, 
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi  				 
						WHERE khochan.product_name='".$search."' 
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