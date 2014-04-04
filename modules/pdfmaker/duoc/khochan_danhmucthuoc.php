<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_product.php');
$Product=new Product();

switch($type){
	case 'tayy': $dongtayy = ' AND khochan.pharma_type IN (1,2,3) ';
		$title1=$LDMedicineCatalogue; break;
	case 'dongy': $dongtayy = ' AND khochan.pharma_type IN (4,8,9,10) '; 
		$title1=$LDVNMedicineCatalogue; break;
	default: $dongtayy = ''; $title1=$LDMedicineList; break;
}

if ($mode=='sort_up'){
	$updown='';
}else if ($mode=='sort_down') {
	$updown=' DESC ';
}


require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Kho chẵn - '.$title1);
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
				<font size="15"><b>DANH MỤC THUỐC</b></font>
			</td>
			<td valign="top" align="right" width="25%">
				MS:................... <br>
				Số:...................
			</td>			
		</tr>
		<tr><td></td><td align="center"><i>Ngày...'.date("d/m/Y").'....</i></td><td></td></tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();

$pdf->SetFont('dejavusans', '', 9);
//Load tieu de bang
$html='	<table cellpadding="2" border="1">
			<tr bgColor="#E1E1E1">
				<td align="center" width="25"><b>STT</b></td>
				<td align="center" width="160"><b>Tên thuốc</b></td>
				<td align="center" width="45"><b>Đơn vị</b></td>
				<td align="center" width="55"><b>Số lô</b></td>
				<td align="center" width="70"><b>Đơn giá</b></td>
				<td align="center" width="55"><b>Số lượng</b></td>
				<td align="center" width="60"><b>Hạn dùng</b></td>
				<td align="center" width="50"><b>Ghi chú</b></td>
				<td align="center" width="50"><b>Dùng</b></td>
			</tr>';
			
//Load du lieu bang

if ($mode=='sort_name')
	$listItem = $Product->ShowNumberCatalogKhoChan_OrderByName($dongtayy, $current_page, $number_items_per_page);
else
	$listItem = $Product->ShowNumberCatalogKhoChan($dongtayy, $current_page, $number_items_per_page, $updown);

if(is_object($listItem)){	
	$sTemp='';
	$n=$listItem->RecordCount();
	for ($i=0;$i<$n;$i++)
	{
		$rowItem = $listItem->FetchRow();
				
		$expdate= formatDate2Local($rowItem['exp_date'],'dd/mm/yyyy');
		if (round($rowItem['price'],3)==round($rowItem['price']))
			$show_price = number_format($rowItem['price']);
		else $show_price = number_format($rowItem['price'],3);
			
		if($rowItem['typeput']==0)
			$usefor=$LDBHYT;
		else
			$usefor=$LDSuNghiep;
			
		$sTemp=$sTemp.'<tr>
							<td align="center">'.($i+1).'</td>
							<td>'.$rowItem['product_name'].'</td>
							<td align="center">'.$rowItem['unit_name_of_medicine'].'</td>
							<td align="center">'.$rowItem['lotid'].'</td>
							<td align="right">'.$show_price.'</td>
							<td align="right">'.number_format($rowItem['numbersum']).'</td>
							<td align="center">'.$expdate.'</td>
							<td align="center"></td>
							<td align="center">'.$usefor.'</td>
						</tr>';
	}
		
} else $sTemp='<tr bgColor="#ffffff"><td colspan="11">'.$LDItemNotFound.'</td></tr>';

$html = $html.$sTemp.'</table>';
	

$pdf->writeHTML($html);


// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('KhoChan_TheKho.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>