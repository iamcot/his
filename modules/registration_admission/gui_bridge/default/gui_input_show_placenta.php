<?php
    if($rows) $pregnancy=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    require($root_path.'classes/datetimemanager/checktime.php'); 
?>
<script language="JavaScript">
<!-- Script Begin 
    var flag='';
    function chkform() {
        var d = document.getElementById('report');        
        if(d.date.value==""){
		alert("<?php echo $LDWarningRau; ?>");
		d.date.focus();
		return false;
        }else if(d.cachsorau.value==""){
		alert("<?php echo $LDWarningCachsorau; ?>");
		d.cachsorau.focus();
		return false;
	}else if(d.matmang.value==""){
		alert("<?php echo $LDWarningMatmang; ?>");
		d.matmang.focus();
		return false;
	}else if(d.matmui.value==""){
		alert("<?php echo $LDWarningMatmui; ?>");
		d.matmui.focus();
		return false;
	}else if(d.cannang.value==""){
		alert("<?php echo $LDWarningCannang1; ?>");
		d.cannang.focus();
		return false;
	}else if(isNaN(d.crdai.value)){
		alert("<?php echo $LDWarningcrdai; ?>");
		d.crdai.focus();
		return false;
	}else if(d.crdai.value==""){
		alert("<?php echo $LDWarningcrdai1; ?>");
		d.crdai.focus();
		return false;
	}else if(d.docu_by.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.docu_by.focus();
		return false;
	}else if(d.time.value=='00:00:00'){
                alert("<?php echo $LDWarningRau1; ?>");
		d.time.focus();
		return false;
        }
        return true;
    }
        
    function setValue(name,value,nr){
        d=document.report;
        if(name=='raucuonco' || name=='kstc' || name=='chaymau'){
            var length=0;
            var value_before=value;
            setValue_checkbox(name,value,nr,length);
        }else{
            var length=2;
            var value_before=document.getElementById(name).value.split(";");
            setValue_checkbox(name,value,nr,length); 
            var i=1; 
            while(i<=length){
                if(i==nr){
                    if(value!=value_before[t]){
                        value_before[i-1]=document.getElementById(name+i).value+';';
                    }
                }else{
                    if(!value_before[i-1] || value_before[i-1]=='' || typeof value_before[t]=='undefined'){
                        value_before[i-1]=';';
                    }                    
                    else
                        value_before[i-1]=value_before[i-1]+';';
                }   

                i++;
            }
            var str='';
            var t=0;
            while(t<value_before.length){
                str+=value_before[t];
                t++;
            }
            document.getElementById(name).value=str;
        }   
        return true;
    }
        
    function setValue_checkbox(name,value,nr,length){   
        if(name=='raucuonco' || name=='kstc' || name=='chaymau'){
            var i=0;
        }else{
            var i=1;
        }        
        while(i<=length){
            if(i==nr){
                if(document.getElementById(name+i).value==1){
                    document.getElementById(name+i).value='';
                }else{
                    document.getElementById(name+i).value=1;
                }
            }else{
                document.getElementById(name+i).value='';
                document.getElementById(name+i).checked='';
            }            
            i++;
        }           
        return true;
    }
    
    function check_nr(name,value){
        switch(name){
            case 'crdai':
                var ten="<?php echo $LDCRDai; ?>";
                break;
            case 'cannang':
                var ten="<?php echo $LD['cannang']; ?>";
                break;    
            default:
                var ten="<?php echo $LD['matmau']; ?>";
                break;
        }
        if(document.getElementById(name).value<0){
            alert(ten+" <?php echo $LDNopass; ?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            return false;
        }else if(isNaN(document.getElementById(name).value)){
            alert(ten+" <?php echo $LD[name].' '.$LDNopass; ?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            return false;
        } 
        
        return true;
    }
    
    $(function(){
        $("#time").mask("**:**:**");
        $("#f-calendar-field-1").mask("**/**/****");
    });
</script>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_Warning=$condition_press;
    
    $rau=explode(";",$pregnancy['rau']);
    $TP_RAU="<td style='padding-left:15px;'><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['rau'][0]."</FONT></td>";
    $TP_RAU.="<td style='padding-left:15px;'><table cellpadding=0><tr>";
    $i=1;
    while($i<3){ 
        $TP_RAU.="<td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['rau'][$i]."</FONT></td>";                
        $TP_RAU.="<td>";
        $TP_RAU.='<input type="checkbox" name="rau'.$i.'" id="rau'.$i.'" value="';
        if($rau[$i-1]) 
            $TP_RAU.=$rau[$i-1].'" checked';
        else
            $TP_RAU.='"';
        $TP_RAU.=' onchange=setValue("rau","'.$rau[$i-1].'","'.$i.'") />';
        $TP_RAU.="</td>";    
        $TP_RAU.="<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $i++;
    }
    $TP_RAU.="</tr></table></td>";
    $TP_RAU.='<input type="hidden" name="rau" id="rau" value="'.$pregnancy['rau'].'" />';
//    
//    $TP_BOC='<input type="checkbox" name="boc" id="boc" value="';
//    if($pregnancy['boc']) 
//        $TP_BOC.=$pregnancy['boc'].'" checked';
//    else
//        $TP_BOC.='"';
//    $TP_BOC.=' onchange=setValue("boc","'.$pregnancy['boc'].'") />'.$LD['boc'];
//    $TP_SO='<input type="checkbox" name="so" id="so" value="';
//    if($pregnancy['so']) 
//        $TP_SO.=$pregnancy['so'].'" checked';
//    else
//            $TP_SO.='"';    
//    $TP_SO.=' onchange=setValue("so","'.$pregnancy['so'].'") />'.$LD['so'];
        

    $TP_IMG_PDATE = $calendar->show_calendar($calendar,$date_format,'date',$pregnancy['date']);
    //gjergji : end
    //end : #154
    # Delivery time
    if($pregnancy['time']) $TP_PTIME=$pregnancy['time'];
    else $TP_PTIME=date('H:i:s');
    
    $TP_CACHSO=$LD['cachsorau'];
    if($pregnancy['cachsorau'])
        $TP_CACHSO_INPUT=$pregnancy['cachsorau'];
    
    $TP_MATMANG=$LD['matmang'];
    if($pregnancy['matmang'])
        $TP_MATMANG_INPUT=$pregnancy['matmang'];
    
    $TP_MATMUI=$LD['matmui'];
    if($pregnancy['matmui'])
        $TP_MATMUI_INPUT=$pregnancy['matmui'];
    
    $TP_BANHRAU=$LD['banhrau'];
    if($pregnancy['banhrau'])
        $TP_BANHRAU_INPUT=$pregnancy['banhrau'];
    
    $TP_CANNANG=$LD['cannang'];
    if($pregnancy['cannang'])
        $TP_CANNANG_INPUT=$pregnancy['cannang'];

    $TP_RAUCUONCO=$LD['raucuonco'];
    $TP_RAUCUONCO_INPUT='<input type="checkbox" name="raucuonco" id="raucuonco0" value="';
    if($pregnancy['raucuonco']) 
        $TP_RAUCUONCO_INPUT.=$pregnancy['raucuonco'].'" checked';
    else
        $TP_RAUCUONCO_INPUT.='"';
    $TP_RAUCUONCO_INPUT.=' onchange=setValue("raucuonco","'.$pregnancy['raucuonco'].'",0) />';
    
    $TP_KSTC=$LD['kstc'];
    $TP_KSTC_INPUT='<input type="checkbox" name="kstc" id="kstc0" value="';
    if($pregnancy['kstc']) 
        $TP_KSTC_INPUT.=$pregnancy['kstc'].'" checked';
    else
        $TP_KSTC_INPUT.='"';
    $TP_KSTC_INPUT.=' onchange=setValue("kstc","'.$pregnancy['kstc'].'",0) />';
    
    $TP_CRDAI=$LD['crdai'];
    if($pregnancy['crdai'])
        $TP_CRDAI_INPUT=$pregnancy['crdai'];
    
    $TP_CHAYMAU=$LD['chaymau'];
    $TP_CHAYMAU_INPUT='<input type="checkbox" name="chaymau" id="chaymau0" value="';
    if($pregnancy['chaymau']) 
        $TP_CHAYMAU_INPUT.=$pregnancy['chaymau'].'" checked';
    else
        $TP_CHAYMAU_INPUT.='"';
    $TP_CHAYMAU_INPUT.=' onchange=setValue("chaymau","'.$pregnancy['chaymau'].'",0) />';
    
    # Blood loss
    $TP_BLOODLOSS.=$LD['matmau'];
    if($pregnancy['matmau']) 
        $TP_BLOSS=$pregnancy['matmau']; 
    # Blood loss unit of measure
    # make ml (milliliter) the default
    if(empty($pregnancy['donvimau'])) $pregnancy['donvimau']='ml';
    $TP_BLOSS_OPTIONS='';
    # Load the volume units
    $unit=&$msr->VolumeUnits();
    while(list($x,$v)=each($unit)){
            $TP_BLOSS_OPTIONS.='<option value="'.$v['id'].'" ';
            if($pregnancy['donvimau']==$v['id']) $TP_BLOSS_OPTIONS.='selected';
            $TP_BLOSS_OPTIONS.='>'.$v['id'];
    }
    
    $TP_XULY=$LD['xuly'];
    if($pregnancy['xuly'])
        $TP_XULY_INPUT=$pregnancy['xuly'];
    else
        $TP_XULY_INPUT='';
    
    # Post labour condition
    $TP_DOCBY=$LD['docu_by'];
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_placenta.htm');
    eval("echo $tp_preg;");
?>
<input type="hidden" name="sid" value="<?php echo $sid; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>" />
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>" />
<input type="hidden" name="rec_nr" value="<?php echo $rec_nr; ?>" />
<input type="hidden" name="allow_update" value="<?php if(isset($allow_update)) echo $allow_update; ?>" />
<input type="hidden" name="target" value="<?php echo trim($target); ?>" />
<input type="hidden" name="mode" value="newdata" />
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?> />

</form>


