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
//$Encounter = new Encounter;
$sepChars=array('-','.','/',':',',');


//Lay info Benh nhan
$patqry="SELECT e.*,p.* FROM care_encounter AS e, care_person AS p WHERE e.encounter_nr=$patientno AND e.pid=p.pid";

$resultpatqry=$db->Execute($patqry);
if(is_object($resultpatqry)) $patient=$resultpatqry->FetchRow();
else $patient=array();

if($patient['sex']=='m') $sex_patient = 'Nam';			//nam hay nu
else $sex_patient = 'Nữ';

$wardname = $ward->WardName($patient['current_ward_nr']);	//thuoc khu phong nao

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




require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor('TT Y tế Tân Uyên');
$pdf->SetTitle('Phiếu Công Khai Thuốc và Tổng Hợp Viện Phí');
$pdf->SetMargins(5, 8, 3);

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
			<td width="25%"><b>SỞ Y TẾ BÌNH DƯƠNG<br>
				TT Y tế Tân Uyên<br>
				KHOA: '.$wardname.'</b>
			</td>
			<td align="center" width="50%"><b><font size="14">PHIẾU CÔNG KHAI THUỐC VÀ TỔNG HỢP VIỆN PHÍ</font></b></td>
			<td align="center">
				MS: 18/BV-01<br>
				Số vào viện: '.$patientno.'
			</td>			
		</tr></table>';
$pdf->writeHTML($header_1);
$pdf->Ln();
$pdf->SetFont('dejavusans', '', 10);

