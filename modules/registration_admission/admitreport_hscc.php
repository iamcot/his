<meta charset="utf-8">
<style>
    table {
        border-collapse: collapse;
    }

    td {
        text-align: center;
    }

    thead tr {
        background: #dadada;
    }
</style>
<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/core/inc_environment_global.php');
$local_user = 'aufnahme_user';
require($root_path . 'include/core/inc_front_chain_lang.php');

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

$smarty->assign('sToolbarTitle', "Thống kê khoa Y Học Cổ Truyền");

$smarty->assign('breakfile', $breakfile);

//echo $datefrom;
$str = "<p>" . PDF_HOSNAME . "</p>
 			<center><B>THỐNG KÊ KHÁM BỆNH - KHOA HỒI SỨC CẤP CỨU<br>
 		Ngày " . date("d/m/Y", strtotime($datefrom)) . (($datefrom != $dateto) ? " tới ngày " . date("d/m/Y", strtotime($dateto)) . "" : "") . "</B>
 </center>";
//ma noi bo cua Khoa kham benh la id = 5
$deptid = 6;

$sqlsumhscc="SELECT COUNT(DISTINCT t.encounter_nr) sumhscc
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from =".$deptid." AND t.type_encounter = 2 ";
if($rs=$db->Execute($sqlsumhscc)){
    while($row=$rs->FetchRow())  {
        $sumhscc=$row['sumhscc'];
    }
}

$sqlsumbh="SELECT COUNT(DISTINCT t.encounter_nr) sumbh
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND DATE_FORMAT(insurance_exp,'%Y-%m-%d') > '$dateto'
		 AND t.dept_to>0 AND t.dept_from =".$deptid." AND  p.insurance_nr != '' AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsumbh)){
    while($row=$rs->FetchRow()) {
        $sqlsumbh=$row['sumbh'];
    }
}

$sqlsumkn="SELECT COUNT(DISTINCT t.encounter_nr) sumkn
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'
		 AND t.dept_to>0 AND t.dept_from =".$deptid." AND  e.loai_kham=1 AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsumkn)){
    while($row=$rs->FetchRow()) {
        $sumkn=$row['sumkn'];
    }
}

$sqlsumknbh="SELECT COUNT(DISTINCT t.encounter_nr) sumknbh
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'  AND DATE_FORMAT(insurance_exp,'%Y-%m-%d') > '$dateto'
		 AND t.dept_to>0 AND t.dept_from =".$deptid." AND  p.insurance_nr != '' and e.loai_kham=1 AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsumknbh)){
    while($row=$rs->FetchRow()){
        $sumknbh=$row['sumknbh'];
    }
}


$sqlsumkng="SELECT COUNT(DISTINCT t.encounter_nr) sumkng
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'
		 AND t.dept_to>0 AND t.dept_from =".$deptid." and e.loai_kham=2 AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsumkng)){
    while($row=$rs->FetchRow()){
        $sumkng=$row['sumkng'];
    }
}

$sqlsumkngbh="SELECT COUNT(DISTINCT t.encounter_nr) sumkngbh
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto'   AND DATE_FORMAT(insurance_exp,'%Y-%m-%d') > '$dateto'
		 AND t.dept_to>0 AND t.dept_from =".$deptid." AND  p.insurance_nr != '' and e.loai_kham=2 AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsumkngbh)){
    while($row=$rs->FetchRow()){
        $sumkngbh=$row['sumkngbh'];
    }
}

$sqlsum6t="SELECT COUNT(DISTINCT t.encounter_nr) sum6t
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0  AND (DATE_FORMAT(NOW(),'%Y') - SUBSTR(`p`.`date_birth`,1,4)<=6)
		AND t.dept_from =".$deptid." AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsum6t)){
    while($row=$rs->FetchRow()){
        $sum6t=$row['sum6t'];
    }
}

$sqlsum15t="SELECT COUNT(DISTINCT t.encounter_nr) sum15t
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0  AND (DATE_FORMAT(NOW(),'%Y') - SUBSTR(`p`.`date_birth`,1,4)<=15)
		AND t.dept_from =".$deptid." AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsum15t)){
    while($row=$rs->FetchRow()){
        $sum15t=$row['sum15t'];
    }
}

$sqlsum60t="SELECT COUNT(DISTINCT t.encounter_nr) sum60t
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0  AND (DATE_FORMAT(NOW(),'%Y') - SUBSTR(`p`.`date_birth`,1,4)>=60)
		AND t.dept_from =".$deptid." AND t.type_encounter = 2";
