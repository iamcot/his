<?php

$returnfile=$_SESSION['sess_file_return'];

$_SESSION['sess_file_return']=$thisfile;
/////// edit 18/11-Huỳnh ////////
if($_COOKIE["ck_login_logged".$sid]) $breakfile = $root_path."modules/registration_admission/aufnahme_daten_zeigen.php".URL_APPEND."&from=such&encounter_nr=".$_SESSION['sess_en']."&target=search";
	else $breakfile = $breakfile.URL_APPEND."&target=entry";
///////////////////////////
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
 $smarty->assign('sToolbarTitle',"$page_title ($sTitleNr)");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPatientRegister')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('Name',"$page_title ( $sTitleNr)");

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('notes_router.php','echo $notestype','".strtr($subtitle,' ','+')."','$mode','$rows')");

  # href for return button
 $smarty->assign('pbBack',$returnfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=show&type_nr='.$type_nr);

# Start buffering extra javascript output
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
if($parent_admit) include($root_path.'main/imgcreator/inc_js_barcode_wristband_popwin.php');

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

/* Load the tabs */
if($parent_admit) {
	$tab_bot_line='#66ee66';
	include('./gui_bridge/default/gui_tabs_patadmit.php');
	$smarty->assign('sTabsFile','registration_admission/admit_tabs.tpl');
	$smarty->assign('sClassItem','class="adm_item"');
	$smarty->assign('sClassInput','class="adm_input"');
}else{
	$tab_bot_line='#66ee66';
	include('./gui_bridge/default/gui_tabs_patreg.php');
	$smarty->assign('sTabsFile','registration_admission/reg_tabs.tpl');
	$smarty->assign('sClassItem','class="reg_item"');
	$smarty->assign('sClassInput','class="reg_input"');
}

# If encounter is already discharged, show warning

////// edit 18/11-Huỳnh ////////////
if(file_exists($root_path.'cache/barcodes/en_'.$encounter_nr.'.png')) {
	$smarty->assign('sEncBarcode','<img src="'.$root_path.'cache/barcodes/en_'.$encounter_nr.'.png" border=0 width=180 height=35>');
}else{
	$smarty->assign('sHiddenBarcode',"<img src='".$root_path."classes/barcode/image.php?code=".$encounter_nr."&style=68&type=I25&width=180&height=50&xres=2&font=5&label=2&form_file=en' border=0 width=0 height=0>");
	$smarty->assign('sEncBarcode',"<img src='".$root_path."classes/barcode/image.php?code=".$encounter_nr."&style=68&type=I25&width=180&height=40&xres=2&font=5' border=0>");
}
require_once($root_path.'include/care_api_classes/class_encounter.php');
$smarty->assign('LDAdmitDate',$LDAdmitDate);
require_once($root_path.'include/care_api_classes/class_encounter.php');
$encounter_obj=new Encounter($encounter_nr);
$encounter_obj->loadEncounterData();
if($encounter_obj->is_loaded) {
    $row=$encounter_obj->encounter;
    //load data
    extract($row);
    # Set edit mode
    if(!$is_discharged) $edit=true;
            else $edit=false;
    # Fetch insurance and encounter classes
    $insurance_class=&$encounter_obj->getInsuranceClassInfo($insurance_class_nr);
    $encounter_class=&$encounter_obj->getEncounterClassInfo($encounter_class_nr);

    //if($data_obj=&$person_obj->getAllInfoObject($pid))
    $list='title,name_first,name_last,name_2,name_3,name_middle,name_maiden,name_others,date_birth,
             sex,addr_str,addr_str_nr,addr_zip,addr_citytown_nr,photo_filename';

    $person_obj->setPID($pid);
    if($row=&$person_obj->getValueByList($list)) {
            extract($row);
    }

    $addr_citytown_name=$person_obj->CityTownName($addr_citytown_nr);
    $encoder=$encounter_obj->RecordModifierID();
    # Get current encounter to check if current encounter is this encounter nr
    $current_encounter=$person_obj->CurrentEncounter($pid);

    # Get the overall status
    if($stat=&$encounter_obj->AllStatus($encounter_nr)){
            $enc_status=$stat->FetchRow();
    }

    # Get ward or department infos
    
            # Get ward name
            include_once($root_path.'include/care_api_classes/class_ward.php');
            $ward_obj=new Ward;
            $current_ward_name=$ward_obj->WardName($current_ward_nr);
   
            # Get ward name
            include_once($root_path.'include/care_api_classes/class_department.php');
            $dept_obj=new Department;
            //$current_dept_name=$dept_obj->FormalName($current_dept_nr);
            $current_dept_LDvar=$dept_obj->LDvar($current_dept_nr);

            if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $current_dept_name=$$current_dept_LDvar;
                    else $current_dept_name=$dept_obj->FormalName($current_dept_nr);
   

}
if($is_discharged){

	$smarty->assign('is_discharged',TRUE);
	$smarty->assign('sWarnIcon',"<img ".createComIcon($root_path,'warn.gif','0','absmiddle').">");
	if($current_encounter) $smarty->assign('sDischarged',$LDEncounterClosed);
		else $smarty->assign('sDischarged',$LDPatientIsDischarged);
}

$smarty->assign('LDCaseNr',$LDCaseNr);
$smarty->assign('encounter_nr',$encounter_nr);

# Create the encounter barcode image

if(file_exists($root_path.'cache/barcodes/en_'.$encounter_nr.'.png')) {
	$smarty->assign('sEncBarcode','<img src="'.$root_path.'cache/barcodes/en_'.$encounter_nr.'.png" border=0 width=180 height=35>');
}else{
	$smarty->assign('sHiddenBarcode',"<img src='".$root_path."classes/barcode/image.php?code=".$encounter_nr."&style=68&type=I25&width=180&height=50&xres=2&font=5&label=2&form_file=en' border=0 width=0 height=0>");
	$smarty->assign('sEncBarcode',"<img src='".$root_path."classes/barcode/image.php?code=".$encounter_nr."&style=68&type=I25&width=180&height=40&xres=2&font=5' border=0>");
}
$smarty->assign('img_source',"<img $img_source>");

$smarty->assign('LDAdmitDate',$LDAdmitDate);

$smarty->assign('sAdmitDate', @formatDate2Local($encounter_date,$date_format));

$smarty->assign('LDAdmitTime',$LDAdmitTime);

$smarty->assign('sAdmitTime',@formatDate2Local($encounter_date,$date_format,1,1));
$smarty->assign('LDInDate','Ngày nhập liệu');
$smarty->assign('sInDate',@formatDate2Local($encounter_in_date,$date_format)." ".@formatDate2Local($encounter_in_date,$date_format,0,1));
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

	# Set a row span counter, initialize with 6
	$iRowSpan = 6;

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

$smarty->assign('LDAddress',$LDAddress);

$smarty->assign('addr_str',$addr_str);
$smarty->assign('addr_str_nr',$addr_str_nr);
$smarty->assign('addr_zip',$addr_zip);
$smarty->assign('addr_citytown',$addr_citytown_name);
$smarty->assign('addr_phuongxa',$addr_phuongxa_name);
$smarty->assign('addr_quanhuyen',$addr_quanhuyen_name);

//start gjergji
//simple admission type, how the patietnt came in
$enc_type = $encounter_obj->getEncounterType();
while( $typeResults = $enc_type->FetchRow()) {
	if($typeResults['type_nr'] == $admit_type )$sTemp = $$typeResults['LD_var'] ; 
}

$smarty->assign('LDAdmitShowTypeInput',$LDAdmitShowTypeInput);
$smarty->assign('sAdmitShowTypeInput',$sTemp);

//start simple triage
if($triage == 'white') { $smarty->assign('sAdmitTriage',$sAdmitTriageWhite); }
elseif($triage == 'green') { $smarty->assign('sAdmitTriage',$sAdmitTriageGreen); }
elseif ($triage == 'yellow') { $smarty->assign('sAdmitTriage',$sAdmitTriageYellow); }
elseif ($triage == 'red') { $smarty->assign('sAdmitTriage',$sAdmitTriageRed); }
$smarty->assign('LDShowTriageData','-');

//end simple triage
//end : gjergji

$smarty->assign('LDAdmitClass',$LDAdmitClass);

# Suggested by Dr. Sarat Nayak to emphasize the OUTPATIENT encounter type

if (isset($$encounter_class['LD_var']) && !empty($$encounter_class['LD_var'])){
	$eclass=$$encounter_class['LD_var'];
	//$fcolor='red';
}else{
	$eclass= $encounter_class['name'];
} 

if($encounter_class_nr==1){
	$fcolor='black';
}else{
	$fcolor='red';
	$eclass='<b>'.$eclass.'</b>';
}

$smarty->assign('sAdmitClassInput',"<font color=$fcolor>$eclass</font>");


	
	$smarty->assign('LDWard',$LDWard);

	$smarty->assign('sWardInput','<a href="'.$root_path.'modules/nursing/'.strtr('nursing-station-pass.php'.URL_APPEND.'&rt=pflege&edit=1&station='.$current_ward_name.'&location_id='.$current_ward_name.'&ward_nr='.$current_ward_nr,' ',' ').'">'.$current_ward_name.'</a>');



	$smarty->assign('LDDepartment',$LDDepartment);

	$smarty->assign('sDeptInput','<a href="'.$root_path.'modules/ambulatory/'.strtr('amb_clinic_patients_pass.php'.URL_APPEND.'&rt=pflege&edit=1&dept='.$$current_dept_LDvar.'&location_id='.$$current_dept_LDvar.'&dept_nr='.$current_dept_nr,' ',' ').'">'.$current_dept_name.'</a>');

$smarty->assign('LDLoaiKham',"Loại khám:");
if($loai_kham==1){
$smarty->assign('sSelectKham','Khám nội');
}else{
$smarty->assign('sSelectKham','Khám ngoai');
}

$smarty->assign('LDTTBA',$LDTTBA);
$smarty->assign('LDTienluong',$LDTienluong);
if($tienluong=='1'){
$smarty->assign('tienluong','Xấu');
}elseif($tienluong=='2'){
$smarty->assign('tienluong','Trung bình');
}elseif($tienluong=='3'){
$smarty->assign('tienluong','Khá');
}elseif($tienluong=='4'){
$smarty->assign('tienluong','Tốt');
}
$smarty->assign('ttba',$tomtat_benhan);
$smarty->assign('LDDiagnosis',$LDDiagnosis);
$smarty->assign('referrer_diagnosis',$referrer_diagnosis);
$smarty->assign('LDRecBy',$LDRecBy);
$smarty->assign('referrer_name',$referrer_name);
$smarty->assign('LDReferrDoc',$LDReferrDoc);
$smarty->assign('doctor_name',$doctor_name);
$smarty->assign('LDTherapy',$LDTherapy);
$smarty->assign('referrer_recom_therapy',$referrer_recom_therapy);
$smarty->assign('LDSpecials',$LDSpecials);
$smarty->assign('referrer_notes',$referrer_notes);
$smarty->assign('LDLidovaovien',$LDLidovaovien);
$smarty->assign('lidovaovien',$lidovaovien);
$smarty->assign('LDQuatrinhbenhly',$LDQuatrinhbenhly);
$smarty->assign('quatrinhbenhly',$quatrinhbenhly);
$smarty->assign('LDBenhphu',"Bệnh phụ (nếu có)");
$smarty->assign('benhphu',nl2br($benhphu));
if($is_cbtc==1){
$smarty->assign('sCanBoTrungCao','CBTC');
}
if($is_tngt==1){
$smarty->assign('sTNGT','TNGT');
}

if(!isset($GLOBAL_CONFIG['patient_service_care_hide']) && $sc_care_class_nr){
	$smarty->assign('LDCareServiceClass',$LDCareServiceClass);

	while($buffer=$care_service->FetchRow()){
		if($sc_care_class_nr==$buffer['class_nr']){
			if(empty($$buffer['LD_var'])) $smarty->assign('sCareServiceInput',$buffer['name']);
				else $smarty->assign('sCareServiceInput',$$buffer['LD_var']);
			break;
		}
	}

	if($sc_care_start && $sc_care_start != DBF_NODATE){
		$smarty->assign('sCSFromInput',' [ '.@formatDate2Local($sc_care_start,$date_format).' ] ');
		$smarty->assign('sCSToInput',' [ '.@formatDate2Local($sc_care_end,$date_format).' ]');
	}
}


if(!isset($GLOBAL_CONFIG['patient_service_room_hide']) && $sc_room_class_nr){
	$smarty->assign('LDRoomServiceClass',$LDRoomServiceClass);

	while($buffer=$room_service->FetchRow()){
		if($sc_room_class_nr==$buffer['class_nr']){
			if(empty($$buffer['LD_var'])) $smarty->assign('sCareRoomInput',$buffer['name']);
				else $smarty->assign('sCareRoomInput',$$buffer['LD_var']);
				break;
		}
	}
	if($sc_room_start && $sc_room_start != DBF_NODATE){
		$smarty->assign('sRSFromInput',' [ '.@formatDate2Local($sc_room_start,$date_format).' ] ');
		$smarty->assign('sRSToInput',' [ '.@formatDate2Local($sc_room_end,$date_format).' ]');
	}
}

if(!isset($GLOBAL_CONFIG['patient_service_att_dr_hide']) && $sc_att_dr_class_nr){
	$smarty->assign('LDAttDrServiceClass',$LDAttDrServiceClass);

	while($buffer=$att_dr_service->FetchRow()){
		if($sc_att_dr_class_nr==$buffer['class_nr']){
			if(empty($$buffer['LD_var'])) $smarty->assign('sCareDrInput',$buffer['name']);
				else $smarty->assign('sCareDrInput',$$buffer['LD_var']);
			break;
		}
	}
	if($sc_att_dr_start && $sc_att_dr_start != DBF_NODATE){
		$smarty->assign('sDSFromInput',' [ '.@formatDate2Local($sc_att_dr_start,$date_format).' ] ');
		$smarty->assign('sDSToInput',' [ '.@formatDate2Local($sc_att_dr_end,$date_format).' ]');
	}
}

//gjergji : billable items list
if(isset($GLOBAL_CONFIG['show_billable_items']) && $encounter_class_nr == 2){
	$smarty->assign('LDAdmitBillItem',$LDAdmitBillItem);
	if($att_bill_item = $eComBill_obj->checkBillExist($encounter_nr)) {
		$bufferBill=$att_bill_item->FetchRow();
		$smarty->assign('sAdmitBillItem',$bufferBill['bill_item_code']);
	} else {
		$smarty->assign('sAdmitBillItem',"----");
	}

}

//gjergji : refered to doctor
if(isset($GLOBAL_CONFIG['show_doctors_list']) && $encounter_class_nr == 2){
	$smarty->assign('LDAdmitDoctorRefered',$LDAdmitDoctorRefered);

	$bufferBill = $encounter_obj->ReferredDoctor($encounter_nr);
	if(!empty($bufferBill) && isset($bufferBill))
		$personellNr = $bufferBill->Fields("referred_dr");

	if($att_doctor = $personell_obj->_getPersonellById($personellNr)) {
		//TODO : gjergji : change to list appointments by doctor...
		$smarty->assign('sAdmitDoctorRefered','<a href="'.$root_path.'modules/personell_admin/'.strtr('personell_register_show.php'.URL_APPEND.'&from=such&target=personell_search&personell_nr=' .$att_doctor->Fields("personell_nr") .'&sem=1',' ',' ').'">'.$att_doctor->Fields("name_first") . ' '  .$att_doctor->Fields("name_last") .'</a>');
	} else {
		$smarty->assign('sAdmitDoctorRefered',"----");
	}
}
//end gjergji : refered to doctor

$smarty->assign('LDAdmitBy',$LDAdmitBy);
if (empty($encoder)) $encoder = $_COOKIE[$local_user.$sid];
$smarty->assign('encoder',$encoder);
$smarty->assign('sAdmitDate', @formatDate2Local($encounter_date,$date_format));
////////////////////////////////////////////////////////

/* Buffer and load the options table  */

ob_start();
    //// edit 18/11-Huỳnh /////////
	if($parent_admit)  include('./gui_bridge/default/gui_patient_reg_options_1.php');
    ////////////////////////////
	$sTemp = ob_get_contents();
ob_end_clean();

$smarty->assign('sOptionsMenu',$sTemp);




# If mode = show then display data

if($mode=='show'){

	if($parent_admit) $bgimg='tableHeaderbg3.gif';
		else $bgimg='tableHeader_gr.gif';

	$tbg= 'background="'.$root_path.'gui/img/common/'.$theme_com_icon.'/'.$bgimg.'"';
        //hiển thị form truy vấn dữ liệu
	/*if($rows){
		
		# Buffer the option block
		ob_start();
			include('./gui_bridge/default/gui_'.$thisfile);
			$sTemp = ob_get_contents();
		ob_end_clean();
		$smarty->assign('sOptionBlock',$sTemp);

	}else{

		$smarty->assign('bShowNoRecord',TRUE);
		
		$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_r.gif','0','absmiddle').'>');
		$smarty->assign('norecordyet',$norecordyet);

		if($parent_admit && !$is_discharged && $thisfile!='show_diagnostics_result.php'){
			$smarty->assign('sPromptIcon','<img '.createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle',TRUE).'>');
			$smarty->assign('sPromptLink','<a href="'.$thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=new">'.$LDEnterNewRecord.'</a>');
 		}else{
			if(file_exists('./gui_bridge/default/gui_person_createnew_'.$thisfile)) include('./gui_bridge/default/gui_person_createnew_'.$thisfile);
		}
	}*/
}
else	//$mode='update' or 'create'
 {
	# Buffer the option input block
	ob_start();
		include('./gui_bridge/default/gui_input_'.$thisfile);
		$sTemp = ob_get_contents();
	ob_end_clean();
	$smarty->assign('sOptionBlock',$sTemp);
}



# Buffer the bottom controls

ob_start();

	if($parent_admit) {
		include('./include/bottom_controls_admission_options.inc.php');
	}else{
		include('./include/bottom_controls_registration_options.inc.php');
	}

	# Get buffer contents and stop buffering

	$sTemp= ob_get_contents();
ob_end_clean();

$smarty->assign('sBottomControls',$sTemp);


$smarty->assign('pbBottomClose','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'close2.gif','0').'  title="'.$LDCancel.'"  align="absmiddle"></a>');


$smarty->assign('sMainBlockIncludeFile','registration_admission/common_option_1.tpl');


$smarty->display('common/mainframe.tpl');

?>
