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
$obj->KhamChuyenKhoaMat();
	require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
require($root_path.'include/core/inc_front_chain_lang.php');
//$db->debug=1;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($encounter_nr);
$enc_obj->loadEncounterData();
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
	header("location:show_chuyenkhoa_mat.php".URL_REDIRECT_APPEND."&target=$target&mode=details&encounter_nr=".$encounter_nr."&nr=".$nr);
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

$smarty->assign('LDThiluccokinhT',$LDThiluccokinhT);
$smarty->assign('LDThiluccokinhP',$LDThiluccokinhP);
$smarty->assign('LDThiluckokinhT',$LDThiluckokinhT);
$smarty->assign('LDThiluckokinhP',$LDThiluckokinhP);
$smarty->assign('LDNhanapT',$LDNhanapT);
$smarty->assign('LDNhanapP',$LDNhanapP);
$smarty->assign('LDThitruongT',$LDThitruongT);
$smarty->assign('LDThitruongP',$LDThitruongP);
$smarty->assign('LDLedaoT',$LDLedaoT);
$smarty->assign('LDLedaoP',$LDLedaoP);
$smarty->assign('LDMimatT',$LDMimatT);
$smarty->assign('LDMimatT',$LDMimatT);
$smarty->assign('LDMimatP',$LDMimatP);
$smarty->assign('LDKetmacT',$LDKetmacT);
$smarty->assign('LDKetmacP',$LDKetmacP);
$smarty->assign('LDMathotT',$LDMathotT);
$smarty->assign('LDMathotP',$LDMathotP);
$smarty->assign('LDGiacmacT',$LDGiacmacT);
$smarty->assign('LDGiacmacP',$LDGiacmacP);
$smarty->assign('LDCungmacT',$LDCungmacT);
$smarty->assign('LDCungmacP',$LDCungmacP);
$smarty->assign('LDTienphongT',$LDTienphongT);
$smarty->assign('LDTienphongP',$LDTienphongP);
$smarty->assign('LDMongmatT',$LDMongmatT);
$smarty->assign('LDMongmatP',$LDMongmatP);
$smarty->assign('LDDongtuP',$LDDongtuP);
$smarty->assign('LDDongtuT',$LDDongtuT);
$smarty->assign('LDThuytinhtheT',$LDThuytinhtheT);
$smarty->assign('LDThuytinhtheP',$LDThuytinhtheP);
$smarty->assign('LDThuytinhdichT',$LDThuytinhdichT);
$smarty->assign('LDThuytinhdichP',$LDThuytinhdichP);
$smarty->assign('LDAnhdongtuT',$LDAnhdongtuT);
$smarty->assign('LDAnhdongtuP',$LDAnhdongtuP);
$smarty->assign('LDNhancauT',$LDNhancauT);
$smarty->assign('LDNhancauP',$LDNhancauP);
$smarty->assign('LDHocmatT',$LDHocmatT);
$smarty->assign('LDHocmatP',$LDHocmatP);
$smarty->assign('LDDaymatT',$LDDaymatP);
$smarty->assign('LDDate',$LDDate);
$smarty->assign('LDBy',$LDBy);
$sql="SELECT *
		FROM 	care_chuyenkhoa_mat
		WHERE   nr=$nr";
if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()) $row=$result->FetchRow();
	}else{
		echo $sql;
	}
	
