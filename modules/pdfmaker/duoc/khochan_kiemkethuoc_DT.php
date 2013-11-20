<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

require_once($root_path.'include/care_api_classes/class_product.php');
$Product=new Product();
	switch($select_type){	
		case 0: $cond_typeput = ''; $titlereport=''; break;
		case 1: $cond_typeput = ' AND sub.typeput=1 '; $titlereport=' KINH PHÍ '; break;
		case 2: $cond_typeput = ' AND sub.typeput=0 '; $titlereport=' BHYT '; break;
		case 3: $cond_typeput = ' AND sub.typeput=2 '; $titlereport=' CBTC '; break;
		default: $cond_typeput = ' AND sub.typeput=1 '; $titlereport='';
	}	
$listItem = $Product->ShowCatalogKhoChan('', '', $cond_typeput);

require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Kiểm kê thuốc kho chẵn');
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
			<td valign="top" width="200">
				Bộ Y Tế (Sở Y Tế):..............<br>
				BV: '.$cell.'<br>
				Khoa: Khoa Dược - Kho Chẵn
			</td>
			<td valign="top" align="center" width="400">
				<font size="15"><b>BIÊN BẢN KIỂM KÊ THUỐC'.$titlereport.'</b></font>
			</td>
			<td valign="top" align="right" width="200">
				MS: 11D/BV-01<br>
				Số:...................
			</td>			
		</tr>
		<tr><td></td><td align="center"><i>Tháng..........năm...............</i></td><td></td></tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();

//Liet ke thanh vien
$html_1='<table width="100%">
		<tr><td colspan="2">- Tổ kiểm kê gồm có:</td></tr>
		<tr><td>1. ............................................................................................................</td><td>Chức danh: ................................................................................................</td></tr>
		<tr><td>2. ............................................................................................................</td><td>Chức danh: ................................................................................................</td></tr>	
		<tr><td>3. ............................................................................................................</td><td>Chức danh: ................................................................................................</td></tr>
		<tr><td>4. ............................................................................................................</td><td>Chức danh: ................................................................................................</td></tr>	
		<tr><td>5. ............................................................................................................</td><td>Chức danh: ................................................................................................</td></tr>			
		<tr>
		<td colspan="2">- Đã kiểm kê tại:................................................................ từ........giờ........ngày........tháng........năm.......... đến ........giờ........ngày........tháng........năm..........</td>			
		</tr>
		<tr><td colspan="2">- Kết quả như sau:</td></tr>
		</table>';
$pdf->writeHTML($html_1);
$pdf->Ln();

//Load tieu de bang
$html='	<table cellpadding="2" border="1">
			<tr>
				<td rowspan="2" align="center" width="30"><b>STT</b></td>				
				<td rowspan="2" align="center" width="150"><b>Tên thuốc, nồng độ, hàm lượng</b></td>
				<td rowspan="2" align="center" width="50"><b>Đơn vị</b></td>
				<td rowspan="2" align="center" width="60"><b>Đơn giá</b></td>
				<td rowspan="2" align="center"><b>Số kiểm soát</b></td>
				<td rowspan="2" align="center" width="50"><b>Nước sản xuất</b></td>
				<td rowspan="2" align="center" ><b>Hạn dùng</b></td>
				<td colspan="5" align="center" width="300"><b>Số lượng</b></td>
				<td rowspan="2" align="center" width="50"><b>Ghi chú</b></td>
			</tr>
			<tr>
				<td align="center" width="50"><b>Sổ sách</b></td>
				<td align="center" width="75"><b>Thành tiền</b></td>
				<td align="center" width="50"><b>Thực tế</b></td>
				<td align="center" width="75"><b>Thành tiền</b></td>
				<td align="center" width="50"><b>Hỏng vỡ</b></td>
			</tr>';
			
$html= $html.'<tr>';			
for ($j=1;$j<=13;$j++)
	$html= $html.'<td align="center"><b>'.$j.'</b></td>';					
$html= $html.'</tr>';

//Load du lieu bang
$congkhoan = 0;
	if(is_object($listItem)){
		$sTemp='';
		for ($i=0;$i<$listItem->RecordCount();$i++)
		{
			$rowItem = $listItem->FetchRow();
			$congkhoan += $rowItem['price']*$rowItem['number'];
			$expdate= formatDate2Local($rowItem['exp_date'],'dd/mm/yyyy');
			
			$sTemp=$sTemp.'<tr>
								<td align="center">'.($i+1).'</td>
								<td>'.$rowItem['product_name'].'</td>
								<td align="center">'.$rowItem['unit_name_of_medicine'].'</td>
								<td align="right">'.number_format($rowItem['price']).'</td>
								<td align="center">'.$rowItem['lotid'].'</td>
								<td align="center">'.$rowItem['nuocsx'].'</td>
								<td align="center">'.$expdate.'</td>
								<td align="right">'.number_format($rowItem['number']).'</td>
								<td align="right">'.number_format($rowItem['price']*$rowItem['number']).'</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
							</tr>';
		}			
	}else{
		$sTemp='<tr bgColor="#ffffff"><td colspan="11">'.$LDItemNotFound.'</td></tr>'; 
	}
	
$html= $html.$sTemp;
$html= $html.'<tr>
				<td></td><td><b>Cộng khoản</b></td><td></td><td></td><td></td><td></td><td></td><td></td>
				<td align="right"><b>'.number_format($congkhoan).'</b></td><td></td><td></td><td></td><td></td>
			</tr>
		</table>';

$pdf->writeHTML($html);
$pdf->Ln();

//Ky ten
$html2='<table width="100%">
		<tr>
			<td colspan="3">Ý kiến đề xuất:.......................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................</td>
		</tr>
		<tr>
			<td></td><td></td><td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>THÀNH VIÊN</b><br><i>(ký và ghi rõ họ tên)</i></td>
			<td align="center"><b>THƯ KÝ</b></td>
			<td align="center"><b>CHỦ TỊCH HỘI ĐỒNG KIỂM KÊ</b></td>
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
$pdf->Output('KhoChan_Kiemkethuoc.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+