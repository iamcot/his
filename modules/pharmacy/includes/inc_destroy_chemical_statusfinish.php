<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require_once($root_path.'include/core/inc_environment_global.php');
    include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
	require_once($root_path.'include/core/access_log.php');
	$logs = new AccessLog();
    if(!isset($Cabinet)) $Cabinet=new CabinetPharma;
    
    $receive_chemical = array_combine($chemical_nr, $receive);

    foreach ($chemical_nr AS $nr) 
    { 
        if($chemical = $Cabinet->getChemicalInDestroy($nr)){
            $encoder = $chemical['product_encoder'];
            $lotid = $chemical['product_lot_id'];
            $number = $receive_chemical[$nr];

            $Cabinet->updateChemicalAvaiDept($encoder, $lotid, $dept_nr, $ward_nr, $number,'-', $typeput);
			
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));	

            #Insert in care_pharma_department_archive, destroy, get_use=0
            $Cabinet->insertChemicalArchive($dept_nr, $ward_nr, $encoder, $lotid, '0', $number, 0, 0, 0, 0, $report_id, $user_accept, $typeput);
        } else {
            $no_redirect = $Cabinet->getLastQuery();
            break;
        }
    } 
    if(!$no_redirect){
        $Cabinet->setInfoPersonWhenDestroyChemical($report_id,$user_accept);
		$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));	
        $Cabinet->setDestroyChemicalStatusFinish($report_id,'1');
    }
    #Go back to previous page

    $patmenu="../pharma_request_chemical_destroy.php".URL_REDIRECT_APPEND."&target=$target&pid=".$_SESSION['sess_pid']."&user_origin=".$user_origin;

    if(!$no_redirect){
        header("Location:".$patmenu);
        exit;
    }
    else{
        echo $no_redirect;
    } 

?>