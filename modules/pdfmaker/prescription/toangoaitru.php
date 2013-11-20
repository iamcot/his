<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

define('NO_CHAIN',1);
define('LANG_FILE','aufnahme.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

$lang_tables=array('departments.php');

include_once($root_path.'include/care_api_classes/class_prescription.php');
if(!isset($Prescription)) $Prescription=new Prescription;


$pres_show = $Prescription->getPrescriptionInfo($pres_id);
if($medicine_in_pres = $Prescription->getAllMedicineInPres($pres_id))
	$medicine_count = $medicine_in_pres->RecordCount();
	
if($pres_show['status_bill']){
	$tempbill=$LDFinish; $tempbill1='check-r.gif';}
else{
	$tempbill=$LDNotYet; $tempbill1='warn.gif';}
if($pres_show['status_finish']){
	$tempfinish=$LDFinish; $tempfinish1='check-r.gif';}
else{
	$tempfinish=$LDNotYet; $tempfinish1='warn.gif';}
	
$date1 = formatDate2Local($pres_show['date_time_create'],'dd/mm/yyyy');
$time1 = substr($pres_show['date_time_create'],-8);
		
//Get info of encounter
require_once($root_path.'include/care_api_classes/class_encounter.php');
# Get the encouter data
$enc_obj=& new Encounter($enc_nr);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	
	if($encounter['sex']=='m') $sex_patient = 'Nam';			//nam hay nu
	else $sex_patient = 'Nữ';
	
	
}
	
require_once($root_path.'include/care_api_classes/class_person.php');
$Person = new Person();
if($re_person = $Person->getInfoInsurEnc($encounter['pid'])){
	$insurance_st = $re_person['insurance_start'];
	$insurance_end = $re_person['insurance_exp'];
	$insurance_kcbbd = $re_person['madkbd'];
	$insurance_nr = $re_person['insurance_nr'];
}

//Get info of current department, ward
require_once($root_path.'include/care_api_classes/class_ward.php');
if(!isset($ward_obj)) $ward_obj=new Ward;
$wardid = $encounter['current_ward_nr'];
if ($wardid)
	$wardname = $ward_obj->WardName($wardid);



	
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A5', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Toa thuoc ngoai tru');
$pdf->SetMargins(5, 3, 3);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

// add a page
$pdf->AddPage();
// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);
$header='<table width="100%">
		<tr>
			<td valign="top" width="35%">
				Sở Y Tế Bình Dương<br>
				<b>'.PDF_HOSNAME.'</b>
			</td>
			<td valign="top" align="center" width="45%">
				<font size="15"><b>ĐƠN THUỐC</b></font>
			</td>
			<td valign="top" width="20%">
				MS: 17/BV-01<br>
				Số: '.$pres_show['prescription_id'].'
			</td>			
		</tr>
		</table>';//'.$wardname.'
$pdf->writeHTML($header);

$pdf->SetFont('dejavusans', '', 9);

$text_sinhhieu = str_replace("Mạch:","Mạch: ",$pres_show['sinhhieu']);
$text_sinhhieu = str_replace("\nNhiệt độ:","</td><td align=\"center\">Nhiệt độ: ",$text_sinhhieu);
$text_sinhhieu = str_replace("\nHA:","</td><td align=\"center\">HA: ",$text_sinhhieu);
$text_sinhhieu = str_replace("\nCân nặng:","</td><td align=\"center\">Cân nặng: ",$text_sinhhieu);

$htmlpatient='<table width="100%" cellpadding="1">
	<tr><td width="65%"><b>Bệnh nhân:</b> '.$encounter['name_last'].' '.$encounter['name_first'].'</td><td width="35%"><b>Mã BA:</b> '.$enc_nr.'</td></tr>
	<tr><td colspan="2"><b>Tuổi:</b> '.$encounter['tuoi'].'&nbsp;&nbsp;&nbsp; <b>Nam/nữ:</b> '.$sex_patient.'&nbsp;&nbsp;&nbsp; <b>Địa chỉ:</b> '.$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name'].", ".$encounter['quanhuyen_name'].", ".$encounter['citytown_name'].'</td></tr>
	<tr><td colspan="2"><b>BHYT (nếu có):</b> '.$insurance_nr.' / '.$insurance_kcbbd.'&nbsp;&nbsp;&nbsp;'.(($insurance_st!="0000-00-00")?' Từ: '.formatDate2Local($insurance_st,$date_format).' đến '.formatDate2Local($insurance_end,$date_format):'').'</td></tr>
	<tr><td colspan="2"><b>Dấu hiệu sinh tồn:</b><br><table><tr><td align="center">'.$text_sinhhieu.'</td></tr></table>
			</td></tr>
	<tr><td colspan="2"><b>Cận lâm sàng:</b><br>'.nl2br($pres_show['cls']).'</td></tr>
	<tr><td colspan="2"><b>Chẩn đoán:</b> '.$pres_show['diagnosis'];
	if($encounter['benhphu']!='')	
		$htmlpatient .= '+ '.$encounter['benhphu'];
	$htmlpatient .='</td></tr></table>';

