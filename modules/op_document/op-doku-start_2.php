<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

define('MAX_BLOCK_ROWS',30);
define('REDIRECT_SINGLERESULT',0); # Define to 1 if single result must be redirected to input page

function createElement($item,$err, $f_size=7, $mx=5)
{
    global $mode, $err_data, $result, $lang, $isTimeElement,$opdoc;

	if($mode=='saveok')
    {
       $ret_str= '<font color="#800000">'.$opdoc[$item].' &nbsp;</font>';
    }
    else
    {
        $ret_str= '<input name="'.$item.'" type="text" size="'.$f_size.'"   maxlength='.$mx.' value="';
       if($err_data){
          $ret_str.=$err;
       }else{
          $ret_str.=$opdoc[$item];
       }

	   if($mode=='') $ret_str.='" ';
	     else $ret_str.='"';

	   if($isTimeElement)  $ret_str.= ' onKeyUp="setTime(this,\''.$lang.'\')">';
	     else $ret_str.='>';
	}
	return $ret_str;
}
$lang_tables[]='departments.php';
$lang_tables[]='doctors.php';
$lang_tables[]='search.php';
$lang_tables[]='prompt.php';
$lang_tables[]='actions.php';
define('LANG_FILE','or.php');
$local_user='ck_opdoku_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');

# Create encounter object
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj= new Encounter;
/* Save dept name to session */
if(!isset($_SESSION['sess_dept_name'])) $_SESSION['sess_dept_name'] = "";
/* Create dept object and preload dept info */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_obj->preloadDept($dept_nr);
$buffer=$dept_obj->LDvar();
if(isset($$buffer)&&!empty($$buffer)) $_SESSION['sess_dept_name']=$$buffer;
	else $_SESSION['sess_dept_name']=$dept_obj->FormalName();
// edit 28/11-Huỳnh///////////////////
require_once($root_path.'include/care_api_classes/class_oproom.php');
# Create the OR object
$OR_obj=& new OPRoom;
# Get all OR
$OR_rooms=$OR_obj->AllORInfo();
# Get the number or returned ORs
//chi chon nhung phong thuoc khoa phau thuat va gay me hoi suc
$select_room=$OR_obj->selectRoom(39);
$info_room=$OR_obj->getHistoryRoom($_POST['op_room']);
//$history=explode("Update:",$info_room['history']);
//$temp=explode(" ",$history[count($history)-1]);
/////////////////////////////////////////////
/* Load global configs */
include_once($root_path.'include/care_api_classes/class_globalconfig.php');
$GLOBAL_CONFIG=array();
$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
$glob_obj->getConfig('patient_%');

// edit 28/11-Huỳnh///////////////////
require_once($root_path.'include/care_api_classes/class_obstetrics.php');
$request_obj= new Obstetrics;
if($request_obj->ProfileSurgery($batch_nr)){
    $request_op=$request_obj->ProfileSurgery($batch_nr);
}
////////////////////////////////////////

if ((substr($matchcode,0,1)=='%')||(substr($matchcode,0,1)=='&')) {header("Location:'.$root_path.'language/".$lang."/lang_".$lang."_invalid-access-warning.php"); exit;};
//////////// edit 16/11-Huỳnh /////////////////
$breakfile=$root_path.'modules/op_document/op_test_request_admin.php'.URL_REDIRECT_APPEND."&target=".$target."&subtarget=".$subtarget."&noresize=1&&user_origin=op&checkintern=1";
$thisfile=basename(__FILE__);

 /* Set color values for the search mask */
$searchmask_bgcolor='#f3f3f3';
$searchprompt=$LDEntryPrompt_1;
$entry_block_bgcolor='#fff3f3';
$entry_border_bgcolor='#6666ee';
$entry_body_bgcolor='#ffffff';

if(!isset($dept)||empty($dept))
    if($_COOKIE['ck_thispc_dept']) $dept=$_COOKIE['ck_thispc_dept'];
            else $dept='plop'; // default department is plop

$linecount=0;
# check date for completeness

