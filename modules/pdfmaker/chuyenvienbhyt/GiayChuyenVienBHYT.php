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
$local_user='ck_pflege_user';
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

//----------------Care2x
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$tpdf = new tFPDF('P','mm','a5');
$tpdf->AddPage();
$tpdf->SetTitle('Giay chuyen vien nguoi benh co the BHYT');
$tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$tpdf->SetRightMargin(10);
$tpdf->SetLeftMargin(10);
$tpdf->SetTopMargin(20);
$tpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$tpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$tpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$tpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$tpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$tpdf->Ln();
$tpdf->SetFont('DejaVu','',10);
$tpdf->Cell(70,5,'CƠ SỞ KCB:............................',0,0,'L');
$tpdf->Cell(0,5,'Mẫu số: 01-QCT/BHYT',0,1,'R');
$tpdf->Line($tpdf->x+15,$tpdf->y,$tpdf->x+40,$tpdf->y);

$tpdf->Ln(); 
$tpdf->SetFont('DejaVu','B',13);
$tpdf->Cell(0,7,'GIẤY CHUYỂN VIỆN NGƯỜI BỆNH CÓ THẺ BHYT',0,1,'C');

$tpdf->SetFont('','',10);
$tpdf->Cell(0,5,'(Có giá trị để thanh toán)',0,1,'C');
$tpdf->Cell(0,5,'Số:........ /CV-BHYT',0,1,'R');

//Thông tin bệnh nhân
$tpdf->SetFont('DejaVu','',10);
$tpdf->Cell(0,5,'Cơ sỏ KCB:............................................................................................................',0,1,'L');
$tpdf->Cell(0,5,'Kính chuyển đến:..................................................................................................',0,1,'L');
if($encounter['sex']=='m'){
	$sex=$LDMale;
}else{
$sex=$LDFemale;
}
$tpdf->Cell(0,5,"Họ và tên người bệnh: ".$encounter['name_last']." ".$encounter['name_first']." Giới tính ".$sex,0,1,'L');
$tpdf->Cell(0,5,"Năm sinh: ".date("Y",strtotime($encounter['date_birth']))." Dân tộc: ".$encounter['dantoc']." Nghề nghiệp: ".$encounter['nghenghiep'],0,1,'L');
$tpdf->Cell(0,5,"Địa chỉ: ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'],0,1,'L');
$tpdf->Cell(0,5,"Thẻ BHYT số: ".$encounter['insurance_nr'],0,1,'L');
$tpdf->Cell(0,5,"Giá trị sử dụng: Từ ".formatDate2Local($encounter['insurance_start'],$date_format)."  Đến ".formatDate2Local($encounter['insurance_exp'],$date_format),0,1,'L');
$y=$tpdf->GetY()+0.5;
$tpdf->Cell(30,5,'Đã KCT:',0,0,'L');				$tpdf->Rect(25,$y,6,3);
$tpdf->Cell(45,5,'Đã ĐT ngoại trú:',0,0,'L');	$tpdf->Rect(69,$y,6,3);
$tpdf->Cell(0,5,'Đã ĐT nội trú:',0,1,'L');		$tpdf->Rect(110,$y,6,3);
$tpdf->Cell(0,5,"Ngày vào viện: ".formatDate2Local($encounter['encounter_date'],$date_format)."  Số ngày đã điều trị:.............",0,1,'L');
$tpdf->Cell(0,5,"Nơi điều trị: ".$current_dept_name." Số bệnh án ".$encounter['encounter_nr'],0,1,'L');
$tpdf->Cell(0,5,"Chẩn đoán của nơi gửi: ".$encounter['referrer_diagnosis'],0,1,'L');
$tpdf->MultiCell(0,5,'Lý do chuyển viện và đề nghị: .............................................................................
.............................................................................................................................',0,'L');
$tpdf->Ln();


//Ký tên
$tpdf->SetFont('DejaVu','',10);
$tpdf->SetX(70);
$tpdf->Cell(60,5,'Ngày........tháng........năm 200....',0,1,'R');
$tpdf->SetFont('DejaVu','B',10);
$tpdf->SetX(70);									$y=$tpdf->GetY();
$tpdf->Cell(64,5,'GIÁM ĐỐC (Trưởng khoa)',0,1,'C');	
$tpdf->SetFont('','I',10);
$tpdf->SetX(70);
$tpdf->Cell(60,5,'(Ký tên, đóng dấu)',0,1,'C');		
$tpdf->SetX(70);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetFont('DejaVu','',10);


$tpdf->SetY($y);
$tpdf->SetFont('','B',10);
$tpdf->Cell(50,5,'BS.KHÁM, ĐIỀU TRỊ',0,1,'C');
$tpdf->SetFont('','I',10);
$tpdf->Cell(50,5,'(Ký và ghi rõ họ tên)',0,1,'C');	
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetFont('','B',10);
$tpdf->Cell(50,5,'CB (CTV) BHXH',0,1,'C');
$tpdf->SetFont('','I',10);
$tpdf->Cell(50,5,'(Ký và ghi rõ họ tên)',0,1,'C');	

$tpdf->Output('GiayChuyenVienBHYT.pdf', 'I');


?>
