<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'global_conf/areas_allow.php');

/* Set the allowed area basing on the target */
if($_SESSION['sess_login_username']!='admin'){
    require_once($root_path.'include/care_api_classes/class_access.php');
    $access= & new Access();
    $role= $access->checkNameRole($_SESSION['sess_login_username']);
    if((strpos($role['role_name'], 'Trưởng khoa')!='' || strpos($role['role_name'], 'Trưởng khoa')==0) && ( strpos($role['dept_nr'], '"$dept_nr"')!='' || $role['location_nr']=='$dept_nr')) 
        $allowedarea=&$allow_area['dean_op'];
    elseif((strpos($role['role_name'], 'Điều dưỡng trưởng')!='' || strpos($role['role_name'], 'Điều dưỡng trưởng')==0) && ( strpos($role['dept_nr'], '"$dept_nr"')!='' || $role['location_nr']=='$dept_nr'))
        $allowedarea=&$allow_area['head_nursing_op'];
    elseif((strpos($role['role_name'], 'Điều dưỡng hành chính')!='' || strpos($role['role_name'], 'Điều dưỡng hành chính')==0) && ( strpos($role['dept_nr'], '"$dept_nr"')!='' || $role['location_nr']=='$dept_nr'))
        $allowedarea=&$allow_area['administrative_nursing'];
    elseif((strpos($role['role_name'], 'Bác sĩ')!='' || strpos($role['role_name'], 'Bác sĩ')==0) && ( strpos($role['dept_nr'], '"$dept_nr"')!='' || $role['location_nr']=='$dept_nr'))
        $allowedarea=&$allow_area['doctors'];
    elseif((strpos($role['role_name'], 'Điều dưỡng')!='' || strpos($role['role_name'], 'Điều dưỡng')==0) && ( strpos($role['dept_nr'], '"$dept_nr"')!='' || $role['location_nr']=='$dept_nr'))
        $allowedarea=&$allow_area['nursings'];
}else{
    $allowedarea=&$allow_area['admin'];
}
$append="?sid=$sid&lang=$lang&from=pass"; 

switch($user_origin)
{
	case 'dutyplan': 
            $fileforward="nursing-or-dienstplan-planen.php".URL_REDIRECT_APPEND."&pmonth=$pmonth&pyear=$pyear&dept_nr=$dept_nr&ward_nr=$ward_nr";
            $title=$LDORNOCScheduler;
            break;
	case 'listpatient': 
            $fileforward="list_patient_op.php".URL_REDIRECT_APPEND."&pmonth=".$month."&pyear=".$year."";
            break;
        case 'liststast': 
            $fileforward="list_personell_stast.php".URL_REDIRECT_APPEND."&pmonth=".$month."&pyear=".$year."";
            break;
	default:{ header("Location:".$root_path."language/".$lang."/lang_".$lang."_invalid-access-warning.php"); exit;}
}

							
$thisfile=basename(__FILE__);

$breakfile=$root_path.'modules/nursing/nursing.php'.URL_APPEND;

$lognote="$title ok";

$userck='ck_op_dienstplan_user';

//reset cookie;
// reset all 2nd level lock cookies
setcookie($userck.$sid,'');
require($root_path.'include/core/inc_2level_reset.php');
setcookie(ck_2level_sid.$sid,'');

require($root_path.'include/core/inc_passcheck_internchk.php');
if ($pass=='check') 	
	include($root_path.'include/core/inc_passcheck.php');

$errbuf="Doctors $title";

require($root_path.'include/core/inc_passcheck_head.php');
?>
<BODY  onLoad="document.passwindow.userid.focus();" bgcolor=<?php echo $cfg['body_bgcolor']; ?>
<?php if (!$cfg['dhtml']){ echo ' link='.$cfg['idx_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['idx_txtcolor']; } ?>>
<FONT    SIZE=-1  FACE="Arial">

<P>

<img <?php echo createComIcon($root_path,'wheelchair.gif','0','top') ?>>
<FONT  COLOR="<?php echo$cfg['top_txtcolor'] ?>"  SIZE=6  FACE="verdana"> <b><?php echo $title ?></b></font>

<table width=100% border=0 cellpadding="0" cellspacing="0"> 

<?php require($root_path.'include/core/inc_passcheck_mask.php') ?>  

<p>
<!-- <img <?php echo createComIcon($root_path,'varrow.gif','0') ?>> <a href="<?php echo $root_path; ?>main/ucons.php<?php echo URL_APPEND; ?>"><?php echo "$LDIntro2 $title" ?></a><br>
<img <?php echo createComIcon($root_path,'varrow.gif','0') ?>> <a href="<?php echo $root_path; ?>main/ucons.php<?php echo URL_APPEND; ?>"><?php echo "$LDWhat2Do $title" ?></a><br>
 -->
<?php
require($root_path.'include/core/inc_load_copyrite.php');
?>

</FONT>


</BODY>
</HTML>