if($mode=='save')
{
	$err_data=0;
	if(!$op_date) {$err_op_date=1; $err_data=1;}
	if(!$operator) {$err_operator=1;$err_data=1;}
        /////// edit 28/11-Huỳnh ///////////
        if(!$surgery) {$err_surgery=1;$err_data=1;}
        if(!$medical_person) {$err_medical_person=1;$err_data=1;}
        ///////////////////////////////////
	if(!$diagnosis) {$err_diagnosis=1;$err_data=1;}
	if(!$localize) {$err_localize=1;$err_data=1;}
	if(!$therapy) {$err_therapy=1;$err_data=1;}
	if(!$special) {$err_special=1;$err_data=1;}
	if(!(($class_s)||($class_m)||($class_l))) {$err_klas=1;$err_data=1;}
	if(!$op_start) {$err_op_start=1;$err_data=1;}
	if(!$op_end) {$err_op_end=1;$err_data=1;}
	if(!$scrub_nurse) {$err_scrub_nurse=1;$err_data=1;}
	if(!$op_room) {$err_op_room=1;$err_data=1;}

	if($err_data) $mode='?';

}

    /* Load date formatter */
    include_once($root_path.'include/core/inc_date_format_functions.php');


    /* If the patient number is available = $patnum , get the data from the admission table */
    if(isset($pn) && !empty($pn)){
            $enc_obj->where=" encounter_nr=$pn";
        if( $enc_obj->loadEncounterData($pn)) {

                    $full_en=$pn;

                    if( $enc_obj->is_loaded){
                            $result=&$enc_obj->encounter;
                            $rows=$enc_obj->record_count;
                    }
            }else{
                    echo "$sql<br>$LDDbNoRead";
                    $mode='?';
            }
    }

    switch($mode){
        case 'update':
            $dbtable='care_op_med_doc';
            $dbtable1='care_room';
            $sql="SELECT * FROM $dbtable WHERE  nr='$nr'";
            if($ergebnis=$db->Execute($sql))
            {
                if($rows=$ergebnis->RecordCount())
                {
                        $opdoc=$ergebnis->FetchRow();
                }
            }else echo "$sql<br>$LDDbNoRead";
            //echo $sql;
            break;

        case 'save':
            $dbtable='care_op_med_doc';
            /* Prepare the time data */
            $op_start=strtr($op_start,'.;,',':::');
            $s_count=substr_count($op_start,':');
            switch($s_count)
            {
               case 0: $op_start.=':00:00'; break;
               case 1: $op_start.=':00';break;
               case '': $op_start.=':00:00';
            }
            $op_end=strtr($op_end,'.;,',':::');
            $s_count=substr_count($op_end,':');
            switch($s_count)
            {
               case 0: $op_end.=':00:00';break;
               case 1: $op_end.=':00';break;
               case '': $op_end.=':00:00';
            }
            if($update)
            {
                $sql="UPDATE $dbtable SET
                        op_date='".formatDate2STD($op_date,$date_format)."',
                        operator='$operator',
                        surgery='$surgery',
                        medical_person='$medical_person',
                        diagnosis='$diagnosis',
                        localize='$localize',
                        therapy='$therapy',
                        special='$special',
                        class_s='$class_s',
                        class_m='$class_m',
                        class_l='$class_l',
                        op_start='$op_start',
                        op_end='$op_end',
                        scrub_nurse='$scrub_nurse',
                        op_room='$op_room',
                        history=".$enc_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
                        modify_id='".$_SESSION['sess_user_name']."',
                        modify_time='".date('YmdHis')."'
                        WHERE nr='$nr'";
                if($ergebnis=$enc_obj->Transact($sql))
                {
                    header("location:op-doku-start_1.php?sid=$sid&lang=$lang&target=$target&mode=saveok&pn=$pn&nr=$nr&dept_nr=$dept_nr&batch_nr=$batch_nr");
                    exit;
                }else echo "$sql<br>$LDDbNoUpdate";
            }
            else
            {
                $sql="INSERT INTO $dbtable
                (	dept_nr,
                        op_date,
                        operator,
                        surgery,
                        medical_person,
                        encounter_nr,
                        diagnosis,
                        localize,
                        therapy,
                        special,
                        class_s,
                        class_m,
                        class_l,
                        op_start,
                        op_end,
                        scrub_nurse,
                        op_room,
                        status,
                        history,
                        create_id,
                        create_time
                         )
                VALUES (
                        '$dept_nr',
                        '".formatDate2STD($op_date,$date_format)."',
                        '$operator',
                        '$surgery',
                        '$medical_person',
                        '$pn',
                        '".htmlspecialchars($diagnosis)."',
                        '".htmlspecialchars($localize)."',
                        '".htmlspecialchars($therapy)."',
                        '".htmlspecialchars($special)."',
                        '$class_s',
                        '$class_m',
                        '$class_l',
                        '$op_start',
                        '$op_end',
                        '$scrub_nurse',
                        '$op_room',
                        'pending',
                        'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
                        '".$_SESSION['sess_user_name']."',
                        '".date('YmdHis')."'
                )";
                $sql1="UPDATE care_room SET is_temp_closed=1,
                                history=".$enc_obj->ConcatHistory('Update: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'].' closed=1'."\n").",
                                modify_id='".$_SESSION['sess_user_name']."',
                                modify_time='".date('Y-m-d H:i:s')."'
                                WHERE nr='$op_room'";
                if($ergebnis=$enc_obj->Transact($sql)&& $close_room=$enc_obj->Transact($sql1)){
                    $oid=$db->Insert_ID();
                    $enc_obj->coretable=$dbtable;
                    $nr = $enc_obj->LastInsertPK('nr',$oid);
                    $oroom->coretable=$dbtable1;
                    $room=$OR_obj->LastInsertPK('nr',$oid);
                    header("location:op-doku-start_1.php?sid=$sid&lang=$lang&target=$target&mode=saveok&pn=$pn&nr=$nr&dept_nr=$dept_nr&batch_nr=$batch_nr");
                    exit;

                }else echo "$sql<br>$LDDbNoSave";
            }
            break;

            case 'saveok':
                $dbtable='care_op_med_doc';
                $sql="SELECT * FROM $dbtable WHERE  nr='$nr'";
                if($ergebnis=$db->Execute($sql))
                {
                    if($rows=$ergebnis->RecordCount())
                    {
                            $opdoc=$ergebnis->FetchRow();
                    }
                }else echo "$sql<br>$LDDbNoRead";
                break;
            case 'select': break;

            default:
                if($_COOKIE["ck_login_logged".$sid]) $mode="dummy";
    }
 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('nursing');

