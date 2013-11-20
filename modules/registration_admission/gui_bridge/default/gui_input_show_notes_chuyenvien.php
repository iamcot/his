<?php
    if($obj && $allow_update) 
        $pregnancy=$obj->FetchRow();
?>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_TOMTAT=$LDTomtat;
    $TP_LAMSANG=$LD['lamsang_notes'];
    if($pregnancy['lamsang_notes'])
        $TP_LAMSANG_INPUT=$pregnancy['lamsang_notes'];
    
    $TP_XETNGHIEM=$LDXetnghiem;
    $TP_XETNGHIEM_INPUT='';
    if($parent_admit){
        $sql="SELECT dr.*, e.encounter_class_nr FROM care_encounter AS e, care_person AS p, care_encounter_diagnostics_report AS dr 
                    WHERE p.pid=".$_SESSION['sess_pid']." 
                            AND p.pid=e.pid 
                            AND e.encounter_nr=".$_SESSION['sess_en']." 
                            AND e.encounter_nr=dr.encounter_nr 
                    ORDER BY dr.create_time DESC";
    }else{
        $sql="SELECT dr.*, e.encounter_class_nr FROM care_encounter AS e, care_person AS p, care_encounter_diagnostics_report AS dr 
                    WHERE p.pid=".$_SESSION['sess_pid']." AND p.pid=e.pid AND e.encounter_nr=dr.encounter_nr 
                    ORDER BY dr.create_time DESC";
    }
    if($result=$db->Execute($sql)){
	$rows=$result->RecordCount();
	include_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj=new Department();
	$depts_array=&$dept_obj->getAll();
    }   
    $i=0;
    while(list($x,$v)=each($depts_array) && $row=$result->FetchRow()){
        $deptnr_ok=false;
        if($v['nr']==$row['reporting_dept_nr']){
            $deptnr_ok=true;
        }
        reset($depts_array);
        if($deptnr_ok){
            if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) 
                $TP_XETNGHIEM_INPUT.=$$v['LD_var'].';  ';
            else 
                $TP_XETNGHIEM_INPUT.=$v['name_formal'].';  ';
        }else{
            $TP_XETNGHIEM_INPUT.=$row['reporting_dept'].';  ';
        }
        $i++;
    }    
    
    $TP_CHANDOAN=$LD['chandoan_notes'];
    if($pregnancy['chandoan_notes'])
        $TP_CHANDOAN_INPUT=$pregnancy['chandoan_notes'];
    
    $TP_THUOC=$LD['thuoc_notes'];
    if($pregnancy['thuoc_notes'])
        $TP_THUOC_INPUT=$pregnancy['thuoc_notes'];
    
    $TP_TINHTRANG=$LD['tinhtrang_notes'];
    if($pregnancy['tinhtrang_notes'])
        $TP_TINHTRANG_INPUT=$pregnancy['tinhtrang_notes'];
    
    $TP_LYDO=$LDLydo;
    if($pregnancy['lydo_notes'])
        $TP_LYDO_INPUT=$pregnancy['lydo_notes'];
    
    $TP_NGAYCHUYEN=$LDDate; 
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date_chuyen',$pregnancy['date_chuyen']);
    
    $TP_TIME_CHUYEN=$LDChuyen;
    if($pregnancy['time_hoichan'])
         $TP_TIME_HOICHAN_INPUT=$pregnancy['time_hoichan'];
    else
        $TP_TIME_HOICHAN_INPUT='00:00:00';
    $TP_DATE_HOICHAN=$LD['date_hoichan'];
    $TP_DATE_HOICHAN_INPUT = $calendar->show_calendar($calendar,$date_format,'date_hoichan',$pregnancy['date_hoichan']);
    
    
       
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
    $TP_KETLUAN=$LD['ketluan_notes'];
    if($pregnancy['ketluan_notes'])
        $TP_KETLUAN_INPUT=$pregnancy['ketluan_notes'];
    $TP_HUONGDIEUTRI=$LD['huongdieutri_notes'];
    if($pregnancy['huongdieutri_notes'])
        $TP_HUONGDIEUTRI_INPUT=$pregnancy['huongdieutri_notes'];
    
    $TP_DOCBY=$LDDocBy;    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_chuyenvien.htm');
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
<script language="JavaScript">
    function chkform() {
        var d = document.getElementById('report'); 
        var birth=new Date('<?php echo $ngaynhapvien;?>');
        var temp=d.date_end.value.split("/");
        var date_end=new Date(temp[2]+'-'+temp[1]+'-'+temp[0]);
        var temp1=d.date_hoichan.value.split("/");
        if(d.date_end.value==""){
            alert("<?php echo $Warningdate_end; ?>");
            d.date_end.focus();
            return false;
        }else if(d.date_hoichan.value==""){
            alert("<?php echo $Warningdate_hoichan; ?>");
            d.date_hoichan.focus();
            return false;            
        }else if(d.hoichan_notes.value==""){
            alert("<?php echo $Warninghoichan_notes; ?>");
            d.hoichan_notes.focus();
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
        }else if((date_hoichan.getFullYear() > date_end.getFullYear()) || (date_hoichan.getMonth() > date_end.getMonth() && date_hoichan.getFullYear() == date_end.getFullYear()) || (date_hoichan.getDate() > date_end.getDate() && date_hoichan.getMonth() == date_end.getMonth() && date_hoichan.getFullYear() == date_end.getFullYear())){
            alert("<?php echo $Warningdate_hoichan1; ?>");
            d.date_hoichan.focus();
            return false;
        }else if((date_end.getFullYear() < birth.getFullYear()) || (date_end.getMonth() < birth.getMonth() && date_end.getFullYear() == birth.getFullYear()) || (date_end.getDate() < birth.getDate() && date_end.getMonth() == birth.getMonth() && date_end.getFullYear() == birth.getFullYear())){
            alert("<?php echo $Warningdate_hoichan2; ?>");
            d.date_end.focus();
            return false;
        }else if((date_hoichan.getFullYear() < birth.getFullYear()) || (date_hoichan.getMonth() < birth.getMonth() && date_hoichan.getFullYear() == birth.getFullYear()) || (date_hoichan.getDate() < birth.getDate() && date_hoichan.getMonth() == birth.getMonth() && date_hoichan.getFullYear() == birth.getFullYear())){
            alert("<?php echo $Warningdate_hoichan3; ?>");
            d.date_hoichan.focus();
            return false;
        }else{
            var time=d.time_hoichan.value;
            time=time.split(":");
            if(time[0].length<=2 && time[1].length<=2 && time[2].length<=2){
                if(parseInt(time[0])>23 || isNaN(time[0])){
                    alert("<?php echo $LDWarningHour; ?>");
                    d.delivery_time.focus();                    
                    return false;
                }
                if(parseInt(time[1])>59 || isNaN(time[1])){
                    alert("<?php echo $LDWarningMinute; ?>");
                    d.delivery_time.focus();                    
                    return false;
                }
                if(parseInt(time[2])>59 || isNaN(time[2])){
                    alert("<?php echo $LDWarningSecond; ?>");
                    d.delivery_time.focus();                    
                    return false;
                }
            }else{  
                alert("<?php echo $LDWarningTime; ?>");
                d.gio_chuyenda.focus();
                return false;
            }
        }
        return true;
    }
    
    function setValueTime(){
        var d = document.getElementById('report');
        if(d.time_hoichan.value=="00:00:00" || d.time_hoichan.value==0 || d.time_hoichan.value=="00:00"){
            d.time_hoichan.value="";
        }
        return true;
    }
    
    function checkValue(){
        var d = document.getElementById('report');
        if(d.time_hoichan.value=="" || d.time_hoichan.value==0 || d.time_hoichan.value=="00:00"){
            d.time_hoichan.value="00:00:00";
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
    
</script>

