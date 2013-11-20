<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

# Init
$thisfile= basename(__FILE__);
$breakfile='nursing-issuepaper-depot.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$fileforward='nursing-issuepaper-depot.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$urlsearch=$thisfile.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;

require_once($root_path.'include/care_api_classes/class_ward.php');
$Ward = new Ward;
	
include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_issuepaper.php');
$IssuePaper = new IssuePaper;

//Get info of current department, ward
if ($ward_nr!=''){	
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
		
		$condition_dept_ward=" dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' ";
	}
} elseif ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
		
		$condition_dept_ward=" dept_nr='".$dept_nr."' ";
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
 $smarty->assign('sToolbarTitle',$LDIssuePaper.' :: '.$LDListDepotTab);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDIssuePaper);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");


 # Hide the return button
 $smarty->assign('pbBack',FALSE);

# Collect additional javascript code
ob_start();
?>

<script  language="javascript">
<!--
function chkform(d) {
	document.listdepotform.action="";
	document.listdepotform.submit();
}

function search()
{
	var search = document.getElementById('search').value;
	document.listdepotform.action="<?php echo $urlsearch;?>&search="+search;
	document.listdepotform.submit();
}

function viewDetail(issue_id)
{
	var win = 'include/inc_issuepaper_showdetail.php<?php echo URL_APPEND; ?>' +'&issue_id=' + issue_id;
	myWindow=window.open( win , 'View Details' , 'height=500,width=700' );
	myWindow.focus();
}
-->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Append the extra javascript to JavaScript block
$smarty->append('JavaScript',$sTemp);


# Load and display the tab
ob_start();
	require('./include/inc_issuepaper_tabs.php');
	$smarty->display('nursing/issuepaper_tab.tpl');
	$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('sTab',$sTemp);


$smarty->assign('sRegForm','<form name="listdepotform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('deptname',$LDDept.': '.$deptname);
$smarty->assign('ward',$LDWard.': '.$wardname);
$smarty->assign('LDWard',$LDWard);
$smarty->assign('LDDetail',$LDDetail);
$smarty->assign('LDIssueId',$LDIssueId);
$smarty->assign('LDCreatorName',$LDCreatorName);
$smarty->assign('LDDatetime',$LDDatetime);
$smarty->assign('LDUseFor',$LDUseFor);
$smarty->assign('LDType',$LDTypeIssue);
$smarty->assign('LDStatus',$LDStatus);
$smarty->assign('LDEdit',$LDEdit);
$smarty->assign('LDsearchGuide',$LDSearchIssueGuide);

if ($search==''){
	//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
	$number_items_per_page=10;
	$condition=" WHERE ".$condition_dept_ward." ORDER BY date_time_create ";
	
	$total_items=$IssuePaper->countIssuePaperItems($condition);
	$total_items=$total_items['sum_item'];
	
	$total_pages=ceil($total_items/$number_items_per_page);
	
	include_once('include/inc_issuepaper_listdepot_splitpage.php');
	
	$listIssue = $IssuePaper->listSomeIssuePaperSplitPage($current_page,$number_items_per_page,$condition);

}else{
	if (strrpos($search,'/') || strrpos($search,'-')){
		$search = formatDate2STD($search,'dd/mm/yyyy');
		$condition=" WHERE ".$condition_dept_ward." AND date_time_create LIKE '".$search."%' ORDER BY date_time_create DESC";
	}
	else
		$condition=" WHERE ".$condition_dept_ward." AND issue_paper_id LIKE '%".$search."%' ORDER BY issue_paper_id";
		
	$listIssue = $IssuePaper->listAllIssuePaper($condition);
	$breakfile='nursing-issuepaper-listdepot.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
}


if(is_object($listIssue)){
	$sTemp='';
	for ($i=0;$i<$listIssue->RecordCount();$i++)
	{
		$rowIssue = $listIssue->FetchRow();
		$date = formatDate2Local($rowIssue['date_time_create'],'dd/mm/yyyy');
		$time=substr($rowIssue['date_time_create'],-8);
			
		if ($rowIssue['status_finish']){
			$editbutton="javascript:alert('".$LDCannotEditIssuePaper."')";
			$status_finish=$LDStatusOk; $temp1='check-r.gif';
		}
		else{
			if ($rowIssue['type']) $target='sum';
			else $target='depot';
			$editbutton='nursing-issuepaper-depot.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&issue_id='.$rowIssue['issue_paper_id'].'&mode=update&target='.$target;
			$status_finish=$LDStatusNotYet; $temp1='warn.gif';
		}
		
		if ($rowIssue['type']) $typeissue=$LDsumpres;
		else $typeissue=$LDnormal;
			

		if($wardinfo = $Ward->getWardInfo($rowIssue['ward_nr'])) 
			$rowIssue['ward_nr'] = $wardinfo['name'];
		else $rowIssue['ward_nr'] = $LDAllWard;
		
		switch($rowIssue['typeput']){
				case 0: $typeput=$LDBH; break;
				case 1: $typeput=$LDNoBH; break;
				case 2: $typeput=$LDCBTC; break;
				default: $typeput=$LDNoBH; break;
		}
		
		$sTemp=$sTemp.'<tr bgColor="#ffffff" >
							<td align="center"><a href=""><input type="image" '.createComIcon($root_path,'info3.gif','0','',TRUE).' onclick="viewDetail('.$rowIssue['issue_paper_id'].')"></a></td>
							<td align="center">'.$rowIssue['issue_paper_id'].'</td>
							<td align="center">'.$date.' &nbsp;'.$time.'</td>
							<td>'.$rowIssue['nurse'].'</td>
							<td>'.$rowIssue['ward_nr'].'</td>
							<td>'.$typeput.'</td>
							<td>'.$typeissue.'</td>
							<td align="center">'.$status_finish.'&nbsp;<img '.createComIcon($root_path,$temp1,'0','',TRUE).'></td>
							<td align="center"><a href="'.$editbutton.'"><img '.createLDImgSrc($root_path,'edit_sm.gif','0','absmiddle').'></a></td>
						</tr>';
	}
	$smarty->assign('listItem',$sTemp);
		
}else{
	$sTemp='<tr bgColor="#ffffff"><td colspan="9">'.$LDIssueNotFound.'</td></tr>';
	$smarty->assign('listItem',$sTemp);
}

$smarty->assign('splitPage',$sTempPage);

//*********************************************************************************

$smarty->assign('pbSearch','<a href="javascript:search()"><input type="image" '.createComIcon($root_path,'Search.png','0','',TRUE).' onclick="" ></a>');
//$smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

//$smarty->assign('test',$target.' '.$dept_nr.' '.$search.' '.$ward_nr.' '.$total_items.' '.$total_pages);


//sHiddenInputs
	$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="current_page" value="'.$current_page.'">
		<input type="hidden" name="number_items_per_page" value="'.$number_items_per_page.'">
		<input type="hidden" name="ward_nr" value="'.$ward_nr.'">
		<input type="hidden" name="dept_nr" value="'.$dept_nr.'">';

	$smarty->assign('sHiddenInputs',$sTempHidden);


# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','nursing/issuepaper_listdepot.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>