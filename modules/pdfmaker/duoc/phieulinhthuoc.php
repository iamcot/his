<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
$lang_tables=array('departments.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_issuepaper.php');


if(!isset($IssuePaper)) $IssuePaper = new IssuePaper;

$report_show = $IssuePaper->getIssuePaperInfo($report_id);
$medicine_in_pres = $IssuePaper->getAllMedicineInIssuePaper($report_id);


$ward_nr = $report_show['ward_nr'];
$dept_nr = $report_show['dept_nr'];
//Get info of current department, ward
if ($ward_nr!='' && $ward_nr!='0'){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} else if ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
	}
}

require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor('BV Đa Khoa Dầu Tiếng');
$pdf->SetTitle('Phiếu Lĩnh Thuốc');
$pdf->SetMargins(5, 8, 3);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

// add a page
$pdf->AddPage();
// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);
$header='<table width="100%">
		<tr>
			<td valign="top">
				Bộ Y Tế (Sở Y Tế):..............<br>
				BV: '.$cell.'<br>
				Khoa: '.$deptname.'
			</td>
			<td valign="top" align="center">
				<font size="12"><b>PHIẾU LĨNH THUỐC</b></font>
			</td>
			<td valign="top" align="right">
				MS: 01D/BV-01<br>
				Số:...................
			</td>			
		</tr></table>';
$pdf->writeHTML($header);
$pdf->Ln();

//Get data of report



if (!$report_id || !$medicine_in_pres){
	$html='Không tìm được dữ liệu';
} else {

	//Load danh sach thuoc
	$html='	<table cellpadding="2" border="1">
					<tr>
						<td rowspan="2" width="30"><b>STT</b></td>
						<td rowspan="2" align="center"><b>Mã</b></td>
						<td rowspan="2" align="center" width="170"><b>Tên thuốc, hàm lượng</b></td>
						<td rowspan="2" align="center" width="50"><b>Đơn vị</b></td>
						<td colspan="2" align="center"><b>Số lượng</b></td>
						<td rowspan="2" align="center"><b>Ghi chú</b></td>
					</tr>
					<tr>
						<td align="center"><b>Yêu cầu</b></td>
						<td align="center"><b>Phát</b></td>
					</tr>';
					
	
	$medicine_count = $medicine_in_pres->RecordCount();
	$sl_yeucau=0; $sl_phat=0;
	for($i=1;$i<=$medicine_count;$i++) { 			
		$rowIssue = $medicine_in_pres->FetchRow();								
		$sl_yeucau += $rowIssue['number_request'];
		$sl_phat += $rowIssue['number_receive'];
		$html= $html.'<tr>
						<td align="center">'.$i.'.</td>
						<td>'.$rowIssue['product_encoder'].'</td>
						<td>'.$rowIssue['product_name'].'</td>				
						<td align="center">'.$rowIssue['units'].'</td>
						<td align="center">'.number_format($rowIssue['number_request']).'</td>
						<td align="center">'.number_format($rowIssue['number_receive']).'</td>
						<td>'.$rowIssue['note'].'</td>
					</tr>';					
	}
	
	$html = $html.'<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
					<tr>
						<td></td><td></td><td><i>Cộng khoản:</i></td><td></td>
						<td align="center"><b>'.number_format($sl_yeucau).'</b></td>
						<td align="center"><b>'.number_format($sl_phat).'</b></td>
						<td></td>
					</tr>
				</table>';
				
	$pdf->writeHTML($html, true, 0, true, 0);

}

$pdf->Ln();
$html2='<table width="100%">
		<tr>
			<td colspan="4" align="right"><i>Ngày ..... tháng ..... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>TRƯỞNG KHOA DƯỢC</b></td>
			<td align="center"><b>NGƯỜI PHÁT</b></td>
			<td align="center"><b>NGƯỜI LĨNH</b></td>
			<td align="center"><b>TRƯỞNG KHOA LÂM SÀNG</b></td>
		</tr>
		<tr><td colspan="4"><br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td align="center">Họ tên.....................</td>
			<td align="center">Họ tên.....................</td>
			<td align="center">Họ tên.....................</td>
			<td align="center">Họ tên.....................</td>
		</tr>		
		</table>';
$pdf->writeHTMLCell(0, 25, '', '', $html2, 0, 1, 0, true, 'L', true);
// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('PhieuLinhThuoc.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+