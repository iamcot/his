<?php 
    if($issue_show['type']){
            $typeissue=$LDSumIssue;
    }
    else{
            $typeissue=$LDDepotIssue;
    }
    if($issue_show['status_finish']){
            $tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
    else{
            $tempfinish=$LDNotYet; $tempfinish1='warn.gif';}

    $date1 = formatDate2Local($issue_show['date_time_create'],'dd/mm/yyyy');
    $time1 = substr($issue_show['date_time_create'],-8);
	
	
    //Get general info
    require_once($root_path.'include/care_api_classes/class_ward.php');
    if(!isset($ward_obj)) $ward_obj=new Ward;

    $wardid = $issue_show['ward_nr'];
    if ($wardid)
        $wardname = $ward_obj->WardName($wardid);	//Khu phong 
        
    //Get general info
    require_once($root_path.'include/care_api_classes/class_department.php');
    if(!isset($dept_obj)) $dept_obj=new Department;
    $deptid = $issue_show['dept_nr'];
    if ($deptid)
        $deptname = $dept_obj->checkNameDept($deptid);	//Khu phong
    
	//Su nghiep, BHYT, CBTC
switch ($issue_show['typeput']){
	case 0: $usefor=$LDBHYT; break;
	case 1: $usefor=$LDSuNghiep; break;
	case 2: $usefor=$LDCBTC; break;
	default: $usefor=$LDSuNghiep; break;
}

?>
&nbsp;
<br>
<table border=0 cellpadding=3 width="98%">
    <tr bgcolor="#f6f6f6">
        <td width="20%">
            <FONT SIZE=-1  color="#000066">
                <?php echo $LDIssueId; ?>
            </FONT>
        </td>
        <td width="35%">
            <FONT SIZE=-1>
                <?php echo $issue_id; ?>
            </FONT>
        </td>
        <td width="15%">
            <FONT SIZE=-1  color="#000066">
                <?php echo $LDGotDrug; ?>
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
            <FONT SIZE=-1 >
                <b><?php echo $$deptname[LD_var]; ?></b><?php echo ' - '.$wardname; ?>
            </FONT>
        </td>
        <td width="20%">
            <FONT SIZE=-1  color="#000066">
                <?php echo $LDUseFor; ?>
            </FONT>
        </td>
        <td width="35%">
            <FONT SIZE=-1 >
                <b><?php echo $usefor; ?></b>
            </FONT>
        </td>        
    </tr> 
    <tr bgcolor="#f6f6f6">
        <td>
            <FONT SIZE=-1  color="#000066">
                <?php echo $LDIssueBy; ?>
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1  >
                <?php echo $issue_show['nurse']; ?>
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1  color="#000066">
                <?php echo $LDDate; ?>
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1  >
                <?php echo $date1.' '.$time1; ?>
            </FONT>
        </td>
    </tr> 
    <tr bgcolor="#f6f6f6">
        <td width="15%">
            <FONT SIZE=-1  color="#000066">
                <?php echo $LDTypeIssue; ?>
            </FONT>
        </td>
        <td width="30%">
            <FONT SIZE=-1  >
                <?php  echo $typeissue; ?>
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1  color="#000066">
                <?php echo $LDNoteCreator; ?>
            </FONT>
        </td>
        <td colspan="3">
            <FONT SIZE=-1  >
                <?php echo $issue_show['note']; ?>
            </FONT>
        </td>
    </tr> 
	
	 <!-- Them thuoc vao toa thuoc -->
    <tr>
        <td colspan="4" align="center"><br>
            <table bgcolor="#EEEEEE" width="100%" cellpadding="2">
                <tr bgColor="#E1E1E1" >
                    <td width="4%" align="center" rowspan="2">
                        <u>
                            <?php echo $LDSTT ?>
                        </u>
                    </td>
                    <td width="30%" align="center" rowspan="2">
                        <u>
                            <?php echo $LDChemicalName ?>
                        </u>
                    </td>
                    <td width="10%" align="center" rowspan="2">
                        <u>
                            <?php echo $LDUnit ?>
                        </u>
                    </td>
                    <td width="9%" align="center" rowspan="2">
                        <u>
                            <?php echo $LDRequest ?>
                        </u>
                    </td>
                    <td width="8%" align="center" rowspan="2">
                        <u>
                            <?php echo $LDPlus ?>
                        </u>
                    </td>
					<td width="10%" align="center" rowspan="2"><font color="#E41B17"><u><?php echo $LDTonKhoChan ?></u></font></td>
                    <td width="20%" align="center" colspan="2">
                        <u>
                            <?php echo $LDNumberOf ?>
                        </u>
                    </td>
                    <td width="10%" align="center" rowspan="2">
                        <u>
                            <?php echo $LDNote ?>
                        </u>
                    </td>
                </tr>
                <tr bgColor="#E1E1E1" >
                    <td align="center">
                        <u>
                            <?php echo $LDTotal ?>
                        </u>
                    </td>
                    <td align="center">
                        <u>
                            <?php echo $LDIssue ?>
                        </u>
                    </td>
                </tr>

                <?php 
                    for($i=1;$i<=$chemical_count;$i++) { 			
                        $rowIssue = $chemical_in_pres->FetchRow();								
						$tonkhochan =  $IssuePaper->searchChemicalInMainSub($rowIssue['product_encoder'], $issue_show['typeput']);	
                        echo '<tr bgColor="#ffffff" >
                                        <td align="center" bgColor="#ffffff">'.$i.'.<input type="hidden" name="chemical_nr['.$i.']" value="'.$rowIssue['nr'].'"></td>
                                        <td bgColor="#ffffff"><b>'.$rowIssue['product_name'].'</b></td>
                                        <td align="center" bgColor="#ffffff"><b>'.$rowIssue['units'].'</b></td>
                                        <td align="right" bgColor="#ffffff">'.number_format($rowIssue['sumpres']).'</td>
                                        <td align="right" bgColor="#ffffff">'.number_format($rowIssue['plus']).'</td>
										<td align="right" bgColor="#ffffff"><font color="#E41B17">'.number_format($tonkhochan).'</font></td>
                                        <td align="right" bgColor="#ffffff"><b>'.number_format($rowIssue['number_request']).'</b></td>
                                        <td align="center" bgColor="#ffffff"><input name="receive['.$i.']" type="text" size=3 value="'.$rowIssue['number_request'].'"></td>
                                        <td align="center" bgColor="#ffffff">'.$rowIssue['note'].'</td>
                                </tr>';
                        } 
                ?>
            </table>
            &nbsp;<br>
        </td>
    </tr>	
	  <!-- Loi dan bac si & button -->
    <tr bgcolor="#f6f6f6">
        <td>
            <FONT SIZE=-1 color="#000066"><?php echo $LDIssueUser; ?>
        </td>
        <td>
            <FONT SIZE=-1 >
            <input name="issue_user" type="text" size="24" value="<?php echo $_SESSION['sess_user_name']; ?>" style="border-color:white;border-style:solid;" readonly />
        </td>
        <td align="top">
            <FONT SIZE=-1 color="#000066">
                <?php echo $LDReceiveUser; ?>
            </FONT>
        </td>
        <td>
            <FONT SIZE=-1 >
                <input name="receive_user" type="text" size="24" value="<?php echo $issue_show['nurse']; ?>" />
            </FONT>
        </td>
    </tr> 
    <tr bgcolor="#f6f6f6">
        <td valign="top">
            <FONT SIZE=-1 color="#000066">
                <?php echo $LDNoteIssue; ?>
            </FONT>
        </td>
        <td colspan="3">
            <FONT SIZE=-1 >
                <textarea name="noteissue" cols="27" rows="3" wrap="physical" ></textarea>
            </FONT>
        </td>
    </tr> 
    <tr bgcolor="#f6f6f6">
        <td colspan="4">&nbsp;<p></td>
    </tr>
 </table>