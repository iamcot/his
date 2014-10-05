<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',"Thống kê khoa Y Học Cổ Truyền");

 $smarty->assign('breakfile',$breakfile);

 //echo $datefrom;
 $str = "<p>".PDF_HOSNAME."</p>
 			<center><B>THỐNG KÊ KHOA KHÁM BỆNH<br>
 		Ngày ".date("d/m/Y",strtotime($datefrom)).(($datefrom != $dateto)?" tới ngày ".date("d/m/Y",strtotime($dateto))."":"")."</B>
 </center>";
 //ma noi bo cua Khoa kham benh la id = 5
 $deptid = 5;
//      $sql="SELECT
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND dept_to>0  ) sumkb,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND insurance_nr != ''
//			AND dept_to>0 ) sumbh,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND loai_kham=1 AND dept_to>0  ) sumknoi,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND loai_kham=1 AND insurance_nr != '' AND dept_to>0  ) sumknoibh,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND loai_kham=2 AND dept_to>0  ) sumkng,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND loai_kham=2 AND insurance_nr != '' AND dept_to>0    ) sumkngbh,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND (DATE_FORMAT(NOW(),'%Y') - yearbirth)<=6 AND dept_to>0  ) sum6t,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND (DATE_FORMAT(NOW(),'%Y') - yearbirth)<=15 AND dept_to>0  ) sum15t,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND (DATE_FORMAT(NOW(),'%Y') - yearbirth)>=60 AND dept_to>0  ) sum60t,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND type_encounter=1 AND dept_to != dept_from AND dept_to>0  ) sumnv,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND (referrer_diagnosis_code LIKE '%A09%' OR referrer_diagnosis LIKE '%A09%')  AND dept_to>0  ) suma09,
// 		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND (referrer_diagnosis_code LIKE 'J10' OR referrer_diagnosis LIKE 'J10%' OR referrer_diagnosis_code LIKE 'J11' OR referrer_diagnosis LIKE 'J11%') AND dept_to>0  ) sumj10,
//		(SELECT COUNT(DISTINCT t.encounter_nr) FROM dfck_admit_inout_dept t,care_encounter e WHERE t.dept_from = 2
// 			AND DATE_FORMAT(t.datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0 AND t.encounter_nr = e.encounter_nr AND e.cbtcinsur != '' ) sumcbtc,
//		(SELECT COUNT(DISTINCT t.encounter_nr) FROM dfck_admit_inout_dept t,care_encounter e WHERE t.dept_from = 2
// 			AND DATE_FORMAT(t.datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0 AND t.encounter_nr = e.encounter_nr AND (e.insurance_nr LIKE 'HN%' OR e.insurance_nr LIKE 'CN%') ) sumhongheo,
//		(SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham !='khac' ) sumksk,
//		(SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='tuyendung') sumksktd,
//		(SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='laixe') sumksklx,
//		(SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='hocsinh') sumkskhs,
//		(SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='khac') sumkskkhac,
//		(SELECT COUNT(DISTINCT encounter_nr) FROM dfck_admit_inout_dept WHERE dept_from = 2
// 			AND DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' AND DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND dept_to = 2) sumchuyenvien"     ;

//$abc="SELECT `t`.`nr`                      AS `nr`,
//  `t`.`encounter_nr`            AS `encounter_nr`,
//  `t`.`pid`                     AS `pid`,
//  `t`.`datein`                  AS `datein`,
//  `t`.`dateout`                 AS `dateout`,
//  `t`.`dept_from`               AS `dept_from`,
//  `t`.`dept_to`                 AS `dept_to`,
//  `t`.`type_encounter`          AS `type_encounter`,
//  `p`.`insurance_nr`            AS `insurance_nr`,
//  SUBSTR(`p`.`date_birth`,1,4)  AS `yearbirth`,
//  `e`.`referrer_diagnosis`      AS `referrer_diagnosis`,
//  `e`.`loai_kham`               AS `loai_kham`,
//  `e`.`referrer_diagnosis_code` AS `referrer_diagnosis_code`
//FROM `dfck_encounter_transfer` `t`, `care_person` `p`, `care_encounter` `e`
//WHERE ";

$sqlsumkb="SELECT COUNT(DISTINCT t.encounter_nr) sumkb
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2 ";
if($rs=$db->Execute($sqlsumkb)){
    while($row=$rs->FetchRow())
    $sumkb=$row['sumkb'];
}

$sqlsumbh="SELECT COUNT(DISTINCT t.encounter_nr) sumbh
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'  AND  DATE_FORMAT(t.insurance_exp,'%Y-%m-%d') > '$dateto'
		AND t.dept_to>0 AND t.dept_from = 2	AND p.insurance_nr != ''";
if($rs=$db->Execute($sqlsumbh)){
    while($row=$rs->FetchRow())
        $sumbh=$row['sumbh'];
}

