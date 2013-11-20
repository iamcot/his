<?php
if($pregs) $row=$pregs->FetchRow();
//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
$tienthai=explode("_",$row['tienthai']);
$tinhtrangkhisinh=explode("_",$row['tinhtrangkhisinh']);
$nuoiduong=explode("_",$row['nuoiduong']);
$chamsoc=explode("_",$row['chamsoc']);
$tiemchung=explode("_",$row['tiemchung']);
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

<br/>	
<form method="post" name="entryform" onSubmit="chkform(this)">
 <table border=0 cellpadding=2 width=100%>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDConthu; ?>
		</td>
		<td class="adm_input">
			<input type="text" value="<?php echo $row['conthu'] ?>" name="conthu" maxlength="2" size="5">
		</td>
		<td class="adm_item">
			<nobr><?php echo $LD['tienthai'][0]; ?></nobr>
		</td>
		<td class="adm_input">
			<nobr><input type="text" name="tienthai1" size="2" value="<?php if($tienthai[0]!='')echo $tienthai[0] ?>" ><?php echo $LD['tienthai'][1]; ?>
			<input type="text" name="tienthai2" size="2" value="<?php if($tienthai[0]!='')echo $tienthai[1] ?>"><?php echo $LD['tienthai'][2]; ?></nobr>
			<input type="text" name="tienthai3" size="2" value="<?php if($tienthai[0]!='')echo $tienthai[2] ?>"><?php echo $LD['tienthai'][3]; ?>
			<input type="text" name="tienthai4" size="2" value="<?php if($tienthai[0]!='')echo $tienthai[3] ?>"><?php echo $LD['tienthai'][4]; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDTinhtrangkhisinh; ?></nobr>
		</td>
		<td colspan=3>
			<input type="checkbox" name="tinhtrangkhisinh1" value="1"  <?php if($tinhtrangkhisinh[0]!='')echo 'checked' ?>><?php echo $LD['tinhtrangkhisinh'][0]; ?>
			<input type="checkbox" name="tinhtrangkhisinh2" value="2"  <?php if($tinhtrangkhisinh[1]!='')echo 'checked' ?>><?php echo $LD['tinhtrangkhisinh'][1]; ?>
			<input type="checkbox" name="tinhtrangkhisinh3" value="3"  <?php if($tinhtrangkhisinh[2]!='')echo 'checked' ?>><?php echo $LD['tinhtrangkhisinh'][2]; ?>
			<input type="checkbox" name="tinhtrangkhisinh4" value="4"  <?php if($tinhtrangkhisinh[3]!='')echo 'checked' ?>><?php echo $LD['tinhtrangkhisinh'][3]; ?>
			<input type="checkbox" name="tinhtrangkhisinh5" value="5"  <?php if($tinhtrangkhisinh[4]!='')echo 'checked' ?>><?php echo $LD['tinhtrangkhisinh'][4]; ?>
			<input type="checkbox" name="tinhtrangkhisinh6" value="6"  <?php if($tinhtrangkhisinh[5]!='')echo 'checked' ?>><?php echo $LD['tinhtrangkhisinh'][5]; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDCannanglucsinh; ?></nobr>
		</td>
		<td class="adm_input">
			<input type="text" name="cannang" value="<?php echo $row['cannang'] ?>" maxlength="3" size="3">
		</td>
		<td class="adm_input">		
			<input type="checkbox" name="ditatbamsinh" value="1" <?php if($row['ditatbamsinh']!='') echo 'checked' ?> ><?php echo $LDDitat; ?>
		
		</td>
		
		<td class="adm_input">
			<textarea rows=1 cols=30 name="ditatnotes"><?php if(!isset($row['ditatnotes'])) echo $row['ditatnotes']; echo "Không có" ?></textarea>
		</td>
	</tr>		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDPTTinhthan; ?></nobr>
		</td>
		<td class="adm_input">
			<textarea rows=1 cols=30 name="pttinhthan"><?php if(!isset($row['pttinhthan'])) echo $row['pttinhthan']; echo "Bình thường" ;?></textarea>
		</td>
		<td class="adm_item">
			<nobr><?php echo $LDPTVandong; ?></nobr>
		</td>
		<td class="adm_input">
			<textarea rows=1 cols=30 name="ptvandong"><?php if(!isset($row['ptvandong'])) echo $row['ptvandong']; echo "Bình thường"; ?></textarea>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDBenhkhac; ?></nobr>
		</td>
		<td class="adm_input">
			<textarea rows=1 cols=30 name="benhkhac"><?php if(!isset($row['benhkhac'])) echo $row['benhkhac']; echo "Không có" ?></textarea>
		</td>
		<td class="adm_item">
			<nobr><?php echo $LD['nuoiduong'][0]; ?></nobr>
		</td>
		<td class="adm_input">
			<input type="checkbox" name="nuoiduong1" value="1" <?php if($nuoiduong[0]!='')echo 'checked' ?> ><?php echo $LD['nuoiduong'][1]; ?>
			<input type="checkbox" name="nuoiduong2" value="2" <?php if($nuoiduong[1]!='')echo 'checked' ?>><?php echo $LD['nuoiduong'][2]; ?>
			<input type="checkbox" name="nuoiduong3" value="3"<?php if($nuoiduong[2]!='')echo 'checked' ?> ><?php echo $LD['nuoiduong'][3]; ?>
		</td>
		
	</tr >		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDCaisua; ?></nobr>
		</td>
		<td class="adm_input">
			<input type="text" name="thangcaisua" value="<?php echo $row['thangcaisua'] ?>" maxlength="2" size="3">
		</td>
		<td class="adm_item">
			<nobr><?php echo $LD['chamsoc'][0]; ?></nobr>
		</td>
		<td class="adm_input">
			<input type="checkbox" name="chamsoc1" value="1"  <?php if($chamsoc[0]!='')echo 'checked' ?>><?php echo $LD['chamsoc'][1]; ?>
			<input type="checkbox" name="chamsoc2" value="2"  <?php if($chamsoc[1]!='')echo 'checked' ?>><?php echo $LD['chamsoc'][2]; ?>			
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LD['tiemchung'][0]; ?></nobr>
		</td>
		<td colspan=3>
			<input type="checkbox" name="tiemchung1" value="1"  <?php if($tiemchung[0]!='')echo 'checked' ?>><?php echo $LD['tiemchung'][1]; ?>
			<input type="checkbox" name="tiemchung2" value="2"  <?php if($tiemchung[1]!='')echo 'checked' ?>><?php echo $LD['tiemchung'][2]; ?>
			<input type="checkbox" name="tiemchung3" value="3"  <?php if($tiemchung[2]!='')echo 'checked' ?>><?php echo $LD['tiemchung'][3]; ?>
			<input type="checkbox" name="tiemchung4" value="4"  <?php if($tiemchung[3]!='')echo 'checked' ?>><?php echo $LD['tiemchung'][4]; ?>
			<input type="checkbox" name="tiemchung5" value="5"  <?php if($tiemchung[4]!='')echo 'checked' ?>><?php echo $LD['tiemchung'][5]; ?>
			<input type="checkbox" name="tiemchung6" value="6"  <?php if($tiemchung[5]!='')echo 'checked' ?>><?php echo $LD['tiemchung'][6]; ?>
			<input type="checkbox" name="tiemchung7" value="7"  <?php if($tiemchung[6]!='')echo 'checked' ?>><?php echo $LD['tiemchung'][7]; ?>		
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDTiemchungkhac; ?></nobr>
		</td>
		<td colspan=3>
			<input type="text" value="" style="width:96%;" name="tiemchungkhac" >
		</td>
	</tr>
    <tr bgcolor="#f6f6f6">
		<td class="adm_item"><?php echo $LDDate; ?></td>
		<td class="adm_input">
			<?php
				//gjergji : new calendar
				echo $calendar->show_calendar($calendar,$date_format,'date',$row['date']);	
				//end : gjergji
			?>
		</td>
		<td class="adm_item"><?php echo $LDBy; ?></td>
		<td class="adm_input"><input type="text" name="doctor_name" size=30 maxlength=60 value="<?php echo $row['doctor_name']; ?>" readonly>
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
