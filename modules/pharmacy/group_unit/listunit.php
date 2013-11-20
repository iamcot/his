<?php

	error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
    * GNU General Public License
    * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
    * elpidio@care2x.org,
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    // Erase all cookies used for 2nd level script locking, all following scripst will be locked
    // reset all 2nd level lock cookies
    //require($root_path.'include/core/inc_2level_reset.php');

    if(!isset($_SESSION['sess_path_referer'])) $_SESSION['sess_path_referer'] = "";
    if(!isset($_SESSION['sess_user_origin'])) $_SESSION['sess_user_origin'] = "";

    $breakfile=$root_path.'modules/pharmacy/pharmacy.php'.URL_APPEND;

    $_SESSION['sess_path_referer']=$top_dir.basename(__FILE__);
    $_SESSION['sess_user_origin']='pharma';
    require ($root_path.'include/care_api_classes/class_access.php');
    $access = new Access($_SESSION['sess_login_userid'],$_SESSION['sess_login_pw']);
    $hideOrder = 0;
    if(ereg("_a_1_pharmadbadmin",$access->PermissionAreas()))
            $hideOrder = 1;

    $this_file="apotheke.php";


require_once ($root_path.'include/care_api_classes/class_unit.php');
$unit_obj=new Units;

//type=pharma, med, chemical

//****************************************************************************************************************************************
/**
 * LOAD Smarty
 */

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',$LDPharmacy.' :: '.$LDPharmaUnit);

 $smarty->assign('breakfile',$breakfile);

 $smarty->assign('Name',$LDPharmaUnit);


ob_start();
?>
<style type="text/css">
.table1 {
	border-bottom: solid 1px #C3C3C3;
	border-top: solid 1px #C3C3C3;
	border-left: solid 1px #C3C3C3;
	border-right: solid 1px #C3C3C3;
}
.tr1 {
	color:#003399;
	border-bottom: solid 1px #C3C3C3;
	background-color:#EDF1F4;
}
</style>
<script language="javascript">
<!--
function closewin() {
	location.href='startframe.php?sid=<?php echo "$sid&lang=$lang";?>';
}
function newitem(type){
	urlholder="new_unit.php<?php echo URL_APPEND; ?>&type="+type;
	updatevalue=window.open(urlholder,"New","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");
}
function updateitem(type){
	switch(type){
		case 'pharma':
			var select = document.getElementById("unit_pharma");  
			//alert(select.selectedIndex);
			if(select.selectedIndex>-1){
				var unit = select.options[select.selectedIndex].value; 
				urlholder="update_unit.php<?php echo URL_APPEND; ?>&nr="+unit+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");
			}
			else alert('<?php echo $LDPlsSelectUnit; ?>');
			break;
			
		case 'med':
			var select = document.getElementById("unit_med");  
			if(select.selectedIndex>-1){
				var unit = select.options[select.selectedIndex].value; 
				urlholder="update_unit.php<?php echo URL_APPEND; ?>&nr="+unit+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");				
			}
			else alert('<?php echo $LDPlsSelectUnit; ?>');			
			break;
		
		case 'chemical':
			var select = document.getElementById("unit_chemical");  
			if(select.selectedIndex>-1){
				var unit = select.options[select.selectedIndex].value; 
				urlholder="update_unit.php<?php echo URL_APPEND; ?>&nr="+unit+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");				
			}
			else alert('<?php echo $LDPlsSelectUnit; ?>');		
			break;
			
		default: return false;
	}
}
function refresh(){
	location.reload(true);
}
</script>

<?php 
$sTemp = ob_get_contents();
ob_end_clean();

// Append javascript to JavaScript block

 $smarty->append('JavaScript',$sTemp);

 $img_pharma=createComIcon($root_path,'pharma.jpg','0');
 $img_med=createComIcon($root_path,'storage.gif','0');
 $img_chemical=createComIcon($root_path,'Chemical.jpg','0');
 
 
ob_start();
?>
<form name="unitform" method="POST">
<center>
<TABLE cellpadding="5" cellspacing="10" width="90%">
	<TBODY>
		<tr>
			<td align="center" valign="top">
				<!-- Don vi thuoc -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width=8% align="center" class="tr1">
							<img <?php echo $img_pharma; ?> >
						</td>
						<th align="center" class="tr1" >
							<?php echo $LDUnitOfPharma; ?>&nbsp;&nbsp;
						</th>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<select id="unit_pharma" name="unit_pharma" size="20" style="width:250px;">
							<?php
								$list_pharma_unit = $unit_obj->listPharmaUnit();
								if(is_object($list_pharma_unit)){
									while($pharma_item = $list_pharma_unit->FetchRow()){
										echo '<option value="'.$pharma_item['unit_of_medicine'].'">'.$pharma_item['unit_name_of_medicine'].'</option>';
									}
								}
							?>
							</select>
							<p>
							<a href="javascript:newitem('pharma');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('pharma');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;						
						</td>
					</tr>
				</table>			
			</td>	
			<td align="center" valign="top">
				<!-- Don vi VTYT -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width=8% align="center" class="tr1">
							<img <?php echo $img_med; ?> >
						</td>
						<th align="center" class="tr1" >
							<?php echo $LDUnitOfMed; ?>&nbsp;&nbsp;
						</th>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<select id="unit_med" name="unit_med" size="20" style="width:250px;">
							<?php
								$list_med_unit = $unit_obj->listMedUnit();
								if(is_object($list_med_unit)){
									while($med_item = $list_med_unit->FetchRow()){
										echo '<option value="'.$med_item['unit_of_medicine'].'">'.$med_item['unit_name_of_medicine'].'</option>';
									}
								}
							?>
							</select>
							<p>	
							<a href="javascript:newitem('med');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('med');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;								
						</td>
					</tr>
				</table>	
			</td>			
			<td align="center" valign="top">
				<!-- Don vi Hoa Chat -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width=8% align="center" class="tr1">
							<img <?php echo $img_chemical; ?> >
						</td>
						<th align="center" class="tr1" >
							<?php echo $LDUnitOfChemical; ?>&nbsp;&nbsp;
						</th>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<select id="unit_chemical" name="unit_chemical" size="20" style="width:250px;">
							<?php
								$list_chemical_unit = $unit_obj->listChemicalUnit();
								if(is_object($list_chemical_unit)){
									while($che_item = $list_chemical_unit->FetchRow()){
										echo '<option value="'.$che_item['unit_of_chemical'].'">'.$che_item['unit_name_of_chemical'].'</option>';
									}
								}
							?>
							</select>
							<p>	
							<a href="javascript:newitem('chemical');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('chemical');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;								
						</td>
					</tr>
				</table>	
			</td>
		</tr>
	</TBODY>
</TABLE>
</center>
</form>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

 $smarty->display('common/mainframe.tpl');

?>