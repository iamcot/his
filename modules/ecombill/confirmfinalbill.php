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
define('LANG_FILE','billing.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
require($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'classes/money/convertMoney.php');
$eComBill = new eComBill();
$Encounter = new Encounter();

$Encounter->loadEncounterData($patientno);
if($discount=="") $discount=0;
$presdate=date("Y-m-d");

$breakfile='final_bill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
$returnfile='final_bill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;

# Start Smarty templating here
/**
 * LOAD Smarty
 */

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Toolbar title

$smarty->assign('sToolbarTitle',$LDBilling . ' - ' . $LDFinalBillPreview);

# href for the return button
$smarty->assign('pbBack',$returnfile);

# href for the  button
$smarty->assign('pbHelp',"javascript:gethelp('billing.php','final-bill')");

$smarty->assign('breakfile',$breakfile);

# Window bar title
$smarty->assign('title',$LDBilling . ' - ' . $LDFinalBillPreview);

# Collect extra javascrit code

ob_start();
?>
    <SCRIPT language="JavaScript">
        <!--
        function submitform() {
            document.confirmfrmfinal.action = "postfinalbill.php";
            document.confirmfrmfinal.submit();
        }
        //-->
    </SCRIPT>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

$smarty->assign('FormTitle',$LDFinalBillPreview . ' - ' . $full_en);

//$smarty->assign('sFormTag','<form name="confirmfrmfinal" method="POST" action="postfinalbill.php">');
$smarty->assign('sFormTag','<form name="confirmfrmfinal" method="POST" action="postfinalbill.php">');

$smarty->assign('LDGeneralInfo',$LDGeneralInfo);
$smarty->assign('LDPatientName',$LDPatientName);
$smarty->assign('LDPatientNameData',$Encounter->encounter['title'] . ' - ' . $Encounter->encounter['name_last'].' '.$Encounter->encounter['name_first']);
$smarty->assign('LDReceiptNumber',$LDBillNo);

$smarty->assign('LDReceiptNumberData',$final_bill_no);
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
$smarty->assign('LDPatientNumberData', $full_en);
$smarty->assign('LDDateofAdmission', $LDDateofAdmission);
$smarty->assign('LDDateofAdmissionData', formatDate2Local($Encounter->encounter['encounter_date'],$date_format));
$smarty->assign('LDPaymentInformation', $LDFinalBillInfo);
$smarty->assign('LDInsurance', $LDInsurance);
$smarty->assign('Insurance', $Encounter->encounter['insurance_nr']);
$smarty->assign('LDInsurance_start', $LDInsurance_start);
$smarty->assign('Insurance_start', formatDate2Local($Encounter->encounter['insurance_start'],$date_format));
$smarty->assign('LDInsurance_exp', $LDInsurance_exp);
$smarty->assign('Insurance_exp', formatDate2Local($Encounter->encounter['insurance_exp'],$date_format));
$smarty->assign('LDMaKCB', $LDMaKCB);
$smarty->assign('makcb', $Encounter->encounter['madk_kcbbd']);


# Load diagnostics --25/9/11
/*$resultdiagno=$Encounter->listAllDiagnosisResultByEncounter($patientno);

if(is_object($resultdiagno)){
	$item_number=$resultdiagno->RecordCount();
	for($i=0;$i<$item_number;$i++)
	{
		$diagresult=$resultdiagno->FetchRow();
		$itemid=$diagresult['report_nr'];
		$item=$diagresult['reporting_dept'];
		$smarty->assign('DiagItemId',$itemid);
		$smarty->assign('DiagItemData',$item); 
		
		ob_start();
		$smarty->display('ecombill/finalbill_diag.tpl');
		$sListRows = $sListRows.ob_get_contents();
		ob_end_clean();
	}
	$smarty->assign('LDReportId',$LDReportId);
	$smarty->assign('LDItemDiag',$sListRows);
}else{
	$smarty->assign('DiagItemId',$None);
	
	ob_start();
	$smarty->display('ecombill/finalbill_diag.tpl');
	$sListRows = $sListRows.ob_get_contents();
	ob_end_clean();
	
	$smarty->assign('LDItemDiag',$sListRows);
}*/
 # inser muchuong vao table person

# Liet ke tat ca cac encounter truoc do --------------------------------------------------------
$arr='';
$smarty->assign('LDTongket', $LDTongket);
$smarty->assign('LDTong', $LDTong);
$smarty->assign('LDBHYT', $LDBHYT);
$smarty->assign('LDConlai', $LDConlai);
$smarty->assign('LDDaThanhToanVaTamUng', $LDDaThanhToanVaTamUng);
$Encounter->listAllEncounterTransfer($patientno, &$arr);
//echo $arr; //#2011000013#2011000001#0
$old_final_bill='';
$list_enc = explode('#',$arr);
$oldenc_totalbill=0; $oldenc_totaloutstanding=0; $oldenc_discount =0;
$oldenc_totalpayment = 0;
for ($k=0; $k<count($list_enc); $k++){
    if($list_enc[$k]!='0' && $list_enc[$k]!=''){
        $smarty->assign('LDEncouterNumber', $LDEncouterNumberPre);
        $smarty->assign('encounterId', $list_enc[$k]);
        $smarty->assign('LDAllBillInfo', $LDAllBillInfo);
        $smarty->assign('LDAllPaymentInfo', $LDAllPaymentInfo);

        $oldenc_each_totalbill=0; $oldenc_each_totaloutstanding=0; $oldenc_each_totalpayment=0; $oldenc_each_discount=0;
        //List bill
        $oldenc_listbill = $eComBill->listCurrentBills($list_enc[$k]);
        if(is_object($oldenc_listbill)){
            $oldbill_temp='';
            while ($oldenc_eachbill = $oldenc_listbill->FetchRow()){

                $oldbill_temp .='
					<tr bgColor="#EBDDE2">
						<td><p>'.$LDBillingId.': '.$oldenc_eachbill['bill_bill_no'].'</p></td>
						<td colspan="4"><p>'.formatDate2Local($oldenc_eachbill['bill_date_time'],$date_format).'</p></td>	
					</tr>		
					<tr><td colspan="5">
							<table width="100%" bgcolor="#FCDFFF" border="0">';
                //items in bill
                $temp_item='';
                $billitem_query = $eComBill->listItemsByBillId($oldenc_eachbill['bill_bill_no']);
                if(is_object($billitem_query)) {
                    while ($billitem_result=$billitem_query->FetchRow()){
                        $billitem_result['bill_item_date']= formatDate2Local($billitem_result['bill_item_date'],$date_format);
                        if ($billitem_result['item_type']=='HS') $item_type=$LDMedicalServices;
                        else if ($billitem_result['item_type']=='LT') $item_type=$LDLaboratoryTests;

                        $temp_item=$temp_item.'<tr>
															<td width="30%">'.$billitem_result['item_description'].'</td>
															<td align="right" width="10%">'.$billitem_result['bill_item_unit_cost'].'</td>
															<td align="right" width="5%">'.$billitem_result['bill_item_units'].'</td>
															<td align="right" >'.$billitem_result['bill_item_amount'].'</td>
															<td align="center">'.$item_type.'</td>
															<td>'.$billitem_result['bill_item_date'].'</td>
														</tr>';
                    }
                }
                $oldbill_temp .=	$temp_item;
                $oldbill_temp .='
							</table>
						</td>
					</tr>
					<tr bgcolor="#FCDFFF">
						<td colspan="3" align="right">'.$LDTotal.': <b>'.$oldenc_eachbill['bill_amount'].'</b></td>
						<td align="right">'.$LDBHYT.': <b>'.$oldenc_eachbill['bill_discount'].'</b></td>
						<td align="center">'.$LDOutstanding.': <b>'.$oldenc_eachbill['bill_outstanding'].'</b></td>
					</tr>';

                $oldenc_each_totalbill += $oldenc_eachbill['bill_amount'];		//tong tien
                $oldenc_each_discount += $oldenc_eachbill['bill_discount'];
                $oldenc_each_totaloutstanding += $oldenc_eachbill['bill_outstanding'];	//tong thanh toan
            }
        }else {	$oldbill_temp = '<tr><td bgcolor="#FCDFFF" colspan="6">'.$NoBill.'</td></tr>';	}
        $smarty->assign('ListAllBill', $oldbill_temp);

        $oldenc_totalbill += $oldenc_each_totalbill;
        $oldenc_totaloutstanding += $oldenc_each_totaloutstanding;
        $oldenc_discount += $oldenc_each_discount;

        //List Payment
        $oldenc_listpayment = $eComBill->listCurrentAdvancedPayments($list_enc[$k]);
        if(is_object($oldenc_listpayment)) {
            $oldpayment_temp=''; $total=0;
            $oldpayment_temp .=	'
					<tr>
						<td colspan="5" bordercolor="#FFFFFF" bgColor="#EBDDE2">'.$LDPaymentId.'&nbsp;</td>
					</tr>';
            while ($oldenc_eachpayment = $oldenc_listpayment->FetchRow()){
                $total = $total + $oldenc_eachpayment['payment_amount_total'];
                $oldpayment_temp .='<tr bgcolor="#FCDFFF">
									<td>'.$oldenc_eachpayment['payment_receipt_no'].'</td>
									<td colspan="2">'.(formatDate2Local($oldenc_eachpayment['payment_date'],$date_format)).'</td>
									<td colspan="2">'.$oldenc_eachpayment['payment_amount_total'].'</td>
								</tr>';
                $oldenc_each_totalpayment += $oldenc_eachpayment['payment_amount_total'];
            }
            $oldpayment_temp .='
					<tr bgcolor="#FCDFFF">
						<td colspan="2" align="right">'.$LDTotal.'</td>
						<td>&nbsp;</td>
						<td colspan="2"><b>'.$total.'</b></td>
					</tr>';
        }else {	$oldpayment_temp = '<tr><td bgColor="#EBDDE2" colspan="5">'.$NoPayment.'</td></tr>';	}
        $smarty->assign('ListAllPayment', $oldpayment_temp);

        $oldenc_totalpayment += $oldenc_each_totalpayment;

        //Tong ket
        $smarty->assign('oldenc_each_totalbill', $oldenc_each_totalbill);
        $smarty->assign('oldenc_each_discount', $oldenc_each_discount);
        $smarty->assign('oldenc_each_paid', $oldenc_each_totaloutstanding+$oldenc_each_totalpayment);
        $smarty->assign('oldenc_each_remain', $oldenc_each_totalbill - ($oldenc_each_totaloutstanding+$oldenc_each_totalpayment+$oldenc_each_discount) );

        ob_start();
        $smarty->display('ecombill/old_final_bill.tpl');
        $old_final_bill = $old_final_bill.ob_get_contents();
        ob_end_clean();
    }
}
$smarty->assign('PastEnc', $old_final_bill);

# Load all bills of this encounter --26/9/11

$sListBillRow='';
$sListItemBillRow='';
$bill_query = $eComBill->listCurrentBills($patientno);
if(is_object($bill_query)) {
    while ($bill_result=$bill_query->FetchRow()) {
        //bill
        $billno = $bill_result['bill_bill_no'];
        $smarty->assign('billno', $billno);
        $smarty->assign('date', formatDate2Local($bill_result['bill_date_time'],$date_format));
        $smarty->assign('amount', number_format($bill_result['bill_amount']));
        $smarty->assign('discountbill', number_format($bill_result['bill_discount']));
        $smarty->assign('outstanding', number_format($bill_result['bill_outstanding']));

        $smarty->assign('LDBillingId', $LDBillingId);
        $smarty->assign('LDTotal', $LDTotal);
        $smarty->assign('LDOutstanding', $LDOutstanding);

        //items in bill
        $temp_item='';
        $billitem_query = $eComBill->listItemsByBillId($billno);
        if(is_object($billitem_query)) {
            while ($billitem_result=$billitem_query->FetchRow()){

                $billitem_result['bill_item_date']= formatDate2Local($billitem_result['bill_item_date'],$date_format);
                if ($billitem_result['item_type']=='HS')
                    $item_type=$LDMedicalServices;
                else if ($billitem_result['item_type']=='LT')
                    $item_type=$LDLaboratoryTests;

                $temp_item=$temp_item.'<tr bgcolor="ffffff">
										<td width="30%">'.$billitem_result['item_description'].'</td>
										<td align="right" width="10%">'.number_format($billitem_result['bill_item_unit_cost']).'</td>
										<td align="right" width="5%">'.$billitem_result['bill_item_units'].'</td>
										<td align="right" >'.number_format($billitem_result['bill_item_amount']).'</td>
										<td align="center">'.$item_type.'</td>
										<td>'.$billitem_result['bill_item_date'].'</td>
									</tr>';
            }
            $smarty->assign('results', $temp_item);
            $smarty->assign('LDMedicalServices', $LDMedicalServices);
            $smarty->assign('LDLaboratoryTests', $LDLaboratoryTests);
        }

        ob_start();
        $smarty->display('ecombill/each_bill_table.tpl');
        $sListBillRow = $sListBillRow.ob_get_contents();
        ob_end_clean();

        $smarty->assign('LDItemAllBill', $sListBillRow);
    }
} else {
    $temp_item='';
    $temp_item=$temp_item.'<tr>
								<td bgColor="#FFFFFF" colspan="6">'.$NoBill.'</td>
							</tr>';

    $smarty->assign('LDItemAllBill',$temp_item);
}

# Load all payments (just advanced: type=0) of this encounter --26/9/11

$sListPaymentRow='';
$paymenttotal=0;
$payment_query = $eComBill->listCurrentAdvancedPayments($patientno);
if(is_object($payment_query)) {
    $smarty->assign('LDPaymentId', $LDPaymentId);
    $smarty->assign('LDTotal', $LDTotal);
    $item_number=$payment_query->RecordCount();
    $temp_item='';
    for($i=0;$i<$item_number;$i++) {
        //payment
        $payment_result=$payment_query->FetchRow();
        $paymenttotal = $paymenttotal + $payment_result['payment_amount_total'];

        $temp_item=$temp_item.'<tr>
									<td bgColor="#FFFFFF">'.$payment_result['payment_receipt_no'].'</td>
									<td colspan="2" bgColor="#FFFFFF">'.(formatDate2Local($payment_result['payment_date'],$date_format)).'</td>
									<td colspan="2" bgColor="#FFFFFF">'.number_format($payment_result['payment_amount_total']).'</td>
								</tr>';

        $smarty->assign('LDListAllPayment', $temp_item);
    }
    $smarty->assign('LDTotalPayment', $LDTotal.':');
    $smarty->assign('LDTotalPaymentValue', number_format($paymenttotal));

} else{
    $smarty->assign('LDPaymentId', $NoPayment);
}




$smarty->assign('LDConfirmBill', TRUE);

$smarty->assign('LDDianosticsInformation', $LDDianosticsInformation);
$smarty->assign('LDEncouterNumberNow', $LDEncouterNumberNow);
$smarty->assign('LDAllBillInformation', $LDAllBillInfo);
$smarty->assign('LDAllPaymentInfo', $LDAllPaymentInfo);
$smarty->assign('LDConvertMoney', $LDConvertMoney);

$smarty->assign('totalbill', number_format($totalbill));
$smarty->assign('totpayment', number_format($totpayment));
$totalremain = $totalbill - $totpayment - $total_discount;
$smarty->assign('totalremain', number_format($totalremain));
$smarty->assign('alldiscountbill', number_format($total_discount));

//Tong ket sau cung
$smarty->assign('LDTotal', $LDTotalBillAmount);		//tat ca hoa don + no truoc
$last_totalbill = $totalremain + ($oldenc_totalbill - $oldenc_discount - $oldenc_totaloutstanding - $oldenc_totalpayment);
$smarty->assign('last_totalbill', number_format($last_totalbill));
if($last_totalbill>=0)
    $sTempMoney = convertMoney($last_totalbill);
else {
    $sTempMoney = convertMoney(-$last_totalbill);
    $sTempMoney = $LDTru.' '.$sTempMoney;
}
$smarty->assign('money_total_Reader',$sTempMoney);

//$smarty->assign('LDDiscountonTotalAmount', $LDDiscountonTotalAmount);
//$smarty->assign('discount', number_format($discount));		//tien bao hiem (tu final_bill)
//$sTempMoney = convertMoney($discount);
//$smarty->assign('money_discount_Reader',$sTempMoney);

$amtwithdisc = $last_totalbill-$discount;
//$totalbill-($discount*$totalbill/100);
//$smarty->assign('LDAmountAfterDiscount', $LDAmountAfterDiscount);
//$smarty->assign('afterdisc', number_format($amtwithdisc));
/*if($amtwithdisc>=0)
	$sTempMoney = convertMoney($amtwithdisc);
else{
	$sTempMoney = convertMoney(-$amtwithdisc);
	$sTempMoney = $LDTru.' '.$sTempMoney;
}
$smarty->assign('money_afterdisc_Reader',$sTempMoney);*/

$smarty->assign('LDAmountPreviouslyReceived', $LDAmountPreviouslyReceived);
$smarty->assign('currentamt','<input type="text" name="currentamt" value="'.$amtwithdisc.'" size="10">');


$smarty->assign('LDCurrentPaidAmount', $LDCurrentPaidAmount);


$smarty->assign('pbSubmit','<a href="javascript:submitform();"><input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');

$smarty->assign('sHiddenInputs','<input type="hidden" name="patientno" value="'. $patientno .'">
							      <input type="hidden" name="totalbill" value="'. $last_totalbill .'">
							      <input type="hidden" name="discount" value="'.$discount .'">
							      <input type="hidden" name="paidamt" value="'. $paidamt .'">
							      <input type="hidden" name="amtdue" value="'.$amtdue .'">
							      <input type="hidden" name="final_bill_no" value="'. $final_bill_no .'">
							      <input type="hidden" name="lang" value="'. $lang .'">
							      <input type="hidden" name="sid" value="'. $sid .'">');

/**
 * show Template
 */
$smarty->assign('sMainBlockIncludeFile','ecombill/bill_payment_header.tpl');

$smarty->display('common/mainframe.tpl');
?>