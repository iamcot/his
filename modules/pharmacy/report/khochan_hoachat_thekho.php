<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$thisfile= basename(__FILE__);
$breakfile='../report_khochan.php'.URL_APPEND;

//Report in
if(!isset($select_type) || $select_type=='')
	$select_type=0;


		
switch($select_type){	
	case 0: $cond_typeput = ''; break;
	case 1: $cond_typeput = ' AND typeput=1 '; break;		//su nghiep
	case 2: $cond_typeput = ' AND typeput=0 '; break;		//bhyt
	case 3: $cond_typeput = ' AND typeput=2 '; break;		//cbtc
	default: $cond_typeput = ' ';
}		


# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDThekho.' - '.$LDChemical);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPharmaReportKhoLeTxt')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDChemical);

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
function printOut(select_type,fromdate,todate)
{
	var encoder = document.getElementById('encoder').value;
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_HC_thekho.php<?php echo URL_APPEND; ?>&select_type="+select_type+"&encoder="+encoder+"&fromdate="+fromdate+"&todate="+todate;
	testprintpdf=window.open(urlholder,"TheKho","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}
function Medicine_AutoComplete(){
			var name_med='medicine';
			var includeScript =  "khochan_autocomplete_chemical.php?type=<?php echo $type; ?>";
			new Ajax.Autocompleter(name_med,"hint",includeScript, {
					method: 'POST',
					paramName: 'search',
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
function selectTypeMed() {
	var temp_i = document.getElementById("type_med").selectedIndex;
	document.getElementById("select_type").value = document.getElementById("type_med").options[temp_i].value;
	document.reportform.action='<?php echo $thisfile.URL_APPEND.'&type='.$type; ?>';
	document.reportform.submit();
}
-->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
 $smarty->assign('sRegForm','<form name="reportform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('LDname',$LDChemicalName.': ');
$smarty->assign('name','<input type="text" id="medicine" name="medicine" size="34" value="'.$medicine.'" onFocus="Medicine_AutoComplete()"><div id="hint"></div>');
$smarty->assign('LDencoder',$LDChemicalID.': ');
$smarty->assign('encoder','<input type="text" id="encoder" name="encoder" size="5" value="'.$encoder.'">&nbsp;');
$smarty->assign('LDcontent',$LDNongDo.': ');
$smarty->assign('content','<input type="text" id="content" name="content" size="11" value="'.$content.'">&nbsp;');
$smarty->assign('LDunit',$LDUnit.': ');
$smarty->assign('unit','<input type="text" id="unit" name="unit" value="'.$unit.'" size="16">');
$smarty->assign('titleForm',$LDTheKho);

$smarty->assign('LDBy',$LDBy.': ');
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

$s0=''; $s1=''; $s2=''; $s3='';
switch($select_type){
	case 0: $s0='selected'; break;
	case 1: $s1='selected'; break;
	case 2: $s2='selected'; break;
	case 3: $s3='selected'; break;
	default: $s0='selected';
}
$temp='<select id="type_med" name="type_med" onchange="selectTypeMed()">
			<option value="0" '.$s0.' >'.$LDChemical.'</option>
			<option value="1" '.$s1.' >'.$LDChemical_KP.'</option>
			<option value="2" '.$s2.' >'.$LDChemical_BH.'</option>
			<option value="3" '.$s3.' >'.$LDChemical_CBTC.'</option>
		</select>';
$smarty->assign('inputby',$temp);

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


require_once($root_path.'include/care_api_classes/class_pharma.php');
$Pharma = new Pharma;

//The kho: nhap xuat tu fromdate(x) -> todate(y)
$listReport = $Pharma->Khochan_HC_thekho($encoder, $fromdate, $todate, $cond_typeput);
	//Lay x
	if(is_object($listReport)){
		$temp_report = $listReport->FetchRow();
		$ngaynhapxuat_gannhat = $temp_report['ngay'];			
	}else $ngaynhapxuat_gannhat = $fromdate;
	
//Ton kho gan nhat (x1) truoc ngay fromdate 
$result_ton = $Pharma->Khochan_hoachat_tontruoc($encoder, $cond_typeput." AND toninfo.todate<'$ngaynhapxuat_gannhat' ");	
	if($result_ton!=false){
		$ton_trc = $result_ton['last_number'];
		$ngayton_trc = $result_ton['todate'];
	}else{
		$ton_trc = 0;
		$ngayton_trc = '2012-01-01';
	}
//Tong nhap xuat (x1->x) truoc ngay fromdate 
$tongnhap_trc=0;
$tongxuat_trc=0;
$result_nhapxuat = $Pharma->Khochan_HC_tongnhapxuat_theongay($encoder, $ngayton_trc, $fromdate, $cond_typeput, 0);
if(is_object($result_nhapxuat)){
	while($tempnx = $result_nhapxuat->FetchRow()){
		$tongnhap_trc += $tempnx['tongnhap'];
		$tongxuat_trc += $tempnx['tongxuat'];
	}
}
$tondauky = $ton_trc + $tongnhap_trc - $tongxuat_trc;



$i=0;
$toncuoiky = $tondauky;

if(is_object($listReport)){
	$listReport->MoveFirst();
	ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		if($rowReport['manhap']>0){	//NHAP
			$toncuoiky += $rowReport['number_voucher'];
			echo '<tr bgcolor="#ffffff">';
			echo 	'<td>'.@formatDate2Local($rowReport['ngay'],'dd/mm/yyyy').'</td>';	
			echo 	'<td>'.$rowReport['voucher_id'].'</td><td></td>';	
			echo 	'<td>'.$rowReport['lotid'].'</td>';	
			echo 	'<td>'.@formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy').'</td>';
			echo 	'<td>'.$rowReport['lydo'].'</td>';			
			if($i==0) echo '<td align="right">'.number_format($tondauky).'</td>'; else echo '<td></td>';
			
			echo 	'<td align="right">'.number_format($rowReport['number_voucher']).'</td>';	//nhap
			echo 	'<td></td>';	//xuat
			echo 	'<td align="right">'.number_format($toncuoiky).'</td>';	//ton cuoi
			echo 	'<td></td>';	//ghi chu
			echo '</tr>';
			$i++;
		}else{	//XUAT
			$toncuoiky -= $rowReport['number_voucher'];
			echo '<tr bgcolor="#ffffff">';
			echo 	'<td>'.@formatDate2Local($rowReport['ngay'],'dd/mm/yyyy').'</td>';	
			echo 	'<td></td><td>'.$rowReport['voucher_id'].'</td>';	
			echo 	'<td>'.$rowReport['lotid'].'</td>';	
			echo 	'<td>'.@formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy').'</td>';
			
			if($rowReport['lydo']>0){
				$tramyte = $Pharma->getNameHealthStation($rowReport['lydo']);
				$xuatcho = $tramyte['name'];
			}else $xuatcho = $LDKhoLe;	
			echo 	'<td>'.$xuatcho.'</td>';
			
			if($i==0) echo '<td align="right">'.number_format($tondauky).'</td>'; else echo '<td></td>';
			echo 	'<td></td>';	//nhap
			echo 	'<td align="right">'.number_format($rowReport['number_voucher']).'</td>';	//xuat
			echo 	'<td align="right">'.number_format($toncuoiky).'</td>';	//ton cuoi
			echo 	'<td></td>';	//ghi chu
			echo '</tr>';
			$i++;		
		}
	}		
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();
		
} else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="11">'.$LDItemNotFound.'</td></tr>';
 
$smarty->assign('divItem',$sTempDiv);
 
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" id="select_type" name="select_type" value="'.$select_type.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

//*********************************************************************************

$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
$smarty->assign('pbPrint','<a href="#"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle" onclick="printOut(\''.$select_type.'\',\''.$ngaynhapxuat_gannhat.'\',\''.$todate.'\')"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/khole_thekho.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

