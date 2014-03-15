<?php
    if($date && $time){
        $cond="AND date='$date' AND time='$time'";
    }
    $gui_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=56");
    if($gui_tv){
        $gui=$gui_tv->FetchRow();
    }
    $cmnd_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=50");
    if($cmnd_tv){
        $cmnd=$cmnd_tv->FetchRow();
    }
    $ngaycap_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=51");
    if($ngaycap_tv){
        $ngaycap=$ngaycap_tv->FetchRow();
    }
    $noicap_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=52");
    if($noicap_tv){
        $noicap=$noicap_tv->FetchRow();
    }
    $time_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=53");
    if($time_tv){
        $time=$time_tv->FetchRow();
    }
    $nguyennhan_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=55");
    if($nguyennhan_tv){
        $nguyennhan=$nguyennhan_tv->FetchRow();
    }
    $noi_tv=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=57");
    if($noi_tv){
        $noi=$noi_tv->FetchRow();
    }
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_KINHGUI='Kính gửi';
    $TP_INPUT_KINHGUI='<input type="text" name="gui" id="gui" size="78" value="';
    if($gui['notes']){
        $TP_INPUT_KINHGUI.=$gui['notes'];
    }
    $TP_INPUT_KINHGUI.='" onblur="ChangeCase(this);" onkeypress="return tabE(this,event)"/>';
    $TP_CMND=$LDNatIdNr;
    $TP_INPUT_CMND='<input type="text" name="cmnd" id="cmnd" size="12" maxlength="9" value="';
    if($cmnd['notes']){
        $TP_INPUT_CMND.=$cmnd['notes'];
    }
    $TP_INPUT_CMND.='"/>';
    $TP_NGAY_CMND=$LDNatIDDate;
    $TP_INPUT_NGAY_CMND=$calendar->show_calendar($calendar,$date_format,'ngaycap',$ngaycap['notes']);
    $TP_NOICAP_CMND=$LDNatIDAddr;
    $TP_INPUT_NOICAP_CMND='<input type="text" name="noicap" id="noicap" size="35" value="';
    if($noicap['notes']){
        $TP_INPUT_NOICAP_CMND.=$noicap['notes'];
    }
    $TP_INPUT_NOICAP_CMND.='"/>';
    $TP_GIOTV=$LD['time_tuvong'];
    $tv=explode(' ',$time['notes']);
    $TP_INPUT_GIOTV='<input type="text" name="giotv" id="giotv" size="12" maxlength="5" value="';
    if($tv['1']){
        $TP_INPUT_GIOTV.=$tv['1'];
    }
    $TP_INPUT_GIOTV.='"/>';
    $TP_NGAYTV=$LDDeathDate;
    $TP_INPUT_NGAYTV=$calendar->show_calendar($calendar,$date_format,'ngaytv',$tv['0']);
    $noitv=explode('@',$noi['notes']);
    $TP_NOITV='Nơi tử vong';
    $TP_INPUT_NOITV='<input type="text" name="noitv" id="noitv" size="78" value="';
    if($noitv['0']){
        $TP_INPUT_NOITV.=$noitv['0'];
    }
    $TP_INPUT_NOITV.='"/>';
    $TP_BVTV='Bệnh viện tử vong';
    $TP_INPUT_BVTV='<input type="text" name="bvtv" id="bvtv" size="78" value="';
    if($noitv['1']){
        $TP_INPUT_BVTV.=$noitv['1'];
    }
    $TP_INPUT_BVTV.='"/>';
    
    $TP_NGUYENNHAN=$LDNguyennhan;
    $TP_INPUT_NGUYENNHAN='<textarea name="nguyennhan" id="nguyennhan" cols="58" rows="3">'; 
    if($nguyennhan['notes']){
        $TP_INPUT_NGUYENNHAN.=$nguyennhan['notes'];
    }
    $TP_INPUT_NGUYENNHAN.='</textarea>';
    $TP_DOCBY=$LDDocBy;    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_baotu.htm');
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
        $("#giotv").mask("**:**");
        $("#f-calendar-field-1").mask("**/**/****");
        $("#f-calendar-field-2").mask("**/**/****");
    });
</script>

