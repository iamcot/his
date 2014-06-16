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
$local_user='aufnahme_user';
define('LANG_FILE','billing.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_ecombill.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'classes/money/convertMoney.php');
$eComBill = new eComBill;
$ward = new Ward;
$Encounter = new Encounter;

$Encounter->loadEncounterData($patientno);//==>nang
//Lay info Benh nhan
$patqry="SELECT e.*,p.* FROM care_encounter AS e, care_person AS p WHERE e.encounter_nr=$patientno AND e.pid=p.pid";

$resultpatqry=$db->Execute($patqry);
if(is_object($resultpatqry)) $patient=$resultpatqry->FetchRow();
else $patient=array();
$mh = $patient['muchuong'];
//echo $mh;
$in_out = $patient['encounter_class_nr'];					//noi tru hay ngoai tru
if($in_out==1) $in_out_patient=$LDInPatient;
else $in_out_patient=$LDOutPatient;

if($patient['sex']=='m') $sex_patient = $LDMale;			//nam hay nu
else $sex_patient = $LDFemale;

$wardname = $ward->WardName($patient['current_ward_nr']);	//thuoc khu phong nao


# Start Smarty templating here
/**
 * LOAD Smarty
 */

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Toolbar title
$smarty->assign('LDFinalBill',$LDFinalBill);
$smarty->assign('sToolbarTitle',$LDFinalBill);

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

        $oldenc_each_totalbill=0;  $oldenc_each_totaloutstanding=0; $oldenc_each_totalpayment=0; $oldenc_each_discount=0;
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
															<td align="right" width="10%">'.number_format($billitem_result['bill_item_unit_cost']).'</td>
															<td align="right" width="5%">'.$billitem_result['bill_item_units'].'</td>
															<td align="right" >'.number_format($billitem_result['bill_item_amount']).'</td>
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
						<td colspan="3" align="right">'.$LDTotal.': <b>'.number_format($oldenc_eachbill['bill_amount']).'</b></td>
						<td align="right">'.$LDBHYT.': <b>'.number_format($oldenc_eachbill['bill_discount']).'</b></td>
						<td align="center">'.$LDOutstanding.': <b>'.number_format($oldenc_eachbill['bill_outstanding']).'</b></td>
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
									<td colspan="2">'.number_format($oldenc_eachpayment['payment_amount_total']).'</td>
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
        $smarty->assign('oldenc_each_totalbill', number_format($oldenc_each_totalbill));
        $smarty->assign('oldenc_each_discount', number_format($oldenc_each_discount));
        $smarty->assign('oldenc_each_paid', number_format($oldenc_each_totaloutstanding+$oldenc_each_totalpayment));
        $smarty->assign('oldenc_each_remain', number_format($oldenc_each_totalbill - ($oldenc_each_totaloutstanding+$oldenc_each_totalpayment+$oldenc_each_discount) ));

        ob_start();
        $smarty->display('ecombill/old_final_bill.tpl');
        $old_final_bill = $old_final_bill.ob_get_contents();
        ob_end_clean();
    }
}
$smarty->assign('PastEnc', $old_final_bill);
//nang
/*
$returnfile='cong_khai_thuoc_tong_hop_vp.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
$smarty->assign('MucHuong', '<form action="'.$returnfile.'" method="POST">
                             Mức hưởng(0%-100%):
                             <input type="text" id="discount" name="discount" value="'.$muchuong.'" size="10"> %
                             <input type="submit" value="Xem" name="ok" id="save">');
$muchuong = $_POST['discount']/100;
if(isset($_POST['discount'])) $Encounter->updateMuchuong($muchuong,$patientno);
$returnfile='cong_khai_thuoc_tong_hop_vp.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
$muchuong = 0;
$temp = "select muchuong from care_encounter WHERE encounter_nr = $patientno ";
$temp1=$db->Execute($temp);
if(is_object($temp1)){
    if($temp1->RecordCount()>0){
        $temp2=$temp1->FetchRow();
        $muchuong = $temp2['muchuong'] * 100;
    }
}
$smarty->assign('MucHuong', '<form action="'.$returnfile.'" method="POST">
                             Mức hưởng(0%-100%):
                             <input type="text" id="discount" name="discount" value="'.$muchuong.'" size="10"> %
                             <input type="submit" value="Xem" name="ok" id="save">');
   */
//Lay ngay xuat vien
if($patient['discharge_date']){
    $datestranfer=formatDate2Local($patient['discharge_date'],$date_format);
    $smarty->assign('LDDateOfTransfer',$LDDateOfTransfer.': '.$datestranfer);
}
else{
    $datestranfer=date('d/m/Y');
    $smarty->assign('LDDateOfTransfer',$LDDateOfTransfer.': ');
}

