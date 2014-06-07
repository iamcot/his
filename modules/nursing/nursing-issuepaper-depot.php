<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');


# Init
$thisfile= basename(__FILE__);
$breakfile='nursing-manage-medicine.php'.URL_APPEND .'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$fileforward='include/inc_issuepaper_savedepot.php'.URL_APPEND;

if (!isset($target) || $target=='') 
	$target='depot';
/*
$target='sum' => new issue paper with list_selected_pres
$target='depot' => create new issue paper
*/

//Get info of current department, ward
if ($ward_nr!=''){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} elseif ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
	}
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
 $smarty->assign('sToolbarTitle',$LDIssuePaper.' :: '.$LDDepotTab);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDIssuePaper);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");


 # Hide the return button
 $smarty->assign('pbBack',FALSE);

# Collect additional javascript code
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
	document.createdepotform.action="include/inc_issuepaper_savedepot.php";
	document.createdepotform.submit();
}

function insertRow()
{
  var tbl = document.getElementById('tblMedicine');
  var lastRow = tbl.tBodies[0].rows.length;
 
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			//var tbl = document.getElementById('tblMedicine');
			var rowadd =tbl.tBodies[0].insertRow(-1);
			//table = table.substr(0, table.length-8);
			rowadd.innerHTML = xmlhttp.responseText;
			//document.getElementById('tblMedicine').innerHTML = table;

			var tblstt = document.getElementById('tblSTT');
			var laststt = tblstt.tBodies[0].rows.length;
			var row=tblstt.tBodies[0].insertRow(-1);
			row.innerHTML = '<tr><td bgColor="#ffffff" align="center"><a href="javascript:;" onclick="deleteRow('+laststt+')">[x]</a></td><td align="center" bgColor="#ffffff"><input name="stt1" type="text" size=1 value="'+laststt+'" style="text-align:center;border-color:white;border-style:solid;" readonly></td></tr>';
			
		}
	}
	var maxid = document.getElementById('maxid');
	maxid.value = maxid.value*1+1;
	var idnum=maxid.value;
	
	xmlhttp.open("GET","include/inc_issuepaper_addmedicine.php?i="+idnum,true);
	xmlhttp.send();
  
}
function deleteRow(i)
{
  var tbl = document.getElementById('tblMedicine');
  var lastRow = tbl.tBodies[0].rows.length;
  i=i-1+2;
  if (lastRow > i)
	tbl.tBodies[0].deleteRow(i);
	
  var tblstt = document.getElementById('tblSTT');
  var laststt = tblstt.tBodies[0].rows.length;
	tblstt.tBodies[0].deleteRow(laststt-1);
}
function deleteIssue()
{
	var r=confirm("<?php echo $LDWouldDeleteIssue; ?>");
	if (r==true) {
		document.createdepotform.action="<?php echo $fileforward;?>&isdelete=delete";
		document.createdepotform.submit();
	}
}
function startCalc(x){
  interval = setInterval("calc("+x+")",1);
}
function calc(x){
  //sum1 * cost1 = totalcost1;
  a = document.createdepotform['sumpres'+x].value;
  b = document.createdepotform['plus'+x].value; 
  document.createdepotform['sum'+x].value = Number(a)+Number(b);
  
}
function stopCalc(){
  clearInterval(interval);
}

function searchMedicine(id_number)
{
	var win = 'include/search_medicine.php?' + 'id_number=' + id_number;
	window.open(win,'popuppage','width=700,toolbar=1,resizable=1,scrollbars=yes,height=600,top=100,left=100');
	//myWindow.focus();
}
function Medicine_AutoComplete(i){
			var name_med='medicine'+i;
			var includeScript = "include/inc_issuepaper_autocomplete_medicine.php?mode=auto&k="+i;
			new Ajax.Autocompleter(name_med,"hint",includeScript, {
					method:'get',
					paramName:'search',
					afterUpdateElement : setSelectionId				
				}
			);
}
		
