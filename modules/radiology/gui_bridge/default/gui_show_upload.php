<?php
$returnfile='show.php'.URL_REDIRECT_APPEND.'&pid='.$pid;
include_once($root_path.'classes/transfont/codaukhongdau.php');

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('nursing');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$page_title);

  # hide back button
 $smarty->assign('pbBack',$returnfile.'&target='.$target.'&type_nr='.$type_nr);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('dicom_upload.php','$rows')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',$page_title);

 # Create select viewer button

 $smarty->assign('pbAux1',"javascript:popSelectDicomViewer('$sid','$lang')");
 $smarty->assign('gifAux1',createLDImgSrc($root_path,'select_viewer.gif','0'));

 # Collect extra javascript code

 ob_start();


//$_SESSION['sess_file_return']=$thisfile;

function createTR($ld_text, $input_val, $colspan = 1)
{
    global $toggle, $root_path;
?>

<tr>
<td bgColor="#eeeeee" ><FONT SIZE=-1  FACE="Arial,verdana,sans serif"><?php echo $ld_text ?>:
</td>
<td colspan=<?php echo $colspan; ?> bgcolor="#ffffee"><FONT SIZE=-1  FACE="Arial,verdana,sans serif"><?php echo $input_val; ?>
</td>
</tr>

<?php

$toggle=!$toggle;

}

?>

<script  language="javascript">
<!-- 

<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>

