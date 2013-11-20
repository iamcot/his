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
		$report_show = $Cabinet->getDestroyInfo($report_id);
		$medicine_in_pres = $Cabinet->getDetailDestroyInfo($report_id);
	}else{
		$report_show = $Cabinet->getChemicalInDestroy($report_id);
		$medicine_in_pres = $Cabinet->getDetailChemicalDestroyInfo($report_id);	
	}
}else{
	include_once($root_path.'include/care_api_classes/class_cabinet_medipot.php');
	if(!isset($Cabinet)) $Cabinet = new CabinetMedipot;
	
	$report_show = $Cabinet->getDestroyInfo($report_id);
	$medicine_in_pres = $Cabinet->getDetailDestroyInfo($report_id);
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
$pdf->SetTitle('Biên Bản Thanh Lý Thuốc, VTYT, HC');
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
				<font size="12"><b>BIÊN BẢN THANH LÝ THUỐC, HÓA<br>CHẤT, VẬT TƯ Y TẾ TIÊU HAO</b></font>
			</td>
			<td valign="top" align="right" width="120">
				MS: 15D/BV-01<br>
				Số:...................
			</td>			
		</tr>
		<tr><td></td><td align="center">Tháng ........ năm ........</td><td></td></tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();

//Liet ke thanh vien
$html_1='<table width="90%">
		<tr>
			<td colspan="2">
				- Hội đồng thanh lý gồm có:<br>
				1. Chủ tịch Hội Đồng:<br>
				2. Thư ký:<br>
				3. Trưởng khoa Dược:<br>
				4. Trưởng phòng tài chính - Kế toán:<br>
				5. Thống kê dược:
			</td>
		</tr>	
		<tr>
			<td>- Đã tiến hành họp xét thanh lý tại:.......................</td>
			<td>  từ........ giờ........, ngày ..../..../'.date('Y').'<br>đến....... giờ........, ngày ..../..../'.date('Y').'</td>			
		</tr>
		<tr><td colspan="2">- Kết quả như sau:</td></tr>
		</table>';
$pdf->writeHTML($html_1);
$pdf->Ln();

//Get data of report

if (!$report_id || !$medicine_in_pres){
	$html='Không tìm được dữ liệu';
} else {

	//Load danh sach thuoc
	$html='	<table cellpadding="2" border="1">
					<tr>
						<td width="25" rowspan="2"><b>STT</b></td>
						<td align="center" rowspan="2" width="150"><b>Tên thuốc, Hóa chất,<br>Vật tư y tế tiêu hao</b></td>
						<td align="center" rowspan="2" width="40"><b>Đơn vị</b></td>
						<td align="center" rowspan="2" width="50"><b>Số kiểm soát</b></td>
						<td align="center" rowspan="2" width="50"><b>Nước sản xuất</b></td>
						<td align="center" rowspan="2" width="50"><b>Hạn dùng</b></td>
						<td align="center" rowspan="2"><b>Đơn giá</b></td>	
						<td align="center" colspan="2" width="100"><b>Xin thanh lý</b></td>							
						<td align="center" rowspan="2" width="65"><b>Kết luận, ghi chú</b></td>
					</tr>
					<tr>
						<td align="center" width="40"><b>Số lượng</b></td>
						<td align="center" width="60"><b>Thành tiền</b></td>
					</tr>
					<tr>
						<td align="center"><b>1</b></td><td align="center"><b>2</b></td><td align="center"><b>3</b></td><td align="center"><b>4</b></td><td align="center"><b>5</b></td>
						<td align="center"><b>6</b></td><td align="center"><b>7</b></td><td align="center"><b>8</b></td><td align="center"><b>9</b></td><td align="center"><b>10</b></td>
					</tr>';
					
	
	$medicine_count = $medicine_in_pres->RecordCount();
	$tongtien=0;
	for($i=1;$i<=$medicine_count;$i++) { 			
		$rowIssue = $medicine_in_pres->FetchRow();	
		$tongtien += $rowIssue['cost']*$rowIssue['number'];
		$html= $html.'<tr>
						<td align="center">'.$i.'.</td>
						<td>'.$rowIssue['product_name'].'</td>				
						<td align="center">'.$rowIssue['units'].'</td>
						<td>'.$rowIssue['sodangky'].'</td>	
						<td>'.$rowIssue['nuocsx'].'</td>
						<td>'.formatDate2Local($rowIssue['exp_date'],'dd/mm/yyyy').'</td>	
						<td align="right">'.number_format($rowIssue['cost']).'</td>						
						<td align="right">'.number_format($rowIssue['number']).'</td>						
						<td align="right">'.number_format($rowIssue['cost']*$rowIssue['number']).'</td>
						<td>'.$rowIssue['note'].'</td>
					</tr>';					
	}
	
	$html = $html.'	<tr>
						<td></td><td>Cộng khoản:</td>
						<td></td><td></td><td></td><td></td><td></td><td></td><td align="right">'.number_format($tongtien).'</td><td></td>
					</tr>
				</table>';
				
	$pdf->writeHTML($html, true, 0, true, 0);

}

$pdf->Ln();
$html2='<table width="100%">
		<tr>
			<td colspan="3">Ý kiến đề xuất:...........................................................................................................................................................................................................................................................................................................................................</td>
		</tr>
		<tr>
			<td colspan="3" align="right"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>THÀNH VIÊN</b><br><i>(ký và ghi rõ họ tên)</i></td>
			<td align="center"><b>THƯ KÝ</b></td>
			<td align="center"><b>CHỦ TỊCH HỘI ĐỒNG</b></td>
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td></td>
			<td align="center">Họ tên.....................</td>
			<td align="center">Họ tên.....................</td>
		</tr>		
		</table>';
$pdf->writeHTMLCell(0, 25, '', '', $html2, 0, 1, 0, true, 'L', true);
// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('BienBanThanhLyThuoc_VTYT_HC.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+