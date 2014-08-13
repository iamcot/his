<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
$thisfile= basename(__FILE__);
$breakfile='../his/main/startframe.php'.URL_APPEND;
//$breakfile='modules/pharmacy/apotheke.php'.URL_APPEND;
$date_format='dd-mm-yyyy';

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');
ob_start();
?>
<?php
$select_type= $_POST['loaikcb'];
function printEx($select_type,$fromdate){
header('location:Examples/xuatbaocao_Ex.php'.URL_APPEND.'&select_type='.$select_type.'&fromdate='.$fromdate);
}
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

$smarty->assign('sRegForm','<form name="reportform" method="POST" enctype="multipart/form-data">');
//gọi hàm in báo cáo viện phí
if($select_type!=''&&$fromdate!=''){
    printEx($select_type,$fromdate);
}
//Test format fromday
if (isset($fromdate) && $fromdate!='' && strpos($fromdate,'-')<3) {
    list($f_day,$f_month,$f_year) = explode("-",$fromdate);
    $fromdate=$f_year.'-'.$f_month.'-'.$f_day;
}
else
    list($f_year,$f_month,$f_day) = explode("-",$fromdate);

//Calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
$calendar->load_files();
$date_format='dd-mm-yyyy';
$smarty->assign('calendarfrom',$calendar->show_calendar($calendar,$date_format,'fromdate',$fromdate));

if(isset($_POST['loaikcb']))
{
    $select_type = $_POST['loaikcb'];
}
$s0=''; $s1='';
switch($select_type){
    case 1: $s0='selected'; break;
    case 2: $s1='selected'; break;
    default: $s1='selected';
}
$temp='Chọn loại khám chữa bệnh : <select id="loaikcb" name="loaikcb" value="'.$select_type.'">
			<option value="1" '.$s0.' >Nội trú</option>
			<option value="2" '.$s1.' >Ngoại trú</option>
		</select>';
$smarty->assign('inputby',$temp);
$smarty->assign('LDFromDate','Chọn ngày:');
$smarty->assign('pbSubmit','<input type="submit" value ="Xem Báo Cáo" >');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

//sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" id="fromdate" value="'.$fromdate.'">
		<input type="hidden" id="select_type" name="select_type" value="'.$select_type.'">
		<input type="hidden" name="user_report" value="'.$_SESSION['sess_user_name'].'">';
$smarty->assign('sHiddenInputs',$sTempHidden);

$smarty->assign('sMainBlockIncludeFile','pharmacy/baocao_vp.tpl');
# Show main frame
$smarty->display('common/mainframe.tpl');

?>

