<?php 
require_once ('../../js/jscalendar/calendar.php');
			$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
			$calendar->load_files();
			?>
<script language="JavaScript">
<!-- Script Begin
function chkform(d) {
	if(d.msr_date.value==""){
		alert("<?php echo $LDPlsEnterDate; ?>");
		d.msr_date.focus();
		return false;
	}else if(d.weight.value==""&&d.height.value==""){
		alert("<?php echo $LDPlsEnterValue; ?>");
		return false;
	}else if(isNaN(d.weight.value)){
		d.height.focus(); // patch for Konqueror
		alert("<?php echo $LDEntryInvalidChar; ?>");
		d.weight.focus();
		return false;
	}else if(d.weight.value<0){
		d.height.focus(); // patch for Konqueror
		alert("<?php echo $LDNotNegValue; ?>");
		d.weight.focus();
		return false;
	}else if(isNaN(d.height.value)){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDEntryInvalidChar; ?>");
		d.height.focus();
		return false;
	}else if(d.height.value<0){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDNotNegValue; ?>");
		d.height.focus();
		return false;
	}else if(isNaN(d.head_c.value)){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDEntryInvalidChar; ?>");
		d.head_c.focus();
		return false;
	}else if(d.head_c.value<0){
		d.weight.focus(); // patch for Konqueror
		alert("<?php echo $LDNotNegValue; ?>");
		d.head_c.focus();
		return false;
	}else if(d.measured_by.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.measured_by.focus();
		return false;
	}else{
		return true;
	}
}
$(function(){
$("#f-calendar-field-1").mask("99/99/9999");
$("input[name='huyetap_c']").focus();
});	
//  Script End -->
</script>
<form method="post" name="wtht_form" onSubmit="return chkform(this)">
 <table border=0 cellpadding=2 width=100%>
   <tr bgcolor="#f6f6f6">
     <td><font color=red>*</font><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDate; ?></td>
     <td colspan=3>
	 	<?php
			//gjergji : new calendar
			
			
			echo $calendar->show_calendar($calendar,$date_format,'msr_date',date("d/m/Y"));
			//gjergji : end
		?> 	
	 </td>
	 
   </tr>
<!--    <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTime; ?></td>
     <td><input type="text" name="msr_time" size=10 maxlength=5 ></td>
   </tr>
 -->   
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDType; ?></td>
     <td><font color=red>*</font><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDUnit.' '.$LDValue; ?></td>
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDUnit.' '.$LDType; ?></td>
     <td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo "$LDNotes ($LDOptional)"; ?></td>
   </tr>
    <!-- huyet ap -->
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDSystolic; ?></td>
     <td><FONT SIZE=-1  FACE="Arial"><input tabindex=1 type="text" name="huyetap_c" size=10 maxlength=10 value="<?php echo $huyetap_c; ?>"></td>
     <td><FONT SIZE=-1  FACE="Arial">
	 	 <select name="sc_unit_nr">
		<?php
			while(list($x,$v)=each($unit_types)){
				echo '<option value="'.$v['nr'].'"';
				if($v['nr']==$sc_unit_nr) echo 'selected';
				echo '>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
				echo '</option>
				';
			}
			reset($unit_types);
		?>
         </select>
</td>
     <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="huyetap_notes" size=40 maxlength=60 value="<?php echo $huyetap_notes; ?>"></td>
   </tr>
     <!-- Nhiet do  -->
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDTemperature; ?></td>
     <td><FONT SIZE=-1  FACE="Arial"><input  tabindex=2 type="text" name="nhietdo_c" size=10 maxlength=10 value="<?php echo $nhietdo_c; ?>"></td>
     <td><FONT SIZE=-1  FACE="Arial">
	 	 <select name="tp_unit_nr">
		<?php
			while(list($x,$v)=each($unit_types)){
				echo '<option value="'.$v['nr'].'"';
				if($v['nr']==$tp_unit_nr) echo 'selected';
				echo '>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
				echo '</option>
				';
			}
			reset($unit_types);
		?>
         </select>
</td>
     <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="nhietdo_notes" size=40 maxlength=60 value="<?php echo $nhietdo_notes; ?>"></td>
   </tr>
    <!-- Mach  -->
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDDiastolic; ?></td>
     <td><FONT SIZE=-1  FACE="Arial"><input  tabindex=3 type="text" name="mach_c" size=10 maxlength=10 value="<?php echo $mach_c; ?>"></td>
     <td><FONT SIZE=-1  FACE="Arial">
	 	 <select name="dc_unit_nr">
		<?php
		
			while(list($x,$v)=each($unit_types)){
				echo '<option value="'.$v['nr'].'"';
				if($v['nr']==$dc_unit_nr) echo 'selected';
				echo '>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
				echo '</option>
				';
			}
			reset($unit_types);
		?>
         </select>
