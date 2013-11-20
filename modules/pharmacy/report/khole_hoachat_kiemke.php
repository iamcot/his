<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    define('LANG_FILE','pharma.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    $thisfile= basename(__FILE__).URL_APPEND;
    $breakfile='../report_khole.php'.URL_APPEND;
    $urlsearch=$thisfile;

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
	
    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('common');

    # Title in the toolbar
    $smarty->assign('sToolbarTitle',$LDKhoLe.' :: '.$LDPharmaReportInventory);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

    # Window bar title
    $smarty->assign('Name',$LDPharmaReportInventory);

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
	function printOut(select_type)
	{
		urlholder="<?php echo $root_path;?>modules/pdfmaker/duoc/khole_kiemkeHC.php<?php echo URL_APPEND; ?>&type=chemical&select_type="+select_type;
		testprintpdf=window.open(urlholder,"KiemKeKhoLe","width=1000,height=760,menubar=yes,resizable=yes,scrollbars=yes");
	}
    function chkform(d) {	
            document.listmedform.action="";
            document.listmedform.submit();
    }
	function selectTypeMed() {
		var temp_i = document.getElementById("type_med").selectedIndex;
		document.getElementById("select_type").value = document.getElementById("type_med").options[temp_i].value;
		document.listmedform.action='<?php echo $thisfile; ?>';
		document.listmedform.submit();
	}	
</script>
<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp); 

    include_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'include/care_api_classes/class_product.php');
    $Product=new Product();

    $datetime=date("d/m/Y G:i:s");

    ob_start();
?>
<form name="listmedform" method="POST"  onSubmit="return chkform(this)">
<center>
<table cellSpacing="1" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="3" color="#5f88be"><?php echo $LDKhoLe; ?></th>
		<td align="right" rowspan="3">
				<table>
					<tr><td><input type="text" id="search" value="" size="30"></td>
						<td><a href="javascript:searchValue()"><input type="image" <?php echo createComIcon($root_path,'Search.png','0','',TRUE) ?> onclick="" ></a></td>
					</tr>
					<tr><td colspan="2"><FONT size="1"><?php echo $LDsearchExpGuide; ?></td></tr>
				</table>
		</td>
	</tr>
	<tr><th align="left" valign="top"><font size="2" color="#85A4CD"><?php echo $LDDanhMuc.' '.$temp; ?></th></tr>
	<tr><td align="left" valign="top"><?php echo $LDOClock.': '.$datetime; ?></td></tr>
</table>
<p>
<table border="0" cellSpacing="1" cellPadding="3" width="95%" bgColor="#C3C3C3">
	<tr bgColor="#EDF1F4">
		<th rowspan="2"><?php echo $LDSTT; ?></th>
		<th rowspan="2"><?php echo $LDChemicalName1; ?></th>
		<th rowspan="2"><?php echo $LDUnit; ?></th>	
		<th rowspan="2"><?php echo $LDCost; ?></th>			
		<th rowspan="2"><?php echo $LDSodangki; ?></th>
		<th rowspan="2"><?php echo $LDLotID1; ?></th>
		<th rowspan="2"><?php echo $LDSupplier; ?></th>
		<th rowspan="2"><?php echo $LDNuocSx1; ?></th>		
		<th rowspan="2"><?php echo $LDExpDate; ?></th>
		<th colspan="2"><?php echo $LDNumberOf; ?></th>	
		<th rowspan="2"><?php echo $LDHongVo; ?></th>
		<th rowspan="2"><?php echo $LDNote; ?></th>	
	</tr>
	<tr bgColor="#EDF1F4">
		<th><?php echo $LDSoSach; ?></th>
		<th><?php echo $LDThucTe; ?></th>
	</tr>
	<?php 
	switch($select_type){	
		case 0: $cond_typeput = ''; break;
		case 1: $cond_typeput = ' AND khole.typeput=1 '; break;
		case 2: $cond_typeput = ' AND khole.typeput=0 '; break;
		case 3: $cond_typeput = ' AND khole.typeput=2 '; break;
		default: $cond_typeput = ' AND khole.typeput=1 ';
	}		
	if ($search==''){
		//current_page, number_items_per_page, total_items, total_pages, location=1,2,3
		$number_items_per_page=20; 	$condition='';
		$condition .= $cond_typeput;	
		
		if ($listItem = $Product->SearchCatalogChemicalKhoLe($condition)){		
			$total_items = $listItem->RecordCount();
		} else $total_items =0;
		
		$total_pages=ceil($total_items/$number_items_per_page);
		
		include_once('../include/inc_issuepaper_listdepot_splitpage.php');

		if ($total_pages>1)
			$listItem = $Product->ShowCatalogChemicalKhoLe($current_page, $number_items_per_page, $condition);
		
	}else{
		if (strrpos($search,'/') || strrpos($search,'-')){
			$search = formatDate2STD($search,'dd/mm/yyyy');
			$condition=" AND exp_date LIKE '".$search."%' ";
		}
		elseif (is_numeric($search))
			$condition=" AND product_lot_id LIKE '%".$search."%' ";
		else
			$condition=" AND (product_name LIKE '".$search."%' OR product_name LIKE ' ".$search."%' )";
		$condition .= $cond_typeput;		

		$listItem = $Product->SearchCatalogChemicalKhoLe($condition);
		$breakfile = $thisfile;
	}

	if(is_object($listItem)){
		$sTemp='';
		for ($i=0;$i<$listItem->RecordCount();$i++)
		{
			$rowItem = $listItem->FetchRow();

			list($year,$month,$day) = explode("-",$rowItem['exp_date']);
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
				
			$expdate= formatDate2Local($rowItem['exp_date'],'dd/mm/yyyy');
			
			$sTemp=$sTemp.'<tr bgColor="'.$bgc.'" >
								<td align="center">'.($i+1).'</td>
								<td>'.$rowItem['product_name'].'</td>
								<td align="center">'.$rowItem['unit_name_of_chemical'].'</td>
								<td align="right">'.number_format($rowItem['price']).'</td>							
								<td align="center">'.$rowItem['sodangky'].'</td>
								<td align="center">'.$rowItem['product_lot_id'].'</td>
								<td align="center">'.$rowItem['care_supplier'].'</td>
								<td align="center">'.$rowItem['nuocsx'].'</td>							
								<td align="center">'.$expdate.'</td>
								<td align="right">'.number_format($rowItem['available_number']).'</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="center"></td>
							</tr>';
		}
		echo $sTemp;
			
	}else{
		$sTemp='<tr bgColor="#ffffff"><td colspan="11">'.$LDItemNotFound.'</td></tr>';
		echo $sTemp;
	}
	
	?>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<td align="center"><?php echo $sTempPage; ?></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="lang" value="<?php echo $lang; ?>">
			<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
			<input type="hidden" name="current_page" value="<?php echo $current_page; ?>">
			<input type="hidden" name="number_items_per_page" value="<?php echo $number_items_per_page; ?>">
			<input type="hidden" id="select_type" name="select_type" value="<?php echo $select_type; ?>">	
			<input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>">
		</td>
	</tr>
	<tr><td align="center">&nbsp;<p><a href="javascript:window.printOut('<?php echo $select_type; ?>');"><img <?php echo createLDImgSrc($root_path,'printout.gif','0','middle'); ?> ></a><p>&nbsp;</td></tr>
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

