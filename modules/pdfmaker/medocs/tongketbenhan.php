<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$report_textsize=12;
$report_titlesize=14;
$report_auxtitlesize=10;
$report_authorsize=10;

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
//$lang_tables[]='startframe.php';

$lang_tables[]='departments.php';
//define('LANG_FILE','nursing.php');
define('NO_CHAIN',1);
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
# Get the encouter data
$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	
	if($encounter['sex']=='m') $sex_patient = 'Nam';			//nam hay nu
	else $sex_patient = 'Nữ';
}

//require_once($root_path.'include/care_api_classes/class_notes_nursing.php');
//$report_obj= new NursingNotes;


$sepChars=array('-','.','/',':',',');

//Get info of current department, ward
$ward_nr=$encounter['current_ward_nr'];
$dept_nr=$encounter['current_dept_nr'];
if ($ward_nr!=''){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} elseif ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = '';
	}
}

require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Tổng kết bệnh án');
$pdf->SetMargins(5, 8, 3);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

// add a page: Trang 1
$pdf->AddPage();

// set font
$pdf->SetFont('dejavusans', '', 10);

// ----------------------------------------------------------------------------

$sql="SELECT nd.*, tp.sort_nr 
		FROM care_encounter_notes AS nd, care_type_notes AS tp
		WHERE nd.encounter_nr='$enc'
		AND nd.type_nr = tp.nr 		
		AND (nd.type_nr IN (8,9,10,11,13,14,22,23,36,37,38,39,40))
		ORDER BY tp.sort_nr";
if($result=$db->Execute($sql)){
	$n=$result->RecordCount();
	if($n){
		while($row=$result->FetchRow()){
			switch($row['type_nr']){
				case 8: $lydovaovien = nl2br($row['notes']); break;
				case 9: $dienbienlamsang = nl2br($row['notes']);  break;
				case 10: $tomtatxetnghiemcls = nl2br($row['notes']);  break;
				case 11: $ketquagiaiphau = nl2br($row['notes']);  break;
				case 13: $chandoanvaovien = nl2br($row['notes']);  break;
				case 14: $phaptri = nl2br($row['notes']);  break;
				case 22: $thoigiandieutri = nl2br($row['notes']);  break;
				case 23: $ketquadieutri = nl2br($row['notes']);  break;
				case 36: $chandoanravien = nl2br($row['notes']);  break;
				case 37: $phuongphapdieutri = nl2br($row['notes']);  break;
				case 38: if($sType=='khac'){
							$text_sSurgery .= '<tr bgcolor="#ffffff">
													<td>'.@formatDate2Local($row['date'],$date_format).' '.$row['time'].'</td>
													<td>'.$row['notes'].'</td>
													<td>'.$row['aux_notes'].'</td>
													<td>'.$row['aux_morenote'].'</td>
												</tr>';											
							$temp_cb = explode(',',$row['morenote']);
							if ($temp_cb[0]) $cb_pt=' checked ';
							if ($temp_cb[1]) $cb_tt=' checked ';
							
						}
						break;
				case 39: $tinhtrangravien = nl2br($row['notes']);  break;
				case 40: $huongdieutri = nl2br($row['notes']);  break;
			}
			$sType = $row['short_notes'];
		}
	}
}	
$html = '<table cellpadding="3" width="100%">';	

