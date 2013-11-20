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
define('LANG_FILE','nursing.php');
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

include_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($objPrescription))
	$objPrescription=new Prescription;


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
$pdf->SetAuthor('BV Đa Khoa Dầu Tiếng');
$pdf->SetTitle('Phiếu Theo Dõi Truyền Dịch');
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
			<td width="22%">SỞ Y TẾ BÌNH DƯƠNG<br>
				'.$cell.'<br>
				KHOA: '.$deptname.' '.$wardname.'
			</td>
			<td align="center" width="55%"><b><font size="16">PHIẾU THEO DÕI<br>TRUYỀN DỊCH</font></b></td>
			<td>
				MS: 17/BV-01<br>
				Số vào viện: '.$enc.'
			</td>			
		</tr></table>';
$pdf->writeHTML($header_1);
$pdf->Ln();
$pdf->writeHTMLCell(100, 0, '', '', str_pad("Họ tên người bệnh: ..".$encounter['name_last'].' '.$encounter['name_first'], 50, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);   
$pdf->writeHTMLCell(35, 0, '', '', str_pad(" Tuổi: ..".$encounter['tuoi'], 20, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Nam/nữ: ..".$sex_patient, 34, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);

$pdf->writeHTMLCell(100, 0, '', '', str_pad(" Số giường: ", 80, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad("Buồng: ..".$encounter['current_room_nr'], 75, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);

$pdf->writeHTMLCell(0, 0, '', '', str_pad("Chẩn đoán: ..".$encounter['referrer_diagnosis'], 216, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);
$pdf->Ln();



$html_thuoc='<table border="1" cellpadding="2">
				<tr>
					<td rowspan="2" align="center" width="7%"><b>Ngày tháng</b></td>
					<td rowspan="2" align="center" width="28%" align="center"><b>TÊN DỊCH TRUYỀN/ HÀM LƯỢNG</b></td>
					<td rowspan="2" align="center" width="7%" align="center"><b>Số lượng</b></td>				
					<td rowspan="2" align="center" width="10%"><b>Lô/Số sản xuất</b></td>
					<td rowspan="2" align="center" width="8%"><b>Tốc độ giọt/ph</b></td>
					<td colspan="2" align="center" width="20%"><b>Thời gian</b></td>
					<td rowspan="2" align="center" width="10%"><b>BS chỉ định</b></td>
					<td rowspan="2" align="center" width="10%"><b>YT(ĐD) thực hiện</b></td>
				</tr>
				<tr>
					<td align="center"><b>bắt đầu</b></td>
					<td align="center"><b>kết thúc</b></td>
				</tr>';

$medis=$objPrescription->getAllPresOfEncounter($enc,$dept_nr,$ward_nr,'1');
if(is_object($medis)){
	$count=$medis->RecordCount();
}				
if($count){
	for($i=0;$i<$count;$i++){
		$row=$medis->FetchRow();
		$tempdate = explode(" ",$row['date_time_create']);
		if(!isset($old_date) || $old_date=='')
			$old_date=$tempdate[0];	
		if ($old_date!=$tempdate[0]){
			$toggle=!$toggle;
			$old_date=$tempdate[0];
		}
		if($toggle) $bgc='#ffffff';
		else $bgc='#eeeeee';

		$temp_time=explode("-",$row['time_use']);
		if($i==0){ 
			$oldid = $row['prescription_id'];
			$oldnote = $row['totalnote'];
		}
		if ($oldid!=$row['prescription_id']){ 
			if ($oldnote!='')
				$html_thuoc .= '<tr><td></td><td>'.$oldnote.'</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
			$oldid = $row['prescription_id'];
			$oldnote = $row['totalnote'];
		}
	
		$html_thuoc .= ' <tr bgcolor="'.$bgc.'">
							<td>'.formatDate2Local($old_date,'dd/mm',false,false,$sepChars).'</td>
							<td>'.$row['product_name'].' &nbsp;&nbsp;/'.$row['desciption'];
						if($row['morenote']!='')	
							$html_thuoc .= '<br>('.$row['morenote'].')';
		$html_thuoc .=		'</td>
							<td>'.$row['sum_number'].' '.$row['note'].'</td>
							<td>'.$row['lotid'].'</td>
							<td>'.$row['speed'].'</td>
							<td>'.$temp_time[0].'</td>
							<td>'.$temp_time[1].'</td>
							<td>'. $row['doctor'].'</td>
							<td></td>
						  </tr>';

	}
	if ($oldnote!=''){ 
		$html_thuoc .= '<tr><td></td><td>'.$oldnote.'</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	}
}
				
$html_thuoc .= '</table>';
$pdf->writeHTML($html_thuoc);

// ----------------------------------------------------------------------------

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('PhieuTheoDoiTruyenDich.pdf', 'I');


?>
