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
$roomName = $ward_obj->_getActiveRoomInfo($enc_obj->encounter['current_room_nr'],$enc_obj->encounter['current_ward_nr']);
$roomNumber = $enc_obj->encounter['current_room_nr'];

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


$tpdf = new tFPDF();
$tpdf->AddPage();
$tpdf->SetTitle('Phieu xet nghiem hoa sinh mau');
$tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$tpdf->SetRightMargin(15);
$tpdf->SetLeftMargin(15);
$tpdf->SetTopMargin(25);
$tpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$tpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$tpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$tpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$tpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);


$tpdf->SetFont('DejaVu','B',16);
$tpdf->Ln(); 
$tpdf->Cell(0,7,'PHIẾU XÉT NGHIỆM HÓA SINH MÁU',0,0,'C');

$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX($tpdf->lMargin);
$tpdf->Cell(150,5,'Sở Y tế: Bình Dương',0,0,'L');
$tpdf->Cell(50,5,'MS: 33/BV-01',0,0,'L');
$tpdf->Ln(); 
$tpdf->Cell(150,5,'BV: ..................',0,0,'L');
$tpdf->Cell(50,5,'Số: ..................',0,0,'L');
$tpdf->Ln(); 

$tpdf->SetX(($tpdf->lMargin)+50);
$tpdf->Cell(40,5,'Thường:',0,0,'C');
$tpdf->Cell(30,5,'Cấp cứu:',0,1,'C');
$tpdf->Ln();

