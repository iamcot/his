<?php
    if($rows) $pregnancy=$pregs->FetchRow();
    $result1=&$obj->BirthDetails($_SESSION['sess_pid']);
    $rows1=$obj->LastRecordCount();
?>
<script language="JavaScript">
<!-- Script Begin 
    var flag='';
    function chkform() {
        var d = document.getElementById('report');            
        if((d.tsm_rach.value==1 && d.tsm_cat.value==1) || (d.tsm_khongrach.value==1 && d.tsm_rach.value==1) || (d.tsm_khongrach.value==1 && d.tsm_cat.value==1)){
		alert("<?php echo $LDWarningCheckbox; ?>");
		d.tsm_rach.focus();
		return false;
	}else if(d.tc_rach.value==1 && d.tc_khongrach.value==1){
		alert("<?php echo $LDWarningCheckbox1; ?>");
		d.tc_rach.focus();
		return false;
	}else if(d.docu_by.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.docu_by.focus();
		return false;
	}else{
            var tempt=d.nr_of_fetuses.value;
            var child_encounter_nr=d.child_encounter_nr.value; 
            var i=1;
            while(i<=tempt){
                if(document.getElementById(i).value==''){
                    alert("<?php echo $alert_birth.' '; ?>"+i);
                    document.getElementById(i).focus();
                    return false;
                }                
                child_encounter_nr+=";"+document.getElementById(i).value;
                i++;
            }            
            d.child_encounter_nr.value=child_encounter_nr;
            return true;
        }
    }
        
    function setValue(name,value){
        if(value==''){
//            if($("#"+name).attr("value")==1){
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
    
    function setInput(name,value){
        d=document.report;
        var i=1;
        var str='';
        var input=document.getElementById('tsm_khau').elements;
        setValue(name,value);
        var rach=d.tsm_rach.value; 
        var cat=d.tsm_cat.value;                
        var phuongphapkhau="<?php echo $pregnancy['phuongphapkhau'];?>";
        var somuikhau="<?php $pregnancy['somuikhau'];?>";
        if( rach!=0 || cat!=0 ){
            d.tsm_khongrach.value=0;
            d.tsm_khongrach.checked='';
            str+="<table border=0 cellpadding=2 width=100%>";
            str+="<tr bgcolor='#f6f6f6'>";
            str+="<td><FONT SIZE=-1  FACE='Arial' color='#000066'><?php echo $LD['phuongphapkhau'];?></FONT></td>";
            str+="<td><textarea name='phuongphapkhau' id='phuongphapkhau' cols='52' rows='2'><?php echo $pregnancy['phuongphapkhau'];?></textarea></td>";
            str+="</tr>";
            str+="<tr bgcolor='#f6f6f6'>";
            str+="<td><FONT SIZE=-1  FACE='Arial' color='#000066'><?php echo $LD['somuikhau'];?></FONT></td>";
            str+="<td><input type='text' name='somuikhau' id='somuikhau' size=10 maxlength='10' value='<?php echo $pregnancy['somuikhau'];?>' onchange='check_nr1(this)'/></td>";
            str+="</tr>";
            str+="</table>";
        }else{
            str+="";
            d.tsm_khongrach.value=1;
            d.tsm_khongrach.checked='checked';
        }        
        document.getElementById('tsm_khau').innerHTML = str;
        return true;
    }
    
    function setInput_tc(name,value){
        d=document.report;        
        setValue(name,value);
        var tc_khongrach=d.tc_khongrach.value;
        var tc_rach=d.tc_rach.value; 
        if(tc_rach!=0){
            d.tc_khongrach.value=0;
            d.tc_khongrach.checked='';
        }
    }
    
    function check_nr1(selectobj){
        d=document.report;
        if(d.somuikhau.value<0){
            alert("<?php echo $LDNrMuiKhau_NotNegValue; ?>");
            d.somuikhau.focus();
            return false;
        }else if(isNaN(d.somuikhau.value) || d.somuikhau.value.indexOf(".")>0){
            alert("<?php echo $LDWarningNrMuiKhau; ?>");
            d.somuikhau.focus();
            return false;
        } 
        return true;
    }   
    
//  Script End -->
</script>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_Warning=$condition_press;
    $TP_sieuam=$LD['nr_of_fetuses'];
    if($parent_admit){
	# Get the pregnancy data of this encounter
	$pregs1=&$obj->history($_SESSION['sess_en'],'_ENC');
        if($pregs1){
            $sieuam=$pregs1->FetchRow();
        }
    }else{
	# Get all pregnancies  of this person
	$pregs1=&$obj->history($_SESSION['sess_pid'],'_REG');
        if($pregs1){
            $sieuam=$pregs1->FetchRow();
        }
    }
    if($pregnancy['nr_of_fetuses']) 
        $TP_sieuam_input=$pregnancy['nr_of_fetuses'];
    else if($sieuam['sieuam']!=0){
        $TP_sieuam_input=$sieuam['sieuam'];
    }else
        $TP_sieuam_input=0;
    $TP_CHILD_ENR=$LD['child_encounter_nr'];
    $TP_CH_ENR="";
    $child_nr=explode(";",$pregnancy['child_encounter_nr']);
    if(sizeof($child_nr)<=1){
        if($TP_sieuam_input<2){
            $TP_CH_ENR.="<input type='text' name='child_encounter_nr[1]' id='1' size=20 maxlength=20 value='".$child_nr[1]."' onchange=check_nr('1','".$child_nr[1]."') />";
        }else{
            $i=1;
            while($i<=$TP_sieuam_input){
                $TP_CH_ENR.=$baby." ".$i.":      <input type='text' name='child_encounter_nr[".$i."]' id='".$i."' size=20 maxlength=20 value='".$child_nr[$i]."' onchange=check_nr('1','".$child_nr[1]."') />"; 
                $TP_CH_ENR.="<br/>";
                $i++;
            }
        }
    }else{
        $i=1;
        while($i<=sizeof($child_nr)-1){
            $TP_CH_ENR.=$baby." ".$i.":      <input type='text' name='child_encounter_nr[".$i."]' id='".$i."' size=20 maxlength=20 value='".$child_nr[$i]."' onchange=check_nr('1','".$child_nr[1]."') />"; 
            $TP_CH_ENR.="<br/>";
            $i++;
        }
    }    
    $TP_INPUT_HIDDEN="<input type='hidden' name='child_encounter_nr' id='child_encounter_nr' value=''/>";
    $TP_SEPARATE=$LD['sepspace'];
    
    $TP_PREG_NR=$LD['da_niemmac'];
    if($pregnancy['da_niemmac']) $TP_PNR=$pregnancy['da_niemmac'];
    if(!isset($pregnancy['delivery_mode'])) $pregnancy['delivery_mode']=1;
        $TP_DELIV_MODE=$LD['delivery_mode'];
    # Delivery mode radio buttosn
    $TP_DMODE_RADIOS='';
    $dm=&$obj->DeliveryModes();
    if($obj->LastRecordCount()){
        while($dmod=$dm->FetchRow()){
            $TP_DMODE_RADIOS.='<input type="radio" name="delivery_mode" value="'.$dmod['nr'].'" ';
            if($pregnancy['delivery_mode']==$dmod['nr']) 
                $TP_DMODE_RADIOS.='checked' ;
            $TP_DMODE_RADIOS.='>';
            if(isset($$dmod['LD_var']) && $$dmod['LD_var']) 
                $TP_DMODE_RADIOS.=$$dmod['LD_var'];
            else 
                $TP_DMODE_RADIOS.=$dmod['name'];
            $TP_DMODE_RADIOS.='&nbsp;';
        }
    }
    $TP_VDRL=$LD['lydo'];
    $TP_VDRL_VAL=$pregnancy['lydo'];
    # Retained placenta
    $TP_RETPLACENTA=$LD['tangsinhmon'];
    $TP_KHONGRACH='<input type="checkbox" name="tsm_khongrach" id="tsm_khongrach" value="';
    if($pregnancy['tsm_khongrach']) 
        $TP_KHONGRACH.=$pregnancy['tsm_khongrach'].'" checked';
    else
        $TP_KHONGRACH.='"';
    $TP_KHONGRACH.=' onchange=setValue("tsm_khongrach","'.$pregnancy['tsm_khongrach'].'") />'.$LD['tsm_khongrach'];
    $TP_RACH='<input type="checkbox" name="tsm_rach" id="tsm_rach" value="';
    if($pregnancy['tsm_rach']){
        $TP_RACH.=$pregnancy['tsm_rach'].'" checked'; 
        $cond=1;
    }else{
        $TP_RACH.='"';
    }        
    $TP_RACH.=' onchange=setInput("tsm_rach","'.$pregnancy['tsm_rach'].'") />'.$LD['tsm_rach'];
    $TP_CAT='<input type="checkbox" name="tsm_cat" id="tsm_cat" value="';
    if($pregnancy['tsm_cat']){
        $TP_CAT.=$pregnancy['tsm_cat'].'" checked';
        $cond=1;
    }else
        $TP_CAT.='"';
    $TP_CAT.=' onchange=setInput("tsm_cat","'.$pregnancy['tsm_cat'].'") />'.$LD['tsm_cat'];    
    if($cond){
        $TP_PHUONGPHAPKHAU="<table border=0 cellpadding=2 width=100%><tr bgcolor='#f6f6f6'>";
        $TP_PHUONGPHAPKHAU.="<td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['phuongphapkhau']."</FONT></td>";
        $TP_PHUONGPHAPKHAU_INPUT="<td><textarea name='phuongphapkhau' id='phuongphapkhau' maxlength='255' cols='52' rows='2'>".$pregnancy['phuongphapkhau'].'</textarea></td>';
        $TP_SOMUIKHAU="<tr bgcolor='#f6f6f6'><td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['somuikhau']."</FONT></td>";
        $TP_SOMUIKHAU_INPUT="<td><input type='text' name='somuikhau' id='somuikhau' size=10 maxlength='10' value='".$pregnancy['somuikhau']."' onchange='check_nr1(this)'/></td>";     
        $TP_SOMUIKHAU_INPUT.="</tr></table>";    
    }
    $TP_TC=$LD['TC'];
    $TP_TC_KHONGRACH='<input type="checkbox" name="tc_khongrach" id="tc_khongrach" value="';
    if($pregnancy['tc_khongrach']) 
        $TP_TC_KHONGRACH.=$pregnancy['tc_khongrach'].'" checked';
    else
        $TP_TC_KHONGRACH.='"';
    $TP_TC_KHONGRACH.=' onchange=setValue("tc_khongrach","'.$pregnancy['tc_khongrach'].'") />'.$LD['tc_khongrach'];
    $TP_TC_RACH='<input type="checkbox" name="tc_rach" id="tc_rach" value="';
    if($pregnancy['tc_rach']) 
        $TP_TC_RACH.=$pregnancy['tc_rach'].'" checked';
    else
        $TP_TC_RACH.='"';
    $TP_TC_RACH.=' onchange=setInput_tc("tc_rach","'.$pregnancy['tc_rach'].'") />'.$LD['tc_rach'];
    # Post labour condition
    $TP_DOCBY=$LD['docu_by'];
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_pregnancy.htm');
    eval("echo $tp_preg;");
?>
<script language="JavaScript">
    function CheckValue_number(name){
        if(isNaN(document.getElementById(name).value)){
            alert("<?php echo $LD['nr_of_fetuses'].' '.$LDNopass;?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            return false;
        }else if((document.getElementById(name).value)==''){
            alert("<?php echo $LD['nr_of_fetuses'].' '.$LDNopass;?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            return false;
        }
        if("<?php echo $sieuam['sieuam']; ?>"!=document.getElementById(name).value){
            var r=confirm("<?php echo $LDPregnancy;?>");
            if(r==false){
                document.getElementById(name).value=value;
                window.setTimeout(function() { document.getElementById(name).focus(); },0);
                return false;
            }           
        }
        CheckNRBaby(name);
    }
    
    function CheckNRBaby(name){
        var rows= <?php echo $rows1?>;
        var number= document.getElementById(name).value;
        d=document.report;
        if(number < rows){
            var answer= confirm("<?php echo $confirm_pregnancy ?> " + rows + " <?php echo $baby_record.'\n'.$confirm ?> " + number + " <?php echo '\n'.$alert_pregnancy ?> " + number); 
            if(answer){
                d.nr_of_fetuses.value=number;
            }else{
                d.nr_of_fetuses.value="<?php echo $pregnancy['nr_of_fetuses']?>";
            }            
        }           
        setInput_child();
    }
    
    function setInput_child(){
        d=document.report;
        var i=1;
        var str='';
        var input=document.getElementById('child_nr').elements;
        var number=d.nr_of_fetuses.value; 
        var child_nr="<?php echo $pregnancy['child_encounter_nr']?>".split(";");  
        str+="<table border=0 cellpadding=2>";
        while(i<=number){  
            if(i<=child_nr.length-1){
                str+="<tr bgcolor='#f6f6f6'><td><?php echo $baby.' '?> "+i+":</td><td><input type='text' name='child_encounter_nr["+i+"]' id='"+i+"' size=20 maxlength=20 value='"+child_nr[i]+"' onchange=check_nr('"+i+"','"+child_nr[i]+"') ></td></tr>";
            }else{
                str+="<tr bgcolor='#f6f6f6'><td><?php echo $baby.' '?> "+i+":</td><td><input type='text' name='child_encounter_nr["+i+"]' id='"+i+"' size=20 maxlength=20 value='' onchange=check_nr('"+i+"','') ></td></tr>";
            }
            i++;
        }
        str+="</table>";
        document.getElementById('child_nr').innerHTML = str;
        return true;
    }
    
    function check_nr(nr,value){
        d=document.report;
        if(document.getElementById(nr).value<=0){
            alert("<?php echo $LD['child_encounter_nr'].' '.$LDYes_s.' '.$LDNotNegValue; ?>");
            document.getElementById(nr).value=value;
            document.getElementById(nr).focus();
            return false;
        }else{
            var sum_baby=d.nr_of_fetuses.value;
            var i=1;
            var j=1;
            while(i<=sum_baby){
                while(j<=sum_baby){
                    if(document.getElementById(i).value==document.getElementById(j).value && i!=j){
                        alert("<?php echo $LD['child_encounter_nr'].' '.$LDThu.' '; ?>"+i+" <?php echo $LDNotDouble.' '.$LDVoi.' '.$LD['child_encounter_nr'].' '.$LDThu.' '; ?>"+j);
                        document.getElementById(j).value=value;
                        document.getElementById(j).focus();
                        return false;
                    }
                    j++;
                }
                i++;    
            }
        }        
        return true;
    }
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


