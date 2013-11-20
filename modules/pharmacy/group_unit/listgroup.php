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


require_once ($root_path.'include/care_api_classes/class_listgroup.php');
$class_obj=new ListGroup;

//type= pharma, pharmasub, medipot, chemical

//****************************************************************************************************************************************
/**
 * LOAD Smarty
 */

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',$LDPharmacy.' :: '.$LDGroupCatalogue);

 $smarty->assign('breakfile',$breakfile);

 $smarty->assign('Name',$LDGroupCatalogue);


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
	urlholder="new_listgroup.php<?php echo URL_APPEND; ?>&type="+type;
	if(type=='pharmasub')
		updatevalue=window.open(urlholder,"New","width=800,height=200,menubar=no,resizable=yes,scrollbars=yes");
	else
		updatevalue=window.open(urlholder,"New","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");
}
function updateitem(type){
	switch(type){
		case 'pharma':
			var select = document.getElementById("lbx_pharma");  
			if(select.selectedIndex>-1){
				var nr = select.options[select.selectedIndex].value; 
				urlholder="update_listgroup.php<?php echo URL_APPEND; ?>&nr="+nr+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=300,menubar=no,resizable=yes,scrollbars=yes");
			}
			else alert('<?php echo $LDPlsSelectPharma; ?>');
			break;
			
		case 'pharmasub':
			var select = document.getElementById("lbx_pharmasub");  
			if(select.selectedIndex>-1){
				var nr = select.options[select.selectedIndex].value; 
				urlholder="update_listgroup.php<?php echo URL_APPEND; ?>&nr="+nr+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=800,height=200,menubar=no,resizable=yes,scrollbars=yes");				
			}
			else alert('<?php echo $LDPlsSelectPharmaSub; ?>');			
			break;
			
		case 'medipot':
			var select = document.getElementById("lbx_medipot");  
			if(select.selectedIndex>-1){
				var nr = select.options[select.selectedIndex].value; 
				urlholder="update_listgroup.php<?php echo URL_APPEND; ?>&nr="+nr+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");				
			}
			else alert('<?php echo $LDPlsSelectMedipot; ?>');			
			break;
			
		case 'chemical':
			var select = document.getElementById("lbx_chemical");  
			if(select.selectedIndex>-1){
				var nr = select.options[select.selectedIndex].value; 
				urlholder="update_listgroup.php<?php echo URL_APPEND; ?>&nr="+nr+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");				
			}
			else alert('<?php echo $LDPlsSelectChemical; ?>');			
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
				<!-- Nhom thuoc -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width="10%" align="center" class="tr1">
							<img <?php echo $img_pharma; ?> >
						</td>
						<td class="tr1" >
							<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LDNhomThuoc; ?></b>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<div style="width:400px;overflow:auto;">
							<select id="lbx_pharma" name="lbx_pharma" size="10">
							<?php
								$list_pm = $class_obj->listPharmaGroup();
								if(is_object($list_pm)){
									while($pm_item = $list_pm->FetchRow()){
										echo '<option value="'.$pm_item['pharma_group_id'].'">'.$pm_item['pharma_group_id'].'- '.$pm_item['pharma_group_name'].'</option>';
									}
								}
							?>
							</select>
							</div>
							<p>
							<a href="javascript:newitem('pharma');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('pharma');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;						
						</td>
					</tr>
				</table>			
			</td>	
			<td align="center" valign="top">
				<!-- VTYT -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width="10%" align="center" class="tr1">
							<img <?php echo $img_med; ?> >
						</td>
						<td class="tr1" >
							<b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LDGroupMedipot; ?></b>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<select id="lbx_medipot" name="lbx_medipot" size="11" style="width:250px;">
							<?php
								$list_med = $class_obj->listMedGroup();
								if(is_object($list_med)){
									$group=''; $listbox='';
									while($med_item = $list_med->FetchRow()){
										if ($group=='' || $group!=$med_item['type_of_med']){
											$listbox .=  '<optgroup label="'.$med_item['type_name_of_med'].'">';
											$listbox .= '<option value="'.$med_item['id'].'">'.$med_item['name_sub'].'</option>';
											$group=$med_item['type_of_med'];
										}	
										else
											$listbox .= '<option value="'.$med_item['id'].'">'.$med_item['name_sub'].'</option>';
										
									}
									echo $listbox;
								}
							?>
							</select>
							<p>
							<a href="javascript:newitem('medipot');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('medipot');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;						
						</td>
					</tr>
				</table>			
			</td>									
		</tr>
		<tr>
			<td align="center" valign="top">
				<!-- Nhom thuoc con -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width="10%" align="center" class="tr1">
							<img <?php echo $img_pharma; ?> >
						</td>
						<td class="tr1" >
							<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LDNhomThuocCon; ?></b>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							
							
							<div style="width:400px;overflow:auto;">
							<select id="lbx_pharmasub" name="lbx_pharmasub" size="10" >
							<?php
								$list_pms = $class_obj->listPharmaGroupSub();
								if(is_object($list_pms)){
									$group=''; $listbox='';
									while($pms_item = $list_pms->FetchRow()){
										if ($group=='' || $group!=$pms_item['pharma_group_id']){
											$listbox .=  '<optgroup label="'.$pms_item['pharma_group_id'].'- '.$pms_item['pharma_group_name'].'">';
											$listbox .= '<option value="'.$pms_item['nr'].'">'.$pms_item['pharma_group_id_sub'].'- '.$pms_item['pharma_group_name_sub'].'</option>';
											$group=$pms_item['pharma_group_id'];
										}	
										else
											$listbox .= '<option value="'.$pms_item['nr'].'">'.$pms_item['pharma_group_id_sub'].'- '.$pms_item['pharma_group_name_sub'].'</option>';
										
									}
									echo $listbox;
								}
							?>
							</select>
							</div>
							<p>	
							<a href="javascript:newitem('pharmasub');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('pharmasub');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;								
						</td>
					</tr>
				</table>	
			</td>
			<td align="center" valign="top">
				<!-- Hoa chat -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width="10%" align="center" class="tr1">
							<img <?php echo $img_chemical; ?> >
						</td>
						<td class="tr1" >
							<b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LDGroupChemical; ?></b>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<select id="lbx_chemical" name="lbx_chemical" size="11" style="width:250px;">
							<?php
								$list_che = $class_obj->listChemicalGroup();
								if(is_object($list_che)){
									while($che_item = $list_che->FetchRow()){
										echo '<option value="'.$che_item['chemical_group_id'].'">'.$che_item['chemical_group_name'].'</option>';
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


