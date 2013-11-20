<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');

# Get the encouter data
$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	//extract($encounter);
}
$encounter_class=$enc_obj->getEncounterClassInfo($encounter['encounter_class_nr']);
$insurance_class=$enc_obj->getInsuranceClassInfo($encounter['insurance_class_nr']);


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
$current_dept_LDvar=$dept_obj->LDvar($encounter['current_dept_nr']);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);
require_once($root_path.'include/care_api_classes/class_measurement.php');
$measurement_obj=new Measurement;
require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj = new Ward();
$wardName = $ward_obj->getWardInfo($enc_obj->encounter['current_ward_nr']);
$roomName = $ward_obj->_getActiveRoomInfo($enc_obj->encounter['current_room_nr'],$enc_obj->encounter['current_ward_nr']);
$roomNumber = $enc_obj->encounter['current_room_nr'];
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
class exec_String {
var $lower = '
a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z
|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ
|đ
|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ
|í|ì|ỉ|ĩ|ị
|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ
|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự
|ý|ỳ|ỷ|ỹ|ỵ';
var $upper = '
A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ
|Đ
|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ
|Í|Ì|Ỉ|Ĩ|Ị
|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ
|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự
|Ý|Ỳ|Ỷ|Ỹ|Ỵ';
var $arrayUpper;
var $arrayLower;
function BASIC_String(){
$this->arrayUpper = explode('|',preg_replace("/\n|\t|\r/","",$this->upper));
$this->arrayLower = explode('|',preg_replace("/\n|\t|\r/","",$this->lower));
}

function lower($str){
return str_replace($this->arrayUpper,$this->arrayLower,$str);
}
function upper($str){
return str_replace($this->arrayLower,$this->arrayUpper,$str);
}
}
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(15, 8, 15);    

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);
    $pdf->AddPage();
// set font
$pdf->SetFont('dejavusans', 'B', 10);

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('dejavusans', '', 10);

// -----------------------------------------------------------------------------
$str=$encounter['name_last']." ".$encounter['name_first'];	
$s_obj=new exec_String();
$s=$s_obj->BASIC_String();	
$s=$s_obj->upper($str);

$tbl = '
<table cellspacing="0" cellpadding="0">
    <tr>
        <td align="left" width="20%">Sở y tế:...........
		<br>BV:...............</td>
        <td align="center" width="60%"><font size="15%">PHIẾU SƠ KẾT 15 NGÀY ĐIỀU TRỊ</font></td>
        <td>MS:18/BV-01
		<br>Số vòa viện:..........</td>
    </tr>
</table>
	<br><br><br>
<table cellspacing="0" cellpadding="0">
<tr><td >-Họ tên người bệnh:.'.str_pad("..".$s,80, ".", STR_PAD_RIGHT).'.Tuổi:.'.$encounter['tuoi'].'.';
if($encounter['sex']=='m'){
 $tbl.='Nam';
 }else{
 $tbl.='Nữ';
 }
 $tbl.='</td></tr>
<tr><td>-Địa chỉ:.'.str_pad("..".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'],120, ".", STR_PAD_RIGHT).'</td></tr>
<tr><td >-Khoa:.'.$deptName.'.....Buồng:.'.$roomName.'.Số lượng:..................</td></tr>
<tr><td >-Chẩn đoán:....................................................................................................................</td></tr>
<tr><td >1.Diễn biến lâm sàng trong đợt điều trị:...........................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>2.Xét nghiệm lâm sàng:..................................................................................................</td></tr>
<tr><td >.........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>3.Quá trình điều trị:...........................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>4.Đánh giá kết quả:............................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>5.Hướng điều trị tiếp và tiên lượng:...................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr><td>........................................................................................................................................</td></tr>
<tr>
<td width="30%" align="left">Ngày ......tháng..... năm.....</td>
<td width="70%" align="right">Ngày ......tháng..... năm.....</td>
</tr>
<tr>
<td width="25%" align="center">Trưởng khoa</td>
<td width="70%" align="center">Bác sĩ điều trị</td>
</tr><br><br><br>
<tr>
<td width="300px" align="left">Họ tên:........................</td>
<td width="150px" align="right">Họ tên:........................</td>
</tr>

</table>';

$pdf->writeHTML($tbl, true, false, false, false, '');




$pdf->Output('demo.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+