if($rs=$db->Execute($sqlsum60t)){
    while($row=$rs->FetchRow()){
        $sum60t=$row['sum60t'];
    }
}

$sqlsumnv="SELECT COUNT(DISTINCT t.encounter_nr) sumnv
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from =".$deptid." AND t.type_encounter = 1 AND t.dept_to != t.dept_from";
if($rs=$db->Execute($sqlsumnv)){
    while($row=$rs->FetchRow()){
        $sumnv=$row['sumnv'];
    }
}

$sqlsuma09="SELECT COUNT(DISTINCT t.encounter_nr) suma09
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from =".$deptid." AND t.type_encounter = 2 AND (e.referrer_diagnosis_code LIKE '%A09%' OR e.referrer_diagnosis LIKE '%A09%')";
if($rs=$db->Execute($sqlsuma09)){
    while($row=$rs->FetchRow()){
        $suma09=$row['suma09'];
    }
}

$sqlsumj10="SELECT COUNT(DISTINCT t.encounter_nr) sumj10
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to>0
		AND t.dept_from =".$deptid." AND t.type_encounter = 2 AND (e.referrer_diagnosis_code LIKE '%J10%' OR e.referrer_diagnosis LIKE '%J10%')";
if($rs=$db->Execute($sqlsumj10)){
    while($row=$rs->FetchRow()){
        $sumj10=$row['sumj10'];
    }
}

$sqlsumchuyenvien="SELECT COUNT(DISTINCT t.encounter_nr) sumchuyenvien
FROM (dfck_encounter_transfer AS t   JOIN care_person AS p)   JOIN care_encounter AS e
WHERE (t.pid = p.pid)  AND (e.encounter_nr=t.encounter_nr)
		AND t.dept_from IN(SELECT care_department.nr  AS nr FROM care_department)
		AND DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <='$dateto' AND t.dept_to=-2
		AND t.dept_from =".$deptid."";
if($rs=$db->Execute($sqlsumchuyenvien)){
    while($row=$rs->FetchRow()){
        $sumchuyenvien=$row['sumchuyenvien'];
    }
}

$str .= '<br>
 		<ul>
 			<li>Tổng: <b>' . $sumhscc . '</b>
 			<ul>
 					<li>BHYT: <b>' . $sumbh . '</b></li>
 					<li>Không BHYT: <b>' . ($sumhscc - $sumbh) . '</b></li>
 				</ul>
 			</li>
 			<li>
 				<table>
 				<tr><td width="50%"></td><td width="25%">BHYT</td><td width="25%">Không BHYT</td></tr>
 				<tr><td>Khám nội: <b>' . $sumkn . '</b></td><td><b>' . $sumknbh . '</td><td><b>' . ($sumkn - $sumknbh) . '</td></tr>
 				<tr><td>Khám ngoại: <b>' . $sumkng . '</b></td><td><b>' . $sumkngbh . '</td><td><b>' . ($sumkng - $sumkngbh) . '</td></tr>
 				</table>
 			</li>
 			<li>Khám&nbsp;Trẻ em (<6T): <b>' . $sum6t . '</b></li>
 			<li>Khám&nbsp;&nbsp;&nbsp;&nbsp;nhi (<15T): <b>' . $sum15t . '</b></li>
 			<li>Người&nbsp;&nbsp;&nbsp;già (>60T): <b>' . $sum60t . '</b></li>
 			<li>Nhập viện: <b>' . $sumnv . '</b>
 				<ul>';
// 			if($row['sumnv'] > 0){
// 				$sql="SELECT
// 					d.name_formal, d.LD_var,(select count(distinct e.encounter_nr) from dfck_admit_inout_dept e where e.dept_from = (select nr from care_department where id=$deptid) and e.dept_to = d.nr and e.type_encounter=1 and DATE_FORMAT(e.datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(e.datein,'%Y-%m-%d') <='$dateto') sumnv
// 				FROM care_department d where d.type=1 and d.id!=$deptid";
// 				if($rs2 = $db->Execute($sql)){
// 					while($row2 = $rs2->FetchRow()){
// 						$str.= '<li>'.(($$row2['LD_var'])?$$row2['LD_var']:$row2['name_formal']).': <b>'.$row2['sumnv'].'</b></li>';
// 					}
// 				}
// 			}
$str .= '</ul>
 			</li>

 		</ul>
 		<br>
 		<table width="100%">
 		<tr><td><b>Trưởng khoa</b></td><td>' . 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y') . '<br><b>Người báo cáo</b></td></tr>
 		</table>';

echo $str;



