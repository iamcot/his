<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($Pres)) $Pres = new Prescription;

# Init
$thisfile= basename(__FILE__);
$breakfile='../nursing-manage-medicine.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
$fileforward='../include/inc_use_patient_medicine_save.php'.URL_APPEND;

if (!isset($target) || !$target) $target='pres';
/*
$target='pres' => select pres in this ward Form => go to depot File with &target=sum and list_selected_pres
$target='edit' => edit list_selected_pres
*/

//Get info of current department, ward
if ($ward_nr!=''){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} elseif ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = $LDAllWard;
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
 $smarty->assign('sToolbarTitle',$LDIssuePaper.' :: '.$LDPhatThuocChoBN);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDIssuePaper);
 
 $smarty->assign('sPresForm','<form name="selectpresform" method="POST" action="" onSubmit="return submitform(this)">');

 $smarty->assign('pbSubmit','<input type="image"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'>');
 $smarty->assign('pbCancel','<a href="'.$breakfile.'" ><img '.createLDImgSrc($root_path,'close2.gif','0','middle').' title="'.$LDCancel.'" align="middle"></a>');
 
 
 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);

# Collect additional javascript code
ob_start();
?>

<script  language="javascript">
<!--
function submitform() {
	document.selectpresform.action = "<?php echo $fileforward; ?>";
	document.selectpresform.submit();
}

function viewDetail(pres_id,encounter_nr)
{
	var win = 'show_prescription_issue_detail.php<?php echo URL_APPEND; ?>' + '&enc_nr=' + encounter_nr +'&pres_id=' + pres_id;
	myWindow=window.open( win , 'View Details' , 'height=500,width=650,menubar=no,resizable=yes,scrollbars=yes' );
	myWindow.focus();
}
function closePres(pres_id){
	var r=confirm("<?php echo $LDWarningClosePres; ?>");
	if (r==true) {
		document.selectpresform.action="<?php echo $fileforward;?>&isdelete=delete&pres_id=" + pres_id;
		document.selectpresform.submit();
	}
}
-->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Append the extra javascript to JavaScript block
$smarty->append('JavaScript',$sTemp);

$smarty->assign('deptname',$LDDept.': '.$deptname);
$smarty->assign('ward',$LDWard.': '.$wardname);

//***********************************NOI DUNG TRANG********************************
include_once($root_path.'include/core/inc_date_format_functions.php');
$smarty->assign('LDPatient',$LDPatient);	
$smarty->assign('LDMedicine',$LDMedicine);	
$smarty->assign('LDNhanVeTuThuoc','Số lượng kê toa');
$smarty->assign('LDDaPhat',$LDDaPhat);	
$smarty->assign('LDIssue',$LDIssue);
$smarty->assign('LDKetThuc',$LDKetThuc);	

//Calendar
require_once ($root_path.'js/jscalendar/calendar.php');
$calendar = new DHTML_Calendar($root_path.'js/jscalendar/',$lang,'calendar-system',true);
$calendar->load_files();
$date_format='dd-mm-yyyy';

