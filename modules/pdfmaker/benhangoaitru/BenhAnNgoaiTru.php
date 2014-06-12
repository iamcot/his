<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$report_textsize=12;
$report_titlesize=16;
$report_auxtitlesize=10;
$report_authorsize=10;
$sex ='';
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
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
# Get the encouter data
$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	//extract($encounter);
}

# Fetch insurance and encounter classes
$encounter_class=$enc_obj->getEncounterClassInfo($encounter['encounter_class_nr']);
$insurance_class=$enc_obj->getInsuranceClassInfo($encounter['pinsurance_class_nr']);

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

require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj = new Ward();
$wardName = $ward_obj->getWardInfo($enc_obj->encounter['current_ward_nr']);

require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;
require_once($root_path.'include/care_api_classes/class_measurement.php');
$measurement_obj=new Measurement;
require_once($root_path.'include/care_api_classes/class_ecombill.php');
$ecombill_obj=new eComBill;
require_once($root_path.'include/care_api_classes/class_prescription.php');
$pres_obj=new Prescription;
$pres=$pres_obj->getAllPresOfEncounterByBillId($encounter['encouter_nr'],1);

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");





$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$fpdf = new tFPDF('P','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Benh An Ngoai Tru');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(10);
$fpdf->SetLeftMargin(10);
$fpdf->SetTopMargin(15);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$fpdf->SetFont('DejaVu','B',18);
$fpdf->Ln(); 
$fpdf->Cell(0,7,'BỆNH ÁN NGOẠI TRÚ',0,0,'C');

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(140,5,'Sở Y tế:..................................',0,0,'L');
$fpdf->Cell(50,5,"Số ngoại trú:".$encounter['encounter_nr'],0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(140,5,'Bệnh viện:.............................',0,0,'L');
$fpdf->Cell(50,5,'Số lưu trữ:...........................',0,0,'L');
$fpdf->Ln(); 


$fpdf->SetFont('','',11);
$fpdf->SetX($fpdf->lMargin);
$kt=$deptName;
$s_obj=new exec_String();
$k=$s_obj->BASIC_String();	
$k=$s_obj->upper($kt);
$fpdf->Cell(0,5,"KHOA: ".$k,0,1,'C');
$fpdf->Ln();

//Thông tin bệnh nhân
$fpdf->SetFont('','B',11);
$fpdf->Cell(90,7,'I. HÀNH CHÍNH',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'Tuổi',0,1,'R');


$str=$encounter['name_last']." ".$encounter['name_first'];	

$s=$s_obj->BASIC_String();	
$s=$s_obj->upper($str);

if($encounter['thang']>0){
    $namsinh=date("Y",strtotime($encounter['date_birth']));
} else {
    $namsinh=    $encounter['date_birth'];
}
$tuoi=date("Y")-$namsinh;


$y=$fpdf->GetY();
$fpdf->Cell(120,5,"1.Họ và Tên:(In hoa) ".$s."        2.Sinh ngày:",0,0,'L');
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,8);
$fpdf->DrawRect($x+58,$y,5,4.5,2);

if($tuoi<10){
$fpdf->Cell(0,5," ".substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),8,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),9,1)."                  ".$tuoi." ",0,0,'L');
}
else{
$fpdf->Cell(0,5," ".substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),2,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),5,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."                     ".substr($tuoi,0,1)."   ".substr($tuoi,1,1)." ",0,0,'L');
}
$fpdf->Ln();

