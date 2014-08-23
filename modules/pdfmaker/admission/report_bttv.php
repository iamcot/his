<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/core/inc_environment_global.php');
define('NO_CHAIN', 1);
$local_user = 'aufnahme_user';
require($root_path . 'include/core/inc_front_chain_lang.php');
define('MAX_ROW_PP', 44); //size 8
define('WIDTH_BT', 30); //size 8
//$daydiff = date_diff(new DateTime(date("Y-m-d",strtotime($dateto))),new DateTime(date("Y-m-d",strtotime($datefrom))));
class DateDiff
{
    var $d, $m, $y;

    function dateDifference($startDate, $endDate)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        //var_dump($startDate.'@@'.$endDate);
        if ($startDate === false || $startDate < 0 || $endDate === false || $endDate < 0 || $startDate > $endDate)
            return false;

        $years = date('Y', $endDate) - date('Y', $startDate);

        $endMonth = date('m', $endDate);
        $startMonth = date('m', $startDate);

        // Calculate months
        $months = $endMonth - $startMonth;
        if ($months <= 0 && $years > 0) {
            $months += 12;
            $years--;
        }
        if ($years < 0)
            return false;

        // Calculate the days
        $offsets = array();
        if ($years > 0)
            $offsets[] = $years . (($years == 1) ? ' year' : ' years');
        if ($months > 0)
            $offsets[] = $months . (($months == 1) ? ' month' : ' months');
        $offsets = count($offsets) > 0 ? '+' . implode(' ', $offsets) : 'now';

        $days = $endDate - strtotime($offsets, $startDate);
        $days = date('z', $days);

        $this->d = $days;
        $this->m = $months;
        $this->y = $years;
    }
}

$daydiff = new DateDiff();
$daydiff->dateDifference($datefrom, $dateto);
//var_dump($daydiff);
$strdatebc = "";
$strshortdate = "";
//var_dump($daydiff);
if ($daydiff->d == 0 && $daydiff->m == 0 && $daydiff->y == 0) //trong ngay
{
    $strdatebc = "BÁO CÁO THỐNG KÊ NGÀY " . date("d/m/Y", strtotime($datefrom));
    $strshortdate = "Ngay_" . date("d-m-Y", strtotime($datefrom));
} else if ((($daydiff->d > 25 && $daydiff->m == 2) || $daydiff->m == 3) && $daydiff->y == 0) {
    $strdatebc = "BÁO CÁO THỐNG KÊ 03 THÁNG NĂM " . date("Y", strtotime($datefrom));
    $strshortdate = "3_thang";
} else if ((($daydiff->d > 25 && $daydiff->m == 5) || $daydiff->m == 6) && $daydiff->y == 0) {
    $strdatebc = "BÁO CÁO THỐNG KÊ 06 THÁNG NĂM " . date("Y", strtotime($datefrom));
    $strshortdate = "6_thang";
} else if ((($daydiff->d > 25 && $daydiff->m == 8) || $daydiff->m == 9) && $daydiff->y == 0) {
    $strdatebc = "BÁO CÁO THỐNG KÊ 09 THÁNG NĂM " . date("Y", strtotime($datefrom));
    $strshortdate = "9_thang";
} else if (($daydiff->m >= 11 && $daydiff->y == 0) || $daydiff->y == 1) {
    $strdatebc = "BÁO CÁO THỐNG KÊ NĂM " . date("Y", strtotime($datefrom));
    $strshortdate = "Nam_" . date("Y", strtotime($datefrom));
} else { //khoang ngay
    $strdatebc = "BÁO CÁO THỐNG KÊ NGÀY " . date("d/m/Y", strtotime($datefrom)) . " - ĐẾN NGÀY " . date("d/m/Y", strtotime($dateto));
    $strshortdate = "Ngay_" . date("d-m-Y", strtotime($datefrom)) . "_" . date("d-m-Y", strtotime($dateto));
}
//echo $strdatebc;

