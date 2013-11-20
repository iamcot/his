<?php 
//$pres_show['']: thong tin chung toa thuoc 
//$result['']: thong tin cua benh nhan
//$medicine_in_pres: danh sach thuoc trong toa
//$medicine_count = $medicine_in_pres->RecordCount()

if($report_show['status_finish']){
	$tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
else{
	$tempfinish=$LDNotYet; $tempfinish1='warn.gif';}

$date1 = formatDate2Local($report_show['create_time'],'dd/mm/yyyy');
$time1 = substr($report_show['create_time'],-8);

if($mode=='khole'){
	$placeto=$LDKhoLe;
}else{
	if($placeto=$Pharma->getNameHealthStation($report_show['health_station']))
		$placeto=$LDTramYTe.' '.$placeto['name'];
}
switch($report_show['typeput']){
	case 0: $typeput=$LDBH; break;
	case 1: $typeput=$LDNoBH; break;
	case 2: $typeput=$LDCBTC; break;
	default: $typeput=$LDNoBH; break;
}


?>&nbsp;<br>
<table border=0 cellpadding=3 width="98%">
		<tr bgcolor="#f6f6f6">
			<td width="20%"><FONT SIZE=-1  color="#000066"><?php echo $LDIssueId; ?></td>
			<td width="35%"><FONT SIZE=-1  ><?php echo $report_id; ?></td>
			<td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $LDGotDrug; ?></td>
			<td width="30%"><FONT SIZE=-1  ><?php echo $tempfinish.' ';?> 
				<img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?>> </td>
	   </tr>
    <tr bgcolor="#f6f6f6">
		<td width="20%"><FONT SIZE=-1  color="#000066"><?php echo $LDPayOutID; ?></td>
		<td width="35%"><FONT SIZE=-1  ><?php echo $report_show['voucher_id']; ?></td>
		<td><FONT SIZE=-1  color="#000066"><?php echo $LDDate; ?></td>
		<td><FONT SIZE=-1  ><?php echo $date1.' '.$time1; ?></td>
   </tr> 
   <tr bgcolor="#f6f6f6">
		<td><FONT SIZE=-1  color="#000066"><?php echo $LDPlace; ?></td>
		<td><FONT SIZE=-1  ><?php echo $placeto; ?></td>
		<td><FONT SIZE=-1  color="#000066"><?php echo $LDTypePutIn1; ?></td>
		<td><FONT SIZE=-1  ><b><?php echo $typeput; ?></b></td>
   </tr> 
	<tr bgcolor="#f6f6f6">
		<td><FONT SIZE=-1  color="#000066"><?php echo $LDNote; ?></td>
		<td><FONT SIZE=-1  ><?php echo $report_show['note']; ?></td>
		<td><FONT SIZE=-1  color="#000066"><?php echo $LDTotal; ?></td>
		<td><FONT SIZE=-1  ><?php echo '<input name="total_money" id="total_money" type="text" size="20" value="'.number_format($report_show['totalcost']).'" style="border-color:white;border-style:solid;"  readonly>'; ?></td>		
 	</tr> 
	
	 <!-- Them thuoc vao toa thuoc -->
	<tr>
		<td colspan="4" align="center"><br>
			<table bgcolor="#EEEEEE" width="100%" cellpadding="2">
				<tr bgColor="#E1E1E1" >
					<td width="4%" align="center" rowspan="2"><u><?php echo $LDSTT ?></u></td>
					<td width="25%" align="center" rowspan="2"><u><?php echo $LDMedicineName1; ?></u></td>
					<td width="9%" align="center" rowspan="2"><u><?php echo $LDUnit ?></u></td>
					<td width="7%" align="center" rowspan="2"><u><?php echo $LDLotID1 ?></u></td>
					<td width="10%" align="center" rowspan="2"><u><?php echo $LDExpDate ?></u></td>
					<td width="11%" align="center" rowspan="2"><u><?php echo $LDPrice ?></u></td>
					<td width="18%" align="center" colspan="2"><u><?php echo $LDNumber ?></u></td>
					<td width="12%" align="center" rowspan="2"><u><?php echo $LDTotalPrice ?></u></td>
					<td width="5%" align="center" rowspan="2"><u><?php echo $LDNote ?></u></td>
				</tr>
				<tr bgColor="#E1E1E1" >
					<td align="center"><u><?php echo $LDNumberInPaper ?></u></td>
					<td align="center"><u><?php echo $LDNumberInFact ?></u></td>
				</tr>
			
		<?php for($i=1;$i<=$medicine_count;$i++) { 			
				$rowReport = $medicine_in_report->FetchRow();
				$rowReport['exp_date']=formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy');	

				if(round($rowReport['price'])==round($rowReport['price'],3))
					$show_price=number_format($rowReport['price']);
				else $show_price=number_format($rowReport['price'],3);				
							
			echo '<tr bgColor="#ffffff" >
					<td align="center" bgColor="#ffffff">'.$i.'.<input type="hidden" name="medicine_nr['.$i.']" value="'.$rowReport['id'].'"></td>
					<td bgColor="#ffffff"><b>'.$rowReport['product_name'].'</b></td>
					<td align="center" bgColor="#ffffff"><b>'.$rowReport['unit_name_of_medicine'].'</b></td>
					<td align="center" bgColor="#ffffff">'.$rowReport['lotid'].'</td>
					<td align="center" bgColor="#ffffff">'.$rowReport['exp_date'].'</td>
					<td align="center" bgColor="#ffffff"><input id="cost'.$i.'" type="text" size="8" value="'.$show_price.'" style="border-color:white;border-style:solid;" readonly></td>
					<td align="center" bgColor="#ffffff"><b>'.number_format($rowReport['number']).'</b></td>
					<td align="center" bgColor="#ffffff"><input name="receive['.$i.']" id="receive'.$i.'" type="text" size=4 value="'.number_format($rowReport['number']).'" onBlur="CalCost('.$i.')" onkeyup="OnchangeFormat(this)"></td>
					<td align="center" bgColor="#ffffff"><input id="totalcost'.$i.'" type="text" size="10" value="'.number_format($rowReport['price']*$rowReport['number']).'" style="border-color:white;border-style:solid;" readonly></td>
					<td align="center" bgColor="#ffffff">'.$rowReport['note'].'</td>
				</tr>';
			} ?>
			</table>
			&nbsp;<br>
		</td>
	</tr>
	
	  <!-- Loi dan bac si & button -->
	<tr bgcolor="#f6f6f6">
		<td><FONT SIZE=-1 color="#000066"><?php echo $LDUserAccept; ?></td>
		<td><FONT SIZE=-1 ><input name="user_accept" type="text" size="24" value="<?php echo $_SESSION['sess_user_name']; ?>" style="border-color:white;border-style:solid;" readonly></td>
		<td align="top"><FONT SIZE=-1 color="#000066"><?php echo $LDPayOutPerson; ?></td>
		<td><FONT SIZE=-1 ><input name="receive_person" type="text" size="24" value="<?php echo $report_show['receiver']; ?>"></td>
 	</tr> 
	<tr bgcolor="#f6f6f6">
		<td colspan="4">&nbsp;
		<input type="hidden" name="typeput" value="<?php echo $report_show['typeput']; ?>">
		<p></td>
	</tr>

 </table>