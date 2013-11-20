<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$root_path='../../../';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();

$total = $_POST['total_items'];
$date_issue	= formatDate2STD($_POST['dateissue'],'dd/mm/yyyy');

//total_items, enc_nr$i, encoder$i, number$i, pres_id$i

$patmenu="../manage_pharma/medicine_use_patient.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid']."&ward_nr=".$_POST['ward_nr'].'&dept_nr='.$_POST['dept_nr'];

include_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($Pres)) $Pres = new Prescription;

if($isdelete=='delete'){
	$Pres->setDongPhatThuoc($pres_id);
}else{
	for($i=1;$i<=$total;$i++){
		$enc_dx = 'enc_nr'.$i;
		$encoder_dx = 'encoder'.$i;
		$number_dx='number'.$i;
		$pres_dx='pres_id'.$i;
		if($$number_dx>0)
			$Pres->IssueMedicineForPatient($$enc_dx, $date_issue, $$encoder_dx, $$number_dx, $$pres_dx);
	}
}
		
if($no_redirect==''){
	header("Location:".$patmenu);
	exit;
}
else{
	echo $no_redirect;
} 	



?>