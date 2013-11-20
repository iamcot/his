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
//echo $sid;
require_once($root_path.'include/core/inc_front_chain_lang.php');
# Load the insurance object
require_once($root_path.'include/care_api_classes/class_drg.php');
$drg_obj=new DRG;

//$db->debug=1;

switch($retpath)
{
	case 'list': $breakfile='icd10_list.php'.URL_APPEND; break;
	case 'search': $breakfile='idc10_search.php'.URL_APPEND; break;
	default: $breakfile='idc10_manage.php'.URL_APPEND; 
}

if(!isset($mode)){
	$mode='';
	$edit=true;		
}else{
	switch($mode)
	{
		case 'save':
		{
			#
			# Validate important data
			#
			$_POST['diagnosis_code']=trim($_POST['diagnosis_code']);
			if(!empty($_POST['diagnosis_code'])){
				#
				# Check if address exists
				#
				if($drg_obj->Icd10Exists($_POST['diagnosis_code'])){
					#
					# Do notification
					#
					$mode='icd10_exists';
				}else{
					if(!isset($_POST['no_header'])){
					if($drg_obj->saveIcd10InfoFromArray($_POST)){
						#
						# Get the last insert ID
						#
						
    					header("location:icd10_info.php?sid=$sid&lang=$lang&diagnosis_code=$diagnosis_code&mode=show&save_ok=1&retpath=$retpath");
						exit;
					}else{echo "$sql<br>$LDDbNoSave";}
					}
					else{
						if($drg_obj->insertICD10Auto($_POST['code'],$_POST['description'])){ 
							echo 1;
						}
						else echo 0;
					}
				}
			}else{
					$mode='bad_data';
			}
			break;
		}
	} // end of switch($mode)
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
 $smarty->assign('sToolbarTitle',"$LDICD10 :: $LDNewICD10");

 

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDICD10 :: $LDNewICD10");

# Coller Javascript code

ob_start();
?>

<script language="javascript">
<!-- 
function check(d)
{
	if((d.diagnosis_code.value=="")){
		alert("<?php echo "$LDAlertNoIcd10DiagnosisCode \\n $LDPlsEnterInfo"; ?>");
		d.diagnosis_code.focus();
		return false;
	}else if(d.description.value==""){
		alert("<?php echo $LDEnterDescription.'\n'.$LDEnterQMark; ?>");
		d.description.focus();
		return false;
	}else{
		return true;
	}
}
function search(){
	urlholder="<?php echo $root_path ?>modules/drg/drg-icd10-search_for_diag.php?sid=<?php echo "$sid&lang=$lang" ?>";
	popwin=window.open(urlholder,"timkiem","menubar=no,width=800,height=500,resizable=yes,scrollbars=yes");
}
// -->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

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
			echo $LDAlertNoIcd10;
			break;
		}
		case 'icd10_exists':
		{
			echo "$LDIcd10Exists<br>$LDDataNoSave";
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
&nbsp;<br>

<form action="<?php echo $thisfile; ?>" method="post" name="idc10" onSubmit="return check(this)">
<font face="Verdana, Arial" size=-1><?php echo $LDEnterAllFields ?>
<table border=0>
  <tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font> <?php echo $LDIcd10DiagnosisCode ?>: </td>
    <td class="adm_input"><input type="text" name="diagnosis_code" size=50 maxlength=60 value="<?php echo $diagnosis_code ?>"><br>
</td>
  </tr> 
  <!-- gjergji added zip code -->
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
 
    <td class=pblock><input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>></td>
	<td  align=right><input type="image" onclick="javascript:search()"<?php echo createLDImgSrc($root_path,'searchlamp.gif','0'); ?>></td>
    <td  align=right><a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> border="0"></a></td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath ?>">
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
