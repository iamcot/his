
<script type="text/javascript">
function viewDetail(enc_nr,pid,pres_id)
{
	var win = 'show_prescription_detail.php<?php echo URL_APPEND; ?>' + '&pid=' + pid + '&enc_nr='+ enc_nr +'&pres_id=' + pres_id;
	myWindow=window.open( win , 'View Details' , 'height=500,width=650,menubar=no,resizable=yes,scrollbars=yes' );
	myWindow.focus();
}
function printOut(enc) {
	urlholder="<?php echo $root_path ?>modules/pdfmaker/prescription/todieutri.php<?php echo URL_REDIRECT_APPEND; ?>&enc="+enc;
	window.open(urlholder,'ToDieuTri',"width=1000,height=800,menubar=no,resizable=yes,scrollbars=yes");
}
</script>

<!-- Toa thuoc -->
<?php 
include_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($pres_obj)) $pres_obj=new Prescription;


if ($type=='pres')
{
?>
	<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
		<tr bgcolor="#85A4CD"><td colspan="3" ><font color="#ffffff"><b><?php echo $LDListPres; ?></b></td></tr>
	<?php

	//Load all prescriptions of this patient
	$toggle=0;
	while($row=$result_1->FetchRow()){
		if($toggle) $bgc='#f3f3f3';
			else $bgc='#fefefe';
		$toggle=!$toggle;

		
	?>
		<tr bgcolor='#ffffff'>&nbsp;</tr>
		<tr bgcolor="<?php echo $bgc; ?>" >
			<td ><FONT SIZE=-1  FACE="Arial">
				<a href="javascript:#"> 
					<img <?php echo createComIcon($root_path,'info3.gif','0','',TRUE); ?> onclick="viewDetail(<?php echo $_SESSION['sess_en'].','.$_SESSION['sess_pid'].','.$row['prescription_id']; ?>)">
				</a>
				<b><?php echo $LDPrescriptionId.": ".$row['prescription_id']."    "; ?></b>
			</td>
			<td colspan=2 ><FONT SIZE=-1  FACE="Arial"><b><?php echo $LDDate.': '. @formatDate2Local($row['date_time_create'],$date_format); ?></b></td>
			
		</tr>
		  
		<tr bgcolor="<?php echo $bgc; ?>" valign="top">
			<td colspan=3><FONT SIZE=-1  FACE="Arial"><?php echo $LDDiagnosis.": ".$row['diagnosis']; ?></td>
		</tr>
		
		<tr bgcolor="<?php echo $bgc; ?>" valign="top">
			  <td><FONT SIZE=-1  FACE="Arial">
				<?php  	if($row['status_bill']){
							$temp=$LDFinish; $temp1='check-r.gif';}
						else{
							$temp=$LDNotYet; $temp1='warn.gif';}?>
				<img <?php echo createComIcon($root_path,$temp1,'0','',TRUE); ?>>	
				<?php 	echo $LDPaid.": ".$temp; ?>
			</td>
			<td colspan=2><FONT SIZE=-1  FACE="Arial">
				<?php  	if($row['status_finish']){
							$temp=$LDFinish; $temp1='check-r.gif';}
						else{
							$temp=$LDNotYet; $temp1='warn.gif';}?>
				<img <?php echo createComIcon($root_path,$temp1,'0','',TRUE); ?>>				
				<?php 	echo $LDGotDrug.": ".$temp; ?>
			</td>
			
		</tr>
		
		<tr bgcolor="<?php echo $bgc; ?>" valign="top">
			<td width="50%"><FONT SIZE=-1  FACE="Arial"><?php echo $LDDoctor.': '.$row['doctor']; ?></td>
			<td width="35%"><FONT SIZE=-1  FACE="Arial"><?php echo $LDPrice.": ".number_format($row['total_cost']); ?></td>
			<td width="15%" align="right">	
				<a href="<?php	if($row['status_bill'])
									echo "javascript:alert('".$LDCannotEdit."')"; 
								elseif($row['status_finish'])
									echo "javascript:alert('".$LDCannotEditFinish."')"; 
								elseif ($row['in_issuepaper']!='0')
									echo "javascript:alert('".$LDCannotEditIssuePaper.' '.$row['in_issuepaper']."')"; 
								else
									echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=update&type='.$type.'&pres_id='.$row['prescription_id']; 
						?>"> 
				<img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0','absmiddle'); ?>>		
				</a>
			</td>
		</tr>
		
	<?php
	}
	?>
	</table>

	<?php
	if($parent_admit&&!$is_discharged) {
	?>
		<p>
		<table><tr><td>
			<img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?>>
		</td><td>
			<a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=create&type=pres'; ?>"> 
			<?php echo $LDCreatePres; ?>
			</a>
		</td></tr></table>
		<p>
<?php
	}
	
} else {	//type='sheet'
?>
<!-- Y Lenh -->

	<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
		<tr bgcolor="#85A4CD">
			<td colspan="3"><table width="100%"><tr><td><font color="#ffffff"><b><?php echo $LDSheetTreatment; ?></b></td>
			<td align="right">
			<a href="javascript:printOut('<?php echo $_SESSION['sess_en']; ?>')"><img <?php echo createLDImgSrc($root_path,'printer.png','0','absmiddle'); ?> title="<?php echo $LDPrint; ?>" ><b><font color="#ffffff"><?php echo ' '.$LDPrint; ?></font></b></a></td></tr></table></td>
		</tr>
		<tr bgcolor="#ffffff">
			<?php
			echo '<td align="center" width="20%"><font color="#5f88be"><b>'.$LDDate1.'</b></td>
				  <td align="center" width="40%"><font color="#5f88be"><b>'.$LDHealthStatus.'</b></td>
				  <td align="center" width="40%"><font color="#5f88be"><b>'.$LDTreatment.'</b></td>';
			?>
		</tr>
		<?php

	//Load all Sheet Treatment of this patient
	$toggle=0;
	while($row_2=$result_2->FetchRow()){
		if($toggle) $bgc='#f3f3f3';
			else $bgc='#fefefe';
		$toggle=!$toggle;

	?>
		<tr bgcolor='#ffffff'>&nbsp;</tr>
		<tr bgcolor="<?php echo $bgc; ?>" >
			<td valign="top"><FONT SIZE=-1  FACE="Arial">
				<?php $datepres = $row_2['date_time_create'];
					echo '<b>'. @formatDate2Local($datepres,$date_format).'</b><br>'; 				  
					if (strlen($datepres)>8)
						$timepres=substr($datepres,-8);
					echo '<b>'.$timepres.'</b><br>'; ?>
			</td>
			<td valign="top"><FONT SIZE=-1  FACE="Arial"><?php 
				if($row_2['total_cost']>0){
					echo nl2br(stripslashes($row_2['diagnosis'])).'<p>'; 				
				}
				echo nl2br(stripslashes($row_2['symptoms']));
			?></td>
			<!-- Danh sach ten thuoc/y lenh -->
			<td valign="top"><FONT SIZE=-1  FACE="Arial" ><?php 
				if ($row_2['total_cost']>0) { 
					$medicine_result = $pres_obj->getAllMedicineInPres($row_2['prescription_id']);
					if(is_object($medicine_result)){
						for ($i=0; $i<$medicine_result->RecordCount();$i++) {
							$items_in_sheet = $medicine_result->FetchRow();
							echo '<b>'.$items_in_sheet['product_name'].'</b><br>';
							echo $items_in_sheet['desciption'].'/'.$LDUseTimes.' x '.$items_in_sheet['number_of_unit'].' '.$LDUseTimes.': '.$items_in_sheet['time_use'].'<br>';
						}
					}
					echo '&nbsp<br>'.stripslashes($row_2['note']);
				} 
				else 
					echo nl2br(stripslashes($row_2['diagnosis'])); 
				?>
			</td>
		</tr>
		  
	<?php if ($row_2['total_cost']) {  ?>
		<tr bgcolor="<?php echo $bgc; ?>" valign="top">
			<td align="right"><FONT SIZE=-1  FACE="Arial"><?php echo $LDPrice.": ".number_format($row_2['total_cost']); ?></td>
			<td align="right"><FONT SIZE=-1  FACE="Arial">
				<?php  	if($row_2['status_bill']){
							$temp=$LDFinish; $temp1='check-r.gif';}
						else{
							$temp=$LDNotYet; $temp1='warn.gif';}?>
				<img <?php echo createComIcon($root_path,$temp1,'0','',TRUE); ?>>	
				<?php 	echo $LDPaid.": ".$temp; ?>
			</td>
			<td colspan=2 align="right"><FONT SIZE=-1  FACE="Arial">
				<?php  	if($row_2['status_finish']){
							$temp=$LDFinish; $temp1='check-r.gif';}
						else{
							$temp=$LDNotYet; $temp1='warn.gif';}?>
				<img <?php echo createComIcon($root_path,$temp1,'0','',TRUE); ?>>				
				<?php 	echo $LDGotDrug.": ".$temp; ?>
			</td>
		</tr>	
	<?php
		} ?>
		<tr bgcolor="<?php echo $bgc; ?>" valign="top">
			<td align="center">
				<?php if ($row_2['total_cost']) { ?>
					<a href="javascript:viewDetail(<?php echo $_SESSION['sess_en'].','.$_SESSION['sess_pid'].','.$row_2['prescription_id']; ?>)"> 
					<img <?php echo createComIcon($root_path,'info3.gif','0','',TRUE); ?>>
					</a>
				<?php } 	?>	
			</td>
			<td align="right"><FONT SIZE=-1  FACE="Arial"><?php echo $LDDoctor.': '.$row_2['doctor']; ?></td>
			<td align="right">	
				<a href="<?php	
							if ($row_2['total_cost']){
								if($row_2['status_bill'])
									echo "javascript:alert('".$LDCannotEdit."')"; 
								elseif($row_2['status_finish'])
									echo "javascript:alert('".$LDCannotEditFinish."')"; 
								elseif ($row_2['in_issuepaper']!='0')
									echo "javascript:alert('".$LDCannotEditIssuePaper.' '.$row['in_issuepaper']."')"; 
								else
									echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=update&todo=0&type='.$type.'&pres_id='.$row_2['prescription_id']; 
							} else {
								$date_pres = substr($row_2['date_time_create'],0,10);
								$today = date("Y-m-d");
								if ($date_pres<$today)
									echo "javascript:alert('".$LDCannotEditSheet."')"; 
								else
									echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=update&todo=1&pres_id='.$row_2['prescription_id'];
							}
						?>"> 
				<img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0','absmiddle'); ?>>		
				</a>
			</td>
		</tr>
	<?php
	} ?>
	</table>

	<?php
	if($parent_admit&&!$is_discharged) {
	?>
		<p>
		<table>
			<tr>
				<td><img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?>></td>
				<td><!-- Y lenh -->
					<a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=create&todo=1&type=sheet'; ?>"> 
						<?php echo $LDAddTreatment; ?>
					</a></br>
				</td>
			</tr>
			<tr>
				<td><img src="../../gui/img/common/default/pixel.gif" border=0 width=14 height=16 align="left"></td>
				<td><!-- Ke toa cu -->
					<a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=create&todo=0&type=sheet&as_old=1'; ?>"> 
						<?php echo $LDAddOldPresTreatment; ?>
					</a></br>
				</td>
			</tr>
			<tr>
				<td><img src="../../gui/img/common/default/pixel.gif" border=0 width=14 height=16 align="left"></td>
				<td><!-- Ke toa moi -->
					<a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=create&todo=0&type=sheet'; ?>"> 
						<?php echo $LDAddNewPresTreatment; ?>
					</a>
				</td>
			</tr>
		</table>
		<p>&nbsp;</p>
<?php
	}
}
?>

