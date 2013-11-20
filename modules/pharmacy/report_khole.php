<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

$thisfile= basename(__FILE__);
$breakfile='pharmacy.php'.URL_APPEND;
	
# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoLe.' :: '.$LDTitleReport );

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPharmaReportUse')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('Name',$LDTitleReport);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
  ob_start();
?>
<style type="text/css">
blockquote.style2 {
	margin: 5px 5px 1px 30px; 
	padding: 0px;
	padding-left: 15px;
	border-left: 3px solid #ccc;
} 
</style>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
 
ob_start();
?>
<form>
<center>
<p>
<br>
<table border="0" width="98%">
<tr>
<td width="33%" align="center" valign="top">		<!-- Thuoc Tay Y-->
	<table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
	<tr><td><b><?php echo $LDThuocTayY; ?></b></td></tr>
	<tr bgColor="#ffffff"><td><blockquote class="style2">
		<?php	
			echo '<a href="report/khole_thuoc_nhapxuatton.php'.URL_APPEND.'&type=tayy" >'.$LDPharmaReportKhoLeTxt.'</a><p>';		
			echo '<a href="report/khole_thuoc_kiemke.php'.URL_APPEND.'&type=tayy">'.$LDPharmaReportInventory.'</a><p>';
			echo '<a href="report/khole_thuoc_thekho.php'.URL_APPEND.'&type=tayy" >'.$LDThekho.'</a><p>';
			echo '<a href="report/khole_thuoc_baocao15ngay.php'.URL_APPEND.'&type=tayy">'.$LDReportUse15Day1.'</a><p>';
			echo '<a href="report/khole_thuoc_baocaothang.php'.URL_APPEND.'&type=tayy">'.$LDReportUseMonth1.'</a><p>';	
		?>
	</blockquote></td></tr>
	</table>
</td>
<td width="33%" align="center" valign="top">		<!-- VTYT -->
	<table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
	<tr><td><b><?php echo $LDMedipot; ?></b></td></tr>
		<tr bgColor="#ffffff"><td><blockquote class="style2">
		<?php	
			echo '<a href="report/khole_vtyt_baocao15ngay.php'.URL_APPEND.'">'.$LDReportUse15Day1.'</a><p>';  
			echo '<a href="report/khole_vtyt_baocaothang.php'.URL_APPEND.'">'.$LDReportUseMonth1.'</a><p>';				
		?>
	</blockquote></td></tr>
	</table>
</td>
<td width="33%" align="center" valign="top">		<!-- Hóa ch?t -->
	<table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
	<tr><td><b><?php echo $LDChemical; ?></b></td></tr>
	<tr bgColor="#ffffff"><td><blockquote class="style2">
		<?php	
			echo '<a href="report/khole_hoachat_baocao15ngay.php'.URL_APPEND.'">'.$LDReportUse15Day1.'</a><p>';
			echo '<a href="report/khole_hoachat_baocaothang.php'.URL_APPEND.'">'.$LDReportUseMonth1.'</a><p>';				
		?>
	</blockquote></td></tr>
	</table>
</td>
</tr>
<tr>
<td width="33%" align="center">		<!-- Thuoc Dong Y-->
	<table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
	<tr><td><b><?php echo $LDThuocDongY; ?></b></td></tr>
	<tr bgColor="#ffffff"><td><blockquote class="style2">
		<?php	
			echo '<a href="report/khole_thuoc_nhapxuatton.php'.URL_APPEND.'&type=dongy" >'.$LDPharmaReportKhoLeTxt.'</a><p>';		
			echo '<a href="report/khole_thuoc_kiemke.php'.URL_APPEND.'&type=dongy">'.$LDPharmaReportInventory.'</a><p>';
			echo '<a href="report/khole_thuoc_thekho.php'.URL_APPEND.'&type=dongy" >'.$LDThekho.'</a><p>';
			echo '<a href="report/khole_thuoc_baocao15ngay.php'.URL_APPEND.'&type=dongy">'.$LDReportUse15Day1.'</a><p>';
			echo '<a href="report/khole_thuoc_baocaothang.php'.URL_APPEND.'&type=dongy">'.$LDReportUseMonth1.'</a><p>';	
		?>
	</blockquote></td></tr>
	</table>
</td>
<td></td>
<td></td>
</tr>
</table>
	
</center>
</form>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

