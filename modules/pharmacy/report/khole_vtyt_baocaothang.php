<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$thisfile= basename(__FILE__).URL_APPEND;
$breakfile='../report_khole.php'.URL_APPEND;


# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoLe.' :: '.$LDReportUseMonth);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDReportUseMonth);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
  ob_start();
?>
<style type="text/css">

</style>
<script  language="javascript">
<!--
function chkform(d) {
	document.reportform.action='<?php echo $thisfile; ?>';
	document.reportform.submit();
}
function printOut(select_type,month,year)
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khole_baocaosudungVTYT.php<?php echo URL_APPEND; ?>&type=medipot&select_type="+select_type+"&monthreport="+month+"&yearreport="+year;
	testprintpdf=window.open(urlholder,"BaoCaoSuDungThuoc","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}
function selectTypeMed() {
	var temp_i = document.getElementById("type_med").selectedIndex;
	document.getElementById("select_type").value = document.getElementById("type_med").options[temp_i].value;
	document.reportform.action='<?php echo $thisfile; ?>';
	document.reportform.submit();	
}
-->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
 $smarty->assign('sRegForm','<form name="reportform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('LDSumAllDept',$LDSumAllDept);
$smarty->assign('LDBy',$LDBy);
$smarty->assign('titleForm',$LDMedipotReport);
$smarty->assign('LDUnitVnd',$LDUnit.': 1.000 '.$LDvnd);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineID',$LDMedipotID);
$smarty->assign('LDPresName',$LDMedipotName);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDCost',$LDCost);
$smarty->assign('Inpatient',$Inpatient);
$smarty->assign('Outpatient',$Outpatient);
$smarty->assign('LDOther',$LDOther);
$smarty->assign('LDDestroy',$LDDestroy);
$smarty->assign('LDTotalNumber',$LDTotalNumber);
$smarty->assign('LDNumberOf',$LDNumberOf);
$smarty->assign('LDMoney',$LDMoney);

if (!isset($monthreport)){
	$monthreport=date("m");
}
if (!isset($yearreport)){
	$yearreport=date("Y");
}
$smarty->assign('monthreport',$LDMonth.': <input type="text" id="monthreport" name="monthreport" size="1" value="'.$monthreport.'">/ <input type="text" id="yearreport" name="yearreport" size="3" value="'.$yearreport.'">');

//Report in
if(!isset($select_type) || $select_type=='')
	$select_type=0;

$s0=''; $s1=''; $s2='';
switch($select_type){
	case 0: $s0='selected'; break;
	case 1: $s1='selected'; break;
	case 2: $s2='selected'; break;
	case 3: $s3='selected'; break;	
	default: $s0='selected';
}
$temp='<select id="type_med" name="type_med" onchange="selectTypeMed()">
			<option value="0" '.$s0.' >'.$LDMedipot.'</option>
			<option value="1" '.$s1.' >'.$LDMedipot_KP.'</option>
			<option value="2" '.$s2.' >'.$LDMedipot_BH.'</option>
			<option value="3" '.$s3.' >'.$LDMedipot_CBTC.'</option>			
		</select>';
$smarty->assign('inputby',$temp);


//Search item from date
$total=0; $flag=0; $flag_1=0;
$i=0;

require_once($root_path.'include/care_api_classes/class_cabinet_medipot.php');
$CabinetMedipot = new CabinetMedipot;
	
switch($select_type){	
	case 0: $listReport = $CabinetMedipot->reportMedipotMonth($monthreport,$yearreport); break;
	case 1: $listReport = $CabinetMedipot->reportMedipotKPMonth($monthreport,$yearreport); break;
	case 2: $listReport = $CabinetMedipot->reportMedipotBHMonth($monthreport,$yearreport); break;
	case 3: $listReport = $CabinetMedipot->reportMedipotCBTCMonth($monthreport,$yearreport); break;	
	default: $listReport = $CabinetMedipot->reportMedipotMonth($monthreport,$yearreport);
}
if(is_object($listReport)){
	$n=$listReport->RecordCount();
	ob_start();	
	for($k=0; $k<=$n; $k++){
		if($k<$n)
			$rowReport = $listReport->FetchRow();
		if (!isset($old_encode) || ($old_encode!=$rowReport['product_encoder']) || ($k==$n)) {
			if (isset($old_encode) || ($k==$n)){
					echo 	'<td>'.$listitem['pres_in'].'</td>';
				
				if ($listitem['pres_in']*$price>0)
					echo 	'<td>'.($listitem['pres_in']*$price).'</td>';
				else echo '<td></td>';
				
					echo 	'<td>'.$listitem['pres_out'].'</td>';
				
				if ($listitem['pres_out']*$price>0)
					echo 	'<td>'.($listitem['pres_out']*$price).'</td>';
				else echo '<td></td>';
				
					echo 	'<td>'.$listitem['use'].'</td>';
				
				if ($listitem['use']*$price>0)
					echo 	'<td>'.($listitem['use']*$price).'</td>';
				else echo '<td></td>';
				
					echo 	'<td>'.$listitem['dest'].'</td>';
				
				if ($listitem['dest']*$price>0)
					echo 	'<td>'.($listitem['dest']*$price).'</td>';
				else echo '<td></td>';	
				
					echo	'<th>'.$total.'</th>';	//Tong cong		
					echo	'<th>'.($total*$price).'</th>';		//Tien
					echo '</tr>';
					
				if($k==$n)
					break;
					
				$listitem['pres_in']=''; $listitem['pres_out']=''; $listitem['use']=''; $listitem['dest']='';
				$total=0;
			}
			$old_encode=$rowReport['product_encoder'];
			$price=$rowReport['price']/1000;
			$i++; $flag=1;
		}else {
			$flag=0; //$flag_1=0;
		}
		
		if ($flag){
			echo '<tr bgColor="#ffffff" align="right">';
			echo	'<td align="center">'.$i.'</td>'; //STT
			echo	'<td align="center">'.$rowReport['product_encoder'].'</td>';	//Ma thuoc
			echo	'<td align="left">'.$rowReport['product_name'].'</td>';	//Ten thuoc
			echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
			echo	'<td>'.($rowReport['price']/1000).'</td>';	//Don gia
		}	
		
		if ($rowReport['pres_id']){
		
			if($resulttemp=$CabinetMedipot->getTypePres($rowReport['pres_id'])){
				if($resulttemp['group_pres']=='1')
					$listitem['pres_in']+=$rowReport['total'];
				else
					$listitem['pres_out']+=$rowReport['total'];
			}
			$total+=$rowReport['total'];
		}
		else if ($rowReport['use_id']){
			$listitem['use']=$rowReport['total'];
			$total+=$rowReport['total'];
		}			
		else if ($rowReport['destroy_id']){
			$listitem['dest']=$rowReport['total'];
			$total+=$rowReport['total'];
		}
	
	}
	
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();	
} else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="15">'.$LDItemNotFound.'</td></tr>';
 
$smarty->assign('divItem',$sTempDiv);
 
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" id="select_type" name="select_type" value="'.$select_type.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

//*********************************************************************************

$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
$smarty->assign('pbPrint','<a href="javascript:window.printOut(\''.$select_type.'\',\''.$monthreport.'\',\''.$yearreport.'\');"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/khole_baocaothang.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

