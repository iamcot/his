<?php
ob_start();
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
date_default_timezone_set('Asia/Ho_Chi_Minh');

/** Include PHPExcel */
require('./roots.php');
//require_once  '../classes/PHPExcel.php';
require_once ($root_path.'classes/PHPExcel.php');
//include_once  ($root_path.'classes/PHPExcel.php');
require($root_path.'include/core/inc_environment_global.php');
//$local_user='aufnahme_user';
define('LANG_FILE','billing.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

//Test format fromday
if (isset($fromdate) && $fromdate!='' && strpos($fromdate,'-')<3) {
    list($f_day,$f_month,$f_year) = explode("-",$fromdate);
    $fromdate=$f_year.'-'.$f_month.'-'.$f_day;
}
else
    list($f_year,$f_month,$f_day) = explode("-",$fromdate);
switch($select_type){
    case 1: $cond_typeput = ' e.encounter_class_nr=1 '; break;
    case 2: $cond_typeput = ' e.encounter_class_nr=2 '; break;
    default: $cond_typeput = 'e.encounter_class_nr=2 ';
}
if($select_type==1){
    $sql = "SELECT  p.name_last, p.name_first, p.sex,p.insurance_nr AS pinsurance_nr,p.madkbd,p.date_birth,
        e.referrer_diagnosis_code,e.encounter_date, e.encounter_in_date, e.discharge_date, e.current_ward_nr,
        e.current_dept_nr, p.is_traituyen, e.encounter_class_nr, e.benhphu,e. lidovaovien,e.insurance_exp,e.insurance_start,
        t.name AS tp_name,qh.name AS quanhuyen_name,
        px.name AS phuongxa_name, vp.*, tk.* ,tnt.tongthuoc AS tt
        FROM view_vienphi AS vp,view_tongket AS tk,viewthuoc_noitru AS tnt, care_encounter AS e,
        care_person AS p
        LEFT JOIN care_address_citytown AS t ON p.addr_citytown_nr=t.nr
        LEFT JOIN care_address_quanhuyen AS qh ON p.addr_quanhuyen_nr=qh.nr
        LEFT JOIN care_address_phuongxa AS px ON p.addr_phuongxa_nr=px.nr
        LEFT JOIN care_type_ethnic_orig AS dt ON p.ethnic_orig=dt.id
        LEFT JOIN care_encounter_location q ON q.type_nr = 5  AND  q.status=''
        WHERE  e.pid=p.pid AND p.madkbd != '' AND MONTH(e.encounter_date)= MONTH('$fromdate') AND YEAR(e.encounter_date)= YEAR('$fromdate')
        AND ".$cond_typeput." AND vp.bill_item_encounter_nr = e.encounter_nr AND vp.bill_item_encounter_nr = tk.bill_encounter_nr
        AND e.encounter_nr = tk.bill_encounter_nr AND tnt.enc_nr= vp.bill_item_encounter_nr AND tnt.enc_nr= tk.bill_encounter_nr
        AND tnt.enc_nr= e.encounter_nr
        GROUP BY bill_item_encounter_nr";
    $result = $db->Execute($sql);
// Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

// Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
// Create a first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', "stt");
    $objPHPExcel->getActiveSheet()->setCellValue('B1', "hoten");
    $objPHPExcel->getActiveSheet()->setCellValue('C1', "namsinh");
    $objPHPExcel->getActiveSheet()->setCellValue('D1', "gioitinh");
    $objPHPExcel->getActiveSheet()->setCellValue('E1', "mathe");
    $objPHPExcel->getActiveSheet()->setCellValue('F1', "ma_dkbd");
    $objPHPExcel->getActiveSheet()->setCellValue('G1', "mabenh");
    $objPHPExcel->getActiveSheet()->setCellValue('H1', "ngay_vao");
    $objPHPExcel->getActiveSheet()->setCellValue('I1', "ngay_ra");
    $objPHPExcel->getActiveSheet()->setCellValue('J1', "ngaydtr");
    $objPHPExcel->getActiveSheet()->setCellValue('K1', "t_xn");
    $objPHPExcel->getActiveSheet()->setCellValue('L1', "t_cdha");
    $objPHPExcel->getActiveSheet()->setCellValue('M1', "t_thuoc");
    $objPHPExcel->getActiveSheet()->setCellValue('N1', "t_mau");
    $objPHPExcel->getActiveSheet()->setCellValue('O1', "t_pttt");
    $objPHPExcel->getActiveSheet()->setCellValue('P1', "t_vtytth");
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', "t_dvktc");
    $objPHPExcel->getActiveSheet()->setCellValue('R1', "t_ktg");
    $objPHPExcel->getActiveSheet()->setCellValue('S1', "t_kham");
    $objPHPExcel->getActiveSheet()->setCellValue('T1', "t_vchuyen");
    $objPHPExcel->getActiveSheet()->setCellValue('U1', "t-tongchi");
    $objPHPExcel->getActiveSheet()->setCellValue('V1', "t_bnct");
    $objPHPExcel->getActiveSheet()->setCellValue('W1', "t_bhtt");
    $objPHPExcel->getActiveSheet()->setCellValue('X1', "t_ngoaids");
    $objPHPExcel->getActiveSheet()->setCellValue('Y1', "lydo_vv");
    $objPHPExcel->getActiveSheet()->setCellValue('Z1', "benhkhac");
    $objPHPExcel->getActiveSheet()->setCellValue('AA1', "noikcb");
    $objPHPExcel->getActiveSheet()->setCellValue('AB1', "thang_qt");
    $objPHPExcel->getActiveSheet()->setCellValue('AC1', "nam_qt");
    $objPHPExcel->getActiveSheet()->setCellValue('AD1', "gt_tu");
    $objPHPExcel->getActiveSheet()->setCellValue('AE1', "gt_den");
    $objPHPExcel->getActiveSheet()->setCellValue('AF1', "diachi");
    $objPHPExcel->getActiveSheet()->setCellValue('AG1', "giamdinh");
    $objPHPExcel->getActiveSheet()->setCellValue('AH1', "t_xuattoan");
    $objPHPExcel->getActiveSheet()->setCellValue('AI1', "lydo_xt");
    $objPHPExcel->getActiveSheet()->setCellValue('AJ1', "t_datuyen");
    $objPHPExcel->getActiveSheet()->setCellValue('AK1', "t_vuottran");
    $objPHPExcel->getActiveSheet()->setCellValue('AL1', "loaikcb");
    $objPHPExcel->getActiveSheet()->setCellValue('AM1', "noi_ttoan");
    $objPHPExcel->getActiveSheet()->setCellValue('AN1', "sophieu");
    $objPHPExcel->getActiveSheet()->setCellValue('AO1', "ma_khoa");
    $objPHPExcel->getActiveSheet()->setCellValue('AP1', "tuyen");

// Rows to repeat at top
    $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
// Add data
    for ($i = 0; $i< $result->RecordCount(); $i++) {
        $resultsql=$result->FetchRow();
        //xet ngoại trú và nội trú
        if($resultsql['encounter_class_nr']==1){
            $loaikcb ='NỘI';
        }                   else{
            $loaikcb ='NGOẠI';
        }
        //lấy tổng ngày điều trị
        if($resultsql['discharge_date'])
            $datestranfer= formatDate2Local($resultsql['discharge_date'],$date_format,false,false,$sepChars);
        else
            $datestranfer=date('d/m/Y');
        $tongngaydieutri = round(abs(strtotime(formatDate2STD($datestranfer,'dd/mm/yyyy',$sepChars))-strtotime($resultsql['encounter_date']))/86400);

        $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2), ($i+1))
            ->setCellValue('B'.($i+2),$resultsql['name_last'].' '.$resultsql['name_first'] )
            ->setCellValue('C'.($i+2),$resultsql['date_birth'] )
            ->setCellValue('D'.($i+2),$resultsql['sex'] )
            ->setCellValue('E'.($i+2),$resultsql['pinsurance_nr'] )
            ->setCellValue('F'.($i+2),$resultsql['madkbd'] )
            ->setCellValue('G'.($i+2),$resultsql['referrer_diagnosis_code'] )
            ->setCellValue('H'.($i+2),formatDate2Local($resultsql['encounter_date'],$date_format) )
            ->setCellValue('I'.($i+2),$datestranfer )
            ->setCellValue('J'.($i+2), $tongngaydieutri )
            ->setCellValue('K'.($i+2),number_format($resultsql['xetnghiem']) )
            ->setCellValue('L'.($i+2),number_format($resultsql['cdha']) )
            ->setCellValue('M'.($i+2),number_format($resultsql['tt']) )
            ->setCellValue('N'.($i+2),number_format($resultsql['mau']) )
            ->setCellValue('O'.($i+2),number_format($resultsql['ttpt']) )
            ->setCellValue('P'.($i+2),number_format($resultsql['vtyt']) )
            ->setCellValue('Q'.($i+2),$resultsql[''] )
            ->setCellValue('R'.($i+2),number_format($resultsql['giuong']) )      //giuong benh
            ->setCellValue('S'.($i+2),number_format($resultsql['khamchuabenh']) )
            ->setCellValue('T'.($i+2),number_format($resultsql['vanchuyen']) )
            ->setCellValue('U'.($i+2),number_format($resultsql['tongchi']) )
            ->setCellValue('V'.($i+2),number_format($resultsql['bnct']) )
            ->setCellValue('W'.($i+2),number_format($resultsql['bhct']) )
            ->setCellValue('X'.($i+2),$resultsql[''] )
            ->setCellValue('Y'.($i+2),$resultsql['lidovaovien'] )
            ->setCellValue('Z'.($i+2),$resultsql['benhphu'] )
            ->setCellValue('AA'.($i+2),$resultsql[''] )
            ->setCellValue('AB'.($i+2),$f_month )
            ->setCellValue('AC'.($i+2),$f_year )
            ->setCellValue('AD'.($i+2),formatDate2Local($resultsql['insurance_start'],$date_format))
            ->setCellValue('AE'.($i+2),formatDate2Local($resultsql['insurance_exp'],$date_format))
            ->setCellValue('AF'.($i+2),$resultsql['phuongxa_name'].','.$resultsql['quanhuyen_name'].','.$resultsql['tp_name'] )
            ->setCellValue('AG'.($i+2),$resultsql[''] )
            ->setCellValue('AH'.($i+2),$resultsql[''] )
            ->setCellValue('AI'.($i+2),$resultsql[''] )
            ->setCellValue('AJ'.($i+2),$resultsql[''] )
            ->setCellValue('AK'.($i+2),$resultsql[''] )
            ->setCellValue('AL'.($i+2), $loaikcb )
            ->setCellValue('AM'.($i+2),$resultsql[''] )
            ->setCellValue('AN'.($i+2),$resultsql[''] )
            ->setCellValue('AO'.($i+2),$resultsql['current_dept_nr'] )
            ->setCellValue('AP'.($i+2),$resultsql['is_traituyen'] );

    }
}
else{
$sql = "SELECT  p.name_last, p.name_first, p.sex,p.insurance_nr AS pinsurance_nr,p.madkbd,p.date_birth,
        e.referrer_diagnosis_code,e.encounter_date, e.encounter_in_date, e.discharge_date, e.current_ward_nr,
        e.current_dept_nr, p.is_traituyen, e.encounter_class_nr, e.benhphu,e. lidovaovien,e.insurance_exp,e.insurance_start,
        t.name AS tp_name,qh.name AS quanhuyen_name,
        px.name AS phuongxa_name, vp.*, tk.*
        FROM view_vienphi AS vp,view_tongket AS tk, care_encounter AS e,
        care_person AS p
        LEFT JOIN care_address_citytown AS t ON p.addr_citytown_nr=t.nr
        LEFT JOIN care_address_quanhuyen AS qh ON p.addr_quanhuyen_nr=qh.nr
        LEFT JOIN care_address_phuongxa AS px ON p.addr_phuongxa_nr=px.nr
        LEFT JOIN care_type_ethnic_orig AS dt ON p.ethnic_orig=dt.id
        LEFT JOIN care_encounter_location q ON q.type_nr = 5  AND  q.status=''
        WHERE  e.pid=p.pid AND p.madkbd != '' AND MONTH(e.encounter_date)= MONTH('$fromdate') AND YEAR(e.encounter_date)= YEAR('$fromdate')
        AND ".$cond_typeput." AND vp.bill_item_encounter_nr = e.encounter_nr AND vp.bill_item_encounter_nr = tk.bill_encounter_nr
        AND e.encounter_nr = tk.bill_encounter_nr
        GROUP BY bill_item_encounter_nr";
$result = $db->Execute($sql);
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");
// Create a first sheet
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', "stt");
$objPHPExcel->getActiveSheet()->setCellValue('B1', "hoten");
$objPHPExcel->getActiveSheet()->setCellValue('C1', "namsinh");
$objPHPExcel->getActiveSheet()->setCellValue('D1', "gioitinh");
$objPHPExcel->getActiveSheet()->setCellValue('E1', "mathe");
$objPHPExcel->getActiveSheet()->setCellValue('F1', "ma_dkbd");
$objPHPExcel->getActiveSheet()->setCellValue('G1', "mabenh");
$objPHPExcel->getActiveSheet()->setCellValue('H1', "ngay_vao");
$objPHPExcel->getActiveSheet()->setCellValue('I1', "ngay_ra");
$objPHPExcel->getActiveSheet()->setCellValue('J1', "ngaydtr");
$objPHPExcel->getActiveSheet()->setCellValue('K1', "t_xn");
$objPHPExcel->getActiveSheet()->setCellValue('L1', "t_cdha");
$objPHPExcel->getActiveSheet()->setCellValue('M1', "t_thuoc");
$objPHPExcel->getActiveSheet()->setCellValue('N1', "t_mau");
$objPHPExcel->getActiveSheet()->setCellValue('O1', "t_pttt");
$objPHPExcel->getActiveSheet()->setCellValue('P1', "t_vtytth");
$objPHPExcel->getActiveSheet()->setCellValue('Q1', "t_dvktc");
$objPHPExcel->getActiveSheet()->setCellValue('R1', "t_ktg");
$objPHPExcel->getActiveSheet()->setCellValue('S1', "t_kham");
$objPHPExcel->getActiveSheet()->setCellValue('T1', "t_vchuyen");
$objPHPExcel->getActiveSheet()->setCellValue('U1', "t-tongchi");
$objPHPExcel->getActiveSheet()->setCellValue('V1', "t_bnct");
$objPHPExcel->getActiveSheet()->setCellValue('W1', "t_bhtt");
$objPHPExcel->getActiveSheet()->setCellValue('X1', "t_ngoaids");
$objPHPExcel->getActiveSheet()->setCellValue('Y1', "lydo_vv");
$objPHPExcel->getActiveSheet()->setCellValue('Z1', "benhkhac");
$objPHPExcel->getActiveSheet()->setCellValue('AA1', "noikcb");
$objPHPExcel->getActiveSheet()->setCellValue('AB1', "thang_qt");
$objPHPExcel->getActiveSheet()->setCellValue('AC1', "nam_qt");
$objPHPExcel->getActiveSheet()->setCellValue('AD1', "gt_tu");
$objPHPExcel->getActiveSheet()->setCellValue('AE1', "gt_den");
$objPHPExcel->getActiveSheet()->setCellValue('AF1', "diachi");
$objPHPExcel->getActiveSheet()->setCellValue('AG1', "giamdinh");
$objPHPExcel->getActiveSheet()->setCellValue('AH1', "t_xuattoan");
$objPHPExcel->getActiveSheet()->setCellValue('AI1', "lydo_xt");
$objPHPExcel->getActiveSheet()->setCellValue('AJ1', "t_datuyen");
$objPHPExcel->getActiveSheet()->setCellValue('AK1', "t_vuottran");
$objPHPExcel->getActiveSheet()->setCellValue('AL1', "loaikcb");
$objPHPExcel->getActiveSheet()->setCellValue('AM1', "noi_ttoan");
$objPHPExcel->getActiveSheet()->setCellValue('AN1', "sophieu");
$objPHPExcel->getActiveSheet()->setCellValue('AO1', "ma_khoa");
$objPHPExcel->getActiveSheet()->setCellValue('AP1', "tuyen");

// Rows to repeat at top
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
// Add data
for ($i = 0; $i< $result->RecordCount(); $i++) {
    $resultsql=$result->FetchRow();
    //xet ngoại trú và nội trú
    if($resultsql['encounter_class_nr']==1){
        $loaikcb ='NỘI';
    }                   else{
        $loaikcb ='NGOẠI';
    }
    //lấy tổng ngày điều trị
    if($resultsql['discharge_date'])
        $datestranfer= formatDate2Local($resultsql['discharge_date'],$date_format,false,false,$sepChars);
    else
        $datestranfer=date('d/m/Y');
    $tongngaydieutri = round(abs(strtotime(formatDate2STD($datestranfer,'dd/mm/yyyy',$sepChars))-strtotime($resultsql['encounter_date']))/86400);

    $objPHPExcel->getActiveSheet()->setCellValue('A'.($i+2), ($i+1))
        ->setCellValue('B'.($i+2),$resultsql['name_last'].' '.$resultsql['name_first'] )
        ->setCellValue('C'.($i+2),$resultsql['date_birth'] )
        ->setCellValue('D'.($i+2),$resultsql['sex'] )
        ->setCellValue('E'.($i+2),$resultsql['pinsurance_nr'] )
        ->setCellValue('F'.($i+2),$resultsql['madkbd'] )
        ->setCellValue('G'.($i+2),$resultsql['referrer_diagnosis_code'] )
        ->setCellValue('H'.($i+2),formatDate2Local($resultsql['encounter_date'],$date_format) )
        ->setCellValue('I'.($i+2),$datestranfer )
        ->setCellValue('J'.($i+2), $tongngaydieutri )
        ->setCellValue('K'.($i+2),number_format($resultsql['xetnghiem']) )
        ->setCellValue('L'.($i+2),number_format($resultsql['cdha']) )
        ->setCellValue('M'.($i+2),number_format($resultsql['thuoc']))
        ->setCellValue('N'.($i+2),number_format($resultsql['mau']) )
        ->setCellValue('O'.($i+2),number_format($resultsql['ttpt']) )
        ->setCellValue('P'.($i+2),number_format($resultsql['vtyt']) )
        ->setCellValue('Q'.($i+2),$resultsql[''] )
        ->setCellValue('R'.($i+2),$resultsql[''] )
        ->setCellValue('S'.($i+2),number_format($resultsql['khamchuabenh']) )
        ->setCellValue('T'.($i+2),number_format($resultsql['vanchuyen']) )
        ->setCellValue('U'.($i+2),number_format($resultsql['tongchi']) )
        ->setCellValue('V'.($i+2),number_format($resultsql['bnct']) )
        ->setCellValue('W'.($i+2),number_format($resultsql['bhct']) )
        ->setCellValue('X'.($i+2),$resultsql[''] )
        ->setCellValue('Y'.($i+2),$resultsql['lidovaovien'] )
        ->setCellValue('Z'.($i+2),$resultsql['benhphu'] )
        ->setCellValue('AA'.($i+2),$resultsql[''] )
        ->setCellValue('AB'.($i+2),$f_month )
        ->setCellValue('AC'.($i+2),$f_year )
        ->setCellValue('AD'.($i+2),formatDate2Local($resultsql['insurance_start'],$date_format))
        ->setCellValue('AE'.($i+2),formatDate2Local($resultsql['insurance_exp'],$date_format))
        ->setCellValue('AF'.($i+2),$resultsql['phuongxa_name'].','.$resultsql['quanhuyen_name'].','.$resultsql['tp_name'] )
        ->setCellValue('AG'.($i+2),$resultsql[''] )
        ->setCellValue('AH'.($i+2),$resultsql[''] )
        ->setCellValue('AI'.($i+2),$resultsql[''] )
        ->setCellValue('AJ'.($i+2),$resultsql[''] )
        ->setCellValue('AK'.($i+2),$resultsql[''] )
        ->setCellValue('AL'.($i+2), $loaikcb )
        ->setCellValue('AM'.($i+2),$resultsql[''] )
        ->setCellValue('AN'.($i+2),$resultsql[''] )
        ->setCellValue('AO'.($i+2),$resultsql['current_dept_nr'] )
        ->setCellValue('AP'.($i+2),$resultsql['is_traituyen'] );

    }
}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2007 file
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Xuatbaocao_Ex.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_clean();
$objWriter->save('php://output');
exit;
?>
