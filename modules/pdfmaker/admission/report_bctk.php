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
$tpdf->SetMargins(5, 8, 3);
// remove default header/footer
$tpdf->setPrintHeader(false);
$tpdf->setPrintFooter(false);

//set auto page breaks
$tpdf->SetAutoPageBreak(FALSE);
$tpdf->AddPage('L','A4');
$tpdf->SetFont('dejavusans', '', 10);

switch ($id) {
    case 'dieutrinoitru':
        $khoa = "ĐIỀU TRỊ NỘI TRÚ";

        break;

    default:
        # code...
        break;
}
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
$allMeDept = $dept_obj->getAllMedical();

$strdatebc = "BÁO CÁO THỐNG KÊ NGÀY ".date("d/m/Y",strtotime($datefrom))." - ĐẾN NGÀY ".date("d/m/Y",strtotime($dateto));
$header_1='<table  >
                <tr>
                    <td width="30%">
                            SỞ Y TẾ BÌNH DƯƠNG<br>
                            '.PDF_HOSNAME.'
                    </td>
                    <td align="center" width="50%">
                    	<b><font size="15">THỐNG KÊ '.$khoa.'</font></b><br><br>
                        <i>('.$strdatebc.')</i>
                    </td>
                    <td align="right" width="18%"></td>
                </tr>
                
                </table>';
$tpdf->writeHTML($header_1);
$tpdf->SetFont('dejavusans', '', 10);

$a="SELECT COUNT(DISTINCT t.encounter_nr)
    FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e, care_department AS d
	WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
    AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)AND
    t.dept_to = d.nr AND t.dept_to = t.dept_from AND t.type_encounter = 1
    AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."'
    AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' ";
