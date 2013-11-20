<?php 
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
if($rows){
 $row=$pregs->FetchRow();
}
$tienthai=explode("_",$row['tienthai']);
$tinhtrangkhisinh=explode("_",$row['tinhtrangkhisinh']);
$nuoiduong=explode("_",$row['nuoiduong']);
$chamsoc=explode("_",$row['chamsoc']);
$tiemchung=explode("_",$row['tiemchung']);
?>
<br/>
<table border=0 cellpadding=2 width=100%>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDConthu; ?>
		</td>
		<td class="adm_input">
			<?php echo $row['conthu'] ?>
		</td>
		<td class="adm_item">
			<nobr><?php echo $LD['tienthai'][0]; ?></nobr>
		</td>
		<td class="adm_input">
			<nobr><input type="text" name="tienthai1" size="2" value="<?php echo $tienthai[0] ?>" disabled><?php echo $LD['tienthai'][1]; ?>
			<input type="text" name="tienthai2" size="2" value="<?php echo $tienthai[1] ?>" disabled><?php echo $LD['tienthai'][2]; ?></nobr>
			<input type="text" name="tienthai3" size="2" value="<?php  echo $tienthai[2] ?>" disabled><?php echo $LD['tienthai'][3]; ?>
			<input type="text" name="tienthai4" size="2" value="<?php  echo $tienthai[3] ?>" disabled><?php echo $LD['tienthai'][4]; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDTinhtrangkhisinh; ?></nobr>
		</td>
		<td colspan=3>
			<input type="checkbox" name="tinhtrangkhisinh1" value="1"  <?php if($tinhtrangkhisinh[0]!='')echo 'checked' ?> disabled><?php echo $LD['tinhtrangkhisinh'][0]; ?>
			<input type="checkbox" name="tinhtrangkhisinh2" value="2"  <?php if($tinhtrangkhisinh[1]!='')echo 'checked' ?> disabled><?php echo $LD['tinhtrangkhisinh'][1]; ?>
			<input type="checkbox" name="tinhtrangkhisinh3" value="3"  <?php if($tinhtrangkhisinh[2]!='')echo 'checked' ?> disabled><?php echo $LD['tinhtrangkhisinh'][2]; ?>
			<input type="checkbox" name="tinhtrangkhisinh4" value="4"  <?php if($tinhtrangkhisinh[3]!='')echo 'checked' ?> disabled><?php echo $LD['tinhtrangkhisinh'][3]; ?>
			<input type="checkbox" name="tinhtrangkhisinh5" value="5"  <?php if($tinhtrangkhisinh[4]!='')echo 'checked' ?> disabled><?php echo $LD['tinhtrangkhisinh'][4]; ?>
			<input type="checkbox" name="tinhtrangkhisinh6" value="6"  <?php if($tinhtrangkhisinh[5]!='')echo 'checked' ?> disabled><?php echo $LD['tinhtrangkhisinh'][5]; ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDCannanglucsinh; ?></nobr>
		</td>
		<td class="adm_input">
			<?php echo $row['cannang'] ?>
		</td>
		<td class="adm_input">		
			<input type="checkbox" name="ditatbamsinh" value="1" <?php if($row['ditatbamsinh']!='') echo 'checked' ?>  disabled ><?php echo $LDDitat; ?>
		
		</td>
		<td class="adm_input">
			<?php echo $row['ditatnotes'] ?>
		</td>
	</tr>		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDPTTinhthan; ?></nobr>
		</td>
		<td class="adm_input">
			<nobr><?php echo $row['pttinhthan'] ?></nobr>
		</td>
		<td class="adm_item">
			<nobr><?php echo $LDPTVandong; ?></nobr>
		</td>
		<td class="adm_input">
		<nobr>	<?php echo $row['ptvandong'] ?></nobr>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDBenhkhac; ?></nobr>
		</td>
		<td class="adm_input">
			<?php echo $row['benhkhac'] ?>
		</td>
		<td class="adm_item">
			<nobr><?php echo $LD['nuoiduong'][0]; ?></nobr>
		</td>
		<td class="adm_input">
			<input type="checkbox" name="nuoiduong1" value="1" <?php if($nuoiduong[0]!='')echo 'checked' ?>  disabled ><?php echo $LD['nuoiduong'][1]; ?>
			<input type="checkbox" name="nuoiduong2" value="2" <?php if($nuoiduong[1]!='')echo 'checked' ?> disabled><?php echo $LD['nuoiduong'][2]; ?>
			<input type="checkbox" name="nuoiduong3" value="3"<?php if($nuoiduong[2]!='')echo 'checked' ?>  disabled ><?php echo $LD['nuoiduong'][3]; ?>
		</td>
		
	</tr >		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDCaisua; ?></nobr>
		</td>
		<td class="adm_input">
			<?php echo $row['thangcaisua'] ?>
		</td>
		<td class="adm_item">
			<nobr><?php echo $LD['chamsoc'][0]; ?></nobr>
		</td>
		<td class="adm_input">
			<input type="checkbox" name="chamsoc1" value="1"  <?php if($chamsoc[0]!='')echo 'checked' ?> disabled><?php echo $LD['chamsoc'][1]; ?>
			<input type="checkbox" name="chamsoc2" value="2"  <?php if($chamsoc[1]!='')echo 'checked' ?> disabled><?php echo $LD['chamsoc'][2]; ?>			
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LD['tiemchung'][0]; ?></nobr>
		</td>
		<td colspan=3>
			<input type="checkbox" name="tiemchung1" value="1"  <?php if($tiemchung[0]!='')echo 'checked' ?> disabled><?php echo $LD['tiemchung'][1]; ?>
			<input type="checkbox" name="tiemchung2" value="2"  <?php if($tiemchung[1]!='')echo 'checked' ?> disabled><?php echo $LD['tiemchung'][2]; ?>
			<input type="checkbox" name="tiemchung3" value="3"  <?php if($tiemchung[2]!='')echo 'checked' ?> disabled><?php echo $LD['tiemchung'][3]; ?>
			<input type="checkbox" name="tiemchung4" value="4"  <?php if($tiemchung[3]!='')echo 'checked' ?> disabled><?php echo $LD['tiemchung'][4]; ?>
			<input type="checkbox" name="tiemchung5" value="5"  <?php if($tiemchung[4]!='')echo 'checked' ?> disabled><?php echo $LD['tiemchung'][5]; ?>
			<input type="checkbox" name="tiemchung6" value="6"  <?php if($tiemchung[5]!='')echo 'checked' ?> disabled><?php echo $LD['tiemchung'][6]; ?>
			<input type="checkbox" name="tiemchung7" value="7"  <?php if($tiemchung[6]!='')echo 'checked' ?> disabled><?php echo $LD['tiemchung'][7]; ?>		
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr><?php echo $LDTiemchungkhac; ?></nobr>
		</td>
		<td colspan=3>
			<?php echo $row['tiemchungkhac']?>
		</td>
	</tr>
    <tr bgcolor="#f6f6f6">
		<td class="adm_item"><?php echo $LDDate; ?></td>
		<td class="adm_input">
			<?php
				//gjergji : new calendar
				echo formatDate2STD($row['date'],$date_format);
				//end : gjergji
			?>
		</td>
		<td class="adm_item"><?php echo $LDBy; ?></td>
		<td class="adm_input"><?php echo $row['doctor_name']; ?></td>
	</tr>  
<tr valign="top">
    <td colspan=2>&nbsp;<br>
	<img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?> />
	<a href="<?php 
		echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='. strtr($target,' ','+').'&mode=new&allow_update=1';
		//if($this_enc_preg) echo '&rec_nr='.$pregbuf[$show_preg_enc]['nr'];
	 ?>"> 
<?php 
        if($no_enc_preg) echo $LDEnterNewRecord;
            else echo $LDUpdate; 
?>
	</a>&nbsp;<p>
    </td>
</tr>    
 </table>