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
    define('LANG_FILE','konsil.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    // Erase all cookies used for 2nd level script locking, all following scripst will be locked
    // reset all 2nd level lock cookies

$breakfile=$root_path."modules/registration_admission/aufnahme_daten_zeigen.php".URL_APPEND."&edit=$edit&encounter_nr=$pn";

//****************************************************************************************************************************************
/**
 * LOAD Smarty
 */

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',$LDReqTest.' :: '.$LDChanDoanHinhAnh);

 $smarty->assign('breakfile',$breakfile);

 $smarty->assign('Name',$LDReqTest);


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
function refresh(){
	location.reload(true);
}
</script>

<?php 
$sTemp = ob_get_contents();
ob_end_clean();

// Append javascript to JavaScript block

 $smarty->append('JavaScript',$sTemp);

 $img_title=createComIcon($root_path,'new_address.gif','0');
 
 
ob_start();
?>
<form name="unitform" method="POST">
<TABLE cellpadding="5" cellspacing="10" >
	<TBODY>
		<tr>
			<td align="right">
			<?php 
			if($edit)
			{
			   echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
			}
			elseif($pn=='')
			{
				$searchmask_bgcolor="#f3f3f3";
				include($root_path.'modules/laboratory/includes/inc_test_request_searchmask.php');
			}
			?>
			</td>
			<td valign="top">
				<table cellspacing="0" cellpadding="5" >
					<tr>
						<td> <img <?php echo $img_title ?>>&nbsp;</td>
						<td>
							<a href="<?php echo $root_path.'modules/nursing/nursing-station-patientdaten-doconsil-dientim.php'.URL_APPEND.'&pn='.$pn.'&edit='.$edit.'&target=dientim'.'&user_origin='.$user_origin.'&noresize='.$noresize; ?>"><?php echo $LDYeuCauDienTim; ?></a>
						</td>
					</tr>
					<tr>
						<td> <img <?php echo $img_title ?>>&nbsp;</td>
						<td valign="top">
							<a href="<?php echo $root_path.'modules/nursing/nursing-station-patientdaten-doconsil-xquangsieuam.php'.URL_APPEND.'&pn='.$pn.'&edit='.$edit.'&target=radio'.'&user_origin='.$user_origin.'&noresize='.$noresize; ?>"><?php echo $LDYeuCauXquangSieuAm; ?></a>						
						</td>
					</tr>
				</table>			
			</td>					
		</tr>
	</TBODY>
</TABLE>
</form>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

 $smarty->display('common/mainframe.tpl');

?>