<?php
    //like labor_test_request_aftersave.php
    error_reporting ( E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR );
    require ('./roots.php');
    require ($root_path . 'include/core/inc_environment_global.php');
    $lang_tables [] = 'departments.php';
    $lang_tables [] = 'konsil.php';
    $lang_tables [] = 'lab.php';
    define ( 'LANG_FILE', 'or.php' );
    if ($user_origin == 'op') {
	$local_user='aufnahme_user';
        if($target='or'){
            $breakfile=$root_path."modules/registration_admission/aufnahme_daten_zeigen.php".URL_APPEND."&from=such&encounter_nr=".$_SESSION['sess_en']."&target=search";
        }
    }
    require_once ($root_path . 'include/core/inc_front_chain_lang.php');
    require_once ($root_path . 'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care ( 'nursing' );
    $thisfile = basename ( __FILE__ );
    $db_request_table = $target;
    $db_request_table_sub = $target . "_sub";
    if (isset ( $pn ) && $pn) {
        include_once ($root_path . 'include/care_api_classes/class_encounter.php');
	$enc_obj = new Encounter ( );
        if ($enc_obj->loadEncounterData ( $pn )) {
		$edit = true;
		include_once ($root_path . 'include/care_api_classes/class_globalconfig.php');
		$GLOBAL_CONFIG = array ();
		$glob_obj = new GlobalConfig ( $GLOBAL_CONFIG );
		$glob_obj->getConfig ( 'patient_%' );
		switch ($enc_obj->EncounterClass ()) {
			case '1' :
				$full_en = ($pn + $GLOBAL_CONFIG ['patient_inpatient_nr_adder']);
				break;
			case '2' :
				$full_en = ($pn + $GLOBAL_CONFIG ['patient_outpatient_nr_adder']);
				break;
			default :
				$full_en = ($pn + $GLOBAL_CONFIG ['patient_inpatient_nr_adder']);
		}
		$_SESSION ['sess_en'] = $pn;
		$_SESSION ['sess_full_en'] = $full_en;
	}
    }
    require_once ($root_path . 'include/core/inc_date_format_functions.php');
    if (!isset ( $mode ))
	$mode = '';
    if ($enc_obj->is_loaded) {
        $sql = "SELECT * FROM care_test_request_" . $db_request_table . " ";
        $sql .= "WHERE batch_nr='" . $batch_nr . "' ";
        if ($ergebnis = $db->Execute ( $sql )) {
            if ($editable_rows = $ergebnis->RecordCount ()) {
                if ($target == 'or') {
                    while ( !$ergebnis->EOF ) {
                        $stored_request=$ergebnis->FetchRow();
                        $ergebnis->MoveNext ();
                    }
                }
                $read_form = 1;
                $printmode = 1;
            }
        }else {
		echo "<p>$sql<p>$LDDbNoRead";
	}
    }
    switch ($target) {
        case 'patho' :
            $formtitle = $LDAnesthesiology;
            $bgc1 = '#cde1ec';
            break;
        default :
            $bgc1 = '#ffffff';
    }

    $smarty->assign ( 'setCharSet', setCharSet () );
    if (!isset ( $edit ) || empty ( $edit ))
	$smarty->assign ( 'edit', FALSE );
    $smarty->assign ( 'printmode', TRUE );
    $smarty->assign ( 'HTMLtag', html_ret_rtl ( $lang ) );
    $smarty->assign ( 'top_txtcolor', $cfg ['top_txtcolor'] );
    $smarty->assign ( 'top_bgcolor', $cfg ['top_bgcolor'] );
    $smarty->assign ( 'body_bgcolor', $cfg ['body_bgcolor'] );
    $smarty->assign ( 'body_txtcolor', $cfg ['body_txtcolor'] );
    $smarty->assign ( 'bgc1', $bgc1 );   
    $smarty->assign ( 'gifHilfeR', createLDImgSrc ( $root_path, 'hilfe-r.gif', '0' ) );
    $smarty->assign ( 'LDCloseAlt', $LDCloseAlt );
    $smarty->assign ( 'gifClose2', createLDImgSrc ( $root_path, 'close2.gif', '0' ) );
    $smarty->assign ( 'sToolbarTitle', "$LDTestRequestOP" );
    $smarty->assign ( 'pbBack', 'javascript:window.history.back()' );
    $smarty->assign ( 'gifBack2', createLDImgSrc ( $root_path, 'back2.gif', '0' ) );
    $smarty->assign ( 'pbHelp', 'javascript:gethelp(\'request_aftersave.php\')' );
    $smarty->assign ( 'breakfile', $breakfile );
    if ($cfg ['dhtml']) {
        $smarty->assign ( 'dhtml', 'class="fadeOut"' );
    } else {
        $smarty->assign ( 'dhtml', '' );
    }?>
<?php
    ob_start ();
    require('in_giay_mo.php');
    $sTemp1 = ob_get_contents ();
    ob_end_clean ();
    $smarty->assign ( 'ShowFrame', $sTemp1);
?>
<?php
    $smarty->assign ( 'title', $LDTestRequest );
    $smarty->assign ( 'Name', $station );
    $smarty->assign ( 'css_lab', '.lab {font-family: arial; font-size: 9; color:purple;}' );
    ob_start ();
?>
<script language="javascript">
<!--
function printOut() {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/emr_generic/report_op.php<?php echo URL_APPEND ?>&subtarget=<?php echo $target ?>&batch_nr=<?php echo $batch_nr ?>&enc=<?php echo $pn ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    //testprintout<?php
				echo $sid?>.print();
}
// -->
</script>
<?php
require ($root_path . 'include/core/inc_js_gethelp.php');
require ($root_path . 'include/core/inc_css_a_hilitebu.php');
$sTemp = ob_get_contents ();
ob_end_clean ();
$smarty->assign ( 'JavaScript', $sTemp );
$smarty->assign ( 'gifMascot', '<img ' . createMascot ( $root_path, 'mascot1_r.gif', '0', 'absmiddle' ) . '>' );
if ($status == "draft")
    $smarty->assign ( 'sAfterSavePrompt', $LDFormSaved [$saved] );
else
    $smarty->assign ( 'sAfterSavePrompt', $LDRequestSent [$saved] );
$smarty->assign ( 'LDWhatToDo', $LDWhatToDo );
$smarty->assign ( 'pbPrintOut', 'javascript:printOut()' );
$smarty->assign ( 'gifGrnArrow', '<img ' . createComIcon ( $root_path, 'bul_arrowgrnsm.gif', '0', 'absmiddle', TRUE ) . '>' );
$smarty->assign ( 'LDPrintForm', $LDPrintForm );
$smarty->assign ( 'pbEditForm', $root_path . "modules/registration_admission/request-OP-station-patientdaten-doconsil.php".URL_APPEND."&edit=$edit&pn=".$pn."&target=or&noresize=1&mode=edit&batch_nr=".$batch_nr."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr."&flag=");
$smarty->assign ( 'LDEditForm', $LDEditForm1 );
$smarty->assign ( 'LDEndTestRequest', $LDEndTestRequest );
//require_once ('includes/inc_test_request_printout_fx.php');
ob_start ();
include ($root_path . 'include/inc_test_request_printout_' . $target . '.php');
$sTemp = ob_get_contents ();
ob_end_clean ();
$smarty->assign ( 'printout_form', $sTemp );
$smarty->assign ( 'sCopyright', $smarty->Copyright () );
$smarty->assign ( 'sPageTime', $smarty->Pagetime () );
$smarty->display ( 'laboratory/request_aftersave_1.tpl' );
?>