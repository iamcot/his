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

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(25, 8, 7);    

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
        <td align="left" width="50%"><font size="15px">BVĐK DẦU TIẾNG</font></td>
        <td align="center" width="55%"><font size="13%">BẢNG THEO DÕI NGHỈ PHÉP NĂM 2012</font></td>
		
	 </tr>
	 <br>
	 <br>
<table border="1" cellpadding="0">
	 <tr>
		<td width="5%" rowspan="2">STT</td>
		<td width="25%" rowspan="2"><font size="11%">HỌ TÊN,BP CÔNG TÁC</font></td>
		<td width="300px" align="center" colspan="15">Tháng/Năm</td>
		<td width="15%" rowspan="2"><font size="10">Tổng ngày</font></td>
	 </tr>
   <tr>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
   </tr>
   <tr>
    <td width="5%" align="center">I</td>
	<td width="25%"><font size="12%">BAN GIÁM ĐỐC </font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%" ></td>
   </tr>
   <tr>
	<td width="5%" align="center">1</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">2</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">II</td>
	<td width="25%"><font size="12%">Phòng-KH-ĐD-TB </font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">3</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">4</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">5</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">6</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">7</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">III</td>
	<td width="25%"><font size="12%">Phòng TCHC </font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">8</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">9</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">10</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">11</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">12</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">13</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">14</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">15</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">16</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">IV</td>
	<td width="25%"><font size="12%">Phòng TCKT </font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">17</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">18</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">19</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">20</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
   <tr>
	<td width="5%" align="center">21</td>
	<td width="25%"><font size="12%"></font></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td></td><td></td><td></td><td></td><td></td>
	<td width="15%"></td>
   </tr>
</table>
</table>';

$pdf->writeHTML($tbl, true, false, false, false, '');




$pdf->Output('demo.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+