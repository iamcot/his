<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
//Get info of current department, ward
$Ward = new Ward;
if ($ward_nr!=''){
//	require_once($root_path.'include/care_api_classes/class_ward.php');
//	$Ward = new Ward;
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


$thisfile= basename(__FILE__);
$breakfile='../nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$fileissue= '../nursing-issuepaper-medipot-depot.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$filereturn= 'medipot_return_medipot.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$urlsearch=$thisfile.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDInventory_Medipot.' :: '.$LDMedipotCatalogue);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 # Window bar title
 $smarty->assign('title',$LDInventory_Medipot);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);

  ob_start();
?>
<style type="text/css">

</style>
<script language="javascript">
function searchValue()
{
	var search = document.getElementById('search').value;
	document.listmedform.action="<?php echo $urlsearch;?>&search="+search;
	document.listmedform.submit();
}
function sortUp()
{
	document.getElementById('mode').value='sort_up';
	document.listmedform.action="<?php echo $urlsearch;?>&mode=sort_up";
	document.listmedform.submit();
}
function sortDown()
{
	document.getElementById('mode').value='sort_down';
	document.listmedform.action="<?php echo $urlsearch;?>&mode=sort_down";
	document.listmedform.submit();
}

function chkform(d) {
	document.listmedform.action="";
	document.listmedform.submit();
}
function issueItem(){
	var i,total=0;
	var itemid='';
	var n= document.getElementsByName('groupcb');
	for(i=0; i <n.length ; i++){
		var obj = document.getElementsByName('groupcb').item(i);
		if(obj.checked){
			itemid = itemid +'_'+ obj.value;
			total++;
		}
	}
	document.listmedform.action="<?php echo $fileissue; ?>&maxid="+total+"&itemid="+itemid;
	document.listmedform.submit();
}
function returnItem(){
	var i,total=0;
	var itemid='';
	var n= document.getElementsByName('groupcb');
	for(i=0; i <n.length ; i++){
		var obj = document.getElementsByName('groupcb').item(i);
		if(obj.checked){
			itemid = itemid +'_'+ obj.value;
			total++;
		}
	}
	document.listmedform.action="<?php echo $filereturn; ?>&maxid="+total+"&itemid="+itemid;
	document.listmedform.submit();
}
function checkUncheckAll(checkAllState)
{
	var cbGroup = document.listmedform.groupcb;
	if(cbGroup.length > 0)
		for (i = 0; i < cbGroup.length; i++)
			cbGroup[i].checked = checkAllState.checked;
}
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_product.php');
$Product=new Product();

$datetime=date("Y-m-d G:i:s");

if(!isset($mode))
	$mode='sort_up';

if ($mode=='sort_up'){
	$picup='arrow_up_blue.gif';
	$picdown='arrow_down_gray.gif';
	$updown='';
}else {
	$picup='arrow_up_gray.gif';
	$picdown='arrow_down_blue.gif';
	$updown=' DESC ';
}

ob_start();
?>
<form name="listmedform" method="POST"  onSubmit="return chkform(this)">
<center>
<table cellSpacing="1" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="3" color="#5f88be"><?php echo $LDDept.': '.$deptname; ?></th>
		<td align="right" rowspan="2">
				<table>
					<tr><td><input type="text" id="search" value="" size="30"></td>
						<td><a href="javascript:searchValue()"><input type="image" <?php echo createComIcon($root_path,'Search.png','0','',TRUE) ?> onclick="" ></a></td>
					</tr>
					<tr><td colspan="2"><FONT size="1"><?php echo $LDsearchExpGuideMedipot; ?></td></tr>
				</table>
		</td>
	</tr>
	<tr><th align="left" valign="top"><font size="2" color="#85A4CD"><?php echo $LDWard.': '.$wardname; ?></th></tr>
</table>
<p>
<table border="0" cellSpacing="1" cellPadding="3" width="95%" bgColor="#C3C3C3">
	<tr bgColor="#EDF1F4">
		<th align="center"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"></th>
		<th><?php echo $LDSTT; ?></th>
<!--		<th>--><?php //echo $LDMedicineID1; ?><!--</th>-->
		<th><?php echo $LDMedipotName; ?></th>
		<th><?php echo $LDUnit; ?></th>
        <th>Loại</th>
<!--		<th>--><?php //echo $LDLotID; ?><!--</th>-->
		<th><?php echo $LDExpDate; ?></th>
		<th><?php echo $LDCabinetMedipotSum; ?>&nbsp;
			<a href="javascript:sortUp()"><input type="image" <?php echo createComIcon($root_path,$picup,'0','',TRUE) ?> onclick="" title="<?php echo $LDSortUp; ?>"></a>&nbsp;<a href="javascript:sortDown()"><input type="image" <?php echo createComIcon($root_path,$picdown,'0','',TRUE) ?> onclick="" title="<?php echo $LDSortDown; ?>"></a></th>
