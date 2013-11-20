<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(15, 15, 15);    

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);
    $pdf->AddPage();
// set font
$pdf->SetFont('dejavusans', 'B', 10);

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('dejavusans', '', 10);

// -----------------------------------------------------------------------------


$tbl = '
<table cellspacing="0" cellpadding="0">
    <tr>
        <td align="left" width="40%" style="font-weight:bold;">BVĐK DẦU TIẾNG
		<br>KHOA: CẬN LÂM SÀNG</td>
        <td align="center" width="60%" style="font-weight:bold;">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
     		<br>Độc lập - Tự do - Hạnh phúc</td>
    </tr>
	<tr>
		<td align="left" style="font-style:italic;">
			Số:......./BC-CLS
		</td>
		<td align="right" style="font-style:italic;">
			Dầu Tiếng,ngày.......tháng.......năm........
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" style="font-weight:bold;">
			BÁO CÁO HOẠT ĐỘNG THÁNG
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" style="font-style:italic;">
			Tháng ...../......
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" style="font-weight:bold;">
			I. CÔNG TÁC CẬN LÂM SÀNG:
		</td>
	</tr>
	<br/>
	
	<tr>
		<td colspan="2">
		<ul>
			<table cellpadding="2" border="1" width="95%">
				<tr>
					<td  width="15%" style="font-weight:bold;font-style:italic;" align="center">
						STT
					</td>
					<td width="40%"  style="font-weight:bold;font-style:italic;" align="center">
						NỘI DUNG
					</td>
					<td width="15%" style="font-weight:bold;font-style:italic;"  align="center">
						Nội trú
					</td>
					<td width="15%" style="font-weight:bold;font-style:italic;" align="center">
						Ngoại trú
					</td>
					<td width="15%" style="font-weight:bold;font-style:italic;" align="center">
						Tổng số
					</td>
				</tr>
				<tr>
					<td  width="15%" style="font-weight:bold;" align="center">
						I.
					</td>
					<td width="40%" style="font-weight:bold;font-style:italic;" align="left">
						&nbsp;Xét nghiệm:
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						Hóa sinh
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						Huyết học
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						Vi sinh - Ký sinh
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						HBsAg
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						HIV
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						HBeAg
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						HCV
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						AFB
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="left" style="font-style:italic;">
						KST Sốt rét
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" style="font-weight:bold;" align="center">
						II.
					</td>
					<td width="40%" style="font-weight:bold;font-style:italic;" align="left">
						Chẩn đoán hình ảnh:
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						Chụp X.quang
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						Siêu âm
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" style="font-weight:bold;" align="center">
						III.
					</td>
					<td width="40%" style="font-weight:bold;font-style:italic;" align="left">
						Thăm dò chức năng:
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
				<tr>
					<td  width="15%" align="center">
						
					</td>
					<td width="40%" align="right" style="font-style:italic;">
						Điện tim
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
					<td width="15%" align="center">
						
					</td>
				</tr>
			</table>
			</ul>
		</td>
		
	</tr>
	<br/>
	<tr>
		<td colspan="2" align="left" style="font-weight:bold;">
			II. PHƯƠNG HƯỚNG THÁNG ...../......
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" >
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" >
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr><tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<br/>
	<tr>
		<td colspan="2" align="left" style="font-weight:bold;">
			II. ĐỀ XUẤT
		</td>
	</tr>
	
	<tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr><tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr><tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr><tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr><tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr><tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr><tr>
		<td colspan="2" align="left">
			<ul>'.str_pad(".....", 140, ".", STR_PAD_RIGHT).'
			</ul>
		</td>
	</tr>
	<br/>
	<tr>
		<td  align="left" style="font-weight:bold;">
			<ul>
				Trưởng khoa
			</ul>
		</td>
		<td  align="right" style="font-weight:bold;">
			Người báo cáo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
</table>
	';

$pdf->writeHTML($tbl, true, false, false, false, '');




$pdf->Output('demo.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+