$classpathFPDF = $root_path . 'classes/fpdf/';
$fontpathFPDF = $classpathFPDF . 'font/unifont/';
//define("_SYSTEM_TTFONTS",$fontpathFPDF);
require_once($root_path . 'classes/tcpdf/config/lang/eng.php');
require_once($root_path . 'classes/tcpdf/tcpdf.php');
include($classpathFPDF . 'tfpdf.php');
$tpdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

$tpdf->SetTitle($strdatebc);
$tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$tpdf->SetMargins(5, 8, 3);
// remove default header/footer
$tpdf->setPrintHeader(false);
$tpdf->setPrintFooter(false);

//set auto page breaks
$tpdf->SetAutoPageBreak(FALSE);
$tpdf->AddPage('L', 'A4');
$tpdf->SetFont('dejavusans', '', 10);


$header_1 = '<table  >
                <tr>
                    <td width="30%">SỞ Y TẾ BÌNH DƯƠNG<br>' . PDF_HOSNAME . '</td>
                    <td align="center" width="50%">
                    	<b><font size="15">TÌNH HÌNH BỆNH TẬT TỬ VONG</font></b><br><br>
                        <i>(' . $strdatebc . ')</i>
                    </td>
                    <td align="right" width="18%">Biểu 15-BCH</td>
                </tr>
            </table>';
$tpdf->writeHTML($header_1);
$tpdf->SetFont('dejavusans', '', 8);
$header2 = '
   <tr>
	   <td rowspan="4" align="center" width="3%"><br><br><br><b>Số<br>TT</b></td>
	   <td rowspan="4" align="center" width="' . WIDTH_BT . '%"><br><br><br><br><b>TÊN BỆNH</b></td>
	   <td rowspan="4" align="center" width="6%"><br><br><br><b>Mã<br>ICD<br>10</b></td>
	   <td colspan="4" rowspan="2" align="center" width="20%"><br><br><b>Tại khoa khám bệnh</b></td>
	   <td colspan="8" align="center" width="40%"><b>Điều trị nội trú</b></td>
   </tr>
   <tr>
	   <td colspan="4" align="center" width="20%"><b>Tổng số</b></td>
	   <td colspan="4" align="center" width="20%"><b>Trong đó TE dưới 15 tuổi</b></td>
   </tr>
   <tr>
	   	<td rowspan="2" align="center" width="5%"><br><br><b>Tổng số</b></td>
	   	<td colspan="3" align="center"  width="15%"><b>Trong đó</b></td>
	   	<td colspan="2" align="center"  width="10%"><b>Mắc</b></td>
	   	<td colspan="2" align="center"  width="10%"><b>Tử vong</b></td>
	   	<td colspan="2" align="center"  width="10%"><b>Mắc</b></td>
	   	<td colspan="2" align="center" width="10%"><b>Tử vong</b></td>
   </tr>
   <tr>
	    <td  align="center" width="5%"><b>Nữ</b></td>
	    <td  align="center" width="5%"><b>TE dưới 15 tuổi</b></td>
	    <td  align="center" width="5%"><b>Tử vong</b></td>
	    <td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Nữ</b></td>
		<td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Nữ</b></td>
		<td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Dưới 5 Tuổi</b></td>
		<td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Dưới 5 Tuổi</b></td>
   </tr>';
//$tpdf->writeHTML($header2);
$header3 = '<tr>
	    <td align="center"  width="3%">VN</td>
	    <td align="center" width="' . WIDTH_BT . '%"></td>
	    <td align="center" width="6%">QT</td>
	    <td align="center" width="5%">4</td><td align="center" width="5%">5</td><td align="center" width="5%">6</td><td align="center" width="5%">7</td>
	    <td align="center" width="5%">8</td><td align="center" width="5%">9</td><td align="center" width="5%">10</td><td align="center" width="5%">11</td>
	    <td align="center" width="5%">12</td><td align="center" width="5%">13</td><td align="center" width="5%">14</td><td align="center" width="5%">15</td>
    </tr>';
//$tpdf->writeHTML($header2);

