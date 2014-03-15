
<script type="text/javascript">
function viewDetail(enc_nr,pid,pres_id)
{
	var win = 'show_prescription_depot_detail.php<?php echo URL_APPEND; ?>' + '&pid=' + pid + '&enc_nr='+ enc_nr +'&pres_id=' + pres_id;
	myWindow=window.open( win , 'View Details' , 'height=500,width=650' );
	myWindow.focus();
}
</script>

<!-- Toa VTYT -->
<?php 
include_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
if(!isset($pres_obj)) $pres_obj=new PrescriptionMedipot;


?>
	<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
		<tr bgcolor="#85A4CD"><td colspan="3" ><font color="#ffffff"><b><?php echo $LDListDepot; ?></b></td></tr>
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
				<a href="javascript:viewDetail(<?php echo $_SESSION['sess_en'].','.$_SESSION['sess_pid'].','.$row['prescription_id']; ?>)"> 
					<img <?php echo createComIcon($root_path,'info3.gif','0','',TRUE); ?>>
				</a>
				<b><?php echo $LDPrescriptionMedipotId.": ".$row['prescription_id']."    "; ?></b>
			</td>
			<td colspan=2 ><FONT SIZE=-1  FACE="Arial"><b><?php echo $LDDate.': '. @formatDate2Local($row['date_time_create'],$date_format); ?></b></td>
			
		</tr>
		  
		<tr bgcolor="<?php echo $bgc; ?>" valign="top">
			<td colspan=3><FONT SIZE=-1  FACE="Arial"><?php echo $LDNote.": ".$row['note']; ?></td>
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
				<?php 	echo $LDGotVTYT.": ".$temp; ?>
			</td>
			
		</tr>
		
		<tr bgcolor="<?php echo $bgc; ?>" valign="top">
			<td width="50%"><FONT SIZE=-1  FACE="Arial"><?php echo $LDNurse.': '.$row['doctor']; ?></td>
			<td width="35%"><FONT SIZE=-1  FACE="Arial"><?php echo $LDPrice.": ".number_format($row['total_cost']); ?></td>
			<td width="15%" align="right">	
				<a href="<?php	if($row['status_bill'])
									echo "javascript:alert('".$LDCannotEdit."')"; 
								elseif($row['status_finish'])
									echo "javascript:alert('".$LDCannotEditFinish."')"; 
								elseif ($row['in_issuepaper']!='0')
									echo "javascript:alert('".$LDCannotEditIssuePaper.' '.$row['in_issuepaper']."')"; 
								else
									echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&encounter_nr='.$encounter_nr.'&mode=update&pres_id='.$row['prescription_id']; 
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
			<a href="<?php echo $thisfile.URL_APPEND.'&pid='.$_SESSION['sess_pid'].'&target='.$target.'&mode=create&type=pres'; ?>"> 
			<?php echo $LDCreateDepot; ?>
			</a>
		</td></tr></table>
		<p>
<?php
	}
	


?>

