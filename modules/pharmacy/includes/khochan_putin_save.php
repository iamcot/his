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

//extract($_POST);

//target= new, update, delete
if ($isdelete=="delete")
	$target = "delete";
else
	$target = $_POST['target'];

$patmenu="../putin_list_medicine.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid'];
$thisfile=basename(__FILE__);


if(!isset($Pharma)) $Pharma=new Pharma;

$logs = new AccessLog();
		
switch($target){
		case 'new':
		case 'create':
				//putin info		
				$date_time=explode(" ",$_POST['date_putin']);
				$vat_temp=(1+ $_POST['vat']/100);
				
				$date_time = formatDate2STD($date_time[0],'dd/mm/yyyy').' '.$date_time[1]; //date("Y-m-d G:i:s"); 
				$history='Create '.$_SESSION['sess_user_name'].' '.$date_time;
				
				if($Pharma->InsertPutInInfo('1',$_POST['supplier_input'], $date_time, $_POST['putin_person'], $_POST['deli_person'], $_POST['voucher_id'], $_POST['vat'], $_POST['typeput'], $_POST['place'], str_replace(',', '', $_POST['total_money'])*$vat_temp, $_POST['generalnote'], $_SESSION['sess_user_name'], $history)!=false){
														
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));									
					//putin
					$putin_id = $Pharma->getLastPutInID();
					if ($putin_id==false)
						$id=1;
					else $id = $putin_id['put_in_id'];
					
					$n = $_POST['maxid'];
					for ($j=1; $j<=$n; $j++) {
						if($_POST['medicine'.$j]){
							$date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
							$Pharma->InsertPharmaPutIn($id, $_POST['encoder'.$j], $_POST['lotid'.$j], '', $date_exp, str_replace(',', '',$_POST['number'.$j]), 0, str_replace(',', '',$_POST['cost'.$j]), $_POST['note'.$j], $_POST['vat']);
						}				
					}
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));					
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
				$id = $_POST['putin_id'];
				$date_time=explode(" ",$_POST['date_putin']);
				$vat_temp=(1+ $_POST['vat']/100);
				$date_time = formatDate2STD($date_time[0],'dd/mm/yyyy').' '.$date_time[1]; //date("Y-m-d G:i:s");
				$history='Update '.$_SESSION['sess_user_name'].' '.$date_time;
				
				//($putin_id, $pharma_type_put_in, $supplier, $date_time, $put_in_person, $delivery_person, $voucher_id, $vat, $typeput, $place, $totalcost, $note, $user, $history)
				if($Pharma->UpdatePutInInfo($id,1, $_POST['supplier_input'], $date_time, $_POST['putin_person'], $_POST['deli_person'], $_POST['voucher_id'], $_POST['vat'], $_POST['typeput'], $_POST['place'], str_replace(',', '',$_POST['total_money'])*$vat_temp, $_POST['generalnote'], $_SESSION['sess_user_name'], $history)!=false) {
				
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));
				
					$Pharma->deleteAllMedicineInPutIn($id);				
					$n = $_POST['maxid'];
					$totalcost=0;
					for ($j=1; $j<=$n; $j++) {
						if($_POST['medicine'.$j]!=""){
							$totalcost+=str_replace(',', '',$_POST['cost'.$j])*str_replace(',', '',$_POST['number'.$j]);
							$date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
							$Pharma->InsertPharmaPutIn($id, $_POST['encoder'.$j], $_POST['lotid'.$j], '', $date_exp, str_replace(',', '',$_POST['number'.$j]), 0, str_replace(',', '',$_POST['cost'.$j]), $_POST['note'.$j], $_POST['vat']);
						}				
					}
					/*if($totalcost){
						$Pharma->UpdatePutInInfo($id,1, $_POST['supplier_input'], $date_time, $_POST['putin_person'], $_POST['deli_person'], $_POST['voucher_id'], $_POST['vat'], $_POST['typeput'], $_POST['place'], $totalcost, $_POST['generalnote'], $_SESSION['sess_user_name'], $history);		
					}*/
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));					
					//echo $Pharma->getLastQuery();
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
				$id = $_POST['putin_id'];
				$Pharma->deleteAllMedicineInPutIn($id);
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));
				$Pharma->deletePutIn($id);				
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