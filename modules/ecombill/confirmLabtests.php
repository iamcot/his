<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
/**
 * eComBill 1.0.04 for Care2002 beta 1.0.04
 * (2003-04-30)
 * adapted from eComBill beta 0.2
 * developed by ecomscience.com http://www.ecomscience.com
 * GPL License
 */
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
$eComBill = new eComBill;

define('NO_CHAIN',1);
define('LANG_FILE','billing.php');

$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');

while (list($cle, $val) = each($_POST)) {
	if (substr($cle,0,7) == "nounits") {
		$no =$no."#".$val;
	}
}
$no=explode("#",$no);

$breakfile='select_services.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&service='.$service.'&target='.$target;
$returnfile='select_services.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&service='.$service.'&target='.$target;


# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Toolbar title

 $smarty->assign('sToolbarTitle',$LDBilling . ' - ' . $LDPatientNumber . ' : ' . $full_en);

 # href for the return button
 $smarty->assign('pbBack',$returnfile);

# href for the  button
 $smarty->assign('pbHelp',"javascript:gethelp('billing.php','select-service')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDBilling);

 # Collect extra javascrit code

 ob_start();
?>
<script language="javascript">
<!--
	function confirmlabtest() {
		document.confirmlab.action="postconfirmlab.php<?php echo URL_APPEND; ?>";
		document.confirmlab.submit();
	}
//-->
</script>
<?php 
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);
if($service=='LT') { $smarty->assign('FormTitle', $LDConfirmLaboratoryTests); }
if($service=='HS') { $smarty->assign('FormTitle', $LDConfirmHospitalServices);}

$smarty->assign('sFormTag','<form name="confirmlab" id="selectlab" method="POST" action="" onSubmit="return confirmlabtest(this)">');
$smarty->assign('LDTestName',$LDTestName);
$smarty->assign('LDTestCode',$LDTestCode);
$smarty->assign('LDCostperunit',$LDCostperunit);
$smarty->assign('LDNumberofUnits',$LDNumberofUnits);

$smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'cancel.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');


/**
* show Template
*/

$qrystr=getenv(QUERY_STRING);
$data="&".$qrystr;

//Ham tach cac gia tri trong chuoi--------------
$paramNumber='Number';
$paramValue='Value';
$paramName="itemcode"; 
        
$strCut = $data."&";

$from=1;
$to=0;
$flag = true;
//itemcode152=&YHCT58&itemcode154=YHCT60&
$aService = explode("&",$strCut);
foreach($aService as $sRow){
    if(trim($sRow)=="") continue;
    $aParam = explode("=",$sRow);
//while($flag){
//    $tempStr=substr($strCut,$from);
//	$to=strpos($tempStr, "&");
//	$equalIndex=strpos($tempStr, "=");
//
//    if($to == 0)
//        $flag = false;
//
//    $paramValue=substr($tempStr,$equalIndex+1,$to-$equalIndex-1);
//    $paramNumber=substr($tempStr,strlen($paramName),$equalIndex - strlen($paramName));
//
//    $from = $from+$to+1;
		
	if($aParam[0]){
		$resultlbqry = $eComBill->listServiceItemsByCode($aParam[0]);

		if(is_object($resultlbqry)) $item=$resultlbqry->FetchRow();

		$smarty->assign('itemName',$item['item_description']);
		$smarty->assign('itemCode',$item['item_code']);
		$smarty->assign('itemPrice',number_format($item['item_unit_cost']));
		$smarty->assign('quantity',$aParam[1]);

		$noOfunits=$noOfunits.$aParam[1]."#";
		$labcode=$labcode."#".$item['item_code'];

		ob_start();
		$smarty->display('ecombill/bill_items_line.tpl');
		$sListRows = $sListRows.ob_get_contents();
		ob_end_clean();	
	}
}
	
//------------------------------


$itemcode=$itemcode1;
$smarty->assign('ItemLine',$sListRows);
$itemcd=$itemcd1;

$smarty->assign('sHiddenInputs','<input type="hidden" name="labcod" value="'. $labcode .'">
							    <input type="hidden" name="noOfunits" value="'. $noOfunits .'">
							    <input type="hidden" name="patientno" value="'. $patientno .'">
							    <input type="hidden" name="lang" value="'. $lang .'">
							    <input type="hidden" name="sid" value="'. $sid .'">
								<input type="hidden" name="target" value="'. $target .'">
							    <input type="hidden" name="full_en" value="'. $full_en .'">');

$smarty->assign('sMainBlockIncludeFile','ecombill/bill_items.tpl');

$smarty->display('common/mainframe.tpl');
?>