function setSelectionId(div,li) {
			var a=li.id;
			var temp_id=a.split('@#');
			var k=temp_id[0];
			document.getElementById('encoder'+k).value = temp_id[1];
			
			var text=div.value; 
			var temp_value=text.split('-- ');
			document.getElementById('medicine'+k).value = temp_value[0];
			document.getElementById('inventory'+k).value = temp_value[4];
			var b=temp_value[3]; 
			var temp_cost=b.split(' vnd/');
			
			document.getElementById('cost'+k).value = temp_cost[0];
			document.getElementById('units'+k).value = temp_cost[1];		
			
			CheckDuplicateMedicine();
			
}

function Fill_Data(i)
{
	var process_file='include/inc_issuepaper_autocomplete_medicine.php?mode=filldata';
	var name_med='medicine'+i;
	
	var xmlhttp;
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
			var a = xmlhttp.responseText.split("@#");
			document.getElementById('encoder'+i).value = a[0];
			document.getElementById('inventory'+i).value = a[1]; 			
			document.getElementById('units'+i).value = a[2];
			document.getElementById('cost'+i).value = a[3];			
			
			//Kiem tra thuoc trung 
			CheckDuplicateMedicine();
		}
	}
	xmlhttp.open("GET",process_file+"&search="+document.getElementById('medicine'+i).value,true);
    xmlhttp.send();

}

//Kiem tra thuoc trung 
function CheckDuplicateMedicine(){
	var n = document.getElementById('maxid').value;		
	var enco_j, enco_k;
	for (j=1; j<=n; j++){
		enco_j = document.getElementById("encoder"+j);
		enco_j.style.backgroundColor="white";
	}
	for (j=1; j<=n; j++){	
		enco_j = document.getElementById("encoder"+j);
		if (enco_j.value!='') {
			for (k=j; k<=n; k++){
				enco_k = document.getElementById("encoder"+k);
				if (k!=j && enco_k.value!='')
					if (enco_j.value==enco_k.value){
						enco_j.style.backgroundColor="gold";
						enco_k.style.backgroundColor="gold";
					}
			}
		}
	}
}

-->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Append the extra javascript to JavaScript block
$smarty->append('JavaScript',$sTemp);


# Load and display the tab
ob_start();
	require('./include/inc_issuepaper_tabs.php');
	$smarty->display('nursing/issuepaper_tab.tpl');
	$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('sTab',$sTemp);


