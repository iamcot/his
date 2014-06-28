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
if ($user_origin == 'lab') {
	$local_user = 'ck_lab_user';
	$breakfile = $root_path . "modules/laboratory/labor.php" . URL_APPEND;
} elseif ($user_origin == 'amb') {
	$local_user = 'ck_lab_user';
	$breakfile = $root_path . 'modules/ambulatory/ambulatory.php' . URL_APPEND;
} else {
	$local_user = 'ck_pflege_user';
	$breakfile = $root_path . "modules/nursing/nursing-station-patientdaten.php" . URL_APPEND . "&edit=$edit&station=$station&pn=$pn";
}


require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/

require_once($root_path.'global_conf/inc_global_address.php');
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
$thisfile= basename(__FILE__);
//$uploadfile= $root_path.'modules/radiology/upload.php'.URL_APPEND.'&user_origin='.$local_user.'&encounter_nr=';

$bgc1='#ffffff'; /* The main background color of the form */
$edit_form=0; /* Set form to non-editable*/
$read_form=1; /* Set form to read */
$edit=0; /* Set script mode to no edit*/

$formtitle='Xét nghiệm vi sinh';

//$db_request_table=$subtarget;
$db_request_table='visinh';

//$db->debug=1;
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

switch($mode){
	case 'update':
	{
		# Create a core object
		include_once($root_path.'include/core/inc_front_chain_lang.php');
		$core = & new Core;

		$sql="UPDATE care_test_request_".$db_request_table." SET
										  hongcau='".$hongcau."',
										  bachcau='".$bachcau."',
										  trunggiundua='".$trunggiundua."',
										  trunggiuntoc='".$trunggiuntoc."',
										  trunggiunmoc='".$trunggiunmoc."',
										  san='".$san."',
										  namhatmen='".$namhatmen."',
										  trichomonas='".$trichomonas."',
										  cocci='".$cocci."',
										  bacisub='".$bacisub."',
										  baciplus='".$baciplus."', 
										  date_mau_1='".formatDate2STD($date_mau_1,$date_format)."',
										  status_mau_1='".$status_mau_1."',
										  results_mau_1='".$results_mau_1."',
										  date_mau_2='".formatDate2STD($date_mau_2,$date_format)."',
										  status_mau_2='".$status_mau_2."',
										   results_mau_2='".$results_mau_2."',
										  date_mau_3='".formatDate2STD($date_mau_3,$date_format)."',
										  status_mau_3='".$status_mau_3."',
										   results_mau_3='".$results_mau_3."',
										  status='received',
										  results='".addslashes(htmlspecialchars($results))."',
                                          results_date='".formatDate2STD($result_date,$date_format)."',
										  results_doctor='".htmlspecialchars($results_doctor)."',
										  results_doctor_nr='".$results_doctor_nr."',
										  history=".$core->ConcatHistory("Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n").",
										  modify_id = '".$_SESSION['sess_user_name']."',
										  modify_time='".date('YmdHis')."'
					WHERE batch_nr = '".$batch_nr."'";

		if($ergebnis=$core->Transact($sql)){
		//	echo $sql;
			$sql1="select * from care_test_findings_".$db_request_table." where batch_nr='".$batch_nr."' and encounter_nr='".$pn."'";
			$temp=$db->execute($sql1);
			if($temp->recordcount()){
				//echo'33333';
							      $sql3="UPDATE care_test_findings_".$db_request_table."  SET
										   doctor_id='".htmlspecialchars($results_doctor)."',
										   doctor_id_nr='".$results_doctor_nr."',
						                    results='".addslashes(htmlspecialchars($results))."',
						                    results_date='".formatDate2STD($result_date,$date_format)."',
						                    results_time='".date('H:i:s')."',
                                            history=".$core->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
                                            modify_id = '".$_SESSION['sess_user_name']."',
                                            modify_time='".date('YmdHis')."'
                                            WHERE batch_nr = '".$batch_nr."'";
				// echo $sql3;
                if($ergebnis=$core->Transact($sql3)){
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql3, date('Y-m-d H:i:s'));
                    signalNewDiagnosticsReportEvent($result_date);
//                    header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=insert&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
                    header('Content-Type: text/html; charset=utf-8');       //đã thêm
                    echo "<script type='text/javascript'>";                 //đã thêm
                    echo "alert('Kết quả đã được lưu.');";                           //đã thêm
//            echo "alert('$LDNotifySave');";                                         //đã thêm
                    echo "window.location.replace('".$thisfile."?sid=".$sid."&lang=".$lang."&edit=".$edit."&saved=insert&mode=edit&pn=".$pn."&station=".$station."&user_origin=".$user_origin."&status=".$status."&target=".$target."&subtarget=".$subtarget."&batch_nr=".$batch_nr."&entry_date=".$entry_date."')"; //đã thêm
                    echo "</script>";
                    exit;
				}else{
					echo "<p>$sql3<p>$LDDbNoSave"; 
					// $mode='';
				}					


			}else{
				$sql2="INSERT INTO care_test_findings_".$db_request_table." 
						(
							batch_nr, encounter_nr, dept_nr, 
							doctor_id,doctor_id_nr,results, results_date, results_time, 
							status,history,create_id,create_time
						)
						VALUES
						(
							'".$batch_nr."','".$pn."','".$dept_nr."', 
							'".htmlspecialchars($results_doctor)."',
							'".$results_doctor_nr."',
							'".addslashes(htmlspecialchars($results))."',
							'".formatDate2STD($result_date,$date_format)."', '".date('H:i:s')."',
							'initial',  
							'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
							'".$_SESSION['sess_user_name']."',
							'".date('YmdHis')."'
						)";
				//echo $sql2;
				if($test=$core->Transact($sql2)){ 
					$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql2, date('Y-m-d H:i:s'));
					signalNewDiagnosticsReportEvent($result_date);
//					header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=insert&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
                    header('Content-Type: text/html; charset=utf-8');       //đã thêm
                    echo "<script type='text/javascript'>";                 //đã thêm
                    echo "alert('Kết quả đã được lưu.');";                           //đã thêm
//            echo "alert('$LDNotifySave');";                                         //đã thêm
                    echo "window.location.replace('".$thisfile."?sid=".$sid."&lang=".$lang."&edit=".$edit."&saved=insert&mode=edit&pn=".$pn."&station=".$station."&user_origin=".$user_origin."&status=".$status."&target=".$target."&subtarget=".$subtarget."&batch_nr=".$batch_nr."&entry_date=".$entry_date."')"; //đã thêm
                    echo "</script>";
                    exit;
				}
				else{
					echo "<p>$sql2<p>$LDDbNoSave"; 
					// $mode='';
				}
			}
		 $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));	
