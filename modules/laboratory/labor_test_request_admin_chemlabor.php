<?php
error_reporting ( E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR );
require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
 * GNU General Public License
 * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
 * , elpidio@care2x.org
 *
 * See the file "copy_notice.txt" for the licence notice
 */

/* Start initializations */
$lang_tables[]='departments.php';
$lang_tables[]='billing.php';
define ( 'LANG_FILE', 'konsil_chemlabor.php' );

/* We need to differentiate from where the user is coming:
*  $user_origin != lab ;  from patient charts folder
*  $user_origin == lab ;  from the laboratory
*  and set the user cookie name and break or return filename
*/

///$db->debug=1;


if ($user_origin == 'lab') {
	$local_user = 'ck_lab_user';
	$breakfile = $root_path . "modules/laboratory/labor.php" . URL_APPEND;
} elseif ($user_origin == 'amb') {
	$local_user = 'ck_lab_user';
	$breakfile = $root_path . 'modules/ambulatory/ambulatory.php' . URL_APPEND;
} else {
	$local_user = 'ck_pflege_user';
	$breakfile = $root_path . "modules/nursing/nursing-station-patientdaten.php" . URL_APPEND . "&edit=$edit&station=$station&pn=$pn";
}
require_once ($root_path . 'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/


$thisfile = 'labor_test_request_admin_chemlabor.php';

$bgc1 = '#fff3f3'; /* The main background color of the form */
$edit_form = 0; /* Set form to non-editable*/
$read_form = 1; /* Set form to read */
$edit = 0; /* Set script mode to no edit*/

$formtitle = $LDChemicalLaboratory;
$dept_nr = 24; // 24 = department Nr. chemical lab


$subtarget = 'chemlabor';
$subtarget_sub = 'chemlabor_sub';

require_once ($root_path . 'include/care_api_classes/class_encounter.php');
require_once ($root_path . 'include/care_api_classes/class_lab.php');
$enc_obj = new Encounter ( );
$lab_obj = new Lab($pn);
require($root_path.'include/care_api_classes/class_ecombill.php');
$eComBill = new eComBill;
/* Here begins the real work */

if (! isset ( $mode ))
	$mode = '';

switch ($mode) {
	case 'done' :
		$sql = "UPDATE care_test_request_" . $subtarget . "
				SET status = 'done',
						history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . ",
						modify_id = '" . $_SESSION ['sess_user_name'] . "',
						modify_time = '" . date ( 'YmdHis' ) . "'
				WHERE batch_nr = '" . $batch_nr . "'";

		if ($ergebnis = $enc_obj->Transact ( $sql )) {
			include_once ('includes/inc_diagnostics_report_fx.php');
			//echo $sql;
			/* If the findings are saved, signal the availability of report
									*/
			$sql1 = "UPDATE care_test_findings_chemlab
				SET status = 'done',
						history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . ",
						modify_id = '" . $_SESSION ['sess_user_name'] . "',
						modify_time = '" . date ( 'YmdHis' ) . "'
				WHERE job_id = '" . $batch_nr . "'";
				//echo $sql1;
				$db->execute($sql1);
			if($result_tests = $lab_obj->GetTestsToDo($batch_nr))
				{
				$para_array=array();
            }

            $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s')); //them dong nay
            signalNewDiagnosticsReportEvent ( '', 'labor_test_request_printpop.php' );
            header ( "location:" . $thisfile . URL_REDIRECT_APPEND . "&edit=$edit&pn=$pn&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&noresize=$noresize" );

            exit ();
        } else {
            echo "<p>$sql<p>$LDDbNoSave";
            $mode = "";
        }
        break;

} // end of switch($mode)


if (!$mode) {/* Get the pending test requests */
	$sql = "SELECT batch_nr,encounter_nr,send_date,dept_nr,room_nr FROM care_test_request_" . $subtarget . "
			WHERE (status='pending' OR status='') ORDER BY  send_date DESC";

	if ($requests = $db->Execute ( $sql )) {
		/* If request is available, load the date format functions */
		require_once ($root_path . 'include/core/inc_date_format_functions.php');

		$batchrows = $requests->RecordCount ();
		if ($batchrows && (! isset ( $batch_nr ) || ! $batch_nr)) {
			$test_request = $requests->FetchRow ();
			/* Check for the patient number = $pn. If available get the patients data */
			$pn = $test_request ['encounter_nr'];
			$batch_nr = $test_request ['batch_nr'];
            $bill_item_code=$test_request['bill_item_code'];
		}
	} else {
		echo "<p>$sql<p>$LDDbNoRead";
		exit ();
	}

    $sql5="SELECT BB.bill_item_code
          FROM care_test_request_".$subtarget." AS TR
          JOIN care_billing_bill_item AS BB ON TR.encounter_nr = BB.bill_item_encounter_nr
          WHERE (STATUS='pending' OR STATUS='received')
          AND TR.batch_nr=".$batch_nr."
          AND DATE(BB.bill_item_date)=DATE(TR.send_date)
          AND HOUR(BB.bill_item_date)=HOUR(TR.send_date)
          AND MINUTE(BB.bill_item_date)=MINUTE(TR.send_date)
          ORDER BY  send_date DESC
    ";
    if ($temp = $db->Execute ( $sql5 )) {
        $buf5 = $temp->FetchRow ();
        $bill_item_code=$buf5['bill_item_code'];
    }

    if($bill_item_code=="NT"){
        $YC = 'NT';
    }
    elseif($bill_item_code=="ION01"){
    $YC = 'ION01';
    }
    elseif($bill_item_code=="ION02"){
    $YC = 'ION02';
    }
    elseif($bill_item_code=="ION03"){
        $YC = 'ION03';
    }
    elseif($bill_item_code=="ION04"){
        $YC = 'ION04';
    }
    elseif($bill_item_code=="HSM01"){         //Glucose       1
        $YC = 'HSM01';
    }
    elseif($bill_item_code=="HSM02"){         //Fructosamin   2
        $YC = 'HSM02';
    }
    elseif($bill_item_code=="HSM03"){         //Urea          3
        $YC = 'HSM03';
    }
    elseif($bill_item_code=="HSM04"){         //Creatinin      4
        $YC = 'HSM04';
    }
    elseif($bill_item_code=="HSM39"){         //Cl           5    x
        $YC = 'HSM39';
    }
    elseif($bill_item_code=="HSM05"){          //Acid Uric     6
        $YC = 'HSM05';
    }
    elseif($bill_item_code=="HSM18"){       //Calci   - Ca ion hóa / ISE  7
        $YC = 'HSM18';
    }
    elseif($bill_item_code=="HSM20"){       //LDH  -  LDH/DGKC            8
        $YC = 'HSM20';
    }
    elseif($bill_item_code=="HSM21"){       //α HBDG - α HBDH             9
        $YC = 'HSM21';
    }
    elseif($bill_item_code=="HSM22"){       //CBK      -  CPK/DGKC        10
        $YC = 'HSM22';
    }
    elseif($bill_item_code=="HSM23"){       //CBK-MB -  CPK - MB/DGKC      11
        $YC = 'HSM23';
    }
    elseif($bill_item_code=="HSM06"){      //SGOT    - SGOT                12
        $YC = 'HSM06';
    }
    elseif($bill_item_code=="HSM07"){      //SGPT   - SGPT                 13
        $YC = 'HSM07';
    }
    elseif($bill_item_code=="HSM25"){       //ACP T.P  -  ACP toàn phần      14
        $YC = 'HSM25';
    }
    elseif($bill_item_code=="HSM24"){       //ACP T.L  -  ACP tiền liệt       15
        $YC = 'HSM24';
    }
    elseif($bill_item_code=="HSM36"){       //ALP T.P    -  ALP toàn phần     16
        $YC = 'HSM36';
    }
    elseif($bill_item_code=="HSM38"){       //GGT/Szasz -                   17
        $YC = 'HSM38';
    }
    elseif($bill_item_code=="HSM08"){        //BilirubinT.P -  Bilirubin - toàn phần/liên hợp  18
        $YC = 'HSM08';
    }
    elseif($bill_item_code=="HSM09"){        //BilirubinT.T -   Bilirubin - liên hợp       19
        $YC = 'HSM09';
    }
    elseif($bill_item_code=="HSM10"){        //Fe - Sắt trưởng thành                       20
        $YC = 'HSM10';
    }
    elseif($bill_item_code=="HSM26"){        //Ferritin/tt   - Ferritin/ tt            21
        $YC = 'HSM26';
    }
    elseif($bill_item_code=="HSM27"){        //Transferrin   - Transferrin/ tt             22
        $YC = 'HSM27';
    }
    elseif($bill_item_code=="HSM11"){       //Protein T.P  - Protein t. phần/ Biuet               23
        $YC = 'HSM11';
    }
    elseif($bill_item_code=="HSM37"){       //Albumin                      24
        $YC = 'HSM37';
    }
    elseif($bill_item_code=="HSM21"){       //Globulin    -    Globulin             28
        $YC = 'HSM21';
    }
    elseif($bill_item_code=="HSM29"){       //Tỉ số A/G   -  Tỉ số A/G                26
        $YC = 'HSM29';
    }
    elseif($bill_item_code=="HSM30"){       //ApoA1     - Apo.A1/SK                  27
        $YC = 'HSM30';
    }
    elseif($bill_item_code=="HSM31"){      //ApoB     - Apo.B/SK                    28
        $YC = 'HSM31';
    }
    elseif($bill_item_code=="HSM12"){      //Triglycerid    -  Triglycerit/tt GPO-PAP             29
        $YC = 'HSM12';
    }
    elseif($bill_item_code=="HSM13"){       //Cholesterol - Cholesterol t.phần/CHOD               30
        $YC = 'HSM13';
    }
    elseif($bill_item_code=="HSM14"){       //HDL Chol   - HDL.C                 31
        $YC = 'HSM14';
    }
    elseif($bill_item_code=="HSM35"){       //	xLDL Chol    - LDL.C               32
        $YC = 'HSM35';
    }
    elseif($bill_item_code=="HSM33"){      //Amyl T.P -    Amyl toàn phần/EPS         33      x
        $YC = 'HSM33';
    }
    elseif($bill_item_code=="HSM32"){       //Amyl T  - Amyl tụy/EPS             34
        $YC = 'HSM32';
    }
    elseif($bill_item_code=="HSM34"){       //CHE   -    CHE            35
        $YC = 'HSM34';
    }
    elseif($bill_item_code=="HSM16"){       //Fibrinogen   - Fibrinogen          36
        $YC = 'HSM16';
    }
//	$sql1 = "SELECT bill.bill_item_status, bill.bill_item_code
//			FROM care_test_request_" . $subtarget . " AS req
//			INNER JOIN care_test_request_chemlabor_sub AS req_sub ON req_sub.batch_nr=req.batch_nr
//			INNER JOIN care_test_param AS tp ON req_sub.paramater_name=tp.id
//			INNER JOIN care_billing_bill_item AS bill ON req_sub.encounter_nr=bill.bill_item_encounter_nr AND DATE(req.send_date)=DATE(bill.bill_item_date) AND tp.bill_item_nr=bill.bill_item_code
//			WHERE req.batch_nr=$batch_nr
//			ORDER BY req.send_date DESC";

    $sql1="SELECT TR.batch_nr,TR.encounter_nr,TR.send_date,BB.bill_item_status
          FROM care_test_request_" . $subtarget . " AS TR
          JOIN care_billing_bill_item AS BB ON TR.encounter_nr = BB.bill_item_encounter_nr
          WHERE BB.bill_item_code='$YC'
          AND DATE(BB.bill_item_date)=DATE(TR.send_date)
          AND HOUR(BB.bill_item_date)=HOUR(TR.send_date)
          AND MINUTE(BB.bill_item_date)=MINUTE(TR.send_date)
          AND TR.batch_nr=".$batch_nr."

          ";
	if ($requests1 = $db->Execute ( $sql1 )) {
		$bill = $requests1->FetchRow ();
		$status_bill=$bill['bill_item_status'];
	}
	$mode = "show";
}

/* Check for the patietn number = $pn. If available get the patients data */
if ($batchrows && $pn) {

	if ($enc_obj->loadEncounterData ( $pn )) {

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

		$result = &$enc_obj->encounter;

		$sql = "SELECT * FROM care_test_request_" . $subtarget . " ";
		$sql .= "INNER JOIN care_test_request_" . $subtarget_sub . " ON ";
		$sql .= "( care_test_request_" . $subtarget . ".batch_nr = care_test_request_" . $subtarget_sub . ".batch_nr) ";
		$sql .= "WHERE care_test_request_" . $subtarget . ".batch_nr='" . $batch_nr . "'";
//echo $sql;
		if ($ergebnis = $db->Execute ( $sql )) {
			//if ($editable_rows = $ergebnis->RecordCount ()) {
				while ( !$ergebnis->EOF ) {
					$stored_param[$ergebnis->fields['paramater_name']] = $ergebnis->fields['parameter_value'];
					$stored_request = $ergebnis->GetRowAssoc ( $toUpper = false );
					$ergebnis->MoveNext ();
				}

				$edit_form = 1;
			//}
		} else {
			echo "<p>$sql<p>$LDDbNoRead";
		}
	}
}

# Prepare title
$sTitle = $LDPendingTestRequest;
if ($batchrows)
	$sTitle = $sTitle . " (" . $batch_nr . ")";

# Start Smarty templating here
/**
 * LOAD Smarty
 */

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme


require_once ($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care ( 'common' );

# Title in toolbar
$smarty->assign ( 'sToolbarTitle', $sTitle );

# href for help button
$smarty->assign ( 'pbHelp', "javascript:gethelp('pending_chemlab.php')" );

# hide return  button
$smarty->assign ( 'pbBack', FALSE );

# href for close button
$smarty->assign ( 'breakfile', $breakfile );

# Window bar title
$smarty->assign ( 'sWindowTitle', $sTitle );

# collect extra javascript code
ob_start ();
?>

<style type="text/css">
.lab {
	font-family: arial;
	font-size: 13;
	color: purple;
}

.lmargin {
	margin-left: 5;
}
</style>

<script language="javascript">
<!--

<?php
if ($edit) {
	?>

function chkForm(d)
{
   return true
}

function loadM(fn)
{
	mBlank=new Image();
	mBlank.src="../img/pink_border.gif";
	mFilled=new Image();
	mFilled.src="../img/filled_pink_block.gif";

	form_name=fn;
}

function setM(m)
{
    eval("marker=document.images."+m);
	eval("element=document."+form_name+"."+m);

    if(marker.src!=mFilled.src)
	{
	   marker.src=mFilled.src;
	   element.value='1';
	  // alert(element.name+element.value);
	}
	 else
	 {
	    marker.src=mBlank.src;
		element.value='0';
	  // alert(element.name+element.value);
	 }
}


function setThis(prep,elem,begin,end,step)
{
  for(i=begin;i<end;i=i+step)
  {
     x=prep + i;
     if(elem!=i)
     {
       eval("marker=document.images."+x);
	   if(marker.src==mFilled.src)  setM(x);
     }
  }
  setM(prep+elem);
}

function sendLater()
{
   document.form_test_request.status.value="draft";
   if(chkForm(document.form_test_request)) document.form_test_request.submit();
}


<?php
}
?>

function printOut()
{
	urlholder="labor_test_request_printpop.php?sid=<?php
	echo $sid?>&lang=<?php
	echo $lang?>&user_origin=<?php
	echo $user_origin?>&target=<?php
	echo $target?>&subtarget=<?php
	echo $subtarget?>&batch_nr=<?php
	echo $batch_nr?>&pn=<?php
	echo $stored_request ['encounter_nr']?>";
	testprintout<?php
	echo $sid?>=window.open(urlholder,"testprintout<?php
	echo $sid?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    //testprintout<?php
				echo $sid?>.print();
}

<?php
require ($root_path . 'include/core/inc_checkdate_lang.php');
?>

//-->
</script>
<?php

$sTemp = ob_get_contents ();
ob_end_clean ();

$smarty->append ( 'JavaScript', $sTemp );

# Buffer page output


ob_start ();

# If pending request available, show list and actual form


if ($batchrows) {

	?>

<table border=0>
	<tr valign="top">
		<!-- Left block for the request list  -->
		<td>
<?php
	;
	/* The following routine creates the list of pending requests */
	require ('includes/inc_test_request_lister_fx.php');

	?>
		</td>
		<!-- right block for the form -->
		<td><!-- Here begins the form  --> <!--<a href="javascript:printOut()"><img
			<?php
	echo createLDImgSrc ( $root_path, 'printout.gif', '0', 'absmiddle' )?>
			alt="<?php
	echo $LDPrintOut?>"></a>--><a
			href="<?php
	echo 'labor_datainput.php' . URL_APPEND . '&encounter_nr=' . $pn . '&job_id=' . $batch_nr . '&mode=' . $mode . '&update=0&user_origin=lab_mgmt';
	?>"><img
			<?php
	echo createLDImgSrc ( $root_path, 'enterresults.gif', '0', 'absmiddle' )?>
			alt="<?php
	echo $LDEnterResult?>"></a> <a
			href="<?php
	echo $thisfile . URL_APPEND . "&edit=" . $edit . "&mode=done&target=" . $target . "&subtarget=" . $subtarget . "&batch_nr=" . $batch_nr . "&pn=" . $pn . "&formtitle=" . $formtitle . "&user_origin=" . $user_origin . "&noresize=" . $noresize;
	?>"><img
			<?php
	echo createLDImgSrc ( $root_path, 'done.gif', '0', 'absmiddle' )?>
			alt="<?php
	echo $LDDone?>"></a>

<?php
	require_once ('includes/inc_test_request_printout_chemlabor.php');
	?>

    <!-- <a href="javascript:printOut()"><img
			<?php
	echo createLDImgSrc ( $root_path, 'printout.gif', '0', 'absmiddle' )?>
			alt="<?php
	echo $LDPrintOut?>"></a>--><a
			href="<?php
	echo 'labor_datainput.php' . URL_APPEND . '&encounter_nr=' . $pn . '&job_id=' . $batch_nr . '&mode=' . $mode . '&update=1&user_origin=lab_mgmt';
	?>"><img
			<?php
	echo createLDImgSrc ( $root_path, 'enterresults.gif', '0', 'absmiddle' )?>
			alt="<?php
	echo $LDEnterResult?>"></a> <a
			href="<?php
	echo $thisfile . URL_APPEND . "&edit=" . $edit . "&mode=done&target=" . $target . "&subtarget=" . $subtarget . "&batch_nr=" . $batch_nr . "&pn=" . $pn . "&formtitle=" . $formtitle . "&user_origin=" . $user_origin . "&noresize=" . $noresize;
	?>"><img
			<?php
	echo createLDImgSrc ( $root_path, 'done.gif', '0', 'absmiddle' )?>
			alt="<?php
	echo $LDDone?>"></a></td>
	</tr>
</table>

<?php
} else {
	?>
<img
	<?php
	echo createMascot ( $root_path, 'mascot1_r.gif', '0', 'absmiddle' )?>>
<font size=3 face="verdana,arial" color="#990000"><b><?php
	echo $LDNoPendingRequest?></b></font>
<p><a href="<?php
	echo $breakfile?>"><img
	<?php
	echo createLDImgSrc ( $root_path, 'back2.gif', '0' )?>></a>
<?php
}

$sTemp = ob_get_contents ();
ob_end_clean ();

# Assign the page output to main frame template


$smarty->assign ( 'sMainFrameBlockData', $sTemp );

/**
 * show Template
 */
$smarty->display('common/mainframe.tpl');

?>
