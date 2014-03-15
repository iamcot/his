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

# Set defaults
if(!isset($wt_unit_nr)||!$wt_unit_nr) $wt_unit_nr=6; # set your default unit of weight msrmnt type, default 6 = kilogram
if(!isset($ht_unit_nr)||!$ht_unit_nr) $ht_unit_nr=7; # set your default unit of height msrmnt type, default 7 = centimeter
if(!isset($hc_unit_nr)||!$hc_unit_nr) $hc_unit_nr=7; # set your default unit of head circumfernce msrmnt type, default 7 = centimeter
if(!isset($sc_unit_nr)||!$sc_unit_nr) $sc_unit_nr= 14;#set default unit of Huyet Ap mmHG
if(!isset($dc_unit_nr)|| !$dc_unit_nr) $dc_unit_nr = 19;#set defaul unit of Mach Number of times per minute 19
if(!isset($tp_unit_nr)|| !$tp_unit_nr) $tp_unit_nr = 15;#set default unit of Nhiet do 15, do C
if(!isset($br_unit_nr) || !$br_unit_nr ) $br_unit_nr = 19;#set default unit of nhip tho, 19 

$thisfile=basename(__FILE__);
include_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=&new Encounter;
  require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
require_once($root_path.'include/care_api_classes/class_measurement.php');
$obj=new Measurement;
$unit_types=$obj->getUnits();
# Prepare unit ids in array
$unit_ids=array();
while(list($x,$v)=each($unit_types)){
	$unit_ids[$v['nr']]=$v['id'];
}
reset($unit_types);
if($typenew=='temp') 
{
	$obj->setTempTable();
	$_POST['status'] = 0;
}
if(!isset($mode)){
	$mode='show';
}elseif($mode=='create'||$mode=='update') {

	include_once($root_path.'include/core/inc_date_format_functions.php');
	if($_POST['msr_date']) 	$_POST['msr_date']=@formatDate2STD($_POST['msr_date'],$date_format);
		else $_POST['msr_date']=date('Y-m-d');
	
	# Non standard time format
	$_POST['msr_time']=date('H:i:s');
	$_POST['create_time']=date('YmdHis'); # Create the timestamp to group the values
	$_POST['create_id']=$_SESSION['sess_user_name'];

	if($weight||$height||$head_c||$huyetap_c||$mach_c||$nhietdo_c||$nhiptho_c){
		# Set to no redirect
		$no_redirect=TRUE;
		
		if($weight){
			$_POST['value']=$weight;
			$_POST['msr_type_nr']=6; # msrmt type 6 = weight
			$_POST['notes']=$wt_notes;
			$_POST['unit_nr']=$wt_unit_nr;
			$_POST['unit_type_nr']=2; # 2 = weight
			include('./include/save_admission_data.inc.php');
		}
		if($height){
			$_POST['value']=$height;
			$_POST['msr_type_nr']=7;  # msrmt type 7 = height
			$_POST['notes']=$ht_notes;
			$_POST['unit_nr']=$ht_unit_nr;
			$_POST['unit_type_nr']=3; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
		if($head_c){
			$_POST['value']=$head_c;
			$_POST['msr_type_nr']=9; # msrmt type 9 = head circumference
			$_POST['notes']=$hc_notes;
			$_POST['unit_nr']=$hc_unit_nr;
			$_POST['unit_type_nr']=3; # 3 = length
			include('./include/save_admission_data.inc.php');
		}
		//cot
		if($huyetap_c){
			$_POST['value']=$huyetap_c;
			$_POST['msr_type_nr']=1; # msrmt type 1 = huyet ap
			$_POST['notes']=$huyetap_notes;
			$_POST['unit_nr']=$sc_unit_nr;
			$_POST['unit_type_nr']=4; # 4 = ap luc
			include('./include/save_admission_data.inc.php');
		}
		if($mach_c){
			$_POST['value']=$mach_c;
			$_POST['msr_type_nr']=2; # msrmt type 2 = mach
			$_POST['notes']=$mach_notes;
			$_POST['unit_nr']=$dc_unit_nr;
			$_POST['unit_type_nr']=6; # 6 = tan suat
			include('./include/save_admission_data.inc.php');
		}
		if($nhietdo_c){
			$_POST['value']=$nhietdo_c;
			$_POST['msr_type_nr']=3; # msrmt type 3 = nhiet do
			$_POST['notes']=$nhietdo_notes;
			$_POST['unit_nr']=$tp_unit_nr;
			$_POST['unit_type_nr']=5; # 5 =nhiet do
			include('./include/save_admission_data.inc.php');
		}
		if($nhiptho_c){
			$_POST['value']=$nhiptho_c;
			$_POST['msr_type_nr']=10; # msrmt type 10 = breath
			$_POST['notes']=$nhiptho_notes;
			$_POST['unit_nr']=$br_unit_nr;
			$_POST['unit_type_nr']=6; # 
			include('./include/save_admission_data.inc.php');
		}
	
		header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&allow_update=1&pid=".$_SESSION['sess_pid']);
		exit;
	}
}

$lang_tables[]='obstetrics.php';
require('./include/init_show.php');
if(isset($current_encounter) && $current_encounter) { 
	$parent_admit=true; 
	$is_discharged=false;
	$_SESSION['sess_en'] = $current_encounter;
}
if($mode=='show'){
/*
	$sql="SELECT m.nr,m.value,m.msr_date,m.msr_time,m.unit_nr,m.encounter_nr,m.msr_type_nr,m.create_time, m.notes
		FROM 	care_encounter AS e, 
					care_person AS p, 
					care_encounter_measurement AS m
		WHERE p.pid=".$_SESSION['sess_pid']." 
			AND p.pid=e.pid 
			AND e.encounter_nr=m.encounter_nr  
			AND (m.msr_type_nr=6 OR m.msr_type_nr=7 OR m.msr_type_nr=9)
		ORDER BY m.msr_date DESC";
*/		
$sql="SELECT m.nr,m.value,m.msr_date,m.msr_time,m.unit_nr,m.encounter_nr,m.msr_type_nr,m.create_time, m.notes
		FROM 	care_encounter AS e, 
					care_person AS p, 
					care_encounter_measurement AS m
		WHERE e.encounter_nr=".$_SESSION['sess_en']."
			AND p.pid=e.pid 
			AND e.encounter_nr=m.encounter_nr  
			AND (m.msr_type_nr=6 OR m.msr_type_nr=7 OR m.msr_type_nr=9 OR m.msr_type_nr=1 OR m.msr_type_nr=2 OR m.msr_type_nr=3  OR m.msr_type_nr=10 )
		ORDER BY m.msr_date DESC, m.msr_time DESC";
	if($result=$db->Execute($sql)){
		if($rows=$result->RecordCount()){
			while($msr_row=$result->FetchRow()){
			//print_r($msr_row);
				# group the elements
				$msr_comp[$msr_row['create_time']]['encounter_nr']=$msr_row['encounter_nr'];
				$msr_comp[$msr_row['create_time']]['msr_date']    =$msr_row['msr_date'];
				$msr_comp[$msr_row['create_time']]['msr_time']    =$msr_row['msr_time'];
				//$msr_comp[$msr_row['create_time']]['msr_type_nr'] =$msr_row['msr_type_nr'];
				$msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]['value'] = $msr_row['value'];
				$msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]['notes'] = $msr_row['notes'];
				$msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]['unit_nr'] = $msr_row['unit_nr'];
				
			}
		}
	}
	$sql="SELECT * FROM dfck_person_measurement 
			WHERE pid = '".$_SESSION['sess_pid']."' AND STATUS=0  ORDER BY msr_date DESC, msr_time DESC";
	if($result=$db->Execute($sql)){
		if($rowstmp=$result->RecordCount()){
			while($msr_row=$result->FetchRow()){
			//print_r($msr_row);
				# group the elements
				$msr_comp[$msr_row['create_time']]['pid']=$msr_row['pid'];
				$msr_comp[$msr_row['create_time']]['msr_date']    =$msr_row['msr_date'];
				$msr_comp[$msr_row['create_time']]['msr_time']    =$msr_row['msr_time'];
				//$msr_comp[$msr_row['create_time']]['msr_type_nr'] =$msr_row['msr_type_nr'];
				$msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]['value'] = $msr_row['value'];
				$msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]['notes'] = $msr_row['notes'];
				$msr_comp[$msr_row['create_time']][$msr_row['msr_type_nr']]['unit_nr'] = $msr_row['unit_nr'];
				
			}
		}
	}

}
//var_dump($msr_comp);
//print_r($msr_comp);
# set your default unit of msrmnt type, default 6 = kilogram
if(!isset($wt_unit_nr)||!$wt_unit_nr) $wt_unit_nr=6;

# set your default unit of msrmnt type, default 7 = centimeter
if(!isset($ht_unit_nr)||!$ht_unit_nr) $ht_unit_nr=7;

# set your default unit of msrmnt type, default 7 = centimeter
if(!isset($hc_unit_nr)||!$hc_unit_nr) $hc_unit_nr=7;

if(!isset($sc_unit_nr)||!$sc_unit_nr) $sc_unit_nr= 14;#set default unit of Huyet Ap mmHG
if(!isset($dc_unit_nr)|| !$dc_unit_nr) $dc_unit_nr = 19;#set defaul unit of Mach Number of times per minute 19
if(!isset($tp_unit_nr)|| !$tp_unit_nr) $tp_unit_nr = 15;#set default unit of Nhiet do 15, do C
if(!isset($br_unit_nr) || !$br_unit_nr ) $br_unit_nr = 19;#set default unit of nhip tho, 19 

$subtitle=$LDMeasurements;

# Set the type of "notes"
$notestype='msr';

$_SESSION['sess_file_return']=$thisfile;

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 

# Load GUI page
require('./gui_bridge/default/gui_show.php');
?>
