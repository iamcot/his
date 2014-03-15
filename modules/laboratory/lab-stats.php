<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('NO_2LEVEL_CHK',1);
require($root_path.'include/core/inc_front_chain_lang.php');

?>
<link type="text/css" rel="stylesheet" href="<?php echo  $root_path;?>js/cssjquery/jquery-ui-1.7.2.custom.css" />
 <script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>
<script src="<?php echo $root_path;?>js/jquery-ui-1.7.2.custom.min.js"></script>
<span>Từ ngày: </span>
<input id="datefrom" type="text" value="">
<span> Đến ngày: </span>
<input id="dateto" type="text"  value="">
<input type="button" value="Xem báo cáo" onclick="viewreportk('<? echo $id;?>')">
<script type="text/javascript">

$(function() {
	$("#datefrom").datepicker();
	 $("#dateto").datepicker();
        $("#datefrom").datepicker("option", "dateFormat","yy-mm-dd");
       $("#dateto").datepicker("option", "dateFormat","yy-mm-dd");
    });

</script>