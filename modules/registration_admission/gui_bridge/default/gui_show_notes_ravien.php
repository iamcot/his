<script language="javascript">
    function printOut(date,time) {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/giaytokhac/Giayravien.php<?php echo URL_APPEND ?>&enc_nr=<?php echo $_SESSION['sess_en'] ?>&date_s="+date+"&time_s="+time;
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
</script>
<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
    <tr bgcolor="#f6f6f6">
        <td <?php echo $tbg; ?>></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDDept; ?></b></FONT></td>        
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDDate1; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDChandoan; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDLoidan; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDEdit; ?></b></FONT></td>
    </tr>
<?php
    $this_file=basename(__FILE__);
    if($rows){
        $i=4;
        while($pregrancy=$pregs->FetchRow()){
            if($i%4==0){
                $date=$pregrancy['date'];
                $time=$pregrancy['time'];
                $nr_dad=$pregrancy['nr'];
                $pregs1=&$obj->_getNotesKhac("notes.encounter_nr='$pn' AND notes.type_nr=types.nr AND types.sort_nr=31 AND notes.date='$date' AND notes.time='$time'", "ORDER BY notes.type_nr DESC");
                $date_array=array();
                if($pregs1){
                    while($row1=$pregs1->FetchRow()){
                        $nr=$row1['type_nr'];
                        $date_array[$nr]=$row1['notes'];
                    }
                }  
?>
    <tr bgcolor="#fefefe">
        <td>
            <a href="javascript:printOut('<?php echo $date;?>','<?php echo $time;?>')"><img <?php echo createComIcon($root_path,'printer.png','0','',TRUE); ?>></a>
        </td>
        <td>
            <?php 
                if($stat=&$enc_obj->AllStatus($current_encounter)){
                    $enc_status=$stat->FetchRow();
                }
                include_once($root_path.'include/care_api_classes/class_department.php');
                require_once($root_path.'language/vi/lang_vi_departments.php');
                $dept_obj=new Department;
                $current_dept_LDvar=$dept_obj->LDvar($enc_status['current_dept_nr']);
                if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);                
                if($deptName!=null)
                    echo $deptName;
            ?>
        </td>
        <td align="center">
            <?php 
                echo @formatDate2Local(substr($date_array[33], 0,10),$date_format).'<br/>'.substr($date_array[33], 11,2).' giờ '.substr($date_array[33], 14,2).' phút '.substr($date_array[33], 17,2).' giây'; 
            ?>
        </td>
        <td align="center">
            <?php 
                echo $date_array[34]; 
            ?>
        </td>
        <td align="center">
            <FONT color="darkblue">
                <b>
                <?php 
                    echo $date_array[35];
                ?>
                </b>
            </FONT>
        </td>
        <td align="center">
            <a href="<?php echo $thisfile.URL_APPEND.'&current_encounter='.$_SESSION['sess_en'].'&target='. strtr($target,' ','+').'&mode=new&allow_update=1&date='.$date.'&time='.$time.'&nr='.$nr_dad;?>">
                <img src="../../gui/img/common/default/update2.gif"/>
            </a>
        </td>
    </tr>    
<?php
        }
        $i++;
    }
    if($parent_admit){ 
?>
    <tr bgcolor="#fefefe">
        <td colspan="7">
            <a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='. $target.'&mode=new';?>">
                <img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?> />
                <?php
                     echo $LDEnterNewRecord
                ?>
            </a>            
        </td>        
    </tr>
<?php
        }    
    }
?>
</table>

