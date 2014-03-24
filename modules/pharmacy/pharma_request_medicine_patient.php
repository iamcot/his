<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','products.php');
if ($user_origin=='ck_prod_order_user')
	define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'global_conf/inc_global_address.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_prescription.php');
require_once($root_path.'include/care_api_classes/class_ward.php');

if(!isset($Pres)) $Pres = new Prescription;
		
$thisfile= basename(__FILE__);
$breakfile=$root_path.'modules/pharmacy/allocation.php'.URL_APPEND;

$bgc1='#ffffff'; /* The main background color of the form */
$edit_form=0; /* Set form to non-editable*/
$read_form=1; /* Set form to read */
$edit=0; /* Set script mode to no edit*/

if(!isset($mode))   $mode='';
if(!isset($typeInOut))   $typeInOut='';


switch($mode){
	/*case 'update':	//update status_finish
	{		
		if($Pres->setPresStatusFinish($pres_nr,'1')){
			//echo $sql;
			header("location:".$thisfile."?sid=$sid&lang=$lang");
			exit;
		} else {
			echo "<p>$sql<p>$LDDbNoSave";
			$mode='';
		}
		break; // end of case 'save'
	}*/
	default: $mode='';
}


/* Get pending prescription */
if(!$mode) {	//$mode='' : load all pres	
	if (!$typeInOut || $typeInOut=='all')
		$list_pres = $Pres->getAllPresByTypePatient('allpatient','0','0');
	elseif ($typeInOut=='inpatient')
		$list_pres = $Pres->getAllPresByTypePatient('inpatient','0','0');
	else
		$list_pres = $Pres->getAllPresByTypePatient('outpatient','0','0');
	
	if(is_object($list_pres)){
		$batchrows = $list_pres->RecordCount();		//So luong all cac don thuoc cua benh nhan dang cho
		
		if($batchrows && (!isset($pres_id) || !$pres_id)){ 			// Check for the prescription_id = $pres_id. If available get the patients data to show 
			$pres_show = $list_pres->FetchRow();
			
		 	$pn = $pres_show['encounter_nr'];
			$pres_id = $pres_show['prescription_id'];
		}
		
	}else{
        ?>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php
		echo "$LDPresNotFound";
		echo '<center><a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'back2.gif','0').'></a></center>';
		exit;
	}
	$mode='update';
}

/* Check for the prescription id = $pres_id. If available get the patients data */
if($batchrows && $pres_id){
	
	include_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj=new Encounter;
	if( $enc_obj->loadEncounterData($pn)) {

		include_once($root_path.'include/care_api_classes/class_globalconfig.php');
		$GLOBAL_CONFIG=array();
		$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
		$glob_obj->getConfig('patient_%');
		switch ($enc_obj->EncounterClass())		//Get info of encounter
		{
			case '1': $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
		                   break;
			case '2': $full_en = ($pn + $GLOBAL_CONFIG['patient_outpatient_nr_adder']);
							break;
			default: $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
		}

		if( $enc_obj->is_loaded){
			$result=&$enc_obj->encounter;
			
			//GET DATA PRESCRIPTION (get all medicine in this pres)
			
			if($medicine_in_pres = $Pres->getAllMedicineInPres($pres_id)){
				if($medicine_count = $medicine_in_pres->RecordCount()){
					$edit_form=1;
				}
			}
		}
	}else{
		$mode='';
		$pres_id='';
	}
}

