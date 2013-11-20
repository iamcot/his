<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','aufnahme.php');
	$dept_nr=$_GET["dept_nr"];
		$sql="SELECT nr,name FROM care_ward WHERE dept_nr='".$dept_nr."' and status NOT IN ('closed','deleted','hidden','inactive','void')";		
		echo '<select name="current_ward_nr" id="current_ward_nr" style="width:96%;">
		 <option value="0"></option>
		';
		$buf=$db->Execute($sql);
		if($buf->RecordCount()){
			while($buf2=$buf->FetchRow()){
			if($current_ward_nr==$buf2['nr']) $selected=' selected ';else $selected=' ';
			echo '<option value="'.$buf2['nr'].'"  '.$selected.' >' . $buf2['name'].'</option>';			
			}		
		}	
		echo '</select>';			
?>