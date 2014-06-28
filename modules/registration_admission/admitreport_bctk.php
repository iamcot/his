<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
$lang_tables[]='departments.php';
require($root_path.'include/core/inc_front_chain_lang.php');
switch ($id) {
	case 'dieutrinoitru': $khoa = 'Thống kê Điều trị nội trú'; break;
	case 'b031dt': $khoa='Hoạt động điều trị - Biểu 03.1-ĐT'; break;
	case 'tkksk': $khoa = 'Thống kê khám sức khỏe'; break;
	case 'tkyhct': $khoa = 'Thống kê khoa YHCT'; break;
	case 'tkkb': $khoa = 'Thống kê khoa Khám bệnh'; break;
	case 'tkhscc': $khoa = 'Thống kê Khám bệnh Khoa HSCC'; break;
        case 'bctuks': $khoa= 'Báo cáo tuần Khoa Sản'; break;
	default : $khoa='Xin chọn một loại báo cáo.';
		break;
}
?>
<h2><? echo $khoa;?></h2>
<br>
<?
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
//$allMeDept = $dept_obj->getAllMedical();
?>
<span>Từ ngày: </span>
<input id="datefrom" type="text" value="">
<span> Đến ngày: </span>
<input id="dateto" type="text"  value="">
<br>
<input type="button" value="Xem báo cáo" onclick="viewreportk('<? echo $id;?>')">
<script type="text/javascript">

$(function() {
	$("#datefrom").datepicker();
	 $("#dateto").datepicker();
        $("#datefrom").datepicker("option", "dateFormat","yy-mm-dd");
       $("#dateto").datepicker("option", "dateFormat","yy-mm-dd");
    });

</script>