# Added for the common header top block

 $smarty->assign('sToolbarTitle',"$LDOrRecord :: (".$_SESSION['sess_dept_name'].")");

 # href for help button
 if(!$mode) $sBuffer ='dummy';
 	else $sBuffer = $mode;

 $smarty->assign('pbHelp',"javascript:gethelp('opdoc.php','create','$sBuffer')");

 # hide return button
 $smarty->assign('pbBack',FALSE);

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDOrRecord :: (".$_SESSION['sess_dept_name'].")");

 # Prepare Body onLoad javascript code
 if(!isset($mode) || empty($mode) || $mode=='dummy') {
	$smarty->assign('sOnLoadJs','onLoad="document.searchform.searchkey.focus();"');
 }
 /**
 * collect JavaScript for Smarty
 */
 ob_start();
?>

<script  language="javascript">
<!--
var iscat=true;
var cat=new Image();
var pix=new Image();


function hilite(idx,mode)
	{
	if(mode==1) idx.filters.alpha.opacity=100
	else idx.filters.alpha.opacity=70;
	}
function lookmatch(d)
{
	m=d.matchcode.value;
	if(m=="") return false;
	if((m.substr(0,1)=="%")||(m.substr(0,1)=="&"))
	{
		d.matchcode.value="";
		d.matchcode.focus();
		return false;
	}
	window.location.replace("op-doku-start_1.php?sid=<?php echo "$sid&lang=$lang" ?>&mode=match&matchcode="+m);
	return false;
}

