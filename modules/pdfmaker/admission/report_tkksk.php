<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('NO_CHAIN',1);
$local_user='aufnahme_user';
$lang_tables[]='departments.php';
require($root_path.'include/core/inc_front_chain_lang.php');
define('MAX_ROW_PP',40); //size 8
define('WIDTH_BT',10); //size 8



$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
//define("_SYSTEM_TTFONTS",$fontpathFPDF);
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
include($classpathFPDF.'tfpdf.php');
$tpdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8',false);
    $tpdf->SetTitle("Thống kê bệnh viên");
    $tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $tpdf->SetMargins(5, 5, 5);    
    // remove default header/footer
    $tpdf->setPrintHeader(false);
    $tpdf->setPrintFooter(false);

    //set auto page breaks
    $tpdf->SetAutoPageBreak(FALSE);
    $tpdf->AddPage('P','A4');
    $tpdf->SetFont('dejavusans', '', 10);

	
	$khoa = "BÁO CÁO CÔNG TÁC KHÁM SỨC KHỎE ĐỊNH KỲ";

include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
$allMeDept = $dept_obj->getAllMedical();

$strdatebc = "BÁO CÁO THỐNG KÊ NGÀY ".date("d/m/Y",strtotime($datefrom))." - ĐẾN NGÀY ".date("d/m/Y",strtotime($dateto));
$header_1='<table width="100%" >
                <tr>
                    <td >
                            SỞ Y TẾ BÌNH DƯƠNG<br>
                            '.PDF_HOSNAME.'
                    </td>
                    <td></td>
                    </tr>
                    <tr>
                    <td width="100%" align="center" colspan="2"><br><br><br>
                    	<b><font size="14">'.$khoa.'</font></b><br><br>
                        <i>('.$strdatebc.')</i>
                    </td>                    
                </tr>
                
                </table>';
$tpdf->writeHTML($header_1);
//$tpdf->SetFont('dejavusans', '', 9);


$sql="SELECT 
        (SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE mucdichkham='tuyendung') tuyendung,
        (SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE mucdichkham='laixe') laixe,
        (SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE mucdichkham='hocsinh') hocsinh,
        (SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE mucdichkham='khac') khac
        FROM dual";
$sql2= "SELECT 
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='m'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumk,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='m' AND ketqua='I'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumi,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='m' AND ketqua='II'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumii,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='m' AND ketqua='III'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumiii,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='m' AND ketqua='IV'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumiv,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='m' AND ketqua='V'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumv
        FROM DUAL
        UNION
        SELECT
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='f'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumk,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='f' AND ketqua='I'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumi,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='f' AND ketqua='II'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumii,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='f' AND ketqua='III'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumiii,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='f' AND ketqua='IV'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumiv,
        (SELECT COUNT(k1.nr)
        FROM care_kham_suc_khoe k1,care_person p1
        WHERE k1.pid = p1.pid AND p1.sex='f' AND ketqua='V'
        AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(k1.date_kham,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') sumv
        FROM DUAL";
$tuyendung='Tuyển dụng';
$laixe='Lái xe';
$hocsinh='Học sinh';
$khac='Khác';
$congtackhamtuyen = '';
$sumcongtackhamtuyen = 0;
if($rs = $db->Execute($sql)){
    if($rs->RecordCount()){
        $row=$rs->FetchRow();
        $sumcongtackhamtuyen = '<b>I. Công tác khám sức khỏe</b><br>Tổng số khám tuyển: '.($row['tuyendung']+$row['laixe']+$row['hocsinh']+$row['khac']).' lượt';
        $congtackhamtuyen = "<br>Trong đó:<ul>
            <li>Tuyển dụng: ".$row['tuyendung']."</li>
            <li>Lái xe: ".$row['laixe']."</li>
            <li>Học sinh: ".$row['hocsinh']."</li>
            <li>Khác: ".$row['khac']."</li>
            </ul>
        ";
        
    }
}
if($rs = $db->Execute($sql2)){
    if($rs->RecordCount()){
        
        $table2 = '<br><br><b>II. Công tác khám sức khỏe định kỳ, phát hiện bệnh nghề nghiệp</b><br><br>
        <table border="1" cellpadding="3">
        <tr><td rowspan="2" align="center">Giới</td><td rowspan="2" align="center">Số người khám</td><td colspan="5" align="center">PHÂN LOẠI SỨC KHỎE</td></tr>
        <tr><td align="center">Loại I</td><td align="center">Loại II</td><td align="center">Loại III</td><td align="center">Loại IV</td><td align="center">Loại V</td></tr>
        ';        
        $row=$rs->FetchRow();
        $table2.='<tr><td align="center">Nam</td><td align="right">'.$row['sumk'].'</td><td align="right">'.$row['sumi'].'</td><td align="right">'.$row['sumii'].'</td><td>'.$row['sumiii'].'</td><td align="right">'.$row['sumiv'].'</td><td align="right">'.$row['sumv'].'</td></tr>';
        $row=$rs->FetchRow();
        $table2.='<tr><td align="center">Nữ</td><td align="right">'.$row['sumk'].'</td><td align="right">'.$row['sumi'].'</td><td align="right">'.$row['sumii'].'</td><td align="right">'.$row['sumiii'].'</td><td align="right">'.$row['sumiv'].'</td><td align="right">'.$row['sumv'].'</td></tr>';
        $table2.='</table>';
    }
}
$tpdf->writeHTML($sumcongtackhamtuyen.$congtackhamtuyen.$table2);
$footer = '<table width=100%>
            <tr>
            <td width="30%" align="center"></td>
                <td width="30%" align="center"></td>
                <td width="38%" align="center">Ngày ... tháng ... năm ...</td>
            </tr>
            <tr>
                <td width="30%" align="center"><b>NGƯỜI LẬP BIỂU</b><br><i>(Chức danh, ký tên)</i></td>
                <td width="30%" align="center"><b>TRƯỞNG PHÒNG KHTH</b><br><i>(Chức danh, ký tên)</i><br><br><br></td>
                <td width="38%" align="center"><b>GIÁM ĐỐC</b><br><i>(Ký tên, đóng dấu)</i><br><br><br></td>
            </tr>            
            </table>';        
$tpdf->writeHTML($footer);
$tpdf->Output('B031DT.pdf','I');