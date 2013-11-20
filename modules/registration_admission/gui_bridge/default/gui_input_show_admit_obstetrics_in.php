<?php
    if($pregs) $pregnancy=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
<script language="JavaScript">    
    function setValue(name,value,nr){
        switch(name){
            case 'oi':
                var length=3;                 
                break;
            case 'oivo':
                var length=2;                
                break;
            default:
                var length=4;
                break;
        }
        setValue_checkbox(name,value,nr,length);
        var i=1;        
        var value_before=document.getElementById(name).value.split(";");
        while(i<=length){
            if(i==nr){
                if(value!=value_before[t]){
                    value_before[i-1]=document.getElementById(name+i).value+';';
                }
            }else{
                if(!value_before[i-1] || typeof value_before[t]=='undefined'){
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
        return true;
    }
    
    function setValue_checkbox(name,value,nr,length){
        var i=1;
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
    
    function CheckValue_number(name,value){
        if(isNaN(document.getElementById(name).value)){
            alert("<?php echo $LD['bishop'].' '.$LDNopass;?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            return false;
        }
        return true;
    }
    
    $(function(){
        $("#time_oivo").mask("**:**:**");
        $("#f-calendar-field-1").mask("**/**/****");
    });
</script>
<form method="post" name="report" id="report" >
<?php    
    $TP_BISHOP=$LD['bishop'];
    $TP_dv=$diem;
    if($pregnancy['bishop']){
        $TP_BISHOP_INPUT=$pregnancy['bishop'];
    }
        
    $TP_AMHO=$LD['amho'];
    if($pregnancy['amho']){
        $TP_AMHO_INPUT=$pregnancy['amho'];
    }
    
    $TP_AMDAO=$LD['amdao'];
    if($pregnancy['amdao']){
        $TP_AMDAO_INPUT=$pregnancy['amdao'];
    }
    $TP_TSM=$LD['tangsinhmon'];
    if($pregnancy['tangsinhmon']){
        $TP_TSM_INPUT=$pregnancy['tangsinhmon'];
    }
    
    $TP_TC=$LD['TC'];
    if($pregnancy['TC']){
        $TP_TC_INPUT=$pregnancy['TC'];
    }
    $TP_PHANPHU=$LD['phanphu'];
    if($pregnancy['phanphu']){
        $TP_PHANPHU_INPUT=$pregnancy['phanphu'];
    }
    
    $oi=explode(";",$pregnancy['oi']);
    $TP_TTOI="<td style='padding-left:15px;'><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['oi'][0]."</FONT></td>";
    $TP_TTOI.="<td style='padding-left:15px;'><table cellpadding=0><tr>";
    $i=1;
    while($i<4){        
        $TP_TTOI.="<td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['oi'][$i]."</FONT></td>";                
        $TP_TTOI.="<td>";
        $TP_TTOI.='<input type="checkbox" name="oi'.$i.'" id="oi'.$i.'" value="';
        if($oi[$i-1]) 
            $TP_TTOI.=$oi[$i-1].'" checked';
        else
            $TP_TTOI.='"';
        $TP_TTOI.=' onchange=setValue("oi","'.$oi[$i-1].'","'.$i.'") />';
        $TP_TTOI.="</td>";    
        $TP_TTOI.="<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $i++;
    }
    $TP_TTOI.="</tr></table></td>";
    $TP_TTOI.='<input type="hidden" name="oi" id="oi" value="'.$pregnancy['oi'].'" />';
    
    $TP_TIME=$LDDate_break;
    if($pregnancy['time_oivo']) $TP_PTIME=$pregnancy['time_oivo'];
    else $TP_PTIME=date('H:i:s');
    $TP_DATE=$LD['date_oivo'];    
    $TP_DATE_INPUT = $calendar->show_calendar($calendar,$date_format,'date_oivo',$pregnancy['date_oivo']);
    
    $oivo=explode(";",$pregnancy['oivo']);
    $TP_OIVO="<td style='padding-left:15px;'><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['oivo'][0]."</FONT></td>";
    $TP_OIVO.="<td style='padding-left:15px;'><table cellpadding=0><tr>";
    $i=1;
    while($i<3){        
        $TP_OIVO.="<td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['oivo'][$i]."</FONT></td>";                
        $TP_OIVO.="<td>";
        $TP_OIVO.='<input type="checkbox" name="oivo'.$i.'" id="oivo'.$i.'" value="';
        if($oivo[$i-1]) 
            $TP_OIVO.=$oivo[$i-1].'" checked';
        else
            $TP_OIVO.='"';
        $TP_OIVO.=' onchange=setValue("oivo","'.$oivo[$i-1].'","'.$i.'") />';
        $TP_OIVO.="</td>";    
        $TP_OIVO.="<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $i++;
    }
    $TP_OIVO.="</tr></table></td>";
    $TP_OIVO.='<input type="hidden" name="oivo" id="oivo" value="'.$pregnancy['oivo'].'" />';
    
    $TP_MSNUOCOI=$LD['msnuocoi'];
    if($pregnancy['msnuocoi']){
        $TP_MSNUOCOI_INPUT=$pregnancy['msnuocoi'];
    }
    
    $TP_NUOCOI=$LD['nuocoi'];
    if($pregnancy['nuocoi']){
        $TP_NUOCOI_INPUT=$pregnancy['nuocoi'];
    }
    
    $TP_NGOI=$LD['ngoi'];
    if($pregnancy['ngoi']){
        $TP_NGOI_INPUT=$pregnancy['ngoi'];
    }
    
    $TP_THE=$LD['the'];
    if($pregnancy['the']){
        $TP_THE_INPUT=$pregnancy['the'];
    }
    
    $TP_KIEUTHE=$LD['kieuthe'];
    if($pregnancy['kieuthe']){
        $TP_KIEUTHE_INPUT=$pregnancy['kieuthe'];
    }
    
    $dolot=$pregnancy['dolot'];
    $TP_DOLOT="<td style='padding-left:15px;'><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['dolot'][0]."</FONT></td>";
    $TP_DOLOT.="<td style='padding-left:15px;'><table cellpadding=0><tr>";
    $i=1;
    while($i<5){        
        $TP_DOLOT.="<td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['dolot'][$i]."</FONT></td>";                
        $TP_DOLOT.="<td>";
        $TP_DOLOT.='<input type="checkbox" name="dolot'.$i.'" id="dolot'.$i.'" value="';
        if($dolot[$i-1]==1) 
            $TP_DOLOT.=$dolot[$i-1].'" checked';
        else
            $TP_DOLOT.='"';
        $TP_DOLOT.=' onchange=setValue("dolot","'.$dolot[$i-1].'","'.$i.'") />';
        $TP_DOLOT.="</td>";    
        $TP_DOLOT.="<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $i++;
    }
    $TP_DOLOT.="</tr></table></td>";
    $TP_DOLOT.='<input type="hidden" name="dolot" id="dolot" value="'.$pregnancy['dolot'].'" />';
    
    $TP_DKNHV=$LD['dknhv'];
    if($pregnancy['dknhv']){
        $TP_DKNHV_INPUT=$pregnancy['dknhv'];
    }
    
    $TP_DOCBY=$LD['docu_by'];    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_admit_obstetrics_in.htm');
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


