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
define('LANG_FILE','aufnahme.php');
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
$pdf->SetTitle('Tờ Điều Trị');
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
			<td width="22%" align="center">SỞ Y TẾ BÌNH DƯƠNG<br>
				<b>'.$cell.'</b>				
			</td>
			<td align="center" width="55%"><b><font size="16">TỜ ĐIỀU TRỊ</font></b></td>
			<td>
				MS: 39/BV-01<br>
				Số vào viện: '.$enc.'
			</td>			
		</tr></table>';
$pdf->writeHTML($header_1);
$pdf->Ln();
$pdf->writeHTMLCell(100, 0, '', '', str_pad("Họ tên người bệnh: ..".$encounter['name_last'].' '.$encounter['name_first'], 70, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);   
$pdf->writeHTMLCell(45, 0, '', '', str_pad(" Tuổi: ..".$encounter['tuoi'], 36, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Nam/nữ: ..".$sex_patient, 34, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);

$pdf->writeHTMLCell(90, 0, '', '', str_pad(" Khoa: ".$deptname." ".$wardname, 50, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(40, 0, '', '', str_pad("Buồng: ..".$encounter['current_room_nr'], 20, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Số giường: ", 30, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);

$pdf->writeHTMLCell(0, 0, '', '', str_pad("Chẩn đoán: ..".$encounter['referrer_diagnosis'], 106, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);
$pdf->Ln();

$toggle=0;
$html_thuoc='<table border="1" cellpadding="2">
				<tr>
					<td align="center" width="20%"><b>NGÀY GIỜ</b></td>
					<td align="center" width="40%" align="center"><b>DIỄN BIẾN BỆNH</b></td>
					<td align="center" width="40%" align="center"><b>Y LỆNH</b></td>				
				</tr>';

$medis=$objPrescription->getAllTreatmentOfEncounter($enc,'1');
if(is_object($medis)){
	$count=$medis->RecordCount();
}				
if($count){
	for($i=0;$i<$count;$i++){
		$row_2=$medis->FetchRow();
		if($toggle) $bgc='#f3f3f3';
			else $bgc='#fefefe';
		$toggle=!$toggle;
		
		$datepres = $row_2['date_time_create'];
		if (strlen($datepres)>8)
			$timepres=substr($datepres,-8);
		
		$html_thuoc .= ' <tr bgcolor="'.$bgc.'">
							<td><b>'. formatDate2Local($datepres,'dd/mm/yyyy',false,false,$sepChars).'</b><br>'.$timepres.'</td>
							<td>';
		
		if($row_2['total_cost']>0){
			$html_thuoc .=  nl2br(stripslashes($row_2['diagnosis'])).'<br>'; 				
		}
		$html_thuoc .=  nl2br(stripslashes($row_2['symptoms']));
				
		$html_thuoc .=  '</td><td>';
							
			if ($row_2['total_cost']>0) { 				
					$medicine_result = $objPrescription->getAllMedicineInPres($row_2['prescription_id']);				
					if(is_object($medicine_result)){
						for ($j=0; $j<$medicine_result->RecordCount();$j++) {
							$items_in_sheet = $medicine_result->FetchRow();
							$html_thuoc .= '<b>'.$items_in_sheet['product_name'].'</b><br>';
							$html_thuoc .= $items_in_sheet['desciption'].'/'.$LDUseTimes.' x '.$items_in_sheet['number_of_unit'].' '.$LDUseTimes.': '.$items_in_sheet['time_use'].'<br>'.$items_in_sheet['morenote'].'<br/>';
						}
					}
					$html_thuoc .= '<br>'.stripslashes($row_2['note']);
			} 
			else 
					$html_thuoc .= nl2br(stripslashes($row_2['diagnosis'])); 					
					
							
		$html_thuoc .=  '<br><br>Bác sỹ: '.$row_2['doctor'].'</td></tr>';
		
	}
}
				
$html_thuoc .= '</table>';
$pdf->writeHTML($html_thuoc);

// ----------------------------------------------------------------------------

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('ToDieuTri.pdf', 'I');


?>
