<?php
    if($pregs && $allow_update) $pregnancy=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_HOICHAN=$LD['hoichan_notes'];
    if($pregnancy['hoichan_notes'])
        $TP_HOICHAN_INPUT=$pregnancy['hoichan_notes'];
    $TP_NGAYNHAPVIEN=$LDNgaynhapvien; 
    $status=$obj->loadEncounterData1($_SESSION['sess_en'],1);
    if($status){
        if($status['encounter_date']){
            $ngaynhapvien=substr($status['encounter_date'],0,10);
            $TP_NGAYNHAPVIEN_INPUT=@formatDate2STD($ngaynhapvien,$date_format);
        }
    }    
    $TP_DATE=$LD['date_end'];    
    $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date_end',$pregnancy['date_end']);
    
    $TP_GIUONG=$LDgiuong;
    $bed=$obj->getBed($_SESSION['sess_en'],$status['current_ward_nr'],$ngaynhapvien);
    if($bed){
        $bed_now=$bed->FetchRow();
        $TP_GIUONG_INPUT=$bed_now['location_nr'];
    }
    $TP_BUONG=$LDBuong;
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
    $TP_TIME_HOICHAN=$LD['time_hoichan'];
    if($pregnancy['time_hoichan'])
         $TP_TIME_HOICHAN_INPUT=$pregnancy['time_hoichan'];
    else
        $TP_TIME_HOICHAN_INPUT=date('H:i');
    $TP_DATE_HOICHAN=$LD['date_hoichan'];
    $TP_DATE_HOICHAN_INPUT = $calendar->show_calendar($calendar,$date_format,'date_hoichan',$pregnancy['date_hoichan']);
    
    $TP_CHANDOAN=$LD['chandoan_notes'];
    if($pregnancy['chandoan_notes'])
        $TP_CHANDOAN_INPUT=$pregnancy['chandoan_notes'];
        
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
    $TP_KETLUAN=$LD['ketluan_notes'];
    if($pregnancy['ketluan_notes'])
        $TP_KETLUAN_INPUT=$pregnancy['ketluan_notes'];
    $TP_HUONGDIEUTRI=$LD['huongdieutri_notes'];
    if($pregnancy['huongdieutri_notes'])
        $TP_HUONGDIEUTRI_INPUT=$pregnancy['huongdieutri_notes'];
    
    $TP_DOCBY=$LDDocBy;    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_hoichan.htm');
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
	
	function ChangeCase(elem)
	{
		elem.value = elem.value.toUpperCase();
	}
	
	function tabE(obj,e){ 
		   var e=(typeof event!='undefined')?window.event:e;// IE : Moz 
		   if(e.keyCode==13){ 

		   	/*
		     var ele = document.forms[0].elements; 
		     for(var i=0;i<ele.length;i++){ 
		       var q=(i==ele.length-1)?0:i+1;// if last element : if any other 
		       if(obj==ele[i]){
		       	console.log(ele[q]);
		       	console.log(ele[q].getAttribute("display"));
		       	ele[q].focus();
		       	break
		       } 
		     } 
		     */
		
		    var currentIndex = $(obj).attr("tabindex");		   
         	var nextIndex = parseInt(currentIndex)+1;       
         	var quit = true; 
         		if($(obj).val()!= "1" && currentIndex==9) nextIndex=14;//truong hop rieng cua bao hiem
         	//	console.log($("input[tabindex='"+nextIndex+"']"));
         	//	console.log($("select[tabindex='"+nextIndex+"']"));
	         	if (($("input[tabindex='"+nextIndex+"']").val() != undefined)){
	         	$("input[tabindex='"+nextIndex+"']").focus();
         		}
         		else if (($("select[tabindex='"+nextIndex+"']").val() != undefined)){
         		 	$("select[tabindex='"+currentIndex+"']").css("font-weight","normal");
         			$("select[tabindex='"+nextIndex+"']").focus();
         			$("select[tabindex='"+nextIndex+"']").css("font-weight","bold");
         		}

         	
		  return false; 

		  } 

		 }
    
    $(function(){
        $("#time_hoichan").mask("**:**");
        $("#f-calendar-field-1").mask("**/**/****");
        $("#f-calendar-field-2").mask("**/**/****");
    });
</script>

