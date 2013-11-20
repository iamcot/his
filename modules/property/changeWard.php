<?php
	require('./roots.php');
	require($root_path.'include/core/inc_environment_global.php');
	/* Load the ward object */
	require_once($root_path.'include/care_api_classes/class_ward.php');

	$ward_nr = $_POST['ward_nr']; 
	$room_nr = $_POST['room_nr']; 
	$ward_obj=new Ward(ward_nr);
	$roomitems = array('nr', 'room_nr');
	$roomlistdata = $ward_obj->getAllItemsActiveRoomsInfo($roomitems, $ward_nr);
	$sTemp = '<option value="-1"> </option>';
	if($roomlistdata != false){
		while($row=$roomlistdata->FetchRow()){
			$sTemp .= "<option value='".$row[nr]."' ".($row[nr]==$room_nr?"selected":"")."> ".$row[room_nr]." </option>";
		}
	} else echo "dberror";
	echo $sTemp;
?>