//content
$content = '';
global $db;
//				(SELECT COUNT(v4.encounter_nr) FROM dfck_bttv_view v4
//					WHERE v4.vncode = v.vncode AND v4.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)
//					AND DATE_FORMAT(v4.death_date,'%Y-%m-%d')>= '".date('Y-m-d',strtotime($datefrom))."'
//					AND DATE_FORMAT(v4.death_date,'%Y-%m-%d')<= '".date('Y-m-d',strtotime($dateto))."'
//					 AND v4.encounter_class_nr = 2) sumdead,
//
//
//				(SELECT COUNT(v8.encounter_nr) FROM dfck_bttv_view v8 WHERE v8.vncode = v.vncode AND v8.encounter_class_nr = 1
//					AND DATE_FORMAT(v8.death_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."'
//					AND DATE_FORMAT(v8.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."') sumpaindead,
//				(SELECT COUNT(v9.encounter_nr) FROM dfck_bttv_view v9 WHERE v9.vncode = v.vncode AND v9.encounter_class_nr = 1
//					AND DATE_FORMAT(v9.death_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."'
//					AND DATE_FORMAT(v9.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' AND v9.sex='f') sumpaindeadf,
//
//
//				(SELECT COUNT(v12.encounter_nr) FROM dfck_bttv_view v12 WHERE v12.vncode = v.vncode AND v12.encounter_class_nr = 1
//					AND ((DATE_FORMAT(NOW(),'%Y') - SUBSTR(v12.birthyear,1,4)) < 15 )
//					AND DATE_FORMAT(v12.death_date,'%Y-%m-%d')>= ".date("Y-m-d",strtotime($datefrom))."
//					AND DATE_FORMAT(v12.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."') sumpainkiddead,
//				(SELECT COUNT(v13.encounter_nr) FROM dfck_bttv_view v13 WHERE v13.vncode = v.vncode AND v13.encounter_class_nr = 1
//					AND ((DATE_FORMAT(NOW(),'%Y') - SUBSTR(v13.birthyear,1,4)) < 5 )
//					AND DATE_FORMAT(v13.death_date,'%Y-%m-%d')>= ".date("Y-m-d",strtotime($datefrom))."
//					AND DATE_FORMAT(v13.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' ) sumpainkiddead5

$sqlsumkkb = "SELECT bt.vncode, COUNT(e.encounter_nr) sumkkb FROM `care_encounter` `e`
                                LEFT JOIN `dfck_icd10_group_bttv` `bt` ON `bt`.`icd10detail` LIKE CONCAT('%',`e`.`referrer_diagnosis_code`,'%')
                                AND `e`.`referrer_diagnosis_code` <> _utf8''
                                LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
                                WHERE bt.vncode!='' AND bt.vncode!='NULL' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')>= '" . date("Y-m-d", strtotime($datefrom)) . "' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')<= '" . date("Y-m-d", strtotime($dateto)) . "' AND
                                e.encounter_class_nr = 2 AND e.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)
                                GROUP BY bt.vncode  ORDER BY `bt`.`vncode`";

$arrsumkkb = array();
if ($rs = $db->Execute($sqlsumkkb)) {
    while ($row = $rs->FetchRow()) {
        $arrsumkkb[($row['vncode'])] = $row['sumkkb'];
    }
}
$sqlkkbf = "SELECT bt.vncode, COUNT(e.encounter_nr) kkbf FROM `care_encounter` `e`
                                LEFT JOIN `dfck_icd10_group_bttv` `bt` ON `bt`.`icd10detail` LIKE CONCAT('%',`e`.`referrer_diagnosis_code`,'%')
                                AND `e`.`referrer_diagnosis_code` <> _utf8''
                                LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
                                WHERE bt.vncode!='' AND bt.vncode!='NULL' AND
                                p.sex ='f' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')>= '" . date("Y-m-d", strtotime($datefrom)) . "' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')<= '" . date("Y-m-d", strtotime($dateto)) . "' AND
                                e.encounter_class_nr = 2 AND e.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)
                                GROUP BY bt.vncode  ORDER BY `bt`.`vncode`";

