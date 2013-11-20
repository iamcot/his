<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('NO_2LEVEL_CHK',1);
# Switch to the selected dicom viewer module

switch($_SESSION['sess_dicom_viewer']){
	case 'raimjava':
			header("location:raimjava/raimjava_launch_single.php".URL_REDIRECT_APPEND."&pid=$pid&img_nr=$img_nr&fn=$fn");
			exit;
	default:
				# Default viewer
}

/*** CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file 'copy_notice.txt' for the licence notice
*/
define('LANG_FILE','actions.php');
//define('LANG_FILE','radio.php');
//define('NO_2LEVEL_CHK',1);
$local_user='ck_radio_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'global_conf/inc_remoteservers_conf.php');
require_once($root_path.'include/care_api_classes/class_image.php');
$img=new Image();
$img->useImgDiagnostic();

$thisfile=basename(__FILE__);

$breakfile='patient_search.php'.URL_APPEND;

$nogo=false;

if(isset($img_nr) && $img_nr&&isset($pid) && $pid&&isset($fn) && $fn){
	//get old path
	$a = $img->SelectImgDiagInfo($img_nr);
	$imgpath = $a['path'];
	//$imgpath=$root_path.$dicom_img_localpath.$pid.'/'.$img_nr.'/'.$fn;
	if(!file_exists($imgpath)){
		$nogo=true;
	}
}else{
	$nogo=true;
}
# If no go, get out of here
if($nogo){
	//echo $imgpath;
	header("location:upload.php".URL_REDIRECT_APPEND."&mode=show&pid=$pid&nr=$img_nr");
	exit;
}
?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php html_rtl($lang); ?>
<HEAD>
<TITLE>
<?php echo $LDDicomViewer ?>
</TITLE>
</HEAD>
<BODY topmargin=0 leftmargin=0  marginwidth=0 marginheight=0><font face="Verdana, Arial" size=1><?php 
if(isset($pop_only) && $pop_only){
?>
<a href="javascript:window.close()">&nbsp;>> <?php echo $LDClose ?> <<</a>
<?php
}else{
?>
<a href="show.php<?php echo URL_APPEND."&saved=1&mode=show&pid=$pid&nr=$img_nr"; ?>"><font size=1>&nbsp;<< <?php echo $LDBack ?></font></a>
<?php
}
?></font>
<br>
<?php 
	//url Dicom.dic
	$scheme = (isset($_SERVER['HTTPS']) && !strcasecmp($_SERVER['HTTPS'], 'on'))?
        "https://" : "http://";
    $tokens = explode("/modules/", $_SERVER['PHP_SELF']);
	$url = $scheme.$_SERVER['SERVER_NAME'].$tokens[0];
    
	//url Dicom Image
	$url_dicom = str_replace('../','',$imgpath);
	$url_dicom = $_SERVER['SERVER_NAME'].$tokens[0].'/'.$url_dicom;
	//echo $_SERVER['SERVER_NAME'];
?>
<!-- Do not forget to set the variable $main_domain to your site domain in include/core/inc_init_main.php -->
 <APPLET
  ARCHIVE="dicomviewer/applet.jar"
  CODEBASE = "."
  CODE = "dicomviewer.Viewer.class"
  NAME = "Viewer.java"
  ARCHIVE = "dicomviewer.jar"
  WIDTH = 100%
  HEIGHT = 100%
  HSPACE = 0
  VSPACE = 0
  ALIGN = middle >
<PARAM NAME = "tmpSize" VALUE = "1">
<PARAM NAME = "NUM" VALUE = "1">
<PARAM NAME = "currentNo" VALUE = "0">
<PARAM NAME = "dicURL" VALUE="<?php echo $url; ?>/modules/radiology/dicomviewer/Dicom.dic">
<PARAM NAME = "imgURL0" VALUE = "<?php echo $scheme."$url_dicom/$fn" ?>">
</APPLET>  
</BODY>
</HTML> 