</td>
     <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="mach_notes" size=40 maxlength=60 value="<?php echo $mach_notes; ?>"></td>
   </tr>
     
          <!-- Nhip tho  -->
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LDbreathing; ?></td>
     <td><FONT SIZE=-1  FACE="Arial"><input  tabindex=4 type="text" name="nhiptho_c" size=10 maxlength=10 value="<?php echo $nhiptho_c; ?>"></td>
     <td><FONT SIZE=-1  FACE="Arial">
	 	 <select name="br_unit_nr">
		<?php
			while(list($x,$v)=each($unit_types)){
				echo '<option value="'.$v['nr'].'"';
				if($v['nr']==$br_unit_nr) echo 'selected';
				echo '>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
				echo '</option>
				';
			}
			reset($unit_types);
		?>
         </select>
</td>
     <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="nhiptho_notes" size=40 maxlength=60 value="<?php echo $nhiptho_notes; ?>"></td>
   </tr>
   <!--can nang-->
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial"><?php echo $LDWeight; ?></td>
     <td><FONT SIZE=-1  FACE="Arial"><input  tabindex=5 type="text" name="weight" size=10 maxlength=10 value="<?php echo $weight; ?>"></td>
     <td><FONT SIZE=-1  FACE="Arial">
	 <select name="wt_unit_nr">
		<?php
			while(list($x,$v)=each($unit_types)){
				echo '<option value="'.$v['nr'].'"';
				if($v['nr']==$wt_unit_nr) echo 'selected';
				echo '>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
				echo '</option>
				';
			}
			reset($unit_types);
		?>
        </select>
	 </td>
     <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="wt_notes" size=40 maxlength=60 value="<?php echo $wt_notes; ?>"></td>
   </tr>
   <!--chieu cao-->
   <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial"><?php echo $LDHeight; ?></td>
     <td><FONT SIZE=-1  FACE="Arial"><input  tabindex=6  type="text" name="height" size=10 maxlength=10 value="<?php echo $height; ?>"></td>
     <td><FONT SIZE=-1  FACE="Arial">
	 <select name="ht_unit_nr">
		<?php
			while(list($x,$v)=each($unit_types)){
				echo '<option value="'.$v['nr'].'"';
				if($v['nr']==$ht_unit_nr) echo 'selected';
				echo '>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
				echo '</option>
				';
			}
			reset($unit_types);
		?>
         </select>
	 </td>
     <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="ht_notes" size=40 maxlength=60 value="<?php echo $ht_notes; ?>"></td>
   </tr>
   <!--chu vi vong dau-->
      <tr bgcolor="#f6f6f6">
     <td><FONT SIZE=-1  FACE="Arial" ><?php echo $LD['head_circumference']; ?></td>
     <td><FONT SIZE=-1  FACE="Arial"><input  tabindex=7 type="text" name="head_c" size=10 maxlength=10 value="<?php echo $head_c; ?>"></td>
     <td><FONT SIZE=-1  FACE="Arial">
	 	 <select name="hc_unit_nr">
		<?php
			while(list($x,$v)=each($unit_types)){
				echo '<option value="'.$v['nr'].'"';
				if($v['nr']==$hc_unit_nr) echo 'selected';
				echo '>';
				if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
					else echo $v['name'];
				echo '</option>
				';
			}
			reset($unit_types);
		?>
         </select>
</td>
     <td><FONT SIZE=-1  FACE="Arial"><input type="text" name="hc_notes" size=40 maxlength=60 value="<?php echo $hc_notes; ?>"></td>
   </tr>
  
   
<!--  wt = 6, ht= 7 -->
   <tr bgcolor="#f6f6f6">
     <td colspan=4>&nbsp;</td>
   </tr>   
   <tr bgcolor="#f6f6f6">
     <td><font color=red>*</font><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDMeasuredBy; ?></td>
     <td colspan=3><input type="text" name="measured_by" size=50 maxlength=60 value="<?php echo $_SESSION['sess_user_name']; ?>"></td>
   </tr>
 </table>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<!--<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">-->
<!-- <input type="hidden" name="create_time" value="<?php echo date('YmdHis'); ?>">
 -->
<input type="hidden" name="mode" value="create">
<? if(isset($typenew)) echo '<input type="hidden" name="typenew" value="'.$typenew.'">'; ?>
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name']."\n"; ?>">
<input tabindex=8 type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>

</form>
