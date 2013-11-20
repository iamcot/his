<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');


	$kcb=$_GET["makcb"];
		$sql="SELECT value FROM care_config_global WHERE type='main_info_maso_kcb_bv' ";
		
		echo '<select id="traituyen" name="is_traituyen" style="width:96%">';
		$buf=$db->Execute($sql);
		$buf->RecordCount();
			$buf2=$buf->FetchRow();
			$result=explode(";",$buf2['value']);
			if(in_array($kcb,$result)) {
			echo '
			<option value="0">Không rõ</option>
			<option value="1"  selected>Đúng tuyến</option>
			<option value="2" >Trái tuyến</option>';
			}else{
			echo '
			<option value="0">Không rõ</option>
			<option value="1">Đúng tuyến</option>
			<option value="2"  selected>Trái tuyến</option>';
			}
		
	
		
	
			echo '</select>';	
		
?>