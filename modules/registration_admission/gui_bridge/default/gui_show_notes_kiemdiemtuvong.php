<script language="javascript">
    function printOut() {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/chuyenvien/Bienbankiemdiemtuvong.php<?php echo URL_APPEND ?>&pid=<?php echo $_SESSION['sess_pid']; ?>&enc_nr=<?php echo $encounter_nr ?>&nr=<?php echo $nr ?>";
	testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
</script>
<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
    <tr bgcolor="#f6f6f6">
        <td <?php echo $tbg; ?>></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LD['sovaovien_notes']; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDShortNotes1; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDDept; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LD['time_kiemtuvong']; ?></b></FONT></td>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDTime; ?></b></FONT></td>
        <?php
            if($parent_admit){
        ?>
        <td <?php echo $tbg; ?> align="center"><FONT color="darkred"><b><?php echo $LDEdit; ?></b></FONT></td>
        <?php
            }
        ?>
    </tr>
<?php
    while($row=$pregs->FetchRow()){
	$this_file=basename(__FILE__);
?>
    <tr bgcolor="#fefefe">
        <td>
            <a href="javascript:printOut()"><img <?php echo createComIcon($root_path,'printer.png','0','',TRUE); ?>></a>
        </td>
        <td>
            <?php echo $row['sovaovien_notes']; ?>
        </td>
        <td>
            <FONT color="darkblue">
                <b>
                <?php 
                    echo $row['ketluan_notes'];
                ?>
                </b>
            </FONT>
        </td>
        <td>
            <?php 
                $sql1="SELECT d.LD_var
                        FROM care_encounter AS e,
                            care_person AS p,
                            care_department AS d
                        WHERE p.pid=".$_SESSION['sess_pid']."
                            AND	p.pid=e.pid
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
                echo @formatDate2Local($row['date_kiemtuvong'],$date_format); 
            ?>
        </td>
        <td>
            <?php 
                echo $row['time_kiemtuvong']; 
            ?>
        </td>
        <?php
            if($parent_admit){
        ?>
        <td align="center">
            <a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='. strtr($target,' ','+').'&mode=new&allow_update=1&nr='.$row['nr'];?>">
                <img src="../../gui/img/common/default/update2.gif"/>
            </a>
        </td>
        <?php
            }
        ?>
    </tr>    
<?php
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
?>
</table>

