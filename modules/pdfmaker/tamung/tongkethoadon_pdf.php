<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
//define('LANG_FILE','billing.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_ward.php');

require_once($root_path.'include/care_api_classes/class_ecombill.php');
//require_once($root_path.'include/care_api_classes/class_encounter.php');

$eComBill = new eComBill;
$ward = new Ward;
$Encounter = new Encounter;
$sepChars=array('-','.','/',':',',');
$Encounter->loadEncounterData($patientno);//==>nang

//Lay info Benh nhan
$patqry="SELECT e.*,p.* FROM care_encounter AS e, care_person AS p WHERE e.encounter_nr=$patientno AND e.pid=p.pid";

$resultpatqry=$db->Execute($patqry);
if(is_object($resultpatqry)) $patient=$resultpatqry->FetchRow();
else $patient=array();
$mh = $patient['muchuong']; //lấy mức hưởng
$in_out = $patient['encounter_class_nr'];//noi tru hay ngoai tru
if($in_out==1) $in_out_patient= 'Nội trú';
else $in_out_patient='Ngoại trú';
if($patient['sex']=='m') $sex_patient = 'Nam';			//nam hay nu
else $sex_patient = 'Nữ';

$wardname = $ward->WardName($patient['current_ward_nr']);	//thuoc khu phong nao
/*$in_out = $patient['encounter_class_nr'];					//noi tru hay ngoai tru
if($in_out==1) $in_out_patient=$LDInPatient;
else $in_out_patient=$LDOutPatient;   */
//Lay ngay xuat vien
if($patient['discharge_date'])
    $datestranfer= formatDate2Local($patient['discharge_date'],$date_format,false,false,$sepChars);
else
    $datestranfer=date('d/m/Y');
$tongngaydieutri = round(abs(strtotime(formatDate2STD($datestranfer,'dd/mm/yyyy',$sepChars))-strtotime($patient['encounter_date']))/86400);


//Lay info Hoa don tong ket cua current_encounter
$finshowqry="SELECT final_bill_no, final_date, final_total_bill_amount, final_discount, final_total_receipt_amount, final_amount_due, final_amount_recieved FROM care_billing_final WHERE final_encounter_nr='$patientno'";
$finshowresult=$db->Execute($finshowqry);
if(is_object($finshowresult)) $final=$finshowresult->FetchRow();

//Lay info all cac phieu tam ung ????
$paymentqry="SELECT SUM(payment_amount_total) AS sumcost FROM care_billing_payment WHERE payment_encounter_nr='$patientno' AND payment_type='0'";
$paymentresult=$db->Execute($paymentqry);
if(is_object($paymentresult)) $payment=$paymentresult->FetchRow();


$resultfinalqry= $eComBill->billAmountByEncounter($patientno);
if(is_object($resultfinalqry)) $cntbill=$resultfinalqry->FetchRow();


require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
//$pdf->SetAuthor('TT Y tế Tân Uyên');
//$pdf->SetTitle('Phiếu Công Khai Thuốc và Tổng Hợp Viện Phí');
//$pdf->SetMargins(5, 8, 3);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

// add a page: Trang 1
$pdf->AddPage();
// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);
$header_1='<table><tr>
			<td width="25%"><b><br><br><br>
             Khoa phòng: '.$wardname.'</b>
			</td>
			<td align="center" width="50%"><b><font size="14">PHIẾU CÔNG KHAI THUỐC VÀ TỔNG HỢP VIỆN PHÍ</font></b><br><br><font size="10">Mức hưởng:'.$mh*100 .'%</font></td>
		    </tr>

		</table>';