$tongngaydieutri = round(abs(strtotime(formatDate2STD($datestranfer,'dd/mm/yyyy'))-strtotime($patient['encounter_date']))/86400);

//Action cho nut Close
$breakfile='patientbill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
//$continue = 'confirmfinalbill1.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
//Lay info Hoa don cuoi cung
$finshowqry="SELECT final_bill_no,final_date,final_total_bill_amount,final_discount,final_total_receipt_amount,final_amount_due,final_amount_recieved FROM care_billing_final WHERE final_encounter_nr='$patientno'";
$finshowresult=$db->Execute($finshowqry);
if(is_object($finshowresult)) $final=$finshowresult->FetchRow();

//Lay info all cac phieu tam ung
$paymentqry="SELECT SUM(payment_amount_total) AS sumcost FROM care_billing_payment WHERE payment_encounter_nr='$patientno' AND payment_type='0'";
$paymentresult=$db->Execute($paymentqry);
if(is_object($paymentresult)) $payment=$paymentresult->FetchRow();

# Show Info Patient
$smarty->assign('LDTitleFinalBill',$LDTitleFinalBill);
$smarty->assign('LDLayMuchuong',$LDLayMuchuong.':'.$mh*100 .'%')  ;
$smarty->assign('LDWard',$LDWard.': '.$wardname);

$smarty->assign('LDPatientNumber',$LDPatientNumber.': '.$patientno);
$smarty->assign('LDEncouterNumberNow',$LDEncouterNumberNow);
$smarty->assign('LDPatientNumberData',$patientno);
$smarty->assign('LDPatientType',$LDPatientType.': '.$in_out_patient);
$smarty->assign('LDBillNo',$LDBillNo.': '.$final['final_bill_no']);
$smarty->assign('LDBillDate',$LDBillDate.': '.formatDate2Local($final['final_date'],$date_format));

$smarty->assign('LDPatientName',$LDPatientFullName.': '.$patient['name_last'].' '.$patient['name_first']);
$smarty->assign('LDDateofBirth',$LDDateofBirth.': '.formatDate2Local($patient['date_birth'],$date_format));
$smarty->assign('LDSex',$LDSex.': '.$sex_patient);
$smarty->assign('LDPatientAddress',$LDPatientAddress.': '.$Encounter->encounter['phuongxa_name'].' '.$Encounter->encounter['quanhuyen_name'].'<br>'.$Encounter->encounter['citytown_name']); //==>n
$smarty->assign('LDRoom',$LDRoom.': '.$patient['current_room_nr']);
$smarty->assign('LDBedNr',$LDBedNr.': ');
$smarty->assign('LDDateofAdmission',$LDDateofAdmission.': '.formatDate2Local($patient['encounter_date'],$date_format));
$smarty->assign('LDPaymentTypePayment',$LDPaymentTypePayment.': '.$payment['sumcost']);
$smarty->assign('LDInsurranceNr',$LDInsurranceNr.': '.$patient['insurance_nr']);
$smarty->assign('LDInsurranceDate',$LDInsurranceDate.': '.formatDate2Local($patient['insurance_exp'],$date_format));
$smarty->assign('LDInsurrancePlace',$LDInsurrancePlace.': '.$patient['madk_kcbbd']);
$smarty->assign('LDDiagnosis',$LDDiagnosis.': '.$patient['referrer_diagnosis']);
$smarty->assign('LDSumOfDate',$LDSumOfDate.': '.$tongngaydieutri);
$noItem = '';



ob_start();
?>

    <script language="JavaScript">
        function printOut()
        {
            urlholder="<?php echo $root_path;?>modules/pdfmaker/tamung/congkhaithuoc_vienphi.php<?php echo URL_APPEND; ?>&finalbill_id=<?php echo $final['final_bill_no']; ?>&patientno=<?php echo $patientno; ?>";
            testprintpdf=window.open(urlholder,"PhieuCongKhaiThuocVaVienPhi","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
        }
        /*
        function submitform() {
            document.frmfinal.action ="confirmfinalbill.php";
            document.frmfinal.submit();
        }     */
    </script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);
$smarty->assign('sFormTag','<form name="frmfinal" method="POST">');

# Show info of Prescription

$smarty->assign('LDNr',$LDNr);
$smarty->assign('LDPrescriptionName','1. '.$LDPrescriptionName);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDDate',$LDDate);
$smarty->assign('LDSumUnit',$LDSumUnit);
$smarty->assign('LDEnterPriceUnit',$LDEnterPriceUnit);
$smarty->assign('LDSumCost',$LDSumCost);
$smarty->assign('LDEncouterNumberNow', $LDEncouterNumberNow);


