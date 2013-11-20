<?php
  //  error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
function exportbctb($tbcontent){
    $report_textsize=12;
    $report_titlesize=14;
    $report_auxtitlesize=10;
    $report_authorsize=10;
    require('./roots.php');
    $lang_tables[]='edp.php';
    $lang_tables[]='departments.php';

    define('LANG_FILE','aufnahme.php');
    define('NO_2LEVEL_CHK',1);
    
    //$classpathFPDF=$root_path.'classes/fpdf/';
    //$fontpathFPDF=$classpathFPDF.'font/unifont/';
    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');

    $tpdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8',false);
    $tpdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $tpdf->SetMargins(5, 8, 3);    

    // remove default header/footer
    $tpdf->setPrintHeader(false);
    $tpdf->setPrintFooter(false);

    //set auto page breaks
    $tpdf->SetAutoPageBreak(TRUE, 3);
    $tpdf->AddPage();
    $tpdf->SetFont('dejavusans', '', 13);
    
    $header_1='<table  >
                <tr>
                    <td width="30%">
                            SỞ Y TẾ BÌNH DƯƠNG<br>
                            '.PDF_HOSNAME.'
                    </td>
                    <td align="center" width="55%">
                        CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM<br>
                        Độc Lập - Tự Do - Hạnh Phúc
                    </td>
                </tr>
                <tr>
                    <td width="16%" align="center">
                            -------------
                    </td>
                    <td width="11%"></td>
                    <td align="center" width="60%">                        
                        ---------------------------------
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                            Số:......./BC-TTBYT
                    </td>
                    <td width="25%"></td>
                    <td align="center" width="55%">
                        Ngày  '.date('d').'  tháng  '.date('m').'  năm  '.date('Y').'<br>
                    </td>
                </tr>
                <tr>
                    <td width="100%" align="center"><b>BÁO CÁO TRANG THIẾT BỊ - DỤNG CỤ Y TẾ</b></td>
                </tr>                
                </table>';
    $tpdf->writeHTML($header_1);
    $tpdf->SetFont('dejavusans', '', 10);
    $table = $tbcontent;
    /*
    $table='<table border="1" cellspacing="0" cellpadding="3" >
            <tr>
                <td align="center" width="3%">STT</td>
                <td align="center" width="13%">Tên trang thiết bị</td>
                <td align="center" width="5%">Đơn vị tính</td>
                <td align="center" width="5%">Số lượng cấp</td>
                <td align="center" width="5%">Nước SX - năm SX</td>
                <td align="center" width="5%">Hãng SX - Model</td>
                <td align="center">Ngày tháng năm trang bị</td>
                <td align="center">Ngày tháng năm đưa vào sử dụng</td>
                <td align="center" width="5%">Số lượng đang sử dụng</td>
                <td align="center" width="4%">Giá trị hiện còn (%)</td>
                <td align="center" width="5%">Số lần sử dụng trung bình/ tháng</td>
                <td align="center">Số lượng chưa sử dụng</td>
                <td align="center">Đánh giá chất lượng</td>
                <td align="center">Điện năng tiêu thụ</td>
                <td align="center">Nguyên nhân chưa sử dụng</td>
                <td align="center">Số lần phải sửa chữa</td>
                <td align="center">Nơi sửa chữa</td>
                <td align="center" width="4%">Ghi chú</td>
            </tr>';
    $tb_property=$property->getInfo_Equip();
    $i=1;    
    while($thietbi=$tb_property->FetchRow()){
        $count=$property->Count_Equip($thietbi['serie']);
        $count_use=$property->Count_EquipUse($thietbi['nr']);
        $use=$property->getLastestTransmitting($thietbi['nr']);
        $table.='<tr>';
        $table.='<td align="center">'.$i.'</td>';
        $table.='<td align="center">'.$thietbi['name_formal'].'</td>';
        $table.='<td align="center">'.$thietbi['unit'].'</td>';
        $table.='<td align="center">'.$count['count_serie'].'</td>';
        if($thietbi['productiondate']!='0000-00-00'){
            $table.='<td align="center">'.$thietbi['nuocsx'].'<br>'.@formatDate2STD($thietbi['productiondate'],$date_format).'</td>';
        }else{
            $table.='<td align="center"></td>';
        }
        $table.='<td align="center">'.$thietbi['factorer'].'<br>-'.$thietbi['model'].'</td>';
        if($thietbi['useddate']!='0000-00-00'){
            $table.='<td align="center">'.@formatDate2STD($thietbi['useddate'],$date_format).'</td>';
        }else{
            $table.='<td align="center"></td>';
        }
        if(substr($use['im_date'],0,10)!='0000-00-00'){
            $table.='<td align="center">'.@formatDate2STD(substr($use['im_date'],0,10),$date_format).'</td>';
        }else{
            $table.='<td align="center"></td>';
        } 
        $table.='<td align="center">'.$count_use['count_use'].'</td>';
        $table.='<td align="center">'.$thietbi['importstatus'].'</td>';
        $table.='<td align="center"></td>';
        $table.='<td align="center"></td>';
        $table.='<td align="center"></td>';
        if($thietbi['power']>0){
            $power=$thietbi['power'].'W';
        }else{
            $power='';
        }
        $table.='<td align="center">'.$power.'</td>';
        $table.='<td align="center"></td>';
        $table.='<td align="center"></td>';
        $table.='<td align="center"></td>';
        $table.='<td align="center">'.$thietbi['note'].'</td>';
        $table.='</tr>';
        $i++;
    }
    $table.='</table>';
    */
    $tpdf->writeHTML($table);
    $tpdf->SetFont('dejavusans', '', 12);
    $bottom='<table width=100%>
            <tr>
                <td width="35%" align="center"><b>GIÁM ĐỐC</b></td>
                <td width="45%" align="right"><b>NGƯỜI BÁO CÁO</b><br><br><br></td>
            </tr>            
            </table>';
    $tpdf->writeHTML($bottom);
    $tpdf->SetFont('dejavusans', 'I', 12);
//    $page=$tpdf->getNumPages();
    $bottom1='<table width=100%>
            <tr>
                <td width="35%" align="center">Họ & tên:.......................................</td>
                <td width="55%" align="right">Họ & tên:..............................................<br><br></td>
            </tr>
            </table>';
    $tpdf->writeHTML($bottom1);
//    $tpdf->writeHTML($page);
    //$tpdf->Output();
    $tpdf->Output('Baocaotrangthietbi.pdf', 'I');
}


?>