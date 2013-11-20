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

//echo $month.'/'.$year; $typeput; $typedongtay

$patmenu="khochan_baocaothuoc.php".URL_REDIRECT_APPEND."&typeput=".$typeput."&typedongtay=".$typedongtay;
if(!isset($Pharma)) $Pharma=new Pharma;

//maybe change later...
$month1 = str_pad($month,2,'0',STR_PAD_LEFT);
$fromdate=$year.'-'.$month1.'-01';
$todate = date("Y-m-t", mktime(0, 0, 0, $month, 1, $year));
		
switch($target){
		case 'update':

					$Pharma->Khochan_thuoc_updatetonkho($typedongtay, $ton_id, $fromdate, $todate, $month, $year, $typeput);
					$Pharma->deleteAllMedicineInTonKho($typedongtay, $ton_id);
					
					for ($i=1; $i<=$maxid; $i++) {
						$encoder_dx = 'encoder'.$i;
						$tondau_dx = 'tondau'.$i;
						$nhap_dx = 'nhap'.$i;
						$xuat_dx = 'xuat'.$i;
						$lotid_dx = 'lotid'.$i;
						$exp_dx = 'exp'.$i;
						$toncuoi_dx = 'toncuoi'.$i;
						$gia_dx = 'gia'.$i;
						if($$encoder_dx && $ton_id>0 && $$toncuoi_dx!=0)
							$Pharma->Khochan_thuoc_luutonkho_chitiet($typedongtay, $ton_id, $$encoder_dx, $$lotid_dx, $typeput, $$exp_dx, $$toncuoi_dx, $$gia_dx);
					}
					header("Location:".$patmenu);
					exit;				
				break;
				
		case 'new':
		case 'create':
		case 'save':
				//report		
				
				$monthreport= $year.'-'.$month1.'-00';
				//$monthreport = formatDate2STD($date_time[0],'dd-mm-yyyy'); 
				
				$Pharma->Khochan_thuoc_luutonkho($typedongtay, $fromdate, $todate, $month, $year, $typeput);
				$monthyear="WHERE monthreport='".$month."' AND yearreport='".$year."' ";
				
				if($anyreport=$Pharma->checkAnyReport_TonKhoChan($monthyear)){
					$ton_id=$anyreport['id'];
				}	
				
				for ($i=1; $i<=$maxid; $i++) {
					$encoder_dx = 'encoder'.$i;
					$tondau_dx = 'tondau'.$i;
					$nhap_dx = 'nhap'.$i;
					$xuat_dx = 'xuat'.$i;
					$lotid_dx = 'lotid'.$i;
					$exp_dx = 'exp'.$i;
					$toncuoi_dx = 'toncuoi'.$i;
					$gia_dx = 'gia'.$i;
					
					if($$encoder_dx){
						//echo $$encoder_dx.' '.$monthreport.' '.$$tondau_dx.' '.$$nhap_dx.' '.$$xuat_dx.' '.$$toncuoi_dx.' '.$$gia_dx.' '.$user_report;
						$Pharma->Khochan_thuoc_luubaocao($$encoder_dx, $monthreport, str_replace(',','', $$tondau_dx), str_replace(',','', $$nhap_dx), str_replace(',','', $$xuat_dx), str_replace(',','', $$toncuoi_dx), str_replace(',','', $$gia_dx), $user_report, $_POST['typeput']);
						
						if($ton_id>0 && $$toncuoi_dx!=0)
							$Pharma->Khochan_thuoc_luutonkho_chitiet($typedongtay, $ton_id, $$encoder_dx, $$lotid_dx, $typeput, $$exp_dx, $$toncuoi_dx, $$gia_dx);
					}
				}
				
				
				
				//echo $Pharma->getLastQuery()
				header("Location:".$patmenu);
				exit;
 
			break;
		
}



?>