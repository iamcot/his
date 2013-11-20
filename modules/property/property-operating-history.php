<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
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
$tb_property_operation='care_property_operation';
$breakfile='property-admi-welcome.php'.URL_APPEND;
define('MAXBLOCKROW',20);
define('LANG_FILE','properties.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();

$smarty->assign('sToolbarTitle',"$LDPropertyManagement::$LDOperationHistory");
$smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
# href for close button
$smarty->assign('breakfile',$breakfile);
# Window bar title
$smarty->assign('sWindowTitle',"$LDPropertyManagement::$LDOperationHistory");

$sTemp = "<tr>
			<th class='adm_item'>$LDOperationID</th>
			<th class='adm_item'>$LDOperation</th>
			<th class='adm_item'>$LDOperator</th>
			<th class='adm_item'>$LDReason</th>
			<th class='adm_item'>$LDManager</th>
			<th class='adm_item'>$LDTime</th>
			<th class='adm_item'>$LDResult</th>
			<th class='adm_item'>$LDBeforeStatus</th>
			<th class='adm_item'>$LDAfterStatus</th>
		 </tr>";
$query="SELECT count(nr) FROM $tb_property_operation WHERE use_nr = $using_nr ";
$rows=$property->countResultRows($query);
$rowperpage=MAXBLOCKROW;
$pagination=ceil($rows/$rowperpage);
if(!isset($page)){
    $page = 1 ;
}
$start = ($page-1) * $rowperpage;

$propitems = array('nr','use_nr','operation', 'reason', 'manager', 'time', 'operator', 'result', 'before_status', 'after_status');
$query="SELECT ";
$querytmp = "";
while (list($key, $val) = each($propitems)) {
	$querytmp .= $val . ", ";
}
$query.=substr($querytmp,0,-2);
$query.= " FROM $tb_property_operation WHERE use_nr = $using_nr ";
$query .= " ORDER BY time DESC ";
$propusinglist=$property->getPropertyItemsObject($query, $start, $rowperpage);

$order = 0;
$toggle=0;
if($propusinglist != false){
	while($row=$propusinglist->FetchRow()){
		$order++;
		if($toggle)	$trc='#dedede';
		else $trc='#efefef';
		$toggle=!$toggle;
		$personell_info = $personell_obj->getPersonellName($row['manager']); 
		$sTemp .= "<tr bgcolor='$trc'><td style='text-align:center;padding: 2px 5px 2px 5px;'><a href='".$root_path."modules/property/property-operate.php".URL_REDIRECT_APPEND."&smtname=modify&use_nr=$using_nr&op_nr=".$row['nr']."'>$order&nbsp;<img ".createComIcon($root_path,'bul_arrowblusm.gif','0','absmiddle')."></a></td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['operation']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['operator']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['reason']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$personell_info['nr']." - ".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".formatDate2Local($row['time'], $date_format)."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['result']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['before_status']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['after_status']."</td></tr>";
	}
}
if($toggle)	$trc='#dedede';
else $trc='#efefef';
$sTemp .= "<tr bgcolor='$trc'><td style='text-align:center'><a href='".$root_path."modules/property/property-operate.php".URL_REDIRECT_APPEND."&smtname=add&use_nr=$using_nr' title='$LDAddOperationInfo'><img ".createComIcon($root_path,'plus.gif','0','absmiddle')."></a></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td>";
$sTemp .= "<td><input type='text' style='width:98%;' maxlength=40 readonly='true'></td></tr>";

$smarty->assign('operatinglis',$sTemp);
$pagingurl = $root_path."modules/property/property-operating-history.php".URL_REDIRECT_APPEND."&mode=changepage&using_nr=$using_nr";
require_once('Pagenation.php');
$smarty->assign('pagelist',$sTemp);
$smarty->assign('sMainBlockIncludeFile','property/property_operating_history.tpl');
$smarty->display('common/mainframe.tpl');

?>