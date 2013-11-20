<?php
    if($date && $time){
        $cond="AND date='$date' AND time='$time'";
    }
    $thoigian=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=33");
    if($thoigian){
        $thoigian_ravien=$thoigian->FetchRow();
    }
    $chandoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=34");
    if($chandoan){
        $chandoan_ravien=$chandoan->FetchRow();
    }
    $loidan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=35");
    if($loidan){
        $loidan_ravien=$loidan->FetchRow();
    }
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    require($root_path.'classes/datetimemanager/checktime.php');
?>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_DATE=$LDNgayRavien;
    if(substr($thoigian_ravien['notes'],0,10)){
        $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date_ravien',substr($thoigian_ravien['notes'],0,10));
    }else{
        $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date_ravien',date('Y-m-d'));
    }    
    $TP_TIME=$LDGioRavien;
    if(substr($thoigian_ravien['notes'],11,8)) $TP_PTIME=substr($thoigian_ravien['notes'],11,8);
    else $TP_PTIME=date('H:i'); 
    
    $TP_CHANDOAN=$LDDiagnosis;
    if($chandoan_ravien['notes'])
        $TP_CHANDOAN_INPUT=$chandoan_ravien['notes'];
    $TP_LOIDAN=$LDLoidan;
    if($loidan_ravien['notes'])
        $TP_LOIDAN_INPUT=$loidan_ravien['notes'];
    
    $TP_DOCBY=$LDDocBy;    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_ravien.htm');
    eval("echo $tp_preg;");
?>
<input type="hidden" name="sid" value="<?php echo $sid; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>" />
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>" />
<input type="hidden" name="allow_update" value="<?php if(isset($allow_update)) echo $allow_update; ?>" />
<input type="hidden" name="target" value="<?php echo trim($target); ?>" />
<input type="hidden" name="mode" value="newdata" />
<input type="hidden" name="nr" value="<?php echo $nr;?>" />
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?> />
</form>
<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
<script language="JavaScript">
    function chkform() {
        var d = document.getElementById('report'); 
        if(d.date_ravien==""){
            alert("<?php echo $LDAlert_date; ?>");
            d.date_ravien.focus();
            return false;
        }else if(d.time==""){
            alert("<?php echo $LDAlert_time; ?>");
            d.time.focus();
            return false;
        }else if(d.chandoan.value==""){
            alert("<?php echo $LDAlert_chandoan; ?>");
            d.chandoan.focus();
            return false;            
        }else if(d.loidan.value==""){
            alert("<?php echo $LDAlert_loidan; ?>");
            d.loidan.focus();
            return false;            
        }else{
            return true;
        }
    }
    $(function(){
        $("#time").mask("**:**");
        $("#f-calendar-field-1").mask("**/**/****");
    });
</script>

