<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

define('LANG_FILE','pharma_put_in.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$thisfile= basename(__FILE__);
$breakfile=$root_path.'modules/pharmacy/apotheke.php'.URL_APPEND;
$urlsearch=$thisfile.URL_APPEND;
	
# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');
 
# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$TypepayoutMedipot);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('Name',$TypepayoutMedipot);

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
	document.listform.action="";
	document.listform.submit();
}

function search()
{
	var search = document.getElementById('search').value;
	document.listform.action="<?php echo $urlsearch;?>&search="+search;
	document.listform.submit();
}

function viewDetail(issue_id)
{
	var win = 'includes/khochan_payout_medipot_showdetail.php<?php echo URL_APPEND; ?>' +'&report_id=' + issue_id+"&type=medipot";
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
$new_report_url='payout_medipot.php';
$list_report_url=$thisfile;
ob_start();
	require('includes/khochan_putin_tab.php');
	$smarty->display('pharmacy/khochan_putin_tab.tpl');
	$sTemp = ob_get_contents();
ob_end_clean();
$smarty->assign('sTab',$sTemp);

$smarty->assign('sRegForm','<form name="listform" method="POST" onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('deptname',$LDPayOutPaper);
$smarty->assign('LDDetail',$LDDetail);
$smarty->assign('LDPutInId',$LDPutInID);
$smarty->assign('LDEncoder',$LDEncoder);
$smarty->assign('LDCreatorName',$LDPutInPerson1);
$smarty->assign('LDDatetime',$LDDate);
$smarty->assign('LDTotal',$LDTotal);
$smarty->assign('LDExportTo',$LDExportTo);
$smarty->assign('LDStatus',$LDStatus);
$smarty->assign('LDEdit',$LDEdit);
$smarty->assign('LDsearchGuide',$LDSearchIssueGuide);
$smarty->assign('LDTypePut',$LDTypePutIn1);

require_once($root_path.'include/care_api_classes/class_pharma.php');
$Pharma = new Pharma;

if ($search==''){
	//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
	$number_items_per_page=20;
	
	$condition=" WHERE pharma_type_pay_out='2' ORDER BY pay_out_id ";
		
	$total_items=$Pharma->countPayOutMedItems($condition);
	$total_items=$total_items['sum_item'];
		
	$total_pages=ceil($total_items/$number_items_per_page);
		
	include_once('includes/khochan_putin_listreport_splitpage.php');

	$listItem = $Pharma->listSomePayOutMedSplitPage($current_page,$number_items_per_page,$condition);
		
}else{
	if (strrpos($search,'/') || strrpos($search,'-')){
		$search = formatDate2STD($search,'dd/mm/yyyy');
		$condition=" WHERE pharma_type_pay_out='2' AND date_time LIKE '".$search."%' ORDER BY date_time DESC";
	}
	else
		$condition=" WHERE pharma_type_pay_out='2' AND pay_out_id LIKE '%".$search."%' ORDER BY pay_out_id";	
		$listItem = $Pharma->listAllPayOutMed($condition);	
		$breakfile = $thisfile.URL_APPEND;
	}

	if(is_object($listItem)){
		$sTemp='';
		for ($i=0;$i<$listItem->RecordCount();$i++)
		{
			$rowItem = $listItem->FetchRow();
			$date = formatDate2Local($rowItem['date_time'],'dd/mm/yyyy');
			$time=substr($rowItem['date_time'],-8);
				
			if ($rowItem['status_finish']){
				$editbutton="javascript:alert('".$LDCannotEditPayOutMed."')";
				$status_finish=$LDPayOutOk; $temp1='check-r.gif';
			}
			else{
				$editbutton='payout_medipot.php'.URL_APPEND.'&payout_id='.$rowItem['pay_out_id'].'&target=update';
				$status_finish=$LDPayOutNotYet; $temp1='warn.gif';
			}						
			if ($rowItem['health_station']==0)
				$placeto=$LDKhoLe;
			else $placeto=$LDTramYTe_PhongKham;
			
			switch($rowItem['typeput']){
				case 0: $typeput=$LDBH; break;
				case 1: $typeput=$LDNoBH; break;
				case 2: $typeput=$LDCBTC; break;
				default: $typeput=$LDNoBH; break;
			}
			$sTemp=$sTemp.'<tr bgColor="#ffffff" >
								<td align="center"><a href="#"><input type="image" '.createComIcon($root_path,'info3.gif','0','',TRUE).' onclick="viewDetail('.$rowItem['pay_out_id'].')"></a></td>
								<td align="center">'.$rowItem['pay_out_id'].'</td>
								<td align="center">'.$rowItem['voucher_id'].'</td>
								<td align="center">'.$date.' &nbsp;'.$time.'</td>
								<td>'.$rowItem['receiver'].'</td>
								<td>'.$typeput.'</td>
								<td align="right">'.number_format($rowItem['totalcost']).'</td>
								<td>'.$placeto.'</td>
								<td align="center">'.$status_finish.'&nbsp;<img '.createComIcon($root_path,$temp1,'0','',TRUE).'></td>
								<td align="center"><a href="'.$editbutton.'"><img '.createLDImgSrc($root_path,'edit_sm.gif','0','absmiddle').'></a></td>
							</tr>';
		}
		$smarty->assign('listItem',$sTemp);
			
	}else{
		$sTemp='<tr bgColor="#ffffff"><td colspan="10">'.$LDItemNotFound.'</td></tr>';
		$smarty->assign('listItem',$sTemp);
	}

$smarty->assign('splitPage',$sTempPage);

//*********************************************************************************

//sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="current_page" value="'.$current_page.'">
		<input type="hidden" name="number_items_per_page" value="'.$number_items_per_page.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

$smarty->assign('pbSearch','<a href="javascript:search()"><input type="image" '.createComIcon($root_path,'Search.png','0','',TRUE).' onclick="" ></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/pay_out_list.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

