<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$root_path='../../../';
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_cabinet_medipot.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();

//target= new, update, delete
if ($isdelete=="delete")
	$target = "delete";
else
	$target = $_POST['target'];
	
$thisfile=basename(__FILE__);	

//echo $target.' '.$target.' '.$list_presid.' '.$_POST['ward_nr'];
//echo $target;
$patmenu="../manage_pharma/medipot_use_ward_list.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid']."&ward_nr=".$_POST['ward_nr'].'&dept_nr='.$_POST['dept_nr'];
if(!isset($Cabinet)) $Cabinet=new CabinetMedipot;


		
switch($target){
		case 'new':
		case 'create':
				//destroy info
				$use_id = $Cabinet->getLastUseID();
				if ($use_id==false)
					$id=1;
				else
					$id = $use_id['use_id']+1;
					

				$Cabinet->useCabinetUse('use_info');
				$pharma_use_info = array('use_id'=> $id,
							'dept_nr' => $_POST['dept_nr'],
							'ward_nr' => $_POST['ward_nr'],
							'typeput' => $_POST['typeput'],
							'date_time_use' => formatDate2STD($_POST['date_time'],'dd/mm/yyyy'),
							'nurse' =>$_SESSION['sess_user_name'],
							'history' =>$_POST['history'],
							'note'=>$_POST['notecreator'],
							'modify_id'=>'',
							'status_finish'=>'0',
							'user_accept'=>'');
				$Cabinet->insertDataFromArray($pharma_use_info);
				$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
				//return sub
				$Cabinet->useCabinetUse('use');
				$n = $_POST['maxid'];
				for ($j=1; $j<=$n; $j++)
				{
					if($_POST['medicine'.$j]){
						$pk = 0;
						$date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
						$pharma_use = array(
							'nr' => $Cabinet->LastInsertPK('nr',$pk),
							'use_id'=> $id,
							'product_encoder'=> $_POST['encoder'.$j],
							'product_lot_id'=> $_POST['lotid'.$j],
							'cost'=> $_POST['cost'.$j],
							'number' =>$_POST['number'.$j],
							'units' =>$_POST['unit'.$j],
							'note'=> $_POST['note'.$j]
						);
						$Cabinet->insertDataFromArray($pharma_use);
					}				
				}	
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $Cabinet->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 
			break;
			
		case 'update': 
				$id = $_POST['use_id'];
				$datetime = 'Update '.date("Y-m-d G:i:s");
				$pharma_use_info = array('use_id'=> $id,
							'dept_nr' => $_POST['dept_nr'],
							'ward_nr' => $_POST['ward_nr'],
							'typeput' => $_POST['typeput'],
							'date_time_use' => formatDate2STD($_POST['date_time'],'dd/mm/yyyy'),
							'nurse' =>$_POST['create_id'],
							'history' => $datetime,
							'note'=>$_POST['notecreator'],
							'modify_id'=>$_SESSION['sess_user_name'],
							'status_finish'=>'0',
							'user_accept'=>''
				);
				$Cabinet->where=' use_id='.$id;
				$Cabinet->useCabinetUse('use_info');				
				if($Cabinet->updateDataFromArray($pharma_use_info,$id)) {
				
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
				
					$Cabinet->useCabinetUse('use');
					$Cabinet->deleteAllMedicineInUse($id);
					$n = $_POST['maxid'];
					for ($j=1; $j<=$n; $j++)
					{
						if($_POST['medicine'.$j]!=""){
							$pk = 0;							
							$pharma_return = array(
								'nr' => $Cabinet->LastInsertPK('nr',$pk),
								'use_id'=> $id,
								'product_encoder'=> $_POST['encoder'.$j],
								'product_lot_id'=> $_POST['lotid'.$j],
								'cost'=> $_POST['cost'.$j],
								'number' =>$_POST['number'.$j],
								'units' =>$_POST['unit'.$j],
								'note'=> $_POST['note'.$j]
							);							
							$Cabinet->insertDataFromArray($pharma_return);
						}				
					}	
				
					if(!$no_redirect){
						header("Location:".$patmenu);
						exit;
					}
				}
				else{
                    echo $Cabinet->getLastQuery().'<br>'.$LDDbNoUpdate;
                    $error=TRUE;
                }	

			break;
			
		case 'delete':
				$id = $_POST['use_id'];
				$Cabinet->deleteAllMedicineInUse($id);
				$Cabinet->deleteUse($id);				
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $Cabinet->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 

			break;
}



?>