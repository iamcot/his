<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');
require($root_path . 'include/core/inc_environment_global.php');

$lang_tables = array('departments.php');
define('LANG_FILE', 'pharma.php');
define('NO_2LEVEL_CHK', 1);
require_once($root_path . 'include/core/inc_front_chain_lang.php');
require_once($root_path . 'include/care_api_classes/class_ward.php');
$Ward = new Ward;
//Get info of current department, ward
if ($ward_nr != '') {
    if ($wardinfo = $Ward->getWardInfo($ward_nr)) {
        $wardname = $wardinfo['name'];
        $deptname = ($$wardinfo['LD_var']);
        $dept_nr = $wardinfo['dept_nr'];
    }
} elseif ($dept_nr != '') {
    require_once($root_path . 'include/care_api_classes/class_department.php');
    $Dept = new Department;
    if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
        $deptname = ($$deptinfo['LD_var']);
        $wardname = $LDAllWard;
    }
}
//require_once($root_path . 'include/care_api_classes/class_listgroup.php');
//$ListGroup =new ListGroup;
//if ($pharma_group_id != '') {
//    if ($typeinfo = $ListGroup->listPharmaGroupMedicine($pharma_group_id)) {
//        $typeid = $typeinfo['pharma_group_id'];
//    }
//}

$thisfile = basename(__FILE__);
$breakfile = '../nursing-manage-medicine.php' . URL_APPEND . '&dept_nr=' . $dept_nr . '&ward_nr=' . $ward_nr;
$fileissue = '../nursing-issuepaper-depot.php' . URL_APPEND . '&dept_nr=' . $dept_nr . '&ward_nr=' . $ward_nr;
$filereturn = 'medicine_return_medicine.php' . URL_APPEND . '&dept_nr=' . $dept_nr . '&ward_nr=' . $ward_nr;
$urlsearch = $thisfile . URL_APPEND . '&dept_nr=' . $dept_nr . '&ward_nr=' . $ward_nr;
//$urlsearchMedicine= $thisfile . URL_APPEND . '&dept_nr=' . $dept_nr . '&ward_nr=' . $ward_nr . '&pharma_group_id=' . $pharma_group_id;

# Start Smarty templating here
/**
 * LOAD Smarty
 */
# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path . 'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Title in the toolbar
$smarty->assign('sToolbarTitle', $LDInventoryCabinet . ' :: ' . $LDMedicineList);

# href for help button
$smarty->assign('pbHelp', "javascript:gethelp('submenu1.php','$LDIssuePaper')");

# Window bar title
$smarty->assign('title', $LDInventoryCabinet);

# Onload Javascript code
$smarty->assign('sOnLoadJs', "if (window.focus) window.focus();");

# Hide the return button
$smarty->assign('pbBack', FALSE);

ob_start();
?>
<style type="text/css">

</style>
<script language="javascript">
    function searchValue() {
        var search = document.getElementById('search').value;
        document.listmedform.action = "<?php echo $urlsearch;?>&search=" + search;
        document.listmedform.submit();
    }
    function searchMedicine() {
        var searchMe = document.getElementById('searchMedicine').value;
        document.listmedform.action = "<?php echo $urlsearch;?>&searchMedicine=" + searchMe;
        if(document.getElementsByName("type"))
        {
            if(document.)
        }
        document.listmedform.submit();
    }
    function checkM(radio)
    {
//        var radioGroup = document.listmedform.typeMedicine;
//        var radioGroup=document.getElementsByName("typeMedicine");
//        var len= radio.length;
//        for(i=0;i<len;i++)
//        {
//            if(radio[i].checked)
//            {
                alert(radio);
//            }
//        }
//        document.listmedform.submit();
    }
    function sortUp() {
        document.getElementById('mode').value = 'sort_up';
        document.listmedform.action = "<?php echo $urlsearch;?>&mode=sort_up";
        document.listmedform.submit();
    }
    function sortDown() {
        document.getElementById('mode').value = 'sort_down';
        document.listmedform.action = "<?php echo $urlsearch;?>&mode=sort_down";
        document.listmedform.submit();
    }

    function chkform(d) {
        document.listmedform.action = "";
        document.listmedform.submit();
    }
    function issueItem() {
        var i, total = 0;
        var itemid = '';
        for (i = 0; i < document.listmedform.groupcb.length; i++) {
            if (document.listmedform.groupcb[i].checked) {
                itemid = itemid + '_' + document.listmedform.groupcb[i].value;
                total++;
            }
        }

        document.listmedform.action = "<?php echo $fileissue; ?>&maxid=" + total + "&itemid=" + itemid;
        document.listmedform.submit();
    }
    function returnItem() {
        var i, total = 0;
        var itemid = '';
        for (i = 0; i < document.listmedform.groupcb.length; i++) {
            if (document.listmedform.groupcb[i].checked) {
                itemid = itemid + '_' + document.listmedform.groupcb[i].value;
                total++;
            }
        }

        document.listmedform.action = "<?php echo $filereturn; ?>&maxid=" + total + "&itemid=" + itemid;
        document.listmedform.submit();
    }
    function checkUncheckAll(checkAllState) {
        var cbGroup = document.listmedform.groupcb;
        if (cbGroup.length > 0)
            for (i = 0; i < cbGroup.length; i++)
                cbGroup[i].checked = checkAllState.checked;
    }
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $sTemp);