//Lay tat ca thuoc da phat cho benh nhan
$tongtienthuoc=0;
$tongtienBHYT=0;
$tongtienthanhtoan=0;
//Neu noi tru (group_pres=1)
$pres_noitru = "SELECT iss.*, sum(iss.number) AS sum, prs.product_name, prs.note AS unit, prs.cost
					FROM care_pharma_prescription_issue AS iss, care_pharma_prescription AS prs
					WHERE iss.enc_nr='".$patientno."' AND prs.prescription_id=iss.pres_id AND prs.product_encoder=iss.product_encoder
					GROUP BY iss.product_encoder, iss.date_issue
					ORDER BY iss.product_encoder, iss.date_issue";
/*$pres_noitru = "SELECT prs.*,prsinfo.date_time_create,prsinfo.sum_date
			FROM care_pharma_prescription AS prs, care_pharma_prescription_info AS prsinfo, care_pharma_type_of_prescription AS tp
			WHERE prsinfo.encounter_nr='".$patientno."' AND prsinfo.prescription_id=prs.prescription_id
			AND prsinfo.prescription_type=tp.prescription_type
			AND prsinfo.status_finish=1 AND tp.group_pres=1
			ORDER BY prs.prescription_id" */;

$list_item = array();
$list_date = array();
$k=0;
$list_name = array();
$list_info = array();
if($pres_item_noitru=$db->Execute($pres_noitru)){
    for($i=0;$i<$pres_item_noitru->RecordCount();$i++){
        $item = $pres_item_noitru->FetchRow();
        $list_item[$item['product_encoder']][$item['date_issue']]= $item['sum'];   //==>n đổi $item['number'] thành  $item['sum']
        if(!in_array($item['date_issue'], $list_date)){
            $k++;
            $list_date[$k]=$item['date_issue'];
        }
        if(!in_array($item['product_encoder'],$list_name)){
            $list_info[$item['product_encoder']]['name']=$item['product_name'];
            $list_info[$item['product_encoder']]['unit']=$item['unit'];
            $list_info[$item['product_encoder']]['cost']=$item['cost'];
        }
    }
}
$stt=1;
ob_start();
//In dong ngay (co 7 cot)
echo '<tr><td colspan="3"></td><td><table width="100%"><tr>';
//	foreach ($list_date as $d) {
//		echo '<td align="center">'.formatDate2Local($d,'dd/mm/yyyy').'</td>';
//	}
echo '</tr></table></td><td colspan="3"></td></tr>';
//In cac dong thuoc chi tiet
foreach ($list_item as $x => $v) {
    // $x: encoder, $v['date_issue'] = number
    echo '<tr><td>'.$list_info[$x]['name'].' </td>';
    echo '<td align="center">'.$list_info[$x]['unit'].'</td>'; //<td><table width="100%"><tr>
    $tongthuoc=0;
    foreach ($list_date as $v1) {
        //echo '<td align="center">'.$v[$v1].'</td>';
        $tongthuoc += $v[$v1];
    }
    $tongtienthuoc += $tongthuoc*$list_info[$x]['cost'];
    //</tr></table></td>
    //  echo '<td align="right">'.$tongthuoc.'</td><td align="right">'.number_format($list_info[$x]['cost']).'</td><td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']).'</td></tr>';
    //nang
    if($muchuong!=0){
        $tongtienBHYT = $tongthuoc*$list_info[$x]['cost']*$muchuong;
    }else{
        $tongtienBHYT = $tongthuoc*$list_info[$x]['cost']*$mh ;
    }
    $tongtienkhac = '';
    $tongtienthanhtoan = $tongtienthuoc - $tongtienBHYT; // tính so tien benh nhan can tra
    echo '<td align="center">'.$tongthuoc.'</td><td align="right">'.number_format($list_info[$x]['cost']).'</td><td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']).'<td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']*$mh).'<td align="right">'.$tongtienkhac.'<td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']-$tongthuoc*$list_info[$x]['cost']*$mh).'</td></tr>';
 //   echo '<td align="center">'.$tongthuoc.'</td><td align="right">'.number_format($list_info[$x]['cost']).'</td><td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']).'<td align="right">'.$tongtienBHYT.'<td align="right">'.$tongtienkhac.'<td align="right">'.$tongtienthanhtoan.'</td></tr>';
    /////////////////
    $stt++;
}
$sTempPres = $sTempPres.ob_get_contents();
ob_end_clean();
 /*
//thu lấy truyền dịch cho phiếu công khai bênh nội trú==> n
$presqry="SELECT prs.*,prsinfo.date_time_create,prsinfo.sum_date
			FROM care_pharma_prescription AS prs, care_pharma_prescription_info AS prsinfo, care_pharma_type_of_prescription AS tp
			WHERE prsinfo.encounter_nr='$patientno' AND prsinfo.prescription_id=prs.prescription_id
			AND prsinfo.prescription_type=tp.prescription_type
			AND prsinfo.status_finish=1 AND tp.group_pres=1
			ORDER BY prs.prescription_id";
$presresult=$db->Execute($presqry);
if(is_object($presresult))
{
    if($presresult->RecordCount()>0)
    {
        for ($i=0;$i<$presresult->RecordCount();$i++)
        {
            $pres=$presresult->FetchRow();
            $end = date("d/m/Y", strtotime($pres['date_time_create'] . "+".($pres['sum_date']-1)." day"));

            //$smarty->assign('LDItemNr',$stt);
            $smarty->assign('LDItemPrescriptionName',$pres['product_name']);
            $smarty->assign('LDItemUnit',$pres['note']);
            //$smarty->assign('LDItemDate',formatDate2Local($pres['date_time_create'],$date_format).' - '.$end.' ('.$pres['sum_date'].' '.$LDdate.')');
            $smarty->assign('LDItemSumUnit',$pres['number_receive']);
            $smarty->assign('LDItemEnterPriceUnit',($pres['cost']));
            $smarty->assign('LDItemSumCost',number_format($pres['cost']*$pres['number_receive']));
            if($muchuong!=0){
                $smarty->assign('LDItemSumCostBHYT',number_format($pres['cost']*$pres['number_receive']*$muchuong));   //nang
                $smarty->assign('LDItemSumCostKhac','');//nang
                $smarty->assign('LDItemSumCostTra',number_format($pres['cost']*$pres['number_receive'] - $pres['cost']*$pres['number_receive']*$muchuong)); //nang
            }   else{
                $smarty->assign('LDItemSumCostBHYT',number_format($pres['cost']*$pres['number_receive']*$mh));   //nang
                $smarty->assign('LDItemSumCostKhac','');//nang
                $smarty->assign('LDItemSumCostTra',number_format($pres['cost']*$pres['number_receive'] - $pres['cost']*$pres['number_receive']*$mh)); //nang
            }
            $tongtienthuoc += ($pres['cost']*$pres['number_receive']);
            if($muchuong!=0){
                $tongtienBHYT += ($pres['cost']*$pres['number_receive'])*$muchuong;
            }   else{
                $tongtienBHYT += ($pres['cost']*$pres['number_receive'])*$mh;
            }
            $tongtienkhac = '';
            $tongtienthanhtoan += $tongtienthuoc - $tongtienBHYT;
            $stt++;
            ob_start();
            $smarty->display('ecombill/showfinalbill_pres_line.tpl');
            $sTempPres = $sTempPres.ob_get_contents();
            ob_end_clean();
        }
    }
}
 //==>n   */
