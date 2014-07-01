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
$fileforward='khochan_baocaothuoc_save.php'.URL_APPEND;
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
 $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDReportImportExport_Medicine);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDReportImportExport_Medicine')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDReportImportExport_Medicine);

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
/*
urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/thongke15ngaydung.php<?php echo URL_APPEND; ?>&type=medicine&dept_nr="+dept+"&ward_nr="+ward+"&fromdate="+fromdate+"&todate="+todate;
testprintpdf=window.open(urlholder,"ThongKe15NgayDung","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");

function printOut(select_type,month,year,lastmonth,lastyear)
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_thuoc_nhapxuatton.php<?php echo URL_APPEND; ?>&typeput="+select_type+"&typedongtay=<?php echo $type;?>"+"&month="+month+"&year="+year+"&lastmonth="+lastmonth+"&lastyear="+lastyear;
	testprintpdf=window.open(urlholder,"NhapXuatTon","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}  */
function printOut(select_type,fromdate,todate)
{
    urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_thuoc_nhapxuatton.php<?php echo URL_APPEND; ?>&typeput="+select_type+"&typedongtay=<?php echo $type;?>"+"&fromdate="+fromdate+"&todate="+todate;
    testprintpdf=window.open(urlholder,"NhapXuatTon","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}
function Save(alertsave){
	if(alertsave=='ok'){		
		var month=document.getElementById('showmonth').value;
		var year=document.getElementById('showyear').value;
		if(confirm("<? echo $LDChotTonCuoi; ?> "+month+"/"+year+" ?")){
			document.reportform.action="<? echo $fileforward; ?>&target=save&month="+month+"&year="+year+"&typeput=<? echo $select_type;?>&typedongtay=<?php echo $type;?>";
			document.reportform.submit();
			//alert(alertsave);
		}
	} 
	else if(alertsave=='update'){
		if (confirm("<? echo $LDDaLuuBaoCao.'\n'.$LDCapNhatBaoCao; ?>")) {
			// Save it!
			var month=document.getElementById('showmonth').value;
			var year=document.getElementById('showyear').value;
			var tonid=document.getElementById('lastton_id').value;
			
			document.reportform.action="<? echo $fileforward; ?>&target=update&month="+month+"&year="+year+"&ton_id="+tonid+"&typeput=<? echo $select_type;?>&typedongtay=<?php echo $type;?>";
			document.reportform.submit();
			//alert(alertsave);
		}			
	}
	else alert(alertsave);
}
function selectTypeMed() {
	var temp_i = document.getElementById("type_med").selectedIndex;
	document.getElementById("select_type").value = document.getElementById("type_med").options[temp_i].value;
	document.reportform.action='<?php echo $thisfile.URL_APPEND.'&type='.$type; ?>';
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
$smarty->assign('LDNumberOf',$LDNumberOf);
$smarty->assign('LDExpDate',$LDExpDate1);
$smarty->assign('LDGiaNhap',$LDGiaNhap);
$smarty->assign('LDGiaXuat',$LDGiaXuat);
$smarty->assign('LDGiaTonCuoi',$LDGiaTonCuoi);
//==>    nang
//Test format fromday
if (isset($fromdate) && $fromdate!='' && strpos($fromdate,'-')<3) {
    list($f_day,$f_month,$f_year) = explode("-",$fromdate);
    $fromdate=$f_year.'-'.$f_month.'-'.$f_day;
}
else
    list($f_year,$f_month,$f_day) = explode("-",$fromdate);
//Test format today
if (isset($todate) && $todate!='' && strpos($todate,'-')<3) {
    list($t_day,$t_month,$t_year) = explode("-",$todate);
    $todate=$t_year.'-'.$t_month.'-'.$t_day;
}
else
    list($t_year,$t_month,$t_day) = explode("-",$todate);

//$smarty->assign('monthreport',$LDMonth.': '.$f_month);
if($f_month == $t_month){
    $smarty->assign('LDMonthReport',$LDMonth.': '.$t_month.'/'.$t_year);
}
else{
    $smarty->assign('LDMonthReport',$LDMonth.': '.$f_month.' '.'Đến'.' '.$t_month.'/'.$t_year);
}
//$smarty->assign('LDMonthReport',$LDMonth.': '.$t_month.'/'.$t_year);

//Calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
$calendar->load_files();
$date_format='dd-mm-yyyy';
$smarty->assign('calendarfrom',$calendar->show_calendar($calendar,$date_format,'fromdate',$fromdate));
$smarty->assign('calendarto',$calendar->show_calendar($calendar,$date_format,'todate',$todate));
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
		case 1: $cond_typeput = ' AND source.typeput=1 '; break;		//su nghiep
		case 2: $cond_typeput = ' AND source.typeput=0 '; break;		//bhyt
		case 3: $cond_typeput = ' AND source.typeput=2 '; break;		//cbtc
		default: $cond_typeput = ' ';
	}
	
	switch($type){
		case 'tayy': $dongtayy =' AND main.pharma_type IN (1,2,3)'; break;	
		case 'dongy': $dongtayy = ' AND main.pharma_type IN (4,8,9,10) '; break;
		default: $dongtayy = ''; break;
	}

	

//$smarty->assign('monthreport',$LDMonth.': <input type="text" id="showmonth" name="showmonth" size="1" value="'.$showmonth.'">/ <input type="text" id="showyear" name="showyear" size="3" value="'.$showyear.'">');
//$smarty->assign('LDMonthReport',$LDMonth.': '.$showmonth.'/'.$showyear);
	
	
$Tong_tondau =0; $Tong_nhap=0; $Tong_xuat=0; $Tong_toncuoi=0;

//echo $type.'@'.@$cond_typeput."@".$showmonth.'@'.$showyear;
//$listReport = $Pharma->Khochan_thuoc_nhapxuatton($type, $cond_typeput, $showmonth, $showyear);
$listReport = $Pharma->Khochan_thuoc_nhapxuatton($type, $cond_typeput, $fromdate,$todate);
//var_dump($listReport);
if(is_object($listReport)){
	//$maxid=$listReport->RecordCount();
	$sTempDiv=''; $stt=1;
	//ob_start();	
	while($rowReport = $listReport->FetchRow())	{
		if($oldencode!=$rowReport['product_encoder']){ 			//thuoc moi
			//echo current $list_encoder
			foreach ($list_encoder as $value) {
				$sTempDiv .=  '<tr bgColor="#ffffff" >';
				$sTempDiv .= 	'<td align="center">'. $stt.'<input type="hidden" name="encoder'.$stt.'" value="'.$value['encoder'].'"></td>'; //STT
				$sTempDiv .= 	'<td>'.$value['name'].'</td>';	//Ten thuoc
				$sTempDiv .=  	'<td align="center">'.$value['unit'].'</td>';  //Don vi
				
				//So lo
				if($value['lonhap']!='') $lotid = $value['lonhap'];
				else if ($value['loton']!='') $lotid = $value['loton'];
				else $lotid = $value['loxuat'];
				$sTempDiv .=  	'<td align="center">'.$lotid.'<input type="hidden" name="lotid'.$stt.'" value="'.$lotid.'"></td>';  
				
				//Han dung
				if($value['hannhap']!='') 
					$expdate = $value['hannhap']; 
				else if($value['hanton']!='')
					$expdate = $value['hanton'];
				else 
					$expdate = $value['hanxuat'];				
				$sTempDiv .=  	'<td align="center">'.@formatDate2Local($expdate,'dd/mm/yyyy').'<input type="hidden" name="exp'.$stt.'" value="'.$expdate.'"></td>';  
				
				//Ton dau
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']).'</td>';		
				if (round($value['giaton'],3)>round($value['giaton'])) $showton = number_format($value['giaton'],3);
				else $showton = number_format($value['giaton']);
				$sTempDiv .= 	'<td align="right">'.$showton.'</td>';	
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']*$value['giaton']).'</td>';	//TT
				
				//Nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']).'</td>';	
				if (round($value['gianhap'],3)>round($value['gianhap'])) $shownhap = number_format($value['gianhap'],3);
				else $shownhap = number_format($value['gianhap']);
				$sTempDiv .= 	'<td align="right">'.$shownhap.'</td>';	//Gia nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']*$value['gianhap']).'</td>';	//TT nhap
				
				//Xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']).'</td>';	
				if (round($value['giaxuat'],3)>round($value['giaxuat'])) $showxuat = number_format($value['giaxuat'],3);
				else $showxuat = number_format($value['giaxuat']);				
				$sTempDiv .= 	'<td align="right">'.$showxuat.'</td>';	//Gia xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']*$value['giaxuat']).'</td>';	//TT xuat
				
				$toncuoi = $value['ton'] + $value['nhap'] - $value['xuat'];
				if($value['giaton']>0 || $value['gianhap']>0)
					$giatoncuoi = max($value['giaton'],$value['gianhap']);
				else $giatoncuoi = $value['giaxuat'];	
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi).'<input type="hidden" name="toncuoi'.$stt.'" value="'.$toncuoi.'"></td>';	//Ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($giatoncuoi).'<input type="hidden" name="gia'.$stt.'" value="'.$giatoncuoi.'"></td>';	//Gia ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi*$giatoncuoi).'</td>';	//TT
				$sTempDiv .= 	'<td></td>';	//Note
				$sTempDiv .=  '</tr>';
				$Tong_tondau += $value['ton']*$value['giaton'];
				$Tong_nhap += $value['nhap']*$value['gianhap'];
				$Tong_xuat += $value['xuat']*$value['giaxuat'];
				$Tong_toncuoi += $toncuoi*$giatoncuoi;
				//$value['ton']*$value['giaton'] + $value['nhap']*$value['gianhap'] - $value['xuat']*$value['giaxuat'];
				$stt++;
				//$sTempDiv .=  $stt;
			}
			//reset new encoder
			unset($list_encoder); $i=1;	
			$list_encoder[$i]['encoder'] = $rowReport['product_encoder'];
			$list_encoder[$i]['name'] = $rowReport['product_name'];
			$list_encoder[$i]['unit'] = $rowReport['unit_name_of_medicine'];
			$list_encoder[$i]['loton'] = $rowReport['loton'];
			$list_encoder[$i]['lonhap'] = $rowReport['lonhap'];	
			$list_encoder[$i]['loxuat'] = $rowReport['loxuat'];	
			$list_encoder[$i]['hanton'] = $rowReport['hanton'];
			$list_encoder[$i]['hannhap'] = $rowReport['hannhap'];
			$list_encoder[$i]['hanxuat'] = $rowReport['hanxuat'];			
			$list_encoder[$i]['ton'] = $rowReport['ton'];
			$list_encoder[$i]['giaton'] = $rowReport['giaton'];
			$list_encoder[$i]['nhap'] = $rowReport['nhap'];
			$list_encoder[$i]['gianhap'] = $rowReport['gianhap'];
			$list_encoder[$i]['xuat'] = $rowReport['xuat'];
			$list_encoder[$i]['giaxuat'] = $rowReport['giaxuat'];
			
			$oldencode=$rowReport['product_encoder'];
			
		}else{		//thuoc cu
			if(($rowReport['ton']!=0) || ($rowReport['nhap']>0 && $list_encoder[$i]['ton']>0) || ($rowReport['nhap']>0 && $list_encoder[$i]['nhap']>0) || (abs($rowReport['gianhap']-$list_encoder[$i]['giaxuat'])>1) || (abs($rowReport['xuat']- $list_encoder[$i]['xuat'])>1) || (abs($rowReport['giaxuat']-$list_encoder[$i]['gianhap'])>1)){
				$i++;	//them dong moi
				$list_encoder[$i]['encoder'] = $rowReport['product_encoder'];
				$list_encoder[$i]['name'] = $rowReport['product_name'];
				$list_encoder[$i]['unit'] = $rowReport['unit_name_of_medicine'];
				$list_encoder[$i]['loton'] = $rowReport['loton'];
				$list_encoder[$i]['lonhap'] = $rowReport['lonhap'];
				$list_encoder[$i]['loxuat'] = $rowReport['loxuat'];				
				$list_encoder[$i]['hanton'] = $rowReport['hanton'];
				$list_encoder[$i]['hannhap'] = $rowReport['hannhap'];
				$list_encoder[$i]['hanxuat'] = $rowReport['hanxuat'];
				$list_encoder[$i]['ton'] = $rowReport['ton'];
				$list_encoder[$i]['giaton'] = $rowReport['giaton'];
				$list_encoder[$i]['nhap'] = $rowReport['nhap'];
				$list_encoder[$i]['gianhap'] = $rowReport['gianhap'];
				$list_encoder[$i]['xuat'] = $rowReport['xuat'];	
				$list_encoder[$i]['giaxuat'] = $rowReport['giaxuat'];	
			} else {	//cong don vao dong cu
				if($rowReport['nhap']>0){
					for ($j=1;$j<=$i;$j++){
						if ($list_encoder[$j]['nhap']<=0 && $list_encoder[$j]['ton']<=0){
							$list_encoder[$j]['nhap'] = $rowReport['nhap'];
							$list_encoder[$j]['gianhap'] = $rowReport['gianhap'];
							break;
						}
					}
				}
				if($rowReport['xuat']>0){
					for ($j=1;$j<=$i;$j++){
						if ($list_encoder[$j]['xuat']<=0){
							$list_encoder[$j]['xuat'] += $rowReport['xuat'];
							$list_encoder[$j]['giaxuat'] = $rowReport['giaxuat'];
							break;
						}
					}
				}				
			}
		}

	}	
			//$sTempDiv .=  last $list_encoder
			foreach ($list_encoder as $value) {
				$sTempDiv .=  '<tr bgColor="#ffffff" >';
				$sTempDiv .= 	'<td align="center">'. $stt.'<input type="hidden" name="encoder'.$stt.'" value="'.$value['encoder'].'"></td>'; //STT
				$sTempDiv .= 	'<td>'.$value['name'].'</td>';	//Ten thuoc
				$sTempDiv .=  	'<td align="center">'.$value['unit'].'</td>';  //Don vi
				
				//So lo
				if($value['lonhap']!='') $lotid = $value['lonhap'];
				else if($value['loton']!='') $lotid = $value['loton'];
				else $lotid = $value['loxuat'];
				$sTempDiv .=  	'<td align="center">'.$lotid.'<input type="hidden" name="lotid'.$stt.'" value="'.$lotid.'"></td>';  
				
				//Han dung
				if($value['hannhap']!='') 
					$expdate = $value['hannhap']; 
				else if ($value['hanton']!='') 
					$expdate = $value['hanton'];
				else 
					$expdate = $value['hanxuat'];
				$sTempDiv .=  	'<td align="center">'.@formatDate2Local($expdate,'dd/mm/yyyy').'<input type="hidden" name="exp'.$stt.'" value="'.$expdate.'"></td>';  
				
				//Ton dau
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']).'</td>';		
				if (round($value['giaton'],3)>round($value['giaton'])) $showton = number_format($value['giaton'],3);
				else $showton = number_format($value['giaton']);
				$sTempDiv .= 	'<td align="right">'.$showton.'</td>';	
				$sTempDiv .= 	'<td align="right">'.number_format($value['ton']*$value['giaton']).'</td>';	//TT
				
				//Nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']).'</td>';	
				if (round($value['gianhap'],3)>round($value['gianhap'])) $shownhap = number_format($value['gianhap'],3);
				else $shownhap = number_format($value['gianhap']);
				$sTempDiv .= 	'<td align="right">'.$shownhap.'</td>';	//Gia nhap
				$sTempDiv .= 	'<td align="right">'.number_format($value['nhap']*$value['gianhap']).'</td>';	//TT nhap
				
				//Xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']).'</td>';	
				if (round($value['giaxuat'],3)>round($value['giaxuat'])) $showxuat = number_format($value['giaxuat'],3);
				else $showxuat = number_format($value['giaxuat']);				
				$sTempDiv .= 	'<td align="right">'.$showxuat.'</td>';	//Gia xuat
				$sTempDiv .= 	'<td align="right">'.number_format($value['xuat']*$value['giaxuat']).'</td>';	//TT xuat
				
				$toncuoi = $value['ton'] + $value['nhap'] - $value['xuat'];
				$giatoncuoi = max($value['giaton'],$value['gianhap'],$value['giaxuat']);
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi).'<input type="hidden" name="toncuoi'.$stt.'" value="'.$toncuoi.'"></td>';	//Ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($giatoncuoi).'<input type="hidden" name="gia'.$stt.'" value="'.$giatoncuoi.'"></td>';	//Gia ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi*$giatoncuoi).'</td>';	//TT
				$sTempDiv .= 	'<td></td>';	//Note
				$sTempDiv .=  '</tr>';
				$Tong_tondau += $value['ton']*$value['giaton'];
				$Tong_nhap += $value['nhap']*$value['gianhap'];
				$Tong_xuat += $value['xuat']*$value['giaxuat'];
				$Tong_toncuoi += $toncuoi*$giatoncuoi;
				$stt++;
			}
	//echo $sTempDiv;
	//$sTempDiv = $sTempDiv.ob_get_contents();				
	//ob_end_clean();
					
} else {
	$stt=0;
	if (!isset($sTempDiv) || $sTempDiv=='')
		$sTempDiv='<tr bgColor="#ffffff"><td colspan="18">'.$LDNotReportThisMonth.'</td></tr>';	
}

