<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

$thisfile= basename(__FILE__).URL_APPEND;
$breakfile='../report_khochan.php'.URL_APPEND;
$fileforward='khochan_kiemkethuoc_save.php'.URL_APPEND;
$urlsearch=$thisfile;

function CheckValidDay($exp_date){
	list($year,$month,$day) = explode("-",$exp_date);
	$valid_year  = $year - date("Y");
	$valid_month = $month - date("m");
	$valid_day   = $day - date("d");

	if ($valid_year!=0){
		if ($valid_year>0) $bgc="#ffffff";
			else $bgc="#FF0000";
	}
	elseif ($valid_month==0){
		if($valid_day>0) $bgc="#FFAAFF";
		else $bgc="#FF0000";
	}
	else{
		switch($valid_month){
			case ($valid_month<0) : $bgc="#FF0000";	break; //do				
			case ($valid_month<=1) : $bgc="#FFAAFF"; break;	//hong
			case ($valid_month<=6) : $bgc="#FFFF00"; break;	//vang
			case ($valid_month<=12) : $bgc="#D2D2D2"; break;	//xam nhat
			case ($valid_month>12) : $bgc="#ffffff"; break;	//trang
			default: $bgc="#ffffff"; break;
		}
					
	}
	return $bgc;
}

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
		
switch($type){
	case 'tayy': $title1=$LDthuocTayY; $tbl='care_pharma_khochan_ton_info';  break;
	case 'dongy': $title1=$LDthuocDongY; $tbl='care_pharma_khochan_dongy_ton_info'; break;
	default: $title1=$LDthuoc; break;
}


//Test format today
if (isset($todate) && $todate!='' && strpos($todate,'-')<3) {
	list($t_day,$t_month,$t_year) = explode("-",$todate);
	$todate=$t_year.'-'.$t_month.'-'.$t_day;
}
else 
	list($t_year,$t_month,$t_day) = explode("-",$todate);