<!--		<th>--><?php //echo $LDInitNumber; ?><!--</th>	-->
        <th>Nhận về</th>
	</tr>
	<?php
    if (!isset($typeput)) $typeput = 0;
    $condition = '';
    if ($typeput > -1)
        $condition = " AND taikhoa.typeput = $typeput ";
	if ($search==''){
		//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
//		$number_items_per_page=20; 	$condition='';

//		if ($listItem = $Product->SearchCatalogMedipotCabinet($dept_nr, $ward_nr, $condition, $updown)){
//			$total_items = $listItem->RecordCount();
//		} else $total_items =0;

//		$total_pages=ceil($total_items/$number_items_per_page);

//		include_once('../include/inc_issuepaper_listdepot_splitpage.php');

//		if ($total_pages>1)
			$listItem = $Product->ShowCatalogMedipotCabinet($dept_nr, $ward_nr,$condition, $current_page, $number_items_per_page, $updown);

	}else{
		if (strrpos($search,'/') || strrpos($search,'-')){
			$search = formatDate2STD($search,'dd/mm/yyyy');
			$condition .=" AND exp_date LIKE '".$search."%' ";
		}
		elseif (is_numeric($search))
			$condition .=" AND product_lot_id LIKE '%".$search."%' ";
		else
			$condition .=" AND product_name LIKE '%".$search."%' ";


		$listItem = $Product->SearchCatalogMedipotCabinet($dept_nr, $ward_nr, $condition, $updown);
		$breakfile = $thisfile.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
	}

	if(is_object($listItem)){
//		$sTemp='';
//		for ($i=0;$i<$listItem->RecordCount();$i++)
//		{
//			$rowItem = $listItem->FetchRow();
//
//			if ($rowItem['available_number']<=0)
//				$bgc="#D47FFF";
//			elseif ($rowItem['available_number']-$rowItem['init_number']<0)
//				$bgc="#AAFFFF";
//			else
//				$bgc="#ffffff";
        $sTemp = '';
        $recordcount = $listItem->RecordCount();
        $khu = '';
        for ($i = 0; $i < $recordcount; $i++) {
            $rowItem = $listItem->FetchRow();

            if ($rowItem['tonkho'] <= 0)
                $bgc = "#D47FFF";
            elseif ($rowItem['tonkho'] - $rowItem['init_number'] < 0)
                $bgc = "#AAFFFF"; else
                $bgc = "#ffffff";

            if ($wardinfo = $Ward->getWardInfo($rowItem['ward_nr']))
                $rowIssue['ward_nr'] = $wardinfo['name'];
            else $rowIssue['ward_nr'] = 'Chưa phân về khu phòng';
			$expdate= formatDate2Local($rowItem['exp_date'],'dd/mm/yyyy');
            if($khu != $rowIssue['ward_nr'])
            {
                $sTemp .= '<tr><td colspan="7"><center><b>'.$rowIssue['ward_nr'].'</b></center></td></tr>';
                $khu = $rowIssue['ward_nr'];
            }
            $sTemp = $sTemp . '<tr bgColor="' . $bgc . '" >
								<td align="center"><input type="checkbox" name="groupcb" value="' . $rowItem['id'] . '"></td>
								<td align="center">' . ($i + 1) . '</td>

								<td>' . $rowItem['product_name'] . '</td>
								<td align="center">' . $rowItem['unit_name_of_medicine'] . '</td>
								<td>';
            switch($rowItem['typeput']){
                case 0: $sTemp.='BHYT';break;
                case 1: $sTemp.='Sự nghiệp';break;
                case 2: $sTemp.='CBTC';break;
            }
            $sTemp.='</td>

								<td align="center">' . $expdate . '</td>

								<td align="center">' . $rowItem['tonkho'] . '</td>
								<td><center>';

            if($rowItem['ward_nr']==0) $sTemp.= $rowItem['nhanvekhoa'];
            $sTemp.='</center></td></tr>';
        }
        echo $sTemp;

    } else {
        $sTemp = '<tr bgColor="#ffffff"><td colspan="10">' . $LDItemNotFound . '</td></tr>';
        echo $sTemp;
    }
//			$sTemp=$sTemp.'<tr bgColor="'.$bgc.'" >
//								<td align="center"><input type="checkbox" name="groupcb" value="'.$rowItem['available_product_id'].'"></td>
//								<td align="center">'.($i+1).'</td>
//								<td align="center">'.$rowItem['product_encoder'].'</td>
//								<td>'.$rowItem['product_name'].'</td>
//								<td align="center">'.$rowItem['unit_name_of_medicine'].'</td>
//								<td align="center">'.$rowItem['product_lot_id'].'</td>
//								<td align="center">'.$expdate.'</td>
//								<td align="center">'.$rowItem['available_number'].'</td>
//								<td align="center">'.$rowItem['init_number'].'</td>
//							</tr>';
//		}
//		echo $sTemp;
//
//	}else{
//		$sTemp='<tr bgColor="#ffffff"><td colspan="10">'.$LDItemNotFound.'</td></tr>';
//		echo $sTemp;
//	}

	?>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<td align="center"><?php echo $sTempPage; ?></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="lang" value="<?php echo $lang; ?>">
			<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>">
			<input type="hidden" name="number_items_per_page" value="<?php echo $number_items_per_page; ?>">
			<input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>">
		</td>
	</tr>
	<tr><td align="center">&nbsp;<p>
		<a href="javascript:issueItem();"><img <?php echo createLDImgSrc($root_path,'issue.gif','0','middle'); ?> ></a><img src="<?php echo $root_path; ?>gui/img/common/default/pixel.gif" border="0" width="30" height="16">
		<a href="javascript:returnItem();"><img <?php echo createLDImgSrc($root_path,'return.gif','0','middle'); ?> ></a><p>&nbsp;</td></tr>
	<tr>
		<td align="right">
		<table border="0" cellpadding="2" cellspacing="1" bgColor="#B4B4B4">
			<tr><td style="background-color:#D47FFF;width:10px;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDListGuide1; ?></td></tr>
			<tr><td style="background-color:#AAFFFF;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDListGuide2; ?></td></tr>
			<tr><td style="background-color:#ffffff;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDListGuide3; ?></td></tr>
		</table>
		</td>
	</tr>
</table>


</center>
</form>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

$smarty->assign('breakfile',$breakfile);

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