$tpdf->SetFont('DejaVu','',11);
if($encounter['sex']=='m'){
	$sex=$LDMale;
}else{
$sex=$LDFemale;
}
$namsinh=date("Y",strtotime($encounter['date_birth']));
$tuoi=date("Y")-$namsinh;
$tpdf->Cell(0,5,"- Họ tên người bệnh: ".$encounter['name_last']." ".$encounter['name_first']." Tuổi: ".$tuoi." ".$sex,0,1,'L');
$tpdf->Cell(90,5,"- Địa chỉ: ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['citytown_name'],0,0,'L');
$tpdf->Cell(26,5," Số thẻ BHYT:".$encounter['insurance_nr'],0,0,'L');
$y=$tpdf->GetY();
$x=$tpdf->GetX();	
$maxi=4;	//Số ô cần vẽ
$w=10;$h=5;	//rộng, cao của ô
$tpdf->DrawRect($x,$y,$w,$h,$maxi);
$tpdf->DrawRect($x+44,$y,$w+9,$h,1);
$tpdf->Cell(0,5,substr($encounter['insurance_nr'],0,2)."      ".substr($encounter['insurance_nr'],3,1)."         ".substr($encounter['insurance_nr'],5,2)."      ".substr($encounter['insurance_nr'],8,2)."   ".substr($encounter['insurance_nr'],11,3).substr($encounter['insurance_nr'],15,5),0,0,'L');
/*
for($i=0;$i<$maxi;$i++) {
	$tpdf->Rect($x,$y,$w,$h);
	$x=$x+$w+1;
}*/

$tpdf->Ln();
$tpdf->Cell(0,5,"- Khoa: ".$deptName." Buồng: ".$roomName." Giường: ".$roomNumber,0,1,'L');
$tpdf->Cell(0,5,"- Chẩn đoán: ".$encounter['referrer_diagnosis'],0,1,'L');
$tpdf->Ln(6);

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Tên\nxét nghiệm",1,'C');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"Trị số\nbình thường",1,'C');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,'Kết quả',1,0,'C');
$tpdf->MultiCell(30,5,"Tên\nxét nghiệm",1,'C');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"Trị số\nbình thường",1,'C');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,'Kết quả',1,1,'C');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,10,"Urê",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,10,"2,5 - 7,5 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,10,"Sắt",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"Nam: 11-27 µmol/L\nNữ: 7-26 µmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Glucose",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"3,9 - 6,4 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(30,5,"Magiê",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"0,8 - 1,00 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,10,"Creatinin",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"Nam: 62-120 µmol/L\n Nữ: 53-100 µmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,10,"AST(GOT)",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,10,"≤37 U/L - 37°C",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,10,"Acid Uric",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"Nam: 180-420 µmol/L\n Nữ: 53-100 µmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,10,"ALT(GPT)",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,10,"≤40 U/L - 37°C",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"BilirubinT.P",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"≤17 µmol",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(30,5,"Amylase",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5," ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"BilirubinT.T\n\nBilirubinG.T",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"≤4,3 µmol\n\n ≤12,7 µmol",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,15,' ',1,0,'L');
$tpdf->MultiCell(30,5,"CK\n\nCK-MB",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"Nam: 24-190U/L-37°\n Nữ: 24-167U/L-37°\n≤24 U/L-37°",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,15,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Protein T.P",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"65 - 82 g/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(30,5,"LDH",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"230-460 U/L-37°",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,10,"Albumin",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,10,"35 - 50 g/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,10,"GGT",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"Nam: 11-50 U/L-37°\nNữ: 7-32 U/L-37°",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Globumin\nTỷ lệ\n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"24 - 38 g/L\n1,3 - 1,8\n ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,15,' ',1,0,'L');
$tpdf->MultiCell(30,5,"Cholinesterase\nPhosphatase kiềm",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"5300-12900 U/L-37°\n\n ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,15,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Fibrinogen",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"2 - 4 g/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(70,5,"Các xét nghiệm khí máu",1,'C');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Cholesterol",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"3,9 - 5,2 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(30,5,"pH động mạch",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"7,37 - 7,45",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Triglycerid\n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"0,46 - 1,88 mmol/L\n ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,5,"pCO2\n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"Nam: 35-46 mmHg\nNữ: 32-43 mmHg",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"HDL - cho\n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"≥0,9 mmol/L\n ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,5,"pO2 động mạch\n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"71 - 104 mmHg\n ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"LDL - cho",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"≤3,4 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(30,5,"HCO3 chuẩn",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"21-26 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Na+",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"135 - 145 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(30,5,"Kiềm dư",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5,"-2 đến +3 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"K+",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"3,5 - 5 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(70,5,"Các xét nghiệm khác",1,'C');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"CL",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"98 - 106 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,5,' ',1,0,'L');
$tpdf->MultiCell(30,5," ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5," ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,5,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Calci\nCalci ion hóa",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"2,15-2,6 mmol/L\n1,17-1,29 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,5," \n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5," \n ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');

$x=$tpdf->GetX();$y=$tpdf->GetY();
$tpdf->MultiCell(30,5,"Phospho\n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+30);
$tpdf->MultiCell(40,5,"TE: 1,3-2,2 mmol/L\nNL: 0,9-1,5 mmol/L",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+70);
$tpdf->Cell(20,10,' ',1,0,'L');
$tpdf->MultiCell(30,5," \n ",1,'L');
$tpdf->SetY($y);$tpdf->SetX($x+120);
$tpdf->MultiCell(40,5," \n ",1,'R');
$tpdf->SetY($y);$tpdf->SetX($x+160);
$tpdf->Cell(20,10,' ',1,1,'L');



//Ký tên
$tpdf->Cell(0,5,' ',0,1,'C');		$y=$tpdf->GetY();


$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX(125);
$tpdf->Cell(60,5,'.......Giờ.......Ngày.......tháng.......năm........',0,1,'C');
$tpdf->SetFont('DejaVu','B',11);
$tpdf->SetX(125);
$tpdf->Cell(60,5,'TRƯỞNG KHOA XÉT NGHIỆM',0,1,'C');
$tpdf->SetX(125);
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetFont('DejaVu','',11);
$tpdf->SetX(125);
$tpdf->Cell(60,5,'Họ tên:.....................................',0,1,'C');


$tpdf->SetY($y);$tpdf->SetX(20);
$tpdf->SetFont('','',11);
$tpdf->Cell(60,5,'.......Giờ.......Ngày.......tháng.......năm........',0,1,'C');
$tpdf->SetFont('','B',11);
$tpdf->Cell(60,5,'BÁC SĨ ĐIỀU TRỊ',0,1,'C');	
$tpdf->Cell(0,25,' ',0,1,'C');
$tpdf->SetFont('','',11);
$tpdf->Cell(60,5,'Họ tên:.....................................',0,1,'C');


$tpdf->Output('PhieuXetNghiemHoaSinhMau.pdf', 'I');


?>
