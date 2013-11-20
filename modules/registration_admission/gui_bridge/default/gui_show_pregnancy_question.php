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
            $array=array('nr','encounter_nr','kinhcuoitu','kinhcuoiden','tuoithai','noikhamthai','uongvan','duoctiem','sieuam','ngaychuyenda','gio_chuyenda','dauhieulucdau','bienchuyen','batdauthaykinh','tinhchatkinh','chuki', 'luongkinh', 'namlaychong', 'tuoilaychong', 'benhphukhoa','khac');
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
		while(list($z,$x)=each($fields)){
                    if($x=='status') break;
                    if($x=='nr'||$x=='encounter_nr'|| (empty($pregbuf[$show_preg_enc][$x]) && $x!='sieuam')) continue;
                    if($LD[$x]!='đến ngày'){
                            if(formatDate2Local($pregbuf[$show_preg_enc][$x],$date_format)=='00/00/0000'){ 
                                continue;
                            }else{
                                if($x=='sieuam'){
                                    echo '<tr bgcolor="yellow">
                                            <td width="30%"  style="padding-left:15px;">
                                            <FONT color="red"><b>';
                                }else{
                                    echo '<tr bgcolor="#fefefe">
                                            <td width="30%"  style="padding-left:15px;">
                                            <FONT color="#006600"><b>';

                                }
                                echo $LD[$x];
                            }
                                    
                                ?>
                            </b>
                        </FONT>
                    </td>
                    <td style="padding-left:15px;">
                    <?php
                    }
                        switch($x){
                            case 'kinhcuoitu':
                                if(formatDate2Local($pregbuf[$show_preg_enc][$x],$date_format)!='00/00/0000'){
                                    echo '<table border=0 width=100%><tr>';
                                    echo '<td width="40%">'.formatDate2Local($pregbuf[$show_preg_enc][$x],$date_format).'</td>';
                                    echo '<td width="20%"><FONT color="#006600"><b>'.$LD['kinhcuoiden'].'</b></FONT></td>';
                                    echo '<td>'.formatDate2Local($pregbuf[$show_preg_enc]['kinhcuoiden'],$date_format).'</td>';
                                    echo '</tr></table>';
                                }                                
                                break;
                            case 'kinhcuoiden': 
                                break;
                            case 'tuoithai':
                                echo $pregbuf[$show_preg_enc][$x].'   '.$LDWeek;
                                break;
                            case 'ngaychuyenda':
                                echo formatDate2Local($pregbuf[$show_preg_enc][$x],$date_format);
                                break;
                            case 'gio_chuyenda': 
                                echo $pregbuf[$show_preg_enc][$x]; 
                                break;
                            case 'uongvan':
                                if($pregbuf[$show_preg_enc][$x]) echo $LDYes_s;
                                break;
                            case 'sieuam':
                                if($pregbuf[$show_preg_enc][$x]) 
                                    echo '<FONT color="red"><b>'.$pregbuf[$show_preg_enc][$x].'  '.$LDthai.'</b></FONT>';
                                else
                                    echo '<FONT color="red"><b>'.$LDUnknown.'</b></FONT>';
                                break;
                            case 'duoctiem':
                                echo $pregbuf[$show_preg_enc][$x].'  '.$LDUseTimes;
                                break;
                            case 'chuki':
                                echo $pregbuf[$show_preg_enc][$x].'  '.$LDday1;
                                break;
                            case 'batdauthaykinh':
                                echo $pregbuf[$show_preg_enc][$x].'  '.$LD['tuoilaychong'];
                                break;
                            default: echo $pregbuf[$show_preg_enc][$x];
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
        $array1=array('nammangthai', 'deduthang', 'dethieuthang', 'say', 'hut', 'nao', 'covac', 'chuangoai','chuatrung','thaichet','conhiensong','cannang','phuongphapde','taibien');
        $obj->setRefArray($array1);
        $fields=&$obj->coreFieldNames();
    ?>
    <tr bgcolor='#f6f6f6'>
        <td colspan="2">
            <table class="submenu_frame" width="100%">
                <tbody class="submenu">
                    <tr>                        
                    <?php
                        echo '<td align="center" width="5%">'.$LD['lanmangthai'].'</td>';
                        while(list($z,$x)=each($fields)){
                            echo '<td align="center">';                            
                            echo $LD[$x];
                            echo '</td>';
                            switch ($x){                                
                                case 'nammangthai':
                                    $nammangthai=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'deduthang':
                                    $deduthang=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'dethieuthang':
                                    $dethieuthang=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'say':
                                    $say=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'hut':
                                    $hut=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'nao':
                                    $nao=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'covac':
                                    $covac=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'chuangoai':
                                    $chuangoai=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'chuatrung':
                                    $chuatrung=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'thaichet':
                                    $thaichet=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'conhiensong':
                                    $conhiensong=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'cannang':
                                    $cannang=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                case 'phuongphapde':
                                    $phuongphapde=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                                default:
                                    $taibien=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    break;
                            }
                        }
                    ?>                        
                    </tr>
                    <?php
                        $i=0;
                        while($i<6){
                            echo '<tr>';
                            if($i%2==0){
                                $bg=' bgcolor="#00FFFF" align="center">';
                            }else{
                                $bg=' bgcolor="#FFFFFF " align="center">';
                            }  
                            echo '<td'.$bg.($i+1).'</td>';
                            echo '<td';
                            if($nammangthai[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$nammangthai[$i].'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($deduthang[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($dethieuthang[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($say[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($hut[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($nao[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($covac[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($chuangoai[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($chuatrung[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($thaichet[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($conhiensong[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($cannang[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$cannang[$i].'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($phuongphapde[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$phuongphapde[$i].'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '<td';
                            if($taibien[$i])
                                echo ' bgcolor="yellow" align="center"><b>'.$LDYes_s.'</b>';
                            else
                                echo $bg;
                            echo '</td>';
                            echo '</tr>';
                            $i++;
                        }
                    ?>
                </tbody>                
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
    ?>
</table>
<?php
}
?>
