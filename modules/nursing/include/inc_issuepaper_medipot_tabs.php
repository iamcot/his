<?php
//tabPrescription, tabMedicineDepot

if($target=="pres" || $target=="edit") {
	$img_pres='pres-blue.gif';
	$img_depot='depot-gray.gif';
	$img_list='list-depot-gray.gif';
}
elseif($target=="sum" || $target=="depot"){ 
	$img_pres='pres-gray.gif';
	$img_depot='depot-blue.gif';
	$img_list='list-depot-gray.gif';
}else{
	$img_pres='pres-gray.gif';
	$img_depot='depot-gray.gif';
	$img_list='list-depot-blue.gif';
}
	
if($cfg['dhtml']) $pbBuffer='class="fadeOut" ';

	
# tabPrescription
$urlPresTab='<a href="nursing-issuepaper-medipot-pres.php'.URL_APPEND.'&target=pres&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'"><img '.createLDImgSrc($root_path,$img_pres,'0').' alt="'.$LDPrescriptionTxt.'"  title="'.$LDPrescriptionTab.'" '.$pbBuffer.' valign=middle></a>';

$smarty->assign('tabPrescription',$urlPresTab);
	
	
# tabMedicineDepot
$urlDepotTab='<a href="nursing-issuepaper-medipot-depot.php'.URL_APPEND.'&target=depot&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'"><img '.createLDImgSrc($root_path,$img_depot,'0').' alt="'.$LDDepotTxt.'"  title="'.$LDDepotTab.'" '.$pbBuffer.' valign=middle></a>';
	
$smarty->assign('tabMedicineDepot',$urlDepotTab);


# tabMedicineDepot
$urlListDepotTab='<a href="nursing-issuepaper-medipot-listdepot.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'"><img '.createLDImgSrc($root_path,$img_list,'0').' alt="'.$LDListDepotTxt.'"  title="'.$LDListDepotTab.'" '.$pbBuffer.' valign=middle></a>';
	
$smarty->assign('tabListDepot',$urlListDepotTab);

?>