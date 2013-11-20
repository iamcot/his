<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
//require_once($root_path.'include/care_api_classes/class_product.php');

$search=$_GET["search"];
$response="";
switch($mode){
	case 'auto': 
				$sql=" SELECT diagnosis_code,description    
						FROM care_icd10_vi 
						WHERE (description LIKE '".$search."%' or description LIKE '%".$search."%') 	 
						
						ORDER BY description LIMIT 15 "; 
				//echo $sql;
				if($result = $db->Execute($sql)){
					$n=$result->RecordCount();
					$item_id=""; $item_value="";
					if ($n){
						echo '<ul>';
						for ($i=0;$i<$n;$i++)
						{
							$medicine=$result->FetchRow();
							echo '<li id="'.$medicine["diagnosis_code"].'">';
							echo '<div><font color="#FF0000">'.$medicine["description"].'</font></div>';
							echo '</li>';
						}
						echo '</ul>';
					}
				}
				break;
			
	case 'filldata': 
				$avai_id =$_GET["avai_id"];
					$sql=" SELECT diagnosis_code,description    
						FROM care_icd10_vi 
						WHERE (description LIKE '".$search."%' or description LIKE '%".$search."%') 	 
						
						ORDER BY description LIMIT 15 "; 
				
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