//		header("location:".$thisfile."?sid=$sid&lang=$lang&edit=$edit&saved=update&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&batch_nr=$batch_nr&noresize=$noresize");
            header('Content-Type: text/html; charset=utf-8');       //đã thêm
            echo "<script type='text/javascript'>";                 //đã thêm
            echo "alert('Kết quả đã được lưu.');";                           //đã thêm
//            echo "alert('$LDNotifySave');";                                         //đã thêm
            echo "window.location.replace('".$thisfile."?sid=".$sid."&lang=".$lang."&edit=".$edit."&saved=update&pn=".$pn."&station=".$station."&user_origin=".$user_origin."&status=".$status."&target=".$target."&subtarget=".$subtarget."&batch_nr=".$batch_nr."&entry_date=".$entry_date."')"; //đã thêm
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
if(!$mode ) {
	$sql="SELECT batch_nr,encounter_nr,send_date,dept_nr,lao,kstdr,huyettrang, urgent FROM care_test_request_".$db_request_table."
				WHERE (lao='1' OR kstdr='1' OR huyettrang='1')
				AND (status='pending' OR status='received') ORDER BY DATE(send_date) DESC, urgent DESC ";
	if($requests=$db->Execute($sql)){
		$batchrows=$requests->RecordCount();
	 	if($batchrows && (!isset($batch_nr) || !$batch_nr)){
			$test_request=$requests->FetchRow();
			/* Check for the patietn number = $pn. If available get the patients data */
		 	$pn=$test_request['encounter_nr'];
			$batch_nr=$test_request['batch_nr'];
			$lao=$test_request['lao'];
			$kstdr=$test_request['kstdr'];
			$huyettrang=$test_request['huyettrang'];
		}
	}else{
		echo "<p>$sql<p>$LDDbNoRead";
		exit;
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
					$edit_form=1;
					$lao=$stored_request['lao'];
					$kstdr=$stored_request['kstdr'];
                    $urgent=$stored_request['urgent'];
					$huyettrang=$stored_request['huyettrang'];
					$sql1="SELECT * FROM care_test_request_radio_sub WHERE batch_nr=".$batch_nr;
					$item_test=$db->Execute($sql1);
				}
			}else{
				echo "<p>$sql<p>$LDDbNoRead";
			}
		}
		//$uploadfile= $uploadfile.$pn.'&pid='.$enc_obj->encounter['pid'];
		
	}else{
		$mode='';
		$pn='';
	}
}
if($lao){
	$YC = 'VTH01';
}
if($kstdr){
//	$YC = 'KSTĐR';
    $YC = 'VTH02';

}
if($huyettrang){
	$YC = 'VTH03';
}
//$sql1 = "SELECT bill.bill_item_status, bill.bill_item_code
//		FROM care_test_request_".$db_request_table." AS req
//		INNER JOIN care_billing_item AS bill_it ON bill_it.item_code='$YC'
//		INNER JOIN care_billing_bill_item AS bill ON req.encounter_nr=bill.bill_item_encounter_nr AND DATE(req.send_date)=DATE(bill.bill_item_date) AND bill_it.item_code=bill.bill_item_code
//		WHERE req.batch_nr=$batch_nr
//		ORDER BY req.send_date DESC";
$sql1="SELECT TR.batch_nr,TR.encounter_nr,TR.send_date,BB.bill_item_status
          FROM care_test_request_" . $db_request_table . " AS TR
          JOIN care_billing_bill_item AS BB ON TR.encounter_nr = BB.bill_item_encounter_nr
          WHERE BB.bill_item_code='$YC'
          AND DATE(BB.bill_item_date)=DATE(TR.send_date)
          AND HOUR(BB.bill_item_date)=HOUR(TR.send_date)
          AND MINUTE(BB.bill_item_date)=MINUTE(TR.send_date)
          AND TR.batch_nr=".$batch_nr."
          GROUP BY TR.batch_nr
          ";
