<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');


if(!isset($Pharma)) $Pharma = new Pharma;
switch($type){
	case 'medicine':
		$report_show = $Pharma->getPutInInfo($report_id);
		$medicine_in_pres = $Pharma->getDetailPutInInfo($report_id);
		break;
	case 'medipot':
		$report_show = $Pharma->getPutInMedInfo($report_id);
		$medicine_in_pres = $Pharma->getDetailPutInMedInfo($report_id);
		break;
	case 'chemical':
		$report_show = $Pharma->getPutInChemicalInfo($report_id);
		$medicine_in_pres = $Pharma->getDetailPutInChemicalInfo($report_id);
		break;		
}

if($report_show!=false){
	$sql1= "select * from care_supplier where supplier='".$report_show['supplier']."' ";
	if($re_sup=$db->Execute($sql1)){
		if($count1=$re_sup->RecordCount()){
			$supplier=$re_sup->FetchRow();
			$suppliername=$supplier['supplier_name'];
			$supplieradd=$supplier['address'];
		}
	}	
	
	switch ($report_show['typeput']){
		case 0: $dang='BHYT '; break;
		case 1: $dang='Kinh phí SN'; break;
		case 2: $dang='CBTC '; break;	
	}
}
 

require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Biên Bản Kiểm Nhập Kho');
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
$header='<table><tr>
			<td width="55%">
				SỞ Y TẾ BÌNH DƯƠNG<br>
				<b>'.$cell.'</b>
			</td>
			<td align="center">
					<b>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</b><br>
					<i>Độc lập - Tự do - Hạnh phúc</i>
			</td>			
		</tr></table>';
$pdf->writeHTML($header);
$pdf->Ln();
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Write(0, 'BIÊN BẢN KIỂM NHẬP KHO', '', 0, 'C', true, 0, false, false, 0);
$pdf->SetFont('dejavusans', '', 10);


//Get data of report
if(!$report_show){
	$html='Không tìm được dữ liệu';
}else{
	$date1 = formatDate2Local($report_show['date_time'],'dd/mm/yyyy');
	$pdf->Write(0, "Mã số nhập: ".$report_show['put_in_id'], '', 0, 'R', 1, 0, false, false, 0);
	$pdf->Write(0, "Hội đồng kiểm nhập gồm có: ", '', 0, 'L', 1, 0, false, false, 0);
	$pdf->writeHTML(nl2br($report_show['hoidongkiemnhap']), true, 0, true, 0);
	$pdf->Ln();
	//Ngay nhap, nguon nhap
	$html4='<table width="100%">
			<tr>
				<td width="55%">Ngày nhập: '.$date1.'<br>
					Nguồn nhập: '.$suppliername.' <br>
					Địa chỉ: '.$supplieradd.' 
				</td>
				<td width="20%">Kinh phí: <br>
					Hình thức thanh toán: <br>
					Số chứng từ: 
				</td>
				<td>'.$dang.' <br>
					'.$report_show['hinhthucthanhtoan'].' <br>
					'.$report_show['voucher_id'].'
				</td>				
			</tr>
			</table>';
	$pdf->writeHTML($html4);	
	$pdf->Ln();
	//Load danh sach thuoc
	$html='	<table cellpadding="2" border="1"  >
					<tr align="center" >
						<td width="20" ><b>TT</b></td>					
						<td width="140" ><b>Tên thuốc, hóa chất, vật tư y tế tiêu hao</b></td>
						<td width="40"><b>'.$LDUnit.'</b></td>
						<td><b>Số kiểm soát</b></td>	
						<td width="45"><b>Nước sản xuất</b></td>
						<td width="63"><b>Hạn dùng</b></td>		
						<td><b>'.$LDCost.'</b></td>
						<td><b>'.$LDNumberOf.'</b></td>	
						<td width="65"><b>'.$LDTotalCost.'</b></td>
						<td width="40"><b>Ghi chú</b></td>
					</tr>';
}					
if(!$medicine_in_pres){	
	$html .='<tr><td colspan="10">Không tìm được dữ liệu</td></tr></table>';
}else{
	$medicine_count = $medicine_in_pres->RecordCount();
	$total=0;
	for($i=1;$i<=$medicine_count;$i++) { 			
		$rowIssue = $medicine_in_pres->FetchRow();								
		if($type=='chemical') $colname='unit_name_of_chemical';
		else  $colname='unit_name_of_medicine';
		$html= $html.'<tr>
						<td align="center">'.$i.'.</td>
						<td>'.$rowIssue['product_name'].'</td>
						<td align="center">'.$rowIssue[$colname].'</td>
						<td>'.$rowIssue['lotid'].'</td>
						<td>'.$rowIssue['nuocsx'].'</td>
						<td align="center">'.formatDate2Local($rowIssue['exp_date'],'dd/mm/yyyy').'</td>
						<td align="right">'.number_format($rowIssue['price'],2).'</td>
						<td align="right">'.number_format($rowIssue['number_voucher']).'</td>
						<td align="right">'.number_format($rowIssue['number_voucher']*$rowIssue['price']).'</td>
						<td>'.$rowIssue['note'].'</td>
					</tr>';					
		$total += ($rowIssue['number_voucher']*$rowIssue['price']);
	}
	
	$totallast = ($total*(1 + $report_show['vat']/100));
	$html = $html.'</table>';
	//$txtmoney_totallast = convertMoney($totallast);
	//$pdf->writeHTMLCell(0, 0, '', '', '<b>Tổng số tiền: '.$txtmoney_totallast.'</b>', 0, 1, 0, true, 'L', true);
}
$pdf->writeHTML($html, true, 0, true, 0);

$pdf->Ln();
$html2='<table width="100%">
		<tr>
			<td width="60%" align="right"><b>Thành tiền</b></td>
			<td align="right"><b>'.number_format($total).'</b></td>
			<td width="5%"></td>
		</tr>
		</table>';
$pdf->writeHTMLCell(0, 10, '', '', $html2, 0, 1, 0, true, 'L', true);

$pdf->Ln();
$html3='<table width="100%">
		<tr>
			<td colspan="5"><i>Nhận xét cảm quan về chất lượng:..................................................</i>
			<br><br><br><br><br><br></td>
		</tr>
		<tr>
			<td width="24%"><b>Trưởng Khoa Dược</b></td>
			<td width="24%"><b>Trưởng phòng TCKT</b></td>
			<td width="16%"><b>Thủ kho dược</b></td>
			<td width="16%"><b>Kế toán dược</b></td>
			<td><b>Cán bộ cung ứng</b></td>
		</tr>
		</table>';
$pdf->writeHTMLCell(0, 25, '', '', $html3, 0, 1, 0, true, 'L', true);

// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('PhieuNhapKho.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+