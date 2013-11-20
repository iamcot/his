<?php
    if($pregs) $pregnancy=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<script language="JavaScript">
    function chkform() {
        var d = document.getElementById('report'); 
        if(d.date.value==""){
		alert("<?php echo $LDPlsEnterDeliveryDate_kham; ?>");
		d.date.focus();
		return false;
        }
        return true;
    }
    $(function(){
        $("#f-calendar-field-1").mask("**/**/****");
    });
</script>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    //Kham ngoai
    $TP_NGOAI=$LDBeside;
    $TP_DAUHIEU=$LD['dauhieu_notes'];
    if($pregnancy['dauhieu_notes']){
        $TP_DAUHIEU_INPUT=$pregnancy['dauhieu_notes'];
    }
    $TP_MOILON=$LD['moilon_notes'];
    if($pregnancy['moilon_notes']){
        $TP_MOILON_INPUT=$pregnancy['moilon_notes'];
    }
    $TP_MOIBE=$LD['moibe_notes'];
    if($pregnancy['moibe_notes']){
        $TP_MOIBE_INPUT=$pregnancy['moibe_notes'];
    }
    $TP_AMVAT=$LD['amvat_notes'];
    if($pregnancy['amvat_notes']){
        $TP_AMVAT_INPUT=$pregnancy['amvat_notes'];
    }
    $TP_AMHO=$LD['amho'];
    if($pregnancy['amho']){
        $TP_AMHO_INPUT=$pregnancy['amho'];
    }
    $TP_MANGTRINH=$LD['mangtrinh_notes'];
    if($pregnancy['mangtrinh_notes']){
        $TP_MANGTRINH_INPUT=$pregnancy['mangtrinh_notes'];
    }
    $TP_TANGSINHMON=$LD['tangsinhmon'];
    if($pregnancy['tangsinhmon']){
        $TP_TANGSINHMON_INPUT=$pregnancy['tangsinhmon'];
    }
    //Kham trong
    $TP_TRONG=$LDIn;
    $TP_AMDAO=$LD['amdao'];
    if($pregnancy['amdao']){
        $TP_AMDAO_INPUT=$pregnancy['amdao'];
    }
    $TP_TUCUNG=$LD['TC'];
    if($pregnancy['TC']){
        $TP_TUCUNG_INPUT=$pregnancy['TC'];
    }
    $TP_THANTC=$LD['thanhtc_notes'];
    if($pregnancy['thanhtc_notes']){
        $TP_THANTC_INPUT=$pregnancy['thanhtc_notes'];
    }
    $TP_PHANPHU=$LD['phanphu'];
    if($pregnancy['phanphu']){
        $TP_PHANPHU_INPUT=$pregnancy['phanphu'];
    }
    $TP_TUICUNG=$LD['tuicung_notes'];
    if($pregnancy['tuicung_notes']){
        $TP_TUICUNG_INPUT=$pregnancy['tuicung_notes'];
    }
        
    $TP_DATE=$LDDate_kham;    
    $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date',$pregnancy['date']);
    
    $TP_DOCBY=$LD['docu_by'];    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_admit_obstetrics_chuyenkhoa.htm');
    eval("echo $tp_preg;");
?>
<input type="hidden" name="sid" value="<?php echo $sid; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>" />
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>" />
<input type="hidden" name="allow_update" value="<?php if(isset($allow_update)) echo $allow_update; ?>" />
<input type="hidden" name="target" value="<?php echo trim($target); ?>" />
<input type="hidden" name="mode" value="newdata" />
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?> />
<input type="hidden" name="flag" value="1" />
</form>


