<?php
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$report_textsize=12;
$report_titlesize=16;
$report_auxtitlesize=10;
$report_authorsize=10;
$sex ='';

$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
$local_user='ck_opdoku_user';
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
//Lấy mã ê-kíp
$temp=$op_obj->getInfo($batch_nr);
$nr_op=$temp->FetchRow();
$op_info=$op_obj->getInfoMedoc($nr_op[nr]);
//Lấy những thành phần trong test_request_or
$test=$op_obj->getInfoTest($batch_nr,'draff');
$test_op=$test->FetchRow();
//insert by Mến 16/04/2014
$test_info=$op_obj->getInfoTestRequest($batch_nr,'pending');
$e_kip=$op_obj->getInfo($batch_nr,'pending');
$op_info1=$e_kip->FetchRow();
$sql="SELECT nr FROM care_op_med_doc WHERE encounter_op_nr='".$nr_op[nr]."'";
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
$fpdf->SetX($fpdf->lMargin+10);
$fpdf->Cell(0,9,'PHIẾU PHẪU THUẬT',0,0,'C');

$fpdf->SetFont('DejaVu','',10);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(170,5,'Sở Y tế: Bình Dương ',0,0,'L');
$fpdf->Cell(0,5,'Số: '.$encounter['encounter_nr'],0,0,'R');
$fpdf->Ln(); 

