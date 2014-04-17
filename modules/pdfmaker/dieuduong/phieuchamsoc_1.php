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
$enc_obj=& new Encounter($pn);
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
$pdf->SetTitle('Phiếu Chăm Sóc');
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
				BV: '.$cell.'<br>
				KHOA: '.$deptname.' '.$wardname.'
			</td>
			<td align="center" width="55%"><b><font size="16">PHIẾU CHĂM SÓC</font></b></td>
			<td>
				MS: 17/BV-01<br>
				Số vào viện: '.$pn.'
			</td>			
		</tr>
		<tr>
			<td></td>
			<td align="center"> Y tá (điều dưỡng) ghi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phiếu số:.................</td><td></td>
		</tr></table>';
$pdf->writeHTML($header_1);
$pdf->Ln();
$pdf->writeHTMLCell(125, 0, '', '', str_pad("Họ tên người bệnh: ..".$encounter['name_last'].' '.$encounter['name_first'], 90, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);   
$pdf->writeHTMLCell(35, 0, '', '', str_pad(" Tuổi: ..".$encounter['tuoi'], 26, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Nam/nữ: ..".$sex_patient, 25, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);

$pdf->writeHTMLCell(120, 0, '', '', str_pad(" Số giường: ", 98, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad("Buồng: ..".$encounter['current_room_nr'], 63, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);

$pdf->writeHTMLCell(0, 0, '', '', str_pad("Chẩn đoán: ..".$encounter['referrer_diagnosis'], 150, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);
$pdf->Ln();



$html_thuoc='<table border="1" cellpadding="2">
				<tr align="center">
					<td width="11%"><b>'.$LDDate.'</b></td>
					<td width="10%"><b>'.$LDHour1.'</b></td>
					<td width="35%"><b>'.$LDTheoDoiDienBien.'</b></td>
					<td width="35%"><b>'.$LDThucHienYLenh.'</b></td>
					<td width="9%"><b>'.$LDSignature.'</b></td>
				</tr>';

$neff_report=&$report_obj->getNursingAndEffectivityReport($pn);

if(is_object($neff_report)){
	$count=$neff_report->RecordCount();
}				
if($count){
	for($i=0;$i<$count;$i++){
		$row=$neff_report->FetchRow();
		
		$html_thuoc .= ' <tr>
							<td>'.formatDate2Local($row['date'],'dd/mm/yyyy').'</td>
							<td>'.$row['time'].'</td>
							<td>';	
			if(stristr($row['aux_notes'],'warn')) 
				$html_thuoc .= '<img '.createComIcon($root_path,'warn.gif','0','absmiddle',TRUE).'> ';
				
			$strbuf=str_replace('~~','</span>',stripcslashes(nl2br($row['notes'])));	
			$html_thuoc .= str_replace('~','<span style="background-color:yellow">',$strbuf);
			
		$html_thuoc .=		'</td>
							<td>';
							
			if(stristr($row['aux_morenote'],'warn')) 
				$html_thuoc .= '<img '.createComIcon($root_path,'warn.gif','0','absmiddle',TRUE).'> ';
			$strbuf2=str_replace('~~','</span>',stripcslashes(nl2br($row['morenote'])));	
			$html_thuoc .= str_replace('~','<span style="background-color:yellow">',$strbuf2);				
							
		$html_thuoc .=		'</td>
							<td>'.$row['personell_name'].'</td>
						  </tr>';
		
	}
}
				
$html_thuoc .= '</table>';
$pdf->writeHTML($html_thuoc);

// ----------------------------------------------------------------------------

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('PhieuChamSoc.pdf', 'I');


?>
