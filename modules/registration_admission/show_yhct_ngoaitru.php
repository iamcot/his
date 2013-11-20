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


require_once($root_path.'include/care_api_classes/class_khambenh.php');
$obj=new Khambenh;
$obj->KhamYHCTNgoaitru();
//$db->debug=1;

if(!isset($mode)){
	$mode='show';
} elseif($mode=='create'||$mode=='update') {
	# Prepare the posted data for saving in databank
	include_once($root_path.'include/core/inc_date_format_functions.php');
	# If date is empty,default to today
	if(empty($_POST['date'])){
		$_POST['date']=date('Y-m-d');
	}else{
		$_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	}
	
	
	# Prepare history
	$_POST['history']='Entry: '.date('Y-m-d H:i:s').' '.$_SESSION['sess_user_name'];
	$_POST['create_time']=date('H:i:s');
	
	
	$redirect=true;
	include('./include/save_admission_data1.inc.php');
}

require('./include/init_show.php');

$page_title='Bệnh án YHCT Ngoại trú';

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
	$sql="SELECT e.encounter_nr,e.is_discharged,ckm.*
		FROM 	care_encounter AS e,
					care_benhan_ngoaitru_yhct AS ckm
					
		WHERE  e.encounter_nr=".$encounter_nr."
			AND e.encounter_nr=ckm.encounter_nr 
			
			ORDER BY ckm.create_time DESC";

	if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()){
			# Resync the encounter_nr
			if($_SESSION['sess_en']!=$encounter_nr) $_SESSION['sess_en']=$encounter_nr;
			if($rows==1){
				$row=$result->FetchRow();
				if($row['is_discharged']) $edit=0;

				header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&mode=details&nolist=1&pid=$pid&encounter_nr=&encounter_nr&nr=".$row['nr']."&edit=$edit&is_discharged=".$row['is_discharged']);
				exit;
			}
		}
	}else{
		//echo "$LDDbNoRead<p>$sql";
	}
}elseif(($mode=='details')&&!empty($nr)){
	$sql="SELECT *
		FROM 	care_benhan_ngoaitru_yhct		WHERE   nr=$nr";

	if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()) $row=$result->FetchRow();
	}else{
		echo $sql;
	}
}

$subtitle=$LDMedocs;
	
$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 
$_SESSION['sess_file_return']=$thisfile;

# Set break file
require('include/inc_breakfile.php');

if($mode=='show') $glob_obj->getConfig('admit_%');
/* Load GUI page */
require('./gui_bridge/default/gui_show_yhct_ngoaitru.php');
?>
