<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
* GNU General Public License
* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
* , elpidio@care2x.org
*
* See the file "copy_notice.txt" for the licence notice
*/

/* Start initializations */ 
$lang_tables[]='departments.php';
define('LANG_FILE','konsil.php');

/* We need to differentiate from where the user is coming: 
*  $user_origin != lab ;  from patient charts folder
*  $user_origin == lab ;  from the laboratory
*  and set the user cookie name and break or return filename
*/
if($user_origin=='lab'){
	$local_user='ck_lab_user';
	$breakfile=$root_path.'modules/radiology/radiolog.php'.URL_APPEND;
}elseif($user_origin=='amb'){
	$local_user='ck_lab_user';
	$breakfile=$root_path.'modules/ambulatory/ambulatory.php'.URL_APPEND;
}else{
	$local_user='ck_pflege_user';
	$breakfile=$root_path."modules/nursing/nursing-station-patientdaten.php".URL_APPEND."&edit=$edit&station=$station&pn=$pn";
}
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/

require_once($root_path.'global_conf/inc_global_address.php');

$thisfile= basename(__FILE__);
$uploadfile= $root_path.'modules/radiology/sieuam/demo1.php'.URL_APPEND.'&encounter_nr=';

$bgc1='#ffffff'; /* The main background color of the form */
$edit_form=0; /* Set form to non-editable*/
$read_form=1; /* Set form to read */
$edit=0; /* Set script mode to no edit*/

$formtitle=$LDRadiology;

//$db_request_table=$subtarget;
$db_request_table='radio';
$subtarget='mono';
//$db->debug=1;

/* Here begins the real work */
require_once($root_path.'include/core/inc_date_format_functions.php');
  
$mode='';

/* Get the pending test requests */
if(!$mode) {
	$sql="SELECT batch_nr,encounter_nr,send_date,ward_nr,dept_nr FROM care_test_request_radio 
				WHERE sono='1' 
				AND (status='pending' OR status='received') ORDER BY  send_date DESC";
	if($requests=$db->Execute($sql)){
		$batchrows=$requests->RecordCount();
	 	if($batchrows && (!isset($batch_nr) || !$batch_nr)){
			$test_request=$requests->FetchRow();
			/* Check for the patietn number = $pn. If available get the patients data */
		 	$pn=$test_request['encounter_nr'];
			$batch_nr=$test_request['batch_nr'];
			$dept_nr=$test_request['dept_nr'];
			$ward_nr=$test_request['ward_nr'];
		}
	}else{
		echo "<p>$sql<p>$LDDbNoRead";	
		
		exit;
	}
	$mode='save';
}

/* Check for the patient number = $pn. If available get the patients data */
if($batchrows && $pn){
	include_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;
	if( $enc_obj->loadEncounterData($pn)) {

		include_once($root_path.'include/care_api_classes/class_globalconfig.php');
		$GLOBAL_CONFIG=array();
		$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
		$glob_obj->getConfig('patient_%');
		switch ($enc_obj->EncounterClass())
		{
			case '1': $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
		                   break;
			case '2': $full_en = ($pn + $GLOBAL_CONFIG['patient_outpatient_nr_adder']);
							break;
			default: $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
		}

		if( $enc_obj->is_loaded){
			$result=&$enc_obj->encounter;

			$sql="SELECT re.*, fi.*, re.create_time as bill_time
					FROM care_test_findings_radio AS fi 
					RIGHT JOIN care_test_request_radio AS re ON re.batch_nr=fi.batch_nr WHERE re.batch_nr='".$batch_nr."' ";
			//echo $sql;
			if($ergebnis=$db->Execute($sql)){
				if($editable_rows=$ergebnis->RecordCount()){
					$stored_request=$ergebnis->FetchRow();
					$edit_form=1;
					
					$sql1="SELECT * FROM care_test_request_radio_sub WHERE batch_nr='".$batch_nr."' ";
					$item_test=$db->Execute($sql1);
				}
			}else{
				echo "<p>$sql<p>$LDDbNoRead";
			}
		}
		$uploadfile= $uploadfile.$pn.'&pid='.$enc_obj->encounter['pid'];
		
	}else{
		$mode='';
		$pn='';
	}
}

