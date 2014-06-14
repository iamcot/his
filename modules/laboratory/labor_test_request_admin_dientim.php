<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
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
define('LANG_FILE','konsil.php');

/* We need to differentiate from where the user is coming: 
*  $user_origin != lab ;  from patient charts folder
*  $user_origin == lab ;  from the laboratory
*  and set the user cookie name and break or return filename
*/
if($user_origin=='lab'){
    $local_user='ck_lab_user';
    $breakfile=$root_path.'modules/radiology/radiolog.php'.URL_APPEND;
}elseif($user_origin=='amb'){
    $local_user='ck_lab_user';
    $breakfile=$root_path.'modules/ambulatory/ambulatory.php'.URL_APPEND;
}else{
    $local_user='ck_pflege_user';
    $breakfile=$root_path."modules/nursing/nursing-station-patientdaten.php".URL_APPEND."&edit=$edit&station=$station&pn=$pn";
}

require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/

require_once($root_path.'global_conf/inc_global_address.php');

$thisfile= basename(__FILE__);
$uploadfile= $root_path.'modules/radiology/upload.php'.URL_APPEND.'&user_origin='.$local_user.'&encounter_nr=';

$bgc1='#ffffff'; /* The main background color of the form */
$edit_form=0; /* Set form to non-editable*/
$read_form=1; /* Set form to read */
$edit=0; /* Set script mode to no edit*/

$formtitle=$LDDienTim;

//$db_request_table=$subtarget;
$db_request_table='dientim';
$subtarget='dientim';
//$db->debug=1;

/* Here begins the real work */
require_once($root_path.'include/core/inc_date_format_functions.php');


if(!isset($mode))   $mode='';

switch($mode){
    case 'update':
    {
        # Create a core object
        include_once($root_path.'include/core/inc_front_chain_lang.php');
        $core = & new Core;

        $sql="UPDATE care_test_request_".$db_request_table." SET
										  results='".addslashes(htmlspecialchars($results))."',
                                          results_date='".formatDate2STD($results_date,$date_format)."',
										  results_doctor='".htmlspecialchars($results_doctor)."',
										  status='received',
										  history=".$core->ConcatHistory("Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n").",
										  modify_id = '".$_SESSION['sess_user_name']."',
										  modify_time='".date('YmdHis')."'  ,
										  create_time='".date('YmdHis')."'
					WHERE batch_nr = '".$batch_nr."'";

        if($ergebnis=$core->Transact($sql)){
            //echo $sql;
//	gốc		header("location:".$thisfile."?sid=$sid&lang=$lang&edit=$edit&saved=update&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&batch_nr=$batch_nr&noresize=$noresize");
            header('Content-Type: text/html; charset=utf-8');       //dã thêm
            echo "<script type='text/javascript'>";                 //dã thêm
            echo "alert('Kết quả đã được lưu.');";                           //dã thêm
//            echo "alert('$LDNotifySave');";                                        //dã thêm
            echo "window.location.replace('".$thisfile."?sid=".$sid."&lang=".$lang."&edit=".$edit."&saved=update&pn=".$pn."&station=".$station."&user_origin=".$user_origin."&status=".$status."&target=".$target."&subtarget=".$subtarget."&batch_nr=".$batch_nr."&noresize=".$noresize."')";//dã thêm
            echo "</script>";
            exit;
        } else {
            echo "<p>$sql<p>$LDDbNoSave";
            $mode='';
        }
        break; // end of case 'save'
    }
    default: $mode='';
}// end of switch($mode)

/* Get the pending test requests */
if(!$mode) {
    $sql="SELECT batch_nr,encounter_nr,send_date,dept_nr FROM care_test_request_".$db_request_table."
				WHERE status='pending' OR status='received' ORDER BY  send_date DESC";
    if($requests=$db->Execute($sql)){
        $batchrows=$requests->RecordCount();
        if($batchrows && (!isset($batch_nr) || !$batch_nr)){
            $test_request=$requests->FetchRow();
            /* Check for the patietn number = $pn. If available get the patients data */
            $pn=$test_request['encounter_nr'];
            $batch_nr=$test_request['batch_nr'];
        }
    }else{
        echo "<p>$sql<p>$LDDbNoRead";
        exit;
    }
    $mode='update';
}

