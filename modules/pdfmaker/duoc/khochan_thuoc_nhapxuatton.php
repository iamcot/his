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
		case 1: $cond_typeput = ' AND source.typeput=1 '; $titlereport=' KINH PHÍ '; break;		//su nghiep
		case 2: $cond_typeput = ' AND source.typeput=0 '; $titlereport=' BHYT '; break;		//bhyt
		case 3: $cond_typeput = ' AND source.typeput=2 '; $titlereport=' CBTC '; break;		//cbtc
		default: $cond_typeput = ' '; $titlereport='';
	}
	
	switch($typedongtay){
		case 'tayy': $dongtayy ='tayy';
					$titlereport=' TÂY Y'.$titlereport; break;	
		case 'dongy': $dongtayy = 'dongy';
					$titlereport=' ĐÔNG Y'.$titlereport; break;
		default: $dongtayy = ''; break;
	}

	
//$listItem = $Product->ShowKhoChanThuoc_Ton($dongtayy_cond, '', '', $cond_typeput, $todate);


require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Báo cáo nhập xuất tồn thuốc kho chẵn');
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
				<font size="13"><b>THUỐC'.$titlereport.'</b></font></td><td></td>
		</tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();


//Load tieu de bang
$html='	<table cellpadding="2" border="1">
				<tr bgColor="#F2F2F2">
					<th rowspan="2" align="center" width="20">STT</th>
					<th rowspan="2" align="center" width="95">Tên thuốc - Hàm lượng</th>
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

$listReport = $Pharma->Khochan_thuoc_nhapxuatton($dongtayy, $cond_typeput, $month, $year);
/*switch($flag){
	case 'tonkho': 
			$listReport = $Pharma->Thuoc_TonKhoChan($month, $year, $cond_typeput.$dongtayy);
			break;
	case 'xuatnhapton':	
			
			//Khochan_baocaothuoc_nhapxuatton($month, $year, $cond_typeput.$dongtayy);
			break;	
	default: break;		
}*/
$Tong_tondau =0; $Tong_nhap=0; $Tong_xuat=0; $Tong_toncuoi=0;
	
