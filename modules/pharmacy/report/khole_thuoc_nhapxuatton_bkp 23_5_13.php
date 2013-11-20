<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

require_once($root_path.'include/care_api_classes/class_pharma.php');
$Pharma = new Pharma;

$thisfile= basename(__FILE__);
$breakfile='../report_khole.php'.URL_APPEND;
$fileforward='khole_baocaothuoc_save.php'.URL_APPEND;
$date_format='dd-mm-yyyy';



# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoLe.' :: '.$LDPharmaReportImportExport);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDReportImportExport_Medicine')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDPharmaReportImportExport);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
 ob_start();
?>
<style type="text/css">
.input2 {border:none;text-align:right;}
.input3 {border:none;}
</style>
<script  language="javascript">
<!--
function chkform(d) {
	document.reportform.action=<?php echo $thisfile; ?>;
	document.reportform.submit();
}
function printOut(select_type,month,year)
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_thuoc_nhapxuatton.php<?php echo URL_APPEND; ?>&type=medicine&select_type="+select_type+"&month="+month+"&year="+year;
	testprintpdf=window.open(urlholder,"NhapXuatTon","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}
function Save(alertsave){
	if(alertsave=='ok'){
		var month=document.getElementById('showmonth').value;
		var year=document.getElementById('showyear').value;
		document.reportform.action="<? echo $fileforward; ?>&target=save&month="+month+"&year="+year;
		document.reportform.submit();
	} 
	else alert(alertsave);
}
function selectTypeMed() {
	var temp_i = document.getElementById("type_med").selectedIndex;
	document.getElementById("select_type").value = document.getElementById("type_med").options[temp_i].value;
	document.reportform.action='<?php echo $thisfile.'&type='.$type; ?>';
	document.reportform.submit();
}
-->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 
 
 $smarty->assign('sRegForm','<form name="reportform" method="POST"  onSubmit="return chkform(this)">');

//***********************************NOI DUNG TRANG********************************
$smarty->assign('LDReportMedicine',$LDReportMedicine);
$smarty->assign('titleForm',$LDReportImportExport_Medicine);

$smarty->assign('LDFromDate',$LDFrom);
$smarty->assign('LDToDate',$LDTo);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineName',$LDMedicineName);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDPrice',$LDCost);
$smarty->assign('LDNhap',$LDImport1);
$smarty->assign('LDXuat',$LDExport1);
$smarty->assign('LDTonDau',$LDTonDau);
$smarty->assign('LDTonCuoi',$LDTonCuoi);
$smarty->assign('LDTotalCost',$LDTotalCost);
$smarty->assign('LDNote',$LDNote);
$smarty->assign('LDLotID',$LDLotID);
$smarty->assign('LDExpDate',$LDExpDate1);
$smarty->assign('LDGiaNhap',$LDGiaNhap);
$smarty->assign('LDGiaXuat',$LDGiaXuat);
$smarty->assign('LDGiaTonCuoi',$LDGiaTonCuoi);

//Report in
if(!isset($select_type) || $select_type=='')
	$select_type=0;
$s0=''; $s1=''; $s2=''; $s3='';
switch($select_type){
	case 0: $s0='selected'; break;
	case 1: $s1='selected'; break;
	case 2: $s2='selected'; break;
	case 3: $s3='selected'; break;
	default: $s0='selected';
}
$temp='<select id="type_med" name="type_med" onchange="selectTypeMed()">
			<option value="0" '.$s0.' >'.$LDMedicine.'</option>
			<option value="1" '.$s1.' >'.$LDMedicine_KP.'</option>
			<option value="2" '.$s2.' >'.$LDMedicine_BH.'</option>
			<option value="3" '.$s3.' >'.$LDMedicine_CBTC.'</option>
		</select>';
$smarty->assign('inputby',$temp);

//reportmonth, reportyear: thang ke tiep can bao cao + luu
//showmonth, showyear: thang nguoi dung muon xem

	switch($select_type){	
		case 0: $cond_typeput = ''; break;
		case 1: $cond_typeput = ' AND typeput=1 '; break;		//su nghiep
		case 2: $cond_typeput = ' AND typeput=0 '; break;		//bhyt
		case 3: $cond_typeput = ' AND typeput=2 '; break;		//cbtc
		default: $cond_typeput = ' AND typeput=1 ';
	}

