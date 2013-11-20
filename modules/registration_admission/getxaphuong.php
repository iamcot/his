<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_address.php');


	$quanhuyen_name=$_GET["quanhuyen_name"];
	
		$sql="SELECT name,nr FROM care_address_phuongxa WHERE quanhuyen_id='".$quanhuyen_name."'";

		$buf=$db->Execute($sql);
		if($buf->RecordCount()){
			while($buf2=$buf->FetchRow()){
			echo '<option value="'.$buf2['nr'].'">'.$buf2['name'].'</option>';
			}
		}
		
		
?>
