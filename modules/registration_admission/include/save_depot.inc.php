<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require_once('./roots.php');
define('LANG_FILE','aufnahme.php');
require_once($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
//require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$local_user='aufnahme_user';

if ($isdelete=="delete")
	$mode = "delete";
else
	$mode = $_POST['mode'];
	
$thisfile=basename(__FILE__);
$sepChars=array('-','.','/',':',',');
	
$patmenu="../show_depot.php".URL_REDIRECT_APPEND."&full_en=".$_SESSION['sess_full_en']."&encounter_nr=".$_POST['encounter_nr']."&lang=".$_POST['lang']."&target=$target&pid=".$_SESSION['sess_pid'];
if(!isset($pres_obj)) $pres_obj=new PrescriptionMedipot;
$dept_nr=$_POST['dept_nr'];
$ward_nr=$_POST['ward_nr'];

$a = explode('_',$_POST['prescription_type_nr']);
$_POST['prescription_type_nr'] = $a[0];
		
$date_time = explode(" ",$_POST['inputdate']);
$date_tp = formatDate2STD($date_time[0],'dd/mm/yyyy',$sepChars);	
if (date('H:i:s',strtotime($date_time[1])) != $date_time[1]) {
	$time_tp =date('H:i:s');
} else $time_tp = $date_time[1];				
$datetime = $date_tp.' '.$time_tp;


		
switch($mode){
		case 'new':
		case 'create':
				//prescription_info 
				$pres_id = $pres_obj->getLastIDPrescription();
				if ($pres_id==false)
					$id=1;
				else
					$id = $pres_id['prescription_id']+1;
				//$datetime = date("Y-m-d G:i:s"); //$_POST['prescribe_date']
			
				$pharma_prescription_info = array('prescription_id' => $id,
					'prescription_type' => $_POST['prescription_type_nr'],	
					'dept_nr' => $dept_nr,
					'ward_nr' => $ward_nr,
					'date_time_create'=> $datetime,
					'symptoms' => $_POST['symptoms'],
					'diagnosis' => $_POST['diagnosis'],
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
					'receive_user' => $_POST['doctor']
				);

				$pres_obj->usePrescription('prescription_info');
				$pres_obj->insertDataFromArray($pharma_prescription_info);
				//echo $pres_obj->getLastQuery();
				//prescription sub
				
				$pres_obj->usePrescription('prescription');
				$n = $_POST['theValue'];
				for ($j=1; $j<=$n; $j++)
				{
					if($_POST['medicinea'.$j]){
						$pk = 0;
						$pharma_prescription = array(
							'nr' => $pres_obj->LastInsertPK('nr',$pk),
							'prescription_id' => $id,
							'product_encoder' => $_POST['encoder'.$j],
							'product_name' => $_POST['medicinea'.$j],
							'sum_number' => $_POST['sum'.$j], //30 (vien) = 1 hop
							'number_receive' => '',
							'number_of_unit' => '', //ngay uong 3 lan
							'desciption' => '',
							'note' => $_POST['units'.$j], //(hop) 
							'cost' => $_POST['cost'.$j],
							'time_use' =>'',
							'morenote'=>$_POST['morenote'.$j]
						);
						$pres_obj->insertDataFromArray($pharma_prescription);
					}				
				}
				//echo $pres_obj->getLastQuery();				
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
				//$datetime = date("Y-m-d G:i:s");
				$pharma_prescription_info = array('prescription_id' => $id,
					'prescription_type' => $_POST['prescription_type_nr'],		
					'dept_nr' => $dept_nr,
					'ward_nr' => $ward_nr,
					'date_time_create'=> $datetime,
					'symptoms' => $_POST['symptoms'],
					'diagnosis' => $_POST['diagnosis'],
					'note' => $_POST['note'],
					'history' => $_POST['history'],
					'doctor' => $_POST['doctor'],	
					'encounter_nr' => $_POST['encounter_nr'],
					'sum_date' => $_POST['totalday'],
					'modify_id' => $_POST['modify_id'],
					'status_bill' => $_POST['status_bill'],
					'status_finish' => $_POST['status_finish'],
					'total_cost' => $_POST['totalpres'],
					'in_issuepaper'=> '0'
				);
				$pres_obj->where=' prescription_id='.$id;
				$pres_obj->usePrescription('prescription_info');				
				if($pres_obj->updateDataFromArray($pharma_prescription_info,$id)) {
					$pres_obj->usePrescription('prescription');
					$pres_obj->deleteAllMedicineInPres($id);
					$n = $_POST['theValue'];
					for ($j=1; $j<=$n; $j++)
					{
						if($_POST['medicinea'.$j]!=""){
							$pk = 0;
							$pharma_prescription = array(
							'nr' => $pres_obj->LastInsertPK('nr',$pk),
							'prescription_id' => $id,
							'product_encoder' => $_POST['encoder'.$j],
							'product_name' => $_POST['medicinea'.$j],
							'sum_number' => $_POST['sum'.$j], //30 (vien) = 1 hop
							'number_receive' => '',
							'number_of_unit' => '', //ngay uong 3 lan
							'desciption' => '',
							'note' => $_POST['units'.$j], //(hop) 
							'cost' => $_POST['cost'.$j],
							'time_use' =>'',
							'morenote'=>$_POST['morenote'.$j]
							);
							//$pres_obj->setDataArray(&$pharma_prescription);
							$pres_obj->insertDataFromArray($pharma_prescription);
						}				
					}	
					//echo $pres_obj->getLastQuery();
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
				$pres_obj->deletePres($id);				
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