include_once($root_path . 'include/core/inc_date_format_functions.php');
require_once($root_path . 'include/care_api_classes/class_product.php');
$Product = new Product();

$datetime = date("Y-m-d G:i:s");

if (!isset($mode))
    $mode = 'sort_up';

if ($mode == 'sort_up') {
    $picup = 'arrow_up_blue.gif';
    $picdown = 'arrow_down_gray.gif';
    $updown = '';
} else {
    $picup = 'arrow_up_gray.gif';
    $picdown = 'arrow_down_blue.gif';
    $updown = ' DESC ';
}

ob_start();
?>
<form name="listmedform" method="POST" onSubmit="return chkform(this)">
    <center>
        <table cellSpacing="1" cellPadding="3" border="0" width="90%">
            <tr>
                <th align="left"><font size="3" color="#5f88be"><?php echo $LDDept . ': ' . $deptname; ?></th>

                <td align="left" rowspan="2">
                <table>
                    <tr>
                        <td> Thuốc:     <br>
                            <input type="radio" name="typeMedicine" onclick="checkM(this)"   value="tatca">Tất cả
                            <!--                            --><?php //echo $LDTatCaMedicine ;  ?><!--    -->

                            <input type="radio" name="typeMedicine" onclick="checkM(this)"      value="tamthan">Hướng Tâm Thần
                            <!--                            --><?php //echo $LDTamThanMedicine ;  ?>   <br>
                        </td>
                        <td><a href="javascript:searchMedicine()"><input
                                    type="image" <?php echo createComIcon($root_path, 'Search.png', '0', '', TRUE) ?>
                                    onclick=""></a></td>
                    </tr>
                </table>

                <td align="right" rowspan="2">
                    <table>
                        <tr>
                            <td>
                                <select name="typeput" class="input1">
                                    <option
                                        value="-1" <?php echo((isset($typeput) && $typeput == -1) ? 'selected' : '') ?>>
                                        Tất cả
                                    </option>
                                    <option
                                        value="0" <?php echo((!isset($typeput) || $typeput == 0) ? 'selected' : '') ?>><?php echo $LDBH; ?></option>
                                    <option
                                        value="1" <?php echo((isset($typeput) && $typeput == 1) ? 'selected' : '') ?>><?php echo $LDNoBH; ?></option>
                                    <option
                                        value="2" <?php echo((isset($typeput) && $typeput == 2) ? 'selected' : '') ?>><?php echo $LDCBTC; ?></option>
                                </select>
                            </td>
                            <td><input type="text" id="search" value="" size="30"></td>
                            <td><a href="javascript:searchValue()"><input
                                        type="image" <?php echo createComIcon($root_path, 'Search.png', '0', '', TRUE) ?>
                                        onclick=""></a></td>
                        </tr>
                        <tr>
                            <td colspan="2"><FONT size="1"><?php echo $LDsearchExpGuide; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" valign="top"><font size="2" color="#85A4CD"><?php echo $LDWard . ': ' . $wardname; ?>
                </th>
            </tr>
        </table>
        <p>
        <table border="0" cellSpacing="1" cellPadding="3" width="95%" bgColor="#C3C3C3">
            <tr bgColor="#EDF1F4">
                <th align="center"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"></th>
                <th><?php echo $LDSTT; ?></th>
<!--                <th>--><?php //echo $LDMedicineID1; ?><!--</th>-->
                <th><?php echo $LDMedicineName; ?></th>
                <th><?php echo $LDUnit; ?></th>
                <th>Loại</th>