$y=$fpdf->GetY();
$x=$fpdf->GetX();	
if(!empty($encounter['nghenghiep'])){
	if($encounter['sex']=='m'){
		$fpdf->Cell(0,5,"3. Giới:         1.Nam  X            2.Nữ                         4.Nghề nghiệp: ".$encounter['nghenghiep'],0,1,'L');
	}else{
		$fpdf->Cell(0,5,"3. Giới:         1.Nam              2.Nữ   X                      4.Nghề nghiệp: ".$encounter['nghenghiep'],0,1,'L');
	}
} else{
	if($encounter['sex']=='m'){
		$fpdf->Cell(0,5,'3. Giới:         1.Nam  X            2.Nữ                         4.Nghề nghiệp:..................................................',0,1,'L');
	}else{
		$fpdf->Cell(0,5,'3. Giới:         1.Nam              2.Nữ   X                      4.Nghề nghiệp:..................................................',0,1,'L');
	}

}
$fpdf->DrawRect($x+36,$y,5,4.5,1);
$fpdf->DrawRect($x+61,$y,5,4.5,1);
$fpdf->DrawRect($x+178,$y,5,4.5,2);
$y=$fpdf->GetY();
$x=$fpdf->GetX();
if((!empty($encounter['dantoc']))&&(!empty($encounter['ngoaikieu']))){	
	$fpdf->Cell(0,5,"5." .$LDEthnicOrigin.":".$encounter['dantoc']                  ."                                                          6. Ngoại kiều:"." ".$encounter['ngoaikieu'],0,1,'L');
}else if((!empty($encounter['dantoc']))&&(empty($encounter['ngoaikieu']))){
	$fpdf->Cell(0,5,"5." .$LDEthnicOrigin.":".$encounter['dantoc']                  ."                                                          6. Ngoại kiều:.........................................",0,1,'L');
}else if((empty($encounter['dantoc']))&&(!empty($encounter['ngoaikieu']))){
	$fpdf->Cell(0,5,"5." .$LDEthnicOrigin.":".$encounter['dantoc']                  ."                                                          6. Ngoại kiều:"." ".$encounter['ngoaikieu'],0,1,'L');
}else{
	$fpdf->Cell(0,5,'5. Dân tộc:............................................                  6. Ngoại kiều:....................................................',0,1,'L');
}
$fpdf->DrawRect($x+70,$y,5,4.5,2);
$fpdf->DrawRect($x+178,$y,5,4.5,2);
if(!empty($encounter['addr_str_nr'])){
	$fpdf->Cell(0,5,"7.".$LDAddress.":Số nhà ".$encounter['addr_str_nr']." Thôn/Phố ".$encounter['addr_str']." Xã/Phường ".$encounter['phuongxa_name'],0,1,'L');
} else{
	$fpdf->Cell(0,5,'7. Địa chỉ: Số nhà:.............. Thôn, phố................................... Xã, phường........................................................',0,1,'L');
}

