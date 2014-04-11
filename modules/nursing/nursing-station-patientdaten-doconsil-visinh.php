<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* , elpidio@care2x.org
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables[] = 'departments.php';
define('LANG_FILE','konsil.php');

/* We need to differentiate from where the user is coming:
*  $user_origin != lab ;  from patient charts folder
*  $user_origin == lab ;  from the laboratory
*  and set the user cookie name and break or return filename
*/
if($user_origin=='lab')
{
  $local_user='aufnahme_user';
  if($target=="radio") $breakfile=$root_path.'modules/radiology/radiolog.php'.URL_APPEND;
   else $breakfile=$root_path.'modules/laboratory/labor.php'.URL_APPEND; 
}
else
{
  $local_user='ck_pflege_user';
  $breakfile="nursing-station-patientdaten.php".URL_APPEND."&edit=$edit&station=$station&pn=$pn";
    
}

require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'global_conf/inc_global_address.php');
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
//$db->debug=1;

$thisfile=basename(__FILE__);

$bgc1='#ffffff';  // entry form's background color

$abtname=get_meta_tags($root_path."global_conf/$lang/konsil_tag_dept.pid");

$formtitle='Khoa xét nghiệm';
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
$target='visinh';						
$db_request_table=$target;

define('_BATCH_NR_INIT_',30000000); 
/*
*  The following are  batch nr inits for each type of test request
*   chemlabor = 10000000; patho = 20000000; baclabor = 30000000; blood = 40000000; generic = 50000000; radio = 60000000
*/
						
/* Here begins the real work */
require_once($root_path.'include/core/inc_date_format_functions.php');
   
