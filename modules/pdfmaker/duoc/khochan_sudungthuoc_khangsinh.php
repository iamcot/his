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



//type_month = 0: bao cao tung thang; 1: bao cao qui; 2: bao cao 6 thang
//showmonth & showyear: thang bao cao


$date_format='dd-mm-yyyy';

// create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Báo cáo sử dụng thuốc khác');
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
				<font size="15"><b>MẪU BÁO CÁO VỀ KHÁNG SINH</b></font>
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
        $smalltitle= 'Tháng '.$showmonth.'/'.$showyear;
        break;

    case 1:  	//qui khoa duoc
        $smalltitle= 'Quí '.(($showmonth-1)/3 +1).'/'.$showyear;
        break;


    case 2:  	//6 thang khoa duoc
        $smalltitle= 'Tháng '.$showmonth.'/'.$showyear.' đến '.($showmonth+5).'/'.$showyear;
        break;


    default: 	//1 thang khoa duoc
        $smalltitle= 'Tháng '.$showmonth.'/'.$showyear;
        break;
}
$titletable= '<table border="1" cellpadding="3" cellspacing="1" width="98%">
   <tr bgColor="#E1E1E1">
		<td align="center" width="4%"><b>TT hoạt chất</b></td>
		<td align="center" width="10%"><b>Tên hoạt chất</b></td>
		<td align="center" width="6%"><b>Mã ATC</b></td>
		<td align="center" width="4%"><b>TT biệt dược</b></td>
		<td align="center" width="24%"><b>Tên biệt dược</b></td>
		<td align="center" width="7%"><b>Nước sản xuất</b></td>
		<td align="center" width="8%"><b>Nồng độ/Hàm lượng</b></td>
		<td align="center" width="7%"><b>Đơn vị đóng gói</b></td>
		<td align="center" width="7%"><b>Đường dùng</b></td>
		<td align="center" width="7%"><b>Số lượng</b></td>
		<td align="center"><b>Đơn giá</b></td>
		<td align="center" width="10%"><b>Thành Tiền</b></td>
	</tr>
	<tr bgColor="#E1E1E1">
		<td align="center"><b>1</b></td>
		<td align="center"><b>2</b></td>
		<td align="center"><b>3</b></td>
		<td align="center"><b>4</b></td>
		<td align="center"><b>5</b></td>
		<td align="center"><b>6</b></td>
		<td align="center"><b>7</b></td>
		<td align="center"><b>8</b></td>
		<td align="center"><b>9</b></td>
		<td align="center"><b>10</b></td>
		<td align="center"><b>11</b></td>
		<td align="center"><b>12</b></td>
	</tr>';

