<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
/**
 * eComBill 1.0.04 for Care2002 beta 1.0.04
 * (2003-04-30)
 * adapted from eComBill beta 0.2
 * developed by ecomscience.com http://www.ecomscience.com
 * Dilip Bharatee
 * Abrar Hazarika
 * Prantar Deka
 * GPL License
 */
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

define('LANG_FILE','billing.php');
define('NO_2LEVEL_CHK',1);

require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$breakfile='billingmenu.php'.URL_APPEND;
$returnfile='billingmenu.php'.URL_APPEND;
$thisfile= basename(__FILE__).URL_APPEND;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDReport15);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LD15DayReport')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDReport15);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
 ob_start();
?>
<style type="text/css">

</style>
<script  language="javascript">
<!--
function chkform(d) {
	document.reportform.action='<?php echo $thisfile; ?>';
	document.reportform.submit();
}
function printOut() {
	window.print();
}
-->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
 $smarty->assign('sRegForm','<form name="reportform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('LDSTT',$LDNr);
$smarty->assign('LDMaKham',$LDMaKham);
$smarty->assign('LDHoTen',$LDHoTen);
$smarty->assign('LDXetNghiem',$LDLaboration);
$smarty->assign('LDCDHA',$LDCDHA);
$smarty->assign('LDThuoc',$LDThuoc);
$smarty->assign('LDMau',$LDBlood);
$smarty->assign('LDPhauThuat',$LDPTTT);
$smarty->assign('LDVTYT', $LDVTYT);
$smarty->assign('LDCongKham',$LDCongKham);
$smarty->assign('LDCPVanChuyen',$LDCPVanChuyen);
$smarty->assign('LDGiuong',$LDBed);
$smarty->assign('LDKhac',$LDKhac);
$smarty->assign('LDTongCong',$LDTongCong);
$smarty->assign('LDBHYT',$LDBHYT);
$smarty->assign('LDThanhToan',$LDThanhToan);
$smarty->assign('LDGhiChu',$LDGhiChu);
$smarty->assign('LDBHYTTra',$LDBHYTTra);
$smarty->assign('LDMaBHYT',$LDInsurance);
$smarty->assign('LDMaKCB',$LDMaKCB);
$smarty->assign('LDMaHoaDon',$LDMaHoaDon);
$smarty->assign('LDNhanVien',$LDNhanVien);
$smarty->assign('LDThoiGian',$LDVaoLuc);

$smarty->assign('LDOnDate',$LDDate);

//Default value date
if(!isset($todate) || $todate=='')
	$todate=date('d-m-Y');
//Test format fromday
if (isset($todate) && $todate!='' && strpos($todate,'-')<3) {
	list($f_day,$f_month,$f_year) = explode("-",$todate);
	$todate=$f_year.'-'.$f_month.'-'.$f_day;
}
else 
	list($f_year,$f_month,$f_day) = explode("-",$todate);

	
//Calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
$calendar->load_files();
$date_format='dd-mm-yyyy';
$smarty->assign('calendartoday',$calendar->show_calendar($calendar,$date_format,'todate',$todate));

//--------------------------------------------------------------------------------------------------------------------------------------
require_once($root_path.'include/care_api_classes/class_ecombill.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$eComBill = new eComBill;
$Encounter = new Encounter;
$tongtienthutrongngay=0;
$tong_xetnghiem=0; $tong_CDHA=0; $tong_thuoc=0; $tong_mau=0; $tong_PTTT=0; $tong_VTYT=0; $tong_congkham=0; $tong_vanchuyen=0; $tong_giuong=0;
$tong_tongcong=0; $tong_BHYTtra=0; 

#Bill at day
$listAllBill = $eComBill->getAllBillAtDay($todate);	
if(is_object($listAllBill)){
	$count=0;
	ob_start();
	while($eachBill = $listAllBill->FetchRow())	{			//tr
		if($Encounter->loadEncounterData($eachBill['bill_encounter_nr'])){
			$encounter=$Encounter->getLoadedEncounterData();
		} else $encounter = array();
		
		$listItem = $eComBill->listItemsByBillId($eachBill['bill_bill_no']);		
		$count++;
		echo '<tr bgColor="#ffffff" align="right">
				<td>'.$count.'</td>
				<td>'.$eachBill['bill_encounter_nr'].'</td>
				<td align="left">'.$encounter['name_last']." ".$encounter['name_first'].'</td>';
		if(is_object($listItem)){
			$sTempLabor=0; $sTempRadio=0; $sTempBlood=0; $sTempSur=0; $sTempBed=0; $sTempKCB=0; $sTempVC=0; $sTempThuoc=0; $sTempVTYT=0;
			for($i=0;$i<$listItem->RecordCount();$i++){		//td
				$item = $listItem->FetchRow();
				$groupnr = $item['item_group_nr'];
				if ($groupnr<=25){								//Xet nghiem 1->25			
					$sTempLabor += $item['bill_item_amount'];
				} elseif (($groupnr>=26 && $groupnr<=29) || $groupnr==39 || $groupnr==38){ 	//CDHA, Tham do	
					$sTempRadio += $item['bill_item_amount'];					
				} elseif ($groupnr>=30 && $groupnr<=32){		//Mau 30, dam 31, dich 32
					$sTempBlood += $item['bill_item_amount'];
				} elseif ($groupnr==33 || $groupnr==34){		//Thu thuat 33, Phau thuat 34
					$sTempSur += $item['bill_item_amount'];					
				} elseif ($groupnr==35 || $groupnr==36){		//Giuong 35,36
					$sTempBed += $item['bill_item_amount'];			
				} elseif ($groupnr==40){						//Kham benh 40
					$sTempKCB += $item['bill_item_amount'];			
				} elseif ($groupnr==41){						//Van chuyen 41
					$sTempVC += $item['bill_item_amount'];			
				} elseif ($groupnr==37){						//Thuoc 37
					$sTempThuoc += $item['bill_item_amount'];			
				} elseif ($groupnr==42 || $groupnr==43){						//VTYT 42
					$sTempVTYT += $item['bill_item_amount'];			
				} 
			}
		  echo '<td>'.number_format($sTempLabor).'</td>
				<td>'.number_format($sTempRadio).'</td>
				<td>'.number_format($sTempThuoc).'</td>
				<td>'.number_format($sTempBlood).'</td>
				<td>'.number_format($sTempSur).'</td>
				<td>'.number_format($sTempVTYT).'</td>
				<td>'.number_format($sTempKCB).'</td>
				<td>'.number_format($sTempVC).'</td>
				<td>'.number_format($sTempBed).'</td>
				<td></td>';
			$tong_xetnghiem += $sTempLabor; $tong_CDHA += $sTempRadio; $tong_thuoc += $sTempThuoc; $tong_mau += $sTempBlood; 
			$tong_PTTT += $sTempSur; $tong_VTYT += $sTempVTYT; $tong_congkham += $sTempKCB; $tong_vanchuyen += $sTempVC; 
			$tong_giuong += $sTempBed;	
		}else echo '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
		  $username = $eComBill->GetUserName($eachBill['create_id']);
		  $texttime = substr($eachBill['create_time'],-8);
		  echo '<td><b>'.number_format($eachBill['bill_amount']).'</b></td>
				<td>'.number_format($eachBill['bill_discount']).'</td>
				<td align="center">'.$encounter['insurance_nr'].'</td>
				<td>'.$encounter['madk_kcbbd'].'</td>
				<td><b>'.number_format($eachBill['bill_outstanding']).'</b></td>
				<td></td>
				<td>'.$eachBill['bill_bill_no'].'</td>
				<td>'.$texttime.'</td>
				<td>'.$username.'</td>
			</tr>';	
		$tongtienthutrongngay += $eachBill['bill_outstanding'];
		$tong_tongcong += $eachBill['bill_amount'];
		$tong_BHYTtra += $eachBill['bill_discount'];
	}
	$sTempDiv = $sTempDiv.ob_get_contents();
	ob_end_clean();
	
} 

#Final Bill at day
$listAllFinalBill = $eComBill->getAllFinalBillAtDay($todate);	
if(is_object($listAllFinalBill)){
	ob_start();
	while($eachFinalBill = $listAllFinalBill->FetchRow())	{
		if($Encounter->loadEncounterData($eachFinalBill['final_encounter_nr'])){
			$encounter=$Encounter->getLoadedEncounterData();
		} else $encounter = array();		
		$count++;
		$username = $eComBill->GetUserName($eachFinalBill['create_id']);
		 $texttime = substr($eachFinalBill['create_time'],-8);
		echo '<tr bgColor="#ffffff" align="right">
				<td>'.$count.'</td>
				<td>'.$eachFinalBill['final_encounter_nr'].'</td>
				<td align="left">'.$encounter['name_last']." ".$encounter['name_first'].'</td>';
		echo   '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
		echo   '<td>'.number_format($eachFinalBill['final_total_bill_amount']-$eachFinalBill['final_total_receipt_amount']).'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><b>'.number_format($eachFinalBill['final_amount_recieved']).'</b></td>
				<td align="left">'.$LDThanhToanNo.'</td>
				<td>'.$eachFinalBill['final_id'].'</td>
				<td>'.$texttime.'</td>
				<td>'.$username.'</td>
			</tr>';	
			$tongtienthutrongngay += $eachFinalBill['final_amount_recieved'];
	}
	$sTempDiv_1 = $sTempDiv_1.ob_get_contents();
	ob_end_clean();
}

#Payment at day
$listAllPayment = $eComBill->getAllPaymentAtDay($todate);	
if(is_object($listAllPayment)){
	ob_start();
	while($eachPayment = $listAllPayment->FetchRow())	{
		if($Encounter->loadEncounterData($eachPayment['payment_encounter_nr'])){
			$encounter=$Encounter->getLoadedEncounterData();
		} else $encounter = array();		
		$count++;
		$username = $eComBill->GetUserName($eachPayment['create_id']);
		$texttime = substr($eachPayment['payment_date'],-8);
		echo '<tr bgColor="#ffffff" align="right">
				<td>'.$count.'</td>
				<td>'.$eachPayment['payment_encounter_nr'].'</td>
				<td align="left">'.$encounter['name_last']." ".$encounter['name_first'].'</td>';
		echo   '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
		echo   '<td>'.number_format($eachPayment['payment_amount_total']).'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><b>'.number_format($eachPayment['payment_amount_total']).'</b></td>
				<td align="left">'.$LDPaymentTypePayment.'</td>
				<td>'.$eachPayment['payment_id'].'</td>
				<td>'.$texttime.'</td>
				<td>'.$username.'</td>
			</tr>';	
			$tongtienthutrongngay += $eachPayment['payment_amount_total'];
	}
	$sTempDiv_2 = $sTempDiv_2.ob_get_contents();
	ob_end_clean();
}
//$tong_xetnghiem=0; $tong_CDHA=0; $tong_thuoc=0; $tong_mau=0; $tong_PTTT=0; $tong_VTYT=0; $tong_congkham=0; $tong_vanchuyen=0; $tong_giuong=0;
//$tong_tongcong=0; $tong_BHYTtra=0; 
$sTempDiv_last='<tr bgColor="#F2F2F2" align="right">
					<th colspan="3" align="center">'.$LDTongket.'</th>
					<th>'.number_format($tong_xetnghiem).'</th>
					<th>'.number_format($tong_CDHA).'</th>
					<th>'.number_format($tong_thuoc).'</th>
					<th>'.number_format($tong_mau).'</th>
					<th>'.number_format($tong_PTTT).'</th>
					<th>'.number_format($tong_VTYT).'</th>
					<th>'.number_format($tong_congkham).'</th>
					<th>'.number_format($tong_vanchuyen).'</th>
					<th>'.number_format($tong_giuong).'</th>
					<th> - </th>
					<th>'.number_format($tong_tongcong).'</th>
					<th>'.number_format($tong_BHYTtra).'</th>
					<th> - </th><th> - </th>
					<th>'.number_format($tongtienthutrongngay).'</th>
					<th> - </th><th> - </th><th> - </th><th> - </th>
				</tr>';

if($count==0)
	$sTempDivTotal='<tr bgColor="#ffffff" ><td colspan="21">'.$LDItemNotFound.'</td></tr>';
else $sTempDivTotal=$sTempDiv.$sTempDiv_1.$sTempDiv_2.$sTempDiv_last;
$smarty->assign('divItem',$sTempDivTotal);

$smarty->assign('TongTienThu','<b>'.$LDTongTienThu.':  '.number_format($tongtienthutrongngay).'</b>');

require_once($root_path.'classes/money/convertMoney.php');
$smarty->assign('TongTienThuReader','<i>('.convertMoney($tongtienthutrongngay).')</i>');

 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">';

$smarty->assign('sHiddenInputs',$sTempHidden);

//*********************************************************************************

$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
$smarty->assign('pbPrint','<a href="javascript:printOut();"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');


# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','ecombill/report15.tpl');

# Show main frame

$smarty->display('common/mainframe.tpl');
?>