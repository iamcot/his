<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* , elpidio@care2x.org
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables[]='prompt.php';
$lang_tables[]='emr.php';
define('LANG_FILE','nursing.php');
$local_user='ck_pflege_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'global_conf/inc_remoteservers_conf.php');
//$db->debug=true;
if(!$encoder) $encoder=$_COOKIE[$local_user.$sid];

$breakfile="nursing-station.php".URL_APPEND."&edit=1&station=$station&ward_nr=$ward_nr";
$thisfile=basename(__FILE__);
//echo $thisfile;
# Load date formatter
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;
if( $enc_obj->loadEncounterData($pn)) {
	if(($mode=='release')&&!(isset($lock)||$lock)){
		$date=(empty($x_date))?date('Y-m-d'):formatDate2STD($x_date,$date_format);
		$time=(empty($x_time))?date('H:i'):convertTimeToStandard($x_time);
		//var_dump($discharged_type);
		//if($discharged_type==4){
		//	$released=$enc_obj->Xinve($pn,$discharged_type,$date,$time);
		//}	
		//else 
			$released=$enc_obj->Discharge($pn,'',$discharged_type,$date,$time);
		//var_dump($released);
		if($discharged_type==6){
			# If patient died				
				include_once($root_path.'include/care_api_classes/class_person.php');
				$person=new Person;
				$death['death_date']=$date;
				$death['death_encounter_nr']=$pn;
				$death['history']=$enc_obj->ConcatHistory("Discharged (cause: death) ".$date.' '.$time." $encoder\n");
				$death['modify_id']=$encoder;
				$death['modify_time']=date('YmdHis');
				@$person->setDeathInfo($enc_obj->PID(),$death);				
		}	

		if($released){
			if($discharged_type!=6){
                            $data_array['encounter_nr']=$pn;
                            $data_array['date']=$date;
                            $data_array['time']=$time;
                            $data_array['personell_name']=$encoder;
                            if($_POST['lamsang_notes']){
                                $data_array['notes']=$_POST['lamsang_notes'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,24);
                            }
                            if($_POST['chandoan_notes']){
                                $data_array['notes']=$_POST['chandoan_notes'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,25);
                            }
                            if($_POST['tinhtrang_notes']){
                                $data_array['notes']=$_POST['tinhtrang_notes'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,26);
                            }
                            if($_POST['phuongtien_notes']){
                                $data_array['notes']=$_POST['phuongtien_notes'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,27);
                            }
                            if($_POST['nguoiduadi_notes']){
                                $data_array['notes']=$_POST['nguoiduadi_notes'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,28);
                            }                            
                            if($_POST['lydochuyenvien']){
                                $data_array['notes']=$_POST['lydochuyenvien'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,47);
                            }
                            if($_POST['noichuyenden']){
                                $data_array['notes']=$_POST['noichuyenden'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,68);//
                            }
                             if($_POST['chandoanravien_code']){
                                $data_array['notes']=$_POST['chandoanravien_code'];
                                $enc_obj->saveDischargeNotesFromArray1($data_array,48);//
                            }
			}
            header("location:$thisfile?sid=$sid&lang=$lang&pn=$pn&bd=$bd&rm=$rm&pyear=$pyear&pmonth=$pmonth&pday=$pday&mode=$mode&released=1&lock=1&x_date=$x_date&x_time=$x_time&relart=$relart&discharged_type=$discharged_type&encoder=".strtr($encoder," ","+")."&info=".strtr($info," ","+")."&station=$station&ward_nr=$ward_nr");
			exit;
			
		}

	}	// end of if (mode=release)		
	include_once($root_path.'include/care_api_classes/class_globalconfig.php');
	$GLOBAL_CONFIG=array();
	$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
	$glob_obj->getConfig('patient_%');	
	$glob_obj->getConfig('person_%');	

	$result=&$enc_obj->encounter;
	/* Check whether config foto path exists, else use default path */			
	$default_photo_path='uploads/photos/registration';
	$photo_filename=$result['photo_filename'];
	$photo_path = (is_dir($root_path.$GLOBAL_CONFIG['person_foto_path'])) ? $GLOBAL_CONFIG['person_foto_path'] : $default_photo_path;
	require_once($root_path.'include/core/inc_photo_filename_resolve.php');
	
	$patient_ok=TRUE;
}else{
	$patient_ok=FALSE;
}	

		
# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Toolbar title

 $smarty->assign('sToolbarTitle',$LDReleasePatient);

 # href for the return button
 $smarty->assign('pbBack',FALSE);

