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
define('LANG_FILE','products.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'include/care_api_classes/class_issuepaper.php');

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
	echo $LDShowIssueDetails; ?>
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
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/phieulinhthuoc.php<?php echo URL_APPEND; ?>&report_id=<?php echo $issue_id; ?>&type=medicine";
	testprintpdf=window.open(urlholder,"PhieuLinhThuoc","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
</script>
</HEAD>

<BODY>

<?php
if(!isset($IssuePaper)) $IssuePaper = new IssuePaper;

$issue_show = $IssuePaper->getIssuePaperInfo($issue_id);
$medicine_in_pres = $IssuePaper->getAllMedicineInIssuePaper($issue_id);

if (!$issue_show || !$medicine_in_pres){
	echo $LDIssueNotFound;
	exit;
}

if($issue_show['type']) $typeissue=$LDSumIssue;
else $typeissue=$LDDepotIssue;

if($issue_show['status_finish']){
	$tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
else{
	$tempfinish=$LDNotYet; $tempfinish1='warn.gif';}

$date1 = formatDate2Local($issue_show['date_time_create'],'dd/mm/yyyy');
$time1 = substr($issue_show['date_time_create'],-8);
	

$ward_nr = $issue_show['ward_nr'];
$dept_nr = $issue_show['dept_nr'];
//Get info of current department, ward
if ($ward_nr!='' && $ward_nr!='0'){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} else if ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
	}
}
	
?>


	<table border=0 cellpadding=3 width="98%">
		<tr><td colspan="4" align="center"><b> <?php echo $LDShowIssueDetails; ?> </b> <br> &nbsp; </td></tr>
		<tr bgcolor="#f6f6f6">
			<td width="20%"><FONT SIZE=-1  color="#000066"><?php echo $LDIssueId; ?></td>
			<td width="35%"><FONT SIZE=-1  ><?php echo $issue_id; ?></td>
			<td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $LDGotDrug; ?></td>
			<td width="30%"><FONT SIZE=-1  ><?php echo $tempfinish.' ';?> 
				<img <?php echo createComIcon($root_path,$tempfinish1,'0','',TRUE); ?>> </td>
	   </tr> 
	   	<tr bgcolor="#f6f6f6">
			<td width="20%"><FONT SIZE=-1  color="#000066"><?php echo $LDDept; ?></td>
			<td width="35%"><FONT SIZE=-1  ><?php echo $deptname; ?></td>
			<td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $LDTypeIssue; ?></td>
			<td width="30%"><FONT SIZE=-1  ><?php  echo $typeissue; ?></td>
	   </tr> 
	   <tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDWard; ?></td>
			<td><FONT SIZE=-1  ><?php echo $wardname; ?></td>
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDDate; ?></td>
			<td><FONT SIZE=-1  ><?php echo $date1.' '.$time1; ?></td>
	   </tr>
	   	<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDIssueBy; ?></td>
			<td><FONT SIZE=-1  ><?php echo $issue_show['nurse']; ?></td>
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDNoteCreator; ?></td>
			<td><FONT SIZE=-1  ><?php echo $issue_show['note']; ?></td>
		</tr> 
		
		 <!-- Them thuoc vao toa thuoc -->
		<tr>
			<td colspan="4" align="center"><br>
				<table bgcolor="#EEEEEE" width="100%" cellpadding="3">
					<tr bgColor="#E1E1E1" >
						<td width="4%" align="center" rowspan="2"><u><?php echo $LDSTT ?></u></td>
						<td width="30%" align="center" rowspan="2"><u><?php echo $LDMedicineName ?></u></td>
						<td width="10%" align="center" rowspan="2"><u><?php echo $LDUnit ?></u></td>
						<td width="9%" align="center" rowspan="2"><u><?php echo $LDRequest ?></u></td>
						<td width="13%" align="center" rowspan="2"><u><?php echo $LDPlus ?></u></td>
						<td width="20%" align="center" colspan="2"><u><?php echo $LDNumberOf ?></u></td>
						<td width="14%" align="center" rowspan="2"><u><?php echo $LDNote ?></u></td>
					</tr>
					<tr bgColor="#E1E1E1" >
						<td align="center"><u><?php echo $LDTotal ?></u></td>
						<td align="center"><u><?php echo $LDIssue ?></u></td>
					</tr>
				
			<?php 
				$medicine_count = $medicine_in_pres->RecordCount();
				for($i=1;$i<=$medicine_count;$i++) { 			
					$rowIssue = $medicine_in_pres->FetchRow();								
								
				echo '<tr bgColor="#ffffff" >
						<td align="center" bgColor="#ffffff">'.$i.'.</td>
						<td bgColor="#ffffff">'.$rowIssue['product_name'].'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['units'].'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['sumpres'].'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['plus'].'</td>
						<td align="center" bgColor="#ffffff">'.number_format($rowIssue['number_request']).'</td>
						<td align="center" bgColor="#ffffff">'.number_format($rowIssue['number_receive']).'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['note'].'</td>
					</tr>';
				} ?>
				</table>
				&nbsp;<br>
			</td>
		</tr>
		
		  <!-- Loi dan bac si & button -->
		<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1 color="#000066"><?php echo $LDIssueUser; ?></td>
			<td><FONT SIZE=-1 ><?php echo $issue_show['issue_user']; ?></td>
			<td align="top"><FONT SIZE=-1 color="#000066"><?php echo $LDReceiveUser; ?></td>
			<td><FONT SIZE=-1 ><?php echo $issue_show['receive_user']; ?></td>
		</tr> 
		<tr bgcolor="#f6f6f6">
			<td valign="top"><FONT SIZE=-1 color="#000066"><?php echo $LDNoteIssue; ?></td>
			<td colspan="3"><FONT SIZE=-1 ><?php echo $issue_show['issue_note']; ?></td>
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
