<?php
    if($rows){
	# Transfer data into array
	while($row=$pregs->FetchRow()){
            $pregbuf[$row['encounter_nr']]=$row;
            $buffer=$row['encounter_nr'];
	}
	$this_enc_preg=false;
	if(!isset($show_preg_enc)||!$show_preg_enc){
            if($parent_admit) {
                    $show_preg_enc=$_SESSION['sess_en'];
                    $this_enc_preg=true;
            }elseif($rows==1){
                    $show_preg_enc=$buffer;
            }
        }
	
?>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<?php
	$show_details=false;
	if($show_preg_enc&&isset($pregbuf[$show_preg_enc])){
            $show_details=true;
            # Get the field names
            $array=array('nr','pid','encounter_nr','nr_of_fetuses','child_encounter_nr','da_niemmac','delivery_mode','lydo', 'tsm_khongrach', 'tsm_rach', 'tsm_cat', 'phuongphapkhau', 'somuikhau', 'tc_khongrach', 'tc_rach', 'status', 'history', 'modify_id', 'modify_time', 'create_id', 'create_time');
            $obj->setRefArray($array);
            $fields=&$obj->coreFieldNames();
            # If not this encounter�s pregnancy, show warn notice
		
?>
    <tr>
	<td colspan=6>
            <table border=0 cellpadding=1 cellspacing=1 width=100% class="frame">
<?php
		if(!$parent_admit){
?>

                <tr bgcolor="#fefefe">
                    <td <?php echo $tbg; ?>><FONT color="#ff0000"><b><?php echo $LDEncounterNr; ?></b></font></td>
                    <td <?php echo $tbg; ?> style="padding-left:15px;"><b><FONT color="#ff0000"><?php echo $pregbuf[$show_preg_enc]['encounter_nr'] ?></font></b></td>
                </tr>

<?php
		}

		while(list($z,$x)=each($fields)){
                    if($x=='status') break;
                    if($x=='nr'||$x=='encounter_nr'||$x=='pid'||(empty($pregbuf[$show_preg_enc][$x]) && $x!='nr_of_fetuses')) continue; 
                    $t1='<tr bgcolor="#fefefe">
                            <td width="40%">
                                <FONT color="#006600">
                                    <b>';
                    $t2='</b></FONT></td>';
                    switch($x){
                        case 'tsm_khongrach':
                        case 'tsm_rach':
                        case 'tsm_cat':
                            echo $t1.$LD['tangsinhmon'].$t2;
                            break;
                        case 'tc_khongrach':
                        case 'tc_rach':
                            echo $t1.$LD['TC'].$t2;
                            break;
                        case 'nr_of_fetuses':                        
                            echo '<tr bgcolor="yellow">
                                        <td>
                                            <FONT color="red">
                                                <b>';
                            echo $LD['nr_of_fetuses'].$t2;
                            break;
                        case 'child_encounter_nr':
                            echo '<tr bgcolor="yellow">
                                        <td>
                                            <FONT color="red">
                                                <b>';
                            echo $LD['child_encounter_nr'].$t2;
                            break;
                        default:
                            echo $t1.$LD[$x].$t2;
                            break;
                    }                                                                                 
?>
                <td style="padding-left:15px;">
                        <?php 
                            switch($x){
                                    case 'child_encounter_nr':
                                        if($pregbuf[$show_preg_enc][$x]){
                                            $child_nr=explode(";",$pregbuf[$show_preg_enc][$x]);
                                            $i=1;
                                            echo '<table class="frame" width="100%">';
                                            while($i<=sizeof($child_nr)-1){
                                                echo '<tr bgcolor="#fefefe"><td width="40%">';
                                                echo $baby.'  '.$i.'</td><td>';
                                                echo '<font size=3 color="blue"><u><a href="show_birthdetail.php'.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&child_nr_chose='.$child_nr[$i].'">'.$child_nr[$i].'</a></u></font>';
                                                echo '</td></tr>';
                                                $i++;
                                            }
                                            echo '</table>';
                                        }                                        
					break;
                                    case 'delivery_mode':
                                            $buf=&$obj->getDeliveryMode($pregbuf[$show_preg_enc]['delivery_mode']);
                                            if(isset($$buf['LD_var']) && $$buf['LD_var']) echo $$buf['LD_var'];
                                                    else echo $buf['name'];
                                            break;
                                    case 'tsm_khongrach':
                                    case 'tsm_rach':
                                    case 'tsm_cat':
                                            if($pregbuf[$show_preg_enc]['tsm_khongrach']) 
                                                echo $LD['tsm_khongrach'];
                                            if($pregbuf[$show_preg_enc]['tsm_rach'])
                                                echo $LD['tsm_rach'];
                                            if($pregbuf[$show_preg_enc]['tsm_cat'])
                                                echo $LD['tsm_cat'];
                                            break;
                                    case 'tc_khongrach':
                                    case 'tc_rach':
                                            if($pregbuf[$show_preg_enc]['tc_khongrach']) 
                                                echo $LD['tc_khongrach'];
                                            if($pregbuf[$show_preg_enc]['tc_rach'])
                                                echo $LD['tc_rach'];
                                            break;
                                    case 'nr_of_fetuses':
                                            if($pregbuf[$show_preg_enc][$x]) 
                                                echo '<FONT color="red"><b>'.$pregbuf[$show_preg_enc][$x].'  '.$baby_record.'</b></FONT>';
                                            else
                                                echo '<FONT color="red"><b>'.$LDUnknown.'</b></FONT>'; 
                                            break;                                    
                                    default: 
                                            echo $pregbuf[$show_preg_enc][$x];
                                            break;
                            }
                        ?>
                    </td>
                </tr>                

<?php

		}
                if($pregbuf[$show_preg_enc]['modify_id']){
?>
                    <tr bgcolor="#fefefe">
                        <td style="padding-left:15px;">
                            <FONT color="#006600">
                            <b>
                            <?php 
                                echo $LD['modify_id'];
                            ?>
                            </b>
                            </FONT>
                        </td>
                        <td style="padding-left:15px;"> 
                            <?php echo $pregbuf[$show_preg_enc]['modify_id']; ?>
                        </td>
                    </tr>
                    <tr bgcolor="#fefefe">
                        <td style="padding-left:15px;">
                            <FONT color="#006600">
                            <b>
                            <?php 
                                echo $LDTimeEdit;
                            ?>
                            </b>
                            </FONT>
                        </td>                        
                        <td style="padding-left:15px;"> 
                            <table>
                                <tr>
                                    <td>
                                        <?php 
                                            $t=explode(' ', $pregbuf[$show_preg_enc]['modify_time']);
                                            echo $t[1]; 
                                        ?>
                                    </td>
                                    <td width="100px"></td>
                                    <td>
                                        <FONT color="#006600">
                                        <b>
                                        <?php echo $LDDate; ?>
                                        </b>
                                        </FONT>
                                    </td>
                                    <td>
                                        <?php echo formatDate2Local($t[0],$date_format); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>                        
<?php                    
                }
?>
                <tr bgcolor="#fefefe">
                    <td>
                        <FONT color="#006600">
                        <b>
                        <?php 
                            echo $LD['docu_by'];
                        ?>
                        </b>
                        </FONT>
                    </td>
                    <td style="padding-left:15px;">
                        <?php
                            echo $pregbuf[$show_preg_enc]['create_id'];
                        ?>
                    </td>
                </tr>
            </table>
	
	</td>
    </tr>
<?php
}

	if($parent_admit&&$edit&&($show_preg_enc==$_SESSION['sess_en']||$no_enc_preg)){
?>
    <tr valign="top">
        <td colspan=2>&nbsp;<br>
            <img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?> />
            <a href="<?php 
                    echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='. strtr($target,' ','+').'&mode=new&allow_update=1';
                    if($this_enc_preg) echo '&rec_nr='.$pregbuf[$show_preg_enc]['nr'];
            ?>"> 
<?php 
            if($no_enc_preg) echo $LDEnterNewRecord;
                            else echo $LD['update_preg_details']; 
?>
            </a>&nbsp;<p>
        </td>
    </tr>
<?php
	}
	if($show_details&&$rows>1){
?>
    <tr bgcolor="#f6f6f6" valign="top">
        <td colspan=6>
            <img <?php echo createComIcon($root_path,'dwnarrowgrnlrg.gif','absmiddle') ?> /> 
            <font size=3><?php echo $LDOtherRecords; ?></font>
        </td>
    </tr>

<?php	
	}

    if($rows>1||($no_enc_preg)){
	
?>
    <tr bgcolor="#f6f6f6" valign="top">
        <td <?php echo $tbg; ?>>
            <FONT color="#000066">&nbsp;</FONT>
        </td>
        <td <?php echo $tbg; ?>>
            <FONT color="#000066">
                <?php echo $LDEncounterNr; ?>
            </FONT>
        </td>
        <td <?php echo $tbg; ?>>
            <FONT color="#000066">
                <?php echo $LDDelivery.' '.$LDDate; ?>
            </FONT>
        </td>
        <td <?php echo $tbg; ?>>
            <FONT color="#000066">
                <?php echo $LDDelivery.' '.$LDMode; ?>
            </FONT>
        </td>
        <td <?php echo $tbg; ?>>
            <FONT color="#000066">
                <?php echo $LDOutcome; ?>
            </FONT>
        </td>
        <td <?php echo $tbg; ?>>
            <FONT color="#000066">
                <?php echo $LDNrOfFetus; ?>
            </FONT>
        </td>
    </tr>
<?php
        while(list($x,$v)=each($pregbuf)){
                # Do not list this encounter�s pregnancy in the admission module
                if($x==$show_preg_enc) continue;
?>
    <tr bgcolor="#fefefe" valign="top">
        <td>
<?php
            if($parent_admit&&($v['encounter_nr']==$_SESSION['sess_en']))	
                echo '<img '.createComIcon($root_path,'info3.gif','0').' />';
            else echo '&nbsp;';
?>
        </td>
        <td>
            <a href="<?php echo $thisfile.URL_APPEND.'&target='.$target.'&show_preg_enc='.$v['encounter_nr'] ?>">
                <?php echo $v['encounter_nr']; ?>
            </a>
        </td>
        <td>
            <?php echo @formatDate2Local($v['delivery_date'],$date_format); ?>
        </td>
        <td>
            <?php echo $v['delivery_mode']; ?>
        </td>
        <td>
            <?php echo $v['outcome']; ?>
        </td>
        <td>
            <?php echo $v['nr_of_fetuses']; ?>
        </td>
    </tr>

<?php
        }
    }
?>
</table>
<?php
}
?>