//Neu ngoai tru (group_pres=0)
$presqry="SELECT prs.*,prsinfo.date_time_create,prsinfo.sum_date
			FROM care_pharma_prescription AS prs, care_pharma_prescription_info AS prsinfo, care_pharma_type_of_prescription AS tp
			WHERE prsinfo.encounter_nr='$patientno' AND prsinfo.prescription_id=prs.prescription_id
			AND prsinfo.prescription_type=tp.prescription_type
			AND prsinfo.status_finish=1 AND tp.group_pres=0
			ORDER BY prs.prescription_id";
$presresult=$db->Execute($presqry);
if(is_object($presresult))
{
    if($presresult->RecordCount()>0)
    {
        for ($i=0;$i<$presresult->RecordCount();$i++)
        {
            $pres=$presresult->FetchRow();
            $end = date("d/m/Y", strtotime($pres['date_time_create'] . "+".($pres['sum_date']-1)." day"));

            //$smarty->assign('LDItemNr',$stt);
            $smarty->assign('LDItemPrescriptionName',$pres['product_name']);
            $smarty->assign('LDItemUnit',$pres['note']);
            //$smarty->assign('LDItemDate',formatDate2Local($pres['date_time_create'],$date_format).' - '.$end.' ('.$pres['sum_date'].' '.$LDdate.')');
            $smarty->assign('LDItemSumUnit',$pres['sum_number']);
            $smarty->assign('LDItemEnterPriceUnit',($pres['cost']));
            $smarty->assign('LDItemSumCost',number_format($pres['cost']*$pres['sum_number']));
            if($muchuong!=0){
                $smarty->assign('LDItemSumCostBHYT',number_format($pres['cost']*$pres['sum_number']*$muchuong));   //nang
                $smarty->assign('LDItemSumCostKhac','');//nang
                $smarty->assign('LDItemSumCostTra',number_format($pres['cost']*$pres['sum_number'] - $pres['cost']*$pres['sum_number']*$muchuong)); //nang
            }   else{
                $smarty->assign('LDItemSumCostBHYT',number_format($pres['cost']*$pres['sum_number']*$mh));   //nang
                $smarty->assign('LDItemSumCostKhac','');//nang
                $smarty->assign('LDItemSumCostTra',number_format($pres['cost']*$pres['sum_number'] - $pres['cost']*$pres['sum_number']*$mh)); //nang
            }
            $tongtienthuoc += ($pres['cost']*$pres['sum_number']);
            if($muchuong!=0){
                $tongtienBHYT += ($pres['cost']*$pres['sum_number'])*$muchuong;
            }   else{
                $tongtienBHYT += ($pres['cost']*$pres['sum_number'])*$mh;
            }
            $tongtienkhac = '';
            $tongtienthanhtoan += $tongtienthuoc - $tongtienBHYT;
            $stt++;
            ob_start();
            $smarty->display('ecombill/showfinalbill_pres_line.tpl');
            $sTempPres = $sTempPres.ob_get_contents();
            ob_end_clean();
        }
    }
}
if($sTempPres!='')
    $smarty->assign('ItemPres',$sTempPres);
