<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('NO_2LEVEL_CHK',1);
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/

define('FILE_DISCRIM','.dcm'); # define here the file discrimator string 

$thisfile=basename(__FILE__);

$returnfile='view_person_search.php'.URL_REDIRECT_APPEND;

///$db->debug=1;

# Load paths und dirs
require_once($root_path.'global_conf/inc_remoteservers_conf.php');
# Create image object
require_once($root_path.'include/care_api_classes/class_image.php');
$img=new Image();
$img->useImgDiagnostic();

$mode='';	//reset value of $mode from upload.php & update.php


$lang_tables[]='radio.php';
$lang_tables[]='prompt.php';
require('./include/init_show.php');

$page_title=$LDDicomImagesTxt;

if($nr){
	//get path
	$a = $img->SelectImgDiagInfo($nr);
	$imgpath = $a['path'];
}

//if delete single img/ folder
if($target=='delete' && $img_temp!='' && $nr){
	//$imgpath=$root_path.$dicom_img_localpath.$pid."/$nr";
	if (!is_dir($imgpath."/".$img_temp)){
		unlink($imgpath."/".$img_temp);
		$img->updateImgMaxNr($nr,-1);
	}
}else if($target=='deleteall' && $nr){
	//$imgpath=$root_path.$dicom_img_localpath.$pid."/$nr";
	$img->__delete($nr);
	chmod($imgpath,0777); 
	unlink($imgpath."/index.htm");
	unlink($imgpath."/index.php");
	rmdir($imgpath);

	header("location:view_person_search.php".URL_REDIRECT_APPEND."&searchkey=$pid");
	exit;
}


if($nr) {
	//$imgpath=$root_path.$dicom_img_localpath.$pid."/$nr";
	$files=&$img->FilesListArray($imgpath,FILE_DISCRIM);
	$rows=$img->LastRecordCount();
}



# Default nr of files
if(!isset($maxpic)||!$maxpic||!is_numeric($maxpic)||$maxpic<0) $maxpic=4;

# Prepare some parameters based on selected dicom viewer module
$pop_only=false;

switch($_SESSION['sess_dicom_viewer']){
	case 'raimjava':
			$pop_only=true;
			break;
	default:
				# Default viewer
}

# Set break file
require('include/inc_breakfile.php');

if($mode=='') $glob_obj->getConfig('medocs_%');
/* Load GUI page */
require('./gui_bridge/default/gui_show.php');
?>