# Prepare title
$sTitle = $LDPendingPresRequest;
if($batchrows) $sTitle = $sTitle." (".$LDPresId.': '.$pres_id.")";
 

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
function mysubmit(type,pres_id){
    console.log(type);
    if(type=='send'){
        FinishPres(pres_id);
    }
    else if(type=='edit'){
        return false;
    }
    else   {
        return false;
    }
}
function FinishPres(pres_id)
{ 
	if(pres_id=='')
	{
		alert('<?php echo $LDPresNotFound; ?>');
		return false;
	}
	var i=1; var n = document.getElementById('countpres').value;
	var flag = true;
	for (i=1;i<=n;i++){
		if(document.getElementById('receive['+i+']').value*1 > document.getElementById('tonkho'+i).value*1){		
			document.getElementById('receive['+i+']').style.backgroundColor="gold"; 
			flag=false;
		}
		else document.getElementById('receive['+i+']').style.backgroundColor="white";
	}
	if(flag==false){
		alert('<?php echo $LDQuaSoLuongThuocTon; ?>');
		return false;
	}
		
	var r=confirm("<?php echo $LDGiveMedicine; ?>");
	if (r==true) {
		document.form_test_request.action="includes/inc_pres_statusfinish.php?pres_id="+ pres_id+"&radiovalue=<?php echo $radiovalue; ?>&user_origin=<?php echo $user_origin; ?>";
		document.form_test_request.submit();
	} else
		return false;
}

function printOut()
{
	window.print();
}

function RefreshList(radio)
{
	var x = document.getElementById('radiovalue');
	if(x.value!=radio.value)
	{
		document.getElementById('mode').value='';
		document.getElementById('tracker').value='1';
		document.form_test_request.action="<?php echo $thisfile; ?>?typeInOut="+radio.id+"&radiovalue="+radio.value+"&user_origin=<?php echo $user_origin; ?>";
		document.form_test_request.submit();
	}
}
function startCalc(x){
  interval = setInterval("calc("+x+")",1);
}
function calc(x){
  //sum1 * cost1 = totalcost1;
  var a = document.getElementById('receive['+x+']').value;
  var idx=document.getElementById("cost"+x).selectedIndex;
  var opt=document.getElementById("cost"+x).options;
  var b = opt[idx].text*1;
  document.getElementById('sumcost'+x).value = a*b;
  
  //totalcost
  var n = document.getElementById('countpres').value;
  var total=0;
  for (i = 1; i <= n; i++)
  {
	if(document.getElementById('sumcost'+i))
		total = total + document.getElementById('sumcost'+i).value*1;
  }
  document.getElementById('totalcost').value = total;
  
  //change inventory

  var text = document.getElementById("hidden_tonkho"+x).value;
  var index = opt[idx].index+1;
  var n = text.split("@");
  document.getElementById("tonkho"+x).value=n[index];
	
  
}
function stopCalc(){
  clearInterval(interval);
}


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
    <td> <!-- ***************      LOAD MENU DANH SACH TOA THUOC      ***************    -->

<?php 

require('includes/inc_pres_request_lister_fx.php');

?>

</td> <!-- ************************************************************************    -->

    <td>

	<form name="form_test_request" method="post" >
		<input type="image" <?php echo createLDImgSrc($root_path,'abschic.gif','0') ?>  title="<?php echo $LDFinishEntry; ?>"> 
		<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut; ?>"></a>
        <p>

	   <!--  outermost table creating form border -->
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
  <tr>
    <td>
		<table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
		<tr>
			<td>
	
				<table   cellpadding=0 cellspacing=1 border=0 width=750>
				<tr  valign="top"> <!-- ***************      LOAD BARCODE      ***************    -->
					<td width="40%" bgcolor="<?php echo $bgc1 ?>"> 
 <?php
        if($edit || $read_form)
        {
			echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
			echo '</td><td bgcolor="'.$bgc1.'" align="right" valign="bottom">';
			$enc_obj->loadEncounterData($pn);
			$result=&$enc_obj->encounter;
			//echo '<font size=1 color="#990000" face="verdana,arial">PID: '.$result['pid'].'</font>&nbsp;&nbsp;<br>';
			//echo "<img src='".$root_path."classes/barcode/image.php?code=".$result['pid']."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0><br>";

		}
?>					</td>   <!-- ********************************************************************    -->
					<td>
					<!-- ***************      HIEN THI NOI DUNG (MEDICINE) TRONG TOA THUOC      ***************    -->
