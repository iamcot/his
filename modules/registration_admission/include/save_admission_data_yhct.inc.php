<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (stristr($_SERVER['SCRIPT_NAME'],'save_admission_data1.inc.php')) 
	die('<meta http-equiv="refresh" content="0; url=../">');	

	
//$obj->setDataArray($_POST);
extract($_POST);
	
switch($mode)
{	
	case 'create': 
	case 'new':
					if($obj->savelankham($encounter_nr, $bienchung, $chandoan, $personell_name, $date)){
						$pk=$obj->LastInsertPK('nr',$db->Insert_ID());
						if($listitem = $obj->showallMucKham()){
							for($k=1;$k<=$listitem->RecordCount();$k++){
								$item = $listitem->FetchRow();
								
								$tempcbx=explode("_",$item['cbx']);
								$cbx='';
								for($j=0;$j<$tempcbx[0];$j++){
									$a = 'cbx'.$k.'_'.$j;
									$cbx .= $$a.'_';
								}
								//$text1, $cbx1_0, $cbx1_1, $radio1
								$radiodx = 'radio'.$k;
								$textdx = 'text'.$k;
								$obj->savemuckham($pk, $item['detail_nr'], $cbx, $$radiodx, $$textdx);							
							}				
						}
						
						header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&mode=details&encounter_nr=".$encounter_nr."&nr=".$pk);
						exit;
						
					} else echo "$obj->sql<br>$LDDbNoSave";
					break;
					
	case 'update': 
					if($obj->updatelankham($nr, $bienchung, $chandoan, $personell_name, $date)){
						if($listitem = $obj->showallMucKham()){
							for($k=1;$k<=$listitem->RecordCount();$k++){
								$item = $listitem->FetchRow();
								
								$tempcbx=explode("_",$item['cbx']);
								$cbx='';
								for($j=0;$j<$tempcbx[0];$j++){
									$a = 'cbx'.$k.'_'.$j;
									$cbx .= $$a.'_';
								}
								//$text1, $cbx1_0, $cbx1_1, $radio1
								$radiodx = 'radio'.$k;
								$textdx = 'text'.$k;
								$obj->updatemuckham($nr, $item['detail_nr'], $cbx, $$radiodx, $$textdx);							
							}				
						}
						
						header("location:show_admit_yhct.php".URL_REDIRECT_APPEND."&target=$target&mode=details&encounter_nr=".$encounter_nr."&nr=".$nr);
						exit;
						
					} else echo "$obj->sql<br>$LDDbNoSave";
					break;								
								
}// end of switch

?>