$y=$fpdf->GetY();
$x=$fpdf->GetX();
if((!empty($encounter['quanhuyen_name']))&&(!empty($encounter['citytown_name']))){
	$fpdf->Cell(0,5,"Huyện (Q,Tx): ".$encounter['quanhuyen_name']."                                           Tỉnh, thành phố: ".$encounter['citytown_name'],0,1,'L');
}else if((!empty($encounter['quanhuyen_name']))&&(empty($encounter['citytown_name']))){
	$fpdf->Cell(0,5,"Huyện (Q,Tx): ".$encounter['quanhuyen_name']."                                           Tỉnh, thành phố:.............................................. ",0,1,'L');
}else if((empty($encounter['quanhuyen_name']))&&(!empty($encounter['citytown_name']))){
	$fpdf->Cell(0,5,"Huyện (Q,Tx):.......................................                                    Tỉnh, thành phố: ".$encounter['citytown_name'],0,1,'L');
}else{
	$fpdf->Cell(0,5,'Huyện (Q,Tx):.......................................                                    Tỉnh, thành phố:................................................',0,1,'L');
}
$fpdf->DrawRect($x+70,$y,5,4.5,2);
$fpdf->DrawRect($x+172,$y,5,4.5,3);
$y=$fpdf->GetY();
$x=$fpdf->GetX();
if(!empty($encounter['noilamviec'])){
	$fpdf->Cell(79,5,"8. Nơi làm việc: ".$encounter['noilamviec'],0,0,'L');
}else{
	$fpdf->Cell(79,5,'8. Nơi làm việc:..............................................',0,0,'L');
}
if($insclass=='BHYT'){
$fpdf->DrawRect($x+116,$y,5,4.5,1);
$fpdf->DrawRect($x+142,$y,5,4.5,1);
$fpdf->DrawRect($x+163,$y,5,4.5,1);
$fpdf->DrawRect($x+184,$y,5,4.5,1);
$fpdf->Cell(0,5,"9.Đối tượng: 1.BHYT  X   2.Thu phí        3.Miễn        4.Khác",0,0,'L');
}
else if($insclass=='Thu Phi'){
$fpdf->DrawRect($x+116,$y,5,4.5,1);
$fpdf->DrawRect($x+142,$y,5,4.5,1);
$fpdf->DrawRect($x+163,$y,5,4.5,1);
$fpdf->DrawRect($x+184,$y,5,4.5,1);
$fpdf->Cell(0,5,"9.Đối tượng: 1.BHYT      2.Thu phí    X     3.Miễn        4.Khác",0,0,'L');
}else if($insclass=='Mien Phi'){
$fpdf->DrawRect($x+116,$y,5,4.5,1);
$fpdf->DrawRect($x+142,$y,5,4.5,1);
$fpdf->DrawRect($x+163,$y,5,4.5,1);
$fpdf->DrawRect($x+184,$y,5,4.5,1);
$fpdf->Cell(0,5,"9.Đối tượng: 1.BHYT       2.Thu phí          3.Miễn  X    4.Khác",0,0,'L');
}else if($insclass=='Khac'){
$fpdf->DrawRect($x+116,$y,5,4.5,1);
$fpdf->DrawRect($x+142,$y,5,4.5,1);
$fpdf->DrawRect($x+163,$y,5,4.5,1);
$fpdf->DrawRect($x+184,$y,5,4.5,1);
$fpdf->Cell(0,5,"9.Đối tượng: 1.BHYT      2.Thu phí        3.Miễn       4.Khác  X",0,0,'L');
}
$fpdf->Ln();
if(!empty($encounter['insurance_exp'])){
$insexp=formatDate2STD($encounter['pinsurance_exp'],$date_format);
	$fpdf->Cell(129,5,"10. BHYT: giá trị đến "."Ngày ".date("d",strtotime($insexp))." tháng ".date("m",strtotime($insexp))." năm ".date("Y",strtotime($insexp))."  ".$LDInsuranceNr.":",0,0,'L');
}else{
	$fpdf->Cell(129,5,'10. BHYT: giá trị đến ngày.......tháng........năm...............    Số thẻ BHYT  ',0,0,'L');
}
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,10,4.5,4);
$fpdf->DrawRect($x+44,$y,18,4.5,1);
$fpdf->Cell(0,5,substr($encounter['pinsurance_nr'],0,2)."      ".substr($encounter['pinsurance_nr'],3,1)."         ".substr($encounter['pinsurance_nr'],5,2)."      ".substr($encounter['pinsurance_nr'],8,2)."   ".substr($encounter['pinsurance_nr'],11,3).substr($encounter['pinsurance_nr'],15,5),0,0,'L');
$fpdf->Ln();
if(!empty($encounter['hotenbaotin'])){
	$fpdf->Cell(0,5,"11. Họ tên, địa chỉ người nhà khi cần báo tin: ".$encounter['hotenbaotin'],0,1,'L');
}else{
	$fpdf->Cell(0,5,'11. Họ tên, địa chỉ người nhà khi cần báo tin:.................................................................................................. ',0,1,'L');
}
if((!empty($encounter['dcbaotin']))&&(!empty($encounter['dtbaotin']))){
	$fpdf->Cell(0,5,"Địa chỉ: ".$encounter['dcbaotin']." Điện thoại số"." ".$encounter['dtbaotin'],0,1,'L');
}else if((!empty($encounter['dcbaotin']))&&(empty($encounter['dtbaotin']))){
	$fpdf->Cell(0,5,"Địa chỉ: ".$encounter['dcbaotin']."                         Điện thoại số................................................................. ",0,1,'L');
}else if((empty($encounter['dcbaotin']))&&(!empty($encounter['dtbaotin']))){
	$fpdf->Cell(0,5,"Địa chỉ:...................................................................................................         Điện thoại số: ".$encounter['dtbaotin'],0,1,'L');
}else{
	$fpdf->Cell(0,5,'Địa chỉ: ...........................................................................   Điện thoại số: ................................................................. ',0,1,'L');
}
$ngayden=formatDate2Local($encounter['encounter_date'],$date_format);
$gioden=formatDate2Local($encounter['encounter_date'],$date_format,TRUE,TRUE);
$fpdf->Cell(0,5,"12. Đến khám bệnh lúc: ".date("H",strtotime($gioden))." giờ ".date("m",strtotime($gioden))." phút, ngày ".substr($ngayden,0,2)." tháng ".substr($ngayden,3,2)." năm ".substr($ngayden,6,4),0,1,'L');
$fpdf->Cell(138,5,'13. Chẩn đoán của nơi giới thiệu:...................................................................... ',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(40,5,'1. Y tế:             2.Tự đến',0,0,'L');
$fpdf->DrawRect($x+15,$y,5,4.5,1);
$fpdf->DrawRect($x+45,$y,5,4.5,1);
$fpdf->Ln();

//Lý do vào viện
$fpdf->SetFont('','B',11);
$fpdf->Cell(40,7,'II. LÝ DO VÀO VIỆN:',0,0,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['lidovaovien'])){
	$fpdf->Cell(0,7,$encounter['lidovaovien'],0,1,'L');
}else{
	$fpdf->Cell(0,7,'.....................................................................................................................................',0,1,'L');
}
//Hỏi bệnh
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,7,'III. HỎI BỆNH:',0,1,'L');
$fpdf->Cell(41,5,'1. Quá trình bệnh lý:',0,0,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['quatrinhbenhly'])){
	$fpdf->MultiCell(0,5,"                   "
.$encounter['quatrinhbenhly']
,0,'L');
} else{
	$fpdf->Cell(0,5,'....................................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
}
$fpdf->Ln();

$fpdf->SetFont('','B',11);
$fpdf->Cell(30,5,'2. Tiền sử bệnh:',0,1,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(20,5,'+ Bản thân:',0,0,'L');
if(!empty($encounter['tiensubenhcanhan'])){
	$fpdf->Cell(0,5," ".$encounter['tiensubenhcanhan'],0,0,'L');
}else{
	$fpdf->Cell(0,5,'.......................................................................................................................................................',0,0,'L');
}
$fpdf->Ln();
$fpdf->Cell(20,5,'+ Gia đình:',0,0,'L');
if(!empty($encounter['tiensubenhgiadinh'])){
	$fpdf->Cell(0,5," ".$encounter['tiensubenhgiadinh'],0,0,'L');
}else{
	$fpdf->Cell(0,5,'.......................................................................................................................................................',0,0,'L');
}
$fpdf->Ln();

//Khám bệnh
$fpdf->SetFont('','B',11);
$fpdf->Cell(140,7,'IV.KHÁM BỆNH:',0,0,'L');
$y1=$fpdf->GetY()+2;
$x1=$fpdf->GetX();
$fpdf->Ln();
$fpdf->Cell(25,5,'1. Toàn thân:',0,0,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['khambenhtoanthan'])){
	$fpdf->MultiCell(70,5,$encounter['khambenhtoanthan'],0,'L');
}else{
	$fpdf->Cell(70,5,'.....................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
}
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(32,5,'2. Các bộ phận:',0,0,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['khambenhbophan'])){
	$fpdf->Cell(0,5,$encounter['khambenhbophan'],0,0,'L');
}else{
	$fpdf->Cell(0,5,'..............................................................................................',0,0,'L');

	$fpdf->Ln();
	$fpdf->SetFont('','',11);
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
}
$fpdf->DrawRect($x+110,$y,5,20,1);
$fpdf->Ln();



//Kẻ ô lấy dấu sinh hiệu
$fpdf->SetY($y1);
$fpdf->SetX($x1);
$fpdf->MultiCell(0,5,"Mạch ".$measurement_obj->getMach($encounter['encounter_nr'])." lần/ph
Nhiệt độ ".$measurement_obj->getTemper($encounter['encounter_nr'])." °C
Huyết áp ".$measurement_obj->getBloodPressure($encounter['encounter_nr'])." mmHg
Nhịp thở ".$measurement_obj->getNhiptho($encounter['encounter_nr'])." lần/ph
Cân nặng ".$measurement_obj->getWeight($encounter['encounter_nr'])." kg",1,'L');
$fpdf->Ln(15);



$fpdf->SetFont('','B',11);
$fpdf->Cell(65,5,'3. Tóm tắt kết quả cận lâm sàng:',0,0,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['ketquacanlamsang'])){
	$fpdf->MultiCell(0,5,$encounter['ketquacanlamsang'],0,'L');
}else{
	$fpdf->Cell(0,5,'..............................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->SetFont('','',11);
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
}
$fpdf->Ln();
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(45,5,'4. Chẩn đoán ban đầu:',0,1,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['referrer_diagnosis'])){
$fpdf->Cell(0,5,$encounter['referrer_diagnosis'],0,0,'L');
}else{
$fpdf->Cell(0,5,'................................................................................................................................',0,0,'L');
}
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(23,5,'5. Đã xử lý:',0,0,'L');
$fpdf->SetFont('','I',11);
$fpdf->Cell(33,5,'(thuốc, chăm sóc)',0,0,'L');
if(is_object($pers)){
while ($med=$pres->FetchRow()){
$fpdf->Cell(30,5,$med['product_name'],0,0,'L');
$fpdf->Ln();
}
}else{
$fpdf->Cell(0,5,'......................................................................................................................',0,0,'L');
$fpdf->Ln();
}
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(50,5,'6. Chẩn đoán khi ra viện:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(115,5,'..............................................................................................  Mã',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,4);
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(58,5,'7. Điều trị ngoại trú từ ngày: ',0,0,'L');
$fpdf->SetFont('','',11);
$today=gmdate("d/m/Y", time());
if(!empty($encounter['encounter_date'])){
$fpdf->Cell(0,5,formatDate2Local($encounter['encounter_date'],$date_format)." đến ngày"." ".$today,0,0,'L');
}else{
$fpdf->Cell(0,5,'........./.........../.......... đến ngày ........../.........../..........',0,0,'L');
}
$fpdf->Ln(10);



//Ký tên

$x=$fpdf->GetX();$y=$fpdf->GetY();
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,"Ngày ".date("d")." Tháng ".date("m")." Năm ".date("Y"),0,1,'R');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetX(135);
$fpdf->Cell(64,5,'Bác sĩ khám bệnh',0,1,'C');
$fpdf->SetX(135);
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');

$fpdf->SetY($y);
$fpdf->Cell(64,5,' ',0,1,'C');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(64,5,'Giám đốc bệnh viện',0,1,'C');
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');


//-----------------------Page2-------------------------------------
$fpdf->AddPage();
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,10,'TỔNG KẾT BỆNH ÁN',0,1,'L');
$fpdf->Cell(0,5,'1. Quá trình bệnh lý và diễn biến lâm sàng:',0,1,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['quatrinhbenhly'])){
	$fpdf->MultiCell(0,5,$encounter['quatrinhbenhly'],0,'L');
}else{
	for($i=0;$i<9;$i++)
	{
		$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
	}
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'2. Tóm tắt kết quả xét nghiệm cận lâm sàng có giá trị chẩn đoán:',0,1,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['ketquacanlamsang'])){
	$fpdf->MultiCell(0,5,$encounter['ketquacanlamsang'],0,'L');
}else{
	for($i=0;$i<5;$i++)
	{
		$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
	}
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'3. Chẩn đoán ra viện:',0,1,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['benhchinh'])){
$fpdf->Cell(165,5,"- Bệnh chính: ".$encounter['benhchinh'],0,0,'L');
}else{
$fpdf->Cell(165,5,'- Bệnh chính:..............................................................................................................................',0,0,'L');
}
$y=$fpdf->GetY();
$x=$fpdf->GetX();