$arrkkbf = array();
if ($rs = $db->Execute($sqlkkbf)) {
    while ($row = $rs->FetchRow()) {
        $arrkkbf[($row['vncode'])] = $row['kkbf'];
    }
}
$sqlkkbkid = "SELECT bt.vncode, COUNT(e.encounter_nr) kkbkid FROM `care_encounter` `e`
                                LEFT JOIN `dfck_icd10_group_bttv` `bt` ON `bt`.`icd10detail` LIKE CONCAT('%',`e`.`referrer_diagnosis_code`,'%')
                                AND `e`.`referrer_diagnosis_code` <> _utf8''
                                LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
                                WHERE bt.vncode!='' AND bt.vncode!='NULL' AND
                               (DATE_FORMAT(NOW(),'%Y') - SUBSTR(p.date_birth,1,4)) < 15  AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')>= '" . date("Y-m-d", strtotime($datefrom)) . "' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')<= '" . date("Y-m-d", strtotime($dateto)) . "' AND
                                e.encounter_class_nr = 2 AND e.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)
                                GROUP BY bt.vncode  ORDER BY `bt`.`vncode`";

$arrkkbkid = array();
if ($rs = $db->Execute($sqlkkbkid)) {
    while ($row = $rs->FetchRow()) {
        $arrkkbkid[($row['vncode'])] = $row['kkbkid'];
    }
}
$sqlntsum = "SELECT bt.vncode, COUNT(e.encounter_nr) ntsum FROM `care_encounter` `e`
                                LEFT JOIN `dfck_icd10_group_bttv` `bt` ON `bt`.`icd10detail` LIKE CONCAT('%',`e`.`referrer_diagnosis_code`,'%')
                                AND `e`.`referrer_diagnosis_code` <> _utf8''
                                LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
                                WHERE bt.vncode!='' AND bt.vncode!='NULL' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')>= '" . date("Y-m-d", strtotime($datefrom)) . "' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')<= '" . date("Y-m-d", strtotime($dateto)) . "' AND
                                e.encounter_class_nr = 1
                                GROUP BY bt.vncode  ORDER BY `bt`.`vncode`";

$arrntsum = array();
if ($rs = $db->Execute($sqlntsum)) {
    while ($row = $rs->FetchRow()) {
        $arrntsum[($row['vncode'])] = $row['ntsum'];
    }
}
$sqlntf = "SELECT bt.vncode, COUNT(e.encounter_nr) ntf FROM `care_encounter` `e`
                                LEFT JOIN `dfck_icd10_group_bttv` `bt` ON `bt`.`icd10detail` LIKE CONCAT('%',`e`.`referrer_diagnosis_code`,'%')
                                AND `e`.`referrer_diagnosis_code` <> _utf8''
                                LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
                                WHERE bt.vncode!='' AND bt.vncode!='NULL' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')>= '" . date("Y-m-d", strtotime($datefrom)) . "' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')<= '" . date("Y-m-d", strtotime($dateto)) . "' AND
                                e.encounter_class_nr = 1 AND p.sex = 'f'
                                GROUP BY bt.vncode  ORDER BY `bt`.`vncode`";

$arrntf = array();
if ($rs = $db->Execute($sqlntf)) {
    while ($row = $rs->FetchRow()) {
        $arrntf[($row['vncode'])] = $row['ntf'];
    }
}
$sqlntsumkid = "SELECT bt.vncode, COUNT(e.encounter_nr) ntsumkid FROM `care_encounter` `e`
                                LEFT JOIN `dfck_icd10_group_bttv` `bt` ON `bt`.`icd10detail` LIKE CONCAT('%',`e`.`referrer_diagnosis_code`,'%')
                                AND `e`.`referrer_diagnosis_code` <> _utf8''
                                LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
                                WHERE bt.vncode!='' AND bt.vncode!='NULL' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')>= '" . date("Y-m-d", strtotime($datefrom)) . "' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')<= '" . date("Y-m-d", strtotime($dateto)) . "' AND
                                e.encounter_class_nr = 1 AND (DATE_FORMAT(NOW(),'%Y') - SUBSTR(p.date_birth,1,4)) < 15
                                GROUP BY bt.vncode  ORDER BY `bt`.`vncode`";

