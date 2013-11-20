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
                $array=array('nr','pid','encounter_nr','rau','date', 'time', 'cachsorau', 'matmang', 'matmui', 'banhrau', 'cannang', 'raucuonco', 'kstc', 'crdai', 'chaymau', 'matmau', 'donvimau', 'xuly', 'status', 'history', 'modify_id', 'modify_time', 'create_id', 'create_time');
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
			if($x=='nr'||$x=='encounter_nr'||$x=='pid'||$x=="time"||$x=="donvimau"||$x=='modify_id'||$x=='modify_time'||$x=='create_id'||$x=='create_time'||$x=='status'||empty($pregbuf[$show_preg_enc][$x])) continue;
                        if($x=='rau'){
                            $t=explode(";",$pregbuf[$show_preg_enc][$x]);
                            if($t[0]!='' || $t[1]!=''){
                                echo '<tr bgcolor="#fefefe">
                                    <td  style="padding-left:15px;">
                                        <FONT color="#006600">
                                            <b>';
                                echo $LD[$x][0];
                                echo '      </b>
                                        </FONT>
                                    </td>';
                            } 
                        }else if($x=='date'){
                            echo '<tr bgcolor="#fefefe">
                                    <td  style="padding-left:15px;">
                                        <FONT color="#006600">
                                            <b>';
                            echo $Date_So;
                            echo '      </b>
                                        </FONT>
                                    </td>';
                        }else{
?>


                            <tr bgcolor="#fefefe">
                                <td style="padding-left:15px;">
                                    <FONT color="#006600">
                                        <b>
                                            <?php
                                                echo $LD[$x];                                         
                                            ?>
                                        </b>
                                    </FONT>
                                </td>                                
	<?php 
                        }
                        echo '<td style="padding-left:15px;">';
			switch($x){
				case 'date': 
                                    echo '<table width="100%"><tr>';
                                    echo '<td width="40%">'.$pregbuf[$show_preg_enc]["time"].'</td>'; 
                                    echo '<td width="10%"><FONT color="#006600"><b>'.$LDDate.'</b></FONT></td>';
                                    echo '<td>'.formatDate2Local($pregbuf[$show_preg_enc][$x],$date_format).'</td>';
                                    echo '</tr></table>';
                                    break;
                                case 'time':
                                    break;
                                case 'rau':
                                    $t=explode(";",$pregbuf[$show_preg_enc][$x]);
                                    $i=0;
                                    while($i<sizeof($t)){
                                        if($t[$i]!='')
                                            echo $LD[$x][$i+1];
                                        $i++;
                                    }                                        
                                    break;
				case 'raucuonco':
                                case 'kstc':
                                case 'chaymau':
                                    if($pregbuf[$show_preg_enc][$x]) 
                                        echo $LDYes_s;
                                    break;
				case 'cannang':
                                    echo $pregbuf[$show_preg_enc][$x].'  gram'; 
                                    break;
                                case 'crdai':
                                    echo $pregbuf[$show_preg_enc][$x].'  Cm'; 
                                    break;
                                case 'matmau': 
                                    echo $pregbuf[$show_preg_enc][$x].'  '.$pregbuf[$show_preg_enc]['donvimau'];
                                    break;
                                case 'donvimau':
                                    break;
                                
				default: 
                                    if($pregbuf[$show_preg_enc][$x])
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
}
if($show_details){
?>
  <tr class="adm_input">
        <td colspan="2">
            <img <?php echo createComIcon($root_path,'update2.gif','0','absmiddle'); ?> />
            <a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&rec_nr='.$pregbuf[$show_preg_enc]['nr'].'&mode=new&allow_update=1&target=entry'; ?>">
            <?php
                echo $LD['update_details_rau'].'</a>';
            ?>
        </td>
    </tr>
<?php
	}
?>
</table>
<?php
}
?>