else
    $smarty->assign('ItemPres','<tr><td align="center" colspan="7">'.$LDNoPres.'</td></tr>');


# Show info of Depot, Surgery, Laborator...
$smarty->assign('LDContent',$LDContent);
$smarty->assign('LDNumberOf',$LDNumberOf);

//Lay tat ca VTYT cua benh nhan
$tongtienVTYT=0;
$tongtienVTYTBHYT=0;
$tongtienVTYTTra=0;
$smarty->assign('LDDepot','2. '.$LDDepot1);
$depotqry="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_med_prescription AS med, care_med_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
$depotresult=$db->Execute($depotqry);
if(is_object($depotresult))
{
    for ($i=0;$i<$depotresult->RecordCount();$i++)
    {
        $depot=$depotresult->FetchRow();
        $smarty->assign('LDItemContent',$depot['product_name']);
        $smarty->assign('LDItemDate',formatDate2Local($depot['date_time_create'],$date_format));
        $smarty->assign('LDItemNumberOf',$depot['sum_number']);
        $smarty->assign('LDItemUnitCost',$depot['cost']);
       $smarty->assign('LDItemSumCost',number_format($depot['cost']*$depot['sum_number']));
        if($muchuong!=0){
            $smarty->assign('LDItemSumCostBHYT',number_format($depot['cost']*$depot['sum_number']*$muchuong)); //nang
            $smarty->assign('LDItemSumCostKhac','');//nang
            $smarty->assign('LDItemSumCostTra',number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$muchuong)); //nang
        }   else{
            $smarty->assign('LDItemSumCostBHYT',number_format($depot['cost']*$depot['sum_number']*$mh));   //nang
            $smarty->assign('LDItemSumCostKhac','');//nang
            $smarty->assign('LDItemSumCostTra',number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh)); //nang
        }
        $tongtienVTYT += $depot['cost']*$depot['sum_number'];
        if($muchuong!=0){
            $tongtienVTYTBHYT += $depot['cost']*$depot['sum_number']*$muchuong;  //nang
        }   else{
            $tongtienVTYTBHYT += $depot['cost']*$depot['sum_number']*$mh;  //nang
        }
        $tongtienVTYTTra += $tongtienVTYT - $tongtienVTYTBHYT;               //nang
        ob_start();
        $smarty->display('ecombill/showfinalbill_other_line.tpl');
        $sTempMed = $sTempMed.ob_get_contents();
        ob_end_clean();
    }
}
//Lay tat ca Hoa Chat cua benh nhan
$tongtienHC=0;
$tongtienHCBHYT=0;
$tongtienHCTra=0;
//$tongtienBHYT =0;
$cheqry="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_chemical_prescription AS med, care_chemical_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
$cheresult=$db->Execute($cheqry);
if(is_object($cheresult))
{
    for ($i=0;$i<$cheresult->RecordCount();$i++)
    {
        $depot=$cheresult->FetchRow();
        $smarty->assign('LDItemContent',$depot['product_name']);
        $smarty->assign('LDItemDate',formatDate2Local($depot['date_time_create'],$date_format));
        $smarty->assign('LDItemNumberOf',$depot['sum_number']);
        $smarty->assign('LDItemUnitCost',$depot['cost']);
        $smarty->assign('LDItemSumCost',number_format($depot['cost']*$depot['sum_number']));
        if($muchuong!=0){
            $smarty->assign('LDItemSumCostBHYT',number_format($depot['cost']*$depot['sum_number']*$muchuong));   //nang
            $smarty->assign('LDItemSumCostKhac','');//nang
            $smarty->assign('LDItemSumCostTra',number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$muchuong)); //nang
        }   else{
            $smarty->assign('LDItemSumCostBHYT',number_format($depot['cost']*$depot['sum_number']*$mh));   //nang
            $smarty->assign('LDItemSumCostKhac','');//nang
            $smarty->assign('LDItemSumCostTra',number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh)); //nang
        }

        $tongtienHC += $depot['cost']*$depot['sum_number'];
        if($muchuong!=0){
            $tongtienHCBHYT += $depot['cost']*$depot['sum_number']*$muchuong;    //nang
        }   else{
            $tongtienHCBHYT += $depot['cost']*$depot['sum_number']*$mh;    //nang
        }
        $tongtienHCTra += $tongtienHC - $tongtienHCBHYT;                     //nang
        ob_start();
        $smarty->display('ecombill/showfinalbill_other_line.tpl');
        $sTempMed = $sTempMed.ob_get_contents();
        ob_end_clean();
    }
}
if($sTempMed!='')
    $smarty->assign('ItemDepot',$sTempMed);