$arrntsumkid = array();
if ($rs = $db->Execute($sqlntsumkid)) {
    while ($row = $rs->FetchRow()) {
        $arrntsumkid[($row['vncode'])] = $row['ntsumkid'];
    }
}
$sqlntsumkid5 = "SELECT bt.vncode, COUNT(e.encounter_nr) ntsumkid5 FROM `care_encounter` `e`
                                LEFT JOIN `dfck_icd10_group_bttv` `bt` ON `bt`.`icd10detail` LIKE CONCAT('%',`e`.`referrer_diagnosis_code`,'%')
                                AND `e`.`referrer_diagnosis_code` <> _utf8''
                                LEFT JOIN `care_person` `p` ON `e`.`pid` = `p`.`pid`
                                WHERE bt.vncode!='' AND bt.vncode!='NULL' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')>= '" . date("Y-m-d", strtotime($datefrom)) . "' AND
                                DATE_FORMAT(e.encounter_date,'%Y-%m-%d')<= '" . date("Y-m-d", strtotime($dateto)) . "' AND
                                e.encounter_class_nr = 1 AND (DATE_FORMAT(NOW(),'%Y') - SUBSTR(p.date_birth,1,4)) < 5
                                GROUP BY bt.vncode  ORDER BY `bt`.`vncode`";

$arrntsumkid5 = array();
if ($rs = $db->Execute($sqlntsumkid5)) {
    while ($row = $rs->FetchRow()) {
        $arrntsumkid5[($row['vncode'])] = $row['ntsumkid5'];
    }
}
$crrsec = "";
$arrsection = array();
$sql = "SELECT s.*,g.vncode groupcode,
        g.info,
        g.icd10,
        g.icd10more FROM dfck_icd10_vi_section s
        JOIN dfck_icd10_group_bttv g ON g.sname = s.shortname
        ORDER BY
        s.shortname, g.vncode";