<?php 
							if(($edit || $read_form) && $medicine_count)
								$pres_show = $Pres->getPrescriptionInfo($pres_id);
							$date1 = formatDate2Local($pres_show['date_time_create'],'dd/mm/yyyy');
							$time1 = substr($pres_show['date_time_create'],-8);
							
							if($pres_show['status_bill']){
								$tempbill=$LDFinish; $tempbill1='check-r.gif';
								$readonly=' readonly ';
							}
							else{
								$tempbill=$LDNotYet; $tempbill1='warn.gif';
								$readonly='';
							}

							if($pres_show['status_finish']){
								$tempfinish=$LDFinish; $tempfinish1='check-r.gif';
								$readonly=' readonly ';
							}
							else{
								$tempfinish=$LDNotYet; $tempfinish1='warn.gif';
								$readonly='';
							}
							//Get info of encounter
							$en_nr = $pn;
							
							if(!isset($ward_obj)) $ward_obj=new Ward;

							$wardid = $result['current_ward_nr'];
							if ($wardid)
								$wardname = $ward_obj->WardName($wardid);	//Khu phong
							//$insurance_nr = $result['insurance_nr'];	//So BHYT
							
?>					
						<table border=0 cellpadding=5 width="100%">	
							<tr>
								<td width="40%"><FONT SIZE=-1 color="#000066"><?php echo $LDPresId; ?></td>
								<td width="60%"><?php  echo $pres_show['prescription_id']; ?></td>
						   </tr> 
							<tr bgcolor="#f6f6f6">
								<td><FONT SIZE=-1 color="#000066"><?php echo $LDWard; ?></td>
								<td><?php echo '<b>'.$pres_show['name_formal'].'</b> - '.$wardname; ?></td>
							</tr>
							<tr>
								<td><FONT SIZE=-1   color="#000066"><?php echo $LDDate; ?></td>
								<td><?php echo $date1.' '.$time1; ?></td>
						   </tr>
						   <tr bgcolor="#f6f6f6">
								<td><FONT SIZE=-1   color="#000066"><?php echo $LDPrescribedBy; ?></td>
								<td><?php echo $pres_show['doctor']; ?></td>
							</tr>
							<tr>
								<td><FONT SIZE=-1 color="#000066"><?php echo $LDPrescription; ?></td>
								<td><b><?php  echo $pres_show['type_name']; ?></b></td>
						   </tr> 
						    <tr bgcolor="#f6f6f6">
								<td><FONT SIZE=-1   color="#000066"><?php echo $LDPaid;?></td>
								<td>
									<?php echo $tempbill.' ';?><img <?php echo createComIcon($root_path,$tempbill1,'0','',TRUE); ?>>
								</td>
							</tr>
							<tr>
								<td><FONT SIZE=-1   color="#000066"><?php echo $LDGotDrug; ?></td>
								<td>
									<?php
											echo $tempfinish.' ';?>
											<img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?>>
								</td>
							</tr>
						</table>
					</td>
				</tr>	
				<tr>
					<td colspan="3">
						<table border=0 cellpadding=5 width="100%">	
							<tr bgcolor="#f6f6f6">
								<td width="20%"><FONT SIZE=-1   color="#000066"><?php echo $LDTotalDay; ?></td>
								<td width="20%"><?php echo $pres_show['sum_date']; ?></td>
								<td width="24%"><FONT SIZE=-1   color="#000066"><?php echo $LDTotalCostPres; ?></td>
								<td width="36%"><input id="totalcost" name="totalcost" type="text" size=15 value="<?php echo $pres_show['total_cost']; ?>" style="text-align:left;border-color:white;border-style:solid;" readonly></td>
							</tr>
						   <tr>
								<td><FONT SIZE=-1   color="#000066"><?php echo $LDSymptoms; ?></td>
								<td colspan="3"><?php echo $pres_show['symptoms']; ?></td>
						   </tr>
							<tr bgcolor="#f6f6f6">
								<td ><FONT SIZE=-1   color="#000066"><?php echo $LDDiagnosis; ?></td>
								<td colspan="3"><?php echo $pres_show['diagnosis']; ?></td>
							</tr>
							<tr>
								<td><FONT SIZE=-1   color="#000066"><?php echo $LDNote; ?></td>
								<td colspan="3"><?php echo $pres_show['note']; ?></td>
							</tr>							
						</table>
					</td>
				</tr>
			
				</table>
		
			</td>
		</tr>
		<tr>	
			<td align="center">
				<?php if(($edit || $read_form) && $medicine_count){ 
						if($pres_show)
							require('includes/inc_medicine_in_pres_toathuoc.php');
				} ?>
			</td>
		</tr>    <!-- *************************************************************************************    -->
		
		
		<tr><td>&nbsp;<br></td>
		</tr>

		</table> 


	 </td>
   </tr>
 </table>

