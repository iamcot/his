<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    $root_path='../../../';
    $top_dir='modules/nursing/include/';
    require_once($root_path.'include/core/inc_environment_global.php');
    /**
     * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
     * GNU General Public License
     * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
     * elpidio@care2x.org, 
     *
     * See the file "copy_notice.txt" for the licence notice
     */
    $lang='vi';
    define('NO_CHAIN',1);
    $lang_tables=array('departments.php');
    define('LANG_FILE','pharma.php');
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    include_once($root_path.'include/care_api_classes/class_cabinet_pharma.php');
?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php
    html_rtl ( $lang );
?>
<HEAD>
<?php
    echo setCharSet ();
?>
<TITLE><?php
	echo $LDDestroyChemical; ?>
</TITLE>

<style type="text/css">
    table {
	font-family: verdana, arial, tahoma;
	font-size: 12px;
	font-weight: normal;
	color: black;
    }
</style>
<script language="javascript">
function printOut()
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/phieuthanhlythuoc.php<?php echo URL_APPEND; ?>&report_id=<?php echo $report_id; ?>&type=chemical";
	testprintpdf=window.open(urlholder,"PhieuTraLaiThuoc","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
</script>
</HEAD>

<BODY>

<?php
    if(!isset($Cabinet)) $Cabinet = new CabinetPharma;
    
    $report_show = $Cabinet->getDestroyChemicalInfo($report_id);
    $chemical_in_pres = $Cabinet->getDetailDestroyChemicalInfo($report_id);

    if (!$report_show || !$chemical_in_pres){
        echo $LDItemNotFound;
        exit;
    }

    if($report_show['status_finish']){
        $tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
    else{
        $tempfinish=$LDNotYet; $tempfinish1='warn.gif';}

    $date1 = formatDate2Local($report_show['date_time_create'],'dd/mm/yyyy');
    $time1 = substr($report_show['date_time_create'],-8);

    $ward_nr = $report_show['ward_nr'];
    $dept_nr = $report_show['dept_nr'];
//Get info of current department, ward
    if ($ward_nr!='' && $ward_nr!='0'){
        require_once($root_path.'include/care_api_classes/class_ward.php');
        $Ward = new Ward;
        if($wardinfo = $Ward->getWardInfo($ward_nr)) {
            $wardname = $wardinfo['name'];
            $deptname = ($$wardinfo['LD_var']);
            $dept_nr = $wardinfo['dept_nr'];
        }
    } else if ($dept_nr!=''){
        require_once($root_path.'include/care_api_classes/class_department.php');
        $Dept = new Department;
        if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
            $deptname = ($$deptinfo['LD_var']);
            $wardname = $LDAllWard;
        }
    }	
?>


    <table border=0 cellpadding=3 width="98%">
        <tr>
            <td colspan="4" align="center">
                <b> <?php echo $LDDestroyChemical; ?> </b> 
                <br/> &nbsp; 
            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td width="20%">
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDIssueId; ?>
                </FONT>
            </td>
            <td width="35%">
                <FONT SIZE=-1  >
                    <?php echo $report_id; ?>
                </FONT>
            </td>
            <td width="15%">
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDStatus; ?>
                </FONT>
            </td>
            <td width="30%">
                <FONT SIZE=-1  >
                    <?php echo $tempfinish.' ';?> 
                    <img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?> /> 
                </FONT>
            </td>
        </tr> 
        <tr bgcolor="#f6f6f6">
            <td width="20%">
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDDept; ?>
                </FONT>
            </td>
            <td width="35%">
                <FONT SIZE=-1  >
                    <?php echo $deptname; ?>
                </FONT>
            </td>
            <td width="15%">
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDDate;  ?>
                </FONT>
            </td>
            <td width="30%">
                <FONT SIZE=-1  >
                    <?php  echo $date1.' '.$time1; ?>
                </FONT>
            </td>
        </tr> 
        <tr bgcolor="#f6f6f6">
            <td>
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDWard; ?>
                </FONT>
            </td>
            <td>
                <FONT SIZE=-1  >
                    <?php echo $wardname; ?>
                </FONT>
            </td>
            <td>
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDIssueBy; ?>
                </FONT>
            </td>
            <td>
                <FONT SIZE=-1  >
                    <?php echo $report_show['doctor']; ?>
                </FONT>
            </td>
        </tr>
        <tr bgcolor="#f6f6f6">
            <td>
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDTypePutIn1; ?>
                </FONT>
            </td>
            <td>
                <FONT SIZE=-1  color="#000066">
                    <?php 
                        switch ($report_show['typeput']){
                            case 0:
                                echo $LDBH;
                                break;
                            default: 
                                echo $LDNoBH;
                                break;
                        }


                    ?>
                </FONT>
            </td>
            <td>
                <FONT SIZE=-1  color="#000066">
                    <?php echo $LDNoteOfCreator; ?>
                </FONT>
            </td>
            <td colspan="3">
                <FONT SIZE=-1  >
                    <?php echo $report_show['note']; ?>
                </FONT>
            </td>
        </tr> 

         <!-- Them thuoc vao toa thuoc -->
        <tr>
            <td colspan="4" align="center">
                <br/>
                    <table bgcolor="#EEEEEE" width="100%" cellpadding="3">
                        <tr bgColor="#E1E1E1" >
                            <td width="4%" align="center">
                                <u><?php echo $LDSTT ?></u>
                            </td>
                            <td width="12%" align="center">
                                <u><?php echo $LDChemicalID ?></u>
                            </td>
                            <td width="30%" align="center">
                                <u><?php echo $LDChemicalName1 ?></u>
                            </td>
                            <td width="10%" align="center">
                                <u><?php echo $LDUnit ?></u>
                            </td>
                            <td width="9%" align="center">
                                <u><?php echo $LDLotID1 ?></u>
                            </td>
                            <td width="9%" align="center">
                                <u><?php echo $LDExpDate1 ?></u>
                            </td>
                            <td width="9%" align="center">
                                <u><?php echo $LDNumberOf ?></u>
                            </td>
                            <td width="9%" align="center">
                                <u><?php echo $LDCost ?></u>
                            </td>
                            <td width="14%" align="center">
                                <u><?php echo $LDNote ?></u>
                            </td>
                        </tr>

                <?php 
                    $chemical_count = $chemical_in_pres->RecordCount();
                    for($i=1;$i<=$chemical_count;$i++) { 			
                            $rowIssue = $chemical_in_pres->FetchRow();								

                    echo '<tr bgColor="#ffffff" >
                            <td align="center" bgColor="#ffffff">'.$i.'.</td>
                            <td bgColor="#ffffff">'.$rowIssue['product_encoder'].'</td>
                            <td bgColor="#ffffff">'.$rowIssue['product_name'].'</td>
                            <td align="center" bgColor="#ffffff">'.$rowIssue['unit_name_of_chemical'].'</td>
                            <td align="center" bgColor="#ffffff">'.$rowIssue['product_lot_id'].'</td>
                            <td align="center" bgColor="#ffffff">'.(formatDate2Local($rowIssue['exp_date'],'dd/mm/yyyy')).'</td>
                            <td align="center" bgColor="#ffffff">'.number_format($rowIssue['number']).'</td>
                            <td align="center" bgColor="#ffffff">'.number_format($rowIssue['cost']).'</td>
                            <td align="center" bgColor="#ffffff">'.$rowIssue['note'].'</td>
                            </tr>';
                    } ?>
                    </table>
                    &nbsp;<br>
            </td>
        </tr>

          <!-- Loi dan bac si & button -->
        <tr bgcolor="#f6f6f6">
            <td align="top">
                <FONT SIZE=-1 color="#000066">
                        <?php echo $LDUserAccept; ?>
                </FONT>
            </td>
            <td colspan="3">
                <FONT SIZE=-1 >
                        <?php echo $report_show['user_accept']; ?>
                </FONT>
            </td>
        </tr> 
		<tr>
			<td colspan="4" align="center">
			&nbsp;<br>
			<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>
			</td>
		</tr>
    </table>
</BODY>
</HTML>
