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

if (!isset($mode) || empty($mode)) {
	$mode='';
	$pday='';
	$pmonth='';
	$pyear='';
	$rows='';
	$ward_id='';
	$description='';
	$room_nr_start='';
	$room_nr_end='';
	$roomprefix='';
	$dept_nr='';
	$dept_nr='';
	$edit='';
	$name='';
}

$lang_tables[]='departments.php';
define('LANG_FILE','nursing.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
/* Load the ward object */
require_once($root_path.'include/care_api_classes/class_ward.php');
$ward=new Ward;
/* Load the dept object */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept=new Department;

$breakfile='nursing-station-manage.php'.URL_APPEND;

if($pday=='') $pday=date('d');
if($pmonth=='') $pmonth=date('m');
if($pyear=='') $pyear=date('Y');
$t_date=$pday.'.'.$pmonth.'.'.$pyear;

if($mode){
	$dbtable='care_ward';
			
	if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
	if($dblink_ok){
		switch($mode)
		{	
			case 'create': 
                        //if(($name!=NULL) || ($ward_id!=NULL) || ($dept_nr!=NULL) || ($description!=NULL) || ($room_nr_start < 1) || ($room_nr_end < 1) || ($room_nr_start > $room_nr_end))
                            if(($name!=NULL) || ($ward_id!=NULL) || ($dept_nr!=NULL) || ($description!=NULL) || ($room_nr_start<1) || ($room_nr_start > $room_nr_end))
                            {
                                if(!$ward->checkWardExist($dept_nr, $name, $ward_id)) {
                            //if(!$ward->IDExists($ward_id)){				
                                    if($ergebnis=$ward->saveWard($_POST)){
                                            if($dbtype=='mysql'){
                                                    $ward_nr=$db->Insert_ID();
                                            }else{
                                                    $ward_nr=$ward->postgre_Insert_ID($dbtable,'nr',$db->Insert_ID());
                                            }
                                            header("location:nursing-station-new-createbeds.php?sid=$sid&lang=$lang&ward_nr=$ward_nr");
                                            exit;
                                    }else{echo "$sql<br>$LDDbNoSave";}
                            //}else{ $ward_exists=true; exit;}
                                } else {
                                        echo "$LDAlertWardExist";
                                        exit;
                                }
                        } else {
                                echo "$LDAlertInvalidInput";
                                exit;
                        }
                        break;
		}// end of switch
	}else{echo "$LDDbNoLink<br>";} 
}else{
	//$depts=&$dept->getAllMedical();
    $depts=&$dept->cot_getAllDept();
}

# Start the smarty templating
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('nursing');

# Added for the common header top block

 $smarty->assign('sToolbarTitle',"$LDCreate::$LDNewStation");

 $smarty->assign('pbHelp',"javascript:gethelp('nursing_ward_mng.php','new')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDCreate::$LDNewStation");

# Buffer page output

ob_start();

?>
<style type="text/css" name="formstyle">

td.pblock{ font-family: verdana,arial; font-size: 12px;padding:5px;}
div.box { border: solid; border-width: thin; width: 100% }
div.pcont{ margin-left: 3; }


</style>

<script language="javascript">
<!-- 
function check(d)
{
	if(d.name.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		d.name.focus();
		return false;
	}
	if(d.ward_id==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		d.ward_id.focus();
		return false;
	}
	if(d.dept_nr.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		d.dept_nr.focus();
		return false;
	}
	/*if(d.description.value=="")
	{
		alert("<?php echo $LDAlertIncomplete ?>");
		d.description.focus();
		return false;
	}*/
	if(d.room_nr_start.value=="")
	{
		alert("<?php echo $LDAlertIncomplete ?>");
		d.room_nr_start.focus();
		return false;
	}
	if(d.room_nr_end.value=="")
	{
		alert("<?php echo $LDAlertIncomplete ?>");
		d.room_nr_end.focus();
		return false;
	}
	if(parseInt(d.room_nr_start.value)>parseInt(d.room_nr_end.value)) 
	{
		alert("<?php echo $LDAlertRoomNr ?>");
		return false;
	}
}
function checkName(name){
	var dept_nr = document.getElementById("sltDept").value;
	if((name.value != '<?php echo $name; ?>') && (name.value != "")){
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
				if(xmlhttp.responseText == "1") {
					alert("<?php echo $LDAlertWardNameExist; ?>");
					return false;
				}
			}
		}
		xmlhttp.open("GET","<?php echo $root_path;?>modules/nursing/checkWardNameExist.php?name="+name.value+"&dept_nr="+dept_nr,true);
		xmlhttp.send();
	}
}
function checkWardID(wardid){
	var dept_nr = document.getElementById("sltDept").value;
	if((wardid.value != '<?php echo $ward_id; ?>') && (wardid.value != "")){
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
				if(xmlhttp.responseText == "1") {
					alert("<?php echo $LDAlertWardIdExist; ?>");
					return false;
				}
			}
		}
		xmlhttp.open("GET","<?php echo $root_path;?>modules/nursing/checkWardIdExist.php?wardid="+wardid.value+"&dept_nr="+dept_nr,true);
		xmlhttp.send();
	}
}
function checkSelectDept(sltdept){
	var wardname = document.getElementById("tbxwardname");
	var wardid = document.getElementById("tbxwardid");
	if(sltdept.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		return false;
	}else {
		if((wardname.value != '<?php echo $name; ?>') && (wardname.value != ""))  checkName(wardname);
		if((wardid.value != '<?php echo $ward_id; ?>') && (wardid.value != "")) checkWardID(wardid);
	}
}
/*function checkDescriptionText(aradescription){
	if(aradescription.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		return false;
	}
}*/
function changefirstNr(firstroom){
	var room_nr_end = document.getElementById("tbx_room_nr_end").value;
	if(parseInt(firstroom.value) < 1){
		alert("<?php echo $LDAlertRoomNrOver0; ?>");
		return false;
	}
	if(parseInt(firstroom.value) > parseInt(room_nr_end)){
		alert("<?php echo $LDAlertFirstNrLargerSecondNr; ?>");
		return false;
	}
	if(isNaN(firstroom.value)) {
		alert("<?php echo $LDAlertRoomNrisNumber; ?>");
		return false;
	}
}
function changesecondNr(lastroom){
	var room_nr_start = document.getElementById("tbx_room_nr_start").value;
	if(parseInt(lastroom.value) < 1){
		alert("<?php echo $LDAlertRoomNrOver0; ?>");
		return false;
	}
	if(parseInt(lastroom.value) < parseInt(room_nr_start)){
		alert("<?php echo $LDAlertFirstNrLargerSecondNr; ?>");
		return false;
	}
	if(isNaN(lastroom.value)) {
		alert("<?php echo $LDAlertRoomNrisNumber; ?>");
		return false;
	}
}
// -->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Assign prompt elements
if($rows){
	$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_r.gif','0','bottom').' align="absmiddle">');
	$smarty->assign('sStationExists',str_replace("~station~",strtoupper($station),$LDStationExists));
}

