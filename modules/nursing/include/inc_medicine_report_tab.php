<?php
//tabCreateReport, tabListReport

if($target=="new" || $target=="create" || $target=="edit" || $target=="update" ) {
	$img_depot='depot-blue.gif';
	$img_list='list-depot-gray.gif';
}else{
	$img_depot='depot-gray.gif';
	$img_list='list-depot-blue.gif';
}
	
if($cfg['dhtml']) $pbBuffer='class="fadeOut" ';
	
	
# tabNewReport
$urlDepotTab='<a href="'.$new_report_url.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'"><img '.createLDImgSrc($root_path,$img_depot,'0').' title="'.$LDNewReportTab.'" '.$pbBuffer.' valign=middle></a>';
	
$smarty->assign('tabNewReport',$urlDepotTab);


# tabListReport
$urlListDepotTab='<a href="'.$list_report_url.URL_APPEND.'&target=list&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'"><img '.createLDImgSrc($root_path,$img_list,'0').' title="'.$LDListReportTab.'" '.$pbBuffer.' valign=middle></a>';
	
$smarty->assign('tabListReport',$urlListDepotTab);

?>