$sql="SELECT d.nr, d.LD_var,
(SELECT COUNT(DISTINCT t.encounter_nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to = d.nr AND t.dept_to = t.dept_from AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' ) numvaovien,
(SELECT COUNT(t.nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_from != t.dept_to AND t.dept_to = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numkhoakhacden,
(SELECT COUNT(DISTINCT t.encounter_nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to = -1 AND t.dept_from = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numravien,
(SELECT COUNT(DISTINCT t.encounter_nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to = -4 AND t.dept_from = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numxinve,
(SELECT COUNT(t.nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to = -3 AND t.dept_from = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >='".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numbove,
(SELECT COUNT(t.nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to = -5 AND t.dept_from = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numduave,
(SELECT COUNT(t.nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to >0 AND t.dept_to != t.dept_from AND t.dept_from = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numchuyenkhoa,
(SELECT COUNT(t.nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to = -2 AND t.dept_from = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numchuyenvien,
(SELECT COUNT(t.nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.dept_to = -6 AND t.dept_from = d.nr AND t.type_encounter = 1 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numtuvong,
(SELECT COUNT(DISTINCT t.encounter_nr) FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND ( ( DATE_FORMAT(t.datein,'%Y-%m-%d') > '".date("Y-m-d",strtotime($dateto))."' AND t.dept_from = d.nr AND ( SELECT DATE_FORMAT(t1.datein,'%Y-%m-%d') FROM (dfck_encounter_transfer AS t1 JOIN care_person AS p) JOIN care_encounter AS e WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)AND t.encounter_nr = t1.encounter_nr AND t1.dept_to = t.dept_from AND t1.status=1 ORDER BY t1.datein DESC LIMIT 0,1 ) < '".date("Y-m-d",strtotime($dateto))."' ) OR (t.status=0 AND t.dept_to = d.nr AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."')) AND t.type_encounter = 1 ) numcuoiky,
(SELECT COUNT(DISTINCT t.encounter_nr)
	FROM (dfck_encounter_transfer AS t JOIN care_person AS p) JOIN care_encounter AS e
	WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)
		AND ( ( DATE_FORMAT(t.datein,'%Y-%m-%d') > '".date("Y-m-d",strtotime($datefrom))."' AND t.dept_from = d.nr
			AND ( SELECT DATE_FORMAT(t1.datein,'%Y-%m-%d') FROM (dfck_encounter_transfer AS t1 JOIN care_person AS p) JOIN care_encounter AS e
				WHERE (t.pid = p.pid) AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr AS nr FROM care_department)
				AND t1.encounter_nr = t.encounter_nr AND t1.dept_to = t.dept_from AND t1.status=1 ORDER BY t1.datein DESC LIMIT 0,1 ) < '".date("Y-m-d",strtotime($datefrom))."' )
			OR ( t.status=0 AND t.dept_to = d.nr AND DATE_FORMAT(t.datein,'%Y-%m-%d') < '".date("Y-m-d",strtotime($datefrom))."' ) )
		AND t.type_encounter = 1  ) numdauky,


(SELECT SUM(DATEDIFF((SELECT IF(t1.status = 0,'".date("Y-m-d",strtotime($dateto))."' ,(IF( DATE_FORMAT (t.datein,'%Y-%m-%d')>'".date("Y-m-d",strtotime($dateto))."','".date("Y-m-d",strtotime($dateto))."',DATE_FORMAT(t.datein,'%Y-%m-%d'))))
					FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e, care_department AS d
					WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr) AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department) AND t.encounter_nr = t1.encounter_nr
						AND (( t.dept_from = t1.dept_to AND t.nr != t1.nr AND t1.status=1) OR (t1.status=0))AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= DATE_FORMAT(t1.datein,'%Y-%m-%d')
					ORDER BY t.nr ASC LIMIT 0,1 ), IF( DATE_FORMAT(t1.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."','".date("Y-m-d",strtotime($datefrom))."',DATE_FORMAT(t1.datein,'%Y-%m-%d'))) + 1 )
	FROM (dfck_encounter_transfer AS t1   JOIN care_person AS p)   JOIN care_encounter AS e, care_department AS d
	WHERE (t1.pid = p.pid)  AND (e.encounter_nr=t1.encounter_nr)AND t1.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)	AND ( (	DATE_FORMAT(t1.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."'
			AND ((t1.status=0 AND t1.dept_to = d.nr ) OR ( (SELECT t2.datein FROM (dfck_encounter_transfer AS t2   JOIN care_person AS p)   JOIN care_encounter AS e, care_department AS d				WHERE (t2.pid = p.pid)  AND (e.encounter_nr=t2.encounter_nr)
						AND t2.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)AND t2.encounter_nr=t1.encounter_nr AND t2.dept_from = t1.dept_to AND t2.status = 1 ORDER BY t2.nr DESC LIMIT 0,1)>='".date("Y-m-d",strtotime($datefrom))."' ) ) )
		OR (DATE_FORMAT(t1.datein,'%Y-%m-%d')>='".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') )     AND t1.type_encounter = 1 AND t1.dept_to = d.nr ) songaydt,
(SELECT SUM(DATEDIFF( (SELECT DATE_FORMAT(t.datein,'%Y-%m-%d') FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e, care_department AS d
	WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
    AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)AND t.encounter_nr = t1.encounter_nr AND t.dept_to < 0 AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= DATE_FORMAT(t1.datein,'%Y-%m-%d') AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' ORDER BY t.nr ASC LIMIT 0,1 ), IF( DATE_FORMAT(t1.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."','".date("Y-m-d",strtotime($datefrom))."',DATE_FORMAT(t1.datein,'%Y-%m-%d'))) + 1 ) FROM (dfck_encounter_transfer AS t1   JOIN care_person AS p)   JOIN care_encounter AS e, care_department AS d
	WHERE (t1.pid = p.pid)  AND (e.encounter_nr=t1.encounter_nr)
    AND t1.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)AND ( (DATE_FORMAT(t1.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."' AND ( (t1.status=0 AND t1.dept_to = d.nr ) OR ( (SELECT t2.datein FROM (dfck_encounter_transfer AS t2   JOIN care_person AS p)   JOIN care_encounter AS e, care_department AS d
	WHERE (t2.pid = p.pid)  AND (e.encounter_nr=t2.encounter_nr)
    AND t2.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)AND t2.encounter_nr=t1.encounter_nr AND t2.dept_from = t1.dept_to AND t2.status = 1 ORDER BY t2.nr DESC LIMIT 0,1)>='".date("Y-m-d",strtotime($datefrom))."' ) ) ) OR (DATE_FORMAT(t1.datein,'%Y-%m-%d')>='".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') ) AND t1.type_encounter = 1 AND t1.dept_to = d.nr ) songaydtrv,
d.name_formal
FROM care_department d
WHERE d.type = 1
ORDER BY d.nr";
$header_2 = '<tr>
                <td rowspan="2" align="center" width="20%"><br><br>KHOA</td>
                <td rowspan="2" align="center" width="5%">BN<br> đầu<br> kỳ</td>
                <td rowspan="2" align="center" width="5%">BN<br> vào<br> viện</td>
                <td rowspan="2" align="center" width="5%">BN<br>khoa<br>khác<br>đến</td>
                <td colspan="7" align="center" width="35%">BỆNH NHÂN RA VIỆN</td>
                <td rowspan="2" align="center" width="7%">BN<br>cuối<br>kỳ</td>
               <td rowspan="2" align="center" width="7%">Số ngày<br>điều trị<br>trong kỳ</td>
                <td rowspan="2" align="center" width="7%">Số ngày<br>điều trị<br>BN ra viện trong kỳ</td>
            </tr>
            <tr>
                <td align="center" width="5%">Ra<br>viện</td>
                <td align="center" width="5%">Xin<br>về</td>
                <td align="center" width="5%">Bỏ<br>về</td>
                <td align="center" width="5%">Đưa<br>về</td>
                <td align="center" width="5%">Ch<br>khoa</td>
                <td align="center" width="5%">Ch<br>viện</td>
                <td align="center" width="5%">Tử<br>vong</td>
            </tr>
            ';
$content="";
global $db;
$s1=$s2=$s3=$s4=$s5=$s6=$s7=$s8=$s9=$s10=$s11=$s12=$s13=0;
if($qr = $db->Execute($sql)){
    if($qr->RecordCount()){
        while($row = $qr->FetchRow()){
            $content .= '<tr>
                <td align="left">'.$$row['LD_var'].'</td>
                <td align="right">'.(($row['numdauky'])?$row['numdauky']:'').'</td>
                <td align="right">'.(($row['numvaovien'])?$row['numvaovien']:'').'</td>
                <td align="right">'.(($row['numkhoakhacden'])?$row['numkhoakhacden']:'').'</td>
                <td align="right">'.(($row['numravien'])?$row['numravien']:'').'</td>
                <td align="right">'.(($row['numxinve'])?$row['numxinve']:'').'</td>
                <td align="right">'.(($row['numbove'])?$row['numbove']:'').'</td>
                <td align="right">'.(($row['numduave'])?$row['numduave']:'').'</td>
                <td align="right">'.(($row['numchuyenkhoa'])?$row['numchuyenkhoa']:'').'</td>
                <td align="right">'.(($row['numchuyenvien'])?$row['numchuyenvien']:'').'</td>
                <td align="right">'.(($row['numtuvong'])?$row['numtuvong']:'').'</td>
                <td align="right">'.(($row['numcuoiky'])?$row['numcuoiky']:'').'</td>
                <td align="right">'.(($row['songaydt'])?$row['songaydt']:'').'</td>
                <td align="right">'.(($row['songaydtrv'])?$row['songaydtrv']:'').'</td>
            </tr>';
            $s1+=$row['numdauky'];
            $s2+=$row['numvaovien'];
            $s3+=$row['numkhoakhacden'];
            $s4+=$row['numravien'];
            $s5+=$row['numxinve'];
            $s6+=$row['numbove'];
            $s7+=$row['numduave'];
            $s8+=$row['numchuyenkhoa'];
            $s9+=$row['numchuyenvien'];
            $s10+=$row['numtuvong'];
            $s11+=$row['numcuoiky'];
            $s12+=$row['songaydt'];
            $s13+=$row['songaydtrv'];
        }
        $content .= '<tr><td align="left">TOÀN BỆNH VIỆN</td>
                
                <td align="right"><b>'.$s1.'</b></td>
                <td align="right"><b>'.$s2.'</b></td>
                <td align="right"><b>'.$s3.'</b></td>
                <td align="right"><b>'.$s4.'</b></td>
                <td align="right"><b>'.$s5.'</b></td>
                <td align="right"><b>'.$s6.'</b></td>
                <td align="right"><b>'.$s7.'</b></td>
                <td align="right"><b>'.$s8.'</b></td>
                <td align="right"><b>'.$s9.'</b></td>
                <td align="right"><b>'.$s10.'</b></td>
                <td align="right"><b>'.$s11.'</b></td>
                <td align="right"><b>'.$s12.'</b></td>
                <td align="right"><b>'.$s13.'</b></td>
                </tr>';
    }
}
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
$tpdf->writeHTML('<table border="1" cellpadding="5">'.$header_2.$content.'</table><br><br>'.$footer);
$tpdf->Output('TKDIEUTRINOITRU.pdf','I');