if($todate!=''){
	$check_sql = "SELECT * FROM $tbl WHERE todate<='".$todate."' ORDER BY todate DESC ";	
	if($temp_re = $db->Execute($check_sql)){
		$last_report = $temp_re->FetchRow();
		$last_report_date = $last_report['todate'];
		if($last_report_date==$todate){ //ngay ton kho = ngay dang xem
			$flag = true;
			$chotngaydau=$last_report['fromdate'];
			$chotngaycuoi=$todate;
			$update_id=$last_report['id'];
		}else{
			//neu ko, tinh ton kho gan nhat + nhap xuat tu $last_report_date->$todate
			$flag = false;
			$chotngaydau=date('Y-m-d', strtotime($last_report['todate'].' +1 day'));
			$chotngaycuoi=$todate;			
			$update_id='';				
		}
		$tempx = explode('-',$chotngaycuoi); $tempy = explode('-',$chotngaydau);
		$thangbaocao = $tempx[1].'/'.$tempx[0];
		$ngaydaux = $tempy[2].'-'.$tempy[1].'-'.$tempy[0];
		$ngaycuoix = $tempx[2].'-'.$tempx[1].'-'.$tempx[0];
	}
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
 $smarty->assign('sToolbarTitle',$LDKhoChan.' :: '.$LDPharmaReportInventory.' '.$title1);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 # Window bar title
 $smarty->assign('title',$LDPharmaReportInventory);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 
  ob_start();
?>
<style type="text/css">

</style>
<script language="javascript">
function searchValue()
{
	var search = document.getElementById('search').value;
	document.listmedform.action="<?php echo $urlsearch;?>&search="+search;
	document.listmedform.submit();
}
function printOut(select_type,flag)
{
	if(flag==true){
	urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khochan_kiemkethuoc.php<?php echo URL_APPEND; ?>&type=medicine&select_type="+select_type+"&dongtayy=<?php echo $type.'&todate='.$todate; ?>";
	testprintpdf=window.open(urlholder,"KiemKeKhoLe","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	}else alert('<?php echo $LDPlsChotNgayTrcKhiIn; ?>');
}
function chkform(d) {	
	document.listmedform.action="";
	document.listmedform.submit();
}
function selectTypeMed() {
	var temp_i = document.getElementById("type_med").selectedIndex;
	document.getElementById("select_type").value = document.getElementById("type_med").options[temp_i].value;
	document.listmedform.action='<?php echo $thisfile.'&type='.$type; ?>';
	document.listmedform.submit();
}
function ChotKiemKe(update_id){

	var warning = "";
	if(update_id!=''){
		warning = "<?php echo $LDKiemKeWaring; ?>";
	}
	var name=prompt(warning + "\n" + "<?php echo $LDChotKiemKeVao.' '.$ngaydaux.' '.$LDDen.' '.$ngaycuoix.' '.$LDChoThang ?>","<?php echo $thangbaocao; ?>");
	if (name!=null)
	{
		document.listmedform.action="<?php echo $fileforward; ?>&target=save&ngaydau=<?php echo $chotngaydau; ?>&ngaycuoi=<?php echo $chotngaycuoi; ?>&update_id="+update_id+"&kkthang="+name;
        document.listmedform.submit();

	}


}
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp); 

include_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_product.php');
$Product=new Product();
require_once($root_path.'include/care_api_classes/class_pharma.php');
$Pharma=new Pharma();
//$datetime=date("d/m/Y G:i:s");


			
//Calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
$calendar->load_files();
$date_format='dd-mm-yyyy';
			
switch($select_type){	
	case 0: $cond_typeput = ''; $typeput=0; break;
	case 1: $cond_typeput = ' AND source.typeput=1 '; $typeput=1; break;
	case 2: $cond_typeput = ' AND source.typeput=0 '; $typeput=0; break;
	case 3: $cond_typeput = ' AND source.typeput=2 '; $typeput=2; break;
	default: $cond_typeput = ' AND source.typeput=1 '; $typeput=1;
}

switch($type){
	case 'tayy': $dongtayy =' AND pharma_type IN (1,2,3)'; break;	
	case 'dongy': $dongtayy = ' AND pharma_type IN (4,8,9,10) '; break;
	default: $dongtayy = ''; break;
}	

		
//$smarty->assign('monthreport',$LDFromDate.' '.$LDToDate);

ob_start();
?>
<form name="listmedform" method="POST"  onSubmit="return chkform(this)">
<center>
<table cellSpacing="1" cellPadding="2" border="0" width="95%">
	<tr><th align="left" colspan="4"><font size="3" color="#5f88be"><?php echo $LDKhoChan; ?></th>
		<td width="40%" align="right" rowspan="3">
				<table>
					<tr><td><input type="text" id="search" name="search" value="" size="30"></td>
						<td><a href="#"><input type="image" <?php echo createComIcon($root_path,'Search.png','0','',TRUE) ?> onclick="searchValue()" ></a></td>
					</tr>
					<tr><td colspan="2"><FONT size="1"><?php echo $LDsearchExpGuide; ?></td></tr>
				</table>
		</td>
	</tr>
	<tr><th align="left" valign="top"><font size="2" color="#85A4CD"><?php echo $LDDanhMuc.' '.$temp; ?></th>
		<td align="left" valign="middle"><?php 			
			echo $LDOnDate.': '; ?></td>
		<td><?php	echo $calendar->show_calendar($calendar,$date_format,'todate',$todate);
			?>
		</td>
		<td><a href="#"><input type="image" <?php echo createLDImgSrc($root_path,'showreport.gif','0','middle') ?> onclick="searchValue()" ></a></td>
	</tr>
	<tr><td colspan="4"><?php echo $LDFrom.' '.$ngaydaux.' '.$LDDen.' '.$ngaycuoix; ?></td></tr>
</table>
<p>
<table border="0" cellSpacing="1" cellPadding="3" width="95%" bgColor="#C3C3C3">
	<tr bgColor="#EDF1F4">
		<th rowspan="2"><?php echo $LDSTT; ?></th>
		<th rowspan="2"><?php echo $LDMedicineName; ?></th>
		<th rowspan="2"><?php echo $LDUnit; ?></th>	
		<th rowspan="2"><?php echo $LDSoKiemSoat; ?></th>
		<th rowspan="2"><?php echo $LDNuocSx1; ?></th>
		<th rowspan="2"><?php echo $LDCost; ?></th>
		<th rowspan="2"><?php echo $LDExpDate; ?></th>
		<th colspan="2"><?php echo $LDNumberOf; ?></th>	
		<th rowspan="2"><?php echo $LDHongVo; ?></th>
		<th rowspan="2"><?php echo $LDTotalCost; ?></th>
		<th rowspan="2"><?php echo $LDNote; ?></th>	
	</tr>
	<tr bgColor="#EDF1F4">
		<th><?php echo $LDSoSach; ?></th>
		<th><?php echo $LDThucTe; ?></th>
	</tr>
	<?php 

	if ($search==''){
		//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
		$number_items_per_page=20; 
		$condition .= $cond_typeput;

		if($flag)
			$listItem = $Product->ShowKhoChanThuoc_Ton($type, $current_page, $number_items_per_page, $condition, $todate);
		else
			$listItem = $Pharma->Khochan_thuoc_nhapxuatton_theongay($type, $cond_typeput, $last_report_date, $todate);
		
	}else{
		if (strrpos($search,'/') || strrpos($search,'-')){
			$search = formatDate2STD($search,'dd/mm/yyyy');
			$condition=" AND exp_date LIKE '".$search."%' ";
		}
		elseif (is_numeric($search))
			$condition=" AND lotid LIKE '%".$search."%' ";
		else
			$condition=" AND (product_name LIKE '".$search."%' OR product_name LIKE ' ".$search."%' )";
			
		if($flag)
			$listItem = $Product->SearchKhoChanThuoc_Ton($type, $condition, $todate);
		else
			$listItem = $Pharma->Khochan_thuoc_nhapxuatton_theongay($type, $condition.' '.$cond_typeput, $last_report_date, $todate);
			
		$breakfile = $thisfile.'&type='.$type;
	}
	
	if(is_object($listItem)){
		$sTemp='';
		$Tong_toncuoi=0;
		
		if($flag){
			for ($i=0;$i<$listItem->RecordCount();$i++)
			{
				$rowItem = $listItem->FetchRow();

				$bgc=CheckValidDay($rowItem['exp_date']);
				$Tong_toncuoi += $rowItem['price']*$rowItem['number'];
				
				$expdate= formatDate2Local($rowItem['exp_date'],'dd/mm/yyyy');
				if (round($rowItem['price'],3)==round($rowItem['price']))
					$show_price = number_format($rowItem['price']);
				else $show_price = number_format($rowItem['price'],3);
				$stt=$i+1;
				$sTemp=$sTemp.'<tr bgColor="'.$bgc.'" >
									<td align="center">'.$stt.'</td>
									<td>'.$rowItem['product_name'].'</td>
									<td align="center">'.$rowItem['unit_name_of_medicine'].'</td>
									<td align="center">'.$rowItem['lotid'].'</td>
									<td align="center">'.$rowItem['nuocsx'].'</td>
									<td align="right">'.$show_price.'</td>
									<td align="center">'.$expdate.'</td>
									<td align="right">'.number_format($rowItem['number']).'</td>
									<td align="center"></td>
									<td align="right"></td>
									<td align="right">'.number_format($rowItem['price']*$rowItem['number']).'</td>
									<td><input type="hidden" name="encoder'.$stt.'" value="'.$rowItem['product_encoder'].'">
										<input type="hidden" name="lotid'.$stt.'" value="'.$rowItem['lotid'].'">
										<input type="hidden" name="exp_date'.$stt.'" value="'.$rowItem['exp_date'].'">
										<input type="hidden" name="number'.$stt.'" value="'.$rowItem['number'].'">
										<input type="hidden" name="price'.$stt.'" value="'.$rowItem['price'].'">
									</td>
								</tr>';
			}
			
			echo $sTemp;
			
		}else{
			$n = $listItem->RecordCount();
			$stt=1;
			for ($j=0;$j<=$n;$j++){
				if($j<$n)
					$rowItem = $listItem->FetchRow();
				
				if($oldencode!=$rowItem['product_encoder'] || $j==$n){ //neu thuoc moi (hoac in loai thuoc cuoi)
					//in list thuoc cu
					foreach ($list_encoder as $value) {
						if($value['toncuoi']!=0){
							$bgc=CheckValidDay($value['exp_date']);
							if (round($value['giatoncuoi'],3)==round($value['giatoncuoi']))
								$show_price = number_format($value['giatoncuoi']);
							else $show_price = number_format($value['giatoncuoi'],3);
							echo '<tr bgColor="'.$bgc.'" >
									<td align="center">'.$stt.'</td>
									<td>'.$value['product_name'].'</td>
									<td align="center">'.$value['unit'].'</td>
									<td align="center">'.$value['lotid'].'</td>
									<td align="center">'.$value['nuocsx'].'</td>
									<td align="right">'.$show_price.'</td>
									<td align="center">'.formatDate2Local($value['exp_date'],'dd/mm/yyyy').'</td>
									<td align="right">'.number_format($value['toncuoi']).'</td>
									<td align="center"></td>
									<td align="center"></td>
									<td align="right">'.number_format($value['toncuoi']*$value['giatoncuoi']).'</td>
									<td><input type="hidden" name="encoder'.$stt.'" value="'.$value['encoder'].'">
										<input type="hidden" name="lotid'.$stt.'" value="'.$value['lotid'].'">
										<input type="hidden" name="exp_date'.$stt.'" value="'.$value['exp_date'].'">
										<input type="hidden" name="number'.$stt.'" value="'.$value['toncuoi'].'">
										<input type="hidden" name="price'.$stt.'" value="'.$value['giatoncuoi'].'">
									</td>
								</tr>';
							$stt++;
							$Tong_toncuoi += $value['toncuoi']*$value['giatoncuoi'];
						}
					}					
					$oldencode=$rowItem['product_encoder'];
					unset($list_encoder); $i=1;
					//Tao thuoc moi
					$list_encoder[$i]['encoder'] = $rowItem['product_encoder'];
					$list_encoder[$i]['product_name'] = $rowItem['product_name'];
					$list_encoder[$i]['unit'] = $rowItem['unit_name_of_medicine'];
					if($rowItem['lonhap']!='') $list_encoder[$i]['lotid'] = $rowItem['lonhap'];
					else $list_encoder[$i]['lotid'] = $rowItem['loton'];
					if($rowItem['hannhap']!='') $list_encoder[$i]['exp_date'] = $rowItem['hannhap'];
					else  $list_encoder[$i]['exp_date'] = $rowItem['hanton'];
					$list_encoder[$i]['nuocsx'] = $rowItem['nuocsx'];
					$list_encoder[$i]['toncuoi'] = $rowItem['ton'] + $rowItem['nhap'] - $rowItem['xuat'];
					$list_encoder[$i]['giatoncuoi'] = max($rowItem['giaton'],$rowItem['gianhap']);

				}else{	//thuoc cu
					if($rowItem['ton']>0 || $rowItem['nhap']>0){
						/*	Cong don vao hang cu:
								ton>0 && nhap>0 && giaton~gianhap
								ton>0 && (giaton~giatoncuoi || giatoncuoi==0)
								nhap>0 && (gianhap~giatoncuoi || giatoncuoi==0)								
							Them hang moi
						*/
						if(($rowItem['giaton']>0 && $rowItem['gianhap']>0 && abs($rowItem['giaton']-$rowItem['gianhap'])<=1) || 
							($rowItem['giaton']>0 && (abs($rowItem['giaton']-$list_encoder[$i]['giatoncuoi'])<=1 || $list_encoder[$i]['giatoncuoi']==0)) ||
							($rowItem['gianhap']>0 && (abs($rowItem['gianhap']-$list_encoder[$i]['giatoncuoi'])<=1 || $list_encoder[$i]['giatoncuoi']==0))){ 
							$list_encoder[$i]['toncuoi'] += $rowItem['ton']+$rowItem['nhap'];
							$list_encoder[$i]['giatoncuoi'] = max($list_encoder[$i]['giatoncuoi'],$rowItem['giaton'],$rowItem['gianhap']);
							
							if($rowItem['lonhap']!='') $list_encoder[$i]['lotid'] = $rowItem['lonhap'];
							else $list_encoder[$i]['lotid'] = $rowItem['loton'];
							if($rowItem['hannhap']!='') $list_encoder[$i]['exp_date'] = $rowItem['hannhap'];
							else  $list_encoder[$i]['exp_date'] = $rowItem['hanton'];
							
						}else{ //them hang moi
							$i++;
							$list_encoder[$i]['encoder'] = $rowItem['product_encoder'];
							$list_encoder[$i]['product_name'] = $rowItem['product_name'];
							$list_encoder[$i]['unit'] = $rowItem['unit_name_of_medicine'];
							if($rowItem['lonhap']!='') $list_encoder[$i]['lotid'] = $rowItem['lonhap'];
							else $list_encoder[$i]['lotid'] = $rowItem['loton'];
							if($rowItem['hannhap']!='') $list_encoder[$i]['exp_date'] = $rowItem['hannhap'];
							else  $list_encoder[$i]['exp_date'] = $rowItem['hanton'];
							$list_encoder[$i]['nuocsx'] = $rowItem['nuocsx'];
							$list_encoder[$i]['toncuoi'] = $rowItem['ton'] + $rowItem['nhap'];
							$list_encoder[$i]['giatoncuoi'] = max($rowItem['giaton'],$rowItem['gianhap']);							
						}
					}
					
					if($rowItem['xuat']>0){
							$flag_gia = true;
							for($k=1;$k<=$i;$k++){
								//echo 'x= '.$rowItem['xuat'].' tc= '.$list_encoder[$k]['toncuoi'].' <br>';
								if($list_encoder[$k]['toncuoi']>0 && abs($list_encoder[$k]['giatoncuoi']-$rowItem['giaxuat'])<=1 && $rowItem['giaxuat']>0){
									if($list_encoder[$k]['toncuoi']>=$rowItem['xuat']){
										$list_encoder[$k]['toncuoi'] = $list_encoder[$k]['toncuoi']-$rowItem['xuat'];
										$flag_gia = false;
										break;
									}else{
										$rowItem['xuat'] = $rowItem['xuat']-$list_encoder[$k]['toncuoi'];
										$list_encoder[$k]['toncuoi']=0;									
									}
								}elseif($k==$i){
									$list_encoder[$k]['toncuoi'] = $list_encoder[$k]['toncuoi']-$rowItem['xuat'];
									$flag_gia = false;									
								}								
							}
							
							if($flag_gia){
								for($k=1;$k<=$i;$k++){
									//echo 'x= '.$rowItem['xuat'].' tc= '.$list_encoder[$k]['toncuoi'].' <br>';								
									if($list_encoder[$k]['toncuoi']>=0){
										if($list_encoder[$k]['toncuoi']>$rowItem['xuat']){
											$list_encoder[$k]['toncuoi'] = $list_encoder[$k]['toncuoi']-$rowItem['xuat'];
											break;
										}else{									
											if($i>1){
												$rowItem['xuat'] = $rowItem['xuat']-$list_encoder[$k]['toncuoi'];
												$list_encoder[$k]['toncuoi']=0;
											}else{
												$list_encoder[$k]['toncuoi'] = $list_encoder[$k]['toncuoi']-$rowItem['xuat'];
											}
										}
									}
								}
							}
						
						//
					}
				}
			}
		}	
	}else{
		$sTemp='<tr bgColor="#ffffff"><td colspan="12">'.$LDItemNotFound.'</td></tr>';
		echo $sTemp;
	}
	
	?>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<td align="right"><b><?php echo $LDTongTien.': '.number_format($Tong_toncuoi); ?></b></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="lang" value="<?php echo $lang; ?>">
			<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
			<input type="hidden" name="typeput" value="<?php echo $typeput; ?>">
			<input type="hidden" name="type" value="<?php echo $type; ?>">
			<input type="hidden" name="maxid" value="<?php echo $stt; ?>">
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>">
			<input type="hidden" name="number_items_per_page" value="<?php echo $number_items_per_page; ?>">
			<input type="hidden" id="select_type" name="select_type" value="<?php echo $select_type; ?>">
			<input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>">
		</td>
	</tr>
	<tr><td align="center">&nbsp;<p>
	<a href="javascript:#"><img <?php echo createLDImgSrc($root_path,'chotkiemke.png','0','middle') ?> align="middle" onclick="ChotKiemKe('<?php echo $update_id; ?>')"></a>&nbsp;
	<a href="javascript:window.printOut('<?php echo $select_type; ?>','<?php echo $flag; ?>');"><img <?php echo createLDImgSrc($root_path,'printout.gif','0','middle'); ?> ></a><p>&nbsp;</td></tr>
	<tr>
	<td align="right">
		<table border="0" cellpadding="2" cellspacing="1" bgColor="#B4B4B4">
			<tr><td style="background-color:#FF0000;width:10px;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDHSDGuide1; ?></td></tr>
			<tr><td style="background-color:#FFAAFF;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDHSDGuide2; ?></td></tr>
			<tr><td style="background-color:#FFFF00;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDHSDGuide3; ?></td></tr>
			<tr><td style="background-color:#D2D2D2;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDHSDGuide4; ?></td></tr>
			<tr><td style="background-color:#ffffff;">&nbsp;</td><td bgColor="#ffffff"><?php echo $LDHSDGuide5; ?></td></tr>
		</table>
		</td>
	</tr>
</table>


</center>
</form>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

$smarty->assign('breakfile',$breakfile);

# Show main frame
$smarty->display('common/mainframe.tpl');

?>

