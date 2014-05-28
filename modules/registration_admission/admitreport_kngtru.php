<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');

 //echo $datefrom;
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
$deptinfo = $dept_obj->getDeptAllInfo($dept);

 $str = "<p>".PDF_HOSNAME."</p>
 			<center><B>THỐNG KÊ KHÁM BỆNH ".mb_strtoupper($deptinfo['name_formal'], "utf8")."<br>
 		Ngày ".date("d/m/Y",strtotime($datefrom)).(($datefrom != $dateto)?" tới ngày ".date("d/m/Y",strtotime($dateto))."":"")."</B>
 ";
//strtoupper($deptinfo['name_formal'])
$str .= "<table ><thead>
<tr>
<td rowspan='2'>STT </td>
<td rowspan='2'>Ngày khám</td>
<td rowspan='2'>Mã BA</td>

<td rowspan='2'>Tên Bệnh nhân</td>
<td colspan='2'>Năm Sinh</td>
<td rowspan='2'>Thẻ BHYT</td>
<td rowspan='2'>Địa chỉ</td>
<td rowspan='2'>Chẩn đoán</td>
<td rowspan='2'>Thuốc - Số lượng</td>
</tr>
<tr>
<td>Nam</td>
<td>Nữ</td>
</tr>
</thead>
<tbody>";
require_once($root_path.'include/care_api_classes/class_prescription.php');
$obj=new Prescription();

$sql="SELECT * from dfck_admit_inout_dept where dept_to = $dept
 			and DATE_FORMAT(datein,'%Y-%m-%d') >='$datefrom' and DATE_FORMAT(datein,'%Y-%m-%d') <='$dateto'  ";
  global $db;
   $j=1;
 if($rs = $db->Execute($sql)){
 	while($row = $rs->FetchRow()){
 		///print_r($row);
 		$str.='<tr><td>'.$j.'</td><td>'.date('d/m/Y',strtotime($row['datein'])).'</td><td>'.$row['encounter_nr'].'</td><td>'.$row['fname'].'</td>'.(($row['sex']=='m')?'<td>'.$row['yearbirth'].'</td><td></td>':'<td></td><td>'.$row['yearbirth'].'</td>').'<td>'.$row['insurance_nr'].'</td><td>'.$row['address'].'</td><td>'.$row['referrer_diagnosis'].'</td>';
 		$thuocsql=$obj->getDetailPrescriptionInfo1($row['encounter_nr']);
 		$thuocrs=$db->Execute($thuocsql);
    	$thuoc = "";
		$j++;
    	$i=1;
    	while($info_thuoc=$thuocrs->FetchRow()){
    	//	print_r($info_thuoc);
        $thuoc .= $i.'. '.$info_thuoc['product_name'].' - '.$info_thuoc['sum_number'].' '.$info_thuoc['note'].'<br> ';
        $i++;
    	}   
    	$str.='<td style="text-align:left">'.$thuoc.'</td>'; 
 		$str .='</tr>';
 	}
 }
 $str .= "<tr><td colspan='10' style='text-align:left;padding-left:20px;'>Tổng bệnh: <b>".($j-1)."</b></td></tr></tbody></table>";
 ?>
<meta charset="utf-8">
<style>
table{
	border-collapse: collapse;
	border:1px solid #000000;
	width:95%;
}
td{
	text-align: center;
	border:1px solid #000000;
	font-size: 12px;
}
thead tr{
	background: #dadada;
}
</style>
<?
echo $str;
?>