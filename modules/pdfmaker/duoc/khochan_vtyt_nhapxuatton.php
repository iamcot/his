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

//select_type=0&typedongtay=tayy&month=8&year=2012&flag=...

	switch($select_type){	
		case 0: $cond_typeput = ''; break;
		case 1: $cond_typeput = ' AND typeput=1 '; $titlereport=' KINH PHÍ '; break;		//su nghiep
		case 2: $cond_typeput = ' AND typeput=0 '; $titlereport=' BHYT '; break;		//bhyt
		case 3: $cond_typeput = ' AND typeput=2 '; $titlereport=' CBTC '; break;		//cbtc
		default: $cond_typeput = ' '; $titlereport='';
	}
	
	
//$listItem = $Product->ShowKhoChanThuoc_Ton($dongtayy_cond, '', '', $cond_typeput, $todate);


require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Báo cáo nhập xuất tồn VTYT kho chẵn');
$pdf->SetMargins(2, 8, 3);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

// add a page
$pdf->AddPage();
// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 9);
$header='<table width="100%">
		<tr>
			<td valign="top" width="200">
				SỞ Y TẾ BÌNH DƯƠNG<br>
				<b>'.$cell.'</b><br>
				Khoa Dược - Kho Chẵn
			</td>
			<td valign="top" align="center" width="400">
				<font size="12">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</font><br>
				<font size="11">Độc lập - Tự do - Hạnh phúc</font>
			</td>
			<td valign="top" align="right" width="200"></td>			
		</tr>
		<tr><td></td><td align="center">
			<font size="15"><b>BÁO CÁO XUẤT NHẬP TỒN KHO </b></font><br></td><td></td>
		</tr>
		<tr><td></td><td align="center">	
				<font size="11"><b>Tháng: '.$month.'/'.$year.'</b></font><br>
				<font size="13"><b>VTYT'.$titlereport.'</b></font></td><td></td>
		</tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();


//Load tieu de bang
$html='	<table cellpadding="2" border="1">
				<tr bgColor="#F2F2F2">
					<th rowspan="2" align="center" width="20">STT</th>
					<th rowspan="2" align="center" width="95">Tên VTYT</th>
					<th rowspan="2" align="center" width="30">Đơn vị</th>
					<th rowspan="2" align="center" width="35">Số lô</th>
					<th rowspan="2" align="center" width="35">Hạn dùng</th>
					<th colspan="3" align="center" width="155">TỒN ĐẦU KỲ</th>
					<th colspan="3" align="center" width="155">NHẬP</th>
					<th colspan="3" align="center" width="155">XUẤT</th>
					<th colspan="3" align="center" width="155">TỒN CUỐI</th>
				</tr>
				<tr bgColor="#F2F2F2">	
					<th align="center" width="40">SL</th>		
					<th align="center" width="55">Đơn giá</th>
					<th align="center" width="60">Thành tiền</th>
					<th align="center" width="40">SL</th>		
					<th align="center" width="55">Giá nhập</th>
					<th align="center" width="60">Thành tiền</th>
					<th align="center" width="40">SL</th>		
					<th align="center" width="55">Giá xuất</th>
					<th align="center" width="60">Thành tiền</th>
					<th align="center" width="40">SL</th>		
					<th align="center" width="55">Giá tồn</th>
					<th align="center" width="60">Thành tiền</th>	
				</tr>';

//Load du lieu bang
$congtondau = 0;
$congnhap=0;
$congxuat=0;
$congtoncuoi=0;

switch($flag){
	case 'tonkho': 
			$listReport = $Pharma->VTYT_TonKhoChan($month, $year, $cond_typeput);
			break;
	case 'xuatnhapton':	
			$listReport = $Pharma->Khochan_baocaovtyt_nhapxuatton($month, $year, $cond_typeput);
			break;	
	default: break;		
}
	
if(is_object($listReport)){
	$i=1; $maxid=$listReport->RecordCount();
	$sTempDiv='';
	ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		$baocaotruoc = $Pharma->Khochan_vtyt_tontruoc($rowReport['product_encoder'], $cond_typeput);
		//if ($i==1)
			//echo $Pharma->GetLastQuery();
		if($baocaotruoc!=false){
			$tondau=$baocaotruoc['last_number'];
			$dongiatondau=$baocaotruoc['last_cost'];
		}else{
			$tondau=$rowReport['number'];
			$dongiatondau=$rowReport['price'];
		}
		if($rowReport['xuat']=='')
			$rowReport['xuat']=0;

		$toncuoi=$tondau+$rowReport['nhap']-$rowReport['xuat'];
		if($rowReport['gianhap']==0)
			$giatoncuoi = $rowReport['price'];
		else $giatoncuoi =$rowReport['gianhap'];
		
		echo '<tr bgColor="#ffffff" >';
		echo	'<td align="center">'.$i.'<input type="hidden" name="encoder'.$i.'" value="'.$rowReport['product_encoder'].'"></td>'; //STT
		echo	'<td>'.$rowReport['product_name'].'</td>';	//Ten thuoc
		echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
		echo 	'<td align="center">'.$rowReport['lotid'].'</td>';  //So lo
		echo 	'<td align="center">'.@formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy').'</td>';  //Han dung
		
		if (round($rowReport['price'],2)>round($rowReport['price']))
			$showprice = number_format($rowReport['price'],2);
		else $showprice = number_format($rowReport['price']);
				
		echo	'<td align="right">'.number_format($tondau).'</td>';	//Ton dau
		echo	'<td align="right">'.$showprice.'</td>';	//Don gia
		echo	'<td align="right">'.number_format($tondau*$dongiatondau).'</td>';	//TT
		echo	'<td align="right">'.number_format($rowReport['nhap']).'</td>';	//Nhap
		echo	'<td align="right">'.number_format($rowReport['gianhap']).'</td>';	//Gia nhap
		echo	'<td align="right">'.number_format($rowReport['nhap']*$rowReport['gianhap']).'</td>';	//TT nhap
		echo	'<td align="right">'.number_format($rowReport['xuat']).'</td>';	//Xuat
		echo	'<td align="right">'.number_format($rowReport['giaxuat']).'</td>';	//Gia xuat
		echo	'<td align="right">'.number_format($rowReport['xuat']*$rowReport['giaxuat']).'</td>';	//TT xuat
		echo	'<td align="right">'.number_format($toncuoi).'</td>';	//Ton cuoi
		echo	'<td align="right">'.number_format($giatoncuoi).'</td>';	//Gia ton cuoi
		echo	'<td align="right">'.number_format($toncuoi*$giatoncuoi).'</td>';	//TT
		echo '</tr>';
		$list_encoder[$i] = $rowReport['product_encoder'];
		$congtondau += $tondau*$dongiatondau;
		$congnhap += $rowReport['nhap']*$rowReport['gianhap'];
		$congxuat += $rowReport['xuat']*$rowReport['giaxuat'];
		$congtoncuoi += $toncuoi*$giatoncuoi;
		$i++;
	}		
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();
					
} else {
	$maxid=0;
	if (!isset($sTempDiv) || $sTempDiv=='')
		$sTempDiv='<tr bgColor="#ffffff"><td colspan="17">'.$flag.'</td></tr>';	
}

