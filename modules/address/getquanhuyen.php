<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_address.php');


	$citytown_id=$_GET["citytown_id"];
	
		$sql="SELECT name,nr FROM care_address_quanhuyen WHERE citytown_id='".$citytown_id."'";
		echo '<tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font>'.$LDQuanHuyen.': </td>';
	echo'<td class="adm_input"><select  tabindex=7 name="quanhuyen_id" id="quanhuyen_id"><option value="-1">Chọn QH</option>';
	
	 $buf=$db->Execute($sql);
		if($buf->RecordCount()){
			while($buf2=$buf->FetchRow()){
			echo '<option value="'.$buf2['nr'].'"  selected >' . $buf2['name'].'</option>';
			
			}
		
		}
   echo '</select></td></tr> ';
		
?>