$pdf->writeHTML('<table width="100%"><tr><td align="center" colspan="2">'.$smalltitle.'<br></td></tr>
                <tr><td>Số giường bệnh kế hoạch/thực kê:....................</td>
                    <td>Công suất sử dụng giường bệnh:....................(%)</td></tr></table>');

//Load noi dung bang
$sTempDiv='';
$oldencoder=''; $flag = true;

switch($type_month){
    case 0: //1 thang khoa duoc
        $listReport= $Pharma->Khochan_sudungthuockhangsinh_thang('tayy', $pharma_group_id, '', $showmonth, $showyear);
        ob_start();
        if(is_object($listReport)){
            $i=1; $j=1;
            while($rowReport = $listReport->FetchRow()){
                if($rowReport['xuat']>0){
                    if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
                        $showgiaxuat = number_format($rowReport['giaxuat']);
                    else $showgiaxuat = number_format($rowReport['giaxuat'],3);

                    if($olddrugid!=$rowReport['pharma_generic_drug_id']){
                        $generic_stt = $i; $generic_atc = $rowReport['ATC'];
                        $generic_name = $rowReport['generic_drug'];
                        $flag=!$flag;
                        $olddrugid=$rowReport['pharma_generic_drug_id'];
                        $i++; $j=0;
                    }else{
                        $generic_stt = ''; $generic_atc =''; $generic_name = '';
                    }

                    if($oldencoder!=$rowReport['product_encoder']){

                        $oldencoder=$rowReport['product_encoder'];
                        $j++;
                        $medicine_stt = $i.'.'.$j;
                    }else $medicine_stt ='';

                    if($flag) $bgc="#EFEFEF";
                    else $bgc="#FFFFFF";

                    echo '<tr bgColor="'.$bgc.'"><td align="center">'.$generic_stt.'</td>';
                    echo '<td>'.$generic_name.'</td>';
                    echo '<td>'.$generic_atc.'</td>';
                    echo '<td align="center">'.$medicine_stt.'</td>';
                    echo '<td>'.$rowReport['product_name'].'</td>';
                    echo '<td>'.$rowReport['nuocsx'].'</td>';
                    echo '<td>'.$rowReport['content'].'</td>';
                    echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
                    echo '<td>'.$rowReport['using_type'].'</td>';
                    echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';
                    echo '<td align="right">'.$showgiaxuat.'</td>';
                    echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td></tr>';
                }
            }

        } else {
            echo '<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';
        }
        $sTempDiv = $sTempDiv.ob_get_contents();
        ob_end_clean();
        break;

    case 1:  //1 qui khoa duoc
        $listReport= $Pharma->Khochan_sudungthuockhangsinh_nhieuthang('tayy', $pharma_group_id, '', $showmonth, ($showmonth+2), $showyear);
        ob_start();
        if(is_object($listReport)){
            $i=1; $j=1;
            while($rowReport = $listReport->FetchRow()){
                if($rowReport['xuat']>0){
                    if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
                        $showgiaxuat = number_format($rowReport['giaxuat']);
                    else $showgiaxuat = number_format($rowReport['giaxuat'],3);

                    if($olddrugid!=$rowReport['pharma_generic_drug_id']){
                        $generic_stt = $i; $generic_atc = $rowReport['ATC'];
                        $generic_name = $rowReport['generic_drug'];

                        $olddrugid=$rowReport['pharma_generic_drug_id'];
                        $i++; $j=0;
                    }else{
                        $generic_stt = ''; $generic_atc =''; $generic_name = '';
                    }

                    if($oldencoder!=$rowReport['product_encoder']){
                        $flag=!$flag;
                        $oldencoder=$rowReport['product_encoder'];
                        $j++;
                    }
                    if($flag) $bgc="#EFEFEF";
                    else $bgc="#FFFFFF";

                    echo '<tr bgColor="'.$bgc.'"><td align="center">'.$generic_stt.'</td>';
                    echo '<td>'.$generic_name.'</td>';
                    echo '<td>'.$generic_atc.'</td>';
                    echo '<td align="center">'.$i.'.'.$j.'</td>';
                    echo '<td>'.$rowReport['product_name'].'</td>';
                    echo '<td>'.$rowReport['nuocsx'].'</td>';
                    echo '<td>'.$rowReport['content'].'</td>';
                    echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
                    echo '<td>'.$rowReport['using_type'].'</td>';
                    echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';
                    echo '<td align="right">'.$showgiaxuat.'</td>';
                    echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td></tr>';
                }
            }
        } else {
            echo '<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';
        }
        $sTempDiv = $sTempDiv.ob_get_contents();
        ob_end_clean();
        break;

    case 2:  //bao cao 6 thang khoa duoc
        $listReport= $Pharma->Khochan_sudungthuockhangsinh_nhieuthang('tayy', $pharma_group_id, '', $showmonth, ($showmonth+5), $showyear);
        ob_start();
        if(is_object($listReport)){
            $i=1; $j=1;
            while($rowReport = $listReport->FetchRow()){
                if($rowReport['xuat']>0){
                    if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
                        $showgiaxuat = number_format($rowReport['giaxuat']);
                    else $showgiaxuat = number_format($rowReport['giaxuat'],3);

                    if($olddrugid!=$rowReport['pharma_generic_drug_id']){
                        $generic_stt = $i; $generic_atc = $rowReport['ATC'];
                        $generic_name = $rowReport['generic_drug'];

                        $olddrugid=$rowReport['pharma_generic_drug_id'];
                        $i++; $j=0;
                    }else{
                        $generic_stt = ''; $generic_atc =''; $generic_name = '';
                    }

                    if($oldencoder!=$rowReport['product_encoder']){
                        $flag=!$flag;
                        $oldencoder=$rowReport['product_encoder'];
                        $j++;
                    }
                    if($flag) $bgc="#EFEFEF";
                    else $bgc="#FFFFFF";

                    echo '<tr bgColor="'.$bgc.'"><td align="center">'.$generic_stt.'</td>';
                    echo '<td>'.$generic_name.'</td>';
                    echo '<td>'.$generic_atc.'</td>';
                    echo '<td align="center">'.$i.'.'.$j.'</td>';
                    echo '<td>'.$rowReport['product_name'].'</td>';
                    echo '<td>'.$rowReport['nuocsx'].'</td>';
                    echo '<td>'.$rowReport['content'].'</td>';
                    echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
                    echo '<td>'.$rowReport['using_type'].'</td>';
                    echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';
                    echo '<td align="right">'.$showgiaxuat.'</td>';
                    echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td></tr>';
                }
            }
        } else {
            echo '<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';
        }
        $sTempDiv = $sTempDiv.ob_get_contents();
        ob_end_clean();
        break;

    default: $sTempDiv='<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';
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
$pdf->Output('KhoChan_KhangSinh.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+