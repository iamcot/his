<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (stristr($_SERVER['SCRIPT_NAME'],'save_soket.php')) 
	die('<meta http-equiv="refresh" content="0; url=../">');	

	
//$obj->setDataArray($_POST);
extract($_POST);


switch($mode)
{	
	case 'create': 
	case 'new':
				
			if($obj->insertSoKet15Ngay($encounter_nr, $date, $text_dienbien, $text_xetnghiemcls, $text_quatrinhdieutri, $text_danhgiakq, $text_huongdieutri)) {										
					header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&mode=show&encounter_nr=".$_SESSION['sess_en']."&type_medoc=".$type_medoc);
					exit;								
			} else echo "$obj->sql<br>$LDDbNoSave";
			break;
			
	case 'update': 
		
			if($obj->updateSoKet15Ngay($encounter_nr, $date, $lansoket, $text_dienbien, $text_xetnghiemcls, $text_quatrinhdieutri, $text_danhgiakq, $text_huongdieutri)){
									
					header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&encounter_nr=".$_SESSION['sess_en']."&type_medoc=".$type_medoc);
					exit;
									
			} else echo "$obj->sql<br>$LDDbNoUpdate";
			break;
}// end of switch

?>
