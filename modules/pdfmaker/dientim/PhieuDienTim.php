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
define('NO_CHAIN',1);
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
$encounter_dientim=$enc_obj->getTestRequestDienTim($encounter['encounter_nr'], $batch_nr);
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
$roomName = $ward_obj->_getActiveRoomInfo($enc_obj->encounter['current_room_nr'],$enc_obj->encounter['current_ward_nr']);
$roomNumber = $enc_obj->encounter['current_room_nr'];

require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;
require_once($root_path.'include/care_api_classes/class_measurement.php');
$measurement_obj=new Measurement;
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
//$lang_tables[]='startframe.php';
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');



$tpdf = new tFPDF();
$tpdf->AddPage();
$tpdf->SetTitle('Phieu Dien Tim');
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
//$tpdf->SetFont('DejaVu','',12);


$tpdf->SetFont('DejaVu','B',18);
$tpdf->Ln(); 
$tpdf->Cell(0,7,'PHIẾU ĐIỆN TIM',0,0,'C');

$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX($tpdf->lMargin);
$tpdf->Cell(150,5,'Sở Y tế Bình Dương',0,0,'L');
$tpdf->Cell(50,5,'MS: 23/BV-01',0,0,'L');
$tpdf->Ln(); 
$tpdf->SetFont('','B',11);
$tpdf->Cell(150,5,'BVĐK ...............',0,0,'L');
$tpdf->SetFont('DejaVu','',11);
$tpdf->Cell(20,5,"Số vào viện ".$encounter['encounter_nr'],0,0,'C');
$tpdf->Ln(); 

$tpdf->SetX($tpdf->lMargin);
$tpdf->SetFont('','BI',11);
$tpdf->Cell(0,5,'(Lần thứ:.........................)',0,1,'C');
$tpdf->SetFont('','',11);
$tpdf->Ln();
if($encounter['sex']=='m'){
	$sex=$LDMale;
}else{
$sex=$LDFemale;
}
$namsinh=date("Y",strtotime($encounter['date_birth']));
$tuoi=date("Y")-$namsinh;
//Thông tin bệnh nhân
$tpdf->Cell(0,5,"- Họ tên người bệnh: .....".$encounter['name_last']." ".$encounter['name_first'].".....   Tuổi: .....".$tuoi."..... ".$sex,0,1,'L');
$tpdf->Cell(0,5,"- Cân nặng: .....".$measurement_obj->getWeight($encounter['encounter_nr'])." kg".".....   Chiều cao: .....".$measurement_obj->getHeight($encounter['encounter_nr'])." cm",0,1,'L');
$tpdf->Cell(0,5,"- Địa chỉ: .....".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['citytown_name'],0,1,'L');
$tpdf->Cell(0,5,"- Khoa: .....".$deptName.".....   Buồng: .....".$wardName['roomprefix'].$roomName.".....   Giường: ......".$roomNumber,0,1,'L');
$tpdf->Cell(0,5,"- Chẩn đoán: .....".$encounter_dientim['clinical_info'].'.....',0,1,'L');
$tpdf->Cell(0,5,"- Yêu cầu kiểm tra: ......".$encounter_dientim['test_request'].'.....',0,1,'L');
$tpdf->Ln();

//Ký tên
$tpdf->SetX(135);
$tpdf->SetFont('DejaVu','I',11);
$tpdf->Cell(0,5,'Ngày........tháng........năm........',0,1,'R');
$tpdf->SetX(135);
$tpdf->SetFont('DejaVu','B',11);
$tpdf->Cell(0,5,'BÁC SĨ ĐIỀU TRỊ',0,1,'C');
$tpdf->SetX(135);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetX(135);
$tpdf->SetFont('DejaVu','',11);
$tpdf->Cell(0,5,'Họ tên:.....................................',0,1,'R');

