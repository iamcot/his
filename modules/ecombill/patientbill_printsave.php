<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require('./roots.php');
//define('NO_CHAIN',1);
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','billing.php');
$local_user='aufnahme_user';

require_once($root_path.'include/care_api_classes/class_core.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
require_once($root_path.'include/care_api_classes/class_prescription.php');
require_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
require_once($root_path.'include/core/access_log.php');

$eComBill = new eComBill;
$core= new Core;
$Pres = new Prescription;
$PresMed = new PrescriptionMedipot;


$logs = new AccessLog();
$thisfile=basename(__FILE__);

//$db->debug=true;
$presdate=date("Y-m-d");
$presdatetime=date("Y-m-d H:i:s");


$ergebnis = $eComBill->listPayments();
if(is_object($ergebnis)) $cntergebnis=$ergebnis->RecordCount();
//check for empty set
if($cntergebnis !=0) {
	$result=$ergebnis->FetchRow();
	$receipt_no=$result['payment_receipt_no'];

	// add one to receipt number for new bill
	$receipt_no+=1;
} else {
	//generate new bill number
	$ybr="6".$ybr."000000";
	$receipt_no=(int)$ybr;

}

$savebillquery="UPDATE care_billing_bill_item SET bill_item_status='1',bill_item_bill_no='$billno' where bill_item_encounter_nr='$patientno' and bill_item_status='0'";

$core->Transact($savebillquery);

//Save prescription + update bill status
$presresult = $Pres->getAllPresOfEncounterByBillId($patientno,'0');
if(is_object($presresult))
{
	for($i=0;$i<$presresult->RecordCount();$i++)
	{
		$pres = $presresult->FetchRow();
		//info of service
		$eComBill->createBillItem($patientno,$pres['prescription_type'],$pres['total_cost'],'1',$pres['total_cost'],$presdatetime,'1',$billno);
			
		$Pres->setPresStatusBill($pres['prescription_id'],$billno);
	}
}
//Save medipot + update bill status
$medresult = $PresMed->getAllPresOfEncounterByBillId($patientno,'0');
if(is_object($medresult))
{
	for($i=0;$i<$medresult->RecordCount();$i++)
	{
		$pres = $medresult->FetchRow();
		//info of service
		$eComBill->createBillItem($patientno,$pres['prescription_type'],$pres['total_cost'],'1',$pres['total_cost'],$presdatetime,'1',$billno);
			
		$PresMed->setPresStatusBill($pres['prescription_id'],$billno);
	}
}
//Save chemical + update bill status
$cheresult = $Pres->getAllChemicalOfEncounterByBillId($patientno,'0');
if(is_object($cheresult))
{
	for($i=0;$i<$cheresult->RecordCount();$i++)
	{
		$pres = $cheresult->FetchRow();
		//info of service
		$eComBill->createBillItem($patientno,$pres['prescription_type'],$pres['total_cost'],'1',$pres['total_cost'],$presdatetime,'1',$billno);
			
		$Pres->setChemicalStatusBill($pres['prescription_id'],$billno);
	}
}


$billquery="INSERT INTO care_billing_bill (bill_bill_no, bill_encounter_nr, bill_date_time, bill_amount, bill_discount, bill_outstanding, create_id) VALUES ('$billno','$patientno','$presdate','$total','$discount1','$outstd1','".$_SESSION['sess_login_userid']."')";

$core->Transact($billquery);
$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $billquery, date('Y-m-d H:i:s'));

$eComBill->createPaymentItem($patientno,$receipt_no,$presdatetime,$outstd1,'0','0','0','0',$outstd1,$billno);
$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $eComBill->getLastQuery(), date('Y-m-d H:i:s'));

$patmenu="patient_bill_links.php".URL_REDIRECT_APPEND."&patientno=".$patientno."&full_en=".$full_en;

header("Location:".$patmenu);
exit;
?>