else
    $smarty->assign('ItemDepot',$noItem);


//Lay tat ca cac items trong tat ca cac hoa don cua benh nhan (tru toa thuoc va VTYT, HC)
$itemresult = $eComBill->listServiceItemsOfEncounter($patientno);
$tongtienDichVu=0;
$tongtienDichVuBHYT =0;
$tongtienDichVuTra=0;
if(is_object($itemresult))
{
   for ($i=0;$i<$itemresult->RecordCount();$i++)
  // for ($i=1;$i<$itemresult->RecordCount();$i++) //nang
    {
        $item=$itemresult->FetchRow();
        $groupnr = $item['item_group_nr'];
        $smarty->assign('LDItemContent',$item['item_description']);
        $smarty->assign('LDItemDate',formatDate2Local($item['bill_item_date'],$date_format));
        $smarty->assign('LDItemNumberOf',$item['bill_item_units']);
        $smarty->assign('LDItemUnitCost',number_format($item['bill_item_unit_cost']));
        $smarty->assign('LDItemSumCost',number_format($item['bill_item_units']*$item['bill_item_unit_cost']));
        if($groupnr==22){ // không cho giảm BHYT của xét nghiệm máu
            $smarty->assign('LDItemSumCostBHYT',number_format($item['bill_item_units']*$item['bill_item_unit_cost']*0));   //nang
            $smarty->assign('LDItemSumCostKhac','');//nang
            $smarty->assign('LDItemSumCostTra',number_format($item['bill_item_units']*$item['bill_item_unit_cost'] - $item['bill_item_units']*$item['bill_item_unit_cost']*0)); //nang
        }   else{
            $smarty->assign('LDItemSumCostBHYT',number_format($item['bill_item_units']*$item['bill_item_unit_cost']*$mh));   //nang
            $smarty->assign('LDItemSumCostKhac','');//nang
            $smarty->assign('LDItemSumCostTra',number_format($item['bill_item_units']*$item['bill_item_unit_cost'] - $item['bill_item_units']*$item['bill_item_unit_cost']*$mh)); //nang
        }
        $tongtienDichVu += $item['bill_item_units']*$item['bill_item_unit_cost'];
        if($groupnr==22){  //xet không cho giảm BHYT của xét nghiệm máu
            $tongtienDichVuBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*0;   //nang
        }   else{
            $tongtienDichVuBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*$mh;   //nang
        }
        $tongtienDichVuTra += $tongtienDichVu - $tongtienDichVuBHYT; //nang

        if ($groupnr<=25){								//Xet nghiem 1->25
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempLabor = $sTempLabor.ob_get_contents();
            ob_end_clean();
        } elseif ($groupnr==26 || $groupnr==28 || $groupnr==39){ 						//XQuang 26
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempRadio = $sTempRadio.ob_get_contents();
            ob_end_clean();
        } elseif ($groupnr==27 || $groupnr==29){		//Sieu am 27, Noi soi 29
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempUltra = $sTempUltra.ob_get_contents();
            ob_end_clean();
        } elseif ($groupnr==38){						//ECG 28
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempECG = $sTempECG.ob_get_contents();
            ob_end_clean();
        } elseif ($groupnr==33 || $groupnr==34){		//Thu thuat 33, Phau thuat 34
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempSur = $sTempSur.ob_get_contents();
            ob_end_clean();
        } elseif ($groupnr>=30 && $groupnr<=32){		//Mau 30, dam 31, dich 32
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempBlood = $sTempBlood.ob_get_contents();
            ob_end_clean();
        } elseif ($groupnr==35 || $groupnr==36){		//Giuong 35,36
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempBed = $sTempBed.ob_get_contents();
            ob_end_clean();
        }
        else
        {										//Khac
            ob_start();
            $smarty->display('ecombill/showfinalbill_other_line.tpl');
            $sTempKhac = $sTempKhac.ob_get_contents();
            ob_end_clean();
        }
    }
}

