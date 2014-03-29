<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
 * GNU General Public License
 * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
///$db->debug=true;
$lang_tables[]='departments.php';
$lang_tables[]='prompt.php';
$lang_tables[]='help.php';
$lang_tables[]='person.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_person.php');
require_once($root_path.'include/care_api_classes/class_insurance.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_globalconfig.php');
require_once($root_path.'include/care_api_classes/class_ecombill.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
//add 1010 - cot encounter_transfer array
//(`encounter_nr`,`pid`,`datein`,`dateout`,`dept_from`,`dept_to`,`home`,`status`,`personell_nr`)
$encounter_transfer = array(
	"nr" => "",
	"encounter_nr" => "",
	"pid" => "",
	"datein" => "",
	"dateout" => "",
	"dept_from" => "",
	"dept_to" => "",
	"home" => "",
	"status" => "",
	"login_id" => "",
	"type_encounter"=>""
	);
//gjergji
$current_dept_nr = $_SESSION['department_nr'];
//end: gjergji
$thisfile=basename(__FILE__);
if($origin=='patreg_reg') $breakfile = 'patient_register_show.php'.URL_APPEND.'&pid='.$pid;
elseif($_COOKIE["ck_login_logged".$sid]) $breakfile = $root_path.'main/startframe.php'.URL_APPEND;
elseif(!empty($_SESSION['sess_path_referer'])) $breakfile=$root_path.$_SESSION['sess_path_referer'].URL_APPEND.'&pid='.$pid;
else $breakfile = "aufnahme_pass.php".URL_APPEND."&target=entry";

$newdata=1;

/* Default path for fotos. Make sure that this directory exists! */
$default_photo_path='uploads/photos/registration';
$photo_filename='nopic';
$error=0;

if(!isset($pid)) $pid=0;
if(!isset($encounter_nr)) $encounter_nr=0;
if(!isset($mode)) $mode='';
if(!isset($forcesave)) $forcesave=0;
if(!isset($update)) $update=0;
if(!isset($_SESSION['sess_pid'])) $_SESSION['sess_pid'] = "";
if(!isset($_SESSION['sess_full_pid'])) $_SESSION['sess_full_pid'] = "";
if(!isset($_SESSION['sess_en'])) $_SESSION['sess_en'] = "";
if(!isset($_SESSION['sess_full_en']))$_SESSION['sess_full_en'] = "";



$patregtable='care_person';  // The table of the patient registration data

$dbtable='care_encounter'; // The table of admission data

/* Create new person's insurance object */
$pinsure_obj=new PersonInsurance($pid);
/* Get the insurance classes */
$insurance_classes=&$pinsure_obj->getInsuranceClassInfoObject('class_nr,name,LD_var AS "LD_var"');

/* Create new person object */
$person_obj=new Person($pid);
/* Create encounter object */
$encounter_obj=new Encounter($encounter_nr);
/* Get all encounter classes */
$encounter_classes=$encounter_obj->AllEncounterClassesObject();
/* Create eComBill object */
$eComBill_obj = new eComBill;
/* Create personell object */
$personell_obj = new Personell;
 require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
//add 0310 - cot
//$_SESSION['sess_login_userid']
//$_SESSION['sess_login_username']
$currbsinfo = $personell_obj->getBSAdminInfo($_SESSION['sess_login_userid']);
//end add
if($pid!='' || $encounter_nr!=''){

	/* Get the patient global configs */
	$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
	$glob_obj->getConfig('patient_%');
	$glob_obj->getConfig('person_foto_path');
	$glob_obj->getConfig('encounter_%');
	$glob_obj->getConfig('show_billable_items');
	$glob_obj->getConfig('show_doctors_list');

	if(!$GLOBAL_CONFIG['patient_service_care_hide']){
		/* Get the care service classes*/
		$care_service=$encounter_obj->AllCareServiceClassesObject();
	}
	if(!$GLOBAL_CONFIG['patient_service_room_hide']){
		/* Get the room service classes */
		$room_service=$encounter_obj->AllRoomServiceClassesObject();
	}
	if(!$GLOBAL_CONFIG['patient_service_att_dr_hide']){
		/* Get the attending doctor service classes */
		$att_dr_service=$encounter_obj->AllAttDrServiceClassesObject();
	}

	/* Check whether config path exists, else use default path */
	$photo_path = (is_dir($root_path.$GLOBAL_CONFIG['person_foto_path'])) ? $GLOBAL_CONFIG['person_foto_path'] : $default_photo_path;

	if ($pid)
	{
	
		/* Check whether the person is currently admitted. If yes jump to display admission data */
		if(!$update&&$encounter_obj->isAdmitted($pid)){ //edit - 03102012 - cot
			$encounter_nr = $encounter_obj->isAdmitted($pid);
			header('Location:aufnahme_daten_zeigen.php'.URL_REDIRECT_APPEND.'&encounter_nr='.$encounter_nr.'&origin=admit&sem=isadmitted&target=entry');
			exit;
		}

		/* Get the related insurance data 
		$p_insurance=&$pinsure_obj->getPersonInsuranceObject($pid);
		if($p_insurance==false) {
			$insurance_show=true;
		} else {
			if(!$p_insurance->RecordCount()) {
				$insurance_show=true;
			} elseif ($p_insurance->RecordCount()==1){
				$buffer= $p_insurance->FetchRow();
				extract($buffer);
				$insurance_show=true;
				$insurance_firm_name=$pinsure_obj->getFirmName($insurance_firm_id);
			} else { $insurance_show=false;}
		}
*/

		$ins_infor=$person_obj->getInfoInsurEnc($pid);
			
		if (($mode=='save') || ($forcesave!='')) {
			if(!$forcesave) {
				//clean and check input data variables
				/**
				*  $error = 1 will cause to show the "save anyway" override button to save the incomplete data
				*  $error = 2 will cause to force the user to enter a data in an input element (no override allowed)
				*/
				 
				//gjergji
				//added the possibility to upload foto here
				// Create image object
				include_once($root_path.'include/care_api_classes/class_image.php');
				$img_obj=& new Image;
				$picext='';
				$valid_image=false;
				$photo_filename='';
				if($img_obj->isValidUploadedImage($_FILES['photo_filename'])){
					$valid_image=TRUE;
					# Get the file extension
					$picext=$img_obj->UploadedImageMimeType();
				}

				if ($valid_image){
					# Compose the new filename
					$photo_filename=$pid.'.'.$picext;
					# Save the file
					$img_obj->saveUploadedImage($_FILES['photo_filename'],$root_path.$photo_path.'/',$photo_filename);
					$person_obj->setPhotoFilename($pid,$photo_filename);
				}
					
				//end : gjergji

				$encoder=trim($encoder);
				if($encoder=='') $encoder=$_SESSION['sess_user_name'];
					
				$referrer_diagnosis=trim($referrer_diagnosis);
				if ($referrer_diagnosis=='') { $errordiagnose=1; $error=1; $errornum++; };
					
					
				
				//add 9-11 start
				//$lidovaovien=trim($lidovaovien);
				//if ($lidovaovien=='') { $errorvaovien=1; $error=1; $errornum++;};
				//$quatrinhbenhly=trim($quatrinhbenhly);
				//if ($quatrinhbenhly=='') { $errorqtbly=1; $error=1; $errornum++;};
				//end
				$encounter_class_nr=trim($encounter_class_nr);
				if ($encounter_class_nr=='') { $errorstatus=1; $error=1; $errornum++;};

				//if($insurance_show) {
				//	if(trim($insurance_nr) &&  trim($insurance_firm_name)=='') { $error_ins_co=1; $error=1; $errornum++;}
				//}
			}
				


			if(!$error) {
				if(!$GLOBAL_CONFIG['patient_service_care_hide']){
					if(!empty($sc_care_start)) $sc_care_start=formatDate2STD($sc_care_start,$date_format);
					if(!empty($sc_care_end)) $sc_care_end=formatDate2STD($sc_care_end,$date_format);
					$care_class=compact('sc_care_nr','sc_care_class_nr', 'sc_care_start', 'sc_care_end','encoder');
				}
				if(!$GLOBAL_CONFIG['patient_service_room_hide']){
					if(!empty($sc_room_start)) $sc_room_start=formatDate2STD($sc_room_start,$date_format);
					if(!empty($sc_room_end)) $sc_room_end=formatDate2STD($sc_room_end,$date_format);
					$room_class=compact('sc_room_nr','sc_room_class_nr', 'sc_room_start', 'sc_room_end','encoder');
				}
				if(!$GLOBAL_CONFIG['patient_service_att_dr_hide']){
					if(!empty($sc_att_dr_start)) $sc_att_dr_start=formatDate2STD($sc_att_dr_start,$date_format);
					if(!empty($sc_att_dr_end)) $sc_att_dr_end=formatDate2STD($sc_att_dr_end,$date_format);
					$att_dr_class=compact('sc_att_dr_nr','sc_att_dr_class_nr','sc_att_dr_start', 'sc_att_dr_end','encoder');
				}
				if ($GLOBAL_CONFIG['show_doctors_list'] && $encounter_class_nr == 2){
					if(!$update)
						if(!empty($referred_dr_list) ) { $_POST['referred_dr'] = $referred_dr_list; }
						else { $_POST['referred_dr'] = '0'; }
				}

				if($update || $encounter_nr) {
					//echo formatDate2STD($geburtsdatum,$date_format);
					//echo $_POST['is_cbtc'];
					if($_POST['is_cbtc']=='') $_POST['is_cbtc']='0';
					if($_POST['is_tngt']=='') $_POST['is_tngt']='0';
					$_POST['encounter_date']=@formatDate2STD($_POST['dat_reg'],$date_format)." ".$_POST['time_reg'];
					$itemno=$itemname;
					$_POST['modify_id']=$encoder;
					if($dbtype=='mysql' ){
						$_POST['history']= "CONCAT(history,'\n Update: ".date('Y-m-d H:i:s')." = $encoder')";
					}else{
						$_POST['history']= "(history || '\n Update: ".date('Y-m-d H:i:s')." = $encoder')";
					}
					if(isset($_POST['encounter_nr'])) unset($_POST['encounter_nr']);
					if(isset($_POST['pid'])) unset($_POST['pid']);
					
					$encounter_obj->setDataArray($_POST);
						
					if($encounter_obj->updateEncounterFromInternalArray($encounter_nr)) {
						//add 1010 cot
						$ecrrtrans = $encounter_obj->getEncounterCurrTrans($encounter_nr);		
						if($ecrrtrans != null){
						//$ecrrtrans['dateout'] = date("Y-m-d H:i:s");
						$ecrrtrans['dept_to'] = $_POST['current_dept_nr'];
						$encounter_obj->updateOldEncounterTrans($ecrrtrans['nr'],$ecrrtrans);
						}
					
					 $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $encounter_obj->getLastQuery(), date('Y-m-d H:i:s'));
						/* Save the service classes */
						if(!$GLOBAL_CONFIG['patient_service_care_hide']){
							$encounter_obj->updateCareServiceClass($care_class);
						}
						if(!$GLOBAL_CONFIG['patient_service_room_hide']){
							$encounter_obj->updateRoomServiceClass($room_class,$encounter_nr);
						}
						if(!$GLOBAL_CONFIG['patient_service_att_dr_hide']){
							$encounter_obj->updateAttDrServiceClass($att_dr_class,$encounter_nr);
						}
						header("Location: aufnahme_daten_zeigen.php".URL_REDIRECT_APPEND."&encounter_nr=$encounter_nr&origin=admit&target=entry&newdata=$newdata");
						exit;
					}

				} else {
						
					$newdata=1;
					//$encounter_in_date=date('Y-m-d H:i:s');
					/* Determine the format of the encounter number */
					if($GLOBAL_CONFIG['encounter_nr_fullyear_prepend']) $ref_nr=(int)date('Y').$GLOBAL_CONFIG['encounter_nr_init'];
					else $ref_nr=$GLOBAL_CONFIG['encounter_nr_init'];
					//echo $ref_nr;
					switch($_POST['encounter_class_nr']) {
						case '1': $_POST['encounter_nr']=$encounter_obj->getNewEncounterNr($ref_nr+$GLOBAL_CONFIG['patient_inpatient_nr_adder'],1);
						break;
						case '2': $_POST['encounter_nr']=$encounter_obj->getNewEncounterNr($ref_nr+$GLOBAL_CONFIG['patient_outpatient_nr_adder'],2);
					}
					if($_POST['is_cbtc']=='') $_POST['is_cbtc']='0';
					if($_POST['is_tngt']=='') $_POST['is_tngt']='0';
					//var_dump($_POST['dat_reg']);
					$_POST['encounter_date']=@formatDate2STD($_POST['dat_reg'],$date_format)." ".$_POST['time_reg'];
					//$_POST['encounter_in_date']=date('Y-m-d H:i:s');
					$_POST['modify_id']=$encoder;
					//$_POST['modify_time']='NULL';
					$_POST['create_id']=$encoder;
					$_POST['create_time']=date('YmdHis');
					$_POST['history']='Create: '.date('Y-m-d H:i:s').' = '.$encoder;
					//if(isset($_POST['encounter_nr'])) unset($_POST['encounter_nr']);

					$encounter_obj->setDataArray($_POST);

					if($encounter_obj->insertDataFromInternalArray()) {
					
					 $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $encounter_obj->getLastQuery(), date('Y-m-d H:i:s'));
						/* Get last insert id */
						if($dbtype=='mysql') {
							//$encounter_nr = $db->Insert_ID();
							$encounter_nr = $encounter_obj->buffer_array['encounter_nr'];
						}else{
							$encounter_nr = $encounter_obj->postgre_Insert_ID($dbtable,'encounter_nr',$db->Insert_ID());
						}
						
						# If appointment number available, mark appointment as "done"
						if(isset($appt_nr) && $appt_nr) $encounter_obj->markAppointmentDone($appt_nr,$_POST['encounter_class_nr'],$encounter_nr);
						/*/$sql="INSERT INTO care_billing_bill_item (bill_item_encounter_nr,bill_item_code, bill_item_unit_cost, bill_item_units, bill_item_amount, bill_item_date,bill_item_status, bill_item_bill_no)
						//		VALUES ('".$encounter_nr."', '0406', '2000', '1', '2000', '".@formatDate2STD($_POST['dat_reg'],$date_format)."', '0', '0')";
						//$db->execute($sql);
						if ($GLOBAL_CONFIG['show_billable_items'] && $encounter_class_nr == 2 ){
							if(!empty($billable_item_list)) {
								$itemPrice = $eComBill_obj->listServiceItemsByCode($billable_item_list);
								$itemUnitCost = $itemPrice->Fields("item_unit_cost");
								$eComBill_obj->createBillItem($encounter_nr,$billable_item_list,$itemUnitCost,1,$itemUnitCost,@formatDate2STD($_POST['dat_reg'],$date_format)." ".$_POST['time_reg']);
							}
						}
						*/
						//add 0310 - cot, benh nhan tiep nhan ngoai tru tu dong chuyen vao khoa
						if($_POST['encounter_class_nr'] == 2)
							$encounter_obj->assignInDept($encounter_nr,$_POST['current_dept_nr'],$_POST['current_dept_nr'],date("Y-m-d"),date("H:i:s"));
						//insert encounter transfer
						$encounter_transfer = array(
												"nr" => "",
												"encounter_nr" => $encounter_nr,
												"pid" => "",
												"datein" => @formatDate2STD($_POST['dat_reg'],$date_format)." ".$_POST['time_reg'],
												"dateout" => "",
												"dept_from" => (($_SESSION['department_nr'])?$_SESSION['department_nr']:$_POST['current_dept_nr']),
												"dept_to" => $_POST['current_dept_nr'],
												"home" => "",
												"status" => "",
												"login_id" => $_SESSION['sess_login_userid'],
												"type_encounter" => $_POST['encounter_class_nr']
												);
//                        $date_reg = @format2DateSTD($POST['dat_reg'], $date_format)." ".$_POST['time_reg'];
						$encounter_obj->insertEncounterTransfer($encounter_transfer);

						//insert temp Measurement
						$sql="INSERT care_encounter_measurement (msr_date, msr_time,encounter_nr, msr_type_nr, 	value,unit_nr,unit_type_nr,
								notes,measured_by,create_id, create_time)
								SELECT pm.msr_date, pm.msr_time,'".$encounter_nr."', pm.msr_type_nr, pm.value,pm.unit_nr,pm.unit_type_nr,
								pm.notes,pm.measured_by,pm.create_id, pm.create_time FROM dfck_person_measurement pm
								WHERE pm.pid = '".$_SESSION['sess_pid']."' AND pm.STATUS=0  ORDER BY pm.msr_date DESC, pm.msr_time DESC";
						$db->execute($sql);
						$sql="UPDATE dfck_person_measurement pm SET pm.STATUS=1 WHERE pm.pid = '".$_SESSION['sess_pid']."' AND pm.STATUS=0 ";
						$db->execute($sql);
						
						//echo $encounter_obj->getLastQuery();
						header("Location: aufnahme_daten_zeigen.php".URL_REDIRECT_APPEND."&encounter_nr=$encounter_nr&origin=admit&target=entry&newdata=$newdata");
						exit;
					}else{
						echo $LDDbNoSave.'<p>'.$encounter_obj->getLastQuery();
					}
						
				}// end of if(update) else()
				
			}	// end of if($error)
		} // end of if($mode)

	}elseif($encounter_nr!='') {
		/* Load encounter data */
		$encounter_obj->loadEncounterData();
		if($encounter_obj->is_loaded) {
			$zeile=&$encounter_obj->encounter;
			//load data
			extract($zeile);

			// Get insurance firm name
			$insurance_firm_name=$pinsure_obj->getFirmName($insurance_firm_id);

			/* GEt the patient's services classes */
				
			if(!empty($GLOBAL_CONFIG['patient_financial_class_single_result'])) $encounter_obj->setSingleResult(true);

			if(!$GLOBAL_CONFIG['patient_service_care_hide']){
				if($buff=&$encounter_obj->CareServiceClass()){
					while($care_class=$buff->FetchRow()){
						extract($care_class);
					}
					reset($care_class);
				}
			}
			if(!$GLOBAL_CONFIG['patient_service_room_hide']){
				if($buff=&$encounter_obj->RoomServiceClass()){
					while($room_class=$buff->FetchRow()){
						extract($room_class);
					}
					reset($room_class);
				}
			}
			if(!$GLOBAL_CONFIG['patient_service_att_dr_hide']){
				if($buff=&$encounter_obj->AttDrServiceClass()){
					while($att_dr_class=$buff->FetchRow()){
						extract($att_dr_class);
					}
					reset($att_dr_class);
				}
			}
		}
	}
	
 // edit de co the chon khoa phong
	/*	if(!$encounter_nr||$encounter_class_nr==1){ */

		# Load all  wards info
		$ward_obj=new Ward;
		$items='nr,name,dept_nr';
		$ward_info=&$ward_obj->getAllWardsItemsObject($items);
	/* } */
	
	/*	if(!$encounter_nr||$encounter_class_nr==2){ */

		# Load all medical departments
		include_once($root_path.'include/care_api_classes/class_department.php');
		$dept_obj=new Department;
		$all_meds=&$dept_obj->getAllMedicalObject();
	/* } */
	 
	$person_obj->setPID($pid);
	if($data=&$person_obj->BasicDataArray($pid)){
		//while(list($x,$v)=each($data))	$$x=$v;
		extract($data);
	}

	# Prepare the photo filename
	include_once($root_path.'include/core/inc_photo_filename_resolve.php');
	/* Get the citytown name */
	$addr_citytown_name=$person_obj->CityTownName($addr_citytown_nr);
	$addr_quanhuyen_name=$person_obj->QuanHuyenName($addr_quanhuyen_nr);
	$addr_phuongxa_name=$person_obj->PhuongXaName($addr_phuongxa_nr);

}
# Prepare text and resolve the numbers
include_once($root_path.'include/core/inc_patient_encounter_type.php');

