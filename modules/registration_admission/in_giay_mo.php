<?php
$sql="SELECT * FROM care_test_request_".$db_request_table." WHERE batch_nr='".$batch_nr."' AND (status='pending' OR status='draff')";
$sql1="SELECT pharma_prescription_info.prescription_type AS prescription_type
        FROM care_pharma_prescription_info AS pharma_prescription_info
        WHERE pharma_prescription_info.dept_nr=".$dept_nr." AND pharma_prescription_info.ward_nr=".$ward_nr." AND pharma_prescription_info.encounter_nr=".$pn."";
if($ergebnis=$db->Execute($sql))
{
    if($editable_rows=$ergebnis->RecordCount())
    {
       $stored_request=$ergebnis->FetchRow();        
        if($ergebnis1=$db->Execute($sql1)){
            $thanhtoan=$ergebnis1->FetchRow();
        }        
       $edit_form=1;
     }
 }
if($edit){
?>
<form name="form_test_request" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">
<?php
/* If in edit mode display the control buttons */
    $controls_table_width=700;
}
elseif(!$read_form && !$no_proc_assist){
?>

<table border=0>
  <tr>
    <td valign="bottom"><img <?php echo createComIcon($root_path,'angle_down_l.gif','0') ?>></td>
    <td>
        <font color="#000099" SIZE=3  FACE="verdana,Arial">
            <b><?php echo $LDPlsSelectPatientFirst ?></b>
        </font>
    </td>
    <td>
        <img <?php echo createMascot($root_path,'mascot1_l.gif','0','absmiddle') ?>/>
    </td>
  </tr>
</table>
<?php
}
?>
   
   <!--  outermost table creating form border -->
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
    <tr>
        <td>	
            <table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
                <tr>
                    <td>
                        <table   cellpadding=0 cellspacing=1 border=0 width=700>
                            <tr  valign="top">
                                <td  bgcolor="#ffffff" rowspan=2>
                                <?php
                                    if($edit){
                                        echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
                                    }elseif($pn==''){
                                        $searchmask_bgcolor="#f3f3f3";
                                        include($root_path.'modules/laboratory/includes/inc_test_request_searchmask.php');
                                        }
                                    ?>
                                </td>
                                <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10>
                                    <div   class=fva2_ml10>
                                        <font size=5 color="#0000ff"><b><?php echo $LDRequestOP ?></b></font>
                                    </div>
                                <br/>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="<?php echo $bgc1 ?>" align="right" valign="bottom">	 
                                <?php
                                    echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
                                    echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>";
                                ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td  valign="top" colspan=2 >
                                    <table border=0 cellpadding=1 cellspacing=1 width=100%>
                                        <tr>
                                            <td colspan=4><hr></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">  
                                <td>                                    
                                </td>
                                <td align="center">
                                    <div class=fva2_ml10>
                                        <?php
                                            echo '<b>'.$LDPayment.'</b>';
                                        ?>
                                        </br>
                                    </div>
                                    <?php
                                        if($edit_form || $read_form){
                                            switch ($thanhtoan['prescription_type']){
                                                case '0397':
                                                    echo $LDPrescriptioninternal.'<p>';
                                                    break;
                                                case '0398':
                                                    echo $LDPrescriptionInsurance.'<p>';
                                                    break;
                                                default:
                                                    break;
                                            }                                                
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td align="center">
                                    <div class=fva2_ml10>
                                        <b>
                                            <?php echo $LDClinicalInfo ?>:
                                        </b>
                                        <br>
                                    </div>
                                    <?php 
                                        if($edit_form || $read_form) 
                                            echo stripslashes($stored_request['clinical_info']); 
                                    ?>
                                </td>
                            <td align="center">
                                <div class=fva2_ml10>
                                    <b>
                                        <?php echo $LDDocOP;?>
                                    </b>
                                </div>
                                <?php if($edit_form || $read_form) echo stripslashes($stored_request['person_surgery']) ?>
                            </td>
                            </tr>	
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td align="center">
                                    <div class=fva2_ml10>
                                        <b>
                                            <?php echo $LDReqTestOP ?>:
                                        </b>
                                        <br>
                                    </div>
                                    <?php 
                                        if($edit_form || $read_form) 
                                            echo stripslashes($stored_request['test_request']) 
                                    ?>
                                </td>
                                <td align="center">
                                    <div class=fva2_ml10>
                                        <b>
                                            <?php echo $LDMethodOP.':  ';?>
                                        </b>                                        
                                    </div>
                                    <?php 
                                        if($edit_form || $read_form){
                                            echo stripslashes($stored_request['method_op']);
                                        }else{
                                            echo $LDNO1;
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align="center">
                                    <div class=fva2_ml10>
                                        <b>
                                            <?php echo $LDLevelMethodOP.':';?>
                                        </b> 
                                        </br>
                                    </div>
                                    <?php 
                                        if($edit_form || $read_form){
                                            echo stripslashes($stored_request['level_method']);
                                        }else{
                                            echo $LDNO1;
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td colspan=2 align="right">
                                    <div class=fva2_ml10>
                                        <?php 
                                            echo '<b>'.$LDDate .":  </b>";
                                            echo date('d-m-Y',strtotime($stored_request['date_request'])).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';				  
                                            echo '<b>'.$LDRequestingDoc ?>:</b>&nbsp;
                                        <?php 
                                            if($edit_form || $read_form){ 
                                                echo $stored_request['send_doctor'];
                                            }else{
                                                echo $_SESSION['sess_user_name'];                                        
                                            }
                                        ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <br>
                                </td>
                            </tr>
                        </table> 
                    </td>
                </tr>
            </table>	
        </td>
    </tr>
</table>
</form>
