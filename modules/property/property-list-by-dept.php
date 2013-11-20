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
$tb_property_use='care_property_use';
$breakfile='property-admi-welcome.php'.URL_APPEND;
$lang_tables[]='departments.php';
define('MAXBLOCKROW',20);
define('LANG_FILE','properties.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
/* Load the ward object */
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');

$dept_info = &$dept_obj->getDeptAllInfo($dept_nr);
# Added for the common header top block
$smarty->assign('sToolbarTitle',$$dept_info['LD_var']."::$LDPropertyList");
$smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
# href for close button
$smarty->assign('breakfile',$breakfile);
# Window bar title
$smarty->assign('sWindowTitle',$$dept_info['LD_var']."::$LDPropertyList");

$sTemp = "<tr>
			<th class='adm_item gray' style='width:5%'>ID</th>
			<th class='adm_item gray' style='width:10%'>$LDPropFormalName</th>
			<th class='adm_item gray' >$LDPropModel</th>
			<th class='adm_item gray' >$LDPropSerieNr</th>
			<th class='adm_item gray' >$LDPropMaker</th>
                        <th class='adm_item gray' >$LDPropMannual</th>
                        <th class='adm_item gray' >$LDPropCountry</th>
                        <th class='adm_item gray' >$LDPropStartUseDate</th>
                        <th class='adm_item gray' >$LDCurrentStatus</th>
			<th class='adm_item gray' >$LDCurrentManager</th>
		
			<th class='adm_item gray'>$LDWorkOrStop</th>
		 </tr>";

$query="SELECT count(DISTINCT nr) FROM $tb_property WHERE current_dept=$dept_nr";
$rows=$property->countResultRows($query);
$rowperpage=MAXBLOCKROW;
$pagination=ceil($rows/$rowperpage);
if(!isset($page)){
    $page = 1 ;
}
$start = ($page-1) * $rowperpage;

$propitems = array('model','name_formal', 'proptype', 'serie', 'status', 'current_status', 'manager','factorer','manual','country','useddate',);
$query="SELECT DISTINCT p.nr AS prop_nr, u.im_date, ";
$querytmp = "";
while (list($key, $val) = each($propitems)) {
	$querytmp .= $val . ", ";
}
$query.=substr($querytmp,0,-2);
$query.=" 	FROM $tb_property_use AS u, $tb_property AS p 
			WHERE 	(p.current_dept=$dept_nr)
					AND (u.dept = $dept_nr)
					AND (u.im_date = (SELECT MAX(u1.im_date) FROM $tb_property_use u1 WHERE u1.prop_nr = p.nr)) 
					AND (p.nr = u.prop_nr)
			ORDER BY u.im_date DESC"; 
$proplistdata=$property->getPropertyItemsObject($query, $start, $rowperpage);
$toggle=0;
$order = 0;
if($proplistdata != false){
	while($row=$proplistdata->FetchRow()){
		if($toggle)	$trc='#dedede';
				else $trc='#efefef';
			$toggle=!$toggle;
		$order++;
		$personell_info = $personell_obj->getPersonellName($row['manager']); 
		$sTemp .= "<style>td{padding:3px;vertical-align:top;}</style><tr bgcolor='$trc'><td style='text-align:center'>".$row['prop_nr']."</td>";
                $sTemp .= "<td style=''><a href='".$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=".$row['prop_nr']."'>".$row['name_formal']."</a></td>";				
                $sTemp .= "<td style=''>".$row['model']."</td>";
                $sTemp .= "<td style=''>".$row['serie']."</td>";
                    $sTemp .= "<td style='text-align:center'>".$row['factorer']."</td>";
                $sTemp .= "<td style='text-align:center'><a href='".$root_path.$row['manual']."'>".$LDManuaDownloadlLink."</a></td>";
                $sTemp .= "<td style='text-align:center'>".$row['country']."</td>";
                $sTemp .= "<td style='text-align:center'>".$row['useddate']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$row['current_status']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".$personell_info['nr']." - ".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."</td>";
		$sTemp .= "<td style='padding: 2px 5px 2px 5px;'>".($row['status']==1?$LDWork:$LDStop)."</td></tr>";
	}
}
$smarty->assign('propertylis',$sTemp);
$pagingurl = $root_path."modules/property/property-list-viadept.php".URL_REDIRECT_APPEND."&mode=changepage";
require_once('Pagenation.php');
$smarty->assign('pagelist',$sTemp);
$smarty->assign('sMainBlockIncludeFile','property/property_list_by_dept.tpl');
$smarty->display('common/mainframe.tpl');

?>