# Create a core object
require_once($root_path.'include/core/inc_front_chain_lang.php');
$core = & new Core;

     /* Check for the patient number = $pn. If available get the patients data, otherwise set edit to 0 */
     if(isset($pn) && $pn)
	 {		
		include_once($root_path.'include/care_api_classes/class_encounter.php');
		$enc_obj=new Encounter;
	    if( $enc_obj->loadEncounterData($pn)) {
/*		
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
*/			$full_en=$pn;
			$result=&$enc_obj->encounter;
		}
	   else 
	   {
	      $edit=0;
		  $mode="";
		  $pn="";
	   }		
     }
	 
	//Tuyen
	if ($mode!="")
	{
		$a = array( "lao" => 0, "kstdr" => 0, "huyettrang" => 0);
		foreach ($a as $k => $v) {
			if ($group_nr==$k){
				$a[$k]=1;			
			}
		}
		//$request_all, $item_code
		//Ham tach cac code_item trong list --------------
		
		$data = substr($code_item,1,strlen($code_item));
		$data_name = substr($request_all,1,strlen($request_all));
		$data = $data.'#';
		$data_name = $data_name."#";
		$from=0; $j=0; 
		$array_code = array();
		$from_n=0; $array_name = array();
		while (strlen($data)){
			$from = strpos($data, '#');
			$from_n = strpos($data_name, "#");
			$temp=substr($data,0,$from);
			$temp_n=substr($data_name,0,$from_n);
			if(strlen($temp)){
				$array_code[$j]=$temp;
				$array_name[$j]=$temp_n;
				$j++;
			}
			$data = substr($data,$from+1,strlen($data));	
			$data_name = substr($data_name,$from_n+1,strlen($data_name));
		}
		$n = count($array_code);
		//echo $n;
		//echo $array_code[0];
	}
	//-------
	
	 if(!isset($mode))   $mode="";
		
		  switch($mode)
		  {
				     case 'save':
								
                                 $sql="INSERT INTO care_test_request_".$db_request_table." 
                                          (batch_nr, encounter_nr, dept_nr, 
										  lao, kstdr, huyettrang, 
										  clinical_info, test_request, send_date, 
										  send_doctor,send_doctor_nr, status, 
										  history,
										  create_id, 
										  create_time)
										  VALUES 
										  (
										   '".$batch_nr."','".$pn."','".$dept_nr."',
										   '".$a['lao']."','".$a['kstdr']."','".$a['huyettrang']."',
										   '".htmlspecialchars($clinical_info)."','".htmlspecialchars($test_request)."','".formatDate2STD($date,$date_format)." ".$time."',
										   '".htmlspecialchars($send_doctor)."','".$send_doctor_nr."', 'pending', 
										   'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
										   '".$_SESSION['sess_user_name']."',
										   '".date('YmdHis')."'
										   )";

							      if($ergebnis=$core->Transact($sql))
       							  {	
								  $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));
									//if($n>0){
									//	for ($i=0; $i<$n; $i++){
										//	$sql="INSERT INTO care_test_request_".$db_request_table."_sub  
										//	  (sub_id, batch_nr, item_bill_code, item_bill_name, encounter_nr)
										//	  VALUES 
										//	  ('0', '".$batch_nr."','".$array_code[$i]."','".$array_name[$i]."','".$pn."')";
											$core->Transact($sql);
										//}
									//}
									//echo $sql;
								  	// Load the visual signalling functions
									include_once($root_path.'include/core/inc_visual_signalling_fx.php');
									// Set the visual signal 
									setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REQUEST);									
									
									 header("location:".$root_path."modules/laboratory/labor_test_request_aftersave_visinh.php?sid=$sid&lang=$lang&edit=$edit&saved=insert&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&noresize=$noresize&batch_nr=$batch_nr");
									 exit;
								  }
								  else 
								  {
								     echo "<p>$sql<p>$LDDbNoSave"; 
									 $mode="";
								  }
								
								break; // end of case 'save'
								
		     case 'update':
			 
							      $sql="UPDATE care_test_request_".$db_request_table." SET 
								          dept_nr = '".$a['dept_nr']."', 
										  lao='".$a['lao']."', kstdr='".$a['kstdr']."', huyettrang='".$a['huyettrang']."', 										 
										  clinical_info='".htmlspecialchars($clinical_info)."', test_request='".htmlspecialchars($test_request)."', 
										  send_date='".formatDate2STD($date,$date_format)." ".$time."', 
										  send_doctor='".htmlspecialchars($send_doctor)."',send_doctor_nr='".$send_doctor_nr."', status='".$status."', 
										  history=".$core->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										  modify_id='".$_SESSION['sess_user_name']."',
										  modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";
										  							
							      if($ergebnis=$core->Transact($sql))
       							  {
								  $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));
										//$sql="DELETE FROM care_test_request_".$db_request_table."_sub WHERE batch_nr='".$batch_nr."'";
										$core->Transact($sql);
										
									//	for ($i=0; $i<$n; $i++){
									//		$sql="INSERT INTO care_test_request_".$db_request_table."_sub  (sub_id, batch_nr, item_bill_code, item_bill_name, encounter_nr) VALUES ('0', '".$batch_nr."','".$array_code[$i]."','".$array_name[$i]."','".$pn."')";
									//		$core->Transact($sql);
											
									//	}
									//echo $sql;
								  	// Load the visual signalling functions
									include_once($root_path.'include/core/inc_visual_signalling_fx.php');
									// Set the visual signal 
									setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REQUEST);									
									
									 header("location:".$root_path."modules/laboratory/labor_test_request_aftersave_visinh.php?sid=$sid&lang=$lang&edit=$edit&saved=update&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&batch_nr=$batch_nr&noresize=$noresize");
									 exit;
								  }
								  else
								   {
								      echo "<p>$sql<p>$LDDbNoSave"; 
								      $mode='';
								   }
								
								break; // end of case 'save'
								
								
	        /* If mode is edit, get the stored test request when its status is either "pending" or "draft"
			*  otherwise it is not editable anymore which happens when the lab has already processed the request,
			*  or when it is discarded, hidden, locked, or otherwise. 
			*/
			case 'edit':
			
		                $sql="SELECT * FROM care_test_request_".$db_request_table." WHERE batch_nr='".$batch_nr."' AND (status='pending' OR status='draft')";
		                if($ergebnis=$db->Execute($sql))
       		            {
				            if($editable_rows=$ergebnis->RecordCount())
					        {
     					       $stored_request=$ergebnis->FetchRow();
							   $edit_form=1;
							   $sql="SELECT * FROM care_test_request_".$db_request_table."_sub WHERE batch_nr='".$batch_nr."' ";
							   if($item_ergebnis = $db->Execute($sql))
									$value_edit = $item_ergebnis->RecordCount();
					         }
			             }
						 
						 break; ///* End of case 'edit': */
			
			 default: $mode="";
						   
		  }// end of switch($mode)
  
          if(!$mode) /* Get a new batch number */
		  {
		                $sql="SELECT batch_nr FROM care_test_request_".$db_request_table." ORDER BY batch_nr DESC";
		                if($ergebnis=$db->SelectLimit($sql,1))
       		            {
				            if($batchrows=$ergebnis->RecordCount())
					        {
						       $bnr=$ergebnis->FetchRow();
							   $batch_nr=$bnr['batch_nr'];
							   if(!$batch_nr) $batch_nr=_BATCH_NR_INIT_; else $batch_nr++;
					         }
					         else
					         {
					            $batch_nr=_BATCH_NR_INIT_;
					          }
			             }
			               else 
						   {
						     echo "<p>$sql<p>$LDDbNoRead";
						   }
						 $mode="save";   
		   }
		   
# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('nursing');

# Title in toolbar
 $smarty->assign('sToolbarTitle', "Xét nghiệm vi sinh :: $formtitle");

  # hide back button
 $smarty->assign('pbBack',FALSE);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('request_radio.php','$pn')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"Xét nghiệm vi sinh :: $formtitle");

 # Create start new button if user comes from lab
  if($user_origin=='lab'){
	$smarty->assign('pbAux1',$thisfile.URL_APPEND."&station=$station&user_origin=$user_origin&status=$status&target=$target&noresize=$noresize");
	$smarty->assign('gifAux1',createLDImgSrc($root_path,'newpat2.gif','0'));
}

 if(!$noresize){
	$sOnLoadJs= 'if (window.focus) window.focus();window.moveTo(0,0); window.resizeTo(1000,740)';
}else{
 	$sOnLoadJs='if (window.focus) window.focus();';
}
if($pn=="") $sOnLoadJs = $sOnLoadJs.'document.searchform.searchkey.focus()';

$smarty->assign('sOnLoadJs','onLoad="'.$sOnLoadJs.'"');

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
</style>

<script language="javascript">
<!-- 
function chkForm(d){

    if((d.send_doctor.value=='')||(d.send_doctor.value==' '))
	{
		alert("<?php echo $LDPlsEnterDoctorName ?>");
		d.send_doctor.focus();
		return false;
	}
	else if((d.send_date.value=='')||(d.send_date.value==' '))
	{
		alert("<?php echo $LDPlsEnterDate ?>");
		d.send_date.focus();
		return false;
	}
	
}

