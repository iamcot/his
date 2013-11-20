<?php 
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
if($rows){
 $row=$pregs->FetchRow();
}
$tiensubenh=explode("_",$row['tiensubenh']);
$hamtren_trai=explode("_",$row['hamtren_trai']);
$hamtren_phai=explode("_",$row['hamtren_phai']);
$hamduoi_trai=explode("_",$row['hamduoi_trai']);
$hamduoi_phai=explode("_",$row['hamduoi_phai']);
$khdieutri=explode("_",$row['khdieutri']);
?>
<script language="javascript">
<!--
function printout(enc){
urlholder="<?php echo $root_path;?>modules/pdfmaker/benhan/benhanrhmngoai.php<?php echo URL_APPEND; ?>&enc="+enc;
	testprintpdf<?php echo $sid ?>=window.open(urlholder,"testprintpdf<?php echo $sid ?>","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
    //testprintout<?php echo $sid ?>.print();
}
//--!>
</script>
<table border=0 cellpadding=2 width=100%>
<tr bgcolor="#f6f6f6">
		<td colspan=2 class="adm_item">
			<?php echo 'TIỀN SỬ BỆNH'; ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			<table cellpadding=2>
				<tr>
					<td align="right">
					Chảy máu lâu<input type="checkbox" value="1" name="tsb1" <?php if($tiensubenh[0]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Phản ứng thuốc<input type="checkbox" value="1" name="tsb2" <?php if($tiensubenh[1]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh dị ứng<input type="checkbox" value="1" name="tsb3" <?php if($tiensubenh[2]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh cao huyết áp<input type="checkbox" value="1" name="tsb4" <?php if($tiensubenh[3]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh tim mạch<input type="checkbox" value="1" name="tsb5" <?php if($tiensubenh[4]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh tiểu đường<input type="checkbox" value="1" name="tsb6" <?php if($tiensubenh[5]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh dạ dày, tiêu hóa<input type="checkbox" value="1" name="tsb7" <?php if($tiensubenh[6]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh phổi(lao, hen)<input type="checkbox" value="1" name="tsb8" <?php if($tiensubenh[7]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh truyền nhiểm<input type="checkbox" value="1" name="tsb9" <?php if($tiensubenh[8]!='') echo 'checked'?> disabled >
					</td>
				</tr>
				
			</table>
		</td>		
		<td>
			<table>
				<tr>
					<td style="border-right:1px solid black;border-bottom:1px solid black;">
						<nobr>8<input type="checkbox" value="1" name="htt8" <?php if($hamtren_trai[7]!='') echo 'checked'?> disabled >
							  7<input type="checkbox" value="1" name="htt7" <?php if($hamtren_trai[6]!='') echo 'checked'?> disabled >
							  6<input type="checkbox" value="1" name="htt6" <?php if($hamtren_trai[5]!='') echo 'checked'?> disabled >
							  5<input type="checkbox" value="1" name="htt5" <?php if($hamtren_trai[4]!='') echo 'checked'?> disabled >
							  4<input type="checkbox" value="1" name="htt4" <?php if($hamtren_trai[3]!='') echo 'checked'?> disabled >
							  3<input type="checkbox" value="1" name="htt3" <?php if($hamtren_trai[2]!='') echo 'checked'?> disabled >
							  2<input type="checkbox" value="1" name="htt2" <?php if($hamtren_trai[1]!='') echo 'checked'?> disabled >
							  1<input type="checkbox" value="1" name="htt1" <?php if($hamtren_trai[0]!='') echo 'checked'?> disabled >
						</nobr>
					</td>
				<td style="border-left:1px solid black;border-bottom:1px solid black;">
					<nobr>1<input type="checkbox" value="1" name="htp1" <?php if($hamtren_phai[0]!='') echo 'checked'?> disabled >
						  2<input type="checkbox" value="1" name="htp2" <?php if($hamtren_phai[1]!='') echo 'checked'?> disabled >
						  3<input type="checkbox" value="1" name="htp3" <?php if($hamtren_phai[2]!='') echo 'checked'?> disabled >
						  4<input type="checkbox" value="1" name="htp4" <?php if($hamtren_phai[3]!='') echo 'checked'?> disabled >
						  5<input type="checkbox" value="1" name="htp5" <?php if($hamtren_phai[4]!='') echo 'checked'?> disabled >
						  6<input type="checkbox" value="1" name="htp5" <?php if($hamtren_phai[5]!='') echo 'checked'?> disabled >
						  7<input type="checkbox" value="1" name="htp7" <?php if($hamtren_phai[6]!='') echo 'checked'?> disabled >
						  8<input type="checkbox" value="1" name="htp8" <?php if($hamtren_phai[7]!='') echo 'checked'?> disabled >
					</nobr>
				</td>
				</tr>
				<tr>
					<td style="border-right:1px solid black;">
						<nobr>8<input type="checkbox" value="1" name="hdt8" <?php if($hamduoi_trai[7]!='') echo 'checked'?> disabled >
						7<input type="checkbox" value="1" name="hdt7" <?php if($hamduoi_trai[6]!='') echo 'checked'?> disabled >
						6<input type="checkbox" value="1" name="hdt6" <?php if($hamduoi_trai[5]!='') echo 'checked'?> disabled >
						5<input type="checkbox" value="1" name="hdt5" <?php if($hamduoi_trai[4]!='') echo 'checked'?> disabled >
						4<input type="checkbox" value="1" name="hdt4" <?php if($hamduoi_trai[3]!='') echo 'checked'?> disabled >
						3<input type="checkbox" value="1" name="hdt3" <?php if($hamduoi_trai[2]!='') echo 'checked'?> disabled >
						2<input type="checkbox" value="1" name="hdt2" <?php if($hamduoi_trai[1]!='') echo 'checked'?> disabled >
						1<input type="checkbox" value="1" name="hdt1" <?php if($hamduoi_trai[0]!='') echo 'checked'?> disabled >
						</nobr>
					</td>
				<td style="border-left:1px solid black;">
					<nobr>1<input type="checkbox" value="1" name="hdp1" <?php if($hamduoi_phai[0]!='') echo 'checked'?> disabled >
						2<input type="checkbox" value="1" name="hdp2" <?php if($hamduoi_phai[1]!='') echo 'checked'?> disabled >
						3<input type="checkbox" value="1" name="hdp3" <?php if($hamduoi_phai[2]!='') echo 'checked'?> disabled >
						4<input type="checkbox" value="1" name="hdp4" <?php if($hamduoi_phai[3]!='') echo 'checked'?> disabled >
						5<input type="checkbox" value="1" name="hdp5" <?php if($hamduoi_phai[4]!='') echo 'checked'?> disabled >
						6<input type="checkbox" value="1" name="hdp6" <?php if($hamduoi_phai[5]!='') echo 'checked'?> disabled >
						7<input type="checkbox" value="1" name="hdp7" <?php if($hamduoi_phai[6]!='') echo 'checked'?> disabled >
						8<input type="checkbox" value="1" name="hdp8" <?php if($hamduoi_phai[7]!='') echo 'checked'?> disabled >
					</nobr>
				</td>
				</tr>
				<tr>
				<td></td>
				</tr>
				<tr>
					<td align="right" style="border-right:1px solid black;border-bottom:1px solid black;">
						<nobr>V<input type="checkbox" value="1" name="">IV<input type="checkbox" value="1" name="">III<input type="checkbox" value="1" name="">II<input type="checkbox" value="1" name="">
						I<input type="checkbox" value="1" name=""></nobr>
					</td>
				<td style="border-left:1px solid black;border-bottom:1px solid black;">
					<nobr>I<input type="checkbox" value="1" name="">II<input type="checkbox" value="1" name="">III<input type="checkbox" value="1" name="">IV<input type="checkbox" value="1" name="">
					V<input type="checkbox" value="1" name=""></nobr>
				</td>
				</tr>
				<tr>
					<td align="right" style="border-right:1px solid black;">
						<nobr>V<input type="checkbox" value="1" name="">IV<input type="checkbox" value="1" name="">III<input type="checkbox" value="1" name="">II<input type="checkbox" value="1" name="">
						I<input type="checkbox" value="1" name=""></nobr>
					</td>
				<td style="border-left:1px solid black;">
					<nobr>I<input type="checkbox" value="1" name="">II<input type="checkbox" value="1" name="">III<input type="checkbox" value="1" name="">IV<input type="checkbox" value="1" name="">
					V<input type="checkbox" value="1" name=""></nobr>
				</td>
				</tr>
				<tr>
					<td colspan="2">
					Nhận xét: <br/>
						<?php if(!empty($row['nhanxet'])) echo $row['nhanxet'];?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td colspan=2 class="adm_item">
			<?php echo 'KẾ HOẠCH ĐIỀU TRỊ'; ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_input" align="right">
			Nha chu<input type="checkbox" value="1" name="khdt1" <?php if($khdieutri[0]!='') echo 'checked'?> disabled >
		<br/>
			Chữa răng<input type="checkbox" value="1" name="khdt2" <?php if($khdieutri[1]!='') echo 'checked'?> disabled >
		<br/>
			Nhỗ rang -TPT<input type="checkbox" value="1" name="khdt3" <?php if($khdieutri[2]!='') echo 'checked'?> disabled >
		<br/>
			Cắn khớp<input type="checkbox" value="1" name="khdt4" <?php if($khdieutri[3]!='') echo 'checked'?> disabled >
		<br/>
			Phục hình cố định<input type="checkbox" value="1" name="khdt5" <?php if($khdieutri[4]!='') echo 'checked'?> disabled >
		<br/>
			Phục hình tháo lắp<input type="checkbox" value="1" name="khdt6" <?php if($khdieutri[5]!='') echo 'checked'?> disabled >
		<br/>
			Chỉnh hình răng mặt<input type="checkbox" value="1" name="khdt7" <?php if($khdieutri[6]!='') echo 'checked'?> disabled >
		<br/>
			Răng trẻ em<input type="checkbox" value="1" name="khdt8" <?php if($khdieutri[7]!='') echo 'checked'?> disabled >
		<br/>
			Phòng ngừa sâu răng<input type="checkbox" value="1" name="khdt9" <?php if($khdieutri[8]!='') echo 'checked'?> disabled >
		<br/>
			Phẩu thuật hàm mặt<input type="checkbox" value="1" name="khdt10" <?php if($khdieutri[9]!='') echo 'checked'?> disabled >
		<br/>
		</td>
		<td class="adm_input">
			Chẩn đoán<br/>
			<?php if(!empty($row['chandoan'])) echo $row['chandoan'];?>
		</td>
	</tr>
<tr bgcolor="#f6f6f6">
		<td class="adm_item"><?php echo $LDDate; ?></td>
		<td style="width:115px">
			<?php
				//gjergji : new calendar
				echo formatDate2STD($row['date'],$date_format);	
				//end : gjergji
			?>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td class="adm_item"><nobr><?php echo $LDBy; ?></nobr></td>
		<td><?php echo $row['doctor_name']; ?></td>
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
	<?php if(!($no_enc_preg)) echo '<img '.createComIcon($root_path,"bul_arrowgrnlrg.gif","0","absmiddle").' /> 
	<a href="javascript:printout('.$encounter_nr.')">In ấn</a>';
	?>
    </td>
</tr>    
	
 </table>