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

//Thuoc dieu tri ky sinh trung, chong nhiem khuan: 6
//$pharma_generic_drug_id
$pharma_group_id='6';

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDSuDungThuocKhangSinh);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDSuDungThuocKhangSinh')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDSuDungThuocKhangSinh);

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
function printOut(type_month,month,year,flag,lastmonth,lastyear)
{
	//urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_thuoc_nhapxuatton.php<?php echo URL_APPEND; ?>&type_month="+type_month+"&typedongtay=<?php echo $type;?>"+"&month="+month+"&year="+year+"&lastmonth="+lastmonth+"&lastyear="+lastyear+"&flag="+flag;
	//testprintpdf=window.open(urlholder,"NhapXuatTon","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	window.print();
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
$smarty->assign('titleForm',$LDSuDungThuocKhangSinh);
$smarty->assign('LDTTHoatChat',$LDTTHoatChat);
$smarty->assign('LDTenHoatChat',$LDTenHoatChat);
$smarty->assign('LDMaATC',$LDMaATC);
$smarty->assign('LDTTBietDuoc',$LDTTBietDuoc);
$smarty->assign('LDTenBietDuoc',$LDTenBietDuoc);
$smarty->assign('LDNuocsx',$LDNuocSx1);
$smarty->assign('LDNongDoHamLuong',$LDNongDoHamLuong);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDDuongDung',$LDDuongDung);
$smarty->assign('LDSoLuong',$LDNumberOf);
$smarty->assign('LDDonGia',$LDCost);
$smarty->assign('LDThanhTien',$LDTotalCost);

//Report in
if(!isset($type_month) || $type_month=='')
	$type_month=0;
$s0=''; $s1=''; $s2=''; 

if(!isset($showmonth) || $showmonth=='')
	$showmonth=1;
	
switch($type_month){
	case 0: $s0='selected'; 	//1 thang khoa duoc			
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
			$option='';
			for($i=1;$i<=12;$i=$i+6){
				if($showmonth==$i) $tempselect=' selected ';
				else $tempselect='';	
				$option .= '<option value="'.$i.'" '.$tempselect.' >'.($i).' -> '.($i+5).'</option>';
			}
			$temp_showmonth= $LDMonth.': <select id="showmonth" name="showmonth" size="1">'.$option.'</select>';
			
			$smalltitle= $LDMonth.': '.$showmonth.' '.$LDDen.' '.($showmonth+5);
			break;		

						
			
	default: $s0='selected';	//1 thang khoa duoc
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
		</select>';
$smarty->assign('inputby',$temp);


//showmonth, showyear: thang nguoi dung muon xem
if($showyear=='')
	$showyear=date('Y');
$smarty->assign('monthreport',$temp_showmonth.'/ <input type="text" id="showyear" name="showyear" size="3" value="'.$showyear.'">');
$smarty->assign('LDMonthReport',$smalltitle.'/'.$showyear);

$sTempDiv='';
$oldencoder=''; $olddrugid=''; $flag = true;
switch($type_month){	
	case 0: //1 thang khoa duoc
			$listReport= $Pharma->Khochan_sudungthuockhangsinh_thang('tayy', $pharma_group_id, '', $showmonth, $showyear);
			ob_start();
			if(is_object($listReport)){				
				$i=1; $j=1;
				while($rowReport = $listReport->FetchRow()){				
					if($rowReport['xuat']>0){
						if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
							$showgiaxuat = number_format($rowReport['giaxuat']);
						else $showgiaxuat = number_format($rowReport['giaxuat'],3);
						
						if($olddrugid!=$rowReport['pharma_generic_drug_id']){							
							$generic_stt = $i; $generic_atc = $rowReport['ATC'];
							$generic_name = $rowReport['generic_drug'];
							$flag=!$flag; 
							$olddrugid=$rowReport['pharma_generic_drug_id'];
							$i++; $j=0;
						}else{
							$generic_stt = ''; $generic_atc =''; $generic_name = '';					
						}
						
						if($oldencoder!=$rowReport['product_encoder']){
							
							$oldencoder=$rowReport['product_encoder'];							
							$j++;
							$medicine_stt = $i.'.'.$j;
						}else $medicine_stt ='';
						
						if($flag) $bgc="#EFEFEF";
						else $bgc="#FFFFFF";
						
						echo '<tr bgColor="'.$bgc.'"><td align="center">'.$generic_stt.'</td>';
						echo '<td>'.$generic_name.'</td>';
						echo '<td>'.$generic_atc.'</td>';
						echo '<td align="center">'.$medicine_stt.'</td>';
						echo '<td>'.$rowReport['product_name'].'</td>';
						echo '<td>'.$rowReport['nuocsx'].'</td>';
						echo '<td>'.$rowReport['content'].'</td>';
						echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
						echo '<td>'.$rowReport['using_type'].'</td>';
						echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
						echo '<td align="right">'.$showgiaxuat.'</td>';	
						echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';				
					}
				}
								
			} else {
				echo '<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			$sTempDiv = $sTempDiv.ob_get_contents();				
			ob_end_clean();
			break;	
			
	case 1:  //1 qui khoa duoc
			$listReport= $Pharma->Khochan_sudungthuockhangsinh_nhieuthang('tayy', $pharma_group_id, '', $showmonth, ($showmonth+2), $showyear);
			ob_start();
			if(is_object($listReport)){				
				$i=1; $j=1;
				while($rowReport = $listReport->FetchRow()){				
					if($rowReport['xuat']>0){
						if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
							$showgiaxuat = number_format($rowReport['giaxuat']);
						else $showgiaxuat = number_format($rowReport['giaxuat'],3);
						
						if($olddrugid!=$rowReport['pharma_generic_drug_id']){							
							$generic_stt = $i; $generic_atc = $rowReport['ATC'];
							$generic_name = $rowReport['generic_drug'];
							
							$olddrugid=$rowReport['pharma_generic_drug_id'];
							$i++; $j=0;
						}else{
							$generic_stt = ''; $generic_atc =''; $generic_name = '';					
						}
						
						if($oldencoder!=$rowReport['product_encoder']){
							$flag=!$flag; 
							$oldencoder=$rowReport['product_encoder'];
							$j++;
						}
						if($flag) $bgc="#EFEFEF";
						else $bgc="#FFFFFF";
						
						echo '<tr bgColor="'.$bgc.'"><td align="center">'.$generic_stt.'</td>';
						echo '<td>'.$generic_name.'</td>';
						echo '<td>'.$generic_atc.'</td>';
						echo '<td align="center">'.$i.'.'.$j.'</td>';
						echo '<td>'.$rowReport['product_name'].'</td>';
						echo '<td>'.$rowReport['nuocsx'].'</td>';
						echo '<td>'.$rowReport['content'].'</td>';
						echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
						echo '<td>'.$rowReport['using_type'].'</td>';
						echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
						echo '<td align="right">'.$showgiaxuat.'</td>';	
						echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';					
					}
				}									
			} else {
				echo '<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			$sTempDiv = $sTempDiv.ob_get_contents();				
			ob_end_clean();
			break;	
			
	case 2:  //bao cao 6 thang khoa duoc
			$listReport= $Pharma->Khochan_sudungthuockhangsinh_nhieuthang('tayy', $pharma_group_id, '', $showmonth, ($showmonth+5), $showyear);			
			ob_start();
			if(is_object($listReport)){				
								$i=1; $j=1;
				while($rowReport = $listReport->FetchRow()){				
					if($rowReport['xuat']>0){
						if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
							$showgiaxuat = number_format($rowReport['giaxuat']);
						else $showgiaxuat = number_format($rowReport['giaxuat'],3);
						
						if($olddrugid!=$rowReport['pharma_generic_drug_id']){							
							$generic_stt = $i; $generic_atc = $rowReport['ATC'];
							$generic_name = $rowReport['generic_drug'];
							
							$olddrugid=$rowReport['pharma_generic_drug_id'];
							$i++; $j=0;
						}else{
							$generic_stt = ''; $generic_atc =''; $generic_name = '';					
						}
						
						if($oldencoder!=$rowReport['product_encoder']){
							$flag=!$flag; 
							$oldencoder=$rowReport['product_encoder'];
							$j++;
						}
						if($flag) $bgc="#EFEFEF";
						else $bgc="#FFFFFF";
						
						echo '<tr bgColor="'.$bgc.'"><td align="center">'.$generic_stt.'</td>';
						echo '<td>'.$generic_name.'</td>';
						echo '<td>'.$generic_atc.'</td>';
						echo '<td align="center">'.$i.'.'.$j.'</td>';
						echo '<td>'.$rowReport['product_name'].'</td>';
						echo '<td>'.$rowReport['nuocsx'].'</td>';
						echo '<td>'.$rowReport['content'].'</td>';
						echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
						echo '<td>'.$rowReport['using_type'].'</td>';
						echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
						echo '<td align="right">'.$showgiaxuat.'</td>';	
						echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';					
					}
				}					
			} else {
				echo '<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			$sTempDiv = $sTempDiv.ob_get_contents();				
			ob_end_clean();
			break;		
	
	default: $sTempDiv='<tr bgColor="#ffffff"><td colspan="12">'.$LDNotReportThisMonth.'</td></tr>';	
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
$smarty->assign('pbPrint','<a href="#"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle" onclick="printOut(\''.$type_month.'\',\''.$showmonth.'\',\''.$showyear.'\',\''.$flag.'\',\''.$lastmonth.'\',\''.$lastyear.'\')"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/khochan_sudungthuoc_khangsinh.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

