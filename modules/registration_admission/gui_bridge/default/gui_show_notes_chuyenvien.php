<script language="javascript">
    function printOut() {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/chuyenvien/GiayChuyenVien.php<?php echo URL_APPEND ?>&enc_nr=<?php echo $_SESSION['sess_en'] ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
</script>
<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
    <tr bgcolor="#f6f6f6">
        <td <?php echo $tbg; ?>></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDLydo; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDDiagnosis; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDDept; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDDateKiem; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDTime; ?></b></FONT></td>
    </tr>
<?php
        while($encounter=$obj->FetchRow()){
            if($encounter['encounter_class_nr']==1){
                    # Get ward name
                    include_once($root_path.'include/care_api_classes/class_ward.php');
                    $ward_obj=new Ward;
                    $current_ward_name=$ward_obj->WardName($encounter['current_ward_nr']);
            }elseif($encounter['encounter_class_nr']==2){
                    # Get ward name
                    include_once($root_path.'include/care_api_classes/class_department.php');
                    $dept_obj=new Department;
                    //$current_dept_name=$dept_obj->FormalName($current_dept_nr);
                    $current_dept_LDvar=$dept_obj->LDvar($encounter['current_dept_nr']);
                    if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $current_dept_name=$$current_dept_LDvar;
                            else $current_dept_name=$dept_obj->FormalName($encounter['current_dept_nr']);
            }
            $this_file=basename(__FILE__);
?>
    <tr bgcolor="#fefefe">
        <td>
            <a href="javascript:printOut()"><img <?php echo createComIcon($root_path,'printer.png','0','',TRUE); ?>></a>
        </td>
        <td>
            <?php 
                $type_nr=$enc_obj->getLocation($encounter_nr);
                $name_type=$enc_obj->getDischargeTypesData_3($type_nr['discharge_type_nr']);
                echo $$name_type['LD_var'];
            ?>
        </td>
        <td>
            <?php 
                echo $encounter['referrer_diagnosis'];
            ?>
        </td>
        <td>
            <?php 
                $sql1="SELECT d.LD_var
                        FROM care_encounter AS e,
                            care_person AS p,
                            care_department AS d
                        WHERE p.pid=e.pid
                            AND e.encounter_nr=".$_SESSION['sess_en']."
                            AND e.current_dept_nr=d.nr";
                if($result1=$db->Execute($sql1)){
                    $rows2=$result1->FetchRow();
                }
                $current_dept_nr=$rows2['LD_var'];
                require_once($root_path.'language/vi/lang_vi_departments.php');
                if($current_dept_nr!=null)
                    echo $$current_dept_nr;
            ?>
        </td>
        <td>
            <?php
                echo date("d",strtotime($encounter['discharge_date']))."/".date("m",strtotime($encounter['discharge_date']))."/".date("Y",strtotime($encounter['discharge_date'])); 
            ?>
        </td>
        <td>
            <?php 
                echo date("G",strtotime($encounter['discharge_time']))." giờ ".date("i",strtotime($encounter['discharge_time']))." phút"; 
            ?>
        </td>
    </tr>    
<?php
        }
?>
</table>

