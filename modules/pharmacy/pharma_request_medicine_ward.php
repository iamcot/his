<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'global_conf/inc_global_address.php');
$lang='vi';
define('LANG_FILE','products.php');
$user_origin=='ck_prod_order_user';
	define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_issuepaper.php');


if(!isset($IssuePaper)) $IssuePaper = new IssuePaper;
		
$thisfile= basename(__FILE__);
$breakfile=$root_path.'modules/pharmacy/allocation.php'.URL_APPEND;

$bgc1='#ffffff'; /* The main background color of the form */
$edit_form=0; /* Set form to non-editable*/
$read_form=1; /* Set form to read */
$edit=0; /* Set script mode to no edit*/

if(!isset($mode)) $mode='';
if(!isset($typeSumDepot)) $typeSumDepot='';


switch($mode){
	case 'update':	//update status_finish
	{		
		/*if($IssuePaper->setIssueStatusFinish($issue_id,'1')){
			//echo $sql;
			header("location:".$thisfile.URL_APPEND);
			exit;
		} else {
			echo "<p>$sql<p>$LDDbNoSave";
			$mode='';
		}
		break; */  // end of case 'save'
	}
	default: $mode='';
}


/* Get pending prescription */
if(!$mode) {	//$mode='' : load all issuepaper	
	if (!$typeSumDepot || $typeSumDepot=='all')
		$condition=" WHERE status_finish='0' ORDER BY date_time_create ";
	elseif ($typeSumDepot=='depot')
		$condition=" WHERE status_finish='0' AND type='0' ORDER BY date_time_create ";
	else	//type='sum'
		$condition=" WHERE status_finish='0' AND type='1' ORDER BY date_time_create ";
	$list_issue = $IssuePaper->listAllIssuePaper($condition);
	
	if(is_object($list_issue)){
		$batchrows = $list_issue->RecordCount();		//So luong all cac don thuoc cua benh nhan dang cho
		
		if($batchrows && (!isset($issue_id) || !$issue_id)){ 			// Check for the prescription_id = $issue_id. If available get the patients data to show 
			$issue_show = $list_issue->FetchRow();		
		 	//$pn = $IssuePaper_show['encounter_nr'];
			$issue_id = $issue_show['issue_paper_id'];
		}
		
	}else{
        ?>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?php
		echo "$LDIssueNotFound";
		echo '<center><a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'back2.gif','0').'></a></center>';
		exit;
	}
	$mode='update';
}

/* Check for the prescription id = $IssuePaper_id. If available get the patients data */
if($batchrows && $issue_id){
		
	//GET DATA PRESCRIPTION (get all medicine in this issuepaper)
			
	if($medicine_in_pres = $IssuePaper->getAllMedicineInIssuePaper($issue_id)){
		if($medicine_count = $medicine_in_pres->RecordCount()){
			$edit_form=1;
		}		
	}else{
		$mode='';
		$issue_id='';
	}
}

# Prepare title
$sTitle = $LDPendingIssueRequest;
if($batchrows) $sTitle = $sTitle." (".$LDIssueId.': '.$issue_id.")";
 

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

function FinishPres(issue_id)
{ 
	if(issue_id=='')
	{
		alert('<?php echo $LDIssueNotFound; ?>');
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
	var r=confirm("<?php echo $LDGiveMedicineIssue; ?>");
	if (r==true) {
		document.form_test_request.action="includes/inc_issuepaper_statusfinish.php?issue_id="+ issue_id+"&radiovalue=<?php echo $radiovalue; ?>&user_origin=<?php echo $user_origin; ?>";
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
		document.form_test_request.action="<?php echo $thisfile; ?>?typeSumDepot="+radio.id+"&radiovalue="+radio.value+"&user_origin=<?php echo $user_origin; ?>";
		document.form_test_request.submit();
	}
}
function startCalc(x){
  interval = setInterval("calc("+x+")",1);
}
function calc(x){
  //sum1 * cost1 = totalcost1;
  a = document.form_test_request['receive'+x].value;
  b = document.form_test_request['cost'+x].value; 
  document.form_test_request['sumcost'+x].value = a*b;
  
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
    <td> <!-- ***************      LOAD MENU DANH SACH PHIEU LINH THUOC      ***************    -->
<?php 


require('includes/inc_issuepaper_request_lister_fx.php');

?></td> <!-- ************************************************************************    -->

    <td>

	<form name="form_test_request" method="post" onSubmit="return FinishPres(<?php echo $issue_id; ?>)">
		<input type="image" <?php echo createLDImgSrc($root_path,'abschic.gif','0') ?>  title="<?php echo $LDFinishEntry; ?>"> 
		<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut; ?>"></a>
        <p>

			   <!--  outermost table creating form border -->
	<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
	  <tr>
		<td>
			<table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0 width=750>
			<tr>	<!-- ***************      HIEN THI NOI DUNG (MEDICINE) TRONG PHIEU LINH THUOC      ***************    -->
				<td align="center">
					<?php if(($edit || $read_form) && $medicine_count){ 
						if($issue_show = $IssuePaper->getIssuePaperInfo($issue_id))
							require('includes/inc_medicine_in_issue.php');
					} ?>
				</td>
			</tr>    <!-- *************************************************************************************    -->
			<tr><td>&nbsp;<br></td></tr>
			</table>	
		</td>
	</tr>
	</table>
<p>
		
			<input type="image" <?php echo createLDImgSrc($root_path,'abschic.gif','0') ?>  title="<?php echo $LDFinishEntry; ?>"> 
			<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut; ?>"></a>

<!--   ***************     HIDDEN  INPUT   ***************    -->
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="pn" value="<?php echo $pn ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
<input type="hidden" id="tracker" name="tracker" value="<?php echo $tracker ?>">
<input type="hidden" id="countpres" name="countpres" value="<?php echo $medicine_count; ?>">
<input type="hidden" id="radiovalue" value="<?php if ($radiovalue) echo $radiovalue; else echo '1'; ?>">
<input type="hidden" name="mode" id="mode" value="<?php if($mode=="edit") echo "update"; else echo $mode ?>">		
			
	</form>
	</td> 
		
	<td> 
			<table> <!-- ***************     MENU CHON SUM/DEPOT- ISSUEPAPER      ***************    -->
				<tr><td>
					<input type="radio" name="typeprespatient" id="all" value="1" onClick="RefreshList(this)" <?php if (!$typeSumDepot || $typeSumDepot=='all') echo 'checked'; ?>><?php echo $LDAllIssue; ?></td></tr>
				<tr><td>
					<input type="radio" name="typeprespatient" id="depot" value="2" onClick="RefreshList(this)" <?php if ($typeSumDepot=='depot') echo 'checked'; ?>><?php echo $LDDepotIssue; ?></td></tr>
				<tr><td>
					<input type="radio" name="typeprespatient" id="sum" value="3" onClick="RefreshList(this)" <?php if ($typeSumDepot=='sum') echo 'checked'; ?>><?php echo $LDSumIssue; ?></td></tr>
			</table> <!-- *********************************************************************    -->
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