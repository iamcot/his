<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require_once($root_path.'include/core/inc_environment_global.php');
    include_once($root_path.'include/care_api_classes/class_pharma.php');
    include_once($root_path.'include/care_api_classes/class_product.php');
	require_once($root_path.'include/core/access_log.php');
	$logs = new AccessLog();
    if(!isset($Product)) $Product=new Product;
    if(!isset($Pharma)) $Pharma=new Pharma;

	$thisfile=basename(__FILE__);
	
    //report_id, put_in_person, total_money, user_accept, typeput, hoidongkiemnhap, ngaynhap, hinhthucthanhtoan

    $receive_chemical = array_combine($chemical_nr, $receive);
    foreach ($chemical_nr AS $nr) 
    { 
            //Update number of chemical
            if($Pharma->setReceiveChemicalInPutIn($nr,$receive_chemical[$nr])!=false){

                    //Get encoder
                    if($chemical=$Pharma->getChemicalInPutIn($nr)){
                            //Update product_main
                            $Product->updateChemicalProductMain($chemical['product_encoder'],$receive_chemical[$nr],'+');

                            //Update or Insert product_main_sub
                            if($Product->checkExistChemicalInLotid($chemical['product_encoder'],$chemical['lotid'],$typeput)!=false){
                                    //$encoder,$lotid,$number,$cost,$cal, $typeput
									$Product->UpdateChemicalInMainSub($chemical['product_encoder'], $chemical['lotid'], $receive_chemical[$nr], $chemical['price'], '+', $typeput);
                            } else {
                                    //$encoder,$lotid,$number,$cost,$cal, $typeput
									$Product->InsertChemicalInMainSub($chemical['product_encoder'], $chemical['lotid'], $chemical['product_date'], $chemical['exp_date'], $receive_chemical[$nr],$chemical['price'], $typeput);
                            }
						$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Product->getLastQuery(), date('Y-m-d H:i:s'));	
                    }

            }else {
                    $no_redirect = $Pharma->getLastQuery();
                    break;
            }
    } 

    if(!$no_redirect){
            $Pharma->setChemicalInfoPutInWhenAccept($report_id,$put_in_person,$total_money,$user_accept, $hoidongkiemnhap, $ngaynhap, $hinhthucthanhtoan);
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pharma->getLastQuery(), date('Y-m-d H:i:s'));
            $Pharma->setChemicalPutInStatusFinish($report_id,'1');
    }


    #Go back to previous page

    $patmenu="../chemical_request_khochan_putin.php".URL_REDIRECT_APPEND."&target=$target&pid=".$_SESSION['sess_pid']."&user_origin=".$user_origin;



    if(!$no_redirect){
            header("Location:".$patmenu);
            exit;
    }
    else{
            echo $no_redirect;
    } 

?>