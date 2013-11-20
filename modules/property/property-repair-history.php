<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$tb_property='care_property';
$tb_property_repair='dfck_property_repair';
$tb_ward = 'care_ward';
define('MAXBLOCKROW',20);
$lang_tables[]='departments.php';
define('LANG_FILE','properties.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
/* Load the ward object */
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
$breakfile='property-admi-welcome.php'.URL_APPEND;
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');
require_once($root_path.'include/care_api_classes/class_ward.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();
/* Load the dept object */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;

$propitems = array('name_formal', 'model','serie');
$prop_info = $property->getInfomationOfProp($propitems, $prop_nr);
# Added for the common header top block
$smarty->assign('sToolbarTitle',"$LDPropertyManagement::$LDRepairHistory: ".$prop_info['name_formal']." - ".$prop_info['model']." - ".$prop_info['serie']);
# href for close button
$smarty->assign('breakfile',$breakfile);
# Window bar title
$smarty->assign('sWindowTitle',"$LDPropertyManagement::$LDRepairHistory");
//<th class='adm_item' style='width:4%;'>$LDView</th>
$sTemp = "<tr>			
			<th class='adm_item' style='width:4%;'>$LDOrder</th>
			<th class='adm_item' >$LDReportdate</th>
			<th class='adm_item' >$LDProposer</th>
			<th class='adm_item' >$LDReportdetail</th>
			<th class='adm_item' >$LDRepairdate</th>
			<th class='adm_item' >$LDRepairdetail</th>
			<th class='adm_item' >$LDRepairperson</th>		
		 </tr>";
$query="SELECT count(nr) FROM $tb_property_repair WHERE prop_nr = $prop_nr ";
$rows=$property->countResultRows($query);
$rowperpage=MAXBLOCKROW;
$pagination=ceil($rows/$rowperpage);
if(!isset($page)){
    $page = 1 ;
}
$start = ($page-1) * $rowperpage;

$propitems = array('nr','damaged_date','request_person','damaged_detail','repair_date','repair_detail','repair_person','prop_nr');
$query="SELECT ";
$querytmp = "";
while (list($key, $val) = each($propitems)) {
	$querytmp .= $val . ", ";
}
$query.=substr($querytmp,0,-2);
$query.= " FROM ".$tb_property_repair." WHERE prop_nr = ".$prop_nr." ";
$query .= " ORDER BY repair_date DESC ";
$propusinglist=$property->getPropertyItemsObject($query, $start, $rowperpage);
$toggle=0;
if($propusinglist != false){
	while($row=$propusinglist->FetchRow()){
		if($toggle)	$trc='#dedede';
		else $trc='#efefef';
		$toggle=!$toggle;

		$personell_info = $personell_obj->getPersonellName($row['request_person']); 
		$sTemp .= "<tr  bgcolor='$trc'>";
		$sTemp .= "<td style='padding: 5px;text-align:center;'>".$row['nr']."</td>";
		$sTemp .= "<td style='padding: 5px;text-align:center;'>".date("d/m/Y",strtotime($row['damaged_date']))."</td>";
		$sTemp .= "<td style='padding: 5px;text-align:center;'>".$personell_info['nr']." - ".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."</td>";
		$sTemp .= "<td style='padding: 5px;text-align:center;'>".$row['damaged_detail']."</td>";
		$sTemp .= "<td style='padding: 5px;text-align:center;'>".date("d/m/Y",strtotime($row['repair_date']))."</td>";
		$sTemp .= "<td style='padding: 5px;text-align:center;'>".$row['repair_detail']."</td>";
		$sTemp .= "<td style='padding: 5px;text-align:center;'>".$row['repair_person']."</td>";
		$sTemp .= "</tr>";
	}
}
$smarty->assign('propertylis',$sTemp);
$pagingurl = $root_path."modules/property/property-using-history.php".URL_REDIRECT_APPEND."&mode=changepage&prop_nr=$prop_nr";
require_once('Pagenation.php');
$smarty->assign('pagelist',$sTemp);
$smarty->assign('sMainBlockIncludeFile','property/property_using_history.tpl');
$smarty->display('common/mainframe.tpl');

?>