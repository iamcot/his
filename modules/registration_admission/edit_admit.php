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

$thisfile=basename(__FILE__);

define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/care_api_classes/class_khambenh.php');
$lang_tables[]='person.php';
$lang_tables[]='prompt.php';
define('LANG_FILE','aufnahme.php');
$obj=new Khambenh;
require($root_path.'include/core/inc_front_chain_lang.php');
//$db->debug=1;
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($encounter_nr);
$enc_obj->loadEncounterData();
require('./include/init_show.php');
# Get encounter class
$enc_class=$enc_obj->EncounterClass();
if($mode=='save'){
$_POST['encounter_nr']=$encounter_nr;
	$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	$_POST['history']='Entry: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'];
	$_POST['time']=date('H:i:s');
	$obj->setDataArray($_POST);
	if($obj->updateDataFromInternalArray($nr)) {
	$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
	header("location:show_admit.php".URL_REDIRECT_APPEND."&target=$target&mode=details&encounter_nr=".$encounter_nr."&nr=".$nr);
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
function chkForm(d) {
	
	if(!d.short_notes[0].checked&&!d.short_notes[1].checked){
		alert("<?php echo $LDPlsMedicalAdvice ?>");
		d.short_notes[0].focus();
		return false;
	}else if(d.text_diagnosis.value==""||d.text_diagnosis.value==" "){
		alert("<?php echo $LDPlsEnterDiagnosis ?>");
		d.text_diagnosis.focus();
		return false;
	}else if(d.text_therapy.value==""||d.text_therapy.value==" "){
		alert("<?php echo $LDPlsEnterTherapy ?>");
		d.text_therapy.focus();
		return false;
	}else if(d.date.value==""){
		alert("<?php echo $LDPlsEnterDate ?>");
		d.date.focus();
		return false;
	}else if(d.personell_name.value==""){
		alert("<?php echo $LDPlsEnterFullName ?>");
		d.personell_name.focus();
		return false;
	}else{
		return true;
	}

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

# Set the table columns´ classes
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

$smarty->assign('LDDate',$LDDate);
$smarty->assign('LDToanthan',$LDToanthan);
$smarty->assign('LDTuanhoan',$LDTuanhoan);
$smarty->assign('LDHohap',$LDHohap);
$smarty->assign('LDBy',$LDBy);
$smarty->assign('LDCaccoquan',$LDCaccoquan);
$smarty->assign('LDTieuhoa',$LDTieuhoa);
$smarty->assign('LDThantietnieusinhduc',$LDThantietnieusinhduc);
$smarty->assign('LDThankinh',$LDThankinh);
$smarty->assign('LDCoxuongkhop',$LDCoxuongkhop);
$smarty->assign('LDTaimuihong',$LDTaimuihong);
$smarty->assign('LDRanghammat',$LDRanghammat);
$smarty->assign('LDMat',$LDMat);
$smarty->assign('LDKhac',$LDKhac);
$smarty->assign('LDTongquat',$LDTongquat);
$smarty->assign('LDChuyenKhoa',$LDChuyenKhoa);
$sql="SELECT *
		FROM 	care_encounter_khambenh
		WHERE   nr=$nr";
if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()) $row=$result->FetchRow();
	}else{
		echo $sql;
	}
	
if(!empty($row['tuanhoan_notes'])) $smarty->assign('sTuanhoanNotes',$row['tuanhoan_notes']);
			if(!empty($row['toanthan_notes'])) $smarty->assign('sToanthanNotes',$row['toanthan_notes']);
			if(!empty($row['hohap_notes'])) $smarty->assign('sHohapNotes',$row['hohap_notes']);
			if(!empty($row['tieuhoa_notes'])) $smarty->assign('sTieuhoaNotes',$row['tieuhoa_notes']);
			if(!empty($row['thantietnieusinhduc_notes'])) $smarty->assign('sThantietnieusinhducNotes',$row['thantietnieusinhduc_notes']);
			if(!empty($row['thankinh_notes'])) $smarty->assign('sThankinhNotes',$row['thankinh_notes']);
			if(!empty($row['coxuongkhop_notes'])) $smarty->assign('sCoxuongkhopNotes',$row['coxuongkhop_notes']);
			if(!empty($row['taimuihong_notes'])) $smarty->assign('sTaimuihongNotes',$row['taimuihong_notes']);
			if(!empty($row['ranghammat_notes'])) $smarty->assign('sRanghammatNotes',$row['ranghammat_notes']);
			if(!empty($row['mat_notes'])) $smarty->assign('sMatNotes',$row['mat_notes']);
			if(!empty($row['khac_notes'])) $smarty->assign('sKhacNotes',$row['khac_notes']);
	if(!empty($row['tongquat_bp'])) $smarty->assign('sTongquatBp',$row['tongquat_bp']);
			if(!empty($row['chuyenkhoa'])) $smarty->assign('sChuyenKhoa',$row['chuyenkhoa']);
	$smarty->assign('sDate',$calendar->show_calendar($calendar,$date_format,'date',$row['date']));
	$smarty->assign('sAuthor',$row['personell_name']);

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
$smarty->assign('sDocsBlockIncludeFile','medocs/form2.tpl');
$smarty->assign('sMainBlockIncludeFile','medocs/main1.tpl');

$smarty->display('common/mainframe.tpl');
?>