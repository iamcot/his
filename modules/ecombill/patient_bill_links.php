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
//$db->debug=true;
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
require_once($root_path.'include/care_api_classes/class_prescription.php');

$eComBill = new eComBill;
$Pres = new Prescription;

$breakfile='patientbill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$patientno.'&target='.$target;
$returnfile='patientbill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$patientno.'&target='.$target;

# Load totalbill,totalpayment,due
//total bill (paid)
$totalbill=0;
$billqry="SELECT SUM(bill_amount) AS sum, SUM(bill_discount) AS sumdis, create_id FROM care_billing_bill WHERE bill_encounter_nr='$patientno'";
$resultbillqry=$db->Execute($billqry);
	if(is_object($resultbillqry)){

		$buffer=$resultbillqry->FetchRow();
		$totalbill=$buffer['sum'];
		$totaldis=$buffer['sumdis'];
	}
	
//items still not paid	
$listitemnotpaid = $eComBill->listAllTotalCostNotPaid($patientno);	
if(is_object($listitemnotpaid)){
	while ($itemnotpaid =$listitemnotpaid->FetchRow()) { 
		$totalbill += $itemnotpaid['total'];
	}
}

//payment
$totalpayment=0;
$paymentqry="SELECT SUM(payment_amount_total) AS sum, create_id FROM care_billing_payment WHERE payment_encounter_nr='$patientno'";
$resultpaymentqry=$db->Execute($paymentqry);
	if(is_object($resultpaymentqry)){

		$buffer=$resultpaymentqry->FetchRow();
		$totalpayment=$buffer['sum'];
	}

$due=$totalbill-$totalpayment-$totaldis;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Toolbar title

 $smarty->assign('sToolbarTitle',$LDBilling . ' - ' . $BillList);

 # href for the return button
 $smarty->assign('pbBack',$returnfile);

# href for the  button
 $smarty->assign('pbHelp',"javascript:gethelp('billing.php','bills')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDBilling . ' - ' . $BillList);

 # Collect extra javascrit code

 ob_start();
?>
<Script language=Javascript>
function showbill(billid) {	
	document.billlinks.action="patient_due_first.php<?php echo URL_APPEND; ?>&billid="+billid;
	document.billlinks.submit();
}
function showfinalbill() {	
	document.billlinks.action="showfinalbill.php<?php echo URL_APPEND; ?>";
	document.billlinks.submit();
}
</script>
<?php 
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

$smarty->assign('FormTitle',$LDPatientNumber . ' - ' . $patientno);
$sepChars=array('-','.','/',':',',');

$smarty->assign('LDReceiptNumber',$LDBillNo);
$smarty->assign('LDReceiptDateTime',$LDBillDate);
$smarty->assign('LDReceiptUser',$LDNhanVien);

$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');

/**
* show Template
*/

//List all Bill paid
$sListRows='';
$billqueryresult = $eComBill->listCurrentBills($patientno);
if(is_object($billqueryresult)) {
	while ($result=$billqueryresult->FetchRow()) { 
	    $smarty->assign('itemNr',"<a href=javascript:showbill('".$result['bill_bill_no']."')>".$result['bill_bill_no']."</a>" ); 
		 
		$texttime = substr($result['create_time'],-8);
		$textdate = formatDate2Local($result['create_time'],"DD/MM/YYYY",false,false,$sepChars);		
	    $smarty->assign('date', $textdate.' '.$texttime);
		
		$username = $eComBill->GetUserName($result['create_id']);
		$smarty->assign('create_name', $username);
	
	    ob_start();
		$smarty->display('ecombill/bill_payment_line.tpl');
		$sListRows = $sListRows.ob_get_contents();
		ob_end_clean(); 
	}
}

//List all Item of this Encounter not paid (bill_item_status=0)
$chkfinalresult = $eComBill->listBillsByEncounter($patientno);
$chkpres = $Pres->getAllPresOfEncounterByBillId($patientno,'0');

	
$finaldate = '';   
$finalno = '';
if(is_object($chkfinalresult) || is_object($chkpres)) {
	if(is_object($chkfinalresult))
		$chkexists = $chkfinalresult->RecordCount();
	if(is_object($chkpres))
		$chkpresexists = $chkpres->RecordCount();
	$check = $chkexists + $chkpresexists;
	
	if($check==0) {	//if not have currentbill, show finalbill
		$result=$chkfinalresult->FetchRow();
		$finaldate=$result['final_date'];
		$finalno=$result['final_bill_no'];
		
		$smarty->assign('itemNr', '<a href=javascript:showfinalbill()>'. $LDFinalBill .'</a>');
		$smarty->assign('date', formatDate2Local($result['final_date'],$date_format));
		
		$username = $eComBill->GetUserName($result['create_id']);
		$smarty->assign('create_name', $username);

	} else {	//Show currentbill
		$smarty->assign('itemNr', '<a href=javascript:showbill(\'currentbill\')>'.$LDCurrentBill.'</a>');
		$smarty->assign('date', formatDate2Local(date("Y-m-d"),$date_format));
		$smarty->assign('create_name', $_SESSION['sess_user_name']);
	}	

	ob_start();
	$smarty->display('ecombill/bill_payment_line.tpl');
	$sListRows = $sListRows.ob_get_contents();
	ob_end_clean(); 
}
 
$smarty->assign('ItemLine',$sListRows);



$smarty->assign('LDTotalBillAmount',$LDTotalBillAmount);
$smarty->assign('LDTotalBillAmountValue',"<b>".number_format($totalbill)."</b>");
$smarty->assign('LDOutstandingAmount',$LDOutstandingAmount1);
$smarty->assign('LDOutstandingAmountValue',number_format($totalpayment+$totaldis));
$smarty->assign('LDAmountDue',$LDAmountDue);
$smarty->assign('LDAmountDueValue',number_format($due));

$smarty->assign('sFormTag','<form name="billlinks" method="POST">');
$smarty->assign('sHiddenInputs','<input type="hidden" name="patientno" value="'. $patientno .'">
								<input type="hidden" name="finalbilldate" value="'. $finaldate .'">
								<input type="hidden" name="finalbillno" value="' . $finalno .'">
								<input type="hidden" name="full_en" value="'. $full_en .'">
								<input type="hidden" name="target" value="'. $target .'">
								<input type="hidden" name="lang" value="'. $lang .'">
								<input type="hidden" name="sid" value="'. $sid .'">');

$smarty->assign('sMainBlockIncludeFile','ecombill/bill_payment.tpl');

$smarty->display('common/mainframe.tpl');
?>