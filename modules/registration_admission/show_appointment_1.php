<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
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
$thisfile=basename(__FILE__);
require_once($root_path.'include/care_api_classes/class_appointment.php');
$obj=new Appointment();
//$db->debug=true;

$bPastDateError = FALSE;

#
# Save PID to session. Patch as result of bug report from Francesco and Marco.
#
if((!isset($pid)||!$pid) && $_SESSION['sess_pid']) $pid=$_SESSION['sess_pid'];
	elseif(isset($pid) && $pid) $_SESSION['sess_pid']=$pid;

if(!isset($mode)){
	$mode='show';
}
$lang_tables=array('prompt.php','departments.php');
require('./include/init_show.php');

if($result=&$obj->getPersonsAppointmentsObj($pid)){
	$rows=$result->RecordCount();
}

# Load the encounter classes
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;

/* Get all encounter classes */
$encounter_classes=&$enc_obj->AllEncounterClassesObject();
$page_title=$LDRequestLAB;
$subtitle=$LDAppointments;
$_SESSION['sess_file_return']=$thisfile;

/* Load departments */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$deptarray=$dept_obj->getAllMedical('name_formal');

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordYet);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

/* Load GUI page */
require('./gui_bridge/default/gui_show_1.php');
?>
