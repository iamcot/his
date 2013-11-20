<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

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
    $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDTitleReport);

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
    <br/>
    <table border="0" width="98%">
        <tr>
            <td width="33%" align="center" valign="top">		<!-- Thuoc -->
                    <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
                    <tr><td><b><?php echo $LDThuocTayY; ?></b></td></tr>
                    <tr bgColor="#ffffff"><td><blockquote class="style2">
                            <?php
                                    echo '<a href="report/khochan_thuoc_catalog.php'.URL_APPEND.'&type=tayy">'.$LDMedicineList.'</a><p>';		
                                    echo '<a href="report/khochan_baocaothuoc.php'.URL_APPEND.'&type=tayy">'.$LDReportImportExport_Medicine.'</a><p>';
                                    echo '<a href="report/khochan_thuoc_kiemke.php'.URL_APPEND.'&type=tayy">'.$LDPharmaReportInventory.'</a><p>';
									echo '<a href="report/khochan_thuoc_thekho.php'.URL_APPEND.'&type=tayy" >'.$LDThekho.'</a><p>';
                            ?>
                    </blockquote></td></tr>
                    </table>
            </td>
            
            <td width="33%" align="center" valign="top">		<!-- VTYT -->
                    <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
                    <tr><td><b><?php echo $LDMedipot; ?></b></td></tr>
                            <tr bgColor="#ffffff"><td><blockquote class="style2">
                            <?php	
                                    echo '<a href="report/khochan_vtyt_catalog.php'.URL_APPEND.'">'.$LDMedipotCatalogue.'</a><p>';
                                    echo '<a href="report/khochan_baocaovtyt.php'.URL_APPEND.'">'.$LDReportImportExport_Medipot.'</a><p>';
                                    echo '<a href="report/khochan_vtyt_kiemke.php'.URL_APPEND.'">'.$LDPharmaReportInventory.'</a><p>';
									echo '<a href="report/khochan_vtyt_thekho.php'.URL_APPEND.'">'.$LDThekho.'</a><p>';
                            ?>
                    </blockquote></td></tr>
                    </table>
            </td>
            
            <td width="33%" align="center" valign="top">		<!-- Hoa chat -->
                    <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
                    <tr>
                        <td><b><?php echo $LDChemical; ?></b></td>
                    </tr>
                    <tr bgColor="#ffffff"><td><blockquote class="style2">
                        <?php
                            echo '<a href="report/khochan_hoachat_catalog.php'.URL_APPEND.'">'.$LDChemicalList.'</a><p>';		
                            echo '<a href="report/khochan_baocaohoachat.php'.URL_APPEND.'">'.$LDReportImportExport_Medicine.'</a><p>';
                            echo '<a href="report/khochan_hoachat_kiemke.php'.URL_APPEND.'">'.$LDPharmaReportInventory.'</a><p>';	
							echo '<a href="report/khochan_hoachat_thekho.php'.URL_APPEND.'" >'.$LDThekho.'</a><p>';							
                        ?>
                    </blockquote></td></tr>
                    </table>
            </td>
        </tr>
		<tr>
            <td width="33%" align="center">		<!-- Thuoc -->
                    <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
                    <tr><td><b><?php echo $LDThuocDongY; ?></b></td></tr>
                    <tr bgColor="#ffffff"><td><blockquote class="style2">
                            <?php
                                    echo '<a href="report/khochan_thuoc_catalog.php'.URL_APPEND.'&type=dongy">'.$LDMedicineList.'</a><p>';		
                                    echo '<a href="report/khochan_baocaothuoc.php'.URL_APPEND.'&type=dongy">'.$LDReportImportExport_Medicine.'</a><p>';
                                    echo '<a href="report/khochan_thuoc_kiemke.php'.URL_APPEND.'&type=dongy">'.$LDPharmaReportInventory.'</a><p>';
									echo '<a href="report/khochan_thuoc_thekho.php'.URL_APPEND.'&type=dongy" >'.$LDThekho.'</a><p>';
                            ?>
                    </blockquote></td></tr>
                    </table>
            </td>
			<td></td>
			<td></td>
		</tr>
		<tr>
            <td width="33%" align="center">		<!-- Cac bao cao khac ve su dung thuoc -->
                    <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="95%" >
                    <tr><td><b><?php echo $LDCacBaoCaoKhac; ?></b></td></tr>
                    <tr bgColor="#ffffff"><td><blockquote class="style2">
                            <?php
                                    echo '<a href="report/khochan_sudungthuoc_tamthan.php'.URL_APPEND.'">'.$LDSuDungThuocTamThan.'</a><p>';		
                                    echo '<a href="report/khochan_sudungthuoc_gaynghien.php'.URL_APPEND.'">'.$LDSuDungThuocGayNghien.'</a><p>';
                                    echo '<a href="report/khochan_sudungthuoc_nhomthuoc.php'.URL_APPEND.'">'.$LDSuDungThuocNhomThuoc.'</a><p>';
									echo '<a href="report/khochan_sudungthuoc_khangsinh.php'.URL_APPEND.'" >'.$LDSuDungThuocKhangSinh.'</a><p>';
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

