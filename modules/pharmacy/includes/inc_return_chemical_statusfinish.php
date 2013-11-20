<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require_once($root_path.'include/core/inc_environment_global.php');
    include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
    include_once($root_path.'include/care_api_classes/class_product.php');
    if(!isset($Product)) $Product=new Product;
    if(!isset($Cabinet)) $Cabinet=new CabinetPharma;
    
    $receive_chemical = array_combine($chemical_nr, $receive);

    foreach ($chemical_nr AS $nr) 
    { 
        if($chemical = $Cabinet->getChemicalInReturn($nr)){
            $encoder = $chemical['product_encoder'];
            $lotid = $chemical['product_lot_id'];
            $ward_nr = $chemical['ward_nr'];
            $number = $receive_chemical[$nr];

            #Minus number of medicine in care_pharma_available_department (available_number)
            $Cabinet->updateChemicalAvaiDept($encoder, $lotid, $dept_nr, $ward_nr, $number,'-', $typeput);

            #Change number of medicine in care_pharma_available_product (available_number)
            $Product->updateChemicalAvaiProduct($encoder, $lotid, $number,'+', $typeput);
			
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Product->getLastQuery(), date('Y-m-d H:i:s'));

            #Insert in care_pharma_department_archive, return, get_use=0
            $Cabinet->insertChemicalArchive($dept_nr, $ward_nr, $encoder, $lotid, '0', $number, 0, 0, 0, $report_id, 0, $user_accept, $typeput);
        }else{
                $no_redirect = $Cabinet->getLastQuery();
                break;
        }
    }
    if(!$no_redirect){
        $Cabinet->setInfoPersonWhenReturnChemical($report_id,$user_accept);
		$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
        $Cabinet->setReturnChemicalStatusFinish($report_id,'1');
    }

    #Go back to previous page
    $patmenu="../pharma_request_chemical_return.php".URL_REDIRECT_APPEND."&target=$target&pid=".$_SESSION['sess_pid']."&user_origin=".$user_origin;

    if(!$no_redirect){
        header("Location:".$patmenu);
        exit;
    }
    else{
        echo $no_redirect;
    } 

?>