$smarty->assign('calendar',$LDDateIssue.': '.$calendar->show_calendar($calendar,$date_format,'dateissue',date('Y-m-d')));


	$condition=''; $total_items=0;
	if ($ward_nr!='' && $ward_nr!='0')
		$condition.=' AND prs.ward_nr='.$ward_nr.' ';
	if ($dept_nr!='' && $dept_nr!='0')
		$condition.=' AND prs.dept_nr='.$dept_nr.' ';

	$sql=	"SELECT prs.prescription_id, prs.encounter_nr, prs.date_time_create, enc.current_room_nr, 
				per.name_first, per.name_last, per.sex, per.tuoi  
			FROM care_pharma_prescription_info AS prs, care_encounter AS enc, care_person AS per 
			WHERE  prs.encounter_nr=enc.encounter_nr
				".$condition." 
				AND(prs.prescription_type='0397' OR prs.prescription_type='0398') AND prs.total_cost>0 AND prs.dongphatthuoc='0' 
				AND per.pid=enc.pid 
				ORDER BY enc.current_room_nr, prs.prescription_id ";
  /*
$sql=	"SELECT prs.prescription_id, prs.encounter_nr, prs.date_time_create, enc.current_room_nr,
				per.name_first, per.name_last, per.sex, per.tuoi
			FROM care_pharma_prescription_info AS prs, care_encounter AS enc, care_person AS per
			WHERE prs.in_issuepaper<>'0' AND prs.status_finish='1' AND prs.phieutheodoi='0'
				AND prs.encounter_nr=enc.encounter_nr
				".$condition."
				AND(prs.prescription_type='0397' OR prs.prescription_type='0398') AND prs.total_cost>0 AND prs.dongphatthuoc='0'
				AND per.pid=enc.pid
				ORDER BY enc.current_room_nr, prs.prescription_id ";       */
	if($listpres=$db->Execute($sql))
	{
		$count = $listpres->RecordCount();
		if($count){
			$flag_g=-1;	//flag check show group name, type name
			ob_start();
			for($i=0;$i<$count;$i++) {
				$item=$listpres->FetchRow();
				
				if ($flag_g!=$item['current_room_nr']){
					echo '<tr bgcolor="#ffffff"><td colspan="6"><b>'.$LDRoom.' '.$item['current_room_nr'].' :</b></td></tr>';
					$flag_g=$item['current_room_nr'];
				}
				if($item['sex']=='m') $item['sex']=$LDNam;
				else if($item['sex']=='f') $item['sex']=$LDNu;				
				$date_pres = formatDate2Local($item['date_time_create'],$date_format,false,false,$sepChars);
				switch($item['typeput']){
					case 0: $dang = 'BHYT'; break;
					case 1: $dang = 'KPSN'; break;
					case 2: $dang = 'CBTC'; break;
				}				
				echo '<tr bgcolor="#eeeeee">
						<td valign="top"><table width="100%">
							<tr><td><b>'.$item['name_last'].' '.$item['name_first'].'</b></td><td align="right"><a href="javascript:#"> 
					<img '.createComIcon($root_path,'info3.gif','0','',TRUE).' onclick="viewDetail(\''.$item['prescription_id'].'\',\''.$item['encounter_nr'].'\')"></a></td></tr>
							<tr><td>'.$LDMaBN.': '.$item['encounter_nr'].' / '.$item['sex'].'</td><td>'.$LDTuoi.': '.$item['tuoi'].'</td></tr>
							<tr><td>'.$LDPresID.': '.$item['prescription_id'].' / '.$dang.'</td>
								<td>'.$LDPresDate.': '.$date_pres.'</td></tr></table>						
						</td>';
				//usleep(100000);
				echo '<td colspan="4" bgcolor="#ffffff">';
	
					if($medicine_result=$Pres->getAllMedicineInPres($item['prescription_id'])){
						echo '<table width="100%">';
						for ($j=0; $j<$medicine_result->RecordCount();$j++) {
							$items_in_sheet = $medicine_result->FetchRow();	
							$total_items++;			
							if($ketqua = $Pres->getMedicineIssue($item['prescription_id'], $items_in_sheet['product_encoder']))
								$tongthuocdaphat = $ketqua['sum'];
							else $tongthuocdaphat=0;
							echo '<tr><td width="50%"><b>'.$items_in_sheet['product_name'].'</b><br>';
							echo $items_in_sheet['desciption'].'/'.$LDUseTimes.' x '.$items_in_sheet['number_of_unit'].' '.$LDUseTimes.': '.$items_in_sheet['time_use'].'</td>';
							echo '<td align="center" width="16%">'.$items_in_sheet['sum_number'].' '.$items_in_sheet['note'].'</td>';
							echo '<td align="center" width="16%">'.$tongthuocdaphat.'</td>';
							echo '<td align="center" width="16%"><input type="text" name="number'.$total_items.'" size="7" value="0">
							<input type="hidden" name="enc_nr'.$total_items.'" value="'.$item['encounter_nr'].'">
							<input type="hidden" name="encoder'.$total_items.'" value="'.$items_in_sheet['product_encoder'].'">
							<input type="hidden" name="pres_id'.$total_items.'" value="'.$item['prescription_id'].'"></td></tr>';
							
						}
						echo '</table>';					
					}
					
				echo '</td><td align="center"><a href="#" onclick="closePres('.$item['prescription_id'].');">'.$LDDongToa.'</a></td>
					</tr>';	
			}
			
			$sListRows = $sListRows.ob_get_contents();
			ob_end_clean();	
		}
		else{
			$sListRows='<tr bgColor="#eeeeee"><td colspan="6">'.$LDNoPres.'</td></tr>';
		}
		$itemcode=$itemcode1;
		$smarty->assign('ItemLine',$sListRows);
	}
	

//*********************************************************************************
//$smarty->assign('test',$target.' '.$ward_nr);



//sHiddenInputs
	$sTempHidden = '<input type="hidden" name="sid" value="'.$sid.'">
		<input type="hidden" name="hidden" value="'. $itemcode .'">
		<input type="hidden" name="lang" value="'.$lang.'">
		<input type="hidden" name="total_items" value="'.$total_items.'">
		<input type="hidden" name="ward_nr" value="'.$ward_nr.'">
		<input type="hidden" name="dept_nr" value="'.$dept_nr.'">';

	$smarty->assign('sHiddenInputs',$sTempHidden);

$sCancel="<a href=";
if($_COOKIE['ck_login_logged'.$sid]) $sCancel.=$breakfile;
	else $sCancel.='aufnahme_pass.php';
$sCancel.='><img '.createLDImgSrc($root_path,'close2.gif','0').' alt="'.$LDCancelClose.'"></a>';

$smarty->assign('pbCancel',$sCancel);

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','nursing/issuepaper_use_patient.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');


?>