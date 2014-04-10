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
$breakfile='../report_khochan.php'.URL_APPEND;
//$fileforward='khochan_baocaothuoc_save.php'.URL_APPEND;
$date_format='dd-mm-yyyy';

//Thuoc huong tam than & tien chat: Thuoc gay te me: 1, Chong co giat, dong kinh: 5, Chong ngo doc: 4, Chong roi loan tam than: 24
//$pharma_generic_drug_id
$nhomdacbiet='gaynghien';

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDSuDungThuocGayNghien);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDSuDungThuocGayNghien')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDSuDungThuocGayNghien);

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
	document.reportform.action="<?php echo $thisfile; ?>";
	document.reportform.submit();
}
function printOut(type_month,month,year)
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_sudungthuoc_gaynghien.php<?php echo URL_APPEND; ?>&type_month="+type_month+"&showmonth="+month+"&showyear="+year;
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
	document.getElementById("showmonth").value='';
	document.getElementById("type_month").value = document.getElementById("type_med").options[temp_i].value;
	document.reportform.action='<?php echo $thisfile.URL_APPEND; ?>';
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
$smarty->assign('LDSelect',$LDSelect);
$smarty->assign('titleForm',$LDSuDungThuocGayNghien);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineName',$LDMedicineName);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDNote',$LDNote);

//Report in
if(!isset($type_month) || $type_month=='')
	$type_month=0;
$s0=''; $s1=''; $s2=''; $s3=''; $s4=''; 

if(!isset($showmonth) || $showmonth=='')
	$showmonth=1;
	
