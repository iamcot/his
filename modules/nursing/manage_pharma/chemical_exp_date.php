<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    $lang_tables=array('departments.php');
    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

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
		
    $thisfile= basename(__FILE__);
    $breakfile='../nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
    $fileforward= 'chemical_destroy_chemical.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
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
    $smarty->assign('sToolbarTitle',$LDInventoryCabinetChemical.' :: '.$TitleTable1);

    # href for help button
//    $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaperChemical')");

    # Window bar title
    $smarty->assign('Name',$LDInventoryCabinetChemical);

    # Onload Javascript code
    $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

    # Hide the return button
    $smarty->assign('pbBack',FALSE);

    ob_start();
?>
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
	for(i=0; i < document.listmedform.groupcb.length; i++){
            if(document.listmedform.groupcb[i].checked){
                itemid = itemid +'_'+ document.listmedform.groupcb[i].value;	
                total++;
            }
	}
	document.listmedform.action="<?php echo $fileforward; ?>&maxid="+total+"&itemid="+itemid;
	document.listmedform.submit();
    }
    function checkUncheckAll(checkAllState)
    {
	var cbGroup = document.listmedform.groupcb;
	if(cbGroup.length > 0)
            for (i = 0; i < cbGroup.length; i++)
                cbGroup[i].checked = checkAllState.checked;
    }
</script>
<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp); 

    include_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'include/care_api_classes/class_product.php');
    $Product=new Product();

    $datetime=date("Y-m-d G:i:s");

    ob_start();
