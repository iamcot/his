<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$search=$_GET["search"];
$response="";
$dept_ward = '';
if ($dept_nr!='')
	$dept_ward = " AND taikhoa.department='".$dept_nr."' ";
if ($ward_nr!='')
	$dept_ward.= " AND taikhoa.ward_nr='".$ward_nr."' ";

switch($mode){
	case 'auto': 
				$sql="SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.price, tatcakhoa.product_lot_id, tatcakhoa.exp_date, taikhoa.available_number, taikhoa.typeput    
                FROM care_med_available_department AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medipot AS donvi, care_ward 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					".$dept_ward." 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
                    AND taikhoa.available_number>0 	
					AND (product_name LIKE '".$search."%' or product_name LIKE '% ".$search."%') 
                ORDER BY product_name LIMIT 15";
				
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					$item_id=""; $item_value="";
					if ($n){
						echo '<ul>';
						for ($i=0;$i<$n;$i++)
						{
							$medicine=$result->FetchRow();
							switch($medicine["typeput"]){
								case 0: $dang='BHYT';break;
								case 1: $dang='KP';break;
								case 2: $dang='CBTC';break;
							}	
							$medicine["exp_date"] = formatDate2Local($medicine["exp_date"],'dd/mm/yyyy');
							echo '<li id="'.$k.'@#'.$medicine["product_encoder"].'">';
							echo '<div><font color="#FF0000">'.$medicine["product_name"].'</font></div>';
							echo '<span>-- '.$medicine["product_lot_id"].'-- '.$medicine["exp_date"].'<br>-- '.$medicine["price"].' vnd/'.$medicine["unit_name_of_medicine"].'-- '.$medicine["available_number"].'-- '.$dang.'<br>&nbsp;</span></li>';

						}
						echo '</ul>';
					}
				}
				break;
			
	case 'filldata': 
				$sql="SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.price, tatcakhoa.product_lot_id, tatcakhoa.exp_date, taikhoa.available_number   
                FROM care_med_available_department AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medipot AS donvi, care_ward 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					".$dept_ward." 
                    AND khochan.product_encoder=tatcakhoa.product_encoder  
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 	
					AND product_name='".$search."'  ";
					//AND tatcakhoa.product_lot_id='".$lotid."'";
				
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					if ($n){
						$medicine=$result->FetchRow();
						if ($medicine["available_number"]=="")
							$medicine["available_number"]="0";
						$medicine["exp_date"] = formatDate2Local($medicine["exp_date"],'dd/mm/yyyy');
						
						$response=$medicine["product_encoder"].'@#'.$medicine["available_number"].'@#'.$medicine["unit_name_of_medicine"].'@#'.$medicine["price"].'@#'.$medicine["product_lot_id"].'@#'.$medicine["exp_date"];	
					}
				}
				echo $response;
				break;
				
	default: $response="";
}
		

?>