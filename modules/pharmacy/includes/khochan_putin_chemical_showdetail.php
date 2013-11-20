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
    echo $LDRequestKhoChanPutInChemical; ?>
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
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/nhapkhochanHC.php<?php echo URL_APPEND; ?>&report_id=<?php echo $report_id; ?>&type=chemical";
	testprintpdf=window.open(urlholder,"PhieuNhapKho","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
function printOutBBKiemNhap()
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/bienbankiemnhapkhochan.php<?php echo URL_APPEND; ?>&report_id=<?php echo $report_id; ?>&type=chemical";
	testprintpdf=window.open(urlholder,"BienBanKiemNhap","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	
}
</script>
</HEAD>

<BODY>

<?php
    if(!isset($Pharma)) $Pharma = new Pharma;

    $report_show = $Pharma->getPutInChemicalInfo($report_id);
    $medicine_in_pres = $Pharma->getDetailPutInChemicalInfo($report_id);

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

    $date2 = formatDate2Local($report_show['date_time'],'dd/mm/yyyy');
	
?>


	<table border=0 cellpadding=3 width="98%">
		<tr><td colspan="4" align="center"><b> <?php echo $LDRequestKhoChanPutInChemical; ?> </b> <br> &nbsp; </td></tr>
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
			<td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $LDDateOfReport;  ?></td>
			<td width="30%"><FONT SIZE=-1  ><?php  echo $date1.' '.$time1; ?></td>
	   </tr>
                <tr bgcolor="#f6f6f6">
			<td width="20%"><FONT SIZE=-1  color="#000066"><?php echo $LDTyptPutIn; ?></td>
			<td width="35%"><FONT SIZE=-1  >
                            <?php 
                                switch ($report_show['typeput']){
                                    case 0;
                                        $typeput='Bảo hiểm y tế';
                                        break;
                                    default:
                                        $typeput='Sự nghiệp';
                                        break;
                                }
                                echo $typeput; 
                            ?>
                            </FONT></td>
                        <td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $LDVAT; ?></FONT></td>
                        <td width="15%"><FONT SIZE=-1  color="#000066"><?php echo $report_show['vat'].'   %'; ?></FONT></td>
               </tr>
	   	<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDSupplier; ?></td>
			<td><FONT SIZE=-1  ><?php echo $report_show['supplier']; ?></td>			
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDDatePutIn; ?></td>
			<td><FONT SIZE=-1  ><?php echo $date2; ?></td>
	   </tr>
		<tr bgcolor="#f6f6f6">
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDNote; ?></td>
			<td><FONT SIZE=-1  ><?php echo $report_show['note']; ?></td>
			<td><FONT SIZE=-1  color="#000066"><?php echo $LDReceiveUser; ?></td>
			<td><FONT SIZE=-1  ><?php echo $report_show['put_in_person']; ?></td>
		</tr> 
		
		 <!-- Them thuoc vao toa thuoc -->
		<tr>
			<td colspan="4" align="center"><br>
				<table bgcolor="#EEEEEE" width="100%" cellpadding="3">
					<tr bgColor="#E1E1E1" >
						<td width="4%" align="center"><u><?php echo $LDSTT ?></u></td>
						<td width="12%" align="center"><u><?php echo $LDChemicalID ?></u></td>
						<td width="30%" align="center"><u><?php echo $LDChemicalName ?></u></td>
						<td width="10%" align="center"><u><?php echo $LDUnit ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDLotID1 ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDExpDate1 ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDNumberOf ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDNhan ?></u></td>
						<td width="9%" align="center"><u><?php echo $LDCost ?></u></td>
						<td width="14%" align="center"><u><?php echo $LDNote ?></u></td>
					</tr>
				
			<?php 
				$medicine_count = $medicine_in_pres->RecordCount();
				for($i=1;$i<=$medicine_count;$i++) { 			
					$rowIssue = $medicine_in_pres->FetchRow();								
								
				echo '<tr bgColor="#ffffff" >
						<td align="center" bgColor="#ffffff">'.$i.'.</td>
						<td bgColor="#ffffff">'.$rowIssue['product_encoder'].'</td>
						<td bgColor="#ffffff">'.$rowIssue['product_name'].'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['unit_name_of_chemical'].'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['lotid'].'</td>
						<td align="center" bgColor="#ffffff">'.(formatDate2Local($rowIssue['exp_date'],'dd/mm/yyyy')).'</td>
						<td align="center" bgColor="#ffffff">'.number_format($rowIssue['number']).'</td>
						<td align="center" bgColor="#ffffff">'.number_format($rowIssue['number_voucher']).'</td>					
						<td align="center" bgColor="#ffffff">'.number_format($rowIssue['price'],2).'</td>
						<td align="center" bgColor="#ffffff">'.$rowIssue['note'].'</td>
					</tr>';
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
			<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printphieunhap.gif','0') ?> alt="<?php echo $LDPrintOutNhapKho ?>"></a>&nbsp;&nbsp;&nbsp;
			<a href="#"><img <?php echo createLDImgSrc($root_path,'printkiemnhap.gif','0') ?> alt="<?php echo $LDPrintOutBBKiemNhap ?>" onclick="printOutBBKiemNhap()"></a>
			</td>
		</tr>
	 </table>


</BODY>
</HTML>
