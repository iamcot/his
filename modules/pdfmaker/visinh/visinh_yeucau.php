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
$sql="select * from care_test_request_visinh where batch_nr='".$batch_nr."' and encounter_nr='".$enc."'";
$temp=$db->execute($sql);
$temp->recordcount();
$enc_visinh=$temp->fetchrow();
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




$fpdf = new tFPDF('L','mm','a5');
$fpdf->AddPage();
$fpdf->SetTitle('Phieu Xet Nghiệm');
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
//$fpdf->SetFont('DejaVu','',12);


$fpdf->SetFont('DejaVu','B',18);
$fpdf->Ln(); 
$fpdf->Cell(0,7,'PHIẾU XÉT NGHIỆM',0,0,'C');

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(150,5,'Sở Y tế Bình Dương',0,0,'L');
$fpdf->Cell(50,5,'MS: 23/BV-01',0,0,'L');
$fpdf->Ln(); 
$fpdf->SetFont('','B',11);
$fpdf->Cell(150,5,'BVĐK TÂN UYÊN',0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(20,5,"Số vào viện ".$encounter['encounter_nr'],0,0,'C');
$fpdf->Ln(); 

$fpdf->SetX($fpdf->lMargin);

$fpdf->Cell(0,5,'Bệnh phẩm:.........................',0,1,'C');
$fpdf->SetFont('','',11);
$fpdf->Ln();
if($encounter['sex']=='m'){
	$sex=$LDMale;
}else{
$sex=$LDFemale;
}
$namsinh=date("Y",strtotime($encounter['date_birth']));
$tuoi=date("Y")-$namsinh;
//Thông tin bệnh nhân
$fpdf->Cell(150,5,"- Họ tên người bệnh: ".$encounter['name_last']." ".$encounter['name_first'],0,0,'L');
$fpdf->Cell(30,5," Tuổi: ".$tuoi." ".$sex,0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,"- Địa chỉ: ".$encounter['addr_str_nr']." ".$encounter['addr_str']." Xã/Phường: ".$encounter['phuongxa_name']." Quận/huyện: ".$encouter['quanhuyen_name']." Tỉnh/Thành phố: ".$encounter['citytown_name'],0,1,'L');
$fpdf->Cell(135,5,"- Khoa: ".$deptName,0,0,'L');
$fpdf->Cell(20,5," Buồng: ".$wardName['roomprefix'].$roomName,0,0,'L');
$fpdf->Cell(10,5," Giường: ".$roomNumber,0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,"- Chẩn đoán: ".$enc_visinh['clinical_info'],0,1,'L');

$fpdf->Ln();
$fpdf->Line(15,55,195,55);
$fpdf->Line(15,62,195,62);
$fpdf->Line(15,100,195,100);
$fpdf->Line(15,55,15,100);
$fpdf->Line(195,55,195,100);
$fpdf->Line(105,55,105,100);
$fpdf->Line(38,60,81,60);
$fpdf->Line(128,60,172,60);
$fpdf->SetY(56);
$fpdf->SetX(37);
$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(90,5,'YÊU CẦU XÉT NGHIỆM',0,0,'L');
$fpdf->Cell(0,5,'KẾT QUẢ XÉT NGHIỆM',0,0,'L');
//Ký tên
$fpdf->Ln(48);

$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(0,5,'      Ngày........tháng........năm........',0,0,'L');
$fpdf->Cell(0,5,'Ngày........tháng........năm........',0,0,'R');
$fpdf->Ln();

$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(0,5,'            BÁC SĨ ĐIỀU TRỊ',0,0,'L');
$fpdf->Cell(0,5,'TRƯỞNG KHOA XÉT NGHIỆM',0,0,'R');
$fpdf->Ln(28);

$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(0,5,'Họ tên:.....................................',0,0,'L');
$fpdf->Cell(0,5,'Họ tên:.....................................',0,0,'R');



//$fpdf->Output();
$fpdf->Output('visinh_yeucau.pdf', 'I');	//I: send to standard output, D: download file, F: save to local file


?>