if(is_object($listReport)){
	//$maxid=$listReport->RecordCount();
	$sTempDiv=''; $stt=1;
	//ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		if($oldencode!=$rowReport['product_encoder']){ 			//thuoc moi
			//echo current $list_encoder
			foreach ($list_encoder as $value) {
				$sTempDiv .=  '<tr bgColor="#ffffff" >';
				$sTempDiv .= 	'<td align="center">'. $stt.'<input type="hidden" name="encoder'.$stt.'" value="'.$value['encoder'].'"></td>'; //STT
				$sTempDiv .= 	'<td>'.$value['name'].'</td>';	//Ten thuoc
				$sTempDiv .=  	'<td align="center">'.$value['unit'].'</td>';  //Don vi
				
				//So lo
				if($value['lonhap']!='') $lotid = $value['lonhap'];
				else $lotid	= $value['loton'];
				$sTempDiv .=  	'<td align="center">'.$lotid.'</td>';  
				
				//Han dung
				if($value['hannhap']!='') $expdate = $value['hannhap']; 
				else $expdate	= $value['hanton'];				
				$sTempDiv .=  	'<td align="center">'.@formatDate2Local($expdate,'dd/mm/yyyy').'</td>';  
				
				//Ton dau
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']).'</td>';		
				if (round($value['giaton'],3)>round($value['giaton'])) $showton = number_format($value['giaton'],3);
				else $showton = number_format($value['giaton']);
				$sTempDiv .= 	'<td align="right">'.$showton.'</td>';	
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']*$value['giaton']).'</td>';	//TT
				
				//Nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']).'</td>';	
				if (round($value['gianhap'],3)>round($value['gianhap'])) $shownhap = number_format($value['gianhap'],3);
				else $shownhap = number_format($value['gianhap']);
				$sTempDiv .= 	'<td align="right">'.$shownhap.'</td>';	//Gia nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']*$value['gianhap']).'</td>';	//TT nhap
				
				//Xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']).'</td>';	
				if (round($value['giaxuat'],3)>round($value['giaxuat'])) $showxuat = number_format($value['giaxuat'],3);
				else $showxuat = number_format($value['giaxuat']);				
				$sTempDiv .= 	'<td align="right">'.$showxuat.'</td>';	//Gia xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']*$value['giaxuat']).'</td>';	//TT xuat
				
				$toncuoi = $value['ton'] + $value['nhap'] - $value['xuat'];
				if($value['giaton']>0 || $value['gianhap']>0)
					$giatoncuoi = max($value['giaton'],$value['gianhap']);
				else $giatoncuoi = $value['giaxuat'];	
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi).'</td>';	//Ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($giatoncuoi).'</td>';	//Gia ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi*$giatoncuoi).'</td>';	//TT
				$sTempDiv .= 	'<td></td>';	//Note
				$sTempDiv .=  '</tr>';
				$Tong_tondau += $value['ton']*$value['giaton'];
				$Tong_nhap += $value['nhap']*$value['gianhap'];
				$Tong_xuat += $value['xuat']*$value['giaxuat'];
				$Tong_toncuoi += $toncuoi*$giatoncuoi;
				//$value['ton']*$value['giaton'] + $value['nhap']*$value['gianhap'] - $value['xuat']*$value['giaxuat'];
				$stt++;
				//$sTempDiv .=  $stt;
			}
			//reset new encoder
			unset($list_encoder); $i=1;	
			$list_encoder[$i]['encoder'] = $rowReport['product_encoder'];
			$list_encoder[$i]['name'] = $rowReport['product_name'];
			$list_encoder[$i]['unit'] = $rowReport['unit_name_of_medicine'];
			$list_encoder[$i]['loton'] = $rowReport['loton'];
			$list_encoder[$i]['lonhap'] = $rowReport['lonhap'];	
			$list_encoder[$i]['hanton'] = $rowReport['hanton'];
			$list_encoder[$i]['hannhap'] = $rowReport['hannhap'];			
			$list_encoder[$i]['ton'] = $rowReport['ton'];
			$list_encoder[$i]['giaton'] = $rowReport['giaton'];
			$list_encoder[$i]['nhap'] = $rowReport['nhap'];
			$list_encoder[$i]['gianhap'] = $rowReport['gianhap'];
			$list_encoder[$i]['xuat'] = $rowReport['xuat'];
			$list_encoder[$i]['giaxuat'] = $rowReport['giaxuat'];
			
			$oldencode=$rowReport['product_encoder'];
			
		}else{		//thuoc cu
			if(($rowReport['ton']>0 && $list_encoder[$i]['ton']>0) || ($rowReport['nhap']>0 && $list_encoder[$i]['ton']>0) || ($rowReport['nhap']>0 && $list_encoder[$i]['nhap']>0) || (abs($rowReport['gianhap']-$list_encoder[$i]['giaxuat'])>1) || (abs($rowReport['xuat']- $list_encoder[$i]['xuat'])>1) || (abs($rowReport['giaxuat']-$list_encoder[$i]['gianhap'])>1)){
				$i++;	//them dong moi
				$list_encoder[$i]['encoder'] = $rowReport['product_encoder'];
				$list_encoder[$i]['name'] = $rowReport['product_name'];
				$list_encoder[$i]['unit'] = $rowReport['unit_name_of_medicine'];
				$list_encoder[$i]['loton'] = $rowReport['loton'];
				$list_encoder[$i]['lonhap'] = $rowReport['lonhap'];	
				$list_encoder[$i]['hanton'] = $rowReport['hanton'];
				$list_encoder[$i]['hannhap'] = $rowReport['hannhap'];
				$list_encoder[$i]['ton'] = $rowReport['ton'];
				$list_encoder[$i]['giaton'] = $rowReport['giaton'];
				$list_encoder[$i]['nhap'] = $rowReport['nhap'];
				$list_encoder[$i]['gianhap'] = $rowReport['gianhap'];
				$list_encoder[$i]['xuat'] = $rowReport['xuat'];	
				$list_encoder[$i]['giaxuat'] = $rowReport['giaxuat'];	
			} else {	//cong don vao dong cu
				if($rowReport['nhap']>0){
					for ($j=1;$j<=$i;$j++){
						if ($list_encoder[$j]['nhap']<=0 && $list_encoder[$j]['ton']<=0){
							$list_encoder[$j]['nhap'] = $rowReport['nhap'];
							$list_encoder[$j]['gianhap'] = $rowReport['gianhap'];
							break;
						}
					}
				}
				if($rowReport['xuat']>0){
					for ($j=1;$j<=$i;$j++){
						if ($list_encoder[$j]['xuat']<=0){
							$list_encoder[$j]['xuat'] += $rowReport['xuat'];
							$list_encoder[$j]['giaxuat'] = $rowReport['giaxuat'];
							break;
						}
					}
				}				
			}
		}

	}	
			//$sTempDiv .=  last $list_encoder
			foreach ($list_encoder as $value) {
				$sTempDiv .=  '<tr bgColor="#ffffff" >';
				$sTempDiv .= 	'<td align="center">'. $stt.'<input type="hidden" name="encoder'.$stt.'" value="'.$value['encoder'].'"></td>'; //STT
				$sTempDiv .= 	'<td>'.$value['name'].'</td>';	//Ten thuoc
				$sTempDiv .=  	'<td align="center">'.$value['unit'].'</td>';  //Don vi
				
				//So lo
				if($value['lonhap']!='') $lotid = $value['lonhap'];
				else $lotid	= $value['loton'];
				$sTempDiv .=  	'<td align="center">'.$lotid.'</td>';  
				
				//Han dung
				if($value['hannhap']!='') $expdate = $value['hannhap']; 
				else $expdate	= $value['hanton'];				
				$sTempDiv .=  	'<td align="center">'.@formatDate2Local($expdate,'dd/mm/yyyy').'</td>';  
				
				//Ton dau
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']).'</td>';		
				if (round($value['giaton'],3)>round($value['giaton'])) $showton = number_format($value['giaton'],3);
				else $showton = number_format($value['giaton']);
				$sTempDiv .= 	'<td align="right">'.$showton.'</td>';	
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']*$value['giaton']).'</td>';	//TT
				
				//Nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']).'</td>';	
				if (round($value['gianhap'],3)>round($value['gianhap'])) $shownhap = number_format($value['gianhap'],3);
				else $shownhap = number_format($value['gianhap']);
				$sTempDiv .= 	'<td align="right">'.$shownhap.'</td>';	//Gia nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']*$value['gianhap']).'</td>';	//TT nhap
				
				//Xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']).'</td>';	
				if (round($value['giaxuat'],3)>round($value['giaxuat'])) $showxuat = number_format($value['giaxuat'],3);
				else $showxuat = number_format($value['giaxuat']);				
				$sTempDiv .= 	'<td align="right">'.$showxuat.'</td>';	//Gia xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']*$value['giaxuat']).'</td>';	//TT xuat
				
				$toncuoi = $value['ton'] + $value['nhap'] - $value['xuat'];
				$giatoncuoi = max($value['giaton'],$value['gianhap'],$value['giaxuat']);
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi).'</td>';	//Ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($giatoncuoi).'</td>';	//Gia ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi*$giatoncuoi).'</td>';	//TT
				$sTempDiv .= 	'<td></td>';	//Note
				$sTempDiv .=  '</tr>';
				$Tong_tondau += $value['ton']*$value['giaton'];
				$Tong_nhap += $value['nhap']*$value['gianhap'];
				$Tong_xuat += $value['xuat']*$value['giaxuat'];
				$Tong_toncuoi += $toncuoi*$giatoncuoi;
				$stt++;
			}	
	//$sTempDiv = $sTempDiv.ob_get_contents();				
	//ob_end_clean();
					
} else {
	$stt=0;
	if (!isset($sTempDiv) || $sTempDiv=='')
		$sTempDiv='<tr bgColor="#ffffff"><td colspan="18">'.$LDNotReportThisMonth.'</td></tr>';	
}

$sTempDiv = $sTempDiv.'<tr bgColor="#ffffff">
							<td colspan="5" align="center"><b>'.$LDTotalNumber.'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_tondau).'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_nhap).'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_xuat).'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_toncuoi).'</b></td>
							<td></td>
						</tr>';

$html = $html.$sTempDiv.'</table>';

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
//
//// -----------------------------------------------------------------------------
ob_clean();
//Close and output PDF document
$pdf->Output('KhoChan_BaocaoXNTthuoc.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+