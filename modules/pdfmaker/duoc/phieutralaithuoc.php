<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
$lang_tables=array('departments.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

if($type=='medicine' || $type=='chemical'){
	include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
	if(!isset($Cabinet)) $Cabinet = new CabinetPharma;

	$Cabinet->useCabinetReturn();
	if($type=='medicine'){
		$report_show = $Cabinet->getReturnInfo($report_id);
		$medicine_in_pres = $Cabinet->getDetailReturnInfo($report_id);
	}else{
		$report_show = $Cabinet->getChemicalInReturn($report_id);
		$medicine_in_pres = $Cabinet->getDetailChemicalReturnInfo($report_id);	
	}
}else{
	include_once($root_path.'include/care_api_classes/class_cabinet_medipot.php');
	if(!isset($Cabinet)) $Cabinet = new CabinetMedipot;
	
	$report_show = $Cabinet->getReturnInfo($report_id);
	$medicine_in_pres = $Cabinet->getDetailReturnInfo($report_id);
}


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
$pdf->SetAuthor($cell);
$pdf->SetTitle('Phiếu Trả Lại Thuốc, VTYT, HC');
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
			<td valign="top" width="180">
				Bộ Y Tế (Sở Y Tế):..............<br>
				BV: '.$cell.'<br>
				Khoa: '.$deptname.'
			</td>
			<td valign="top" align="center"  width="270">
				<font size="12"><b>PHIẾU TRẢ LẠI THUỐC, HÓA CHẤT,<br>VẬT TƯ Y TẾ TIÊU HAO</b></font>
			</td>
			<td valign="top" align="right" width="120">
				MS: 05D/BV-01<br>
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
						<td width="30"><b>STT</b></td>
						<td align="center" width="150"><b>Tên thuốc/Hóa chất/<br>Vật tư y tế tiêu hao</b></td>
						<td align="center" width="50"><b>Đơn vị</b></td>
						<td align="center"><b>Số kiểm soát</b></td>
						<td align="center"><b>Số lượng</b></td>
						<td align="center"><b>Đơn giá</b></td>	
						<td align="center"><b>Thành tiền</b></td>							
						<td align="center"><b>Ghi chú</b></td>
					</tr>
					<tr>
						<td align="center"><b>1</b></td><td align="center"><b>2</b></td><td align="center"><b>3</b></td><td align="center"><b>4</b></td>
						<td align="center"><b>5</b></td><td align="center"><b>6</b></td><td align="center"><b>7</b></td><td align="center"><b>8</b></td>
					</tr>';
					
	
	$medicine_count = $medicine_in_pres->RecordCount();
	$sl_yeucau=0; $sl_phat=0;
	for($i=1;$i<=$medicine_count;$i++) { 			
		$rowIssue = $medicine_in_pres->FetchRow();								
		$html= $html.'<tr>
						<td align="center">'.$i.'.</td>
						<td>'.$rowIssue['product_name'].'</td>				
						<td align="center">'.$rowIssue['units'].'</td>
						<td>'.$rowIssue['sodangky'].'</td>						
						<td align="right">'.number_format($rowIssue['number']).'</td>
						<td align="right">'.number_format($rowIssue['cost']).'</td>
						<td align="right">'.number_format($rowIssue['cost']*$rowIssue['number']).'</td>
						<td>'.$rowIssue['note'].'</td>
					</tr>';					
	}
	
	$html = $html.'</table>';
				
	$pdf->writeHTML($html, true, 0, true, 0);

}

$pdf->Ln();
$html2='<table width="100%">
		<tr>
			<td colspan="4" align="right"><i>Ngày ..... tháng ..... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>TRƯỞNG KHOA DƯỢC</b></td>
			<td align="center"><b>TRƯỞNG PHÒNG<br>TÀI CHÍNH-KẾ TOÁN</b></td>
			<td align="center"><b>NGƯỜI LẬP PHIẾU</b></td>
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
$pdf->Output('PhieuTraLaiThuoc_VTYT_HC.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+