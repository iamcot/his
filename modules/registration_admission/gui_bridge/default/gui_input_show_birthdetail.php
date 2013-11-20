<style type="text/css">

    input.text {
        font-size: 12px;
        color: darkred;
    }

</style> 
<?php 
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    require($root_path.'classes/datetimemanager/checktime.php'); 
?>
<script language="JavaScript">
<!-- Script Begin
    function popClassification() {
	urlholder="./neonatal_classifications.php<?php echo URL_REDIRECT_APPEND; ?>";
	CLASSWIN<?php echo $sid ?>=window.open(urlholder,"CLASSWIN<?php echo $sid ?>","menubar=no,width=300,height=450,resizable=yes,scrollbars=yes");

    }
    function clearClassification() {
	document.report.classification.value="";
	document.report.classification.focus();
    }

    function chkform(){
        var d = document.getElementById('report');
        if(d.sex.value==""){
		d.sex.value='f';
	}else if(d.date.value==""){
		alert("<?php echo $LDPlsEnterDeliveryDate; ?>");
		return false;
	}else if(d.delivery_time.value=="" || d.delivery_time.value=="00:00:00" || d.delivery_time.value==0 || d.delivery_time.value=='00:00'){
		alert("<?php echo $LDPlsEnterDeliveryTime; ?>");
		d.delivery_time.focus();
		return false;
	}else if(isNaN(d.weight.value)){
		d.length.focus(); // patch for Konqueror
		alert("<?php echo $LDEntryInvalidChar4; ?>");
		d.weight.focus();
		return false;
	}else if(d.weight.value<0){
		d.length.focus(); // patch for Konqueror
		alert("<?php echo $LDNotNegValue; ?>");
		d.weight.focus();
		return false;
	}else if(isNaN(d.length.value)){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDEntryInvalidChar5; ?>");
		d.length.focus();
		return false;
	}else if(d.length.value<0){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDNotNegValue1; ?>");
		d.length.focus();
		return false;
	}else if(isNaN(d.head_circumference.value)){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDEntryInvalidChar6; ?>");
		d.head_circumference.focus();
		return false;
	}else if(d.head_circumference.value<0){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDNotNegValue2; ?>");
		d.head_circumference.focus();
		return false;
	}else if(d.docu_by.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.docu_by.focus();
		return false;
	}else{
            return true;
	}
    }

    $(function(){
        $("#delivery_time").mask("**:**:**");
        $("#f-calendar-field-1").mask("**/**/****");
    });
    
//  Script End -->
</script>
<form method="post" name="report" id="report" onSubmit="return(chkform())">
<?php
$TP_Warning=$condition_press;
# Parent's (mother's) encounter nr.
$TP_PARENT_ENR= $LD['parent_encounter_nr'];

if($birth_update['parent_encounter_nr']) $TP_PARENT_ENR_NR= $birth_update['parent_encounter_nr'];
else $TP_PARENT_ENR_NR = $_SESSION['sess_en'];

# Delivery nr.
$TP_DELIV_NR=$LD['delivery_nr'];

if($birth_update['delivery_nr']) $TP_DELIV_PARA_NR= $birth_update['delivery_nr'];
else $TP_DELIV_PARA_NR=$para;


//
//if($birth_update['sex']=='m') 
//    $TP_SEX_M.=$birth_update['sex'].'" checked';
//else
//    $TP_SEX_M.='"';
//$TP_SEX_M.='>'.$LDTrai;
//$TP_SEX_FM='<input type="radio" name="sex" value="';
//if($birth_update['sex']=='f') 
//    $TP_SEX_FM.=$birth_update['sex'].'" checked';
//else
//    $TP_SEX_FM.='"';
//$TP_SEX_FM.='>'.$LDGai;
$TP_SEX=$LD['sex'];
$TP_SEX_M='<input type="radio" name="sex" value="1" ';
if($birth_update['sex']==1) $TP_SEX_M.='checked';
$TP_SEX_M.='>'.$LDTrai;
$TP_SEX_FM='<input type="radio" name="sex" value="2" ';
if($birth_update['sex']==2) $TP_SEX_FM.='checked';
$TP_SEX_FM.='>'.$LDGai;
# Delivery place
$TP_DELIV_PLACE=$LD['delivery_place'];
$TP_DPLACE=$birth_update['delivery_place']; 

# Delivery mode
$TP_DELIV_MODE=$LD['delivery_mode'];