$sqlsumknoi="SELECT COUNT(DISTINCT t.encounter_nr) sumknoi
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2	AND e.loai_kham=1";
if($rs=$db->Execute($sqlsumknoi)){
    while($row=$rs->FetchRow())
        $sumknoi=$row['sumknoi'];
}

$sqlsumknoibh="SELECT COUNT(DISTINCT t.encounter_nr) sumknoibh
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND  DATE_FORMAT(t.insurance_exp,'%Y-%m-%d') > '$dateto'
		AND t.dept_to>0 AND t.dept_from = 2	AND e.loai_kham=1 AND p.insurance_nr != '' ";
if($rs=$db->Execute($sqlsumknoibh)){
    while($row=$rs->FetchRow())
        $sumknoibh=$row['sumknoibh'];
}

$sqlsumkng="SELECT COUNT(DISTINCT t.encounter_nr) sumkng
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
AND t.dept_from = 2	AND e.loai_kham=2";
if($rs=$db->Execute($sqlsumkng)){
    while($row=$rs->FetchRow())
        $sumkng=$row['sumkng'];
}

$sqlsumkngbh="SELECT COUNT(DISTINCT t.encounter_nr) sumkngbh
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'     AND  DATE_FORMAT(t.insurance_exp,'%Y-%m-%d') > '$dateto'
		AND t.dept_to>0   AND t.dept_from = 2	AND e.loai_kham=2 AND p.insurance_nr != '' ";
if($rs=$db->Execute($sqlsumkngbh)){
    while($row=$rs->FetchRow())
        $sumkngbh=$row['sumkngbh'];
}

$sqlsum6t="SELECT COUNT(DISTINCT t.encounter_nr)  sum6t
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2 AND DATE_FORMAT(NOW(),'%Y') - SUBSTR(`p`.`date_birth`,1,4)<=6";
if($rs=$db->Execute($sqlsum6t)){
    while($row=$rs->FetchRow())
        $sum6t=$row['sum6t'];
}

$sqlsum15t="SELECT COUNT(DISTINCT t.encounter_nr)  sum15t
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2 AND DATE_FORMAT(NOW(),'%Y') - SUBSTR(`p`.`date_birth`,1,4)<=15";
if($rs=$db->Execute($sqlsum15t)){
    while($row=$rs->FetchRow())
        $sum15t=$row['sum15t'];
}

$sqlsum60t="SELECT COUNT(DISTINCT t.encounter_nr)  sum60t
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2 AND DATE_FORMAT(NOW(),'%Y') - SUBSTR(`p`.`date_birth`,1,4)<=60";
if($rs=$db->Execute($sqlsum60t)){
    while($row=$rs->FetchRow())
        $sum60t=$row['sum60t'];
}

$sqlsumnv="SELECT COUNT(DISTINCT t.encounter_nr) sumnv
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2	AND `t`.`type_encounter`=1 AND `t`.`dept_to`!= `t`.`dept_from`";
if($rs=$db->Execute($sqlsumnv)){
    while($row=$rs->FetchRow())
        $sumnv=$row['sumnv'];
}

$sqlsuma09="SELECT COUNT(DISTINCT t.encounter_nr) suma09
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2	AND (e.referrer_diagnosis_code LIKE '%A09%' OR e.referrer_diagnosis LIKE '%A09%')";
if($rs=$db->Execute($sqlsuma09)){
    while($row=$rs->FetchRow())
        $suma09=$row['suma09'];
}

$sqlsumj10="SELECT COUNT(DISTINCT t.encounter_nr) sumj10
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from = 2	AND (e.referrer_diagnosis_code LIKE 'J10' OR e.referrer_diagnosis LIKE 'J10%' OR e.referrer_diagnosis_code LIKE 'J11' OR e.referrer_diagnosis LIKE 'J11%')";
if($rs=$db->Execute($sqlsumj10)){
    while($row=$rs->FetchRow())
        $sumj10=$row['sumj10'];
}

$sqlsumcbtc="SELECT COUNT(DISTINCT t.encounter_nr) sumcbtc
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'
		AND t.dept_from = 2	AND t.dept_to>0 AND t.encounter_nr = e.encounter_nr AND e.cbtcinsur != '' ";
if($rs=$db->Execute($sqlsumcbtc)){
    while($row=$rs->FetchRow())
        $sumcbtc=$row['sumcbtc'];
}

$sqlsumhongheo="SELECT COUNT(DISTINCT t.encounter_nr) sumhongheo
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'
		AND t.dept_from = 2	AND t.dept_to>0 AND t.encounter_nr = e.encounter_nr AND (e.insurance_nr LIKE 'HN%' OR e.insurance_nr LIKE 'CN%') ";
if($rs=$db->Execute($sqlsumhongheo)){
    while($row=$rs->FetchRow())
        $sumhongheo=$row['sumhongheo'];
}

$sqlsumksk=" SELECT COUNT(nr) sumksk FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham !='khac' ";
if($rs=$db->Execute($sqlsumksk)){
    while($row=$rs->FetchRow())
        $sumksk=$row['sumksk'];
}