/* Check for the patient number = $pn. If available get the patients data */
if($batchrows && $pn){
    include_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter;
    if( $enc_obj->loadEncounterData($pn)) {

        include_once($root_path.'include/care_api_classes/class_globalconfig.php');
        $GLOBAL_CONFIG=array();
        $glob_obj=new GlobalConfig($GLOBAL_CONFIG);
        $glob_obj->getConfig('patient_%');
        switch ($enc_obj->EncounterClass())
        {
            case '1': $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
                break;
            case '2': $full_en = ($pn + $GLOBAL_CONFIG['patient_outpatient_nr_adder']);
                break;
            default: $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
        }

        if( $enc_obj->is_loaded){
            $result=&$enc_obj->encounter;

            $sql="SELECT * FROM care_test_request_".$db_request_table." WHERE batch_nr='".$batch_nr."'";
            if($ergebnis=$db->Execute($sql)){
                if($editable_rows=$ergebnis->RecordCount()){
                    $stored_request=$ergebnis->FetchRow();
                    $edit_form=1;

                    $sql1="SELECT * FROM care_test_request_dientim_sub WHERE batch_nr=".$batch_nr;
                    $item_test=$db->Execute($sql1);
                }
            }else{
                echo "<p>$sql<p>$LDDbNoRead";
            }
        }
        $uploadfile= $uploadfile.$pn.'&pid='.$enc_obj->encounter['pid'];
    }else{
        $mode='';
        $pn='';
    }
}

# Prepare title
$sTitle = $LDDienTim.': '.$LDPendingTestRequest;
if($batchrows) $sTitle = $sTitle." (".$batch_nr.")";

# Start Smarty templating here
/**
 * LOAD Smarty
 */

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('nursing');

# Title in toolbar
$smarty->assign('sToolbarTitle',$sTitle);

# hide back button
$smarty->assign('pbBack',FALSE);

# href for help button
$smarty->assign('pbHelp',"javascript:gethelp('pending_radio.php')");

# href for close button
$smarty->assign('breakfile',$breakfile);

# Window bar title
$smarty->assign('sWindowTitle',$sTitle);

# Create button to view results

$smarty->assign('pbAux1',"javascript:viewallresults()");
$smarty->assign('gifAux1',createLDImgSrc($root_path,'showreport.gif','0'));

$smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus();"');

# Collect extra javascript code

ob_start();
?>

