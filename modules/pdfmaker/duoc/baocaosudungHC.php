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


$listReport = $Cabinet->reportChemicalMonth($dept_nr,$ward_nr,$monthreport,$yearreport);

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
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Báo Cáo Sử Dụng Hóa Chất');
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
			<td valign="top" >
				Bộ Y Tế (Sở Y Tế):..............<br>
				BV: '.$cell.'<br>
				Khoa: '.$deptname.'
			</td>
			<td valign="top" align="center" >
				<font size="15"><b>BÁO CÁO SỬ DỤNG HÓA CHẤT</b></font>
			</td>
			<td valign="top" align="right" >
				MS: 08D/BV-01<br>
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
						<td align="center" rowspan="2" width="160"><b>Tên hóa chất<br>nước sản xuất</b></td>
						<td align="center" rowspan="2" width="40"><b>Đơn vị</b></td>						
						<td align="center" rowspan="2"><b>Đơn giá</b></td>	
						<td align="center" colspan="2" width="100"><b>Lâm sàng</b></td>							
						<td align="center" colspan="2" width="100"><b>Cận lâm sàng</b></td>
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
$k=1; $congkhoan_noitru=0; $congkhoan_ngoaitru=0; $congkhoan_khac=0; $congkhoan_huy=0; $congkhoan_total=0;
					
if(is_object($listReport)){
	$n=$listReport->RecordCount();
	ob_start();	
	for($i=0; $i<=$n; $i++){
	
		if($i<$n)
			$rowReport = $listReport->FetchRow();
	
		if (!isset($old_encode) || ($old_encode!=$rowReport['product_encoder']) || ($i==$n)) {
			if (isset($old_encode) || ($i==$n)){
					echo 	'<td align="right">'.$listitem['pres_in'].'</td>';			//noitru
				if ($listitem['pres_in']*$price>0)
					echo 	'<td align="right">'.($listitem['pres_in']*$price).'</td>';
				else echo '<td></td>';
					echo 	'<td align="right">'.$listitem['pres_out'].'</td>';			//ngoai tru
				if ($listitem['pres_out']*$price>0)
					echo 	'<td align="right">'.($listitem['pres_out']*$price).'</td>';
				else echo '<td></td>';
					echo 	'<td align="right">'.$listitem['use'].'</td>';				//khac
				if ($listitem['use']*$price>0)
					echo 	'<td align="right">'.($listitem['use']*$price).'</td>';
				else echo '<td></td>';
					echo 	'<td align="right">'.$listitem['dest'].'</td>';				//huy
				if ($listitem['dest']*$price>0)
					echo 	'<td align="right">'.($listitem['dest']*$price).'</td>';
				else echo '<td></td>';	
					echo	'<th align="right">'.$total.'</th>';	//Tong cong		
					echo	'<th align="right">'.($total*$price).'</th>';		//Tien
					echo '</tr>';
				
				$congkhoan_noitru += $listitem['pres_in']*$price;
				$congkhoan_ngoaitru += $listitem['pres_out']*$price;
				$congkhoan_khac += $listitem['use']*$price;
				$congkhoan_huy += $listitem['dest']*$price;
				$congkhoan_total += $total*$price;
				
				if($i==$n)
					break;
				
				$listitem['pres_in']=''; $listitem['pres_out']=''; $listitem['use']=''; $listitem['dest']='';
				$total=0;
				$k++;
			}

			$old_encode=$rowReport['product_encoder'];
			$price=$rowReport['price']/1000;
			$flag=1;
		}else {
			$flag=0; 
		}
		
		if ($flag){
			echo '<tr>';
			echo	'<td align="center">'.$k.'.</td>'; //STT
			echo	'<td>'.$rowReport['product_encoder'].'</td>';	//Ma thuoc
			echo	'<td>'.$rowReport['product_name'].'</td>';	//Ten thuoc
			echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
			echo	'<td align="right">'.($rowReport['price']/1000).'</td>';	//Don gia
		}	
		
		if ($rowReport['pres_id']){
			if($resulttemp=$Cabinet->getTypeChemicalPres($rowReport['pres_id'])){
				if($resulttemp['group_pres']=='1')
					$listitem['pres_in']+=$rowReport['total'];
				else
					$listitem['pres_out']+=$rowReport['total'];
			}
			$total+=$rowReport['total'];
		}
			
		if ($rowReport['use_id']){
			$listitem['use']=$rowReport['total'];
			$total+=$rowReport['total'];
		}
			
		if ($rowReport['destroy_id']){
			$listitem['dest']=$rowReport['total'];
			$total+=$rowReport['total'];
		}
		
	}
	
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();	
} else $sTempDiv='<tr><td colspan="15">'.$LDItemNotFound.'</td></tr>';

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
			<td></td><td></td>
			<td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i></td>
		</tr>
		<tr>
			<td align="center"><b>NGƯỜI LẬP BÁO CÁO</b></td>
			<td align="center"><b>KẾ TOÁN DƯỢC</b></td>
			<td align="center"><b>TRƯỞNG KHOA LÂM SÀNG</b></td>
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
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
$pdf->Output('BaocaosudungHC.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+