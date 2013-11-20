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
require_once($root_path.'include/care_api_classes/class_core.php');
require_once($root_path.'include/care_api_classes/class_drg.php');
$drg_obj=new DRG;

switch($retpath)
{
	case 'list': $breakfile='icd10_list.php'.URL_APPEND; break;
	case 'search': $breakfile='icd10_search.php'.URL_APPEND; break;
	default: $breakfile='icd10_manage.php'.URL_APPEND; 
}

if(isset($diagnosis_code) && $diagnosis_code){
	if(isset($mode) && $mode=='update'){
	$sql="update care_icd10_vi set description='".$_POST['description']."',class_sub='".$_POST['class_sub']."',type='".$_POST['type']."',notes='".$_POST['notes']."',sub_level='".$_POST['sub_level']."' where diagnosis_code='".$diagnosis_code."'";
		if($drg_obj->transact($sql)){
    		header("location:icd10_info.php?sid=$sid&lang=$lang&diagnosis_code=$diagnosis_code&mode=show&save_ok=1&retpath=$retpath");
			exit;
		}else{
			echo $drg_obj->getLastQuery();
			$mode='bad_data';
		}	
	}elseif($row=$drg_obj->getIcd10Info($diagnosis_code)){
		if(is_object($row)){
			$icd=$row->FetchRow();
			# Globalize the array values
			extract($icd);
		}
	}
}else{
	// Redirect to search function
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
 $smarty->assign('sToolbarTitle',"$LDICD10 :: $LDUpdateData");

 # href for help button
 

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDICD10 :: $LDUpdateData");

# Buffer page output

ob_start();

?>

<ul>
<?php
if(!empty($mode)){ 
?>
<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?>></td>
    <td valign="bottom"><br><font class="warnprompt"><b>
<?php 
	switch($mode)
	{
		case 'bad_data':
		{
			echo "ffffff";
			break;
		}
		case 'icd10_exists':
		{
			echo "$LDICD10Exists<br>$LDDataNoSave";
		}
	}
?>
	</b></font><p>
</td>
  </tr>
</table>
<?php 
} 
?>
<script language="javascript">
<!--
function check(d)
{
	if(d.description.value==""){
		alert("<?php echo $LDEnterDescription.'\n'.$LDEnterQMark; ?>");
		d.description.focus();
		return false;
	}else{
		return true;
	}
}
// -->
</script>

<form action="<?php echo $thisfile; ?>" method="post" name="icd10"  onSubmit="return check(this)">
<table border=0>
  <tr>
    <td align=right class="adm_item"><?php echo $LDIcd10DiagnosisCode ?>: </td>
    <td class="adm_input"><?php echo $diagnosis_code ?><br></td>
  </tr> 
  <!-- apmuthu added zip code -->
 <tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font><?php echo $LDDescription  ?>: </td>
    <td class="adm_input"><input type="text" name="description" size=50 maxlength=500 value="<?php echo $description  ?>"><br></td>
  </tr>  
  <!-- end:gjergji added zip code. apmuthu increased maxlength from 5 to 15 - db table field is VARCHAR(25).  -->   
 
 <tr>
    <td align=right class="adm_item"><?php echo $LDSubLevel  ?>: </td>
    <td class="adm_input"><input type="text" name="sub_level" size=50 maxlength=15 value="<?php echo $sub_level  ?>"><br></td>
  </tr>  
  <tr>
    <td align=right class="adm_item"><?php echo $LDNotes  ?>: </td>
    <td class="adm_input"><input type="text" name="notes" size=50 maxlength=15 value="<?php echo $notes  ?>"><br></td>
  </tr>  
  <tr>
    <td align=right class="adm_item"><?php echo $LDClassSub  ?>: </td>
    <td class="adm_input"><input type="text" name="class_sub" size=50 maxlength=15 value="<?php echo $class_sub ?>"><br></td>
  </tr>  
  <tr>
    <td align=right class="adm_item"><?php echo $LDType  ?>: </td>
    <td class="adm_input"><input type="text" name="type" size=50 maxlength=15 value="<?php echo $type ?>"><br></td>
  </tr>  
  <tr>
    <td><input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>></td>
    <td  align=right><a href="<?php echo $breakfile;?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a></td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="lang" value="<?php echo $lang ?>">


<input type="hidden" name="retpath" value="<?php echo $retpath ?>">
</form>
<p>

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
