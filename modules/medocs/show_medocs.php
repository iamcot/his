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
$thisfile=basename(__FILE__);

if(!isset($type_nr)||!$type_nr) $type_nr=1; //* 1 = history physical notes

require_once($root_path.'include/care_api_classes/class_notes.php');
$obj=new Notes;
$types=$obj->getAllTypesSort('name');
$this_type=$obj->getType($type_nr);

//$db->debug=1;

if(!isset($mode)){
	$mode='show';
} elseif(($mode=='create'|| $mode=='new'|| $mode=='update') &&!empty($_POST['date']) &&!empty($_POST['personell_name'])) {
	# Prepare the posted data for saving in databank
	include_once($root_path.'include/core/inc_date_format_functions.php');
	# If date is empty,default to today
	if(empty($_POST['date'])){
		$_POST['date']=date('Y-m-d');
	}else{
		$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	}
	
	# Prune the aux_notes  data to max 255
		$_POST['aux_notes']=substr($_POST['aux_notes'],0,255);

	$redirect=true;
	include('./include/save_admission_data.inc.php');
}

require('./include/init_show.php');

$page_title=$LDMedocs1;

# Load the entire encounter data
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($encounter_nr);
$enc_obj->loadEncounterData();
# Get encounter class
$enc_class=$enc_obj->EncounterClass();
/*if($enc_class==2)  $_SESSION['sess_full_en']=$GLOBAL_CONFIG['patient_outpatient_nr_adder']+$encounter_nr;
	else $_SESSION['sess_full_en']=$GLOBAL_CONFIG['patient_inpatient_nr_adder']+$encounter_nr;
*/
$_SESSION['sess_full_en']=$encounter_nr;
	
if(empty($encounter_nr)&&!empty($_SESSION['sess_en'])){
	$encounter_nr=$_SESSION['sess_en'];
}elseif($encounter_nr) {
	$_SESSION['sess_en']=$encounter_nr;
}
	
if($mode=='show') 
{
	$sql="SELECT e.encounter_nr, e.is_discharged, nd.*, type.sort_nr  
		FROM   care_encounter AS e, care_type_notes AS type, care_encounter_notes AS nd
		WHERE  e.encounter_nr='".$encounter_nr."'
			AND e.encounter_nr=nd.encounter_nr 
			AND nd.type_nr = type.nr 
			AND (nd.type_nr IN (8,9,10,11,13,14,22,23,36,37,38,39,40))
			ORDER BY type.sort_nr";

		/* 12 = text_diagnosis type of notes 
		*  13 = text_therapy type of notes
		*/
	if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()){
			# Resync the encounter_nr
			if($_SESSION['sess_en']!=$encounter_nr) $_SESSION['sess_en']=$encounter_nr;
			/*if($rows==1){
				$row=$result->FetchRow();
				if($row['is_discharged']) $edit=0;

				header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&mode=details&nolist=1&pid=$pid&encounter_nr=&encounter_nr&nr=".$row['nr']."&edit=$edit&is_discharged=".$row['is_discharged']);
				exit;
			}*/
		}
	}else{
		echo "$LDDbNoRead<p>$sql";
	}
}elseif(($mode=='update')&&!empty($encounter_nr)){
	$sql="SELECT nd.*, tp.sort_nr 
		FROM care_encounter_notes AS nd, care_type_notes AS tp
		WHERE nd.encounter_nr='$encounter_nr'
		AND nd.type_nr = tp.nr 		
		AND (nd.type_nr IN (8,9,10,11,13,14,22,23,36,37,38,39,40))
		ORDER BY tp.sort_nr";

	if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()) $row=$result->FetchRow();
	}else{
		echo $sql;
	}
}

$subtitle=$LDMedocs1;
	
$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 
$_SESSION['sess_file_return']=$thisfile;

# Set break file
require('include/inc_breakfile.php');

if($mode=='show') $glob_obj->getConfig('medocs_%');
/* Load GUI page */
require('./gui_bridge/default/gui_show_medocs.php');
?>
