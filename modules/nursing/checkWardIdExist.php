<?php
	require('./roots.php');
	require($root_path.'include/core/inc_environment_global.php');
	$local_user='ck_edv_user';
	define('NO_2LEVEL_CHK',1);
	require_once($root_path.'include/core/inc_front_chain_lang.php');
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$ward=new Ward;
	
	$wardid = $_GET['wardid']; 
	$dept_nr = $_GET['dept_nr']; 
	
	if($ward->checkWardExist($dept_nr, "#@     @#", $wardid)) echo "1";
	else echo "0";
?>