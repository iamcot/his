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

	
	$khoa = "HOẠT ĐỘNG ĐIỀU TRỊ";

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
                    	<b><font size="15">'.$khoa.'</font></b><br><br>
                        <i>('.$strdatebc.')</i>
                    </td>
                    <td align="right" width="18%">Biểu 03.1-ĐT</td>
                </tr>
                
                </table>';
$tpdf->writeHTML($header_1);
$tpdf->SetFont('dejavusans', '', 9);
//numvaovien hien dang tinh la chi tinh BN duoc tiep nhan vao khoa
$sql = "SELECT 
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.type_encounter = 1
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' and f1.sex='f') numvaovien,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    AND f1.type_encounter = 1
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' and f1.sex='f'
    and ( DATE_FORMAT(f1.datein,'%Y') - f1.yearbirth) < 6 ) numvaovienkid6,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    AND f1.type_encounter = 1
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' and f1.sex='f'
    and ( DATE_FORMAT(f1.datein,'%Y') - f1.yearbirth) < 15) numvaovienkid15,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.type_encounter = 1
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' and f1.sex='f' 
    and f1.dept_to = (select nr from care_department where LD_var like '%YHCT%' limit 0,1) ) numvaovienyhct,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.type_encounter = 1
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' and f1.sex='f' 
    and f1.dept_from = (select nr from care_department where LD_var like '%HSCC%' limit 0,1) ) numvaoviencapcuu,
( SELECT COUNT(DISTINCT f1f.encounter_nr)
FROM dfck_admit_inout_dept f1f 
    WHERE  f1f.insurance_nr != '' and f1f.type_encounter = 1
    and DATE_FORMAT(f1f.insurance_start,'%Y-%m-%d') < '".date("Y-m-d",strtotime($datefrom))."' 
    and  DATE_FORMAT(f1f.insurance_exp,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($dateto))."'
    AND DATE_FORMAT(f1f.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1f.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' and f1f.sex='f') numbhyt,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.sex='f'
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    ) numtuvong,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.sex='f'
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f9.datein,'%Y') - f9.yearbirth) <= 1
    ) numtuvongkid1,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.sex='f'
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f9.datein,'%Y') - f9.yearbirth) <= 5
    ) numtuvongkid5,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.sex='f'
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f9.datein,'%Y') - f9.yearbirth) <= 15
    ) numtuvongkid15,