//Liet ke them nhung thuoc co ton truoc ma ko co nhap xuat
if(is_object($listReport) && $list_tonthangtruoc = $Pharma->VTYT_TonKhoChan($lastmonth, $lastyear, $cond_typeput.$dongtayy)){
	while($tempitem = $list_tonthangtruoc->FetchRow())	{
		$tempencoder = $tempitem['product_encoder'];
		$tondau = $tempitem['number']; $dongiatondau = $tempitem['price'];
		if(in_array($tempencoder, $list_encoder)==false){
			ob_start();
				echo '<tr bgColor="#ffffff" >';
				echo	'<td align="center">'.$i.'<input type="hidden" name="encoder'.$i.'" value="'.$tempitem['product_encoder'].'"></td>'; //STT
				echo	'<td>'.$tempitem['product_name'].'</td>';	//Ten thuoc
				echo 	'<td align="center">'.$tempitem['unit_name_of_medicine'].'</td>';  //Don vi
				echo 	'<td align="center">'.$tempitem['lotid'].'</td>';  //So lo
				echo 	'<td align="center">'.@formatDate2Local($tempitem['exp_date'],'dd/mm/yyyy').'</td>';  //Han dung
				
				if (round($tempitem['price'],2)>round($tempitem['price']))
					$showprice = number_format($tempitem['price'],2);
				else $showprice = number_format($tempitem['price']);
						
				echo	'<td align="right">'.number_format($tondau).'</td>';	//Ton dau
				echo	'<td align="right">'.$showprice.'</td>';	//Don gia
				echo	'<td align="right">'.number_format($tondau*$dongiatondau).'</td>';	//TT
				echo	'<td align="right">0</td>';	//Nhap
				echo	'<td align="right">0</td>';	//Gia nhap
				echo	'<td align="right">0</td>';	//TT nhap
				echo	'<td align="right">0</td>';	//Xuat
				echo	'<td align="right">0</td>';	//Gia xuat
				echo	'<td align="right">0</td>';	//TT xuat
				echo	'<td align="right">'.number_format($tondau).'</td>';	//Ton cuoi
				echo	'<td align="right">'.number_format($dongiatondau).'</td>';	//Gia ton cuoi
				echo	'<td align="right">'.number_format($tondau*$dongiatondau).'</td>';	//TT
				echo '</tr>';
			
			$sTempDiv = $sTempDiv.ob_get_contents();				
			ob_end_clean();
			$congtondau += $tondau*$dongiatondau;
			$congtoncuoi += $tondau*$dongiatondau;
			$i++;
		}
	}
}
	
$html = $html.$sTempDiv.'<tr>
						<td></td> <td colspan="4"><b>TỔNG CỘNG</b></td> 
						<td colspan="3" align="right"><b>'.number_format($congtondau).'</b></td>
						<td colspan="3" align="right"><b>'.number_format($congnhap).'</b></td>
						<td colspan="3" align="right"><b>'.number_format($congxuat).'</b></td>
						<td colspan="3" align="right"><b>'.number_format($congtoncuoi).'</b></td>
					</tr>
			</table>';
	
$pdf->writeHTML($html);
$pdf->Ln();

$pdf->SetFont('dejavusans', '', 10);
//Ky ten
$html2='<table width="100%">
		<tr>
			<td></td><td></td><td></td><td></td><td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i><br></td>
		</tr>
		<tr>
			<td align="center"><b>GIÁM ĐỐC</b></td>
			<td align="center" width="150"><b>P.TÀI CHÍNH-KẾ TOÁN</b></td>
			<td align="center"><b>TRƯỞNG KHOA DƯỢC</b></td>
			<td align="center"><b>THỦ KHO</b></td>
			<td align="center"><b>KẾ TOÁN KHO</b></td>	
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
			<td align="center">Họ tên.....................</td>
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
$pdf->Output('KhoChan_BaocaoXNTvtyt.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+