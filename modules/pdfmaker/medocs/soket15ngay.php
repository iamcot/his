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

require_once($root_path.'include/care_api_classes/class_notes_nursing.php');
$report_obj= new NursingNotes;


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
$pdf->SetTitle('Phiếu sơ kết 15 ngày điều trị');
$pdf->SetMargins(5, 8, 3);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

// add a page: Trang 1
$pdf->AddPage();

// ----------------------------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);
$header_1='<table><tr>
			<td width="22%">SỞ Y TẾ:.................<br>
				BV: '.$cell.'<br>			
			</td>
			<td align="center" width="55%"><b><font size="16">PHIẾU SƠ KẾT 15 NGÀY ĐIỀU TRỊ</font></b></td>
			<td>
				MS: 18/BV-01<br>
				Số vào viện: '.$enc.'
			</td>			
		</tr>
		</table>';
$pdf->writeHTML($header_1);
$pdf->Ln(2);
$pdf->writeHTMLCell(125, 0, '', '', str_pad("- Họ tên người bệnh: ..".$encounter['name_last'].' '.$encounter['name_first'], 90, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);   
$pdf->writeHTMLCell(35, 0, '', '', str_pad(" Tuổi: ..".$encounter['tuoi'], 26, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Nam/nữ: ..".$sex_patient, 25, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad("- Địa chỉ: ..".$encounter['addr_str_nr'].' ... '.$encounter['addr_str'].' ... '.$encounter['phuongxa_name'], 155, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);
$pdf->writeHTMLCell(125, 0, '', '', str_pad("- Khoa: ..".$deptname.' - '.$wardname, 85, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(35, 0, '', '', str_pad(" Buồng: ..".$encounter['current_room_nr'], 25, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Số giường: ..", 30, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);


$pdf->writeHTMLCell(0, 0, '', '', str_pad("- Chẩn đoán: ..".$encounter['referrer_diagnosis'], 165, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);
$pdf->Ln();



$html='<table border="0" cellpadding="3">';


	$sql="SELECT nd.*, tp.sort_nr 
		FROM care_encounter_notes AS nd, care_type_notes AS tp
		WHERE nd.encounter_nr='$enc'
		AND nd.type_nr = tp.nr 		
		AND (nd.type_nr IN (42,43,44,45,46))
		AND nd.short_notes='$time_nr' 
		ORDER BY tp.sort_nr";

	if($result=$db->Execute($sql)){
		if($result->RecordCount()){ 
			while($row=$result->FetchRow()){
				switch($row['type_nr']){
					case 42: $dienbienlamsang = nl2br($row['notes']); break;
					case 43: $xetnghiemcls = nl2br($row['notes']);  break;
					case 44: $quatrinhdieutri = nl2br($row['notes']);  break;
					case 45: $danhgiaketqua = nl2br($row['notes']);  break;
					case 46: $huongdieutri = nl2br($row['notes']);  break;
				}
			}
		}
	}
	
$html .= '<tr><td> <b>1. Diễn biến lâm sàng trong đợt điều trị:</b> ..'.str_pad($dienbienlamsang, 625, ".", STR_PAD_RIGHT).' </td></tr>
		<tr><td> <b>2. Xét nghiệm cận lâm sàng:</b> ..'.str_pad($xetnghiemcls, 645, ".", STR_PAD_RIGHT).' </td></tr>
		<tr><td> <b>3. Quá trình điều trị:</b> ..'.str_pad($quatrinhdieutri, 665, ".", STR_PAD_RIGHT).' </td></tr>
		<tr><td> <b>4. Đánh giá kết quả:</b> ..'.str_pad($danhgiaketqua, 665, ".", STR_PAD_RIGHT).' </td></tr>
		<tr><td> <b>5. Hướng điều trị tiếp và tiên lượng:</b> ..'.str_pad($huongdieutri, 635, ".", STR_PAD_RIGHT).' </td></tr>';	
				
$html .= '</table>';
$pdf->writeHTML($html);
$pdf->Ln();

$html2='<table width="100%">
		<tr>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>Trưởng khoa</b></td>
			<td align="center"><b>Bác sĩ điều trị</b></td>
		</tr>
		<tr><td colspan="2"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td align="center">Họ tên.....................</td>
			<td align="center">Họ tên.....................</td>
		</tr>			
		</table>';
$pdf->writeHTMLCell(0, 25, '', '', $html2, 0, 1, 0, true, 'L', true);

// ----------------------------------------------------------------------------

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('SoKet15Ngay.pdf', 'I');


?>