<?php
if($mode!='saveok'){
?>
function chkForm(d){
	if(d.op_date.value==""){
		alert("<?php echo $LDPlsEnterDate; ?>");
		d.op_date.focus();
		return false;
	}else if(d.operator.value==""){
		alert("<?php echo $LDPlsEnterDoctor; ?>");
		d.operator.focus();
		return false;
	}else if(d.diagnosis.value==""){
		alert("<?php echo $LDPlsEnterDiagnosis; ?>");
		d.diagnosis.focus();
		return false;
	}else if(d.localize.value==""){
		alert("<?php echo $LDPlsEnterLocalization; ?>");
		d.localize.focus();
		return false;
	}else if(d.therapy.value==""){
		alert("<?php echo $LDPlsEnterTherapy; ?>");
		d.therapy.focus();
		return false;
	}else if(d.special.value==""){
		alert("<?php echo $LDPlsEnterNotes; ?>");
		d.special.focus();
		return false;
	}else if(d.class_s.value==""&&d.class_m.value==""&&d.class_l.value==""){
		alert("<?php echo $LDPlsEnterClassification; ?>");
		d.class_s.focus();
		return false;
	}else if(d.op_start.value==""){
		alert("<?php echo $LDPlsEnterStartTime; ?>");
		d.op_start.focus();
		return false;
	}else if(d.op_end.value==""){
		alert("<?php echo $LDPlsEnterEndTime; ?>");
		d.op_end.focus();
		return false;
	}else if(d.scrub_nurse.value==""){
		alert("<?php echo $LDPlsEnterScrubNurse; ?>");
		d.scrub_nurse.focus();
		return false;
	}else if(d.op_room.value==""){
		alert("<?php echo $LDPlsEnterORNr; ?>");
		d.op_room.focus();
		return false;
	}else{
		return true;
	}
}
<?php
}
?>
<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>
//-->
</script>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Buffer page output

ob_start();

