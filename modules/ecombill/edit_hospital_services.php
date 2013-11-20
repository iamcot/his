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
//define('NO_CHAIN',1);
define('LANG_FILE','billing.php');
//$db->debug=true;
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
$eComBill = new eComBill;
$resultqryLT = $eComBill->listServiceItemsByType($service);
if(is_object($resultqryLT)) $cntLT=$resultqryLT->RecordCount();

$breakfile='billingmenu.php'.URL_APPEND;
$returnfile='billingmenu.php'.URL_APPEND;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Toolbar title

 $smarty->assign('sToolbarTitle',$LDBilling);

 # href for the return button
 $smarty->assign('pbBack',$returnfile);

# href for the  button
 $smarty->assign('pbHelp',"javascript:gethelp('billing.php','create-service')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDBilling);

 # Collect extra javascrit code

 ob_start();
?>

<SCRIPT language="JavaScript">
<!--
function check() {
	document.editservice.action ="posteditservice.php";
	document.editservice.submit();
}


function show(){
var xmlhttp;
var str=document.getElementById("item").value;
if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("editservice").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","gettype.php?type="+str+"&service=<?php echo $service ?>",true);
	xmlhttp.send();
}
//-->
</SCRIPT>
<?php 
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);
if($service=='LT') $Title = $LDEditLaboratoryTestItems;
if($service=='HS') $Title = $LDEditHospitalServiceItems;
$smarty->assign('FormTitle',$LDBilling . ' - ' . $Title);

$smarty->assign('sFormTag','<form name="editservice" id="editservice" method="POST" action="" onSubmit="return check(this)">');
$smarty->assign('LDName',$LDNameHS);
$smarty->assign('LDCode',$LDCode);
$smarty->assign('LDCostPerUnit',$LDCostPerUnit);
$smarty->assign('LDDiscount',$LDDiscount);

$smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');


/**
* show Template
*/
$sListRows='';
$flag_g=0; $flag_t=0;	//flag check show group name, type name
for($cnt=0;$cnt<$cntLT;$cnt++) {
	$itemdetails="";
	$result=$resultqryLT->FetchRow();
	
	$smarty->assign('TP_code',$result['item_code']);
	$smarty->assign('TP_description',$result['item_description']);
	$smarty->assign('itemnmcnt','itemnm' . '#' . $cnt);
	$smarty->assign('TP_unit_cost',$result['item_unit_cost']);
	$smarty->assign('itemcscnt','itemcs' . '#' . $cnt);
	$smarty->assign('TP_discount_max',$result['item_discount_max_allowed']);
	$smarty->assign('itemdccnt','itemdc' . '#' . $cnt);

	$itemcd=$result['item_code'];
	$itemcd1=$itemcd1.$itemcd;
	$itemcd1=$itemcd1."#";	

	//show group, type name
	$flagtpl_g=false; $flagtpl_t=false;
	$group_nr=$result['item_group_nr'];
		if ($flag_g!=$group_nr)
		{
			$grouptype = $eComBill->getTypeGroupOfItem($group_nr);
			if($grouptype)
			{
				$type_nr = $grouptype['item_group'];
				if ($flag_t!=$type_nr)
				{
					$flagtpl_t = true;
					$smarty->assign('typeName',$grouptype['type_name']);
					$smarty->assign('typeNr',$type_nr);
					$flag_t=$type_nr;
				}				
				$flagtpl_g=true;
				$smarty->assign('groupName',$grouptype['group_name']);
				$smarty->assign('groupNr',$group_nr);
			}	
			$flag_g=$group_nr;
		}
	$smarty->assign('typeflag',$flagtpl_t);
	$smarty->assign('groupflag',$flagtpl_g);	
		
	ob_start();
		$smarty->display('ecombill/edit_item_line.tpl');
		$sListRows = $sListRows.ob_get_contents();
	ob_end_clean();
	ob_start();
		$smarty->display('ecombill/option_item_line.tpl');
		$sOptionRows = $sOptionRows.ob_get_contents();
	ob_end_clean();
}

$smarty->assign('ItemLine',$sListRows);
$smarty->assign('OptionLine',$sOptionRows);
$itemcd=$itemcd1;

$smarty->assign('sHiddenInputs','<input type="hidden" name="itemcd" value="'. $itemcd .'">
					<input type="hidden" name="lang" value="'. $lang .'">
					<input type="hidden" name="sid" value="'. $sid .'">
					<input type="hidden" name="full_en" value="'. $full_en .'">');

$smarty->assign('sMainBlockIncludeFile','ecombill/edit_item.tpl');

$smarty->display('common/mainframe.tpl');
?>