switch($type){
		case 'tayy': $dongtayy =' AND main.pharma_type IN (1,2,3)'; break;	
		case 'dongy': $dongtayy = ' AND main.pharma_type IN (4,8,9,10) '; break;
		default: $dongtayy = ''; break;
	}
	
//Check month report
$result=$Pharma->checkAnyReport($cond_typeput);

#Neu da co bao cao thang truoc
if($result!=false){					
	$lastmonth=$result['getmonth'];
	$lastyear=$result['getyear'];
	if($lastmonth!=12){
		$reportmonth=$lastmonth+1;	
		$reportyear=$lastyear;
	}
	else{
		$reportmonth=1;
		$reportyear=$lastyear+1;
	}
	
	if(!isset($showmonth) || $showmonth==''){
		$showmonth=$reportmonth;
		$showyear=$reportyear;
	} 
	
	//So sanh $showmonth & reportmonth
	if(($showmonth<$reportmonth && $showyear==$reportyear) || ($showyear<$reportyear)){
		//Neu tha'ng nguoi dung muon xem da luu:
		$listReport = $Pharma->Khochan_thuoc_tonthangtruoc($showmonth, $showyear, $cond_typeput.$dongtayy);		//AND re.typeput='' 
		$alertsave=$LDDaLuuBaoCao;
	}else if($showmonth==$reportmonth && $showyear==$reportyear){
		//Neu tha'ng nguoi dung muon xem la tha'ng tiep theo can bao cao + luu: 
		$listReport = $Pharma->Khochan_baocaothuoc_nhapxuatton($showmonth, $showyear, $cond_typeput.$dongtayy);
		$alertsave='ok';
	}else if(($showmonth>$reportmonth && $showyear==$reportyear) || ($showyear>$reportyear)){
		//Neu tha'ng nguoi dung muon xem nam sau tha'ng can luu: 
		$sTempDiv='<tr bgColor="#ffffff"><td colspan="18">'.$LDPlsSaveReport.' '.$reportmonth.'/'.$reportyear.' '.$LDfirst.'</td></tr>';
		$alertsave=$LDPlsSaveReport.' '.$reportmonth.'/'.$reportyear.' '.$LDfirst;
	}
#Neu chua co bao cao nao
}else{				
	//Neu chua co bao cao nao + da co nhap xuat				
	if($result1=$Pharma->checkAnyPutIn($cond_typeput)){
		$reportmonth=$result1['getmonth'];
		$reportyear=$result1['getyear'];
		
		if(!isset($showmonth) || $showmonth==''){
			$showmonth=$reportmonth;
			$showyear=$reportyear;
		} 
		 
		if($showmonth==$reportmonth && $showyear==$reportyear){
			//Neu tha'ng nguoi dung muon xem la tha'ng can bao cao + luu:
			$listReport = $Pharma->Khochan_baocaothuoc_nhapxuatton($showmonth, $showyear, $cond_typeput.$dongtayy);
			$alertsave='ok';
		}else if(($showmonth>$reportmonth && $showyear==$reportyear) || ($showyear>$reportyear)){		
			//Neu tha'ng nguoi dung muon xem nam sau tha'ng can luu: 
			$sTempDiv='<tr bgColor="#ffffff"><td colspan="18">'.$LDPlsSaveReport.' '.$reportmonth.'/'.$reportyear.' '.$LDfirst.'</td></tr>';
			$alertsave=$LDPlsSaveReport.' '.$reportmonth.'/'.$reportyear.' '.$LDfirst;
		}else{ 
			//Neu tha'ng nguoi dung muon xem nam truoc thang can luu: 
			$alertsave=$LDChuaCoBaoCao;
			$sTempDiv='<tr bgColor="#ffffff"><td colspan="18">'.$LDNotAnyPutIn.' ('.$showmonth.'/'.$showyear.')</td></tr>';
		}
		
	//Chua co bao cao nao, chua co ton dau, chua co nhap xuat	
	}else{
		$sTempDiv='<tr bgColor="#ffffff"><td colspan="18">'.$LDNotAnyPutIn.' ('.$showmonth.'/'.$showyear.')</td></tr>';
		$alertsave=$LDChuaCoBaoCao;
	}
}


$smarty->assign('monthreport',$LDMonth.': <input type="text" id="showmonth" name="showmonth" size="1" value="'.$showmonth.'">/ <input type="text" id="showyear" name="showyear" size="3" value="'.$showyear.'">');
$smarty->assign('LDMonthReport',$LDMonth.': '.$showmonth.'/'.$showyear);

