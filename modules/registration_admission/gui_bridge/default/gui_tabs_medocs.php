<?php
if(!isset($notabs)||!$notabs){
	
	if($target=="entry")  $img='document-blue.gif'; //echo '<img '.createLDImgSrc($root_path,'admit-blue.gif','0').' alt="'.$LDAdmit.'">';
		else{ $img='document-gray.gif';}

	$smarty->assign('pbNew','<a href="patient_register_show.php'.URL_APPEND.'&pid'.$_SESSION['sess_full_pid'].'&edit=1&status='.$_SESSION['sess_en'].'&target='.$target.'&user_origin=&noresize=1&mode="><img '.createLDImgSrc($root_path,$img,'0').' title="'.$LDAdmit.'" class="fadeOut" ></a>');

	if($target=="search") $img='such-b.gif';
		else{ $img='such-gray.gif'; }

	$smarty->assign('pbSearch','<a href="aufnahme_daten_zeigen.php'.URL_APPEND.'&from=such&encounter_nr='.$_SESSION['sess_en'].'&target='.$target.'"><img '.createLDImgSrc($root_path,$img,'0').' title="'.$LDSearch.'"  class="fadeOut" ></a>');

}

if(!empty($subtitle)) $smarty->assign('subtitle','<font color="#fefefe" SIZE=3  FACE="verdana,Arial"><b>:: '.$subtitle);
?>