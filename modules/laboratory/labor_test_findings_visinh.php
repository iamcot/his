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

$breakfile=$root_path.'modules/laboratory/labor.php'.URL_APPEND;
$returnfile='labor_test_request_admin_'.$subtarget.'.php'.URL_APPEND.'&target='.$target.'&subtarget='.$subtarget.'&user_origin='.$user_origin;
$thisfile='labor_test_findings_'.$subtarget.'.php';

$bgc1='#ffffff';

$edit=1; /* Assume to edit first */

$formtitle='Xét nghiệm vi sinh';
$dept_nr=19; // 19 = department nr. of radiology
//$db_request_table=$subtarget;
$db_request_table='visinh';

//$db->debug=1;
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();

require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;
require($root_path.'include/care_api_classes/class_ecombill.php');
$eComBill = new eComBill;
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
	 
	   
	 //if(!isset($mode) && $batch_nr && $pn)   $mode='edit';
//echo $mode;

	switch($mode)
		  {
					 case 'save':
							
                                 $sql="INSERT INTO care_test_findings_".$db_request_table." 
								          (
										   batch_nr, encounter_nr, dept_nr, 
										   results,
										   result_date, result_time, 
										   status, 
										   history,
										  create_id,
										  create_time
										  )
										   VALUES
										   (
										   '".$batch_nr."','".$pn."','".$dept_nr."', 
										   '".addslashes(htmlspecialchars($result))."',
										   '".formatDate2STD($result_date,$date_format)."', '".date('H:i:s')."',
										   'initial',  
										   'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
										  '".$_SESSION['sess_user_name']."',
										  '".date('YmdHis')."'
										   )";
//echo $sql;
								
							      if($ergebnis=$enc_obj->Transact($sql))
       							  {
									 $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));
								     signalNewDiagnosticsReportEvent($findings_date);
									 //echo $sql;
									 header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=insert&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
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
										   doctor_id='".htmlspecialchars($result_doctor)."', 
										   results='".addslashes(htmlspecialchars($result))."',
										   result_date='".formatDate2STD($result_date,$date_format)."',
										   result_time='".date('H:i:s')."', 
										   history=".$enc_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";
//echo $sql;
							      if($ergebnis=$enc_obj->Transact($sql))
       							  {
								   $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));
								     signalNewDiagnosticsReportEvent($findings_date);
									 //echo $sql;
									 header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=insert&mode=edit&pn=$pn&station=$station&user_origin=$user_origin&status=$status&target=$target&subtarget=$subtarget&noresize=$noresize&batch_nr=$batch_nr&entry_date=$entry_date");
									 exit;
								  }
								  else
								   {
								      echo "<p>$sql<p>$LDDbNoSave"; 
								      $mode='';
								   }
								
								break; // end of case 'save'
								
		     case 'done':
			 
							      $sql="UPDATE care_test_findings_".$db_request_table." SET 
										   status='done',
										   history=".$enc_obj->ConcatHistory("Done: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";
										//echo $sql;  							
							      if($ergebnis=$enc_obj->Transact($sql))
       							  {
									//
									
							          $sql="UPDATE care_test_request_".$db_request_table." SET 
										   status='done',
										   history=".$enc_obj->ConcatHistory("Done: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
										   modify_id = '".$_SESSION['sess_user_name']."',
										   modify_time='".date('YmdHis')."'
										   WHERE batch_nr = '".$batch_nr."'";

							          if($ergebnis=$enc_obj->Transact($sql))
       							      {
									  
								  		// Load the visual signalling functions
										include_once($root_path.'include/core/inc_visual_signalling_fx.php');
										// Set the visual signal 
										setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REPORT);	
										/*
									    $sql1="select lao,kstdr,huyettrang from care_test_request_visinh where batch_nr='".$batch_nr."'";
										$test=$db->execute($sql1);
										if($test->recordcount()){
											$buf=$test->fetchrow();
										}
										if($buf['lao']==1){
											$eComBill->createBillItem($pn, 'VTH01','12000', 1, '12000',date("Y-m-d G:i:s") );	
										}elseif($buf['kstdr']==1){
											$eComBill->createBillItem($pn, 'VTH02','12000', 1, '12000',date("Y-m-d G:i:s") );	
										}elseif($buf['huyettrang']==1){
											$eComBill->createBillItem($pn, 'VTH03','12000', 1, '12000',date("Y-m-d G:i:s") );	
										}
										*/
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
								header("location:labor_test_request_admin_visinh.php?sid=$sid&lang=$lang&user_origin=$user_origin&target=$target&subtarget=$subtarget&noresize=$noresize");
								break; // end of case 'save'
								
						 
			 default:	$mode='';
			 
		  }// end of switch($mode)