if(is_object($listReport)){
	$i=1; $maxid=$listReport->RecordCount();
	$sTempDiv='';
	ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		$baocaotruoc = $Pharma->Khochan_thuoc_tontruoc($rowReport['product_encoder'], $cond_typeput.$dongtayy);
		if($baocaotruoc!=false){
			$tondau=$baocaotruoc['last_number'];
			$dongiatondau=$baocaotruoc['last_cost'];
		}else{
			$tondau=0;
			$dongiatondau=$rowReport['price'];
		}
		if($rowReport['xuat']=='')
			$rowReport['xuat']=0;
		$toncuoi=$tondau+$rowReport['nhap']-$rowReport['xuat'];
		
		echo '<tr bgColor="#ffffff" >';
		echo	'<td align="center">'.$i.'<input type="hidden" name="encoder'.$i.'" value="'.$rowReport['product_encoder'].'"></td>'; //STT
		echo	'<td>'.$rowReport['product_name'].'</td>';	//Ten thuoc
		echo 	'<td align="center">'.$rowReport['unit_name_of_medicine'].'</td>';  //Don vi
		echo 	'<td align="center">'.$rowReport['lotid'].'</td>';  //So lo
		echo 	'<td align="center">'.@formatDate2Local($rowReport['exp_date'],'dd/mm/yyyy').'</td>';  //Han dung
		
		if (strstr($rowReport['price'],'.'))
			$showprice = number_format($rowReport['price'],2);
		else $showprice = number_format($rowReport['price']);
				
		echo	'<td><input type="text" size="5" name="tondau'.$i.'" value="'.number_format($tondau).'" class="input2" readonly></td>';	//Ton dau
		echo	'<td><input type="text" size="5" name="gia'.$i.'" value="'.$showprice.'" class="input2" readonly></td>';	//Don gia
		echo	'<td align="right">'.number_format($tondau*$dongiatondau).'</td>';	//TT
		echo	'<td><input type="text" size="5" name="nhap'.$i.'" value="'.number_format($rowReport['nhap']).'" class="input2" readonly></td>';	//Nhap
		echo	'<td align="right">'.number_format($rowReport['gianhap']).'</td>';	//Gia nhap
		echo	'<td align="right">'.number_format($rowReport['nhap']*$rowReport['gianhap']).'</td>';	//TT nhap
		echo	'<td><input type="text" size="5" name="xuat'.$i.'" value="'.number_format($rowReport['xuat']).'" class="input2" readonly></td>';	//Xuat
		echo	'<td align="right">'.number_format($rowReport['giaxuat']).'</td>';	//Gia xuat
		echo	'<td align="right">'.number_format($rowReport['xuat']*$rowReport['giaxuat']).'</td>';	//TT xuat
		echo	'<td><input type="text" size="5" name="toncuoi'.$i.'" value="'.number_format($toncuoi).'" class="input2" readonly></td>';	//Ton cuoi
		echo	'<td align="right">'.number_format($rowReport['giaxuat']).'</td>';	//Gia ton cuoi
		echo	'<td align="right">'.number_format($toncuoi*$rowReport['giaxuat']).'</td>';	//TT
		echo	'<td>'.$rowReport['note'].'</td>';	//Note
		echo '</tr>';
		$i++;
	}		
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();
					
} else {
	$maxid=0;
	if (!isset($sTempDiv) || $sTempDiv=='')
		$sTempDiv='<tr bgColor="#ffffff"><td colspan="18">'.$LDNotReportThisMonth.'</td></tr>';	
}

$smarty->assign('divItem',$sTempDiv);
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="maxid" value="'.$maxid.'">
		<input type="hidden" id="select_type" name="select_type" value="'.$select_type.'">
		<input type="hidden" name="user_report" value="'.$_SESSION['sess_user_name'].'">';

$smarty->assign('sHiddenInputs',$sTempHidden);


//*********************************************************************************
if ($alertsave=='ok'){
	$monthnow=date('m');
	if ($monthnow>$reportmonth)
		$alertsave='ok';
	else 
		$alertsave=$LDQuaThangMoiBaoCao;
}


$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
$smarty->assign('pbSave','<a href="javascript:Save(\''.$alertsave.'\');"><img '.createLDImgSrc($root_path,'savedisc.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbPrint','<a href="javascript:window.printOut(\''.$select_type.'\',\''.$showmonth.'\',\''.$showyear.'\');"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/khochan_report.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

