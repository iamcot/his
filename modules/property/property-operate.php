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
$breakfile='property-admi-welcome.php'.URL_APPEND;
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();
include_once($root_path.'include/core/inc_date_format_functions.php');

if($mode){
	if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
	if($dblink_ok){
		switch($mode)
		{	
			case 'add':
				$_POST['time']=@formatDate2STD($_POST['time'],$date_format);
				$property->addNewOperation($_POST);
				header('location:'.$root_path."modules/property/property-operating-history.php".URL_REDIRECT_APPEND."&using_nr=$use_nr");
				exit;
			break;
			case 'modify':
				$_POST['time']=@formatDate2STD($_POST['time'],$date_format);
				$property->updateOperation($op_nr, $_POST);
				header('location:'.$root_path."modules/property/property-operating-history.php".URL_REDIRECT_APPEND."&using_nr=$use_nr");
				exit;
			break;
		}// end of switch
	}else{echo "$LDDbNoLink<br>";} 
}

# Added for the common header top block
 $smarty->assign('sToolbarTitle',"$LDPropertyManagement::$LDOperationHistory");
 $smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
 # href for close button
 $smarty->assign('breakfile',$breakfile);
 # Window bar title
 $smarty->assign('sWindowTitle',"$LDPropertyManagement::$LDOperationHistory");
 
 ob_start();
?>
<script language="javascript">
	
	function check(d)
	{
		if((d.operation.value=="-1") || (d.reason.value=="-1") || (d.manager.value=="-1") || (d.operator.value=="") || (d.result.value=="") || (d.before_status.value=="") || (d.after_status.value==""))
		{
			alert("<?php echo $LDAlertIncomplete ?>");
			return false;
		}
		if(d.time.value==""){
			alert("<?php echo $LDPlsEnterDateBirth; ?>");
			d.time.focus();
			return false;
		}
	}
	function popSearchWin(target,obj_val,obj_name){
		urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
		DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
	}
<?php
require($root_path.'include/core/inc_checkdate_lang.php');
?>
</script>
<?php
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
$sTemp = ob_get_contents();
ob_end_clean();

if(isset($smtname) && ($smtname=='modify')){
	$operation_info = $property->getOperationInfo($op_nr);
}

$smarty->append('JavaScript',$sTemp);
$smarty->assign('LDSubmitLink', $root_path."modules/property/property-operate.php".URL_REDIRECT_APPEND);
$smarty->assign('LDOperation',$LDOperation);
$smarty->assign('LDOperator',$LDOperator);
$smarty->assign('LDReason',$LDReason);
$smarty->assign('LDManager',$LDManager);
$smarty->assign('LDTime',$LDTime);
$smarty->assign('LDResult',$LDResult);
$smarty->assign('LDBeforeStatus',$LDBeforeStatus);
$smarty->assign('LDAfterStatus',$LDAfterStatus);

$smarty->assign('operation',$operation_info['operation']);
$smarty->assign('operator',$operation_info['operator']);
$smarty->assign('result',$operation_info['result']);
$smarty->assign('reason',$operation_info['reason']);
$smarty->assign('beforestatus',$operation_info['before_status']);
$smarty->assign('afterstatus',$operation_info['after_status']);
$personell_info = $personell_obj->getPersonellName($operation_info['manager']); 

$smarty->assign('time',$calendar->show_calendar($calendar,$date_format,'time',$operation_info['time']));
$sBuff ="<a href=\"javascript:popSearchWin('referrer_dr')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
$smarty->assign('manager','<input name="manager" type="text" readonly="readonly" style="width:30%;" value="'.$personell_info['nr'].'">&nbsp;&nbsp;<input name="newmanagername" type="text" readonly="readonly" style="width:50%;" value="'.$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first'].'">&nbsp;&nbsp;'.$sBuff);

$smarty->assign('sCancel','<a style="float:left;" href="javascript:history.back()"><img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0"></a>');
$smarty->assign('sSaveButton','<input type="hidden" name="sid" value="'.$sid.'">
<input type="hidden" name="use_nr" value="'.$use_nr.'">
<input type="hidden" name="op_nr" value="'.$op_nr.'">
<input type="hidden" name="mode" value="'.($smtname=="modify"?$smtname:"add").'">
<input type="hidden" name="lang" value="'.$lang.'">
<input type="submit" style="height:25px; float:left; margin-left: 10px;" value="'.($smtname=='modify'?$LDButtonModify:$LDSaveData).'">');

$smarty->assign('sMainBlockIncludeFile','property/property_operate.tpl');
$smarty->display('common/mainframe.tpl');
?>