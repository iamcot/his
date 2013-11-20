<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$root_path='../../../';
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

//extract($_POST);

//target= new, update, delete
if ($isdelete=="delete")
	$target = "delete";
else
	$target = $_POST['target'];

$patmenu="../putin_medipot_list_medicine.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid'];
if(!isset($Pharma)) $Pharma=new Pharma;
		
switch($target){
		case 'new':
		case 'create':
				//putin info					
				$date_time = explode(" ",$_POST['date_putin']); 
				$vat_temp=(1+ $_POST['vat']/100);
				
				$date_time = formatDate2STD($date_time[0],'dd/mm/yyyy').' '.$date_time[1];
				$history='Create '.$_SESSION['sess_user_name'].' '.$date_time;
				
				if($Pharma->InsertPutInMedInfo('2',$_POST['supplier_input'], $date_time, $_POST['putin_person'], $_POST['deli_person'], $_POST['voucher_id'],  $_POST['vat'], $_POST['typeput'], $_POST['place'], $_POST['total_money']*$vat_temp, $_POST['generalnote'], $_SESSION['sess_user_name'], $history)!=false){
							
					
					//putin
					$putin_id = $Pharma->getLastPutInMedID();
					if ($putin_id==false)
						$id=1;
					else $id = $putin_id['put_in_id'];
					
					$n = $_POST['maxid'];
					for ($j=1; $j<=$n; $j++) {
						if($_POST['medicine'.$j]){
							$date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
							$Pharma->InsertPharmaPutInMed($id, $_POST['encoder'.$j], $_POST['lotid'.$j], '', $date_exp, $_POST['number'.$j], 0, $_POST['cost'.$j], $_POST['note'.$j], $_POST['vat']);
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
				$id = $_POST['putin_id'];
				$date_time=explode(" ",$_POST['date_putin']);
				$vat_temp=(1+ $_POST['vat']/100);
				//echo $vat_temp*$_POST['total_money'];
				$date_time = formatDate2STD($date_time[0],'dd/mm/yyyy').' '.$date_time[1];
				$history='Update '.$_SESSION['sess_user_name'].' '.$date_time;
				
				if($Pharma->UpdatePutInMedInfo($id,2, $_POST['supplier_input'], $date_time, $_POST['putin_person'], $_POST['deli_person'], $_POST['voucher_id'], $_POST['vat'], $_POST['typeput'], $_POST['place'], ($_POST['total_money']*$vat_temp), $_POST['generalnote'], $_SESSION['sess_user_name'], $history)!=false) {
				
					//echo $Pharma->GetLastQuery();
					$Pharma->deleteAllMedicineInPutInMed($id);				
					$n = $_POST['maxid'];
					$totalcost=0;
					for ($j=1; $j<=$n; $j++) {
						if($_POST['medicine'.$j]!=""){
							$totalcost+=$_POST['cost'.$j]*$_POST['number'.$j];
							$date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
							$Pharma->InsertPharmaPutInMed($id, $_POST['encoder'.$j], $_POST['lotid'.$j], '', $date_exp, $_POST['number'.$j], 0, $_POST['cost'.$j], $_POST['note'.$j],$_POST['vat']);
						}				
					}	
					/*if($totalcost){
						$Pharma->UpdatePutInMedInfo($id,2, $_POST['supplier_input'], $date_time, $_POST['putin_person'], $_POST['deli_person'], $_POST['voucher_id'], $_POST['vat'], $_POST['typeput'], $_POST['place'], $totalcost, $_POST['generalnote'], $_SESSION['sess_user_name'], $history);		
					}*/
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
				$Pharma->deleteAllMedicineInPutInMed($id);
				$Pharma->deletePutInMed($id);				
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