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

//report_id, receive_person, total_money, user_accept, typeput
$total_money = str_replace(',', '', $total_money);

$receive_med = array_combine($medicine_nr, $receive);

foreach ($medicine_nr AS $nr) 
{ 
	$receive_med[$nr] = str_replace(',', '', $receive_med[$nr]);
	//Update number of medicine
	if($Pharma->setReceiveMedicineInPayOut($nr,$receive_med[$nr])!=false){

		//Get encoder
		if($medicine=$Pharma->getMedicineInPayOut($nr)){		

			//Update product_main (kho chan)
			$Product->updateMedicineProductMain($medicine['product_encoder'],$receive_med[$nr],'-');
			
			//Update product_main_sub
			$Product->UpdateMedicineInMainSub($medicine['product_encoder'],$medicine['lotid'],$receive_med[$nr],'','-', $typeput);
			
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Product->getLastQuery(), date('Y-m-d H:i:s'));
			
			if($mode=='khole'){
				//Update or Insert Avai_Product (kho le)
				if($Product->checkExistMedicineInAvaiProduct($medicine['product_encoder'],$medicine['lotid'],$typeput)!=false){
					$Product->updateMedicineAvaiProduct($medicine['product_encoder'],$medicine['lotid'],$receive_med[$nr],'+',$typeput);
				} else {
					$Product->InsertMedicineInAvaiProduct($medicine['product_encoder'], $medicine['lotid'], $medicine['product_date'], $medicine['exp_date'], $receive_med[$nr], $medicine['price'], $typeput);
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
	$Pharma->setInfoPayOutWhenAccept($report_id,$receive_person,$total_money,$user_accept);
	$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));
	$Pharma->setPayOutStatusFinish($report_id,'1');
}


#Go back to previous page

$patmenu="../pharma_request_khochan_payout.php".URL_REDIRECT_APPEND."&target=$target&pid=".$_SESSION['sess_pid']."&user_origin=".$user_origin."&mode=".$mode;



if(!$no_redirect){
	header("Location:".$patmenu);
	exit;
}
else{
	echo $no_redirect;
} 

?>