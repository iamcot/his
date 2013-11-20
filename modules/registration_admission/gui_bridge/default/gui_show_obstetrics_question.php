<?php
    if($rows>0){
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
            $array=array('nr','pid','encounter_nr','batdauthaykinh','tuoithaykinh','tinhchatkinh','luongkinh','chuki','songaykinh','kinhcuoitu','daubung','time','namlaychong','tuoilaychong','namhetkinh','tuoihetkinh','benhphukhoa', 'tienthai','status','history', 'create_id', 'create_time', 'modify_id', 'modify_time');
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
                    <td <?php echo $tbg; ?>><FONT color="#ff0000"><?php echo $pregbuf[$show_preg_enc]['encounter_nr'] ?></font></td>
                </tr>

            <?php
		}
                require_once($root_path.'include/care_api_classes/class_person.php');
                $person= new Person();
                $info= $person->getAllInfoArray($_SESSION['sess_pid']);

                require_once($root_path.'include/care_api_classes/class_encounter.php');
                $obj1=new Encounter;
                $status=$obj1->loadEncounterData1($_SESSION['sess_en'],1);
		while(list($z,$x)=each($fields)){                    
                    if($info && $status){
                        echo '<tr bgcolor="#fefefe"><td style="padding-left:15px;"><FONT color="#006600"><b>';
                        echo $LDQuatrinhbenhly;
                        echo '</b></FONT></td>';
                        echo '<td style="padding-left:15px;">'.$status['quatrinhbenhly'];
                        echo '</tr>';
                        echo '<tr bgcolor="#fefefe"><td style="padding-left:15px;"><FONT color="#006600"><b>';
                        echo $LDTSBenhCN;
                        echo '</b></FONT></td>';
                        echo '<td style="padding-left:15px;">'.$info['tiensubenhcanhan'];
                        echo '</tr>';
                        echo '<tr bgcolor="#fefefe"><td style="padding-left:15px;"><FONT color="#006600"><b>';
                        echo $LDTSBenhGD;
                        echo '</b></FONT></td>';
                        echo '<td style="padding-left:15px;">'.$info['tiensubenhgiadinh'];
                        echo '</tr>';
                        $info='';
                        $status='';
                        continue;
                    }                    
                    
                    if($x=='status') break;
                    if($x=='nr'||$x=='pid'||$x=='tuoithaykinh'||$x=='tuoilaychong'||$x=='tuoihetkinh'||$x=='status'||$x=='encounter_nr'||$x=='create_id'||$x=='create_time'||$x=='modify_id'||$x=='modify_time'|| empty($pregbuf[$show_preg_enc][$x])) continue;
                    
            ?>
            <tr bgcolor="#fefefe">
                    <td width="30%"  style="padding-left:15px;">
                        <FONT color="#006600"><b>
                                <?php
                                    switch($x){
                                        case 'time':
                                        case 'tienthai':
                                            echo $LD[$x][0];
                                            break;
                                        default:
                                            echo $LD[$x];
                                            break;
                                    } 
                                ?>
                            </b>
                        </FONT>
                    </td>
                    <td style="padding-left:15px;">
                    <?php
                        switch($x){
                            case 'chuki':
                                echo $pregbuf[$show_preg_enc][$x].'   '.$LDday1;
                                break;
                            case 'daubung':
                                echo $LDYes_s;
                                break;
                            case 'time': 
                                $time=explode(';',$pregbuf[$show_preg_enc][$x]);
                                $i=1;
                                while($i<  sizeof($time)){
                                    if($time[$i-1]!=''){
                                        echo $LD['time'][$i];
                                    }                                    
                                    $i++;
                                }                                
                                break;
                            case 'tienthai':
                                $tienthai=explode(';', $pregbuf[$show_preg_enc][$x]);
                                $i=0;
                                while($i<  sizeof($tienthai)){
                                    if($tienthai[$i]!=''){
                                        echo $tienthai[$i];
                                    }                                    
                                    $i++;
                                }
                                break;
                            case 'batdauthaykinh':
                                echo '<table border=0 cellpadding=1 cellspacing=1 width=100%><tr>';
                                echo '<td>'.$pregbuf[$show_preg_enc][$x].'</td>';
                                echo '<td><FONT color="#006600"><b>'.$LDTuoi.'</b></font>  '.$pregbuf[$show_preg_enc]['tuoithaykinh'].'</td>';
                                echo '</tr></table>';
                                break;
                            case 'tuoithaykinh':
                                break;
                            case 'namlaychong':
                                echo '<table border=0 cellpadding=1 cellspacing=1 width=100%><tr>';
                                echo '<td>'.$pregbuf[$show_preg_enc][$x].'</td>';
                                echo '<td><FONT color="#006600"><b>'.$LDTuoi.'</b></font>  '.$pregbuf[$show_preg_enc]['tuoilaychong'].'</td>';
                                echo '</tr></table>';
                                break;
                            case 'tuoilaychong':
                                break;
                            case 'namhetkinh':
                                echo '<table border=0 cellpadding=1 cellspacing=1 width=100%><tr>';
                                echo '<td>'.$pregbuf[$show_preg_enc][$x].'</td>';
                                echo '<td><FONT color="#006600"><b>'.$LD['tuoihetkinh'].'</b></font>  '.$pregbuf[$show_preg_enc]['tuoihetkinh'].'</td>';
                                echo '</tr></table>';
                                break;
                            case 'tuoihetkinh':
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
                <tr bgcolor="#fefefe" >
                    <td style="padding-left:15px;">
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
        if($parent_admit&&$edit&&($show_preg_enc==$_SESSION['sess_en']||$no_enc_preg)){
    ?>
    <tr valign="top">
        <td colspan=2>&nbsp;<br>
            <img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?> />
            <a href="<?php 
                    echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='. strtr($target,' ','+').'&mode=new&allow_update=1';
                    if($this_enc_preg) echo '&rec_nr='.$pregbuf[$show_preg_enc]['nr'];
                ?>" /> 
            <?php 
                if($no_enc_preg) echo $LDEnterNewRecord;
                else echo $LD['update_history_details']; 
            ?>
            </a>
            &nbsp;
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