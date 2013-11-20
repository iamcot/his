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

$local_user='ck_edv_user';
if ($local_user='ck_edv_user') define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
if(isset($ck_edv_admin_user)) setcookie('ck_edvzugang_user',$ck_edv_admin_user);
?>
<?php html_rtl($lang); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title></title>
</head>
<?php
if($lang=='ar'||$lang=='fa'){
?>
<frameset cols="*,15%">
  <frame name="SYSADMIN_WFRAME" src="<?php echo $root_path ?>/modules/timekeeping/tkp-admi-welcome.php<?php echo URL_REDIRECT_APPEND ?>">
  <frame name="SYSADMIN_INDEX" src="<?php echo $root_path ?>/modules/timekeeping/tkp-func-list.php<?php echo URL_REDIRECT_APPEND ?>">
<noframes>
<body>
</body>
</noframes>
</frameset>
<?php
}else{
?>
<frameset cols="15%,*">
  <frame name="SYSADMIN_INDEX" src="<?php echo $root_path ?>/modules/timekeeping/tkp-func-list.php<?php echo URL_REDIRECT_APPEND ?>">
  <frame name="SYSADMIN_WFRAME" src="<?php echo $root_path ?>/modules/timekeeping/tkp-admi-welcome.php<?php echo URL_REDIRECT_APPEND ?>">
<noframes>
<body>
</body>
</noframes>
</frameset>
<?php
}
?>
</html>