$header_2='<table border="0" width="100%" cellpadding="0">
            <tr>
                <td>Mã bệnh nhân: '.$patientno.'</td>
                <td>Dạng điều trị: '.$in_out_patient.'</td>
                <td>Số hóa đơn: '.$final['final_bill_no'].'</td>
                <td>Ngày lập hóa đơn: '.formatDate2Local($final['final_date'],$date_format).'</td>
            </tr>
            <tr bgcolor=#eeeeee>
                <td>Họ tên người bệnh: '.$patient['name_last'].' '.$patient['name_first'].'</td>
                <td>Ngày sinh: '.formatDate2Local($patient['date_birth'],$date_format).'</td>
                <td>Giới tính: '.$sex_patient.'</td>
                <td>Địa chỉ: '.$Encounter->encounter['phuongxa_name'].' '.$Encounter->encounter['quanhuyen_name'].'<br>'.$Encounter->encounter['citytown_name'].'</td>
            </tr>
            <tr>
                <td>Phòng: '.$patient['current_room_nr'].' &nbsp; &nbsp; &nbsp; &nbsp;Số giường: </td>
                <td>Ngày nhập viện:'.formatDate2Local($patient['encounter_date'],$date_format).'</td>
                <td>Ngày xuất viện: '.$datestranfer.'</td>
                <td>Tạm ứng: '.$payment['sumcost'].'</td>
            </tr>
            <tr bgcolor=#eeeeee>
                <td>Số thẻ BHYT: '.$patient['insurance_nr'].'</td>
                <td>Hạn sử dụng: '.formatDate2Local($patient['insurance_exp'],$date_format).'</td>
                <td colspan=2>Nơi đăng ký: '.$patient['madk_kcbbd'].'</td>
            </tr>
            <tr>
                <td colspan=3>Chuẩn đoán: '.$patient['referrer_diagnosis'].'</td> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
                &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; <td>Tổng số ngày điều trị:'.$tongngaydieutri.' </td>
            </tr>

        </table>';
$header_3='<table><tr>
			<td width="25%"><b><br><br><br>
             Mã hiện tại: '.$patientno.'</b></td>
             </tr>
        </table>';
$pdf->writeHTML($header_1);
$pdf->writeHTML($header_2);
$pdf->writeHTML($header_3);
$pdf->Ln();
$pdf->SetFont('dejavusans', '', 10);

#---------------------------------- Show info of Prescription, Show info of Depot, Surgery, Laborator ---------------------------------------------
//$pdf->Ln();
$html_thuoc='';
$html='<table border="1" cellpadding="2">
				<tr>
					<td rowspan="2" width="15%" align="center"><b>NỘI DUNG</b></td>
					<td rowspan="2" align="center" width="20%"><b>Ngày</b></td>
					<td rowspan="2" width="10%" align="center"><b>Số lượng</b></td>
					<td rowspan="2" width="15%" align="center"><b>Đơn giá</b></td>
					<td rowspan="2" width="15%" align="center"><b>Thành tiền</b></td>
					<td colspan="3" width="25%"  align="center"><b>Nguồn thanh toán</b></td>
				</tr>
                    <tr>
                        <td align="center"><b>Quỹ BHYT</b></td>
                        <td align="center"><b>Khác</b></td>
                        <td align="center"><b>Người bệnh</b></td>
                    </tr>

			';
//lấy thuốc

$tongtienthuoc=0;
$tongtienthuocBHYT=0;
$tongtienthuocTra=0;
//Neu noi tru (group_pres=1)
$pres_noitru = "SELECT iss.*, sum(iss.number) AS sum, prs.product_name, prs.note AS unit, prs.cost
					FROM care_pharma_prescription_issue AS iss, care_pharma_prescription AS prs
					WHERE iss.enc_nr='".$patientno."' AND prs.prescription_id=iss.pres_id AND prs.product_encoder=iss.product_encoder
					GROUP BY iss.product_encoder, iss.date_issue
					ORDER BY iss.product_encoder, iss.date_issue";

$list_item = array();
$list_date = array(); $k=0;
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
foreach ($list_item as $x => $v) {
    $tongthuoc=0;
    foreach ($list_date as $v1) {
        $tongthuoc += $v[$v1];
    }
     $html_thuoc.=   '<tr>
							<td colspan="1" align="center">'.$list_info[$x]['name'].'</td>
							<td colspan="1" align="center">'.$list_info[$x]['unit'].'</td>
							<td align="center">'.$tongthuoc.'</td>
							<td align="center">'.$list_info[$x]['cost'].'</td>
							<td align="center">'.number_format($tongthuoc*$list_info[$x]['cost']).'</td>
							<td align="center">'.number_format($tongthuoc*$list_info[$x]['cost']*$mh).'</td>
                            <td align="center">0</td>
                            <td align="center">'.number_format($tongthuoc*$list_info[$x]['cost'] - $tongthuoc*$list_info[$x]['cost']*$mh).'</td>
						</tr>';
        $tongtienthuoc += $tongthuoc*$list_info[$x]['cost'];
        $tongtienthuocBHYT += $tongthuoc*$list_info[$x]['cost']*$mh;
    $tongtienthuocTra += $tongthuoc*$list_info[$x]['cost']  - $tongthuoc*$list_info[$x]['cost']*$mh ;
        //$tongtienthuocTra +=  $tongtienthuoc - $tongtienthuocBHYT;
    $stt++;
}