# Prepare title
$sTitle = $LDSieuAm.': '.$LDPendingTestRequest;
if($batchrows) $sTitle = $sTitle." (".$batch_nr.")";

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('nursing');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$sTitle);

  # hide back button
 $smarty->assign('pbBack',FALSE);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('pending_radio.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',$sTitle);
 
  # Create button to view results

 $smarty->assign('pbAux1',"javascript:viewallresults()");
 $smarty->assign('gifAux1',createLDImgSrc($root_path,'showreport.gif','0')); 

$smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus();"');

 # Collect extra javascript code

 ob_start();
?>

<style type="text/css">
div.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10;}
div.fa2_ml10 {font-family: arial; font-size: 12; margin-left: 10;}
div.fva2_ml3 {font-family: verdana; font-size: 12; margin-left: 3; }
div.fa2_ml3 {font-family: arial; font-size: 12; margin-left: 3; }
.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
.fva2b_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
.fva0_ml10 {font-family: verdana,arial; font-size: 10; margin-left: 10; color:#000000;}
</style>

<script language="javascript">
<!-- 

function chkForm(d)
{ 
	if(d.results.value=="" || d.results.value==" ") 
	{
	  return false;
	}
	else if(d.results_date.value=="" || d.results_date.value==" ")
	  {
	     alert('<?php echo $LDPlsEnterDate ?>');
		 d.results_date.focus();
		 return false;
	  }
	  else if(d.results_doctor.value=="" || d.results_doctor.value=="")
		{
	     alert('<?php echo $LDPlsEnterDoctorName ?>');
		 d.results_doctor.focus();
		   return false;
		}
		else return true; 
}

function doneResult(){
	var d = document.form_test_request;
	if(d.results_date.value=="" || d.results_date.value==" ")
	{
	     alert('<?php echo $LDPlsEnterDate ?>');
		 d.results_date.focus();
		 return false;
	}
	else if(d.results_doctor.value=="" || d.results_doctor.value=="")
	{
	     alert('<?php echo $LDPlsEnterDoctorName ?>');
		 d.results_doctor.focus();
		   return false;
	}
	else
	{
        var r=confirm('<?php echo $LDSaveBeforeDone; ?>');
        if(r==true)
        {
		window.location="<?php echo 'labor_test_findings_mono.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&entry_date='.$stored_request['xray_date'].'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker.'&mode=done'; ?>";

	}
        else
        {return false;}
}
}

function saveResult(){
	var i,total=0;
	var itemid='';
	if(document.form_test_request.gr_monoimg){
		if(document.form_test_request.gr_monoimg.length){
			for(i=0; i < document.form_test_request.gr_monoimg.length; i++){
				if(document.form_test_request.gr_monoimg[i].checked){
					itemid = itemid +'_'+ document.form_test_request.gr_monoimg[i].value;	
					total++;
				}
				if(total==2) 			//chi luu toi da 2 anh
					break;
			}
		}else itemid = '_'+ document.form_test_request.gr_monoimg.value;
	}

    var r=alert ('<?php echo $LDAlertBeforeSave; ?>');
	document.form_test_request.action="<?php echo 'labor_test_findings_'.$subtarget.'.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker.'&mode=save&itemid='; ?>"+itemid;
	document.form_test_request.submit();
}
	
function printOut()
{
	var item_code=document.getElementById('item_code').value;
	urlholder="<?php echo $root_path;?>modules/pdfmaker/xquang/sieuam.php<?php echo URL_APPEND; ?>&enc=<?php echo $pn;?>&batch_nr=<?php echo $batch_nr ?>&item_code="+item_code;
	testprintpdf=window.open(urlholder,"KetQuaSieuAm","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
function PopupGetImage()
{
	var item_code=document.getElementById('item_code').value;
	var win = '<?php echo $uploadfile; ?>&batch_nr=<?php echo $batch_nr; ?>&item_code='+item_code;
	myWindow=window.open( win , 'Upload' , 'height=650,width=1000' );
	myWindow.focus();
}
function ShowResult(item_code){
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			CKEDITOR.instances.kq_sieuam.setData(xmlhttp.responseText);
			document.getElementById('item_code').value=item_code;
			ShowMonoImage();
		}
	}
	
	xmlhttp.open("POST","sieuam/frameset_kq.php?item_code="+item_code+'&flag=1&batch_nr='+'<?php echo $batch_nr; ?>',true);
	xmlhttp.send();
}
function ShowMonoImage(){
	var item_code = document.getElementById('item_code').value;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			document.getElementById("showmonoimg").innerHTML=xmlhttp.responseText;
			//alert(xmlhttp.responseText);
		}
	}
	xmlhttp.open("POST","sieuam/showmonoimage.php?item_code="+item_code+'&batch_nr='+'<?php echo $batch_nr; ?>',true);
	xmlhttp.send();
}

