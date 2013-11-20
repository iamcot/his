<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
    * GNU General Public License
    * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
    * elpidio@care2x.org,
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    // Erase all cookies used for 2nd level script locking, all following scripst will be locked
    // reset all 2nd level lock cookies
    //require($root_path.'include/core/inc_2level_reset.php');

    if(!isset($_SESSION['sess_path_referer'])) $_SESSION['sess_path_referer'] = "";
    if(!isset($_SESSION['sess_user_origin'])) $_SESSION['sess_user_origin'] = "";

    $breakfile=$root_path.'modules/pharmacy/pharmacy.php'.URL_APPEND;

    $_SESSION['sess_path_referer']=$top_dir.basename(__FILE__);
    $_SESSION['sess_user_origin']='pharma';
    require ($root_path.'include/care_api_classes/class_access.php');
    $access = new Access($_SESSION['sess_login_userid'],$_SESSION['sess_login_pw']);
    $hideOrder = 0;
    if(ereg("_a_1_pharmadbadmin",$access->PermissionAreas()))
            $hideOrder = 1;

    $this_file="apotheke.php";

?>
<style type="text/css">
.table1 {
	border-bottom: solid 1px #C3C3C3;
	border-top: solid 1px #C3C3C3;
	border-left: solid 1px #C3C3C3;
	border-right: solid 1px #C3C3C3;
}
</style>
<script language="javascript">
<!--
function closewin() {
	location.href='startframe.php?sid=<?php echo "$sid&lang=$lang";?>';
}

</script>

<?php

//****************************************************************************************************************************************
/**
 * LOAD Smarty
 */

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',$LDPharmacy.' :: '.$LDPharmaAllocation);

 $smarty->assign('breakfile',$breakfile);

 $smarty->assign('Name',$LDPharmaAllocation);

 if(isset($stb) && $stb) $smarty->assign('sOnLoadJs','onLoad="startbot()"');

 ob_start();
$sTemp = ob_get_contents();
ob_end_clean();

