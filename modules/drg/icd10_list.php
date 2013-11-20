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
# Default value for the maximum nr of rows per block displayed, define this to the value you wish
# In normal cases this value is derived from the db table "care_config_global" using the "pagin_address_list_max_block_rows" element.
define('MAX_BLOCK_ROWS',30); 

$lang_tables[]='search.php';
define('LANG_FILE','drg.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
# Load the insurance object
require_once($root_path.'include/care_api_classes/class_drg.php');
$drg_obj=new DRG;

$breakfile='icd10_manage.php'.URL_APPEND;
$thisfile=basename(__FILE__);

# Initialize page's control variables
if($mode!='paginate'){
	# Reset paginator variables
	$pgx=0;
	$totalcount=0;
	# Set the sort parameters
	if(empty($oitem)) $oitem='diagnosis_code';
	if(empty($odir)) $odir='ASC';
}

$GLOBAL_CONFIG=array();
include_once($root_path.'include/care_api_classes/class_globalconfig.php');
$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
$glob_obj->getConfig('pagin_address_list_max_block_rows');
if(empty($GLOBAL_CONFIG['pagin_address_list_max_block_rows'])) $GLOBAL_CONFIG['pagin_address_list_max_block_rows']=MAX_BLOCK_ROWS; # Last resort, use the default defined at the start of this page

#Load and create paginator object
require_once($root_path.'include/care_api_classes/class_paginator.php');
$pagen=new Paginator($pgx,$thisfile,$_SESSION['sess_searchkey'],$root_path);
# Adjust the max nr of rows in a block
$pagen->setMaxCount($GLOBAL_CONFIG['pagin_address_list_max_block_rows']);

# Get all the active firms info
//$address=$address_obj->getAllActiveCityTown();
$icd10=&$drg_obj->getLimitIcd10($GLOBAL_CONFIG['pagin_address_list_max_block_rows'],$pgx,$oitem,$odir);
# Get the resulting record count
//echo $address_obj->getLastQuery();
$linecount=$drg_obj->LastRecordCount();
$pagen->setTotalBlockCount($linecount);
# Count total available data
if(isset($totalcount) && $totalcount){
	$pagen->setTotalDataCount($totalcount);
}else{
	$totalcount=$drg_obj->countIcd10();
	$pagen->setTotalDataCount($totalcount);
}

$pagen->setSortItem($oitem);
$pagen->setSortDirection($odir);

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',"$LDICD10 :: $LDListAll");

 # href for help button
 

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDICD10 :: $LDListAll");

# Buffer page output

ob_start();
?>

 <ul>

 &nbsp;
 <br>
<?php 
if(is_object($icd10)){
	
	if ($linecount) echo str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
		else echo str_replace('~nr~','0',$LDSearchFound); 


?>
<table border=0 cellpadding=2 cellspacing=1>
  <tr class="wardlisttitlerow">
      <td><b>
	  <?php 
	  	if($oitem=='diagnosis_code') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDIcd10DiagnosisCode,'diagnosis_code',$odir,$flag); 
			 ?></b>
  <!-- gjergji added zip code -->
	</td>
      <td><b>
	  <?php 
	  	if($oitem=='description') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDDescription,'description',$odir,$flag); 
			 ?></b>
	</td>
  <!-- end:gjergji added zip code -->	
      <td><b>
	  <?php 
	  	if($oitem=='sub_level') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDSubLevel,'sub_level',$odir,$flag); 
			 ?></b>
	</td>
	
      <td><b>
	  <?php 
	  	if($oitem=='notes') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDNotes,'notes',$odir,$flag); 
			 ?></b>
	</td>

      <td><b>
	  <?php 
	  	if($oitem=='class_sub') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDClassSub,'class_sub',$odir,$flag); 
			 ?></b>
	</td>
	<td><b>
	  <?php 
	  	if($oitem=='type') $flag=TRUE;
			else $flag=FALSE; 
		echo $pagen->SortLink($LDType,'type',$odir,$flag); 
			 ?></b>
	</td>

  </tr> 
<?php
	$toggle=0;
	while($icd=$icd10->FetchRow()){
		if($toggle) $bgc='wardlistrow2';
			else $bgc='wardlistrow1';
		$toggle=!$toggle;
?>
  <tr  class="<?php echo $bgc ?>">
    <td><a href="icd10_info.php<?php echo URL_APPEND.'&retpath=list&diagnosis_code='.$icd['diagnosis_code']; ?>"><?php echo $icd['diagnosis_code']; ?></a></td>
    <!-- gjergji added zip code -->
    <td><?php echo $icd['description']; ?></td>
    <!-- end:gjergji added zip code -->
    <td><?php echo $icd['sub_level']; ?></td>
    <td><?php echo $icd['notes']; ?></td>
    <td><?php echo $icd['class_sub']; ?></td>
	<td><?php echo $icd['type']; ?></td>
</td>
  </tr> 
<?php
	}
	echo '
	<tr><td colspan=3>'.$pagen->makePrevLink($LDPrevious).'</td>
	<td align=right>'.$pagen->makeNextLink($LDNext).'</td>
	</tr>';
?>
  </table>
<?php
}
?>
<p>

<form action="icd10_new.php" method="post">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="retpath" value="list">
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
