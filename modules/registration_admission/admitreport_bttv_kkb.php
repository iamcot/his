<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');
switch ($id) {
	case 'kkb': $khoa = 'Khoa Khám Bệnh'; break;
	case 'dtnt': $khoa = 'Điều trị nội trú'; break;
	case 'all': $khoa = 'Toàn bệnh viện'; break;
	
	default:
		$khoa = 'Toàn bệnh viện';
		$id = 'all';
		break;
}
?>
<h2>Báo cáo bệnh tật tử vong <? echo $khoa;?></h2>
<span>Từ ngày: </span>
<input id="datefrom" type="text" value="">
<span> Đến ngày: </span>
<input id="dateto" type="text"  value="">
<input type="button" value="Xem báo cáo" onclick="viewreportbttv('<? echo $id;?>')">
<script type="text/javascript">

$(function() {
	$("#datefrom").datepicker();
	 $("#dateto").datepicker();
        $("#datefrom").datepicker("option", "dateFormat","yy-mm-dd");
       $("#dateto").datepicker("option", "dateFormat","yy-mm-dd");
    });

</script>