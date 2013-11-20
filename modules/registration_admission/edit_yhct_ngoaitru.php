<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
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

$thisfile=basename(__FILE__);

define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/care_api_classes/class_khambenh.php');
$lang_tables[]='person.php';
$lang_tables[]='prompt.php';
define('LANG_FILE','aufnahme.php');
$obj=new Khambenh;
$obj->KhamYHCTNgoaitru();
require($root_path.'include/core/inc_front_chain_lang.php');
//$db->debug=1;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($encounter_nr);
$enc_obj->loadEncounterData();
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
require('./include/init_show.php');
# Get encounter class
$enc_class=$enc_obj->EncounterClass();
if($mode=='save'){
$_POST['encounter_nr']=$encounter_nr;
	
	$_POST['history']='Entry: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'];
	$_POST['modify_time']=date('H:i:s');
	$_POST['modify_id']=$_SESSION['sess_user_name'];
	$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	$obj->setDataArray($_POST);
	if($obj->updateDataFromInternalArray($nr)) {
	$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
	header("location:show_yhct_ngoaitru.php".URL_REDIRECT_APPEND."&target=$target&mode=details&encounter_nr=".$encounter_nr."&nr=".$nr);
										exit;
	}
}
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

if($parent_admit) $sTitleNr= ($_SESSION['sess_full_en']);
	else $sTitleNr = ($_SESSION['sess_full_pid']);

# Title in the toolbar
 $smarty->assign('sToolbarTitle',"$page_title $encounter_nr");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPatientRegister')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',"$page_title $encounter_nr");

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('medocs_entry.php')");

  # href for return button
 $smarty->assign('pbBack',$returnfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=show&type_nr='.$type_nr);


# Buffer extra javascript code

ob_start();

require_once ('../../js/jscalendar/calendar.php');
	$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
	$calendar->load_files();

?>
<script language="javascript">
<!-- Script Begin
function popDocPer(target,obj_val,obj_name){
			urlholder="./personell_search1.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}
		$(function(){
$("#f-calendar-field-1").mask("99/99/9999");

});	
//  Script End -->
</script>
<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('sDocsJavaScript',$sTemp);
	//$smarty->assign('sYesRadio',"<input type='radio' name='short_notes' value='marre_keshille_mjekesore'>");
	//$smarty->assign('sNoRadio',"<input type='radio' name='short_notes' value=''>");

	//gjergji : new calendar
	
	$smarty->assign('sDateMiniCalendar',$calendar->show_calendar($calendar,$date_format,'date'));
	//end gjergji
	$smarty->assign('TP_user_name',$_SESSION['sess_user_name']);

	
$smarty->append('JavaScript',$sTemp);

require('./gui_bridge/default/gui_tabs_medocs.php');

if($enc_obj->Is_Discharged()){ 

	$smarty->assign('is_discharged',TRUE);
	$smarty->assign('sWarnIcon',"<img ".createComIcon($root_path,'warn.gif','0','absmiddle').">");
	$smarty->assign('sDischarged',$LDPatientIsDischarged);

}

# Set the table columns� classes
$smarty->assign('sClassItem','class="adm_item"');
$smarty->assign('sClassInput','class="adm_input"');

$smarty->assign('LDCaseNr',$LDAdmitNr);

$smarty->assign('sEncNrPID',$_SESSION['sess_en']);

$smarty->assign('img_source',"<img $img_source>");

$smarty->assign('LDTitle',$LDTitle);
$smarty->assign('title',$title);
$smarty->assign('LDLastName',$LDLastName);
$smarty->assign('name_last',$name_last);
$smarty->assign('LDFirstName',$LDFirstName);
$smarty->assign('name_first',$name_first);

# If person is dead show a black cross and assign death date

if($death_date && $death_date != DBF_NODATE){
	$smarty->assign('sCrossImg','<img '.createComIcon($root_path,'blackcross_sm.gif','0').'>');
	$smarty->assign('sDeathDate',@formatDate2Local($death_date,$date_format));
}

	# Set a row span counter, initialize with 7
	$iRowSpan = 7;

	if($GLOBAL_CONFIG['patient_name_2_show']&&$name_2){
		$smarty->assign('LDName2',$LDName2);
		$smarty->assign('name_2',$name_2);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_3_show']&&$name_3){
		$smarty->assign('LDName3',$LDName3);
		$smarty->assign('name_3',$name_3);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_middle_show']&&$name_middle){
		$smarty->assign('LDNameMid',$LDNameMid);
		$smarty->assign('name_middle',$name_middle);
		$iRowSpan++;
	}

$smarty->assign('sRowSpan',"rowspan=\"$iRowSpan\"");

$smarty->assign('LDBday',$LDBday);
$smarty->assign('sBdayDate',@formatDate2Local($date_birth,$date_format));

$smarty->assign('LDSex',$LDSex);
if($sex=='m') $smarty->assign('sSexType',$LDMale);
	elseif($sex=='f') $smarty->assign('sSexType',$LDFemale);

$smarty->assign('LDBloodGroup',$LDBloodGroup);
if($blood_group){
	$buf='LD'.$blood_group;
	$smarty->assign('blood_group',$$buf);
}

$smarty->assign('LDKhambenhYHCT',$LDKhambenhYHCT);
$smarty->assign('LDVongchan',$LDVongchan);
$smarty->assign('LDVanchan',$LDVanchan);
$smarty->assign('LDVanchan1',$LDVanchan1);
$smarty->assign('LDThietchan',$LDThietchan);
$smarty->assign('LDChandoan',$LDChandoan);
$smarty->assign('LDBenhdanh',$LDBenhdanh);
$smarty->assign('LDBatcuong',$LDBatcuong);
$smarty->assign('LDTangphu',$LDTangphu);
$smarty->assign('LDNguyennhan',$LDNguyennhan);
$smarty->assign('LDDieutri',$LDDieutri);
$smarty->assign('LDPhepchua',$LDPhepchua);
$smarty->assign('LDPhuongthuoc',$LDPhuongthuoc);
$smarty->assign('LDPhuonghuyet',$LDPhuonghuyet);
$smarty->assign('LDXoabop',$LDXoabop);
$smarty->assign('LDChedoan',$LDChedoan);
$smarty->assign('LDChedoholy',$LDChedoholy);
$smarty->assign('LDTienluong',$LDTienluong);
$smarty->assign('LDDate',$LDDate);
$smarty->assign('LDBy',$LDBy);
$sql="SELECT *
		FROM 	care_benhan_ngoaitru_yhct
		WHERE   nr=$nr";
if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()) $row=$result->FetchRow();
	}else{
		echo $sql;
	}
	
