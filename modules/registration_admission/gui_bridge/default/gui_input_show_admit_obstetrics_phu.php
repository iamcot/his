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
    # Pregnancy nr
    $TP_DATE=$LDDate_kham;
    $TP_TOANTHAN=$LDToanthan;
    
    $TP_DA=$LD['da_notes'];
    if($pregnancy['da_notes']){
        $TP_DA_INPUT=$pregnancy['da_notes'];
    }
    $TP_HACH=$LD['hach_notes'];
    if($pregnancy['hach_notes']){
        $TP_HACH_INPUT=$pregnancy['hach_notes'];
    }    
    $TP_VU=$LD['vu_notes'];
    if($pregnancy['vu_notes']){
        $TP_VU_INPUT=$pregnancy['vu_notes'];
    }
    
    $TP_COQUAN=$LDCaccoquan;
    
    $TP_TUANHOAN=$LD['tuanhoan_notes'];
    if($pregnancy['tuanhoan_notes']){
        $TP_TUANHOAN_INPUT=$pregnancy['tuanhoan_notes'];
    }
    $TP_HOHAP=$LD['hohap_notes'];
    if($pregnancy['hohap_notes']){
        $TP_HOHAP_INPUT=$pregnancy['hohap_notes'];
    }
    $TP_TIEUHOA=$LD['tieuhoa_notes'];
    if($pregnancy['tieuhoa_notes']){
        $TP_TIEUHOA_INPUT=$pregnancy['tieuhoa_notes'];
    }
    $TP_TIETNIEU=$LDThantietnieusinhduc;
    if($pregnancy['thantietnieusinhduc_notes']){
        $TP_TIETNIEU_INPUT=$pregnancy['thantietnieusinhduc_notes'];
    }
    $TP_THANKINH=$LD['thankinh_notes'];
    if($pregnancy['thankinh_notes']){
        $TP_THANKINH_INPUT=$pregnancy['thankinh_notes'];
    }
    $TP_COXUONG=$LD['coxuongkhop_notes'];
    if($pregnancy['coxuongkhop_notes']){
        $TP_COXUONG_INPUT=$pregnancy['coxuongkhop_notes'];
    }
    $TP_KHAC=$LD['khac_notes'];
    if($pregnancy['khac_notes']){
        $TP_KHAC_INPUT=$pregnancy['khac_notes'];
    }
    $TP_DATE=$LD['date_khamngoai'];    
    $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date',$pregnancy['date']);
    
    $TP_DOCBY=$LD['docu_by'];    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_admit_obstetrics_phu.htm');
    eval("echo $tp_preg;");
?>
<input type="hidden" name="sid" value="<?php echo $sid; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>" />
<input type="hidden" name="allow_update" value="<?php if(isset($allow_update)) echo $allow_update; ?>" />
<input type="hidden" name="target" value="<?php echo trim($target); ?>" />
<input type="hidden" name="mode" value="newdata" />
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?> />
<input type="hidden" name="flag" value="2" />
</form>


