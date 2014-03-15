<?php
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/care_api_classes/class_core.php');
require_once($root_path.'include/care_api_classes/class_drg.php');
$drg = new DRG;
$key = $_POST['searchQuery'];
$rs = $drg->getICD10Fromkey($key);
if($rs){
	$str = "<ul>";
	while($row = $rs->FetchRow()) {
		$str .= '<li>'.$row['diagnosis_code'].' '.$row['description'].'</li>';
	}
	$str .= "</ul>";
	echo $str;
}

//end of file