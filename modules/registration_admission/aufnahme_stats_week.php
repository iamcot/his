<?php
/*
 Thống kê quí

*/
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables=array('date_time.php','departments.php','actions.php','prompt.php');
define('LANG_FILE','aufnahme.php');
$local_user="aufnahme_user";
require($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=&new Encounter();

if(!isset($currYear)||!$currYear) $currYear=date('Y');
if(!isset($currMonth)||!$currMonth) $currMonth=date('m');

$sTitle = "Tiếp nhận bệnh::Thống kê::Tuần";

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$sTitle);

 # href for help button
 $smarty->assign('pbHelp',"");

 # href for close button
 $smarty->assign('breakfile',$rettarget);

 # Window bar title
 $smarty->assign('sWindowTitle',$sTitle);

 # Collect extra javascript

 ob_start();
 require_once ('../../js/jscalendar/calendar.php');
		$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
		$calendar->load_files();
require('./include/js_popsearchwindow.inc.php');
 ?>
 <script language="javascript">
 
 function getbyweek(){
 var xmlhttp;
 var s_day=document.getElementById("f-calendar-field-1").value;
 var e_day=document.getElementById("f-calendar-field-2").value;
 if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("result").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","getresultstatsbyweek.php?sday="+s_day+"&eday="+e_day,true);
	xmlhttp.send();

 }
  function printOut(sday,eday)
    {
            urlholder="<?php echo $root_path ?>modules/pdfmaker/admission/admitstatsweek.php<?php echo URL_APPEND ?>&sday="+sday+"&eday="+eday+"";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
            //testprintout<?php echo $sid ?>.print();
    }
</script>
 <?php
 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->append('JavaScript',$sTemp);
 
 ob_start();
?>
</HEAD>


<BODY bgcolor="<?php echo $cfg['body_bgcolor'];?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 <?php if (!$cfg['dhtml']){ echo ' link='.$cfg['body_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['body_txtcolor']; } ?>>
<ul>
<font size="4">
	Từ ngày :&nbsp;
<?php echo $calendar->show_calendar($calendar,$date_format,'day_start',$insurance_start)?>			
	
			
	Đến ngày :&nbsp;
			
	<?php echo $calendar->show_calendar($calendar,$date_format,'day_end',$insurance_start)?>

		&nbsp;
	<input type="button" value="Xem" onclick="getbyweek();">
</font>				
</ul>

<table id="result" border="0" width="80%">
	<tbody>
		<tr>
			<td>
				<p style="text-align:left;margin-left:100px;">Chọn khoảng ngày cần xem . Sau đó nhấn nút xem</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>
				<img width="20" height="15" border="0" src="../../gui/img/common/default/varrow.gif">
				<a href="aufnahme_stats_qui.php?ntid=false&lang=vi">Thống kê quí</a>
				<br>
				<img width="20" height="15" border="0" src="../../gui/img/common/default/varrow.gif">
				<a href="aufnahme_stats.php?ntid=false&lang=vi&currMonth=<?php echo $currMonth; ?>&currYear=<?php echo $currYear; ?>">Thống kê tháng</a>
				</p>
			</td>
		</tr>
	</tbody>	
</table>


<?php
 $sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 $smarty->display('common/mainframe.tpl');
?>