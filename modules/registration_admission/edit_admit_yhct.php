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
$returnfile = 'show_admit_general.php';

define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/care_api_classes/class_khambenh_yhct.php');
$lang_tables[]='person.php';
$lang_tables[]='prompt.php';
define('LANG_FILE','aufnahme.php');
$obj=new KhamBenhYHCT;
require($root_path.'include/core/inc_front_chain_lang.php');
//$db->debug=1;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($encounter_nr);
$enc_obj->loadEncounterData();
require('./include/init_show.php');
# Get encounter class
$enc_class=$enc_obj->EncounterClass();
if($mode=='update'){
	$_POST['encounter_nr']=$encounter_nr;
	if($_POST['chandoan']!='' && $_POST['date']!=''){
		# If date is empty,default to today
		if(empty($_POST['date'])){
			$_POST['date']=date('Y-m-d');
		}else{
			$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
		}

		# Prepare history
		$_POST['history']='Entry: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'];
		$_POST['time']=date('H:i:s');
		
		# Prevent redirection
		$redirect=true;
		include('./include/save_admission_data_yhct.inc.php');
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
$smarty->assign('LDBy',$LDBy);
$smarty->assign('LDKhamYHCT',$LDKhamYHCTNoiTru);
$smarty->assign('LDBienChungLuanTri','<b>V/. '.$LDBienChungLuanTri.'</b>');
$smarty->assign('LDChanDoan','<b>VI/. '.$LDChanDoan.'</b>');

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
		
		alert('abc!');
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

	$sTemp='';
if($result=$obj->detailLanKham($nr)){
	for($i=0;$i<$result->RecordCount();$i++){
		$row=$result->FetchRow();
		if($i==0){
			$sDate=$row['date'];
			$sAuthor = $row['doctor'];
			$sBienChung = $row['bienchung'];
			$sChanDoan = $row['chandoan'];
		}
		if(!isset($type1)){
			$type1=$row['type1'];
			$iTitle=$row['name1'];
		} else if($type1!=$row['type1']){
			$smarty->assign('iTitle',$type1.'. '.$iTitle);
			$smarty->assign('iContent',$iContent);
			ob_start();
				$smarty->display('medocs/form_yhct_row.tpl');
				$sTemp = $sTemp.ob_get_contents();
			ob_end_clean();
			$iContent='';
			$type1=$row['type1'];
			$iTitle=$row['name1'];
			$smarty->assign('bTypeI',false);			
		}
		if($typeI!=$row['typeI']){
			$smarty->assign('bTypeI',true);
			$smarty->assign('sNameI',$row['typeI'].'/. '.$row['nameI']);
			$typeI=$row['typeI'];
		}	
		if($row['check_yesno']=='yes')
			$radio= '&nbsp;&nbsp;&nbsp; <input type="radio" name="radio'.$row['detail_nr'].'" value="yes" checked >'.$LDYes1.' &nbsp;&nbsp;&nbsp; <input type="radio" name="radio'.$row['detail_nr'].'" value="no">'.$LDNo;
		else if ($row['check_yesno']=='no')
			$radio= '&nbsp;&nbsp;&nbsp; <input type="radio"  name="radio'.$row['detail_nr'].'" value="yes" >'.$LDYes1.' &nbsp;&nbsp;&nbsp; <input type="radio"  name="radio'.$row['detail_nr'].'" value="no" checked>'.$LDNo;
		else $radio= '&nbsp;&nbsp;&nbsp; <input type="radio"  name="radio'.$row['detail_nr'].'" value="yes" >'.$LDYes1.' &nbsp;&nbsp;&nbsp; <input type="radio"  name="radio'.$row['detail_nr'].'" value="no">'.$LDNo;	
			
	
		$detail = str_replace("    ","&nbsp;&nbsp;&nbsp;&nbsp;",$row['detail']);		
		$detail = str_replace("\n", "<p>", $detail);		
		$detail = str_replace("-----",$radio,$detail);

		if($row['cbx']!=''){
			$cbx = '';
			$cbx_temp = explode('_',$row['cbx']);
			$cbx_answer = explode ('_',$row['check_number']);
			if($cbx_temp[0]!=''&&$cbx_temp[1]!=''){				
				for($k=0;$k<$cbx_temp[0];$k++){
					$cbx .= '<select name="cbx'.$row['detail_nr'].'_'.$k.'"><option value=""></option>';					
					$flag=1;
					
					for ($k1=1;$k1<=$cbx_temp[1];$k1++){					
						if(in_array($k1, $cbx_answer) && $flag){
							$cbx .= '<option value="'.$k1.'" selected >'.$k1.'</option>';
							$index = array_search($k1, $cbx_answer);
							$cbx_answer[$index]='';
							$flag=0;
						}
						else
							$cbx .= '<option value="'.$k1.'">'.$k1.'</option>';
					}
					
					$cbx .= '</select>';
				}
			}
			$detail = $detail.'&nbsp;&nbsp;&nbsp;&nbsp;'.$cbx;
		}
		
		if($row['mota']=='0')
			$iContent .=  $detail.'<p>';
		else	
			$iContent .=  $detail.'<p><u>'.$LDMota.'</u>: <input type="text" name="text'.$row['detail_nr'].'" value="'.$row['description'].'" size=80><p>';
	}
	$smarty->assign('iTitle',$type1.'. '.$iTitle);
	$smarty->assign('iContent',$iContent);
	ob_start();
		$smarty->display('medocs/form_yhct_row.tpl');
		$sTemp = $sTemp.ob_get_contents();
	ob_end_clean();
	
	$smarty->assign('sContent',$sTemp);
}

		

	//gjergji : new calendar
	
	$smarty->assign('sDateMiniCalendar',$calendar->show_calendar($calendar,$date_format,'date',$sDate));
	//end gjergji

	$smarty->assign('sAuthor',$sAuthor);
	$smarty->assign('sBienChung','<textarea name="bienchung" cols="68" rows="3">'.$sBienChung.'</textarea>');
	$smarty->assign('sChanDoan','<textarea name="chandoan" cols="68" rows="5">'.$sChanDoan.'</textarea>');


	ob_start();

?>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_time" value="null">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="edit" value="<?php echo $edit; ?>">
<input type="hidden" name="is_discharged" value="<?php echo $is_discharged; ?>">
<p>
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->assign('sHiddenInputs',$sTemp);
	
$smarty->assign('sListLinkIcon','<img '.createComIcon($root_path,'l-arrowgrnlrg.gif','0','absmiddle').'>');
$smarty->assign('sListRecLink','<a href="show_admit_yhct.php'.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&encounter_nr='.$_SESSION['sess_en'].'&target='.$target.'&mode=show">'.$LDShowDocList.'</a>');

$smarty->assign('pbBottomClose','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'cancel.gif','0').'  title="'.$LDCancelClose.'"  align="absmiddle"></a>');
$smarty->assign('sDocsBlockIncludeFile','medocs/form_yhct.tpl');
$smarty->assign('sMainBlockIncludeFile','medocs/main_yhct.tpl');

$smarty->display('common/mainframe.tpl');
?>