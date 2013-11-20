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

# Get the encouter data
$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	//extract($encounter);
}

# Fetch insurance and encounter classes
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

require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj = new Ward();
$wardName = $ward_obj->getWardInfo($enc_obj->encounter['current_ward_nr']);

require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;
require_once($root_path.'include/care_api_classes/class_measurement.php');
$measurement_obj=new Measurement;
require_once($root_path.'include/care_api_classes/class_ecombill.php');
$ecombill_obj=new eComBill;
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");





$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$fpdf = new tFPDF('L','mm','a5');
$fpdf->AddPage();
$fpdf->SetTitle('Giay Cam Doan Phau Thuat');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(15);
$fpdf->SetLeftMargin(15);
$fpdf->SetTopMargin(20);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$fpdf->SetFont('DejaVu','',12);
$fpdf->Ln(); 
$fpdf->Cell(30,5,'Sở Y tế: Bình Dương',0,0,'L');
$fpdf->SetX(65);
$fpdf->Cell(80,5,'Cộng hòa xã hội chủ nghĩa Việt Nam',0,0,'C');
$fpdf->SetX(160);
$fpdf->Cell(0,5,'MS: 05/BV-99',0,0,'R');
$fpdf->Ln(); 

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin);

$fpdf->Cell(30,5,'BV:...........................',0,0,'L');
$fpdf->SetX(75);
$fpdf->Cell(60,5,'Độc lập - Tự do - Hạnh phúc',0,0,'C');
$fpdf->SetX(150);
$fpdf->Cell(30,5,'Số vào viện:......................',0,0,'L');
$fpdf->Ln(); 
$fpdf->Line(90,20,120,20);

$fpdf->Ln(); 

$fpdf->SetFont('DejaVu','B',18);
$fpdf->SetX($fpdf->lMargin);

$fpdf->Cell(0,8,'GIẤY CAM ĐOAN PHẨU THUẬT',0,0,'C');
$fpdf->Ln(); 
//Thông tin bệnh nhân
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(0,5,'- Tên tôi là:......................................................................................................... Tuổi:................ Nam/Nữ',0,1,'L');
$fpdf->Cell(0,5,'- Dân tộc:........................................................................ Ngoại kiều: .......................................................',0,1,'L');
$fpdf->Cell(0,5,'- Nghề nghiệp:................................................................ Nơi làm việc: .....................................................',0,1,'L');
$fpdf->Cell(0,5,'- Địa chỉ:.....................................................................................................................................................',0,1,'L');
$fpdf->Cell(0,5,'- Là người bệnh/đại diện gia đình người bệnh/họ tên là: ...........................................................................',0,1,'L');
$fpdf->Cell(0,5,'  hiện đang được điều trị tại Khoa: ............................. Bệnh viện..............................................................',0,1,'L');
$fpdf->Ln(2);
$fpdf->MultiCell(0,5,'  Sau khi nghe Bác sĩ cho biết tình trạng của tôi / người của gia đình tôi / những nguy hiểm của bệnh 
  nếu không phẫu thuật và những rủi ro có thể xảy ra khi phẩu thuật; tôi tự nguyện viết giấy cam 
  đoan này:',0,'L');


$x=$fpdf->GetX();$y=$fpdf->GetY();	
$fpdf->DrawRect($x+8,$y,4.5,5,1);
$fpdf->SetX(29);
$fpdf->Cell(0,5,'Đồng ý xin phẩu thuật và xin để giấy này làm bằng.',0,0,'L');
$fpdf->Ln(7);
$x=$fpdf->GetX();$y=$fpdf->GetY();	
$fpdf->DrawRect($x+8,$y,4.5,5,1);
$fpdf->SetX(29);
$fpdf->Cell(0,5,'Không đồng ý xin phẩu thuật và xin để giấy này làm bằng.',0,0,'L');
//Ký tên
$fpdf->Ln(4);
$fpdf->SetFont('DejaVu','I',11);
$fpdf->SetX($fpdf->lMargin+5);

$fpdf->SetX(130);
$fpdf->Cell(60,5,'Ngày........tháng........năm........',0,1,'R');
//
//$fpdf->Cell(60,5,'Ngày........tháng........năm........',0,1,'R');
//$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetX(130);
$fpdf->Cell(64,5,'NGƯỜI BỆNH / ĐẠI DIỆN GIA ĐÌNH',0,1,'C');
$fpdf->Ln(10);


$fpdf->Cell(30,5,'Hướng dẫn',0,1,'L');
$fpdf->SetFont('DejaVu','I',11);
$fpdf->Cell(80,5,'Đánh dấu vào ô thích hợp và xóa ô không thích hợp',0,1,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetY(116);
$fpdf->Cell(0,5,'Họ tên:                                             ',0,1,'R');
//$fpdf->Cell(0,25,' ',0,1,'C');
//$fpdf->SetFont('DejaVu','',11);
//$fpdf->SetX($fpdf->lMargin+5);
//$fpdf->Cell(60,5,'Họ tên:.....................................',0,0,'L');
//$fpdf->SetX(135);
//$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');





//$fpdf->Output();
$fpdf->Output('PhieuPhauThuat.pdf', 'I');


?>