if(!empty($row['thiluc_khongkinh_trai'])) $smarty->assign('sThiluckokinhT',$row['thiluc_khongkinh_trai']);
		if(!empty($row['thiluc_khongkinh_phai'])) $smarty->assign('sThiluckokinhP',$row['thiluc_khongkinh_phai']);
		if(!empty($row['thiluc_cokinh_trai'])) $smarty->assign('sThiluccokinhT',$row['thiluc_cokinh_trai']);
		if(!empty($row['thiluc_cokinh_phai'])) $smarty->assign('sThiluccokinhP',$row['thiluc_cokinh_phai']);
		if(!empty($row['nhanap_trai'])) $smarty->assign('sNhanapT',$row['nhanap_trai']);
		if(!empty($row['nhanap_phai'])) $smarty->assign('sNhanapP',$row['nhanap_phai']);
		if(!empty($row['thitruong_trai'])) $smarty->assign('sThitruongT',$row['thitruong_trai']);
		if(!empty($row['thitruong_phai'])) $smarty->assign('sThitruongP',$row['thitruong_phai']);
		if(!empty($row['ledao_trai'])) $smarty->assign('sLedaoT',$row['ledao_trai']);
		if(!empty($row['ledao_phai'])) $smarty->assign('sLedaoP',$row['ledao_phai']);
		if(!empty($row['mimat_trai'])) $smarty->assign('sMimatT',$row['mimat_trai']);
		if(!empty($row['mimat_phai'])) $smarty->assign('sMimatP',$row['mimat_phai']);
		if(!empty($row['ketmac_trai'])) $smarty->assign('sKetmacT',$row['ketmac_trai']);
		if(!empty($row['ketmac_phai'])) $smarty->assign('sKetmacP',$row['ketmac_phai']);
		if(!empty($row['mathot_trai'])) $smarty->assign('sMathotT',$row['mathot_trai']);
		if(!empty($row['mathot_phai'])) $smarty->assign('sMathotP',$row['mathot_phai']);
		if(!empty($row['giacmac_trai'])) $smarty->assign('sGiacmacT',$row['giacmac_trai']);
		if(!empty($row['giacmac_phai'])) $smarty->assign('sGiacmacP',$row['giacmac_phai']);
		if(!empty($row['cungmac_trai'])) $smarty->assign('sCungmacT',$row['cungmac_trai']);
		if(!empty($row['cungmac_phai'])) $smarty->assign('sCungmacP',$row['cungmac_phai']);
		if(!empty($row['tienphong_trai'])) $smarty->assign('sTienphongT',$row['tienphong_trai']);
		if(!empty($row['tienphong_phai'])) $smarty->assign('sTienphongP',$row['tienphong_phai']);
		if(!empty($row['mongmat_trai'])) $smarty->assign('sMongmatT',$row['mongmat_trai']);
		if(!empty($row['mongmat_phai'])) $smarty->assign('sMongmatP',$row['mongmat_phai']);
		if(!empty($row['dongtu_trai'])) $smarty->assign('sDongtuT',$row['dongtu_trai']);
		if(!empty($row['dongtu_phai'])) $smarty->assign('sDongtuP',$row['dongtu_phai']);
		if(!empty($row['thuytinhthe_trai'])) $smarty->assign('sThuytinhtheT',$row['thuytinhthe_trai']);
		if(!empty($row['thuytinhthe_phai'])) $smarty->assign('sThuytinhtheP',$row['thuytinhthe_phai']);
		if(!empty($row['thuytinhdich_trai'])) $smarty->assign('sThuytinhdichT',$row['thuytinhdich_trai']);
		if(!empty($row['thuytinhdich_phai'])) $smarty->assign('sThuytinhdichP',$row['thuytinhdich_phai']);
		if(!empty($row['anhdongtu_trai'])) $smarty->assign('sAnhdongtuT',$row['anhdongtu_trai']);
		if(!empty($row['anhdongtu_phai'])) $smarty->assign('sAnhdongtuP',$row['anhdongtu_phai']);
		if(!empty($row['nhancau_trai'])) $smarty->assign('sNhancauT',$row['nhancau_trai']);
		if(!empty($row['nhancau_phai'])) $smarty->assign('sNhancauP',$row['nhancau_phai']);
		if(!empty($row['hocmat_trai'])) $smarty->assign('sHocmatT',$row['hocmat_trai']);
		if(!empty($row['hocmat_phai'])) $smarty->assign('sHocmatP',$row['hocmat_phai']);
		if(!empty($row['daymat_trai'])) $smarty->assign('sDaymatT',$row['daymat_trai']);
		if(!empty($row['daymat_phai'])) $smarty->assign('sDaymatP',$row['daymat_phai']);
		if(!empty($row['doctor_name'])) $smarty->assign('sBy',$row['doctor_name']);
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
$smarty->assign('sDocsBlockIncludeFile','registration_admission/form2.tpl');
$smarty->assign('sMainBlockIncludeFile','registration_admission/main1.tpl');

$smarty->display('common/mainframe.tpl');
?>