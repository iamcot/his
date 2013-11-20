<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
* GNU General Public License
* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables=array('departments.php');
define('LANG_FILE','konsil.php');
$local_user='ck_lab_user';

require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'global_conf/inc_global_address.php');
require_once ('includes/inc_diagnostics_report_fx.php');

$breakfile=$root_path.'modules/radiology/radiolog.php'.URL_APPEND;
$returnfile='labor_test_request_admin_radio.php'.URL_APPEND.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin;
//$thisfile='labor_test_findings_radio.php';

$bgc1='#ffffff';

$edit=1; /* Assume to edit first */

$formtitle=$LDRadiology;
$dept_nr=19; // 19 = department nr. of radiology
//$db_request_table=$subtarget;
$db_request_table='radio';

//$db->debug=1;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;

/* Here begins the real work */

   require_once($root_path.'include/core/inc_date_format_functions.php');
   

     /* Check for the patient number = $pn. If available get the patients data, otherwise set edit to 0 */
     if(isset($pn) && $pn)
	 {		

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

			$result=&$enc_obj->encounter;
		}
	   else 
	   {
	      $edit=0;
		  //$mode='';
		  $pn='';
	   }		
     }
 
//check findings_radio
$sql_sub="SELECT count(*) AS count FROM care_test_findings_radio WHERE batch_nr='".$batch_nr."'";	
if($result_sub=$db->Execute($sql_sub)){
	$temp2=$result_sub->FetchRow();
	if($temp2['count']>0){ 
		$_exist=true;
		$_flag_update=false;
		if($mode!='done')
			$mode='update';
	}
	else $_exist=false;
}else $_exist=false;	 


if($_exist==false){
    $sql="INSERT INTO care_test_findings_radio 
		(batch_nr, encounter_nr, dept_nr, findings, diagnosis, doctor_id, result_date, result_time, status, history, create_id, create_time)
	VALUES ( '".$batch_nr."','".$pn."','".$dept_nr."', 
			'".addslashes(htmlspecialchars($results))."','".addslashes(htmlspecialchars($diagnosis))."',
			 '".htmlspecialchars($results_doctor)."', '".formatDate2STD($results_date,$date_format)."', '".date('H:i:s')."',
			'received',  'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
			'".$_SESSION['sess_user_name']."','".date('YmdHis')."' )";
			
	if($ergebnis=$enc_obj->Transact($sql))
		$_exist=true;
		
	$_flag_update=true;	
}

$sql="UPDATE care_test_request_radio SET
						xray_nr='".$xray_nr."',
						r_cm_2='".$r_cm_2."',
						mtr='".$mtr."',
                        xray_date='".formatDate2STD($xray_date,$date_format)."',
						results='".addslashes(htmlspecialchars($results))."',
                        results_date='".formatDate2STD($results_date,$date_format)."',
						results_doctor='".htmlspecialchars($results_doctor)."',
						status='received',
						history=".$enc_obj->ConcatHistory("Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n").",
						modify_id = '".$_SESSION['sess_user_name']."',
						modify_time='".date('YmdHis')."'
					WHERE batch_nr = '".$batch_nr."'";
$enc_obj->Transact($sql);