function sendLater()
{
   document.form_test_request.status.value="draft";
   if(chkForm(document.form_test_request)) document.form_test_request.submit(); 
}
function popDocPer(target,obj_val,obj_name){
			urlholder="./personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}

function printOut()
{
	urlholder="<?php echo $root_path; ?>modules/laboratory/labor_test_request_printpop.php?sid=<?php echo $sid ?>&lang=<?php echo $lang ?>&user_origin=<?php echo $user_origin ?>&subtarget=<?php echo $target ?>&batch_nr=<?php echo $batch_nr ?>&pn=<?php echo $pn ?>&local_user=<?php echo $local_user?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    testprintout<?php echo $sid ?>.print();
}

$(function(){
$("#f-calendar-field-1").mask("99/99/9999");
$("#time").mask("99:99");
});	

<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>
//-->
</script>
<?php

$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript',$sTemp);

ob_start();

?>

<ul>

<?php
if($edit){

?>
<form name="form_test_request" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">

<?php
/* If in edit mode display the control buttons */

$controls_table_width=700;

require($root_path.'modules/laboratory/includes/inc_test_request_controls.php');

}
elseif(!$read_form && !$no_proc_assist)
{
?>

<table border=0>
  <tr>
    <td valign="bottom"><img <?php echo createComIcon($root_path,'angle_down_l.gif','0') ?>></td>
    <td><font color="#000099" SIZE=3  FACE="verdana,Arial"> <b><?php echo $LDPlsSelectPatientFirst ?></b></font></td>
    <td><img <?php echo createMascot($root_path,'mascot1_l.gif','0','absmiddle') ?>></td>
  </tr>
</table>
<?php
}
?>
   
   <!--  outermost table creating form border -->
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
  <tr>
    <td>
	
	<table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
   <tr>
     <td>
	
	   <table   cellpadding=0 cellspacing=1 border=0 width=700>
   <tr  valign="top">
   <td  bgcolor="#ffffff" rowspan=2>
 <?php
/*echo '
		<div class=fva2b_ml10><span style="background:yellow"><b>'.$result[patnum].'</b></span><br>
		<b>'.$result[name].', '.$result[vorname].'</b> <br>
		<font color=maroon>'.formatDate2Local($result[gebdatum],$date_format).'</font> <br><font size=1>
		'.nl2br($result[address]).'<p>
		'.$station.'&nbsp;'.$result[kasse].' '.$result[kassename].'</div>';
echo '
		<input type="text" name="stat_dept" value="'.strtoupper($station).'" size=25 maxlength=30>
  		</div>
		';*/
        if($edit)
        {
		   echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
		}
        elseif($pn=='')
		{
		    $searchmask_bgcolor="#f3f3f3";
            include($root_path.'modules/laboratory/includes/inc_test_request_searchmask.php');
        }
		?></td>
      <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10><div   class=fva2_ml10><font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
		 <br><?php echo $global_address[$target].'<br>'.$LDTel.'&nbsp;'.$global_phone[$target]; ?>
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
      <td><input type="radio" name="group_nr" value="lao" <?php if(($edit_form || $read_form) && $stored_request['lao']) echo "checked" ?>></td>
      <td align="right"><div class=fva2_ml10>KSTĐR</td>
      <td><input type="radio" name="group_nr" value="kstdr" <?php if(($edit_form || $read_form) && $stored_request['kstdr']) echo "checked" ?>></td>
	  <td align="right"><div class=fva2_ml10>Huyết trắng</td>
      <td><input type="radio" name="group_nr" value="huyettrang" <?php if(($edit_form || $read_form) && $stored_request['huyettrang']) echo "checked" ?>></td>
    </tr>
    
   
	
    <tr>
      <td colspan=6><hr></td>
    </tr>

   
  </table>
  
		
  </td>
</tr>
		 
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=3><div class=fva2_ml10><?php echo $LDClinicalInfo ?>:<br>
		<textarea name="clinical_info" cols=80 rows=6 wrap="physical"><?php if($edit_form || $read_form) echo stripslashes($stored_request['clinical_info']) ?></textarea>
				</td>
		</tr>	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=3><div class=fva2_ml10>
			<?php echo $LDReqTest ?>:<br>
			
		<textarea name="test_request" cols=80 rows=6 wrap="physical"><?php if($edit_form || $read_form) echo stripslashes($stored_request['test_request']) ?></textarea>
				</td>
		</tr>	


	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td align="left"><div class=fva2_ml10><font color="#000099">
			 <?php echo $LDDate .":";

							//gjergji : new calendar
			
			//end : gjergji
			if ($stored_request['send_date']=="")
				$dateshow=date("Y-m-d");
			else $dateshow=formatDate2Local($stored_request['send_date'],$date_format);
			
			echo $calendar->show_calendar($calendar,$date_format,'date',$dateshow);
			if(isset($stored_request['send_date']))
			{echo '<input type="text" size="5" id="time" name="time" value="'.@convertTimeToLocal(formatDate2Local($stored_request['send_date'],$date_format,0,1)).'">';
			}else{
			echo '<input type="text" size="5" id="time" name="time" value="'.date("H:i").'">';
			}
			//end gjergji ?>
			
		</td>
		<td>
			<?php echo $LDRequestingDoc ?>:

                <input type="text" name="send_doctor" size=34 maxlength=40 value="<?php if($edit_form || $read_form) echo $stored_request['send_doctor'];else echo $pers_name;?>">
                <input type="hidden" name="send_doctor_nr" value="<?php if(!empty( $stored_request['send_doctor_nr'])) echo $stored_request['send_doctor_nr'];else echo $pers_nr; ?>"> <a href="javascript:popDocPer('doctor_nr','send_doctor_nr','send_doctor')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>>

               <br>
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

<?php
if($edit)
{

/* If in edit mode display the control buttons */
require($root_path.'modules/laboratory/includes/inc_test_request_controls.php');

require($root_path.'modules/laboratory/includes/inc_test_request_hiddenvars.php');

?>

</form>

<?php
}
?>

</ul>

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
<!--<input type="text" name="send_doctor" size=34 maxlength=40 value="--><?php //if($edit_form || $read_form) echo $stored_request['send_doctor']; else echo $pers_name; ?><!--">-->
<!--<input type="hidden" name="send_doctor_nr" value="--><?php //if($read_form && $stored_request['send_doctor_nr']) echo $stored_request['send_doctor_nr']; else echo $pers_nr;?><!--"> <a href="javascript:popDocPer('doctor_nr')"><img --><?php //echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?><!-->-->