switch($type_month){
	case 0: $s0='selected'; 	//1 thang khoa duoc
			$smarty->assign('LDTonDau',$LDTon_1);
			$smarty->assign('LDNhap',$LDNhap_1);
			$smarty->assign('LDTong',$LDTong_1);
			$smarty->assign('LDXuat',$LDXuat_1);
			$smarty->assign('LDHong',$LDHong_1);
			$smarty->assign('LDTonCuoi',$LDTon_1);
			$template_report = 'pharmacy/khochan_sudungthuoc_khoaduoc.tpl'; 
			
			$option='';
			for($i=1;$i<=12;$i++){
				if($showmonth==$i) $tempselect=' selected ';
				else $tempselect='';	
				$option .= '<option value="'.$i.'" '.$tempselect.' >'.$i.'</option>';
			}
			$temp_showmonth= $LDMonth.': <select id="showmonth" name="showmonth" size="1">'.$option.'</select>';
			
			$smalltitle= $LDMonth.': '.$showmonth;
			break;

	case 1: $s1='selected'; 	//qui khoa duoc
			$smarty->assign('LDCost',$LDCost);
			$smarty->assign('LDTonDau',$LDTon_2);
			$smarty->assign('LDNhap',$LDNhap_2);
			$smarty->assign('LDXuat',$LDXuat_2);
			$smarty->assign('LDHong',$LDHong_2);
			$smarty->assign('LDTonCuoi',$LDTon_2);
			$template_report = 'pharmacy/khochan_sudungthuoc_6thang.tpl';  
			
			$option='';
			for($i=1;$i<=12;$i=$i+3){
				if($showmonth==$i) $tempselect=' selected ';
				else $tempselect='';	
				$option .= '<option value="'.$i.'" '.$tempselect.' >'.($i).' -> '.($i+2).'</option>';
			}
			$temp_showmonth= $LDMonth.': <select id="showmonth" name="showmonth" size="1">'.$option.'</select>';			
			$smalltitle= $LDQui.': '.(($showmonth-1)/3 +1);	
			break;			

			
	case 2: $s2='selected'; 	//6 thang khoa duoc
			$smarty->assign('LDCost',$LDCost);
			$smarty->assign('LDTonDau',$LDTon_2);
			$smarty->assign('LDNhap',$LDNhap_2);
			$smarty->assign('LDXuat',$LDXuat_2);
			$smarty->assign('LDHong',$LDHong_2);
			$smarty->assign('LDTonCuoi',$LDTon_2);	
			$template_report = 'pharmacy/khochan_sudungthuoc_6thang.tpl';
			
			$option='';
			for($i=1;$i<=12;$i=$i+6){
				if($showmonth==$i) $tempselect=' selected ';
				else $tempselect='';	
				$option .= '<option value="'.$i.'" '.$tempselect.' >'.($i).' -> '.($i+5).'</option>';
			}
			$temp_showmonth= $LDMonth.': <select id="showmonth" name="showmonth" size="1">'.$option.'</select>';
			
			$smalltitle= $LDMonth.': '.$showmonth.' '.$LDDen.' '.($showmonth+5);
			break;		

						
	case 3: $s3='selected'; 	//1 thang tung khoa
			//$smarty->assign('LDMonth',$LDMonth);
			$smarty->assign('LDKhoaNgoai',$LDKhoaNgoai);
			$smarty->assign('LDKhoaSan',$LDKhoaSan);
			$smarty->assign('LDKhoaHSCC',$LDKhoaCCHS);
			$smarty->assign('LDKhoaNoi',$LDKhoaNoi);
			$smarty->assign('LDKhoaDuoc',$LDKhoaDuoc);
			$smarty->assign('LDTong',$LDTong_1);	
			$template_report = 'pharmacy/khochan_sudungthuoc_khoakhac_1thang.tpl';
			
			$option='';
			for($i=1;$i<=12;$i++){
				if($showmonth==$i) $tempselect=' selected ';
				else $tempselect='';	
				$option .= '<option value="'.$i.'" '.$tempselect.' >'.$i.'</option>';
			}
			$temp_showmonth= $LDMonth.': <select id="showmonth" name="showmonth" size="1">'.$option.'</select>';			
			$smalltitle= $LDMonth.': '.$showmonth;
			$smarty->assign('month_1','<font size="1">'.$showmonth.'/'.$showyear.'</font>');
		
			break;
			
	case 4: $s4='selected'; 	//qui tung khoa
			//$smarty->assign('LDMonth',$LDMonth);
			$smarty->assign('LDKhoaNgoai',$LDKhoaNgoai);
			$smarty->assign('LDKhoaSan',$LDKhoaSan);
			$smarty->assign('LDKhoaHSCC',$LDKhoaCCHS);
			$smarty->assign('LDKhoaNoi',$LDKhoaNoi);
			$smarty->assign('LDKhoaDuoc',$LDKhoaDuoc);
			$smarty->assign('LDTong',$LDTong_1);	
			$template_report = 'pharmacy/khochan_sudungthuoc_khoakhac.tpl';
			
			$option='';
			for($i=1;$i<=12;$i=$i+3){
				if($showmonth==$i) $tempselect=' selected ';
				else $tempselect='';	
				$option .= '<option value="'.$i.'" '.$tempselect.' >'.($i).' -> '.($i+2).'</option>';
			}
			$temp_showmonth= $LDMonth.': <select id="showmonth" name="showmonth" size="1">'.$option.'</select>';
			$smarty->assign('month_1','<font size="1">'.$showmonth.'/'.$showyear.'</font>');
			$smarty->assign('month_2','<font size="1">'.($showmonth+1).'/'.$showyear.'</font>');
			$smarty->assign('month_3','<font size="1">'.($showmonth+2).'/'.$showyear.'</font>');
			
			$smalltitle= $LDQui.': '.(($showmonth-1)/3 +1);			
			break;			
			
	default: $s0='selected';	//1 thang khoa duoc
			$smarty->assign('LDTonDau',$LDTon_1);
			$smarty->assign('LDNhap',$LDNhap_1);
			$smarty->assign('LDTong',$LDTong_1);
			$smarty->assign('LDXuat',$LDXuat_1);
			$smarty->assign('LDHong',$LDHong_1);
			$smarty->assign('LDTonCuoi',$LDTon_1);
			$template_report = 'pharmacy/khochan_sudungthuoc_khoaduoc.tpl';
			
			$option='';
			for($i=1;$i<=12;$i++){
				if($showmonth==$i) $tempselect=' selected ';
				else $tempselect='';	
				$option .= '<option value="'.$i.'" '.$tempselect.' >'.$i.'</option>';
			}
			$temp_showmonth= $LDMonth.': <select id="showmonth" name="showmonth" size="1">'.$option.'</select>';
			$smalltitle= $LDMonth.': '.$showmonth;
			break;
}

$temp='<select id="type_med" name="type_med" onchange="selectTypeMed()">
			<option value="0" '.$s0.' >'.$LDBaoCaoMoiThang.' '.$LDKhoaDuoc.'</option>
			<option value="1" '.$s1.' >'.$LDBaoCaoMoiQui.' '.$LDKhoaDuoc.'</option>
			<option value="2" '.$s2.' >'.$LDBaoCao6Thang.' '.$LDKhoaDuoc.'</option>
			<option value="3" '.$s3.' >'.$LDBaoCaoMoiThang.' '.$LDCacKhoaKhac.'</option>
			<option value="4" '.$s4.' >'.$LDBaoCaoMoiQui.' '.$LDCacKhoaKhac.'</option>
		</select>';
$smarty->assign('inputby',$temp);


