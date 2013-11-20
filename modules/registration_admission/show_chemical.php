<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('NO_2LEVEL_CHK',1);
/**
* CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
* GNU General Public License
* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/

///$db->debug=1;

$thisfile=basename(__FILE__);
$breakfile='aufnahme_daten_zeigen.php'.URL_APPEND.'&encounter_nr='.$encounter_nr;
$returnfile='aufnahme_daten_zeigen.php'.URL_APPEND.'&encounter_nr='.$encounter_nr;

include_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=&new Encounter;
if(!isset($mode)){
	$mode='show';
	
} elseif($mode=='create' || $mode=='new' || $mode=='update') {
	include_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
	if(!isset($obj)) $obj=new PrescriptionMedipot;
	include_once($root_path.'include/core/inc_date_format_functions.php');
	
	if($_POST['prescribe_date']) $_POST['prescribe_date']=@formatDate2STD($_POST['prescribe_date'],$date_format);
	else $_POST['prescribe_date']=date('Y-m-d');

	$_POST['modify_id']=$_SESSION['sess_user_name'];

	//$db->debug=true;

	if($prescribe_date&&$diagnosis&&$totalday&&$total){
	//if ($_POST['prescribe_date']){
		include('./include/save_chemical.inc.php');
	}
	//end : gjergji
}

require('./include/init_show.php');
if(isset($current_encounter) && $current_encounter) { 
	$parent_admit=true; 
	$is_discharged=false;
	$current_encounter = $encounter_nr;
	$_SESSION['sess_en'] = $current_encounter;
}

$sql="SELECT pr.*, grp.group_pres 
			  FROM care_encounter AS e, 
				   care_person AS p, 
				   care_chemical_prescription_info AS pr, 
				   care_chemical_type_of_prescription AS grp  
			WHERE p.pid=e.pid 
				AND e.encounter_nr='".$encounter_nr."' 
				AND e.encounter_nr=pr.encounter_nr 
				AND pr.prescription_type = grp.prescription_type 
			ORDER BY pr.date_time_create";
			
	if($result_1=$db->Execute($sql)){
		$rows_1=$result_1->RecordCount();
	}else{ 
		//echo $sql; 
	}
	$rows= $rows_1;
	$subtitle=$LDListChemical;
	
//$noitru_ngoaitru = $enc_obj->getClassNr($encounter_nr); load het lam sang & CLS

$notestype='chemical';

$_SESSION['sess_file_return']=$returnfile;

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 


/* Load GUI page */
require('./gui_bridge/default/gui_show.php');




?>
