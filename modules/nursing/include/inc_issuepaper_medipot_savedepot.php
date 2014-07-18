<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$root_path='../../../';
define('LANG_FILE','aufnahme.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_issuepaper_medipot.php');
include_once($root_path.'include/care_api_classes/class_prescription_medipot.php');

//mode= new, update, delete
if ($isdelete=="delete")
	$mode = "delete";
else
	$mode = $_POST['mode'];
	
//target= depot, sum
if(!isset($target))
	$target = $_POST['target'];
	
//list_presid = 1#17#25
if($target=='sum' && $mode!='update') {
	if(!isset($list_presid))
		$list_presid = $_POST['list_presid'];
	//Ham tach cac pres_id trong list --------------
	$data = substr($list_presid,1,strlen($list_presid));
	$data = $data."#";
	$from=0; $i=0; $array_presid = array();
	while (strlen($data)){
		$from = strpos($data, "#");
		$temp=substr($data,0,$from);
		if(strlen($temp)){
			$array_presid[$i]=$temp;
			$i++;
			//echo $temp.'<br>';
		}
		$data = substr($data,$from+1,strlen($data));		
	}
	if(count($array_presid)==0)
		echo 'Error! Array list_pres_id = null';
}
//echo $mode.' '.$target.' '.$list_presid.' '.$_POST['ward_nr'];
//echo $mode;
$patmenu="../nursing-issuepaper-medipot-listdepot.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid']."&ward_nr=".$_POST['ward_nr'].'&dept_nr='.$_POST['dept_nr'];
if(!isset($issue_obj)) $issue_obj=new IssueMedipot;
if(!isset($pres_obj)) $pres_obj=new PrescriptionMedipot;


		
switch($mode){
		case 'new':
		case 'create':
				//issue paper info
				$issue_id = $issue_obj->getLastIDIssue();
				if ($issue_id==false)
					$id=1;
				else
					$id = $issue_id['issue_paper_id']+1;
					
				$datetime = date("Y-m-d G:i:s"); //$_POST['issuecribe_date']
				
				//prescription_info 
				if($target=='sum'){
					for ($i=0; $i<count($array_presid); $i++){
						$pres_obj->setPresStatusInIssue($array_presid[$i],$id);
					}
				}
				$issue_obj->useIssuePaper('issuepaper_info');
				$pharma_issue_paper_info = array('issue_paper_id' => $id,
					'dept_nr' => $_POST['dept_nr'],
					'ward_nr' => $_POST['ward_nr'],	
					'type' => $_POST['type'],			
					'typeput'=> $_POST['typeput'],					
					'date_time_create'=> $datetime,
					'nurse' => $_SESSION['sess_user_name'],
					'history' => $_POST['history'],
					'note' => $_POST['notecreator'],	
					'modify_id' => '',
					'status_finish' => '0',
					'issue_user'=> '',
					'issue_note'=> '',
					'receive_user'=> ''
				);
				$issue_obj->insertDataFromArray($pharma_issue_paper_info);
				//echo $issue_obj->getLastQuery();
				
				//issue_paper sub
				$issue_obj->useIssuePaper('issuepaper');
				$n = $_POST['maxid'];
				for ($j=1; $j<=$n; $j++)
				{
					if($_POST['medicine'.$j]){
						$pk = 0;
						if($target=='sum') $plus=$_POST['plus'.$j];
						else $plus=$_POST['sum'.$j];
						
						$pharma_issue_paper = array(
							'nr' => $issue_obj->LastInsertPK('nr',$pk),
							'issue_paper_id' => $id,
							'product_encoder' => $_POST['encoder'.$j],
							'product_name' => $_POST['medicine'.$j],
							'units' => $_POST['units'.$j], 
							'sumpres' => $_POST['sumpres'.$j],
							'plus' => $plus,
							'number_request' => $_POST['sum'.$j], 
							'number_receive' => '',
							'note' => $_POST['note'.$j],
                            'available_product_id'=> $_POST['available_product_id'.$j]
						);
						$issue_obj->insertDataFromArray($pharma_issue_paper);
					}				
				}
				//echo $issue_obj->getLastQuery();				
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $issue_obj->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 
			break;
			
		case 'update': 
				$id = $_POST['IssueId'];
				$datetime = 'Update '.date("Y-m-d G:i:s");
				$pharma_issue_paper_info = array('issue_paper_id' => $id,
					'dept_nr' => $_POST['dept_nr'],
					'ward_nr' => $_POST['ward_nr'],	
					'type' => $_POST['type'],			
					'typeput'=> $_POST['typeput'],
					'date_time_create'=> $_POST['date_time'],
					'nurse' => $_POST['create_id'],
					'history' => $datetime,
					'note' => $_POST['notecreator'],	
					'modify_id' => $_SESSION['sess_user_name'],
					'status_finish' => '0',
					'issue_user'=>'',
					'issue_note'=>'',
					'receive_user'=>''
				);
				$issue_obj->where=' issue_paper_id='.$id;
				$issue_obj->useIssuePaper('issuepaper_info');				
				if($issue_obj->updateDataFromArray($pharma_issue_paper_info,$id)) {
				
					$issue_obj->useIssuePaper('issuepaper');
					$issue_obj->deleteAllMedipotInIssue($id);
					$n = $_POST['maxid'];
					for ($j=1; $j<=$n; $j++)
					{
						if($_POST['medicine'.$j]!=""){
							$pk = 0;
							if($target=='sum') $plus=$_POST['plus'.$j];
							else $plus=$_POST['sum'.$j];
							
							$pharma_issue_paper = array(
							'nr' => $issue_obj->LastInsertPK('nr',$pk),
							'issue_paper_id' => $id,
							'product_encoder' => $_POST['encoder'.$j],
							'product_name' => $_POST['medicine'.$j],
							'units' => $_POST['units'.$j], //30 (vien) = 1 hop
							'sumpres' => $_POST['sumpres'.$j],
							'plus' => $plus,
							'number_request' => $_POST['sum'.$j], //ngay uong 3 lan
							'number_receive' => '',
							'note' => $_POST['note'.$j],
                                'available_product_id'=> $_POST['available_product_id'.$j]
							);
							//$issue_obj->setDataArray(&$pharma_issue_paper);
							$issue_obj->insertDataFromArray($pharma_issue_paper);
						}				
					}	
				
					if(!$no_redirect){
						header("Location:".$patmenu);
						exit;
					}
				}
				else{
                    echo $issue_obj->getLastQuery().'<br>'.$LDDbNoUpdate;
                    $error=TRUE;
                }	

			break;
			
		case 'delete':
				$id = $_POST['IssueId'];
				//prescription_info 
				if($target=='sum'){
					for ($i=0; $i<count($array_presid); $i++){
						$pres_obj->setPresStatusInIssue($array_presid[$i],'0');
					}
				}
				$issue_obj->deleteAllMedipotInIssue($id);
				$issue_obj->deleteIssue($id);				
				if(!$no_redirect){
					header("Location:".$patmenu);
					exit;
				}
				else{
					echo $issue_obj->getLastQuery().' '.$LDDbNoSave;
					$error=TRUE;
				} 

			break;
}



?>