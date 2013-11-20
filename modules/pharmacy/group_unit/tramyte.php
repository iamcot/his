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


require_once ($root_path.'include/care_api_classes/class_health_station.php');
$class_obj=new HealthStation;

//type= type, health

//****************************************************************************************************************************************
/**
 * LOAD Smarty
 */

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',$LDPharmacy.' :: '.$LDPharmaTramYTe);

 $smarty->assign('breakfile',$breakfile);

 $smarty->assign('Name',$LDPharmaTramYTe);


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
	urlholder="new_tramyte.php<?php echo URL_APPEND; ?>&type="+type;
	updatevalue=window.open(urlholder,"New","width=400,height=300,menubar=no,resizable=yes,scrollbars=yes");
}
function updateitem(type){
	switch(type){
		case 'health':
			var select = document.getElementById("sl_health");  
			if(select.selectedIndex>-1){
				var unit = select.options[select.selectedIndex].value; 
				urlholder="update_tramyte.php<?php echo URL_APPEND; ?>&nr="+unit+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=300,menubar=no,resizable=yes,scrollbars=yes");
			}
			else alert('<?php echo $LDPlsSelectHealth; ?>');
			break;
			
		case 'type':
			var select = document.getElementById("sl_type");  
			if(select.selectedIndex>-1){
				var unit = select.options[select.selectedIndex].value; 
				urlholder="update_tramyte.php<?php echo URL_APPEND; ?>&nr="+unit+"&type="+type;
				updatevalue=window.open(urlholder,"update","width=400,height=200,menubar=no,resizable=yes,scrollbars=yes");				
			}
			else alert('<?php echo $LDPlsSelectHealthType; ?>');			
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

 $img_pharma=createComIcon($root_path,'bestell.gif','0');
 
 
ob_start();
?>
<form name="unitform" method="POST">
<center>
<TABLE cellpadding="5" cellspacing="10" width="90%">
	<TBODY>
		<tr>
			<td align="center" valign="top">
				<!-- Dang thuoc -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width="10%" align="center" class="tr1">
							<img <?php echo $img_pharma; ?> >
						</td>
						<td class="tr1" >
							<b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LDTramYTe; ?></b>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<select id="sl_health" name="sl_health" size="20" style="width:250px;">
							<?php
								$list_ht = $class_obj->listHealth();
								if(is_object($list_ht)){
									$group=''; $listbox='';
									while($ht_item = $list_ht->FetchRow()){
										if ($group=='' || $group!=$ht_item['type']){
											$listbox .=  '<optgroup label="'.$ht_item['typename'].'">';
											$listbox .= '<option value="'.$ht_item['health_station'].'">'.$ht_item['village'].'</option>';
											$group=$ht_item['type'];
										}	
										else
											$listbox .= '<option value="'.$ht_item['health_station'].'">'.$ht_item['village'].'</option>';
										
									}
									echo $listbox;
								}
							?>
							</select>
							<p>
							<a href="javascript:newitem('health');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('health');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;						
						</td>
					</tr>
				</table>			
			</td>	
			<td align="center" valign="top">
				<!-- Duong dung -->
				<table width="280px" cellspacing="0" cellpadding="5" class="table1" >
					<tr>
						<td width="10%" align="center" class="tr1">
							<img <?php echo $img_pharma; ?> >
						</td>
						<td class="tr1" >
							<b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $LDTypeHealth; ?></b>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center"> 
							<select id="sl_type" name="sl_type" size="20" style="width:250px;">
							<?php
								$list_type = $class_obj->listType();
								if(is_object($list_type)){
									while($tp_item = $list_type->FetchRow()){
										echo '<option value="'.$tp_item['nr'].'">'.$tp_item['typename'].'</option>';
									}
								}
							?>
							</select>
							<p>	
							<a href="javascript:newitem('type');"><img <?php echo createLDImgSrc($root_path,'add_sm.gif','0') ?> title="<?php echo $LDNew ?>"></a>&nbsp;
							<a href="javascript:updateitem('type');"><img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> title="<?php echo $LDEdit ?>"></a>&nbsp;								
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