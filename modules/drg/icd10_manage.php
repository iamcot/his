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

$breakfile=$root_path."main/spediens.php".URL_APPEND;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',"$LDICD10 :: $LDManager");

 

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDICD10 :: $LDManager");

# Buffer page output

ob_start();
?>

  <p><br>
  
  <table border=0 cellpadding=5>
    <tr>
      <td> <a href="icd10_new.php<?php echo URL_APPEND; ?>"><img <?php  echo createComIcon($root_path,'form_pen.gif','0'); ?>></a> </td>
      <td>
	  		<a href="icd10_new.php<?php echo URL_APPEND; ?>"><b><font color="#990000"><?php echo $LDNewIcd10; ?></font></b></a><br>
	  		<?php echo $LDNewIcd10Txt ?></td> 
			
    </tr>
	
    <tr>
      <td> <a href="icd10_list.php<?php echo URL_APPEND; ?>"><img <?php  echo createComIcon($root_path,'form_pen.gif','0'); ?>></a> </td>
      <td>
	  		<a href="icd10_list.php<?php echo URL_APPEND; ?>"><b><font color="#990000"><?php echo $LDListAllIcd10 ?></font></b></a><br>
			<?php echo $LDListAllIcd10Txt ?></td>
			
    </tr>
    <tr>
      <td> <a href="icd10-search.php<?php echo URL_APPEND; ?>"><img <?php  echo createComIcon($root_path,'search_glass.gif','0'); ?>></a> </td>
      <td>
	  	<a href="icd10-search.php<?php echo URL_APPEND; ?>"><b><font color="#990000"><?php echo $LDSearchIcd10 ?></font></b></a><br>
			<?php echo $LDSearchIcd10Txt ?></td>
	  
    </tr>
  </table>
  
<p>
<ul>
<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> border="0"></a>
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
