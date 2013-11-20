<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    $root_path='../../../';
    $top_dir='modules/pharmacy/includes';
    require_once($root_path.'include/core/inc_environment_global.php');
	define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');	
    include_once($root_path.'include/care_api_classes/class_issuepaper.php');
    include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
    include_once($root_path.'include/care_api_classes/class_product.php');
	require_once($root_path.'include/core/access_log.php');
	$logs = new AccessLog();
	
    if(!isset($Product)) $Product=new Product;
    if(!isset($Cabinet)) $Cabinet=new CabinetPharma;

    $Issue = new IssuePaper;

    if ($res_dept_ward = $Issue->getDeptWardChemical($issue_id)){
            $dept = $res_dept_ward['dept_nr']; 
            $ward = $res_dept_ward['ward_nr'];
    }

    //issue_id, radiovalue
	$thisfile=basename(__FILE__);

    if($res_type = $Issue->getIssuePaperChemicalType($issue_id)){
        $type = $res_type['type'];
		$typeput = 	$res_type['typeput'];	//0:BHYT, 1:SN, 2:CBTC
	}	
    #Change IssuePaper Status (update status_finish='1')

    $receive_chemical = array_combine($chemical_nr, $receive);

    foreach ($chemical_nr AS $nr) 
    { 
        if($Issue->setReceiveChemicalInIssue($nr,$receive_chemical[$nr])){

            if($res_encoder = $Issue->getEncoderChemical($nr))
                $encoder = $res_encoder['product_encoder'];	

            unset($list_lotid);
            $list_lotid = $Product->getListChemicalLotID($encoder, $receive_chemical[$nr], $typeput);		 //avai product
			if($list_lotid!=''){

                foreach ($list_lotid as $key => $value) {			
                    if($Product->updateChemicalAvaiProduct($encoder,$key,$value,'-',$typeput)==false){ //avai number
                        $no_redirect = $Product->getLastQuery();
                        break;
                    }
                    if($Cabinet->checkExistChemicalInAvaiDept($dept, $ward, $encoder, $key, $typeput)!=false){
                            $Cabinet->updateChemicalAvaiDept($encoder, $key, $dept, $ward, $value,'+', $typeput);
                    } else {
                            $Cabinet->insertChemicalAvaiDept($encoder, $key, $dept, $ward, $value, $typeput);
                    }
					
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));

                    $Cabinet->insertChemicalArchive($dept, $ward, $encoder, $key, '1', $value, $issue_id, 0, 0, 0, 0, $receive_user, $typeput);
                }
                if($type==1){
                    if($result=$Issue->getSumPresChemical($issue_id, $encoder))
                        $number_use=$result['sumpres'];

                    $list_lotid_use = $Cabinet->useChemicalAvaiDept($encoder, $dept, $ward, $number_use, $typeput);
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
							
                    if($list_lotid_use!=''){
                        foreach ($list_lotid_use as $key => $value) {
                            $Cabinet->insertChemicalArchive($dept, $ward, $encoder, $key, '0', $value, 0, $pres_id, 0, 0, 0, $receive_user, $typeput);
                        }
                    }	
                }	

            }//else $no_redirect = $Product->getLastQuery();
		}else $no_redirect = $Issue->getLastQuery();	
    } 

    if(!$no_redirect){
        $Issue->setInfoPersonWhenIssueChemical($issue_id,$issue_user,$noteissue,$receive_user);
        $Issue->setIssueChemicalStatusFinish($issue_id,'1');
		$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Issue->getLastQuery(), date('Y-m-d H:i:s'));
        if($type==1){
                $Issue->setPresStatusFinish($issue_id,'1');
        }
    }else $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $no_redirect, date('Y-m-d H:i:s'));


    #Go back to previous page
    if (!$radiovalue || $radiovalue=='1')
        $typeSumDepot='all';
    elseif ($radiovalue=='2')
        $typeSumDepot='depot';
    else
        $typeSumDepot='sum';

    $patmenu="../pharma_request_chemical_ward.php".URL_REDIRECT_APPEND."&full_en=".$_POST['encounter_nr']."&lang=".$_POST['lang']."&target=$target&pid=".$_SESSION['sess_pid']."&radiovalue=".$radiovalue."&typeSumDepot=".$typeSumDepot."&user_origin=".$user_origin;



    if(!$no_redirect){
    header("Location:".$patmenu);
    exit;
    }
    else{
        echo '<center>'.$LDKhongCapPhat.'<p><a href="'.$patmenu.'"><img src="'.$root_path.'gui/img/control/default/vi/vi_back2.gif"></a></center>';
    } 

?>