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
require_once($root_path.'classes/money/convertMoney.php');

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
$tinhtrang= $patient['is_traituyen'];
if($tinhtrang==1){
    $tt = 'Đúng tuyến';
}elseif($tinhtrang==2){
    $tt ='Trái tuyến';
}else{
    $tt='Không rõ';
}
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
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

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
			<td width="60%"><font size="12">
			 TRUNG TÂM Y TẾ HUYỆN TÂN UYÊN <br>
             Khoa: '.$wardname.'</font></td>
            <td align="center" width="35%"> Mẫu số: 02/BV <br> Số khám bệnh<br> Mã số người bệnh: '.$patientno.'</td>

             </tr>
		</table>';
$header_1_1='<table><tr>
			<td width="60%"><font size="12">
			 TRUNG TÂM Y TẾ HUYỆN TÂN UYÊN <br>
             Khoa: '.$wardname.'</font></td>
             <td align="center" width="35%"> Mẫu số: 01/BV <br> Số khám bệnh<br> Mã số người bệnh: '.$patientno.'</td>
             </tr>
		</table>';
$header_11='<table><tr>
			</td>
			<td align="center" width="100%"><b><font size="12">BẢNG KÊ CHI PHÍ KHÁM BỆNH, CHỮA BỆNH NỘI TRÚ</font></b><br><font size="10">Mức hưởng:'.$mh*100 .'%</font></td>
		    </tr>

		</table>';
$header_12='<table><tr>
			</td>
			<td align="center" width="100%"><b><font size="13">BẢNG KÊ CHI PHÍ KHÁM BỆNH, CHỮA BỆNH NGOẠI TRÚ</font></b><br><font size="10">Mức hưởng:'.$mh*100 .'%</font></td>
		    </tr>
		</table>';
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->DrawRect(($x+169),($y+40),5,4.5,1); //nam
$pdf->DrawRect(($x+185),($y+40),5,4.5,1); //nữ
$pdf->DrawRect(($x+23),($y+48),5,4.5,1); //BHYT
$pdf->DrawRect(($x+35),($y+53),5,4.5,1); //không BHYT
$pdf->DrawRect(($x+53),($y+48),6,4.5,1); //số thẻ bhyt
$pdf->DrawRect(($x+59),($y+48),4,4.5,1); //số thẻ bhyt
$pdf->DrawRect(($x+63),($y+48),6,4.5,1); //số thẻ bhyt
$pdf->DrawRect(($x+69),($y+48),5,4.5,1); //số thẻ bhyt
$pdf->DrawRect(($x+74),($y+48),8,4.5,1); //số thẻ bhyt
$pdf->DrawRect(($x+82),($y+48),13,4.5,1); //số thẻ bhyt
$pdf->DrawRect(($x+85),($y+61),15,4.5,1); //số thẻ bhyt
$pdf->DrawRect(($x+22),($y+70),5,4.5,1); //đúng tuyến
$pdf->DrawRect(($x+50),($y+70),5,4.5,1); //nơi chuyển đến
$pdf->DrawRect(($x+162),($y+70),5,4.5,1); //trái tuyến  j
$pdf->DrawRect(($x+173),($y+75),8,4.5,1); //mã bênh


$header_2='<table border="0" width="100%" cellpadding="0">
            <tr>
             <b><td> I. Hành chính</td></b><br>
                <td>(1) Họ và tên người bệnh: </td>
                <td>'.$patient['name_last'].' '.$patient['name_first'].' </td>
                <td>Ngày sinh: '.formatDate2Local($patient['date_birth'],$date_format).'</td>';
                if($patient['sex']=='m'){
                    $header_2.='<td width="30%">Giới tính: Nam&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp; Nữ</td>';
                }else{
                    $header_2.='<td width="30%">Giới tính: Nam&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nữ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;X</td>';
                }
$header_2.= '</tr>
            <tr bgcolor=#eeeeee>
                <td width="50%">(2) Địa chỉ: '.$Encounter->encounter['phuongxa_name'].'-'.$Encounter->encounter['quanhuyen_name'].'-'.$Encounter->encounter['citytown_name'].'</td>
            </tr>
            <tr>';
            if($patient['insurance_nr']!=''){
                $header_2.='<td width="15%">(3)Có BHYT:&nbsp;&nbsp; X</td>';
            }else{
                $header_2.='<td width="15%">(3)Có BHYT: </td>';
            }