(
    SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.sex='f'
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and  DATEDIFF(DATE_FORMAT(f9.death_date,'%Y-%m-%d'),
        (select DATE_FORMAT(f9b.datein,'%Y-%m-%d') from dfck_admit_inout_dept f9b 
            where f9b.encounter_nr = f9.encounter_nr 
            and f9b.nr < f9.nr and f9b.dept_to = f9.dept_from 
            order by f9b.nr limit 0,1
        ) 
    ) <= 1 
) numtuvong24h,
(SELECT COUNT(DISTINCT f10.encounter_nr) 
    FROM dfck_admit_inout_dept f10
    WHERE f10.sex='f' and ( ( DATE_FORMAT(f10.datein,'%Y-%m-%d') > '".date("Y-m-d",strtotime($dateto))."' 
        AND ( SELECT DATE_FORMAT(f11.datein,'%Y-%m-%d') 
        from dfck_admit_inout_dept f11 
        where f11.encounter_nr = f10.encounter_nr 
        and f11.dept_to = f10.dept_from  and f11.status=1
        order by f11.datein DESC limit 0,1 ) < '".date("Y-m-d",strtotime($dateto))."'
        ) 
    OR (f10.status=0  and DATE_FORMAT(f10.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'))   
    AND f10.type_encounter = 1
    ) numcuoiky,
(SELECT COUNT(DISTINCT f12.encounter_nr) 
    FROM dfck_admit_inout_dept f12
    WHERE f12.sex='f' and ( ( DATE_FORMAT(f12.datein,'%Y-%m-%d') > '".date("Y-m-d",strtotime($datefrom))."'
        AND ( SELECT DATE_FORMAT(f13.datein,'%Y-%m-%d') 
        from dfck_admit_inout_dept f13 
        where f13.encounter_nr = f12.encounter_nr 
        and f13.dept_to = f12.dept_from   and f13.status=1
        order by f13.datein DESC limit 0,1 ) < '".date("Y-m-d",strtotime($datefrom))."'
        ) 
    OR ( f12.status=0 and DATE_FORMAT(f12.datein,'%Y-%m-%d') < '".date("Y-m-d",strtotime($datefrom))."' ) ) AND f12.type_encounter = 1
        ) numdauky,
(SELECT 
    SUM(DATEDIFF((SELECT IF(f14.status = 0,'".date("Y-m-d",strtotime($dateto))."' ,(IF( DATE_FORMAT(f15.datein,'%Y-%m-%d')>'".date("Y-m-d",strtotime($dateto))."','".date("Y-m-d",strtotime($dateto))."',DATE_FORMAT(f15.datein,'%Y-%m-%d'))))
        FROM dfck_admit_inout_dept f15 
        WHERE f15.encounter_nr = f14.encounter_nr 
        AND (( f15.dept_from = f14.dept_to AND f15.nr != f14.nr AND f14.status=1) OR (f14.status=0))
        AND DATE_FORMAT(f15.datein,'%Y-%m-%d') >= DATE_FORMAT(f14.datein,'%Y-%m-%d')
        ORDER BY f15.nr ASC LIMIT 0,1
        ), 
        IF( DATE_FORMAT(f14.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."','".date("Y-m-d",strtotime($datefrom))."',DATE_FORMAT(f14.datein,'%Y-%m-%d'))) + 1 )
    FROM dfck_admit_inout_dept f14 
    WHERE f14.sex='f' and (
    (DATE_FORMAT(f14.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."' 
        AND ( (f14.status=0 )
            OR ( (SELECT f1b.datein FROM dfck_admit_inout_dept f1b 
                WHERE f1b.encounter_nr=f14.encounter_nr 
                AND f1b.dept_from = f14.dept_to 
                AND f1b.status = 1 ORDER BY f1b.nr DESC LIMIT 0,1)>='".date("Y-m-d",strtotime($datefrom))."' )  ) ) 
    OR (DATE_FORMAT(f14.datein,'%Y-%m-%d')>='".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(f14.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."')
    )
    AND f14.type_encounter = 1
   ) songaydt
FROM dual
";
//echo $sql;
if($qr = $db->Execute($sql))
    if($qr->RecordCount())
    $rowf = $qr->FetchRow();
$trf = '<tr>
                <td >Trong đó nữ</td>
                <td align="center">****</td>
                <td align="center">'.(($rowf['numdauky'])?$rowf['numdauky']:'').'</td>
                <td align="center">'.(($rowf['numvaovien'])?$rowf['numvaovien']:'').'</td>
                <td align="center">'.(($rowf['numvaovienyhct'])?$rowf['numvaovienyhct']:'').'</td>
                <td align="center">'.(($rowf['numvaovienkid6'])?$rowf['numvaovienkid6']:'').'</td>
                <td align="center">'.(($rowf['numvaovienkid15'])?$rowf['numvaovienkid15']:'').'</td>
                <td align="center">'.(($rowf['numvaoviencapcuu'])?$rowf['numvaoviencapcuu']:'').'</td>
                <td align="center">'.(($rowf['songaydt'])?$rowf['songaydt']:'').'</td>
                <td align="center">'.(($rowf['numtuvong'])?$rowf['numtuvong']:'').'</td>
                <td align="center">'.(($rowf['numtuvongkid1'])?$rowf['numtuvongkid1']:'').'</td>
                <td align="center">'.(($rowf['numtuvongkid5'])?$rowf['numtuvongkid5']:'').'</td>
                <td align="center">'.(($rowf['numtuvongkid15'])?$rowf['numtuvongkid15']:'').'</td>
                <td align="center">'.(($rowf['numtuvong24h'])?$rowf['numtuvong24h']:'').'</td>
                <td align="center">'.(($rowf['numbhyt'])?$rowf['numbhyt']:'').'</td>
                <td align="center">'.(($rowf['numcuoiky'])?$rowf['numcuoiky']:'').'</td>                
            </tr>';
$sql2 = "SELECT d.nr,  d.LD_var, (select sum(r.nr_of_beds) from care_room r where r.dept_nr = d.nr and r.type_nr=1) sumbed,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.type_encounter = 1 and f1.dept_to = d.nr
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' ) numvaovien,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.dept_to = f1.dept_from AND f1.type_encounter = 1  and f1.dept_to = d.nr
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f1.datein,'%Y') - f1.yearbirth) < 6 ) numvaovienkid6,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.type_encounter = 1  and f1.dept_to = d.nr
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f1.datein,'%Y') - f1.yearbirth) < 15) numvaovienkid15,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.type_encounter = 1  and f1.dept_to = d.nr
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' 
    and f1.dept_to = (select nr from care_department where LD_var like '%YHCT%' limit 0,1) ) numvaovienyhct,
(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.type_encounter = 1  and f1.dept_to = d.nr
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and f1.dept_from = (select nr from care_department where LD_var like '%HSCC%' limit 0,1) ) numvaoviencapcuu,
( SELECT COUNT(DISTINCT f1f.encounter_nr)
FROM dfck_admit_inout_dept f1f 
    WHERE  f1f.insurance_nr != ''  
    and (f1f.dept_from = d.nr OR f1f.dept_to = d.nr)  and f1f.type_encounter = 1
    and DATE_FORMAT(f1f.insurance_start,'%Y-%m-%d') < '".date("Y-m-d",strtotime($datefrom))."' 
    and  DATE_FORMAT(f1f.insurance_exp,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($dateto))."'
    AND DATE_FORMAT(f1f.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1f.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' ) numbhyt,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6  and f9.dept_from = d.nr
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    ) numtuvong,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6  and f9.dept_from = d.nr
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f9.datein,'%Y') - f9.yearbirth) <= 1
    ) numtuvongkid1,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.dept_from = d.nr
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f9.datein,'%Y') - f9.yearbirth) <= 5
    ) numtuvongkid5,
(SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.dept_from = d.nr
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and ( DATE_FORMAT(f9.datein,'%Y') - f9.yearbirth) <= 15
    ) numtuvongkid15,
(
    SELECT COUNT(DISTINCT f9.encounter_nr) 
    FROM dfck_admit_inout_dept f9 
    WHERE f9.dept_to = -6 and f9.dept_from = d.nr
    AND f9.type_encounter = 1
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f9.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'
    and  DATEDIFF(DATE_FORMAT(f9.death_date,'%Y-%m-%d'),
        (select DATE_FORMAT(f9b.datein,'%Y-%m-%d') from dfck_admit_inout_dept f9b

            where f9b.encounter_nr = f9.encounter_nr 
            and f9b.nr < f9.nr and f9b.dept_to = f9.dept_from 
            order by f9b.nr limit 0,1
        ) 
    ) <= 1 
) numtuvong24h , 
(SELECT COUNT(DISTINCT f10.encounter_nr) 
    FROM dfck_admit_inout_dept f10
    WHERE ( ( DATE_FORMAT(f10.datein,'%Y-%m-%d') > '".date("Y-m-d",strtotime($dateto))."' AND f10.dept_from = d.nr 
        AND ( SELECT DATE_FORMAT(f11.datein,'%Y-%m-%d') 
        from dfck_admit_inout_dept f11 
        where f11.encounter_nr = f10.encounter_nr 
        and f11.dept_to = f10.dept_from  and f11.status=1
        order by f11.datein DESC limit 0,1 ) < '".date("Y-m-d",strtotime($dateto))."'
        ) 
    OR (f10.status=0  AND f10.dept_to = d.nr  and DATE_FORMAT(f10.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."'))   
    AND f10.type_encounter = 1
    ) numcuoiky,
(SELECT COUNT(DISTINCT f12.encounter_nr) 
    FROM dfck_admit_inout_dept f12
    WHERE  ( ( DATE_FORMAT(f12.datein,'%Y-%m-%d') > '".date("Y-m-d",strtotime($datefrom))."'  AND f12.dept_from = d.nr 
        AND ( SELECT DATE_FORMAT(f13.datein,'%Y-%m-%d') 
        from dfck_admit_inout_dept f13 
        where f13.encounter_nr = f12.encounter_nr 
        and f13.dept_to = f12.dept_from   and f13.status=1
        order by f13.datein DESC limit 0,1 ) < '".date("Y-m-d",strtotime($datefrom))."'
        ) 
    OR ( f12.status=0  AND f12.dept_to = d.nr and DATE_FORMAT(f12.datein,'%Y-%m-%d') < '".date("Y-m-d",strtotime($datefrom))."' ) ) AND f12.type_encounter = 1
        ) numdauky,
(SELECT 
    SUM(DATEDIFF((SELECT IF(f14.status = 0,'".date("Y-m-d",strtotime($dateto))."' ,(IF( DATE_FORMAT(f15.datein,'%Y-%m-%d')>'".date("Y-m-d",strtotime($dateto))."','".date("Y-m-d",strtotime($dateto))."',DATE_FORMAT(f15.datein,'%Y-%m-%d'))))
        FROM dfck_admit_inout_dept f15 
        WHERE f15.encounter_nr = f14.encounter_nr 
        AND (( f15.dept_from = f14.dept_to AND f15.nr != f14.nr AND f14.status=1) OR (f14.status=0))
        AND DATE_FORMAT(f15.datein,'%Y-%m-%d') >= DATE_FORMAT(f14.datein,'%Y-%m-%d')
        ORDER BY f15.nr ASC LIMIT 0,1
        ), 
        IF( DATE_FORMAT(f14.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."','".date("Y-m-d",strtotime($datefrom))."',DATE_FORMAT(f14.datein,'%Y-%m-%d'))) + 1 )
    FROM dfck_admit_inout_dept f14 
    WHERE  (
    (DATE_FORMAT(f14.datein,'%Y-%m-%d')<='".date("Y-m-d",strtotime($datefrom))."' 
        AND ( (f14.status=0 AND f14.dept_to = d.nr )
            OR ( (SELECT f1b.datein FROM dfck_admit_inout_dept f1b 
                WHERE f1b.encounter_nr=f14.encounter_nr 
                AND f1b.dept_from = f14.dept_to 
                AND f1b.status = 1 ORDER BY f1b.nr DESC LIMIT 0,1)>='".date("Y-m-d",strtotime($datefrom))."' )  ) ) 
    OR (DATE_FORMAT(f14.datein,'%Y-%m-%d')>='".date("Y-m-d",strtotime($datefrom))."' AND DATE_FORMAT(f14.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."')
    )
    AND f14.type_encounter = 1 AND f14.dept_to = d.nr
   ) songaydt,
d.name_formal
FROM care_department d
WHERE d.type = 1
ORDER BY d.nr
";
//echo $sql2;
$content = "";
$s1=$s2=$s3=$s4=$s5=$s6=$s7=$s8=$s9=$s10=$s11=$s12=$s13=$s14=$s15=0;
if($qr = $db->Execute($sql2)){
    if($qr->RecordCount()){
    while($rowf = $qr->FetchRow()){
        $content .= '<tr>
                <td >'.$$rowf['LD_var'].'</td>
                <td align="center">'.$rowf['sumbed'].'</td>
                <td align="center">'.(($rowf['numdauky'])?$rowf['numdauky']:'').'</td>
                <td align="center">'.(($rowf['numvaovien'])?$rowf['numvaovien']:'').'</td>
                <td align="center">'.(($rowf['numvaovienyhct'])?$rowf['numvaovienyhct']:'').'</td>
                <td align="center">'.(($rowf['numvaovienkid6'])?$rowf['numvaovienkid6']:'').'</td>
                <td align="center">'.(($rowf['numvaovienkid15'])?$rowf['numvaovienkid15']:'').'</td>
                <td align="center">'.(($rowf['numvaoviencapcuu'])?$rowf['numvaoviencapcuu']:'').'</td>
                <td align="center">'.(($rowf['songaydt'])?$rowf['songaydt']:'').'</td>
                <td align="center">'.(($rowf['numtuvong'])?$rowf['numtuvong']:'').'</td>
                <td align="center">'.(($rowf['numtuvongkid1'])?$rowf['numtuvongkid1']:'').'</td>
                <td align="center">'.(($rowf['numtuvongkid5'])?$rowf['numtuvongkid5']:'').'</td>
                <td align="center">'.(($rowf['numtuvongkid15'])?$rowf['numtuvongkid15']:'').'</td>
                <td align="center">'.(($rowf['numtuvong24h'])?$rowf['numtuvong24h']:'').'</td>
                <td align="center">'.(($rowf['numbhyt'])?$rowf['numbhyt']:'').'</td>
                <td align="center">'.(($rowf['numcuoiky'])?$rowf['numcuoiky']:'').'</td>                
            </tr>';
            $s1 += $rowf['sumbed'];
            $s2 += $rowf['numdauky'];
            $s3 += $rowf['numvaovien'];
            $s4 += $rowf['numvaovienyhct'];
            $s5 += $rowf['numvaovienkid6'];
            $s6 += $rowf['numvaovienkid15'];
            $s7 += $rowf['numvaoviencapcuu'];
            $s8 += $rowf['songaydt'];
            $s9 += $rowf['numtuvong'];
            $s10 += $rowf['numtuvongkid1'];
            $s11 += $rowf['numtuvongkid5'];
            $s12 += $rowf['numtuvongkid15'];
            $s13 += $rowf['numtuvong24h'];
            $s14 += $rowf['numbhyt'];
            $s15 += $rowf['numcuoiky'];
        }
    }}
$sumtr = '<tr>
                <td >Tổng số</td>
                <td align="center"><b>'.$s1.'</b></td>
                <td align="center"><b>'.$s2.'</b></td>
                <td align="center"><b>'.$s3.'</b></td>
                <td align="center"><b>'.$s4.'</b></td>
                <td align="center"><b>'.$s5.'</b></td>
                <td align="center"><b>'.$s6.'</b></td>
                <td align="center"><b>'.$s7.'</b></td>
                <td align="center"><b>'.$s8.'</b></td>
                <td align="center"><b>'.$s9.'</b></td>
                <td align="center"><b>'.$s10.'</b></td>
                <td align="center"><b>'.$s11.'</b></td>
                <td align="center"><b>'.$s12.'</b></td>
                <td align="center"><b>'.$s13.'</b></td>
                <td align="center"><b>'.$s14.'</b></td>
                <td align="center"><b>'.$s15.'</b></td>
                </tr>';
$header_2 = '<tr>
                <td rowspan="3" align="center" width="20%"><br><br><br>KHOA</td>
                <td rowspan="3" align="center" width="5%">Số<br>giường<br>bệnh</td>
                <td rowspan="3" align="center" width="5%">Số <br>BN<br> đầu<br> kỳ</td>
                <td colspan="5" align="center" width="25%">Người bệnh vào điều trị nội trú</td>
                <td rowspan="3" align="center" width="5%">Số <br>ngày<br>điều<br> trị</td>
                <td colspan="5" align="center" width="25%">Người bệnh tử vong</td>
                <td rowspan="3" align="center" width="5%">BN<br>có <br>thẻ<br>BHYT</td>
                <td rowspan="3" align="center" width="5%">Số BN<br>cuối kỳ</td>
            </tr>
            <tr>
                <td rowspan="2" align="center" width="5%">Tổng<br>số</td>
                <td colspan="4" align="center" width="20%">Trong đó</td>
                <td rowspan="2" align="center" width="5%">Tổng số</td>
                <td colspan="4" align="center" width="20%">Trong đó</td>                  
            </tr>
            <tr>
                <td align="center" width="5%">YHCT</td>
                <td align="center" width="5%">TE dưới<br>6 tuổi</td>
                <td align="center" width="5%">TE dưới<br>15 tuổi</td>
                <td align="center" width="5%">Số cấp cứu</td>
                <td align="center" width="5%">TE dưới<br>1 tuổi</td>
                <td align="center" width="5%">TE dưới<br>5 tuổi</td>
                <td align="center" width="5%">TE dưới<br>15 tuổi</td>
                <td align="center" width="5%">Trước 24 giờ</td>
            </tr>
            <tr>
                <td align="center"></td>
                <td align="center">1</td><td align="center">2</td><td align="center">3</td><td align="center">4</td><td align="center">5</td>
                <td align="center">6</td><td align="center">7</td><td align="center">8</td><td align="center">9</td><td align="center">10</td>
                <td align="center">11</td><td align="center">12</td><td align="center">13</td><td align="center">14</td><td align="center">15</td>                
            </tr>
            ';
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
$tpdf->writeHTML('<table border="1" cellpadding="3">'.$header_2.$sumtr.$trf.$content.'</table><br><br>'.$footer);
$tpdf->Output('B031DT.pdf','I');