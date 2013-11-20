<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$thisfile= basename(__FILE__);
$breakfile='../nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
require_once($root_path.'include/care_api_classes/class_ward.php');
$Ward = new Ward;

//Get info of current department, ward
if ($ward_nr!=''){
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
		
		$condition_dept_ward="dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' ";
	}
} elseif ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
		
		$condition_dept_ward="dept_nr='".$dept_nr."' ";
	}
}


if (!isset($target))
	$target='list';
	
# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');
 
# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDDestroyMedicine.' :: '.$LDListReportTab);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDDestroyMedicine);

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
	document.listdestroyform.action="";
	document.listdestroyform.submit();
}

function search()
{
	var search = document.getElementById('search').value;
	document.listdestroyform.action="<?php echo $urlsearch;?>&search="+search;
	document.listdestroyform.submit();
}

function viewDetail(issue_id)
{
	var win = '../include/inc_destroy_showdetail.php<?php echo URL_APPEND; ?>' +'&report_id=' + issue_id;
	myWindow=window.open( win , 'View Details' , 'height=500,width=700' );
	myWindow.focus();
}
-->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
# Load and display the tab
$new_report_url='medicine_destroy_med.php';
$list_report_url=$thisfile;
ob_start();
	require('../include/inc_medicine_report_tab.php');
	$smarty->display('nursing/manage_medicine_tab.tpl');
	$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('sTab',$sTemp);

$smarty->assign('sRegForm','<form name="listdestroyform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('deptname',$LDDept.': '.$deptname);
$smarty->assign('ward',$LDWard.': '.$wardname);
$smarty->assign('LDWard',$LDWard);
$smarty->assign('LDDetail',$LDDetail);
$smarty->assign('LDIssueId',$LDIssueId);
$smarty->assign('LDCreatorName',$LDCreatorName);
$smarty->assign('LDDatetime',$LDDatetime);
$smarty->assign('LDType',$LDTypeIssue);
$smarty->assign('LDStatus',$LDStatus);
$smarty->assign('LDEdit',$LDEdit);
$smarty->assign('LDsearchGuide',$LDSearchIssueGuide);

require_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
$Cabinet = new CabinetPharma;

if ($search==''){
	//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
	$number_items_per_page=20;
	
	$condition=" WHERE ".$condition_dept_ward." ORDER BY date_time_create ";
		
	$total_items=$Cabinet->countDestroyItems($condition);
	$total_items=$total_items['sum_item'];
		
	$total_pages=ceil($total_items/$number_items_per_page);
		
	include_once('../include/inc_issuepaper_listdepot_splitpage.php');

	$listItem = $Cabinet->listSomeDestroySplitPage($current_page,$number_items_per_page,$condition);
		
}else{
	if (strrpos($search,'/') || strrpos($search,'-')){
		$search = formatDate2STD($search,'dd/mm/yyyy');
		$condition=" WHERE ".$condition_dept_ward." AND date_time_create LIKE '".$search."%' ORDER BY date_time_create DESC";
	}
	else
		$condition=" WHERE ".$condition_dept_ward." AND destroy_id LIKE '%".$search."%' ORDER BY destroy_id";	
		$listItem = $Cabinet->listAllDestroy($condition);	
		$breakfile = $thisfile.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
	}

	if(is_object($listItem)){
		$sTemp='';
		for ($i=0;$i<$listItem->RecordCount();$i++)
		{
			$rowItem = $listItem->FetchRow();
			$date = formatDate2Local($rowItem['date_time_create'],'dd/mm/yyyy');
			$time=substr($rowItem['date_time_create'],-8);
				
			if ($rowItem['status_finish']){
				$editbutton="javascript:alert('".$LDCannotEditIssuePaper."')";
				$status_finish=$LDDestroyOk; $temp1='check-r.gif';
			}
			else{
				$editbutton='medicine_destroy_med.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&des_id='.$rowItem['destroy_id'].'&target=update';
				$status_finish=$LDDestroyNotYet; $temp1='warn.gif';
			}
			
			if($wardinfo = $Ward->getWardInfo($rowItem['ward_nr'])) 
				$rowItem['ward_nr'] = $wardinfo['name'];
			else $rowItem['ward_nr'] = $LDAllWard;
			
			$sTemp=$sTemp.'<tr bgColor="#ffffff" >
								<td align="center"><a href=""><input type="image" '.createComIcon($root_path,'info3.gif','0','',TRUE).' onclick="viewDetail('.$rowItem['destroy_id'].')" ></a></td>
								<td align="center">'.$rowItem['destroy_id'].'</td>
								<td align="center">'.$date.' &nbsp;'.$time.'</td>
								<td>'.$rowItem['doctor'].'</td>
								<td>'.$rowItem['ward_nr'].'</td>
								<td align="center">'.$status_finish.'&nbsp;<img '.createComIcon($root_path,$temp1,'0','',TRUE).'></td>
								<td align="center"><a href="'.$editbutton.'"><img '.createLDImgSrc($root_path,'edit_sm.gif','0','absmiddle').'></a></td>
							</tr>';
		}
		$smarty->assign('listItem',$sTemp);
			
	}else{
		$sTemp='<tr bgColor="#ffffff"><td colspan="8">'.$LDItemNotFound.'</td></tr>';
		$smarty->assign('listItem',$sTemp);
	}

$smarty->assign('splitPage',$sTempPage);

//*********************************************************************************

//sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="current_page" value="'.$current_page.'">
		<input type="hidden" name="number_items_per_page" value="'.$number_items_per_page.'">
		<input type="hidden" name="ward_nr" value="'.$ward_nr.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

$smarty->assign('pbSearch','<a href="javascript:search()"><input type="image" '.createComIcon($root_path,'Search.png','0','',TRUE).' onclick="" ></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','nursing/medicine_destroymed_list.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

