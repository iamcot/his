<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
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
  <frame name="SYSADMIN_WFRAME" src="<?php echo $root_path ?>/modules/laboratory/tkp-admi-welcome.php<?php echo URL_REDIRECT_APPEND ?>">
  <frame name="SYSADMIN_INDEX" src="<?php echo $root_path ?>/modules/laboratory/tkp-func-list.php<?php echo URL_REDIRECT_APPEND ?>">
<noframes>
<body>
</body>
</noframes>
</frameset>
<?php
}else{
?>
<frameset cols="15%,*">
  <frame name="SYSADMIN_INDEX" src="<?php echo $root_path ?>/modules/laboratory/tkp-func-list.php<?php echo URL_REDIRECT_APPEND ?>">
  <frame name="SYSADMIN_WFRAME" src="<?php echo $root_path ?>/modules/laboratory/tkp-admi-welcome.php<?php echo URL_REDIRECT_APPEND ?>">
<noframes>
<body>
</body>
</noframes>
</frameset>
<?php
}
?>
</html>

