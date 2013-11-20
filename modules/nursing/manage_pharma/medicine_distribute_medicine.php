<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

//Get info of current department, ward
require_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'include/care_api_classes/class_ward.php');
$Ward = new Ward;
$Dept = new Department;
if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
	$deptname = ($$deptinfo['LD_var']);
	$wardname = $LDAllWard;
}
if($dept_nr){
	$list_ward = $Ward->getAvaiWardOfDept($dept_nr);
	if(is_object($list_ward)){
		$number_ward=$list_ward->RecordCount();
	}else $number_ward=0;
}
if($ward_nr=='0')
	$ward_nr='';
		
$thisfile= basename(__FILE__);
$breakfile='../nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$fileforward= 'medicine_distribute.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
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
 $smarty->assign('sToolbarTitle',$LDDistributeMedicine);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDDistributeMedicine')");

 # Window bar title
 $smarty->assign('title',$LDDistributeMedicine);

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
function chkform(d) {	
	document.listmedform.action="";
	document.listmedform.submit();
}
function sendItem(){
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
	if (total<=0){
		alert("<?php echo $LDNoMedChosen; ?>");
		return;
	}
	document.listmedform.action="<?php echo $fileforward; ?>&maxid="+total+"&itemid="+itemid;
	document.listmedform.submit();
}
function checkUncheckAll(checkAllState)
{
	var n= document.getElementsByName('groupcb');
	if(n.length > 0)
		for (i = 0; i < n.length; i++){
			var cbGroup =  document.getElementsByName('groupcb').item(i);
			cbGroup.checked = checkAllState.checked;
		}
}
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 

include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
$CabinetPharma = new CabinetPharma;

$datetime=date("Y-m-d G:i:s");

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
					<tr><td colspan="2"><FONT size="1"><?php echo $LDsearchExpGuide; ?></td></tr>
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
		<th><?php echo $LDMedicineID1; ?></th>
		<th><?php echo $LDMedicineName; ?></th>
		<th><?php echo $LDUnit; ?></th>	
		<th><?php echo $LDLotID; ?></th>
		<th><?php echo $LDExpDate; ?></th>
		<th><?php echo $LDImport; ?></th>	
	</tr>																																
	<?php 
	if ($search==''){
		//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
		$number_items_per_page=20; 	$condition=""; $ward_nr="0"; $updown="";
		
		if ($listItem = $CabinetPharma->SearchDistributeCabinet($dept_nr, $condition)){		
			$total_items = $listItem->RecordCount();
		} else $total_items =0;
		
		$total_pages=ceil($total_items/$number_items_per_page);
		
		include_once('../include/inc_issuepaper_listdepot_splitpage.php');

		if ($total_pages>1)
			$listItem = $CabinetPharma->ShowDistributeCabinet($dept_nr, $current_page, $number_items_per_page);
		
	}else{
		if (strrpos($search,'/') || strrpos($search,'-')){
			$search = formatDate2STD($search,'dd/mm/yyyy');
			$condition=" AND exp_date LIKE '".$search."%' ";
		}
		elseif (is_numeric($search))
			$condition=" AND product_lot_id LIKE '%".$search."%' ";
		else
			$condition=" AND product_name LIKE '%".$search."%' ";
			

		$listItem = $CabinetPharma->SearchDistributeCabinet($dept_nr, $condition);
		$breakfile = $thisfile.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
	}

		if(is_object($listItem)){
		$sTemp='';
		for ($i=0;$i<$listItem->RecordCount();$i++)
		{
			$rowItem = $listItem->FetchRow();	
			$expdate= formatDate2Local($rowItem['exp_date'],'dd/mm/yyyy');
			
			$sTemp=$sTemp.'<tr bgColor="#ffffff" >
								<td align="center"><input type="checkbox" name="groupcb" value="'.$rowItem['ID'].'"></td>
								<td align="center">'.($i+1).'</td>
								<td align="center">'.$rowItem['product_encoder'].'</td>
								<td>'.$rowItem['product_name'].'</td>
								<td align="center">'.$rowItem['unit_name_of_medicine'].'</td>
								<td align="center">'.$rowItem['product_lot_id'].'</td>
								<td align="center">'.$expdate.'</td>
								<td align="center">'.$rowItem['available_number'].'</td>
							</tr>';
		}
		echo $sTemp;
			
	}else{
		$sTemp='<tr bgColor="#ffffff"><td colspan="9">'.$LDItemNotFound.'</td></tr>';
		echo $sTemp;
	}
	
	?>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<td align="center"><?php echo $sTempPage; ?></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="lang" value="<?php echo $lang; ?>">
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>">
			<input type="hidden" name="number_items_per_page" value="<?php echo $number_items_per_page; ?>">
			<input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>">
			<input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
		</td>
	</tr>
	<tr><td align="center">&nbsp;<p><a href="javascript:sendItem();"><img <?php echo createLDImgSrc($root_path,'distribute.gif','0','middle'); ?> ></a><p>&nbsp;</td></tr>
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

