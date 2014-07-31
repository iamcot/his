<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
$top_dir='modules/pharmacy/includes';
require_once($root_path.'include/core/inc_environment_global.php');
include_once($root_path.'include/care_api_classes/class_prescription.php');
include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
include_once($root_path.'include/care_api_classes/class_product.php');
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();
if(!isset($Product)) $Product=new Product;
if(!isset($Cabinet)) $Cabinet=new CabinetPharma;
//pres_id, radiovalue

$thisfile=basename(__FILE__);

#Change Prescription Status (update status_finish='1')
$Pres = new Prescription;
if ($res_dept_ward = $Pres->getDeptWard($pres_id)){
    $dept = $res_dept_ward['dept_nr'];
    $ward = $res_dept_ward['ward_nr'];
}
if ($temp = $Pres->getTypePut($pres_id)){
    $typeput=$temp['typeput'];
}

$receive_med = array_combine($medicine_nr, $receive);

//Chi tac dong tren kho le va tu thuoc
$ix=0;
    foreach ($medicine_nr AS $nr)
    {
    $ix++; $dxcost = 'cost'.$ix; $dxencoder = 'encoder'.$ix;
    $Pres->updateCostOneMedicine($medicine_nr[$ix],$$dxcost,$receive_med[$nr]);
    }

if(!$no_redirect){
    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $Pres->getLastQuery(), date('Y-m-d H:i:s'));
    $sql = " SELECT prescription_id FROM care_pharma_prescription_info WHERE in_issuepaper='".$pres_id."'";
    $pre_id = $db->Execute($sql);
    for ($i=0;$i<$pre_id->RecordCount();$i++)
    {
        $preid=$pre_id->FetchRow();
        $pr = $preid['prescription_id'] ;
        $Pres->setCostPres1($pr);
    }
   // $totalcost = preg_replace('/,/','',$totalcost);
  //  $Pres->setCostPres($pres_id,$totalcost); }
    //$no_redirect=$Pres->getLastQuery();
}
#Go back to previous page
if (!$radiovalue || $radiovalue=='1')
    $typeInOut='all';
elseif ($radiovalue=='2')
    $typeInOut='inpatient';
else
    $typeInOut='outpatient';

$patmenu="../pharma_request_medicine_ward.php".URL_REDIRECT_APPEND."&full_en=".$_POST['encounter_nr']."&lang=".$_POST['lang']."&target=$target&pid=".$_SESSION['sess_pid']."&radiovalue=".$radiovalue."&typeInOut=".$typeInOut."&user_origin=".$user_origin.'&pres_id='.$pres_id.'&tracker='.$tracker.'&pn='.$pn;
if(!$no_redirect){
    header("Location:".$patmenu);
    exit;
}
else{
    //echo $no_redirect;
}