if ($rs = $db->Execute($sql)) {
    if ($rs->RecordCount()) {
        $numline = 1;
        $nowpage = 1;
        if ($nowpage == 1) $numline = 12;
        while ($row = $rs->FetchRow()) {
            if (!isset($arrsumkkb[($row['groupcode'])]) && !isset($arrntsum[($row['groupcode'])])) continue;
            //xu li dong dau tien cua chuong
            if ($crrsec != $row['vncode']) {
                $crrsec = $row['vncode'];
                $description = 'Chương ' . $row['shortname'] . ': ' . $row['info'] . '<br>Chapter ' . $row['shortname'] . ': ' . $row['info_en'];
                //xu li phan trang
                $column_width = 270 * WIDTH_BT / 100; //mm
                $num_thisline = (ceil($tpdf->GetStringWidth($description) / ($column_width))) + 1;
                $numline += $num_thisline;
                if ($numline >= MAX_ROW_PP) {
                    if ($nowpage == 1) $tpdf->writeHTML('<table border="1"  cellpadding="3">' . $header2 . $header3 . $content . '</table><div align="right">Trang ' . $nowpage . '</div>');
                    else $tpdf->writeHTML('<table border="1"  cellpadding="3">' . $header3 . $content . '</table><div align="right">Trang ' . $nowpage . '</div>');
                    $tpdf->AddPage(); // page break.
                    $content = "";
                    $numline = $num_thisline;
                    $nowpage++;
                }
                $content .= '<tr>
							<td align="center"><b>' . $row['vncode'] . '</b></td>
							<td><b>' . $description . '</b></td>
							<td align="center"><b>' . $row['cbegin'] . ' - ' . $row['cend'] . '</b></td>
							<td></td><td></td>
							<td></td><td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td><td></td>
						</tr>';

            }
            //xu li phan trang
            $description = $row['info'] . (($row['icd10more'] != '') ? ' (' . $row['icd10more'] . ') ' : '');
            $column_width = 270 * WIDTH_BT / 110; //mm
            $num_thisline = ceil($tpdf->GetStringWidth($description) / ($column_width)) ;
            $numline += $num_thisline;
            if ($numline >= MAX_ROW_PP) {
                if ($nowpage == 1) $tpdf->writeHTML('<table border="1"  cellpadding="3">' . $header2 . $header3 . $content . '</table><div align="right">Trang ' . $nowpage . '</div>');
                else $tpdf->writeHTML('<table border="1"  cellpadding="3">' . $header3 . $content . '</table><div align="right">Trang ' . $nowpage . '</div>');
                $tpdf->AddPage(); // page break.
                $content = "";
                $numline = $num_thisline;
                $nowpage++;
            }
            //xu li noi dung
            $content .= '<tr>
						<td align="center">' . $row['groupcode'] . '</td>
						<td> ' . $description . '</td>
						<td align="center">' . $row['icd10'] . '</td>
						<td align="center">' . ((isset($arrsumkkb[($row['groupcode'])]) && ($id == 'kkb' || $id == 'all')) ? $arrsumkkb[($row['groupcode'])] : '') . '</td>
						<td align="center">' . ((isset($arrkkbf[($row['groupcode'])]) && ($id == 'kkb' || $id == 'all')) ? $arrkkbf[($row['groupcode'])] : '') . '</td>
						<td align="center">' . ((isset($arrkkbkid[($row['groupcode'])]) && ($id == 'kkb' || $id == 'all')) ? $arrkkbkid[($row['groupcode'])] : '') . '</td>
						<td align="center">' . (($row['sumdead'] > 0 && ($id == 'kkb' || $id == 'all')) ? $row['sumdead'] : '') . '</td>
						<td align="center">' . ((isset($arrntsum[($row['groupcode'])]) && ($id == 'dtnt' || $id == 'all')) ? $arrntsum[($row['groupcode'])] : '') . '</td>
						<td align="center">' . ((isset($arrntf[($row['groupcode'])]) && ($id == 'dtnt' || $id == 'all')) ? $arrntf[($row['groupcode'])] : '') . '</td>
						<td align="center">' . (($row['sumpaindead'] > 0 && ($id == 'dtnt' || $id == 'all')) ? $row['sumpaindead'] : '') . '</td>
						<td align="center">' . (($row['sumpaindeadf'] > 0 && ($id == 'dtnt' || $id == 'all')) ? $row['sumpaindeadf'] : '') . '</td>
						<td align="center">' . ((isset($arrntsumkid[($row['groupcode'])]) && ($id == 'dtnt' || $id == 'all')) ? $arrntsumkid[($row['groupcode'])] : '') . '</td>
						<td align="center">' . ((isset($arrntsumkid5[($row['groupcode'])]) && ($id == 'dtnt' || $id == 'all')) ? $arrntsumkid5[($row['groupcode'])] : '') . '</td>
						<td align="center">' . (($row['sumpainkiddead'] > 0 && ($id == 'dtnt' || $id == 'all')) ? $row['sumpainkiddead'] : '') . '</td>
						<td align="center">' . (($row['sumpainkiddead5'] > 0 && ($id == 'dtnt' || $id == 'all')) ? $row['sumpainkiddead5'] : '') . '</td>
					</tr>';
        }
    }
}

if ($nowpage == 1) $tpdf->writeHTML('<table border="1"  cellpadding="3">' . $header2 . $header3 . $content . '</table><div align="right">Trang ' . $nowpage . '</div>');
else $tpdf->writeHTML('<table border="1"  cellpadding="3">' . $header3 . $content . '</table>');
$tpdf->SetFont('dejavusans', '', 10);
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
if (($numline + 8) >= MAX_ROW_PP) {
    $tpdf->writeHTML($header3 . $content . '</table><div align="right">Trang ' . $nowpage . '</div>');
    $tpdf->AddPage(); // page break.
    //$content = "";
    //$numline = 1;
    //$nowpage ++;
}
$tpdf->writeHTML($footer);

$tpdf->Output($id . '_bttv_' . $strshortdate . '.pdf', 'I');