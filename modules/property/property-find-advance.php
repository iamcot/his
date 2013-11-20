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
$lang_tables[]='departments.php';
define('MAXBLOCKROW',20);
define('LANG_FILE','properties.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
/* Load the ward object */
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();
//require($root_path.'include/core/inc_checkdate_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
$breakfile='property-admi-welcome.php'.URL_APPEND;

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');
# Added for the common header top block
$dept_info = &$dept_obj->getDeptAllInfo($dept_nr);
$smarty->assign('sToolbarTitle',(isset($dept_nr)?$$dept_info['LD_var']:"$LDPropertyManagement")."::$LDFindProperties");
$smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
# href for close button
$smarty->assign('breakfile',$breakfile);
# Window bar title
$smarty->assign('sWindowTitle',(isset($dept_nr)?$$dept_info['LD_var']:"$LDPropertyManagement")."::$LDFindProperties");
# Buffer page output
ob_start();
?>
<style type="text/css" name="formstyle">

</style>

<script language="javascript">
function check(d){
	
}
<?php
require($root_path.'include/core/inc_checkdate_lang.php');
?>
</script>

<?php
//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
//end : gjergji
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);
# make dept select

$deptmanastr = "<option value='%'>Tất cả</option>";
//echo $_POST['dept_mana'];
foreach($deptlist as $dept){    
	//echo $dept[0];
	if($dept[0] == $_POST['dept_mana']) $select=' selected="true" '; else $select = '';
    $deptmanastr .= '<option value="'.$dept[0].'" '.$select.'>'.$dept[1].'</option>';
}
 $smarty->assign('dept_mana',$deptmanastr); 
