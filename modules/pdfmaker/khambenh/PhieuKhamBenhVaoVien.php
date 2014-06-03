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
$pres=$pres_obj->getAllPresOfEncounter($encounter['encounter_nr'],$encounter['dept_nr'],$encounter['ward_nr'],'');
//var_dump($pres);
//$med=$pres->FetchRow();
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");


require_once($root_path.'include/care_api_classes/class_khambenh.php');
$kb_obj= new Khambenh();
$kb_ck=$kb_obj->_getKhambenh('encounter_nr='.$enc.'','');
if($kb_ck){
$kb_ck_1=$kb_ck->fetchrow();
}


require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

$fpdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$fpdf->AddPage();
$fpdf->SetTitle('Phieu Kham Benh Vao Vien');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(10);
$fpdf->SetLeftMargin(10);
$fpdf->SetTopMargin(10);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)



$fpdf->SetFont('dejavusans','B',14);
$fpdf->Ln(); 
$fpdf->Cell(0,7,'PHIẾU KHÁM BỆNH VÀO VIỆN',0,0,'C');

$fpdf->SetFont('dejavusans','',10);
$fpdf->SetX(10);
$fpdf->Cell(140,5,'SỞ Y TẾ BÌNH DƯƠNG',0,0,'L');
$fpdf->Cell(50,5,"MS: 11/BV-99",0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(140,5,'TTYT TÂN UYÊN',0,0,'L');
$fpdf->Cell(50,5,'Số vào viện:'.$encounter['encounter_nr'],0,0,'L');
$fpdf->Ln(); 


$fpdf->SetFont('','',10);
$fpdf->SetX(10);
$kt=$deptName;
$s_obj=new exec_String();
$k=$s_obj->BASIC_String();	
$k=$s_obj->upper($kt);
$fpdf->Cell(0,5,"KHOA: ".$k,0,1,'C');
$fpdf->Ln();

//Thông tin bệnh nhân
$fpdf->SetFont('','B',10);
$fpdf->Cell(90,7,'I. HÀNH CHÍNH',0,0,'L');
$fpdf->SetFont('','',10);
$fpdf->Cell(0,5,'Tuổi',0,1,'R');


$str=$encounter['name_last']." ".$encounter['name_first'];	

$s=$s_obj->BASIC_String();	
$s=$s_obj->upper($str);
$namsinh=date("Y",strtotime($encounter['date_birth']));
$ngaysinh=date("d",strtotime($encounter['date_birth']));
$thangsinh=date("m",strtotime($encounter['date_birth']));


$y=$fpdf->GetY();
$fpdf->Cell(120,5,"1.Họ và Tên:(In hoa) ".$s."        2.Sinh ngày:",0,0,'L');
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,8);
$fpdf->DrawRect($x+58,$y,5,4.5,2);
if($encounter['tuoi']<10){
	if(strlen($encounter['date_birth'])>4){
$fpdf->Cell(0,5," ".substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),8,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),9,1)."                  ".$encounter['tuoi']." ",0,0,'L');
	}else{
	$fpdf->Cell(0,5,"                      ".substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),2,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."                 ".$encounter['tuoi']." ",0,0,'L');
	}
}
else{
	if(strlen($encounter['date_birth'])>4){
$fpdf->Cell(0,5," ".substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),8,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),9,1)."             ".substr($encounter['tuoi'],0,1)."   ".substr($encounter['tuoi'],1,1)." ",0,0,'L');
	}else{
	$fpdf->Cell(0,5,"                      ".substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),2,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."            ".substr($encounter['tuoi'],0,1)."   ".substr($encounter['tuoi'],1,1),0,0,'L');
	}
}
$fpdf->Ln();