$smarty->assign('sRegForm','<form name="createdepotform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************

$smarty->assign('deptname',$LDDept.': '.$deptname);
$smarty->assign('ward',$LDWard.': '.$wardname);
$smarty->assign('titleForm',$LDISSUEPAPER);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDPresID',$LDMedicineID);
$smarty->assign('LDPresName',$LDMedicineName);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDNumberOf',$LDNumberOf);
$smarty->assign('LDRequest',$LDRequest);
$smarty->assign('LDIssue',$LDIssue);
$smarty->assign('LDNote',$LDNote);
$smarty->assign('LDTotal',$LDTotal);
$smarty->assign('LDInventory',$LDInventory);
$smarty->assign('LDDate',$LDDay);//đã thêm
$smarty->assign('LDTYPE',$LDTypePutIn1);
$smarty->assign('LDTT',$LDTamThan);  //đã thêm


if($target=='depot') 
{
	$smarty->assign('depot',true);
	$smarty->assign('sTypePut','<select name="typeput" class="input1" onChange="alertselected(this)"><option value="0">'.$LDBH.'</option><option value="1" selected>'.$LDNoBH.'</option><option value="2">'.$LDCBTC.'</option></select>');
    $smarty->assign('sTypePutTT','<input type="checkbox" name="cbTT" value="tt" />');
	$smarty->assign('AddRow','<a href="javascript:;" onclick="insertRow();">&nbsp;[+]&nbsp;'.$LDAddRowMedicine.'</a>');
	$type=0; $style=''; $readonly='';
	
	if(!isset($mode) || $mode!='update'){
		$mode='new'; 
		if(!isset($maxid) || $maxid==''){
			$maxid=5; $flag=0;
			$rowIssue='';
		} else {
			$listid = explode('_',$itemid);
			$flag=1;
			require_once($root_path.'include/care_api_classes/class_product.php');
			$Product = new Product;
		}

		$create_id = $_SESSION['sess_user_name'];
		for ($i=1;$i<=$maxid;$i++){
			if($flag) {
				$condition = " AND tatcakhoa.available_product_id='".$listid[$i]."' ";
				if($listIssue = $Product->SearchExpCabinet($dept_nr, $ward_nr, $condition)){
					$rowIssue = $listIssue->FetchRow();
					$rowIssue['units'] = $rowIssue['unit_name_of_medicine'];
					$inventory = $rowIssue['number']; 
					$rowIssue['number_request'] = $rowIssue['number'];
				}
			}
			ob_start();
				require('./include/inc_issuepaper_addmedicine.php');
				$sTempDiv = $sTempDiv.ob_get_contents();
			ob_end_clean();

			$sTempDivStt = $sTempDivStt.'<tr bgColor="#ffffff">
							<td align="center" height="20" ><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
							<td align="center"><input name="stt'.$i.'" type="text" size=1 value="'.$i.'" style="text-align:center;border-color:white;border-style:solid;" readonly></td>
						</tr>';
		}		
		$smarty->assign('divMedicine',$sTempDiv);
		$smarty->assign('divSTT',$sTempDivStt);
		
	} else {	//mode=update
	
		$smarty->assign('IssueId',$LDIssueId.': '.'<input type="text" size=8 name="IssueId" value="'.$issue_id.'" style="text-align:center;border-color:white;border-style:solid;" readonly>');
		
		require_once($root_path.'include/care_api_classes/class_issuepaper.php');
		$IssuePaper = new IssuePaper;
		
		$listIssue = $IssuePaper->getDetailIssuePaperInfo($issue_id);
		if(is_object($listIssue)){
			$sTempDivStt='';$sTempDiv='';
			$maxid=$listIssue->RecordCount();
			for ($i=1;$i<=$maxid;$i++){
				$rowIssue = $listIssue->FetchRow();

				ob_start();
				require('./include/inc_issuepaper_addmedicine.php');
				$sTempDiv = $sTempDiv.ob_get_contents();				
				ob_end_clean();	

				$sTempDivStt.='<tr bgColor="#ffffff">
									<td align="center" height="20" ><a href="javascript:;" onclick="deleteRow('.$i.')">[x]</a></td>
									<td align="center"><input name="stt'.$i.'" type="text" size=1 value="'.$i.'" style="text-align:center;border-color:white;border-style:solid;" readonly></td>
								</tr>';							
			}	
			$create_id= $rowIssue ['nurse'];
			$date_time= $rowIssue ['date_time_create'];
			$sTempDiv = '<div id="myDiv">'.$sTempDiv.'</div>';
			$flag1=''; $flag2=''; $flag3='';
			switch ($rowIssue['typeput']){
					case 0:	//BHYT
						$flag1='selected="selected"';
						break;
					case 1:	//Su nghiep
						$flag2='selected="selected"';
						break;
					case 2: //CBTC
						$flag3='selected="selected"';
						break;	
						
					default:
						$flag2='selected="selected"';
						break;
			}							
		}
		$smarty->assign('sTypePut','<select name="typeput" class="input1" onChange="alertselected(this)"><option value="0" '.$flag1.'>'.$LDBH.'</option><option value="1" '.$flag2.'>'.$LDNoBH.'</option><option value="2" '.$flag3.'>'.$LDCBTC.'</option></select>');
		$smarty->assign('divMedicine',$sTempDiv);
		$smarty->assign('divSTT',$sTempDivStt);
		$smarty->assign('pbDelete','<a href="javascript:deleteIssue()" ><img '.createLDImgSrc($root_path,'delete.gif','0','middle').' title="'.$LDDelete.'" align="middle"></a>');
		
	}

}elseif($target=='sum') {
	$smarty->assign('sum',true);
	$smarty->assign('sTypePut','<select name="typeput" class="input1" onChange="alertselected(this)"><option value="0">'.$LDBH.'</option><option value="1">'.$LDNoBH.'</option><option value="2">'.$LDCBTC.'</option></select>');	
	$smarty->assign('LDPlus',$LDPlus);
	
	$type=1;	//Lay info danh sach cac toa thuoc da chon
	$style=' style="border-color:white;border-style:solid;" '; $readonly=' readonly ';
	
	if(!isset($mode)){
		$mode='new';
		$create_id = $_SESSION['sess_user_name'];
		
		//Lay cau url va lay danh sach pres_id --------------
		$data=getenv(QUERY_STRING); 
		$from=0;
		for($i=0;$i<3;$i++){
			$from =strpos($data, "&");
			$data = substr($data,$from+1,strlen($data));
		}
		
		//Ham tach cac pres_id trong list --------------
		$paramNumber='Number';
		$paramValue='Value';
		$paramName="itemcode"; 
				
		$strCut = "&".$data;

		$from=1; $to=0; $flag = true;
		$list_pres_id = array();
		
		while($flag){
			$tempStr=substr($strCut,$from);
			$to=strpos($tempStr, "&");
			$equalIndex=strpos($tempStr, "=");

			if($to == 0)
				$flag = false;

			$paramValue=substr($tempStr,$equalIndex+1,$to-$equalIndex-1);
			$paramNumber=substr($tempStr,strlen($paramName),$equalIndex - strlen($paramName));

			$from = $from+$to+1;
				
			if(strlen($paramValue)){
				$list_pres_id[$paramNumber-1]=$paramValue;
				$list_id=$list_id.'#'.$paramValue;
			}
		}
		
		//create issue_paper from list pres_id
		include_once($root_path.'include/care_api_classes/class_prescription.php');
		if(!isset($Pres)) $Pres = new Prescription;
		$listIssue = $Pres->sumMedicineByListPresId($list_pres_id);
		if(is_object($listIssue)){
			$sTempDivStt='';$sTempDiv='';
			$maxid=$listIssue->RecordCount();
			
			for ($i=1;$i<=$maxid;$i++){
				$rowIssue = $listIssue->FetchRow();
				//$rowIssue['product_encoder'],$inventory
					$sql = "SELECT allocation_temp FROM care_pharma_products_main WHERE product_encoder='".$rowIssue['product_encoder']."'";
					if($tempinven = $db->Execute($sql))
						if($tempinven->RecordCount()){
							$tempcount=$tempinven->FetchRow();
							$inventory=$tempcount['allocation_temp'];
						}	
				ob_start();
				$rowIssue['number_request']=$rowIssue['sumpres'];
				require('./include/inc_issuepaper_addmedicine_sum.php');
				$sTempDiv = $sTempDiv.ob_get_contents();				
				ob_end_clean();	

				$sTempDivStt.='<tr bgColor="#ffffff">
								<td align="center"><input name="stt'.$i.'" type="text" size=1 value="'.$i.'" style="text-align:center;border-color:white;border-style:solid;" readonly></td>
							   </tr>';			
			}	
			$sTempDiv = '<div id="myDiv">'.$sTempDiv.'</div>';		
		}
		$smarty->assign('divMedicine',$sTempDiv);
		$smarty->assign('divSTT',$sTempDivStt);
		
	} else { //update
	
		$smarty->assign('IssueId',$LDIssueId.': '.'<input type="text" size=8 name="IssueId" value="'.$issue_id.'" style="text-align:center;border-color:white;border-style:solid;" readonly>');
		
		require_once($root_path.'include/care_api_classes/class_issuepaper.php');
		$IssuePaper = new IssuePaper;
		
		$listIssue = $IssuePaper->getDetailIssuePaperInfo($issue_id);
		if(is_object($listIssue)){
			$sTempDivStt='';$sTempDiv='';
			$maxid=$listIssue->RecordCount();
			for ($i=1;$i<=$maxid;$i++){
				$rowIssue = $listIssue->FetchRow();
				//$rowIssue['product_encoder'],$inventory
					$sql = "SELECT allocation_temp FROM care_pharma_products_main WHERE product_encoder='".$rowIssue['product_encoder']."'";
					if($tempinven = $db->Execute($sql))
						if($tempinven->RecordCount()){
							$tempcount=$tempinven->FetchRow();
							$inventory=$tempcount['allocation_temp'];
						}
				$style=' style="border-color:white;border-style:solid;" '; $readonly=' readonly ';
				
				ob_start();
				require('./include/inc_issuepaper_addmedicine_sum.php');
				$sTempDiv = $sTempDiv.ob_get_contents();				
				ob_end_clean();	
				
				$sTempDivStt.='<tr bgColor="#ffffff">
									<td align="center" ><input name="stt'.$i.'" type="text" size=1 value="'.$i.'" style="text-align:center;border-color:white;border-style:solid;" readonly></td>
								   </tr>';						
			}	
			$create_id= $rowIssue ['nurse'];
			$date_time= $rowIssue ['date_time_create'];
			$sTempDiv = '<div id="myDiv">'.$sTempDiv.'</div>';
			$flag1=''; $flag2=''; $flag3='';
			switch ($rowIssue['typeput']){
					case 0:	//BHYT
						$flag1='selected="selected"';
						break;
					case 1:	//Su nghiep
						$flag2='selected="selected"';
						break;
					case 2: //CBTC
						$flag3='selected="selected"';
						break;	
						
					default:
						$flag2='selected="selected"';
						break;
			}
		}
		
		$target='sum';$type=1;
		$smarty->assign('AddRow','');
		
		$smarty->assign('sTypePut','<select name="typeput" class="input1" onChange="alertselected(this)"><option value="0" '.$flag1.'>'.$LDBH.'</option><option value="1" '.$flag2.'>'.$LDNoBH.'</option><option value="2" '.$flag3.'>'.$LDCBTC.'</option></select>');		
		$smarty->assign('divMedicine',$sTempDiv);
		$smarty->assign('divSTT',$sTempDivStt);
		$smarty->assign('pbDelete','<a href="javascript:deleteIssue()" ><img '.createLDImgSrc($root_path,'delete.gif','0','middle').' title="'.$LDDelete.'" align="middle"></a>');
		
	
	}
}

//*********************************************************************************

include_once($root_path.'include/core/inc_date_format_functions.php');
//mp
if($date_time=='')
    $date_time=date('Y-m-d');
//gjergji : new calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
//echo $date_format;
$smarty->assign('sCalendar',$calendar->show_calendar($calendar,$date_format,'date',$date_time));

//$smarty->assign('sCalendar',$calendar->show_calendar($calendar,$date_format,'date_time',''));
//$smarty->assign('calendar',$LDDateIssue.': '.$calendar->show_calendar($calendar,$date_format,'dateissue',date('Y-m-d')));
//end gjergji

//$rowIssue ['note'];
$smarty->assign('UserName',$LDUserIssue.': '.$create_id);
$smarty->assign('NoteOfCreator',$LDNoteOfCreator.':<br> <textarea name="notecreator" cols="50" rows="2" wrap="physical" >'.$rowIssue['generalnote'].'</textarea>');

$smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

//$smarty->assign('test',$target.' '.$data.' '.$mode.' '.$ward_nr.' '.$maxid);


//sHiddenInputs                đã xóa dòng name="date_time" , sau name="mode"
	$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" id="maxid" name="maxid" value="'.$maxid.'">
		<input type="hidden" name="type" value="'.$type.'">
		<input type="hidden" name="mode" value="'.$mode.'">
        <input type="hidden" name="date_time" value="'.$date_time.'">
		<input type="hidden" name="target" value="'.$target.'">
		<input type="hidden" name="list_presid" value="'.$list_id.'">
		<input type="hidden" name="create_id" value="'.$create_id.'">
		<input type="hidden" name="ward_nr" value="'.$ward_nr.'">
		<input type="hidden" name="dept_nr" value="'.$dept_nr.'">';

	$smarty->assign('sHiddenInputs',$sTempHidden);


# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','nursing/issuepaper_depot.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>