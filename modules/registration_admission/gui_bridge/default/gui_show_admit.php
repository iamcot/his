<?php
$returnfile=$_SESSION['sess_file_return'];

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

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

?>

<script  language="javascript">
<!-- 

<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>

function popRecordHistory(table,pid) {
	urlholder="./record_history.php<?php echo URL_REDIRECT_APPEND; ?>&table="+table+"&pid="+pid;
	HISTWIN<?php echo $sid ?>=window.open(urlholder,"histwin<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
}

-->
</script>
<?php 

$sTemp = ob_get_contents();
ob_end_clean();
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

$smarty->assign('LDTuoi',$LDTuoi);
$smarty->assign('tuoi',$tuoi);
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
$smarty->assign('LDTongquat',$LDTongquat);
$smarty->assign('LDChuyenKhoa',$LDChuyenKhoa);
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

if($mode=='show'){
	if($rows){

		# Set the document list template file
		
		$smarty->assign('sDocsBlockIncludeFile','medocs/docslist_frame1.tpl');

		$smarty->assign('LDDetails',$LDDetails);

		$sTemp = '';
		$toggle=0;
		while($row=$result->FetchRow()){
			//echo $row['date'];
			if($toggle) $smarty->assign('sRowClass','class="wardlistrow2"');
				else $smarty->assign('sRowClass','class="wardlistrow1"');
			$toggle=!$toggle;
			if(!empty($row['date'])) $smarty->assign('sDate',@formatDate2Local($row['date'],$date_format));
				else $smarty->assign('sDate','?');


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
			
			

			$smarty->assign('sDetailsIcon','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$_SESSION['sess_en'].'&target='.$target.'&mode=details&nr='.$row['nr'].'"><img '.createComIcon($root_path,'info3.gif','0').'></a>');
			//$smarty->assign('sMakePdfIcon','<a href="'.$root_path.'modules/pdfmaker/medocs/report.php'.URL_APPEND.'&enc='.$_SESSION['sess_en'].'&mnr='.$row['nr'].'&target='.$target.'" target=_blank><img '.createComIcon($root_path,'pdf_icon.gif','0').'></a>');
			if($row['personell_name']) $smarty->assign('sAuthor',$row['personell_name']);
			
			ob_start();
				$smarty->display('medocs/docslist_row1.tpl');
				$sTemp = $sTemp.ob_get_contents();
			ob_end_clean();
		}

		$smarty->assign('sDocsListRows',$sTemp);
	
	}else{
	
		# Show no record prompt

		$smarty->assign('bShowNoRecord',TRUE);

		$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_r.gif','0','absmiddle').'>');
		$smarty->assign('norecordyet',$norecordyet);

	}
}elseif($mode=='details'){

	# Show the record details

	# Set the include file

	$smarty->assign('sDocsBlockIncludeFile','medocs/form1.tpl');
	
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
	$smarty->assign('sDate',formatDate2Local($row['date'],$date_format));
	$smarty->assign('sAuthor',$row['personell_name']);

# Create a new form for data entry

}else {

	# Create a new entry form

	# Set the include file

	$smarty->assign('sDocsBlockIncludeFile','medocs/form1.tpl');
	
	# Set form table as active form
	$smarty->assign('bSetAsForm',TRUE);

	# Collect extra javascript
	
	ob_start();
	require_once ('../../js/jscalendar/calendar.php');
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
	
	$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
	$calendar->load_files();
	$smarty->assign('sDateMiniCalendar',$calendar->show_calendar($calendar,$date_format,'date'));
	//end gjergji
	$smarty->assign('TP_user_name',$_SESSION['sess_user_name']);

	# Collect hidden inputs
	
	ob_start();

?>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_time" value="null">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="edit" value="<?php echo $edit; ?>">
<input type="hidden" name="is_discharged" value="<?php echo $is_discharged; ?>">
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('sHiddenInputs',$sTemp);

} 


if(($mode=='show'||$mode=='details')&&!$enc_obj->Is_Discharged()){
	
	$smarty->assign('sNewLinkIcon','<img '.createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle').'>');	
	if($mode=='details'){
	$smarty->assign('sNewRecLink','<a href="'.$root_path.'modules/registration_admission/edit_admit.php'.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$_SESSION['sess_en'].'&target='.$target.'&nr='.$nr.'">Cập nhật</a>&nbsp;<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$_SESSION['sess_en'].'&target='.$target.'&mode=new">'.$LDEnterNewRecord.'</a>');
	}else{
	$smarty->assign('sNewRecLink','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$_SESSION['sess_en'].'&target='.$target.'&mode=new">'.$LDEnterNewRecord.'</a>');
	
	}
} 
if(($mode!='show'&&!$nolist) ||($mode=='show'&&$nolist&&$rows>1)){

	$smarty->assign('sListLinkIcon','<img '.createComIcon($root_path,'l-arrowgrnlrg.gif','0','absmiddle').'>');
	$smarty->assign('sListRecLink','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$_SESSION['sess_en'].'&target='.$target.'&mode=show">'.$LDShowDocList.'</a>');

}

$smarty->assign('pbBottomClose','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'cancel.gif','0').'  title="'.$LDCancelClose.'"  align="absmiddle"></a>');

$smarty->assign('sMainBlockIncludeFile','medocs/main1.tpl');

$smarty->display('common/mainframe.tpl');

?>