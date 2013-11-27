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

define('NO_2LEVEL_CHK',1);
define('LANG_FILE','billing.php');

$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require($root_path.'include/care_api_classes/class_ecombill.php');
$eComBill = new eComBill;
$resultqryLT = $eComBill->listServiceItemsByType($service);					//$service = LT or HS

if(is_object($resultqryLT)) $cntLT=$resultqryLT->RecordCount();

$breakfile='patientbill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;
$returnfile='patientbill.php'.URL_APPEND.'&patientno='.$patientno.'&full_en='.$full_en.'&target='.$target;

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
<SCRIPT language="JavaScript">
<!--
function submitform() {
    //var sel = new Array(document.selectlab.elements.length);
    //var temp;
    //var tempstr;
    //var counter;
    //str = document.selectlab.hidden.value;
    var querystr = "confirmLabtests.php?";

    //counter = 1;
    var flag = true;
    var trs = document.getElementById("selectlab").getElementsByTagName("tr");
    for (var i = 0; i < trs.length; i++) {
        var sname = "";
        var svalue = "";
        var inps = trs[i].getElementsByTagName("input");
        for (var j = 0; j < inps.length; j++) {
            if (inps[j].type == "checkbox" && inps[j].checked) {
                sname = inps[j].getAttribute("id");
            }
            if (inps[j].type == "text" && inps[j].name == "count") {
                svalue = inps[j].value;
            }
        }

        if (sname != "" && svalue != "") {
            querystr += sname + "=" + svalue + "&";
        }
        else if(sname !="" && svalue.trim() == ""){
            alert("Chưa nhập số lần cho mã dịch vụ "+sname);
            return false;
        }
    }
    console.log(querystr);
    document.selectlab.action = querystr;
    document.selectlab.submit();
}
function show() {
    var xmlhttp;
    var str = document.getElementById("item").value;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("selectlab").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "getservices.php?type=" + str + "&service=<?php echo $service ?>", true);
    xmlhttp.send();
}
//-->
</SCRIPT>
<?php 
$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);
if($service=='LT') { $Title  = $LDPleaseSelectLaboratoryTestsforthePatient;}
if($service=='HS') { $Title  = $LDPleaseSelectHospitalServicesforthePatient;}
$smarty->assign('FormTitle', $Title);

$smarty->assign('sFormTag','<form name="selectlab" id="" method="POST" action="" onSubmit="return submitform(this)">');
$smarty->assign('LDTestName',$LDTestName);
$smarty->assign('LDTestCode',$LDTestCode);
$smarty->assign('LDCostperunit',$LDCostperunit);
$smarty->assign('LDNumberofUnits',$LDNumberofUnits);
$smarty->assign('service',$service);
$smarty->assign('pbSubmit','<input type="image"  style="float:left" '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" style="margin-left:20px;float:left"><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');

$cbx='<input type="text" name="count">' ;
//for($j=1; $j<=15; $j++){
//	$cbx .= '<option>'.$j.'</option>';
//}
/**
* show Template
*/
if($cntLT){
	$flag_g=0; $flag_t=0;	//flag check show group name, type name
	for($cnt=0;$cnt<$cntLT;$cnt++) {
		$item=$resultqryLT->FetchRow();
		$itemcode="";
		$smarty->assign('itemName','selectitem' .$cnt);
		$smarty->assign('itemID',$item['item_code']);		//checkbox: id=nounits1,2,3...
		$smarty->assign('itemName',$item['item_description']);
		$smarty->assign('itemCode',$item['item_code']);
		$smarty->assign('itemPrice',number_format($item['item_unit_cost']));
		$smarty->assign('quantity',$cbx);

		$itemcode=$item['item_code'];
		$itemcode1=$itemcode1.$itemcode;
		$itemcode1=$itemcode1."#";
		
		
		//show group, type name
		$flagtpl_g=false; $flagtpl_t=false;
		$group_nr=$item['item_group_nr'];
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
		$smarty->display('ecombill/bill_items_line.tpl');
		$sListRows = $sListRows.ob_get_contents();
		ob_end_clean();		
		ob_start();
		$smarty->display('ecombill/option_item_line.tpl');
		$sOptionRows = $sOptionRows.ob_get_contents();
	ob_end_clean();
	}
}
$itemcode=$itemcode1;
$smarty->assign('ItemLine',$sListRows);
$smarty->assign('OptionLine',$sOptionRows);
//$itemcd=$itemcd1;

$smarty->assign('sHiddenInputs','<input type="hidden" name="hidden" value="'. $itemcode .'">
								<input type="hidden" name="patientno" value="'. $patientno .'">
								<input type="hidden" name="target" value="'. $target .'">
								<input type="hidden" name="service" value="'. $service .'">
								<input type="hidden" name="lang" value="'. $lang .'">
								<input type="hidden" name="sid" value="'. $sid .'">
								<input type="hidden" name="full_en" value="'. $full_en .'">');

$smarty->assign('sMainBlockIncludeFile','ecombill/bill_items.tpl');

$smarty->display('common/mainframe.tpl');
?>