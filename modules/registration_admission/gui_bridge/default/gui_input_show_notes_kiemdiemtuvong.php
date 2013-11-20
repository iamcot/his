<?php
    if($pregs && $allow_update) $pregnancy=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_SOVAOVIEN=$LD['sovaovien_notes'];
    if($pregnancy['sovaovien_notes'])
        $TP_SOVAOVIEN_INPUT=$pregnancy['sovaovien_notes'];
    $TP_NGAYNHAPVIEN=$LDVaovien; 
    $status=$obj->loadEncounterData1($_SESSION['sess_en'],1);
    if($status){
        if($status['encounter_date']){
            $ngaynhapvien=substr($status['encounter_date'],0,10);
            $timenhapvien=substr($status['encounter_date'],11,10);
            $TP_NGAYNHAPVIEN_INPUT=@formatDate2STD($ngaynhapvien,$date_format);
        }
    }    
    $TP_DATE=$LD['date_hoichan'];
    if($status['current_room_nr'])
        $TP_BUONG_INPUT=$status['current_room_nr'];
    $TP_KHOA=$LDDept;
    $sql1="SELECT d.LD_var
            FROM care_encounter AS e,
                 care_person AS p,
                 care_department AS d
            WHERE p.pid=".$_SESSION['sess_pid']."
                AND	p.pid=e.pid
                AND e.encounter_nr=".$_SESSION['sess_en']."
                AND e.current_dept_nr=d.nr";
    if($result1=$db->Execute($sql1)){
        $rows2=$result1->FetchRow();
    }
    $current_dept_nr=$rows2['LD_var'];
    require_once($root_path.'language/vi/lang_vi_departments.php');
    if($current_dept_nr!=null)
        $TP_KHOA_INPUT=$$current_dept_nr;
    
    $TP_TIME_TUVONG=$LD['time_tuvong'];
    if($pregnancy['time_tuvong'])
         $TP_TIME_TUVONG_INPUT=$pregnancy['time_tuvong'];
    else
        $TP_TIME_TUVONG_INPUT=date('H:i');
    
    $TP_DATE_TUVONG_INPUT = $calendar->show_calendar($calendar,$date_format,'date_tuvong',$pregnancy['date_tuvong']);
    
    $TP_TIME_KIEMTUVONG=$LD['time_kiemtuvong'];
    if($pregnancy['time_kiemtuvong'])
         $TP_TIME_KIEMTUVONG_INPUT=$pregnancy['time_kiemtuvong'];
    else
        $TP_TIME_KIEMTUVONG_INPUT=date('H:i');
    
    $TP_DATE_KIEMTUVONG_INPUT = $calendar->show_calendar($calendar,$date_format,'date_kiemtuvong',$pregnancy['date_kiemtuvong']);
            
    $TP_CHUTOA=$LD['chutoa_notes'];
    if($pregnancy['chutoa_notes'])
        $TP_CHUTOA_INPUT=$pregnancy['chutoa_notes'];
    
    $TP_THUKI=$LD['thuki_notes'];
    if($pregnancy['thuki_notes'])
        $TP_THUKI_INPUT=$pregnancy['thuki_notes'];
    
    $TP_THANHVIEN=$LD['thanhvien_notes'];
    if($pregnancy['thanhvien_notes'])
        $TP_THANHVIEN_INPUT=$pregnancy['thanhvien_notes'];
    $TP_IMG_ADD=createLDImgSrc($root_path,'add_sm.gif','0');
    $TP_IMG_CLEAR=createLDImgSrc($root_path,'clearall_sm.gif','0');
    
    $TP_TOMTAT=$LD['tomtat_notes'];
    if($pregnancy['tomtat_notes'])
        $TP_TOMTAT_INPUT=$pregnancy['tomtat_notes'];
    $TP_KETLUAN=$LDShortNotes1;
    if($pregnancy['ketluan_notes'])
        $TP_KETLUAN_INPUT=$pregnancy['ketluan_notes'];
    
    $TP_DOCBY=$LDDocBy;    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_kiemdiemtuvong.htm');
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
<?php 
    require($root_path.'classes/datetimemanager/checktime.php'); 
