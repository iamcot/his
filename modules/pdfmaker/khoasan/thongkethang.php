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
    //Get info of current department, ward
    if ($dept_nr!=''){
        require_once($root_path.'include/care_api_classes/class_department.php');
        $Dept = new Department;
        if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
            $deptname = ($$deptinfo['LD_var']);
        }
    }
    require_once($root_path.'include/care_api_classes/class_obstetrics.php');
    $obj = new Obstetrics;
    $report= $obj->Report($month,$year);
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
                        <td align="center">KHOA: '.$deptname.'</td>
                        <td align="center"></td>  
                    </tr>
                    <tr>
                        <td align="center">SỐ:........./BC</td>
                        <td align="right">'.substr(PDF_HOSNAME,14).', ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2"><br/><br/><font size="16"><b>BÁO CÁO<br/>
                            CÔNG TÁC CHĂM SÓC SỨC KHỎE SINH SẢN<br/>
                            Tháng '.$month.' năm '.$year.'</b></font>
                        </td>
                    </tr>
		</table><br/>';
    $pdf->writeHTML($header);
    // set font
    $pdf->SetFont('dejavusans', '', 13);
    $pdf->SetLeftMargin(15);
    $tong_ngaydt=$info_report['SONGAYDT_XV']+$info_report['SONGAYDT_CXV'];
    if(strpos($array,$month)!==false || $month=='12'){
        $date=31;
    }else if($month=='2'){
        $date=28;
    }else{
        $date=30;
    }
    $body='<table>
               <tr>
                   <td><b><u>I.Đặc điểm tình hình:</u></b></td>
               </tr>
               <tr>
                   <td>
                       <u>1.Nhân lực:</u><br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tổng số: '.$info_report['TongNS'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Biên chế: '.$info_report['BC'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Hợp đồng: '.$info_report['HD'].'
                   </td>
               </tr>
               <tr>
                   <td>
                       <u>2.Trình độ chuyên môn:</u><br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bác sĩ: '.$info_report['BS'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;NHSTH: ????<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;CN.NHS: ????<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Hộ lý: ????
                   </td>
               </tr>
               <tr>
                   <td colspan="2"><b><u>II.Nội dung:</u></b></td>
               </tr>  
               <tr>
                   <td>
                       <u>1.Chuyên môn chung:</u><br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số giường bệnh: '.round($tong_ngaydt/$date,0).'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số bệnh điều trị nội trú: '.$info_report['NOITRU'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số khám bệnh: '.$info_report['NGOAITRU'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Tổng ngày điều trị nội trú: '.$tong_ngaydt.'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Ngày điều trị trung bình 1 bệnh nội trú: '.round($tong_ngaydt/$info_report['NOITRU'],0).'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số bệnh có BHYT: '.$info_report['BHYT'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số bệnh có BHYT tự nguyện: '.$info_report['BHYT_TN'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số bệnh có BHYT nghèo: '.$info_report['BHYT_HN'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số bệnh có BHXH: '.$info_report['BHXH'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Chuyển viện: '.$info_report['CV'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Chuyển viện tự túc: '.$info_report['CVTT'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Bệnh án ngoại trú: '.$info_report['BANT'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Đo ECG: '.$info_report['doDT'].'
                   </td>
               </tr>
               <tr>
                   <td>
                       <u>2.Sức khỏe bà mẹ:</u><br/>
                       &nbsp;&nbsp;a.&nbsp;Khám thai: <br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số lượt khám thai: '.$info_report['khamthai'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;<i>Trong đó vị thành niên: '.$info_report['khamthai_vtn'].'</i><br/>
                       &nbsp;&nbsp;b.&nbsp;Số phụ nữ đẻ: '.$info_report['sode'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;
                       <table>
                            <tr>
                                <td width="17%">*&nbsp;&nbsp; Trong đó: </td>
                                <td width="30%"><i>+ Sanh thường: '.$info_report['dethuong'].'</i></td>
                            </tr>
                            <tr>
                                <td width="17%"></td>
                                <td width="30%"><i>+ Sanh khó: '.$info_report['dekho'].'</i></td>
                            </tr>
                            <tr>
                                <td width="17%"></td>
                                <td width="30%"><i>+ Mổ lấy thai: '.$info_report['demo'].'</i></td>
                            </tr>
                            <tr>
                                <td colspan="2">-&nbsp;&nbsp;&nbsp;Số Sanh con thứ 3 trở lên: '.$info_report['deconthu3'].'</td>
                            </tr>
                            <tr>
                                <td colspan="2">*&nbsp;&nbsp;<b><i>Tai biến sản khoa: '.$info_report['taibiensankhoa'].'</i></b></td>
                            </tr>
                       </table>
                   </td>
               </tr>
               <tr>
                   <td>
                       c.&nbsp;<u>Khám phụ khoa:</u> <br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Tổng số lượt khám phụ khoa: '.$info_report['khamphukhoa'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Tổng số mắc bệnh phụ khoa: '.$info_report['macphukhoa'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Tổng số lượt chữa phụ khoa: '.$info_report['chuaphukhoa'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Papmear: '.$info_report['papmear'].'<br/>
                   </td>
               </tr>
               <tr>
                   <td>
                       d.&nbsp;<u>Phá thai:</u> '.$info_report['phathai'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Dưới 7 tuần: '.$info_report['naothai'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Trên 7 - 12 tuần: '.$info_report['hutthai'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Phá thai nội khoa: '.$info_report['phathainoikhoa'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;Số vị thành niên phá thai: '.$info_report['phathai_vtn'].'
                   </td>
               </tr>
               <tr>
                   <td>
                       đ.&nbsp;<u>Sẩy thai:</u> '.$info_report['saythai'].'<br/>
                       &nbsp;&nbsp;*&nbsp;&nbsp;<b><i>Tai biến do phá thai: '.$info_report['taibienphathai'].'</i></b>
                   </td>
               </tr>
               <tr>
                   <td>
                       3.&nbsp;<u>Sức khỏe trẻ em:</u><br/>                       
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số trẻ đẻ ra sống: '.$info_report['beduocsinh'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;<i>Trong đó bé gái: '.$info_report['begaiduocsinh'].'</i><br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số trẻ được cân: '.$info_report['beduoccan'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;<i>Trong đó dưới 2500g: '.$info_report['becanduoi2500g'].'</i><br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số sơ sinh được bú mẹ trong giờ đầu: '.$info_report['becobusua'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số sơ sinh được tiêm vitamin K1: '.$info_report['betiemvitaminK1'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số trẻ tiêm viêm gan B sơ sinh: '.$info_report['betiemviemganB'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số trẻ tiêm BCG: '.$info_report['betiemBCG'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số trẻ chết: '.$info_report['bechet'].'
                   </td>
               </tr>
               <tr>
                   <td>
                       4.&nbsp;<u>Kế hoạch hóa gia đình:</u><br/>                       
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số đặt DCTC: '.$info_report['datvong'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Tháo DCTC: '.$info_report['thaovong'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Thuốc uống (vỉ): '.$info_report['thuocuong'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Thuốc tiêm (lọ): '.$info_report['thuoctiem'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Thuốc cấy: '.$info_report['thuoccay'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Bao cao su (cái): '.$info_report['BCS'].'<br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Tổng số triệt sản: '.$info_report['trietsan'].'<br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;<i>Trong đó nam: '.$info_report['trietsannam'].'</i><br/>
                       &nbsp;&nbsp;*&nbsp;<b><i>Tai biến do sử dụng biện pháp tránh thai: '.$info_report['taibientranhthai'].'</i></b>
                   </td>
               </tr>
               <tr>
                   <td>
                       5.&nbsp;<u>Sinh hoạt người bệnh: </u><br/>                       
                       &nbsp;&nbsp;a.&nbsp;<u>Tư vấn:</u><br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số buổi nói chuyện: <br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số người dự: <br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Tư vấn cá nhân hàng ngày tại khoa sản: <br/><br/>
                       &nbsp;&nbsp;b.&nbsp;<u>Họp hội đồng người bệnh:</u><br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số buổi: <br/>
                       &nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Số người dự: <br/>
                   </td>
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
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->SetLeftMargin(15);
    $footer='<table>
                <tr>
                    <td><br/><br/><br/><br/><br/><br/>
                        * Nơi nhận:<br/>
                        &nbsp;&nbsp;- Khoa sản BV Tỉnh Bình Dương<br/>
                        &nbsp;&nbsp;- Phòng KHNV<br/>
                        &nbsp;&nbsp;- Lưu
                    </td>
                </tr>
             </table>';
    $pdf->writeHTML($footer);
    $pdf->setJPEGQuality(90);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    // ----------------------------------------------------------------------------
    $pdf->lastPage();
    //Close and output PDF document
    $pdf->Output('Thongke_thang_khoasan.pdf', 'I');
?>
