<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

require_once($root_path.'include/care_api_classes/class_pharma.php');
$Pharma = new Pharma;

	switch($select_type){	
		case 0: $cond_typeput = ''; $titlereport=''; break;
		case 1: $cond_typeput = ' AND typeput=1 '; $titlereport=' KP '; break;
		case 2: $cond_typeput = ' AND typeput=0 '; $titlereport=' BHYT '; break;
		case 3: $cond_typeput = ' AND typeput=2 '; $titlereport=' CBTC '; break;
		default: $cond_typeput = ' '; $titlereport='';
	}

if($result_info = $Pharma->getInfoMedipot($encoder)){
	$medicine_name = $result_info['product_name'];		//Ten vtyt
	$medicine_unit = $result_info['unit_name_of_medicine'];	//Don vi
	$medicine_content = '';	//Quy cach dong goi
}
	
//Ton kho gan nhat (x1) truoc ngay fromdate 
$result_ton = $Pharma->Khochan_vtyt_tontruoc($encoder, $cond_typeput." AND toninfo.todate<'$fromdate' ");	
	if($result_ton!=false){
		$ton_trc = $result_ton['last_number'];
		$ngayton_trc = $result_ton['todate'];
	}else{
		$ton_trc = 0;
		$ngayton_trc = '2012-01-01';
	}
//Tong nhap xuat (x1->x) truoc ngay fromdate 
$tongnhap_trc=0;
$tongxuat_trc=0;
$result_nhapxuat = $Pharma->Khochan_vtyt_tongnhapxuat_theongay($encoder, $ngayton_trc, $fromdate, $cond_typeput, 0);
if(is_object($result_nhapxuat)){
	while($tempnx = $result_nhapxuat->FetchRow()){
		$tongnhap_trc += $tempnx['tongnhap'];
		$tongxuat_trc += $tempnx['tongxuat'];
	}
}
$tondauky = $ton_trc + $tongnhap_trc - $tongxuat_trc; 
	
//The kho: nhap xuat tu fromdate(x) -> todate(y)
$listReport = $Pharma->Khochan_vtyt_thekho($encoder, $fromdate, $todate, $cond_typeput);


require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Kho chẵn - Thẻ kho');
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
			<td valign="top" width="25%">
				SỞ Y TẾ BÌNH DƯƠNG<br>
				<b>'.$cell.'</b><br>
				Khoa Dược - Kho Chẵn
			</td>
			<td valign="top" align="center" width="50%">
				<font size="15"><b>THẺ KHO</b></font>
			</td>
			<td valign="top" align="right" width="25%">
				MS: 04D/BV-01<br>
				Số:...................
			</td>			
		</tr>
		<tr><td></td><td align="center"><i>Ngày..........tháng..........năm...............</i></td><td></td></tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();

//Liet ke thanh vien
$html_1='<table width="100%">
		<tr><td width="80%"><b>Tên thuốc, hóa chất, vật tư y tế tiêu hao:</b> ...'.$medicine_name.'......</td><td width="20%"><b>Mã số:</b> ...'.$encoder.'.....</td></tr>
		<tr><td><b>Hàm lượng, nồng độ, quy cách đóng gói:</b> ...'.str_pad($medicine_content, 60, ".", STR_PAD_RIGHT).'</td><td><b>Mã vạch:</b> .........</td></tr>
		<tr><td colspan="2"><b>Đơn vị:</b> ...'.str_pad($medicine_unit, 150, ".", STR_PAD_RIGHT).'</td></tr>
		</table>';
$pdf->writeHTML($html_1);
$pdf->Ln();
$pdf->SetFont('dejavusans', '', 9);
//Load tieu de bang
$html='	<table cellpadding="2" border="1">
			<tr bgColor="#E1E1E1">
				<td rowspan="2" align="center" width="60"><b>Ngày tháng</b></td>
				<td colspan="2" align="center" width="90"><b>Số chứng từ</b></td>
				<td rowspan="2" align="center" width="45"><b>Lô sản xuất</b></td>
				<td rowspan="2" align="center" width="60"><b>Hạn dùng</b></td>
				<td rowspan="2" align="center" width="60"><b>Diễn giải</b></td>
				<td rowspan="2" align="center"><b>Số lượng tồn đầu kỳ</b></td>
				<td colspan="3" align="center"><b>Số lượng</b></td>
				<td rowspan="2" align="center"><b>Ghi chú</b></td>
			</tr>
			<tr bgColor="#E1E1E1">
				<td align="center" width="45"><b>Nhập</b></td>
				<td align="center" width="45"><b>Xuất</b></td>	
				<td align="center"><b>Nhập</b></td>
				<td align="center"><b>Xuất</b></td>
				<td align="center"><b>Tồn cuối kỳ</b></td>		
			</tr>';
			
//Load du lieu bang
$i=0;
$toncuoiky = $tondauky;

if(is_object($listReport)){
	$listReport->MoveFirst();
	ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		if($rowReport['manhap']>0){	//NHAP
			$toncuoiky += $rowReport['number_voucher'];
			echo '<tr bgcolor="#ffffff">';
			echo 	'<td>'.@formatDate2Local($rowReport['ngay'],'dd/mm/yyyy').'</td>';	
			echo 	'<td>'.$rowReport['voucher_id'].'</td><td></td>';	
			echo 	'<td>'.$rowReport['lotid'].'</td>';	
			echo 	'<td>'.@formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy').'</td>';
			echo 	'<td>'.$rowReport['lydo'].'</td>';			
			if($i==0) echo '<td align="right">'.number_format($tondauky).'</td>'; else echo '<td></td>';
			
			echo 	'<td align="right">'.number_format($rowReport['number_voucher']).'</td>';	//nhap
			echo 	'<td></td>';	//xuat
			echo 	'<td align="right">'.number_format($toncuoiky).'</td>';	//ton cuoi
			echo 	'<td></td>';	//ghi chu
			echo '</tr>';
			$i++;
		}else{	//XUAT
			$toncuoiky -= $rowReport['number_voucher'];
			echo '<tr bgcolor="#ffffff">';
			echo 	'<td>'.@formatDate2Local($rowReport['ngay'],'dd/mm/yyyy').'</td>';	
			echo 	'<td></td><td>'.$rowReport['voucher_id'].'</td>';	
			echo 	'<td>'.$rowReport['lotid'].'</td>';	
			echo 	'<td>'.@formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy').'</td>';
			
			if($rowReport['lydo']>0){
				$tramyte = $Pharma->getNameHealthStation($rowReport['lydo']);
				$xuatcho = $tramyte['name'];
			}else $xuatcho = $LDKhoLe;	
			echo 	'<td>'.$xuatcho.'</td>';
			
			if($i==0) echo '<td align="right">'.number_format($tondauky).'</td>'; else echo '<td></td>';
			echo 	'<td></td>';	//nhap
			echo 	'<td align="right">'.number_format($rowReport['number_voucher']).'</td>';	//xuat
			echo 	'<td align="right">'.number_format($toncuoiky).'</td>';	//ton cuoi
			echo 	'<td></td>';	//ghi chu
			echo '</tr>';
			$i++;		
		}
	}		
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();
		
} else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="11">'.$LDItemNotFound.'</td></tr>';

$html = $html.$sTempDiv.'</table>';

$pdf->writeHTML($html);


// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('KhoChan_TheKho.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+