<?php 
//$pres_show['']: thong tin chung toa thuoc 
//$result['']: thong tin cua benh nhan
//$medicine_in_pres: danh sach thuoc trong toa
//$medicine_count = $medicine_in_pres->RecordCount()

?>
<table border=0 cellpadding=5 width="100%">
	 <!-- Them thuoc vao toa thuoc -->
	<tr>
		<td colspan="4" align="center"><br>
			<table bgcolor="#EEEEEE" width=100% cellpadding="5">
				<tr bgColor="#E1E1E1">
					<td align="center"><u><?php echo 'STT'; ?></u></td>
					<td align="center"><u><?php echo $LDMedipotName; ?></u></td>
					<td align="center" align="center"><font color="#E41B17"><u><?php echo $LDTonKhoChan ?></u></font></td>
					<td align="center"><u><?php echo $LDNumberRequest; ?></u></td>
					<td align="center"><u><?php echo $LDNumberReceive; ?></u></td>
					<td align="center"><u><?php echo $LDUnits; ?></u></td>
					<td align="center"><u><?php echo $LDCost; ?></u></td>
					<td align="center"><u><?php echo $LDTotalCost; ?></u></td>
				</tr>
			
		<?php for($i=1;$i<=$medicine_count;$i++) { 			
				$medicine_pres = $medicine_in_pres->FetchRow();	
				
				$tonkhochan = $Pres->findInventoryKhoChan($medicine_pres['product_encoder'],$pres_show['typeput']);
				$totalcostmedicine = $medicine_pres['sum_number']*$medicine_pres['cost'];						
				$strtext = $medicine_pres['desciption']; //howtouse count totalunits/per
					$strtext = explode("/", $strtext);
					$split_desciption = explode(" ", $strtext[0]);								
							
			echo '<tr bgcolor="#ffffff">
					<td width="3%">'.$i.'.<input type="hidden" name="medicine_nr['.$i.']" value="'.$medicine_pres['nr'].'"></td>
					<td width="35%">
						<!-- Ten thuoc / lieu luong-->
						<b>'.$medicine_pres['product_name'].'</b>
					</td>
					<td align="right" width="12%"><font color="#E41B17">'.number_format($tonkhochan).'</font></td>';
					
			echo	'<!-- So luong, don gia, thanh tien -->
					<td align="center" width="10%"><b>
						'.$medicine_pres['sum_number'].'</b>
					</td>
					<td align="center" width="10%">
						<input type="text" size=3 name="receive['.$i.']" value="'.$medicine_pres['sum_number'].'" onFocus="startCalc('.$i.');" onBlur="stopCalc();" '.$readonly.'>	
					</td>
					<td align="center" width="6%"><b>
						'.$medicine_pres['note'].'</b>
					</td>
					<td align="right" width="12%">
						<input name="cost'.$i.'" type="text" size=1 value="'.$medicine_pres['cost'].'" style="text-align:center;border-color:white;border-style:solid;" readonly>
					</td>
					<td align="right" width="13%">
						<input name="sumcost'.$i.'" type="text" size=1 value="'.$totalcostmedicine.'" style="text-align:center;border-color:white;border-style:solid;" readonly>
					</td>
				</tr>';

			} ?>
			</table>
			&nbsp;<br>
		</td>
	</tr>
	<tr bgcolor="#f6f6f6">
		<td><FONT SIZE=-1 color="#000066"><?php echo $LDIssueUser; ?></td>
		<td><FONT SIZE=-1 ><input name="issue_user" type="text" size="24" value="<?php echo $_SESSION['sess_user_name']; ?>" style="border-color:white;border-style:solid;" readonly></td>
		<td align="top"><FONT SIZE=-1 color="#000066"><?php echo $LDReceiveUser; ?></td>
		<td><FONT SIZE=-1 ><input name="receive_user" type="text" size="24" value="<?php echo $pres_show['doctor']; ?>"></td>
 	</tr> 
	<tr bgcolor="#f6f6f6">
		<td valign="top"><FONT SIZE=-1 color="#000066"><?php echo $LDNoteIssue; ?></td>
		<td colspan="3"><FONT SIZE=-1 ><textarea name="noteissue" cols="27" rows="3" wrap="physical" ></textarea></td>
 	</tr> 
	<tr bgcolor="#f6f6f6">
		<td colspan="4">&nbsp;<p></td>
	</tr>
	<tr>
		<td colspan="4" ><FONT SIZE=1  > <?php if($bill || $pres_show['status_bill']) echo $LDNoteMedicineBill; else echo $LDNoteMedicinePres; ?></td>
	</tr>
	 
 	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
 </table>