if(!$birth_update['delivery_mode']) $birth_update['delivery_mode']=1;  # 1= Normal delivery
# Delivery mode radio buttons
$TP_DMODE_RADIOS='';
$dm=&$obj->DeliveryModes();
if($obj->LastRecordCount()){
    while($dmod=$dm->FetchRow()){
            $TP_DMODE_RADIOS.='<input type="radio" name="delivery_mode" value="'.$dmod['nr'].'" ';
            if($birth_update['delivery_mode']==$dmod['nr']) $TP_DMODE_RADIOS.='checked' ;
            $TP_DMODE_RADIOS.='>';
            if(isset($$dmod['LD_var']) && $$dmod['LD_var']) $TP_DMODE_RADIOS.=$$dmod['LD_var'];
                    else $TP_DMODE_RADIOS.=$dmod['name'];
    }
}

# Ceasarean reason
$TP_CES_REASON=$LD['c_s_reason'];
$TP_CREASON=$birth_update['c_s_reason'];

$TP_IMG_PDATE = $calendar->show_calendar($calendar,$date_format,'date',$birth_update['date']);
# Delivery time
if($birth_update['delivery_time']) $TP_PTIME=$birth_update['delivery_time'];
else $TP_PTIME=date('H:i:s');

# Born before arrival
$TP_BB_ARRIVAL=$LD['born_before_arrival'];
$TP_BB_AR_YES='<input type="radio" name="born_before_arrival" value="1" ';
if($birth_update['born_before_arrival']) $TP_BB_AR_YES.='checked';
$TP_BB_AR_YES.='>'.$LDYes_s;
$TP_BB_AR_NO='<input type="radio" name="born_before_arrival" value="0" ';
if(!$birth_update['born_before_arrival']) $TP_BB_AR_NO.='checked';
$TP_BB_AR_NO.='>'.$LDNo;

# Face presentation
$TP_FACE_PRES=$LD['face_presentation'];

if(!isset($birth_update['face_presentation'])) $birth_update['face_presentation']=1;
$TP_FACE_PRES_YES='<input type="radio" name="face_presentation" value="1" ';
if($birth_update['face_presentation']) $TP_FACE_PRES_YES.='checked';
$TP_FACE_PRES_YES.='>'.$LDYes_s;

$TP_FACE_PRES_NO='<input type="radio" name="face_presentation" value="0" ';
if(!$birth_update['face_presentation']) $TP_FACE_PRES_NO.='checked';
$TP_FACE_PRES_NO.='>'.$LDNo;

# Posterio -occipital position
$TP_POS_OCCI=$LD['posterio_occipital_position'];
$TP_POS_OCCI_YES='<input type="radio" name="posterio_occipital_position" value="1" ';
if($birth_update['posterio_occipital_position']) $TP_POS_OCCI_YES.='checked';
$TP_POS_OCCI_YES.='>'.$LDYes_s;
$TP_POS_OCCI_NO='<input type="radio" name="posterio_occipital_position" value="0" ';
if(!$birth_update['posterio_occipital_position']) $TP_POS_OCCI_NO.='checked';
$TP_POS_OCCI_NO.='>'.$LDNo;

# Delivery rank
$TP_DELIV_RANK=$LD['duoctiem'];
if($birth_update['delivery_rank']) $TP_DRANK=$birth_update['delivery_rank'];

# Apgar item names
$TP_APGAR1=$LD['apgar_1_min'];
if(!isset($birth_update['apgar_1_min'])) $birth_update['apgar_1_min']=-1;
$TP_APGAR5=$LD['apgar_5_min'];
if(!isset($birth_update['apgar_5_min'])) $birth_update['apgar_5_min']=-1;
$TP_APGAR10=$LD['apgar_10_min'];
if(!isset($birth_update['apgar_10_min'])) $birth_update['apgar_10_min']=-1;

# Apgar 1 min - radio buttons
$TP_APGAR1_RADIOS='<input type="radio" name="apgar_1_min" value="0" ';
if($birth_update['apgar_1_min']==0) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>0
            <input type="radio" name="apgar_1_min" value="1" ';
if($birth_update['apgar_1_min']==1) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>1
    <input type="radio" name="apgar_1_min" value="2" ';
if($birth_update['apgar_1_min']==2) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>2
    <input type="radio" name="apgar_1_min" value="3" ';
if($birth_update['apgar_1_min']==3) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>3
    <input type="radio" name="apgar_1_min" value="4" ';
if($birth_update['apgar_1_min']==4) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>4
    <input type="radio" name="apgar_1_min" value="5" ';
