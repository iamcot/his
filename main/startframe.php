<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
//$_SESSION['sess_path_referer']=str_replace($doc_root.'/','',__FILE__);
//echo __FILE__;
if(isset($_GET['page']))
	$page=$_GET['page'];
else $page=0;
header("Location:".$root_path."modules/news/start_page.php?sid=$sid&lang=$lang&page=".$page);
exit;
?>