//Lay tat ca thu thuat, phau thuat cua benh nhan
$smarty->assign('LDSurgery','3. '.$LDSurgery);
if($sTempSur)
    $smarty->assign('ItemSurgery',$sTempSur);
else
    $smarty->assign('ItemSurgery',$noItem);

//Lay tat ca xet nghiem cua benh nhan
$smarty->assign('LDLaboration','4. '.$LDLaboration);
if($sTempLabor)
    $smarty->assign('ItemLDLabor',$sTempLabor);
else
    $smarty->assign('ItemLDLabor',$noItem);

//Lay tat ca chup XQuang cua benh nhan
$smarty->assign('LDRadio','5. '.$LDRadio);
if($sTempRadio)
    $smarty->assign('ItemRadio',$sTempRadio);
else
    $smarty->assign('ItemRadio',$noItem);

//Lay tat ca sieu am cua benh nhan
$smarty->assign('LDUltrasonic','6. '.$LDUltrasonic);
if($sTempUltra)
    $smarty->assign('ItemUltrasonic',$sTempUltra);
else
    $smarty->assign('ItemUltrasonic',$noItem);

//Lay tat ca dien tam do cua benh nhan
$smarty->assign('LDECG','7. '.$LDECG);
if($sTempECG)
    $smarty->assign('ItemECG',$sTempECG);
else
    $smarty->assign('ItemECG',$noItem);

//Lay tat ca xet nghiem mau cua benh nhan
$smarty->assign('LDBlood','8. '.$LDBlood);
if($sTempBlood)
    $smarty->assign('ItemBlood',$sTempBlood);
else
    $smarty->assign('ItemBlood',$noItem);

//Lay tat ca ngay nam vien cua benh nhan
$smarty->assign('LDBed','9. '.$LDBed);
if($sTempBed)
    $smarty->assign('ItemBed',$sTempBed);
else
    $smarty->assign('ItemBed',$noItem);

//Cac dich vu khac
$smarty->assign('LDKhac','10. '.$LDKhac);
if($sTempBed)
    $smarty->assign('ItemKhac',$sTempKhac);
else
    $smarty->assign('ItemKhac',$noItem);


# Show Final Bill
//Lay info Hoa don
//$finalqry="SELECT bill_amount,bill_outstanding, SUM(bill_amount) AS total_amount, SUM(bill_outstanding) AS total_outstanding, SUM(bill_discount) AS total_discount FROM care_billing_bill WHERE bill_encounter_nr='$patientno' ORDER BY bill_bill_no";

$resultfinalqry= $eComBill->billAmountByEncounter($patientno);
if(is_object($resultfinalqry)) $cntbill=$resultfinalqry->FetchRow();

//$cntbill['total_amount'] += $tongtienthuoc + $tongtienVTYT + $tongtienHC + $tongtienDichVu;
$cntbill['total_amount'] = $tongtienthuoc + $tongtienVTYT + $tongtienHC + $tongtienDichVu;
//$cntbill['total_amount'] += $tongtienthanhtoan + $tongtienVTYTTra + $tongtienHCTra + $tongtienDichVuTra;    //nang
$smarty->assign('LDTotal',$LDTotalFinalBill.': ');
$smarty->assign('LDTotalValue',number_format($cntbill['total_amount'])); 				//$final['final_total_bill_amount']
$sTempMoney = convertMoney($cntbill['total_amount']);
$smarty->assign('money_total_Reader',$sTempMoney);
$cntbill['total_discount'] = $tongtienBHYT + $tongtienVTYTBHYT + $tongtienHCBHYT + $tongtienDichVuBHYT;  //nang- tinh so tien dc giam BHYT

$smarty->assign('LDDiscountonTotalAmount',$LDDiscountonTotalAmount);
$smarty->assign('LDDiscountonTotalAmountValue',number_format($cntbill['total_discount'])); 			//$final['final_discount']
$sTempMoney = convertMoney($cntbill['total_discount']);
$smarty->assign('money_disc_Reader',$sTempMoney);

$discamt=$cntbill['total_amount']-$cntbill['total_discount'];

$smarty->assign('LDAmountAfterDiscount',$LDAmountAfterDiscount);
$smarty->assign('LDAmountAfterDiscountValue',number_format($discamt));
$sTempMoney = convertMoney($discamt);
$smarty->assign('money_afterdisc_Reader',$sTempMoney);
//số tiền thanh toán+ tạm ứng
$smarty->assign('LDAmountPreviouslyReceived',$LDAmountPreviouslyReceived);
$smarty->assign('LDAmountPreviouslyReceivedValue',number_format($cntbill['total_outstanding']+$payment['sumcost']));
$sTempMoney = convertMoney($cntbill['total_outstanding']+$payment['sumcost']);
$smarty->assign('money_receive_Reader',$sTempMoney);

