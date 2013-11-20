<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
$lang_tables=array('departments.php');
define('LANG_FILE','radio.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

$thisfile= basename(__FILE__).URL_APPEND.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin;
$breakfile='../laboratory/labor_test_request_admin_dientim.php'.URL_APPEND.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin;
$urlsearch=$thisfile.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin;



//Test format today
if (isset($todate) && $todate!='' && strpos($todate,'-')<3) {
	list($t_day,$t_month,$t_year) = explode("-",$todate);
	$todate=$t_year.'-'.$t_month.'-'.$t_day;
}
else 
	list($t_year,$t_month,$t_day) = explode("-",$todate);

if($todate==''){
	$todate=date('Y-m-d');
}
if($current_page=='')
	$current_page=1;
	
# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDXemKetQua.' :: '.$LDDienTim);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDDienTim')");

 # Window bar title
 $smarty->assign('title',$LDDienTim);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
  ob_start();
?>
<style type="text/css">

</style>
<script language="javascript">
function searchValue()
{
	var search = document.getElementById('search').value;
	document.listmedform.action="<?php echo $urlsearch;?>&search="+search;
	document.listmedform.submit();
}
function chkform(d) {	
	document.listmedform.action="";
	document.listmedform.submit();
}
function viewDetailPDF(path)
{
	var win = '../pdfmaker/' + path;
	myWindow=window.open( win , 'View Details' , 'height=500,width=650,menubar=no,resizable=yes,scrollbars=yes' );
	myWindow.focus();
}
function viewDetailHoSo(enc_nr)
{
	var win = '../registration_admission/aufnahme_daten_zeigen.php<?php echo URL_APPEND; ?>'+'&encounter_nr='+enc_nr;
	myWindow=window.open( win , 'View Details' , 'height=500,width=650,menubar=no,resizable=yes,scrollbars=yes' );
	myWindow.focus();
}
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 

include_once($root_path.'include/core/inc_date_format_functions.php');

require_once($root_path.'include/care_api_classes/class_chandoanHA.php');
$CDHA=new ChanDoanHA();
//$datetime=date("d/m/Y G:i:s");


			
//Calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
$calendar->load_files();
$date_format='dd-mm-yyyy';
			

		
//$smarty->assign('monthreport',$LDFromDate.' '.$LDToDate);

ob_start();
?>
<form name="listmedform" method="POST"  onSubmit="return chkform(this)">
<center>
<table cellSpacing="1" cellPadding="2" border="0" width="95%">
	<tr><th align="left"><font size="2" color="#85A4CD"><?php echo $LDTestReceptionDT; ?></th>
		<td width="40%" align="right" rowspan="3">
				<table>
					<tr><td><input type="text" id="search" name="search" value="" size="30"></td>
						<td><a href="#"><input type="image" <?php echo createComIcon($root_path,'Search.png','0','',TRUE) ?> onclick="searchValue()" ></a></td>
					</tr>
					<tr><td colspan="2"><FONT size="1"><?php echo $LDsearchExpGuide; ?></td></tr>
				</table>
		</td>
	</tr>
	<tr>
		<td><?php	echo $LDDateTaken.': &nbsp;&nbsp;';
					echo $calendar->show_calendar($calendar,$date_format,'todate',$todate);
			?>&nbsp;
		<a href="#"><input type="image" <?php echo createLDImgSrc($root_path,'showreport.gif','0','middle') ?> onclick="searchValue()" ></a></td>
	</tr>
	<tr><td><br><b><?php echo $LDDanhsachBNtrongngay; ?></b></td></tr>
</table>
<p>
<table border="0" cellSpacing="1" cellPadding="3" width="95%" bgColor="#C3C3C3">
	<tr bgColor="#EDF1F4">
		<th><?php echo $LDSTT; ?></th>
		<th><?php echo $LDMaCaNhan; ?></th>
		<th><?php echo $LDPatientName; ?></th>
		<th><?php echo $LDPID1; ?></th>	
		<th><?php echo $LDTuoi; ?></th>
		<th><?php echo $LDNamNu; ?></th>
		<th><?php echo $LDTienHanh; ?></th>
		<th><?php echo $LDXemHoSo; ?></th>	
		<th><?php echo $LDDateTaken; ?></th>	
	</tr>
	<?php 
	
	if ($search==''){
		//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
		$number_items_per_page=30; 
		//$todate_temp = formatDate2STD($todate,'dd/mm/yyyy');
		$condition=" AND re.report_date='".$todate."' ";
		
		$total= $CDHA->countResultsDTItems($condition);
		$total_items=$total['sum_item'];
		
		$total_pages=ceil($total_items/$number_items_per_page);
			
		include_once('include/listreport_splitpage.php');

		$listItem = $CDHA->listResultsDTSplitPage($current_page, $number_items_per_page, $condition);
		
	}else{
		if (is_numeric($search))
			$condition=" AND per.pid ='".$search."' ";
		else
			$condition=" AND (per.name_first  LIKE '%".$search."%' OR per.name_last LIKE '%".$search."%' )";
			
		$listItem = $CDHA->listResultsDT($condition);
		
		$breakfile = $thisfile;
		if(is_object($listItem)) $total_items=$listItem->RecordCount();
		else $total_items=0;
	}
	
	if(is_object($listItem)){
		if($search==''){
			$stt= ($current_page-1)*$number_items_per_page+1; 
		}else $stt=1;
		for ($i=0;$i<$listItem->RecordCount();$i++)
		{
			$rowItem = $listItem->FetchRow();
			//$date = formatDate2Local($rowItem['date_time'],'dd/mm/yyyy');
			echo '<tr bgcolor="#ffffff"><td align="center">'.$stt.'</td>';
			echo '	<td>'.$rowItem['pid'].'</td>';
			echo '	<td>'.$rowItem['name_last'].' '.$rowItem['name_first'].'</td>';
			echo '	<td>'.$rowItem['encounter_nr'].'</td>';
			echo '	<td align="right">'.$rowItem['tuoi'].'</td>';
			if($rowItem['sex']=='m') $sx=$LDNam;
			else $sx=$LDNu;
			echo '	<td align="center">'.$sx.'</td>';
			echo '	<td><a href="javascript:viewDetailPDF(\''.$rowItem['script_call'].'\')">'.$rowItem['reporting_dept'].'</a></td>';
			echo '	<td align="center"><a href="javascript:viewDetailHoSo('.$rowItem['encounter_nr'].')"> 
					<img '.createComIcon($root_path,'info3.gif','0','',TRUE).'></a></td>';
			echo '	<td align="center">'.formatDate2Local($rowItem['report_date'],'dd/mm/yyyy').' &nbsp;'.$rowItem['report_time'].'</td></tr>';
			$stt++;
		}	
	}
	
	
	?>
</table>
<p>
<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr><td align="right"><b><?php echo $LDHienThi.': '.($stt-1).'/'.$total_items; ?></b></td></tr>
	<tr>
		<td align="center"><?php echo $sTempPage; ?></td>
	</tr>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<td>
			<input type="hidden" name="lang" value="<?php echo $lang; ?>">
			<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
			<input type="hidden" name="maxid" value="<?php echo $stt; ?>">
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>">
			<input type="hidden" name="number_items_per_page" value="<?php echo $number_items_per_page; ?>">

		</td>
	</tr>
	<tr><td align="center">
	<a href="<?php echo $breakfile; ?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0','middle'); ?> ></a></td></tr>
	<tr>
</table>


</center>
</form>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

$smarty->assign('breakfile',$breakfile);

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

