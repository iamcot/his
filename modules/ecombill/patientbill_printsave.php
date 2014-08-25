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

//xét nội trú hay ngoại trú
$patqry="SELECT e.*,p.* FROM care_encounter AS e, care_person AS p WHERE e.encounter_nr=$patientno AND e.pid=p.pid";
$resultpatqry=$db->Execute($patqry);
if(is_object($resultpatqry)) $patient=$resultpatqry->FetchRow();
else $patient=array();
// xem dạng điều trị
$in_out = $patient['encounter_class_nr'];//noi tru hay ngoai tru
if($in_out==1) $in_out_patient= 'Nội trú';
else $in_out_patient='Ngoại trú';

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
/**
ngoại trú thì dùng lại hàm dưới, nếu nội trú thì lấy care_pharma_prescription_issue  và cập nhật vào
 */
if($in_out ==1){  // lưu hóa đơn hiện tại của nội trú với số lượng tiền thuốc = số thuốc cấp phát*đon giá  ==>n

    $presresult = $Pres->getAllPresOfEncounterByBillId_noitru($patientno,'0');
    if(is_object($presresult))
    {
        for($i=0;$i<$presresult->RecordCount();$i++)
        {
            $pres = $presresult->FetchRow();
            //info of service
           // $eComBill->createBillItem_noitru($patientno,$pres['date_issue'],$pres['product_encoder'],$pres['sum'],$pres['pres_id'],$pres['create_id'],$presdatetime,$pres['available_product_id'],'1');
          //  $eComBill->createBillItem($patientno,$pres['prescription_type'],$pres['total_cost'],'1',$pres['total_cost'],$presdatetime,'1',$billno);
            $Pres->setPresStatusBill_noitru($pres['pres_id'],'1'); //update trang thái đã lưu những thuốc cấp phát của bệnh nhân nội trú là 1 (1: đã lưu)
        }
    }
}   else{

//Save prescription + update bill status
$presresult = $Pres->getAllPresOfEncounterByBillId($patientno,'0');
if(is_object($presresult))
{
	for($i=0;$i<$presresult->RecordCount();$i++)
	{
		$pres = $presresult->FetchRow();
		//info of service  lưu vào table
		$eComBill->createBillItem($patientno,$pres['prescription_type'],$pres['total_cost'],'1',$pres['total_cost'],$presdatetime,'1',$billno);

		$Pres->setPresStatusBill($pres['prescription_id'],$billno);
	}
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

$discount = str_replace(',','',$discount);
$outstd =   str_replace(',','',$outstd);
$billquery="INSERT INTO care_billing_bill (bill_bill_no, bill_encounter_nr, bill_date_time, bill_amount, bill_discount, bill_outstanding, create_id) VALUES ('$billno','$patientno','$presdate','$total','$discount','$outstd','".$_SESSION['sess_login_userid']."')";  //$discount->$discount1,$outstd->$outstd1

$core->Transact($billquery);
$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $billquery, date('Y-m-d H:i:s'));

$eComBill->createPaymentItem($patientno,$receipt_no,$presdatetime,$outstd,'0','0','0','0',$outstd,$billno); //$outstd ->outstd1
$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $eComBill->getLastQuery(), date('Y-m-d H:i:s'));

$patmenu="patient_bill_links.php".URL_REDIRECT_APPEND."&patientno=".$patientno."&full_en=".$full_en;

header("Location:".$patmenu);
exit;
?>



