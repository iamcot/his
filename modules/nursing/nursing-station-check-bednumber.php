<?php
	require('./roots.php');
	require($root_path.'include/core/inc_environment_global.php');
	/* Load the ward object */
	require_once($root_path.'include/care_api_classes/class_ward.php');
		
	$dept_nr = $_POST['dept_nr']; 
	$ward_nr = $_POST['ward_nr']; 
	$room_nr = $_POST['room_nr'];
	$beds = $_POST['beds'];
	$ward_obj=new Ward($ward_nr);
	if($ward_obj->RoomExists($room_nr)){
		$patients = $ward_obj->countPatients($room_nr);
		if($patients == false) echo 'dberror';
		else if($beds >= $patients) echo 'ok';
		else echo $patients;
	}else echo 'dberror';
?>