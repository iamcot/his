<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
$top_dir='modules/pharmacy/includes';
require_once($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');
include_once($root_path.'include/care_api_classes/class_product.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();
if(!isset($Product)) $Product=new Product;
if(!isset($Pharma)) $Pharma=new Pharma;

$thisfile=basename(__FILE__);

//report_id, receive_person, total_money, user_accept

$receive_chemical = array_combine($chemical_nr, $receive);
foreach ($chemical_nr AS $nr) 
{ 
	//Update number of medicine
	if($Pharma->setReceiveChemicalInPayOut($nr,$receive_chemical[$nr])!=false){
		//Get encoder
		if($chemical=$Pharma->getchemicalInPayOut($nr)){
                    
			//Update product_main (kho chan)
			$Product->updateChemicalProductMain($chemical['product_encoder'],$receive_chemical[$nr],'-');
			
			//Update product_main_sub
			$Product->UpdateChemicalInMainSub($chemical['product_encoder'],$chemical['lotid'],$receive_chemical[$nr],'','-', $typeput);
			
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Product->getLastQuery(), date('Y-m-d H:i:s'));
			
			if($mode=='khole'){
				//Update or Insert Avai_Product (kho le)
				if($Product->checkExistChemicalInAvaiProduct($chemical['product_encoder'],$chemical['lotid'], $typeput)!=false){
					$Product->updateChemicalAvaiProduct($chemical['product_encoder'],$chemical['lotid'],$receive_chemical[$nr],'+', $typeput);
				} else {
					$Product->InsertChemicalInAvaiProduct($chemical['product_encoder'], $chemical['lotid'], $chemical['product_date'], $chemical['exp_date'], $receive_chemical[$nr], $chemical['price'], $typeput);
				}
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Product->getLastQuery(), date('Y-m-d H:i:s'));
			}
	
		}
		
	}else {
		$no_redirect = $Pharma->getLastQuery();
		break;
	}
} 

if(!$no_redirect){
	$Pharma->setInfoChemicalPayOutWhenAccept($report_id,$receive_person,$total_money,$user_accept);
	$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));
	$Pharma->setChemicalPayOutStatusFinish($report_id,'1');
}


#Go back to previous page

$patmenu="../chemical_request_khochan_payout.php".URL_REDIRECT_APPEND."&target=$target&pid=".$_SESSION['sess_pid']."&user_origin=".$user_origin."&mode=".$mode;



if(!$no_redirect){
	header("Location:".$patmenu);
	exit;
}
else{
	echo $no_redirect;
} 

?>