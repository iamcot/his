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
$tb_property_use='care_property_use';
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

$propitems = array('name_formal', 'name_short');
$prop_info = $property->getInfomationOfProp($propitems, $prop_nr);
# Added for the common header top block
$smarty->assign('sToolbarTitle',"$LDPropertyManagement::$LDUseHistory: ".$prop_info['name_formal']." - ".$prop_info['name_short']);
$smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
# href for close button
$smarty->assign('breakfile',$breakfile);
# Window bar title
$smarty->assign('sWindowTitle',"$LDPropertyManagement::$LDUseHistory");
//<th class='adm_item' style='width:4%;'>$LDView</th>
$sTemp = "<tr>
			
			<th class='adm_item' style='width:4%;'>$LDOrder</th>
			<th class='adm_item' style='width:12%;'>$LDDeptName</th>
			<th class='adm_item' style='width:5%;'>$LDWardName</th>
			<th class='adm_item' style='width:5%;'>$LDRoomName</th>
			<th class='adm_item' style='width:12%;'>$LDManager</th>
			<th class='adm_item' style='width:8%;'>$LDImportDate</th>
			<th class='adm_item' style='width:10%;'>$LDReason</th>
			<th class='adm_item' style='width:10%;'>$LDImportStatus</th>
			<th class='adm_item' style='width:8%;'>$LDUseDate</th>
			<th class='adm_item' style='width:10%;'>$LDFunction</th>
			<th class='adm_item' style='width:12%;'>$LDCurrentStatus</th>
		 </tr>";
$query="SELECT count(nr) FROM $tb_property_use WHERE prop_nr = $prop_nr ";
$rows=$property->countResultRows($query);
$rowperpage=MAXBLOCKROW;
$pagination=ceil($rows/$rowperpage);
if(!isset($page)){
    $page = 1 ;
}
$start = ($page-1) * $rowperpage;

$propitems = array('nr','dept','ward', 'room', 'manager', 'im_date', 'im_status', 'use_date', 'function', 'current_status', 'reason', 'pre_use');
$query="SELECT ";
$querytmp = "";
while (list($key, $val) = each($propitems)) {
	$querytmp .= $val . ", ";
}
$query.=substr($querytmp,0,-2);
$query.= " FROM $tb_property_use WHERE prop_nr = $prop_nr ";
$query .= " ORDER BY im_date DESC ";
$propusinglist=$property->getPropertyItemsObject($query, $start, $rowperpage);

$toggle=0;
if($propusinglist != false){
	while($row=$propusinglist->FetchRow()){
		if($toggle)	$trc='#dedede';
		else $trc='#efefef';
		$toggle=!$toggle;
		$dept_info = &$dept_obj->getDeptAllInfo($row['dept']);
		$ward_obj = new Ward($row['ward']);
		$ward_items = array("ward_id","name");
		$ward_info = $ward_obj->getWardsItemsArray($ward_items);
		$room_info = $ward_obj->getRoomsNumber($row['room']);
		$personell_info = $personell_obj->getPersonellName($row['manager']); 
		$sTemp .= "<tr bgcolor='$trc'>";
		//<td style='text-align:center; padding: 2px 5px 2px 5px;'><a href='".$root_path."modules/property/property-operating-history.php".URL_REDIRECT_APPEND."&using_nr=".$row['nr']."'><img ".createComIcon($root_path,'bul_arrowblusm.gif','0','absmiddle')."></a></td>";
		$sTemp .= "<td style='text-align:center; padding: 2px 5px 2px 5px;'><a href='".$root_path."modules/property/property-transmit.php".URL_REDIRECT_APPEND."&mode=modify&prop_nr=$prop_nr&pre_using=".$row['pre_use']."&using_nr=".$row['nr']."'> ".$prop_info['name_short'].$row['nr']."</a></td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$$dept_info['LD_var']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$ward_info['ward_id']." - ".$ward_info['name']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$room_info['room_nr']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$personell_info['nr']." - ".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".formatDate2Local($row['im_date'], $date_format)."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['reason']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['im_status']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".formatDate2Local($row['use_date'], $date_format)."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['function']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['current_status']."</td></tr>";
	}
}
$smarty->assign('propertylis',$sTemp);
$pagingurl = $root_path."modules/property/property-using-history.php".URL_REDIRECT_APPEND."&mode=changepage&prop_nr=$prop_nr";
require_once('Pagenation.php');
$smarty->assign('pagelist',$sTemp);
$smarty->assign('sMainBlockIncludeFile','property/property_using_history.tpl');
$smarty->display('common/mainframe.tpl');

?>