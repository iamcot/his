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
# add 10-11 by vy
$encounter_chuyenkhoa=$enc_obj->getTestRequestChuyenKhoa($encounter['encounter_nr']);

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

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

//----------------Care2x
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');




$tpdf = new tFPDF('L','mm','a5');
$tpdf->AddPage();
$tpdf->SetTitle('Phieu Kham Chuyen Khoa');
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
$tpdf->Cell(0,10,'KẾT QUẢ KHÁM CHUYÊN KHOA',1,1,'C');
$tpdf->Cell(0,45,$encounter_chuyenkhoa['results'],1,1,'C');
$tpdf->Cell(0,3,' ',0,1,'C');

$tpdf->SetX(135);
$tpdf->SetFont('DejaVu','I',11);
$ketqua=formatDate2STD($encounter_chuyenkhoa['results_date'],$date_format);
$tpdf->Cell(0,5,"Ngày ".date("d",strtotime($ketqua))." tháng ".date("m",strtotime($ketqua))." năm ".date("Y",strtotime($ketqua)),0,1,'R');
$tpdf->SetX(135);
$tpdf->SetFont('DejaVu','B',11);
$tpdf->Cell(0,5,'BÁC SĨ KHÁM BỆNH',0,1,'C');
$tpdf->SetX(135);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetX(135);
$tpdf->SetFont('DejaVu','',11);
$tpdf->Cell(0,5,"Họ tên: ".$encounter_chuyenkhoa['results_doctor'],0,1,'R');



//$tpdf->Output();
$tpdf->Output('PhieuKhamChuyenKhoa.pdf', 'I');	//I: send to standard output, D: download file, F: save to local file


?>
