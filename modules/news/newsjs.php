<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','startframe.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_news.php');
$newsobj=new News;

if(isset($_POST['itype']) && $_POST['itype']=='updatestatus'){
	$rs= $newsobj->updateNewsStatus($_POST['nr'],$_POST['status']);
	echo $rs;
}

//end of file
