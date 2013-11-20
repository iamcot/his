<?php
	# Transfer data into array
	if($row=$pregs->FetchRow()){
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
                    
			if($x=='nr'||$x=='pid'||$x=='encounter_nr'||$x=='status'||$x=='history'||$x=='create_id'||$x=='create_time'||$x=='modify_id'||$x=='modify_time'||empty($pregbuf[$show_preg_enc][$x])) continue;

?>


  <tr bgcolor="#fefefe">
    <td  style="padding-left:15px;">
        <FONT color="#006600">
            <b>
                <?php
                    if($x=='date')
                        echo $LDDate_kham;
                    else
                        echo $LD[$x];                                                 
                ?>
            </b>
        </FONT>
    </td>
    <td style="padding-left:15px;">
	<?php 

			switch($x){
				case 'date': 
                                    echo formatDate2Local($pregbuf[$show_preg_enc][$x],$date_format); 
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
            else echo $LD['update_admit_obstetrics']; 
?>
	</a>&nbsp;<p>
    </td>
</tr>
<?php
	}
?>
</table>
