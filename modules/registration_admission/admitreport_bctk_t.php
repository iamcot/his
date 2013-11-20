<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
$lang_tables[]='departments.php';
require($root_path.'include/core/inc_front_chain_lang.php');
switch ($id) {
    case 'bctks': 
        $khoa= 'Báo cáo tháng Khoa Sản';
        $temp=1;
        break;
    case 'bm07ks':
        $khoa= 'Thống kê khám chữa phụ khoa và nạo phá thai';
        $temp=2;
        break;
    default : $khoa='Xin chọn một loại báo cáo.';
        break;
}
?>
<h2><? echo $khoa;?></h2>
<?
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
//$allMeDept = $dept_obj->getAllMedical();
?>
<form method="POST" name="report" id="report">
    <?php 
        if($temp==1){
    ?>
    <span>Tháng: </span>
    <input id="month" type="text" size="7" value="">
    <span>Năm: </span>
    <input id="year" type="text" size="10" value="">
    <input type="button" value="Xem báo cáo" onclick="viewreportk('<? echo $id;?>')">
    <?php
        }else if($temp==2){
    ?>
    <span>Năm: </span>
    <input id="year" type="text" size="10" value="">
    <span>Quí:</span>
    <input id="month" type="text" size="7" value="">tháng&nbsp;&nbsp;&nbsp;
    <input type="button" value="Xem báo cáo" onclick="viewreportk('<? echo $id;?>')">
    <?php
        }
    ?>
</form>



