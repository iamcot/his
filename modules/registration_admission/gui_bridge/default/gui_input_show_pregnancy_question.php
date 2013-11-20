<?php
    if($rows!=0) 
        $history_question=$pregs->FetchRow();
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    require($root_path.'classes/datetimemanager/checktime.php'); 
?>
<script language="JavaScript">
    function chkform(){
        var d = document.getElementById('report');
        if(isNaN(d.tuoithai.value)){
            alert("<?php echo $LDPlsEnterTuoithai; ?>");
            d.tuoithai.focus();
            return false;
        }else if(d.ngaychuyenda.value==''){
            alert("<?php echo $LDEnterNgaychuyenda; ?>");
            d.ngaychuyenda.focus();
            return false;
        }else if(d.dauhieulucdau.value==''){
            alert("<?php echo $LDEnterdauhieulucdau; ?>");
            d.tuoithaidauhieulucdau.focus();
            return false;
        }else if(isNaN(d.batdauthaykinh.value)){
            alert("<?php echo $LDEnterbatdauthaykinh; ?>");
            d.batdauthaykinh.focus();
            return false;
        }else if(isNaN(d.chuki.value)){
            alert("<?php echo $LDEnterchuki; ?>");
            d.chuki.focus();
            return false;
        }else if(isNaN(d.namlaychong.value) || d.namlaychong.value.indexOf(".")>0){
            alert("<?php echo $LDEnternamlaychong; ?>");
            d.namlaychong.focus();
            return false;
        }else if(isNaN(d.tuoilaychong.value)){
            alert("<?php echo $LDEntertuoilaychong; ?>");
            d.tuoilaychong.focus();
            return false;
        }else{ 
            return true;                        
        }
    }
        
    function setValue_checkbox(name,value,i){
        var value_before=document.getElementById(name).value.split(";");
        if(value==value_before[i]){
            if(document.getElementById(name+i).value==1){
                document.getElementById(name+i).value=0;
            }else{
                document.getElementById(name+i).value=1;
            }
        }else{
            if(document.getElementById(name+i).value==1){
                document.getElementById(name+i).value=0;
            }else{
                document.getElementById(name+i).value=1;
            }
        }      
        return true;
    }
    
    function settable_history(name, i){
        var d = document.getElementById('report');
        var t=0;
        var name_db= document.getElementById(name).value.split(";");
        var value=document.getElementById(name+i).value;                
        while(t<6){
            if(t==i){
                switch(name){
                    case "nammangthai":
                        if(isNaN(document.getElementById(name+i).value) || document.getElementById(name+i).value.indexOf(".")>0){
                            alert("<?php echo $LDEnternammangthai.' '; ?>"+(i+1)+" <?php echo $LDWarning;?>");
                            window.setTimeout(function () { document.getElementById(name+i).focus(); }, 0);
                            return false;
                        }
//                        if(document.getElementById(name+i).value < document.getElementById('namlaychong').value){
//                            alert("<?php echo $LDEnternammangthai.' '; ?>"+(i+1)+" <?php echo $LDWarningQuestion.' '.$LDYearLC;?>");
//                            document.getElementById(name+i).value='';
//                            window.setTimeout(function () { document.getElementById(name+i).focus(); }, 0);
//                            return false;
//                        } 
                        var j=i-1;
                        if(j>0){
                            var temp=document.getElementById(name+j).value;
                            if((document.getElementById(name+i).value <= temp || document.getElementById(name+i).value >"<?php echo date('Y'); ?>") && document.getElementById(name+i).value!=''){
                                alert("<?php echo $LDEnternammangthai.' '; ?>"+(i+1)+" <?php echo $LDWarningQuestion1.' '.$LDOr.' '.$LDYearNow;?>");
                                document.getElementById(name+i).value='';
                                window.setTimeout(function () { document.getElementById(name+i).focus(); }, 0);
                                return false;
                            }
                        }else if(document.getElementById(name+i).value >"<?php echo date('Y'); ?>"){
                            alert("<?php echo $LDEnternammangthai.' '; ?>"+(i+1)+" <?php echo $LDWarningQuestion2.' '.$LDYearNow;?>");
                            document.getElementById(name+i).value='';
                            window.setTimeout(function () { document.getElementById(name+i).focus(); }, 0);
                            return false;
                        } 
                        break;
                    case "cannang":
                        if(isNaN(document.getElementById(name+i).value) || document.getElementById(name+i).value.indexOf(".")>0){
                            alert("<?php echo $LDEntercannang.' '; ?>"+(i+1)+" <?php echo $LDWarning;?>");                            
                            window.setTimeout(function () { document.getElementById(name+i).focus(); }, 0);
                            return false;
                        }                        
                        break;
                    case "phuongphapde":          
                        break;
                    default:
                        setValue_checkbox(name,value,i);                        
                        break;                    
                }
                               
                if(document.getElementById(name+i).value!=name_db[t] && document.getElementById(name+i).value!=''){
                    name_db[t]=document.getElementById(name+i).value+';';
                }else{
                    name_db[t]=';';
                }
            }else{
                if(!name_db[t] || typeof name_db[t]=='undefined'){
                    name_db[t]=';';
                }                    
                else
                    name_db[t]=name_db[t]+';';
            }
            t++;
        }
        t=0;
        var str='';
        while(t<name_db.length){
            str+=name_db[t];
            t++;
        }
        document.getElementById(name).value=str;
//        update(i);
        if(document.getElementById('say'+i).value==1 || document.getElementById('hut'+i).value==1 ||document.getElementById('nao'+i).value==1 ||document.getElementById('covac'+i).value==1  || document.getElementById('thaichet'+i).value==1){
            document.getElementById("cannang"+i).disabled=true;
            document.getElementById("phuongphapde"+i).disabled=true;
        }else{
            document.getElementById("cannang"+i).disabled=false;
            document.getElementById("phuongphapde"+i).disabled=false;
        }
    }
    
    function Set_uongvan(){
        var d = document.getElementById('report');
        var value=document.getElementById("uongvan").value;
        if(value==1){
            document.getElementById("uongvan").value=0;
            document.getElementById("duoctiem").disabled=true;
        }else{
            document.getElementById("uongvan").value=1;
            document.getElementById("duoctiem").disabled=false;
        }
        return true;
    }
    
    function checkTuoi(tuoi){
        var birth=<?php echo substr($date_birth,0,4); ?>;
        if(tuoi<=1){
            alert("<?php echo $LDEntertuoilaychong; ?>");
            window.setTimeout(function () { document.getElementById('tuoilaychong').focus(); }, 0);  
        }
        else{
            var warning="<?php echo $tuoilaychong; ?>";
            if(document.getElementById('tuoilaychong').value<=10){
                alert(warning);
                window.setTimeout(function () { document.getElementById('tuoilaychong').focus(); }, 0); 
                document.getElementById('tuoilaychong').value='';
                document.getElementById('namlaychong').value='';
                return false;
            }
            var ty = parseInt(birth)+parseInt(document.getElementById('tuoilaychong').value);
            if(ty <= birth){
                alert(warning);
                document.getElementById('tuoilaychong').value='';
                window.setTimeout(function () { document.getElementById('tuoilaychong').focus(); }, 0); 
                document.getElementById('namlaychong').value='';
                return false;
            }
            $("#namlaychong").val(ty);
            }
    } 
    
    function checkYear(year){
        var year_now=<?php echo date("Y"); ?>;
        var birth="<?php echo substr($date_birth,0,4); ?>";
        if( year>year_now || year<(birth-10) || year<=birth){
            alert("<?php echo $LDEnternamlaychong; ?>");
            window.setTimeout(function () { document.getElementById('namlaychong').focus(); }, 0);  
            document.getElementById('namlaychong').value='';
            document.getElementById('tuoilaychong').value='';
            
            return false;
        }
        else{
            var warning="<?php echo $laychong; ?>";
            var ty = parseInt(document.getElementById('namlaychong').value)-parseInt(birth);
            if(ty<=10){
                alert(warning);
                window.setTimeout(function () { document.getElementById('namlaychong').focus(); }, 0);  
                document.getElementById('namlaychong').value='';
                document.getElementById('tuoilaychong').value='';
                return false;
            }
            $("#tuoilaychong").val(ty);
        }
        return true;
    }
    
    function update(id){
        var i=0;
        var array=new Array("deduthang","dethieuthang","say","hut","nao","covac","chuangoai","chuatrung","thaichet","conhiensong","cannang","phuongphapde","taibien");
        while(i<array.length){
            if(document.getElementById('nammangthai'+id).value!=''){                
                document.getElementById(array[i]+id).disabled=false;
            }else{
                document.getElementById(array[i]+id).value='';
            }
            i++;
        }
    }
    
    function CheckValue_number(name,value){
        if(isNaN(document.getElementById(name).value)){
            alert("<?php echo $LD['sieuam'].' '.$LDNopass;?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            return false;
        }
        if(name=='tuoithai' && value>48){
            alert("<?php echo $LD['tuoithai'].' '.$LDNopass;?>");
            document.getElementById(name).value=value;
            window.setTimeout(function() { document.getElementById(name).focus(); },0);
            document.getElementById(name).value='';
            return false;
        }
        return true;
    } 
    
    function CheckTuoiTK(tuoitk){
        var d = document.getElementById('report');
        if(tuoitk>50){
            alert("<?php echo $LDEnterbatdauthaykinh; ?>");
            d.batdauthaykinh.value='';
            d.batdauthaykinh.focus();
            return false;
        }
    }
    
    $(function(){
        $("#gio_chuyenda").mask("**:**:**");
        $("#f-calendar-field-1").mask("**/**/****");
        $("#f-calendar-field-2").mask("**/**/****");
        $("#f-calendar-field-3").mask("**/**/****");
    });
</script>

<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
    $TP_Warning=$condition_press;
    //qua trinh thai ki
    $TP_LIST_SICK=$list_sick;
    $TP_HISTORY_SICK=$history_sick;
    $TP_HISTORY_OBS=$history_Obstetrics;
    $TP_HISTORY_GYN=$history_Gynecology;
    $TP_kinhcuoi_from=$LD['kinhcuoitu'];
    $TP_kinhcuoi_to=$LD['kinhcuoiden'];    
    $TP_INPUT_DATE_FROM= $calendar->show_calendar($calendar,$date_format,'kinhcuoitu',$history_question['kinhcuoitu']);
    $TP_INPUT_DATE_TO= $calendar->show_calendar($calendar,$date_format,'kinhcuoiden',$history_question['kinhcuoiden']);
    $TP_tuoithai=$LD['tuoithai'];
    if($history_question['tuoithai']) $TP_tuoithai_PNR=$history_question['tuoithai'];
    $TP_khamthai_place=$LD['noikhamthai'];
    if($history_question['noikhamthai']) $TP_khamthai=$history_question['noikhamthai'];
    $TP_tiemuongvan=$LD['uongvan'];
        
    $TP_tiemuongvan_input='<input type="checkbox" id="uongvan" name="uongvan" value="';
    if($history_question['uongvan']){
        $TP_tiemuongvan_input.=$history_question['uongvan'].'" checked';  
        $disabled='';        
    }else{        
        $TP_tiemuongvan_input.='"';  
        $disabled='disabled="disabled"';
    }
    $TP_tiemuongvan_input.=' onchange="Set_uongvan()"/>';
    $TP_duoctiem="<table border=0 cellpadding=2><tr bgcolor='#f6f6f6'><td><FONT SIZE=-1  FACE='Arial' color='#000066'>".$LD['duoctiem']."</FONT></td>";
    $TP_duoctiem.="<td><input type='text' name='duoctiem' id='duoctiem' size=10 value='";
    if($history_question['duoctiem']) 
        $TP_duoctiem.=$history_question['duoctiem']."' $disabled/>&nbsp;&nbsp;$LDUseTimes</td></tr></table>";
    else
        $TP_duoctiem.="' $disabled/>&nbsp;&nbsp;$LDUseTimes</td></tr></table>";
    $TP_chuyendaluc=$LD['gio_chuyenda'];
    $TP_IMG_PDATE = $calendar->show_calendar($calendar,$date_format,'ngaychuyenda',$history_question['ngaychuyenda']);
    if($history_question['gio_chuyenda']) $TP_PTIME=$history_question['gio_chuyenda'];
    else $TP_PTIME=date('H:i:s');
    $TP_dauhieu_bandau=$LD['dauhieulucdau'];
    if($history_question['dauhieulucdau']) $TP_dauhieu=$history_question['dauhieulucdau'];
    $TP_bienchuyen=$LD['bienchuyen'];
    if($history_question['bienchuyen']) $TP_bienchuyen_input=$history_question['bienchuyen'];
    $TP_sieuam=$LD['sieuam'];
    if($history_question['sieuam']) 
        $TP_sieuam_input=$history_question['sieuam'];
    else
        $TP_sieuam_input=0;
    
    //tien su benh
    require_once($root_path.'include/care_api_classes/class_person.php');
    $person= new Person();
    $info= $person->getAllInfoArray($_SESSION['sess_pid']);
    $TP_canhan=$info['tiensubenhcanhan'];
    $TP_giadinh=$info['tiensubenhgiadinh'];
    
    //tien su phu khoa
    $TP_batdauthaykinh=$LD['batdauthaykinh'];
    if($history_question['batdauthaykinh']) $TP_thaykinh_input=$history_question['batdauthaykinh'];
    $TP_tinhchatkinh=$LD['tinhchatkinh'];
    if($history_question['tinhchatkinh']) $TP_tinhchat_input=$history_question['tinhchatkinh'];
    $TP_chuki=$LD['chuki'];
    if($history_question['chuki']) $TP_chuki_input=$history_question['chuki'];
    $TP_luongkinh=$LD['luongkinh'];
    if($history_question['luongkinh']) $TP_luongkinh_input=$history_question['luongkinh'];
    $TP_laychongnam=$LD['namlaychong'];
    if($history_question['namlaychong']) $TP_laychongnam_input=$history_question['namlaychong'];
    $TP_tuoilaychong=$LD['tuoilaychong'];
    if($history_question['tuoilaychong']) $TP_tuoilaychong_input=$history_question['tuoilaychong'];
    $TP_benhphukhoa=$LD['benhphukhoa'];
    if($history_question['benhphukhoa']) $TP_benhphukhoa_input=$history_question['benhphukhoa'];
    
    //tien su san khoa
    $TP_lanmangthai=$LD['lanmangthai'];
    $TP_nam=$LD['nammangthai'];    
    $TP_duthang=$LD['deduthang'];    
    $TP_thieuthang=$LD['dethieuthang'];    
    $TP_say=$LD['say'];
    $TP_hut=$LD['hut'];
    $TP_nao=$LD['nao'];
    $TP_covac=$LD['covac'];
    $TP_chuangoai=$LD['chuangoai'];
    $TP_chuatrung=$LD['chuatrung'];
    $TP_thaichet=$LD['thaichet'];
    $TP_conhiensong=$LD['conhiensong'];
    $TP_cannang=$LD['cannang'];
    $TP_phuongphapde=$LD['phuongphapde'];
    $TP_taibien=$LD['taibien'];
    $TP_DOCBY=$LD['docu_by'];
    $TP_DBY=$_SESSION['sess_user_name'];
    $TP_WHILE='';
    $i=0;
    $nammangthai=explode(";", $history_question['nammangthai']);
    $deduthang=explode(";", $history_question['deduthang']);
    $dethieuthang=explode(";", $history_question['dethieuthang']);
    $say=explode(";", $history_question['say']);
    $hut=explode(";", $history_question['hut']);
    $nao=explode(";", $history_question['nao']);
    $covac=explode(";", $history_question['covac']);
    $chuangoai=explode(";", $history_question['chuangoai']);
    $chuatrung=explode(";", $history_question['chuatrung']);
    $thaichet=explode(";", $history_question['thaichet']);
    $conhiensong=explode(";", $history_question['conhiensong']);
    $cannang=explode(";", $history_question['cannang']);
    $phuongphapde=explode(";", $history_question['phuongphapde']);
    $taibien=explode(";", $history_question['taibien']);
    while($i<6){
        $TP_WHILE.='<tr>';
        $TP_WHILE.='<td bgcolor="#ffffff" align="center">'.($i+1).'</td>';
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="text" name="nammangthai'.$i.'" id="nammangthai'.$i.'" size="5" value="';
        if($nammangthai[$i]) $TP_WHILE.=$nammangthai[$i];
        $TP_WHILE.='" onchange=settable_history("nammangthai",'.$i.') /></td>';
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="deduthang'.$i.'" name="deduthang'.$i.'" value="';
        if($deduthang[$i]) 
            $TP_WHILE.=$deduthang[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("deduthang",'.$i.') /></td>';        
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="dethieuthang'.$i.'" name="dethieuthang'.$i.'" value="';
        if($dethieuthang[$i]) 
            $TP_WHILE.=$dethieuthang[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("dethieuthang",'.$i.') /></td>';   
         
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="say'.$i.'" name="say'.$i.'" value="';
        if($say[$i]) 
            $TP_WHILE.=$say[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("say",'.$i.') /></td>'; 
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="hut'.$i.'" name="hut'.$i.'" value="';
        if($hut[$i]) 
            $TP_WHILE.=$hut[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("hut",'.$i.') /></td>'; 
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="nao'.$i.'" name="nao'.$i.'" value="';
        if($nao[$i]) 
            $TP_WHILE.=$nao[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("nao",'.$i.') /></td>'; 
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="covac'.$i.'" name="covac'.$i.'" value="';
        if($covac[$i]) 
            $TP_WHILE.=$covac[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("covac",'.$i.') /></td>'; 
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="chuangoai'.$i.'" name="chuangoai'.$i.'" value="';
        if($chuangoai[$i])
            $TP_WHILE.=$chuangoai[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("chuangoai",'.$i.') /></td>';
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="chuatrung'.$i.'" name="chuatrung'.$i.'" value="';
        if($chuatrung[$i]) 
            $TP_WHILE.=$chuatrung[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("chuatrung",'.$i.') /></td>';
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="thaichet'.$i.'" name="thaichet'.$i.'" value="';
        if($thaichet[$i]) 
            $TP_WHILE.=$thaichet[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("thaichet",'.$i.') /></td>'; 
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="conhiensong'.$i.'" name="conhiensong'.$i.'" value="';
        if($conhiensong[$i]) 
            $TP_WHILE.=$conhiensong[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("conhiensong",'.$i.') /></td>';
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center" width="15%"><input type="text" disable name="cannang'.$i.'" id="cannang'.$i.'" size="5" value="';
        if($cannang[$i]) $TP_WHILE.=$cannang[$i];
        if(!$nammangthai[$i] || $say[$i] || $hut[$i] || $nao[$i] || $covac[$i] || $thaichet[$i])
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("cannang",'.$i.') />gram</td>';
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="text" name="phuongphapde'.$i.'" id="phuongphapde'.$i.'" size="15" value="';
        if($phuongphapde[$i]) 
            $TP_WHILE.=$phuongphapde[$i];
        if(!$nammangthai[$i] || $say[$i] || $hut[$i] || $covac[$i] || $nao[$i] || $thaichet[$i])
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("phuongphapde",'.$i.') /></td>';
        
        $TP_WHILE.='<td bgcolor="#ffffff" align="center"><input type="checkbox" id="taibien'.$i.'" name="taibien'.$i.'" value="';
        if($taibien[$i]) 
            $TP_WHILE.=$taibien[$i].'" checked';
        else if(!$nammangthai[$i]) 
            $TP_WHILE.='" ';
        else
            $TP_WHILE.='"';
        $TP_WHILE.=' onchange=settable_history("taibien",'.$i.') /></td>';
        
        $TP_WHILE.='</tr>';
        $i++;
    }
    $TP_WHILE.='<input type="hidden" name="nammangthai" id="nammangthai" value="'.$history_question['nammangthai'].'"/>';
    $TP_WHILE.='<input type="hidden" name="deduthang" id="deduthang" value="'.$history_question['deduthang'].'"/>';
    $TP_WHILE.='<input type="hidden" name="dethieuthang" id="dethieuthang" value="'.$history_question['dethieuthang'].'"/>';
    $TP_WHILE.='<input type="hidden" name="say" id="say" value="'.$history_question['say'].'"/>';
    $TP_WHILE.='<input type="hidden" name="hut" id="hut" value="'.$history_question['hut'].'"/>';
    $TP_WHILE.='<input type="hidden" name="nao" id="nao" value="'.$history_question['nao'].'"/>';
    $TP_WHILE.='<input type="hidden" name="covac" id="covac" value="'.$history_question['covac'].'"/>';
    $TP_WHILE.='<input type="hidden" name="chuangoai" id="chuangoai" value="'.$history_question['chuangoai'].'"/>';
    $TP_WHILE.='<input type="hidden" name="chuatrung" id="chuatrung" value="'.$history_question['chuatrung'].'"/>';
    $TP_WHILE.='<input type="hidden" name="thaichet" id="thaichet" value="'.$history_question['thaichet'].'"/>';
    $TP_WHILE.='<input type="hidden" name="conhiensong" id="conhiensong" value="'.$history_question['conhiensong'].'"/>';
    $TP_WHILE.='<input type="hidden" name="cannang" id="cannang" value="'.$history_question['cannang'].'"/>';
    $TP_WHILE.='<input type="hidden" name="phuongphapde" id="phuongphapde" value="'.$history_question['phuongphapde'].'"/>';
    $TP_WHILE.='<input type="hidden" name="taibien" id="taibien" value="'.$history_question['taibien'].'"/>';
    
    
    $san='<table class="submenu_frame" width="100%">';
    $san.='<tbody class="submenu><tr><td><table  width="100%">';
    $san.='<tr bgcolor="#f6f6f6"><td><FONT SIZE=-1  FACE=Arial color=#000066>'.$TP_khamthai_place.'</FONT></td>';
    $san.='<td  colspan="14"><input type="text" name="noikhamthai" id="noikhamthai" size=100 maxlength=100 value="'.$TP_khamthai.'" /></td></tr>';
    $san.='<tr bgcolor="#f6f6f6"><td><FONT SIZE=-1  FACE="Arial" color="#000066">'.$TP_tiemuongvan.'</FONT>'.$TP_tiemuongvan_input.'</td>';
    $san.='<td  colspan="14"><table border=0 cellpadding=2><tr bgcolor="#f6f6f6"><td><FONT SIZE=-1  FACE="Arial" color="#000066">'.$TP_duoctiem.'</FONT></td>';
    $san.='<td><input type="text" name="duoctiem" id="duoctiem" size=10 value="'.$TP_duoctiem_input.'" />&nbsp;&nbsp;'.$LDUseTimes.'</td></tr></table></td></tr>';
    $san.='<tr bgcolor="#f6f6f6">';
    $san.='<td colspan="15">';
    $san.='<FONT SIZE=3  FACE="Arial" color="#000066"><b>4.';
    $san.=$TP_HISTORY_OBS.':</b></FONT></td></tr>';
    $san.='<tr><td align="center" width="5%">'.$TP_lanmangthai.'</td>';
    $san.='<td align="center">'.$TP_nam.'</td>';
    $san.='<td align="center">'.$TP_duthang.'</td>';
    $san.='<td align="center">'.$TP_thieuthang.'</td>';
    $san.='<td align="center" width="5%">'.$TP_say.'</td>';
    $san.='<td align="center" width="5%">'.$TP_hut.'</td>';
    $san.='<td align="center" width="5%">'.$TP_nao.'</td>';
    $san.='<td align="center">'.$TP_covac.'</td>';
    $san.='<td align="center">'.$TP_chuangoai.'</td>';
    $san.='<td align="center">'.$TP_chuatrung.'</td>';
    $san.='<td align="center">'.$TP_thaichet.'</td>';
    $san.='<td align="center">'.$TP_conhiensong.'</td>';
    $san.='<td align="center">'.$TP_cannang.'</td>';
    $san.='<td align="center">'.$TP_phuongphapde.'</td>';
    $san.='<td align="center">'.$TP_taibien.'</td></tr>';
    $san.=$TP_WHILE.'</tbody></table>';
    
    $TP_other=$LD['khac'];
    if($history_question['khac']) $TP_khac_input=$history_question['khac'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_pregnancy_question.htm');
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