$sqlsumksktd ="SELECT COUNT(nr) sumksktd FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='tuyendung'";
if($rs=$db->Execute($sqlsumksktd)){
    while($row=$rs->FetchRow())
        $sumksktd=$row['sumksktd'];
}

$sqlsumksklx="SELECT COUNT(nr) sumksklx FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='laixe'";
if($rs=$db->Execute($sqlsumksklx)){
    while($row=$rs->FetchRow())
        $sumksklx=$row['sumksklx'];
}

$sqlsumkskhs="SELECT COUNT(nr) sumkskhs FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='hocsinh";
if($rs=$db->Execute($sqlsumkskhs)){
    while($row=$rs->FetchRow())
        $sumkskhs=$row['sumkskhs'];
}
$sqlsumkskkhac="SELECT COUNT(nr) FROM care_kham_suc_khoe WHERE date_kham >='$datefrom' AND date_kham <='$dateto' AND mucdichkham='khac'";
if($rs=$db->Execute($sqlsumkskkhac)){
    while($row=$rs->FetchRow())
        $sumkskkhac=$row['sumkskkhac'];
}

$sqlsumcv="SELECT COUNT(DISTINCT t.encounter_nr) sumcv
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to = 2
AND t.dept_from = 2 ";
if($rs=$db->Execute($sqlsumcv)){
    while($row=$rs->FetchRow())
        $sumcv=$row['sumcv'];
}
// global $db;
// if($rs = $db->Execute($sql)){
//     $row=null;
// 	if($row = $rs->FetchRow()){

 		$str .= '<br>
 		<ul>
 			<li>Tổng: <b>'.$sumkb.'</b>
 			<ul>
 					<li>BHYT: <b>'.$sumbh.'</b></li>
 					<li>Không BHYT: <b>'.($sumkb - $sumbh).'</b></li>
 				</ul>
 			</li>
 			<li>
 				<table>
 				<tr><td width="50%"></td><td width="25%">BHYT</td><td width="25%">Không BHYT</td></tr>
 				<tr><td>Khám nội: <b>'.$sumknoi.'</b></td><td><b>'.$sumknoibh.'</td><td><b>'.($sumknoi-$sumknoibh).'</td></tr>
 				<tr><td>Khám ngoại: <b>'.$sumkng.'</b></td><td><b>'.$sumkngbh.'</td><td><b>'.($sumkng-$sumkngbh).'</td></tr>
 				</table>
 			</li>
 			<li>Khám&nbsp;Trẻ em (<6T): <b>'.$sum6t.'</b></li>
 			<li>Khám&nbsp;&nbsp;&nbsp;&nbsp;nhi (<15T): <b>'.$sum15t.'</b></li>
 			<li>Người&nbsp;&nbsp;&nbsp;già (>60T): <b>'.$sum60t.'</b></li>
 			<li>Nhập viện: <b>'.$sumnv.'</b>
 				<ul>';
 			if($sumnv > 0){
 				$sql="SELECT
 					d.name_formal, d.LD_var,(select count(distinct e.encounter_nr) from dfck_admit_inout_dept e where e.dept_from = (select nr from care_department where id=$deptid) and e.dept_to = d.nr and e.type_encounter=1 and DATE_FORMAT(e.datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(e.datein,'%Y-%m-%d') <='$dateto') sumnv
 				FROM care_department d where d.type=1 and d.id!=5";
 				if($rs2 = $db->Execute($sql)){
 					while($row2 = $rs2->FetchRow()){
 						$str.= '<li>'.(($$row2['LD_var'])?$$row2['LD_var']:$row2['name_formal']).': <b>'.$row2['sumnv'].'</b></li>';
 					}
 				}
 			}
 			$str.='</ul>
 			</li>
 			<li>Cúm : <b>'.$sumj10.'</b>    Tiêu chảy : <b>'.$suma09.'</b></li>
 			<li>Cán bộ trung cao: <b>'.$sumcbtc.'</b></li>
 			<li>Hộ nghèo: <b>'.$sumhongheo.'</b></li>
 			<li>Khám sức khỏe: <b>'.$sumksk.'</b>
 				<ul>
 					<li>Tuyển dụng: <b>'.$sumksktd.'</b></li>
 					<li>Lái xe: <b>'.$sumksklx.'</b></li>
 					<li>Học sinh: <b>'.$sumkskhs.'</b></li>
 				</ul>
 			</li>
 			<li>Cấp giấy chuyển viện: <b>'.$sumcv.'</b></li>
 			<li>Cấp giấy nghỉ ốm: <b>'.$sumkskkhac.'</b></li>
 		</ul>
 		<br>
 		<table width="90%">
 		<tr><td><b>Trưởng khoa</b></td><td>'.'Ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'<br><b>Người báo cáo</b></td></tr>
 		</table>';
// 	}
// }
 ?>
<meta charset="utf-8">
<style>
table{
	border-collapse: collapse;
}
td{
	text-align: center;
}
thead tr{
	background: #dadada;
}
</style>

<?
echo  $str;
?>