$fpdf->Cell(150,5,PDF_HOSNAME,0,0,'L');
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
$fpdf->Cell(0,5," Họ tên bệnh nhân:    ".$encounter['name_last']." ".$encounter['name_first']."                                                                                  Tuổi: ".$encounter[tuoi],0,1,'L');
$ngayden=formatDate2Local($encounter['encounter_date'],$date_format);
$gioden=formatDate2Local($encounter['encounter_date'],$date_format,TRUE,TRUE);
$time=explode(':',$gioden);
$giomo=formatDate2Local($test_op[date_request],$date_format);
$fpdf->Cell(0,5," Địa chỉ:   ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'],0,1,'L');
$fpdf->Cell(0,5," Ngày giờ vào viện:        ".$time['0']." giờ ".$time['1']." phút     ngày ".substr($ngayden,0,2)." tháng ".substr($ngayden,3,2)." năm ".substr($ngayden,6,4),0,1,'L');
$fpdf->Cell(0,5," Ngày giờ phẩu thuật:    ".substr($op_info['op_start'],0,2)." giờ ".substr($op_info['op_start'],3,2)." phút    ngày ".substr($giomo,0,2)." tháng ".substr($giomo,3,2)." năm ".substr($giomo,6,4),0,1,'L');

//edit 16/04/2014
if(!empty($test_info['clinical_info'])){
$fpdf->Cell(0,5," Chẩn đoán trước mổ:    ".$test_info['clinical_info'],0,1,'L'); //edit
}else {
$fpdf->Cell(0,5,' Chẩn đoán trước mổ:..............................................................................................................................',0,1,'L');
}
$fpdf->Ln();

$fpdf->Rect(15,55,180,60);
$fpdf->Rect(15,55,90,60);
$x=$fpdf->GetX(); 
$y=$fpdf->GetY();
$fpdf->SetX(15);
//Lấy những người trong ca mổ
$function=array("4","12","5","8","10","7");
for($i=0;$i<6;$i++){
    $personell=$op_obj->searchPersonell($nr_op[nr],$function[$i],'chosed');
    if($personell){
        $personell_info[$i]=$personell;
    }                                
}
if($personell_info[0]){
    for($i=0;$i<sizeof($personell_info[0]);$i++){
        if(trim($personell_info[0][$i],'\x')=='') continue;
        else $fpdf->Cell(80,5," Bác sĩ phẩu thuật: ".trim($personell_info[0][$i],'\x'),0,1,'L');
    }    
}else{
$fpdf->Cell(80,5,' Bác sĩ phẩu thuật:.................................................',0,0,'L');
}
$fpdf->SetX(64);
if($op_info['therapy']){
	$fpdf->SetY($y);
	$fpdf->SetX(79);
	$fpdf->Cell(0,5," Phương pháp vô cảm:    ".$op_info['therapy'],0,1,'C');
}

if($personell_info[1]){
    for($i=0;$i<sizeof($personell_info[1]);$i++){
        if(trim($personell_info[1][$i],'\x')=='') continue;
        else{
            $fpdf->Ln();
			$fpdf->SetY($y)-$y;
            $fpdf->SetX(37);
            $fpdf->Cell(80,5,"Phụ ".$i.": ".trim($personell_info[1][$i],'\x'),0,0,'L');
        }
    }
}else{
    $fpdf->Ln();
    $fpdf->SetX(37);
    $fpdf->Cell(80,5,'Phụ :...................................................',0,0,'L');
}
$fpdf->SetX(104);
if($op_info['hoisuc']){
	$fpdf->SetX(55);
	$fpdf->Cell(45,5,' Hồi sức: '.$op_info['hoisuc'],0,0,'C');
}else{
	$fpdf->SetX(118);
	$fpdf->Cell(45,5,' Hồi sức: .................................................',0,0,'C');
}
$fpdf->Ln();
if($op_info['op_start']){
$fpdf->SetX(55);
$fpdf->Cell(0,5," Giờ bắt đầu: ".$op_info['op_start'],0,0,'C');
}else{
$fpdf->SetX(104);
$fpdf->Cell(0,5,' Giờ bắt đầu:.........................................................',0,0,'C');
}
$fpdf->Ln();
if($op_info['op_end']){
$fpdf->SetX(68);
$fpdf->Cell(0,5," Giờ bệnh nhân tỉnh: ".$op_info['op_end'],0,0,'C');
}else{
$fpdf->SetX(104);
$fpdf->Cell(0,5,' Giờ bệnh nhân tỉnh:.........................................................',0,0,'C');
}
if($personell_info[4]){
    for($i=0;$i<sizeof($personell_info[4]);$i++){
        if(trim($personell_info[4][$i],'\x')=='') continue;
        else{
            $fpdf->Ln();
            $fpdf->SetX(20);
            $fpdf->Cell(0,5,"Dụng cụ viên ".($i+1)." :  ".trim($personell_info[4][$i],'\x'),0,0,'L');
        }
    }
}else{
    $fpdf->Ln();
    $fpdf->SetX(20);
    $fpdf->Cell(0,5,'Dụng cụ viên:.................................................',0,0,'L');
}

if($personell_info[5]){
    for($i=0;$i<sizeof($personell_info[5]);$i++){
        if(trim($personell_info[5][$i],'\x')=='') continue;
        else{
            $fpdf->Ln();
            $fpdf->SetX(16);
            $fpdf->Cell(0,5,"Y tá chạy ngoài ".$i." :   ".trim($personell_info[5][$i],'\x'),0,0,'L');
        }
    }
}else{
    $fpdf->Ln();
    $fpdf->SetX(20);
    $fpdf->Cell(0,5,'Y tá chạy ngoài:.................................................',0,0,'L');
}
if($personell_info[2]||$personell_info[3]){
    for($i=0;$i<sizeof($personell_info[2]);$i++){
        if(trim($personell_info[2][$i],'\x')=='') continue;
        else{
            $fpdf->Ln();
            $fpdf->SetX(18);
            $fpdf->Cell(0,5,"Bác sĩ gây mê ".($i+1)." :   ".trim($personell_info[2][$i],'\x'),0,0,'L');
        }
    }
    for($i=0;$i<sizeof($personell_info[3]);$i++){
        if(trim($personell_info[3][$i],'\x')=='') continue;
        else{
            $fpdf->Ln();
            $fpdf->SetX(18);
            $fpdf->Cell(0,5,"Bác sĩ gây mê ".($i+1)." :   ".trim($personell_info[3][$i],'\x'),0,0,'L');
        }
    }
}else{
    $fpdf->Ln();
    $fpdf->SetX(20);
    $fpdf->Cell(0,5,'Bác sĩ gây mê:.....................................................',0,0,'L');
}
//$fpdf->SetX(104);
//$fpdf->Cell(0,5,' Giờ bệnh nhân tỉnh:.............................................',0,0,'C');

$fpdf->Ln(30);
$fpdf->Rect(15,115,180,170);
$x=$fpdf->GetX(); $y=$fpdf->GetY();
//$fpdf->Ln(12);
$fpdf->SetFont('DejaVu','B',13);
$fpdf->Cell(0,6,' PHƯƠNG PHÁP MỔ',0,0,'C');

$fpdf->Ln(10);
$fpdf->SetX(20);
$fpdf->MultiCell(0,6,$op_info['localize'],0,'L');
$fpdf->Ln(100);
//Ký tên

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin+5);
$fpdf->setY(230);
$fpdf->SetX(20);
$fpdf->Cell(60,5,'Chẩn đoán sau mổ',0,0,'L');
$fpdf->SetX(130);
$fpdf->Cell(60,5,'Ngày '.date('d').' tháng '.date('m').' năm '.date('Y'),0,1,'R');
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
$fpdf->SetX($fpdf->lMargin+5);
$fpdf->Cell(60,5,$op_info['special'],0,0,'L');
$fpdf->SetX(130);
$fpdf->Cell(60,52,'Họ tên:.....................................',0,1,'R');





//$fpdf->Output();
$fpdf->Output('PhieuPhauThuat.pdf', 'I');


?>
