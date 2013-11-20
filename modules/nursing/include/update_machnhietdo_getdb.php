<?php	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
$lang='vi';
define('NO_CHAIN',1);
require_once ($root_path . 'include/core/inc_front_chain_lang.php');
include_once ($root_path . 'include/core/inc_date_format_functions.php') ;

//ghi log
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();

$thisfile=basename(__FILE__);

	if(!isset($nr))
		$name_med=$_GET["nr"];
	if(!isset($mode))
		$name_med=$_GET["mode"];
	if(!isset($ttime))
		$name_med=$_GET["ttime"];
	if(!isset($tdata))
		$name_med=$_GET["tdata"];	
		
if($mode=='update' && $nr!='' && $ttime!='' && $tdata!=''){	

	$sql="UPDATE care_encounter_measurement 
			SET 
			msr_time = '".$ttime."' ,  
			value = '".$tdata."' , 
			modify_id = '".$_SESSION['sess_user_name']."' , 
			modify_time = '".date('Y-m-d H:i:s')."' 
			WHERE
			nr = '".$nr."'";
	

}
else if($mode=='delete' && $nr!=''){
	$sql="DELETE FROM care_encounter_measurement WHERE nr='".$nr."'";
}

$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));

	
if($sql!='' && $ergebnis=$db->Execute($sql))
{
	echo 'ok';		
}
else echo 'not';
	
	
	
		
?>