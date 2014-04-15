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
if($encounter['encounter_class_nr']==1){
	# Get ward name
	include_once($root_path.'include/care_api_classes/class_ward.php');
	$ward_obj=new Ward;
	$current_ward_name=$ward_obj->WardName($encounter['current_ward_nr']);
}elseif($encounter['encounter_class_nr']==2){
	# Get ward name
	include_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj=new Department;
	//$current_dept_name=$dept_obj->FormalName($current_dept_nr);
	$current_dept_LDvar=$dept_obj->LDvar($encounter['current_dept_nr']);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $current_dept_name=$$current_dept_LDvar;
		else $current_dept_name=$dept_obj->FormalName($encounter['current_dept_nr']);
}

require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');


$tpdf = new tFPDF('P','mm','a4');
$tpdf->AddPage();
$tpdf->SetTitle('Giay chung nhan thuong tich');
$tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$tpdf->SetRightMargin(15);
$tpdf->SetLeftMargin(15);
$tpdf->SetTopMargin(20);
$tpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$tpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$tpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$tpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$tpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$tpdf->SetFont('DejaVu','',11);
$tpdf->Ln(); 
$y=$tpdf->GetY();
$tpdf->Cell(0,7,'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM',0,1,'C');
$tpdf->Cell(0,6,'Độc lập - Tự Do - Hạnh Phúc',0,1,'C');
$tpdf->Cell(0,6,'----------------------',0,1,'C');

$tpdf->SetFont('DejaVu','',11);
//$tpdf->SetX($tpdf->lMargin);
$tpdf->SetY($y);
$tpdf->Cell(145,5,'Sở Y tế: Bình Dương',0,0,'L');
$tpdf->Cell(50,5,'MS: 19/BV-01',0,0,'L');
$tpdf->Ln(); 
$tpdf->Cell(145,5,PDF_HOSNAME,0,0,'L');
$tpdf->Cell(20,5,"Số vào viện: ".$encounter['encounter_nr'],0,1,'L');
$tpdf->Cell(150,5,'Số:          /CN',0,1,'L');

$tpdf->Ln(); 
$tpdf->SetFont('DejaVu','B',18);
$tpdf->Ln(); 
$tpdf->Cell(0,7,'GIẤY CHỨNG NHẬN THƯƠNG TÍCH',0,0,'C');
$tpdf->Ln(15);

//Thông tin bệnh nhân
$tpdf->SetFont('','B',11);
$tpdf->Cell(45,6,'GIÁM ĐỐC BỆNH VIỆN:',0,0,'L');
$tpdf->SetFont('','');
$tpdf->Cell(70,6,'...............................................................',0,0,'L');
$tpdf->Cell(0,6,'Chứng nhận:',0,1,'L');
if($encounter['sex']=='m'){
	$danhxung=$LDOng;
	$sex=$LDMale;
}else{
$danhxung=$LDBa;
$sex=$LDFemale;
}
$tpdf->Cell(85,5,$danhxung.":".$encounter['name_last']." ".$encounter['name_first'],0,0,'L');
$tpdf->Cell(0,5,"Sinh ngày ".formatDate2Local($encounter['date_birth'],$date_format)."    ".$sex,0,1,'L');
$tpdf->Cell(85,5,"- Nghề nghiệp: ".$encounter['job'],0,0,'L');
$tpdf->Cell(0,5,"Nơi làm việc: ".$encounter['jod_addr'],0,1,'L');
$tpdf->Cell(85,5,"- Số CMND/ Hộ khẩu: ".$encounter['nat_id_nr'],0,0,'L');
$tpdf->Cell(0,5,"Ngày và nơi cấp: ".formatDate2Local($encounter['nat_id_date_issue'],$date_format)." ".$encounter['nat_id_addr_issue'],0,1,'L');
$tpdf->Cell(0,5,"- Địa chỉ: ".$encounter ['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'],0,1,'L');
$tpdf->Cell(0,5,"- Vào viện lúc: ".date("G",strtotime(formatDate2Local($encounter['encounter_date'],$date_format,TRUE,TRUE)))." giờ ".date("i",strtotime(formatDate2Local($encounter['encounter_date'],$date_format,TRUE,TRUE)))." phút, ngày ".date("d",strtotime($encounter['encounter_date']))." tháng ".date("m",strtotime($encounter['encounter_date']))." năm ".date("Y",strtotime($encounter['encounter_date'])),0,1,'L');
$tpdf->Cell(0,5,"- Ra viện lúc: ".date("G",time())." giờ ".date("i",time())." phút, ngày ".date("d",time())." tháng ".date("m",time())." năm ".date("Y",time()),0,1,'L');
$tpdf->Cell(0,5,"- Lý do vào viện: ".$encounter['lidovaovien'],0,1,'L');
$tpdf->Cell(0,5,'................................................................................................................................................................',0,1,'L');

$tpdf->SetFont('','B');
$tpdf->Cell(26,6,'- Chẩn đoán:',0,1,'L');
$tpdf->SetFont('','');
$tpdf->MultiCell(0,6,$encounter['referrer_diagnosis'],0,'L');


$tpdf->SetFont('','B');
$tpdf->Cell(20,6,'- Điều trị:',0,0,'L');
$tpdf->SetFont('','');
$tpdf->Cell(0,6,$encounter['referrer_recom_therapy'],0,1,'L');
for($i=0;$i<2;$i++)
{
	$tpdf->Cell(0,5,'................................................................................................................................................................',0,1,'L');
}

$tpdf->SetFont('','B');
$tpdf->Cell(73,6,'- Tình trạng thương tích lúc vào viện:',0,0,'L');
$tpdf->SetFont('','');
$tpdf->Cell(0,6,$encounter['thuongtichvao'],0,1,'L');

$tpdf->SetFont('','B');
$tpdf->Cell(71,6,'- Tình trạng thương tích hiện tại:',0,0,'L');
$tpdf->SetFont('','');
$tpdf->Cell(0,6,$encounter['thuongtichra'],0,1,'L');

$tpdf->Ln();

//Ký tên
$tpdf->SetFont('','I');
$tpdf->Cell(0,5,"Ngày ".date("d",time())." tháng ".date("m",time())." năm ".date("Y",time()),0,1,'R');
$tpdf->SetFont('','B');
$tpdf->Cell(60,5,'GIÁM ĐỐC BỆNH VIỆN',0,0,'C');
$tpdf->Cell(60,5,'TRƯỞNG KHOA',0,0,'C');
$tpdf->Cell(60,5,'BÁC SĨ ĐIỀU TRỊ',0,1,'C');
$tpdf->SetFont('','');
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->Cell(60,5,'Họ tên:.....................................',0,0,'C');
$tpdf->Cell(60,5,'Họ tên:.....................................',0,0,'C');
$tpdf->Cell(60,5,'Họ tên:.....................................',0,1,'C');


//$tpdf->Output();
$tpdf->Output('GiayChungNhanThuongTich.pdf', 'I');


?>
