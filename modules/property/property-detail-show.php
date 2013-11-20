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

define('LANG_FILE','properties.php');
$breakfile='property-admi-welcome.php'.URL_APPEND;
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_personell.php');
$personell_obj = new Personell();
require_once($root_path.'include/care_api_classes/class_property.php');
$property=new Property;
require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('property');
include_once($root_path.'include/core/inc_date_format_functions.php');
# Added for the common header top block
$smarty->assign('sToolbarTitle',"$LDPropertyManagement::$LDViewProperties");
$smarty->assign('pbHelp',"javascript:gethelp('property_mng.php','new')");
# href for close button
$smarty->assign('breakfile',$breakfile); 
# Window bar title
$smarty->assign('sWindowTitle',"$LDPropertyManagement::$LDViewProperties");
# Buffer page output

//$propitems = array('nr','model','serie','unit','price','power','source','name_formal', 'name_short', 'propfunction', 'status', 'importdate', 'importstatus', 'productiondate', 'useddate', 'warranty', 'factorer', 'vender', 'description', 'note', 'manual', 'image', 'current_dept','usepercent','dept_mana');
$propitems = array('nr','model','name_formal','name_short','LD_var','description','propfunction','factorer','vender','warranty','manual','note','image','status','history','modify_id','modify_time','create_id','create_time','importdate','useddate', 'productiondate', 'importstatus', 'current_dept','price','source','usepercent','dept_mana','serie','unit','power','country','volta','proptype');
$propinfo = $property->getInfomationOfProp($propitems, $prop_nr);

if(isset($subfunction)){
	if(($subfunction=="return")&&($propinfo['status']==2)){
		$propreturn = $property->getPropReturnInfo($prop_nr);
		$personell_info = $personell_obj->getPersonellName($propreturn['manager']); 
	}
	else if(($subfunction=="newreturn")&&($propinfo['status']==1)) { //status la dang dung moi dc return
		if((isset($_POST['prop_nr']))&&($_POST['prop_nr']!="")&&(isset($_POST['manager']))&&($_POST['manager']!="")&&(isset($_POST['reason']))&&($_POST['reason']!="")&&(isset($_POST['return_date']))&&($_POST['return_date']!="")){
			$_POST['return_date']=@formatDate2STD($_POST['return_date'],$date_format);
			$property->insertReturnInfo($_POST);
			$rsrepair = $property->updatePropReturnStatus($prop_nr);				
			
		}
		$rsrepair = "Chưa nhập đủ thông tin";
	}
	else if(($subfunction == "repair") && ($propinfo['status']!=2) && ($propinfo['status']!=3) ){
		$propreturn = $property->getPropReturnInfo($prop_nr);
		$personell_info = $personell_obj->getPersonellName($propreturn['manager']); 
	}
	else if(($subfunction=="saverepair")&&($propinfo['status']!=2)&&($propinfo['status']!=3)){
		//var_dump($_POST);
		if((isset($_POST['prop_nr']))&&($_POST['prop_nr']!="")&&(isset($_POST['request_person']))&&($_POST['request_person']!="")&&(isset($_POST['damaged_date']))&&($_POST['damaged_date']!="")&&(isset($_POST['repair_date']))&&($_POST['repair_date']!="")&&(isset($_POST['repair_detail']))&&($_POST['repair_detail']!="")&&(isset($_POST['repair_person']))&&($_POST['repair_person']!="")){
			//echo '@@@@';
			$_POST['damaged_date']=@formatDate2STD($_POST['damaged_date'],$date_format);
			$_POST['repair_date']=@formatDate2STD($_POST['repair_date'],$date_format);
			$rsrepair = ($property->insertRepairInfo($_POST));			
		}
	}
	else if($subfunction=="editreturn"&&($propinfo['status']==2)){
		if((isset($_POST['prop_nr']))&&($_POST['prop_nr']!="")&&(isset($_POST['manager']))&&($_POST['manager']!="")&&(isset($_POST['reason']))&&($_POST['reason']!="")&&(isset($_POST['return_date']))&&($_POST['return_date']!="")){
			$propreturn = $property->getPropReturnInfo($prop_nr);
			$_POST['return_date']=@formatDate2STD($_POST['return_date'],$date_format);
			$property->updateReturnInfo($propreturn['nr'],$_POST);
		}
	}
	else if($subfunction=="liquidation"&&($propinfo['status']==0)){//chi co thiet bi dang nhan roi moi thanh ly duoc
		$propliquidation = $property->getPropLiquiInfo($prop_nr); 
		$personell_info = $personell_obj->getPersonellName($propliquidation['manager']); 
	}
	else if($subfunction=="newliquidation"&&($propinfo['status']==0)){
		if((isset($_POST['prop_nr']))&&($_POST['prop_nr']!="")&&(isset($_POST['manager']))&&($_POST['manager']!="")&&(isset($_POST['decision_nr']))&&($_POST['decision_nr']!="")&&(isset($_POST['reason']))&&($_POST['reason']!="")&&(isset($_POST['liquidation_date']))&&($_POST['liquidation_date']!="")){
			$_POST['liquidation_date']=@formatDate2STD($_POST['liquidation_date'],$date_format);
			$property->insertLiquiInfo($_POST);
			$property->updatePropLiquiStatus($prop_nr);
		}
	}
	else if($subfunction=="editliquidation"&&($propinfo['status']==2)){//edit thiet bi dang thanh ly
		if((isset($_POST['prop_nr']))&&($_POST['prop_nr']!="")&&(isset($_POST['manager']))&&($_POST['manager']!="")&&(isset($_POST['decision_nr']))&&($_POST['decision_nr']!="")&&(isset($_POST['reason']))&&($_POST['reason']!="")&&(isset($_POST['liquidation_date']))&&($_POST['liquidation_date']!="")){
			$propliquidation = $property->getPropLiquiInfo($prop_nr);
			$_POST['liquidation_date']=@formatDate2STD($_POST['liquidation_date'],$date_format);
			$property->updateLiquiInfo($propliquidation['nr'],$_POST);
		}
	}
}