# Assign form items
$smarty->assign('LDPropFormalName',$LDPropFormalName);
$smarty->assign('LDDeptMana',$LDDeptMana);
$smarty->assign('LDPropModel',$LDPropModel);
$smarty->assign('LDPropSerieNr',$LDPropSerieNr);
$smarty->assign('LDPropUnit',$LDPropUnit);
$smarty->assign('LDPropPrice',$LDPropPrice);
$smarty->assign('LDPropFunction',$LDPropFunction);
$smarty->assign('LDPropPower',$LDPropPower);
$smarty->assign('LDPropSource',$LDPropSource);
$smarty->assign('LDPropDescription',$LDPropDescription);
$smarty->assign('LDPropMaker',$LDPropMaker);
$smarty->assign('LDPropVendor',$LDPropVendor);
$smarty->assign('LDPropProductionYear',$LDPropProductionYear);
$smarty->assign('LDPropImDate',$LDPropImDate);
$smarty->assign('LDPropImStatus',$LDPropImStatus);
$smarty->assign('LDPropStartUseDate',$LDPropStartUseDate);
$smarty->assign('LDPropWarranty',$LDPropWarranty);
$smarty->assign('LDWorkOrStop',$LDWorkOrStop);
$smarty->assign('LDPropNote',$LDPropNote);
$spropstatus = '';
foreach ($propstatus as $statusitem) {
	if(!isset($_POST['status']))
		$_POST['status'] = 0;
	$spropstatus .= "<input type='radio' name='status' value='".$statusitem[0]."' ".($_POST['status']==$statusitem[0]?'checked=1':'')." />".$statusitem[1]." ";
}
$smarty->assign('propstatus',$spropstatus);
//$smarty->assign('propstatus',"<input type='radio' name='status' value='1' checked='1'/>".$propstatus[1]."<input type='radio' name='status' value='0' />".$propstatus[0]."<input type='radio' name='status' value='2' />".$propstatus[2]."<input type='radio' name='status' value='3' />".$propstatus[3]);
$propsourcetype = $property->getPropSourceTypeList(NULL);
$sourcetypelist = "";
while($row = $propsourcetype->FetchRow()){
	$sourcetypelist .= "<option value='".$row['nr']."' ".($propinfo['source']==$row['nr']?"selected='1'":"").">".$row['type']."</option>";
}
$smarty->assign('prosource',$sourcetypelist);
$smarty->assign('productionyear',$calendar->show_calendar($calendar,$date_format,'productiondate',$propinfo['productiondate']));
$smarty->assign('importdate',$calendar->show_calendar($calendar,$date_format,'importdate',$propinfo['importdate']));
$smarty->assign('propusedate',$calendar->show_calendar($calendar,$date_format,'useddate',$propinfo['useddate']));
$smarty->assign('propwarranty',$calendar->show_calendar($calendar,$date_format,'warranty',$propinfo['warranty']));
$smarty->assign('sCancel','<a style="float:left;" href="javascript:history.back()" class="butcancel"><img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0"></a>');
$smarty->assign('sSaveButton','<input type="hidden" name="sid" value="'.$sid.'" >
<input type="hidden" name="mode" value="search">
<input type="hidden" name="lang" value="'.$lang.'">
<input type="submit" class="butbg" value="'.$LDFindProp.'" style="float:left">');

$sTemp = "<tr>
			<th class='adm_item gray' style='width:5%'>ID</th>
			<th class='adm_item gray' >$LDPropFormalName</th>
			<th class='adm_item gray' >$LDPropModel</th>
			<th class='adm_item gray' >$LDPropSerieNr</th>
			<th class='adm_item gray' >$LDPropMaker</th>
                        <th class='adm_item gray' >$LDPropMannual</th>
                        <th class='adm_item gray' >$LDPropCountry</th>
                        <th class='adm_item gray' >$LDPropStartUseDate</th>
			<th class='adm_item gray' >$LDCurrentDept</th>
			<th class='adm_item gray' >$LDCurrentManager</th>
			
                        <th class='adm_item gray' >$LDCurrentStatus</th>
			<th class='adm_item gray' >$LDWorkOrStop</th>
		 </tr>";
if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
if($dblink_ok){	 
	$rowcount = "SELECT count(nr) ";
	$propitems = array('nr','model','serie','name_formal', 'name_short', 'propfunction', 'status', 'importdate', 'current_dept','country','volta','manual','useddate','factorer','usepercent');
	$selectquery="SELECT ";
	$querytmp = "";
	while (list($key, $val) = each($propitems)) {
		$querytmp .= $val . ", ";
	}
	$selectquery.=substr($querytmp,0,-2);
	$fromquery.= " FROM $tb_property"; 
	
	if(isset($mode) && $mode=='search'){
		$fld_property = array('model','serie','price','power','source','unit','name_formal','name_short','propfunction','factorer','vender','warranty','status','importdate','useddate', 'productiondate', 'importstatus', 'current_dept','dept_mana');
		$_POST['productiondate']=@formatDate2STD($_POST['productiondate'],$date_format);
		$_POST['importdate']=@formatDate2STD($_POST['importdate'],$date_format);
		$_POST['useddate']=@formatDate2STD($_POST['useddate'],$date_format);
		$_POST['warranty']=@formatDate2STD($_POST['warranty'],$date_format);
		$wherequery = " WHERE ";
		if(isset($dept_nr)){
			$wherequery .= "current_dept = $dept_nr AND ";
		}
		$querytmp = "";
		$replace_str = array("  ", "   ", "    ", "     ", "      ", "       ", "        ", "         ", "          ", "           ", "            ", "             ", "              ");
		while (list($key, $val) = each($_POST)) {
			if((trim($val) != "") && in_array($key,$fld_property)) $querytmp .= "(".$key." LIKE '%".trim(str_replace($replace_str, " ", $val))."%') AND ";
		}
		$wherequery .= substr($querytmp,0,-4);
				
		$query = $selectquery.$fromquery.$wherequery." ORDER BY nr DESC";
		$rowcount .= $fromquery.$wherequery;
		$rows=$property->countResultRows($rowcount);
		$rowperpage=MAXBLOCKROW;
		$pagination=ceil($rows/$rowperpage);	
		if(!isset($page)){
			$page = 1 ;
		}
		$start = ($page-1) * $rowperpage;
		$proplistdata=$property->getPropertyItemsObject($query, $start, $rowperpage);
		$toggle=0;
		$order=0;
		if($proplistdata != false){
			while($row=$proplistdata->FetchRow()){
				if($toggle)	$trc='#dedede';
				else $trc='#efefef';
				$toggle=!$toggle;
				$order++;
				$dept_info = &$dept_obj->getDeptAllInfo($row['current_dept']);
				$sTemp .= "<style>td{padding:3px;vertical-align:top;}</style><tr bgcolor='$trc'><td style='text-align:center'>".$row['nr']."</td>";
				$sTemp .= "<td style=''><a href='".$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=".$row['nr']."'>".$row['name_formal']."</a></td>";				
				$sTemp .= "<td style=''>".$row['model']."</td>";
				$sTemp .= "<td style=''>".$row['serie']."</td>";
				 $sTemp .= "<td style='text-align:center'>".$row['factorer']."</td>";
                                $sTemp .= "<td style='text-align:center'><a href='".$root_path.$row['manual']."'>".$LDManuaDownloadlLink."</a></td>";
                                $sTemp .= "<td style='text-align:center'>".$row['country']."</td>";
                                $sTemp .= "<td style='text-align:center'>".$row['useddate']."</td>";
                                $sTemp .= "<td style='text-align:center'>".(($dept_info['LD_var'] && $dept_info['LD_var']!="")?($$dept_info['LD_var']):($dept_info['name_formal']))."</td>";
				if(isset($dept_nr)){
					$query = "SELECT current_status, manager FROM $tb_property_use WHERE (prop_nr = ".$row['nr'].") AND (dept = $dept_nr) AND (im_date = (SELECT MAX(im_date) FROM $tb_property_use WHERE prop_nr = ".$row['nr']."))";
				} else {
					$query = "SELECT current_status, manager FROM $tb_property_use WHERE (prop_nr = ".$row['nr'].") AND (im_date = (SELECT MAX(im_date) FROM $tb_property_use WHERE prop_nr = ".$row['nr']."))";
				}
				$using_info =$property->getPropertyItemsObject($query, NULL, NULL);
				if($using_info != false){
					$using_row = $using_info->FetchRow();
					$personell_info = $personell_obj->getPersonellName($using_row['manager']);
					$sTemp .= "<td style=''>".$personell_info['nr']." - ".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."</td>";
					$sTemp .= "<td style=''>".$using_row['current_status']."</td>";
				}else {
					$sTemp .= "<td style=''></td>";
					$sTemp .= "<td style=''></td>";
				}
				$sTemp .= "<td style=''>".$propstatus[$row['status']][1]."</td></tr>";
			}
		}
		$smarty->assign('allpropertylist',$sTemp);
		$pagingurl = $root_path."modules/property/property-find-advance.php".URL_REDIRECT_APPEND."&mode=changepage";
		require_once('Pagenation.php');
		$smarty->assign('pagelist',$sTemp);
	}
	
} else {echo "$LDDbNoLink<br>";}

$smarty->assign('sMainBlockIncludeFile','property/property_find_advance_form.tpl');
$smarty->display('common/mainframe.tpl');
?>
