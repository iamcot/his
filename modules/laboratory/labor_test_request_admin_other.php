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
$lang_tables[]='billing.php';
define('LANG_FILE','konsil.php');

/* We need to differentiate from where the user is coming: 
*  $user_origin != lab ;  from patient charts folder
*  $user_origin == lab ;  from the laboratory
*  and set the user cookie name and break or return filename
*/
if($user_origin=='lab'){
	$local_user='ck_lab_user';
	$breakfile=$root_path.'modules/laboratory/labor.php'.URL_APPEND;
}elseif($user_origin=='amb'){
	$local_user='ck_lab_user';
	$breakfile=$root_path.'modules/ambulatory/ambulatory.php'.URL_APPEND;
}else{
	$local_user='ck_pflege_user';
	$breakfile=$root_path."modules/nursing/nursing-station-patientdaten.php".URL_APPEND."&edit=$edit&station=$station&pn=$pn";
}
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/

require_once($root_path.'global_conf/inc_global_address.php');

$thisfile= basename(__FILE__);

$bgc1='#ffffff'; /* The main background color of the form */
$edit_form=0; /* Set form to non-editable*/
$read_form=1; /* Set form to read */
$edit=0; /* Set script mode to no edit*/

$formtitle='Xét nghiệm khác';

//$db_request_table=$subtarget;
$db_request_table='other';
//echo $subtarget;
//$db->debug=1;

/* Here begins the real work */
require_once($root_path.'include/core/inc_date_format_functions.php');
  
$sql="select personell_nr,name from care_users  where login_id='".$_SESSION['sess_login_userid']."'";
//echo $sql;
$temp=$db->execute($sql);
if($temp->recordcount())
{
	if($result=$temp->fetchrow()){
		$pers_nr=$result['personell_nr'];
		$pers_name=$result['name'];
	}else{
		$pers_nr='';
		$pers_name='';
	}
}
/* Here begins the real work */
require_once($root_path.'include/core/inc_date_format_functions.php');
   require_once ('includes/inc_diagnostics_report_fx.php');
if(!isset($mode))   $mode='';
//echo $thisfile;

switch($mode){

	case 'update':
	{
		# Create a core object
		include_once($root_path.'include/core/inc_front_chain_lang.php');
		$core = & new Core;

		$sql="UPDATE care_test_request_".$db_request_table." SET										 
                                          
										  results='".$results."',
                                          results_date='".formatDate2STD($date,$date_format)." ".$time."',
										  results_doctor='".htmlspecialchars($results_doctor)."',
										  results_doctor_nr='".$results_doctor_nr."',
										  status='received',
										  history=".$core->ConcatHistory("Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n").",
										  modify_id = '".$_SESSION['sess_user_name']."',
										  modify_time='".date('YmdHis')."'
					WHERE batch_nr = '".$batch_nr."'";

		if($ergebnis=$core->Transact($sql)){
		$sql1="select * from care_test_findings_".$db_request_table." where batch_nr='".$batch_nr."' and encounter_nr='".$pn."'";
		//echo $sql1;
			$temp=$db->execute($sql1);
			if($temp->recordcount()){
				//echo'33333';
							      $sql3="UPDATE care_test_findings_".$db_request_table."  SET										  
										   findings='".addslashes(htmlspecialchars($result))."',	
										   doctor_id='".htmlspecialchars($results_doctor)."',
										   doctor_id_nr='".$results_doctor_nr."',
										   findings_date='".formatDate2STD($date,$date_format)."',
										   findings_time='".$time."', 
										   history=".$core->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";//echo $sql3;
//echo $sql;
							      if($ergebnis=$core->Transact($sql3))
       							  { $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql3, date('Y-m-d H:i:s'));
								     signalNewDiagnosticsReportEvent($result_date);
									
//header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=insert&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
									// exit;
								  }
								  else
								   {
								      echo "<p>$sql3<p>$LDDbNoSave"; 
								     // $mode='';
								   }
			
			
			}else{
			//echo $sql;
			 $sql2="INSERT INTO care_test_findings_".$db_request_table." 
								          (
										   batch_nr, encounter_nr, dept_nr, 
										   findings, diagnosis,
										   doctor_id,doctor_id_nr, findings_date, findings_time, 
										   status, 
										   history,
										  create_id,
										  create_time
										  )
										   VALUES
										   (
										   '".$batch_nr."','".$pn."','".$dept_nr."', 
										   '".addslashes(htmlspecialchars($findings))."','".addslashes(htmlspecialchars($diagnosis))."',
										   '".htmlspecialchars($results_doctor)."','".$results_doctor_nr."', '".formatDate2STD($date,$date_format)."','".$time."',
										   'initial',  
										   'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
										  '".$_SESSION['sess_user_name']."',
										  '".date('YmdHis')."'
										   )";
										   if($test=$core->Transact($sql2))
       							  { $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql2, date('Y-m-d H:i:s'));
								     signalNewDiagnosticsReportEvent($result_date);
//echo $sql2;
									// header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=insert&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
									// exit;
								  }
								  else 
								  {
								     echo "<p>$sql2<p>$LDDbNoSave"; 
									// $mode='';
								  }
			}
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));	
//			header("location:".$thisfile."?sid=$sid&lang=$lang&edit=$edit&saved=update&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&batch_nr=$batch_nr&noresize=$noresize");

            header('Content-Type: text/html; charset=utf-8');                                          //đã thêm
            echo "<script type='text/javascript'>";                                                   //đã thêm
            echo "alert('Kết quả đã được lưu');";                                                      //đã thêm
