<?php
	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$diagnosis_code=$_GET["diagnosis_code"];
	
				
	$sql="SELECT diagnosis_code,description FROM care_icd10_vi WHERE diagnosis_code ='".$diagnosis_code."'";
				$temp=$db->Execute($sql);
				if($temp->RecordCount()) {
				$result=$temp->FetchRow();
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					
						echo $result['diagnosis_code'].' '.$result['description'];
	         		}
				
?>