function viewallresults(){
	document.form_test_request.action="<?php echo '../radiology/viewresults_mono.php?sid='.$sid.'&lang='.$lang.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin; ?>";
	document.form_test_request.submit();
}	

function popDocPer(target,obj_val,obj_name){     //đã thêm
    urlholder="<?php echo $root_path; ?>modules/laboratory/personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;  //đã thêm
    DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");                                //đã thêm
}

<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>

//-->
</script>
<?php

$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript',$sTemp);

ob_start();

if($batchrows){

?>

<!-- Table for the list index and the form -->
<table border=0>
  <tr valign="top">
    <td>
<?php 

/* The following routine creates the list of pending requests */
require('includes/inc_test_request_lister_fx.php');

?></td>

    <td>

	<form name="form_test_request" method="post" action="<?php echo $thisfile ?>" >
		<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>" onclick="saveResult();"> 
		<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>
        <a href="#" onclick="doneResult();"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a>

	   <!--  outermost table creating form border -->
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
  <tr>
    <td>
	
	<table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
   <tr>
     <td>
	
	   <table   cellpadding=2 cellspacing=2 border=0 width=700>
   <tr  valign="top">
   <td  bgcolor="<?php echo $bgc1 ?>" rowspan=2>
 <?php
        if($edit || $read_form)
        {
		   echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
		}
		?></td>
      <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10><div   class=fva2_ml10><font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
		 <br><?php echo $global_address[$subtarget].'<br>'.$LDTel.'&nbsp;'.$global_phone[$subtarget]; ?>
		 </td>
		 </tr>
	 <tr>
      <td bgcolor="<?php echo $bgc1 ?>" align="right" valign="bottom">	 
	  <?php
		    echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
			  echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>";
     ?>
	     </td>
		 </tr>
		 	
		<tr bgcolor="<?php echo $bgc1 ?>">
		<td  valign="top" colspan=2 >
		
		<table border=0 cellpadding=1 cellspacing=1 width=100%>
    <tr>
      <td align="right"><div class=fva2_ml10><?php echo $LDXrayTest ?></td><br>
      <td>&nbsp;<?php printRadioButton('xray',1); ?></td>
      <td align="right"><div class=fva2_ml10><?php echo $LDSonograph ?></td>
      <td>&nbsp;<?php printRadioButton('sono',1); ?></td>
    </tr>
    <tr>
      <td align="right"><div class=fva2_ml10><?php echo $LDCT ?></td>
      <td>&nbsp;<?php printRadioButton('ct',1); ?></td>
      <td align="right"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right"><div class=fva2_ml10><?php echo $LDMRT ?></td>
      <td>&nbsp;<?php printRadioButton('mrt',1); ?></td>
      <td align="right"></td>
      <td></td>
    </tr>
	
    <tr>
      <td colspan=4><hr></td>
    </tr>

    <tr>
      <td align="right"><div class=fva2_ml10><?php echo $LDPatMobile ?> &nbsp;<?php echo $LDYes ?></td>
      <td><font size=2 face="verdana,arial">&nbsp;<?php printRadioButton('if_patmobile',1); ?>&nbsp;<?php echo $LDNot ?>
	  &nbsp;<?php printRadioButton('if_patmobile',0); ?></td>
      <td align="right"><div class=fva2_ml10><?php echo $LDAllergyKnown ?> &nbsp;<?php echo $LDYes ?></td>
      <td><font size=2 face="verdana,arial">&nbsp;<?php printRadioButton('if_allergy',1); ?>&nbsp;<?php echo $LDNot ?>
	  &nbsp;<?php printRadioButton('if_allergy',0); ?></td>
    </tr>
    <tr>
      <td align="right"><div class=fva2_ml10><?php echo $LDHyperthyreosisKnown ?> &nbsp;<?php echo $LDYes ?></td>
      <td><font size=2 face="verdana,arial">&nbsp;<?php printRadioButton('if_hyperten',1); ?>&nbsp;<?php echo $LDNot ?>
	  &nbsp;<?php printRadioButton('if_hyperten',0); ?></td>
      <td align="right"><div class=fva2_ml10><?php echo $LDPregnantPossible ?> &nbsp;<?php echo $LDYes ?></td>
      <td><font size=2 face="verdana,arial">&nbsp;<?php printRadioButton('if_pregnant',1); ?>&nbsp;<?php echo $LDNot ?>
	  &nbsp;<?php printRadioButton('if_pregnant',0); ?>
	  </td>
    </tr>
  </table>
  &nbsp;<br>
		
  </td>
</tr>
		 
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10><?php echo $LDClinicalInfo ?>:<br>
		<font face="courier" size=2 color="#000099"><?php echo stripslashes($stored_request['clinical_info']) ?></font><p>
				</td>
		</tr>	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10>
		<?php
			echo '<table width="100%"><tr><td>'.$LDReqTest.': </td><td align="right">'.$LDThanhToan.': </td></tr></table>';
			echo '<table width="100%" style="font-family:courier;font-size:small;" >';
			//$note="";
			if (is_object($item_test)){
				for ($i=0;$i<$item_test->RecordCount();$i++){
					$item = $item_test->FetchRow();
					echo "<tr><td><a href=\"javascript:ShowResult('".$item['item_bill_code']."')\">".$item['item_bill_name'].'</a></td><td align="right">';
					if($item_code=='' && $i==0){
						$item_code=$item['item_bill_code'];
				}
					$sql_bill="SELECT * FROM care_billing_bill_item
							WHERE bill_item_code='".$item['item_bill_code']."' AND bill_item_encounter_nr='".$pn."' AND bill_item_date='".$stored_request['bill_time']."'";
					//echo $sql_bill;
					if($bill=$db->Execute($sql_bill)){
						if($bill->RecordCount()){
							$bill_row=$bill->FetchRow();
						}else $bill_row['bill_item_status']=0;
			}

					if($bill_row['bill_item_status']){
						$tempfinish=$LDFinish; $tempfinish1='check-r.gif';
					}
					else{
						$tempfinish=$LDNotYet; $tempfinish1='warn.gif';
					}
					echo $tempfinish.' ';
					echo '<img '.createComIcon($root_path,$tempfinish1,'0','',TRUE).'> </td></tr>';
				}
			}
			echo '</table>';
		?>
		<br><br>
		<?php echo $LDAddFindings.': <br><font face="courier" size=2 color="#000099">'.$stored_request['test_request'].'</font>'; ?>
		</td>
	</tr>	


	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10>
		 <?php echo $LDDate ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php 
		
		            
					  echo formatDate2Local($stored_request['send_date'],$date_format); 
					
				  ?></font>&nbsp;
  <?php echo $LDRequestingDoc ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php echo $stored_request['send_doctor'] ?></font></div><br>
		</td>
    </tr>


	<tr bgcolor="#9E7BFF" >
		<td colspan="2"><a href="javascript:PopupGetImage()"><font color="#ffffff"><b>&nbsp;>>&nbsp;<?php echo $LDUploadImageMono; ?></b></font></a></td>
	</tr>		
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td  colspan=2 bgcolor="#cccccc"><div class=fva2_ml10>
        <nobr>
		<font color="#000099">
		<?php echo $LDXrayNumber ?>
        <input type="text" name="xray_nr" value="<?php if($read_form && $stored_request['xray_nr']) echo $stored_request['xray_nr']; ?>" size=9 maxlength=9> 
		<?php echo $LD_r_cm2 ?>
        <input type="text" name="r_cm_2" value="<?php if($read_form && $stored_request['r_cm_2']) echo $stored_request['r_cm_2']; ?>" size=7 maxlength=15> 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 <?php echo $LDXrayTechnician ?>&nbsp;
        <input type="text" name="mtr" value="<?php if($read_form && $stored_request['mtr']) echo $stored_request['mtr']; ?>" size=25 maxlength=35> 
		<?php echo $LDDate ?>&nbsp;
		<?php
			if($stored_request['xray_date']=='0000-00-00')
				$stored_request['xray_date']=date('Y-m-d');
			//gjergji : new calendar
			require_once ('../../js/jscalendar/calendar.php');
			$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
			$calendar->load_files();
			
			echo $calendar->show_calendar($calendar,$date_format,'xray_date',$stored_request['xray_date']);
			//end : gjergji	
		?>
		</nobr>
	  </div>
    </tr>
	<tr><td colspan="2">
		<?php 
			$sql_item="SELECT * FROM care_test_findings_radio_sub
						WHERE batch_nr='".$batch_nr."' AND item_bill_code='".$item_code."' ";
			if($re_item=$db->Execute($sql_item)){
				if($count1=$re_item->RecordCount()){
					$item=$re_item->FetchRow();
				}
			} 
		?>
		<script type="text/javascript" src="<?php echo $root_path; ?>classes/ckeditor/ckeditor.js"></script>
		<table width="100%">
			<tr bgcolor="<?php echo $bgc1 ?>">
				<td valign="top" colspan="2"><div class=fva2_ml10>&nbsp;<font color="#000099"><?php echo $LDKqSieuAm.': '; ?></font><br>
						<textarea  id="kq_sieuam" name="kq_sieuam" wrap="physical">
						<?php 
						if($count1 && $item['kq_sieuam']!=''){
							echo stripslashes($item['kq_sieuam']);
						}
						else{
							$file='sieuam/'.$item_code.'.txt';
							if(file_exists($file)){
								$fh = fopen($file, 'r');
								$theData = fread($fh, filesize($file));
								fclose($fh);
								echo $theData;
							}	
						}
						?>
						</textarea>
						<script type="text/javascript">
						//<![CDATA[

							// This call can be placed at any point after the
							// <textarea>, or inside a <head><script> in a
							// window.onload event handler.

							// Replace the <textarea id="editor"> with an CKEditor
							// instance, using default configurations.
							CKEDITOR.replace( 'kq_sieuam',
							{
								height:"400", width:"770",
								enterMode : CKEDITOR.ENTER_BR,
								toolbar :
								[
									{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Subscript','Superscript','-','RemoveFormat' ] },
									{ name: 'clipboard', items : [ 'Cut','Copy','Paste','-','Undo','Redo' ] },
									{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Outdent','Indent' ]},
									{ name: 'insert', items : [ 'Table'] }
								],
								on :
								   {
									  instanceReady : function ( evt )
									  {
										 // Hide the editor top bar.
										 //document.getElementById( 'cke_top_' + evt.editor.name ).style.display = 'none';
										 document.getElementById( 'cke_bottom_' + evt.editor.name ).style.display = 'none';
									  }
								   }
							});
							
							//echo mb_convert_encoding($str, 'UTF-8', 'HTML-ENTITIES');
						//]]>
						</script>
						</td>
				</td>
			</tr>
		</table>
	</td></tr>
	<tr bgcolor="#9E7BFF">
		<td colspan="2"><a href="javascript:ShowMonoImage()"><font color="#ffffff"><b>&nbsp;>>&nbsp;<?php echo $LDChooseImageMono; ?></b></font></a></td>
	</tr>
	<tr><td colspan="2"> 
		<div id="showmonoimg"> 
			<?php 
				if($count1 && $item['img_path']!=''){
					if($item['img_name']!=''){	//just check names in img_name
						$imgname=explode(',',$item['img_name']);
					}
					//show all imgage in folder
					if(is_dir($item['img_path'])){
						$listimage = glob($item['img_path']."/*.jpg");
						
						echo '<table width="100%"><tr>';
						if ($listimage!=false){							
							for($j=0;$j<count($listimage);$j++){
								$listimage[$j] = str_replace($item['img_path'].'/','',$listimage[$j]);
								
								if($j!=1 && ($j % 4 == 1)){
									echo '</tr><tr>';
								}
								echo '<td align="center">';
								echo 	'<img src="'.$item['img_path'].'/'.$listimage[$j].'" width="160" height="120"><br>';
								echo $listimage[$j].'<br>';
								if (in_array($listimage[$j], $imgname))
									echo '<input type="checkbox" name="gr_monoimg" value="'.$listimage[$j].'" checked> ';
								else
									echo '<input type="checkbox" name="gr_monoimg" value="'.$listimage[$j].'"> ';
								echo '</td>';
							}
						} else echo '<td>'.$LDNoImageHere.'</td>';
						echo '</tr></table>';
					}
				}
			?>
		</div> 
	</td></tr>
	<tr><td colspan=2><div class=fva2_ml10><font size="1"><?php echo $LDSaveBeforeNext; ?><br>&nbsp; </td></tr>
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10><font color="#000099">
		<?php echo $LDDate ?>

		<?php
			if($stored_request['results_date']=='0000-00-00')
				$stored_request['results_date']=date('Y-m-d');
			//gjergji : new calendar
			
			echo $calendar->show_calendar($calendar,$date_format,'results_date',$stored_request['results_date']);
			//end : gjergji	
		?>
				  
  <?php echo $LDBacsisieuam ?>
        <!--  gốc      <input type="text" name="results_doctor" value="--><?php //
//		//	if($read_form && $stored_request['results_doctor']) echo $stored_request['results_doctor'];
//			//else
//				echo $_SESSION['sess_user_name']; ?><!--" size=35 maxlength=35>-->

        <input type="text" name="results_doctor" size=37 maxlength=40 value="<?php if($edit_form || $read_form) echo $stored_request['results_doctor'];else echo $pers_name;?>">
        <input type="hidden" name="results_doctor_nr" value="<?php if(!empty( $stored_request['results_doctor_nr'])) echo $stored_request['results_doctor_nr'];else echo $pers_nr; ?>"> <a href="javascript:popDocPer('doctor_nr','results_doctor_nr','results_doctor')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>>
		</td>
    </tr>
		</table> 
		

	 </td>
   </tr>
 </table>
	
	</td>
  </tr>
</table> 
<p>
		<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>" onclick="saveResult();"> 
		<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>
        <a href="#" onclick="doneResult();"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a>

<?php
require($root_path.'modules/laboratory/includes/inc_test_request_hiddenvars.php');
?>
<input type="hidden" id="item_code" name="item_code" value="<?php echo $item_code; ?>">
			</form>
		</td>
	</tr>
</table>

<?php
}
else
{
?>
<img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?> align="absmiddle">
<form name="form_test_request" method="post" action="<?php echo $thisfile ?>" >
<font size=3 face="verdana,arial" color="#990000"><b>
<?php echo $LDNoPendingRequest ?></b></font>
</form>
<p>
<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>></a>
<?php
}

$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

 ?>
