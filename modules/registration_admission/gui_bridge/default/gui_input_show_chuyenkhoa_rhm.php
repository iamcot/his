<?php
if($pregs) $row=$pregs->FetchRow();
//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();

//var_dump($tienthai);
//end : gjergji
?>
<script language="JavaScript">
<!-- Script Begin
function chkform(d) {
	if(d.date.value==""){
		alert("<?php echo $LDPlsEnterDate; ?>");
		d.date.focus();
		return false;
	}else if(d.notes.value==""){
		alert("");
		d.notes.focus();
		return false;
	}else if(d.doctor_nr.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.doctor_nr.focus();
		return false;
	}else{
		return true;
	}
}
function popDocPer(target,obj_val,obj_name){
			urlholder="./personell_search1.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name;
			DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
		}
$(function(){

$("#f-calendar-field-1").mask("99/99/9999");
});
//  Script End -->
</script>

<br/>	
<form method="post" name="entryform" onSubmit="chkform(this)">
 <table border=0 cellpadding=2 width=100%>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDToanthan; ?>
		</td>
		<td class="adm_input">
			<textarea name="toanthan_notes" rows="2" cols=60><?php if(!(isset($row['toanthan_notes']))) echo $row['toanthan_notes']; echo "Bình thường"; ?></textarea>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Bệnh chuyên khoa</nobr>
		</td>
		<td class="adm_input">
			<textarea name="ck_notes" rows="2" cols=60><?php if(!(isset($row['ck_notes']))) echo $row['ck_notes']; echo "Bình thường"; ?></textarea>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương phải</nobr>
		</td>		
		<td class="adm_input">
			<textarea rows=2 cols=60 name="phai_notes"><?php if(!isset($row['phai_notes'])) echo $row['phai_notes']; echo "Không có" ?></textarea>
		</td>
	</tr>		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương trái</nobr>
		</td>
		<td class="adm_input">
			<textarea rows=2 cols=60 name="trai_notes"><?php if(!isset($row['trai_notes'])) echo $row['trai_notes']; echo "Không có" ;?></textarea>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương thẳng</nobr>
		</td>
		<td class="adm_input">
			<textarea rows=2 cols=60 name="thang_notes"><?php if(!isset($row['thang_notes'])) echo $row['thang_notes']; echo "Không có" ?></textarea>
		</td>		
	</tr >		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương hàm trên và họng</nobr>
		</td>
		<td class="adm_input">
			<textarea rows=2 cols=60 name="hamtrenhong_notes"><?php if(!isset($row['hamtrenhong_notes'])) echo $row['hamtrenhong_notes']; echo "Không có" ?></textarea>
		</td>		
	</tr >	

	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương hàm dưới</nobr>
		</td>
		<td class="adm_input">
			<textarea rows=2 cols=60 name="hamduoi_notes"><?php if(!isset($row['hamduoi_notes'])) echo $row['hamduoi_notes']; echo "Không có" ?></textarea>
		</td>		
	</tr >	
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Phân loại khe hở môi vòm miệng</nobr>
		</td>
		<td class="adm_input">
			<textarea rows=2 cols=60 name="kheho_notes"><?php if(!isset($row['kheho_notes'])) echo $row['kheho_notes']; echo "Không có" ?></textarea>
		</td>		
	</tr >	
    <tr bgcolor="#f6f6f6">
		<td class="adm_item"><?php echo $LDDate; ?></td>
		<td class="adm_input">
			<?php
				//gjergji : new calendar
				echo $calendar->show_calendar($calendar,$date_format,'date',$row['date']);	
				//end : gjergji
			?>
		</td>
		
	</tr>  
	<tr>
		<td class="adm_item"><?php echo $LDBy; ?></td>
		<td class="adm_input"><input type="text" name="doctor_name" size=30 maxlength=60 value="<?php echo $row['doctor_name']; ?>" readonly>
			<input type="hidden" name="doctor_nr" size=30 maxlength=60 value="<?php echo $row['doctor_nr']?>" >
				<a href="javascript:popDocPer('doctor_nr')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>>
		</td>
	</tr>
    
 </table>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_time" value="null">
<input type="hidden" name="mode" value="newdata">
<input type="hidden" name="type_nr" value="<?php echo $type_nr; ?>">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name']."\n"; ?>">
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>

</form>
