<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
$top_dir='modules/pharmacy/includes';
require_once($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/care_api_classes/class_prescription.php');
include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
include_once($root_path.'include/care_api_classes/class_product.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();
if(!isset($Product)) $Product=new Product;
if(!isset($Cabinet)) $Cabinet=new CabinetPharma;
//pres_id, radiovalue

$thisfile=basename(__FILE__);

#Change Prescription Status (update status_finish='1')
$Pres = new Prescription;
if ($res_dept_ward = $Pres->getChemicalDeptWard($pres_id)){
	$dept = $res_dept_ward['dept_nr']; 
	$ward = $res_dept_ward['ward_nr'];
}
if ($temp = $Pres->getChemicalTypePut($pres_id)){
	$typeput=$temp['typeput'];
}

$receive_med = array_combine($medicine_nr, $receive);
//Chi tac dong tren kho le va tu thuoc
foreach ($medicine_nr AS $nr) 
{ 
	if($Pres->setReceiveChemicalInPres($nr,$receive_med[$nr])){
		
		if($res_encoder = $Pres->getEncoderChemical($nr))
			$encoder = $res_encoder['product_encoder'];	
		//echo $encoder."<br>";
		unset($list_lotid_get);
		$list_lotid_get = $Product->getListChemicalLotID($encoder, $receive_med[$nr], $typeput);		 //avai product
		
		foreach ($list_lotid_get as $key => $value) {			
			
			#Change number of medicine in care_chemical_available_product (available_number)
			if($Product->updateChemicalAvaiProduct($encoder,$key,$value,'-',$typeput)==false){ //avai number
				$no_redirect = $Product->getLastQuery();
				echo 'updateChemicalAvaiProduct';
				break;
			}	
		
			#Plus/Insert number of medicine in care_chemical_available_department (available_number)
			if($Cabinet->checkExistChemicalInAvaiDept($dept, $ward, $encoder, $key, $typeput)!=false){
				$Cabinet->updateChemicalAvaiDept($encoder, $key, $dept, $ward, $value,'+', $typeput);
			} else {
				$Cabinet->insertChemicalAvaiDept($encoder, $key, $dept, $ward, $value, $typeput);
			}			
			
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
			
			#Insert in care_chemical_department_archive, issue_paper, get_use=1 
			$Cabinet->insertChemicalArchive($dept, $ward, $encoder, $key, '1', $value, 0, $pres_id, 0, 0, 0, $receive_user, $typeput);
			
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));		
		}	
		
		#Minus number of medicine in care_chemical_available_department (available_number)
		$list_lotid_use = $Cabinet->useChemicalAvaiDept($encoder, $dept, $ward, $receive_med[$nr], $typeput);
		//echo $Cabinet->getLastQuery();
		if($list_lotid_use!=''){
			foreach ($list_lotid_use as $key => $value) {
			
				#Insert in care_chemical_department_archive, prescription, get_use=0
				$Cabinet->insertChemicalArchive($dept, $ward, $encoder, $key, '0', $value, 0, $pres_id, 0, 0, 0, $receive_user, $typeput);
			}
		}			
	}
	else $no_redirect=$Pres->getLastQuery();	
} 

if(!$no_redirect){
	$Pres->setChemicalStatusFinish($pres_id,'1');
	$Pres->setInfoPersonWhenIssueChemical($pres_id,$issue_user,$noteissue,$receive_user);
	$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pres->getLastQuery(), date('Y-m-d H:i:s'));
	$Pres->setCostChemical($pres_id,$totalcost);
	//$no_redirect=$Pres->getLastQuery();
}

#Go back to previous page
if (!$radiovalue || $radiovalue=='1')
		$typeInOut='all';
	elseif ($radiovalue=='2')
		$typeInOut='inpatient';
	else
		$typeInOut='outpatient';

$patmenu="../pharma_request_chemical_patient.php".URL_REDIRECT_APPEND."&full_en=".$_POST['encounter_nr']."&lang=".$_POST['lang']."&target=$target&pid=".$_SESSION['sess_pid']."&radiovalue=".$radiovalue."&typeInOut=".$typeInOut."&user_origin=".$user_origin;



if(!$no_redirect){
	header("Location:".$patmenu);
	exit;
}
else{
	echo $no_redirect;
} 

?>