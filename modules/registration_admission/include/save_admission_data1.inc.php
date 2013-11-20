<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (stristr($_SERVER['SCRIPT_NAME'],'save_admission_data1.inc.php')) 
	die('<meta http-equiv="refresh" content="0; url=../">');	

	
$obj->setDataArray($_POST);
	
switch($mode)
{	
	case 'create': 
								if($obj->insertDataFromInternalArray()) {
								// $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
									if(isset($redirect) && $redirect){
										header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&mode=details&encounter_nr=".$_SESSION['sess_en']."&nr=".$obj->LastInsertPK('nr',$db->Insert_ID()));
										exit;
									}
								} else echo "$obj->sql<br>$LDDbNoSave";
								
								break;
	case 'update': 
								$obj->where=' nr='.$nr;
								if($obj->updateDataFromInternalArray($nr)) {
								 //$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
									if($redirect){
										header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&encounter_nr=".$_SESSION['sess_en']);
										echo "$obj->sql<br>$LDDbNoUpdate";
										exit;
									}
								} else echo "$obj->sql<br>$LDDbNoUpdate";
								break;
}// end of switch

?>
