<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$tb_property='care_property';
$tb_property_use='care_property_use';
$breakfile='property-admi-welcome.php'.URL_APPEND;
$lang_tables[]='departments.php';
define('MAXBLOCKROW',10);
define('LANG_FILE','properties.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
require_once($root_path.'include/care_api_classes/class_ward.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

if($mode){
	if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
	if($dblink_ok){
		switch($mode)
		{	
			case 'add':
				$_POST['im_date']=@formatDate2STD($_POST['im_date'],$date_format);
				$_POST['use_date']=@formatDate2STD($_POST['use_date'],$date_format);
				if($property->addNewPropertyTranSmitting($_POST)){
					header('location:'.$root_path."modules/property/property-using-history.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr");
				} else {
					$smarty->assign('actionnotation',$FailTransmit);
				}
				exit;
			break;
			case 'modify':
				if(isset($edited) && ($edited == 'yes')){
					$_POST['im_date']=@formatDate2STD($_POST['im_date'],$date_format);
					$_POST['use_date']=@formatDate2STD($_POST['use_date'],$date_format);
					if($property->updatePropertyUseInfo($using_nr, $_POST)) $smarty->assign('actionnotation',$SuccessTransmit);
					else $smarty->assign('actionnotation',$FailTransmit);
				}
			break;
		}// end of switch
	}else{echo "$LDDbNoLink<br>";} 
}

# Added for the common header top block
$smarty->assign('sToolbarTitle',"$LDPropertyManagement::$LDTransmiting");
$smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
# href for close button
$smarty->assign('breakfile',$breakfile);
# Window bar title
$smarty->assign('sWindowTitle',"$LDPropertyManagement::$LDTransmiting");

ob_start();
?>
<script language="javascript">
	function check(d)
	{
		if((d.dept.value=="-1") || (d.manager.value=="") || (d.im_status.value=="") )
		{
			alert("<?php echo $LDAlertIncomplete ?>");
			return false;
		}
		if(d.im_date.value==""){
			alert("<?php echo $LDPlsEnterDate; ?>");
			d.im_date.focus();
			return false;
		}
	}
	function popSearchWin(target,obj_val,obj_name){
		urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
		DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
	}
	function changeDept(newdept, ward_nr){
		$.ajax({
			type: "POST",
			url: "<?php echo $root_path;?>modules/property/changeDepartment.php",
			data: "dept_nr="+newdept+"&ward_nr="+ward_nr,
			success: function(result)
			{
				if(result == 'dberror'){
					alert("<?php echo $LDAlertWardGettingError; ?>");
				}else {
					document.getElementById("slbward").innerHTML = result;
				}
			}
		});
	}
	function changeWard(newward, room_nr){
		$.ajax({
			type: "POST",
			url: "<?php echo $root_path;?>modules/property/changeWard.php",
			data: "ward_nr="+newward+"&room_nr"+room_nr,
			success: function(result)
			{
				if(result == 'dberror'){
					alert("<?php echo $LDAlertRoomGettingError; ?>");
				}else {
					document.getElementById("slbroom").innerHTML = result;
				}
			}
		});
	}
	
<?php
require($root_path.'include/core/inc_checkdate_lang.php');
?>
</script>
<?php
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

$smarty->assign('LDSubmitLink', $root_path."modules/property/property-transmit.php".URL_REDIRECT_APPEND);
$smarty->assign('LDPropFormalName',$LDPropFormalName);
$smarty->assign('LDPropShortName',$LDPropShortName);
$smarty->assign('LDPropModel',$LDPropModel);
$smarty->assign('LDPropSerieNr',$LDPropSerieNr);
$smarty->assign('LDOldDept',$LDOldDept);
$smarty->assign('LDOldWard',$LDOldWard);
$smarty->assign('LDOldRoom',$LDOldRoom);
$smarty->assign('LDOldManager',$LDOldManager);
$smarty->assign('LDOldFunction',$LDOldFunction);
$smarty->assign('LDNewDept',$LDNewDept);
$smarty->assign('LDNewWard',$LDNewWard);
$smarty->assign('LDNewRoom',$LDNewRoom);
$smarty->assign('LDNewManager',$LDNewManager);
$smarty->assign('LDNewFunction',$LDNewFunction);
$smarty->assign('LDImDate',$LDImportDate);
$smarty->assign('LDImpStatus',$LDImportStatus);
$smarty->assign('LDReason',$LDReason);
$smarty->assign('LDUsingDate',$LDUseDate);
$smarty->assign('LDCurrentStatus',$LDCurrentStatus);
$smarty->assign('LDinstruction',$LDinstruction);
$smarty->assign('sRequest',$sRequest);
$smarty->assign('sColor','style="color:red;"');

$propitems = array('name_formal','name_short','model', 'serie', 'status');
$prop_info = $property->getInfomationOfProp($propitems, $prop_nr);
$smarty->assign('propformalname',$prop_info['name_formal']);
$smarty->assign('propshortname',$prop_info['name_short']);
$smarty->assign('propmodel',$prop_info['model']);
$smarty->assign('propserie',$prop_info['serie']);

if(!isset($mode)) $prop_last_using_info = $property->getLastestTransmitting($prop_nr);
if($mode == 'modify'){
	if(!isset($edited)) {
		$prop_last_using_info = $property->getTransmittingInfo($pre_use);
		$prop_using_info = $property->getTransmittingInfo($using_nr);
	}
}

$dept_info = &$dept_obj->getDeptAllInfo($prop_last_using_info['dept']);
$ward_items = array("ward_id","name");
$ward_obj=new Ward($prop_last_using_info['ward']);
$ward_info = $ward_obj->getWardsItemsArray($ward_items); 
$room_info = $ward_obj->getRoomsNumber($prop_last_using_info['room']);
$personell_info = $personell_obj->getPersonellName($prop_last_using_info['manager']); 
$smarty->assign('oldept',$$dept_info['LD_var']);
$smarty->assign('oldward',$ward_info['ward_id']." - ".$ward_info['name']);
$smarty->assign('oldroom',$room_info['room_nr']);
$smarty->assign('oldmanager',$personell_info['nr']." - ".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']);
$smarty->assign('oldfunction',$prop_last_using_info['function']);

$dept_info = &$dept_obj->getAllActive(-1);
$smarty->assign('LDPlsSelectDept',$LDPlsSelectDept);
$smarty->assign('LDPlsSelectWard',$LDPlsSelectWard);
$smarty->assign('sSelectIcon','<img '.createComIcon($root_path,'l_arrowgrnsm.gif','0').'>');
$sTemp = '<select name="dept" onchange="changeDept(this.value, '.($prop_using_info['ward']?$prop_using_info['ward']:-1).')">
			<option value="-1"> </option>';
if($dept_info&&is_array($dept_info)){
	while(list($x,$v)=each($dept_info)){
		$sTemp = $sTemp.'	
		<option value="'.$v['nr'].'"';
		if($v['nr']==$prop_using_info['dept']) $sTemp = $sTemp.' selected';
			$sTemp = $sTemp.'>';
		if(isset($$v['LD_var']) && $$v['LD_var']) $sTemp = $sTemp.$$v['LD_var'];
			else $sTemp = $sTemp.$v['name_formal'];
		$sTemp = $sTemp.'</option>';
	}
}
$sTemp = $sTemp.'</select>';

$smarty->assign('sDeptSelectBox',$sTemp);
if($mode == "modify"){
	$ward_items = array("nr","ward_id","name");
	$ward_obj=new Ward($prop_using_info['ward']);
	$ward_info = $ward_obj->getWardsItemsArray($ward_items); 
	$room_info = $ward_obj->getRoomsNumber($prop_using_info['room']);
}

$sTemp = '<select name="ward" id="slbward" onchange="changeWard(this.value, '.($prop_using_info["room"]?$prop_using_info["room"]:-1).')"></select>';
$smarty->assign('sWardSelectBox', $sTemp);
$sTemp = "";
if($mode=='modify') {
	$sTemp ='<script language="javascript">';
	$sTemp .="changeDept(".$prop_using_info['dept'].", ".$prop_using_info['ward'].");";
	$sTemp .='</script>';
}
$smarty->append('JavaScript',$sTemp);
$sTemp = "";
//$sTemp .= ($mode=='modify'?'<option value="'.$ward_info["nr"].'">'.$ward_info["ward_id"].' - '.$ward_info["name"].'</option>':'').'</select>';
//$smarty->assign('sWardSelectBox', $sTemp);
$sTemp = '<select name="room" id="slbroom"></select>';
$smarty->assign('sRoomSelectBox', $sTemp);
$sTemp = "";
if($mode=='modify') {
	$sTemp ='<script language="javascript">';
	$sTemp .="changeWard((".$prop_using_info['ward'].", ".$prop_using_info["room"].");";
	$sTemp .='</script>';
}
$smarty->append('JavaScript',$sTemp);
$sTemp = "";
//$sTemp .= ($mode=='modify'?'<option value="'.$room_info["nr"].'">'.$room_info["room_nr"].'</option>':'').'</select>';
//$smarty->assign('sRoomSelectBox', $sTemp);
$sBuff ="<a href=\"javascript:popSearchWin('referrer_dr')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
$personell_info = $personell_obj->getPersonellName($prop_using_info['manager']); 
$smarty->assign('newmanager','<input name="manager" type="text" readonly="readonly" style="width:30%;" value="'.$personell_info['nr'].'">&nbsp;&nbsp;<input name="newmanagername" type="text" readonly="readonly" style="width:50%;" value="'.$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first'].'">&nbsp;&nbsp;'.$sBuff);
$smarty->assign('impstatus',$prop_using_info['im_status']);
$smarty->assign('reason',$prop_using_info['reason']);
$smarty->assign('newfunction',$prop_using_info['function']);
$smarty->assign('importdate',$calendar->show_calendar($calendar,$date_format,'im_date',$prop_using_info['im_date']));
$smarty->assign('usingdate',$calendar->show_calendar($calendar,$date_format,'use_date',$prop_using_info['use_date']));
$smarty->assign('currentreason',($mode == 'modify'?$prop_using_info['current_status']:$prop_last_using_info['current_status']));

$smarty->assign('sCancel','<a style="float:left;" href="javascript:history.back()" class="butcancel"><img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0"></a>');
$smarty->assign('sSaveButton','<input type="hidden" name="sid" value="'.$sid.'">
<input type="hidden" name="mode" value="'.($mode=="modify"?$mode:"add").'">
<input type="hidden" name="using_nr" value="'.($mode=="modify"?$using_nr:"noedit").'">
<input type="hidden" name="prop_nr" value="'.$prop_nr.'">
<input type="hidden" name="pre_use" value="'.$pre_use.'">
<input type="hidden" name="edited" value="yes">
<input type="hidden" name="lang" value="'.$lang.'">
<input class="butadd" type="submit" value="">
');
//<input class="butbg" type="submit" style="float:left; margin-left: 10px;" value="'.($mode!='modify'?$LDSubmitTransmit:$LDSubmitEditTransmit).'">
$sTemp = "<tr>
			<th class='adm_item' style='width:4%;'>$LDView</th>
			<th class='adm_item' style='width:4%;'>$LDOrder</th>
			<th class='adm_item' style='width:12%;'>$LDDeptName</th>
			<th class='adm_item' style='width:5%;'>$LDWardName</th>
			<th class='adm_item' style='width:5%;'>$LDRoomName</th>
			<th class='adm_item' style='width:12%;'>$LDManager</th>
			<th class='adm_item' style='width:8%;'>$LDImportDate</th>
			<th class='adm_item' style='width:10%;'>$LDReason</th>
			<th class='adm_item' style='width:10%;'>$LDImportStatus</th>
			<th class='adm_item' style='width:8%;'>$LDUseDate</th>
			<th class='adm_item' style='width:10%;'>$LDFunction</th>
			<th class='adm_item' style='width:12%;'>$LDCurrentStatus</th>
		 </tr>";
$query="SELECT count(nr) FROM $tb_property_use WHERE prop_nr = $prop_nr order by nr desc";
$rows=$property->countResultRows($query);
$rowperpage=MAXBLOCKROW;
$pagination=ceil($rows/$rowperpage);
if(!isset($page)){
    $page = 1 ;
}
$start = ($page-1) * $rowperpage;

$propitems = array('nr','dept','ward', 'room', 'manager', 'im_date', 'im_status', 'use_date', 'function', 'current_status', 'reason', 'pre_use');
$query="SELECT ";
$querytmp = "";
while (list($key, $val) = each($propitems)) {
	$querytmp .= $val . ", ";
}
$query.=substr($querytmp,0,-2);
$query.= " FROM $tb_property_use WHERE prop_nr = $prop_nr ";
$query .= " ORDER BY nr DESC";
$propusinglist=$property->getPropertyItemsObject($query, $start, $rowperpage);

$toggle=0;
if($propusinglist != false){
	while($row=$propusinglist->FetchRow()){
		if($toggle)	$trc='#dedede';
		else $trc='#efefef';
		$toggle=!$toggle;
		$dept_info = &$dept_obj->getDeptAllInfo($row['dept']);
		$ward_obj = new Ward($row['ward']);
		$ward_items = array("ward_id","name");
		$ward_info = $ward_obj->getWardsItemsArray($ward_items);
		$room_info = $ward_obj->getRoomsNumber($row['room']);
		$personell_info = $personell_obj->getPersonellName($row['manager']); 
		$sTemp .= "<tr bgcolor='$trc'><td style='text-align:center;padding: 2px 5px 2px 5px;'><a href='".$root_path."modules/property/property-operating-history.php".URL_REDIRECT_APPEND."&using_nr=".$row['nr']."' title='$LDViewTransmitTitle'><img ".createComIcon($root_path,'bul_arrowblusm.gif','0','absmiddle')."></a></td>";
		$sTemp .= "<td style='text-align:right;padding: 2px 5px 2px 5px;'><a href='".$root_path."modules/property/property-transmit.php".URL_REDIRECT_APPEND."&mode=modify&prop_nr=$prop_nr&pre_use=".$row['pre_use']."&using_nr=".$row['nr']."' title='$LDModifyTransmitTitle'> ".$prop_info['name_short'].$row['nr']."</a></td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$$dept_info['LD_var']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$ward_info['ward_id']." - ".$ward_info['name']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$room_info['room_nr']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$personell_info['nr']." - ".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".formatDate2Local($row['im_date'], $date_format)."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['reason']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['im_status']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".formatDate2Local($row['use_date'], $date_format)."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['function']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['current_status']."</td></tr>";
	}
}
$smarty->assign('propertylis',$sTemp);
$pagingurl = $root_path."modules/property/property-using-history.php".URL_REDIRECT_APPEND."&mode=changepage";
require_once('Pagenation.php');
$smarty->assign('pagelist',$sTemp);
$smarty->assign('sMainBlockIncludeFile','property/property_transmit.tpl');
$smarty->display('common/mainframe.tpl');
?>