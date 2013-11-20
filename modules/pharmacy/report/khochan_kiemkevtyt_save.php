<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

extract($_POST);

//ngaydau, ngaycuoi, update_id, kkthang = 08/2012

$patmenu="khochan_vtyt_kiemke.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid'];
if(!isset($Pharma)) $Pharma=new Pharma;
		
$temp = explode('/',$kkthang);
$monthreport = 	intval($temp[0]); 
$yearreport = $temp[1];	
		
switch($target){
		case 'new':
		case 'create':
		case 'save':
				if($update_id!=''){		//update
					$Pharma->Khochan_vtyt_updatetonkho($update_id, $ngaydau, $ngaycuoi, $monthreport, $yearreport, $typeput);
					$Pharma->deleteAllMedipotInTonKho($update_id);
					for ($i=1; $i<=$maxid; $i++) {
						$encoder_dx = 'encoder'.$i;
						$lotid_dx = 'lotid'.$i;
						$exp_date_dx = 'exp_date'.$i;
						$number_dx = 'number'.$i;
						$price_dx = 'price'.$i;
						
						if($$encoder_dx!=''){
							$Pharma->Khochan_vtyt_luutonkho_chitiet($update_id, $$encoder_dx, $$lotid_dx, $typeput, $$exp_date_dx, $$number_dx, $$price_dx);
						}
					} 
					
				}else{					//new
					$Pharma->Khochan_vtyt_luutonkho($ngaydau, $ngaycuoi, $monthreport, $yearreport, $typeput);
					$lastid = $Pharma->getLastTonKhovtytID();
					for ($i=1; $i<=$maxid; $i++) {
						$encoder_dx = 'encoder'.$i;
						$lotid_dx = 'lotid'.$i;
						$exp_date_dx = 'exp_date'.$i;
						$number_dx = 'number'.$i;
						$price_dx = 'price'.$i;
						
						if($$encoder_dx!=''){
							$Pharma->Khochan_vtyt_luutonkho_chitiet($lastid['ton_id'], $$encoder_dx, $$lotid_dx, $typeput, $$exp_date_dx, $$number_dx, $$price_dx);
						}
					}
				
				}
				//echo $Pharma->getLastQuery()
				header("Location:".$patmenu);
				exit;
 
			break;
		
}



?>