<style type="text/css">
    div.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10;}
    div.fa2_ml10 {font-family: arial; font-size: 12; margin-left: 10;}
    div.fva2_ml3 {font-family: verdana; font-size: 12; margin-left: 3; }
    div.fa2_ml3 {font-family: arial; font-size: 12; margin-left: 3; }
    .fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva2b_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva0_ml10 {font-family: verdana,arial; font-size: 10; margin-left: 10; color:#000000;}
</style>

<script language="javascript">
    <!--

    function chkForm(d)
    {
        if(d.results.value=="" || d.results.value==" ")
        {
            return false;
        }
        else if(d.results_date.value=="" || d.results_date.value==" ")
        {
            alert('<?php echo $LDPlsEnterDate ?>');
            d.results_date.focus();
            return false;
        }
        else if(d.results_doctor.value=="" || d.results_doctor.value=="")
        {
            alert('<?php echo $LDPlsEnterDoctorName ?>');
            d.results_doctor.focus();
            return false;
        }
        else return true;
    }
    function saveDone()
    {
        var r=confirm('<?php echo $LDSaveBeforeDone;?>');
        if(r==true)
        {
            window.location="<?php echo 'labor_test_findings_'.$subtarget.'.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker.'&mode=done'; ?>";
        }
        else
        {
            return false;
        }
    }
    function saveResult()
    {
        <!--    var r=alert('--><?php //echo $LDAlertBeforeSave;?><!--');-->
        var r=alert('<?php echo $LDNotifySave;?>');
        document.form_test_request.action="<?php echo 'labor_test_findings_'.$subtarget.'.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker.'&mode=save'; ?>";
        document.form_test_request.submit();
    }
    function printOut()
    {
        urlholder="<?php echo $root_path;?>modules/pdfmaker/dientim/PhieuDienTim.php<?php echo URL_APPEND; ?>&enc=<?php echo $pn;?>";
        testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
        //testprintout<?php echo $sid ?>.print();
    }
    function viewallresults(){
        document.form_test_request.action="<?php echo '../radiology/viewresults_dientim.php?sid='.$sid.'&lang='.$lang.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin; ?>";
        document.form_test_request.submit();
    }

    function popDocPer(target,obj_val,obj_name){     //dã thêm
        urlholder="<?php echo $root_path; ?>modules/laboratory/personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;  //dã thêm
        DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");                                //dã thêm
    }
    <?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>

    //-->
</script>
<?php

$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript',$sTemp);

ob_start();

if($batchrows){

    ?>

    <!-- Table for the list index and the form -->
    <table border=0>
        <tr valign="top">
            <td>
                <?php

                /* The following routine creates the list of pending requests */
                require('includes/inc_test_request_lister_fx.php');

                ?></td>

            <td>

                <form name="form_test_request" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">
                    <!-- 		<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>" onclick="saveResult();">
			<a href="#" onclick="saveDone();"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a> -->

                    <input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>">
<!--                    <a href="--><?php //echo 'labor_test_findings_'.$subtarget.'.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker.'&mode=done'; ?><!--"><img --><?php //echo createLDImgSrc($root_path,'enter_result.gif','0') ?><!-- alt="--><?php //echo $LDEnterResult ?><!--"></a>-->
                    <a href="#" onclick="saveDone();"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a>

                    <!--  outermost table creating form border -->
                    <table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
                        <tr>
                            <td>

                                <table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
                                    <tr>
                                        <td>

                                            <table   cellpadding=0 cellspacing=1 border=0 width=700>
                                                <tr  valign="top">
                                                    <td  bgcolor="<?php echo $bgc1 ?>" rowspan=2>
                                                        <?php
                                                        if($edit || $read_form)
                                                        {
                                                            echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
                                                        }
                                                        ?></td>
                                                    <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10><div   class=fva2_ml10><font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
                                                            <br>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td bgcolor="<?php echo $bgc1 ?>" align="right" valign="bottom">
                                                        <?php
                                                        echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
                                                        echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>";
                                                        ?>
                                                    </td>
                                                </tr>

                                                <tr bgcolor="<?php echo $bgc1 ?>">
                                                    <td  valign="top" colspan=2 >

                                                        <table border=0 cellpadding=1 cellspacing=1 width=100%>


                                                            <tr>
                                                                <td colspan=4><hr></td>
                                                            </tr>


                                                        </table>
                                                        &nbsp;<br>

                                                    </td>
                                                </tr>

                                                <tr bgcolor="<?php echo $bgc1 ?>">
                                                    <td colspan=2><div class=fva2_ml10><?php echo $LDChandoan ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
                                                                <font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['clinical_info']) ?></font>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="<?php echo $bgc1 ?>">
                                                    <td colspan=2><div class=fva2_ml10><?php echo $LDReqTestTim ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
                                                                <font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['test_request']) ?></font>
                                                    </td>
                                                </tr>

                                                <tr bgcolor="<?php echo $bgc1 ?>">
                                                    <td colspan=2><div class=fva2_ml10>

                                                <?php
                                                echo '<table width="100%"><tr> <td align="right">'.$LDThanhToan.': </td></tr></table>';
                                                echo '<table width="100%" style="font-family:courier;font-size:small;" >';
                                                //$note="";
                                                if (is_object($item_test)){
                                                    for ($i=0;$i<$item_test->RecordCount();$i++){
                                                        $item = $item_test->FetchRow();
                                                        echo "<tr><td><a href=\"javascript:ShowResult('".$item['item_bill_code']."')\">".$item['item_bill_name'].'</a></td><td align="right">';
                                                        if($item_code=='' && $i==0){
                                                            $item_code=$item['item_bill_code'];
                                                        }
                                                        $sql_bill="SELECT * FROM care_billing_bill_item
							                                        WHERE bill_item_code='".$item['item_bill_code']."' AND bill_item_encounter_nr='".$pn."' AND bill_item_date='".$stored_request['create_time']."'";   //bill_time
                                                        //echo $sql_bill;
                                                        if($bill=$db->Execute($sql_bill)){
                                                            if($bill->RecordCount()){
                                                                $bill_row=$bill->FetchRow();
                                                            }else $bill_row['bill_item_status']=0;
                                                        }

                                                        if($bill_row['bill_item_status']){
                                                            $tempfinish=$LDFinish; $tempfinish1='check-r.gif';
                                                        }
                                                        else{
                                                            $tempfinish=$LDNotYet; $tempfinish1='warn.gif';
                                                        }
                                                        echo $tempfinish.' ';
                                                        echo '<img '.createComIcon($root_path,$tempfinish1,'0','',TRUE).'> </td></tr>';
                                                    }
                                                }
                                                echo '</table>';
                                                ?>
                                                    </td>
                                                </tr>

                                                <tr bgcolor="<?php echo $bgc1 ?>">
                                                    <td colspan=2 align="right"><div class=fva2_ml10>
                                                            <?php echo $LDDate ?>:
                                                            <font face="courier" size=2 color="#000000">&nbsp;<?php


                                                                echo formatDate2Local($stored_request['send_date'],$date_format);

                                                                ?></font>&nbsp;
                                                            <?php echo $LDRequestingDoc ?>:
                                                            <font face="courier" size=2 color="#000000">&nbsp;<?php echo $stored_request['send_doctor'] ?></font></div><br>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="<?php echo $bgc1 ?>">

                                                </tr>
                                                <tr bgcolor="<?php echo $bgc1 ?>">
                                                    <td colspan=2>
                                                        <div class=fva2_ml10>&nbsp;<br><font color="#000099"><?php echo $LDKqDienTim; ?></font><br>
                                                            <textarea name="results" cols=80 rows=5 wrap="physical"><?php if($read_form && $stored_request['results']) echo stripslashes($stored_request['results']) ?></textarea>
                                                    </td>
                                                </tr>

                                                <tr bgcolor="<?php echo $bgc1 ?>">
                                                    <td colspan=2 align="right"><div class=fva2_ml10><font color="#000099">
                                                                <?php echo $LDDate ?>

                                                                <?php
                                                                if($stored_request['results_date']=='0000-00-00')
                                                                    $stored_request['results_date']=date('Y-m-d');

                                                                //gjergji : new calendar
                                                                require_once ('../../js/jscalendar/calendar.php');
                                                                $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
                                                                $calendar->load_files();
                                                                //gjergji : new calendar
                                                                echo $calendar->show_calendar($calendar,$date_format,'results_date',$stored_request['results_date']);
                                                                //end : gjergji
                                                                ?>

                                                                <?php echo $LDReportingDoc ?>
                                                                <!--  g?c      <input type="text" name="results_doctor" value="--><?php //if($read_form && $stored_request['results_doctor']) echo $stored_request['results_doctor']; else echo $_SESSION['sess_user_name']; ?><!--" size=35 maxlength=35> -->

                                                                <input type="text" name="results_doctor" size=37 maxlength=40 value="<?php if($edit_form || $read_form) echo $stored_request['results_doctor'];else echo $pers_name;?>">
                                                                <input type="hidden" name="results_doctor_nr" value="<?php if(!empty( $stored_request['results_doctor_nr'])) echo $stored_request['results_doctor_nr'];else echo $pers_nr; ?>"> <a href="javascript:popDocPer('doctor_nr','results_doctor_nr','results_doctor')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>>
                                                    </td>
                                                </tr>
                                            </table>


                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                    <p>
                        <input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>">
                        <a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>
<!--                        <a href="--><?php //echo 'labor_test_findings_'.$subtarget.'.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker.'&mode=done'; ?><!--"><img --><?php //echo createLDImgSrc($root_path,'enter_result.gif','0') ?><!-- alt="--><?php //echo $LDEnterResult ?><!--"></a>-->

                        <?php

                        require($root_path.'modules/laboratory/includes/inc_test_request_hiddenvars.php');

                        ?>
                </form>
            </td>
        </tr>
    </table>

<?php
}
else
{
?>
<img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?> align="absmiddle"><form name="form_test_request" method="post" action="<?php echo $thisfile ?>" >
    <font size=3 face="verdana,arial" color="#990000"><b><?php echo $LDNoPendingRequest ?></b></font>
</form>
<p>
    <a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>></a>
    <?php
    }

    $sTemp = ob_get_contents();
    ob_end_clean();

    # Assign to page template object
    $smarty->assign('sMainFrameBlockData',$sTemp);

    /**
     * show Template
     */
    $smarty->display('common/mainframe.tpl');

    ?>