$fpdf->Ln();
if(!empty($encounter['benhphu'])){
$fpdf->Cell(165,5,"- Bệnh kèm theo (nếu có): ".$encounter['benhphu'],0,0,'L');
}else{
$fpdf->Cell(165,5,'- Bệnh kèm theo (nếu có):.........................................................................................................',0,0,'L');
}
$y=$fpdf->GetY();
$x=$fpdf->GetX();

$fpdf->Ln();

$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'4. Phương pháp điều trị:',0,1,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['referrer_recom_therapy'])){
	$fpdf->MultiCell(0,5,$encounter['referrer_recom_therapy'],0,'L');
}else{
	for($i=0;$i<5;$i++)
	{
		$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
	}
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'5. Tình trạng người bệnh ra viện:',0,1,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['tinhtrangravien'])){
$fpdf->MultiCell(0,5,$encounter['tinhtrangravien'],0,'L');
}else{
	for($i=0;$i<5;$i++)
	{
		$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
	}
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'6. Hướng điều trị và các chế độ tiếp theo:',0,1,'L');
$fpdf->SetFont('','',11);
if(!empty($encounter['huongdieutritiep'])){
$fpdf->MultiCell(0,5,$encounter['huongdieutritiep'],0,'L');
}else{
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}
}
$fpdf->Ln();