$smarty->assign('LDEnterAllFields',$LDEnterAllFields);

# Assign form items
$smarty->assign('LDStation',$LDStation);
$smarty->assign('LDWard_ID',$LDWard_ID);
$smarty->assign('LDDept',$LDDept);
$smarty->assign('LDPlsSelect',$LDPlsSelect);
$smarty->assign('LDNoSpecChars',$LDNoSpecChars);
$smarty->assign('LDDescription',$LDDescription);
$smarty->assign('LDRoom1Nr',$LDRoom1Nr);
$smarty->assign('LDRoom2Nr',$LDRoom2Nr);
$smarty->assign('LDRoomPrefix',$LDRoomPrefix);
$smarty->assign('LDIsPatientArea',$LDIsPatientArea);
$smarty->assign('sSelectIcon','<img '.createComIcon($root_path,'l_arrowgrnsm.gif','0').'>');
$smarty->assign('sRequest',"(*)");
$smarty->assign('sColor','style="color:red;"');

# Assign input values
$smarty->assign('inputwardname',"<input type='text' name='name' id='tbxwardname' size=20 maxlength=40 value='$name' onblur='checkName(this)'>");
$smarty->assign('inputwardid',"<input type='text' name='ward_id' id='tbxwardid' size=20 maxlength=40 value='$ward_id' onblur='checkWardID(this)' style='text-transform:uppercase;'>");
$smarty->assign('description',$description);
$smarty->assign('room_nr_start',$room_nr_start);
$smarty->assign('room_nr_end',$room_nr_end);
$smarty->assign('roomprefix',$roomprefix);

$wardtype = $ward->getWardTypeList(NULL);
$wardtypelist = "";
while($row = $wardtype->FetchRow()){
	$wardtypelist .= "<input type='radio' name='type' value='".$row['nr']."' />".$row['name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
}
$smarty->assign('LDIsPatientAreaRadiobtn',$wardtypelist);

# Create department select box
$sTemp = '<select name="dept_nr" id="sltDept" onblur="checkSelectDept(this)">
			<option value=""> </option>';
if($depts&&is_array($depts)){
	while(list($x,$v)=each($depts)){
		$sTemp = $sTemp.'	
		<option value="'.$v['nr'].'"';
		if($v['nr']==$dept_nr) $sTemp = $sTemp.' selected';
		$sTemp = $sTemp.'>';
		if(isset($$v['LD_var']) && $$v['LD_var']) $sTemp = $sTemp.$$v['LD_var'];
			else $sTemp = $sTemp.$v['name_formal'];
		$sTemp = $sTemp.'</option>';
	}
}
$sTemp = $sTemp.'
	</select>';

$smarty->assign('sDeptSelectBox',$sTemp);

$smarty->assign('sCancel','<a href="javascript:history.back()" class="butcancel"><img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0"></a>');
$smarty->assign('sSaveButton','<input type="hidden" name="sid" value="'.$sid.'">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="edit" value="'.$edit.'">
<input type="hidden" name="lang" value="'.$lang.'">
<input type="submit" class="butadd" value="">');
//'.$LDCreateStation.'
$smarty->assign('sMainBlockIncludeFile','nursing/ward_create_form.tpl');

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
