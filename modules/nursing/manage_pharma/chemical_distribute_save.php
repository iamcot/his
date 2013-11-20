<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

    $root_path='../../../';
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_environment_global.php');
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');

    extract($_POST);
    $patmenu="chemical_distribute_chemical.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid']."&ward_nr=".$ward_nr.'&dept_nr='.$dept_nr;
    if(!isset($Cabinet)) $Cabinet=new CabinetPharma;

    //dept_nr, maxid, itemid
    $listid = explode('_',$itemid);
    
    foreach ($chemical as $key => $value){
        $proid_ward = explode('_',$key);
        $result = $Cabinet->getChemicalAvaiDeptIfExist($proid_ward[0], $dept_nr, $proid_ward[1]);

        if(is_object($result) && $result->RecordCount()){ 									//if exist-> update		
            $itemrow=$result->FetchRow();
            if($Cabinet->updateChemicalAvaiDept_phanphoi($itemrow['ID'], $value))
                $no_redirect=='';
            else{	
                $no_redirect = $Cabinet->getLastQuery().' '.$LDDbNoSave;
                break;
            }

        }else{																				//if not-> insert	
            if($Cabinet->insertChemicalAvaiDept_phanphoi($proid_ward[0], $dept_nr, $proid_ward[1], $value))
                $no_redirect=='';
            else {	
                $no_redirect = $Cabinet->getLastQuery().' '.$LDDbNoSave;
                break;
            }
        }
    }

    if($no_redirect==''){								//delete old value of dept
        for ($i=1;$i<=$maxid;$i++){			
            if($Cabinet->deleteChemicalAvaiDept($listid[$i]))
                $no_redirect='';
            else {
                $no_redirect = $Cabinet->getLastQuery().' '.$LDDbNoSave;
                break;
            }
        }
    }

    if($no_redirect==''){
        header("Location:".$patmenu);
        exit;
    }
    else{
        echo $no_redirect;
    } 	
?>