if ($requests1 = $db->Execute ( $sql1 )) {
	$bill = $requests1->FetchRow ();
	$status_bill=$bill['bill_item_status'];
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

 # Collect extra javascript code

 ob_start();
 require_once ('../../js/jscalendar/calendar.php');
			$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
			$calendar->load_files();
?>

<style type="text/css">
div.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10;}
div.fa2_ml10 {font-family: arial; font-size: 12; margin-left: 10;}
div.fva2_ml3 {font-family: verdana; font-size: 12; margin-left: 3; }
div.fa2_ml3 {font-family: arial; font-size: 12; margin-left: 3; }
.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
.fva2b_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
.fva0_ml10 {font-family: verdana,arial; font-size: 10; margin-left: 10; color:#000000;}
#f-calendar-field-1{margin:3px;}
#f-calendar-field-2{margin:3px;}
#f-calendar-field-3{margin:3px;}
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
	
function printOut()
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/xquang/PhieuChieuChupXQuang.php<?php echo URL_APPEND; ?>&enc=<?php echo $pn;?>&batch_nr=<?php echo $batch_nr ?>";
	testprintpdf<?php echo $sid ?>=window.open(urlholder,"testprintpdf<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
function PopupGetImage()
{
	var win = '<?php echo $uploadfile; ?>';
	myWindow=window.open( win , 'Upload' , 'height=650,width=700' );
	myWindow.focus();
}
<!--function popDocPer(target,obj_val,obj_name){-->
<!--			urlholder="./personell_search.php--><?php //echo URL_REDIRECT_APPEND; ?><!--&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;-->
<!--			DSWIN--><?php //echo $sid ?><!--=window.open(urlholder,"wblabel--><?php //echo $sid ?><!--","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");-->
<!--		}-->

function popDocPer(target,obj_val,obj_name){  //đã thêm hàm popDocPer
    urlholder="<?php echo $root_path; ?>modules/laboratory/personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
    DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
}
$(function(){
$("#f-calendar-field-1").mask("99/99/9999");
$("#f-calendar-field-2").mask("99/99/9999");
$("#f-calendar-field-3").mask("99/99/9999");
$("#f-calendar-field-4").mask("99/99/9999");

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
<?php
			//gjergji : new calendar
			
			
			//echo $calendar->show_calendar($calendar,$date_format,'xray_date',$stored_request['xray_date']);
			//end : gjergji	
		?>
	<form name="form_test_request" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">
		<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>"> 
		<!--<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>-->
        <a href="#" onclick="doneRequest();"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a>

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
		      <br><br><?php echo $global_address[$subtarget].'<br>'.$LDTel.'&nbsp;'.$global_phone[$subtarget]; ?>
              <br> <?php echo "Khẩn cấp: "?> <input type="checkbox" <?php if($urgent==1){?> checked="checked"<?php } ?>>
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
      <td align="right"><div class=fva2_ml10>Lao</td><br>
      <td>&nbsp;<?php printRadioButton('lao',1); ?></td>
      <td align="right"><div class=fva2_ml10>Kstdr</td>
      <td>&nbsp;<?php printRadioButton('kstdr',1); ?></td>
	   <td align="right"><div class=fva2_ml10>Huyết trắng</td>
      <td>&nbsp;<?php printRadioButton('huyettrang',1); ?></td>
    </tr>
  
	
    <tr>
      <td colspan=6><hr></td>
    </tr>

   
  </table>
  &nbsp;<br>
		
  </td>
</tr>
		 
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10><?php echo $LDClinicalInfo ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
		<font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['clinical_info']) ?></font>
				</td>
		</tr>	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10><?php echo $LDReqTest ?>:
		<?php
			/*$note="";
			if (is_object($item_test)){
				for ($i=0;$i<$item_test->RecordCount();$i++){
					$item = $item_test->FetchRow();
					$note=$note."<br>".$item['item_bill_name'];
				}
			}*/
			$note="<br>".$stored_request['test_request'];
		?>
		<font face="courier" size=2 color="#000099"><?php echo $note; ?></font>
				</td>
		</tr>	


	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10>
		 <?php echo $LDDate ?> gởi:
		<font face="courier" size=2 color="#000000">&nbsp;<?php 
		
		            
					  echo formatDate2Local($stored_request['send_date'],$date_format).' '.@convertTimeToLocal(formatDate2Local($stored_request['send_date'],$date_format,0,1)); 
					
				  ?></font>&nbsp;
  <?php echo $LDRequestingDoc ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php echo $stored_request['send_doctor'] ?></font></div><br>
		</td>
    </tr>
	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10>
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
		<td colspan=2 ><div class=fva2_ml10>
		 Kết quả:
		 </td>
		
	</tr>
	<tr>
		 <td colspan=2>
		 <?php if($stored_request['kstdr']==1){
		echo'
			 <table>
				 <tbody>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Hồng cầu</td>
				   <td><input type="text" name="hongcau" maxlength="5" value="';				   
		if($stored_request['hongcau']) echo $stored_request['hongcau'];		
		echo'"></td>
				   <td class="adm_item">Trứng giun tóc</td>
				   <td><input type="text" name="trunggiuntoc" maxlength="5" value="';				   
		if($stored_request['trunggiuntoc']) echo $stored_request['trunggiuntoc'];
		
		echo'"></td>
				  </tr>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Bạch cầu</td>
				   <td><input type="text" name="bachcau" maxlength="5" value="';				   
		if($stored_request['bachcau']) echo $stored_request['bachcau'];		
		echo'"></td>
				   <td class="adm_item">Trứng giun móc</td>
				   <td><input type="text" name="trunggiunmoc" maxlength="5" value="';
				   
		if($stored_request['trunggiunmoc']) echo $stored_request['trunggiunmoc'];
		
		echo'"></td>
				  </tr>
				   <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Trứng giun đũa</td>
				   <td><input type="text" name="trunggiundua" maxlength="5" value="';				   
		if($stored_request['trunggiundua']) echo $stored_request['trunggiundua'];		
		echo'"></td>
				   <td class="adm_item">Sán</td>
				   <td><input type="text" name="san" maxlength="5" value="';				   
		if($stored_request['san']) echo $stored_request['san'];		
		echo'"></td>
				  </tr>
				 </tbody>
			 </table>
		 ';	 
		 }elseif($stored_request['huyettrang']==1){
		 echo'
			 <table>
				 <tbody>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Nấm hạt men&nbsp;&nbsp;</td>
				   <td><input type="text" name="namhatmen" maxlength="5" value="';				   
		if($stored_request['namhatmen']) echo $stored_request['namhatmen'];		
		echo'"></td>
				   <td class="adm_item">Trichomonas&nbsp;&nbsp;</td>
				   <td><input type="text" name="trichomonas" maxlength="5" value="';				   
		if($stored_request['trichomonas']) echo $stored_request['trichomonas'];		
		echo'"></td>
				  </tr>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Cocci</td>
				   <td><input type="text" name="cocci" maxlength="5" value="';				   
		if($stored_request['cocci']) echo $stored_request['cocci'];		
		echo'"></td>
				   <td class="adm_item">Baci(-)</td>
				   <td><input type="text" name="bacisub" maxlength="5" value="';				   
		if($stored_request['bacisub']) echo $stored_request['bacisub'];		
		echo'"></td>
				  </tr>
				   <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Baci(+)</td>
				   <td><input type="text" name="baciplus" maxlength="5" value="';				   
		if($stored_request['baciplus']) echo $stored_request['baciplus'];		
		echo'"></td>
				  
				  </tr>
				 </tbody>
			 </table>
		 ';	 
		 }elseif($stored_request['lao']==1){
		 echo'
			 <table width=100% cellspacing="0" cellpadding="0" border="1">
				 <tbody>
				  <tr bgcolor="'.$bgc1.'">
					   <td align=center class="adm_item">Ngày nhận mẫu</td>
					   <td align=center class="adm_item">Mẫu đờm</td>
					   <td align=center class="adm_item">Trạng thái đờm đại thể</td>
					   <td align=center colspan=5>
						   <table width=100% border="1">
							   <tbody>
								<tr>
									<td align=center colspan=5 class="adm_item">Kết quả</td>
								</tr>
								<tr>

                                    <td width=45% align=center class="adm_item">(1-9 AFB)</td>

									<td width=15% align=center class="adm_item">Âm</td>
									<td width=13% align=center class="adm_item">1+</td>
									<td width=13% align=center class="adm_item">2+</td>
									<td width=13% align=center class="adm_item">3+</td>
								</tr>
							   </tbody>
						   </table>
					   </td>
				   </tr>
				   <tr>
					<td >
					  <nobr>
						'.$calendar->show_calendar($calendar,$date_format,'date_mau_1',$stored_request['date_mau_1']).'
					  </nobr>
					</td>
					<td align=center>
						1
					</td>
					<td>
						<input type="text" style="margin:3px;" maxlength="5" value="';
					if($stored_request['status_mau_1']) echo $stored_request['status_mau_1'];	
					echo'" name="status_mau_1">
					</td>';
					if($stored_request['results_mau_1']=="am"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_1 ">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am" checked name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_1"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_1">
					</td>';
					}elseif($stored_request['results_mau_1']=="1+"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_1">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" checked name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_1"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_1">
					</td>';
					}elseif($stored_request['results_mau_1']=="2+"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_1">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" checked name="results_mau_1"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_1">
					</td>';
					}elseif($stored_request['results_mau_1']=="3+"){
						echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_1">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+"  name="results_mau_1"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" checked name="results_mau_1">
					</td>';
					}else{
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="';
						if($stored_request['results_mau_1']) echo $stored_request['results_mau_1'];
					echo'" name="results_mau_1">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_1">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_1"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_1">
					</td>';
					}
				echo'
				   </tr> 
				   <tr>
					<td ><nobr>';
						echo $calendar->show_calendar($calendar,$date_format,'date_mau_2',$stored_request['date_mau_2']);
				echo'	</nobr></td>
					<td align=center>
						2
					</td>
					<td>
						<input style="margin:3px;" type="text" maxlength="5" value="';
					if($stored_request['status_mau_2']) echo $stored_request['status_mau_2'];	
					echo'" name="status_mau_2">
					</td>';
					if($stored_request['results_mau_2']=="am"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_2">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am" checked name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_2"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_2">
					</td>';
					}elseif($stored_request['results_mau_2']=="1+"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_2">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" checked name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_2"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_2">
					</td>';
					}elseif($stored_request['results_mau_2']=="2+"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_2">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" checked name="results_mau_2"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_2">
					</td>';
					}elseif($stored_request['results_mau_2']=="3+"){
						echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_2">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+"  name="results_mau_2"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" checked name="results_mau_2">
					</td>';
					}else{
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="';
						if($stored_request['results_mau_2']) echo $stored_request['results_mau_2'];
					echo'" name="results_mau_2">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_2">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_2"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_2">
					</td>';
					}
					echo'
				   </tr> 
				   <tr>
					<td style="padding-top:2px;margin-left:2px;">
						<nobr >'.$calendar->show_calendar($calendar,$date_format,'date_mau_3',$stored_request['date_mau_3']).'</nobr>
					</td>
					<td align=center>
						3
					</td>
					<td align=center>
						<input type="text" maxlength="5" value="';
					if($stored_request['status_mau_3']) echo $stored_request['status_mau_3'];	
					echo'" name="status_mau_3">
					</td>';
					if($stored_request['results_mau_3']=="am"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_3">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am" checked name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_3"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_3">
					</td>';
					}elseif($stored_request['results_mau_3']=="1+"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_3">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" checked name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_3"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_3">
					</td>';
					}elseif($stored_request['results_mau_3']=="2+"){
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_3">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" checked name="results_mau_3"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_3">
					</td>';
					}elseif($stored_request['results_mau_3']=="3+"){
						echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="" name="results_mau_3">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+"  name="results_mau_3"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" checked name="results_mau_3">
					</td>';
					}else{
					echo'<td align=center width=29%>
						<input type="text" maxlength="5" value="';
						if($stored_request['results_mau_3']) echo $stored_request['results_mau_3'];
					echo'" name="results_mau_3">
					</td>
					<td align=center width=14%>
						<input type="radio" value="am"  name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" name="results_mau_3">
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" name="results_mau_3"
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" name="results_mau_3">
					</td>';
					}
					echo'
				   </tr> 
				   </tbody>
			</table>';
		 }
		 ?>
		 </td>
	</tr>	 
		
		
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2> 
		 <div class=fva2_ml10>&nbsp;<br><font color="#000099"><?php echo $LDNotesTempReport ?></font><br>
         <textarea name="results" cols=80 rows=5 wrap="physical"><?php if($stored_request['results']) echo stripslashes($stored_request['results']);?></textarea>				
		 </td>
		</tr>	
		
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10><font color="#000099">
		 <?php echo $LDDate ?>

		<?php
			//gjergji : new calendar
			if($stored_request['result_date']){
				echo $calendar->show_calendar($calendar,$date_format,'result_date',$stored_request['result_date']);
			}else{
				echo $calendar->show_calendar($calendar,$date_format,'result_date',date('Y-m-d'));
			}
			//end : gjergji	
		?>
				  
  Bác sĩ xét nghiệm
<!--       <input type="text" name="results_doctor" value="--><?php //if($read_form && $stored_request['results_doctor']) echo $stored_request['results_doctor'];else echo $pers_name; ?><!--" size=35 maxlength=35> -->
<!--        <input type="hidden" name="results_doctor_nr" value="--><?php //if($read_form && $stored_request['results_doctor_nr']) echo $stored_request['results_doctor_nr']; else echo $pers_nr;?><!--"> <a href="javascript:popDocPer('doctor_nr')"><img --><?php //echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?><!-->-->
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
		<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  title="<?php echo $LDSaveEntry ?>"> 
		<!--<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>-->
        <a href="#" onclick="doneRequest();"><img <?php echo createLDImgSrc($root_path,'done.gif','0') ?> alt="<?php echo $LDEnterResult ?>"></a>

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
