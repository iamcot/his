<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
$lang_tables=array('departments.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

//Test format fromday
if (isset($fromdate) && $fromdate!='' && strpos($fromdate,'-')<3) {
	list($f_day,$f_month,$f_year) = explode("-",$fromdate);
	$fromdate=$f_year.'-'.$f_month.'-'.$f_day;
}
else 
	list($f_year,$f_month,$f_day) = explode("-",$fromdate);
//Test format today
if (isset($todate) && $todate!='' && strpos($todate,'-')<3) {
	list($t_day,$t_month,$t_year) = explode("-",$todate);
	$todate=$t_year.'-'.$t_month.'-'.$t_day;
}
else 
	list($t_year,$t_month,$t_day) = explode("-",$todate);
	
//Search item from date
$j=(int)$f_day; $total=0;
$i=0;
if ($f_day>16)
	$end_day=31;
else
	$end_day=$f_day+15;
	
if ($t_day>$end_day)
	$todate=$t_year.'-'.$t_month.'-'.$end_day;	

switch($type){	
	case 'medicine':
			include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
			if(!isset($Cabinet)) $Cabinet = new CabinetPharma;	
			$listReport = $Cabinet->report15Day($dept_nr,$ward_nr,$fromdate,$todate);
			break;
	case 'chemical':	
			include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
			if(!isset($Cabinet)) $Cabinet = new CabinetPharma;		
			$listReport = $Cabinet->reportChemical15Day($dept_nr,$ward_nr,$fromdate,$todate);
			break;
	case 'medipot':
			require_once($root_path.'include/care_api_classes/class_cabinet_medipot.php');
			$CabinetMedipot = new CabinetMedipot;		
			$listReport = $CabinetMedipot->report15Day($dept_nr,$ward_nr,$fromdate,$todate);
			break;
	default: 		//medicine
			include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
			if(!isset($Cabinet)) $Cabinet = new CabinetPharma;	
			$listReport = $Cabinet->report15Day($dept_nr,$ward_nr,$fromdate,$todate);	
			break;
}
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
$pdf->SetTitle('Thống kê 15 ngày sử dụng');
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
				Khoa: '.$deptname.'
			</td>
			<td valign="top" align="center" width="400">
				<font size="15"><b>THỐNG KÊ 15 NGÀY SỬ DỤNG THUỐC, HÓA CHẤT, VẬT TƯ Y TẾ TIÊU HAO</b></font>
			</td>
			<td valign="top" align="right" width="200">
				MS: 16D/BV-01<br>
				Số:...................
			</td>			
		</tr>
		<tr><td></td><td align="center"><i><b>Từ ngày '.$f_day.'/'.$f_month.'/'.$f_year.' đến '.$t_day.'/'.$t_month.'/'.$t_year.'</b></i></td><td></td></tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();

//Load tieu de bang
$html='	<table cellpadding="2" border="1">
			<tr>
				<td rowspan="2" align="center" width="30"><b>STT</b></td>				
				<td rowspan="2" align="center" width="150"><b>Tên thuốc (nồng độ/hàm lượng)/ hóa chất/ vật tư y tế tiêu hao</b></td>
				<td rowspan="2" align="center"><b>Đơn vị</b></td>
				<td rowspan="2" align="center"><b>Quy cách</b></td>
				<td colspan="16" align="center" width="480"><b>Ngày</b></td>
				<td rowspan="2" align="center" width="50"><b>Tổng cộng</b></td>
				<td rowspan="2" align="center"><b>Ghi chú</b></td>
			</tr>';
					
					
//Load day ngay bao cao					
ob_start();
echo '<tr align="center">';
if ($f_day=='')
	$f_day=1;
for ($i=$f_day;$i<$f_day+16;$i++){
	if($i<=31){
		$temp=str_pad((int) $i,2,"0",STR_PAD_LEFT);
		echo '<td>'.$temp.'</td>';
	} else echo '<td>&nbsp;&nbsp;&nbsp;</td>';
	
}
echo '</tr>';
$sTempDay = ob_get_contents();				
ob_end_clean();
$html = $html.$sTempDay;

$html = $html.'<tr bgColor="#E1E1E1" align="center">
				<td>A</td> <td>B</td> <td>C</td> <td>D</td>
				<td></td> <td></td> <td></td> <td></td>
				<td></td> <td></td> <td></td> <td></td>
				<td></td> <td></td> <td></td> <td></td>
				<td></td> <td></td> <td></td> <td></td>
				<td>E</td> <td>G</td>
			</tr>';				
					
//Load du lieu bang
$congkhoan_total=0; $i=0;
					
if(is_object($listReport)){
	ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		if (!isset($old_encode) || ($old_encode!=$rowReport['product_encoder'])) {
			if (isset($old_encode)){
				for ($j;$j<=$end_day;$j++)
					echo '<td></td>';
				echo	'<td align="right">'.$total.'</td>';	//Tong cong		
				echo	'<td></td>	</tr>';		//Note
			}
			$old_encode=$rowReport['product_encoder'];
			$j=(int)$f_day;
			$congkhoan_total+=$total;
			$flag=1; $total=0;
			$i++;			
		}else $flag=0;
		
			
		if ($flag){
			echo '<tr bgColor="#ffffff" >';
			echo	'<td align="center">'.$i.'</td>'; //STT
			echo	'<td>'.$rowReport['product_name'].'</td>';	//Ten thuoc
			echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
			echo	'<td> </td>';	//Quy cach?		
		}
		for($j;$j<=(int)$rowReport['at_day'];$j++) {
			if ($j==(int)$rowReport['at_day']){
				echo '<td align="right">'.$rowReport['total'].'</td>';  //Ngay
				$total+=$rowReport['total'];
				$j++;
				break;
			}
			else
				echo '<td></td>';
		}
							
	}
	for ($j;$j<=$end_day;$j++)
		echo '<td></td>';
	$congkhoan_total+=$total;
	echo	'<td align="right">'.$i.'</td>';	//Tong cong
	echo	'<td></td>	</tr>';		//Note
			
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();	
} else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="22">'.$LDItemNotFound.'</td></tr>';

$html = $html.$sTempDiv;

$html = $html.'<tr>';
for($j=1;$j<=22;$j++){
	if($j==21)
		$html= $html.'<td align="right">-</td>';
	else
		$html= $html.'<td></td>';
}	
$html = $html.'</tr>';	
	
$html = $html.' <tr>
					<td></td> <td><b><i>Cộng khoản:</i></b></td>';
for($j=1;$j<=18;$j++)
	$html= $html.'	<td></td>';
$html = $html.'		<td align="right"><b>'.number_format($i).'</b></td><td></td>
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
$pdf->Output('Thongke15ngaydung.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+