?>
<form name="listmedform" method="POST"  onSubmit="return chkform(this)">
    <center>
        <table cellSpacing="1" cellPadding="3" border="0" width="90%">
            <tr>
                <th align="left">
                    <font size="3" color="#5f88be">
                        <?php echo $LDDept.': '.$deptname; ?>
                    </font>
                </th>
                <td align="right" rowspan="2">
                    <table>
                        <tr>
                            <td>
                                <input type="text" id="search" value="" size="30" />
                            </td>
                            <td>
                                <input type="image" <?php echo createComIcon($root_path,'Search.png','0','',TRUE) ?> OnClick="searchValue()" >
                            </td>
                        </tr>
                            <tr>
                                <td colspan="2">
                                    <FONT size="1">
                                        <?php echo $LDsearchExpGuide; ?>
                                    </FONT>
                                </td>
                            </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th align="left" valign="top">
                    <font size="2" color="#85A4CD">
                        <?php echo $LDWard.': '.$wardname; ?>
                    </font>
                </th>
            </tr>
        </table>
        <br/>
        <table border="0" cellSpacing="1" cellPadding="3" width="95%" bgColor="#C3C3C3">
            <tr bgColor="#EDF1F4">
                <th align="center">
                    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);" />
                </th>
                <th><?php echo $LDSTT; ?></th>
                <th><?php echo $LDChemicalID; ?></th>
                <th><?php echo $LDChemicalName1; ?></th>
                <th><?php echo $LDUnit; ?></th>	
                <th><?php echo $LDLotID; ?></th>
                <th><?php echo $LDExpDate; ?></th>
                <th><?php echo $LDCabinetChemicalSum; ?></th>	
            </tr>																																
            <?php 
                if ($search==''){
                    //current_page, number_items_per_page, total_items, total_pages, location=1,2,3
                    $number_items_per_page=20; 	$condition='';

                    if ($listItem = $Product->SearchExpCabinetChemical($dept_nr, $ward_nr, $condition)){		
                            $total_items = $listItem->RecordCount();
                    } else $total_items =0;

                    $total_pages=ceil($total_items/$number_items_per_page);

                    include_once('../include/inc_issuepaper_listdepot_splitpage.php');

                    if ($total_pages>1)
                        $listItem = $Product->ShowExpCabinetChemical($dept_nr, $ward_nr, $current_page, $number_items_per_page);

                }else{
                    if (strrpos($search,'/') || strrpos($search,'-')){
                        $search = formatDate2STD($search,'dd/mm/yyyy');
                        $condition=" AND exp_date LIKE '".$search."%' ";
                    }
                    elseif (is_numeric($search))
                        $condition=" AND product_lot_id LIKE '%".$search."%' ";
                    else
                        $condition=" AND product_name LIKE '%".$search."%' ";


                    $listItem = $Product->SearchExpCabinetChemical($dept_nr, $ward_nr, $condition);
                    $breakfile = $thisfile.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
                }

                if(is_object($listItem)){
                    $sTemp='';
                    for ($i=0;$i<$listItem->RecordCount();$i++)
                    {
                        $rowItem = $listItem->FetchRow();
                        /*
                                Hoa chat da het han: do
                                Hoa chat con 1 thang het han: hong
                                Hoa chat con 6 thang het han: vang
                                Hoa chat con 1 nam het han: xam nhat
                                Hoa chat tren 1 nam het han: trang
                        */	
						$valid_year  = $rowItem['yearexp'] - date("Y");
						$valid_month = $rowItem['monthexp'] - date("m");
						$valid_day   = $rowItem['dayexp'] - date("d");

                        if ($valid_year!=0){
                            if ($valid_year>0) $bgc="#ffffff";
                            else $bgc="#FF0000";
                        }
                        elseif ($valid_month==0){
                            if($valid_day>0) $bgc="#FFAAFF";
                            else $bgc="#FF0000";
                        }
                        else{
                            switch($valid_month){
                                case ($valid_month<0) : $bgc="#FF0000";	break; //do				
                                case ($valid_month<=1) : $bgc="#FFAAFF"; break;	//hong
                                case ($valid_month<=6) : $bgc="#FFFF00"; break;	//vang
                                case ($valid_month<=12) : $bgc="#D2D2D2"; break;	//xam nhat
                                case ($valid_month>12) : $bgc="#ffffff"; break;	//trang
                                default: $bgc="#ffffff"; break;
                            }

                        }

                        $sTemp=$sTemp.'<tr bgColor="'.$bgc.'" >
                                            <td align="center"><input type="checkbox" name="groupcb" value="'.$rowItem['available_product_id'].'.'.$rowItem['ward_nr'].'" /></td>
                                            <td align="center">'.($i+1).'</td>
                                            <td align="center">'.$rowItem['product_encoder'].'</td>
                                            <td>'.$rowItem['product_name'].'</td>
                                            <td align="center">'.$rowItem['unit_name_of_chemical'].'</td>
                                            <td align="center">'.$rowItem['product_lot_id'].'</td>
                                            <td align="center">'.$rowItem['dayexp'].'/'.$rowItem['monthexp'].'/'.$rowItem['yearexp'].'</td>
                                            <td align="center">'.$rowItem['available_number'].'</td>
                                        </tr>';
                    }
                    echo $sTemp;

                }else{
                    $sTemp='<tr bgColor="#ffffff"><td colspan="8">'.$LDItemNotFound.'</td></tr>';
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
                    <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
                    <input type="hidden" name="current_page" value="<?php echo $current_page; ?>" />
                    <input type="hidden" name="number_items_per_page" value="<?php echo $number_items_per_page; ?>" />
                    <input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>" />
                </td>
            </tr>
            <tr>
                <td align="center">
                    &nbsp;
                    <br/>
                    <img <?php echo createLDImgSrc($root_path,'destroy.gif','0','middle'); ?> OnClick="sendItem()" />
                    <br/>&nbsp;
                </td></tr>
            <tr>
                <td align="right">
                    <table border="0" cellpadding="2" cellspacing="1" bgColor="#B4B4B4">
                        <tr>
                            <td style="background-color:#FF0000;width:10px;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDHSDGuide1; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color:#FFAAFF;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDHSDGuide2; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color:#FFFF00;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDHSDGuide3; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color:#D2D2D2;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDHSDGuide4; ?></td>
                        </tr>
                        <tr>
                            <td style="background-color:#ffffff;">&nbsp;</td>
                            <td bgColor="#ffffff"><?php echo $LDHSDGuide5; ?></td>
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

    $smarty->assign('breakfile',$breakfile);

    # Show main frame
    $smarty->display('common/mainframe.tpl');

?>

