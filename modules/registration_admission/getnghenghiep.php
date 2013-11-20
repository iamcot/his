<?php
	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$mnn=$_GET["mann"];
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');	
	$sql="SELECT name FROM care_code_job WHERE id ='".$mnn."'";
	//echo $sql;
				$temp=$db->Execute($sql);
				if($temp->RecordCount()) {
				$result=$temp->FetchRow();				
						echo $$result['name'];
	         		}
				
?>