<p>
		<input onclick="mysubmit('send',<?php echo $pres_id; ?>)" type="image" <?php echo createLDImgSrc($root_path,'abschic.gif','0') ?>  title="<?php echo $LDFinishEntry; ?>">

    <a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut; ?>"></a>
    <input type="button" onclick="mysubmit('edit',<?php echo $pres_id; ?>)" value="Cập nhật giá">

<!--   ***************     HIDDEN  INPUT   ***************    -->
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="pn" value="<?php echo $pn ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
<input type="hidden" id="countpres" name="countpres" value="<?php echo $medicine_count; ?>">
<input type="hidden" id="tracker" name="tracker" value="<?php echo $tracker ?>">
<input type="hidden" id="radiovalue" value="<?php if ($radiovalue) echo $radiovalue; else echo '1'; ?>">
<input type="hidden" name="mode" id="mode" value="<?php if($mode=="edit") echo "update"; else echo $mode ?>">		
		
		</form>
		</td> 
		
		<td> 
			<table><!-- ***************     MENU CHON ALL/IN/OUT-PATIENT      ***************    -->
				<tr><td>
					<input type="radio" name="typeprespatient" id="all" value="1" onClick="RefreshList(this)" <?php if (!$typeInOut || $typeInOut=='all') echo 'checked'; ?>><?php echo $LDAllpatient; ?></td></tr>
				<tr><td>
					<input type="radio" name="typeprespatient" id="inpatient" value="2" onClick="RefreshList(this)" <?php if ($typeInOut=='inpatient') echo 'checked'; ?>><?php echo $LDInpatient; ?></td></tr>
				<tr><td>
					<input type="radio" name="typeprespatient" id="outpatient" value="3" onClick="RefreshList(this)" <?php if ($typeInOut=='outpatient') echo 'checked'; ?>><?php echo $LDOutpatient; ?></td></tr>
			</table><!-- *********************************************************************    -->
			<p>
			<br>
			<table border="0" > <!-- ***************     SEARCH      ***************    -->
				<tr>
					<td colspan="2"> &nbsp; <?php echo $LDSearch; ?></td>
				</tr>
				<tr>
					<td align="right"><input type="text" id="search" name="search" value=""></td>
					<td align="right"><a href="javascript:search()"><input type="image" <?php echo createComIcon($root_path,'Search.png','0','',TRUE); ?> ></a></td>
				</tr>
				<tr>
					<td align="center" colspan="2"><FONT size=1><?php echo $LDSearchIssueGuide; ?></td>
				</tr>
			</table> 			<!-- ******************************    -->
		</td>
	</tr>
</table>

<?php
}
else
{
?>
<img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom'); ?> align="absmiddle"><font size=3 face="verdana,arial" color="#990000"><b><?php echo $LDNoPendingRequest; ?></b></font>
<p>
<a href="<?php echo $breakfile; ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0'); ?>></a>
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