$y=$fpdf->GetY();
$fpdf->Cell(75,6,'Hồ sơ, phim, ảnh',1,0,'C');
$x=$fpdf->GetX();
$fpdf->Ln();
$fpdf->Cell(45,6,'Loại',1,0,'C');
$x1=$fpdf->GetX();
$fpdf->Cell(30,6,'Số tờ',1,1,'C');
$y1=$fpdf->GetY();
$fpdf->MultiCell(45,6,"- X-Quang\n- CT Scanner\n- Siêu âm\n- Xét nghiệm\n- Khác\n- Toàn bộ hồ sơ",1,'L');
$fpdf->SetY($y1);$fpdf->SetX($x1);
$fpdf->Rect($x1,$y1,30,36);
$fpdf->SetY($y);$fpdf->SetX($x);
$fpdf->MultiCell(55,6,"Người giao hồ sơ\n\n\nHọ tên:............................",1,'C');
$fpdf->SetX($x);
$fpdf->MultiCell(55,6,"Người nhận hồ sơ\n\n\nHọ tên:............................",1,'C');
$fpdf->SetY($y);$fpdf->SetX($x+55);
$fpdf->MultiCell(58,6,"Ngày.......tháng.......năm............\nBác sĩ điều trị\n\n\n\n\n\nHọ tên:............................",1,'C');