if($birth_update['apgar_1_min']==5) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>5
            <input type="radio" name="apgar_1_min" value="6" ';
if($birth_update['apgar_1_min']==6) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>6
            <input type="radio" name="apgar_1_min" value="7" ';
if($birth_update['apgar_1_min']==7) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>7
    <input type="radio" name="apgar_1_min" value="8" ';
if($birth_update['apgar_1_min']==8) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>8
    <input type="radio" name="apgar_1_min" value="9" ';
if($birth_update['apgar_1_min']==9) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>9
    <input type="radio" name="apgar_1_min" value="10" ';
if($birth_update['apgar_1_min']==10) $TP_APGAR1_RADIOS.='checked';
$TP_APGAR1_RADIOS.='>10';

# Apgar 5 min radio buttons
$TP_APGAR5_RADIOS='<input type="radio" name="apgar_5_min" value="0" ';
if($birth_update['apgar_5_min']==0) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>0
            <input type="radio" name="apgar_5_min" value="1" ';
if($birth_update['apgar_5_min']==1) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>1
    <input type="radio" name="apgar_5_min" value="2" ';
if($birth_update['apgar_5_min']==2) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>2
    <input type="radio" name="apgar_5_min" value="3" ';
if($birth_update['apgar_5_min']==3) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>3
    <input type="radio" name="apgar_5_min" value="4" ';
if($birth_update['apgar_5_min']==4) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>4
    <input type="radio" name="apgar_5_min" value="5" ';
if($birth_update['apgar_5_min']==5) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>5
            <input type="radio" name="apgar_5_min" value="6" ';
if($birth_update['apgar_5_min']==6) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>6
            <input type="radio" name="apgar_5_min" value="7" ';
if($birth_update['apgar_5_min']==7) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>7
    <input type="radio" name="apgar_5_min" value="8" ';
if($birth_update['apgar_5_min']==8) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>8
    <input type="radio" name="apgar_5_min" value="9" ';
if($birth_update['apgar_5_min']==9) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>9
    <input type="radio" name="apgar_5_min" value="10" ';
if($birth_update['apgar_5_min']==10) $TP_APGAR5_RADIOS.='checked';
$TP_APGAR5_RADIOS.='>10';

# Apgar 10 mins radio buttons
$TP_APGAR10_RADIOS='<input type="radio" name="apgar_10_min" value="0" ';
if($birth_update['apgar_10_min']==0) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>0
            <input type="radio" name="apgar_10_min" value="1" ';
if($birth_update['apgar_10_min']==1) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>1
    <input type="radio" name="apgar_10_min" value="2" ';
if($birth_update['apgar_10_min']==2) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>2
    <input type="radio" name="apgar_10_min" value="3" ';
if($birth_update['apgar_10_min']==3) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>3
    <input type="radio" name="apgar_10_min" value="4" ';
if($birth_update['apgar_10_min']==4) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>4
    <input type="radio" name="apgar_10_min" value="5" ';
if($birth_update['apgar_10_min']==5) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>5
            <input type="radio" name="apgar_10_min" value="6" ';
if($birth_update['apgar_10_min']==6) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>6
            <input type="radio" name="apgar_10_min" value="7" ';
if($birth_update['apgar_10_min']==7) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>7
    <input type="radio" name="apgar_10_min" value="8" ';
if($birth_update['apgar_10_min']==8) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>8
    <input type="radio" name="apgar_10_min" value="9" ';
if($birth_update['apgar_10_min']==9) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>9
    <input type="radio" name="apgar_10_min" value="10" ';
if($birth_update['apgar_10_min']==10) $TP_APGAR10_RADIOS.='checked';
$TP_APGAR10_RADIOS.='>10';

# Time to spontan respiration
$TP_SPONTANRESP=$LD['time_to_spont_resp'];
if($birth_update['time_to_spont_resp']) $TP_SP_RESP=$birth_update['time_to_spont_resp'];

$TP_CONDITION=$LD['condition'];
$TP_COND=$birth_update['condition'];
$TP_WEIGHT=$LD['weight'];
if($birth_update['weight']>0) $TP_WT=$birth_update['weight'];
$TP_LENGTH=$LD['length'];
if($birth_update['length']>0) $TP_LEN=$birth_update['length'];
$TP_HEAD_CIRC=$LD['head_circumference'];
if($birth_update['head_circumference']>0) $TP_HCIRC=$birth_update['head_circumference'];
# congenital abnormality 
$TP_CONG_ABNORM=$LD['congenital_abnormality'];
if($birth_update['scored_gestational_disability']) $TP_GAGE=$birth_update['scored_gestational_disability'];

