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
$sTitle = "Tiếp nhận bệnh::Thống kê::Quí";

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
 ?>
 <script language="javascript">
 function getbymonth(){
 var xmlhttp;
 var str=document.getElementById("currYear").value;
 var month=document.getElementById("select_month").value;
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
	xmlhttp.open("GET","getresultstats.php?year="+str+"&month="+month,true);
	xmlhttp.send();

 }
 function getbyqui(){
 var xmlhttp;
 var str=document.getElementById("currYear").value;
 var month=document.getElementById("select_qui").value;
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
	xmlhttp.open("GET","getresultstatsbyqui.php?year="+str+"&qui="+month,true);
	xmlhttp.send();

 }
  function printOut(currYear,qui)
    {
            urlholder="<?php echo $root_path ?>modules/pdfmaker/admission/admitstatsqui.php<?php echo URL_APPEND ?>&currYear="+currYear+"&qui="+qui+"";
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
	Chọn quí:&nbsp;
			
	<Select id="select_qui" name="select_qui">
		<option value="1">Quí I</option>
		<option value="2">Quí II</option>
		<option value="3">Quí III</option>
		<option value="4">Quí IV</option>
	</select>
			
	Chọn năm:&nbsp;
			
	<?php
		echo'<select id="currYear" name="currYear" size="1">';
	for ($i=(date(Y)-11);$i<(date(Y)+100);$i++){
		 $sBuffer1 = $sBuffer1.'<option  value="'.$i.'" ';
		 if ($currYear==$i) $sBuffer1 = $sBuffer1.'selected';
		 $sBuffer1 = $sBuffer1.'>'.$i.'</option>';
		 $sBuffer1 = $sBuffer1."\n";
	}
	$sBuffer1 = $sBuffer1.'</select>';
	echo $sBuffer1;
	?>	
		&nbsp;
	<input type="button" value="Xem" onclick="getbyqui();">
</font>				
</ul>

<table id="result" border="0" width="80%">
	<tbody>
		<tr>
			<td>
			    <p style="margin-left:100px;">Chọn quí và năm cần xem. Sau đó nhấn nút xem</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>
				<img width="20" height="15" border="0" src="../../gui/img/common/default/varrow.gif">
				<a href="aufnahme_stats_week.php?ntid=false&lang=vi">Thống kê tuần</a>
				<br>
				<img width="20" height="15" border="0" src="../../gui/img/common/default/varrow.gif">
				<a href="aufnahme_stats.php?ntid=false&lang=vi&currMonth=<?php echo $currMonth; ?>&currYear=<?php echo $currYear; ?>">Thống kê tháng</a>
				</p>
			</td>
		</tr>
	</tbody>	
</table>
<br/>


<?php
 $sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 $smarty->display('common/mainframe.tpl');
?>