?>
<?php echo setCharSet(); ?>
<table width=100% border=0 cellspacing=0 cellpadding=0>
    <?php require('./gui_tabs_op_doku_1.php'); ?>
    <tr>
        <td colspan=2><p>
            <ul>
                <?php
                if(!$rows&&!$err_data) {
                ?>
                <table border="0">
                    <tr>
                        <td><img <?php echo createComIcon($root_path,'angle_down_l.gif','0','absmiddle',TRUE) ?>></td>
                        <td class="prompt">
                            <?php
                                if($mode=='search') echo '<font color=maroon>'.$LDSorryNotFound.'</font>';
                                else echo $LDPlsSelectPatientFirst;
                            ?>
                        </td>
                        <td valign="top">
                            <img <?php echo createMascot($root_path,'mascot1_l.gif','0','absmiddle') ?>>
                        </td>
                    </tr>
                </table>

                <table border=0 cellpadding=10 bgcolor="<?php echo $entry_border_bgcolor ?>">
                    <tr>
                        <td>
                            <?php
                            include($root_path.'include/core/inc_patient_searchmask.php');
                            ?>
                        </td>
                    </tr>
                </table>
                <?php
                }
                ?>

                <?php
                if($rows || $err_data){
                    $bg_img=$root_path.'gui/img/common/default/tableHeaderbg3.gif';
                    if($err_data){
                    ?>
                    <table border="0">
                        <tr>
                            <td> <img <?php echo createMascot($root_path,'mascot2_r.gif','0','absmiddle') ?>></td>
                            <td class="prompt"><?php echo $LDPlsFillInfo ?></td>
                        </tr>
                    </table>
                    <?php
                    }
                    ?>
                    <table border=0 cellpadding=3>
                        <form method="post" action="op-doku-start_1.php" name="opdoc" <?php if($mode!='saveok') echo 'onSubmit="return chkForm(this)"'; ?>>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT color=red><?php if($err_op_date) echo '*'; ?><?php echo $LDOpDate ?>:<br>
                                </td>
                                <td>
                                    <?php
                                        //gjergji : new calendar
                                        require_once ('../../js/jscalendar/calendar.php');
                                        $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
                                        $calendar->load_files();
                                        if($mode=='saveok'){
                                            echo '<b>'.formatDate2Local($opdoc['op_date'],$date_format).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>';
                                        }else{
                                            echo $calendar->show_calendar($calendar,$date_format,'op_date');
                                        }
                                    ?>
                                    <font size=2 face="arial" color=red>&nbsp; &nbsp;<?php if($err_operator) echo 'color=#cc0000'; ?> <?php echo $LDOperator1 ?>:
                                    <?php
                                        if($mode=='saveok'){
                                            echo '<font color="#800000">'.$opdoc['operator'].'</font>';
                                        }else{
                                        echo '<input name="operator" type="text" size="25" value="';
                                        if($err_data){
                                            echo $operator;
                                        }else{
                                            echo $request_op['person_surgery'];
                                            //echo $_COOKIE[$local_user.$sid];
                                        }
                                        echo '"/>';
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><p>
                                    <FONT <?php if($err_patnum) echo 'color=#cc0000'; ?>><?php echo $LDPatientNr ?>:
                                </td>
                                <td>
                                    <table border=0 cellpadding=3>
                                        <tr>
                                            <td>
                                                <FONT color="#000099">
                                                <?php
                                                    echo '<b>'.$full_en.'</b>';
                                                ?>
                                                </FONT>
                                            </td>
                                            <td width="15"></td>
                                            <td width="115">
                                                <font size=2 face="arial" color=red>&nbsp; &nbsp;<?php if($err_surgery) echo 'color=#cc0000'; ?>
                                                <?php echo $LDSurgery ?>:
                                                </font>
                                            </td>
                                            <td>
                                                <?php
                                                    if($mode=='saveok'){
                                                        echo '<font color="#800000">'.$opdoc['surgery'].'</font>';
                                                    }else{
                                                        echo '<textarea name="surgery" rows=2 cols=25';
                                                        if($isTimeElement)  echo ' onKeyUp="setTime(this,\''.$lang.'\')">';
                                                        else echo '>';
                                                        if($err_data){
                                                            echo $surgery.'</textarea>';
                                                        }else{
                                                            echo $opdoc['surgery'].'</textarea>';
                                                            //echo $_COOKIE[$local_user.$sid];
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT <?php if($err_name) echo 'color=#cc0000'; ?>><?php echo $LDLastName ?>:
                                </td>
                                <td>
                                    <table border=0>
                                        <tr>
                                            <td>
                                                <FONT color="#000099">
                                                    <?php
                                                        echo '<b>'.$result['name_last'].'</b>';
                                                    ?>
                                                </FONT>
                                            </td>
                                            <td width="100"></td>
                                            <td width="115">
                                                <font size=2 face="arial" color=red>&nbsp; &nbsp;<?php if($err_medical_person) echo 'color=#cc0000'; ?>
                                                <?php echo $LDMedicalOP ?>:
                                                </font>
                                            </td>
                                            <td>
                                                <?php
                                                    if($mode=='saveok'){
                                                        echo '<font color="#800000">'.$opdoc['medical_person'].'</font>';
                                                    }else{
                                                        echo '<textarea name="medical_person" rows=2 cols=30';
                                                        if($isTimeElement)  echo ' onKeyUp="setTime(this,\''.$lang.'\')">';
                                                        else echo '>';
                                                        if($err_data){
                                                            echo $medical_person.'</textarea>';
                                                        }else{
                                                            echo $opdoc['medical_person'].'</textarea>';
                                                            //echo $_COOKIE[$local_user.$sid];
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT <?php if($err_vorname) echo 'color=#cc0000'; ?>><?php echo $LDName ?>:
                                </td>
                                <td>
                                    <table border=0 cellpadding=3>
                                        <tr>
                                            <td>
                                                <FONT color="#000099">
                                                <?php
                                                    echo '<b>'.$result['name_first'].'</b>';
                                                ?>
                                                </FONT>
                                            </td>
                                            <td width="22"></td>
                                            <td width="115">
                                                <font size=2 face="arial" color=red>&nbsp; &nbsp;<?php if($err_scrub_nurse) echo 'color=#cc0000'; ?>
                                                <?php echo $LDScrubNurse ?>:
                                                </font>
                                            </td>
                                            <td>
                                                <?php
                                                    if($mode=='saveok'){
                                                        echo '<font color="#800000">'.$opdoc['scrub_nurse'].'</font>';
                                                    }else{
                                                        echo '<input name="scrub_nurse" type="text" size="31" value="';
                                                        if($err_data){
                                                            echo $scrub_nurse;
                                                        }else{
                                                            echo $opdoc['scrub_nurse'];
                                                        }
                                                        echo '"/>';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT <?php if($err_gebdatum) echo 'color=#cc0000'; ?>><?php echo $LDBday ?>:
                                </td>
                                <td><FONT color="#000099">
                                <?php
                                    echo @formatDate2Local($result['date_birth'],$date_format);
                                ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td><FONT  color="#000099">
                                    <font color="#000099">
                                        <?php
                                            switch($result['status'])
                                            {
                                                case "stat": echo $LDStationary;break;
                                                case "amb": echo $LDAmbulant; break;
                                            }
                                        ?>
                                    </font>
                                <br>
                                <FONT color="#000099">
                                    <?php
                                        if ($result['kasse']=="kasse")
                                        {
                                            echo $LDInsurance;
                                        }
                                        elseif($result['kasse']=="privat")
                                        {
                                            echo $LDPrivate;
                                        }
                                        elseif($result['kasse']=="x")
                                        {
                                            echo $LDSelfPay;
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT  color=red><?php if($err_diagnosis) echo '*'; ?><?php echo $LDDiagnosis ?>:
                                </td>
                                <td>
                                    <?php
                                        echo createElement('diagnosis',$diagnosis,80,100);
                                    ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT  color=red> <?php if($err_localize) echo '*'; ?><?php echo $LDMethodSurgery ?>:
                                </td>
                                <td>
                                     <?php
                                                    if($mode=='saveok'){
                                                        echo '<font color="#800000">'.$opdoc['localize'].'</font>';
                                                    }else{
                                                        echo '<textarea name="localize" rows=4 cols=62';
                                                        if($isTimeElement)  echo ' onKeyUp="setTime(this,\''.$lang.'\')">';
                                                        else echo '>';
                                                        if($err_data){
                                                            echo $localize.'</textarea>';
                                                        }else{
                                                            echo $opdoc['localize'].'</textarea>';
                                                        }
                                                    }
                                                ?>
                                <?php
                                    //echo createElement('localize',$localize,60,100);
                                ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT  color=red><?php if($err_therapy) echo '*'; ?><?php echo $LDTherapy1 ?>:
                                </td>
                                <td>
                                    <?php
                                        echo createElement('therapy',$therapy,80,100);
                                    ?>
                                </td>
                            </tr >
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT color=red><?php if($err_special) echo '*'; ?><?php echo $LDMethodDiagnosis ?>:
                                </td>
                                <td>
                                <?php
                                    echo createElement('special',$special,80,100);
                                ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><FONT  color=red><?php if($err_klas) echo '*'; ?><?php echo $LDClassification ?>:
                                </td>
                                <td><font color="#800000">
                                    <?php if($mode=='saveok')
                                    {
                                        if($opdoc[class_s]) echo "$opdoc[class_s] $LDMinor  &nbsp; ";
                                        if($opdoc[class_m]) echo "$opdoc[class_m] $LDMiddle &nbsp; ";
                                        if($opdoc[class_l]) echo "$opdoc[class_l] $LDMajor";
                                        echo " $LDOperation";
                                    }
                                    else
                                    {
                                    ?>
                                    <select name="class_s">
                                    <option value="0"> </option>
                                    <?php
                                        for($i=1;$i<9;$i++){
                                            echo "<option value=\"$i\"";
                                            if($err_data) $buf= $class_s; else $buf = $opdoc['class_s'];
                                            if($i == $buf) echo 'selected';
                                            echo ">$i</option>";
                                        }
                                    ?>
                                    </select>
                                    <?php echo $LDMinor ?>&nbsp;
                                    <select name="class_m">
                                        <option value="0"> </option>
                                        <?php
                                            for($i=1;$i<9;$i++){
                                                echo "<option value=\"$i\"";
                                                if($err_data) $buf= class_m; else $buf = $opdoc['class_m'];
                                                if($i == $buf) echo 'selected';
                                                echo ">$i</option>";
                                            }
                                        ?>
                                    ?>
                                    </select>
                                    <?php echo $LDMiddle ?>&nbsp;
                                    <select name="class_l">
                                        <option value="0"></option>
                                        <?php
                                            for($i=1;$i<9;$i++){
                                                echo "<option value=\"$i\"";
                                                if($err_data) $buf= class_l; else $buf = $opdoc['class_l'];
                                                if($i == $buf) echo 'selected';
                                                echo ">$i</option>";
                                            }
                                        ?>
                                    </select>
                                    <?php echo "$LDMajor $LDOperation" ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <?php
                    if($rows || $err_data)
                    {
                    ?>

                    <p>
                    <FONT color=red><?php if($err_op_start) echo '*'; ?>
                        <?php
                            /* Set the global $isTimeElement to 1 to cause the function to insert the setTime Code in the form input code */
                            $isTimeElement=1;
                            echo $LDOpStart1.':';
                            echo createElement('op_start',$op_start);
                            echo '<font color="red">';if($err_op_end) echo '<font color="*">'; ?> &nbsp; <?php echo $LDOpEnd1.':';
                            echo createElement('op_end',$op_end);
                            /* Reset the global $isTimeElement to 1 to disable the setTime code insertion*/
                            $isTimeElement=0;
                            echo '<font color="red">';if($err_op_room) echo '<font color="*">'; ?>  &nbsp; <?php echo $LDOpRoom.':';
                       ?>
                       <font color="#800000">
                           <?php if($mode=='saveok')
                                    {
                                        if($opdoc[op_room]) echo $opdoc[op_room];
                                    }
                                    else
                                    {
                                    ?>
                                    <select name="op_room">
                                    <?php
                                        $rows=$OR_obj->LastRecordCount();
                                        while($ORoom=$select_room->FetchRow()){
                                            echo "<option value=\"$ORoom[nr]\"";
                                            if($err_data){
                                                $buf= $ORoom['nr'];
                                            }
                                            else
                                                $buf= $opdoc[op_room];
                                            if($ORoom[nr] == $buf)echo 'selected';
                                            echo ">$ORoom[nr]</option>";
                                        }
                                    ?>
                                    </select><?php } ?>
                       </font>
                    <p>
                    <?php if($mode=='saveok') : ?>
                    <!--<input  type="image" <?php echo createLDImgSrc($root_path,'update_data.gif','0','absmiddle') ?>  alt="<?php echo $LDSave ?>">-->
                    <input type="button" value="<?php echo $LDStartNewDocu ?>" onclick="window.location.replace('op-doku-start_1.php<?php echo URL_REDIRECT_APPEND."&target=$target&dept_nr=$dept_nr"; ?>&mode=dummy')">
                    <?php else : ?>
                    <input  type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  alt="<?php echo $LDSave ?>">
                    <a href="javascript:document.opdoc.reset()"><img <?php echo createLDImgSrc($root_path,'reset.gif','0') ?> alt="<?php echo $LDResetAll ?>" ></a>
                    <a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>  alt="<?php echo $LDClose ?>"></a>
                    
                    <?php endif ?>
                    <input type="hidden" name="mode" value="<?php if($mode=='saveok') echo 'update'; else echo 'save' ?>">
                    <input type="hidden" name="dept_nr" value="<?php echo $dept_nr ?>">
                    <input type="hidden" name="sid" value="<?php echo $sid ?>">
                    <input type="hidden" name="lang" value="<?php echo $lang ?>">
                    <input type="hidden" name="update" value="<?php if ($mode=='update') echo '1' ?>">
                    <input type="hidden" name="pn" value="<?php if($mode=='match' && $rows==1) echo $result['encounter_nr']; else echo $pn ?>">
                    <input type="hidden" name="nr" value="<?php echo $nr ?>">
                    <input type="hidden" name="target" value="<?php echo $target ?>">
                    </form>
                <?php } ?>
                <p>
            </ul>
        </td>
    </tr>
</table>
<?php

$sTemp = ob_get_contents();
 ob_end_clean();
# Assign the page output to main frame template

 $smarty->assign('sMainFrameBlockData',$sTemp);
 $smarty->display('common/mainframe.tpl');

?>