$header_2.='<td width="45%">Mã thẻ BHYT: '.$patient['insurance_nr'].' </td>
              <td>Giá trị từ: '.formatDate2Local($patient['insurance_start'],$date_format).'</td>
              <td>đến: '.formatDate2Local($patient['insurance_exp'],$date_format).'</td>
            </tr>';
            if($patient['insurance_nr']==''){
                $header_2.='<tr><td width="30%">(4)Không có BHYT:&nbsp;&nbsp;&nbsp;X</td></tr>';
            }else{
                $header_2.='<tr><td width="30%">(4)Không có BHYT:</td></tr>';
            }
$header_2.=' <tr bgcolor=#eeeeee>
                <td  width="50%">(5) Cơ sở đăng ký KCB BHYT ban đầu: '.$patient['insurance_local'].'</td>

            </tr>
            <tr>
                <td width="60%">(6) Mã số của cơ sở đăng ký KCB BHYT ban đầu: '.$patient['madkbd'].' </td>

            </tr>
            <tr>
                <td width="35%">(7) Vào viện vào lúc: '.formatDate2Local($patient['encounter_date'],$date_format).'</td>
                <td width="35%">(8) Ra viện lúc: '.$datestranfer.'</td>
                <td>Tổng số ngày điều trị: '.$tongngaydieutri.' </td>
            </tr>'  ;
              if($patient['is_traituyen']==1){
                $header_2.='<tr><td width="70%">(9)Cấp cứu:&nbsp;&nbsp;X&nbsp;&nbsp;Đúng tuyến&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nơi chuyển đến</td>
                <td>(10) Trái tuyến&nbsp;&nbsp;</td>
                </tr>';
            }elseif($patient['is_traituyen']==2){
                  $header_2.='<tr><td width="70%">(9)Cấp cứu:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Đúng tuyến&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nơi chuyển đến</td>
                                  <td>(10) Trái tuyến&nbsp;&nbsp;&nbsp;&nbsp;X</td>
                  </tr>';

              }else{
                  $header_2.='<tr><td width="70%">(9)Cấp cứu:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Đúng tuyến&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nơi chuyển đến</td>
                                  <td>(10) Trái tuyến&nbsp;&nbsp;</td>

                  </tr>';

              }
$header_2.='<tr>
                <td width="70%">(11) Chuẩn đoán khi ra viện: '.$patient['referrer_diagnosis'].'</td>
                <td >(12) Mã bệnh(ICD-10): '.$patient['referrer_diagnosis_code'].'</td>
            </tr>

        </table>';

$header_3='<table><tr>
			<td width="40%"><b>
             II. Chi phí khám, chữa bệnh</b></td>
             </tr>
        </table>';
//$pdf->writeHTML($header_1);
if($in_out==1){
    $pdf->writeHTML($header_1);
    $pdf->writeHTML($header_11);}
else{
    $pdf->writeHTML($header_1_1);
    $pdf->writeHTML($header_12);
}
$pdf->writeHTML($header_2);
$pdf->writeHTML($header_3);
$pdf->Ln();
$pdf->SetFont('dejavusans', '', 10);


