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

require_once($root_path.'include/care_api_classes/class_encounter_op.php');
$op_obj=new OPEncounter();
$op_info=$op_obj->getInfoMedoc($encounter['encounter_nr']);
$sql="SELECT * FROM care_op_med_doc WHERE encounter_nr='".$encounter['encounter_nr']."'";
$buf=$db->Execute($sql);
$buf->RecordCount();
$buf2=$buf->FetchRow();
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");





$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$fpdf = new tFPDF('P','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Phieu Xet Nghiem');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(15);
$fpdf->SetLeftMargin(15);
$fpdf->SetTopMargin(30);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$fpdf->SetFont('DejaVu','B',24);
$fpdf->Ln(); 
$fpdf->Cell(0,9,'PHIẾU PHẨU THUẬT',0,0,'C');

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(150,5,'Sở Y tế: Bình Dương ',0,0,'L');
$fpdf->Cell(0,5,'Số: .............................',0,0,'R');
$fpdf->Ln(); 

$fpdf->Cell(150,5,'BV:...........................',0,0,'L');
$fpdf->Ln(); 

$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Ln(); 
$fpdf->Rect(15,25,180,30);
$x=$fpdf->GetX(); $y=$fpdf->GetY();
$fpdf->SetX($x+42);
$fpdf->SetY($y+2);
//Thông tin bệnh nhân
$fpdf->SetFont('DejaVu','',11);
$namsinh=date("Y",strtotime($encounter['date_birth']));
$tuoi=date("Y")-$namsinh;
$fpdf->Cell(0,5," Họ tên bệnh nhân: ".$encounter['name_last']." ".$encounter['name_first']."                                                                                  Tuổi: ".$tuoi,0,1,'L');
$ngayden=formatDate2Local($encounter['encounter_date'],$date_format);
$gioden=formatDate2Local($encounter['encounter_date'],$date_format,TRUE,TRUE);
$giomo=formatDate2Local($op_info['op_date'],$date_format);
$fpdf->Cell(0,5," Địa chỉ: ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'],0,1,'L');
$fpdf->Cell(0,5," Ngày giờ vào viện: ".date("H",strtotime($gioden))." giờ ".date("m",strtotime($gioden))." phút ngày ".substr($ngayden,0,2)." tháng ".substr($ngayden,3,2)." năm ".substr($ngayden,6,4),0,1,'L');
$fpdf->Cell(0,5," Ngày giờ phẩu thuật: ".substr($op_info['op_start'],0,2)." giờ ".substr($op_info['op_start'],3,2)." phút ngày ".substr($giomo,0,2)." tháng ".substr($giomo,3,2)." năm ".substr($giomo,6,4),0,1,'L');
if($op_info['diagnosis']){
$fpdf->Cell(0,5," Chẩn đoán trước mổ: ".$op_info['diagnosis'],0,1,'L');
}else {
$fpdf->Cell(0,5,' Chẩn đoán trước mổ:..............................................................................................................................',0,1,'L');
}
$fpdf->Ln();

$fpdf->Rect(15,55,180,30);
$fpdf->Rect(15,55,90,30);
$x=$fpdf->GetX(); 
$y=$fpdf->GetY();
$fpdf->SetX(15);
if($op_info['operator']){
$fpdf->Cell(80,5," Bác sĩ phẩu thuật: ".$op_info['operator'],0,0,'L');
}else{
$fpdf->Cell(80,5,' Bác sĩ phẩu thuật:.................................................',0,0,'L');
}
$fpdf->SetX(64);
if($op_info['therapy']){
$fpdf->Cell(0,5," Phương pháp vô cảm: ".$op_info['therapy'],0,0,'C');
}else{
$fpdf->Cell(80,5,' Phương pháp vô cảm:..........................................',0,0,'C');
}
$fpdf->Ln();
$fpdf->SetX(37);
if($op_info['assistant']){
$fpdf->Cell(80,5,"Phụ 1: ".$op_info['assistant'],0,0,'L');
}else{
$fpdf->Cell(80,5,'Phụ 1:................................................',0,0,'L');
}
$fpdf->SetX(104);

$fpdf->Cell(0,5,' Người thực hiện:..................................................',0,0,'C');
$fpdf->Ln();
$fpdf->SetX(37);
$fpdf->Cell(80,5,'Phụ 2:................................................',0,0,'L');

if($op_info['op_start']){
$fpdf->SetX(57);
$fpdf->Cell(0,5," Giờ bắt đầu: ".$op_info['op_start'],0,0,'C');
}else{
$fpdf->SetX(104);
$fpdf->Cell(0,5,' Giờ bắt đầu:.........................................................',0,0,'C');
}
$fpdf->Ln();
$fpdf->SetX(24);
if($op_info['rotating_nurse']){
$fpdf->Cell(0,5,"Dụng cụ viên: ".$op_info['rotating_nurse'],0,0,'L');
}else{
$fpdf->Cell(0,5,'Dụng cụ viên:.................................................',0,0,'L');
}
$fpdf->SetX(104);
$fpdf->Cell(0,5,' Hồi sức:................................................................',0,0,'C');
$fpdf->Ln();
$fpdf->SetX(20);
if($op_info['scrub_nurse']){
$fpdf->Cell(0,5,"Y tá chạy ngoài: ".$op_info['scrub_nurse'],0,0,'L');
}else{
$fpdf->Cell(0,5,'Y tá chạy ngoài:.................................................',0,0,'L');
}
$fpdf->SetX(104);
$fpdf->Cell(0,5,' Giờ bệnh nhân tỉnh:.............................................',0,0,'C');

$fpdf->Ln(12);
$fpdf->Rect(15,85,180,180);
$x=$fpdf->GetX(); $y=$fpdf->GetY();
$fpdf->SetFont('DejaVu','B',13);
$fpdf->Cell(0,6,' PHƯƠNG PHÁP MỖ',0,0,'C');
$fpdf->Ln(140);
//Ký tên

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin+5);
$fpdf->SetX(20);
$fpdf->Cell(60,5,'Chẩn đoán sau mổ',0,0,'L');
$fpdf->SetX(130);
$fpdf->Cell(60,5,'Ngày........tháng........năm........',0,1,'R');
//
//$fpdf->Cell(60,5,'Ngày........tháng........năm........',0,1,'R');
//$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetX(30);
$fpdf->Cell(58,5,'GHI CHÚ',0,0,'L');
$fpdf->SetX(130);
$fpdf->Cell(64,5,'Phẩu thuật viên ký',0,1,'C');
//$fpdf->SetX(135);
//$fpdf->Cell(0,25,' ',0,1,'C');
//$fpdf->SetFont('DejaVu','',11);
//$fpdf->SetX($fpdf->lMargin+5);
//$fpdf->Cell(60,5,'Họ tên:.....................................',0,0,'L');
//$fpdf->SetX(135);
//$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');





//$fpdf->Output();
$fpdf->Output('PhieuPhauThuat.pdf', 'I');


?>
