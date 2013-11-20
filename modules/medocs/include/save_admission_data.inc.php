<?php
/*------begin------ This protection code was suggested by Luki R. luki@karet.org ---- */
if (stristr($_SERVER['SCRIPT_NAME'],'save_admission_data.inc.php')) 
	die('<meta http-equiv="refresh" content="0; url=../">');	

	
//$obj->setDataArray($_POST);
extract($_POST);

$array_phauthuat=array();
if($cb_pt=='') $cb_pt=0;
if($cb_tt=='') $cb_tt=0;
for($i=0;$i<$maxelements;$i++){
	$ngaygiodx='pt_ngaygio'.$i;
	$ngay = explode(' ',$$ngaygiodx);
	$ppdx='pt_phuongphap'.$i;
	$bsptdx='pt_bspt'.$i;
	$bsgmdx='pt_bsgm'.$i;

	$array_phauthuat[$i]['date']=formatDate2STD($ngay[0],$date_format);
	$array_phauthuat[$i]['time']=$ngay[1];
	$array_phauthuat[$i]['pppt']=$$ppdx;
	$array_phauthuat[$i]['bspt']=$$bsptdx;
	$array_phauthuat[$i]['bsgm']=$$bsgmdx;
	$array_phauthuat[$i]['cb_pt']=$cb_pt;
	$array_phauthuat[$i]['cb_tt']=$cb_tt;
	//echo $$ngaygiodx.' '.$$ppdx.' '.$$bsptdx.' '.$$bsgmdx.' '.$cb_pt.' '.$cb_tt;
}

switch($mode)
{	
	case 'create': 
	case 'new':
				
			if($obj->insertTongKetBenhAn($encounter_nr, $date, $type_medoc, $yhct_lydovao, $text_progress, $text_sumLab, $yhct_kqgpb, $yhct_chandoanvao, $yhct_phaptri, $yhct_thoigiantri, $yhct_ketquatri, $text_outdia, $text_therapy, $array_phauthuat, $text_outhos, $text_treatment)) {										
					header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&mode=show&encounter_nr=".$_SESSION['sess_en']."&type_medoc=".$type_medoc);
					exit;								
			} else echo "$obj->sql<br>$LDDbNoSave";
			break;
			
	case 'update': 
		
			if($obj->updateTongKetBenhAn($encounter_nr, $date, $type_medoc, $yhct_lydovao, $text_progress, $text_sumLab, $yhct_kqgpb, $yhct_chandoanvao, $yhct_phaptri, $yhct_thoigiantri, $yhct_ketquatri, $text_outdia, $text_therapy, $array_phauthuat, $text_outhos, $text_treatment)){
									
					header("location:".$thisfile.URL_REDIRECT_APPEND."&target=$target&encounter_nr=".$_SESSION['sess_en']."&type_medoc=".$type_medoc);
					exit;
									
			} else echo "$obj->sql<br>$LDDbNoUpdate";
			break;
}// end of switch

?>