$pdf->writeHTMLCell(100, 0, '', '', "Họ tên người bệnh: ..".$patient['name_last'].' '.$patient['name_first'].'..', 0, 0, 0, true, 'L', true);   
$pdf->writeHTMLCell(25, 0, '', '', str_pad(" Tuổi: ..".$patient['tuoi'], 18, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(35, 0, '', '', str_pad(" Nam/nữ: ..".$sex_patient, 20, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '',  str_pad(" Địa chỉ: ".$patient['addr_str_nr'].' '.$patient['addr_str'], 98, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);

$pdf->writeHTMLCell(35, 0, '', '', str_pad("Phòng: ..".$patient['current_room_nr'], 22, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(40, 0, '', '', str_pad(" Số giường: ", 32, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(60, 0, '', '', str_pad(" Ngày vào viện: ..".formatDate2Local($patient['encounter_date'],$date_format,false,false,$sepChars), 38, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
if($patient['discharge_date'])
	$pdf->writeHTMLCell(60, 0, '', '', str_pad(" Ngày xuất viện: ..".$datestranfer, 38, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
else
	$pdf->writeHTMLCell(60, 0, '', '', str_pad(" Ngày xuất viện: .....", 38, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
	
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Tạm ứng: ..".$payment['sumcost'], 70, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);

$pdf->writeHTMLCell(100, 0, '', '', str_pad("Số thẻ BHYT: ..".$patient['insurance_nr'], 60, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(80, 0, '', '', str_pad(" Hạn sử dụng: ..".formatDate2Local($patient['insurance_exp'],$date_format,false,false,$sepChars), 57, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true); 
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Nơi đăng ký: ..".$patient['madk_kcbbd'], 80, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);

$pdf->writeHTMLCell(225, 0, '', '', str_pad("Chẩn đoán: ..".$patient['referrer_diagnosis'], 138, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Tổng số ngày điều trị: ..".$tongngaydieutri, 48, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);





# Liet ke tat ca cac encounter truoc do --------------------------------------------------------
/*
$arr='';
$Encounter->listAllEncounterTransfer($patientno, &$arr);	
//echo $arr; //#2011000013#2011000001#0
$old_final_bill='';
$list_enc = explode('#',$arr);
for ($k=0; $k<count($list_enc); $k++){
	if($list_enc[$k]!='0' && $list_enc[$k]!=''){
		
			
	}
}
*/

#---------------------------------- Show info of Prescription ---------------------------------------------
$pdf->Ln();

if($tongngaydieutri<=11)
	$flag1=1;
else 
	$flag1=0;
$html_thuoc='<table border="1" cellpadding="2">
				<tr>
					<td rowspan="2" width="4%"><b>STT</b></td>
					<td rowspan="2" width="20%" align="center"><b>1. Tên thuốc, hàm lượng</b></td>
					<td rowspan="2" width="5%" align="center"><b>Đơn vị</b></td>
					<td colspan="11" align="center" width="50%"><b>Ngày</b></td>
					<td rowspan="2" align="center"><b>Tổng số</b></td>
					<td rowspan="2" align="center"><b>Đơn giá</b></td>
					<td rowspan="2" align="center" width="10%"><b>Thành tiền</b></td>
				</tr>';
  $tongtienthuoc=0;
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
		$list_item[$item['product_encoder']][$item['date_issue']]= $item['number'];
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
	//In dong ngay (co 11 cot ngay)
	echo '<tr>';
	foreach ($list_date as $d) {
		echo '<td align="center">'.formatDate2Local($d,'dd/mm').'</td>';
	}
	for($i1=count($list_date);$i1<11;$i1++)
		echo '<td></td>';
	echo '</tr>';
	//In cac dong thuoc chi tiet
	foreach ($list_item as $x => $v) {
		// $x: encoder, $v['date_issue'] = number
		echo '<tr><td align="center">'.$stt.'</td><td>'.$list_info[$x]['name'].' </td>';
		echo '<td align="center">'.$list_info[$x]['unit'].'</td>';
		$tongthuoc=0;
		foreach ($list_date as $v1) {
			echo '<td align="center">'.$v[$v1].'</td>';
			$tongthuoc += $v[$v1];
		}
		for($i1=count($list_date);$i1<11;$i1++)
			echo '<td></td>';
		$tongtienthuoc += $tongthuoc*$list_info[$x]['cost'];
		echo '<td align="right">'.$tongthuoc.'</td><td align="right">'.number_format($list_info[$x]['cost']).'</td><td align="right">'.number_format($tongthuoc*$list_info[$x]['cost']).'</td></tr>';
		$stt++;
	}
$html_thuoc = $html_thuoc.ob_get_contents();
ob_end_clean();	
 
 
 
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
			if($presid=='' || $presid!=$pres['prescription_id']){
				$html_thuoc .='<tr><td></td><td></td><td></td>';
				for($j=0;$j<11;$j++){
					if($j<$pres['sum_date'])
						$end = date("d/m", strtotime($pres['date_time_create'] . "+".$j." day"));
					else $end ='';	
					$html_thuoc .='<td>'.$end.'</td>';			
				}
				$html_thuoc .='<td></td><td></td><td></td></tr>';
				$presid=$pres['prescription_id'];
				$tongtienthuoc+=$pres['total_cost'];
			}
			$html_thuoc .= '<tr>
								<td>'.($i+1).'</td>
								<td>'.$pres['product_name'].'</td>
								<td>'.$pres['note'].'</td>';
								
						for($j=0;$j<11;$j++){
							if($j<$pres['sum_date'])
								$use = $pres['number_of_unit'];
							else $use ='';	
							$html_thuoc .='<td>'.$use.'</td>';			
						}												
			$html_thuoc .= 		'<td align="center">'.$pres['sum_number'].'</td>
								<td align="right">'.number_format($pres['cost']).'</td>
								<td align="right">'.number_format($pres['cost']*$pres['sum_number']).'</td>
							</tr>';			
		}
	}
	for ($i=$presresult->RecordCount();$i<18;$i++){
		$html_thuoc .= '<tr>';
		for ($j=0; $j<17; $j++)
			$html_thuoc .='<td></td>';
		$html_thuoc .= '</tr>';
	}
}else{
	for ($i=0;$i<18;$i++){
		$html_thuoc .= '<tr>';
		for ($j=0; $j<17; $j++)
			$html_thuoc .='<td></td>';
		$html_thuoc .= '</tr>';
	}
}	

$html_thuoc.='<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
					</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
			<tr>
				<td></td><td><b>Người bệnh ký tên</b></td>					<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				<td><i>Tổng:</i></td><td align="right"><b>'.number_format($tongtienthuoc).'</b></td>
			</tr></table>';
				
$pdf->writeHTML($html_thuoc);

# ---------------------------------- Show info of Depot, Surgery, Laborator... ---------------------------------- 
  
// add a page: Trang 2
$pdf->AddPage();
$pdf->setEqualColumns(2,600);

 //Lay tat ca VTYT cua benh nhan
$html_vtyt='<table border="1" cellpadding="2">
				<tr>
					<td rowspan="2" width="15%"><b>NỘI DUNG</b></td>
					<td colspan="8" align="center" width="45%"><b>Ngày</b></td>
					<td rowspan="2" width="10%"><b>Số lượng</b></td>
					<td rowspan="2" width="15%"><b>Đơn giá</b></td>
					<td rowspan="2" width="15%"><b>Thành tiền</b></td>
				</tr>
				<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
				<tr>
					<td><b>2. Y cụ</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>';

//Gop chung VTYT va Hoa chat
 $depotqry="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_med_prescription AS med, care_med_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
 $depotresult=$db->Execute($depotqry);
 $count_depot=0;
 $tongtienvtyt=0;
 if(is_object($depotresult)) 
 {
	$count_depot=$depotresult->RecordCount();
	for ($i=0;$i<$count_depot;$i++)
	{	
		$depot=$depotresult->FetchRow();		
		$html_vtyt .= '<tr>
							<td colspan="7">'.$depot['product_name'].'</td>
							<td colspan="2">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td>'.$depot['sum_number'].'</td>
							<td>'.number_format($depot['cost']).'</td>
							<td>'.number_format($depot['cost']*$depot['sum_number']).'</td>
						</tr>';
		$tongtienvtyt= $tongtienvtyt+ $depot['cost']*$depot['sum_number'];
	}	
 } 
 $depotqry1="SELECT med.*,medinfo.date_time_create,medinfo.sum_date FROM care_chemical_prescription AS med, care_chemical_prescription_info AS medinfo WHERE medinfo.encounter_nr='$patientno' AND medinfo.prescription_id=med.prescription_id ORDER BY med.prescription_id";
 $depotresult=$db->Execute($depotqry1);
 $count_HC=0;
 $tongtienHC=0;
 if(is_object($depotresult)) 
 {
	$count_depot=$depotresult->RecordCount();
	for ($i=0;$i<$count_HC;$i++)
	{	
		$depot=$depotresult->FetchRow();		
		$html_vtyt .= '<tr>
							<td colspan="7">'.$depot['product_name'].'</td>
							<td colspan="2">'.formatDate2Local($depot['date_time_create'],'dd/mm',false,false,$sepChars).'</td>
							<td>'.$depot['sum_number'].'</td>
							<td>'.number_format($depot['cost']).'</td>
							<td>'.number_format($depot['cost']*$depot['sum_number']).'</td>
						</tr>';
		$tongtienHC= $tongtienHC+ $depot['cost']*$depot['sum_number'];
	}	
 } 
 $count_depot += $count_HC;
 $tongtienvtyt += $tongtienHC;
 
 
 
 $tongtienxndv=0;
 //Lay tat ca cac items trong tat ca cac hoa don cua benh nhan (tru toa thuoc va VTYT)
 $html_item = '<table border="1" cellpadding="2" >
				<tr>
					<td rowspan="2" width="15%"><b>NỘI DUNG</b></td>
					<td colspan="8" align="center" width="45%"><b>Ngày</b></td>
					<td rowspan="2" width="10%"><b>Số lượng</b></td>
					<td rowspan="2" width="15%"><b>Đơn giá</b></td>
					<td rowspan="2" width="15%"><b>Thành tiền</b></td>
				</tr>
				<tr> <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>';
				
 $itemresult = $eComBill->listServiceItemsOfEncounter($patientno);
 $countItem=0;
 if(is_object($itemresult))
 {
	$countSur=0; $countItem = $itemresult->RecordCount();
	for ($i=0;$i<$countItem;$i++)
	{	
		$item=$itemresult->FetchRow();

		$row_item=	'<tr>
						<td colspan="7">'.$item['item_description'].'</td>
						<td colspan="2">'.formatDate2Local($item['bill_item_date'],'dd/mm',false,false,$sepChars).'</td>					
						<td>'.$item['bill_item_units'].'</td>
						<td>'.number_format($item['bill_item_unit_cost']).'</td>
						<td>'.number_format($item['bill_item_units']*$item['bill_item_unit_cost']).'</td>
					</tr>';
		$tongtienxndv = $tongtienxndv+ $item['bill_item_units']*$item['bill_item_unit_cost'];
		$groupnr = $item['item_group_nr'];
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
$html_item .= '<tr>
					<td colspan="2"><b>4. Xét nghiệm</b></td><td></td><td></td><td></td>
					<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempLabor.'
				<tr>
					<td colspan="2"><b>5. X.Quang</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempRadio.'
				<tr>
					<td colspan="2"><b>6. Siêu âm</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempUltra.'
				<tr>
					<td colspan="2"><b>7. ECG</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempECG.'
				<tr>
					<td colspan="2"><b>8. Máu</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>
				'.$sTempBlood.'
				<tr>
					<td colspan="2"><b>9. Giường</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>'.$sTempBed.'
				<tr>
					<td colspan="2"><b>10. Khác</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
				</tr>'.$sTempKhac;
				
$finalbilldate = explode('/',formatDate2Local($final['final_date'],$date_format,false,false,$sepChars));
$html_item .= '<tr>
					<td colspan="10"><i><b>Tổng cộng (Cộng: 1+2+3+4+5+6+7+8+9): </b></i></td>
					<td colspan="2" align="right"><b>'.number_format($tongtienthuoc+$tongtienvtyt+$tongtienxndv).'</b></td>
				</tr>
				<tr>
					<td colspan="12" align="center">Ngày '.$finalbilldate[0].' tháng '.$finalbilldate[1].' năm '.$finalbilldate[2].'<br>
					<b>ĐD PHỤ TRÁCH</b> <br>&nbsp;<br>&nbsp;
					</td>
				</tr>
			</table>';



$html_sur = '<tr>
				<td rowspan="2" colspan="2"><b>3. Thủ thuật, phẫu thuật</b></td>
				<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
			</tr>
			<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
			'.$sTempSur;

$rowfill= $countItem - $countpres - $count_depot - $countSur*4 + 6;
for($k=0; $k<$rowfill; $k++){
	$html_sur .='<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';			
}
//$html_sur .='<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';	
$htmlcol1 = $html_vtyt.$html_sur.'
				<tr>
					<td colspan="12" align="center"><b>Người bệnh ký tên</b><br>
					&nbsp;<br>&nbsp;<br>&nbsp;
					</td>
				</tr></table>';

$pdf->writeHTML($htmlcol1);
$pdf->selectColumn(1);
$pdf->writeHTML($html_item);
 


// -----------------------------------------------------------------------------

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('PhieuCongKhaiThuocVaVienPhi.pdf', 'I');


?>