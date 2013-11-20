<?php
    if($date && $time){
        $cond="AND date='$date' AND time='$time'";
    }
    $nguoinha=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=29");
    if($nguoinha){
        $name_nguoinha=$nguoinha->FetchRow();
    }
    $tuoi=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=30");
    if($tuoi){
        $tuoi_nguoinha=$tuoi->FetchRow();
    }
    $quanhe=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=31");
    if($quanhe){
        $moiquanhe=$quanhe->FetchRow();
    }
    $camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=32");
    if($camdoan){
        $ct_camdoan=$camdoan->FetchRow();
    }
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_NGUOICAMDOAN=$LDNguoicamdoan;
    if($name_nguoinha['notes'])
        $TP_NGUOICAMDOAN_INPUT=$name_nguoinha['notes'];
    $TP_TUOI=$LDTuoi;
    if($tuoi_nguoinha['notes'])
        $TP_TUOI_INPUT=$tuoi_nguoinha['notes'];
    $TP_QUANHE=$LDQuanhevoinguoibenh;
    if($moiquanhe['notes'])
        $TP_QUANHE_INPUT=$moiquanhe['notes'];
    $TP_CAMDOAN=$LDCamdoan;
    if($ct_camdoan['notes']){
        $TP_CAMDOAN_INPUT=$ct_camdoan['notes'];
    }   
    
    $TP_DOCBY=$LDDocBy;    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_camdoan.htm');
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
        if(isNaN(d.tuoinguoicamdoan.value) || d.tuoinguoicamdoan.value<18){
            alert("<?php echo $LDAlert_tuoi; ?>");
            d.tuoinguoicamdoan.focus();
            return false;
        }else if(d.text_camdoan.value==""){
            alert("<?php echo $Alert_camdoan; ?>");
            d.text_camdoan.focus();
            return false;            
        }else{
            return true;
        }
    }
</script>

