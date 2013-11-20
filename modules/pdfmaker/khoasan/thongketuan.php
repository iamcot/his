<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    $classpathFPDF=$root_path.'classes/fpdf/';
    $fontpathFPDF=$classpathFPDF.'font/unifont/';
    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
    * GNU General Public License
    * Copyright 2002,2003,2004,2005 Elpidio Latorilla
    * elpidio@care2x.org, 
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    $lang_tables[]='departments.php';
    $lang_tables[]='aufnahme.php';
    define('LANG_FILE','nursing.php');
    define('NO_CHAIN',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'modules/news/includes/inc_editor_fx.php');
    require_once($root_path.'include/care_api_classes/class_obstetrics.php');
    $obj = new Obstetrics;
    $report= $obj->Report_tuan($datefrom,$dateto);
    $info_report=$report->FetchRow();

    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');

    // create new PDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);

    // set document information
    $pdf->SetAuthor(PDF_HOSNAME);
    $pdf->SetTitle('Bieu Đo Chuyen Da');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(5, 8, 5);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);

    // add a page: Trang 1
    $pdf->AddPage();
    
    // set font
    $pdf->SetFont('dejavusans', '', 12);
    $header='<table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
			<td width="35%" align="center">
                            <b>SỞ Y TẾ BÌNH DƯƠNG</b>
                        </td>
                        <td width="65%" align="center">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</td>
                    </tr>
                    <tr>
                        <td align="center">'.PDF_HOSNAME.'</td>
                        <td align="center"><u>Độc Lập - Tự Do - Hạnh Phúc</u></td>
                    </tr>
                    <tr>
                        <td align="center"></td>
                        <td align="right">'.substr(PDF_HOSNAME,14).', ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2"><br/><br/><font size="16"><b>BÁO CÁO TUẦN KHOA SẢN<br/>
                            TỪ NGÀY '.formatDate2Local($datefrom, $date_format).' ĐẾN NGÀY '.formatDate2Local($dateto, $date_format).'</b></font>
                        </td>
                    </tr>
		</table><br/>';
    $pdf->writeHTML($header);
    // set font
    $pdf->SetFont('dejavusans', '', 13);
    $pdf->SetLeftMargin(15);
    $body='<table>
               <tr>
                   <td>- Số lượt sanh: '.$info_report['sode'].'</td>
               </tr>
               <tr>
                   <td>- Số lượt khám thai: '.$info_report['khamthai'].'</td>
               </tr>
               <tr>
                   <td>- Tổng số lượt khám phụ khoa: '.$info_report['khamphukhoa'].'</td>
               </tr>
               <tr>
                   <td>- Sẩy thai: '.$info_report['saythai'].'</td>
               </tr>
               <tr>
                   <td>- Kế hoạch hóa gia đình:<br/>                       
                       &nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;Số đặt DCTC: '.$info_report['datvong'].'<br/>
                       &nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;Tháo DCTC: '.$info_report['thaovong'].'<br/>
                       &nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;Thuốc uống: '.$info_report['thuocuong'].'<br/>
                       &nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;Thuốc tiêm : '.$info_report['thuoctiem'].'<br/>
                       &nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;Thuốc cấy: '.$info_report['thuoccay'].'<br/>
                       &nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;Bao cao su: '.$info_report['BCS'].'<br/>
                       &nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;Tổng số triệt sản: '.$info_report['trietsan'].'
                   </td>
               </tr>
               <tr>
                   <td>- Đo ECG: '.$info_report['doDT'].'</td>
               </tr>
               <tr>
                   <td>- Chuyển viện nội trú: '.$info_report['CV'].'</td>
               </tr>
               <tr>
                   <td>- Chuyển viện tự túc: '.$info_report['CVTT'].'</td>
               </tr>
           </table>';
    $pdf->writeHTML($body);
    $chuky=' <table>
                <tr>
                    <td width="35%" align="center"><b>Người báo cáo</b></td>
                    <td width="30%" align="center"><b>Trưởng khoa</b></td>
                    <td align="center"><b>Ban giám đốc</b></td>
                </tr>
            </table>';
    $pdf->writeHTML($chuky);
    // ----------------------------------------------------------------------------
    $pdf->lastPage();
    //Close and output PDF document
    $pdf->Output('Thongke_tuan_khoasan.pdf', 'I');
?>
