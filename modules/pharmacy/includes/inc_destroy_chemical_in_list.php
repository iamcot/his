<?php
    if($report_show['status_finish']){
	$tempfinish=$LDFinish; 
        $tempfinish1='check-r.gif';        
    }
    else{
	$tempfinish=$LDNotYet; 
        $tempfinish1='warn.gif';        
    }
    $date1 = formatDate2Local($report_show['date_time_create'],'dd/mm/yyyy');
    $time1 = substr($report_show['date_time_create'],-8);

    //Get info of current department, ward
    $ward_nr=$report_show['ward_nr'];
    $dept_nr=$report_show['dept_nr'];
    if ($ward_nr!=''  && $ward_nr!=0){
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
        }
    }elseif ($dept_nr!=''){
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
        }
    }


?>
&nbsp;
<br/>
<table border=0 cellpadding=3 width="98%">
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
                <?php echo $LDDestroy; ?>
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
                <?php echo $LDDepartment; ?>
            </FONT>
        </td>
        <td width="35%">
            <FONT SIZE=-1  >
                <?php echo $deptname; ?>
            </FONT>
        </td>
        <td><FONT SIZE=-1  color="#000066"><?php echo $LDDate; ?></td>
        <td><FONT SIZE=-1  ><?php echo $date1.' '.$time1; ?></td>
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
                <?php echo $LDCreatorName; ?>
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
                <?php echo $LDNote; ?>
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
        <td colspan="4" align="center"><br>
            <table bgcolor="#EEEEEE" width="100%" cellpadding="2">
                <tr bgColor="#E1E1E1" >
                <td width="4%" align="center">
                    <u><?php echo $LDSTT ?></u>
                </td>
                <td width="10%" align="center">
                    <u><?php echo $LDChemicalID; ?></u>
                </td>
                <td width="24%" align="center">
                    <u><?php echo $LDChemicalName; ?></u>
                </td>
                <td width="9%" align="center">
                    <u><?php echo $LDUnit ?></u>
                </td>
                <td width="9%" align="center">
                    <u><?php echo $LDLotID1 ?></u>
                </td>
                <td width="10%" align="center">
                    <u><?php echo $LDExpDate1; ?></u>
                </td>
                <td width="9%" align="center">
                    <u><?php echo $LDCost ?></u>
                </td>
                <td width="9%" align="center">
                    <u><?php echo $LDNumberOf ?></u>
                </td>
                <td width="9%" align="center">
                    <u><?php echo $LDTotalCost ?></u>
                </td>
                <td width="10%" align="center">
                    <u><?php echo $LDNote ?></u>
                </td>
                </tr>
                <?php 
                    $totalcost=0;
                    for($i=1;$i<=$chemical_count;$i++) { 			
                        $rowReport = $chemical_in_report->FetchRow();
                        $rowReport['exp_date']=formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy');
                        echo '<tr bgColor="#ffffff" >
                                <td align="center" bgColor="#ffffff">'.$i.'.<input type="hidden" name="chemical_nr['.$i.']" value="'.$rowReport['nr'].'"></td>
                                <td>'.$rowReport['product_encoder'].'</td>
                                <td><b>'.$rowReport['product_name'].'</b></td>
                                <td align="center" bgColor="#ffffff"><b>'.$rowReport['unit_name_of_chemical'].'</b></td>
                                <td align="center" bgColor="#ffffff">'.$rowReport['product_lot_id'].'</td>
                                <td align="center" bgColor="#ffffff">'.$rowReport['exp_date'].'</td>
                                <td align="center" bgColor="#ffffff">'.$rowReport['cost'].'</td>
                                <td align="center" bgColor="#ffffff"><b><input name="receive['.$i.']" type="text" size=3 value="'.$rowReport['number'].'"   style="border-color:white;border-style:solid;" readonly ></b></td>
                                <td align="center" bgColor="#ffffff">'.($rowReport['cost']*$rowReport['number']).'</td>
                                <td align="center" bgColor="#ffffff">'.$rowReport['note'].'</td>
                                </tr>';
                        $totalcost = $totalcost + $rowReport['cost']*$rowReport['number'];
                    } 
                ?>
            </table>
            &nbsp;
            <br/>
        </td>
    </tr>

    <!-- Loi dan bac si & button -->
    <tr bgcolor="#f6f6f6">
        <td>
            <FONT SIZE=-1 color="#000066">
                <?php echo $LDUserAccept; ?>
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1 >
                <input name="user_accept" type="text" size="24" value="<?php echo $_SESSION['sess_user_name']; ?>" style="border-color:white;border-style:solid;" readonly />
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1 color="#000066">
                <?php echo $LDTotalNumber; ?>
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1>
                <?php echo $totalcost; ?>
            </FONT>
        </td>
    </tr> 
    <tr bgcolor="#f6f6f6">
        <td colspan="4">&nbsp;
		<input type="hidden" name="typeput" value="<?php echo $report_show['typeput']; ?>"><p></td>
    </tr>
</table>