//Lay tat ca toa thuoc ngoai tru cua benh nhan

$presqry="SELECT prs.*,prsinfo.date_time_create,prsinfo.sum_date
			FROM care_pharma_prescription AS prs, care_pharma_prescription_info AS prsinfo, care_pharma_type_of_prescription AS tp
			WHERE prsinfo.encounter_nr='$patientno' AND prsinfo.prescription_id=prs.prescription_id
			AND prsinfo.prescription_type=tp.prescription_type
			AND prsinfo.status_finish=1 AND tp.group_pres=0
			ORDER BY prs.prescription_id";
$presresult=$db->Execute($presqry);
$countpres=0;
if(is_object($presresult))
{
    $countpres=$presresult->RecordCount();
    if($countpres>0)
    {
        for ($i=0;$i<$countpres;$i++)
        {
            $pres=$presresult->FetchRow();
            $html_thuoc.= '<tr>
							<td colspan="1" align="center">'.$pres['product_name'].'</td>
							<td colspan="1" align="center">'.$pres['number_of_unit'].'</td>
							<td align="center">'.$pres['sum_number'].'</td>
							<td align="center">'.number_format($pres['cost']).'</td>
							<td align="center">'.number_format($pres['cost']*$pres['sum_number']).'</td>
							<td align="center">'.number_format($pres['cost']*$pres['sum_number']*$mh).'</td>
                            <td align="center">0</td>
                            <td align="center">'.number_format($pres['cost']*$pres['sum_number'] - $pres['cost']*$pres['sum_number']*$mh).'</td>
						    </tr>';
            $tongtienthuoc += $pres['cost']*$pres['sum_number'];
            $tongtienthuocBHYT += $pres['cost']*$pres['sum_number']*$mh;
            $tongtienthuocTra +=  $pres['cost']*$pres['sum_number'] - $pres['cost']*$pres['sum_number']*$mh;
            $stt++;
        }
    }

}

//Gop chung VTYT va Hoa chat
$html_vtyt_hc='';
$depotqry="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_med_prescription AS med, care_med_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
$depotresult=$db->Execute($depotqry);
$count_depot=0;
$tongtienvtyt=0;
$tongtienvtytTBHYT=0;
$tongtienVTYTTra=0;
if(is_object($depotresult))
{
    $count_depot=$depotresult->RecordCount();
    for ($i=0;$i<$count_depot;$i++)
    {
        $depot=$depotresult->FetchRow();
        $namedepot = $depot['product_name'];
        $html_vtyt_hc.= '<tr>
					    	<td colspan="1" align="center">'.$depot['product_name'].'</td>
							<td colspan="1" align="center">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td align="center">'.$depot['sum_number'].'</td>
							<td align="center">'.number_format($depot['cost']).'</td>
							<td align="center">'.number_format($depot['cost']*$depot['sum_number']).'</td>
							<td align="center">'.number_format($depot['cost']*$depot['sum_number']*$mh).'</td>
                            <td align="center">0</td>
                            <td align="center">'.number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh).'</td>
					</tr>';
       // $tongtienvtyt+= $tongtienvtyt+ $depot['cost']*$depot['sum_number'];
        $tongtienvtyt +=  $depot['cost']*$depot['sum_number'];
        $tongtienvtytTBHYT += $depot['cost']*$depot['sum_number']*$mh;
        $tongtienVTYTTra += $depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh;
       // $tongtienVTYTTra +=  $tongtienvtyt - $tongtienvtytBHYT;
       // $stt++;
    }
}
$depotqry1="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_chemical_prescription AS med, care_chemical_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
$depotresult1=$db->Execute($depotqry1);
$count_HC=0;
$tongtienHC=0;
$tongtienHCBHYT=0;
$tongtienHCTra=0;
if(is_object($depotresult1))
{
    $count_HC=$depotresult1->RecordCount();
    for ($i=0;$i<$count_HC;$i++)
    {
        $depot=$depotresult1->FetchRow();
        $html_vtyt_hc.= '<tr>
							<td colspan="1" align="center">'.$depot['product_name'].'</td>
							<td colspan="1" align="center">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td align="center">'.$depot['sum_number'].'</td>
							<td align="center">'.number_format($depot['cost']).'</td>
							<td align="center">'.number_format($depot['cost']*$depot['sum_number']).'</td>
							<td align="center">'.number_format($depot['cost']*$depot['sum_number']*$mh).'</td>
                            <td align="center">0</td>
                            <td align="center">'.number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh).'</td>
						</tr>';
       // $tongtienHC += $tongtienHC+ $depot['cost']*$depot['sum_number'];
        $tongtienHC +=  $depot['cost']*$depot['sum_number'];
        $tongtienHCBHYT += $depot['cost']*$depot['sum_number']*$mh;
        $tongtienHCTra +=  $depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh;
      //  $tongtienHCTra +=$tongtienHC - $tongtienHCBHYT;
       //$stt++;
    }
}
$count_depot += $count_HC;
$tongtienvtyt += $tongtienHC;
$tongtienvtytTBHYT += $tongtienHCBHYT;
$tongtienVTYTTra   +=$tongtienHCTra;


