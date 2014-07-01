<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

$thisfile= basename(__FILE__);
$breakfile='nursing-issuepaper-selectward.php'.URL_APPEND;

//Get info of current department, ward
if ($ward_nr!=''){
    require_once($root_path.'include/care_api_classes/class_ward.php');
    $Ward = new Ward;
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

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in the toolbar
$smarty->assign('sToolbarTitle',$LDCabinetMedicine.' :: '.$LDManageMedicine);

# href for help button
$smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

$smarty->assign('breakfile',$breakfile);

# Window bar title
$smarty->assign('Name',$LDCabinetMedicine);

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
<table cellSpacing="1" cellPadding="3" border="0" width="90%">
    <tr>
        <th align="left"><font size="3" color="#5f88be">
                <?php echo $LDDept.': '.$deptname; ?>
        </th>
    </tr>
    <tr>
        <th align="left"><font size="2" color="#85A4CD">
                <?php echo $LDWard.': '.$wardname; ?>
        </th>
    </tr>
</table>
<br/>
<table border="0" width="95%">
<tr>
    <td align="center">
        <font color="#800000">
            <b>
                <?php echo $LDMedicine; ?>
            </b>
    </td>
    <td align="center">
        <font color="#800000">
            <b>
                <?php echo $LDMedipot; ?>
            </b>
    </td>
    <td align="center">
        <font color="#800000">
            <b>
                <?php echo $LDChemical; ?>
            </b>
    </td>
</tr>
<tr>
    <td width="33%" align="center">		<!-- Thuoc -->
        <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="90%" >
            <tr>
                <td>
                    <b><?php echo $LDImport_Export_Medicine; ?></b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="nursing-issuepaper-pres.php'.URL_APPEND.'&target=pres&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDIssuePaper.'</a><p>';
                        if ($ward_nr=='')
                            echo '<a href="manage_pharma/medicine_distribute_medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDDistributeMedicine.'</a><p>';
                        echo '<a href="manage_pharma/medicine_return_medicine.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDReturnMedicine.'</a><p>';
                        echo '<a href="manage_pharma/medicine_destroy_med.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDDestroyMedicine.'</a><p>';
                        echo '<a href="manage_pharma/medicine_use_patient.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDPhatThuocChoBN.'</a><p>';
                        echo '<a href="manage_pharma/medicine_use_ward.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDKeKhaiSuDungThuocChoKhoa.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        <?php echo $LDInventoryCabinet; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="manage_pharma/medicine_list_catalogue.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDMedicineList.'</a><p>';
                        echo '<a href="manage_pharma/medicine_exp_date.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDExpDate.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        <?php echo $LDUseMedicineReport; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="manage_pharma/medicine_report_15day.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LD15DayReport.'</a><p>';
                        echo '<a href="manage_pharma/medicine_report_month.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDMonthReport.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
        </table>
    </td>
    <td width="33%" align="center">		<!-- VTYT -->
        <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="90%" >
            <tr>
                <td>
                    <b>
                        <?php echo $LDImport_Export_Medipot; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="nursing-issuepaper-medipot-pres.php'.URL_APPEND.'&target=pres&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDIssueMedipot.'</a><p>';
                        if ($ward_nr=='')
                            echo '<a href="manage_pharma/medipot_distribute_medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDDistributeMedipot.'</a><p>';
                        echo '<a href="manage_pharma/medipot_return_medipot.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDReturnMedipot.'</a><p>';
                        echo '<a href="manage_pharma/medipot_destroy_medipot.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDDestroyMedipot.'</a><p>';
                        echo '<a href="manage_pharma/medipot_use_patient.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDPhatVTYTChoBN.'</a><p>';
                        echo '<a href="manage_pharma/medipot_use_ward.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDKeKhaiSuDungVTYTChoKhoa.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        <?php echo $LDInventory_Medipot; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="manage_pharma/medipot_list_catalogue.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDMedipotCatalogueTxt.'</a><p>';
                        echo '<a href="manage_pharma/medipot_exp_date.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDExpDate.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        <?php echo $LDUseMedipotReport; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="manage_pharma/medipot_report_15day.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LD15DayReport.'</a><p>';
                        echo '<a href="manage_pharma/medipot_report_month.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDMonthReport.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
        </table>
    </td>
    <td width="33%" align="center">		<!-- Hoa chat -->
        <table bgColor="#E1E1E1" cellpadding="5" cellspacing="1" width="90%" >
            <tr>
                <td>
                    <b>
                        <?php echo $LDImport_Export_Chemical; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="nursing-issuepaper-chemical-pres.php'.URL_APPEND.'&target=pres&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDIssuePaperChemical.'</a><p>';
                        if ($ward_nr=='')
                            echo '<a href="manage_pharma/chemical_distribute_chemical.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDDistributeChemical.'</a><p>';
                        echo '<a href="manage_pharma/chemical_return_chemical.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDReturnChemical.'</a><p>';
                        echo '<a href="manage_pharma/chemical_destroy_chemical.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDDestroyChemical.'</a><p>';
                        echo '<a href="manage_pharma/chemical_use_patient.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDPhatHCChoBN.'</a><p>';
                        echo '<a href="manage_pharma/chemical_use_ward.php'.URL_APPEND.'&target=new&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDKeKhaiSuDungHCChoKhoa.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        <?php echo $LDInventoryCabinetChemical; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="manage_pharma/chemical_list_catalogue.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDChemicalList.'</a><p>';
                        echo '<a href="manage_pharma/chemical_exp_date.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDExpDate.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        <?php echo $LDUseChemicalReport; ?>
                    </b>
                </td>
            </tr>
            <tr bgColor="#ffffff">
                <td>
                    <blockquote class="style2">
                        <?php
                        echo '<a href="manage_pharma/chemical_report_15day.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LD15DayReport.'</a><p>';
                        echo '<a href="manage_pharma/chemical_report_month.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDMonthReport.'</a>';
                        ?>
                    </blockquote>
                </td>
            </tr>
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

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

