<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
$lang_tables[]='departments.php';
require($root_path.'include/core/inc_front_chain_lang.php');
switch ($id) {
	case 'vaokhoa': $khoa = 'Vào Khoa'; break;
	case 'rakhoa': $khoa = 'Ra Khoa'; break;
	case 'vaovien': $khoa = 'Vào viện'; break;
	case 'ravien': $khoa = 'Ra Viện'; break;
	case 'chuyenvien': $khoa = 'Chuyển Viện'; break;
	default:
		break;
}
?>
<h2>Thống kê danh sách Bệnh nhân <? echo $khoa;?></h2>
<?
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
$allMeDept = $dept_obj->getAllMedical();
?>
Chọn khoa: <select id="deptselect"><option value=0>Tất cả</option>
<?
foreach($allMeDept as $dept){
	echo '<option value="'.$dept['nr'].'">'.$$dept['LD_var'].'</option>';
}
?></select><br><br>
<span>Từ ngày: </span>
<input id="datefrom" type="text" value="">
<span> Đến ngày: </span>
<input id="dateto" type="text"  value="">
<input type="button" value="Xem báo cáo" onclick="viewreportinout('<? echo $id;?>')">
<script type="text/javascript">

$(function() {
	$("#datefrom").datepicker();
	 $("#dateto").datepicker();
        $("#datefrom").datepicker("option", "dateFormat","yy-mm-dd");
       $("#dateto").datepicker("option", "dateFormat","yy-mm-dd");
    });

</script>
