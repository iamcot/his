<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (stristr($_SERVER['SCRIPT_NAME'],'save_admission_data.inc.php')) 
	die('<meta http-equiv="refresh" content="0; url=../">');	
	
$obj->setDataArray($_POST);
	
switch($mode){	
		case 'create': 
						if($obj->insertDataFromInternalArray()) {
						$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
							if(!$no_redirect){
								header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&pid=".$_SESSION['sess_pid']);
								//echo "$obj->getLastQuery<br>$LDDbNoSave";
								exit;
							}
						} else{
                           echo "$obj->getLastQuery<br>$LDDbNoSave";
                           $error=TRUE;
                        }
						break;
		case 'update': 
						$obj->where=' nr='.$nr;
						if($obj->updateDataFromInternalArray($nr)) {
						$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $obj->getLastQuery(), date('Y-m-d H:i:s'));
							if(!$no_redirect){
								header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&type_nr=$type_nr&allow_update=1&pid=".$_SESSION['sess_pid']);
								//echo "$obj->sql<br>$LDDbNoUpdate";
								exit;
							}
						} else{
                          echo "$obj->getLastQuery<br>$LDDbNoUpdate";
                          $error=TRUE;
                        }
						break;
}// end of switch

?>