//showmonth, showyear: thang nguoi dung muon xem
if($showyear=='')
	$showyear=date('Y');
$smarty->assign('monthreport',$temp_showmonth.'/ <input type="text" id="showyear" name="showyear" size="3" value="'.$showyear.'">');
$smarty->assign('LDMonthReport',$smalltitle.'/'.$showyear);

$sTempDiv='';
unset($list_encoder);
switch($type_month){	
	case 0: //1 thang khoa duoc
			$listReport= $Pharma->Khochan_sudungthuocdacbiet_thang($nhomdacbiet, $showmonth, $showyear);
			if(is_object($listReport)){					
				while($rowReport = $listReport->FetchRow()){					
					$list_encoder[$rowReport['product_encoder']]['name'] = $rowReport['product_name'];
					$list_encoder[$rowReport['product_encoder']]['unit'] = $rowReport['unit_name_of_medicine'];
					$list_encoder[$rowReport['product_encoder']]['ton'] += $rowReport['ton'];
					$list_encoder[$rowReport['product_encoder']]['nhap'] += $rowReport['nhap'];
					$list_encoder[$rowReport['product_encoder']]['xuat'] += $rowReport['xuat'];
				}
				$i=1;
				ob_start();
				foreach ($list_encoder as $value) {
					echo '<tr bgColor="#ffffff"><td>'.$i.'</td>';
					echo '<td>'.$value['name'].'</td>';
					echo '<td>'.$value['unit'].'</td>';
					echo '<td align="right">'.number_format($value['ton']).'</td>';
					echo '<td align="right">'.number_format($value['nhap']).'</td>';
					echo '<td align="right">'.number_format($value['ton']+$value['nhap']).'</td>';		//tong cong
					echo '<td align="right">'.number_format($value['xuat']).'</td>';
					echo '<td></td>';		//hu hong
					echo '<td align="right">'.number_format($value['ton']+$value['nhap']-$value['xuat']).'</td>';	
					echo '<td></td></tr>';	//ghi chu
					$i++;
				}
				$sTempDiv = $sTempDiv.ob_get_contents();				
				ob_end_clean();
								
			} else {
				$sTempDiv='<tr bgColor="#ffffff"><td colspan="10">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			break;	
			
	case 1:  //1 qui khoa duoc
			$listReport= $Pharma->Khochan_sudungthuocdacbiet_nhieuthang($nhomdacbiet, $showmonth, ($showmonth+2), $showyear);
			if(is_object($listReport)){
				while($rowReport = $listReport->FetchRow()){					
					$list_encoder[$rowReport['product_encoder']]['name'] = $rowReport['product_name'];
					$list_encoder[$rowReport['product_encoder']]['unit'] = $rowReport['unit_name_of_medicine'];
					$list_encoder[$rowReport['product_encoder']]['ton'] += $rowReport['ton'];
					$list_encoder[$rowReport['product_encoder']]['nhap'] += $rowReport['nhap'];
					$list_encoder[$rowReport['product_encoder']]['xuat'] += $rowReport['xuat'];
					if($rowReport['giaton']>0)
						$list_encoder[$rowReport['product_encoder']]['giaton'] = $rowReport['giaton'];				
					if($rowReport['gianhap']>0)
						$list_encoder[$rowReport['product_encoder']]['gianhap'] = $rowReport['gianhap'];					
					if($rowReport['giaxuat']>0)
						$list_encoder[$rowReport['product_encoder']]['giaxuat'] = $rowReport['giaxuat'];
				}
				$i=1;
				ob_start();
				foreach ($list_encoder as $value) {
					$dongia = max($value['giaton'],$value['gianhap'],$value['giaxuat']);
					if(round($dongia,3)==round($dongia))
						$showdongia = number_format($dongia);
					else $showdongia = number_format($dongia,3);
					$toncuoi = $value['ton']+$value['nhap']-$value['xuat'];
					echo '<tr bgColor="#ffffff"><td>'.$i.'</td>';
					echo '<td>'.$value['name'].'</td>';
					echo '<td>'.$value['unit'].'</td>';
					echo '<td>'.$showdongia.'</td>';
					echo '<td align="right">'.number_format($value['ton']).'</td>';
					echo '<td align="right">'.number_format($value['ton']*$dongia).'</td>';
					echo '<td align="right">'.number_format($value['nhap']).'</td>';
					echo '<td align="right">'.number_format($value['nhap']*$dongia).'</td>';
					echo '<td align="right">'.number_format($value['xuat']).'</td>';
					echo '<td align="right">'.number_format($value['xuat']*$dongia).'</td>';
					echo '<td></td><td></td>';		//hu hong
					echo '<td align="right">'.number_format($toncuoi).'</td>';
					echo '<td align="right">'.number_format($toncuoi*$dongia).'</td>';					
					echo '<td></td></tr>';	//ghi chu
					$i++;
				}
				$sTempDiv = $sTempDiv.ob_get_contents();				
				ob_end_clean();
								
			} else {
				$sTempDiv='<tr bgColor="#ffffff"><td colspan="15">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			break;	
			
	case 2:  //bao cao 6 thang khoa duoc
			$listReport= $Pharma->Khochan_sudungthuocdacbiet_nhieuthang($nhomdacbiet, $showmonth, ($showmonth+5), $showyear);			
			if(is_object($listReport)){
				while($rowReport = $listReport->FetchRow()){					
					$list_encoder[$rowReport['product_encoder']]['name'] = $rowReport['product_name'];
					$list_encoder[$rowReport['product_encoder']]['unit'] = $rowReport['unit_name_of_medicine'];
					$list_encoder[$rowReport['product_encoder']]['ton'] += $rowReport['ton'];
					$list_encoder[$rowReport['product_encoder']]['nhap'] += $rowReport['nhap'];
					$list_encoder[$rowReport['product_encoder']]['xuat'] += $rowReport['xuat'];
					if($rowReport['giaton']>0)
						$list_encoder[$rowReport['product_encoder']]['giaton'] = $rowReport['giaton'];				
					if($rowReport['gianhap']>0)
						$list_encoder[$rowReport['product_encoder']]['gianhap'] = $rowReport['gianhap'];					
					if($rowReport['giaxuat']>0)
						$list_encoder[$rowReport['product_encoder']]['giaxuat'] = $rowReport['giaxuat'];
				}
				$i=1;
				ob_start();
				foreach ($list_encoder as $value) {
					$dongia = max($value['giaton'],$value['gianhap'],$value['giaxuat']);
					if(round($dongia,3)==round($dongia))
						$showdongia = number_format($dongia);
					else $showdongia = number_format($dongia,3);
					$toncuoi = $value['ton']+$value['nhap']-$value['xuat'];
					echo '<tr bgColor="#ffffff"><td>'.$i.'</td>';
					echo '<td>'.$value['name'].'</td>';
					echo '<td>'.$value['unit'].'</td>';
					echo '<td>'.$showdongia.'</td>';
					echo '<td align="right">'.number_format($value['ton']).'</td>';
					echo '<td align="right">'.number_format($value['ton']*$dongia).'</td>';
					echo '<td align="right">'.number_format($value['nhap']).'</td>';
					echo '<td align="right">'.number_format($value['nhap']*$dongia).'</td>';
					echo '<td align="right">'.number_format($value['xuat']).'</td>';
					echo '<td align="right">'.number_format($value['xuat']*$dongia).'</td>';
					echo '<td></td><td></td>';		//hu hong
					echo '<td align="right">'.number_format($toncuoi).'</td>';
					echo '<td align="right">'.number_format($toncuoi*$dongia).'</td>';					
					echo '<td></td></tr>';	//ghi chu
					$i++;
				}
				$sTempDiv = $sTempDiv.ob_get_contents();				
				ob_end_clean();
								
			} else {
				$sTempDiv='<tr bgColor="#ffffff"><td colspan="15">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			break;
		
	case 3:	// bao cao tung thang cac khoa khac
			$sTempDiv='<tr bgColor="#ffffff"><td colspan="10">'.$LDNotReportThisMonth.'</td></tr>';
			break;
			
	case 4:	// bao cao qui cac khoa khac
			$sTempDiv='<tr bgColor="#ffffff"><td colspan="20">'.$LDNotReportThisMonth.'</td></tr>';
			break;			
	
	default: $sTempDiv='<tr bgColor="#ffffff"><td colspan="15">'.$LDNotReportThisMonth.'</td></tr>';	
			break;
}


$smarty->assign('divItem',$sTempDiv);
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="maxid" value="'.$maxid.'">
		<input type="hidden" id="type_month" name="type_month" value="'.$type_month.'">
		<input type="hidden" name="user_report" value="'.$_SESSION['sess_user_name'].'">';

$smarty->assign('sHiddenInputs',$sTempHidden);


//*********************************************************************************


$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
//$smarty->assign('pbSave','<a href="javascript:Save(\''.$alertsave.'\');"><img '.createLDImgSrc($root_path,'savedisc.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbPrint','<a href="#"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle" onclick="printOut(\''.$type_month.'\',\''.$showmonth.'\',\''.$showyear.'\')"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile',$template_report);

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