//Con lai
$total_resume = $cntbill['total_amount']-$cntbill['total_discount']-$cntbill['total_outstanding']-$payment['sumcost'];     //$cntbill['total_outstanding'] s? ti?n tính hóa ??n hi?n t?i thanh toán
$smarty->assign('LDAmountDue',$LDAmountDue);                                                                               //$payment['sumcost'] s? ti?n t?m ?ng
$smarty->assign('LDAmountDueValue',number_format($total_resume));
if($total_resume>=0)
    $sTempMoney = convertMoney($total_resume);
else {
    $sTempMoney = convertMoney(-$total_resume);
    $sTempMoney = $LDTru.' '.$sTempMoney;
}
$smarty->assign('money_due_Reader',$sTempMoney);
 /*
//No truoc
$old_resume = $oldenc_totalbill - $oldenc_discount - $oldenc_totaloutstanding - $oldenc_totalpayment;
$smarty->assign('LDOldResume',$LDNoTruoc);
$smarty->assign('LDOldResumeValue',number_format($old_resume));
if($old_resume>=0)
    $sTempMoney = convertMoney($old_resume);
else {
    $sTempMoney = convertMoney(-$old_resume);
    $sTempMoney = $LDTru.' '.$sTempMoney;
}
$smarty->assign('money_oldresume_Reader',$sTempMoney);

//So tien thanh toan sau cung
$smarty->assign('LDCurrentPaidAmount',$LDFinalPaidAmount);
$smarty->assign('LDCurrentPaidAmountValue',number_format($total_resume+$old_resume));
if(($total_resume+$old_resume)>=0)
    $sTempMoney = convertMoney($total_resume+$old_resume);
else {
    $sTempMoney = convertMoney(-($total_resume+$old_resume));
    $sTempMoney = $LDTru.' '.$sTempMoney;
}
$smarty->assign('money_paid_Reader',$sTempMoney);

//Benh nhan tra
$smarty->assign('LDPatientPaid',$LDBenhNhanTraSauCung);
$smarty->assign('LDPatientPaidValue',number_format($final['final_amount_recieved']));
if($final['final_amount_recieved']>=0)
    $sTempMoney = convertMoney($final['final_amount_recieved']);
else {
    $sTempMoney = convertMoney(-$final['final_amount_recieved']);
    $sTempMoney = $LDTru.' '.$sTempMoney;
}
$smarty->assign('money_patientpaid_Reader',$sTempMoney);

//Con lai sau cung
$lastdue=$total_resume+$old_resume-$final['final_amount_recieved'];
$smarty->assign('LDAmountDueLast',$LDConlaisaucung);
$smarty->assign('LDAmountDueLastValue',number_format($lastdue));
if($lastdue>=0)
    $sTempMoney = convertMoney($lastdue);
else {
    $sTempMoney = convertMoney(-$lastdue);
    $sTempMoney = $LDTru.' '.$sTempMoney;
}
$smarty->assign('money_duelast_Reader',$sTempMoney);
 */
//Show Button
$smarty->assign('pbPrint','<a href="javascript:window.printOut();"><input type="image"  '.createLDImgSrc($root_path,'printout.gif','0','middle').'></a>');
$smarty->assign('pbClose','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0').' title="'.$LDCancel.'" align="middle"></a>');
/*if($target!='nursing')
    $smarty->assign('pbSubmit','<a href="'.$continue.'"><img  '.createLDImgSrc($root_path,'continue.gif','0','middle').'></a>');
else
    $smarty->assign('pbSubmit','<a href="JavaScript:window.printOut();"><input type="image"  '.createLDImgSrc($root_path,'printout.gif','0','middle').'></a>');
*/
//$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');
//Hidden Input
//$smarty->assign('sFormTag','<form name=confirmfrmfinal method="POST" action="postfinalbill.php">');
$smarty->assign('sHiddenInputs','<input type="hidden" name="cleared" value="cleared">
								<input type="hidden" name="patientno" value="'. $patientno .'">
								<input type="hidden" name="lang" value="'. $lang .'">
								<input type="hidden" name="final_bill_no" value="'. $final['final_bill_no'] .'">
								<input type="hidden" name="sid" value="'. $sid . '">
								<input type="hidden" name="full_en" value="'.$full_en .'">
								<input type="hidden" name="target" value="'. $target .'">');
/**
 * show Template
 */

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','ecombill/showfinalbill.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>