ob_start();
?>
<style type="text/css" name="formstyle">

</style>

<script language="javascript">
	function popSearchWin(target,obj_val,obj_name){
		urlholder="./data_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
		DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
	}
	function checkReturnForm(f){
		if((f.manager.value=="") || (f.reason.value==""))
		{
			alert("<?php echo $LDAlertIncomplete ?>");
			return false;
		}
		if(f.return_date.value==""){
			alert("<?php echo $LDPlsEnterDate; ?>");
			f.return_date.focus();
			return false;
		}
	}
	function checkLiquiForm(f){
		if((f.manager.value=="") || (f.reason.value=="") || (f.decision_nr.value==""))
		{
			alert("<?php echo $LDAlertIncomplete ?>");
			return false;
		}
		if(f.liquidation_date.value==""){
			alert("<?php echo $LDPlsEnterDate; ?>");
			f.liquidation_date.focus();
			return false;
		}
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
$smarty->append('JavaScript',$sTemp);
# Assign form items
# notification
if(isset($rsrepair)){
	if($rsrepair) $notif = $LDRepairsaveok;
	else $notif = $LDRepairsavefail;
	$smarty->assign('notif',$notif);
}

#
$smarty->assign('LDPropFormalName',$LDPropFormalName);
$smarty->assign('LDPropType',$LDPropType);
$smarty->assign('LDPropvolta',$LDPropvolta);
$smarty->assign('LDPropModel',$LDPropModel);
$smarty->assign('LDPropSerieNr',$LDPropSerieNr);
$smarty->assign('LDPropUnit',$LDPropUnit);
$smarty->assign('LDPropPrice',$LDPropPrice);
$smarty->assign('LDPropPower',$LDPropPower);
$smarty->assign('LDPropSource',$LDPropSource);
$smarty->assign('LDPropFunction',$LDPropFunction);
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
$smarty->assign('LDPropImage',$LDPropImage);
$smarty->assign('LDPropMannual',$LDPropMannual);
$smarty->assign('LDDeptMana',$LDDeptMana);

$smarty->assign('imgLeftFunctionList',"<img src='".$root_path."gui/img/common/default/angle_left_s.gif'>");
$smarty->assign('LDSearchLink',$LDSearchLink);
$smarty->assign('LDIconSearch',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
if(isset($dept_nr)) {
	$smarty->assign('LDSearchURL',$root_path."modules/property/property-find-advance.php".URL_REDIRECT_APPEND."&dept_nr=$dept_nr");
} else {
	$smarty->assign('LDSearchURL',$root_path."modules/property/property-find-advance.php".URL_REDIRECT_APPEND);
}
$smarty->assign('LDModifyData',$LDModifyData);
$smarty->assign('LDPropCountry',$LDPropCountry);
$smarty->assign('LDIconModify',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
$smarty->assign('LDModifyURL',$root_path."modules/property/property-create-new.php".URL_REDIRECT_APPEND."&mode=modify&prop_nr=$prop_nr");
$smarty->assign('LDUseHistory',$LDUseHistory);
$smarty->assign('LDIconHistory',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
$smarty->assign('LDHistoryURL',$root_path."modules/property/property-using-history.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr");
$smarty->assign('LDRepairHistory',$LDRepairHistory);
$smarty->assign('LDRepairHistoryURL',$root_path."modules/property/property-repair-history.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr");
$smarty->assign('LDTransmiting',$LDTransmiting);
$smarty->assign('LDIconTransmite',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
$smarty->assign('LDTransmitingURL',$root_path."modules/property/property-transmit.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr");
$smarty->assign('LDReturn',$LDReturn);
$smarty->assign('LDIconReturn',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
$smarty->assign('LDReturnURL',$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr&subfunction=return");
$smarty->assign('LDLiquidation',$LDLiquidation);
$smarty->assign('LDIconLiquidation',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
$smarty->assign('LDLiquidationURL',$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr&subfunction=liquidation");
/*
Add repair function
*/
$smarty->assign('LDRepair',$LDRepair);
$smarty->assign('LDIconRepair',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
$smarty->assign('LDRepairURL',$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr&subfunction=repair");
//end
$smarty->assign('LDCreateNew',$LDCreateNew);
$smarty->assign('LDIconCreateNew',"<img ".createComIcon($root_path,'redpfeil.gif','0','absmiddle').">");
$smarty->assign('LDCreateURL',$root_path."modules/property/property-create-new.php".URL_REDIRECT_APPEND."&mode=new&prop_nr=$prop_nr");

$propsourcetype = $property->getPropSourceTypeList($propinfo['source'])->Fetchrow();
$smarty->assign('propformalname',$propinfo['name_formal']);
$smarty->assign('propvolta',$propinfo['volta']);
$smarty->assign('proptype',$propinfo['proptype']);
$smarty->assign('propcountry',$propinfo['country']);
$smarty->assign('propmodel',$propinfo['model']);
$smarty->assign('importdate',$propinfo['importdate']);
$smarty->assign('importstatus',$propinfo['importstatus']);
$smarty->assign('propserie',$propinfo['serie']);
$smarty->assign('propunit',$propinfo['unit']);
$smarty->assign('propprice',  number_format($propinfo['price'], 0, '.',','));
$smarty->assign('proppower',$propinfo['power']);
$smarty->assign('propsource',$propsourcetype['type']);
$smarty->assign('productionyear',$propinfo['productiondate']);
$smarty->assign('propusedate',$propinfo['useddate']);
$smarty->assign('propwarranty',$propinfo['warranty']);
$smarty->assign('propmaker',$propinfo['factorer']);
$smarty->assign('propvendor',$propinfo['vender']);
$smarty->assign('profunction',$propinfo['propfunction']);
$smarty->assign('prodescription',$propinfo['description']);
$smarty->assign('propstatus',$propstatus[$propinfo['status']][1]);
$smarty->assign('propnote',$propinfo['note']);
$smarty->assign('usepercent',$propinfo['usepercent']);
$smarty->assign('dept_mana_name',$propinfo['dept_mana_name']);
$smarty->assign('propsatustoshowsubmenu',$propinfo['status']);
if($propinfo['manual']){
	$smarty->assign('propmannual',"<a href='".$root_path.$propinfo['manual']."'>".$LDManuaDownloadlLink."</a>");
} else {
	$smarty->assign('propmannual',$LDNoManualFile);
}
if($propinfo['image']) {
	$smarty->assign('propimage',"<img style='max-width:300px;' src='".$root_path.$propinfo['image']."'>");
} else {
	$smarty->assign('propimage',"<img  src='".$root_path."gui/img/common/default/x-blank.gif'>");
}
$sBuff ="<a href=\"javascript:popSearchWin('referrer_dr')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
$link_back=$root_path.'modules/property/property-detail-show.php'.URL_REDIRECT_APPEND.'&prop_nr='.$prop_nr;
if(isset($subfunction)){
	if($subfunction=="return"){
		$subfunctionform="<form name='proptransmitting' onSubmit='return checkReturnForm(this)' action='".$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr&subfunction=".($propinfo['status']!=2?"newreturn":"editreturn")."' method='post'><table style='width:25%;float:left;margin:20px 5px 10px 5px;' border='0' cellpadding='2' cellspacing='1' bgcolor='#dedede'>";
		$subfunctionform .= "<tr><th style='color: rgb(0,0,160);font-size:16px;'>$LDReturn</th>";
                $subfunctionform .= '<th align="right"><a href="'.$link_back.'"><img '.createComIcon($root_path,'pharmacy_cancel.png','0','',TRUE).'></a></th></tr>';
		$subfunctionform .= "<tr><td ><input type='hidden' name='prop_nr' value='".$propinfo['nr']."'></td></tr>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDProposer <span style='color:red;'>(*)</span>:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'><input name='manager' type='text' readonly='readonly' style='width:25%;' value='".$propreturn['manager']."'>&nbsp;<input name='newmanagername' type='text' readonly='readonly' style='width:55%;' value='".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."'>&nbsp;&nbsp;".$sBuff."</td></tr>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDReason <span style='color:red;'>(*)</span>:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'><textarea name='reason' style='width:95%;' rows=3 wrap='physical'>".$propreturn['reason']."</textarea></td></tr>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDReturnDate <span style='color:red;'>(*)</span>:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'>".$calendar->show_calendar($calendar,$date_format,'return_date',$propreturn['return_date'])."</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'>$LDinstruction<span style='color:red;'>(*)</span></td></tr>";
		$subfunctionform .= "<tr><td style='text-align:right;padding-right:10px;'><input style='width:100px;height:30px;' type='submit' value='$LDReturn'/></td></tr>";
	}
	else if($subfunction=="liquidation"){
		$subfunctionform="<form name='proptransmitting' onSubmit='return checkLiquiForm(this)' action='".$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr&subfunction=".($propinfo['status']!=3?"newliquidation":"editliquidation")."' method='post'><table style='width:25%;float:left;margin:20px 5px 10px 5px;' border='0' cellpadding='2' cellspacing='1' bgcolor='#dedede'>";
		$subfunctionform .= "<tr><th style='color: rgb(0,0,160);font-size:16px;'>$LDLiquidation</th>";
                $subfunctionform .= '<th align="right"><a href="'.$link_back.'"><img '.createComIcon($root_path,'pharmacy_cancel.png','0','',TRUE).'></a></th></tr>';
		$subfunctionform .= "<tr><td ><input type='hidden' name='prop_nr' value='".$propinfo['nr']."'></td></tr>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDProposer <span style='color:red;'>(*)</span>:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'><input name='manager' type='text' readonly='readonly' style='width:25%;' value='".$propliquidation['manager']."'>&nbsp;<input name='newmanagername' type='text' readonly='readonly' style='width:55%;' value='".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."'>&nbsp;&nbsp;".$sBuff."</td></tr>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDDecisionNr <span style='color:red;'>(*)</span>:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'><input name='decision_nr' type='text' style='width:95%;' value='".$propliquidation['decision_nr']."'>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDReason <span style='color:red;'>(*)</span>:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'><textarea name='reason' style='width:95%;' rows=3 wrap='physical'>".$propliquidation['reason']."</textarea></td></tr>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDPropLiquiBuyer:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'><input name='buyer' type='text' style='width:95%;' value='".$propliquidation['buyer']."'>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDPropLiquiPrice:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'><input name='price' type='text' style='width:95%;' value='".$propliquidation['price']."'>";
		$subfunctionform .= "<tr><td style='color: rgb(0,0,128);font-size:14px;'>$LDLiquiDate <span style='color:red;'>(*)</span>:</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'>".$calendar->show_calendar($calendar,$date_format,'liquidation_date',$propliquidation['liquidation_date'])."</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:5px;'>$LDinstruction<span style='color:red;'>(*)</span></td></tr>";
		$subfunctionform .= "<tr><td style='text-align:right;padding-right:10px;'><input style='width:100px;height:30px;' type='submit' value='$LDLiquidation'/></td></tr>";
	}
	else if($subfunction=="repair"){

		$subfunctionform="<form class='formsub' name='proprepair' onSubmit='return checkRepairForm(this)' action='".$root_path."modules/property/property-detail-show.php".URL_REDIRECT_APPEND."&prop_nr=$prop_nr&subfunction=".($propinfo['status']!=3?"saverepair":"edit_repair")."' method='post'>
		<table style='width:25%;float:left;margin:10px 10px 10px 10px;border:1px solid #aaa' cellpadding='2' cellspacing='1' bgcolor='#dedede'>";
		$subfunctionform .= "<tr><th style='color: red;font-size:16px;'>$LDRepair</th>";
                $subfunctionform .= '<th align="right"><a href="'.$link_back.'"><img '.createComIcon($root_path,'pharmacy_cancel.png','0','',TRUE).'></a></th></tr>';
		$subfunctionform .= "<tr><td ><input type='hidden' name='prop_nr' value='".$propinfo['nr']."'></td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:10px;font-weight:bold'>$LDProposer</td></tr>";
		$sBuff ="<a href=\"javascript:popSearchWin('requestrepair')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
		$subfunctionform .= "<tr><td style='text-align:left;padding:5px;'><input name='request_person' type='text' readonly='readonly' style='width:25%;' value='".$proprepair['request_person']."'>&nbsp;<input name='request_person_name' type='text' readonly='readonly' style='width:55%;' value='".$personell_info['name_last']." ".$personell_info['name_middle']." ".$personell_info['name_first']."'>&nbsp;&nbsp;".$sBuff."</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:10px;font-weight:bold'>$LDReportdate</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding:5px;'>".$calendar->show_calendar($calendar,$date_format,'damaged_date',$proprepair['damaged_date'])."</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:10px;font-weight:bold'>$LDReportdetail</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding:5px;'><textarea  class='inputmaxwidth' name='damaged_detail'></textarea></td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:10px;font-weight:bold'>$LDRepairdate</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding:5px;'>".$calendar->show_calendar($calendar,$date_format,'repair_date',$proprepair['repair_date'])."</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:10px;font-weight:bold'>$LDRepairdetail</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding:5px;'><textarea  class='inputmaxwidth' name='repair_detail'></textarea></td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:10px;font-weight:bold'>$LDRepairperson</td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding:5px;'><input class='inputmaxwidth' type='text' name='repair_person'></td></tr>";
		$subfunctionform .= "<tr><td style='text-align:left;padding-left:10px;'><input style='width:80px;height:24px;' type='submit' value='$LDRepair'/></td></tr>";
	}
	$subfunctionform .= "</table></form>";
	$smarty->assign('sSubfunctionArea',$subfunctionform);
}
$smarty->assign('sCancel','<a href="javascript:history.back()"><img '.createLDImgSrc($root_path,'cancel.gif','0').' border="0"></a>');

$smarty->assign('sMainBlockIncludeFile','property/property_view_form.tpl');
$smarty->display('common/mainframe.tpl');
?>
