<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
$top_dir='modules/nursing/include/';
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
$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');

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
	echo $LDPharmaPayOutMedicine; ?>
</TITLE>

<style type="text/css">
table {
	font-family: verdana, arial, tahoma;
	font-size: 12px;
	font-weight: normal;
	color: black;
}
</style>
<script language="javascript">
function printOut()
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/xuatkhochan.php<?php echo URL_APPEND; ?>&report_id=<?php echo $report_id; ?>&type=medicine";
	testprintpdf=window.open(urlholder,"PhieuXuatKho","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
</script>
</HEAD>

<BODY>

<?php
if(!isset($Pharma)) $Pharma = new Pharma;



$report_show = $Pharma->getPayOutInfo($report_id);
$medicine_in_pres = $Pharma->getDetailPayOutInfo($report_id);

if (!$report_show || !$medicine_in_pres){
	echo $LDItemNotFound;
	exit;
}

if($report_show['status_finish']){
	$tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
else{
	$tempfinish=$LDNotYet; $tempfinish1='warn.gif';}

$date1 = formatDate2Local($report_show['create_time'],'dd/mm/yyyy');
$time1 = substr($report_show['create_time'],-8);

if ($report_show['health_station']==0)
	$placeto=$LDKhoLe;
else {	
	if($tempplace = $Pharma->getNameHealthStation($report_show['health_station'])){
		$placeto=$tempplace['typename'].' '.$tempplace['name'];
	}
}	
//Su nghiep, BHYT, CBTC
switch ($report_show['typeput']){
	case 0: $usefor=$LDBH; break;
	case 1: $usefor=$LDNoBH; break;
	case 2: $usefor=$LDCBTC; break;
	default: $usefor=$LDNoBH; break;
}
	
?>


	<table border=0 cellpadding=3 width="98%">
		<tr><td colspan="4" align="center"><b> <?php echo $LDPharmaPayOutMedicine; ?> </b> <br> &nbsp; </td></tr>
		<tr bgcolor="#f6f6f6">
			<td width="20%"><FONT SIZE=-1  color="#000066"><?php echo $LDIssueId; ?></td>
			<td width="35%"><FONT SIZE=-1  ><?php echo $report_id; ?></td>
			<td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $LDStatus; ?></td>
			<td width="30%"><FONT SIZE=-1  ><?php echo $tempfinish.' ';?> 
				<img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?>> </td>
	   </tr> 
	   	<tr bgcolor="#f6f6f6">
			<td width="20%"><FONT SIZE=-1  color="#000066"><?php echo $LDVoucher; ?></td>
			<td width="35%"><FONT SIZE=-1  ><?php echo $report_show['voucher_id']; ?></td>
			<td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $LDDate;  ?></td>
			<td width="30%"><FONT SIZE=-1  ><?php  echo $date1.' '.$time1; ?></td>
	   </tr> 
	   	<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDExportTo; ?></td>
			<td><FONT SIZE=-1  ><?php echo $placeto; ?></td>
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDReceiveUser; ?></td>
			<td><FONT SIZE=-1  ><?php echo $report_show['receiver']; ?></td>
	   </tr>
		<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDNote; ?></td>
			<td><FONT SIZE=-1  ><?php echo $report_show['note']; ?></td>
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDUseFor; ?></td>
			<td><FONT SIZE=-1  ><?php echo $usefor; ?></td>
		</tr> 
		
		 <!-- Them thuoc vao toa thuoc -->
		<tr>
			<td colspan="4" align="center"><br>
				<table bgcolor="#EEEEEE" width="100%" cellpadding="3">
					<tr bgColor="#E1E1E1" >
						<td width="4%" align="center"><u><?php echo $LDSTT ?></u></td>
						<td width="12%" align="center"><u><?php echo $LDMedicineID ?></u></td>
						<td width="30%" align="center"><u><?php echo $LDMedicineName ?></u></td>
						<td width="10%" align="center"><u><?php echo $LDUnit ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDLotID1 ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDExpDate1 ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDNumberOf ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDCost ?></u></td>
						<td width="14%" align="center"><u><?php echo $LDNote ?></u></td>
					</tr>
				
			<?php 
				$medicine_count = $medicine_in_pres->RecordCount();
				//$total_temp=0;
				for($i=1;$i<=$medicine_count;$i++) { 			
					$rowIssue = $medicine_in_pres->FetchRow();								
								
				echo '<tr bgColor="#ffffff" >
						<td align="center" bgColor="#ffffff">'.$i.'.</td>
						<td bgColor="#ffffff">'.$rowIssue['product_encoder'].'</td>
						<td bgColor="#ffffff">'.$rowIssue['product_name'].'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['unit_name_of_medicine'].'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['lotid'].'</td>
						<td align="center" bgColor="#ffffff">'.(formatDate2Local($rowIssue['exp_date'],'dd/mm/yyyy')).'</td>
						<td align="center" bgColor="#ffffff">'.number_format($rowIssue['number']).'</td>
						<td align="center" bgColor="#ffffff">'.number_format($rowIssue['price'],2).'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['note'].'</td>
					</tr>';
						//$total_temp += $rowIssue['number_voucher']*$rowIssue['price'];
				} ?>
				</table>
				&nbsp;<br>
			</td>
		</tr>
		
		  <!-- Loi dan bac si & button -->
		<tr bgcolor="#f6f6f6">
			<td align="top"><FONT SIZE=-1 color="#000066"><?php echo $LDUserAccept; ?></td>
			<td><FONT SIZE=-1 ><?php echo $report_show['user_accept']; ?></td>
			<td align="top"><FONT SIZE=-1 color="#000066"><?php echo $LDTotalNumber; ?></td>
			<td align="center"><FONT SIZE=-1 ><?php echo number_format($report_show['totalcost']); ?></td>
		</tr> 
		<tr>
			<td colspan="4" align="center">
			&nbsp;<br>
			<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut ?>"></a>
			</td>
		</tr>
	 </table>


</BODY>
</HTML>