$y=$fpdf->GetY();
$x=$fpdf->GetX();	
//echo $encounter['nghenghiepcode'];
if(!empty($encounter['nghenghiep'])){
	if($encounter['sex']=='m'){
		$fpdf->Cell(179,5,"3. Giới:         1.Nam  X           2.Nữ                         4.Nghề nghiệp: ".$encounter['nghenghiep'],0,0,'L');
		$fpdf->Cell(0,5,substr($encounter['nghenghiepcode'],0,1)."   ".substr($encounter['nghenghiepcode'],1,1),0,0,'L');
	}else{
		$fpdf->Cell(179,5,"3. Giới:         1.Nam              2.Nữ   X                      4.Nghề nghiệp: ".$encounter['nghenghiep'],0,0,'L');
		$fpdf->Cell(0,5,substr($encounter['nghenghiepcode'],0,1)."   ".substr($encounter['nghenghiepcode'],1,1),0,0,'L');
	}
} else{
	if($encounter['sex']=='m'){
		$fpdf->Cell(0,5,'3. Giới:         1.Nam  X           2.Nữ                         4.Nghề nghiệp:..................................................',0,1,'L');
	}else{
		$fpdf->Cell(0,5,'3. Giới:         1.Nam              2.Nữ   X                      4.Nghề nghiệp:..................................................',0,1,'L');
	}

}
$fpdf->DrawRect($x+36,$y,5,4.5,1);
$fpdf->DrawRect($x+61,$y,5,4.5,1);
$fpdf->DrawRect($x+178,$y,5,4.5,2);
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Ln();
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
if((!empty($encounter['addr_str_nr']))&&(!empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
	$fpdf->Cell(0,5,"7.".$LDAddress.":Số nhà ".$encounter['addr_str_nr']." Thôn/Phố ".$encounter['addr_str']." Xã/Phường ".$encounter['phuongxa_name'],0,1,'L');
}else if((empty($encounter['addr_str_nr']))&&(empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
	$fpdf->Cell(0,5,"7.".$LDAddress.":Số nhà .............. Thôn/Phố ".$encounter['addr_str']." Xã/Phường ",0,1,'L');
}else if((empty($encounter['addr_str_nr']))&&(!empty($encounter['phuongxa_name']))&&(empty($encounter['addr_str']))){
	$fpdf->Cell(0,5,"7.".$LDAddress.":Số nhà .............. Thôn/Phố .......................... Xã/Phường ".$encounter['phuongxa_name'],0,1,'L');
}else if((!empty($encounter['addr_str_nr']))&&(empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
	$fpdf->Cell(0,5,"7.".$LDAddress.":Số nhà ".$encounter['addr_str_nr']." Thôn/Phố ".$encounter['addr_str']." Xã/Phường .............",0,1,'L');
}else if((empty($encounter['addr_str_nr']))&&(!empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
	$fpdf->Cell(0,5,"7.".$LDAddress.":Số nhà .............. Thôn/Phố ".$encounter['addr_str']." Xã/Phường ".$encounter['phuongxa_name'],0,1,'L');
}
 else{
	$fpdf->Cell(0,5,'7. Địa chỉ: Số nhà:.............. Thôn, phố................................... Xã, phường........................................................',0,1,'L');
}

$y=$fpdf->GetY();
$x=$fpdf->GetX();
if((!empty($encounter['quanhuyen_name']))&&(!empty($encounter['citytown_name']))){
	$fpdf->Cell(0,5,"Huyện (Q,Tx) ".$encounter['quanhuyen_name']."                                        Tỉnh, thành phố ".$encounter['citytown_name'],0,1,'L');
}else if((!empty($encounter['quanhuyen_name']))&&(empty($encounter['citytown_name']))){
	$fpdf->Cell(0,5,"Huyện (Q,Tx) ".$encounter['quanhuyen_name']."                                        Tỉnh, thành phố.............................................. ",0,1,'L');
}else if((empty($encounter['quanhuyen_name']))&&(!empty($encounter['citytown_name']))){
	$fpdf->Cell(0,5,"Huyện (Q,Tx):.......................................                  Tỉnh, thành phố ".$encounter['citytown_name'],0,1,'L');
}else{
	$fpdf->Cell(0,5,'Huyện (Q,Tx):.......................................                  Tỉnh, thành phố................................................',0,1,'L');
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
if(($encounter['pinsurance_exp'])!='0000-00-00'){
$insexp=formatDate2STD($encounter['pinsurance_exp'],$date_format);
	$fpdf->Cell(129,5,"10. BHYT: giá trị đến "."Ngày ".date("d",strtotime($insexp))." tháng ".date("m",strtotime($insexp))." năm ".date("Y",strtotime($insexp))."  ".$LDInsuranceNr.":",0,0,'L');
}else{
	$fpdf->Cell(129,5,'10. BHYT: giá trị đến ngày.......tháng........năm...............    Số thẻ BHYT  ',0,0,'L');
}
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,10,4.5,4);
$fpdf->DrawRect($x+44,$y,18,4.5,1);
$fpdf->Cell(0,5,substr($encounter['pinsurance_nr'],0,2)."      ".substr($encounter['pinsurance_nr'],3,1).substr($encounter['pinsurance_nr'],5,2)."     ".substr($encounter['pinsurance_nr'],8,2)."     ".substr($encounter['pinsurance_nr'],11,3)."     ".substr($encounter['pinsurance_nr'],15,5),0,0,'L');
$fpdf->Ln();
if(!empty($encounter['hotenbaotin'])){
	$fpdf->Cell(0,5,"11. Họ tên, địa chỉ người nhà khi cần báo tin:"." Tên ".$encounter['hotenbaotin'],0,1,'L');
}else{
	$fpdf->Cell(0,5,'11. Họ tên, địa chỉ người nhà khi cần báo tin:.................................................................................................. ',0,1,'L');
}
if((!empty($encounter['dcbaotin']))&&(!empty($encounter['dtbaotin']))){
	$fpdf->Cell(0,5,"Địa chỉ ".$encounter['dcbaotin']." Điện thoại số"." ".$encounter['dtbaotin'],0,1,'L');
}else if((!empty($encounter['dcbaotin']))&&(empty($encounter['dtbaotin']))){
	$fpdf->Cell(0,5,"Địa chỉ ".$encounter['dcbaotin']."                         Điện thoại số................................................................. ",0,1,'L');
}else if((empty($encounter['dcbaotin']))&&(!empty($encounter['dtbaotin']))){
	$fpdf->Cell(0,5,"...................................................................................Điện thoại số ".$encounter['dtbaotin'],0,1,'L');
}else{
	$fpdf->Cell(0,5,'...................................................................................Điện thoại số................................................................. ',0,1,'L');
}
$ngayden=formatDate2Local($encounter['encounter_date'],$date_format);
$gioden=@convertTimeToLocal(formatDate2Local($encounter['encounter_date'],$date_format,0,1));
$fpdf->Cell(0,5,"12. Đến khám bệnh lúc: ".substr($gioden,0,2)." giờ ".substr($gioden,3,2)." phút........ngày ".substr($ngayden,0,2)." tháng ".substr($ngayden,3,2)." năm ".substr($ngayden,6,4),0,1,'L');
if(!empty($encounter['referrer_notes'])){
$fpdf->Cell(138,5,'13. Chẩn đoán của nơi giới thiệu:'.$encounter['referrer_notes'],0,0,'L');
}else{
$fpdf->Cell(138,5,'13. Chẩn đoán của nơi giới thiệu:...................................................................... ',0,0,'L');
}
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(40,5,'1. Y tế:             2.Tự đến',0,0,'L');
$fpdf->DrawRect($x+15,$y,5,4.5,1);
$fpdf->DrawRect($x+45,$y,5,4.5,1);
$fpdf->Ln();

//Lý do vào viện
$fpdf->SetFont('','B',10);
$fpdf->Cell(40,7,'II. LÝ DO VÀO VIỆN:',0,1,'L');
$fpdf->SetFont('','',10);
if(!empty($encounter['lidovaovien'])){
	$fpdf->Cell(0,7,$encounter['lidovaovien'],0,1,'L');
}else{
	$fpdf->Cell(0,7,'.....................................................................................................................................',0,1,'L');
}
//Hỏi bệnh
$fpdf->SetFont('','B',10);
$fpdf->Cell(0,7,'III. HỎI BỆNH:',0,1,'L');
$fpdf->Cell(41,5,'1. Quá trình bệnh lý:',0,1,'L');
$fpdf->SetFont('','',10);
if(!empty($encounter['quatrinhbenhly'])){
	$fpdf->MultiCell(0,5,$encounter['quatrinhbenhly'],0,'L');
} else{
	$fpdf->Cell(0,5,'....................................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
}
$fpdf->Ln();

$fpdf->SetFont('','B',10);
$fpdf->Cell(30,5,'2. Tiền sử bệnh:',0,1,'L');
$fpdf->SetFont('','',10);
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
$fpdf->SetFont('','B',10);
$fpdf->Cell(140,7,'IV.KHÁM BỆNH:',0,0,'L');
$y1=$fpdf->GetY()+2;
$x1=$fpdf->GetX();
$fpdf->Ln();
$fpdf->Cell(25,5,'1. Toàn thân:',0,1,'L');
$fpdf->SetFont('','',10);
if(!empty($kb_ck_1['toanthan_notes'])){
	$fpdf->MultiCell(70,5,$kb_ck_1['toanthan_notes'],0,'L');
}else{
	$fpdf->Cell(70,5,'.....................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
}
$fpdf->Ln();
$fpdf->SetFont('','B',10);
$fpdf->Cell(32,5,'2. Các bộ phận:',0,0,'L');
$fpdf->SetFont('','',10);
if(!empty($kb_ck_1['tongquat_bp'])){
	$fpdf->MultiCell(0,5,$kb_ck_1['tongquat_bp'],0,'L');
}else if($kb_ck_1['tuanhoan_notes'] || $kb_ck_1['hohap_notes'] || $kb_ck_1['tieuhoa_notes'] || $kb_ck_1['thantietnieusinhduc_notes'] || $kb_ck_1 ['khac_notes']){
	$fpdf->Ln();
	$fpdf->MultiCell(0,5,'Tuần hoàn '.$kb_ck_1['tuanhoan_notes'].', Hô hấp '.$kb_ck_1['hohap_notes'].', Tiêu hóa '.$kb_ck_1['tieuhoa_notes'].', Tiết niệu '.$kb_ck_1['thantietnieusinhduc_notes'].', Các bộ phận khác '.$kb_ck_1 ['khac_notes'],0,'L');
}else{
	$fpdf->Cell(0,5,'..............................................................................................',0,0,'L');

	$fpdf->Ln();
	$fpdf->SetFont('','',10);
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
	$fpdf->Ln();
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
	
	$fpdf->Ln();}

$fpdf->Ln();



//Kẻ ô lấy dấu sinh hiệu
$fpdf->SetY($y1);
$fpdf->SetX($x1);
$fpdf->MultiCell(0,5,"Mạch ".$measurement_obj->getMach($encounter['encounter_nr'])." lần/ph
Nhiệt độ ".$measurement_obj->getTemper($encounter['encounter_nr'])." °C
Huyết áp ".$measurement_obj->getBloodPressure($encounter['encounter_nr'])." mmHg
Nhịp thở ".$measurement_obj->getNhiptho($encounter['encounter_nr'])." lần/ph
Cân nặng ".$measurement_obj->getWeight($encounter['encounter_nr'])." kg",1,'L');
$sql="select reporting_dept from care_encounter_diagnostics_report where encounter_nr='$enc'";
//echo $sql;
$buf=$db->execute($sql);

$fpdf->SetFont('','B',10);
$fpdf->Ln();
$fpdf->Cell(65,5,'3. Xét nghiệm:',0,0,'L');
$fpdf->SetFont('','',10);
if($buf->recordcount()){
 while($temp=$buf->fetchrow()){
 $fpdf->Cell(0,5,$temp['reporting_dept'],0,0,'L');
 }
}else{
$fpdf->Cell(0,5,'..............................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','',10);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');

}

$fpdf->Ln();
$fpdf->SetFont('','B',10);
$fpdf->Cell(45,5,'4. Chẩn đoán ban đầu:',0,1,'L');
$fpdf->SetFont('','',10);
//echo $encounter['referrer_diagnosis'];
if(!empty($encounter['referrer_diagnosis'])){
$fpdf->MultiCell(0,5,$encounter['referrer_diagnosis'],0,'L');
}else{
$fpdf->Cell(0,5,'................................................................................................................................',0,0,'L');
}

$fpdf->SetFont('','B',10);
$fpdf->Cell(60,5,'5. Đã xử lý (thuốc, chăm sóc):',0,0,'L');

$fpdf->SetFont('','',10);
if(is_object($pres)){
while ($med=$pres->FetchRow()){
$fpdf->Cell(0,5,$med['product_name'],0,0,'L');
$fpdf->Ln();
}
}else{
$fpdf->Cell(0,5,'......................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','',10);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
}

$fpdf->Ln();
$fpdf->SetFont('','B',10);
$fpdf->Cell(60,5,'6. Cho vào điều trị tại khoa:',0,0,'L');
$fpdf->SetFont('','',10);
$fpdf->Cell(145,5,$k,0,0,'L');

$fpdf->Ln();
$fpdf->SetFont('','B',10);
$fpdf->Cell(20,5,'7. Chú ý: ',0,0,'L');
$fpdf->SetFont('','',10);
$fpdf->Cell(0,5,'................................................................................................................................................',0,0,'L');

$fpdf->Ln(10);



//Ký tên


$fpdf->SetFont('dejavusans','',10);
$fpdf->SetX(135);
$fpdf->Cell(60,5,"Ngày ".date("d")." Tháng ".date("m")." Năm ".date("Y"),0,1,'R');
$fpdf->SetFont('dejavusans','B',10);
//$fpdf->SetX(25);
//$fpdf->Cell(64,5,'Giám đốc bệnh viện',0,0,'L');
$fpdf->SetX(155);
$fpdf->Cell(64,5,'Bác sĩ khám bệnh',0,0,'L');
$fpdf->SetX(135);
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('dejavusans','',10);
//$fpdf->Cell(60,5,'Họ tên:.....................................',0,0,'L');
$fpdf->SetX(135);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,0,'L');

ob_clean();
//$fpdf->Output();
$fpdf->Output('PhieuKhamBenhVaoVien.pdf', 'I');


?>
