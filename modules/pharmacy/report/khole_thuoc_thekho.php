<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$thisfile= basename(__FILE__);
$breakfile='../report_khole.php'.URL_APPEND;



# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoLe.' :: '.$LDPharmaReportKhoLeTxt);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPharmaReportKhoLeTxt')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDPharmaReportKhoLeTxt);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
 ob_start();
?>
<style type="text/css">
div.box { border: double; border-width: thin; width: 100%; border-color: black; }
.v12 { font-family:verdana,arial;font-size:12; }
.v13 { font-family:verdana,arial;font-size:13; }
.v10 { font-family:verdana,arial;font-size:10; }
#hint ul {
	list-style-type: none;
	font-family: verdana;
 	arial, sans-serif;
	font-size: 10px;
	margin: 0 0 0 -28px;
}
#hint li {
	list-style-type: none;
	border: 1px dotted #C0C0C0;
	margin: 0 0 0 -10px;
	cursor: default;
	color: black;
	text-align:left;
}
#hint {
	background:#fff;
	border: 0px;
}
#hint > li:hover {
	background: #ffc;
}
.sx {
	text-align:left;
	font-size: 12px;
	font-variant: small-caps;
	color: blue;
}
li.selected {
	background: #FFE4E1;
}
.nav:hover {
	background:#FFFF99;
}
.together { border-left:thick solid #0000FF; }
</style>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/lib/prototype.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/effects.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/controls.js"></script>
<script type="text/javascript" src="<?php echo $root_path; ?>js/scriptaculous/src/builder.js"></script>
<script src="<?php echo $root_path; ?>js/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script  language="javascript">
<!--
function chkform(d) {
	document.reportform.action=<?php echo $thisfile; ?>;
	document.reportform.submit();
}
function printout() {
	window.print();
}
function Medicine_AutoComplete(){
	var name_med='medicine';
	var includeScript = "khole_autocomplete_medicine.php";
	new Ajax.Autocompleter(name_med,"hint",includeScript, {
			afterUpdateElement : setSelectionId
		}
	);
}
function setSelectionId(div,li) {
	document.getElementById('encoder').value = li.id;
	var text=div.value; 
	var temp_value=text.split('-- ');
	document.getElementById('medicine').value = temp_value[0];
	document.getElementById('content').value = temp_value[2];
	var b=temp_value[3]; 
	var temp_cost=b.split(' vnd/');
	document.getElementById('unit').value = temp_cost[1];
}
-->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
 $smarty->assign('sRegForm','<form name="reportform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('LDname',$LDMedicineName2.': ');
$smarty->assign('name','<input type="text" id="medicine" name="medicine" size="30" onFocus="Medicine_AutoComplete()"><div id="hint"></div>');
$smarty->assign('LDencoder',$LDMedicineID.': ');
$smarty->assign('encoder','<input type="text" id="encoder" name="encoder" size="10">&nbsp;');
$smarty->assign('LDcontent',$LDHamLuong.': ');
$smarty->assign('content','<input type="text" id="content" name="content" size="10">&nbsp;');
$smarty->assign('LDunit',$LDUnit.': ');
$smarty->assign('unit','<input type="text" id="unit" name="unit" size="10">');
$smarty->assign('titleForm',$LDReportImportExport_Medicine);

$smarty->assign('LDFromDate',$LDFromDate);
$smarty->assign('LDToDate',$LDToDate);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDDay',$LDDay);
$smarty->assign('LDTotalNumber',$LDTotalNumber);

$smarty->assign('LDDayMonth',$LDDayMonth);
$smarty->assign('LDVoucher',$LDVoucher);
$smarty->assign('LDImport',$LDImport1);
$smarty->assign('LDExport',$LDExport1);
$smarty->assign('LDLotID',$LDLotID1);
$smarty->assign('LDExpDate',$LDExpDate1);
$smarty->assign('LDExplain',$LDExplain);
$smarty->assign('LDFirstInventory',$LDFirstInventory);
$smarty->assign('LDNumberOf',$LDNumberOf);
$smarty->assign('LDLastInventory',$LDLastInventory);
$smarty->assign('LDNote',$LDNote);

//Test format fromday
if (isset($fromdate) && $fromdate!='' && strpos($fromdate,'-')<3) {
	list($f_day,$f_month,$f_year) = explode("-",$fromdate);
	$fromdate=$f_year.'-'.$f_month.'-'.$f_day;
}
else 
	list($f_year,$f_month,$f_day) = explode("-",$fromdate);
//Test format today
if (isset($todate) && $todate!='' && strpos($todate,'-')<3) {
	list($t_day,$t_month,$t_year) = explode("-",$todate);
	$todate=$t_year.'-'.$t_month.'-'.$t_day;
}
else 
	list($t_year,$t_month,$t_day) = explode("-",$todate);
			
$smarty->assign('monthreport',$LDMonth.': '.$f_month);
	
//Calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
$calendar->load_files();
$date_format='dd-mm-yyyy';
$smarty->assign('calendarfrom',$calendar->show_calendar($calendar,$date_format,'fromdate',$fromdate));
$smarty->assign('calendarto',$calendar->show_calendar($calendar,$date_format,'todate',$todate));


//Search item from date
$j=(int)$f_day; $total=0;
$i=0;
if ($f_day>16)
	$end_day=31;
else
	$end_day=$f_day+15;
	
if ($t_day>$end_day)
	$todate=$t_year.'-'.$t_month.'-'.$end_day;

//echo $j.' '.$end_day;
//require_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
//$CabinetPharma = new CabinetPharma;
		
/*$listReport = $CabinetPharma->report15Day($fromdate,$todate);
if(is_object($listReport)){
	ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		if (!isset($old_encode) || ($old_encode!=$rowReport['product_encoder'])) {
			if (isset($old_encode)){
				for ($j;$j<=$end_day;$j++)
					echo '<td></td>';
				echo	'<th>'.$total.'</th>';	//Tong cong		
				echo	'<td></td>	</tr>';		//Note
			}
			$old_encode=$rowReport['product_encoder'];
			$j=(int)$f_day;
			$flag=1; $total=0;
			$i++;
		}else $flag=0;
		
			
		if ($flag){
			echo '<tr bgColor="#ffffff" >';
			echo	'<td align="center">'.$i.'</td>'; //STT
			echo	'<td>'.$rowReport['product_name'].'</td>';	//Ten thuoc
			echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
			echo	'<td> </td>';	//Quy cach?		
		}
		for($j;$j<=(int)$rowReport['at_day'];$j++) {
			if ($j==(int)$rowReport['at_day']){
				echo '<td>'.$rowReport['total'].'</td>';  //Ngay
				$total+=$rowReport['total'];
				$j++;
				break;
			}
			else
				echo '<td></td>';
		}
							
	}
	for ($j;$j<=$end_day;$j++)
		echo '<td></td>';
	echo	'<th>'.$total.'</th>';	//Tong cong		
	echo	'<td></td>	</tr>';		//Note
			
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();
		
} else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="11">'.$LDItemNotFound.'</td></tr>';
 */
$smarty->assign('divItem',$sTempDiv);
 
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

//*********************************************************************************

$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
$smarty->assign('pbPrint','<a href="javascript:window.print();"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/khole_thekho.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

