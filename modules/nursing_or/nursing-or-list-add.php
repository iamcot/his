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
//define('LANG_FILE','doctors.php');
if($_SESSION['sess_user_origin']=='personell_admin'){
	$local_user='aufnahme_user';
	if ($local_user='aufnahme_user') define('NO_2LEVEL_CHK',1);
}else{
	$local_user='ck_op_dienstplan_user';
	if ($local_user='ck_op_dienstplan_user') define('NO_2LEVEL_CHK',1);
}
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
$pers_obj->useAssignmentTable();
$data=array();
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
//$db->debug=true;

if($mode!='delete'){
	$data['personell_nr']=$nr;
	$data['role_nr']=16; // 16 = nurse (role person)
	$data['location_type_nr']=1; // 1 = dept (location type)
	$data['location_nr']=$dept_nr;
	$data['date_start']=date('Y-m-d');
	$data['chucvu_nr']=$chucvu_nr;
}

$data['modify_id']=$_SESSION['sess_user_name'];

switch($mode){
	case 'save':
					$data['history']="Add: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n";
					$data['create_id']=$_SESSION['sess_user_name'];
					$data['create_time']=date('YmdHis');
					$pers_obj->setDataArray($data);
					if(!$pers_obj->insertDataFromInternalArray())  echo "$obj->sql<br>$LDDbNoSave";
					break;
	case 'update':
					$data['history']=$pers_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n");
					$pers_obj->setDataArray($data);
					$data['modfiy_id']=$_SESSION['sess_user_name'];
					$data['modify_time']=date('YmdHis');
					if(!$pers_obj->updateDataFromInternalArray($item_nr))  echo "$obj->sql<br>$LDDbNoUpdate";
					break;
	case 'delete':
					$data['status']='deleted';
					$data['date_end']=date('Y-m-d');
					$data['history']=$pers_obj->ConcatHistory("Deleted: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n");
					$data['modfiy_id']=$_SESSION['sess_user_name'];
					$data['modify_time']=date('YmdHis');
					$pers_obj->setDataArray($data);
					if(!$pers_obj->updateDataFromInternalArray($item_nr))  echo "$obj->sql<br>$LDDbNoUpdate";
}
$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pers_obj->getLastQuery(), date('Y-m-d H:i:s'));
header("location:nursing-or-dienst-personalliste.php".URL_REDIRECT_APPEND."&saved=1&retpath=$retpath&ipath=$ipath&dept_nr=$dept_nr&nr=$nr");
exit;
?>
