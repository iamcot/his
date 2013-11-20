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
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
require($root_path.'include/care_api_classes/class_encounter.php');
$eComBill = new eComBill;
$Encounter = new Encounter;

$Encounter->loadEncounterData($patientno);

$payment = $eComBill->listCurrentPaymentsByRecipeNr($receiptid);

$breakfile='patient_payment_links.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
$returnfile='patient_payment_links.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Toolbar title

 $smarty->assign('sToolbarTitle',$LDBilling . ' - ' . $LDPaymentReceipt);

 # href for the return button
 $smarty->assign('pbBack',$returnfile);

# href for the  button
 $smarty->assign('pbHelp',"javascript:gethelp('billing.php','payments')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDBilling . ' - ' . $LDPaymentReceipt);

 # Collect extra javascrit code

 ob_start();
?>
<SCRIPT language="JavaScript">
<!--
	function submitform() {		
		document.confirmpayment.action = "postpayment.php";
		document.confirmpayment.submit();
	}
	function printOut(){
		//window.print();
		urlholder="<?php echo $root_path; ?>modules/pdfmaker/tamung/PhieuTamUngVaoVien.php<?php echo URL_APPEND.'&enc='.$patientno."&tamung_id=".$receiptid ?>";
		win = window.open(urlholder,"PhieuTamUng","width=600,height=400,menubar=no,resizable=yes,scrollbars=yes");
	}
//-->
</SCRIPT>
<?php 
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

$smarty->assign('FormTitle',$LDPatientNumber . ' - ' . $full_en);

$smarty->assign('sFormTag','<form name="confirmpayment" method="POST">');

$smarty->assign('LDGeneralInfo',$LDGeneralInfo);
$smarty->assign('LDPatientName',$LDPatientName);
$smarty->assign('LDPatientNameData',$Encounter->encounter['title'] . ' - ' . $Encounter->encounter['name_last'].' '.$Encounter->encounter['name_first']);
$smarty->assign('LDReceiptNumber',$LDReceiptNumber);
$smarty->assign('LDReceiptNumberData',$receiptid);
$smarty->assign('LDPatientAddress',$LDPatientAddress);
$smarty->assign('LDPatientAddressData',$Encounter->encounter['addr_str'].' '.$Encounter->encounter['addr_str_nr'].'<br>'.$Encounter->encounter['addr_zip'].' '.$Encounter->encounter['addr_citytown_nr']);
$smarty->assign('LDPaymentDate', $LDPaymentDate);
$smarty->assign('LDPaymentDateData', formatDate2Local($payment->fields['payment_date'],$date_format,1));
$smarty->assign('LDPatientType', $LDPatientType );
$smarty->assign('LDPatientTypeData', $Encounter->encounter['encounter_class_nr'] );
$smarty->assign('LDDateofBirth', $LDDateofBirth );
$smarty->assign('LDDateofBirthData', formatDate2Local($Encounter->encounter['date_birth'],$date_format) );
$smarty->assign('LDSex', $LDSex );
$smarty->assign('LDSexData', $Encounter->encounter['sex'] );
$smarty->assign('LDPatientNumber', $LDPatientNumber);
$smarty->assign('LDPatientNumberData', $full_en);
$smarty->assign('LDDateofAdmission', $LDDateofAdmission);
$smarty->assign('LDDateofAdmissionData', formatDate2Local($Encounter->encounter['encounter_date'],$date_format));
$smarty->assign('LDPaymentInformation', $LDPaymentInformation);
$smarty->assign('LDModeofPayment', $LDModeofPayment);
$smarty->assign('LDAmount', $LDAmount);
$smarty->assign('LDInsurance', $LDInsurance);
$smarty->assign('LDInsurance_start', $LDInsurance_start);
$smarty->assign('LDInsurance_exp', $LDInsurance_exp);
$smarty->assign('LDMaKCB', $LDMaKCB);
require_once($root_path.'include/care_api_classes/class_person.php');
$Person = new Person();
if($re_person = $Person->getInfoInsurEnc($Encounter->encounter['pid'])){
	$insurance_nr = $re_person['insurance_nr'];
	$insurance_start = $re_person['insurance_start'];
	$insurance_exp = $re_person['insurance_exp'];
	$madk_kcbbd = $re_person['madkbd'];
	$is_traituyen = $re_person['is_traituyen'];
}else{
	$insurance_nr = $Encounter->encounter['insurance_nr'];
	$insurance_start = $Encounter->encounter['insurance_start'];
	$insurance_exp = $Encounter->encounter['insurance_exp'];
	$madk_kcbbd = $Encounter->encounter['madk_kcbbd'];
	$is_traituyen = $Encounter->encounter['is_traituyen'];
}
$smarty->assign('Insurance', $insurance_nr);
$smarty->assign('Insurance_start', formatDate2Local($insurance_start,$date_format));
$smarty->assign('Insurance_exp', formatDate2Local($insurance_exp,$date_format));
$smarty->assign('makcb', $madk_kcbbd);
$smarty->assign('LDPaymentList', TRUE);

if($payment->fields['payment_cash_amount']!=0)  {
	$smarty->assign('LDModeofPaymentData', $LDCash);	
	$smarty->assign('LDAmountData', number_format($payment->fields['payment_cash_amount']));	
} else if($payment->fields['payment_creditcard_amount'] !=0) {
	$smarty->assign('LDModeofPaymentData', $LDCreditCard);	
	$smarty->assign('LDAmountData', $LDCardNumber.': '.$payment->fields['payment_creditcard_no'] . '<br>' . $payment->fields['payment_creditcard_amount']);		
} else if($payment->fields['payment_cheque_amount'] !=0) {
	$smarty->assign('LDModeofPaymentData', $LDCheck);	
	$smarty->assign('LDAmountData', $LDCheckNumber.': '.$payment->fields['payment_cheque_no'] . '<br>' . $payment->fields['payment_cheque_amount']);		
}
//type=0: tam ung; type!=0: thanh toan hoa don
$typepayment = $payment->fields['payment_type'];
if($typepayment==0) 
	$type = '<b>'.$LDPaymentTypePayment.'</b>'; 
else 
	$type = '<a href="patient_due_first.php'.URL_APPEND.'&patientno='.$patientno.'&target='.$target.'&billid='.$typepayment.'">'.$LDPaymentTypeBill.': '.$typepayment.'</a>'; 
$smarty->assign('LDPaymentType',$LDPaymentType);
$smarty->assign('LDPaymentTypeData',$type); 

if($typepayment)	
	$smarty->assign('pbSubmit','<a href="javascript:window.print();"><input type="image"  '.createLDImgSrc($root_path,'printout.gif','0','middle').'></a>');
else	//tam ung
	$smarty->assign('pbSubmit','<a href="javascript:printOut();"><input type="image"  '.createLDImgSrc($root_path,'printout.gif','0','middle').'></a>');
	
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');

/**
* show Template
*/
$smarty->assign('sHiddenInputs','<input type="hidden" name="patientno" value="'. $patientno .'">
								<input type="hidden" name="hidden" value="C6#C7#C8#">
								<input type="hidden" name="lang" value="'. $lang .'">
								<input type="hidden" name="sid" value="'. $sid .'">
								<input type="hidden" name="full_en" value="'. $full_en .'">
								<input type="hidden" name="target" value="'. $target .'">');

$smarty->assign('sMainBlockIncludeFile','ecombill/bill_payment_header.tpl');

$smarty->display('common/mainframe.tpl');
?>