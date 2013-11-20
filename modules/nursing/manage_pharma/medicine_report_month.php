<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$thisfile= basename(__FILE__).URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$breakfile='../nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;

//Get info of current department, ward
if ($ward_nr!=''){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} elseif ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
	}
}

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDUseMedicineReport.' :: '.$LDMedicineReport);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDUseMedicineReport);

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
function printOut(month,year,dept,ward)
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/baocaosudungthuoc.php<?php echo URL_APPEND; ?>&type=medicine&dept_nr="+dept+"&ward_nr="+ward+"&monthreport="+month+"&yearreport="+year;
	testprintpdf=window.open(urlholder,"BaoCaoSuDungThuoc","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}
-->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
 $smarty->assign('sRegForm','<form name="reportform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('deptname',$LDDept.': '.$deptname);
$smarty->assign('ward',$LDWard.': '.$wardname);
$smarty->assign('titleForm',$LDMedicineReport);
$smarty->assign('LDUnitVnd',$LDUnit.': 1.000 '.$LDvnd);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineID',$LDMedicineID);
$smarty->assign('LDPresName',$LDMedicineName);
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

//Search item from date
$total=0; $flag=0; $flag_1=0;
$i=0;

require_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
$CabinetPharma = new CabinetPharma;
		
$listReport = $CabinetPharma->reportMonth($dept_nr,$ward_nr,$monthreport,$yearreport);

if(is_object($listReport)){
	ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		if (!isset($old_encode) || ($old_encode!=$rowReport['product_encoder'])) {
			if (isset($old_encode)){
					echo 	'<td align="right">'.$listitem['pres_in'].'</td>';
				if ($listitem['pres_in']*$price>0)
					echo 	'<td align="right">'.($listitem['pres_in']*$price).'</td>';
				else echo '<td></td>';
					echo 	'<td align="right">'.$listitem['pres_out'].'</td>';
				if ($listitem['pres_out']*$price>0)
					echo 	'<td align="right">'.($listitem['pres_out']*$price).'</td>';
				else echo '<td></td>';
					echo 	'<td align="right">'.$listitem['use'].'</td>';
				if ($listitem['use']*$price>0)
					echo 	'<td align="right">'.($listitem['use']*$price).'</td>';
				else echo '<td></td>';
					echo 	'<td align="right">'.$listitem['dest'].'</td>';
				if ($listitem['dest']*$price>0)
					echo 	'<td align="right">'.($listitem['dest']*$price).'</td>';
				else echo '<td></td>';	
					echo	'<th align="right">'.$total.'</th>';	//Tong cong		
					echo	'<th align="right">'.($total*$price).'</th>';		//Tien
					echo '</tr>';
				
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
			echo '<tr bgColor="#ffffff" >';
			echo	'<td align="center">'.$i.'</td>'; //STT
			echo	'<td>'.$rowReport['product_encoder'].'</td>';	//Ma thuoc
			echo	'<td>'.$rowReport['product_name'].'</td>';	//Ten thuoc
			echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
			echo	'<td align="right">'.($rowReport['price']/1000).'</td>';	//Don gia
		}	
	
		/*if ($rowReport['pres_id']){
			$listitem['pres_in']=$rowReport['total'];
			$total+=$rowReport['total'];
		}
			
		if ($rowReport['pres_id']){
			$listitem['pres_out']=$rowReport['total'];
		}*/
		
		if ($rowReport['pres_id']){
			if($resulttemp=$CabinetPharma->getTypePres($rowReport['pres_id'])){
				if($resulttemp['group_pres']=='1')
					$listitem['pres_in']+=$rowReport['total'];
				else
					$listitem['pres_out']+=$rowReport['total'];
			}
			$total+=$rowReport['total'];
		}
			
		if ($rowReport['use_id']){
			$listitem['use']=$rowReport['total'];
			$total+=$rowReport['total'];
		}
			
		if ($rowReport['destroy_id']){
			$listitem['dest']=$rowReport['total'];
			$total+=$rowReport['total'];
		}
	
	}
	
	echo 	'<td>'.$listitem['pres_in'].'</td>';
	if ($listitem['pres_in']*$price>0)
	echo 	'<td align="right">'.($listitem['pres_in']*$price).'</td>';  else echo '<td></td>';
	echo 	'<td align="right">'.$listitem['pres_out'].'</td>';
	if ($listitem['pres_out']*$price>0)
	echo 	'<td align="right">'.($listitem['pres_out']*$price).'</td>'; else echo '<td></td>';
	echo 	'<td align="right">'.$listitem['use'].'</td>';
	if ($listitem['use']*$price>0)
	echo 	'<td align="right">'.($listitem['use']*$price).'</td>'; else echo '<td></td>';
	echo 	'<td align="right">'.$listitem['dest'].'</td>';
	if ($listitem['dest']*$price>0)
	echo 	'<td align="right">'.($listitem['dest']*$price).'</td>'; else echo '<td></td>';		
	echo	'<th align="right">'.$total.'</th>';	//Tong cong		
	echo	'<th align="right">'.($total*$price).'</th>';		//Tien
	echo '</tr>';

	
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();	
} else $sTempDiv='<tr bgColor="#ffffff" ><td colspan="15">'.$LDItemNotFound.'</td></tr>';
 
$smarty->assign('divItem',$sTempDiv);
 
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="ward_nr" value="'.$ward_nr.'">
		<input type="hidden" name="dept_nr" value="'.$dept_nr.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

//*********************************************************************************

$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
$smarty->assign('pbPrint','<a href="javascript:window.printOut(\''.$monthreport.'\',\''.$yearreport.'\',\''.$dept_nr.'\',\''.$ward_nr.'\');"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','nursing/medicine_monthreport.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

