<?php
//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2010-08-08
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(8, 8, 7);    

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
        <td align="center" width="40%">Sở y tế Bình Dương
		<br>BVĐK HUYỆN...........<br>------------<br>SỐ:......../BC-BV-HĐTĐKT</td>
        <td align="center">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
		<br>Độc lập-Tự do-Hạnh phúc
		<br>--------------------
		<br><br><br>......,ngày.....tháng ......năm......
		</td>
		<br>
		
       
    </tr>

</table><br/>';

$pdf->writeHTML($tbl, true, false, false, false, '');


$pdf->SetFont('dejavusans', 'B', 15);
$pdf->Write(0,'DANH SÁCH CBVC XÉT THI ĐUA', '', 0, 'C', true, 0, false, false, 0);


$pdf->Write(0,'THÁNG 4/2012', '', 0, 'C', true, 0, false, false, 0);
$pdf->Ln(15);
$pdf->SetFont('dejavusans', '', 10);

$tbl = '
<table border="1" width="100%" >
    <tr>
        <td width="7%">Stt</td>
		<td align="center"width="25%">Họ và tên</td>
		<td align="center"width="20%">Chức danh<br>Chức vụ</td>
		<td align="center"width="15%">Bộ phận</td>
		<td align="center"width="10%">Xếp loại</td>
		<td align="center"width="20%">Ghi chú<br>(Tổng ngày công làm việc)</td>
    </tr>
	<tr width="15%">
	<td width="7%"align="center">1</td>
	<td align="left"width="25%"><font size="10">Bùi Công Chiến</font></td>	
	<td>BS-GĐ</td>
	<td>BGĐ</td>
	<td>A</td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">2</td>
	<td align="left"width="25%"><font size="10">Trần Ngọc Thanh</font></td>	
	<td>BS-P.GĐ</td>
	<td>BGĐ</td>
	<td>A</td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">3</td>
	<td align="left"width="25%"><font size="10">Lâm Thị Hoài Thương</font></td>	
	<td>NHS-PP</td>
	<td>P.KH-TB-ĐD</td>
	<td>A</td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">4</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">5</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">6</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">7</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">8</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">9</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">10</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">11</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">12</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">13</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">10</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">15</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">16</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">17</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">18</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">19</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">20</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">21</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">22</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">23</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">24</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">25</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">27</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">27</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">28</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">29</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">30</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">31</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">32</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">33</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">34</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">35</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">36</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr width="10%">
	<td width="7%"align="center">37</td>
	<td align="left"width="25%"><font size="10"></font></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	
	
	
</table>';
$pdf->writeHTML($tbl, true, false, false, false, '');



$pdf->Output('demo.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+