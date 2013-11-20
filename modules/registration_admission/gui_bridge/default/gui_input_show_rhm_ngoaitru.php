<?php
if($pregs) $row=$pregs->FetchRow();
//gjergji : new calendar
require_once ('../../js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
$calendar->load_files();
$tiensubenh=explode("_",$row['tiensubenh']);
$hamtren_trai=explode("_",$row['hamtren_trai']);
$hamtren_phai=explode("_",$row['hamtren_phai']);
$hamduoi_trai=explode("_",$row['hamduoi_trai']);
$hamduoi_phai=explode("_",$row['hamduoi_phai']);
$khdieutri=explode("_",$row['khdieutri']);

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
		<td colspan=2 class="adm_item">
			<?php echo 'TIỀN SỬ BỆNH'; ?>
		</td>		
	</tr>
	<tr bgcolor="#f6f6f6">
		<td>
			<table cellpadding=2>
				<tr>
					<td align="right">
					Chảy máu lâu<input type="checkbox" value="1" name="tsb1" <?php if($tiensubenh[0]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Phản ứng thuốc<input type="checkbox" value="1" name="tsb2" <?php if($tiensubenh[1]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh dị ứng<input type="checkbox" value="1" name="tsb3" <?php if($tiensubenh[2]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh cao huyết áp<input type="checkbox" value="1" name="tsb4" <?php if($tiensubenh[3]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh tim mạch<input type="checkbox" value="1" name="tsb5" <?php if($tiensubenh[4]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh tiểu đường<input type="checkbox" value="1" name="tsb6" <?php if($tiensubenh[5]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh dạ dày, tiêu hóa<input type="checkbox" value="1" name="tsb7" <?php if($tiensubenh[6]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh phổi(lao, hen)<input type="checkbox" value="1" name="tsb8" <?php if($tiensubenh[7]!='') echo 'checked'?> >
					</td>
				</tr>
				<tr>
					<td align="right">
					Bệnh truyền nhiểm<input type="checkbox" value="1" name="tsb9" <?php if($tiensubenh[8]!='') echo 'checked'?> >
					</td>
				</tr>
				
			</table>
		</td>		
		<td>
			<table>
				<tr>
					<td style="border-right:1px solid black;border-bottom:1px solid black;">
						<nobr>8<input type="checkbox" value="1" name="htt8" <?php if($hamtren_trai[7]!='') echo 'checked'?> >
							  7<input type="checkbox" value="1" name="htt7" <?php if($hamtren_trai[6]!='') echo 'checked'?> >
							  6<input type="checkbox" value="1" name="htt6" <?php if($hamtren_trai[5]!='') echo 'checked'?> >
							  5<input type="checkbox" value="1" name="htt5" <?php if($hamtren_trai[4]!='') echo 'checked'?> >
							  4<input type="checkbox" value="1" name="htt4" <?php if($hamtren_trai[3]!='') echo 'checked'?> >
							  3<input type="checkbox" value="1" name="htt3" <?php if($hamtren_trai[2]!='') echo 'checked'?> >
							  2<input type="checkbox" value="1" name="htt2" <?php if($hamtren_trai[1]!='') echo 'checked'?> >
							  1<input type="checkbox" value="1" name="htt1" <?php if($hamtren_trai[0]!='') echo 'checked'?> >
						</nobr>
					</td>
				<td style="border-left:1px solid black;border-bottom:1px solid black;">
					<nobr>1<input type="checkbox" value="1" name="htp1" <?php if($hamtren_phai[0]!='') echo 'checked'?> >
						  2<input type="checkbox" value="1" name="htp2" <?php if($hamtren_phai[1]!='') echo 'checked'?> >
						  3<input type="checkbox" value="1" name="htp3" <?php if($hamtren_phai[2]!='') echo 'checked'?> >
						  4<input type="checkbox" value="1" name="htp4" <?php if($hamtren_phai[3]!='') echo 'checked'?> >
						  5<input type="checkbox" value="1" name="htp5" <?php if($hamtren_phai[4]!='') echo 'checked'?> >
						  6<input type="checkbox" value="1" name="htp5" <?php if($hamtren_phai[5]!='') echo 'checked'?> >
						  7<input type="checkbox" value="1" name="htp7" <?php if($hamtren_phai[6]!='') echo 'checked'?> >
						  8<input type="checkbox" value="1" name="htp8" <?php if($hamtren_phai[7]!='') echo 'checked'?> >
					</nobr>
				</td>
				</tr>
				<tr>
					<td style="border-right:1px solid black;">
						<nobr>8<input type="checkbox" value="1" name="hdt8" <?php if($hamduoi_trai[7]!='') echo 'checked'?> >
						7<input type="checkbox" value="1" name="hdt7" <?php if($hamduoi_trai[6]!='') echo 'checked'?> >
						6<input type="checkbox" value="1" name="hdt6" <?php if($hamduoi_trai[5]!='') echo 'checked'?> >
						5<input type="checkbox" value="1" name="hdt5" <?php if($hamduoi_trai[4]!='') echo 'checked'?> >
						4<input type="checkbox" value="1" name="hdt4" <?php if($hamduoi_trai[3]!='') echo 'checked'?> >
						3<input type="checkbox" value="1" name="hdt3" <?php if($hamduoi_trai[2]!='') echo 'checked'?> >
						2<input type="checkbox" value="1" name="hdt2" <?php if($hamduoi_trai[1]!='') echo 'checked'?> >
						1<input type="checkbox" value="1" name="hdt1" <?php if($hamduoi_trai[0]!='') echo 'checked'?> >
						</nobr>
					</td>
				<td style="border-left:1px solid black;">
					<nobr>1<input type="checkbox" value="1" name="hdp1" <?php if($hamduoi_phai[0]!='') echo 'checked'?> >
						2<input type="checkbox" value="1" name="hdp2" <?php if($hamduoi_phai[1]!='') echo 'checked'?> >
						3<input type="checkbox" value="1" name="hdp3" <?php if($hamduoi_phai[2]!='') echo 'checked'?> >
						4<input type="checkbox" value="1" name="hdp4" <?php if($hamduoi_phai[3]!='') echo 'checked'?> >
						5<input type="checkbox" value="1" name="hdp5" <?php if($hamduoi_phai[4]!='') echo 'checked'?> >
						6<input type="checkbox" value="1" name="hdp6" <?php if($hamduoi_phai[5]!='') echo 'checked'?> >
						7<input type="checkbox" value="1" name="hdp7" <?php if($hamduoi_phai[6]!='') echo 'checked'?> >
						8<input type="checkbox" value="1" name="hdp8" <?php if($hamduoi_phai[7]!='') echo 'checked'?> >
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
						<textarea rows="4" style="width:99%;" name="nhanxet" ><?php if(!empty($row['nhanxet'])) echo $row['nhanxet'];?></textarea>
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
			Nha chu<input type="checkbox" value="1" name="khdt1" <?php if($khdieutri[0]!='') echo 'checked'?> >
		<br/>
			Chữa răng<input type="checkbox" value="1" name="khdt2" <?php if($khdieutri[1]!='') echo 'checked'?> >
		<br/>
			Nhỗ rang -TPT<input type="checkbox" value="1" name="khdt3" <?php if($khdieutri[2]!='') echo 'checked'?> >
		<br/>
			Cắn khớp<input type="checkbox" value="1" name="khdt4" <?php if($khdieutri[3]!='') echo 'checked'?> >
		<br/>
			Phục hình cố định<input type="checkbox" value="1" name="khdt5" <?php if($khdieutri[4]!='') echo 'checked'?> >
		<br/>
			Phục hình tháo lắp<input type="checkbox" value="1" name="khdt6" <?php if($khdieutri[5]!='') echo 'checked'?> >
		<br/>
			Chỉnh hình răng mặt<input type="checkbox" value="1" name="khdt7" <?php if($khdieutri[6]!='') echo 'checked'?> >
		<br/>
			Răng trẻ em<input type="checkbox" value="1" name="khdt8" <?php if($khdieutri[7]!='') echo 'checked'?> >
		<br/>
			Phòng ngừa sâu răng<input type="checkbox" value="1" name="khdt9" <?php if($khdieutri[8]!='') echo 'checked'?> >
		<br/>
			Phẩu thuật hàm mặt<input type="checkbox" value="1" name="khdt10" <?php if($khdieutri[9]!='') echo 'checked'?> >
		<br/>
		</td>
		<td class="adm_input">
			Chẩn đoán<br/>
			<textarea rows="6" style="width:90%" name="chandoan"><?php if(!empty($row['chandoan'])) echo $row['chandoan'];?></textarea>
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
