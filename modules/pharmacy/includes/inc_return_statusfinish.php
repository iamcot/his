<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
include_once($root_path.'include/care_api_classes/class_product.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();
if(!isset($Product)) $Product=new Product;
if(!isset($Cabinet)) $Cabinet=new CabinetPharma;
$thisfile=basename(__FILE__);

//report_id, user_accept, dept_nr, ward_nr, typeput 

 
$receive_med = array_combine($medicine_nr, $receive);
$ix=0;
foreach ($medicine_nr AS $nr) 
{ 
	$ix++; $dxcost = 'cost'.$ix;
	if($medicine = $Cabinet->getMedicineInReturn($nr)){
		$encoder = $medicine['product_encoder'];
		$lotid = $medicine['product_lot_id'];
        $available_product_id=$medicine['available_product_id'];
		$number = $receive_med[$nr];

		#Minus number of medicine in care_pharma_available_department (available_number)
		$Cabinet->updateMedicineAvaiDept($encoder, $available_product_id, $dept_nr, $ward_nr, $number,'-', $typeput);
		
		$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
		
		#Change number of medicine in care_pharma_available_product (available_number)
		$Product->updateMedicineAvaiProduct($encoder, $available_product_id, $number,'+', $typeput);
		
		$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Product->getLastQuery(), date('Y-m-d H:i:s'));
			
		#Insert in care_pharma_department_archive, return, get_use=0
		$Cabinet->insertArchive($dept_nr, $ward_nr, $encoder, $available_product_id, '0', $number, $$dxcost, 0, 0, 0, $report_id, 0, $user_accept, $typeput);
		
		
	} else {
		$no_redirect = $Cabinet->getLastQuery();
		break;
	}
} 


if(!$no_redirect){
	$Cabinet->setInfoPersonWhenReturn($report_id,$user_accept);
	$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
	$Cabinet->setReturnStatusFinish($report_id,'1');
}


#Go back to previous page

$patmenu="../pharma_request_medicine_return.php".URL_REDIRECT_APPEND."&target=$target&pid=".$_SESSION['sess_pid']."&user_origin=".$user_origin;



if(!$no_redirect){
	header("Location:".$patmenu);
	exit;
}
else{
	echo $no_redirect;
} 

?>