<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
/**
* eComBill 1.0.04 for Care2002 beta 1.0.04 
* (2003-04-30)
* adapted from eComBill beta 0.2 
* developed by ecomscience.com http://www.ecomscience.com 
* GPL License
*/
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('NO_2LEVEL_CHK',1);
define('LANG_FILE','billing.php');

$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
require_once($root_path.'include/care_api_classes/class_prescription.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$eComBill=new eComBill;
$Pres = new Prescription;
$Encounter = new Encounter;
//$db->debug=true;
$returnfile='patientbill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
if($patnum==""){
	$patient_no=$patientno;
}else{
	$patient_no=$patnum;
}
if($target=='nursing'){
	$breakfile=$root_path.'modules/registration_admission/aufnahme_daten_zeigen.php'.URL_APPEND.'&encounter_nr='.$patient_no;
	$returnfile=$root_path.'modules/registration_admission/aufnahme_daten_zeigen.php'.URL_APPEND.'&encounter_nr='.$patient_no;
	$thisfile= basename(__FILE__).URL_APPEND;
}else{
	$breakfile=$root_path.'modules/ecombill/search.php'.URL_APPEND;
	$returnfile= $root_path.'modules/ecombill/search.php'.URL_APPEND;
	$thisfile= basename(__FILE__).URL_APPEND;
}
  //lay thong tin benh nhan xem noi tru hay ngoai tru -->nang
$patqry="SELECT e.* FROM care_encounter AS e WHERE e.encounter_nr=$patient_no";

$resultpatqry=$db->Execute($patqry);
if(is_object($resultpatqry)) $patient=$resultpatqry->FetchRow();
else $patient=array();
$in_out = $patient['encounter_class_nr']; // xet noi tru hay ngoai tru -->nang
//echo $in_out;
// Check if final bill is available, if yes hide new entry of bills and make payment menu items

$chkexists = 0;
$chkfinalresult = $eComBill->checkFinalBillExist($patient_no);     //MP

if(is_object($chkfinalresult)) $chkexists = $chkfinalresult->RecordCount();

// Check if bill(s) exist (both paid and not paid), if yes show view bill and generate final bill menu items
$billexists = 0;
$billqueryresult = $eComBill->checkBillExist($patient_no);
if(is_object($billqueryresult))  $billexists = $billqueryresult->RecordCount();

$presqueryresult = $Pres->getAllPresOfEncounterByBillId($patient_no,'0');
if(is_object($presqueryresult))  $presexists = $presqueryresult->RecordCount();

// Check if payment(s) exist, if yes show view payment menu item
$payexists = 0;
$payqueryresult = $eComBill->checkPaymentExist($patient_no);
if(is_object($payqueryresult))	$payexists = $payqueryresult->RecordCount();

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');
 $smarty2 = new smarty_care('common', FALSE);
# Toolbar title

 $smarty->assign('sToolbarTitle',$LDBilling . ' - ' . $LDPatientNumber . ' : ' . $full_en);

 # href for the return button
 $smarty->assign('pbBack',$returnfile);

# href for the  button
 $smarty->assign('pbHelp',"javascript:gethelp('billing.php','select-service')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDBilling);

 # Collect extra javascrit code

 ob_start();
?>

<Script language=Javascript>
<!--
function subbill() {
	document.patientfrm.action="patient_bill_links.php<?php echo URL_APPEND; ?>";
	document.patientfrm.submit();
}

function subpayment() {
	document.patientfrm.action="patient_payment_links.php<?php echo URL_APPEND; ?>";
	document.patientfrm.submit();
}

function subLT() {
	document.patientfrm.action="select_services.php<?php echo URL_APPEND; ?>&service=LT";
	document.patientfrm.submit();
}

function subHS() {
	document.patientfrm.action="select_services.php<?php echo URL_APPEND; ?>&service=HS";
	document.patientfrm.submit();
}

function show() {
	document.patientfrm.action="patient_payment.php<?php echo URL_APPEND; ?>";
	document.patientfrm.submit();
}

function finalbill() {
	document.patientfrm.action="final_bill.php<?php echo URL_APPEND; ?>";
	document.patientfrm.submit();
}

function showfinalbill() {
	document.patientfrm.action="showfinalbill.php<?php echo URL_APPEND; ?>";
	document.patientfrm.submit();
}

function showfinalbill1() {
    document.patientfrm.action="showfinalbill1.php<?php echo URL_APPEND; ?>";
    document.patientfrm.submit();
}
function xemcongkhaithuoc() {
	document.patientfrm.action="cong_khai_thuoc_tong_hop_vp.php<?php echo URL_APPEND; ?>";
	document.patientfrm.submit();
}
//-->
</script>
<?php 

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

$smarty->assign('sFormTag','<form name="patientfrm"  method="POST" action="" >');

$smarty->assign('sHiddenInputs','<input type="hidden" name="patientno" value="'. $patient_no .'">	
	<input type="hidden" name="lang" value="'. $lang .'">
	<input type="hidden" name="sid" value="'. $sid .'">
	<input type="hidden" name="full_en" value="'. $full_en .'">
	<input type="hidden" name="target" value="'.$target.'">
	<input type="hidden" name="is_discharged" value="'.$is_discharged.'">');

$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'cancel.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');


 # Prepare the submenu icons
$aSubMenuIcon=array(createComIcon($root_path,'add2.jpg','0'),
					createComIcon($root_path,'add2.jpg','0'),
					createComIcon($root_path,'dollarsign.gif','0'),
					createComIcon($root_path,'dollarsign.gif','0'),
					createComIcon($root_path,'button_info.gif','0'),					
					createComIcon($root_path,'dollarsign.gif','0'),
					createComIcon($root_path,'dollarsign.gif','0')
				);


# Prepare the submenu item descriptions

$aSubMenuText=array($LDMuchuong,
                    $LDPleaseSelectHospitalServicesforthePatient,
					$LDPleaseSelectLaboratoryTestsforthePatient,
					$LDViewBillTxt,
					$LDViewPaymentTxt,
					$LDCongKhaiThuocVaTongHopVienPhiTxt,
					$LDGenerateFinalBillTxt,
					$LDPatienthasclearedallthebillsTxT
					);
				
					
# Prepare the submenu item links indexed by their template tags
$aSubMenuItem=array();

$muchuong =0;

//Nếu có cập nhật thì update
if(isset($_POST['discount']))
{
    $muchuong = $_POST['discount']/100;
    $Encounter->updateMuchuong($muchuong,$patientno);
}

$temp = "select muchuong from care_encounter WHERE encounter_nr = $patient_no ";
$temp1=$db->Execute($temp);
if(is_object($temp1)){
    if($temp1->RecordCount()>0){
      $temp2=$temp1->FetchRow();
        $muchuong = $temp2['muchuong'] * 100;

    }
}
if(!$chkexists){
   $aSubMenuItem['LDMuchuong']='<form action="'.$returnfile.'" method="POST">
                             Mức hưởng(0%-100%):
                             <input type="text" id="discount" name="discount" value="'.$muchuong.'" size="10"> %
                             <input type="submit" value="Sửa" name="ok" id="save">';
}


if(!$chkexists && $is_discharged!=1) {
	if($target=='nursing'){
		$aSubMenuItem['LDSelectHospitalServices'] = '<a href="javascript:subHS()"">'.$LDSelectHospitalServices.'</a>';
		$aSubMenuItem['LDSelectLaboratoryTests'] = '<a href="javascript:subLT()"">'.$LDSelectLaboratoryTests.'</a>';
    }else{
        $aSubMenuItem['LDSelectHospitalServices'] = $LDSelectHospitalServices;
        $aSubMenuItem['LDSelectLaboratoryTests'] = $LDSelectLaboratoryTests;

        }
}

if(!$chkexists && ($billexists || $presexists)) {
	$aSubMenuItem['LDViewBill'] = '<a href="javascript:subbill()"">'.$LDViewBill.'</a>';		

}

if(!$chkexists){
	$aSubMenuItem['LDViewPayment'] = '<a href="javascript:subpayment()"">'.$LDViewPayment.'</a>';
//	$aSubMenuItem['LDCongKhaiThuocVaTongHopVienPhi'] = '<a href="javascript:xemcongkhaithuoc()"">'.$LDCongKhaiThuocVaTongHopVienPhi.'</a>';
	if($billexists || $presexists)
		$aSubMenuItem['LDGenerateFinalBill'] = '<a href="javascript:finalbill()"">'.$LDGenerateFinalBill.'</a>';
}
// xet phần hiên phiêu cong khai cho nội trú và k hiện o ngoại trú  ->nang
if(!$chkexists){
    if($in_out==1){
        $aSubMenuItem['LDCongKhaiThuocVaTongHopVienPhi'] = '<a href="javascript:xemcongkhaithuoc()"">'.$LDCongKhaiThuocVaTongHopVienPhi.'</a>';
    } else{
        $aSubMenuItem['LDCongKhaiThuocVaTongHopVienPhi'] = $LDCongKhaiThuocVaTongHopVienPhi;
    }
}

//Neu benh nhan da thanh toan hoa don cuoi cung (xuat vien)
if($chkexists>0) {
    // khi xuat vien nếu nội tru hiện công khai và tong ket, con ngoại tru chi hien tong ket
    if($in_out==1) {
	$aSubMenuItem['LDPatienthasclearedallthebills'] = '<a href="javascript:showfinalbill()"">'.$LDPatienthasclearedallthebills.'</a>';
    $aSubMenuItem['LDCongKhaiThuocVaTongHopVienPhi'] = '<a href="javascript:showfinalbill1()"">'.$LDCongKhaiThuocVaTongHopVienPhi.'</a>';    //nang
    }
    else{
    $aSubMenuItem['LDPatienthasclearedallthebills'] = '<a href="javascript:showfinalbill()"">'.$LDPatienthasclearedallthebills.'</a>';
    }
}
# Create the submenu rows

$iRunner = 0;
while(list($x,$v)=each($aSubMenuItem)){
	$sTemp='';
	ob_start();
		if($cfg['icons'] != 'no_icon') $smarty2->assign('sIconImg','<img '.$aSubMenuIcon[$iRunner].'>');
		$smarty2->assign('sSubMenuItem',$v);
		if (!$chkexists) $smarty2->assign('sSubMenuText',$aSubMenuText[$iRunner]);
			else $smarty2->assign('sSubMenuText',$LDPatienthasclearedallthebillsTxt);
		$smarty2->display('common/submenu_row.tpl');
 		$sTemp = ob_get_contents();
 	ob_end_clean();
	$iRunner++;
	$smarty->assign($x,$sTemp);
}


$smarty->assign('sMainBlockIncludeFile','ecombill/billing_menu_ecombill.tpl');
 /**
 * show Template
 */

$smarty->display('common/mainframe.tpl');
// $smarty->display('debug.tpl');
?>