#---------------------------------- Show info of Prescription, Show info of Depot, Surgery, Laborator ---------------------------------------------
//$pdf->Ln();
if($in_out==1){
$html_thuoc='';
$html='<table border="1" cellpadding="2">
				<tr>
					<td rowspan="2" width="25%" align="center"><b>NỘI DUNG</b></td>
					<td rowspan="2" align="center" width="10%"><b>Đơn vị tính</b></td>
					<td rowspan="2" width="7%" align="center"><b>Số lượng</b></td>
					<td rowspan="2" width="15%" align="center"><b>Đơn giá</b></td>
					<td rowspan="2" width="15%" align="center"><b>Thành tiền</b></td>
					<td colspan="3" width="28%"  align="center"><b>Nguồn thanh toán</b></td>
				</tr>
                 <tr>
                        <td align="center"><b>Quỹ BHYT</b></td>
                        <td align="center"><b>Khác</b></td>
                        <td align="center"><b>Người bệnh</b></td>
                 </tr>';

    $html.=   '<tr>
							<td colspan="1" align="center">(1)</td>
							<td colspan="1" align="center">(2)</td>
							<td align="center">(3)</td>
							<td align="center">(4)</td>
							<td align="center">(5)</td>
							<td align="center">(6)</td>
                            <td align="center">(7)</td>
                            <td align="center">(8)=(5)-(6)-(7)</td>
						</tr>';
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
							<td colspan="1" align="left">- '.$list_info[$x]['name'].'</td>
							<td colspan="1" align="center">'.$list_info[$x]['unit'].'</td>
							<td align="center">'.$tongthuoc.'</td>
							<td align="right">'.$list_info[$x]['cost'].'</td>
							<td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']).'</td>
							<td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($tongthuoc*$list_info[$x]['cost'] - $tongthuoc*$list_info[$x]['cost']*$mh).'</td>
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
			AND prsinfo.status_finish=0 AND tp.group_pres=0
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
							<td colspan="1" align="left">- '.$pres['product_name'].'</td>
							<td colspan="1" align="center">'.$pres['number_of_unit'].'</td>
							<td align="center">'.$pres['sum_number'].'</td>
							<td align="right">'.number_format($pres['cost']).'</td>
							<td align="right">'.number_format($pres['cost']*$pres['sum_number']).'</td>
							<td align="right">'.number_format($pres['cost']*$pres['sum_number']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($pres['cost']*$pres['sum_number'] - $pres['cost']*$pres['sum_number']*$mh).'</td>
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
					    	<td colspan="1" align="left">- '.$depot['product_name'].'</td>
							<td colspan="1" align="center">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td align="center">'.$depot['sum_number'].'</td>
							<td align="right">'.number_format($depot['cost']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh).'</td>
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
							<td colspan="1" align="left">- '.$depot['product_name'].'</td>
							<td colspan="1" align="center">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td align="center">'.$depot['sum_number'].'</td>
							<td align="right">'.number_format($depot['cost']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh).'</td>
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
$cong3 = $tongtienthuoc + $tongtienvtyt;
$cong3BHYT = $tongtienthuocBHYT+ $tongtienvtytTBHYT;
$cong3NB = $cong3 - $cong3BHYT;

$tongtienxndv=0;
$tongtienxndvBHYT=0;
$tongtienxndvTra=0;
$itemresult = $eComBill->listServiceItemsOfEncounter_in($patientno);

$countItem=0;
$cong1=0;
$cong1BHYT=0;
$cong1NB=0;
$cong2 =0;
$cong2BHYT =0;
$cong2NB =0;
if(is_object($itemresult))
{
    $countSur=0; $countItem = $itemresult->RecordCount();
    for ($i=0;$i<$countItem;$i++)
    {   $d=0;
        $item=$itemresult->FetchRow();
        $groupnr = $item['item_group_nr'];
        $item_code =  $item['item_code'] ;
        //tinh tiền cộng 1

        if($groupnr==35 || $groupnr==36){   //giường
            $cong1 +=$item['s'];
            $cong1BHYT += $item['s']*$mh;
            $cong1NB = $cong1 - $cong1BHYT;
        }elseif($groupnr==41 || $groupnr==40){   //chuyen vien 41, hồ sơ cong kham 40
            $cong1 +=$item['s'];
            $cong1BHYT += $item['s']*0;
            $cong1NB = $cong1 - $cong1BHYT;
        } elseif($item_code =='XNK07' || $item_code =='XNK02'){      //xet nghiệm HBsAg, serodia
            $cong2 +=$item['s'];
            $cong2BHYT += $item['s']*0;
            $cong2NB = $cong2 - $cong2BHYT;
        }
        else{
            $cong2 +=$item['s'];
            $cong2BHYT += $item['s']*$mh;
            $cong2NB = $cong2 - $cong2BHYT;
        }

        if($groupnr==41){  //không cho giảm BHYT của chuyển viện
            $row_item='<tr>
						<td colspan="1" align="left">- '.$item['group_name'].'</td>
						<td colspan="1" align="center">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>
						<td align="center">'.$item['bill_item_units'].'</td>
						<td align="right">'.number_format($item['s']).'</td>
						<td align="right">'.number_format($item['s']).'</td>
                        <td align="right">'.number_format($item['s']*0).'</td>
                        <td align="right">0</td>
                        <td align="right">'.number_format($item['s'] - $item['s']*0).'</td>
					  </tr>';
        }
        elseif($item_code =='XNK07' || $item_code =='XNK02' || $item_code=='0407'){   //xét nghiệm HbsAg, xét nghiệm serodia, hồ sơ
            $row_item='<tr>
						<td colspan="1" align="left">- '.$item['group_name'].'</td>
						<td colspan="1" align="center">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>
						<td align="center">'.$item['bill_item_units'].'</td>
						<td align="right">'.number_format($item['s']).'</td>
						<td align="right">'.number_format($item['s']).'</td>
                        <td align="right">'.number_format($item['s']*0).'</td>
                        <td align="right">0</td>
                        <td align="right">'.number_format($item['s'] - $item['s']*0).'</td>
					  </tr>';
        }
        else{
            $row_item='<tr>
						<td colspan="1" align="left">- '.$item['group_name'].'</td>
						<td colspan="1" align="center">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>
						<td align="center">'.$item['bill_item_units'].'</td>
						<td align="right">'.number_format($item['s']).'</td>
						<td align="right">'.number_format($item['s']).'</td>
                        <td align="right">'.number_format($item['s']*$mh).'</td>
                        <td align="right">0</td>
                        <td align="right">'.number_format($item['s'] - $item['s']*$mh).'</td>
					  </tr>';
        }
        /*
        $row_item='<tr>
						<td colspan="1" align="left">- '.$item['group_name'].'</td>
						<td colspan="1" align="center">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>
						<td align="center">'.$item['bill_item_units'].'</td>
						<td align="right">'.number_format($item['s']).'</td>
						<td align="right">'.number_format($item['s']).'</td>
                        <td align="right">'.number_format($item['s']*$mh).'</td>
                        <td align="right">0</td>
                        <td align="right">'.number_format($item['s'] - $item['s']*$mh).'</td>
					  </tr>'; */
        $tongtienxndv +=  $item['s'];
       if($groupnr==41){  //không cho giảm BHYT của chuyển viện
            $tongtienxndvBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*0;   //nang
        }
       elseif($item_code =='XNK07' || $item_code =='XNK02' || $item_code=='0407'){   //xét nghiệm HbsAg, xét nghiệm serodia, hồ sơ
           $tongtienxndvBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*0;   //nang
           }
      else{
            $tongtienxndvBHYT +=  $item['s']*$mh;  //nang
        }
        $tongtienxndvTra += $tongtienxndv - $tongtienxndvBHYT;


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
					<td colspan="1"><b>1. Ngày giường chuyên khoa </b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempKhac.'  '.$sTempBed.'
                 <tr>
                <td  align="right" colspan="1"><b>Cộng: 1</b></td><td></td><td></td><td></td><td align="right"><b>'.number_format($cong1).'</b></td><td align="right"><b>'.number_format($cong1BHYT).'</b></td><td></td><td align="right"><b>'.number_format($cong1NB).'</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                 </tr>
                <tr>
                    <td colspan="1"><b>2. Xét nghiệm</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                '.$sTempSur.' '.$sTempLabor.' '.$sTempRadio.' '.$sTempUltra.'  '.$sTempECG.' '.$sTempBlood.'
                   <tr>
                <td  align="right" colspan="1"><b>Cộng: 2</b></td><td></td><td></td><td></td><td align="right"><b>'.number_format($cong2).'</b></td><td align="right"><b>'.number_format($cong2BHYT).'</b></td><td></td><td align="right"><b>'.number_format($cong2NB).'</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                 </tr>
				<tr>
					<td colspan="1"><b>3. Thuốc, dịch truyền</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr> 3.1 Thuốc<br>'.$html_thuoc.'3.2 Y cụ và hóa chất<br>'.$html_vtyt_hc.'
				<tr>
                <td  align="right" colspan="1"><b>Cộng: 3</b></td><td></td><td></td><td></td><td align="right"><b>'.number_format($cong3).'</b></td><td align="right"><b>'.number_format($cong3BHYT).'</b></td><td></td><td align="right"><b>'.number_format($cong3NB).'</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                 </tr>';


    // $finalbilldate = explode('/',formatDate2Local($final['final_date'],$date_format,false,false,$sepChars));
    $html.= '<tr>
                <td colspan="1" align="right"><i><b>Tổng cộng: </b></i></td>
               <td></td>
               <td></td>
               <td></td>
               <td align="right"><b>'.number_format($tongtienthuoc+$tongtienvtyt+$tongtienxndv).'</b></td>
               <td align="right"><b>'.number_format($tienBHYT).'</b></td>
               <td></td>
               <td align="right"><b>'.number_format($tongtienthuocTra+$tongtienVTYTTra+$tongtienxndvTra).'</b></td>
              </tr>
			</table>';
    $html1='<table width="100%">
		<tr>
			<td><b>Số tiền ghi bằng chữ:</b></td>
		</tr>
		<tr>
			<td>- Tổng chi phí đợt điều trị: '.convertMoney($tongtienthuoc+$tongtienvtyt+$tongtienxndv).'</td>
		</tr>
		<tr>
		    <td>- Số tiền Quỹ BHYT thanh toán: '.convertMoney($tienBHYT).'</td>
		</tr>
		<tr>
			<td>- Số tiền người bệnh trả: '.convertMoney($tongtienthuocTra+$tongtienVTYTTra+$tongtienxndvTra).'</td>
		</tr>
		<tr>
			<td>- Nguồn khác:</td>
		</tr>
		</table>';
$html2='<table width="100%">
		<tr>
			<td></td><td></td>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>NGƯỜI LẬP BẢNG KÊ</b><br>(Ký,ghi rõ họ tên)</td>
			<td></td>
			<td align="center"><b>KẾ TOÁN VIỆN PHÍ</b><br>(Ký,ghi rõ họ tên)</td>
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td align="center"></td>
			<td></td>
			<td align="center"></td>
		</tr>
		</table>';
$html3='<table width="100%">
		<tr>
			<td></td><td></td>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>XÁC NHẬN CỦA NGƯỜI BỆNH</b><br>(Ký,ghi rõ họ tên)</td>
			<td></td>
			<td align="center"><b>GIÁM ĐỊNH BHYT</b><br>(Ký,ghi rõ họ tên)</td>
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td align="center"></td>
			<td></td>
			<td align="center"></td>
		</tr>
		</table>';
}else{
    $html_thuoc='';
    $html='<table border="1" cellpadding="2">
				<tr>
					<td rowspan="2" width="25%" align="center"><b>NỘI DUNG</b></td>
					<td rowspan="2" align="center" width="10%"><b>Đơn vị tính</b></td>
					<td rowspan="2" width="7%" align="center"><b>Số lượng</b></td>
					<td rowspan="2" width="15%" align="center"><b>Đơn giá</b></td>
					<td rowspan="2" width="15%" align="center"><b>Thành tiền</b></td>
					<td colspan="3" width="28%"  align="center"><b>Nguồn thanh toán</b></td>
				</tr>
                    <tr>
                        <td align="center"><b>Quỹ BHYT</b></td>
                        <td align="center"><b>Khác</b></td>
                        <td align="center"><b>Người bệnh</b></td>
                    </tr>';
    $html.=   '<tr>
							<td colspan="1" align="center">(1)</td>
							<td colspan="1" align="center">(2)</td>
							<td align="center">(3)</td>
							<td align="center">(4)</td>
							<td align="center">(5)</td>
							<td align="center">(6)</td>
                            <td align="center">(7)</td>
                            <td align="center">(8)=(5)-(6)-(7)</td>
						</tr>';
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
							<td colspan="1" align="center">- '.$list_info[$x]['name'].'</td>
							<td colspan="1" align="center">'.$list_info[$x]['unit'].'</td>
							<td align="center">'.$tongthuoc.'</td>
							<td align="right">'.$list_info[$x]['cost'].'</td>
							<td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']).'</td>
							<td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($tongthuoc*$list_info[$x]['cost'] - $tongthuoc*$list_info[$x]['cost']*$mh).'</td>
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
			AND prsinfo.status_finish=0 AND tp.group_pres=0
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
							<td colspan="1" align="left">- '.$pres['product_name'].'</td>
							<td colspan="1" align="center">'.$pres['note'].'</td>
							<td align="center">'.$pres['sum_number'].'</td>
							<td align="right">'.number_format($pres['cost']).'</td>
							<td align="right">'.number_format($pres['cost']*$pres['sum_number']).'</td>
							<td align="right">'.number_format($pres['cost']*$pres['sum_number']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($pres['cost']*$pres['sum_number'] - $pres['cost']*$pres['sum_number']*$mh).'</td>
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
					    	<td colspan="1" align="left">- '.$depot['product_name'].'</td>
							<td colspan="1" align="center">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td align="center">'.$depot['sum_number'].'</td>
							<td align="right">'.number_format($depot['cost']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh).'</td>
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
							<td colspan="1" align="left">- '.$depot['product_name'].'</td>
							<td colspan="1" align="center">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td align="center">'.$depot['sum_number'].'</td>
							<td align="right">'.number_format($depot['cost']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']).'</td>
							<td align="right">'.number_format($depot['cost']*$depot['sum_number']*$mh).'</td>
                            <td align="right">0</td>
                            <td align="right">'.number_format($depot['cost']*$depot['sum_number'] - $depot['cost']*$depot['sum_number']*$mh).'</td>
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
    //tính cộng 3
    $cong3 = $tongtienthuoc + $tongtienvtyt;
    $cong3BHYT = $tongtienthuocBHYT+ $tongtienvtytTBHYT;
    $cong3NB = $cong3 - $cong3BHYT;
   //dịch vụ y tế và xét nghiệm
    $tongtienxndv=0;
    $tongtienxndvBHYT=0;
    $tongtienxndvTra=0;
    $itemresult = $eComBill->listServiceItemsOfEncounter_in($patientno);

    $countItem=0;
    $cong1=0;
    $cong1BHYT=0;
    $cong1NB=0;
    $cong2 =0;
    $cong2BHYT =0;
    $cong2NB =0;
    if(is_object($itemresult))
    {
        $countSur=0; $countItem = $itemresult->RecordCount();
        for ($i=0;$i<$countItem;$i++)
        {   $d=0;
            $item=$itemresult->FetchRow();
            $groupnr = $item['item_group_nr'];
            $item_code =  $item['item_code'] ;
            //tinh tiền cộng 1 ,cộng 2

            if($groupnr==35 || $groupnr==36 || $groupnr==40 ){  //giường, công khám
                $cong1 +=$item['s'];
                $cong1BHYT += $item['s']*$mh;
                $cong1NB = $cong1 - $cong1BHYT;

            }
            elseif( $groupnr==41){ //chuyển viện
                $cong1 +=$item['s'];
                $cong1BHYT += $item['s']*0;
                $cong1NB = $cong1 - $cong1BHYT;
            } elseif($item_code =='XNK07' || $item_code =='XNK02'){
                $cong2 +=$item['s'];
                $cong2BHYT += $item['s']*0;
                $cong2NB = $cong2 - $cong2BHYT;
            }
            else{
                $cong2 +=$item['s'];
                $cong2BHYT += $item['s']*$mh;
                $cong2NB = $cong2 - $cong2BHYT;
            }
            $row_item='<tr>
						<td colspan="1" align="left">- '.$item['group_name'].'</td>
						<td colspan="1" align="center">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>
						<td align="center">'.$item['bill_item_units'].'</td>
						<td align="right">'.number_format($item['s']).'</td>
						<td align="right">'.number_format($item['s']).'</td>
                        <td align="right">'.number_format($item['s']*$mh).'</td>
                        <td align="right">0</td>
                        <td align="right">'.number_format($item['s'] - $item['s']*$mh).'</td>
					  </tr>';
            //$tongtienxndv += $tongtienxndv+ $item['bill_item_units']*$item['bill_item_unit_cost'];
            $tongtienxndv +=  $item['s'];
            if($groupnr==41){  //không cho giảm BHYT của chuyển viện
                $tongtienxndvBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*0;   //nang
            }
            elseif($item_code =='XNK07' || $item_code =='XNK02' || $item_code=='0407'){   //xét nghiệm HbsAg, xét nghiệm serodia, hồ sơ
                $tongtienxndvBHYT +=  $item['bill_item_units']*$item['bill_item_unit_cost']*0;   //nang
            }
            else{
                $tongtienxndvBHYT +=  $item['s']*$mh;  //nang
            }
            $tongtienxndvTra += $tongtienxndv - $tongtienxndvBHYT;


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
					<td colspan="1"><b>1.Khám bệnh </b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempKhac.'
                    <tr>
                <td  align="right" colspan="1"><b>Cộng: 1</b></td><td></td><td></td><td></td><td align="right"><b>'.number_format($cong1).'</b></td><td align="right"><b>'.number_format($cong1BHYT).'</b></td><td></td><td align="right"><b>'.number_format($cong1NB).'</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                 </tr>
                <tr>
                    <td colspan="1"><b>2. Chuẩn đoán hình ảnh, thăm dò chức năng</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                '.$sTempSur.' '.$sTempLabor.' '.$sTempRadio.' '.$sTempUltra.'  '.$sTempECG.' '.$sTempBlood.'
				    <tr>
                <td  align="right" colspan="1"><b>Cộng: 2</b></td><td></td><td></td><td></td><td align="right"><b>'.number_format($cong2).'</b></td><td align="right"><b>'.number_format($cong2BHYT).'</b></td><td></td><td align="right"><b>'.number_format($cong2NB).'</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                 </tr>
				<tr>
					<td colspan="1"><b>3. Thuốc, dịch truyền</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr> 3.1 Thuốc<br>'.$html_thuoc.'3.2 Y cụ và hóa chất<br>'.$html_vtyt_hc.'
					<tr>
                <td  align="right" colspan="1"><b>Cộng: 3</b></td><td></td><td></td><td></td><td align="right"><b>'.number_format($cong3).'</b></td><td align="right"><b>'.number_format($cong3BHYT).'</b></td><td></td><td align="right"><b>'.number_format($cong3NB).'</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                 </tr>';

    // $finalbilldate = explode('/',formatDate2Local($final['final_date'],$date_format,false,false,$sepChars));
    $html.= '<tr>
                <td colspan="1" align="right" ><i><b>Tổng cộng: </b></i></td>
               <td></td>
               <td></td>
               <td></td>
               <td align="right"><b>'.number_format($tongtienthuoc+$tongtienvtyt+$tongtienxndv).'</b></td>
               <td align="right"><b>'.number_format($tienBHYT).'</b></td>
               <td></td>
               <td align="right"><b>'.number_format($tongtienthuocTra+$tongtienVTYTTra+$tongtienxndvTra).'</b></td>
              </tr>
			</table>';
    $html1='<table width="100%">
		<tr>
			<td><b>Số tiền ghi bằng chữ:</b></td>
		</tr>
		<tr>
			<td>- Tổng chi phí đợt điều trị: '.convertMoney($tongtienthuoc+$tongtienvtyt+$tongtienxndv).'</td>
		</tr>
		<tr>
		    <td>- Số tiền Quỹ BHYT thanh toán: '.convertMoney($tienBHYT).'</td>
		</tr>
		<tr>
			<td>- Số tiền người bệnh trả: '.convertMoney($tongtienthuocTra+$tongtienVTYTTra+$tongtienxndvTra).'</td>
		</tr>
		<tr>
			<td>- Nguồn khác:</td>
		</tr>
		</table>';
    $html2='<table width="100%">
		<tr>
			<td></td><td></td>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>NGƯỜI LẬP BẢNG KÊ</b><br>(Ký,ghi rõ họ tên)</td>
			<td></td>
			<td align="center"><b>KẾ TOÁN VIỆN PHÍ</b><br>(Ký,ghi rõ họ tên)</td>
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td align="center"></td>
			<td></td>
			<td align="center"></td>
		</tr>
		</table>';
    $html3='<table width="100%">
		<tr>
			<td></td><td></td>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>XÁC NHẬN CỦA NGƯỜI BỆNH</b><br>(Ký,ghi rõ họ tên)</td>
			<td></td>
			<td align="center"><b>GIÁM ĐỊNH BHYT</b><br>(Ký,ghi rõ họ tên)</td>
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td align="center"></td>
			<td></td>
			<td align="center"></td>
		</tr>
		</table>';
}
$html = $html.ob_get_contents();
ob_clean();
$pdf->writeHTML($html);
$pdf->writeHTML($html1);
$pdf->writeHTML($html2);
$pdf->writeHTML($html3);

//$pdf->writeHTML($htmlcol1);
$pdf->selectColumn(1);

// -----------------------------------------------------------------------------

$pdf->lastPage();//thêm trang
//Close and output PDF document
$pdf->Output('PhieuTongKetHoaDon.pdf', 'I');


?>