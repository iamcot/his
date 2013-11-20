<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
* GNU General Public License
* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables=array('departments.php');
define('LANG_FILE','stdpass.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

/* Set the default file forward */
$fileforward=$root_path."main/op-doku_1.php".URL_APPEND;

$thisfile='op_test_request_pass.php';

# Set the breakfile

$test_pass_logo='micros.gif';
# Refilter
switch($user_origin){
    case 'op':
        $title=$LDPendingRequest." - ".$LDTestType[$subtarget];
        $fileforward="op_test_request_admin.php".URL_REDIRECT_APPEND."&subtarget=".$subtarget."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr."&noresize=1&&user_origin=".$user_origin;
        break;
    case 'op_done':
        $title=$LDPendingRequest." - ".$LDTestType[$subtarget];
        $fileforward="op_test_request_admin_done.php".URL_REDIRECT_APPEND."&subtarget=".$subtarget."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr."&noresize=1&&user_origin=".$user_origin;
        break;
    case 'op_e_kip':
        $title=$LDPendingRequest." - ".$LDTestType[$subtarget];
        $fileforward="op_list_e_kip.php".URL_REDIRECT_APPEND."&subtarget=".$subtarget."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr."&user_origin=".$user_origin;
        break;
    case 'search':
        $fileforward=$root_path."modules/or_logbook/op-pflege-logbuch-xtsuch-start.php".URL_REDIRECT_APPEND."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr;
        break;
    case 'stast':
        $fileforward="listall_for_personell.php".URL_REDIRECT_APPEND."&dept_nr=".$dept_nr."&ward_nr=".$ward_nr;
        break;
	default:            
        break;
}
$lognote="$title ok";

//reset cookie;
// reset all 2nd level lock cookies
$userck='ck_opdoku_user';
setcookie($userck.$sid,'');
require($root_path.'include/core/inc_2level_reset.php');
setcookie('ck_2level_sid'.$sid,'');
require($root_path.'include/core/inc_passcheck_internchk.php');
if ($pass=='check') 	
	include($root_path.'include/core/inc_passcheck.php');
$errbuf=$title;
$minimal=1;
require_once($root_path.'include/core/inc_config_color.php');
require($root_path.'include/core/inc_passcheck_head.php');
?>
<BODY  onLoad="document.passwindow.userid.focus();" bgcolor=<?php echo $cfg['body_bgcolor']; ?>
<?php if (!$cfg['dhtml']){ echo ' link='.$cfg['idx_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['idx_txtcolor']; } ?>>
<?php
    $maskBorderColor='#66ee66';
    echo '<table width=100% border=0 cellpadding="0" cellspacing="0"> ';
    require($root_path.'include/core/inc_passcheck_mask.php');
    echo '<br>';
    require($root_path.'include/core/inc_load_copyrite.php');
?>
</BODY>
</HTML>