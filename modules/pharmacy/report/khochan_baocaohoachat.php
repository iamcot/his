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
$fileforward='khochan_baocaohoachat_save.php'.URL_APPEND;
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
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDReportImportExport_Medipot')");

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
	document.reportform.action=<?php echo $thisfile; ?>;
	document.reportform.submit();
}
function printOut(select_type,month,year,lastmonth,lastyear)
{
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_hoachat_nhapxuatton.php<?php echo URL_APPEND; ?>&select_type="+select_type+"&typedongtay=<?php echo $type;?>"+"&month="+month+"&year="+year+"&lastmonth="+lastmonth+"&lastyear="+lastyear;
	testprintpdf=window.open(urlholder,"NhapXuatTon","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
}
/*function Save(alertsave){
	if(alertsave=='ok'){
		var month=document.getElementById('showmonth').value;
		var year=document.getElementById('showyear').value;
		document.reportform.action="<? echo $fileforward; ?>&target=save&month="+month+"&year="+year;
		document.reportform.submit();
	} 
	else alert(alertsave);
}*/
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
$smarty->assign('LDReportMedicine',$LDReportChemical);
$smarty->assign('titleForm',$LDReportImportExport_Medicine);

$smarty->assign('LDFromDate',$LDFrom);
$smarty->assign('LDToDate',$LDTo);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineName',$LDChemicalName);
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
			<option value="0" '.$s0.' >'.$LDChemical.'</option>
			<option value="1" '.$s1.' >'.$LDChemical_KP.'</option>
			<option value="2" '.$s2.' >'.$LDChemical_BH.'</option>
			<option value="3" '.$s3.' >'.$LDChemical_CBTC.'</option>
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


$smarty->assign('monthreport',$LDMonth.': <input type="text" id="showmonth" name="showmonth" size="1" value="'.$showmonth.'">/ <input type="text" id="showyear" name="showyear" size="3" value="'.$showyear.'">');
$smarty->assign('LDMonthReport',$LDMonth.': '.$showmonth.'/'.$showyear);	
	
	
$Tong_tondau =0; $Tong_nhap=0; $Tong_xuat=0; $Tong_toncuoi=0;
	
$listReport = $Pharma->Khochan_hoachat_nhapxuatton($cond_typeput, $showmonth, $showyear);

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
				else $lotid	= $value['loton'];
				$sTempDiv .=  	'<td align="center">'.$lotid.'</td>';  
				
				//Han dung
				if($value['hannhap']!='') $expdate = $value['hannhap']; 
				else $expdate	= $value['hanton'];				
				$sTempDiv .=  	'<td align="center">'.@formatDate2Local($expdate,'dd/mm/yyyy').'</td>';  
				
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
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi).'</td>';	//Ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($giatoncuoi).'</td>';	//Gia ton cuoi
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
			$list_encoder[$i]['hanton'] = $rowReport['hanton'];
			$list_encoder[$i]['hannhap'] = $rowReport['hannhap'];			
			$list_encoder[$i]['ton'] = $rowReport['ton'];
			$list_encoder[$i]['giaton'] = $rowReport['giaton'];
			$list_encoder[$i]['nhap'] = $rowReport['nhap'];
			$list_encoder[$i]['gianhap'] = $rowReport['gianhap'];
			$list_encoder[$i]['xuat'] = $rowReport['xuat'];
			$list_encoder[$i]['giaxuat'] = $rowReport['giaxuat'];
			
			$oldencode=$rowReport['product_encoder'];
			
		}else{		//thuoc cu
			if(($rowReport['ton']>0 && $list_encoder[$i]['ton']>0) || ($rowReport['nhap']>0 && $list_encoder[$i]['ton']>0) || ($rowReport['nhap']>0 && $list_encoder[$i]['nhap']>0) || (abs($rowReport['gianhap']-$list_encoder[$i]['giaxuat'])>1) || (abs($rowReport['xuat']- $list_encoder[$i]['xuat'])>1) || (abs($rowReport['giaxuat']-$list_encoder[$i]['gianhap'])>1)){
				$i++;	//them dong moi
				$list_encoder[$i]['encoder'] = $rowReport['product_encoder'];
				$list_encoder[$i]['name'] = $rowReport['product_name'];
				$list_encoder[$i]['unit'] = $rowReport['unit_name_of_medicine'];
				$list_encoder[$i]['loton'] = $rowReport['loton'];
				$list_encoder[$i]['lonhap'] = $rowReport['lonhap'];	
				$list_encoder[$i]['hanton'] = $rowReport['hanton'];
				$list_encoder[$i]['hannhap'] = $rowReport['hannhap'];
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
				else $lotid	= $value['loton'];
				$sTempDiv .=  	'<td align="center">'.$lotid.'</td>';  
				
				//Han dung
				if($value['hannhap']!='') $expdate = $value['hannhap']; 
				else $expdate	= $value['hanton'];				
				$sTempDiv .=  	'<td align="center">'.@formatDate2Local($expdate,'dd/mm/yyyy').'</td>';  
				
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
				$sTempDiv .= 	'<td align="right">'.number_format($toncuoi).'</td>';	//Ton cuoi
				$sTempDiv .= 	'<td align="right">'.number_format($giatoncuoi).'</td>';	//Gia ton cuoi
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
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="maxid" value="'.$stt.'">
		<input type="hidden" id="select_type" name="select_type" value="'.$select_type.'">
		<input type="hidden" id="type" name="type" value="'.$type.'">
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
//$smarty->assign('pbSave','<a href="javascript:Save(\''.$alertsave.'\');"><img '.createLDImgSrc($root_path,'savedisc.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbPrint','<a href="#"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle" onclick="printOut(\''.$select_type.'\',\''.$showmonth.'\',\''.$showyear.'\',\''.$lastmonth.'\',\''.$lastyear.'\')"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/khochan_report.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