switch($mode)
{
			case 'save':					
							      if($_exist)
       							  {
									 //signalNewDiagnosticsReportEvent($results_date);
									
									 header("location:labor_test_request_admin_radio.php?lang=$lang&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=radio&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
									 exit;
								  }
								  else 
								  {
								     echo "<p>$sql<p>$LDDbNoSave"; 
									 $mode='';
								  }
								
								break; // end of case 'save'
			case 'edit':						
			case 'update':
								//findings, diagnosis, doctor_id, result_date, result_time, status, history
								if($_flag_update==false){
							      		$sql="UPDATE care_test_findings_radio SET
										  findings='".addslashes(htmlspecialchars($results))."',
                                          result_date='".formatDate2STD($results_date,$date_format)."',
										  result_time='".date('H:i:s')."',
										  doctor_id='".htmlspecialchars($results_doctor)."',
										  status='received',
										  history=".$enc_obj->ConcatHistory("Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n").",
										  modify_id = '".$_SESSION['sess_user_name']."',
										  modify_time='".date('YmdHis')."'
											WHERE batch_nr = '".$batch_nr."'";
									$ergebnis=$enc_obj->Transact($sql);	
									//echo $sql;									
								}
								
							      if($ergebnis)
       							  {
								     //signalNewDiagnosticsReportEvent($result_date);
									 //echo $sql;
									 header("location:labor_test_request_admin_radio.php?lang=$lang&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=radio&batch_nr=$batch_nr");
									 exit;
								  }
								  else
								   {
								      echo "<p>$sql<p>$LDDbNoSave"; 
								      $mode='';
								   }

								break; // end of case 'save'
								
		     case 'done':
			 
							      $sql="UPDATE care_test_findings_radio SET 
										   status='done',
										   history=".$enc_obj->ConcatHistory("Done: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";
										  							
							      if($ergebnis=$enc_obj->Transact($sql))
       							  {
									//echo $sql;
							          $sql="UPDATE care_test_request_radio SET 
										   status='done',
										   history=".$enc_obj->ConcatHistory("Done: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";

							          if($ergebnis=$enc_obj->Transact($sql))
       							      {
											$sql_radio_sub="SELECT  fi.*, bill.* FROM care_test_findings_radio_sub AS fi, care_billing_item AS bill WHERE fi.batch_nr='".$batch_nr."' AND fi.item_bill_code=bill.item_code ";	
											if($re_ra_sub=$db->Execute($sql_radio_sub)){
												for($j=0;$j<$re_ra_sub->RecordCount();$j++){
													$temp=$re_ra_sub->FetchRow();

													$sql_bill="INSERT INTO care_billing_bill_item 
													(bill_item_id, bill_item_encounter_nr, bill_item_code, bill_item_unit_cost, bill_item_units, 
													bill_item_amount, bill_item_date, bill_item_status, bill_item_bill_no)
													VALUES
													('0', '".$pn."', '".$temp['item_bill_code']."', '".$temp['item_unit_cost']."', '1', 
													'".$temp['item_unit_cost']."', '".date('Y-m-d H:i:s')."', '0', '0' )" ;
													
													$enc_obj->Transact($sql_bill);
													
												}										
											}
											
											$sql_report=" INSERT INTO care_encounter_diagnostics_report 
													(item_nr, report_nr, reporting_dept_nr, reporting_dept, report_date, report_time, 
													encounter_nr, script_call, status, history, modify_id, modify_time, create_id, create_time)
													VALUES
													('0', '".$batch_nr."', '14', 'XQuang', '".date('Y-m-d')."', '".date('H:i:s')."', '".$pn."', 'xquang/PhieuChieuChupXQuang.php?enc=".$pn."&lang=vi&batch_nr=".$batch_nr."', 'done', '', '', '', '".$_SESSION['sess_user_name']."', '".date('Y-m-d H:i:s')."')";
													
											$enc_obj->Transact($sql_report);
										
										// Load the visual signalling functions
										include_once($root_path.'include/core/inc_visual_signalling_fx.php');
										// Set the visual signal 
										setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REPORT);									
									     
									     //exit;
								       }
								       else
								       {
								          echo "<p>$sql<p>$LDDbNoSave"; 
								          $mode='save';
								        }								 
									}
								  else
								   {
								      echo "<p>$sql<p>$LDDbNoSave"; 
								      $mode='save';
								   }
//echo $sql;								   
								header("location:labor_test_request_admin_radio.php?sid=$sid&lang=$lang&user_origin=$user_origin&target=$target&subtarget=radio&noresize=$noresize");
								break; // end of case 'save'
								
						 
			 default:	$mode='';
			 
		  }// end of switch($mode)