# href for the  button
 $smarty->assign('pbHelp',"javascript:gethelp('inpatient_discharge.php','','','$station','$LDReleasePatient')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('Name',$LDReleasePatient);

 # Collect extra javascrit code if patient is not released yet
 
 if(!$released){
//gjergji : new calendar
	require_once ('../../js/jscalendar/calendar.php');
	$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
	$calendar->load_files();
	//end gjergji
	ob_start();
?>

<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
 <link href="<? echo  $root_path;?>js/autocomplete.css" rel="stylesheet" type="text/css" />
 <script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>
<script src="<?php echo $root_path;?>js/icd10-jquery.jSuggest.1.0.js"></script>
<script type="text/javascript">
<!-- 

$(function(){
	 $("#referrer_diagnosis").jSuggest({
	   url: "<?php echo $root_path;?>jsloadicd10.php",
	   type: "POST",
	   data: "searchQuery" ,/* in this case it's suggestion.html?searchQuery=[text in the text field] */
	   autoChange: false
	 });
	});

	function pruf(d){
	if(!d.sure.checked){
		alert("Vui lòng chọn Đồng ý xuất chuyển");
		return false;
		}
		else{
			//alert($("input[name='discharged_type']:checked").val());
			if(!$("input[name='discharged_type']:checked").val()){
			 alert("Vui lòng chọn loại xuất/chuyển");
			 return false;
			}
			return true;
		}
	}
    function transenclass(pid){
        urlholder="<?php echo $root_path ?>modules/registration_admission/aufnahme_start_1.php<?php echo URL_APPEND; ?>&pid="+pid+"&origin=patreg_reg&encounter_class_nr=2";
            //window.location.href=urlholder;
            patientwin=window.open(urlholder,pid,"width=700,height=500,menubar=no,resizable=yes,scrollbars=yes");
    }
    
    function popClassification() {
	urlholder="./chuyenvien_classifications.php<?php echo URL_REDIRECT_APPEND.'&name='; ?>"+document.getElementById('nguoiduadi_notes').value;
	CLASSWIN<?php echo $sid ?>=window.open(urlholder,"CLASSWIN<?php echo $sid ?>","menubar=no,width=1000,height=550,resizable=yes,scrollbars=yes");
    }
    
    function clearClassification(name) {
	document.getElementById(name).value="";
	document.getElementById(name).focus();
    }
        $(function(){
        $.noConflict();	
        $("#x_time").mask("**:**");
    }); 
	function getnoichuyen(nr){
		urlholder="./get_noichuyen.php<?php echo URL_REDIRECT_APPEND.'&id='; ?>"+nr;
		CLASSWIN<?php echo $sid ?>=window.open(urlholder,"CLASSWIN<?php echo $sid ?>","menubar=no,width=1000,height=550,resizable=yes,scrollbars=yes");	
	}
	function fill(input,thisValue,id) {
	input.value = input.value.toUpperCase();
		$.ajax({
			type:"GET",
			url:"<? echo $root_path?>modules/registration_admission/rpc.php?diagnosis_code="+thisValue,
			success: function(msg){
				if(msg!="")
				//$("#"+id).val($("#"+id).val()+msg+"\n");
				$("#"+id).val(msg);
			}
		});
	}
	function actionOfType(val){
		if(val=='2'){
			$("tr[group='cv']").css("display","");
		}
		else{
			$("tr[group='cv']").css("display","none");
		}
	}
<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>
   
//-->
</script>
<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

	$smarty->append('JavaScript',$sTemp);

} // End of if !$released

if(($mode=="release")&&($released)){
	$smarty->assign('sPrompt',$LDJustReleased);
}

if($patient_ok){
	$smarty->assign('thisfile',$thisfile);
	$smarty->assign('sBarcodeLabel','<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>');
	$smarty->assign('img_source','<img '.$img_source.' align="top">');
	$smarty->assign('LDLocation',$LDPatListElements[0].':&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$rm);
	$smarty->assign('sLocation',$LDPatListElements[1].':&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$bd);
	$smarty->assign('LDDate',$LDDate);
	
	
	if($released){
		$smarty->assign('released',TRUE);
		$smarty->assign('x_date',nl2br($x_date));
	}else{
		//gjergji : new calendar
		$smarty->assign('sDateInput',$calendar->show_calendar($calendar,$date_format,'x_date',date('Y-m-d')));
		//end gjergji
	}
	$smarty->assign('LDClockTime',$LDClockTime);
	$smarty->assign('LDType',$LDType);
	if($released){
		$smarty->assign('discharged_type',$discharged_type);
		if($discharged_type==1){
			$smarty->assign('type','Xuất viện');
		}elseif($discharged_type==2){
			$smarty->assign('type','Chuyển viện');
		}elseif($discharged_type==3){
			$smarty->assign('type','Trốn viện');	
		}elseif($discharged_type==4){
			$smarty->assign('type','Xin về');		
		}elseif($discharged_type==5){
			$smarty->assign('type','Đưa về');		
		}elseif($discharged_type==6){
			$smarty->assign('type','Tử vong');
		}			
	}else{
	$smarty->assign('sType','
		<input type="radio" name="discharged_type" value="1" onclick="actionOfType(this.value)">Xuất viện
		<input type="radio" name="discharged_type" value="2" onclick="actionOfType(this.value)">Chuyển viện
		<input type="radio" name="discharged_type" value="3" onclick="actionOfType(this.value)">Trốn viện
		<input type="radio" name="discharged_type" value="4" onclick="actionOfType(this.value)">Xin về
		<input type="radio" name="discharged_type" value="5" onclick="actionOfType(this.value)">Đưa về
		<input type="radio" name="discharged_type" value="6" onclick="actionOfType(this.value)">Tử vong
		');
	}
	if($released) $smarty->assign('x_time',nl2br($x_time));
		else $smarty->assign('sTimeInput','<input type="text" name="x_time" id="x_time" size=6 maxlength=6 value="'.date('H:i').'" onBlur="checkTime(this)" >');//onKeyUp=setTime(this,\''.$lang.'\')
	
        //By Huynh
        $info1=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=24");
        if($info1){
            $info_encounter=$info1->FetchRow();
        }        
        $smarty->assign('LDDauhieu',$LDLamsang);
        $smarty->assign('lamsang_notes',nl2br($info_encounter['notes']));
        $smarty->assign('lamsang_Input','<textarea name="lamsang_notes" id="lamsang_notes" rows=1 cols=63>'.nl2br($info_encounter['lamsang_notes']).'</textarea>');
        
        $info2=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=25");
        if($info2){
            $info_encounter1=$info2->FetchRow();
        }
        $info2=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=48");
        if($info2b){
            $info_encounter1b=$info2->FetchRow();
        }
        $smarty->assign('LDChandoan',$LDChandoan);
        $smarty->assign('chandoan_notes',nl2br($info_encounter1['notes']));
        $smarty->assign('chandoan_Input','<input type="text" name="chandoanravien_code" id="referrer_diagnosis_code" size=10 maxlength=10 value="'.$info_encounter1['chandoanravien_code'].'" onblur="fill(this,this.value,\'referrer_diagnosis\');"><input type="text" name="chandoan_notes" id="referrer_diagnosis" size=70 maxlength=250 value="'.nl2br($info_encounter1['chandoan_notes']).'" ondblclick="this.value=\'\'"/>');
        
        $info3=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=26");
        if($info3){
            $info_encounter2=$info3->FetchRow();
            $info1='';
        }
        //$info_encounter2['tinhtrang_notes']
        $smarty->assign('LDTinhtrang',$LDTinhtrang);
        switch ($info_encounter2['notes']) {
        	case 1: $tinhtrangstr = 'Khỏi';        		
        		break;
        	case 2: $tinhtrangstr = 'Đở, giãm';        		
        		break;
        	case 3: $tinhtrangstr = 'Không thay đổi';        		
        		break;
        	case 4: $tinhtrangstr = 'Nặng hơn';        		
        		break;
        	default:
        		$tinhtrangstr = $info_encounter2['notes'];
        		break;
        }
        $smarty->assign('tinhtrangraviennote',$tinhtrangstr);
        /*$smarty->assign('tinhtrang_Input','<select name="tinhtrang_notes" id="tinhtrang_notes">
        	<option value="1">Khỏi</option>
        	<option value="2">Đở, giãm</option>
        	<option value="3">Không thay đổi</option>
        	<option value="4">Nặng hơn</option>
        	</select>');*/
        $smarty->assign('tinhtrang_Input','<input type="text" size="82" name="tinhtrang_notes" id="tinhtrang_notes" value="'.$tinhtrangstr.'"/>');
        $info4=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=27");
        if($info4){
            $info_encounter3=$info4->FetchRow();
        }      
        $smarty->assign('LDPhuongtien',$LDPhuongtien);
        $smarty->assign('phuongtien_notes',nl2br($info_encounter3['notes']));
        $smarty->assign('phuongtien_Input','<input type="text" name="phuongtien_notes" id="phuongtien_notes" size=82 maxlength=250 value="'.nl2br($info_encounter3['phuongtien_notes']).'" />');
		$info6=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=68");
        if($info6){
            $info_encounter6=$info6->FetchRow();
            $snamenoichuyen = $info_encounter6['notes'];
        } 
        else $snamenoichuyen="";
        $smarty->assign('noichuyen',$LDLocMove);
        $strnoichuyen = $enc_obj->getListBV($snamenoichuyen);
		$smarty->assign('noichuyen_note','<select name="noichuyenden" disabled="true">'.$strnoichuyen.'</select>');		
		$smarty->assign('noichuyen_input','<select name="noichuyenden" >'.$strnoichuyen.'</select>');

		$info5=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=47");
        if($info5){
            $info_encounter2=$info5->FetchRow();
        }   
        $smarty->assign('LDLydoChuyenVien',$LDLydoChuyenVien);
        $smarty->assign('LydoChuyenVien_note',$info_encounter2['notes']);
		$smarty->assign('sLyDoChuyenVien','<input type="text" name="lydochuyenvien" id="lydochuyenvien" size=82 maxlength=250 value="Từ cấp cứu" />');

		$smarty->assign('LDNotes',$LDChucdanh);
        $info5=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=28");
        if($info5){
            $info_encounter4=$info5->FetchRow();
            $info1='';
        }        
        $smarty->assign('info',nl2br($info_encounter4['notes']));
        $smarty->assign('Nguoiduadi_Input','<textarea name="nguoiduadi_notes" id="nguoiduadi_notes" cols=63 rows=1 readonly>'.nl2br($info_encounter4['notes']).'</textarea>');
        
        $smarty->assign('IMG_ADD',"<a href='javascript:popClassification()'><img ".createLDImgSrc($root_path,'add_sm.gif','0')." /></a>");
        $smarty->assign('IMG_CLEAR',"<a href=javascript:clearClassification('nguoiduadi_notes')><img ".createLDImgSrc($root_path,'clearall_sm.gif','0').' /></a>');
        if($discharged_type==1)
        	$smarty->assign('Print',"<a href='javascript:printOutRV();'><input type='image'".createLDImgSrc($root_path,'printout.gif','0')." /></a>");
        else if($discharged_type==2)	
        $smarty->assign('Print',"<a href='javascript:printOut();'><input type='image'".createLDImgSrc($root_path,'printout.gif','0')." /></a>");

	$smarty->assign('LDNurse',$LDDocBy);

	$smarty->assign('encoder',$encoder);

	if(!(($mode=='release')&&($released))) {

		$smarty->assign('bShowValidator',TRUE);
	
		$smarty->assign('pbSubmit','<input type="submit" value="'.$LDRelease.'">');
		
		$smarty->assign('sValidatorCheckBox','<input type="checkbox" name="sure">');
		$smarty->assign('LDYesSure',$LDYesSure);
	}

	$sTemp = '<input type="hidden" name="mode" value="release">';

	if(($released)||($lock)) $sTemp = $sTemp.'<input type="hidden" name="lock" value="1">';

	$sTemp = $sTemp.'<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="station" value="'.$station.'">
		<input type="hidden" name="ward_nr" value="'.$ward_nr.'">
		<input type="hidden" name="dept" value="'.$dept.'">
		<input type="hidden" name="dept_nr" value="'.$dept_nr.'">
		<input type="hidden" name="pday" value="'.$pday.'">
		<input type="hidden" name="pmonth" value="'.$pmonth.'">
		<input type="hidden" name="pyear" value="'.$pyear.'">
		<input type="hidden" name="rm" value="'.$rm.'">
		<input type="hidden" name="bd" value="'.$bd.'">
		<input type="hidden" name="pn" value="'.$pn.'">
		<input type="hidden" name="s_date" value="'."$pyear-$pmonth-$pday".'">';

	$smarty->assign('sHiddenInputs',$sTemp);


}else{
	$smarty->assign('sPrompt',"$LDErrorOccured $LDTellEdpIfPersist");
}

if(($mode=='release')&&($released)) $sBreakButton= '<img '.createLDImgSrc($root_path,'close2.gif','0').'>';
	else $sBreakButton= '<img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0">';

$smarty->assign('pbCancel','<a href="'.$breakfile.'">'.$sBreakButton.'</a>');

$smarty->assign('sMainBlockIncludeFile','nursing/discharge_patient_form.tpl');

 /**
 * show Template
 */

 $smarty->display('common/mainframe.tpl');
 // $smarty->display('debug.tpl'); 
 ?>
<script>
    function printOut() {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/chuyenvien/GiayChuyenVien.php<?php echo URL_APPEND ?>&enc_nr=<?php echo $pn; ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
    function printOutRV() {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/giaytokhac/Giayravien_cot.php<?php echo URL_APPEND ?>&enc_nr=<?php echo $pn; ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
</script>        