?>
<script language="JavaScript">
    function chkform() {
        var d = document.getElementById('report'); 
        var birth=new Date('<?php echo $ngaynhapvien;?>');
        var temp=d.date_tuvong.value.split("/");
        var date_tuvong=new Date(temp[2]+'-'+temp[1]+'-'+temp[0]);
        var temp1=d.date_kiemtuvong.value.split("/");
        var date_kiemtuvong=new Date(temp1[2]+'-'+temp1[1]+'-'+temp1[0]);
        if(d.date_tuvong.value==""){
            alert("<?php echo $Warningdate_tuvong; ?>");
            d.date_tuvong.focus();
            return false;
        }else if(d.date_kiemtuvong.value==""){
            alert("<?php echo $Warningdate_kiemtuvong; ?>");
            d.date_kiemtuvong.focus();
            return false;            
        }else if(d.chutoa_notes.value==""){
            alert("<?php echo $Warningchutoa_notes; ?>");
            d.chutoa_notes.focus();
            return false;            
        }else if(d.thanhvien_notes.value==""){
            alert("<?php echo $Warningthanhvien_notes; ?>");
            d.thanhvien_notes.focus();
            return false;            
        }else if(d.ketluan_notes.value==""){
            alert("<?php echo $Warningketluan_notes; ?>");
            d.ketluan_notes.focus();
            return false;            
        }else if((date_tuvong.getFullYear() > <?php echo date("Y");?>) || (date_tuvong.getMonth() > <?php echo date("m");?> && date_tuvong.getFullYear() == <?php echo date("Y");?>) || (date_tuvong.getDate() > <?php echo date("d");?> && date_tuvong.getMonth() == <?php echo date("m");?> && date_tuvong.getFullYear() == <?php echo date("Y");?>)){
            alert("<?php echo $Warningdate_hoichan4; ?>");
            d.date_tuvong.focus();
            return false;
        }else if((date_kiemtuvong.getFullYear() > <?php echo date("Y");?>) || (date_kiemtuvong.getMonth() > <?php echo date("m");?> && date_kiemtuvong.getFullYear() == <?php echo date("Y");?>) || (date_kiemtuvong.getDate() > <?php echo date("d");?> && date_kiemtuvong.getMonth() == <?php echo date("m");?> && date_kiemtuvong.getFullYear() == <?php echo date("Y");?>)){
            alert("<?php echo $Warningdate_hoichan6; ?>");
            d.date_tuvong.focus();
            return false;
        }else if((date_tuvong.getFullYear() < birth.getFullYear()) || (date_tuvong.getMonth() < birth.getMonth() && date_tuvong.getFullYear() == birth.getFullYear()) || (date_tuvong.getDate() < birth.getDate() && date_tuvong.getMonth() == birth.getMonth() && date_tuvong.getFullYear() == birth.getFullYear())){
            alert("<?php echo $Warningdate_hoichan5; ?>");
            d.date_tuvong.focus();
            return false;
        }else if((date_kiemtuvong.getFullYear() < birth.getFullYear()) || (date_kiemtuvong.getMonth() < birth.getMonth() && date_kiemtuvong.getFullYear() == birth.getFullYear()) || (date_kiemtuvong.getDate() < birth.getDate() && date_kiemtuvong.getMonth() == birth.getMonth() && date_kiemtuvong.getFullYear() == birth.getFullYear())){
            alert("<?php echo $Warningdate_hoichan7; ?>");
            d.date_kiemtuvong.focus();
            return false;
        }else{
            return true;
        }
        return true;
    }   
    
    function popClassification(name) {
	urlholder="./hoichan_classifications.php<?php echo URL_REDIRECT_APPEND.'&text='; ?>"+name+"&"+name+"="+document.getElementById(name).value;
	CLASSWIN<?php echo $sid ?>=window.open(urlholder,"CLASSWIN<?php echo $sid ?>","menubar=no,width=1000,height=550,resizable=yes,scrollbars=yes");
    }
    
    function clearClassification(name) {
	document.getElementById(name).value="";
	document.getElementById(name).focus();
    }
    
    $(function(){
        $("#time_tuvong").mask("**:**");
        $("#time_kiemtuvong").mask("**:**");
        $("#f-calendar-field-1").mask("**/**/****");
        $("#f-calendar-field-2").mask("**/**/****");
    });
</script>

