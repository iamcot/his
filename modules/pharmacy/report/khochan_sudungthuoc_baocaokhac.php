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
$breakfile='khochan_sudungthuoc_nhomthuoc.php'.URL_APPEND;
//$fileforward='khochan_baocaothuoc_save.php'.URL_APPEND;
$date_format='dd-mm-yyyy';

//$pharma_generic_drug_id
$pharma_group_id=$group_cb;
if(!isset($subtitle)){
	$name = 'groupname'.$group_cb;
	$subtitle=$$name;
}

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDCacBaoCaoKhac);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDSuDungThuocNhomThuoc')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDSuDungThuocNhomThuoc);

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
function printOut(type_month,month,year,pharma_group_id)
{
	var title = document.getElementById("subtitle").value;
    urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_sudungthuoc_baocaokhac.php<?php echo URL_APPEND; ?>&type_month="+type_month+"&typedongtay=<?php echo $type;?>"+"&showmonth="+month+"&showyear="+year+"&pharma_group_id="+pharma_group_id+"&subtitle="+title;
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
$smarty->assign('subtitle',$subtitle);
$smarty->assign('LDSelect',$LDSelect);
$smarty->assign('titleForm',$LDCacBaoCaoKhac);
$smarty->assign('LDSTT',$LDSTT);
$smarty->assign('LDMedicineName',$LDMedicineName);
$smarty->assign('LDUnit',$LDUnit);
$smarty->assign('LDNote',$LDNote);
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
$oldencoder=''; $flag = true;
//echo $pharma_group_id;
switch($type_month){	
	case 0: //1 thang khoa duoc
			if ($pharma_group_id>0){
				$listReport= $Pharma->Khochan_sudungthuockhac_thang('tayy', $pharma_group_id, '', $showmonth, $showyear);
			}else{
				$listReport= $Pharma->Khochan_sudungthuockhac_thang('tayy', $pharma_group_id, 'noi', $showmonth, $showyear);
				$listReport_ngoai= $Pharma->Khochan_sudungthuockhac_thang('tayy', $pharma_group_id, 'ngoai', $showmonth, $showyear);
			}
			ob_start();
			//Thuoc noi hay nhom thuoc
			if($pharma_group_id==0){
				echo '<tr bgColor="#ffffff"><td colspan="7" align="center"><b>'.$LDThuocNoi.'</b></td></tr>';
			}
			if(is_object($listReport)){				
				$i=1;
				while($rowReport = $listReport->FetchRow()){				
					if($rowReport['xuat']>0){
						if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
							$showgiaxuat = number_format($rowReport['giaxuat']);
						else $showgiaxuat = number_format($rowReport['giaxuat'],3);
						if($oldencoder!=$rowReport['product_encoder']){
							$flag=!$flag; 
							$oldencoder=$rowReport['product_encoder'];
						}
						if($flag) $bgc="#EFEFEF";
						else $bgc="#FFFFFF";
						
						echo '<tr bgColor="'.$bgc.'"><td align="center">'.$i.'</td>';
						echo '<td>'.$rowReport['product_name'].'</td>';
						echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
						echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
						echo '<td align="right">'.$showgiaxuat.'</td>';	
						echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';
						echo '<td></td></tr>';	//ghi chu
						$i++;					
					}
				}
								
			} else {
				echo '<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			//Thuoc Ngoai
			if($pharma_group_id==0){
				echo '<tr bgColor="#ffffff"><td colspan="7" align="center"><b>'.$LDThuocNgoai.'</b></td></tr>';
				if(is_object($listReport_ngoai)){												
					$i=1;
					while($rowReport = $listReport_ngoai->FetchRow()){				
						if($rowReport['xuat']>0){
							if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
								$showgiaxuat = number_format($rowReport['giaxuat']);
							else $showgiaxuat = number_format($rowReport['giaxuat'],3);
							if($oldencoder!=$rowReport['product_encoder']){
								$flag=!$flag; 
								$oldencoder=$rowReport['product_encoder'];
							}
							if($flag) $bgc="#EFEFEF";
							else $bgc="#FFFFFF";
							
							echo '<tr bgColor="'.$bgc.'"><td align="center">'.$i.'</td>';
							echo '<td>'.$rowReport['product_name'].'</td>';
							echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
							echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
							echo '<td align="right">'.$showgiaxuat.'</td>';	
							echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';
							echo '<td></td></tr>';	//ghi chu
							$i++;					
						}
					}								
				} else {
					echo '<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
				}		
			}			
			$sTempDiv = $sTempDiv.ob_get_contents();				
			ob_end_clean();
			break;	
			
	case 1:  //1 qui khoa duoc
			if ($pharma_group_id>0){
				$listReport= $Pharma->Khochan_sudungthuockhac_nhieuthang('tayy', $pharma_group_id, '', $showmonth, ($showmonth+2), $showyear);
			}else{
				$listReport= $Pharma->Khochan_sudungthuockhac_nhieuthang('tayy', $pharma_group_id, 'noi', $showmonth, ($showmonth+2), $showyear);
				$listReport_ngoai= $Pharma->Khochan_sudungthuockhac_nhieuthang('tayy', $pharma_group_id, 'ngoai', $showmonth, ($showmonth+2), $showyear);
			}
			ob_start();
			//Thuoc noi hay nhom thuoc
			if($pharma_group_id==0)
				echo '<tr bgColor="#ffffff"><td colspan="7" align="center"><b>'.$LDThuocNoi.'</b></td></tr>';
							
			if(is_object($listReport)){				
				$i=1;
				while($rowReport = $listReport->FetchRow()){				
					if($rowReport['xuat']>0){
						if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
							$showgiaxuat = number_format($rowReport['giaxuat']);
						else $showgiaxuat = number_format($rowReport['giaxuat'],3);
						if($oldencoder!=$rowReport['product_encoder']){
							$flag=!$flag; 
							$oldencoder=$rowReport['product_encoder'];
						}
						if($flag) $bgc="#EFEFEF";
						else $bgc="#FFFFFF";
						
						echo '<tr bgColor="'.$bgc.'"><td align="center">'.$i.'</td>';
						echo '<td>'.$rowReport['product_name'].'</td>';
						echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
						echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
						echo '<td align="right">'.$showgiaxuat.'</td>';	
						echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';
						echo '<td></td></tr>';	//ghi chu
						$i++;					
					}
				}							
			} else {
				echo '<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			//Thuoc Ngoai
			if($pharma_group_id==0){
				echo '<tr bgColor="#ffffff"><td colspan="7" align="center"><b>'.$LDThuocNgoai.'</b></td></tr>';
				if(is_object($listReport_ngoai)){												
					$i=1;
					while($rowReport = $listReport_ngoai->FetchRow()){				
						if($rowReport['xuat']>0){
							if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
								$showgiaxuat = number_format($rowReport['giaxuat']);
							else $showgiaxuat = number_format($rowReport['giaxuat'],3);
							if($oldencoder!=$rowReport['product_encoder']){
								$flag=!$flag; 
								$oldencoder=$rowReport['product_encoder'];
							}
							if($flag) $bgc="#EFEFEF";
							else $bgc="#FFFFFF";
							
							echo '<tr bgColor="'.$bgc.'"><td align="center">'.$i.'</td>';
							echo '<td>'.$rowReport['product_name'].'</td>';
							echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
							echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
							echo '<td align="right">'.$showgiaxuat.'</td>';	
							echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';
							echo '<td></td></tr>';	//ghi chu
							$i++;					
						}
					}							
				} else {
					echo '<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
				}
			}
			$sTempDiv = $sTempDiv.ob_get_contents();				
			ob_end_clean();
			break;	
			
	case 2:  //bao cao 6 thang khoa duoc		
			if ($pharma_group_id>0){
				$listReport= $Pharma->Khochan_sudungthuockhac_nhieuthang('tayy', $pharma_group_id, '', $showmonth, ($showmonth+5), $showyear);
			}else{
				$listReport= $Pharma->Khochan_sudungthuockhac_nhieuthang('tayy', $pharma_group_id, 'noi', $showmonth, ($showmonth+5), $showyear);
				$listReport_ngoai= $Pharma->Khochan_sudungthuockhac_nhieuthang('tayy', $pharma_group_id, 'ngoai', $showmonth, ($showmonth+5), $showyear);
			}
			ob_start();
			//Thuoc noi hay nhom thuoc
			if($pharma_group_id==0)
				echo '<tr bgColor="#ffffff"><td colspan="7" align="center"><b>'.$LDThuocNoi.'</b></td></tr>';							
			if(is_object($listReport)){				
				$i=1;
				while($rowReport = $listReport->FetchRow()){				
					if($rowReport['xuat']>0){
						if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
							$showgiaxuat = number_format($rowReport['giaxuat']);
						else $showgiaxuat = number_format($rowReport['giaxuat'],3);
						if($oldencoder!=$rowReport['product_encoder']){
							$flag=!$flag; 
							$oldencoder=$rowReport['product_encoder'];
						}
						if($flag) $bgc="#EFEFEF";
						else $bgc="#FFFFFF";
						
						echo '<tr bgColor="'.$bgc.'"><td align="center">'.$i.'</td>';
						echo '<td>'.$rowReport['product_name'].'</td>';
						echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
						echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
						echo '<td align="right">'.$showgiaxuat.'</td>';	
						echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';
						echo '<td></td></tr>';	//ghi chu
						$i++;					
					}
				}							
			} else {
				echo '<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
			}
			//Thuoc Ngoai
			if($pharma_group_id==0){
				echo '<tr bgColor="#ffffff"><td colspan="7" align="center"><b>'.$LDThuocNgoai.'</b></td></tr>';
				if(is_object($listReport_ngoai)){																
					$i=1;
					while($rowReport = $listReport_ngoai->FetchRow()){				
						if($rowReport['xuat']>0){
							if(round($rowReport['giaxuat'],3)==round($rowReport['giaxuat']))
								$showgiaxuat = number_format($rowReport['giaxuat']);
							else $showgiaxuat = number_format($rowReport['giaxuat'],3);
							if($oldencoder!=$rowReport['product_encoder']){
								$flag=!$flag; 
								$oldencoder=$rowReport['product_encoder'];
							}
							if($flag) $bgc="#EFEFEF";
							else $bgc="#FFFFFF";
							
							echo '<tr bgColor="'.$bgc.'"><td align="center">'.$i.'</td>';
							echo '<td>'.$rowReport['product_name'].'</td>';
							echo '<td>'.$rowReport['unit_name_of_medicine'].'</td>';
							echo '<td align="right">'.number_format($rowReport['xuat']).'</td>';						
							echo '<td align="right">'.$showgiaxuat.'</td>';	
							echo '<td align="right">'.number_format($rowReport['giaxuat']*$rowReport['xuat']).'</td>';
							echo '<td></td></tr>';	//ghi chu
							$i++;					
						}
					}							
				} else {
					echo '<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
				}
			}
			$sTempDiv = $sTempDiv.ob_get_contents();				
			ob_end_clean();
			break;
	
	default: $sTempDiv='<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
			break;
}

if($sTempDiv=='')
	$sTempDiv='<tr bgColor="#ffffff"><td colspan="7">'.$LDNotReportThisMonth.'</td></tr>';	
$smarty->assign('divItem',$sTempDiv);
 
 
 //sHiddenInputs
$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="maxid" value="'.$maxid.'">
		<input type="hidden" name="group_cb" value="'.$group_cb.'">
		<input type="hidden" id="type_month" name="type_month" value="'.$type_month.'">
		<input type="hidden" id="subtitle" name="subtitle" value="'.$subtitle.'">
		<input type="hidden" name="user_report" value="'.$_SESSION['sess_user_name'].'">';

$smarty->assign('sHiddenInputs',$sTempHidden);


//*********************************************************************************


$smarty->assign('pbSubmit','<input type="image" '.createLDImgSrc($root_path,'showreport.gif','0','middle').'>');
//$smarty->assign('pbSave','<a href="javascript:Save(\''.$alertsave.'\');"><img '.createLDImgSrc($root_path,'savedisc.gif','0','middle').' align="middle"></a>');
$smarty->assign('pbPrint','<a href="#"><img '.createLDImgSrc($root_path,'printout.gif','0','middle').' align="middle" onclick="printOut(\''.$type_month.'\',\''.$showmonth.'\',\''.$showyear.'\',\''.$pharma_group_id.'\')"></a>');
$smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDBackTo.'" align="middle"></a>');

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','pharmacy/khochan_sudungthuoc_baocaokhac.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