function popRecordHistory(table,pid) {
	urlholder="./record_history.php<?php echo URL_REDIRECT_APPEND; ?>&table="+table+"&pid="+pid;
	HISTWIN<?php echo $sid ?>=window.open(urlholder,"histwin<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
}
function chkform(d) {
	var r=false;
	for(i=0; i<<?php echo $maxpic ?>;i++){
		eval("if(d.f"+i+".value!=''){ r=true;}");
	}
	if(r) return true;
	else{
		alert('<?php echo $LDUploadMax1Pic; ?>');
		return false;
	}
}
function changeNumberUpdate() {
	document.getElementById('mode').value='';
	document.entryform.action="<?php echo $thisfile.URL_REDIRECT_APPEND; ?>";
	document.entryform.submit();
}
// -->

</script>

<script language="javascript" src="<?php echo $root_path; ?>js/dicom.js"></script>

<?php 
$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript',$sTemp);

ob_start();

?>

<table width=100% border=0 cellspacing="0"  cellpadding=0 >

	<form method="post" name="entryform"  ENCTYPE="multipart/form-data" onSubmit="return chkform(this)">

<?php
/* Create the tabs */

require('./gui_bridge/default/gui_tabs_upload.php');

?>
	<tr>
		<td colspan=3   bgcolor="<?php echo $cfg['body_bgcolor']; ?>">

			<table border=0 cellspacing=1 cellpadding=0>
				<tr bgcolor="#ffffff">
				<td colspan=3 valign="top">
<?php

$smarty->assign('sClassItem','class="reg_item"');
$smarty->assign('sClassInput','class="reg_input"');

$smarty->assign('LDCaseNr',$LDPID);

$smarty->assign('sEncNrPID',$pid);

$smarty->assign('img_source',"<img $img_source>");

$smarty->assign('LDTitle',$LDTitle);
$smarty->assign('title',$title);
$smarty->assign('LDLastName',$LDLastName);
$smarty->assign('name_last',$name_last);
$smarty->assign('LDFirstName',$LDFirstName);
$smarty->assign('name_first',$name_first);

# If person is dead show a black cross and assign death date

if($death_date && $death_date != DBF_NODATE){
	$smarty->assign('sCrossImg','<img '.createComIcon($root_path,'blackcross_sm.gif','0').'>');
	$smarty->assign('sDeathDate',@formatDate2Local($death_date,$date_format));
}

	# Set a row span counter, initialize with 7
	$iRowSpan = 7;

	if($GLOBAL_CONFIG['patient_name_2_show']&&$name_2){
		$smarty->assign('LDName2',$LDName2);
		$smarty->assign('name_2',$name_2);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_3_show']&&$name_3){
		$smarty->assign('LDName3',$LDName3);
		$smarty->assign('name_3',$name_3);
		$iRowSpan++;
	}

	if($GLOBAL_CONFIG['patient_name_middle_show']&&$name_middle){
		$smarty->assign('LDNameMid',$LDNameMid);
		$smarty->assign('name_middle',$name_middle);
		$iRowSpan++;
	}

$smarty->assign('sRowSpan',"rowspan=\"$iRowSpan\"");

$smarty->assign('LDBday',$LDBday);
$smarty->assign('sBdayDate',@formatDate2Local($date_birth,$date_format));

$smarty->assign('LDSex',$LDSex);
if($sex=='m') $smarty->assign('sSexType',$LDMale);
	elseif($sex=='f') $smarty->assign('sSexType',$LDFemale);

$smarty->assign('LDBloodGroup',$LDBloodGroup);
if($blood_group){
	$buf='LD'.$blood_group;
	$smarty->assign('blood_group',$$buf);
}

$smarty->display('registration_admission/basic_data.tpl');
?>

					<table border=0 width=100% cellspacing=1 cellpadding=3>



<?php
# Show input elements for additional info
if($mode=='new'){

	$cbbox_encouter_nr='<select name="encounter_nr">';
	$sql="SELECT * FROM care_encounter WHERE pid='".$pid."' ORDER BY encounter_date DESC";
	if($ergebnis=$db->Execute($sql)){
		$n=$ergebnis->RecordCount();
		for($i=0; $i<$n; $i++)
		{
			$item=$ergebnis->FetchRow();
			if(($encounter_nr=='' && $i==0) || ($encounter_nr==$item['encounter_nr']))
				$cbbox_encouter_nr= $cbbox_encouter_nr.'<option selected value="'.$item['encounter_nr'].'">'.$item['encounter_nr'].'</option>';
			else 
				$cbbox_encouter_nr= $cbbox_encouter_nr.'<option value="'.$item['encounter_nr'].'">'.$item['encounter_nr'].'</option>';
		}
	}
	$cbbox_encouter_nr= $cbbox_encouter_nr.'</select>';
	
	$typeupload[0]=$LDXquangCTMR ; $typeupload[1]=$LDSieuAm;
	$cbbox_docref='<select name="doc_ref_ids">';
	for ($i=0; $i<2; $i++){
		if (($doc_ref_ids==''&&$i==0) || $doc_ref_ids==$i)
			$cbbox_docref= $cbbox_docref.'<option selected value="'.$i.'">'.$typeupload[$i].'</option>';
		else
			$cbbox_docref= $cbbox_docref.'<option value="'.$i.'">'.$typeupload[$i].'</option>';
	}
	$cbbox_docref= $cbbox_docref.'</select>';
?>
<tr>
<td colspan=3><img <?php  echo createComIcon($root_path,'warn.gif','0') ?>> &nbsp;<FONT SIZE=-1  FACE="Arial"><?php  echo $LDEnterRelatedInfo ?>: 
</td>
</tr>
<tr>
<td bgColor="#eeeeee" ><FONT SIZE=-1  FACE="Arial"><nobr><?php  echo $LDRelatedEncNr ?>:</nobr>
</td>
<td bgcolor="#ffffee"  colspan=2> <?php echo $cbbox_encouter_nr; ?>
</td>
</tr>
<tr>
<td bgColor="#eeeeee" ><FONT SIZE=-1  FACE="Arial"><nobr><?php  echo $LDRelatedDocsIds ?>:</nobr><br>
</td>
<td bgcolor="#ffffee" colspan=2><?php echo $cbbox_docref; ?>
</td>
</tr>
<tr>
<td bgColor="#eeeeee" ><FONT SIZE=-1  FACE="Arial"><nobr><?php  echo $LDDiagnosis ?>:</nobr><br>
</td>
<td bgcolor="#ffffee" colspan=2>
<textarea name="notes" cols=40 rows=3><?php echo $notes; ?></textarea>
</td>
</tr>
<?php
}
?>
</table>


<?php

?>
<br>


<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="8000000">
<table width="100%"><tr bgcolor="#f6f6f6"><td><FONT class="prompt"><?php echo $LDCreateNewGroupImg; ?></td></tr></table>
&nbsp;<table><tr><td><img <?php  echo createComIcon($root_path,'arrow_blueW.gif','0') ?>></td><td><?php echo $LDTypeImg; ?>:&nbsp;</td><td>
<select name="typeimg">
	<option value="dcm" selected >dcm</option>
	<option value="jpg">jpg</option>
</select></td></tr></table>
<p>
&nbsp;<?php echo $LDUploadNew; ?>&nbsp;
<input type="text" name="maxpic" size=3 maxlength=2 value="<?php echo $maxpic; ?>"> <?php echo $LDNewImageFiles; ?>.
<input type="hidden" name="sid" value="<?php echo $sid; ?>">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="pid" value="<?php echo $pid; ?>"> 
<input type="button" value="<?php echo $LDContinue; ?>" onclick="changeNumberUpdate();">

<table border=0>
  <tr>
    <td><FONT class="vi_data"><?php echo $LDImgNumber; ?></td>
    <td></td>
    <td></td>
  </tr>

 <?php

for($i=0;$i<$maxpic;$i++){
	echo  '<tr><td align=center>'.($i+1).'
				</td><td><input type="file" name="f'.$i.'" size=40></td>
			    <td></td>
			  </tr>';
}
?>
   
    
</table>

<input type="hidden" name="sid" value="<?php echo $sid; ?>">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
 <input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="birthday" value="<?php 
		$birthday_t=str_replace("-","",$date_birth);		
		if(strlen($birthday_t)<8)  $birthday_t=str_pad($birthday_t, 8,'1', STR_PAD_RIGHT);
		echo $birthday_t; 
		?>">
<input type="hidden" name="sex" value="<?php echo $sex; ?>">
<input type="hidden" name="namealias" value="<?php echo convert2Alias($name_last.' '.$name_first); ?>">

<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="edit" value="<?php echo $edit; ?>">

<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>  >

</td>
<!-- Load the options table  -->
<td rowspan=2  valign="top">
&nbsp;
</td>
</tr>

</table>
<p>

<?php 
if($parent_admit) {
	include('./include/bottom_controls_admission_options.inc.php');
}else{
	include('./include/bottom_controls_registration_options.inc.php');
}
?>

<p>
</ul>

</form>

<form>


</form>

<p>
</td>
</tr>
</table>       

<p>
&nbsp;
<p>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

 ?>
