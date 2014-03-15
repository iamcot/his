<?php
    $returnfile=$_SESSION['sess_file_return'];
    
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
    $smarty->assign('Name',"$page_title $encounter_nr");

    # Onload Javascript code
    $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('medocs_entry.php')");

    # href for return button
    $smarty->assign('pbBack',$returnfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=show&type_nr='.$type_nr);
    
    ob_start();

    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp);

    require('./gui_bridge/default/gui_tabs_medocs.php');

    if($enc_obj->Is_Discharged()){
	$smarty->assign('is_discharged',TRUE);
	$smarty->assign('sWarnIcon',"<img ".createComIcon($root_path,'warn.gif','0','absmiddle').">");
	$smarty->assign('sDischarged',$LDPatientIsDischarged);
    }

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
	$smarty->assign('LDTuoi',$LDTuoi);
	$smarty->assign('tuoi',$tuoi);
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
    if($sex=='m') 
        $smarty->assign('sSexType',$LDMale);
    elseif($sex=='f') 
        $smarty->assign('sSexType',$LDFemale);

    $smarty->assign('LDBloodGroup',$LDBloodGroup);
    if($blood_group){
        $buf='LD'.$blood_group;
        $smarty->assign('blood_group',$$buf);
    }
    
    $smarty->assign('sListLinkIcon','<img '.createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle').'>');
    $smarty->assign('sListRecLink','<a href="show_admit_general.php'.URL_APPEND."&pid=".$pid.'&target='.$target.'&flag=KS_p">'.$LDKhamPhu.'</a>');
    $smarty->assign('sListRecLink1','<a href="show_admit_general.php'.URL_APPEND."&pid=".$pid.'&target='.$target.'&flag=KS_s">'.$LDKhamSan.'</a>');

$smarty->assign('pbBottomClose','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'cancel.gif','0').'  title="'.$LDCancelClose.'"  align="absmiddle"></a>');

$smarty->assign('sMainBlockIncludeFile','medocs/main_KS_general_all.tpl');

$smarty->display('common/mainframe.tpl');

?>