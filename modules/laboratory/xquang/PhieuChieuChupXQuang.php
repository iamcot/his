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
$local_user='ck_lab_user';
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');

$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	//extract($encounter);
	
}

# Fetch insurance and encounter classes
$encounter_radio=$enc_obj->getTestRequestRadio($encounter['encounter_nr'],$batch_nr);
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
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$tpdf = new tFPDF('P','mm','a4');
$tpdf->AddPage();
$tpdf->SetTitle('Phieu Chieu, Chup X Quang');
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


$tpdf->SetFont('DejaVu','B',18);
$tpdf->Ln(); 
$tpdf->Cell(0,7,'PHIẾU CHIẾU/CHỤP X QUANG',0,0,'C');

$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX($tpdf->lMargin);
$tpdf->Cell(150,5,'Sở Y tế: Bình Dương',0,0,'L');
$tpdf->Cell(50,5,'MS: 19/BV-01',0,0,'L');
$tpdf->Ln(); 
$tpdf->Cell(150,5,PDF_HOSNAME,0,0,'L');
$tpdf->Cell(50,5,'Số: '.$enc,0,0,'L');
$tpdf->Ln(); 

$tpdf->SetFont('DejaVu','BI',11);
$tpdf->SetX($tpdf->lMargin);
$tpdf->Cell(0,5,"(Lần thứ: ".$batch_nr." )",0,1,'C');
$tpdf->Ln();

$tpdf->SetFont('DejaVu','',11);
$namsinh=date("Y",strtotime($encounter['date_birth']));
$tuoi=date("Y")-$namsinh;
if($encounter['sex']=='m'){
	$sex=$LDMale;
}else{
$sex=$LDFemale;
}
$tpdf->Cell(0,5,"- Họ tên người bệnh: ".$encounter['name_last']." ".$encounter['name_first']."  Tuổi: ".$tuoi."  Nam/Nữ:".$sex,0,1,'L');
$tpdf->Cell(0,5,"- Địa chỉ: ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'],0,1,'L');
if($roomNumber==0){
	$roomNumber='';
}
$tpdf->Cell(0,5,"- Khoa: ".$deptName.", Buồng: ".$roomNumber.", Giường: ".$wardName['roomprefix'].' '.$roomName,0,1,'L');
$tpdf->Cell(0,5,"- Chẩn đoán: ".$encounter['referrer_diagnosis'],0,1,'L');
$tpdf->Ln();

$tpdf->SetFont('DejaVu','B',12);
$tpdf->Cell(0,7,'YÊU CẦU CHIẾU/CHỤP',1,1,'C');
if($encounter_radio['xray']=='1'){
   $yeucau_1=$LDXrayTest;
} else{
	$yeucau_1='';
}

$sql1="SELECT * FROM care_test_request_radio_sub WHERE batch_nr='".$batch_nr."' ";
$item_test=$db->Execute($sql1);
$note="";
if (is_object($item_test)){
	for ($i=0;$i<$item_test->RecordCount();$i++){
		$item = $item_test->FetchRow();
		$note=$note." \n ".$item['item_bill_name'];
	}
}
$note=$note." \n \n ".$encounter_radio['test_request'];

$tpdf->SetFont('DejaVu','',12);
$y=$tpdf->GetY();
$tpdf->MultiCell(0,5,$note,0,'L',false);
$tpdf->SetY($y);
$tpdf->Cell(0,40,'',1,1,'C');

$tpdf->Cell(0,3,'',0,1,'C');

$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX(135);
$ngaygoi=formatDate2STD($encounter_radio['send_date'],$date_format);

$tpdf->Cell(60,5,"Ngày ".date("d",strtotime($ngaygoi))." tháng ".date("m",strtotime($ngaygoi))." năm ".date("Y",strtotime($ngaygoi)),0,1,'R');
$tpdf->SetFont('DejaVu','B',11);
$tpdf->SetX(135);
$tpdf->Cell(64,5,'BÁC SĨ CHỈ ĐỊNH',0,1,'C');
/*
$tpdf->SetX(135);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX(135);
$tpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');        */
//   đã sửa
$tpdf->SetX(135);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetX(135);
$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX(135);
$tpdf->Cell(0,5,'Họ tên: '.$encounter_radio['send_doctor'],0,1,'R');

$tpdf->Ln();
$tpdf->SetFont('DejaVu','B',12);
$tpdf->Cell(0,7,'KẾT QUẢ CHIẾU/CHỤP',1,1,'C');
$tpdf->MultiCell(0,60,$encounter_radio['results'],1,'C',false);
//$tpdf->Cell(0,60,$encounter_radio['results'],1,1,'C');

$tpdf->Cell(0,3,' ',0,1,'C');

$tpdf->SetX($tpdf->lMargin);
$tpdf->SetFont('','U');
$tpdf->Cell(60,5,'Lời dặn của BS chuyên khoa',0,0,'L');
$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX(135);
$result_date=formatDate2STD($encounter_radio['results_date'],$date_format);
$tpdf->Cell(60,5,"Ngày ".date("d",strtotime($result_date))." tháng ".date("m",strtotime($result_date))." năm ".date("Y",strtotime($result_date)),0,1,'R');
$tpdf->SetFont('DejaVu','B',11);
$tpdf->SetX(135);
$tpdf->Cell(64,5,'BÁC SĨ CHUYÊN KHOA',0,1,'C');
$tpdf->SetX(135);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX(135);
$tpdf->Cell(60,5,"Họ tên: ".$encounter_radio['results_doctor'],0,1,'C');


//$tpdf->Output();
$tpdf->Output('PhieuChieu_ChupXQuang.pdf', 'I');


?>
