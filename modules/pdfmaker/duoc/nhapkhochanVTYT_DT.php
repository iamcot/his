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

$report_show = $Pharma->getPutInMedInfo($report_id);
$medicine_in_pres = $Pharma->getDetailPutInMedInfo($report_id);
if($report_show!=false){
	$sql1= "select * from care_supplier where supplier='".$report_show['supplier']."' ";
	if($re_sup=$db->Execute($sql1)){
		if($count1=$re_sup->RecordCount()){
			$supplier=$re_sup->FetchRow();
			$suppliername=$supplier['supplier_name'];
		}
	}	
	
	if ($report_show['typeput']==0)
		$bhyt=' (Thuốc BHYT) ';
	else $bhyt ='';
}

function convertMoney($number){
	
	$donvi=" đồng ";
	$tiente=array("nganty" => " nghìn tỷ ","ty" => " tỷ ","trieu" => " triệu ","ngan" =>" nghìn ","tram" => " trăm ");
	$num_f=$nombre_format_francais = number_format($number, 2, ',', ' ');
	$vitri=strpos($num_f,',');
	$num_cut=substr($num_f,0,$vitri);
	$mang=explode(" ",$num_cut);
	$sophantu=count($mang);
	switch($sophantu)
	{
		case '5':
				$nganty=doc3so($mang[0]);
				$text=$nganty;
				$ty=doc3so($mang[1]);
				$trieu=doc3so($mang[2]);
				$ngan=doc3so($mang[3]);
				$tram=doc3so($mang[4]);
				if((int)$mang[1]!=0)
				{
					$text.=$tiente['ngan'];
					$text.=$ty.$tiente['ty'];
				}
				else
				{
					$text.=$tiente['nganty'];
				}
				if((int)$mang[2]!=0)
					$text.=$trieu.$tiente['trieu'];
				if((int)$mang[3]!=0)
					$text.=$ngan.$tiente['ngan'];
				if((int)$mang[4]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '4':
				$ty=doc3so($mang[0]);
				$text=$ty.$tiente['ty'];
				$trieu=doc3so($mang[1]);
				$ngan=doc3so($mang[2]);
				$tram=doc3so($mang[3]);
				if((int)$mang[1]!=0)
					$text.=$trieu.$tiente['trieu'];
				if((int)$mang[2]!=0)
					$text.=$ngan.$tiente['ngan'];
				if((int)$mang[3]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '3':
				$trieu=doc3so($mang[0]);
				$text=$trieu.$tiente['trieu'];
				$ngan=doc3so($mang[1]);
				$tram=doc3so($mang[2]);
				if((int)$mang[1]!=0)
					$text.=$ngan.$tiente['ngan'];
				if((int)$mang[2]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '2':
				$ngan=doc3so($mang[0]);
				$text=$ngan.$tiente['ngan'];
				$tram=doc3so($mang[1]);
				if((int)$mang[1]!=0)
					$text.=$tram;
				$text.=$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		case '1':
				$tram=doc3so($mang[0]);
				$text=$tram.$donvi;
				$text[1] = strtoupper($text[1]);
				return $text;
				break;
		default:
			echo "Xin lỗi số quá lớn không thể đổi được";
		break;
	}
}	
function doc3so($so)
{
		$achu = array ( " không "," một "," hai "," ba "," bốn "," năm "," sáu "," bảy "," tám "," chín " );
		$aso = array ( "0","1","2","3","4","5","6","7","8","9" );
		$kq = "";
		$tram = floor($so/100); // Hàng trăm
		$chuc = floor(($so/10)%10); // Hàng chục
		$donvi = floor(($so%10)); // Hàng đơn vị
		if($tram==0 && $chuc==0 && $donvi==0) $kq = "";
		if($tram!=0)
		{
			$kq .= $achu[$tram] . " trăm ";
			if (($chuc == 0) && ($donvi != 0)) $kq .= " lẻ ";
		}
		if (($chuc != 0) && ($chuc != 1))
		{
				$kq .= $achu[$chuc] . " mươi";
				if (($chuc == 0) && ($donvi != 0)) $kq .= " linh ";
		}
		if ($chuc == 1) $kq .= " mười ";
		switch ($donvi)
		{
			case 1:
				if (($chuc != 0) && ($chuc != 1))
				{
					$kq .= " mốt ";
				}
				else
				{
					$kq .= $achu[$donvi];
				}
				break;
			case 5:
				if ($chuc == 0)
				{
					$kq .= $achu[$donvi];
				}
				else
				{
					$kq .= " lăm ";
				}
				break;
			default:
				if ($donvi != 0)
				{
					   $kq .= $achu[$donvi];
				}
				break;
		}
		if($kq=="")
		$kq=0;   
		return $kq;
}
function doc_so($so)
{
		$so = preg_replace("([a-zA-Z{!@#$%^&*()_+<>?,.}]*)","",$so);
		if (strlen($so) <= 21)
		{
			$kq = "";
			$c = 0;
			$d = 0;
			$tien = array ( "", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ", " tỷ tỷ" );
			for ($i = 0; $i < strlen($so); $i++)
			{
				if ($so[$i] == "0")
					$d++;
				else break;
			}
			$so = substr($so,$d);
			for ($i = strlen($so); $i > 0; $i-=3)
			{
				$a[$c] = substr($so, $i, 3);
				$so = substr($so, 0, $i);
				$c++;
			}
			$a[$c] = $so;
			for ($i = count($a); $i > 0; $i--)
			{
				if (strlen(trim($a[$i])) != 0)
				{
					if (doc3so($a[$i]) != "")
					{
						if (($tien[$i-1]==""))
						{
							if (count($a) > 2)
								$kq .= " không trăm lẻ ".doc3so($a[$i]).$tien[$i-1];
							else $kq .= doc3so($a[$i]).$tien[$i-1];
						}
						else if ((trim(doc3so($a[$i])) == "mười") && ($tien[$i-1]==""))
						{
							if (count($a) > 2)
								$kq .= " không trăm ".doc3so($a[$i]).$tien[$i-1];
							else $kq .= doc3so($a[$i]).$tien[$i-1];
						}
						else
						{
						$kq .= doc3so($a[$i]).$tien[$i-1];
						}
					}
				}
			}
			return $kq;
		}
		else
		{
			return "Số quá lớn!";
		}
}  

require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Phiếu Nhập Kho');
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
				Đơn vị: '.$cell.'<br>
				Mã số:<br>
				Địa chỉ: '.$cell_address.'
			</td>
			<td align="center">
					Mẫu số: C21 HD<br>
					(Ban hành theo QĐ số 19/2006 QĐ-BTC <br> Ngày 30/3/2006 của Bộ trưởng Bộ Tài Chính)
			</td>			
		</tr></table>';
$pdf->writeHTML($header);
$pdf->Ln();
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Write(0, 'PHIẾU NHẬP KHO', '', 0, 'C', true, 0, false, false, 0);
$pdf->SetFont('dejavusans', '', 10);


//Get data of report
if (!$report_show || !$medicine_in_pres){
	$html='Không tìm được dữ liệu';
}else{
	$date1 = formatDate2Local($report_show['date_time'],'dd/mm/yyyy');
	if($date1!=''){
		$date1 = explode('/',$date1);
		$pdf->writeHTMLCell(0, 0, '', '', "Ngày ".$date1[0]." tháng ".$date1[1]." năm ".$date1[2], 0, 1, 0, true, 'C', true);
	} 
	else $pdf->writeHTMLCell(0, 0, '', '', "Ngày ".date('d')." tháng ".date('m')." năm ".date('Y'), 0, 1, 0, true, 'C', true);
	$pdf->Ln();
	$pdf->Write(0, "Họ và tên người giao hàng: ".$report_show['delivery_person'], '', 0, 'L', 1, 0, false, false, 0);
	$pdf->Write(0, "Theo hóa đơn số: ".$report_show['voucher_id']."    ngày ".$date1[0]." tháng ".$date1[1]." năm ".$date1[2]."    của ".$suppliername, '', 0, 'L', 1, 0, false, false, 0);
	$pdf->Write(0, 'Nhập tại: kho Dược '.$cell.' '.$bhyt, '', 0, 'L', 1, 0, false, false, 0);
	$pdf->Ln();
	//Load danh sach thuoc
	$html='	<table cellpadding="2" border="1"  >
					<tr align="center" >
						<td rowspan="2" width="30" ><b>'.$LDSTT.'</b></td>					
						<td rowspan="2" width="140" ><b>Tên nhãn hiệu, quy cách sản phẩm vật tư</b></td>
						<td rowspan="2"><b>Mã số</b></td>
						<td rowspan="2" width="50"><b>'.$LDUnit.'</b></td>
						<td colspan="2"><b>'.$LDNumberOf.'</b></td>
						<td rowspan="2"><b>'.$LDCost.'</b></td>
						<td rowspan="2"><b>'.$LDTotalCost.'</b></td>
					</tr>
					<tr align="center">
						<td><b>'.$LDRequest1.'</b></td>
						<td><b>'.$LDThucNhap.'</b></td>
					</tr>';
					
	
	$medicine_count = $medicine_in_pres->RecordCount();
	$total=0;
	for($i=1;$i<=$medicine_count;$i++) { 			
		$rowIssue = $medicine_in_pres->FetchRow();								

		$html= $html.'<tr>
						<td align="center">'.$i.'.</td>
						<td>'.$rowIssue['product_name'].'</td>
						<td>'.$rowIssue['product_encoder'].'</td>
						<td align="center">'.$rowIssue['unit_name_of_medicine'].'</td>
						<td align="center">'.number_format($rowIssue['number']).'</td>
						<td align="right">'.number_format($rowIssue['number_voucher']).'</td>
						<td align="right">'.number_format($rowIssue['price']).'</td>
						<td align="right">'.number_format($rowIssue['number_voucher']*$rowIssue['price']).'</td>
					</tr>';					
		$total += ($rowIssue['number_voucher']*$rowIssue['price']);
	}
	
	$totallast = ($total*(1 + $report_show['vat']/100));
	$html = $html.'<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td align="right"> - </td></tr>
					<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td align="right"> - </td></tr>
					<tr>
						<td></td><td></td><td></td><td></td><td></td><td></td><td></td>
						<td align="right">'.number_format($total).'</td>
					</tr>
					<tr>
						<td></td><td></td><td></td><td></td><td colspan="2" align="center">'.$report_show['vat'].'% Thuế GTGT</td><td></td>
						<td align="right">'.number_format($total*$report_show['vat']/100).'</td>
					</tr>
					<tr>
						<td></td><td> <b>Tổng cộng</b> </td><td></td><td></td><td></td><td></td><td></td>
						<td align="right"><b>'.number_format($totallast).'</b></td>
					</tr>
				</table>';
				
	$pdf->writeHTML($html, true, 0, true, 0);
	
	$txtmoney_totallast = convertMoney($totallast);
	$pdf->writeHTMLCell(0, 0, '', '', '<b>Tổng số tiền:</b> '.$txtmoney_totallast.' ', 0, 1, 0, true, 'L', true);
}
$pdf->Ln();
$html2='<table width="100%">
		<tr>
			<td align="center"><b>Giám đốc</b></td>
			<td align="center"><b>Người giao hàng</b></td>
			<td align="center"><b>Thủ kho</b></td>
		</tr>
		</table>';
$pdf->writeHTMLCell(0, 25, '', '', $html2, 0, 1, 0, true, 'L', true);
//$pdf->Write(0, 'text', '', 0, 'C', true, 0, false, false, 0);
$html2='<table width="100%">
		<tr>
			<td align="center"><b>Kế toán trưởng</b></td>
			<td align="center"><b>Trưởng khoa</b></td>
			<td align="center"><b>Người lập</b></td>
		</tr>
		</table>';
$pdf->writeHTMLCell(0, 25, '', '', $html2, 0, 1, 0, true, 'L', true);
// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('PhieuNhapKho.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+