//            echo "alert('$LDNotifySave');";                                                           //đã thêm
            echo "window.location.replace('".$thisfile."?sid=".$sid."&lang=".$lang."&edit=".$edit."&saved=update&pn=".$pn."&station=".$station."&user_origin=".$user_origin."&status=".$status."&target=".$target."&subtarget=".$subtarget."&batch_nr=".$batch_nr."&noresize=".$noresize."')"; //đã thêm
            echo "</script>";

            exit;
			
		} else {
			echo "<p>$sql<p>$LDDbNoSave";
			$mode='';
		}
		break; // end of case 'save'
	}
	default: $mode='';
}// end of switch($mode)

/* Get the pending test requests */
if(!$mode||$mode=='') {
	$sql="SELECT batch_nr,encounter_nr,send_date,dept_nr, urgent FROM care_test_request_".$db_request_table."
				WHERE status='pending' OR status='received' ORDER BY  DATE(send_date) DESC, urgent DESC";



	if($requests=$db->Execute($sql)){
		$batchrows=$requests->RecordCount();
	 	if($batchrows && (!isset($batch_nr) || !$batch_nr)){
			$test_request=$requests->FetchRow();
			/* Check for the patient number = $pn. If available get the patients data */
		 	$pn=$test_request['encounter_nr'];
			$batch_nr=$test_request['batch_nr'];
//            $bill_item_code=$test_request['bill_item_code'];
		}
	}else{
		echo "<p>$sql<p>$LDDbNoRead";
		exit;
	}

    $sql5="SELECT BB.bill_item_code
          FROM care_test_request_".$db_request_table." AS TR
          JOIN care_billing_bill_item AS BB ON TR.encounter_nr = BB.bill_item_encounter_nr
          WHERE (STATUS='pending' OR STATUS='received')
          AND TR.batch_nr=".$batch_nr."
          AND DATE(BB.bill_item_date)=DATE(TR.send_date)
          AND HOUR(BB.bill_item_date)=HOUR(TR.send_date)
          AND MINUTE(BB.bill_item_date)=MINUTE(TR.send_date)
          ORDER BY  send_date DESC
    ";
    if ($temp = $db->Execute ( $sql5 )) {
        $buf5 = $temp->FetchRow ();
        $bill_item_code=$buf5['bill_item_code'];
    }
	$mode='update';
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

			$sql="SELECT * FROM care_test_request_".$db_request_table." WHERE batch_nr='".$batch_nr."'";
			if($ergebnis=$db->Execute($sql)){
				if($editable_rows=$ergebnis->RecordCount()){
					$stored_request=$ergebnis->FetchRow();
                    $urgent=$stored_request['urgent'];
					$edit_form=1;
				}
			}else{
				echo "<p>$sql<p>$LDDbNoRead";
			}
		}
	}else{
		$mode='';
		$pn='';
	}
}

# Prepare title
$sTitle = $LDPendingTestRequest;
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

$smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus();"');
require_once ('../../js/jscalendar/calendar.php');
			$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
			$calendar->load_files();
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
function doneRequest(){
	var r=confirm('<?php echo $LDSaveBeforeDone; ?>');
	if (r==true) {
		window.location="<?php echo 'labor_test_findings_'.$subtarget.'.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&entry_date='.$stored_request['result_date'].'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker.'&mode=done'; ?>";
	} else
		return false;
}
function saveResult(){
	document.form_test_request.action="<?php echo 'labor_test_findings_other.php?sid='.$sid.'&lang='.$lang.'&batch_nr='.$batch_nr.'&pn='.$pn.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&mode=save'; ?>";
	document.form_test_request.submit();
}
function printOut()
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/xetnghiem/others.php<?php echo URL_APPEND; ?>&enc=<?php echo $pn;?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
    //testprintout<?php echo $sid ?>.print();
}
function popDocPer(target,obj_val,obj_name){
			urlholder="<?php echo $root_path; ?>modules/laboratory/personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}
$(function(){
$("#f-calendar-field-1").mask("99/99/9999");
});
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

	<form name="form_test_request" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">
			<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>"> 
		<!--<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>-->
		<a href="#" onclick="doneRequest()"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a>
	   <!--  outermost table creating form border -->
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
  <tr>
    <td>
	
	<table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
   <tr>
     <td>
	
	   <table   cellpadding=0 cellspacing=1 border=0 width=700>
   <tr  valign="top">
   <td  bgcolor="<?php echo $bgc1 ?>" rowspan=2>
 <?php
        if($edit || $read_form)
        {
		   echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
		}
		?></td>
      <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10><div   class=fva2_ml10><font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
		 <br>
              <br><br><?php echo $global_address[$subtarget].'<br>'.$LDTel.'&nbsp;'.$global_phone[$subtarget]; ?>
              <br> <?php echo "Khẩn cấp: "?> <input type="checkbox" <?php if($urgent==1){?> checked="checked"<?php } ?> disabled>
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
		<?php
		$other_types=explode("_",$stored_request['other_type']);
		echo '<td class="adm_item"> Yêu cầu xét nghiệm</td>';
		foreach($other_types as $other_type){
		$sql="select name from care_test_param_other where id='".$other_type."'";
		//echo $sql;
		$temp=$db->execute($sql);
		if($temp->recordcount()){
		$result=$temp->fetchrow();
		    echo '<td class="adm_input">'.$result['name'].' </td>';
			}
//			$YC=$result['name'];
		}
//		$sql1 = "SELECT bill.bill_item_status, bill.bill_item_code
//			FROM care_test_request_" . $db_request_table . " AS req
//			INNER JOIN care_billing_item AS bill_it ON bill_it.item_description LIKE '%$YC%'
//			INNER JOIN care_billing_bill_item AS bill ON req.encounter_nr=bill.bill_item_encounter_nr AND DATE(req.send_date)=DATE(bill.bill_item_date) AND bill_it.item_code=bill.bill_item_code
//			WHERE req.batch_nr=$batch_nr";
        if($bill_item_code=="XNK01"){
            $YC = 'XNK01';
        }
        elseif($bill_item_code=="XNK02"){
            $YC = 'XNK02';
        }
        elseif($bill_item_code=="XNK03"){
            $YC = 'XNK03';
        }
        elseif($bill_item_code=="XNK04"){
            $YC = 'XNK04';
        }
        elseif($bill_item_code=="XNK05"){
            $YC = 'XNK05';
        }
        elseif($bill_item_code=="XNK06"){
            $YC = 'XNK06';
        }
        elseif($bill_item_code=="XNK07"){
            $YC = 'XNK07';
        }
        elseif($bill_item_code=="XNK08"){
            $YC = 'XNK08';
        }
        elseif($bill_item_code=="XNK09"){
            $YC = 'XNK09';
        }
        elseif($bill_item_code=="XNK10"){
            $YC = 'XNK10';
        }
        elseif($bill_item_code=="XNK11"){
            $YC = 'XNK11';
        }
        elseif($bill_item_code=="XNK12"){
            $YC = 'XNK12';
        }
        elseif($bill_item_code=="XNK13"){
            $YC = 'XNK13';
        }
        elseif($bill_item_code=="XNK14"){
            $YC = 'XNK14';
        }
        elseif($bill_item_code=="XNK15"){
            $YC = 'XNK15';
        }
        $sql1="SELECT TR.batch_nr,TR.encounter_nr,TR.send_date,BB.bill_item_status
          FROM care_test_request_" . $db_request_table . " AS TR
          JOIN care_billing_bill_item AS BB ON TR.encounter_nr = BB.bill_item_encounter_nr
          WHERE BB.bill_item_code='$YC'
          AND DATE(BB.bill_item_date)=DATE(TR.send_date)
          AND HOUR(BB.bill_item_date)=HOUR(TR.send_date)
          AND MINUTE(BB.bill_item_date)=MINUTE(TR.send_date)
          AND TR.batch_nr=$batch_nr

          ";
		if ($requests1 = $db->Execute ( $sql1 )) {
			$bill = $requests1->FetchRow ();
			$status_bill=$bill['bill_item_status'];
		}
		?>
	</tr>
    <tr>
      <td colspan=4><hr></td>
    </tr>

    
  </table>
  &nbsp;<br>
		
  </td>
</tr>
		 
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10><?php echo $LDChandoan ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
		<font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['clinical_info']) ?></font>
				</td>
		</tr>	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10><?php echo $LDReqTestTim ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
		<font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['test_request']) ?></font>
				</td>
		</tr>	



	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10>
		 <?php echo $LDDate ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php 
		
		            
					  echo formatDate2Local($stored_request['send_date'],$date_format); 
					echo' Giờ gởi '.@convertTimeToLocal(formatDate2Local($stored_request['send_date'],$date_format,0,1));
				  ?></font>&nbsp;
  <?php echo $LDRequestingDoc ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php echo $stored_request['send_doctor'] ?></font></div>
		</td>
    </tr>
	
	<tr>
		<td colspan=10 align="right"><div class=fva2_ml10>
			<font face="courier" size=3 color="red"><b>
			<?php
				if($status_bill){
					echo $LDDaThanhtoan;
				}else{
					echo $LDChuaThanhToan;
				}
			?>
			</b></font>
		</td>
	</tr>
	
	<tr bgcolor="<?php echo $bgc1 ?>">
		
    </tr>	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2> 
		 <div class=fva2_ml10>&nbsp;<br><font color="#000099"><?php echo $LDNotesTempReport ?></font><br>
         <textarea name="results" style="width:96%;height:60px;white-space: pre-line;" >
		 <?php if($read_form && $stored_request['results']) 
		 {
		 echo $stored_request['results'];
		 }
		 else{
		 foreach($other_types as $other_type){
		$sql="select name from care_test_param_other where id='".$other_type."'";
		//echo $sql;
		$temp=$db->execute($sql);
		if($temp->recordcount()){
		$result=$temp->fetchrow();
			echo $result['name'].' :&#10;';		
			}
		 }
		 } ?></textarea>				
		 </td>
		</tr>	
		
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10><font color="#000099">
		 <?php echo $LDDate ?>
		
		<?php
		//gjergji : new calendar
			//echo formatDate2Local($stored_request['results_date'],$date_format);
			//gjergji : new calendar
			if(isset($stored_request['results_date'])&&(formatDate2Local($stored_request['results_date'],$date_format)!=0000-00-00))
			{
			echo $calendar->show_calendar($calendar,$date_format,'date',$stored_request['results_date']);
			echo'<input type="text" size="5" id="time" name="time" value="'.@convertTimeToLocal(formatDate2Local($stored_request['results_date'],$date_format,0,1)).'">';
			}else{
			echo $calendar->show_calendar($calendar,$date_format,'date',date("Y-m-d"));
			echo'<input type="text" size="5" id="time" name="time" value="'.date("H:i").'">';
			}
			
			//end : gjergji	
		?>
				  
  <?php echo 'BS xét nghiệm' ?>
        <input type="text" name="results_doctor" value="<?php if($read_form && $stored_request['results_doctor']) echo $stored_request['results_doctor'];else echo $pers_name; ?>" size=35 maxlength=35> 
		 <input type="hidden" name="results_doctor_nr" value="<?php if($read_form && $stored_request['results_doctor_']) echo $stored_request['results_doctor_nr'];else echo $pers_nr; ?>"> <a href="javascript:popDocPer('doctor_nr','results_doctor_nr','results_doctor')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>>
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
		<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>"> 
		<!--<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>-->
		<a href="#" onclick="doneRequest()"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a>
<?php

require($root_path.'modules/laboratory/includes/inc_test_request_hiddenvars.php');

?>
			</form>
		</td>
	</tr>
</table>

<?php
}
else
{
?>
<img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?> align="absmiddle"><font size=3 face="verdana,arial" color="#990000"><b><?php echo $LDNoPendingRequest ?></b></font>
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