if(!empty($row['vongchan'])) $smarty->assign('sVongchan',$row['vongchan']);
		if(!empty($row['vanchan'])) $smarty->assign('sVanchan',$row['vanchan']);
		if(!empty($row['van_chan'])) $smarty->assign('sVanchan1',$row['van_chan']);
		if(!empty($row['thietchan'])) $smarty->assign('sThietchan',$row['thietchan']);
		if(!empty($row['benhdanh'])) $smarty->assign('sBenhdanh',$row['benhdanh']);
		if(!empty($row['batcuong'])) $smarty->assign('sBatcuong',$row['batcuong']);
		if(!empty($row['tangphu'])) $smarty->assign('sTangphu',$row['tangphu']);
		if(!empty($row['nguyennhan'])) $smarty->assign('sNguyennhan',$row['nguyennhan']);
		if(!empty($row['phepchua'])) $smarty->assign('sPhepchua',$row['phepchua']);
		if(!empty($row['phuongthuoc'])) $smarty->assign('sPhuongthuoc',$row['phuongthuoc']);
		if(!empty($row['phuonghuyet'])) $smarty->assign('sPhuonghuyet',$row['phuonghuyet']);
		if(!empty($row['xoabop'])) $smarty->assign('sXoabop',$row['xoabop']);
		if(!empty($row['chedoan'])) $smarty->assign('sChedoan',$row['chedoan']);
		if(!empty($row['chedoholy'])) $smarty->assign('sChedoholy',$row['chedoholy']);
		if(!empty($row['tienluong'])) $smarty->assign('sTienluong',$row['tienluong']);
		if(!empty($row['doctor_name'])) $smarty->assign('sBy',$row['doctor_name']);
		if(!empty($row['doctor_nr'])) $smarty->assign('sDocNr',$row['doctor_nr']);
	$smarty->assign('sDate',$calendar->show_calendar($calendar,$date_format,'date',$row['date']));

	ob_start();

?>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_time" value="null">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="edit" value="<?php echo $edit; ?>">
<input type="hidden" name="is_discharged" value="<?php echo $is_discharged; ?>">
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('sHiddenInputs',$sTemp);


$smarty->assign('pbBottomClose','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'cancel.gif','0').'  title="'.$LDCancelClose.'"  align="absmiddle"></a>');
$smarty->assign('sDocsBlockIncludeFile','registration_admission/form-yhct-edit.tpl');
$smarty->assign('sMainBlockIncludeFile','registration_admission/main-yhct.tpl');

$smarty->display('common/mainframe.tpl');
?>