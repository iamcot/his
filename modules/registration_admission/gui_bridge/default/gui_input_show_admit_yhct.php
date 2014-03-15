<?php
if($pregs) $row=$pregs->FetchRow();
//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();

$hinhthai=explode("_",$row['hinhthai']);
$thansac=explode("_",$row['thansac']);
$luoi=explode("_",$row['luoi']);
$luoinotes=explode("_",$row['luoi_notes']);
$amthanh=explode("_",$row['amthanh']);
$amthanhnotes=explode("_",$row['amthanh_notes']);
$mui=explode("_",$row['mui']);
$muinotes=explode("_",$row['mui_notes']);
$hannhiet=explode("_",$row['hannhiet']);
$hannhietnotes=explode("_",$row['hannhiet_notes']);
$mohoi=explode("_",$row['mohoi']);
$daumat=explode("_",$row['daumat']);
$bungnguc=explode("_",$row['bungnguc']);
$an=explode("_",$row['an']);
$uong=explode("_",$row['uong']);
$daitt=explode("_",$row['daitt']);
$ngu=explode("_",$row['ngu']);
$knsd=explode("_",$row['kn_sd']);
$knsdnotes=explode("_",$row['kn_sd_notes']);
$dkxh=explode("_",$row['dkxh']);
$xucchan=explode("_",$row['xucchan']);
$machchan=explode("_",$row['machchan']);
$chedoan=explode("_",$row['chedoan']);
$chedochamsoc=explode("_",$row['chedochamsoc']);
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
	}else if(d.doctor_name.value==""){
		alert("<?php echo $LDPlsEnterFullName; ?>");
		d.doctor_name.focus();
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
		<td class="adm_item" colspan=4>
			<?php echo $LD['vongchan'][0]; ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;<?php echo $LD['vongchan'][1]; ?>
		</td>
		<td colspan=3>
			<input type="checkbox" name="hinhthai1" value="1"  <?php if($hinhthai[0]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][2]; ?>
			<input type="checkbox" name="hinhthai2" value="2"  <?php if($hinhthai[1]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][3]; ?>
			<input type="checkbox" name="hinhthai3" value="3"  <?php if($hinhthai[2]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][4]; ?>
			<input type="checkbox" name="hinhthai4" value="4"  <?php if($hinhthai[3]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][5]; ?>
			<input type="checkbox" name="hinhthai5" value="5"  <?php if($hinhthai[4]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][6]; ?>
			<input type="checkbox" name="hinhthai6" value="6"  <?php if($hinhthai[5]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][7]; ?>
			<input type="checkbox" name="hinhthai7" value="7"  <?php if($hinhthai[6]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][8]; ?>
			<input type="checkbox" name="hinhthai8" value="8"  <?php if($hinhthai[7]!='')echo 'checked' ?> ><?php echo $LD['vongchan'][9]; ?>
			
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="hinhthai_notes" value="<?php echo $row['hinhthai_notes']; ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;<?php echo $LD['thansac'][0]; ?>
		</td>
		<td class="adm_item">
			&nbsp;<?php echo $LD['thansac'][1]; ?>
		</td>
		<td colspan=2>
			<?php if(!empty($row['tinhtao_radio'])) { ?>
			<input type="radio" name="tinhtao_radio" <?php if($row['tinhtao_radio']=='yes') echo 'checked';?> value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="tinhtao_radio" value="no" <?php if($row['tinhtao_radio']=='no') echo 'checked';?> > <?php echo $LDNo ?>
			<? }else { ?>
			<input type="radio" name="tinhtao_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="tinhtao_radio" value="no" checked> <?php echo $LDNo ?>
			<? } ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;&nbsp;<?php echo $LD['thansac'][2]; ?>
		</td>
		<td colspan=3>
			<input type="checkbox" name="sac1" value="1"  <?php if($thansac[0]!='')echo 'checked' ?> ><?php echo $LD['thansac'][3]; ?>
			<input type="checkbox" name="sac2" value="2"  <?php if($thansac[1]!='')echo 'checked' ?> ><?php echo $LD['thansac'][4]; ?>
			<input type="checkbox" name="sac3" value="3"  <?php if($thansac[2]!='')echo 'checked' ?> ><?php echo $LD['thansac'][5]; ?>
			<input type="checkbox" name="sac4" value="4"  <?php if($thansac[3]!='')echo 'checked' ?> ><?php echo $LD['thansac'][6]; ?>
			<input type="checkbox" name="sac5" value="5"  <?php if($thansac[4]!='')echo 'checked' ?> ><?php echo $LD['thansac'][7]; ?>
			<input type="checkbox" name="sac6" value="6"  <?php if($thansac[5]!='')echo 'checked' ?> ><?php echo $LD['thansac'][8]; ?>
			<input type="checkbox" name="sac7" value="7"  <?php if($thansac[6]!='')echo 'checked' ?> ><?php echo $LD['thansac'][9]; ?>
		</td>
	</tr>
	
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;&nbsp;<?php echo $LD['thansac'][10]; ?>
		</td>
		<td colspan=3>
			<input type="checkbox" name="trach1" value="1"  <?php if($thansac[7]!='')echo 'checked' ?> ><?php echo $LD['thansac'][11]; ?>
			<input type="checkbox" name="trach2" value="2"  <?php if($thansac[8]!='')echo 'checked' ?> ><?php echo $LD['thansac'][12]; ?>
			<input type="checkbox" name="trach3" value="3"  <?php if($thansac[9]!='')echo 'checked' ?> ><?php echo $LD['thansac'][13]; ?>
		</td>
	</tr>

	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="thansac_notes" value="<?php echo $row['thansac_notes']; ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		<nobr>&nbsp;<?php echo $LD['luoi'][0]; ?>&nbsp;&nbsp;&nbsp;<?php echo $LD['luoi'][1]; ?></nobr>
		</td>
		<td colspan=3>
			<input type="checkbox" name="chatluoi1" value="1"  <?php if($luoi[0]!='')echo 'checked' ?> ><?php echo $LD['luoi'][2]; ?>
			<input type="checkbox" name="chatluoi2" value="2"  <?php if($luoi[1]!='')echo 'checked' ?> ><?php echo $LD['luoi'][3]; ?>
			<input type="checkbox" name="chatluoi3" value="3"  <?php if($luoi[2]!='')echo 'checked' ?> ><?php echo $LD['luoi'][4]; ?>
			<input type="checkbox" name="chatluoi4" value="4"  <?php if($luoi[3]!='')echo 'checked' ?> ><?php echo $LD['luoi'][5]; ?>
			<input type="checkbox" name="chatluoi5" value="5"  <?php if($luoi[4]!='')echo 'checked' ?> ><?php echo $LD['luoi'][6]; ?>
			<input type="checkbox" name="chatluoi6" value="6"  <?php if($luoi[5]!='')echo 'checked' ?> ><?php echo $LD['luoi'][7]; ?>
			<input type="checkbox" name="chatluoi7" value="7"  <?php if($luoi[6]!='')echo 'checked' ?> ><?php echo $LD['luoi'][8]; ?>
			<input type="checkbox" name="chatluoi8" value="8"  <?php if($luoi[7]!='')echo 'checked' ?> ><?php echo $LD['luoi'][9]; ?>
			<input type="checkbox" name="chatluoi9" value="9"  <?php if($luoi[8]!='')echo 'checked' ?> ><?php echo $LD['luoi'][10]; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="chatluoi_notes" value="<?php echo $luoinotes[0]; ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		<nobr>&nbsp;&nbsp;<?php echo $LD['luoi'][11]; ?> </nobr>
		</td>
		<td colspan=3>
			<input type="checkbox" name="sacluoi1" value="1"  <?php if($luoi[9]!='')echo 'checked' ?> ><?php echo $LD['luoi'][12]; ?>
			<input type="checkbox" name="sacluoi2" value="2"  <?php if($luoi[10]!='')echo 'checked' ?> ><?php echo $LD['luoi'][13]; ?>
			<input type="checkbox" name="sacluoi3" value="3"  <?php if($luoi[11]!='')echo 'checked' ?> ><?php echo $LD['luoi'][14]; ?>
			<input type="checkbox" name="sacluoi4" value="4"  <?php if($luoi[12]!='')echo 'checked' ?> ><?php echo $LD['luoi'][15]; ?>
			<input type="checkbox" name="sacluoi5" value="5"  <?php if($luoi[13]!='')echo 'checked' ?> ><?php echo $LD['luoi'][16]; ?>
			<input type="checkbox" name="sacluoi6" value="6"  <?php if($luoi[14]!='')echo 'checked' ?> ><?php echo $LD['luoi'][17]; ?>
			<input type="checkbox" name="sacluoi7" value="7"  <?php if($luoi[15]!='')echo 'checked' ?> ><?php echo $LD['luoi'][18]; ?>
			<input type="checkbox" name="sacluoi8" value="8"  <?php if($luoi[16]!='')echo 'checked' ?> ><?php echo $LD['luoi'][19]; ?>
			<input type="checkbox" name="sacluoi9" value="9"  <?php if($luoi[17]!='')echo 'checked' ?> ><?php echo $LD['luoi'][20]; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="sac_luoi" value="<?php echo $luoinotes[1]; ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		<nobr>&nbsp;&nbsp;<?php echo $LD['luoi'][21]; ?> </nobr>
		</td>
		<td colspan=3>
			<input type="checkbox" name="reuluoi1" value="1"  <?php if($luoi[19]!='')echo 'checked' ?> ><?php echo $LD['luoi'][22]; ?>
			<input type="checkbox" name="reuluoi2" value="2"  <?php if($luoi[20]!='')echo 'checked' ?> ><?php echo $LD['luoi'][23]; ?>
			<input type="checkbox" name="reuluoi3" value="3"  <?php if($luoi[21]!='')echo 'checked' ?> ><?php echo $LD['luoi'][24]; ?>
			<input type="checkbox" name="reuluoi4" value="4"  <?php if($luoi[22]!='')echo 'checked' ?> ><?php echo $LD['luoi'][25]; ?>
			<input type="checkbox" name="reuluoi5" value="5"  <?php if($luoi[23]!='')echo 'checked' ?> ><?php echo $LD['luoi'][26]; ?>
			<input type="checkbox" name="reuluoi6" value="6"  <?php if($luoi[24]!='')echo 'checked' ?> ><?php echo $LD['luoi'][27]; ?>
			<input type="checkbox" name="reuluoi7" value="7"  <?php if($luoi[25]!='')echo 'checked' ?> ><?php echo $LD['luoi'][28]; ?>
			<input type="checkbox" name="reuluoi8" value="8"  <?php if($luoi[26]!='')echo 'checked' ?> ><?php echo $LD['luoi'][29]; ?>
			<input type="checkbox" name="reuluoi9" value="9"  <?php if($luoi[27]!='')echo 'checked' ?> ><?php echo $LD['luoi'][30]; ?>
			<input type="checkbox" name="reuluoi10" value="10"  <?php if($luoi[28]!='')echo 'checked' ?> ><?php echo $LD['luoi'][31]; ?>
			<input type="checkbox" name="reuluoi11" value="11"  <?php if($luoi[29]!='')echo 'checked' ?> ><?php echo $LD['luoi'][32]; ?>
			<input type="checkbox" name="reuluoi12" value="12"  <?php if($luoi[30]!='')echo 'checked' ?> ><?php echo $LD['luoi'][33]; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="reuluoi_notes" value="<?php echo $luoinotes[2]; ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item" colspan=4>
		&nbsp;<?php echo $LDBophanbenh; ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="bophan_notes" value="<?php echo $row['bophan_notes']; ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item" colspan=4>
		&nbsp;<?php echo $LDVANCHAN; ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		<nobr>&nbsp;<?php echo $LD['amthanh'][0]; ?>&nbsp;&nbsp;&nbsp;<?php echo $LD['amthanh'][1]; ?></nobr>
		</td>	
		<td colspan=3>
			<input type="checkbox" name="tiengnoi1" value="1" <?php if($amthanh[0]!='')echo 'checked' ?> ><?php echo $LD['amthanh'][2]; ?>
			<input type="checkbox" name="tiengnoi2" value="2" <?php if($amthanh[1]!='')echo 'checked' ?> ><?php echo $LD['amthanh'][3]; ?>
			<input type="checkbox" name="tiengnoi3" value="3" <?php if($amthanh[2]!='')echo 'checked' ?> ><?php echo $LD['amthanh'][4]; ?>
			<input type="checkbox" name="tiengnoi4" value="4" <?php if($amthanh[3]!='')echo 'checked' ?> ><?php echo $LD['amthanh'][5]; ?>
			<input type="checkbox" name="tiengnoi5" value="5" <?php if($amthanh[4]!='')echo 'checked' ?> ><?php echo $LD['amthanh'][6]; ?>
			<input type="checkbox" name="tiengnoi6" value="6" <?php if($amthanh[5]!='')echo 'checked' ?> ><?php echo $LD['amthanh'][7]; ?>
			<input type="checkbox" name="tiengnoi7" value="7" <?php if($amthanh[6]!='')echo 'checked' ?>  ><?php echo $LD['amthanh'][8]; ?>
			<input type="checkbox" name="tiengnoi8" value="8" <?php if($amthanh[7]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][9]; ?>
			<input type="checkbox" name="tiengnoi9" value="9" <?php if($amthanh[8]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][10]; ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="tiengnoi_notes" value="<?php echo $amthanhnotes[0] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		<nobr>&nbsp;&nbsp;<?php echo $LD['amthanh'][11]; ?></nobr>
		</td>	
		<td colspan=3>
			<input type="checkbox" name="hoitho1" value="1" <?php if($amthanh[9]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][2]; ?>
			<input type="checkbox" name="hoitho2" value="2" <?php if($amthanh[10]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][12]; ?>
			<input type="checkbox" name="hoitho3" value="3" <?php if($amthanh[11]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][13]; ?>
			<input type="checkbox" name="hoitho4" value="4" <?php if($amthanh[12]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][14]; ?>
			<input type="checkbox" name="hoitho5" value="5" <?php if($amthanh[13]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][15]; ?>
			<input type="checkbox" name="hoitho6" value="6" <?php if($amthanh[14]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][16]; ?>
			<input type="checkbox" name="hoitho7" value="7" <?php if($amthanh[15]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][17]; ?>
			<input type="checkbox" name="hoitho8" value="8" <?php if($amthanh[16]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][18]; ?>
			<input type="checkbox" name="hoitho9" value="9" <?php if($amthanh[17]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][19]; ?>
			<input type="checkbox" name="hoitho10" value="10" <?php if($amthanh[18]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][20]; ?>		
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="hoitho_notes" value="<?php echo $amthanhnotes[1] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;&nbsp;<?php echo $LD['amthanh'][21]; ?>
		</td>
		<td colspan=3>
		<? if(empty($row['ho_radio'])) { ?>
			<input type="radio" name="ho_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="ho_radio" value="no" checked> <?php echo $LDNo ?>
		<? } else { ?>
			<input type="radio" name="ho_radio" <?php if($row['ho_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="ho_radio" value="no" <?php if($row['ho_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
		</td>
		<td colspan=3>
			<input type="checkbox" name="ho1" value="1" <?php if($amthanh[19]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][22]; ?>
			<input type="checkbox" name="ho2" value="2" <?php if($amthanh[20]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][23]; ?>
			<input type="checkbox" name="ho3" value="3" <?php if($amthanh[21]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][24]; ?>
			<input type="checkbox" name="ho4" value="4" <?php if($amthanh[22]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][25]; ?>
			<input type="checkbox" name="ho5" value="5" <?php if($amthanh[23]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][26]; ?>
			<input type="checkbox" name="ho6" value="6" <?php if($amthanh[24]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][27]; ?>
			<input type="checkbox" name="ho7" value="7" <?php if($amthanh[25]!='')echo 'checked' ?>   ><?php echo $LD['amthanh'][28]; ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="ho_notes" value="<?php echo $amthanhnotes[2] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			&nbsp;&nbsp;<?php echo $LD['amthanh'][29]; ?>
		</td>
		<td colspan=3>
		<?php if(empty($row['onac_radio'])) { ?>
			<input type="radio" name="onac_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="onac_radio" value="no" checked> <?php echo $LDNo ?>
		<? } else { ?>
			<input type="radio" name="onac_radio"  <?php if($row['onac_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="onac_radio" value="no" <?php if($row['onac_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="onac_notes" value="<?php echo $amthanhnotes[3] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;<?php echo $LD['mui'][0]; ?>
		</td>	
		<td class="adm_item">
			<nobr><?php echo $LD['mui'][1]; ?></nobr>
		</td>
		<td colspan=2>			
		<? if(empty($row['mui_radio'])) { ?>
			<input type="radio" name="mui_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="mui_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>
			<input type="radio" name="mui_radio"  <?php if($row['mui_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="mui_radio" value="no"  <?php if($row['mui_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? }?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">	
		<td>
		</td>	
		<td colspan=3>
			<input type="checkbox" name="mui1" value="1" <?php if($mui[0]!='')echo 'checked' ?>   ><?php echo $LD['mui'][2]; ?>
			<input type="checkbox" name="mui2" value="2" <?php if($mui[1]!='')echo 'checked' ?>   ><?php echo $LD['mui'][3]; ?>
			<input type="checkbox" name="mui3" value="3" <?php if($mui[2]!='')echo 'checked' ?>   ><?php echo $LD['mui'][4]; ?>
			<input type="checkbox" name="mui4" value="4" <?php if($mui[3]!='')echo 'checked' ?>   ><?php echo $LD['mui'][5]; ?>
			<input type="checkbox" name="mui5" value="5" <?php if($mui[4]!='')echo 'checked' ?>   ><?php echo $LD['mui'][6]; ?>
			<input type="checkbox" name="mui6" value="6" <?php if($mui[5]!='')echo 'checked' ?>   ><?php echo $LD['mui'][7]; ?>
			<input type="checkbox" name="mui7" value="7" <?php if($mui[6]!='')echo 'checked' ?>   ><?php echo $LD['mui'][8]; ?>		
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="chathai_notes" value="<?php echo $muinotes[0]?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		<nobr>&nbsp;<?php echo $LD['mui'][9]; ?></nobr>
		</td>			
		<td colspan=3>			
		<?if(empty($row['hoinguoi_radio'])) { ?>
			<input type="radio" name="hoinguoi_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="hoinguoi_radio" value="no" checked> <?php echo $LDNo ?>
		<? } else { ?>
			<input type="radio" name="hoinguoi_radio"  <?php if($row['hoinguoi_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="hoinguoi_radio"  <?php if($row['hoinguoi_radio']=='no') echo 'checked';?>  value="no"> <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">	
		<td>
		</td>	
		<td colspan=3>
			<input type="checkbox" name="hoinguoi1" value="1" <?php if($mui[7]!='')echo 'checked' ?>   ><?php echo $LD['mui'][10]; ?>
			<input type="checkbox" name="hoinguoi2" value="2" <?php if($mui[8]!='')echo 'checked' ?>   ><?php echo $LD['mui'][11]; ?>
			<input type="checkbox" name="hoinguoi3" value="3" <?php if($mui[9]!='')echo 'checked' ?>   ><?php echo $LD['mui'][12]; ?>
			<input type="checkbox" name="hoinguoi4" value="4" <?php if($mui[10]!='')echo 'checked' ?>   ><?php echo $LD['mui'][13]; ?>
			<input type="checkbox" name="hoinguoi5" value="5" <?php if($mui[11]!='')echo 'checked' ?>   ><?php echo $LD['mui'][14]; ?>
			<input type="checkbox" name="hoinguoi6" value="6" <?php if($mui[12]!='')echo 'checked' ?>   ><?php echo $LD['mui'][15]; ?>		
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="hoinguoi_notes" value="<?php echo $muinotes[1]?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item" colspan=4>
			<?php echo $LDVAN_CHAN; ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['hannhiet'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['hannhiet'][1]; ?></nobr>
		</td>			
		<td colspan=2>			
		<?php if(empty($row['hannhietbl_radio'])) { ?>
			<input type="radio" name="hannhietbl_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="hannhietbl_radio" value="no" checked> <?php echo $LDNo ?>
		<?php }else{ ?>	
			<input type="radio" name="hannhietbl_radio"  <?php if($row['hannhietbl_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="hannhietbl_radio" value="no" <?php if($row['hannhietbl_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<?php } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">	
		<td>
		</td>	
		<td colspan=3>
			<input type="checkbox" name="hannhietbl1" value="1" <?php if($hannhiet[0]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][2]; ?>
			<input type="checkbox" name="hannhietbl2" value="2" <?php if($hannhiet[1]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][3]; ?>
			<input type="checkbox" name="hannhietbl3" value="3" <?php if($hannhiet[2]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][4]; ?>
			<input type="checkbox" name="hannhietbl4" value="4" <?php if($hannhiet[3]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][5]; ?>
			<input type="checkbox" name="hannhietbl5" value="5" <?php if($hannhiet[4]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][6]; ?>
			<input type="checkbox" name="hannhietbl6" value="6" <?php if($hannhiet[5]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][7]; ?>			
			<input type="checkbox" name="hannhietbl7" value="7" <?php if($hannhiet[6]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][8]; ?>			
			<input type="checkbox" name="hannhietbl8" value="8" <?php if($hannhiet[7]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][9]; ?>			
			<input type="checkbox" name="hannhietbl9" value="9" <?php if($hannhiet[8]!='')echo 'checked' ?>   ><?php echo $LD['hannhiet'][10]; ?>			
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="hannhietbl_notes" value="<?php echo $hannhietnotes[0]?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['hannhiet'][11]; ?></nobr>
		</td>	
					
		<td colspan=3>			
		<? if(empty($row['benhtd_radio'])) { ?>
			<input type="radio" name="benhtd_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="benhtd_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else { ?> 	
			<input type="radio" name="benhtd_radio" <?php if($row['benhtd_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="benhtd_radio" value="no" <?php if($row['benhtd_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="benhtd_notes" value="<?php echo $hannhietnotes[1]?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['mohoi'][0]; ?></nobr>
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="mohoi1" value="1" <?php if($mohoi[0]!='')echo 'checked' ?>   ><?php echo $LD['mohoi'][1]; ?>
			<input type="checkbox" name="mohoi2" value="2" <?php if($mohoi[1]!='')echo 'checked' ?>   ><?php echo $LD['mohoi'][2]; ?>
			<input type="checkbox" name="mohoi3" value="3" <?php if($mohoi[2]!='')echo 'checked' ?>   ><?php echo $LD['mohoi'][3]; ?>
			<input type="checkbox" name="mohoi4" value="4" <?php if($mohoi[3]!='')echo 'checked' ?>   ><?php echo $LD['mohoi'][4]; ?>
			<input type="checkbox" name="mohoi5" value="5" <?php if($mohoi[4]!='')echo 'checked' ?>   ><?php echo $LD['mohoi'][5]; ?>
			<input type="checkbox" name="mohoi6" value="6" <?php if($mohoi[5]!='')echo 'checked' ?>   ><?php echo $LD['mohoi'][6]; ?>		
			<input type="checkbox" name="mohoi7" value="7" <?php if($mohoi[6]!='')echo 'checked' ?>   ><?php echo $LD['mohoi'][7]; ?>				
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="mohoi_notes" value="<?php echo $row['mohoi_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['daumat'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['daumat'][1]; ?></nobr>
		</td>			
		<td colspan=2>		
		<?if(empty($row['daumatbl_radio'])) {?>		
			<input type="radio" name="daumatbl_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="daumatbl_radio" value="no" checked> <?php echo $LDNo ?>
		<? } else { ?>	
			<input type="radio" name="daumatbl_radio" <?php if($row['daumatbl_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="daumattbl_radio" value="no" <?php if($row['daumatbl_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['daumat'][2]; ?></nobr>
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="daudau1" value="1" <?php if($daumat[0]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][3]; ?>
			<input type="checkbox" name="daudau2" value="2" <?php if($daumat[1]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][4]; ?>
			<input type="checkbox" name="daudau3" value="3" <?php if($daumat[2]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][5]; ?>
			<input type="checkbox" name="daudau4" value="4" <?php if($daumat[3]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][6]; ?>
			<input type="checkbox" name="daudau5" value="5" <?php if($daumat[4]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][7]; ?>
			<input type="checkbox" name="daudau6" value="6" <?php if($daumat[5]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][8]; ?>	
			<input type="checkbox" name="daudau7" value="7" <?php if($daumat[6]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][9]; ?>	
			<input type="checkbox" name="daudau8" value="8" <?php if($daumat[7]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][10]; ?>	
			<input type="checkbox" name="daudau9" value="9" <?php if($daumat[8]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][11]; ?>	
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['daumat'][12]; ?></nobr>
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="daudau10" value="10" <?php if($daumat[9]!='')echo 'checked' ?> ><?php echo $LD['daumat'][13]; ?>			
			<input type="checkbox" name="daudau11" value="11" <?php if($daumat[10]!='')echo 'checked' ?> ><?php echo $LD['daumat'][14]; ?>			
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['daumat'][15]; ?></nobr>
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="daudau12" value="12" <?php if($daumat[11]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][16]; ?>			
			<input type="checkbox" name="daudau13" value="13" <?php if($daumat[12]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][17]; ?>			
			<input type="checkbox" name="daudau14" value="14" <?php if($daumat[13]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][18]; ?>			
			<input type="checkbox" name="daudau15" value="15" <?php if($daumat[14]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][19]; ?>		
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['daumat'][20]; ?></nobr>
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="daudau16" value="16" <?php if($daumat[15]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][21]; ?>			
			<input type="checkbox" name="daudau17" value="17" <?php if($daumat[16]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][22]; ?>			
			<input type="checkbox" name="daudau18" value="18" <?php if($daumat[17]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][23]; ?>	
			<input type="checkbox" name="daudau19" value="19" <?php if($daumat[18]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][24]; ?>	
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['daumat'][25]; ?></nobr>
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="daudau20" value="20" <?php if($daumat[19]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][26]; ?>			
			<input type="checkbox" name="daudau21" value="21" <?php if($daumat[20]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][27]; ?>			
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['daumat'][28]; ?></nobr>
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="daudau22" value="22" <?php if($daumat[21]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][29]; ?>			
			<input type="checkbox" name="daudau23" value="23" <?php if($daumat[22]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][30]; ?>			
			<input type="checkbox" name="daudau24" value="24" <?php if($daumat[23]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][31]; ?>			
			<input type="checkbox" name="daudau25" value="25" <?php if($daumat[24]!='')echo 'checked' ?>   ><?php echo $LD['daumat'][32]; ?>			
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="daumat_notes" value="<?php echo $row['daumat_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['lung'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['lung'][1]; ?></nobr>
		</td>			
		<td colspan=2>	
		<? if(empty($row['lungbl_radio'])) {?>
			<input type="radio" name="lungbl_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="lungbl_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>
			<input type="radio" name="lungbl_radio" <?php if($row['lungbl_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="lungbl_radio" value="no" <?php if($row['lungbl_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="lung_notes" value="<?php echo $row['lung_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['bungnguc'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['bungnguc'][1]; ?></nobr>
		</td>			
		<td colspan=2>	
		<? if(empty($row['bungnguc_radio'])) {?>			
			<input type="radio" name="bungnguc_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="bungnguc_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>
			<input type="radio" name="bungnguc_radio" <?php if($row['bungnguc_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="bungnguc_radio" value="no" <?php if($row['bungnguc_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="bungnguc1" value="1" <?php if($bungnguc[0]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][2]; ?>			
			<input type="checkbox" name="bungnguc2" value="2" <?php if($bungnguc[1]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][3]; ?>			
			<input type="checkbox" name="bungnguc3" value="3" <?php if($bungnguc[2]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][4]; ?>		
			<input type="checkbox" name="bungnguc4" value="4" <?php if($bungnguc[3]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][5]; ?>		
			<input type="checkbox" name="bungnguc5" value="5" <?php if($bungnguc[4]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][6]; ?>		
			<input type="checkbox" name="bungnguc6" value="6" <?php if($bungnguc[5]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][7]; ?>		
			<input type="checkbox" name="bungnguc7" value="7" <?php if($bungnguc[6]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][8]; ?>		
			<input type="checkbox" name="bungnguc8" value="8" <?php if($bungnguc[7]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][9]; ?>		
			<input type="checkbox" name="bungnguc9" value="9" <?php if($bungnguc[8]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][10]; ?>	
			<input type="checkbox" name="bungnguc10" value="10" <?php if($bungnguc[9]!='')echo 'checked' ?>   ><?php echo $LD['bungnguc'][11]; ?>	
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="bungnguc_notes"  value="<?php echo $row['bungnguc_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['chantay'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['chantay'][1]; ?></nobr>
		</td>			
		<td colspan=2>		
		<? if(empty($row['chantay_radio'])) {?>			
			<input type="radio" name="chantay_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="chantay_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>
			<input type="radio" name="chantay_radio" <?php if($row['chantay_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="chantay_radio" value="no" <?php if($row['chantay_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="chantay_notes" value="<?php echo $row['chantay_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['an'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['an'][1]; ?></nobr>
		</td>			
		<td colspan=2>	
		<? if(empty($row['an_radio'])) {?>		
			<input type="radio" name="an_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="an_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>
			<input type="radio" name="an_radio" <?php if($row['an_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="an_radio" value="no" <?php if($row['an_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			
		</td>	
					
		<td colspan=3>
			<input type="checkbox" name="an1" value="1" <?php if($an[0]!='')echo 'checked' ?>   ><?php echo $LD['an'][2]; ?>			
			<input type="checkbox" name="an2" value="2" <?php if($an[1]!='')echo 'checked' ?>   ><?php echo $LD['an'][3]; ?>			
			<input type="checkbox" name="an3" value="3" <?php if($an[2]!='')echo 'checked' ?>   ><?php echo $LD['an'][4]; ?>		
			<input type="checkbox" name="an4" value="4" <?php if($an[3]!='')echo 'checked' ?>   ><?php echo $LD['an'][5]; ?>		
			<input type="checkbox" name="an5" value="5" <?php if($an[4]!='')echo 'checked' ?>   ><?php echo $LD['an'][6]; ?>		
			<input type="checkbox" name="an6" value="6" <?php if($an[5]!='')echo 'checked' ?>   ><?php echo $LD['an'][7]; ?>		
			<input type="checkbox" name="an7" value="7" <?php if($an[6]!='')echo 'checked' ?>   ><?php echo $LD['an'][8]; ?>		
			<input type="checkbox" name="an8" value="8" <?php if($an[7]!='')echo 'checked' ?>   ><?php echo $LD['an'][9]; ?>		
			<input type="checkbox" name="an9" value="9" <?php if($an[8]!='')echo 'checked' ?>   ><?php echo $LD['an'][10]; ?>		
			<input type="checkbox" name="an10" value="10" <?php if($an[9]!='')echo 'checked' ?>   ><?php echo $LD['an'][11]; ?>		
			<input type="checkbox" name="an11" value="11" <?php if($an[10]!='')echo 'checked' ?>   ><?php echo $LD['an'][12]; ?>		
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="an_notes" value="<?php echo $row['an_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['uong'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['uong'][1]; ?></nobr>
		</td>			
		<td colspan=2>		
			<? if(empty($row['uong_radio'])) {?>
			<input type="radio" name="uong_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="uong_radio" value="no" checked> <?php echo $LDNo ?>
			<? }else{ ?>
			<input type="radio" name="uong_radio" <?php if($row['uong_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="uong_radio" value="no" <?php if($row['uong_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>			
		</td>						
		<td colspan=3>
			<input type="checkbox" name="uong1" value="1" <?php if($uong[0]!='')echo 'checked' ?>   ><?php echo $LD['uong'][2]; ?>			
			<input type="checkbox" name="uong2" value="2" <?php if($uong[1]!='')echo 'checked' ?>   ><?php echo $LD['uong'][3]; ?>			
			<input type="checkbox" name="uong3" value="3" <?php if($uong[2]!='')echo 'checked' ?>   ><?php echo $LD['uong'][4]; ?>		
			<input type="checkbox" name="uong4" value="4" <?php if($uong[3]!='')echo 'checked' ?>   ><?php echo $LD['uong'][5]; ?>		
			<input type="checkbox" name="uong5" value="5" <?php if($uong[4]!='')echo 'checked' ?>   ><?php echo $LD['uong'][6]; ?>		
			<input type="checkbox" name="uong6" value="6" <?php if($uong[5]!='')echo 'checked' ?>   ><?php echo $LD['uong'][7]; ?>		
			<input type="checkbox" name="uong7" value="7" <?php if($uong[6]!='')echo 'checked' ?>   ><?php echo $LD['uong'][8]; ?>	
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="uong_notes" value="<?php echo $row['uong_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['daitieutien'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['uong'][1]; ?></nobr>
		</td>			
		<td colspan=2>		
			<? if(empty($row['daitt_radio'])) {?>
			<input type="radio" name="daitt_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="daitt_radio" value="no" checked> <?php echo $LDNo ?>
			<? }else{ ?>
			<input type="radio" name="daitt_radio" <?php if($row['daitt_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="daitt_radio" value="no" <?php if($row['daitt_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['daitieutien'][1]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="tieutien1" value="1" <?php if($daitt[0]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][2]; ?>			
			<input type="checkbox" name="tieutien2" value="2" <?php if($daitt[1]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][3]; ?>			
			<input type="checkbox" name="tieutien3" value="3" <?php if($daitt[2]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][4]; ?>		
			<input type="checkbox" name="tieutien4" value="4" <?php if($daitt[3]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][5]; ?>		
			<input type="checkbox" name="tieutien5" value="5" <?php if($daitt[4]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][6]; ?>		
			<input type="checkbox" name="tieutien6" value="6" <?php if($daitt[5]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][7]; ?>		
			<input type="checkbox" name="tieutien7" value="7" <?php if($daitt[6]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][8]; ?>	
			<input type="checkbox" name="tieutien8" value="8" <?php if($daitt[7]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][9]; ?>	
			<input type="checkbox" name="tieutien9" value="9" <?php if($daitt[8]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][10]; ?>	
			<input type="checkbox" name="tieutien10" value="10" <?php if($daitt[9]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][11]; ?>	
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['daitieutien'][12]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="daitien1" value="1" <?php if($daitt[10]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][13]; ?>			
			<input type="checkbox" name="daitien2" value="2" <?php if($daitt[11]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][14]; ?>			
			<input type="checkbox" name="daitien3" value="3" <?php if($daitt[12]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][15]; ?>		
			<input type="checkbox" name="daitien4" value="4" <?php if($daitt[13]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][16]; ?>		
			<input type="checkbox" name="daitien5" value="5" <?php if($daitt[14]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][17]; ?>		
			<input type="checkbox" name="daitien6" value="6" <?php if($daitt[15]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][18]; ?>		
			<input type="checkbox" name="daitien7" value="7" <?php if($daitt[16]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][19]; ?>	
			<input type="checkbox" name="daitien8" value="8" <?php if($daitt[17]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][20]; ?>	
			<input type="checkbox" name="daitien9" value="9" <?php if($daitt[18]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][21]; ?>	
			<input type="checkbox" name="daitien10" value="10" <?php if($daitt[19]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][22]; ?>	
			<input type="checkbox" name="daitien11" value="11" <?php if($daitt[20]!='')echo 'checked' ?>   ><?php echo $LD['daitieutien'][23]; ?>	
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="daitt_notes" value="<?php echo $row['daitt_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['ngu'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['uong'][1]; ?></nobr>
		</td>			
		<td colspan=2>		
		<? if(empty($row['ngu_radio'])) {?>
			<input type="radio" name="ngu_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="ngu_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>
			<input type="radio" name="ngu_radio" <?php if($row['ngu_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="ngu_radio" value="no" <?php if($row['ngu_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			
		</td>						
		<td colspan=3>
			<input type="checkbox" name="ngu1" value="1" <?php if($ngu[0]!='')echo 'checked' ?>   ><?php echo $LD['ngu'][1]; ?>			
			<input type="checkbox" name="ngu2" value="2" <?php if($ngu[1]!='')echo 'checked' ?>   ><?php echo $LD['ngu'][2]; ?>			
			<input type="checkbox" name="ngu3" value="3" <?php if($ngu[2]!='')echo 'checked' ?>   ><?php echo $LD['ngu'][3]; ?>		
			<input type="checkbox" name="ngu4" value="4" <?php if($ngu[3]!='')echo 'checked' ?>   ><?php echo $LD['ngu'][4]; ?>		
			<input type="checkbox" name="ngu5" value="5" <?php if($ngu[4]!='')echo 'checked' ?>   ><?php echo $LD['ngu'][5]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="ngu_notes" value="<?php echo $row['ngu_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][0]; ?></nobr>
		</td>	
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][1]; ?></nobr>
		</td>			
		<td colspan=2>		
			<? if(empty($row['kn_sd_radio'])) {?>
			<input type="radio" name="kn_sd_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="kn_sd_radio" value="no" checked> <?php echo $LDNo ?>
			<? }else{ ?>
			<input type="radio" name="kn_sd_radio"  <?php if($row['kn_sd_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="kn_sd_radio" value="no" <?php if($row['kn_sd_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
			<? } ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][2]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="roiloankn1" value="1" <?php if($knsd[0]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][3]; ?>			
			<input type="checkbox" name="roiloankn2" value="2" <?php if($knsd[1]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][4]; ?>			
			<input type="checkbox" name="roiloankn3" value="3" <?php if($knsd[2]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][5]; ?>		
			<input type="checkbox" name="roiloankn4" value="4" <?php if($knsd[3]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][6]; ?>		
			<input type="checkbox" name="roiloankn5" value="5" <?php if($knsd[4]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][7]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][8]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="roiloankn6" value="1" <?php if($knsd[5]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][9]; ?>			
			<input type="checkbox" name="roiloankn7" value="2" <?php if($knsd[6]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][10]; ?>			
			<input type="checkbox" name="roiloankn8" value="3" <?php if($knsd[7]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][11]; ?>		
			<input type="checkbox" name="roiloankn9" value="4" <?php if($knsd[8]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][12]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][13]; ?></nobr>
		</td>						
		<td colspan=3>
		<? if(empty($row['doiha_radio'])) { ?>
			<input type="radio" name="doiha_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="doiha_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>	
			<input type="radio" name="doiha_radio" <?php if($row['doiha_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="doiha_radio" value="no" <?php if($row['doiha_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			
		</td>						
		<td colspan=3>
			<input type="checkbox" name="doiha1" value="1" <?php if($knsd[9]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][14]; ?>			
			<input type="checkbox" name="doiha2" value="2" <?php if($knsd[10]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][15]; ?>			
			<input type="checkbox" name="doiha3" value="3" <?php if($knsd[11]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][16]; ?>		
			<input type="checkbox" name="doiha4" value="4" <?php if($knsd[12]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][17]; ?>
			<input type="checkbox" name="doiha5" value="5" <?php if($knsd[13]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][18]; ?>
			<input type="checkbox" name="doiha6" value="6" <?php if($knsd[14]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][19]; ?>
			<input type="checkbox" name="doiha7" value="7" <?php if($knsd[15]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][20]; ?>
			<input type="checkbox" name="doiha8" value="8" <?php if($knsd[16]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][21]; ?>
			<input type="checkbox" name="doiha9" value="9" <?php if($knsd[17]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][22]; ?>
			<input type="checkbox" name="doiha10" value="10" <?php if($knsd[18]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][23]; ?>
		</td>	
	</tr><tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="kn_notes" value="<?php echo $knsdnotes[0] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][24]; ?></nobr>
		</td>						
		<td colspan=3>
		<? if(empty($row['sd_radio'])) { ?>
			<input type="radio" name="sd_radio" value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="sd_radio" value="no" checked> <?php echo $LDNo ?>
		<? }else{ ?>
			<input type="radio" name="sd_radio" <?php if($row['sd_radio']=='yes') echo 'checked';?>  value="yes" ><?php echo $LDYes1 ?> <input type="radio" name="sd_radio" value="no" <?php if($row['sd_radio']=='no') echo 'checked';?>  > <?php echo $LDNo ?>
		<? } ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][25]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="nam_sd1" value="1" <?php if($knsd[19]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][26]; ?>			
			<input type="checkbox" name="nam_sd2" value="2" <?php if($knsd[20]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][27]; ?>			
			<input type="checkbox" name="nam_sd3" value="3" <?php if($knsd[21]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][28]; ?>		
			<input type="checkbox" name="nam_sd4" value="4" <?php if($knsd[22]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][29]; ?>
			<input type="checkbox" name="nam_sd5" value="5" <?php if($knsd[23]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][30]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['kn_sd'][31]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="nu_sd1" value="1" <?php if($knsd[24]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][32]; ?>			
			<input type="checkbox" name="nu_sd2" value="2" <?php if($knsd[25]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][33]; ?>			
			<input type="checkbox" name="nu_sd3" value="3" <?php if($knsd[26]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][34]; ?>		
			<input type="checkbox" name="nu_sd4" value="4" <?php if($knsd[27]!='')echo 'checked' ?>   ><?php echo $LD['kn_sd'][35]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="sd_notes" value="<?php echo $knsdnotes[1] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['dkxuathien'][0]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="dkxuathien1" value="1" <?php if($dkxh[0]!='')echo 'checked' ?>   ><?php echo $LD['dkxuathien'][1]; ?>			
			<input type="checkbox" name="dkxuathien2" value="2" <?php if($dkxh[1]!='')echo 'checked' ?>   ><?php echo $LD['dkxuathien'][2]; ?>			
			<input type="checkbox" name="dkxuathien3" value="3" <?php if($dkxh[2]!='')echo 'checked' ?>   ><?php echo $LD['dkxuathien'][3]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="dkxh_notes" value="<?php echo $row['dkxh_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item" colspan=4>
			<nobr><?php echo $LDTHIETCHAN; ?></nobr>
		</td>						
			
	</tr>
	<tr bgcolor="#f6f6f6">
		<td colspan=4 class="adm_item">
			<nobr>&nbsp;<?php echo $LD['xucchan'][0]; ?></nobr>
		</td>					
		
	</tr>
		<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['xucchan'][1]; ?></nobr>
		</td>						
		<td colspan=3>
				<input type="checkbox" name="xucchan1" value="1" <?php if($xucchan[0]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][2]; ?>			
			<input type="checkbox" name="xucchan2" value="2" <?php if($xucchan[1]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][3]; ?>			
			<input type="checkbox" name="xucchan3" value="3" <?php if($xucchan[2]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][4]; ?>
			<input type="checkbox" name="xucchan4" value="4" <?php if($xucchan[3]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][5]; ?>
			<input type="checkbox" name="xucchan5" value="5" <?php if($xucchan[4]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][6]; ?>
			<input type="checkbox" name="xucchan6" value="6" <?php if($xucchan[5]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][7]; ?>
			<input type="checkbox" name="xucchan7" value="7" <?php if($xucchan[6]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][8]; ?>
			<input type="checkbox" name="xucchan8" value="8" <?php if($xucchan[7]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][9]; ?>
			<input type="checkbox" name="xucchan9" value="9" <?php if($xucchan[8]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][10]; ?>
			<input type="checkbox" name="xucchan10" value="10" <?php if($xucchan[9]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][11]; ?>
			<input type="checkbox" name="xucchan11" value="11" <?php if($xucchan[10]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][12]; ?>			
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['xucchan'][13]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="mohoi1" value="1" <?php if($xucchan[12]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][14]; ?>			
			<input type="checkbox" name="mohoi2" value="2" <?php if($xucchan[13]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][15]; ?>			
			<input type="checkbox" name="mohoi3" value="3" <?php if($xucchan[14]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][16]; ?>
			<input type="checkbox" name="mohoi4" value="4" <?php if($xucchan[15]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][17]; ?>
			</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['xucchan'][18]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="conhuc1" value="1" <?php if($xucchan[12]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][19]; ?>			
			<input type="checkbox" name="conhuc2" value="2" <?php if($xucchan[13]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][20]; ?>			
			<input type="checkbox" name="conhuc3" value="3" <?php if($xucchan[14]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][21]; ?>
			<input type="checkbox" name="conhuc4" value="4" <?php if($xucchan[15]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][22]; ?>
			<input type="checkbox" name="conhuc5" value="5" <?php if($xucchan[16]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][23]; ?>
			<input type="checkbox" name="conhuc6" value="6" <?php if($xucchan[17]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][24]; ?>
			<input type="checkbox" name="conhuc7" value="7" <?php if($xucchan[18]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][25]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['xucchan'][26]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="checkbox" name="bung1" value="1" <?php if($xucchan[19]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][27]; ?>			
			<input type="checkbox" name="bung2" value="2" <?php if($xucchan[20]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][28]; ?>			
			<input type="checkbox" name="bung3" value="3" <?php if($xucchan[21]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][29]; ?>
			<input type="checkbox" name="bung4" value="4" <?php if($xucchan[22]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][30]; ?>
			<input type="checkbox" name="bung5" value="5" <?php if($xucchan[23]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][31]; ?>
			<input type="checkbox" name="bung6" value="6" <?php if($xucchan[24]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][32]; ?>
			<input type="checkbox" name="bung7" value="7" <?php if($xucchan[25]!='')echo 'checked' ?>   ><?php echo $LD['xucchan'][33]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="xucchan_notes" value="<?php echo $row['xucchan_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;<?php echo $LD['machchan'][0]; ?></nobr>
		</td>						
		<td colspan=3>
			<?php echo $LD['machchan'][1]; ?>			
			<?php echo $LD['machchan'][2]; ?>			
			<?php echo $LD['machchan'][3]; ?>
			<?php echo $LD['machchan'][4]; ?>
			<?php echo $LD['machchan'][5]; ?>
			<?php echo $LD['machchan'][6]; ?>
			<?php echo $LD['machchan'][7]; ?>
			<?php echo $LD['machchan'][8]; ?>
			<?php echo $LD['machchan'][9]; ?>
			<?php echo $LD['machchan'][10]; ?>
			<?php echo $LD['machchan'][11]; ?>
			<?php echo $LD['machchan'][12]; ?>
			<?php echo $LD['machchan'][13]; ?>
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['machchan'][14]; ?></nobr>
		</td>	
		
		<td colspan=3>
		<nobr>
			<input type="text" size="2" name="machtaytrai1" value="<?php echo $machchan[0]?>" >			
			<input type="text" size="2" name="machtaytrai2" value="<?php echo $machchan[1]?>">		
			<input type="text" size="2" name="machtaytrai3" value="<?php echo $machchan[2]?>"><?php echo $LD['machchan'][15]; ?>			
			<input type="text" size="2" name="machtaytrai4" value="<?php echo $machchan[3]?>">		
			<input type="text" size="2" name="machtaytrai5" value="<?php echo $machchan[4]?>">		
			<input type="text" size="2" name="machtaytrai6" value="<?php echo $machchan[5]?>"><?php echo $LD['machchan'][16]; ?>			
			<input type="text" size="2" name="machtaytrai7" value="<?php echo $machchan[6]?>" >			
			<input type="text" size="2" name="machtaytrai8" value="<?php echo $machchan[7]?>">				
			<input type="text" size="2" name="machtaytrai9" value="<?php echo $machchan[8]?>"><?php echo $LD['machchan'][17]; ?>					
		</nobr>
		</td>	
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['machchan'][18]; ?></nobr>
		</td>						
		<td colspan=3>
			<input type="text" size="2" name="machtayphai1" value="<?php echo $machchan[9]?>">		
			<input type="text" size="2" name="machtayphai2" value="<?php echo $machchan[10]?>">			
			<input type="text" size="2" name="machtayphai3" value="<?php echo $machchan[11]?>"><?php echo $LD['machchan'][15]; ?>			
			<input type="text" size="2" name="machtayphai4" value="<?php echo $machchan[12]?>">		
			<input type="text" size="2" name="machtayphai5" value="<?php echo $machchan[13]?>">		
			<input type="text" size="2" name="machtayphai6" value="<?php echo $machchan[14]?>"><?php echo $LD['machchan'][16]; ?>			
			<input type="text" size="2" name="machtayphai7" value="<?php echo $machchan[15]?>">		
			<input type="text" size="2" name="machtayphai8" value="<?php echo $machchan[16]?>">			
			<input type="text" size="2" name="machtayphai9" value="<?php echo $machchan[17]?>"><?php echo $LD['machchan'][17]; ?>			
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>&nbsp;&nbsp;<?php echo $LD['machchan'][19]; ?></nobr>
		</td>	
				
		<td colspan=3>
		<? if(empty($row['tongkhan'])){?>
			<textarea name="tongkhan" style="width:96%;"><?php echo $LD['machchan'][20]."\n"; ?>
<?php echo $LD['machchan'][21]; ?></textarea>	
		<? } else { ?>
		<textarea name="tongkhan" style="width:96%;"><?php echo $row['tongkhan']; ?>
</textarea>	
		<? } ?>
		</td>	
	</tr>
	
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		&nbsp;&nbsp;<?php echo $LDMota; ?>
		</td>
		<td colspan=3>
			<input type="text" name="machchan_notes" value="<?php echo $row['machchan_notes'] ?>" style="width:96%;">
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item" colspan=4>
			V.TÓM TẮT TỨ CHẨN & BIỆN CHỨNG LUẬN TRỊ
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_input" colspan="4">
		<? if(empty($row['chandoan'])){?>
			<textarea rows=4 style="width:98%;" name="bienchung"></textarea>
			<? } else { ?>
			<textarea rows=4 style="width:98%;" name="bienchung"><?php echo $row['bienchung'] ?></textarea>
			<? } ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item" colspan=4>
			VI.CHẨN ĐOÁN
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_input" colspan="4">
		<? if(empty($row['chandoan'])){?>
			<textarea rows=4 style="width:98%;" name="chandoan">
1-Bệnh danh:
2-Chẩn đoán bát cương:
3-Chẩn đoán tạng phủ,kinh lạc:
4-Chẩn đoán nguyên nhân:
			</textarea>
			<? } else { ?>
			<textarea rows=4 style="width:98%;" name="chandoan">
<?php echo $row['chandoan'] ?>	
			</textarea>
			<? } ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item" colspan=4>
			VII.ĐIỀU TRỊ
		</td>
		
	</tr>
		<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			1.Điều trị đơn thuần YHCT
		</td>
		<td>
		<? if(empty($row['dieutri_radio'])) { ?>
			<input type="radio" name="dieutri_radio" value="yes" >
		</td>
		<td class="adm_item">
			Điều trị kết hợp YHCT với YHHĐ
		</td>
		<td><input type="radio" name="dieutri_radio" value="no" checked></td>
		<? }else{ ?>	
			<input type="radio" name="dieutri_radio" <?php if($row['dieutri_radio']=='yes') echo 'checked';?>  value="yes" >
		</td> 
		<td class="adm_item">
				Điều trị kết hợp YHCT với YHHĐ
		</td>
		<td>
			<input type="radio" name="dieutri_radio" value="no" <?php if($row['dieutri_radio']=='no') echo 'checked';?>  > 
		</td>
		<? } ?>
		
	</tr>
	
	<tr bgcolor="#f6f6f6">
		<td class="adm_input" colspan="4">
		<? if(empty($row['dieutri'])){?>
			<textarea rows=4 style="width:98%;" name="dieutri">
1-Pháp chữa:
2-Phương thuốc:
3-Phương huyệt:
4-Xoa bóp dưỡng sinh:
			</textarea>
			<? } else { ?>
			<textarea rows=4 style="width:98%;" name="dieutri">
<?php echo $row['dieutri'] ?>	
			</textarea>
			<? } ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		2.Chế độ ăn
		</td>
		<td class="adm_input" colspan=3>
			<input type="checkbox" name="chedoan1" value="1" <?php if($chedoan[0]!='')echo 'checked' ?>   >1.Lỏng		
			<input type="checkbox" name="chedoan2" value="2" <?php if($chedoan[1]!='')echo 'checked' ?>   >2.Nữa lỏng			
			<input type="checkbox" name="chedoan3" value="3" <?php if($chedoan[2]!='')echo 'checked' ?>   >3.Đặc
			<input type="checkbox" name="chedoan4" value="4" <?php if($chedoan[3]!='')echo 'checked' ?>   >4.Tự do
			<input type="checkbox" name="chedoan5" value="5" <?php if($chedoan[4]!='')echo 'checked' ?>   >5.Kiêng muối, mỡ, đường
			<input type="checkbox" name="chedoan6" value="6" <?php if($chedoan[5]!='')echo 'checked' ?>   >6.Khác
		</td>	
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
		3.Chế độ chăm soc
		</td>
		<td class="adm_input" colspan=3>
			<input type="checkbox" name="chedochamsoc1" value="1" <?php if($chedochamsoc[0]!='')echo 'checked' ?>   >1.Cấp I		
			<input type="checkbox" name="chedochamsoc2" value="2" <?php if($chedochamsoc[1]!='')echo 'checked' ?>   >2.Cấp II			
			<input type="checkbox" name="chedochamsoc3" value="3" <?php if($chedochamsoc[2]!='')echo 'checked' ?>   >3.Cấp III
			
		</td>	
	</tr>
    <tr bgcolor="#f6f6f6">
		<td class="adm_item"><?php echo $LDDate; ?></td>
		<td style="width:115px">
			<?php
				//gjergji : new calendar
				echo $calendar->show_calendar($calendar,$date_format,'date',$row['date']);	
				//end : gjergji
			?>
		</td>
		<td class="adm_item"><nobr><?php echo $LDBy; ?></nobr></td>
		<td><nobr><input type="text" name="doctor_name" style="width:90%;" maxlength=60 value="<?php echo $row['doctor_name']?>" readonly >
			<input type="hidden" name="doctor_nr" size=30 maxlength=60 value="<?php echo $row['doctor_nr']?>" >
			<a href="javascript:popDocPer('doctor_nr')"><img <?php echo createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE) ?>></nobr></td>
	</tr>      
 </table>
<input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
<input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
<input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
<input type="hidden" name="modify_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_id" value="<?php echo $_SESSION['sess_user_name']; ?>">
<input type="hidden" name="create_time" value="null">
<input type="hidden" name="mode" value="newdata">
<input type="hidden" name="type_nr" value="<?php echo $type_nr; ?>">
<input type="hidden" name="target" value="<?php echo $target; ?>">
<input type="hidden" name="history" value="Created: <?php echo date('Y-m-d H:i:s'); ?> : <?php echo $_SESSION['sess_user_name']."\n"; ?>">


</form>