$pdf->writeHTML($htmlpatient);

//Load tieu de bang
$html='<table width="100%" cellpadding="2" cellspacing="1" bgcolor="#EEEEEE">';
					/*<tr >
						<td align="center" width="6%"></td>
						<td align="center" width="50%"><i>Tên thuốc</i></td>
						<td align="center" width="13%"><i>Số lượng</i></td>
						<td align="center" width="15%"><i>Đơn giá</i></td>
						<td align="center" width="17%"><i>Thành tiền</i></td>
					</tr>*/
					
//Load du lieu bang
					
if($medicine_count){
	ob_start();	
	for($i=1;$i<=$medicine_count;$i++) { 			
					$medicine_pres = $medicine_in_pres->FetchRow();	
					$totalcostmedicine = $medicine_pres['sum_number']*$medicine_pres['cost'];						
					$strtext = $medicine_pres['desciption']; //howtouse count totalunits/per
						//$strtext = explode("/", $strtext);
						$split_desciption = explode(" ", $strtext);								
				if(trim($medicine_pres['morenote'])!='')	
					$medicine_pres['morenote'] = '<br>'.$medicine_pres['morenote'];
				echo '<tr bgcolor="#ffffff">
						<td width="6%">'.$i.'.</td> 
						<td width="50%">
							<b>'.$medicine_pres['product_name'].'</b><br>
							'.$LDDate.' '.$split_desciption[0].' '.$medicine_pres['number_of_unit'].' '.$LDUseTimes.', 
							'.$LDEachTime.' '.$split_desciption[1].' '.$split_desciption[2].'<br>
							<i>'.$LDAtTime.' '.str_replace('-', ' - ', $medicine_pres['time_use']).'</i>'.$medicine_pres['morenote'].'
						</td>';
						
				echo	'<td align="center" width="13%">
							'.$medicine_pres['sum_number'].' '.$medicine_pres['note'].'
						</td>
						<td align="right" width="15%">
							
						</td>
						<td align="right" width="17%">
							
						</td>
					</tr>';
					//'.number_format($medicine_pres['cost']).'	'.number_format($totalcostmedicine).'
	}
	$sTempDiv = $sTempDiv.ob_get_contents();				
	ob_end_clean();	
} else $sTempDiv='<tr><td colspan="5">'.$LDItemNotFound.'</td></tr>';

$html = $html.$sTempDiv.'</table>';			
$pdf->writeHTML($html, true, 0, true, 0);

$datetemp = explode('/',$date1);
if ($pres_show['taikham']==0 || $pres_show['taikham']=='0') 
	$pres_show['taikham']='';
if ($pres_show['nghiphep']==0 || $pres_show['nghiphep']=='0') 
	$pres_show['nghiphep']='';	

//'.number_format($pres_show['total_cost']).'	
$htmlnote='<table width="100%" cellpadding="1">
	<tr><td colspan="2" align="right">Tiền thuốc (chưa BHYT):............................</td></tr>
	<tr><td colspan="2" align="right"><b>Tổng tiền:</b>............................</td></tr>
	<tr><td colspan="4"><b>Ghi chú:</b> '.$pres_show['note'].'<br>';
	if($pres_show['taikham']!='')
		$htmlnote .= '+ '.$LDTaiKham.' ..'.$pres_show['taikham'].'.. '.$LDday1.'.<br>';
	if($pres_show['nghiphep']!='')	
		$htmlnote .= '+ '.$LDBenhNhanDuocNghiPhep.' ..'.$pres_show['nghiphep'].'.. '.$LDday1.'.<br>';
	
$htmlnote .='+ Khi tái khám nhớ đem theo toa thuốc này hay sổ khám bệnh.<br>';
if($encounter['tuoi']<=12){
	$htmlnote .= '+ Người thân: '.$encounter['hotenbaotin'];
}
$htmlnote .='</td></tr><tr><td colspan=2 width="100%">
	<table><td width="20%" align="center"><br><br><b>Giám đốc</b></td>
	<td width="20%" align="center"><br><br><b>Khoa Dược</b></td>
	<td width="20%" align="center"><br><br><b>Bệnh nhân</b></td>
	<td width="39%" align="center">Ngày '.$datetemp[0].' tháng '.$datetemp[1].' năm '.$datetemp[2].'<br><b>Bác sĩ điều trị<br><br><br><br>'.$pres_show['doctor'].'</b></td>
	
	</table>
	</td></tr>';
	//<tr><td></td><td align="center">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>'.$pres_show['doctor'].'</td></tr>';
	$htmlnote.='</table>';//edit 0310 - cot 
$pdf->writeHTML($htmlnote);
// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('toathuocngoaitru.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+