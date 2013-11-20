<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('./roots.php');
require_once($root_path.'include/core/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
 * GNU General Public License
 * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
 * elpidio@care2x.org, 
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','aufnahme.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

?>

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php
html_rtl ( $lang );
?>
<HEAD>
<?php
echo setCharSet ();
?>
<TITLE><?php
	echo $LDShowDetails; ?>
</TITLE>

<style type="text/css">

</style>
</HEAD>

<BODY>

<?php

//echo $LDShowDetails.' '.$pid.' '.$pres_id;

//$pres_show['']: thong tin chung toa thuoc 
//$result['']: thong tin cua benh nhan
//$medicine_in_pres: danh sach thuoc trong toa
//$medicine_count = $medicine_in_pres->RecordCount()


//Get info of pres & medicine
include_once($root_path.'include/care_api_classes/class_prescription_medipot.php');
if(!isset($pres)) $pres=new PrescriptionMedipot;
$pres_show = $pres->getPrescriptionInfo($pres_id);
if($medicine_in_pres = $pres->getAllMedicineInPres($pres_id))
	$medicine_count = $medicine_in_pres->RecordCount();

if($pres_show['status_bill']){
	$tempbill=$LDFinish; $tempbill1='check-r.gif';}
else{
	$tempbill=$LDNotYet; $tempbill1='warn.gif';}
if($pres_show['status_finish']){
	$tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
else{
	$tempfinish=$LDNotYet; $tempfinish1='warn.gif';}
	
$date1 = formatDate2Local($pres_show['date_time_create'],'dd/mm/yyyy');
$time1 = substr($pres_show['date_time_create'],-8);
		
//Get info of encounter
include_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter;
$enc_obj->loadEncounterData($enc_nr);
if( $enc_obj->is_loaded)
	$result=&$enc_obj->encounter;
	
require_once($root_path.'include/care_api_classes/class_ward.php');
if(!isset($ward_obj)) $ward_obj=new Ward;

$wardid = $result['current_ward_nr'];
if ($wardid)
	$wardname = $ward_obj->WardName($wardid);	//Khu phong
$insurance_nr = $result['insurance_nr'];	//So BHYT

echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?lang='.$lang.'&fen='.$enc_nr.'&en='.$enc_nr.'" width=282 height=178>';
echo '<FONT SIZE=-1  FACE="Arial"><p><b>'.$LDPrescriptionMedipotId.': '.$pres_show['prescription_id'].'</b></p>';
?>
	<table border=0 cellpadding=3 width="95%">
		<tr bgcolor="#f6f6f6">
			<td width="15%"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDWard; ?></td>
			<td width="40%"><FONT SIZE=-1  FACE="Arial"><?php echo $wardname; ?></td>
			<td width="20%"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPrescriptionMedipot; ?></td>
			<td width="25%"><FONT SIZE=-1  FACE="Arial"><?php  echo $pres_show['type_name']; ?></td>
	   </tr> 
	   <tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPrescribedBy; ?></td>
			<td><FONT SIZE=-1  FACE="Arial"><?php echo $pres_show['doctor']; ?></td>
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDate; ?></td>
			<td><FONT SIZE=-1  FACE="Arial"><?php echo $date1.' '.$time1; ?></td>
	   </tr>
	   <tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTotalDay; ?></td>
			<td><FONT SIZE=-1  FACE="Arial"><?php echo $pres_show['sum_date']; ?></td>
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDInsuranceNr; ?></td>
			<td><FONT SIZE=-1  FACE="Arial"><?php echo $insurance_nr; ?></td>
	   </tr>
	   <tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDPaid;?></td>
			<td><FONT SIZE=-1  FACE="Arial">
				<?php echo $tempbill.' ';?><img <?php echo createComIcon($root_path,$tempbill1,'0','',TRUE); ?>>
			</td>
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDGotVTYT; ?></td>
			<td><FONT SIZE=-1  FACE="Arial">
				<?php
						echo $tempfinish.' ';?>
						<img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?>>
			</td>
		</tr>
		
		 <!-- Them thuoc vao toa thuoc -->
		<tr>
			<td colspan="4" align="center"><br>
				<table bgcolor="#EEEEEE" width="100%" cellpadding="3">
					<tr bgcolor="#ffffff">
						<td align="center"><FONT SIZE=-1  FACE="Arial"><u><?php echo 'STT'; ?></u></td>
						<td align="center"><FONT SIZE=-1  FACE="Arial"><u><?php echo $LDPresEncoder; ?></u></td>
						<td align="center"><FONT SIZE=-1  FACE="Arial"><u><?php echo $LDMedipotName; ?></u></td>
						<td align="center"><FONT SIZE=-1  FACE="Arial"><u><?php echo $LDNumberOf; ?></u></td>
						<td align="center"><FONT SIZE=-1  FACE="Arial"><u><?php echo $LDCost; ?></u></td>
						<td align="center"><FONT SIZE=-1  FACE="Arial"><u><?php echo $LDTotalCost; ?></u></td>
					</tr>
				
			<?php for($i=1;$i<=$medicine_count;$i++) { 			
					$medicine_pres = $medicine_in_pres->FetchRow();	
					$totalcostmedicine = $medicine_pres['sum_number']*$medicine_pres['cost'];						
					$strtext = $medicine_pres['desciption']; //howtouse count totalunits/per
						$strtext = explode("/", $strtext);
						$split_desciption = explode(" ", $strtext[0]);								
								
				echo '<tr bgcolor="#ffffff">
						<td width="3%">'.$i.'.</td>
						<td width="12%"><FONT SIZE=-1  FACE="Arial">'.$medicine_pres['product_encoder'].'</td> 
						<td width="45%"><FONT SIZE=-1  FACE="Arial">
							<!-- Ten thuoc / lieu luong-->
							<b>'.$medicine_pres['product_name'].'</b>
						</td>';
						
				echo	'<!-- So luong, don gia, thanh tien -->
						<td align="center" width="15%"><FONT SIZE=-1  FACE="Arial">
							'.$medicine_pres['sum_number'].' '.$medicine_pres['note'].'
						</td>
						<td align="right" width="12%"><FONT SIZE=-1  FACE="Arial">
							'.number_format($medicine_pres['cost']).'
						</td>
						<td align="right" width="13%"><FONT SIZE=-1  FACE="Arial">
							'.number_format($totalcostmedicine).'
						</td>
					</tr>';

				} ?>
				</table>
				&nbsp;<br>
			</td>
		</tr>
		
		  <!-- Loi dan bac si & button -->
		<tr bgcolor="#f6f6f6">
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDNote; ?></td>
			<td><FONT SIZE=-1  FACE="Arial"><?php echo $pres_show['note']; ?></td>
			<td><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTotal; ?></td>
			<td><FONT SIZE=-1  FACE="Arial"><?php echo number_format($pres_show['total_cost']); ?></td>
		</tr>  
		<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1 color="#000066"><?php echo $LDIssueUser; ?></td>
			<td><FONT SIZE=-1 ><?php echo $pres_show['issue_user']; ?></td>
			<td align="top"><FONT SIZE=-1 color="#000066"><?php echo $LDReceiveUser; ?></td>
			<td><FONT SIZE=-1 ><?php echo $pres_show['receive_user']; ?></td>
		</tr> 
		<tr bgcolor="#f6f6f6">
			<td valign="top"><FONT SIZE=-1 color="#000066"><?php echo $LDNoteIssue; ?></td>
			<td colspan="3"><FONT SIZE=-1 ><?php echo $pres_show['issue_note']; ?></td>
		</tr> 
		<tr bgcolor="#f6f6f6">
			<td colspan="4"> &nbsp; </td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" align="left"><FONT SIZE=1  FACE="Arial"> <?php if($pres_show['status_bill']) echo $LDNoteMedicineBill; else echo $LDNoteMedipotPres; ?></td>
		</tr>

	 </table>


</BODY>
</HTML>
