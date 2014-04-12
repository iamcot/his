<?php

function createDataBlock ( $param ) {
	global $stored_findings, $edit ;
	
	if ($edit) {
		echo '
	 		<textarea name="' . $param . '" cols=82 rows=4 wrap="physical">' . stripslashes ( $stored_findings [ $param ] ) . '</textarea>' ;
	} else {
		echo '
			<blockquote><font color="#000000" size=2>' . nl2br ( stripslashes ( $stored_findings [ $param ] ) ) . '</font></blockquote>' ;
	}
}

function createInputBlock ( $param , $value ) {
	global $stored_findings, $date_format, $edit, $lang ;
	
	if ($edit) {
		echo '&nbsp;<input type="text" name="' . $param . '"  value="' . $value . '" size=' ;
		if ($param == "doctor_id")
			echo '35 maxlength=35>' ; else
			echo '10 maxlength=10 onBlur="IsValidDate(this,\'' . $date_format . '\')"  onKeyUp="setDate(this,\'' . $date_format . '\',\'' . $lang . '\')">' ;
	} else {
		echo '&nbsp;<font color="#000000" size=2>' . $value . '</font><br>&nbsp;' ;
	}
}
?>

<script language="javascript">          //đã thêm
    function popDocPer(target,obj_val,obj_name){     //đã thêm
        urlholder="<?php echo $root_path; ?>modules/laboratory/personell_search.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;  //đã thêm
        DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");                                //đã thêm
    }
</script>

<table cellpadding="0" cellspacing=1 border="0" width=700>
	<tr valign="top" bgcolor="<?php
	echo $bgc1 ?>">
		<td width=35%>
		<div class="fva2_ml10"><font size=5 color="#0000ff"> <b><?php
		echo $LDTestFindings ?></b></font><br>
		<font size=3 color="#000099"><b><?php
		echo $formtitle ?></b></font><br>
		<font size=1 color="#000099"><?php
		echo $global_address [ $subtarget ] . '<br>' . $LDTel . '&nbsp;' . $global_phone [ $subtarget ] ?></font>
		
		</td>
		<td align="right" width=65%><font size=1 color="#000099">
	    <?php
					echo $batch_nr . '&nbsp;&nbsp;<br>' ;
					echo "<img src='" . $root_path . "classes/barcode/image.php?code=$batch_nr&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>" ;
					?>&nbsp;&nbsp;<br>
		 <?php
			
			echo $LDEntryDate . '&nbsp;&nbsp;<br>' ;
			if ($entry_date != "0000-00-00")
				echo formatDate2Local ( $entry_date, $date_format ) ;
			
			?>&nbsp;&nbsp;</font></td>
	</tr>

	<tr bgcolor="<?php
	echo $bgc1 ?>">
		<td valign="top" align="right"><font color="#000099" size=2>
		<?php
		echo $LDCaseNr . '<br>' . $LDFamilyName . '<br>' . $LDName . '<br>' . $LDBDay ;
		?>
        </td>
		<td valign="top">
		<div class="fva0_ml10"><font color="#000000" size=2>
		<?php
		echo $full_en . '<br>' . $result [ 'name_last' ] . '<br>' . $result [ 'name_first' ] . '<br>' . formatDate2Local ( $result [ 'date_birth' ], $date_format ) ;
		?>
		 </div>
		</td>
	</tr>

	<tr bgcolor="<?php
	echo $bgc1 ?>">
		<td valign="top" colspan=2>
		<div class="fva0_ml10"><font color="#000099">
		<?php
		echo $LDMaterial ?><br>
		<?php
		createDataBlock ( 'material' ) ?>
		</div>
		</td>
	</tr>

	<tr bgcolor="<?php
	echo $bgc1 ?>">
		<td valign="top" colspan=2>
		<div class=fva0_ml10><font color="#000099">
		<?php
		echo $LDMACROFindings ?><br>
		<?php
		createDataBlock ( 'macro' ) ?>
		</div>
		</td>
	</tr>

	<tr bgcolor="<?php
	echo $bgc1 ?>">
		<td valign="top" colspan=2>
		<div class=fva0_ml10><font color="#000099">
		<?php
		echo $LDMicroFindings ?><br>
		<?php
		createDataBlock ( 'micro' ) ?>
		</div>
		</td>
	</tr>

	<tr bgcolor="<?php
	echo $bgc1 ?>">
		<td valign="top" colspan=2>
		<div class=fva0_ml10><font color="#000099">
		<?php
		echo $LDAddFindings ?><br>
		<?php
		createDataBlock ( 'findings' ) ?>
		</div>
		</td>
	</tr>

	<tr bgcolor="<?php
	echo $bgc1 ?>">
		<td valign="top" colspan=2>
		<div class=fva0_ml10><font color="#000099">
		<?php
		echo $LDDiagnosis ?><br>
		<?php
		createDataBlock ( 'diagnosis' ) ?>
		</div>
		</td>
	</tr>
<?php
//#170
//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
//end gjergji
?>
	<tr bgcolor="<?php
	echo $bgc1 ?>">
		<td>
		<div class=fva2_ml10><font color="#000099">
		 <?php
			echo $LDDate ?>:
		 <?php
			//#170
		    //gjergji : new calendar
			if ($edit) {
				echo $calendar->show_calendar($calendar,$date_format,'findings_date',$stored_findings ['findings_date']);
			} else {
				echo @formatDate2Local($stored_findings ['findings_date'],$date_format) ;
			}		 
			?>
            </div>
		</td>
		<td align="right">
		<div class=fva2_ml10><font color="#000099">
		<?php
		echo $LDReportingDoc ?>:
		<?php
//		createInputBlock ( 'doctor_id', $stored_findings [ 'doctor_id' ] ) ;
		?>
                <input type="text" name="doctor_id" size=37 maxlength=40 value="<?php if($edit_form || $read_form) echo $stored_findings['doctor_id'];else echo $pers_name;?>">
                <input type="hidden" name="doctor_id_nr" value="<?php if(!empty( $stored_findings['doctor_id_nr'])) echo $stored_findings['doctor_id_nr'];else echo $pers_nr; ?>"> <a href="javascript:popDocPer('doctor_nr','doctor_id_nr','doctor_id')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>>
        &nbsp;&nbsp;
		</div>
		</td>
	</tr>
</table>
