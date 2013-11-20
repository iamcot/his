<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

    $root_path='../../../';
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_environment_global.php');
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');

    //target= new, update, delete
    if ($isdelete=="delete")
        $target = "delete";
    else
        $target = $_POST['target'];
	
    $patmenu="../manage_pharma/chemical_destroy_chemical_list.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid']."&ward_nr=".$_POST['ward_nr'].'&dept_nr='.$_POST['dept_nr'];
    if(!isset($Cabinet)) $Cabinet=new CabinetPharma;
		
    switch($target){
        case 'new':
        case 'create':
                //destroy info
                $issue_id = $Cabinet->getLastChemicalID();
                if ($issue_id==false)
                    $id=1;
                else
                    $id = $issue_id['destroy_id']+1;

                $datetime = date("Y-m-d G:i:s"); //$_POST['issuecribe_date']

                $Cabinet->useCabinetDestroyChemical('des_info');
                $chemical_destroy_info = array('destroy_id'=> $id,
                                        'dept_nr' => $_POST['dept_nr'],                                        
                                        'typeput' => $_POST['typeput'],
                                        'date_time_create' => $datetime,
                                        'doctor' =>$_SESSION['sess_user_name'],
                                        'history' =>$_POST['history'],
                                        'note'=>$_POST['notecreator'],
                                        'modify_id'=>'',
                                        'status_finish'=>'0',
                                        'user_accept'=>'');
                $Cabinet->insertDataFromArray($chemical_destroy_info);

                //destroy sub
                $Cabinet->useCabinetDestroyChemical('des');
                $n = $_POST['maxid'];
                for ($j=1; $j<=$n; $j++)
                {
                    if($_POST['chemical'.$j]){
                            $pk = 0;
                            $date_exp = formatDate2STD($_POST['exp'.$j],'dd/mm/yyyy');
                            $chemical_destroy = array(
                                    'nr' => $Cabinet->LastInsertPK('nr',$pk),
                                    'destroy_id'=> $id,
                                    'product_encoder'=> $_POST['encoder'.$j],
                                    'product_lot_id'=> $_POST['lotid'.$j],
                                    'ward_nr' => $_POST['ward_nr'],
                                    'exp_date'=> $date_exp,
                                    'cost'=> $_POST['cost'.$j],
                                    'number' =>$_POST['number'.$j],
									'units' =>$_POST['unit'.$j],
                                    'note'=> $_POST['note'.$j]
                            );
                            $Cabinet->insertDataFromArray($chemical_destroy);
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
                $id = $_POST['des_id'];
                $datetime = 'Update '.date("Y-m-d G:i:s");
                $chemical_destroy_info = array('destroy_id'=> $id,
                                        'dept_nr' => $_POST['dept_nr'],
                                        'typeput' => $_POST['typeput'],
                                        'date_time_create' => $_POST['date_time'],
                                        'doctor' =>$_POST['create_id'],
                                        'history' => $datetime,
                                        'note'=>$_POST['notecreator'],
                                        'modify_id'=>$_SESSION['sess_user_name'],
                                        'status_finish'=>'0',
                                        'user_accept'=>''
                );
                $Cabinet->where=' destroy_id='.$id;
                $Cabinet->useCabinetDestroyChemical('des_info');				
                if($Cabinet->updateDataFromArray($chemical_destroy_info,$id)) {

                        $Cabinet->useCabinetDestroyChemical('des');
                        $Cabinet->deleteAllChemicalInDestroy($id);
                        $n = $_POST['maxid'];
                        for ($j=1; $j<=$n; $j++)
                        {
                            if($_POST['chemical'.$j]!=""){
                                $pk = 0;							
                                $chemical_destroy = array(
                                        'nr' => $Cabinet->LastInsertPK('nr',$pk),
                                        'destroy_id'=> $id,
                                        'product_encoder'=> $_POST['encoder'.$j],
                                        'product_lot_id'=> $_POST['lotid'.$j],
                                        'ward_nr' => $_POST['ward_nr'],
                                        'exp_date'=> $_POST['exp'.$j],
                                        'cost'=> $_POST['cost'.$j],
                                        'number' =>$_POST['number'.$j],
										'units' =>$_POST['unit'.$j],
                                        'note'=> $_POST['note'.$j]
                                );							
                                $Cabinet->insertDataFromArray($chemical_destroy);
                            }				
                        }	

                        if(!$no_redirect){
                            header("Location:".$patmenu);
                            exit;
                        }
                }else{
                    echo $Cabinet->getLastQuery().'<br>'.$LDDbNoUpdate;
                    $error=TRUE;
                }	

                break;
			
            case 'delete':
                $id = $_POST['des_id'];
                $Cabinet->deleteAllChemicalInDestroy($id);
                $Cabinet->deleteDestroyChemical($id);				
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