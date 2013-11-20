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
define('LANG_FILE','drg.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
# Load the insurance object
require_once($root_path.'include/care_api_classes/class_drg.php');
$drg_obj=new DRG;

switch($retpath)
{
	case 'list': $breakfile='icd10_list.php'.URL_APPEND; break;
	case 'search': $breakfile='icd10_search.php'.URL_APPEND; break;
	default: $breakfile='icd10_manage.php'.URL_APPEND; 
}

if(isset($diagnosis_code) && $diagnosis_code&&($row=&$drg_obj->getIcd10Info($diagnosis_code))){
	$icd10=$row->FetchRow();
	$edit=true;
}else{
	# Redirect to search function
}


# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',"$LDICD10 :: $LDData");

 

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDICD10 :: $LDData");

# Buffer page output

ob_start();

?>

<ul>
<?php
if(isset($save_ok) && $save_ok){ 
?>
<img <?php echo createMascot($root_path,'mascot1_r.gif','0','absmiddle') ?>><font face="Verdana, Arial" size=3 color="#880000">
<b>
<?php 
 	echo $LDicd10InfoSaved;
?>
</b></font>
<?php 
} 
?>


<table border=0 cellpadding=4 >
  </tr> 
  <tr>
    <td align=right class="adm_item"></font><?php echo $LDIcd10DiagnosisCode ?>: </td>
    <td class="adm_input"><?php echo $icd10['diagnosis_code'] ?><br></td>
  </tr> 
  <!-- gjergji added zip code -->
  <tr>
    <td align=right class="adm_item"><font color=#ff0000></font><?php echo $LDDescription ?>: </td>
    <td class="adm_input"><?php echo $icd10['description']; ?></td>
  </tr>  
  <!-- end:gjergji added zip code -->  
  <tr>
    <td align=right class="adm_item"><font color=#ff0000></font><?php echo $LDSubLevel ?>: </td>
    <td class="adm_input"><?php echo $icd10['sub_level']; ?></td>
  </tr>
 
   <tr>
    <td align=right class="adm_item"></font><?php echo $LDNotes ?>: </td>
    <td class="adm_input"><?php echo $icd10['notes']; ?></td>
  </tr>
  <tr>
    <td align=right class="adm_item"><?php echo $LDClassSub ?>: </td>
    <td class="adm_input"><?php echo $icd10['class_sub']; ?><br></td>
  </tr>
  <tr>
    <td align=right class="adm_item"><?php echo $LDType ?>: </td>
    <td class="adm_input"><?php echo $icd10['type'] ?><br></td>
  </tr>
  
  <tr>
<td><a href="icd10_update.php<?php echo URL_APPEND.'&retpath='.$retpath.'&diagnosis_code='.$icd10['diagnosis_code']; ?>"><img <?php echo createLDImgSrc($root_path,'update.gif','0') ?>></a></td> 
    <td  align=right><a href="icd10_list.php<?php echo URL_APPEND; ?>"><img <?php echo createLDImgSrc($root_path,'list_all.gif','0') ?>></a> <a href="<?php echo $breakfile; ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a></td>
  </tr>
</table>
<p>
<form action="icd10_new.php" method="post">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath ?>">
<input type="submit" value="<?php echo $LDNeedEmptyFormPls ?>">
</form>

</ul>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
