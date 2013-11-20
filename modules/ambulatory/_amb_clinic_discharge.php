<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables[]='prompt.php';
$lang_tables[]='emr.php';
define('LANG_FILE','nursing.php');
$local_user='ck_pflege_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');

if(!$encoder) $encoder=$_SESSION['sess_user_name'];

$breakfile="amb_clinic_patients.php".URL_APPEND."&edit=$edit&dept_nr=$dept_nr";
$thisfile=basename(__FILE__);

# Load date formatter 
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;
	
if($enc_obj->loadEncounterData($pn)){		
	//$db->debug=1;
	$pid=$enc_obj->EncounterExists($pn);
	if(($mode=='release')&&!(isset($lock)||$lock)){
		$date=(empty($x_date))?date('Y-m-d'):formatDate2STD($x_date,$date_format);
		$time=(empty($x_time))?date('H:i:s'):convertTimeToStandard($x_time);
		# Check the discharge type
		//var_dump($relart);
		switch($relart){
			case 8: if( $released=$enc_obj->DischargeFromDept($pn,$relart,$date,$time)){
							# Reset current department
							//$enc_obj->ResetAllCurrentPlaces($pn,0);
						}
						 break;
			case 9:$released=true; break;
			default: 
			//echo $discharged_type;
			$released=$enc_obj->Discharge($pn,$relart,$discharged_type,$date,$time); break;
		}	
		if($released){
			# If discharge note present
			if($relart!=9){
			
			
			# If patient died
			//$db->debug=1;
			if($relart==7){
				include_once($root_path.'include/care_api_classes/class_person.php');
				$person=new Person;
				$death['death_date']=$date;
				$death['death_encounter_nr']=$pn;
				if($dbtype=='mysql') $death['history']="CONCAT(history,'Discharged ".date('Y-m-d H:i:s')." $encoder\n')";
					else $death['history']="history || 'Discharged ".date('Y-m-d H:i:s')." $encoder\n' ";
				$death['modify_id']=$encoder;
				$death['modify_time']=date('YmdHis');
				@$person->setDeathInfo($enc_obj->PID(),$death);
				//echo $person->getLastQuery();
			}else{
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
			
			}
			//header("location:$thisfile?sid=$sid&lang=$lang&pn=$pn&bd=$bd&rm=$rm&pyear=$pyear&pmonth=$pmonth&pday=$pday&mode=$mode&released=1&lock=1&x_date=$x_date&x_time=$x_time&relart=$relart&discharged_type=$discharged_type&encoder=".strtr($encoder," ","+")."&info=".strtr($info," ","+")."&station=$station&dept_nr=$dept_nr");
			//exit;
			}else{
			//	header("location:".$root_path."modules/registration_admission/aufnahme_start_1.php".URL_APPEND."&pid=".$enc_obj->getPid($pn)."&origin=patreg_reg&encounter_class_nr=1");
			exit;
			}
		}
	}
			
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
		/* Load the discharge types */
		$discharge_types=&$enc_obj->getDischargeTypesData();

		if(!isset($dept)||empty($dept)){
			# Create nursing notes object 
			include_once($root_path.'include/care_api_classes/class_department.php');
			$dept_obj= new Department;
			$dept=$dept_obj->FormalName($dept_nr);
		}
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
 $smarty->assign('pbHelp',"javascript:gethelp('outpatient_discharge.php','discharge','','$station','$LDReleasePatient')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDReleasePatient);

 # If not yet released, create javascript code
 # Collect extra javascript code

  if(!$released){
 
	ob_start();
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
?>

<script language="javascript">
<!-- 

function pruf(d)
{ 
	if(!d.sure.checked){
		return false;
	}else{
		if(!d.encoder.value){ 
			alert("<?php echo $LDAlertNoName ?>"); 
			d.encoder.focus();
			return false;
		}
		if (!d.x_date.value){ alert("<?php echo $LDAlertNoDate ?>"); d.x_date.focus();return false;}
		if (!d.x_time.value){ alert("<?php echo $LDAlertNoTime ?>"); d.x_time.focus();return false;}
		// Check if death
		if(d.relart[3].checked==true&&d.x_date.value!=""){
			if(!confirm("<?php echo $LDDeathDateIs ?> "+d.x_date.value+". <?php echo "$LDIsCorrect $LDProceedSave" ?>")) return false;
		}
		if(d.relart[5].checked==true){
		//alert("4");
			window.location.href = ("<?php echo $root_path ?>modules/registration_admission/aufnahme_start_1.php<?php echo URL_APPEND; ?>&pid=<?php echo $enc_obj->getPid($pn);?>&origin=patreg_reg&encounter_class_nr=1");
			return false;
		}
		return true;
	}
}
 function popClassification() {
	urlholder="<?php echo $root_path ?>modules/nursing/chuyenvien_classifications.php<?php echo URL_REDIRECT_APPEND.'&name='; ?>"+document.getElementById('nguoiduadi_notes').value;
	CLASSWIN<?php echo $sid ?>=window.open(urlholder,"CLASSWIN<?php echo $sid ?>","menubar=no,width=1000,height=550,resizable=yes,scrollbars=yes");
    }
    
    function clearClassification(name) {
	document.getElementById(name).value="";
	document.getElementById(name).focus();
    }
        
 
$(function(){
$("#f-calendar-field-1").mask("99/99/9999");
$('#x_time').mask("99:99");
});	
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


$smarty->assign('sBarcodeLabel','<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>');
$smarty->assign('img_source','<img '.$img_source.' align="top">');
$smarty->assign('LDLocation',"$LDClinic/$LDDept");
$smarty->assign('sLocation',$dept);
$smarty->assign('LDDate',$LDDate);
//gjergji : new calendar
//var_dump($enc_obj->encounter);
//end gjergji
	if($released){
		$smarty->assign('released',TRUE);
		$smarty->assign('x_date',nl2br($x_date));
	}else{
		//gjergji : new calendar
		$smarty->assign('sDateInput',$calendar->show_calendar($calendar,$date_format,'x_date',$enc_obj->encounter['encounter_date'] ));
		//end gjergji
	}
	$smarty->assign('LDClockTime',$LDClockTime);

	if($released) $smarty->assign('x_time',nl2br($x_time));
		else $smarty->assign('sTimeInput','<input type="text" id="x_time" name="x_time" size=12 maxlength=12 value="'.convertTimeToLocal(date('H:i:s')).'" onKeyUp=setTime(this,\''.$lang.'\')>');
	$smarty->assign('LDReleaseType',$LDReleaseType);
	$smarty->assign('LDType',$LDType);
if($released){
		if($discharged_type==1){
			$smarty->assign('type','Xuất viện');
		}elseif($discharged_type==2){
			$smarty->assign('type','Chuyển viện');
		}elseif($discharged_type==3){
			$smarty->assign('type','Trốn viện');
		}	
	}else{
	$smarty->assign('sType','<input type="radio" name="discharged_type" value="1" checked="true">Xuất viện<input type="radio" name="discharged_type" value="2">Chuyển viện<input type="radio" name="discharged_type" value="3">Trốn viện');
	}
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
        $smarty->assign('LDChandoan',$LDChandoan);
        $smarty->assign('chandoan_notes',nl2br($info_encounter1['notes']));
        $smarty->assign('chandoan_Input','<input type="text" name="chandoan_notes" id="chandoan_notes" size=82 maxlength=250 value="'.nl2br($info_encounter1['chandoan_notes']).'" />');
        
        $info3=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=26");
        if($info3){
            $info_encounter2=$info3->FetchRow();
            $info1='';
        }
        $smarty->assign('LDTinhtrang',$LDTinhtrang);
        $smarty->assign('tinhtrangravien',nl2br($info_encounter2['notes']));
        $smarty->assign('tinhtrang_Input','<input type="text" name="tinhtrang_notes" id="tinhtrang_notes" size=82 maxlength=250 value="'.nl2br($info_encounter2['tinhtrang_notes']).'" />');
        
        $info4=$enc_obj->_getNotes("encounter_nr=$pn AND type_nr=27");
        if($info4){
            $info_encounter3=$info4->FetchRow();
            $info1='';
        }      
        $smarty->assign('LDPhuongtien',$LDPhuongtien);
        $smarty->assign('phuongtien_notes',nl2br($info_encounter3['notes']));
        $smarty->assign('phuongtien_Input','<input type="text" name="phuongtien_notes" id="phuongtien_notes" size=82 maxlength=250 value="'.nl2br($info_encounter3['phuongtien_notes']).'" />');
        
        $smarty->assign('LDReleaseType',$LDReleaseType);
	$sTemp = '';
	if($released){

		while($dis_type=$discharge_types->FetchRow()){
			if($dis_type['nr']==$relart){
				//$sTemp = $sTemp.'&nbsp;';
				if(isset($$dis_type['LD_var'])&&!empty($$dis_type['LD_var'])) $sTemp = $sTemp.$$dis_type['LD_var'];
					else $sTemp = $sTemp.$dis_type['name'];
				break;
			}
		}
	}else{
		$init=1;
		while($dis_type=$discharge_types->FetchRow()){
				# We will display only discharge types 1 to 7
				if(stristr('4,5,6',$dis_type['nr'])) continue;
				
			     $sTemp = $sTemp.'<input type="radio" name="relart" value="'.$dis_type['nr'].'"';
			     if($init){
				    $sTemp = $sTemp.' checked';
				    $init=0;
		         }
			     $sTemp = $sTemp.'>';
			     if(isset($$dis_type['LD_var'])&&!empty($$dis_type['LD_var'])) $sTemp = $sTemp.$$dis_type['LD_var'];
				    else $sTemp = $sTemp.$dis_type['name'];
			     $sTemp = $sTemp.'<br>';
		}
	}
	$smarty->assign('sDischargeTypes',$sTemp);

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
        $smarty->assign('Print',"<a href='javascript:printOut();'><input type='image'".createLDImgSrc($root_path,'printout.gif','0')." /></a>");

	$smarty->assign('LDNurse',$LDDocBy);

	$smarty->assign('encoder',$encoder);

	if(!(($mode=='release')&&($released))) {

		$smarty->assign('bShowValidator',TRUE);
		$smarty->assign('pbSubmit','<input type="submit" value="'.$LDRelease.'" >');
		$smarty->assign('sValidatorCheckBox','<input type="checkbox" name="sure" value="1" checked="true">');
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

?>

<?php
if(($mode=='release')&&($released))
{ $sBreakButton= '<img '.createLDImgSrc($root_path,'close2.gif','0').'></a>'; 
  
}
	else {$sBreakButton= '<img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0"></a>';

	}
$smarty->assign('pbCancel','<a href="'.$breakfile.'">'.$sBreakButton);

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
</script>   