$TP_CHR_BODY_T='<input type="checkbox" name="tatbamsinh" value="1" ';
if($birth_update['tatbamsinh']) $TP_CHR_BODY_T.='checked';
$TP_CHR_BODY_T.='>'.$characteristics_body_t;
$TP_CHRBODY_C='<input type="checkbox" name="cohaumon" value="1" ';
if($birth_update['cohaumon']) $TP_CHRBODY_C.='checked';
$TP_CHRBODY_C.='>'.$characteristics_body_c;

$TP_FEEDING=$LD['feeding'];

# Feeding, set default to "breast" = type #1
if(!isset($birth_update['feeding'])||!$birth_update['feeding']) $birth_update['feeding']=1;
# Feeding radio buttons
$TP_FEED_RADIOS='';
$fd=&$obj->FeedingTypes();
if($obj->LastRecordCount()){
    while($feed=$fd->FetchRow()){
            $TP_FEED_RADIOS.='<input type="radio" name="feeding" value="'.$feed['nr'].'" ';
            if($birth_update['feeding']==$feed['nr']) $TP_FEED_RADIOS.='checked' ;
            $TP_FEED_RADIOS.='>';
            if(isset($$feed['LD_var']) && $$feed['LD_var']) $TP_FEED_RADIOS.=$$feed['LD_var'];
                    else $TP_FEED_RADIOS.=$feed['name'];
    }
}

# Classification 
$TP_CLASSIFICATION=$LD['classification'];
$TP_CLASSIF='';
if(!empty($birth_update['classification'])) $TP_CLASSIF=$birth_update['classification'];
# Image buttons for javascript activation
$TP_IMG_ADD=createLDImgSrc($root_path,'add_sm.gif','0');
$TP_IMG_CLEAR=createLDImgSrc($root_path,'clearall_sm.gif','0');

# Outcome
if(!$birth_update['outcome']) $birth_update['outcome']=1; # 1 = living
$TP_OUTCOME=$LD['outcome'];
# Outcome radio buttons
$TP_OUT_RADIOS='';
$oc=&$obj->Outcomes();
if($obj->LastRecordCount()){
    while($otc=$oc->FetchRow()){
            $TP_OUT_RADIOS.='<input type="radio" name="outcome" value="'.$otc['nr'].'" ';
            if($birth_update['outcome']==$otc['nr']) $TP_OUT_RADIOS.='checked' ;
            $TP_OUT_RADIOS.='>';
            if(isset($$otc['LD_var']) && $$otc['LD_var']) $TP_OUT_RADIOS.=$$otc['LD_var'];
                    else $TP_OUT_RADIOS.=$otc['name'];
    }
}
# Disease categories
$TP_DIS_CAT=$LD['disease_category'];
# Disease category radio buttons
$TP_DISCAT_RADIOS='';
$dc=&$obj->DiseaseCategories();
if($obj->LastRecordCount()){
    while($dcat=$dc->FetchRow()){
            $TP_DISCAT_RADIOS.='<input type="radio" name="disease_category" id="disease_category" value="'.$dcat['nr'].'" ';
            if($birth_update['disease_category']==$dcat['nr']) $TP_DISCAT_RADIOS.='checked' ;
            $TP_DISCAT_RADIOS.='>';
            if(isset($$dcat['LD_var']) && $$dcat['LD_var']) $TP_DISCAT_RADIOS.=$$dcat['LD_var'];
                    else $TP_DISCAT_RADIOS.=$dcat['name'];
    }
}
# Documented by
$TP_DOCBY=$LD['docu_by'];
$TP_DBY=$_SESSION['sess_user_name'];

# Load the template
$tp_birth=$TP_obj->load('registration_admission/tp_input_show_birthdetail.htm');
eval("echo $tp_birth;");
?>

<input type="hidden" name="nr" value="<?php echo $nr; ?>" />
<input type="hidden" name="sid" value="<?php echo $sid; ?>" />
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>"/>
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>" />
<input type="hidden" name="allow_update" value="<?php if(isset($allow_update)) echo $allow_update; ?>" />
<input type="hidden" name="target" value="<?php echo $target; ?>" />
<input type="hidden" name="delivery_date" value="<?php echo $date_birth; ?>" />
<input type="hidden" name="mode" value="newdata" />
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?> />

</form>
