<?php
	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$mnn=$_GET["mann"];
	
				
	$sql="SELECT name FROM care_type_ethnic_orig WHERE id ='".$mnn."'";
	//echo $sql;
				$temp=$db->Execute($sql);
				if($temp->RecordCount()) {
				$result=$temp->FetchRow();				
						echo $result['name'];
	         		}
				
?>