$sTempDiv = $sTempDiv.'<tr bgColor="#ffffff">
							<td colspan="5" align="center"><b>'.$LDTotalNumber.'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_tondau).'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_nhap).'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_xuat).'</b></td>
							<td colspan="3" align="right"><b>'.number_format($Tong_toncuoi).'</b></td>
							<td></td>
						</tr>';

$smarty->assign('divItem',$sTempDiv);

//********************************************************************************* 

$monthyear="WHERE monthreport='".$showmonth."' AND yearreport='".$showyear."' ";

if($anyreport=$Pharma->checkAnyReport_TonKhoChan($monthyear)){
	$lastton_id=$anyreport['id'];
	$lastton_month=$anyreport['monthreport'];
	$lastton_year=$anyreport['yearreport'];
}

//echo $showmonth.' '.$showyear.' '.$lastton_year.' '.$lastton_month.' '.$lastton_id;

if(($showyear>$lastton_year) || ($showmonth>$lastton_month && $showyear==$lastton_year)){
	$alertsave='ok';
}
else{
	$alertsave='update';
}



 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="maxid" value="'.$stt.'">
		<input type="hidden" id="showmonth" value="'.$showmonth.'">
		<input type="hidden" id="showyear" value="'.$showyear.'">
		<input type="hidden" id="lastton_id" name="lastton_id" value="'.$lastton_id.'">
		<input type="hidden" id="select_type" name="select_type" value="'.$select_type.'">
		<input type="hidden" id="type" name="type" value="'.$type.'">
		<input type="hidden" name="user_report" value="'.$_SESSION['sess_user_name'].'">';

$smarty->assign('sHiddenInputs',$sTempHidden);


$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
$smarty->assign('pbSave','<a href="#"><img '.createLDImgSrc($root_path,'savedisc.gif','0','middle').' align="middle" onclick="Save(\''.$alertsave.'\')" > </a>');
//$smarty->assign('pbPrint','<a href="#"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle" onclick="printOut(\''.$select_type.'\',\''.$showmonth.'\',\''.$showyear.'\',\''.$lastmonth.'\',\''.$lastyear.'\')"></a>');
$smarty->assign('pbPrint','<a href="javascript:window.printOut(\''.$select_type.'\',\''.$fromdate.'\',\''.$todate.'\');"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle"></a>');

$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
//$smarty->assign('sMainBlockIncludeFile','pharmacy/khochan_report.tpl');
$smarty->assign('sMainBlockIncludeFile','pharmacy/khochan_report_thuoc.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

