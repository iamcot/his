<?php
    if($rows!=0) 
        $history_question=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<script language="JavaScript">
    function Set_daubung(){
        var d = document.getElementById('report');
        var value="<?php echo $history_question['daubung'];?>";
        if(value==1){
            document.getElementById("daubung").value=0;
        }else{
            document.getElementById("daubung").value=1;
        }
        return true;
    }
    
    function checkTuoi(name,tuoi){
        var birth=<?php echo substr($date_birth,0,4); ?>;
        if(name=='thaykinh'){
            name1='batdauthaykinh';
            var warning="<?php echo $tuoithaykinh; ?>";
        }else{
            if(name=='laychong'){
                warning="<?php echo $tuoilaychong; ?>";
            }else{
                warning="<?php echo $tuoihetkinh; ?>";
            }
            name1='nam'+name;            
        }
        if(tuoi<=1){
            alert(warning);
            window.setTimeout(function () { document.getElementById("tuoi"+name).focus(); }, 0);   
            document.getElementById("tuoi"+name).value='';
            return false;
        }
        else{            
            if((name=='laychong' && document.getElementById("tuoi"+name).value<10)|| (document.getElementById("tuoithaykinh").value>document.getElementById("tuoihetkinh").value && document.getElementById("tuoihetkinh").value) ){
                alert(warning);
                window.setTimeout(function () { document.getElementById("tuoi"+name).focus(); }, 0); 
                document.getElementById("tuoi"+name).value='';
                document.getElementById(name1).value='';
                return false;
            }            
            ty=parseInt (birth)+parseInt(document.getElementById("tuoi"+name).value);
            if(ty<birth){
                alert(warning);
                window.setTimeout(function () { document.getElementById("tuoi"+name).focus(); }, 0); 
                document.getElementById("tuoi"+name).value='';
                return false;
            }
            $("#"+name1).val(ty);
        }
    } 
    
    function checkYear(name,year){
        if(name=='thaykinh'){
            tuoi='batdauthaykinh';
            var warning="<?php echo $thaykinh; ?>";
        }else{
            tuoi='nam'+name;
            if(name=='laychong'){
                warning="<?php echo $laychong; ?>";
            }else{
                warning="<?php echo $hetkinh; ?>";
            }
        }
        var year_now="<?php echo date("Y"); ?>";
        var birth="<?php echo substr($date_birth,0,4); ?>";
        if((year>year_now || year<birth) && name=='laychong' || (document.getElementById("batdauthaykinh").value>document.getElementById("namhetkinh").value && document.getElementById("namhetkinh").value)){
            alert(warning); 
            document.getElementById(tuoi).value='';
            window.setTimeout(function () { document.getElementById(tuoi).focus(); }, 0);
            document.getElementById("tuoi"+name).value='';
            return false;
        }else{            
            var ty = document.getElementById(tuoi).value-birth;
            if(ty<0){
                ty='';
            }
            $("#tuoi"+name).val(ty);
            if(name=='laychong' && document.getElementById("tuoi"+name).value<=10){
                alert(warning);
                document.getElementById("nam"+name).value='';
                window.setTimeout(function () { document.getElementById("nam"+name).focus(); }, 0); 
                document.getElementById("tuoi"+name).value='';
                return false;
            } 
        }
        return true;
    }
        
    function CheckValue_number(name,value){
        switch(name){
            case 'chuki':
                var temp="<?php echo $LD['chuki']; ?>";
                break;
            case 'songaykinh':
                var temp="<?php echo $LD['songaykinh']; ?>";
                break;
        }
        if(isNaN(document.getElementById(name).value)){
            alert(temp+"<?php echo ' '.$LDNopass;?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            return false;
        }
        return true;
    } 
    
    function setValue(name,value,nr){
        switch(name){
            case 'tienthai':
                var length=4;                
                break;
            default:
                var length=3;
                setValue_checkbox(name,value,nr,length);
                break;
        }                
        var i=1;      
        var value_before=document.getElementById(name).value.split(";");
        var value2='';
        while(i<=length){  
            value2=document.getElementById(name+i).value;  
            if(value2){
                value1=value2;
            }else{
                value1=value_before[i-1];
            }
            if(i==nr){
                if(value1!=value_before[i-1]){
                    value_before[i-1]=document.getElementById(name+i).value+';';
                }
            }else{
                if(!value_before[i-1] || typeof value_before[t]=='undefined' || value_before[i-1]==''){
                    if(value1!=''){
                        value_before[i-1]=value1+';';
                    }else
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
        if(document.getElementById(name+nr).value==1){
            document.getElementById(name+nr).value=0;
        }else{
            document.getElementById(name+nr).value=1;
        }  
        return true;
    }
    $(function(){
        $("#f-calendar-field-1").mask("**/**/****");
    });
</script>

<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_Warning=$condition_press;
    //tien su benh
    require_once($root_path.'include/care_api_classes/class_person.php');
    $person= new Person();
    $info= $person->getAllInfoArray($_SESSION['sess_pid']);
    $TP_canhan=$info['tiensubenhcanhan'];
    $TP_giadinh=$info['tiensubenhgiadinh'];
    
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $obj1=new Encounter;
    $status=$obj1->loadEncounterData1($_SESSION['sess_en'],1);
    if($status){
        if($status['quatrinhbenhly']){
            $TP_benhly=$status['quatrinhbenhly'];
        }
        
    }
    $TP_LIST_SICK=$LDQuatrinhbenhly;
    $TP_HISTORY_SICK=$history_sick;    
    
    $TP_kinhcuoi_from=$LDKinhlancuoi;  
    $TP_INPUT_DATE_FROM= $calendar->show_calendar($calendar,$date_format,'kinhcuoitu',$history_question['kinhcuoitu']);
    $TP_daubung=$LD['daubung'];
    $TP_daubung_input='<input type="checkbox" id="daubung" name="daubung" value="';
    if($history_question['daubung']) 
        $TP_daubung_input.=$history_question['daubung'].'" checked';
    else
        $TP_daubung_input.='"';
    $TP_daubung_input.=' onchange="Set_daubung()"/>';
//    $TP_daubung_input.='<input type="hidden" name="daubung" id="daubung" value="'.$history_question['daubung'].'"/>';
    $time=explode(";",$history_question['time']);
    $TP_TIME="<td style='padding-left:15px;'><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['time'][0]."</FONT></td>";
    $TP_TIME.="<td style='padding-left:15px;'><table cellpadding=0><tr>";
    $i=1;
    while($i<4){        
        $TP_TIME.="<td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['time'][$i]."</FONT></td>";                
        $TP_TIME.="<td>";
        $TP_TIME.='<input type="radio" name="time'.$i.'" id="time'.$i.'" value="';
        if($time[$i-1]) 
            $TP_TIME.=$time[$i-1].'" checked';
        else
            $TP_TIME.='"';
        $TP_TIME.=' onchange=setValue("time","'.$time[$i-1].'","'.$i.'") />';
        $TP_TIME.="</td>";    
        $TP_TIME.="<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $i++;
    }
    $TP_TIME.="</tr></table></td>";
    $TP_TIME.='<input type="hidden" name="time" id="time" value="'.$history_question['time'].'" />';
    $TP_laychongnam=$LD['namlaychong'];
    if($history_question['namlaychong']) $TP_laychongnam_input=$history_question['namlaychong'];
    $TP_tuoilaychong=$LD['tuoilaychong'];
    if($history_question['tuoilaychong']) $TP_tuoilaychong_input=$history_question['tuoilaychong'];
    $TP_namhetkinh=$LD['namhetkinh'];
    if($history_question['namhetkinh']) $TP_namhetkinh_input=$history_question['namhetkinh'];
    $TP_tuoihetkinh=$LD['tuoihetkinh'];
    if($history_question['tuoihetkinh']) $TP_tuoihetkinh_input=$history_question['tuoihetkinh'];
    $TP_benhphukhoa=$LD['benhphukhoa'];
    if($history_question['benhphukhoa']) $TP_benhphukhoa_input=$history_question['benhphukhoa'];
    
    $tienthai=explode(";",$history_question['tienthai']);
    $TP_TIENTHAI="<td style='padding-left:15px;'><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['tienthai'][0]."</FONT></td>";
    $TP_TIENTHAI.="<td style='padding-left:15px;'><table  border=0 cellpadding=2 width=100%><tr>";
    $i=1;
    while($i<5){        
        $TP_TIENTHAI.="<td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['tienthai'][$i]."</FONT></td>";                
        $TP_TIENTHAI.="<td>";
        $TP_TIENTHAI.='<input type="text" name="tienthai'.$i.'" id="tienthai'.$i.'" value="';
        if($tienthai[$i-1]!='') 
            $TP_TIENTHAI.=$tienthai[$i-1].'"';
        else
            $TP_TIENTHAI.='"';
        $TP_TIENTHAI.='size=1 onchange=setValue("tienthai","'.$tienthai[$i-1].'","'.$i.'")/>';
        $TP_TIENTHAI.="</td>";    
        $TP_TIENTHAI.="<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $i++;
    }
    $TP_TIENTHAI.="</tr></table></td>";
    $TP_TIENTHAI.='<input type="hidden" name="tienthai" id="tienthai" value="'.$history_question['tienthai'].'" />';
    
    //tien su san phu khoa
    $TP_HISTORY_GYN=$history_Obstetrics_Gynecology;
    $TP_HISTORY_OBS=$history_Obstetrics;
    $TP_batdauthaykinh=$LD['batdauthaykinh'];
    if($history_question['batdauthaykinh']) $TP_thaykinh_input=$history_question['batdauthaykinh'];
    $TP_tuoithaykinh=$LDTuoi;
    if($history_question['tuoithaykinh']) $TP_tuoithaykinh_input=$history_question['tuoithaykinh'];    
    $TP_tinhchatkinh=$LD['tinhchatkinh'];
    if($history_question['tinhchatkinh']) $TP_tinhchat_input=$history_question['tinhchatkinh'];
    $TP_chuki=$LD['chuki'];
    if($history_question['chuki']) $TP_chuki_input=$history_question['chuki'];
    $TP_luongkinh=$LD['luongkinh'];
    if($history_question['luongkinh']) $TP_luongkinh_input=$history_question['luongkinh'];
    $TP_songaykinh=$LD['songaykinh'];
    if($history_question['songaykinh']) $TP_songaykinh_input=$history_question['songaykinh'];
           
    $TP_DOCBY=$LD['docu_by'];
    $TP_DBY=$_SESSION['sess_user_name'];
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_obstetrics_question.htm');
    eval("echo $tp_preg;");
?>
    </script>
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

