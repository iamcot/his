<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
if($_COOKIE['ck_login_logged'.$sid]!=true) //chưa đăng nhập
{
    header("Location:".$root_path."main/login.php?sid=$sid&lang=$lang");

}
else header("Location:".$root_path."modules/pharmacy/pharmacy.php?".URL_APPEND);
exit;
?>