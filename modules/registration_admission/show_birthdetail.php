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
$thisfile=basename(__FILE__);
require_once($root_path.'include/care_api_classes/class_obstetrics.php');
$obj=new Obstetrics;
require_once($root_path.'include/core/access_log.php');
require_once($root_path.'include/care_api_classes/class_access.php');
$logs = new AccessLog();
$user = & new Access();
//$db->debug=true;

if(!isset($allow_update)) $allow_update=false;

if(!isset($mode)){
	$mode='show';
}elseif($mode=='newdata') {
	
	include_once($root_path.'include/core/inc_date_format_functions.php');
	$saved=false;

	# Prepare additional info for saving
	# Create own timestamp for cross db compatibility
	if($_POST['docu_by']) $_POST['modify_id']=$_POST['docu_by'];
		else $_POST['modify_id']=$_SESSION['sess_user_name'];
        if(empty($_POST['date'])) $_POST['date']=date('Y-m-d');
		else $_POST['date']=@formatDate2STD($_POST['date'],$date_format);
	if(empty($_POST['delivery_time'])) $_POST['delivery_time']=date('H:i:s');
		else $_POST['delivery_time']=@convertTimeToStandard($_POST['delivery_time']);
        if(empty($_POST['tatbamsinh'])){
            $_POST['tatbamsinh']=0;
        }
        if(empty($_POST['cohaumon'])){
            $_POST['cohaumon']=0;
        }
        if(empty($_POST['sex'])){
            $_POST['sex']=0;
        }
	# Update child encounter to parent encounter
	if(!empty($_POST['parent_encounter_nr'])) $obj->AddChildNrToParent($_SESSION['sess_en'],$_POST['parent_encounter_nr'],$_POST);
	//echo $obj->getLastQuery();
	
	if($allow_update){
                $_POST['modify_id']=$_SESSION['sess_user_name'];
                $_POST['modify_time']=date('YmdHis');
		$obj->setWhereCondition('pid='.$_POST['pid'].' AND nr='.$_POST[nr]);
                $array=array('nr','pid','parent_encounter_nr','delivery_nr','sex','encounter_nr','delivery_place','delivery_mode','c_s_reason','date','delivery_time','born_before_arrival','face_presentation','posterio_occipital_position','delivery_rank','apgar_1_min', 'apgar_5_min', 'apgar_10_min', 'time_to_spont_resp', 'condition', 'weight', 'length', 'head_circumference', 'scored_gestational_disability', 'tatbamsinh', 'cohaumon', 'feeding', 'congenital_abnormality', 'classification', 'disease_category', 'outcome', 'history', 'status', 'modify_id', 'modify_time', 'create_id', 'create_time');
                $obj->setRefArray($array);
		$obj->setDataArray($_POST);

		if($obj->updateDataFromInternalArray($_POST['pid'])) {
                        $flag=true;
			$saved=true;
		}elseif($flag){
			echo $obj->getLastQuery."<br>$LDDbNoUpdate";
		}
	}else{
		# Deactivate the old record first if exists
		//$obj->deactivateBirthDetails($_SESSION['sess_pid']);
		
		$_POST['history']="Create ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n";
		if($_POST['docu_by']) $_POST['create_id']=$_POST['docu_by'];
			else $_POST['create_id']=$_SESSION['sess_user_name'];
		$_POST['create_time']=date('YmdHis'); # Create own timestamp for cross db compatibility
                $array=array('nr','pid','parent_encounter_nr','delivery_nr','sex','encounter_nr','delivery_place','delivery_mode','c_s_reason','date','delivery_time','born_before_arrival','face_presentation','posterio_occipital_position','delivery_rank','apgar_1_min', 'apgar_5_min', 'apgar_10_min', 'time_to_spont_resp', 'condition', 'weight', 'length', 'head_circumference', 'scored_gestational_disability', 'tatbamsinh', 'cohaumon', 'feeding', 'congenital_abnormality', 'classification', 'disease_category', 'outcome', 'history', 'status', 'modify_id', 'modify_time', 'create_id', 'create_time');
                $obj->setRefArray($array);
		$obj->setDataArray($_POST);

		if($obj->insertDataFromInternalArray()) {
			$saved=true;
		}else{
			echo $obj->getLastQuery();
			echo "<br>$LDDbNoSave";
		}		
	}
        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
	if($saved){
		header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&allow_update=1&pid=".$_SESSION['sess_pid']."&time=".strtotime(date("h:i:s")));
		exit;
	}
}

# Add extra language table
$lang_tables=array('obstetrics.php');
require('./include/init_show.php');
if(isset($current_encounter) && $current_encounter) { 
	$parent_admit=true; 
	$is_discharged=false;
	$_SESSION['sess_en'] = $current_encounter;
}
# Get all birth details data of the person
if($nr){
    $result_update=&$obj->BirthDetails1($_SESSION['sess_pid'], $nr);
    if($rows_update=$obj->LastRecordCount()){
        $birth_update=$result_update->FetchRow();
        $birth_update=$birth_update;
    }
}else{
    $result=&$obj->BirthDetails($_SESSION['sess_pid']);
    if($rows=$obj->LastRecordCount()){
        $birth=$result->FetchRow();
        $birth_update=$birth;
    }
}
$page_title=$LDBirthDetails;
$subtitle=$LDBirthDetails;

$_SESSION['sess_file_return']="aufnahme_daten_zeigen.php".URL_APPEND."&encounter_nr=".$_SESSION['sess_en']."&target=search";

$buffer=str_replace('~tag~',$title.' '.$name_last,$LDNoRecordFor);
$norecordyet=str_replace('~obj~',strtolower($subtitle),$buffer); 
/* Load GUI page */

require('./gui_bridge/default/gui_show.php');
?>
