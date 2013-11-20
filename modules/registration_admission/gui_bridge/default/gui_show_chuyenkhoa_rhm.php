<?php 
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
if($rows){
 $row=$pregs->FetchRow();
}
?>
<br/>
<table border=0 cellpadding=2 width=100%>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<?php echo $LDToanthan; ?>
		</td>
		<td class="adm_input">
			<?php echo nl2br($row['toanthan_notes']); ?>
		</td>
		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Bệnh chuyên khoa</nobr>
		</td>
		<td class="adm_input">
			<?php echo nl2br($row['ck_notes']); ?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương phải</nobr>
		</td>		
		<td class="adm_input">
			<?php echo nl2br($row['phai_notes']);?>
		</td>
	</tr>		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương trái</nobr>
		</td>
		<td class="adm_input">
			<?php  echo nl2br($row['trai_notes']); ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương thẳng</nobr>
		</td>
		<td class="adm_input">
			<?php  echo nl2br($row['thang_notes']);?>
		</td>		
	</tr >		
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương hàm trên và họng</nobr>
		</td>
		<td class="adm_input">
			<?php  echo nl2br($row['hamtrenhong_notes']);?>
		</td>		
	</tr >	

	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Tổn thương hàm dưới</nobr>
		</td>
		<td class="adm_input">
			<?php  echo nl2br($row['hamduoi_notes']); ?>
		</td>		
	</tr >	
	<tr bgcolor="#f6f6f6">
		<td class="adm_item">
			<nobr>Phân loại khe hở môi vòm miệng</nobr>
		</td>
		<td class="adm_input">
			<?php echo nl2br($row['kheho_notes']); ?>
		</td>		
	</tr >	
    <tr bgcolor="#f6f6f6">
		<td class="adm_item"><?php echo $LDDate; ?></td>
		<td class="adm_input">
			<?php
				//gjergji : new calendar
				echo formatDate2STD($row['date'],$date_format);
				//end : gjergji
			?>
		</td>
	</tr>
	<tr>
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