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
$returnfile='labor_test_request_admin_dientim.php'.URL_APPEND.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin.'&tracker='.$tracker;
$thisfile='labor_test_findings_dientim.php';

$bgc1='#ffffff';

$edit=1; /* Assume to edit first */

$formtitle=$LDRadiology;
$dept_nr=19; // 19 = department nr. of radiology
$db_request_table=$subtarget;

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
		  $mode='';
		  $pn='';
	   }		
     }
	 

//echo $mode;   
	 //if(!isset($mode) && $batch_nr && $pn)   $mode='edit';
		
		  switch($mode)
		  {
				     case 'save':
							
                                 $sql="INSERT INTO care_test_findings_".$db_request_table." 
								          (
										   batch_nr, encounter_nr, dept_nr, 
										   findings, diagnosis,
										   doctor_id, findings_date, findings_time, 
										   status, 
										   history,
										  create_id,
										  create_time
										  )
										   VALUES
										   (
										   '".$batch_nr."','".$pn."','".$dept_nr."', 
										   '".addslashes(htmlspecialchars($findings))."','".addslashes(htmlspecialchars($diagnosis))."',
										   '".htmlspecialchars($doctor_id)."', '".formatDate2STD($findings_date,$date_format)."', '".date('H:i:s')."',
										   'initial',  
										   'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
										  '".$_SESSION['sess_user_name']."',
										  '".date('YmdHis')."'
										   )";


							      if($ergebnis=$enc_obj->Transact($sql))
       							  {
								     //signalNewDiagnosticsReportEvent($findings_date);
									 //echo $sql;
									header("location:labor_test_request_admin_dientim.php?lang=$lang&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=radio&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
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
			 
							      $sql="UPDATE care_test_findings_".$db_request_table."  SET 
										   findings='".addslashes(htmlspecialchars($findings))."', 
										   diagnosis='".addslashes(htmlspecialchars($diagnosis))."',
										   doctor_id='".htmlspecialchars($doctor_id)."', 
										   findings_date='".formatDate2STD($findings_date,$date_format)."',
										   findings_time='".date('H:i:s')."', 
										   history=".$enc_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";

							      if($ergebnis=$enc_obj->Transact($sql))
       							  {
								     //signalNewDiagnosticsReportEvent($findings_date);
									 //echo $sql;
									  header("location:labor_test_request_admin_dientim.php?lang=$lang&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=radio&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
									 exit;
								  }
								  else
								   {
								      echo "<p>$sql<p>$LDDbNoSave"; 
								      $mode='';
								   }
								
								break; // end of case 'update'
								
		     case 'done':
			 
							      $sql="UPDATE care_test_findings_".$db_request_table." SET 
										   status='done',
										   history=".$enc_obj->ConcatHistory("Done: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";
									
									
							      if($ergebnis=$enc_obj->Transact($sql))
       							  {
									
							          $sql="UPDATE care_test_request_".$db_request_table." SET 
										   status='done',
										   history=".$enc_obj->ConcatHistory("Done: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";

							          if($ergebnis=$enc_obj->Transact($sql))
       							      {
								  		// Load the visual signalling functions
										
										$sql_report=" INSERT INTO care_encounter_diagnostics_report 
													(item_nr, report_nr, reporting_dept_nr, reporting_dept, report_date, report_time, 
													encounter_nr, script_call, status, history, modify_id, modify_time, create_id, create_time)
													VALUES
													('0', '".$batch_nr."', '14', '".$LDDienTim."', '".date('Y-m-d')."', '".date('H:i:s')."', '".$pn."', 'dientim/PhieuDienTim.php?enc=".$pn."&lang=vi&batch_nr=".$batch_nr."', 'done', '', '', '', '".$_SESSION['sess_user_name']."', '".date('Y-m-d H:i:s')."')";
													
											$enc_obj->Transact($sql_report);
										
										//echo $sql_report;
										
										include_once($root_path.'include/core/inc_visual_signalling_fx.php');
										// Set the visual signal 
										setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REPORT);									
									      header("location:labor_test_request_admin_dientim.php?lang=$lang&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=radio&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
									     exit;
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
								
								break; // end of case 'done'
								
						 
			 default:	$mode='';
			 
		  }// end of switch($mode)


//if($edit) $returnfile.='&batch_nr='.$batch_nr.'&pn='.$pn.'&tracker='.$tracker; 

?>