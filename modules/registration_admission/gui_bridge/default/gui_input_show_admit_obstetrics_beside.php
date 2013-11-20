<?php
    if($pregs) $pregnancy=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<script language="JavaScript">
    function chkform() {
        var d = document.getElementById('report'); 
        if(d.date_khamngoai.value==""){
		alert("<?php echo $LDPlsEnterDeliveryDate_kham; ?>");
		d.date_khamngoai.focus();
		return false;
        }
        return true;
    }
    
    function setValue(name,value){
        if(value==''){
            if(document.getElementById(name).value==1){
                document.getElementById(name).value=0;
            }else{
                document.getElementById(name).value=1;
            }
        }else{
            if(document.getElementById(name).value==1){               
                document.getElementById(name).value=0;
            }else{
                document.getElementById(name).value=1;
            }
        }      
        return true;
    }
    
    function CheckValue(name,value){
        if(isNaN(document.getElementById(name).value)){
            switch(name){
                case "timthai":
                    alert("<?php echo $LD['timthai'].' '.$LDNopass;?>");
                    break;
                case "chieucaotc":
                    alert("<?php echo $LD['chieucaotc'].' '.$LDNopass;?>");
                    break;
                default:
                    alert("<?php echo $LD['vongbung'].' '.$LDNopass;?>");
                    break;
            }
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
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
    $TP_SEO=$LD['seopt'];
    $TP_SEO_INPUT='<input type="checkbox" name="seopt" id="seopt" value="';
    if($pregnancy['seopt']) 
        $TP_SEO_INPUT.=$pregnancy['seopt'].'" checked';
    else
        $TP_SEO_INPUT.='"';
    $TP_SEO_INPUT.=' onchange=setValue("seopt","'.$pregnancy['seopt'].'") />';
    
    $TP_TIMTHAI=$LD['timthai'];
    $TP_dv=$LD['dvtimthai'];
    if($pregnancy['timthai']){
        $TP_TIMTHAI_INPUT=$pregnancy['timthai'];
    }
        
    $TP_HDTC=$LD['hdtc'];
    if($pregnancy['hdtc']){
        $TP_HDTC_INPUT=$pregnancy['hdtc'];
    }
    $TP_TUTHE=$LD['tuthe'];
    if($pregnancy['tuthe']){
        $TP_TUTHE_INPUT=$pregnancy['tuthe'];
    }
    $TP_CHIEUCAOTC=$LD['chieucaotc'];
    if($pregnancy['chieucaotc']){
        $TP_CHIEUCAOTC_INPUT=$pregnancy['chieucaotc'];
    }
    $TP_VONGBUNG=$LD['vongbung'];
    if($pregnancy['vongbung']){
        $TP_VONGBUNG_INPUT=$pregnancy['vongbung'];
    }
    $TP_Quidinh=$LDQuidinh;
    $TP_CCTC=$LD['cctc'];
    $TP_CCTC_INPUT='<input type="text" name="cctc" id="cctc" size="80" maxlength="250" value=';
    if($pregnancy['cctc']){
        $TP_CCTC_INPUT.=$pregnancy['cctc'];
    }else{
        $TP_CCTC_INPUT.='';
    }
    $TP_CCTC_INPUT.=' >';
    $TP_VU=$LD['vu'];
    if($pregnancy['vu']){
        $TP_VU_INPUT=$pregnancy['vu'];
    }
    
    $TP_DATE=$LDDate;    
    $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date_khamngoai',$pregnancy['date_khamngoai']);
    
    $TP_DOCBY=$LD['docu_by'];    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_admit_obstetrics_beside.htm');
    eval("echo $tp_preg;");
?>
<input type="hidden" name="sid" value="<?php echo $sid; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>" />
<input type="hidden" name="allow_update" value="<?php if(isset($allow_update)) echo $allow_update; ?>" />
<input type="hidden" name="target" value="<?php echo trim($target); ?>" />
<input type="hidden" name="mode" value="newdata" />
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?> />
<input type="hidden" name="flag" value="1" />
</form>


