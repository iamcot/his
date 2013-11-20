<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
$lang_tables=array('departments.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
if(!isset($Cabinet)) $Cabinet = new CabinetPharma;


switch($select_type){	
	case 0: $listReport = $Cabinet->reportMedicineMonth($monthreport,$yearreport); 
			$titlereport=''; break;
	case 1: $listReport = $Cabinet->reportMedicineKPMonth($monthreport,$yearreport); 
			$titlereport=' KINH PHÍ '; break;
	case 2: $listReport = $Cabinet->reportMedicineBHMonth($monthreport,$yearreport); 
			$titlereport=' BHYT '; break;
	case 3: $listReport = $Cabinet->reportMedicineCBTCMonth($monthreport,$yearreport); 
			$titlereport=' CBTC '; break;			
	default: $listReport = $Cabinet->reportMedicineMonth($monthreport,$yearreport);
			$titlereport=''; break;
}

require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Báo Cáo Sử Dụng Thuốc');
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
				BV: '.$cell.'
			</td>
			<td valign="top" align="center" width="400">
				<font size="15"><b>BÁO CÁO SỬ DỤNG THUỐC'.$titlereport.'</b></font>
			</td>
			<td valign="top" align="right" width="200">
				MS: 05D/BV-01<br>
				Số:...................
			</td>			
		</tr>
		<tr><td></td><td align="center">Tháng '.$monthreport.'/'.$yearreport.'</td><td></td></tr>
		<tr><td colspan="3" align="right"><i>Đơn vị: 1.000đ</i></td></tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();



	//Load tieu de bang
		$html='	<table cellpadding="2" border="1">
					<tr>
						<td width="25" rowspan="2"><b>STT</b></td>
						<td align="center" rowspan="2" width="30"><b>Mã</b></td>
						<td align="center" rowspan="2" width="160"><b>Tên thuốc, nồng độ,<br>hàm lượng</b></td>
						<td align="center" rowspan="2" width="40"><b>Đơn vị</b></td>						
						<td align="center" rowspan="2"><b>Đơn giá</b></td>	
						<td align="center" colspan="2" width="100"><b>Nội trú</b></td>							
						<td align="center" colspan="2" width="100"><b>Ngoại trú</b></td>
						<td align="center" colspan="2" width="100"><b>Khác</b></td>
						<td align="center" colspan="2" width="100"><b>Hủy</b></td>
						<td align="center" colspan="2"><b>Tổng cộng</b></td>
					</tr>
					<tr>
						<td align="center"><b>Số lượng</b></td>
						<td align="center"><b>Tiền</b></td>
						<td align="center"><b>Số lượng</b></td>
						<td align="center"><b>Tiền</b></td>
						<td align="center"><b>Số lượng</b></td>
						<td align="center"><b>Tiền</b></td>
						<td align="center"><b>Số lượng</b></td>
						<td align="center"><b>Tiền</b></td>
						<td align="center"><b>Số lượng</b></td>
						<td align="center"><b>Tiền</b></td>
					</tr>';
	$html = $html.'<tr bgColor="#E1E1E1">';
	for($j=1;$j<=15;$j++)
		$html= $html.'<td align="center">'.$j.'</td>';
	$html = $html.'</tr>';	
					
//Load du lieu bang
$congkhoan_noitru=0; $congkhoan_ngoaitru=0; $congkhoan_khac=0; $congkhoan_huy=0; $congkhoan_total=0;
$total=0; $flag=0; $flag_1=0;
$i=0;					

if(is_object($listReport)){
	$n=$listReport->RecordCount();
	ob_start();
	for($k=0; $k<=$n; $k++){
		if($k<$n)
			$rowReport = $listReport->FetchRow();
			
		if (!isset($old_encode) || ($old_encode!=$rowReport['product_encoder']) || ($k==$n)) {
			if (isset($old_encode) || ($k==$n)){
					echo 	'<td>'.$listitem['pres_in'].'</td>';
				
				if ($listitem['pres_in']*$price>0)
					echo 	'<td>'.($listitem['pres_in']*$price).'</td>';
				else echo '<td></td>';
				
					echo 	'<td>'.$listitem['pres_out'].'</td>';
				
				if ($listitem['pres_out']*$price>0)
					echo 	'<td>'.($listitem['pres_out']*$price).'</td>';
				else echo '<td></td>';
				
					echo 	'<td>'.$listitem['use'].'</td>';
				
				if ($listitem['use']*$price>0)
					echo 	'<td>'.($listitem['use']*$price).'</td>';
				else echo '<td></td>';
				
					echo 	'<td>'.$listitem['dest'].'</td>';
				
				if ($listitem['dest']*$price>0)
					echo 	'<td>'.($listitem['dest']*$price).'</td>';
				else echo '<td></td>';	
				
					echo	'<th>'.$total.'</th>';	//Tong cong		
					echo	'<th>'.($total*$price).'</th>';		//Tien
					echo '</tr>';
					
				$congkhoan_noitru += $listitem['pres_in']*$price;
				$congkhoan_ngoaitru += $listitem['pres_out']*$price;
				$congkhoan_khac += $listitem['use']*$price;
				$congkhoan_huy += $listitem['dest']*$price;
				$congkhoan_total += $total*$price;
				
				if($k==$n)
					break;
				
				$listitem['pres_in']=''; $listitem['pres_out']=''; $listitem['use']=''; $listitem['dest']='';
				$total=0;
			}
			$old_encode=$rowReport['product_encoder'];
			$price=$rowReport['price']/1000;
			$i++; $flag=1;
		}else {
			$flag=0; //$flag_1=0;
		}
		
		if ($flag){
			echo '<tr bgColor="#ffffff" align="right">';
			echo	'<td align="center">'.$i.'</td>'; //STT
			echo	'<td align="center">'.$rowReport['product_encoder'].'</td>';	//Ma thuoc
			echo	'<td align="left">'.$rowReport['product_name'].'</td>';	//Ten thuoc
			echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
			echo	'<td>'.($rowReport['price']/1000).'</td>';	//Don gia
		}	
		
		if ($rowReport['pres_id']){
			if($resulttemp=$Cabinet->getTypePres($rowReport['pres_id'])){
				if($resulttemp['group_pres']=='1')
					$listitem['pres_in']+=$rowReport['total'];
				else
					$listitem['pres_out']+=$rowReport['total'];
			}
			$total+=$rowReport['total'];
		}
		else if ($rowReport['use_id']){
			$listitem['use']=$rowReport['total'];
			$total+=$rowReport['total'];
		}			
		else if ($rowReport['destroy_id']){
			$listitem['dest']=$rowReport['total'];
			$total+=$rowReport['total'];
		}
	
	}
	
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();	
} else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="15">'.$LDItemNotFound.'</td></tr>';

$html = $html.$sTempDiv;

$html = $html.'<tr>';
for($j=1;$j<=15;$j++){
	if($j>6 && $j%2==1)
		$html= $html.'<td align="right">-</td>';
	else
		$html= $html.'<td></td>';
}	
$html = $html.'</tr>';	
	
$html = $html.' <tr>
					<td></td><td></td><td><b><i>Cộng khoản:</i></b></td><td></td><td></td>
					<td></td><td align="right"><b>'.number_format($congkhoan_noitru,3).'</b></td>
					<td></td><td align="right"><b>'.number_format($congkhoan_ngoaitru,3).'</b></td>
					<td></td><td align="right"><b>'.number_format($congkhoan_khac,3).'</b></td>
					<td></td><td align="right"><b>'.number_format($congkhoan_huy,3).'</b></td>
					<td></td><td align="right"><b>'.number_format($congkhoan_total,3).'</b></td>
				</tr>
			</table>';				
$pdf->writeHTML($html, true, 0, true, 0);


$pdf->Ln();
$html2='<table width="100%">
		<tr>
			<td></td><td></td><td></td>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>NGƯỜI LẬP BÁO CÁO</b></td>
			<td align="center"><b>TRƯỞNG PHÒNG TCKT</b></td>
			<td align="center"><b>TRƯỞNG KHOA DƯỢC</b></td>
			<td align="center"><b>GIÁM ĐỐC<br><i>(Ký tên, đóng dấu)</i></b></td>
		</tr>
		<tr><td colspan="4"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
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
$pdf->Output('Baocaosudungthuoc.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+