//Kết quả
$tpdf->SetFont('','B',11);
$tpdf->Cell(0,7,'KẾT QUẢ ĐIỆN TIM',0,1,'L');
$tpdf->SetFont('','',11);
$tpdf->Cell(0,5,'- Chuyển đạo mẫu:..................................................................................................................................',0,1,'L');
$tpdf->Cell(0,5,'................................................................................................................................................................',0,1,'L');
$tpdf->Cell(90,5,'Nhịp, tần số:.............................................................',0,0,'L');
$tpdf->Cell(0,5,'Góc α:.....................................................................',0,1,'L');
$tpdf->Cell(90,5,'- Trục:.......................................................................',0,0,'L');
$tpdf->Cell(0,5,'Tư thế tim:..............................................................',0,1,'L');
$tpdf->Cell(90,5,'- P:............................................................................',0,0,'L');
$tpdf->Cell(0,5,'- PQ:.......................................................................',0,1,'L');
$tpdf->Cell(0,5,'- QRS:......................................................................................................................................................',0,1,'L');
$tpdf->Cell(0,5,'................................................................................................................................................................',0,1,'L');
$tpdf->Cell(0,5,'- ST:.........................................................................................................................................................',0,1,'L');
$tpdf->Cell(0,5,'- T:...........................................................................................................................................................',0,1,'L');
$tpdf->Cell(0,5,'- QT:.........................................................................................................................................................',0,1,'L');
$tpdf->Cell(0,5,'- Chuyển đạo trước tim:...........................................................................................................................',0,1,'L');
$tpdf->Ln();

$tpdf->SetFont('','B',11);
$tpdf->Cell(0,7,'KẾT LUẬN',0,1,'L');
$tpdf->SetFont('','',11);
$tpdf->Cell(0,5,'.......'.$encounter_dientim['results'].'.......',0,1,'L');
for($i=0;$i<6;$i++)
{
	$tpdf->Cell(0,5,'................................................................................................................................................................',0,1,'L');
}
$tpdf->Ln();

//Ký tên
$tpdf->SetX($tpdf->lMargin);
$tpdf->SetFont('','U');
$tpdf->Cell(60,5,'Lời dặn của BS chuyên khoa',0,0,'L');
$tpdf->SetFont('','I');
$tpdf->SetX(135);
$tpdf->Cell(60,5,'Ngày........tháng........năm........',0,1,'R');
$tpdf->SetFont('','B');
$tpdf->SetX(135);
$tpdf->Cell(64,5,'BÁC SĨ CHUYÊN KHOA',0,1,'C');
$tpdf->SetX(135);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetFont('','');
$tpdf->SetX(135);
$tpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');


//-----------------------Page2-------------------------------------
$tpdf->AddPage();
$tpdf->Ln();

$y1=$y=$tpdf->GetY();
$x=$tpdf->GetX();
$w=($tpdf->w)-($tpdf->lMargin)-($tpdf->rMargin);
$h=56;
for($i=0;$i<4;$i++)
{
	$tpdf->Rect($x,$y,$w,$h);
	$y=$y+$h+6;
}
$tpdf->SetFont('','B');
$tpdf->setY($y1+45);
$tpdf->Cell(90,5,'   DI',0,0,'L');
$tpdf->Cell(0,5,'DII   ',0,1,'R');
$tpdf->setY($y1+108);
$tpdf->Cell(90,5,'   DIII',0,0,'L');
$tpdf->Cell(0,5,'AVR   ',0,1,'R');
$tpdf->setY($y1+168);
$tpdf->Cell(90,5,'   AVL',0,0,'L');
$tpdf->Cell(0,5,'AVF   ',0,1,'R');
$tpdf->setY($y1+231);
$tpdf->Cell(90,5,'   S5 - ĐẠO TRÌNH THỰC QUẢN MCL1',0,0,'L');
$tpdf->Cell(0,5,'2   ',0,1,'R');


//-----------------------Page3-------------------------------------
$tpdf->AddPage();
$tpdf->Ln();

$y1=$y=$tpdf->GetY();
$x=$tpdf->GetX();
$w=($tpdf->w)-($tpdf->lMargin)-($tpdf->rMargin);
$h=56;
for($i=0;$i<4;$i++)
{
	$tpdf->Rect($x,$y,$w,$h);
	$y=$y+$h+6;
}
$tpdf->SetFont('','B');
$tpdf->setY($y1+45);
$tpdf->Cell(90,5,'   V1',0,0,'L');
$tpdf->Cell(0,5,'V2   ',0,1,'R');
$tpdf->setY($y1+108);
$tpdf->Cell(90,5,'   V3',0,0,'L');
$tpdf->Cell(0,5,'V4   ',0,1,'R');
$tpdf->setY($y1+168);
$tpdf->Cell(90,5,'   V5',0,0,'L');
$tpdf->Cell(0,5,'V6   ',0,1,'R');
$tpdf->setY($y1+231);
$tpdf->Cell(90,5,'   V4R',0,0,'L');
$tpdf->Cell(0,5,'3   ',0,1,'R');


//$tpdf->Output();
$tpdf->Output('PhieuDienTim.pdf', 'I');	//I: send to standard output, D: download file, F: save to local file


?>
