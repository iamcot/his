<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require_once('./roots.php');
define('LANG_FILE','aufnahme.php');
require_once($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/care_api_classes/class_prescription.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/core/access_log.php');

$local_user='aufnahme_user';

if ($isdelete=="delete")
	$mode = "delete";
else
	$mode = $_POST['mode'];

	
$patmenu="../show_prescription.php".URL_REDIRECT_APPEND."&full_en=".$_SESSION['sess_full_en']."&encounter_nr=".trim($_POST['encounter_nr'])."&lang=".$_POST['lang']."&pid=".$_SESSION['sess_pid'].'&type='.$_POST['type'].'&encounter_class_nr='.trim($_POST['encounter_class_nr']);

$thisfile=basename(__FILE__);
$sepChars=array('-','.','/',':',',');
   
$logs = new AccessLog();

$a = explode('_',$_POST['prescription_type_nr']);
$_POST['prescription_type_nr'] = $a[0];
	
if(!isset($pres_obj)) $pres_obj=new Prescription;
$dept_nr=$_POST['dept_nr'];
$ward_nr=$_POST['ward_nr'];
		
switch($mode){
		case 'new':
		case 'create':
				//prescription_info 
				$pres_id = $pres_obj->getLastIDPrescription();
				if ($pres_id==false)
					$id=1;
				else
					$id = $pres_id['prescription_id']+1;
					
				$date_time = explode(" ",$_POST['inputdate']);
				/*if (date('d-m-Y',strtotime($date_time[0])) != $date_time[0]) {
					$date_tp =date('Y-m-d');
				} else 	*/$date_tp = formatDate2STD($date_time[0],'dd/mm/yyyy',$sepChars);	
				
				if (date('H:i:s',strtotime($date_time[1])) != $date_time[1]) {
					$time_tp =date('H:i:s');
				} else $time_tp = $date_time[1];
				
				$datetime = $date_tp.' '.$time_tp;
				

				
				$pharma_prescription_info = array('prescription_id' => $id,
					'prescription_type' => $_POST['prescription_type_nr'],	
					'dept_nr' => $dept_nr,
					'ward_nr' => $ward_nr,
					'date_time_create'=> $datetime,
					'symptoms' => addslashes(htmlspecialchars($_POST['symptoms'])),
					'diagnosis' => addslashes(htmlspecialchars($_POST['diagnosis'])),
					'note' => $_POST['note'],
					'history' => $_POST['history'],
					'doctor' => $_POST['doctor'],	
					'encounter_nr' => $_POST['encounter_nr'],
					'sum_date' => $_POST['totalday'],
					'modify_id' => $_POST['modify_id'],
					'status_bill' => $_POST['status_bill'],
					'status_finish' => $_POST['status_finish'],
					'total_cost' => $_POST['totalpres'],
					'in_issuepaper'=> '0',
					'issue_user' =>'',
					'issue_note' =>'',
					'receive_user' => $_POST['doctor'],
					'taikham'=>$_POST['taikham'],
					'nghiphep'=>$_POST['nghiphep'],
					'cls'=>addslashes(htmlspecialchars($_POST['cls'])),
					'sinhhieu'=>addslashes(htmlspecialchars($_POST['sinhhieu']))
				);

				$pres_obj->usePrescription('prescription_info');
				$pres_obj->insertDataFromArray($pharma_prescription_info);
				
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pres_obj->getLastQuery(), date('Y-m-d H:i:s'));
				
				//prescription sub
				$pres_obj->usePrescription('prescription');
				$n = $_POST['theValue']; 
				for ($j=1; $j<=$n; $j++)
				{
					if($_POST['medicinea'.$j]){
						$pk = 0;
						$temp_n=$_POST['times'.$j]; $attime_save='';
						for ($k=1;$k<=$temp_n;$k++)
							$attime_save=$attime_save.'-'.$_POST['attime_'.$j.'_'.$k];
						$attime_save=substr($attime_save, 1); 
						
						$attime_save = str_replace("'''","s",$attime_save); $attime_save = str_replace("''","p",$attime_save); $attime_save = str_replace("'","h",$attime_save);
						
						$pharma_prescription = array(
							'nr' => $pres_obj->LastInsertPK('nr',$pk),
							'prescription_id' => $id,
							'product_encoder' => $_POST['encoder'.$j],
							'product_name' => $_POST['medicinea'.$j],
							'sum_number' => $_POST['sum'.$j], //30 (vien) = 1 hop
							'number_receive' => '',
							'number_of_unit' => $_POST['times'.$j], //ngay uong 3 lan
							'type_use' => $_POST['howtouse'.$j],
							'desciption' => $_POST['howtouse'.$j].' '.$_POST['count'.$j].' '.$_POST['totalunits'.$j], //1 vien (/lan)
							'note' => $_POST['units'.$j], //(hop) 
							'cost' => $_POST['cost'.$j],
							'time_use' =>$attime_save,
							'morenote'=>$_POST['morenote'.$j],
							'speed'=>'',
							'avai_pro_id' => $_POST['avai_id'.$j],
							'cost_dutinh' => $_POST['cost'.$j]
						);
						$pres_obj->insertDataFromArray($pharma_prescription);
						//$pres_obj->updateAvaiPro_Sub($_POST['avai_id'.$j], $_POST['sum'.$j]);
					}
				}
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pres_obj->getLastQuery(), date('Y-m-d H:i:s'));	
				
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $pres_obj->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 
			break;
			
		case 'update': 
				$id = $_POST['idpres'];
				$date_time = explode(" ",$_POST['inputdate']);
				
				/*if (date('d-m-Y',strtotime($date_time[0])) != $date_time[0]) {
					$date_tp =date('Y-m-d');
				} else*/ 	$date_tp = formatDate2STD($date_time[0],'dd/mm/yyyy',$sepChars);	
				
				if (date('H:i:s',strtotime($date_time[1])) != $date_time[1]) {
					$time_tp =date('H:i:s');
				} else $time_tp = $date_time[1];
				
				$datetime = $date_tp.' '.$time_tp;
				//$datetime = formatDate2STD($date_time[0],'dd/mm/yyyy',$sepChars).' '.$date_time[1];
				
				//$datetime = date("Y-m-d G:i:s");
				$pharma_prescription_info = array('prescription_id' => $id,
					'prescription_type' => $_POST['prescription_type_nr'],		
					'dept_nr' => $dept_nr,
					'ward_nr' => $ward_nr,
					'date_time_create'=> $datetime,
					'symptoms' => addslashes(htmlspecialchars($_POST['symptoms'])),
					'diagnosis' => addslashes(htmlspecialchars($_POST['diagnosis'])),
					'note' => $_POST['note'],
					'history' => $_POST['history'],
					'doctor' => $_POST['doctor'],	
					'encounter_nr' => $_POST['encounter_nr'],
					'sum_date' => $_POST['totalday'],
					'modify_id' => $_POST['modify_id'],
					'status_bill' => $_POST['status_bill'],
					'status_finish' => $_POST['status_finish'],
					'total_cost' => $_POST['totalpres'],
					'in_issuepaper'=> '0',
					'taikham'=>$_POST['taikham'],
					'nghiphep'=>$_POST['nghiphep'],
					'cls'=>addslashes(htmlspecialchars($_POST['cls'])),
					'sinhhieu'=>addslashes(htmlspecialchars($_POST['sinhhieu']))
				);
				$pres_obj->where=' prescription_id='.$id;
				$pres_obj->usePrescription('prescription_info');				
				if($pres_obj->updateDataFromArray($pharma_prescription_info,$id)) {
				
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pres_obj->getLastQuery(), date('Y-m-d H:i:s'));
					
					$pres_obj->usePrescription('prescription');
					//$pres_obj->updateAvaiPro_TamTon($_POST['avai_id'.$j], '+', $_POST['sum'.$j]);
					$pres_obj->updateAvaiPro_Plus($id);
					$pres_obj->deleteAllMedicineInPres($id);
					$n = $_POST['theValue']; 
					for ($j=1; $j<=$n; $j++)
					{
						if($_POST['medicinea'.$j]!=""){
							$pk = 0;
							$temp_n=$_POST['times'.$j]; $attime_save='';
							for ($k=1;$k<=$temp_n;$k++)
								$attime_save=$attime_save.'-'.$_POST['attime_'.$j.'_'.$k];
							$attime_save=substr($attime_save, 1); 
							
							$attime_save = str_replace("'''","s",$attime_save); $attime_save = str_replace("''","p",$attime_save); $attime_save = str_replace("'","h",$attime_save);
						
							$pharma_prescription = array(
								'nr' => $pres_obj->LastInsertPK('nr',$pk),
								'prescription_id' => $id,
								'product_encoder' => $_POST['encoder'.$j],
								'product_name' => $_POST['medicinea'.$j],
								'sum_number' => $_POST['sum'.$j],
								'number_receive' => '',
								'number_of_unit' => $_POST['times'.$j],
								'type_use' => $_POST['howtouse'.$j],
								'desciption' => $_POST['howtouse'.$j].' '.$_POST['count'.$j].' '.$_POST['totalunits'.$j], //uong 1 vien/lan
								'note' => $_POST['units'.$j],
								'cost' => $_POST['cost'.$j],
								'time_use' => $attime_save,
								'morenote' => $_POST['morenote'.$j],
								'speed'=>'',
								'avai_pro_id' => $_POST['avai_id'.$j],
								'cost_dutinh' => $_POST['cost'.$j]
							);
							//$pres_obj->setDataArray(&$pharma_prescription);
							$pres_obj->insertDataFromArray($pharma_prescription);
							//$pres_obj->updateAvaiPro_Sub($_POST['avai_id'.$j], $_POST['sum'.$j]);
						}				
					}
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pres_obj->getLastQuery(), date('Y-m-d H:i:s'));					
					if(!$no_redirect){
						header("Location:".$patmenu);
						exit;
					}
				}
				else{
                    echo $pres_obj->getLastQuery().'<br>'.$LDDbNoUpdate;
                    $error=TRUE;
                }	

			break;
			
		case 'delete':
				$id = $_POST['idpres'];
				$pres_obj->deleteAllMedicineInPres($id);
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pres_obj->getLastQuery(), date('Y-m-d H:i:s'));
				$pres_obj->deletePres($id);
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pres_obj->getLastQuery(), date('Y-m-d H:i:s'));				
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $pres_obj->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 

			break;
}



?>