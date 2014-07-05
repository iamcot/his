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
$breakfile='medipot_distribute_medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr=';
$fileforward= 'medipot_distribute_save.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr=';

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDDistributeMedipot);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDDistributeMedipot')");

 # Window bar title
 $smarty->assign('title',$LDDistributeMedipot);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
  ob_start();
?>
<style type="text/css">

</style>
<script language="javascript">

function SaveValue() {	
	var flag=true;
	var ni=<?php echo $maxid; ?>;
	var color;

	for (var i=1;i<=ni;i++){
		color=document.getElementById('sum'+i).style.backgroundColor;
		if (color!="#ffffff" && color!="#FFFFFF" && color!="rgb(255, 255, 255)"){
			flag=false;
			break;
		}
	}
		
	if(flag==false){
		alert("<?php echo $LDAllValueEqualSum; ?>");
		return;
	}else{
		document.listmedform.action="<?php echo $fileforward; ?>";
		document.listmedform.submit();
	}
}

function CheckValue(i) {
	var sum_value=document.getElementById('sum'+i).value;
	var nj=<?php echo $number_ward; ?>;
	var value=0;
	for (var j=0;j<nj;j++){
        if(document.getElementById('item'+i+'_'+j).value=='') document.getElementById('item'+i+'_'+j).value = 0;
		value=value+ parseInt(document.getElementById('item'+i+'_'+j).value);
	}
	if(value==sum_value){
		document.getElementById('sum'+i).style.backgroundColor='#FFFFFF';
	}else 
		document.getElementById('sum'+i).style.backgroundColor='#FFAAFF';
}

</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 

include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_cabinet_medipot.php');
$CabinetPharma = new CabinetMedipot;

$datetime=date("Y-m-d G:i:s");
unset($list_wardnr);

ob_start();
?>
<form name="listmedform" method="POST"  onSubmit="return chkform(this)">
<center>
<table cellSpacing="1" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="3" color="#5f88be"><?php echo $LDDept.': '.$deptname; ?></th>
	</tr>
	<tr><th align="left" valign="top"><font size="2" color="#85A4CD"><?php echo $LDWard.': '.$wardname; ?></th></tr>
</table>
<p>
<table border="0" cellSpacing="1" cellPadding="3" width="95%" bgColor="#C3C3C3">
	<tr bgColor="#EDF1F4">
		<th><?php echo $LDSTT; ?></th>
		<th><?php echo $LDMedipotID; ?></th>
		<th><?php echo $LDMedipotName; ?></th>
		<th><?php echo $LDUnit; ?></th>	
		<th><?php echo $LDImport; ?></th>	
	<?php 	
		if($number_ward)
			for ($i=0;$i<$number_ward;$i++){
				$tempward=$list_ward->FetchRow();
				$list_wardnr[$i]=$tempward['nr'];
				echo '<th><font color="#DD6001">'.$tempward['name'].'</font></th>';
			}
	?>
	</tr>																																
	<?php 
	$listid = explode('_',$itemid);
	reset($list_wardnr);
	//echo $listid[1].' '.$listid[2];
	
	for ($j=1;$j<=$maxid;$j++){
		$rowItem = $CabinetPharma->getInfoMedInAvaiDept($listid[$j]);
		if($rowItem){ 
			$sTemp=$sTemp.'<tr bgColor="#ffffff" >
							<td align="center">'.$j.'</td>
							<td align="center">'.$rowItem['product_encoder'].'</td>
							<td>'.$rowItem['product_name'].'</td>
							<td align="center">'.$rowItem['unit_name_of_medicine'].'</td>
							<td align="center"><input type="text" id="sum'.$j.'" value="'.$rowItem['available_number'].'" size="7" readonly style="text-align:center;border-color:white;border-style:solid;background-color:#FFAAFF;" ></td>';
			if($number_ward)
				for ($i=0;$i<$number_ward;$i++)
					$sTemp=$sTemp.'<td align="center"><input ondblclick="this.value=\'\'" type="text" name="med['.$rowItem['available_product_id'].'_'.$list_wardnr[$i].']" id="item'.$j.'_'.$i.'" size="2" value="0" onBlur="CheckValue('.$j.')"></td>';
			$sTemp=$sTemp.'</tr>';
		}
	}
	echo $sTemp;
	?>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<td>
			<input type="hidden" name="lang" value="<?php echo $lang; ?>">
			<input type="hidden" name="maxid" id="maxid" value="<?php echo $maxid; ?>">
			<input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>">
			<input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
			<input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
		</td>
	</tr>
	<tr><td align="center">&nbsp;<p><a href="javascript:SaveValue();" ><img <?php echo createLDImgSrc($root_path,'done.gif','0','middle'); ?> ></a><p>&nbsp;</td></tr>
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