// Append javascript to JavaScript block

 $smarty->append('JavaScript',$sTemp);

 $img_allocation=createComIcon($root_path,'pharma.jpg','0');
 $smarty->assign('LDRequestMedicineImg', $img_allocation);
  $smarty->assign('line', '--------------');
 $img_vtyt=createComIcon($root_path,'storage.gif','0');
 $smarty->assign('LDRequestMedipotImg', $img_vtyt);
 
 $smarty->assign('LDForKhoChan',$LDForKhoChan); //Cho kho chan
 $smarty->assign('LDRequestKhoChanPutIn','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khochan_putin" title="'.$LDRequestKhoChanPutInTxt.'" >'.$LDRequestKhoChanPutIn.'</a>');
 
  $smarty->assign('LDRequestPayOut','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khochan_payout" title="'.$LDRequestPayOutTxt.'" >'.$LDRequestPayOut.'</a>');
 
 $smarty->assign('LDRequestKhoChanPutInMedipot','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khochan_putin_med" title="'.$LDRequestKhoChanPutInMedipotTxt.'" >'.$LDRequestKhoChanPutInMedipot.'</a>');
 $smarty->assign('LDRequestPayOutMedipot','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khochan_payout_med" title="'.$LDRequestPayOutMedipotTxt.'" >'.$LDRequestPayOutMedipot.'</a>');
 
 $smarty->assign('LDForKhoLe',$LDForKhoLe); //Cho kho le
 $smarty->assign('LDRequestKhoLePutIn','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khole_putin" title="'.$LDRequestKhoLePutInTxt.'" >'.$LDRequestKhoLePutIn.'</a>');
 
 
  
 $smarty->assign('LDRequestKhoLePutInMedipot','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khole_putin_med" title="'.$LDRequestKhoLePutInMedipotTxt.'" >'.$LDRequestKhoLePutInMedipot.'</a>');
 
 $smarty->assign('LDForAllDept',$LDForAllDept); //Cho cac phong khoa
 $smarty->assign('LDRequestMedicinePatient','<a href="apotheke-pass.php'.URL_APPEND.'&mode=pres" title="'.$LDRequestMedicinePatientTxt.'">'.$LDRequestMedicinePatient.'</a>');
 $smarty->assign('LDRequestMedicineWard','<a href="apotheke-pass.php'.URL_APPEND.'&mode=issuepaper" title="'.$LDRequestMedicineWardTxt.'">'.$LDRequestMedicineWard.'</a>');
 $smarty->assign('LDRequestMedicineReturn','<a href="apotheke-pass.php'.URL_APPEND.'&mode=return" title="'.$LDRequestMedicineReturnTxt.'">'.$LDRequestMedicineReturn.'</a>');
 $smarty->assign('LDRequestMedicineDestroy','<a href="apotheke-pass.php'.URL_APPEND.'&mode=destroy" title="'.$LDRequestMedicineDestroyTxt.'">'.$LDRequestMedicineDestroy.'</a>');

 $smarty->assign('LDRequestMedipotPatient','<a href="apotheke-pass.php'.URL_APPEND.'&mode=pres_med" title="'.$LDRequestMedipotPatientTxt.'">'.$LDRequestMedipotPatient.'</a>');
 $smarty->assign('LDRequestMedipotWard','<a href="apotheke-pass.php'.URL_APPEND.'&mode=issuepaper_med" title="'.$LDRequestMedipotWardTxt.'">'.$LDRequestMedipotWard.'</a>');
 $smarty->assign('LDRequestMedipotReturn','<a href="apotheke-pass.php'.URL_APPEND.'&mode=return_med" title="'.$LDRequestMedipotReturnTxt.'">'.$LDRequestMedipotReturn.'</a>');
 $smarty->assign('LDRequestMedipotDestroy','<a href="apotheke-pass.php'.URL_APPEND.'&mode=destroy_med" title="'.$LDRequestMedipotDestroyTxt.'">'.$LDRequestMedipotDestroy.'</a>');
 
 //**************************************************************************
 //**************************************************************************
 //Hoa chat
 //**************************************************************************
 //**************************************************************************
 
 $img_allocation1=createComIcon($root_path,'Chemical.jpg','0');
 $smarty->assign('LDRequestChemicalImg', $img_allocation1);
 
 $smarty->assign('LDRequestChemicalPutInkhochan','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khochan_putin_chemical" title="'.$LDRequestChemicalPutInTxt.'" >'.$LDRequestChemicalPutIn.'</a>');
 
 $smarty->assign('LDRequestChemicalPayOut','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khochan_payout_chemical" title="'.$LDRequestChemicalPayOutTxt.'" >'.$LDRequestChemicalPayOut.'</a>');
 
 $smarty->assign('LDRequestChemicalPutIn','<a href="apotheke-pass.php'.URL_APPEND.'&mode=khole_putin_chemical" title="'.$LDRequestChemicalPutInTxt.'" >'.$LDRequestChemicalPutIn.'</a>');
 
 $smarty->assign('LDRequestChemicalPatient','<a href="apotheke-pass.php'.URL_APPEND.'&mode=pres_chemical" title="'.$LDRequestChemicalPatientTxt.'">'.$LDRequestChemicalPatient.'</a>');
 $smarty->assign('LDRequestChemicalWard','<a href="apotheke-pass.php'.URL_APPEND.'&mode=issuepaper_chemical" title="'.$LDRequestChemicalWardTxt.'">'.$LDRequestChemicalWard.'</a>');
 $smarty->assign('LDRequestChemicalReturn','<a href="apotheke-pass.php'.URL_APPEND.'&mode=return_chemical" title="'.$LDRequestChemicalReturnTxt.'">'.$LDRequestChemicalReturn.'</a>');
 $smarty->assign('LDRequestChemicalDestroy','<a href="apotheke-pass.php'.URL_APPEND.'&mode=destroy_chemical" title="'.$LDRequestChemicalDestroyTxt.'">'.$LDRequestChemicalDestroy.'</a>');
//**************************************************************************
 //**************************************************************************
 
 $smarty->assign('sMainBlockIncludeFile','pharmacy/allocation.tpl');
 $smarty->display('common/mainframe.tpl');
?>
