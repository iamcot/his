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
$lang_tables[]='search.php';
$lang_tables[]='departments.php';
$lang_tables[]='personell.php';
define('LANG_FILE','doctors.php');
if($_SESSION['sess_user_origin']=='personell_admin'){
	$local_user='aufnahme_user';
	define('NO_2LEVEL_CHK',1);
	$bShowSearchEntry = FALSE;
	if(!isset($saved)||!$saved){
		$mode='search';
		$searchkey=$nr;
	}
	$breakfile=$root_path.'modules/personell_admin/personell_register_show.php'.URL_APPEND.'&target=personell_reg&personell_nr='.$nr;
}else{
	$local_user='ck_doctors_dienstplan_user';
	define('NO_2LEVEL_CHK',1);
	$breakfile='javascript:history.back()';
	$bShowSearchEntry = TRUE;
}

require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_globalconfig.php');

$chucvu_nr=$_GET["chucvu_nr"];
$row['nr']=$_GET["nr"];
$dept_nr=$_GET["dept_nr"];
$img_options_add=createLDImgSrc($root_path,'add2list_sm.gif','0');
echo '
						<td id="doctor">&nbsp;
							<a href="doctors-list-add.php'.URL_APPEND.'&nr='.$row['nr'].'&dept_nr='.$dept_nr.'&mode=save&retpath='.$retpath.'&ipath='.$ipath.'&chucvu_nr='.$chucvu_nr.'" title="'.$LDAddDoctorToList.'">
							<img '.$img_options_add.' alt="'.$LDShowData.'"></a>&nbsp;';
							

 if(!file_exists($root_path.'cache/barcodes/en_'.$full_en.'.png'))
	      		       {
			               echo "<img src='".$root_path."classes/barcode/image.php?code=".$full_en."&style=68&type=I25&width=180&height=50&xres=2&font=5&label=2&form_file=en' border=0 width=0 height=0>";
		               }
						echo '</td>';
?>