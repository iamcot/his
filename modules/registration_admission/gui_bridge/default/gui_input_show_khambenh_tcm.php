<?php
if($pregs) $row=$pregs->FetchRow();
//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
//end : gjergji
$trigiac=explode("_",$row['trigiac']);
$tiengtim=explode("_",$row['th_tiengtim']);
$hohap=explode("_",$row['hohap']);
$thankinh=explode("_",$row['thankinh']);
//var_dump($row);
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
	}else if(d.personell_name.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.personell_name.focus();
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

<br>
<form method="post" name="entryform" onSubmit="chkform(this)">
 <table border=0 cellpadding=2 width=100%>
	<tr>
		<td class="adm_item" colspan=4>
			1.<?php echo $LDToanthan; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">		
		<td>
			<input type="checkbox" value="1" name="tim" <?php if($row['tim']!='')echo 'checked="checked"' ?>><?php echo $LDTim ?>
		</td>
		<td>
			<?php echo $LDSpo2;?>&nbsp;<input type="text" value="<?php echo $row['spo2']?>" name="spo2"  size="5">
		</td>
		<td class="adm_item">
			<?php echo $LD['trigiac'][0]; ?>
		</td>
		<td>
			<input type="checkbox" name="trigiac1" value="1" <?php if($trigiac[0]!='')echo 'checked' ?>><?php echo $LD['trigiac'][1]; ?>
			<input type="checkbox" name="trigiac2" value="2" <?php if($trigiac[1]!='')echo 'checked' ?>><?php echo $LD['trigiac'][2]; ?>
			<input type="checkbox" name="trigiac3" value="3" <?php if($trigiac[2]!='')echo 'checked' ?>><?php echo $LD['trigiac'][3]; ?>			
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr>
		<td class="adm_item" colspan=4>
			<?php echo $LDCaccoquan; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			+ <?php echo $LDTuanhoan; ?>
		</td>
		<td>
			<?php echo $LD['tiengtim'][0];?>
		</td>
		<td colspan=2>
			<input type="checkbox" name="tiengtim1" value="1" <?php if($tiengtim[0]!='')echo 'checked' ?>><?php echo $LD['tiengtim'][1]; ?>
			<input type="checkbox" name="tiengtim2" value="2" <?php if($tiengtim[1]!='')echo 'checked' ?>><?php echo $LD['tiengtim'][2]; ?>
			<input type="checkbox" name="tiengtim3" value="3" <?php if($tiengtim[2]!='')echo 'checked' ?>><?php echo $LD['tiengtim'][3]; ?>	
			<input type="checkbox" name="tiengtim4" value="4" <?php if($tiengtim[3]!='')echo 'checked' ?>><?php echo $LD['tiengtim'][4]; ?>	
			<input type="text" value="<?php echo $row['amthoi_notes']?>" name="amthoi_notes" >
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			<input type="checkbox" name="tiengtim5" value="5" <?php if($tiengtim[4]!='')echo 'checked' ?>><?php echo $LD['tiengtim'][5]; ?>	
		</td>
		<td class="adm_item">
			<?php echo $LD['tiengtim'][6]; ?>
		</td>
		<td>
			<input type="text" value="<?php echo $row['time_maomach'] ?>" size="5" name="time_maomach" > giây
		</td>
		<td>
			<input type="checkbox" name="tiengtim6" value="6" <?php if($tiengtim[5]!='')echo 'checked' ?>><?php echo $LD['tiengtim'][7]; ?>	
			<input type="checkbox" name="tiengtim7" value="7" <?php if($tiengtim[6]!='')echo 'checked' ?>><?php echo $LD['tiengtim'][8]; ?>	
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDDauhieukhac; ?>
		</td>
		<td colspan=3>
			<input type="text" size="80" name="th_khac" value="<?php echo $row['th_khac'] ?>">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			+ <?php echo $LDHohap; ?>
		</td>
		<td>
			<input type="checkbox" name="hohap1" value="1" <?php if($hohap[0]!='')echo 'checked' ?>><?php echo $LD['hohap'][0]; ?>	
		</td>
		<td>
			<input type="checkbox" name="hohap2" value="2" <?php if($hohap[1]!='')echo 'checked' ?>><?php echo $LD['hohap'][1]; ?>	
		</td>
		<td>
			<input type="checkbox" name="hohap3" value="3" <?php if($hohap[2]!='')echo 'checked' ?>><?php echo $LD['hohap'][2]; ?>	
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			<input type="checkbox" name="hohap4" value="4" <?php if($hohap[3]!='')echo 'checked' ?>><?php echo $LD['hohap'][3]; ?>	
		</td>
		<td>
			<input type="checkbox" name="hohap5" value="5" <?php if($hohap[4]!='')echo 'checked' ?>><?php echo $LD['hohap'][4]; ?>	
		</td>
		<td>
			<input type="checkbox" name="hohap6" value="6" <?php if($hohap[5]!='')echo 'checked' ?>><?php echo $LD['hohap'][5]; ?>	
		</td>
		<td>
			<input type="checkbox" name="hohap7" value="7" <?php if($hohap[6]!='')echo 'checked' ?>><?php echo $LD['hohap'][6]; ?>	
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDDauhieukhac; ?>
		</td>
		<td colspan=3>
			<input type="text" size="80" name="hohap_khac" value="<?php echo $row['hohap_khac'] ?>">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			+ <?php echo $LDTieuhoa; ?>
		</td>
		<td>
			<input type="checkbox" name="th_ganto" value="1" <?php if($row['th_ganto']!='')echo 'checked' ?>><?php echo $LDGanto; ?>	
		</td>
		<td>
			<input type="text" size="5" name="th_ganto_cm" value="<?php echo $row['th_ganto_cm']?>" > cm DBS,Đặc điểm
		</td>
		<td>
			<input type="text" value="<?php echo $row['th_ganto_dd']?>" name="th_ganto_dd">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDDauhieukhac; ?>
		</td>
		<td colspan=3>
			<input type="text" size="80" name="thoa_khac" value="<?php echo $row['thoa_khac']?>">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			+<?php echo $LDThantietnieusinhduc; ?>
		</td>
		<td colspan=3>
			<input type="text" size="80" name="than_tn_sd" value="<?php echo $row['than_tn_sd']?>">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			+<?php echo $LDThankinh; ?>
		</td>
		<td>
			<?php echo $LD['thankinh'][0] ?>&nbsp;<input type="text" name="tk_dongtu" size="6" value="<?php echo $row['tk_dongtu']?>" >
		</td>
		<td>
			<?php echo $LD['thankinh'][1] ?>&nbsp;<input type="text" name="tk_pxas" size="6" value="<?php echo $row['tk_pxas']?>" >
		</td>
		<td>
			<input type="checkbox" name="thankinh1" value="1" <?php if($thankinh[0]!='')echo 'checked' ?>><?php echo $LD['thankinh'][2]; ?>	
			
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			<input type="checkbox" name="thankinh2" value="2" <?php if($thankinh[1]!='')echo 'checked' ?>><?php echo $LD['thankinh'][3]; ?>	
		</td>
		<td>
			<input type="checkbox" name="thankinh3" value="3" <?php if($thankinh[2]!='')echo 'checked' ?>><?php echo $LD['thankinh'][4]; ?>	
		</td>
		<td>
			<input type="checkbox" name="thankinh4" value="4" <?php if($thankinh[3]!='')echo 'checked' ?>><?php echo $LD['thankinh'][5]; ?>	
		</td>
		<td>
			<input type="checkbox" name="thankinh5" value="5" <?php if($thankinh[4]!='')echo 'checked' ?>><?php echo $LD['thankinh'][6]; ?>	
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			<input type="checkbox" name="thankinh6" value="6" <?php if($thankinh[5]!='')echo 'checked' ?>><?php echo $LD['thankinh'][7]; ?>	
		</td>
		<td>
			<input type="checkbox" name="thankinh7" value="7" <?php if($thankinh[6]!='')echo 'checked' ?>><?php echo $LD['thankinh'][8]; ?>	
		</td>
		<td>
			<input type="checkbox" name="thankinh8" value="8" <?php if($thankinh[7]!='')echo 'checked' ?>><?php echo $LD['thankinh'][9]; ?>	
		</td>
		<td>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDDauhieukhac; ?>
		</td>
		<td colspan=3>
			<input type="text" size="80" name="tk_khac" value="<?php echo $row['tk_khac']?>">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			+<?php echo $LDCoxuongkhop; ?>
		</td>
		<td colspan=3>
			<input type="text" size="80" name="co_xuong_khop" value="<?php echo $row['co_xuong_khop']?>">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			+<?php echo $LDOthers; ?>
		</td>
		<td colspan=3>
			<input type="text" size="80" name="tmh_rhm_m_khac" value="<?php echo $row['tmh_rhm_m_khac']?>">
		</td>
	</tr>
	<tr>
		<td colspan=4 class="space">
		</td>
	</tr>
    <tr bgcolor="#f6f6f6">
		<td class="adm_item"> <?php echo $LDDate; ?></td>
		<td>
			<?php
				//gjergji : new calendar
				echo $calendar->show_calendar($calendar,$date_format,'date',$row['date']);	
				//end : gjergji
			?>
		</td>
		<td class="adm_item"><?php echo $LDBy; ?></td>
		<td><input type="text" name="doctor_name" style="width:90%;" maxlength=60 value="<?php echo $row['doctor_name']?>" readonly >
			<input type="hidden" name="doctor_nr" size=30 maxlength=60 value="<?php echo $row['doctor_nr']?>" >
			<a href="javascript:popDocPer('doctor_nr')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>></td>
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
