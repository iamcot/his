<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require('./roots.php');
$local_user='aufnahme_user';
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','billing.php');
require($root_path.'include/core/inc_environment_global.php');
//include_once($root_path."classes/fpdf/fpdf.php");
//include_once($root_path."classes/PHPJasperXML/PHPJasperXML.inc");

require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
require($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
//$db->debug=true;


$eComBill = new eComBill;
$glob_obj = new GlobalConfig;
$Encounter = new Encounter;

$Encounter->loadEncounterData($patientno);

//$hospitalname = substr($glob_obj->getConfigValue('main_info_address') , 0, 30);



# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 # Window bar title
 $smarty->assign('title',$LDBilling . ' - ' . $LDFinalBill);
 $smarty->assign('LDFinalBill',$LDFinalBill);
 
 
// $smarty->assign('FormTitle',$LDFinalBill . ' - ' . $full_en);

$smarty->assign('sFormTag','<form name="printbill" method="POST">');
//Info Patient
$smarty->assign('LDGeneralInfo',$LDGeneralInfo);
$smarty->assign('LDPatientName',$LDPatientName);
$smarty->assign('LDPatientNameData',$Encounter->encounter['title'] . ' - ' . $Encounter->encounter['name_last'].' '.$Encounter->encounter['name_first']);
$smarty->assign('LDReceiptNumber',$LDBillNo);

$smarty->assign('LDReceiptNumberData',$receiptid);
$smarty->assign('LDPatientAddress',$LDPatientAddress);
$smarty->assign('LDPatientAddressData',$Encounter->encounter['addr_str'].' '.$Encounter->encounter['addr_str_nr'].'<br>'.$Encounter->encounter['addr_zip'].' '.$Encounter->encounter['addr_citytown_nr']);
$smarty->assign('LDPaymentDate', $LDBillDate);
$smarty->assign('LDPaymentDateData', formatDate2Local($presdate,$date_format));
$smarty->assign('LDPatientType', $LDPatientType );
$smarty->assign('LDPatientTypeData', $Encounter->encounter['encounter_class_nr'] );
$smarty->assign('LDDateofBirth', $LDDateofBirth );
$smarty->assign('LDDateofBirthData', formatDate2Local($Encounter->encounter['date_birth'],$date_format) );
$smarty->assign('LDSex', $LDSex );
$smarty->assign('LDSexData', $Encounter->encounter['sex'] );
$smarty->assign('LDPatientNumber', $LDPatientNumber);
$smarty->assign('LDPatientNumberData', $patientno);
$smarty->assign('LDDateofAdmission', $LDDateofAdmission);
$smarty->assign('LDDateofAdmissionData', formatDate2Local($Encounter->encounter['encounter_date'],$date_format));

$smarty->assign('LDPaymentInformation', $LDBillInfo);
$smarty->assign('LDBillList', TRUE);
$smarty->assign('LDDescription', $LDDescription);
$smarty->assign('LDCostPerUnit', $LDCostPerUnit);
$smarty->assign('LDUnits', $LDUnits);
$smarty->assign('LDTotalCost', $LDTotalCost);
$smarty->assign('LDItemType', $LDItemType);

//List items in bill
$oldbilltotal=0;
$oldbdqueryresult = $eComBill->checkBillByBillId($receiptid);
if(is_object($oldbdqueryresult)) $billitemcount = $oldbdqueryresult->RecordCount();


	for ($obc=0;$obc<$billitemcount;$obc++) {

		$oldbd=$oldbdqueryresult->FetchRow();

		$itemdescresult = $eComBill->listServiceItemsByCode($oldbd['bill_item_code']);
		if(is_object($itemdescresult)) $it=$itemdescresult->FetchRow();

		$smarty->assign('DescriptionData', $it['item_description']);
		$smarty->assign('CostPerUnitData', $oldbd['bill_item_unit_cost']);
		$smarty->assign('UnitsData', $oldbd['bill_item_units']);
		$smarty->assign('TotalCostData', $totcost);

		if($it['item_type']=="HS") { 
			$smarty->assign('ItemTypeData', $LDMedicalServices);
		} else if($it['item_type']=="LT") { 
			$smarty->assign('ItemTypeData', $LDLaboratoryTests); 
		}

		if($lb1['item_type']=="HS") { $HStotal=$HStotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units']); }  
		if($lb1['item_type']=="LT") { $LTtotal=$LTtotal+($labres['bill_item_unit_cost'])*($labres['bill_item_units']); }
		
		ob_start();
		$smarty->display('ecombill/bill_payment_header_line.tpl');
		$sListRows = $sListRows.ob_get_contents();
		ob_end_clean(); 		
		
		$oldbilltotal=$oldbilltotal+$oldbd['bill_item_amount'];
	}
	
$smarty->assign('ItemLine',$sListRows);


//total
$smarty->assign('LDTotal', $LDTotal);
$smarty->assign('LDTotalData', $total);
$smarty->assign('LDOutstandingAmount', $LDOutstandingAmount);
$smarty->assign('LDOutstandingAmountData', $outstanding);
$smarty->assign('LDAmountDue', $LDAmountDue);
$smarty->assign('LDAmountDueData', $totaldue);


/**
* show Template
*/

$smarty->display('ecombill/bill_print.tpl');

?>