//$fpdf->Output();
$fpdf->Output('BenhAnNgoaiTru.pdf', 'I');
/*
$fpdf = new tFPDF('P','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Benh An Ngoai Tru');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(10);
$fpdf->SetLeftMargin(10);
$fpdf->SetTopMargin(15);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$fpdf->SetFont('DejaVu','B',18);
$fpdf->Ln(); 
$fpdf->Cell(0,7,'BỆNH ÁN NGOẠI TRÚ',0,0,'C');

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(140,5,'Sở Y tế:..................................',0,0,'L');
$fpdf->Cell(50,5,'Số ngoại trú:.......................',0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(140,5,'Bệnh viện:.............................',0,0,'L');
$fpdf->Cell(50,5,'Số lưu trữ:...........................',0,0,'L');
$fpdf->Ln(); 


$fpdf->SetFont('','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(0,5,'KHOA: .................................................',0,1,'C');
$fpdf->Ln();

//Thông tin bệnh nhân
$fpdf->SetFont('','B',11);
$fpdf->Cell(90,7,'I. HÀNH CHÍNH',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'Tuổi',0,1,'R');

$y=$fpdf->GetY();		
$fpdf->Cell(120,5,'1. Họ và tên: (In hoa)............................................. 2. Sinh ngày',0,0,'L');
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,8);
$fpdf->DrawRect($x+58,$y,5,4.5,2);
$fpdf->Ln();

$y=$fpdf->GetY();
$x=$fpdf->GetX();	
$fpdf->Cell(0,5,'3. Giới:         1.Nam               2.Nữ                          4. Nghề nghiệp:......................................................',0,1,'L');
$fpdf->DrawRect($x+36,$y,5,4.5,1);
$fpdf->DrawRect($x+61,$y,5,4.5,1);
$fpdf->DrawRect($x+178,$y,5,4.5,2);

$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(0,5,'5. Dân tộc:............................................                  6. Ngoại kiều:.........................................................',0,1,'L');
$fpdf->DrawRect($x+70,$y,5,4.5,2);
$fpdf->DrawRect($x+178,$y,5,4.5,2);

$fpdf->Cell(0,5,'7. Địa chỉ: Số nhà:.............. Thôn, phố................................... Xã, phường........................................................',0,1,'L');

$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(0,5,'Huyện (Q,Tx):.......................................                  Tỉnh, thành phố................................................',0,1,'L');
$fpdf->DrawRect($x+70,$y,5,4.5,2);
$fpdf->DrawRect($x+172,$y,5,4.5,3);

$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(0,5,'8. Nơi làm việc:............................................. 9.Đối tượng: 1.BHYT         2.Thu phí         3.Miễn        4.Khác',0,0,'L');
$fpdf->DrawRect($x+116,$y,5,4.5,1);
$fpdf->DrawRect($x+142,$y,5,4.5,1);
$fpdf->DrawRect($x+163,$y,5,4.5,1);
$fpdf->DrawRect($x+184,$y,5,4.5,1);
$fpdf->Ln();

$fpdf->Cell(129,5,'10. BHYT: giá trị đến ngày.......tháng........năm...............    Số thẻ BHYT  ',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,10,4.5,4);
$fpdf->DrawRect($x+44,$y,16,4.5,1);
$fpdf->Ln();
$fpdf->Cell(0,5,'11. Họ tên, địa chỉ người nhà khi cần báo tin:.................................................................................................. ',0,1,'L');
$fpdf->Cell(0,5,'...................................................................................Điện thoại số................................................................. ',0,1,'L');
$fpdf->Cell(0,5,'12. Đến khám bệnh lúc:..............giờ.............phút.............ngày.............tháng.............năm................ ',0,1,'L');
$fpdf->Cell(138,5,'13. Chẩn đoán của nơi giới thiệu:...................................................................... ',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(40,5,'1. Y tế:             2.Tự đến',0,0,'L');
$fpdf->DrawRect($x+15,$y,5,4.5,1);
$fpdf->DrawRect($x+45,$y,5,4.5,1);
$fpdf->Ln();

//Lý do vào viện
$fpdf->SetFont('','B',11);
$fpdf->Cell(40,7,'II. LÝ DO VÀO VIỆN:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,7,'.....................................................................................................................................',0,1,'L');

//Hỏi bệnh
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,7,'III. HỎI BỆNH:',0,1,'L');
$fpdf->Cell(41,5,'1. Quá trình bệnh lý:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'....................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();

$fpdf->SetFont('','B',11);
$fpdf->Cell(30,5,'2. Tiền sử bệnh:',0,1,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(20,5,'+ Bản thân:',0,0,'L');
$fpdf->Cell(0,5,'.......................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(20,5,'+ Gia đình:',0,0,'L');
$fpdf->Cell(0,5,'.......................................................................................................................................................',0,0,'L');
$fpdf->Ln();

//Khám bệnh
$fpdf->SetFont('','B',11);
$fpdf->Cell(140,7,'IV.KHÁM BỆNH:',0,0,'L');
$y1=$fpdf->GetY()+2;
$x1=$fpdf->GetX();
$fpdf->Ln();
$fpdf->Cell(25,5,'1. Toàn thân:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(70,5,'.....................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(32,5,'2. Các bộ phận:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'..............................................................................................',0,0,'L');

$fpdf->Ln();
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->DrawRect($x+110,$y,5,20,1);
$fpdf->Ln();



//Kẻ ô lấy dấu sinh hiệu
$fpdf->SetY($y1);
$fpdf->SetX($x1);
$fpdf->MultiCell(0,5,"Mạch........................lần/ph
Nhiệt độ.........................°C
Huyết áp......./.........mmHg
Nhịp thở...................lần/ph
Cân nặng.......................kg",1,'R');
$fpdf->Ln(15);



$fpdf->SetFont('','B',11);
$fpdf->Cell(65,5,'3. Tóm tắt kết quả cận lâm sàng:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'..............................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(45,5,'4. Chẩn đoán ban đầu:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(23,5,'5. Đã xử lý:',0,0,'L');
$fpdf->SetFont('','I',11);
$fpdf->Cell(33,5,'(thuốc, chăm sóc)',0,0,'L');
$fpdf->Cell(0,5,'......................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(50,5,'6. Chẩn đoán khi ra viện:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(115,5,'..............................................................................................  Mã',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,4);
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(58,5,'7. Điều trị ngoại trú từ ngày: ',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'........./.........../.......... đến ngày ........../.........../..........',0,0,'L');
$fpdf->Ln(10);



//Ký tên

$x=$fpdf->GetX();$y=$fpdf->GetY();
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,'Ngày........tháng........năm........',0,1,'R');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetX(135);
$fpdf->Cell(64,5,'Bác sĩ khám bệnh',0,1,'C');
$fpdf->SetX(135);
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');

$fpdf->SetY($y);
$fpdf->Cell(64,5,' ',0,1,'C');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(64,5,'Giám đốc bệnh viện',0,1,'C');
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');


//-----------------------Page2-------------------------------------
$fpdf->AddPage();
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,10,'TỔNG KẾT BỆNH ÁN',0,1,'L');
$fpdf->Cell(0,5,'1. Quá trình bệnh lý và diễn biến lâm sàng:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<9;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'2. Tóm tắt kết quả xét nghiệm cận lâm sàng có giá trị chẩn đoán:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'3. Chẩn đoán ra viện:',0,1,'L');
$fpdf->SetFont('','',11);

$fpdf->Cell(165,5,'- Bệnh chính:..............................................................................................................................',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,4);
$fpdf->Ln();

$fpdf->Cell(165,5,'- Bệnh kèm theo (nếu có):.........................................................................................................',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,4);
$fpdf->Ln();

$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'4. Phương pháp điều trị:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}

$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'5. Tình trạng người bệnh ra viện:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}

$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'6. Hướng điều trị và các chế độ tiếp theo:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}
$fpdf->Ln();

$y=$fpdf->GetY();
$fpdf->Cell(75,6,'Hồ sơ, phim, ảnh',1,0,'C');
$x=$fpdf->GetX();
$fpdf->Ln();
$fpdf->Cell(45,6,'Loại',1,0,'C');
$x1=$fpdf->GetX();
$fpdf->Cell(30,6,'Số tờ',1,1,'C');
$y1=$fpdf->GetY();
$fpdf->MultiCell(45,6,"- X-Quang\n- CT Scanner\n- Siêu âm\n- Xét nghiệm\n- Khác\n- Toàn bộ hồ sơ",1,'L');
$fpdf->SetY($y1);$fpdf->SetX($x1);
$fpdf->Rect($x1,$y1,30,36);
$fpdf->SetY($y);$fpdf->SetX($x);
$fpdf->MultiCell(55,6,"Người giao hồ sơ\n\n\nHọ tên:............................",1,'C');
$fpdf->SetX($x);
$fpdf->MultiCell(55,6,"Người nhận hồ sơ\n\n\nHọ tên:............................",1,'C');
$fpdf->SetY($y);$fpdf->SetX($x+55);
$fpdf->MultiCell(58,6,"Ngày.......tháng.......năm............\nBác sĩ điều trị\n\n\n\n\n\nHọ tên:............................",1,'C');




//$fpdf->Output();
$fpdf->Output('BenhAnNgoaiTru.pdf', 'I');
*/

?>