<!--                <th>--><?php //echo $LDLotID; ?><!--</th>-->
                <th><?php echo $LDExpDate; ?></th>
<!--                <th>--><?php //echo $LDWard; ?><!--</th>-->
                <th><?php echo $LDCabinetMedicineSum; ?>&nbsp;
                    <a href="javascript:sortUp()"><input
                            type="image" <?php echo createComIcon($root_path, $picup, '0', '', TRUE) ?> onclick=""
                            title="<?php echo $LDSortUp; ?>"></a>&nbsp;<a href="javascript:sortDown()"><input
                            type="image" <?php echo createComIcon($root_path, $picdown, '0', '', TRUE) ?> onclick=""
                            title="<?php echo $LDSortDown; ?>"></a></th>

<!--                <th>--><?php //echo $LDInitNumber; ?><!--</th>-->
                <th>Nhận về</th>
            </tr>
            <?php
            if (!isset($typeput)) $typeput = 0;
            $condition = '';
            if ($typeput > -1)
                $condition = " AND taikhoa.typeput = $typeput ";
            if ($search == '') {
                //current_page, number_items_per_page, total_items, total_pages, location=1,2,3
                //$number_items_per_page = 20;


//                if ($listItem = $Product->SearchCatalogCabinet($dept_nr, $ward_nr, $condition, $updown)) {
//                    $total_items = $listItem->RecordCount();
//                } else $total_items = 0;
                // $total_items = $Product->countExpCabinet($dept_nr, $ward_nr, $condition, $updown);
                //var_dump($total_items);
                //$total_pages = ceil($total_items / $number_items_per_page);

                //include_once('kiemke_splitpage.php');

                // if ($total_pages > 1)
                $listItem = $Product->ShowCatalogCabinet($dept_nr, $ward_nr, $condition, $current_page, $number_items_per_page, $updown);

            } else {
                if (strrpos($search, '/') || strrpos($search, '-')) {
                    $search = formatDate2STD($search, 'dd/mm/yyyy');
                    $condition .= " AND exp_date LIKE '" . $search . "%' ";
                } else
                    if (is_numeric($search))
                        $condition .= " AND product_lot_id LIKE '%" . $search . "%' ";
                    else
                        $condition .= " AND product_name LIKE '%" . $search . "%' ";


                $listItem = $Product->SearchCatalogCabinet($dept_nr, $ward_nr, $condition, $updown);
                $breakfile = $thisfile . URL_APPEND . '&dept_nr=' . $dept_nr . '&ward_nr=' . $ward_nr;
            }

            if (is_object($listItem)) {
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

                    $expdate = formatDate2Local($rowItem['exp_date'], 'dd/mm/yyyy');
//<td align="center">' . $rowItem['product_encoder'] . '</td>
                    //<td align="center">' . $rowItem['product_lot_id'] . '</td>
                    //<td align="center">' . $rowItem['init_number'] . '</td>
                    //<td>' . $rowIssue['ward_nr'] . '</td>
                    if($khu != $rowIssue['ward_nr'])
                    {
                        $sTemp .= '<tr><td colspan="7"><center><b>'.$rowIssue['ward_nr'].'</b></center></td></tr>';
                        $khu = $rowIssue['ward_nr'];
                    }
                    $sTemp = $sTemp . '<tr bgColor="' . $bgc . '" >
								<td align="center"><input type="checkbox" name="groupcb" value="' . $rowItem['available_product_id'] . '"></td>
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
            <tr>
                <td align="center">&nbsp;<p>
                        <a href="javascript:issueItem();"><img <?php echo createLDImgSrc($root_path, 'issue.gif', '0', 'middle'); ?> ></a><img
                            src="<?php echo $root_path; ?>gui/img/common/default/pixel.gif" border="0" width="30"
                            height="16">
                        <a href="javascript:returnItem();"><img <?php echo createLDImgSrc($root_path, 'return.gif', '0', 'middle'); ?> ></a>

                    <p>&nbsp;</td>
            </tr>
            <tr>
                <td align="right">
                    <table border="0" cellpadding="2" cellspacing="1" bgColor="#B4B4B4">
                        <tr>
                            <td style="background-color:#D47FFF;width:10px;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDListGuide1; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color:#AAFFFF;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDListGuide2; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color:#ffffff;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDListGuide3; ?></td>
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
$smarty->assign('sMainFrameBlockData', $sTemp);

$smarty->assign('breakfile', $breakfile);

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