$tongtienxndv=0;
$tongtienxndvBHYT=0;
$tongtienxndvTra=0;
$itemresult = $eComBill->listServiceItemsOfEncounter($patientno);
$countItem=0;
if(is_object($itemresult))
{
    $countSur=0; $countItem = $itemresult->RecordCount();
    for ($i=0;$i<$countItem;$i++)
    {
        $item=$itemresult->FetchRow();
        $groupnr = $item['item_group_nr'];
        if($groupnr==22){
            $row_item='<tr>
						<td colspan="1" align="center">'.$item['item_description'].'</td>
						<td colspan="1" align="center">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>
						<td align="center">'.$item['bill_item_units'].'</td>
						<td align="center">'.number_format($item['bill_item_unit_cost']).'</td>
						<td align="center">'.number_format($item['bill_item_units']*$item['bill_item_unit_cost']).'</td>
                        <td align="center">'.number_format($item['bill_item_units']*$item['bill_item_unit_cost']*0).'</td>
                        <td align="center">0</td>
                        <td align="center">'.number_format($item['bill_item_units']*$item['bill_item_unit_cost'] - $item['bill_item_units']*$item['bill_item_unit_cost']*0).'</td>
					  </tr>';
        }   else{
        $row_item='<tr>
						<td colspan="1" align="center">'.$item['item_description'].'</td>
						<td colspan="1" align="center">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>
						<td align="center">'.$item['bill_item_units'].'</td>
						<td align="center">'.number_format($item['bill_item_unit_cost']).'</td>
						<td align="center">'.number_format($item['bill_item_units']*$item['bill_item_unit_cost']).'</td>
                        <td align="center">'.number_format($item['bill_item_units']*$item['bill_item_unit_cost']*$mh).'</td>
                        <td align="center">0</td>
                        <td align="center">'.number_format($item['bill_item_units']*$item['bill_item_unit_cost'] - $item['bill_item_units']*$item['bill_item_unit_cost']*$mh).'</td>
					  </tr>';   }

        //$tongtienxndv += $tongtienxndv+ $item['bill_item_units']*$item['bill_item_unit_cost'];
        $tongtienxndv +=  $item['bill_item_units']*$item['bill_item_unit_cost'];
        if($groupnr==22){  //xet không cho giảm BHYT của xét nghiệm máu
            $tongtienxndvBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*0;   //nang
        }   else{
            $tongtienxndvBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*$mh;  //nang
        }
       // $tongtienxndvTra +=$item['bill_item_units']*$item['bill_item_unit_cost'] - $item['bill_item_units']*$item['bill_item_unit_cost']*$mh;
        $tongtienxndvTra = $tongtienxndv - $tongtienxndvBHYT;


        if ($groupnr<=25){								//Xet nghiem 1->25
            $sTempLabor .= $row_item;
        } elseif ($groupnr==26 || $groupnr==28 || $groupnr==39){ 	//XQuang 26
            $sTempRadio .= $row_item;
        } elseif ($groupnr==27 || $groupnr==29){		//Sieu am 27, Noi soi 29
            $sTempUltra .= $row_item;
        } elseif ($groupnr==38){						//ECG 28
            $sTempECG .= $row_item;
        } elseif ($groupnr==33 || $groupnr==34){		//Thu thuat 33, Phau thuat 34
            $sTempSur .= $row_item;
            $countSur++;
        } elseif ($groupnr>=30 && $groupnr<=32){		//Mau 30, dam 31, dich 32
            $sTempBlood .= $row_item;
        } elseif ($groupnr==35 || $groupnr==36){		//Giuong 35,36
            $sTempBed .= $row_item;
        }else{											//Khac
            $sTempKhac .= $row_item;
        }
    }
}
//tính số tiền hóa đơn đã thanh toán
$thanhtoan = $cntbill['total_outstanding'];
//tính số tiền mà bệnh nhân tạm ứng
$tamung= $payment['sumcost'];
$tienBHYT =  $tongtienxndvBHYT+ $tongtienvtytTBHYT + $tongtienthuocBHYT;
//echo $tienBHYT;
$html.= ' <tr>
					<td colspan="1"><b>1.Tên thuốc, hàm lượng </b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$html_thuoc.'
				<tr>
					<td colspan="1"><b>2. Y cụ + Hóa chất</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
               '.$html_vtyt_hc.'
                <tr>
                    <td colspan="1"><b>3. Thủ thuật, phẫu thuật</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                '.$sTempSur.'
                <tr>
                    <td colspan="1"><b>4. Xét nghiệm</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                '.$sTempLabor.'
				<tr>
					<td colspan="1"><b>5. X.Quang</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempRadio.'
				<tr>
					<td colspan="1"><b>6. Siêu âm</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempUltra.'
				<tr>
					<td colspan="1"><b>7. ECG</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempECG.'
				<tr>
					<td colspan="1"><b>8. Máu</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempBlood.'
				<tr>
					<td colspan="1"><b>9. Giường</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>'.$sTempBed.'
				<tr>
					<td colspan="1"><b>10. Khác</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>'.$sTempKhac;

               // $finalbilldate = explode('/',formatDate2Local($final['final_date'],$date_format,false,false,$sepChars));
            $html.= '<tr>
                            <td colspan="8"><i><b>Tổng cộng (Cộng: 1+2+3+4+5+6+7+8+9): &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;'.number_format($tongtienthuoc+$tongtienvtyt+$tongtienxndv).' vnd</b></i></td>
                          </tr>
                            <tr>
                            <td  colspan="8"><i><b>Được giảm (BHYT): &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;'.number_format($tienBHYT).' vnd</b></i></td>
                            </tr>
                            <tr>
                            <td  colspan="8"><i><b>Tổng số tiền sau khi giảm: &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;'.number_format($tongtienthuocTra+$tongtienVTYTTra+$tongtienxndvTra).' vnd</b></i></td>
				           </tr>
                           < tr>
                            <td  colspan="8"><i><b>Tổng số tiền đã thanh toán + tạm ứng: &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;'.number_format($thanhtoan + $tamung).' vnd</b></i></td>
				           </tr>
				           <tr>
                            <td  colspan="8"><i><b>Còn lại: &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;'.number_format($tongtienthuocTra+$tongtienVTYTTra+$tongtienxndvTra - ($thanhtoan + $tamung)).' vnd</b></i></td>
				           </tr>
			</table>';
$html = $html.ob_get_contents();
ob_clean();
$pdf->writeHTML($html);

//$pdf->writeHTML($htmlcol1);
$pdf->selectColumn(1);

// -----------------------------------------------------------------------------

$pdf->lastPage();//thêm trang
//Close and output PDF document
$pdf->Output('PhieuTongKetHoaDon.pdf', 'I');


?>