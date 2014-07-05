<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
$top_dir='modules/pharmacy/includes';
require_once($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_issuepaper_medipot.php');
include_once($root_path.'include/care_api_classes/class_cabinet_medipot.php');
include_once($root_path.'include/care_api_classes/class_product.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();
if(!isset($Product)) $Product=new Product;
if(!isset($Cabinet)) $Cabinet=new CabinetMedipot;

$Issue = new IssueMedipot;

if ($res_dept_ward = $Issue->getDeptWard($issue_id)){
    $dept = $res_dept_ward['dept_nr'];
    $ward = $res_dept_ward['ward_nr'];
}

//issue_id, radiovalue
$thisfile=basename(__FILE__);

if($res_type = $Issue->getIssuePaperType($issue_id)){
    $type = $res_type['type'];	//Binh thuong, tong hop
    $typeput = 	$res_type['typeput'];	//0:BHYT, 1:SN, 2:CBTC
}

#Change IssuePaper Status (update status_finish='1')

$receive_med = array_combine($medicine_nr, $receive);
$ix=0;
foreach ($medicine_nr AS $nr)
{
    $ix++;
    $dxcost = 'cost'.$ix;
    $dxavailable_product_id = 'available_product_id'.$ix;
    if($Issue->setReceiveMedicineInIssue($nr,$receive_med[$nr],$$dxavailable_product_id,$$dxcost)){

        if($res_encoder = $Issue->getEncoder($nr))
            $encoder = $res_encoder['product_encoder'];
        //echo $encoder."<br>";
//        unset($list_lotid);
//        $list_lotid = $Product->getListLotIDMedipot_KhoChan($encoder, $receive_med[$nr], $typeput);
        if($$dxavailable_product_id!=''){
//            foreach ($list_lotid as $key => $value) {
                //echo "Lot_id: $key; Number: $value<br />\n";

                #Change number of medicine in care_pharma_available_product (available_number)
                if($Product->UpdateMedipotInMainSub($$dxavailable_product_id,$receive_med[$nr],'-')==false){ //avai number
                    $no_redirect = $Product->getLastQuery();
                    break;
                }

                #Plus/Insert number of medicine in care_pharma_available_department (available_number)
                if($Cabinet->checkExistMedicineInAvaiDept($dept, $ward, $$dxavailable_product_id, $typeput)!=false){
                    $Cabinet->updateMedicineAvaiDept($encoder,$$dxavailable_product_id, $dept, $ward, $receive_med[$nr],'+', $typeput);
                } else {
                    $Cabinet->insertMedicineAvaiDept($$dxavailable_product_id, $dept, $ward, $receive_med[$nr], $typeput);
                }

                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));

                #Insert in care_pharma_department_archive, issue_paper, get_use=1
                $Cabinet->insertArchive($dept_nr, $ward_nr, $encoder, $$dxavailable_product_id, '1', $receive_med[$nr],$$dxcost, $issue_id, 0, 0, 0, 0, $receive_user, $typeput);

//                if($value>0){
                    $Issue->setMedipotReceiveOfPresInIssue($issue_id, $encoder, $value);
//                }
//            }
            if($type==1){
                #Minus number of medicine in care_pharma_available_department (available_number) for list_pres
                if($result=$Issue->getSumPres($issue_id, $encoder))
                    $number_use=$result['sumpres'];

                #Insert in care_pharma_department_archive, list_pres, get_use=0
                $list_lotid_use = $Cabinet->useMedicineAvaiDept($encoder, $dept, $ward, $number_use, $typeput);
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));

                if($list_lotid_use!=''){
                    foreach ($list_lotid_use as $key => $value) {

                        #Insert in care_pharma_department_archive, prescription, get_use=0
                        $Cabinet->insertArchive($dept_nr, $ward_nr, $encoder, $$dxavailable_product_id, '1', $receive_med[$nr],$$dxcost, $issue_id, 0, 0, 0, 0, $receive_user, $typeput);
                    }
                }
                /*
                if($Cabinet->useMedicineAvaiDept($encoder, $dept_nr, $ward_nr, $number_use, $typeput)){			//????
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Cabinet->getLastQuery(), date('Y-m-d H:i:s'));
                    $no_redirect = $Cabinet->getLastQuery();
                    break;
                }

                reset($list_lotid);
                #Insert in care_pharma_department_archive, list_pres, get_use=0
                $Cabinet->insertArchiveUseListPres($dept_nr, $ward_nr, $encoder, $list_lotid, $issue_id, $receive_user);		//????
                */
            }
        }//else $no_redirect = $Product->getLastQuery();
    }else $no_redirect = $Issue->getLastQuery();
}

if(!$no_redirect){
    $Issue->setInfoPersonWhenIssue($issue_id,$issue_user,$noteissue,$receive_user);
    $Issue->setIssueStatusFinish($issue_id,'1');
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

$patmenu="../pharma_request_medipot_ward.php".URL_REDIRECT_APPEND."&full_en=".$_POST['encounter_nr']."&lang=".$_POST['lang']."&target=$target&pid=".$_SESSION['sess_pid']."&radiovalue=".$radiovalue."&typeSumDepot=".$typeSumDepot."&user_origin=".$user_origin;



if(!$no_redirect){
    header("Location:".$patmenu);
    exit;
}
else{
    echo '<center>'.$LDKhongCapPhat.'<p><a href="'.$patmenu.'"><img src="'.$root_path.'gui/img/control/default/vi/vi_back2.gif"></a></center>';
}

?>