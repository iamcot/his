<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$root_path='../../../';
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();

//extract($_POST);

//target= new, update, delete
if ($isdelete=="delete")
	$target = "delete";
else
	$target = $_POST['target'];

$patmenu="../payout_list_medicine.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid'];
$thisfile=basename(__FILE__);
if(!isset($Pharma)) $Pharma=new Pharma;

$_POST['total_money'] = str_replace(',', '', $_POST['total_money']);
		
switch($target){
		case 'new':
		case 'create':
				//putin info					
				$date_time=explode(" ",$_POST['date_payout']);
				$date_time = formatDate2STD($date_time[0],'dd/mm/yyyy').' '.$date_time[1]; 
				$history='Create '.$_SESSION['sess_user_name'].' '.$date_time;
				
				//($pharma_type_pay_out, $placefrom, $date_time, $pay_out_person, $receiver, $voucher_id, $note, $health_station, $totalcost)
				if($Pharma->InsertPayOutInfo('1',$_POST['placefrom'], $date_time, $_POST['payout_person'], $_POST['receiver'], $_POST['voucher_id'], $_POST['typeput'], $_POST['generalnote'], $_POST['health_station'], $_POST['total_money'], $_SESSION['sess_user_name'], $history)!=false){
				
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));									
					//putin
					$payout_id = $Pharma->getLastPayOutID();
					if ($payout_id==false)
						$id=1;
					else $id = $payout_id['pay_out_id'];
					
					$n = $_POST['maxid'];
					for ($j=1; $j<=$n; $j++) {
						if($_POST['medicine'.$j]){
							$date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
							$_POST['number'.$j] = str_replace(',', '',$_POST['number'.$j]);
							$_POST['cost'.$j] = str_replace(',', '',$_POST['cost'.$j]);
							$Pharma->InsertPharmaPayOut($id, $_POST['encoder'.$j], $_POST['lotid'.$j], '', $date_exp, $_POST['number'.$j], 0, $_POST['cost'.$j], $_POST['note'.$j]);
						}				
					}	
				}
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $Pharma->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 
			break;
			
		case 'update': 		
				$id = $_POST['payout_id'];
				$date_time=explode(" ",$_POST['date_payout']);
				$date_time = formatDate2STD($date_time[0],'dd/mm/yyyy').' '.$date_time[1];  //date("Y-m-d G:i:s");
				$history='Update '.$_SESSION['sess_user_name'].' '.$date_time;
				
				if($Pharma->UpdatePayOutInfo($id,1,$_POST['placefrom'], $date_time, $_POST['payout_person'], $_POST['receiver'], $_POST['voucher_id'], $_POST['typeput'], $_POST['health_station'],$_POST['total_money'], $_POST['generalnote'], $_SESSION['sess_user_name'], $history)!=false) {
				
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));
				
					$Pharma->deleteAllMedicineInPayOut($id);				
					$n = $_POST['maxid'];
					for ($j=1; $j<=$n; $j++) {
						if($_POST['medicine'.$j]!=""){
							$date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
							$_POST['number'.$j] = str_replace(',', '',$_POST['number'.$j]);
							$_POST['cost'.$j] = str_replace(',', '',$_POST['cost'.$j]);
							$Pharma->InsertPharmaPayOut($id, $_POST['encoder'.$j], $_POST['lotid'.$j], '', $date_exp, $_POST['number'.$j], 0, $_POST['cost'.$j], $_POST['note'.$j]);
						}				
					}	
				
					if(!$no_redirect){
						header("Location:".$patmenu);
						exit;
					}
				}
				else{
                    echo $Pharma->getLastQuery().'<br>'.$LDDbNoUpdate;
                    $error=TRUE;
                }	

			break;
			
		case 'delete':
				$id = $_POST['payout_id'];
				$Pharma->deleteAllMedicineInPayOut($id);
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));
				$Pharma->deletePayOut($id);				
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $Pharma->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 

			break;
}



?>