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
 			<center><B>THỐNG KÊ KHÁM BỆNH - KHOA HỒI SỨC CẤP CỨU<br>
 		Ngày ".date("d/m/Y",strtotime($datefrom)).(($datefrom != $dateto)?" tới ngày ".date("d/m/Y",strtotime($dateto))."":"")."</B>
 </center>";
 //ma noi bo cua Khoa kham benh la id = 5
 $deptid = 6;
 $sql="SELECT 
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and dept_to>0  and type_encounter = 2) sumkb,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' AND insurance_nr != ''
			AND DATE_FORMAT(insurance_exp,'%Y-%m-%d') > '$dateto' and dept_to>0  and type_encounter = 2) sumbh,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and loai_kham=1 and dept_to>0   and type_encounter = 2) sumknoi,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and loai_kham=1 AND insurance_nr != ''
			AND DATE_FORMAT(insurance_exp,'%Y-%m-%d') > '$dateto' and dept_to>0  and type_encounter = 2 ) sumknoibh,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and loai_kham=2 and dept_to>0  and type_encounter = 2 ) sumkng,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and loai_kham=2 AND insurance_nr != ''
			AND DATE_FORMAT(insurance_exp,'%Y-%m-%d') > '$dateto' and dept_to>0   and type_encounter = 2  ) sumkngbh,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and (DATE_FORMAT(now(),'%Y') - yearbirth)<=6 and dept_to>0  and type_encounter = 2 ) sum6t,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and (DATE_FORMAT(now(),'%Y') - yearbirth)<=15 and dept_to>0  and type_encounter = 2 ) sum15t,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and (DATE_FORMAT(now(),'%Y') - yearbirth)>=60 and dept_to>0  and type_encounter = 2 ) sum60t,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and type_encounter=1 and dept_to != dept_from and dept_to>0 ) sumnv,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and (referrer_diagnosis_code like '%A09%' OR referrer_diagnosis like '%A09%')  and dept_to>0  and type_encounter = 2 ) suma09,
 		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and (referrer_diagnosis_code like '%J10%' OR referrer_diagnosis like '%J10%') and dept_to>0  and type_encounter = 2 ) sumj10,
		
		(select count(distinct encounter_nr) from dfck_admit_inout_dept where dept_from = (select nr from care_department where id=$deptid)
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto' and dept_to = -2) sumchuyenvien
 		from dual";
 		//echo $sql;
 global $db;
 if($rs = $db->Execute($sql)){
 	if($row = $rs->FetchRow()){
 		$str .= '<br>
 		<ul>
 			<li>Tổng: <b>'.$row['sumkb'].'</b>
 			<ul>
 					<li>BHYT: <b>'.$row['sumbh'].'</b></li>
 					<li>Không BHYT: <b>'.($row['sumkb'] - $row['sumbh']).'</b></li>
 				</ul>
 			</li>
 			<li>
 				<table>
 				<tr><td width="50%"></td><td width="25%">BHYT</td><td width="25%">Không BHYT</td></tr>
 				<tr><td>Khám nội: <b>'.$row['sumknoi'].'</b></td><td><b>'.$row['sumknoibh'].'</td><td><b>'.($row['sumknoi']-$row['sumknoibh']).'</td></tr>
 				<tr><td>Khám ngoại: <b>'.$row['sumkng'].'</b></td><td><b>'.$row['sumkngbh'].'</td><td><b>'.($row['sumkng']-$row['sumkngbh']).'</td></tr>
 				</table>
 			</li>
 			<li>Khám&nbsp;Trẻ em (<6T): <b>'.$row['sum6t'].'</b></li>
 			<li>Khám&nbsp;&nbsp;&nbsp;&nbsp;nhi (<15T): <b>'.$row['sum15t'].'</b></li>
 			<li>Người&nbsp;&nbsp;&nbsp;già (>60T): <b>'.$row['sum60t'].'</b></li>
 			<li>Nhập viện: <b>'.$row['sumnv'].'</b>
 				<ul>';
 			if($row['sumnv'] > 0){
 				$sql="SELECT 
 					d.name_formal, d.LD_var,(select count(distinct e.encounter_nr) from dfck_admit_inout_dept e where e.dept_from = (select nr from care_department where id=$deptid) and e.dept_to = d.nr and e.type_encounter=1 and DATE_FORMAT(e.datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(e.datein,'%Y-%m-%d') <='$dateto') sumnv
 				FROM care_department d where d.type=1 and d.id!=$deptid";
 				if($rs2 = $db->Execute($sql)){
 					while($row2 = $rs2->FetchRow()){
 						$str.= '<li>'.(($$row2['LD_var'])?$$row2['LD_var']:$row2['name_formal']).': <b>'.$row2['sumnv'].'</b></li>';
 					}
 				}
 			}
 			$str.='</ul>
 			</li>
 			
 		</ul>
 		<br>
 		<table width="100%">
 		<tr><td><b>Trưởng khoa</b></td><td>'.'Ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'<br><b>Người báo cáo</b></td></tr>
 		</table>';
 	}
 }
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