# Prepare the title
if($encounter_nr) $headframe_title = "$headframe_title $headframe_append ";

# Prepare onLoad JS code
if(!$encounter_nr && !$pid) $sOnLoadJs ='onLoad="if(document.searchform.searchkey.focus) document.searchform.searchkey.focus();"';


# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in the toolbar
$smarty->assign('sToolbarTitle',$headframe_title);

# href for help button
$smarty->assign('pbHelp',"javascript:gethelp('admission_how2new.php')");

$smarty->assign('breakfile',$breakfile);

# Window bar title
$smarty->assign('title',$headframe_title);

# Onload Javascript code
$smarty->assign('sOnLoadJs',$sOnLoadJs);

# href for help button
$smarty->assign('pbHelp',"javascript:gethelp('person_admit.php')");

# Hide the return button
$smarty->assign('pbBack',FALSE);


# Start collectiong extra Javascript code
ob_start();
require_once ('../../js/jscalendar/calendar.php');
		$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
		$calendar->load_files();
require('./include/js_popsearchwindow.inc.php');
# If  pid exists, output the form checker javascript
if(isset($pid) && $pid){

	?>

<script language="javascript">
<!--


function chkform(d) {
	encr=<?php if ($encounter_class_nr) {echo $encounter_class_nr; } else {echo '0';} ?>;
	//*
    if(d.encounter_class_nr[0]&&d.encounter_class_nr[1]&&!d.encounter_class_nr[0].checked&&!d.encounter_class_nr[1].checked){
		alert("<?php echo $LDPlsSelectAdmissionType; ?>");
		return false;
	}else if(!d.current_dept_nr.value){
		alert("<?php echo $LDPlsSelectDept; ?>");
		d.current_dept_nr.focus();
		return false;
	}else if(d.referrer_diagnosis.value=="" || d.referrer_diagnosis_code.value==""){
		alert("<?php echo $LDPlsEnterRefererDiagnosis; ?>");
		d.referrer_diagnosis.focus();
		return false;
	}else if(d.doctor_nr.value==""){
		alert("<?php echo $LDPlsEnterReferer; ?>");
		d.doctor_nr.focus();
		return false;
	}else if(d.encoder.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.encoder.focus();
		return false;
	}
	else{
		return true;
	}
}

function resolveLoc(){
	d=document.aufnahmeform;
	if(d.encounter_class_nr[0].checked==true) d.current_dept_nr.selectedIndex=0;
		else d.current_ward_nr.selectedIndex=0;
}
function popSearchWin(target,obj_val,obj_name){
			urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}
	function popSearchPer(target,obj_val,obj_name){
			urlholder="./personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}
		function popDocPer(target,obj_val,obj_name){
			urlholder="./personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}
//add by vy -start
function showWard(){
    str=document.getElementById("current_dept_nr").value;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("ward").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","getward.php?dept_nr="+str,true);
	xmlhttp.send();

}
function getref(pn,id){
	urlholder="<?php echo $root_path ?>modules/drg/drg-icd10-search_for_diag.php?sid=<?php echo "$sid&lang=$lang" ?>&pn="+pn+"&id="+id;
	popwin=window.open(urlholder,pn,"menubar=no,width=700,height=500,resizable=yes,scrollbars=yes");
}

function check(){
			if(aufnahmeform.is_cbtc.checked){	
           		$("#cbtcinsur").show();							
				//$('input[name=is_cbtc]').val('yes');				
			}else{
				$("#cbtcinsur").hide();		
				//$('input[name=is_cbtc]').attr('checked',false);					
				//$('input[name=is_cbtc]').val('no');	
							
			}
		
		}


	
	function fill(input,thisValue,id) {
	input.value = input.value.toUpperCase();
		$.ajax({
			type:"GET",
			url:"rpc.php?diagnosis_code="+thisValue,
			success: function(msg){
				if(msg!="")
				//$("#"+id).val($("#"+id).val()+msg+"\n");
				if(id=='benhphu') 
					$("#"+id).val($("#"+id).val()+msg+"\n");
					else
					$("#"+id).val(msg);
			}
		});
	}
	$(function(){
	$("#f-calendar-field-1").mask("99/99/9999");
	$("#time_reg").mask("99:99");
	});
//end
<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>

-->
</script>
 <link href="<? echo  $root_path;?>js/autocomplete.css" rel="stylesheet" type="text/css" />
 <!--<script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>-->
<script src="<?php echo $root_path;?>js/icd10-jquery.jSuggest.1.0.js"></script>
<script type="text/javascript">
$(function(){
	 $("#referrer_diagnosis").jSuggest({
	   url: "<?php echo $root_path;?>jsloadicd10.php",
	   type: "POST",
	   data: "searchQuery" ,/* in this case it's suggestion.html?searchQuery=[text in the text field] */
	   autoChange: false
	 });
	});
$(function(){
	 $("#benhphu").jSuggest({
	   url: "<?php echo $root_path;?>jsloadicd10.php",
	   type: "POST",
	   data: "searchQuery" ,/* in this case it's suggestion.html?searchQuery=[text in the text field] */
	   autoChange: false
	 });
	});
</script>
<style type="text/css">
.suggestionsBox {
		position: absolute;
		left: 620px;
		margin: 10px 0px 0px 0px;
		width: 200px;
		background-color: #00ffff;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border: 2px solid #fff;	
		color: #000;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}

</style>
<?php

} // End of if(isset(pid))


//add by vy 9-11


$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# Load tabs
$target='entry';

$parent_admit = TRUE;

include('./gui_bridge/default/gui_tabs_patadmit.php');

# If the origin is admission link, show the search prompt
if(!isset($pid) || !$pid){

	$searchmask_bgcolor="#f3f3f3";
	# Set color values for the search mask
	$searchprompt=$LDEntryPrompt;
	$entry_block_bgcolor='#fff3f3';
	$entry_body_bgcolor='#ffffff';

	$smarty->assign('entry_border_bgcolor','#6666ee');

	$smarty->assign('sSearchPromptImg','<img '.createComIcon($root_path,'angle_down_l.gif','0','',TRUE).'>');

	$smarty->assign('LDPlsSelectPatientFirst',$LDPlsSelectPatientFirst);
	$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_l.gif','0','absmiddle').'>');

	# Start buffering the searchmask

	ob_start();

	$search_script='patient_register_search.php';
	$user_origin='admit';
	include($root_path.'include/core/inc_patient_searchmask.php');

	$sTemp = ob_get_contents();

	ob_end_clean();

	$smarty->assign('sSearchMask',$sTemp);
	$smarty->assign('sWarnIcon','<img '.createComIcon($root_path,'warn.gif','0','absmiddle',TRUE).'>');
	$smarty->assign('LDRedirectToRegistry',$LDRedirectToRegistry);

}else{

	$smarty->assign('bSetAsForm',TRUE);
	
	if($error){
		$smarty->assign('error',TRUE);
		$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_r.gif','0','bottom').' align="absmiddle">');

		if ($errornum>1) $smarty->assign('LDError',$LDErrorS);
		else 	$smarty->assign('LDError',$LDError);
	}

	$smarty->assign('LDCaseNr',$LDCaseNr);
	if(isset($encounter_nr) && $encounter_nr) 	$smarty->assign('encounter_nr',$encounter_nr);
	else  $smarty->assign('encounter_nr','<font color="red">'.$LDNotYetAdmitted.'</font>');
	if(file_exists($root_path.'cache/barcodes/en_'.$encounter_nr.'.png')) {
	$smarty->assign('sEncBarcode','<img src="'.$root_path.'cache/barcodes/en_'.$encounter_nr.'.png" border=0 width=180 height=35>');
	}else{
	$smarty->assign('sHiddenBarcode',"<img src='".$root_path."classes/barcode/image.php?code=".$encounter_nr."&style=68&type=I25&width=180&height=50&xres=2&font=5&label=2&form_file=en' border=0 width=0 height=0>");
	$smarty->assign('sEncBarcode',"<img src='".$root_path."classes/barcode/image.php?code=".$encounter_nr."&style=68&type=I25&width=180&height=40&xres=2&font=5' border=0>");
	}
	$smarty->assign('img_source',"<img $img_source>");
	//gjergji
	if ($photo_filename=='' || $photo_filename=='nopic' || !file_exists($root_path.$default_photo_path.'/'.$photo_filename)){
		$smarty->assign('sFileBrowserInput','<input name="photo_filename" type="file" size="15"   onChange="showpic(this)" value="'.$pfile.'">');
	}
	//end : gjergji

	$smarty->assign('LDAdmitDate',$LDAdmitDate);
    if(isset($encounter_date)){
        $smarty->assign('sAdmitDate',@formatDate2Local($encounter_in_date,$date_format)." ".@convertTimeToLocal(formatDate2Local($encounter_in_date,$date_format,0,1)));
//        $smarty->assign('sAdmitDate', @formatDate2Local($encounter_date,$date_format));
//        $smarty->assign('sAdmitTime',@formatDate2Local($encounter_date,$date_format,1,1));
//         $smarty->assign('sAdmitDate',$calendar->show_calendar($calendar,$date_format,'dat_reg',@formatDate2Local($encounter_date,$date_format)));
//         $smarty->assign('sAdmitTime','<input name="time_reg" id="time_reg" type="text" value="'.@convertTimeToLocal(formatDate2Local($encounter_date,$date_format,0,1)).'" size="5">');
   }
    else{
     $smarty->assign('sAdmitDate',$calendar->show_calendar($calendar,$date_format,'dat_reg',date("d/m/Y")));
     $smarty->assign('sAdmitTime','<input name="time_reg" id="time_reg" type="text" value="'.date("H:i").'" size="5">');
    }
	$smarty->assign('LDAdmitTime',$LDAdmitTime);


	$smarty->assign('LDInDate','Ngày chỉnh sửa gần nhất');
    if(!empty($encounter_in_date)){
        $smarty->assign('sInDate',$calendar->show_calendar($calendar,$date_format,'dat_reg',date('d/m/Y')));
        $smarty->assign('sInTime','<input name="time_reg" id="time_reg" type="text" value="'.date('H:i').'" size="5">');
	}else{
	    $smarty->assign('sInDate',date('d/m/Y H:i:s').'<input name="encounter_in_date" type="hidden" value="'.date('Y-m-d H:i:s').'">');
	    }

	$smarty->assign('LDTitle',$LDTitle);
	$smarty->assign('title',$title);
	$smarty->assign('LDLastName',$LDLastName);
	$smarty->assign('name_last',$name_last);
	$smarty->assign('LDFirstName',$LDFirstName);
	$smarty->assign('name_first',$name_first);

	# Set a row span counter, initialize with 6
	$iRowSpan = 6;

	if($GLOBAL_CONFIG['patient_name_2_show']&&$name_2){
		$smarty->assign('LDName2',$LDName2);
		$smarty->assign('name_2',$name_2);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_3_show']&&$name_3){
		$smarty->assign('LDName3',$LDName3);
		$smarty->assign('name_3',$name_3);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_middle_show']&&$name_middle){
		$smarty->assign('LDNameMid',$LDNameMid);
		$smarty->assign('name_middle',$name_middle);
		$iRowSpan++;
	}
	$smarty->assign('sRowSpan',"rowspan=\"$iRowSpan\"");

	$smarty->assign('LDBday',$LDBday);
	$smarty->assign('sBdayDate',@formatDate2Local($date_birth,$date_format));

	$smarty->assign('LDSex',$LDSex);
	if($sex=='m') $smarty->assign('sSexType',$LDMale);
	elseif($sex=='f') $smarty->assign('sSexType',$LDFemale);

	$smarty->assign('LDBloodGroup',$LDBloodGroup);
	if($blood_group){
		$buf='LD'.$blood_group;
		$smarty->assign('blood_group',$$buf);
	}
$smarty->assign('LDPlsEnterReferer',$LDPlsEnterReferer);
    //*
$smarty->assign('LDPlsEnterRefererDiagnosis',$LDPlsEnterRefererDiagnosis);
    $smarty->assign('LDPlsSelectAdmissionType', $LDPlsSelectAdmissionType);
	$smarty->assign('LDAddress',$LDAddress);

	$smarty->assign('addr_str',$addr_str);
	$smarty->assign('addr_str_nr',$addr_str_nr);
	$smarty->assign('addr_zip',$addr_zip);
	$smarty->assign('addr_citytown',$addr_citytown_name);
	$smarty->assign('addr_quanhuyen',$addr_quanhuyen_name);
	$smarty->assign('addr_phuongxa',$addr_phuongxa_name);
	
	$smarty->assign('LDAdmitClass',$LDAdmitClass);

	if(is_object($encounter_classes)){
		$sTemp = '';
		while($result=$encounter_classes->FetchRow()) {
			$LD=$result['LD_var'];
			if($encounter_nr ){ # If admitted, freeze encounter class
				if ($encounter_class_nr==$result['class_nr']){
					if(isset($$LD)&&!empty($$LD)) $sTemp = $sTemp.$$LD;
					else $sTemp = $sTemp.$result['name'];
					$sTemp = $sTemp.'<input name="encounter_class_nr" type="hidden"  value="'.$encounter_class_nr.'">';
					break;
				}
			}else{
				$sTemp = $sTemp.'<input name="encounter_class_nr" onClick="resolveLoc()" type="radio"  value="'.$result['class_nr'].'" ';
				if($encounter_class_nr==$result['class_nr']) $sTemp = $sTemp.'checked';
				$sTemp = $sTemp.'>';

				if(isset($$LD)&&!empty($$LD)) $sTemp = $sTemp.$$LD;
				else $sTemp = $sTemp.$result['name'];
			}
		}
		$smarty->assign('sAdmitClassInput',$sTemp);
	} 
	//echo $encounter_obj->InBed($encounter_nr,$current_ward_nr);
	//edit 21-11  vy 
		if ( $errorward ) $smarty->assign('LDDepartment',"<font color=red>$LDDepartment</font>");
		else $smarty->assign('LDDepartment',"$LDDepartment");
		$sTemp = '';		
		$sTemp = $sTemp.'<select id="current_dept_nr" style="width:96%;"name="current_dept_nr" onclick="showWard()">';			
			if(is_object($all_meds)){
				while($deptrow=$all_meds->FetchRow()){
					if($current_dept_nr==$deptrow['nr']) $selected=' selected '; else $selected=' ';
						$sTemp = $sTemp.'<option value="'.$deptrow['nr'].'"  '.$selected.' >'.$$deptrow['LD_var'].'</option>';				
				}
			}
			$sTemp = $sTemp.'</select>';	
		$smarty->assign('sDeptInput',$sTemp);

		if ($errorward) $smarty->assign('LDWard',"<font color=red>$LDPavijon</font>");
		$smarty->assign('LDWard',$LDWard);
	if(!empty($current_ward_nr)){
		$sTemp='<select name="current_ward_nr" id="current_ward_nr" style="width:96%;">
				<option value="'.$current_ward_nr.'">'.$ward_obj->WardName($current_ward_nr).'</option>
		';
	}else{
		$sTemp='<select name="current_ward_nr" id="current_ward_nr" style="width:96%;">
				<option value="0"></option>
		';
		}
		$smarty->assign('sWardInput',$sTemp);
	  // End of if no encounter nr
		
	//add by vy 24-11 start
	$smarty->assign('LDDiagnosis',"<font color=red>$LDDiagnosis</font>");
	
	$sBuf='<div style="float:left;width:14%;">
			<input type="text" size="8" id="referrer_diagnosis_code" name="referrer_diagnosis_code" value="'.$referrer_diagnosis_code.'" onblur="fill(this,this.value,\'referrer_diagnosis\');" >	
			<p></p>			
			<input type="button" value="ICD10" onclick="getref('.$encounter_nr.',\'referrer_diagnosis\')" >		
			</div>';

	
	//$smarty->assign('referrer_diagnosis',' '.$sBuf.'<textarea name="referrer_diagnosis" id="referrer_diagnosis" style="width:85%;" rows="3">'.$referrer_diagnosis.'</textarea>');
	$smarty->assign('referrer_diagnosis',' '.$sBuf.'<input  type="text" name="referrer_diagnosis" id="referrer_diagnosis" style="width:85%;" value="'.$referrer_diagnosis.'" ondblclick="this.value=\'\'" autocomplete="off">');
	
	
	$smarty->assign('LDBenhphu',"Bệnh phụ (nếu có)");
	
	$sBuf='<div style="float:left;width:14%;">
			<input type="text" size="8" id="benhphu_code" name="benhphu_code" value="'.$benhphu_code.'" onblur="fill(this,this.value,\'benhphu\');" >	
			<p></p>			
			<input type="button" value="ICD10" onclick="getref('.$encounter_nr.',\'benhphu\')" >		
			</div>';

	
	//$smarty->assign('benhphu',' '.$sBuf.'<textarea name="benhphu" id="benhphu" style="width:85%;" rows="3">'.$benhphu.'</textarea>');
	$smarty->assign('benhphu',' '.$sBuf.'<textarea type="text" name="benhphu" id="benhphu" style="width:85%;"  ondblclick="this.value=\'\'" autocomplete="off" >'.$benhphu.'</textarea>');
	
		$smarty->assign('LDTienluong',$LDTienluong);
		if($tienluong=='1'){
	$smarty->assign('tienluong','<input type="radio" value="1" name="tienluong" checked >Xấu
								<input type="radio" value="2" name="tienluong">Trung bình
								<input type="radio" value="3" name="tienluong">Khá
								<input type="radio" value="4" name="tienluong">Tốt');
	}elseif($tienluong=='2'){
	$smarty->assign('tienluong','<input type="radio" value="1" name="tienluong">Xấu
								<input type="radio" value="2" name="tienluong" checked >Trung bình
								<input type="radio" value="3" name="tienluong">Khá
								<input type="radio" value="4" name="tienluong">Tốt');
	}elseif($tienluong=='3'){
	$smarty->assign('tienluong','<input type="radio" value="1" name="tienluong">Xấu
								<input type="radio" value="2" name="tienluong">Trung bình
								<input type="radio" value="3" name="tienluong" checked >Khá
								<input type="radio" value="4" name="tienluong">Tốt');
	}elseif($tienluong=='4'){
	$smarty->assign('tienluong','<input type="radio" value="1" name="tienluong">Xấu
								<input type="radio" value="2" name="tienluong">Trung bình
								<input type="radio" value="3" name="tienluong">Khá
								<input type="radio" value="4" name="tienluong" checked >Tốt');
	}else{
	$smarty->assign('tienluong','<input type="radio" value="1" name="tienluong">Xấu
								<input type="radio" value="2" name="tienluong">Trung bình
								<input type="radio" value="3" name="tienluong">Khá
								<input type="radio" value="4" name="tienluong">Tốt');
	}
	$smarty->assign('LDTTBA',$LDTTBA);
	$smarty->assign('ttba','<textarea style="width:98%;" maxlength="255" name="tomtat_benhan" rows="1">'.$tomtat_benhan.'</textarea>');//end
	//$sBuff ="<a href=\"javascript:popSearchPer('referrer_dr')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
	$smarty->assign('LDLoaiKham',"Loại khám:");
	$smarty->assign('sSelectKham','<select name="loai_kham"><option value="1">Khám nội</option><option value="2">Khám ngoại</option></select>');
	if($errorreferrer) $smarty->assign('LDRecBy',"<font color=red>$LDRecBy</font>");
	 else $smarty->assign('LDRecBy',$LDRecBy);
	
	$smarty->assign('referrer_name','<input name="referrer_name" type="text" style="width:96%;" value="'.$referrer_name.'" >');
 //add 0310 - cot
	if(!isset($doctor_nr)) $doctor_nr = $currbsinfo['doctornr'];
	if(!isset($doctor_name)) $doctor_name = $currbsinfo['fname'];
	/* add 20-04-2012*/
	
	$sBuf ="<a href=\"javascript:popDocPer('doctor_nr')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
	$smarty->assign('LDReferrDoc',"<font color=red>$LDReferrDoc</font>");
	$smarty->assign('doctor_name','<input name="doctor_nr" type="hidden"value="'.$doctor_nr.'"><input name="doctor_name" type="text" style="width:90%;" value="'.$doctor_name.'" readonly>'.$sBuf);
	$smarty->assign('LDTherapy',$LDTherapy);
	$smarty->assign('referrer_recom_therapy','<input maxlength="255" name="referrer_recom_therapy" type="text" style="width:96%;" value="'.$referrer_recom_therapy.'">');
	$smarty->assign('LDSpecials',$LDSpecials);
	$smarty->assign('referrer_notes','<input maxlength="255" name="referrer_notes" type="text" style="width:96%;" value="'.$referrer_notes.'">');
	
	//add 9-11 start
	$smarty->assign('LDLidovaovien',"<font color=red>$LDLidovaovien</font>");
	
	$smarty->assign('lidovaovien','<input name="lidovaovien" type="text" style="width:96%;" value="'.$lidovaovien.'">');
	$smarty->assign('LDQuatrinhbenhly',"<font color=red>$LDQuatrinhbenhly</font>");
	
	$smarty->assign('quatrinhbenhly','<textarea name="quatrinhbenhly" style="width:96%;" rows="3">'.$quatrinhbenhly.'</textarea>');
	
	$smarty->assign('cbtcinsur','<tr id="cbtcinsur" style="display:none;"><td class="adm_item">Số thẻ BH</td><td class="adm_input"><input name="cbtcinsur" type="text" style="width:96%" value="'.$cbtcinsur.'"></td></tr>');
	if(($is_cbtc!='')&&($is_cbtc=='on')){
	$smarty->assign('sCanBoTrungCao','<input type="checkbox" onchange="check()" name="is_cbtc" id="is_cbtc" checked="checked"> CBTC');
	}else{
	$smarty->assign('sCanBoTrungCao','<input type="checkbox" onchange="check()" name="is_cbtc" id="is_cbtc"  > CBTC');
	}
	if(($is_tngt!='')&&($is_tngt=='on')){
	$smarty->assign('sTNGT','<input type="checkbox" name="is_tngt" checked="checked">  TNGT');
	}else{
	$smarty->assign('sTNGT','<input type="checkbox" name="is_tngt">  TNGT');
	}
	//$smarty->assign();
	if (!$GLOBAL_CONFIG['patient_service_care_hide']&& is_object($care_service)){
		$smarty->assign('LDCareServiceClass',$LDCareServiceClass);
		$sTemp = '';

		$sTemp = $sTemp.'<select name="sc_care_class_nr" >';

		while($buffer=$care_service->FetchRow()){
			$sTemp = $sTemp.'
						<option value="'.$buffer['class_nr'].'" ';
			if($sc_care_class_nr==$buffer['class_nr']) $sTemp = $sTemp.'selected';
			$sTemp = $sTemp.'>';
			if(empty($$buffer['LD_var'])) $sTemp = $sTemp.$buffer['name'];
			else $sTemp = $sTemp.$$buffer['LD_var'];
			$sTemp = $sTemp.'</option>';
		}
		$sTemp = $sTemp.'</select>';

		$smarty->assign('sCareServiceInput',$sTemp);

		$smarty->assign('LDFrom',$LDFrom);
		$sTemp = '';
		if(!empty($sc_care_start)) $sTemp = @formatDate2Local($sc_care_start,$date_format);

		$smarty->assign('sCSFromInput','<input type="text" name="sc_care_start"  value="'.$sTemp.'" size=9 maxlength=10   onBlur="IsValidDate(this,\''.$date_format.'\')" onKeyUp="setDate(this,\''.$date_format.'\',\''.$lang.'\')">');
		$smarty->assign('LDTo',$LDTo);
		$sTemp = '';
		if(!empty($sc_care_end)) $sTemp = @formatDate2Local($sc_care_end,$date_format);
		$smarty->assign('sCSToInput','<input type="text" name="sc_care_end"  value="'.$sTemp.'"  size=9 maxlength=10   onBlur="IsValidDate(this,\''.$date_format.'\')" onKeyUp="setDate(this,\''.$date_format.'\',\''.$lang.'\')">');
		$smarty->assign('sCSHidden','<input type="hidden" name="sc_care_nr" value="'.$sc_care_nr.'">');

	}

	if (!$GLOBAL_CONFIG['patient_service_room_hide']&& is_object($room_service)){
		$smarty->assign('LDRoomServiceClass',$LDRoomServiceClass);
		$sTemp = '';

		$sTemp = $sTemp.'<select name="sc_room_class_nr" >';

		while($buffer=$room_service->FetchRow()){
			$sTemp = $sTemp.'
						<option value="'.$buffer['class_nr'].'" ';
			if($sc_room_class_nr==$buffer['class_nr']) $sTemp = $sTemp.'selected';
			$sTemp = $sTemp.'>';
			if(empty($$buffer['LD_var'])) $sTemp = $sTemp.$buffer['name'];
			else $sTemp = $sTemp.$$buffer['LD_var'];
			$sTemp = $sTemp.'</option>';
		}
		$sTemp = $sTemp.'</select>';

		$smarty->assign('sCareRoomInput',$sTemp);

		//$smarty->assign('LDFrom',$LDFrom);
		$sTemp = '';
		if(!empty($sc_room_start)) $sTemp = @formatDate2Local($sc_room_start,$date_format);

		$smarty->assign('sRSFromInput','<input type="text" name="sc_room_start"  value="'.$sTemp.'" size=9 maxlength=10   onBlur="IsValidDate(this,\''.$date_format.'\')" onKeyUp="setDate(this,\''.$date_format.'\',\''.$lang.'\')">');
		//$smarty->assign('LDTo',$LDTo);
		$sTemp = '';
		if(!empty($sc_room_end)) $sTemp = @formatDate2Local($sc_room_end,$date_format);
		$smarty->assign('sRSToInput','<input type="text" name="sc_room_end"  value="'.$sTemp.'"  size=9 maxlength=10   onBlur="IsValidDate(this,\''.$date_format.'\')" onKeyUp="setDate(this,\''.$date_format.'\',\''.$lang.'\')">');
		$smarty->assign('sRSHidden','<input type="hidden" name="sc_room_nr" value="'.$sc_room_nr.'">');

	}

	if (!$GLOBAL_CONFIG['patient_service_att_dr_hide']&& is_object($att_dr_service)){
		$smarty->assign('LDAttDrServiceClass',$LDAttDrServiceClass);
		$sTemp = '';

		$sTemp = $sTemp.'<select name="sc_att_dr_class_nr" >';

		while($buffer=$att_dr_service->FetchRow()){
			$sTemp = $sTemp.'
						<option value="'.$buffer['class_nr'].'" ';
			if($sc_att_dr_class_nr==$buffer['class_nr']) $sTemp = $sTemp.'selected';
			$sTemp = $sTemp.'>';
			if(empty($$buffer['LD_var'])) $sTemp = $sTemp.$buffer['name'];
			else $sTemp = $sTemp.$$buffer['LD_var'];
			$sTemp = $sTemp.'</option>';
		}
		$sTemp = $sTemp.'</select>';

		$smarty->assign('sCareDrInput',$sTemp);

		$sTemp = '';
		if(!empty($sc_att_dr_start)) $sTemp = @formatDate2Local($sc_att_dr_start,$date_format);

		$smarty->assign('sDSFromInput','<input type="text" name="sc_att_dr_start"  value="'.$sTemp.'" size=9 maxlength=10   onBlur="IsValidDate(this,\''.$date_format.'\')" onKeyUp="setDate(this,\''.$date_format.'\',\''.$lang.'\')">');
		$sTemp = '';
		if(!empty($sc_att_dr_end)) $sTemp = @formatDate2Local($sc_att_dr_end,$date_format);
		$smarty->assign('sDSToInput','<input type="text" name="sc_att_dr_end"  value="'.$sTemp.'"  size=9 maxlength=10   onBlur="IsValidDate(this,\''.$date_format.'\')" onKeyUp="setDate(this,\''.$date_format.'\',\''.$lang.'\')">');
		$smarty->assign('sDSHidden','<input type="hidden" name="sc_att_dr_nr" value="'.$sc_att_dr_nr.'">');

	}
		
	if ($GLOBAL_CONFIG['show_billable_items'] && $encounter_class_nr == 2){
		$smarty->assign('LDAdmitBillItem',$LDAdmitBillItem);
		$eComBillItems = $eComBill_obj->listServiceItems();
		$eComBillsForPatient = $eComBill_obj->listBillsByEncounter($encounter_nr);

		if(isset($eComBillsForPatient) && !empty($eComBillsForPatient)) $bi = $eComBillsForPatient->Fields("bill_item_code");

		$sTemp = '';
		$sTemp = $sTemp.'<select name="billable_item_list"';
		if($update) $sTemp .= 'disabled="disabled"';
		$sTemp .= '>';

		while($buffer = $eComBillItems->FetchRow()){
			$sTemp = $sTemp.'
						<option value="'.$buffer['item_code'].'" ';
			if($bi == $buffer['item_code']) $sTemp = $sTemp.'selected';
			$sTemp = $sTemp.'>';
			$sTemp = $sTemp . $buffer['item_code'] . ' - ' . $buffer['item_description'];
			$sTemp = $sTemp.'</option>';
		}
		$sTemp = $sTemp.'</select>';

		$smarty->assign('sBIFromInput',$sTemp);
		$smarty->assign('sDSHidden','<input type="hidden" name="billable_item_hidden" value="'.$sc_billable_item.'">');
	}

	if ($GLOBAL_CONFIG['show_doctors_list'] && $encounter_class_nr == 2){
		$smarty->assign('LDAdmitDoctorRefered',$LDAdmitDoctorRefered);
		$personellItems = $personell_obj->_getAllPersonellByRole(17);

		$bufferBill = $encounter_obj->ReferredDoctor($encounter_nr);
		$tmpPersonell = $personell_obj->_getPersonellById($bufferBill);

		if(isset($tmpPersonell) && !empty($tmpPersonell)) $referred_dr = $tmpPersonell->Fields("personell_nr");

		$sTemp = '';
		$sTemp = $sTemp.'<select name="referred_dr_list"';
		if($update) $sTemp .= 'disabled="disabled"';
		$sTemp .= '>';
		while($buffer = $personellItems->FetchRow()) {
			$sTemp = $sTemp.'
						<option value="'.$buffer['personell_nr'].'" ';
			if($referred_dr == $buffer['personell_nr']) $sTemp = $sTemp.'selected';
			$sTemp = $sTemp.'>';
			$sTemp = $sTemp . $buffer['name_first'] . ' ' . $buffer['name_last'];
			$sTemp = $sTemp.'</option>';
		}
		$sTemp = $sTemp.'</select>';

		$smarty->assign('sRefDrFromInput',$sTemp);
		$smarty->assign('sRefDrHidden','<input type="hidden" name="referred_dr_hidden" value="'.$referred_dr.'">');
	}
		
	$smarty->assign('LDAdmitBy',$LDAdmitBy);
	if (empty($encoder)) $encoder = $_COOKIE[$local_user.$sid];
	
	$smarty->assign('encoder','<input  name="encoder" type="text" value="'.$encoder.'" size="28" readonly>');

	$sTemp = '<input type="hidden" name="pid" value="'.$pid.'">
	<input type="hidden" name="flag" value="0">
				<input type="hidden" name="encounter_nr" value="'.$encounter_nr.'">
				<input type="hidden" name="encounter_status" value="disallow_cancel">
				<input type="hidden" name="insurance_nr" value="'.$ins_infor['insurance_nr'].'">
				<input type="hidden" name="insurance_class_nr" value="'.$ins_infor['insurance_class_nr'].'">
				<input type="hidden" name="insurance_start" value="'.$ins_infor['insurance_start'].'">
				<input type="hidden" name="insurance_exp" value="'.$ins_infor['insurance_exp'].'">
				<input type="hidden" name="is_traituyen" value="'.$ins_infor['is_traituyen'].'">
				<input type="hidden" name="madkbd" value="'.$ins_infor['madkbd'].'">
				<input type="hidden" name="appt_nr" value="'.$appt_nr.'">
				<input type="hidden" name="sid" value="'.$sid.'">
				<input type="hidden" name="lang" value="'.$lang.'">
				<input type="hidden" name="mode" value="save">				
				<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000">'; // <-- this line gjergji

	if($update) $sTemp = $sTemp.'<input type="hidden" name=update value=1>';

	$smarty->assign('sHiddenInputs',$sTemp);

	$smarty->assign('pbSave','<input  type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').' title="'.$LDSaveData.'" align="absmiddle">');

	$smarty->assign('pbRegData','<a href="patient_register_show.php'.URL_APPEND.'&pid='.$pid.'"><img '.createLDImgSrc($root_path,'reg_data.gif','0').'  title="'.$LDRegistration.'"  align="absmiddle"></a>');
	$smarty->assign('pbCancel','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'cancel.gif','0').'  title="'.$LDCancel.'"  align="absmiddle"></a>');
	//<!-- Note: uncomment the ff: line if you want to have a reset button  -->
	/*<!--
	$smarty->assign('pbRefresh','<a href="javascript:document.aufnahmeform.reset()"><img '.createLDImgSrc($root_path,'reset.gif','0').' alt="'.$LDResetData.'"  align="absmiddle"></a>');
	-->
	*/
		
	if($error==1)
	$smarty->assign('sErrorHidInputs','<input type="hidden" name="forcesave" value="1">
				<input  type="submit" value="'.$LDForceSave.'">');

	if (!($newdata)) {

		$sTemp = '
		<form action='.$thisfile.' method=post>
		<input type="hidden" name=sid value='.$sid.'>
		<input type="hidden" name=patnum value="">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type=submit value="'.$LDNewForm.'">
		</form>';

		$smarty->assign('sNewDataForm',$sTemp);
	}

}  // end of if !isset($pid...

# Prepare shortcut links to other functions

$smarty->assign('sSearchLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_daten_such.php'.URL_APPEND.'">'.$LDPatientSearch.'</a>');
$smarty->assign('sArchiveLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_list.php'.URL_APPEND.'&newdata=1&from=entry">'.$LDArchive.'</a>');

$smarty->assign('sMainBlockIncludeFile','registration_admission/admit_input.tpl');

$smarty->display('common/mainframe.tpl');