switch($sType){
	case 'noitru':	
		$html .='<tr><td><b>B. TỔNG KẾT BỆNH ÁN</b></td></tr>
				<tr><td>
				<table width="100%" border="1" cellpadding="2"><tr><td colspan="4">
				<table width="100%">';	
		$html .= '<tr><td> <b>1. Quá trình bệnh lý và diễn biến lâm sàng:</b> ..'.str_pad($dienbienlamsang, 1425, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>2. Tóm tắt kết quả cận lâm sàng có giá trị chẩn đoán:</b> ..'.str_pad($tomtatxetnghiemcls, 945, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>3. Phương pháp điều trị:</b> ..'.str_pad($phuongphapdieutri, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>4. Tình trạng người bệnh ra viện:</b> ..'.str_pad($tinhtrangravien, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>5. Hướng điều trị tiếp và các chế độ tiếp theo:</b> ..'.str_pad($huongdieutri, 735, ".", STR_PAD_RIGHT).' </td></tr>';
		$html .= '</table>';
		break;
		
	case 'ngoaitru':
		$html .='<tr><td><b>TỔNG KẾT BỆNH ÁN</b></td></tr>
				<tr><td>
				<table width="100%" border="1" cellpadding="2"><tr><td colspan="4">
				<table width="100%">';	
		$html .= '<tr><td> <b>1. Quá trình bệnh lý và diễn biến lâm sàng:</b> ..'.str_pad($dienbienlamsang, 1425, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>2. Tóm tắt kết quả cận lâm sàng có giá trị chẩn đoán:</b> ..'.str_pad($tomtatxetnghiemcls, 945, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>3. Chẩn đoán ra viện:</b> ..'.str_pad($chandoanravien, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>4. Phương pháp điều trị:</b> ..'.str_pad($phuongphapdieutri, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>5. Tình trạng người bệnh ra viện:</b> ..'.str_pad($tinhtrangravien, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>6. Hướng điều trị tiếp và các chế độ tiếp theo:</b> ..'.str_pad($huongdieutri, 735, ".", STR_PAD_RIGHT).' </td></tr>';
		$html .= '</table>';	
		break;
		
	case 'khac':	
		$html .='<tr><td><b>B. TỔNG KẾT BỆNH ÁN</b></td></tr>
				<tr><td>
				<table width="100%" border="1" cellpadding="2"><tr><td colspan="4">
				<table width="100%">';	
		$html .= '<tr><td> <b>1. Quá trình bệnh lý và diễn biến lâm sàng:</b> ..'.str_pad($dienbienlamsang, 1425, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>2. Tóm tắt kết quả cận lâm sàng có giá trị chẩn đoán:</b> ..'.str_pad($tomtatxetnghiemcls, 945, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>3. Phương pháp điều trị:</b> ..'.str_pad($phuongphapdieutri, 765, ".", STR_PAD_RIGHT).' </td></tr>';
			if($text_sSurgery==''){
				$text_sSurgery='<tr><td></td><td></td><td></td><td></td></tr>
								<tr><td></td><td></td><td></td><td></td></tr>
								<tr><td></td><td></td><td></td><td></td></tr>';
			}		
		$html .='<tr>
					<td>
						<table width="100%" cellpadding="1" cellspacing="1" border="1">
							<tr><td colspan="2" align="center">- Phẫu thuật: <input type="checkbox" name="cb_pt" '.$cb_pt.' DISABLED></td>
								<td colspan="2">- Thủ thuật: <input type="checkbox" name="cb_tt" '.$cb_tt.' DISABLED></td></tr>
							<tr align="center" >
								<td>Giờ, ngày</td><td>Phương pháp phẫu thuật/vô cảm</td>
								<td>Bác sĩ phẫu thuật</td><td>Bác sĩ gây mê</td>				
							</tr>
							'.$text_sSurgery.'
						</table>
						<br>			
					</td>	
				</tr>';
		$html .= '<tr><td> <b>4. Tình trạng người bệnh ra viện:</b> ..'.str_pad($tinhtrangravien, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>5. Hướng điều trị tiếp và các chế độ tiếp theo:</b> ..'.str_pad($huongdieutri, 735, ".", STR_PAD_RIGHT).' </td></tr>';
		$html .= '</table>';		
		break;
		
	case 'yhct':	
		$html .='<tr><td><b>PHẦN III: TỔNG KẾT BỆNH ÁN</b></td></tr>
				<tr><td>
				<table width="100%" border="1" cellpadding="2"><tr><td>
				<table width="100%">';	
		$html .= '<tr><td> <b>1. Lý do vào viện:</b> ..'.str_pad($lydovaovien, 725, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>2. Quá trình bệnh lý và diễn biến lâm sàng:</b> ..'.str_pad($dienbienlamsang, 1025, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>3. Kết quả cận lâm sàng chính:</b> ..'.str_pad($tomtatxetnghiemcls, 745, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>4. Kết quả giải phẫu bệnh:</b> ..'.str_pad($ketquagiaiphau, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>5. Chẩn đoán vào viện:</b> ..'.str_pad($chandoanvaovien, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>6. Phương pháp điều trị:</b> ..'.str_pad($phuongphapdieutri, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>7. Kết quả điều trị:</b> ..'.str_pad($ketquadieutri, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>8. Chẩn đoán ra viện:</b> ..'.str_pad($chandoanravien, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>9. Tình trạng người bệnh khi ra viện:</b> ..'.str_pad($tinhtrangravien, 765, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td> <b>10. Hướng điều trị tiếp và các chế độ tiếp:</b> ..'.str_pad($huongdieutri, 735, ".", STR_PAD_RIGHT).' </td></tr>
				<tr><td><table width="100%"><tr><td></td><td align="center">Ngày .......... tháng ........... năm ..............<br><b>THẦY THUỐC ĐIỀU TRỊ</b>
						<br><br><br><br><br><br>Họ tên:.......................................</td></tr></table></td></tr>';
		$html .= '</table>';	
		break;
}


if($sType=='yhct'){
	$html .= '</td></tr></table>';
}else{
	$html .= '</td></tr>
			<tr>
				<td colspan="2" align="center" width="35%"><b>Hồ sơ, phim, ảnh</b></td>
				<td rowspan="4" align="center" width="30%"><b>Người giao hồ sơ</b><br><br><br><br> Họ tên:................................</td>
				<td rowspan="8" align="center" width="35%">Ngày..... tháng....... năm......... <br>
						<b>Bác sĩ điều trị</b><br><br><br><br><br><br><br><br> Họ tên:................................</td></tr>
			<tr><td align="center" width="25%">Loại</td><td align="center" width="10%">Số tờ</td></tr>
			<tr><td>- X-Quang</td><td></td></tr>
			<tr><td>- CT Scanner</td><td></td></tr>
			<tr><td>- Siêu âm</td><td></td>
				<td rowspan="4" align="center"><b>Người nhận hồ sơ</b><br><br><br><br> Họ tên:................................</td></tr>
			<tr><td>- Xét nghiệm</td><td></td></tr>
			<tr><td>- Khác ...............</td><td></td></tr>
			<tr><td>- Toàn bộ hồ sơ</td><td></td></tr>				
		</table>';
}
		
$html .= '</td></tr></table>';

ob_start();
echo $html;
$sTemp = ob_get_contents();
ob_end_clean();

$pdf->writeHTML($sTemp);
$pdf->Ln();


// ----------------------------------------------------------------------------

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('SoKet15Ngay.pdf', 'I');


?>
