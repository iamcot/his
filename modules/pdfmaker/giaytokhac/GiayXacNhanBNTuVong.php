<?php
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

define('NO_2LEVEL_CHK',1);
$lang_tables[]='emr.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($enc_nr);
if($enc_obj->loadEncounterData()){
	$status=$enc_obj->getLoadedEncounterData();
}
$encounter_class=$enc_obj->getEncounterClassInfo($status['encounter_class_nr']);
$insurance_class=$enc_obj->getInsuranceClassInfo($status['insurance_class_nr']);

# Resolve the encounter class name
if (isset($$encounter_class['LD_var'])&&!empty($$encounter_class['LD_var'])){
	$eclass=$$encounter_class['LD_var'];
}else{
	$eclass= $encounter_class['name'];
} 
# Resolve the insurance class name
if (isset($$insurance_class['LD_var'])&&!empty($$insurance_class['LD_var'])) $insclass=$$insurance_class['LD_var']; 
    else $insclass=$insurance_class['name']; 

# Get ward or department infos
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj = new Department();
$current_dept_LDvar=$dept_obj->LDvar($status['current_dept_nr']);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);

require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj = new Ward();
$wardName = $ward_obj->getWardInfo($enc_obj->encounter['current_ward_nr']);

require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
$pdf->SetTitle('GIAY XAC NHAN BENH NHAN TU VONG');
$pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$pdf->SetMargins(8, 8, 7);    

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);
$pdf->AddPage();
	
$pdf->SetFont('dejavusans', '', 11);
        
$tbl = '
<table cellpadding="3">
    <tr>
		<td width="40%" align="center"><b>SỞ Y TẾ BÌNH DƯƠNG</b>
		</td>
		<td width="60%" align="center"><b>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM </b> 
		</td>
	</tr>
	<tr>
		<td align="center" width="40%">
		<b>'.PDF_HOSNAME.'</b><br/>
		'.str_pad('',17,'__',STR_PAD_RIGHT).'
		</td>
		<td align="center" width="60%"><b>Độc lập - Tự do - Hạnh phúc</b> 
		<br/>
		'.str_pad('',26,'__',STR_PAD_RIGHT).'
		</td>
	</tr>
	<tr>
		<td align="center">
		</td>
		<td align="right"><br/><br/> <i> '.PDF_HOSNAME.', ngày ...'.str_pad(date('d'),5,'.',STR_PAD_RIGHT).' tháng ...'.str_pad(date('m'),5,'.',STR_PAD_RIGHT).' năm ...'.str_pad(date('Y'),7,'.',STR_PAD_RIGHT).'</i> 
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><br/><br/><b><font size="13"> GIẤY XÁC NHẬN BỆNH NHÂN TỬ VONG</font></b><br/>
		</td>
	</tr>
	<tr>
	</tr>
	<tr>
		<td colspan="2">
			<b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.PDF_HOSNAME.' xác nhận: </b>
		</td>
	</tr>
	<tr>
		<td colspan="2">
                <table>
                    <tr>
                        <td width="50%">
			- Ông (bà) : ';
                            if($status['name_last']&&$status['name_first']){
                                $tbl.=$status['name_last'].' '.$status['name_first'];
                            }else{
                                $tbl.='................................................................................................';
                            }
                        $tbl.='</td><td>'.$LDSex.': ';
                            if($status['sex']=='f'){
                                $tbl.=$LDFemale;
                            }else if($status['sex']=='m'){
                                $tbl.=$LDMale;
                            }else{
                                $tbl.='...........................................';
                            }
        $tbl.='</td>
                </tr>
                </table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
                <table>
                    <tr>
                        <td  width="50%">
			- Năm sinh: ';
                        if($status['date_birth']){
                            $tbl.=$status['date_birth'];
                        }else{
                            $tbl.='..................................';
                        }
                        $tbl.='</td><td>'.$LDNatIdNr.': ';
                        if($status['nat_id_nr']){
                            $tbl.=$status['nat_id_nr'];
                        }else{
                            $tbl.='.............................................................';
                        }
        $tbl.='</td>
                </tr>
                </table>
                </td>
	</tr>
	<tr>
		<td colspan="2">  - Địa chỉ:  ';
                        if($status['addr_str_nr']){
                            $tbl.=$status['addr_str_nr'].', ';
                        }
                        if($status['addr_str']){
                            $tbl.=$status['addr_str'].', ';
                        }
                        if($status['phuongxa_name']){
                            $tbl.=$status['phuongxa_name'].', ';
                        }
                        if($status['quanhuyen_name']){
                            $tbl.=$status['quanhuyen_name'].', ';
                        }
                        if($status['citytown_name']){
                            $tbl.=$status['citytown_name'];
                        }
        
	$tbl.='</td>
	</tr>
	<tr>
		<td colspan="2">
			  - Đơn vị công tác: ';
                        if($status['noilamviec']){
                            $tbl.=$status['noilamviec'];
                        }else{
                            $tbl.='............................................................................................................................';
                        }  
	$time=explode(' ', $status['encounter_date']);					
	$tbl.='	</td>
	</tr>
	<tr>
		<td colspan="2">
			  - Vào viện lúc: '; 
        $tbl.= substr($time['1'],0,2).' giờ '.substr($time['1'],3,2).' phút, ngày '.substr($status['encounter_date'],8,2).' tháng '.substr($status['encounter_date'],5,2).' năm '.substr($status['encounter_date'],0,4);
	$tbl.='	</td>
	</tr>
	<tr>
		<td colspan="2">
			  - Vào khoa: ';
                        if($deptName){
                            $tbl.=$deptName;
                        }else{
                            $tbl.='........................................................................................................................................................';
                        }         
	$tbl.='	</td>
	</tr>
	<tr>
		<td colspan="2">
			  - Lý do vào viện: ';
                        if($status['lidovaovien']){
                            $tbl.=$status['lidovaovien'];
                        }else{
                            $tbl.='..............................................................................................................................................';
                        }          
	$tbl.='	</td>
	</tr>
	<tr>
		<td colspan="2">
			  - Tình trạng hiện tại: Tử vong';
//                        if($status['quatrinhbenhly']){
//                            $tbl.=$status['quatrinhbenhly'];
//                        }else{
//                            $tbl.='..........................................................................................................................................';
//                        } 
	$tbl.='	</td>
	</tr>
	<tr>
		<td colspan="2">
			  - Chẩn đoán: ';
                        if($status['referrer_diagnosis']){
                            $tbl.=$status['referrer_diagnosis'];
                        }else{
                            $tbl.='....................................................................................................................................................';
                        }                          
	$tbl.='	</td>
	</tr>
	<tr>
		<td colspan="2">
			
		</td>
	</tr>
	<tr>
		<td width="40%" align="center">
			<b>GIÁM ĐỐC</b>
		</td>
		<td align="center">
			<b>BÁC SỸ ĐIỀU TRỊ</b>
		</td>
	</tr>
        </table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->ln(20);
    $tbl1='<table cellpadding="3">
	<tr>
		<td width="40%" align="center">
                    ....................................
		</td>
		<td align="right">
                    .......................................................
		</td>
	</tr>
</table>';    
$pdf->writeHTML($tbl1, true, false, false, false, '');
//ob_clean();
$pdf->Output('giayxacnhanBNtuvong.pdf', 'I');		