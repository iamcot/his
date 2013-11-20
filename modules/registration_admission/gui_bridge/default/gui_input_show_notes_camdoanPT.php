<?php
    if($date && $time){
        $cond="AND date='$date' AND time='$time'";
    }
    $name_camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=60");
    if($name_camdoan){
        $name=$name_camdoan->FetchRow();
    }
    $tuoi_camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=61");
    if($tuoi_camdoan){
        $tuoi_cd=$tuoi_camdoan->FetchRow();
    }
    $dantoc_camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=62");
    if($dantoc_camdoan){
        $dantoc=$dantoc_camdoan->FetchRow();
    }
    $ngoaikieu_camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=63");
    if($ngoaikieu_camdoan){
        $ngoaikieu=$ngoaikieu_camdoan->FetchRow();
    }
    $nghenghiep_camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=64");
    if($nghenghiep_camdoan){
        $nghenghiep_cd=$nghenghiep_camdoan->FetchRow();
    }
    $diachi_camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=65");
    if($diachi_camdoan){
        $diachi=$diachi_camdoan->FetchRow();
    }
    $camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=66");
    if($camdoan){
        $pt=$camdoan->FetchRow();
    }
    $gioitinh_camdoan=$obj->_getNotes("encounter_nr='$current_encounter' $cond AND type_nr=67");
    if($gioitinh_camdoan){
        $sex_cd=$gioitinh_camdoan->FetchRow();
    }
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<form method="post" name="aufnahmeform" id="aufnahmeform" onSubmit="return(chkform())">
<?php
    $TP_NGUOICAMDOAN=$LDNguoicamdoan;
    if($name['notes'])
        $TP_NGUOICAMDOAN_INPUT=$name['notes'];
    $TP_TUOI=$LDTuoi;
    if($tuoi_cd['notes'])
        $TP_TUOI_INPUT=$tuoi_cd['notes'];
    $TP_GIOITINH=$LDSex;
	$TP_SEX_M='<input type="radio" name="sex" value="1" ';
	if($sex_cd['notes']==1) $TP_SEX_M.='checked';
	$TP_SEX_M.='>'.$LDMale;
	$TP_SEX_FM='<input type="radio" name="sex" value="2" ';
	if($sex_cd['notes']==2) $TP_SEX_FM.='checked';
	$TP_SEX_FM.='>'.$LDFemale;
	$TP_SEX_KR='<input type="radio" name="sex" value="0" ';
	if($sex_cd['notes']==0) $TP_SEX_KR.='checked';
	$TP_SEX_KR.='>'.$LDKhongRo;
    $TP_DANTOC=$LDEthnicOrigin;
    if($dantoc['notes']){
        $TP_DANTOC_INPUT=$dantoc['notes'];
    }else{
		$TP_DANTOC_INPUT='Kinh';
	}
	
	$TP_NGOAIKIEU=$LDNgoaiKieu;
    if($ngoaikieu['notes']){
        $TP_NGOAIKIEU_INPUT=$ngoaikieu['notes'];
    } 
    $TP_NGHENGHIEP=$LDnghenghiep;
    if($nghenghiep_cd['notes']){
        $TP_NGHENGHIEP_INPUT=$nghenghiep_cd['notes'];
    } 
	$TP_DIACHI=$LDAddress;
    if($diachi['notes']){
        $TP_DIACHI_INPUT=$diachi['notes'];
    } 
	$TP_PT=$LDPT;
	$TP_PT_Y='<input type="radio" name="camdoan" value="1" ';
	if($pt['notes']==1) $TP_PT_Y.='checked';
	$TP_PT_Y.='>'.$LDYes;
	$TP_PT_N='<input type="radio" name="camdoan" value="0" ';
	if($pt['notes']==0) $TP_PT_N.='checked';
	$TP_PT_N.='>'.$LDNo;
    $TP_DOCBY=$LDDocBy;    
    $TP_DBY=$_SESSION['sess_user_name'];
    # Load the template
    $tp_preg=&$TP_obj->load('registration_admission/tp_input_show_notes_camdoanPT.htm');
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
        var d = document.getElementById('aufnahmeform'); 
        if( d.nguoicamdoan.value==''){
            alert("<?php echo $LDAlert_name; ?>");
            d.nguoicamdoan.focus();
            return false;
        }else if(isNaN(d.tuoinguoicamdoan.value) || d.tuoinguoicamdoan.value<18){
            alert("<?php echo $LDAlert_tuoi; ?>");
            d.tuoinguoicamdoan.focus();
            return false;
        }else if(d.text_camdoan.value==""){
            alert("<?php echo $Alert_camdoan; ?>");
            d.text_camdoan.focus();
            return false;            
        }else{
            return true;
        }
    }
	
	
	function popSearchWin(target,obj_val,obj_name) {
		urlholder="./data_search.php?sid=ac3600523592c8f866492f9fbb80c075&lang=vi&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
		DSWINac3600523592c8f866492f9fbb80c075=window.open(urlholder,"wblabelac3600523592c8f866492f9fbb80c075","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
	}

</script>

