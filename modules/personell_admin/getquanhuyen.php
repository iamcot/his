<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_address.php');


	$citytown_name=$_GET["citytown_name"];
	
		$sql="SELECT name,nr FROM care_address_quanhuyen WHERE citytown_id='".$citytown_name."'";
		echo '<select style="width:96%;" id="addr_quanhuyen_name"  name="addr_quanhuyen_nr" onclick="showXaphuong()" >';
		$buf=$db->Execute($sql);
		if($buf->RecordCount()){
			while($buf2=$buf->FetchRow()){
			echo '<option  value="'.$buf2['nr'].'">'.$buf2['name'].'</option>';
			}
		}
			echo '</select>';
?>
