<?php
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
define('LANG_FILE','pharma.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

require_once($root_path.'include/care_api_classes/class_pharma.php');
$Pharma = new Pharma;



//type_month = 0: bao cao tung thang; 1: bao cao qui; 2: bao cao 6 thang; 3: 1 thang tung khoa; 4: qui tung khoa
//showmonth & showyear: thang bao cao

$nhomdacbiet='gaynghien';

// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Báo cáo sử dụng thuốc gây nghiện');
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
				Bộ Y Tế (Sở Y Tế):..............<br>
				BV: '.$cell.'
			</td>
			<td valign="top" align="center" width="400">
				<font size="15"><b>BÁO CÁO SỬ DỤNG THUỐC GÂY NGHIỆN</b></font>
			</td>
			<td valign="top" align="right" width="200">
				MS:......../BV-01<br>
				Số:...................
			</td>
		</tr>
		</table>';
$pdf->writeHTML($header);
$pdf->Ln();

//Load tieu de bang

switch($type_month){
    case 0:  	//1 thang khoa duoc
        $smalltitle= '<table width="100%"><tr><td align="center">Tháng: '.$showmonth.'/'.$showyear.'</td></tr></table><br>';
        $titletable= '<table border="1" cellpadding="3" cellspacing="1" width="98%">
        <tr bgColor="#E1E1E1">
            <td align="center" width="4%"><b>STT</b></td>
            <td align="center" width="24%"><b>Tên thuốc</b></td>
            <td align="center" width="7%"><b>Đơn vị</b></td>
            <td align="center" width="10%"><b>Tồn kho cuối tháng</b></td>
            <td align="center" width="10%"><b>Số lượng mua trong tháng</b></td>
            <td align="center" width="10%"><b>Tổng số</b></td>
            <td align="center" width="10%"><b>Số lượng xuất trong tháng</b></td>
            <td align="center" width="10%"><b>Số lượng hư hỏng</b></td>
            <td align="center" width="10%"><b>Tồn kho cuối tháng</b></td>
            <td align="center" width="7%"><b>Ghi chú</b></td>
        </tr>';


        break;

    case 1:  	//qui khoa duoc
        $smalltitle= '<table width="100%"><tr><td align="center">Quí: '.(($showmonth-1)/3 +1).'/'.$showyear.'</td></tr></table><br>';
        $titletable= '<table border="1" cellpadding="3" cellspacing="1" width="98%">
        <tr bgColor="#E1E1E1">
            <td align="center"><b>STT</b></td>
            <td align="center"><b>Tên thuốc</b></td>
            <td align="center"><b>Đơn vị</b></td>
            <td align="center"><b>Đơn giá</b></td>
            <td align="center" colspan="2"><b>Tồn kho cuối kỳ</b></td>
            <td align="center" colspan="2"><b>Nhập trong kỳ</b></td>
            <td align="center" colspan="2"><b>Xuất trong kỳ</b></td>
            <td align="center" colspan="2"><b>Số lượng hư hao, đổi dư</b></td>
            <td align="center" colspan="2"><b>Tồn kho cuối kỳ</b></td>
            <td align="center"><b>Ghi chú</b></td>
        </tr>
        <tr bgColor="#E1E1E1">
            <td></td><td></td><td></td><td></td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center"></td>
        </tr>';

        break;


    case 2:     //6 thang khoa duoc
        $smalltitle= '<table width="100%"><tr><td align="center">Tháng: '.$showmonth.'/'.$showyear.' đến tháng '.($showmonth+5).'/'.$showyear.'</td></tr></table><br>';
        $titletable= '<table border="1" cellpadding="3" cellspacing="1" width="98%">
        <tr bgColor="#E1E1E1">
            <td align="center"><b>STT</b></td>
            <td align="center"><b>Tên thuốc</b></td>
            <td align="center"><b>Đơn vị</b></td>
            <td align="center"><b>Đơn giá</b></td>
            <td align="center" colspan="2"><b>Tồn kho cuối kỳ</b></td>
            <td align="center" colspan="2"><b>Nhập trong kỳ</b></td>
            <td align="center" colspan="2"><b>Xuất trong kỳ</b></td>
            <td align="center" colspan="2"><b>Số lượng hư hao, đổi dư</b></td>
            <td align="center" colspan="2"><b>Tồn kho cuối kỳ</b></td>
            <td align="center"><b>Ghi chú</b></td>
        </tr>
        <tr bgColor="#E1E1E1">
            <td></td><td></td><td></td><td></td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center">SL</td>
            <td align="center">TT</td>
            <td align="center"></td>
        </tr>';


        break;


    case 3:  	//1 thang tung khoa
        $smalltitle= '<table width="100%"><tr><td align="center">Tháng: '.$showmonth.'/'.$showyear.'</td></tr></table><br>';
        $titletable= '<table border="1" cellpadding="3" cellspacing="1" width="98%">
        <tr bgColor="#E1E1E1">
            <td align="center" rowspan="2"><b>STT</b></td>
            <td align="center" rowspan="2"><b>Tên thuốc</b></td>
            <td align="center" rowspan="2"><b>Đơn vị</b></td>
            <td align="center"><b>Khoa Ngoại</b></td>
            <td align="center"><b>Khoa Sản</b></td>
            <td align="center"><b>Khoa HSCC</b></td>
            <td align="center"><b>Khoa Nội</b></td>
            <td align="center"><b>Khoa Dược</b></td>
            <td align="center" rowspan="2"><b>Tổng số</b></td>
            <td align="center" rowspan="2"><b>Ghi chú</b></td>
        </tr>
        <tr bgColor="#E1E1E1">
            <td align="center">'.$showmonth.'/'.$showyear.'</td>
            <td align="center">'.$showmonth.'/'.$showyear.'</td>
            <td align="center">'.$showmonth.'/'.$showyear.'</td>
            <td align="center">'.$showmonth.'/'.$showyear.'</td>
            <td align="center">'.$showmonth.'/'.$showyear.'</td>
        </tr>';

        break;

    case 4: 	//qui tung khoa
        $smalltitle= '<table width="100%"><tr><td align="center">Quí: '.(($showmonth-1)/3 +1).'/'.$showyear.'</td></tr></table><br>';
        $titletable= '<table border="1" cellpadding="3" cellspacing="1" width="98%">
        <tr bgColor="#E1E1E1">
            <td align="center" rowspan="2"><b>STT</b></td>
            <td align="center" rowspan="2"><b>Tên thuốc</b></td>
            <td align="center" rowspan="2"><b>Đơn vị</b></td>
            <td align="center" colspan="3"><b>Khoa Ngoại</b></td>
            <td align="center" colspan="3"><b>Khoa Sản</b></td>
            <td align="center" colspan="3"><b>Khoa HSCC</b></td>
            <td align="center" colspan="3"><b>Khoa Nội</b></td>
            <td align="center" colspan="3"><b>Khoa Dược</b></td>
            <td align="center" rowspan="2"><b>Tổng số</b></td>
            <td align="center" rowspan="2"><b>Ghi chú</b></td>
        </tr>
        <tr bgColor="#E1E1E1">
            <td>'.$showmonth.'/'.$showyear.'</td>
            <td>'.($showmonth+1).'/'.$showyear.'</td>
            <td>'.($showmonth+2).'/'.$showyear.'</td>
            <td>'.$showmonth.'/'.$showyear.'</td>
            <td>'.($showmonth+1).'/'.$showyear.'</td>
            <td>'.($showmonth+2).'/'.$showyear.'</td>
            <td>'.$showmonth.'/'.$showyear.'</td>
            <td>'.($showmonth+1).'/'.$showyear.'</td>
            <td>'.($showmonth+2).'/'.$showyear.'</td>
            <td>'.$showmonth.'/'.$showyear.'</td>
            <td>'.($showmonth+1).'/'.$showyear.'</td>
            <td>'.($showmonth+2).'/'.$showyear.'</td>
            <td>'.$showmonth.'/'.$showyear.'</td>
            <td>'.($showmonth+1).'/'.$showyear.'</td>
            <td>'.($showmonth+2).'/'.$showyear.'</td>
        </tr>';

        break;

    default: 	//1 thang khoa duoc
        $smalltitle= '<table width="100%"><tr><td align="center">Tháng: '.$showmonth.'/'.$showyear.'</td></tr></table><br>';
        $titletable= '<table border="1" cellpadding="3" cellspacing="1" width="98%">
        <tr bgColor="#E1E1E1">
            <td align="center" width="4%"><b>STT</b></td>
            <td align="center" width="24%"><b>Tên thuốc</b></td>
            <td align="center" width="7%"><b>Đơn vị</b></td>
            <td align="center" width="10%"><b>Tồn kho cuối tháng</b></td>
            <td align="center" width="10%"><b>Số lượng mua trong tháng</b></td>
            <td align="center" width="10%"><b>Tổng số</b></td>
            <td align="center" width="10%"><b>Số lượng xuất trong tháng</b></td>
            <td align="center" width="10%"><b>Số lượng hư hỏng</b></td>
            <td align="center" width="10%"><b>Tồn kho cuối tháng</b></td>
            <td align="center" width="7%"><b>Ghi chú</b></td>
        </tr>';
        break;
}

$pdf->writeHTML($smalltitle);

//Load noi dung bang
$sTempDiv='';
unset($list_encoder);
switch($type_month){
    case 0: //1 thang khoa duoc
        $listReport= $Pharma->Khochan_sudungthuocdacbiet_thang($nhomdacbiet, $showmonth, $showyear);
        if(is_object($listReport)){
            while($rowReport = $listReport->FetchRow()){
                $list_encoder[$rowReport['product_encoder']]['name'] = $rowReport['product_name'];
                $list_encoder[$rowReport['product_encoder']]['unit'] = $rowReport['unit_name_of_medicine'];
                $list_encoder[$rowReport['product_encoder']]['ton'] += $rowReport['ton'];
                $list_encoder[$rowReport['product_encoder']]['nhap'] += $rowReport['nhap'];
                $list_encoder[$rowReport['product_encoder']]['xuat'] += $rowReport['xuat'];
            }
            $i=1;
            ob_start();
            foreach ($list_encoder as $value) {
                echo '<tr bgColor="#ffffff"><td>'.$i.'</td>';
                echo '<td>'.$value['name'].'</td>';
                echo '<td>'.$value['unit'].'</td>';
                echo '<td align="right">'.number_format($value['ton']).'</td>';
                echo '<td align="right">'.number_format($value['nhap']).'</td>';
                echo '<td align="right">'.number_format($value['ton']+$value['nhap']).'</td>';		//tong cong
                echo '<td align="right">'.number_format($value['xuat']).'</td>';
                echo '<td></td>';		//hu hong
                echo '<td align="right">'.number_format($value['ton']+$value['nhap']-$value['xuat']).'</td>';
                echo '<td></td></tr>';	//ghi chu
                $i++;
            }
            $sTempDiv = $sTempDiv.ob_get_contents();
            ob_end_clean();

        } else {
            $sTempDiv='<tr bgColor="#ffffff"><td colspan="10">'.$LDNotReportThisMonth.'</td></tr>';
        }
        break;

    case 1:  //1 qui khoa duoc
        $listReport= $Pharma->Khochan_sudungthuocdacbiet_nhieuthang($nhomdacbiet, $showmonth, ($showmonth+2), $showyear);
        if(is_object($listReport)){
            while($rowReport = $listReport->FetchRow()){
                $list_encoder[$rowReport['product_encoder']]['name'] = $rowReport['product_name'];
                $list_encoder[$rowReport['product_encoder']]['unit'] = $rowReport['unit_name_of_medicine'];
                $list_encoder[$rowReport['product_encoder']]['ton'] += $rowReport['ton'];
                $list_encoder[$rowReport['product_encoder']]['nhap'] += $rowReport['nhap'];
                $list_encoder[$rowReport['product_encoder']]['xuat'] += $rowReport['xuat'];
                if($rowReport['giaton']>0)
                    $list_encoder[$rowReport['product_encoder']]['giaton'] = $rowReport['giaton'];
                if($rowReport['gianhap']>0)
                    $list_encoder[$rowReport['product_encoder']]['gianhap'] = $rowReport['gianhap'];
                if($rowReport['giaxuat']>0)
                    $list_encoder[$rowReport['product_encoder']]['giaxuat'] = $rowReport['giaxuat'];
            }
            $i=1;
            ob_start();
            foreach ($list_encoder as $value) {
                $dongia = max($value['giaton'],$value['gianhap'],$value['giaxuat']);
                if(round($dongia,3)==round($dongia))
                    $showdongia = number_format($dongia);
                else $showdongia = number_format($dongia,3);
                $toncuoi = $value['ton']+$value['nhap']-$value['xuat'];
                echo '<tr bgColor="#ffffff"><td>'.$i.'</td>';
                echo '<td>'.$value['name'].'</td>';
                echo '<td>'.$value['unit'].'</td>';
                echo '<td>'.$showdongia.'</td>';
                echo '<td align="right">'.number_format($value['ton']).'</td>';
                echo '<td align="right">'.number_format($value['ton']*$dongia).'</td>';
                echo '<td align="right">'.number_format($value['nhap']).'</td>';
                echo '<td align="right">'.number_format($value['nhap']*$dongia).'</td>';
                echo '<td align="right">'.number_format($value['xuat']).'</td>';
                echo '<td align="right">'.number_format($value['xuat']*$dongia).'</td>';
                echo '<td></td><td></td>';		//hu hong
                echo '<td align="right">'.number_format($toncuoi).'</td>';
                echo '<td align="right">'.number_format($toncuoi*$dongia).'</td>';
                echo '<td></td></tr>';	//ghi chu
                $i++;
            }
            $sTempDiv = $sTempDiv.ob_get_contents();
            ob_end_clean();

        } else {
            $sTempDiv='<tr bgColor="#ffffff"><td colspan="15">'.$LDNotReportThisMonth.'</td></tr>';
        }
        break;

    case 2:  //bao cao 6 thang khoa duoc
        $listReport= $Pharma->Khochan_sudungthuocdacbiet_nhieuthang($nhomdacbiet, $showmonth, ($showmonth+5), $showyear);
        if(is_object($listReport)){
            while($rowReport = $listReport->FetchRow()){
                $list_encoder[$rowReport['product_encoder']]['name'] = $rowReport['product_name'];
                $list_encoder[$rowReport['product_encoder']]['unit'] = $rowReport['unit_name_of_medicine'];
                $list_encoder[$rowReport['product_encoder']]['ton'] += $rowReport['ton'];
                $list_encoder[$rowReport['product_encoder']]['nhap'] += $rowReport['nhap'];
                $list_encoder[$rowReport['product_encoder']]['xuat'] += $rowReport['xuat'];
                if($rowReport['giaton']>0)
                    $list_encoder[$rowReport['product_encoder']]['giaton'] = $rowReport['giaton'];
                if($rowReport['gianhap']>0)
                    $list_encoder[$rowReport['product_encoder']]['gianhap'] = $rowReport['gianhap'];
                if($rowReport['giaxuat']>0)
                    $list_encoder[$rowReport['product_encoder']]['giaxuat'] = $rowReport['giaxuat'];
            }
            $i=1;
            ob_start();
            foreach ($list_encoder as $value) {
                $dongia = max($value['giaton'],$value['gianhap'],$value['giaxuat']);
                if(round($dongia,3)==round($dongia))
                    $showdongia = number_format($dongia);
                else $showdongia = number_format($dongia,3);
                $toncuoi = $value['ton']+$value['nhap']-$value['xuat'];
                echo '<tr bgColor="#ffffff"><td>'.$i.'</td>';
                echo '<td>'.$value['name'].'</td>';
                echo '<td>'.$value['unit'].'</td>';
                echo '<td>'.$showdongia.'</td>';
                echo '<td align="right">'.number_format($value['ton']).'</td>';
                echo '<td align="right">'.number_format($value['ton']*$dongia).'</td>';
                echo '<td align="right">'.number_format($value['nhap']).'</td>';
                echo '<td align="right">'.number_format($value['nhap']*$dongia).'</td>';
                echo '<td align="right">'.number_format($value['xuat']).'</td>';
                echo '<td align="right">'.number_format($value['xuat']*$dongia).'</td>';
                echo '<td></td><td></td>';		//hu hong
                echo '<td align="right">'.number_format($toncuoi).'</td>';
                echo '<td align="right">'.number_format($toncuoi*$dongia).'</td>';
                echo '<td></td></tr>';	//ghi chu
                $i++;
            }
            $sTempDiv = $sTempDiv.ob_get_contents();
            ob_end_clean();

        } else {
            $sTempDiv='<tr bgColor="#ffffff"><td colspan="15">'.$LDNotReportThisMonth.'</td></tr>';
        }
        break;

    case 3:	// bao cao tung thang cac khoa khac
        $sTempDiv='<tr bgColor="#ffffff"><td colspan="10">'.$LDNotReportThisMonth.'</td></tr>';
        break;

    case 4:	// bao cao qui cac khoa khac
        $sTempDiv='<tr bgColor="#ffffff"><td colspan="20">'.$LDNotReportThisMonth.'</td></tr>';
        break;

    default: $sTempDiv='<tr bgColor="#ffffff"><td colspan="15">'.$LDNotReportThisMonth.'</td></tr>';
    break;
}


$html = $titletable.$sTempDiv.'</table>'; // .$sTempDiv
//echo $html;

$pdf->writeHTML($html);
$pdf->Ln();
$pdf->SetFont('dejavusans', '', 10);
//Ky ten
$html2='<table width="100%">
		<tr>
			<td></td><td></td><td align="center"><i>Ngày ....... tháng ....... năm '.date('Y').'</i><br></td>
		</tr>
		<tr>
			<td align="center"><b>KHOA DƯỢC</b></td>
			<td align="center"><b>P.TÀI CHÍNH KẾ TOÁN</b></td>
			<td align="center"><b>GIÁM ĐỐC BỆNH VIỆN</b></td>
		</tr>
		<tr><td colspan="3"><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
		<tr>
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

//Close and output PDF document
$pdf->Output('KhoChan_ThuocGayNghien.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+