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
$lang_tables[]='emr.php';
$lang_tables[]='departments.php';
$lang_tables[]='nursing.php';
$lang_tables[]='prompt.php';
define('LANG_FILE','aufnahme.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
# Get the encouter data

$enc_obj=& new Encounter($enc_nr);
$encounter=$enc_obj->loadEncounterData1($enc_nr,1);
if($enc_obj->loadEncounterData()){
            $encounter=$enc_obj->getLoadedEncounterData();
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
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
//$current_dept_name=$dept_obj->FormalName($current_dept_nr);
$current_dept_LDvar=$dept_obj->LDvar($encounter['current_dept_nr']);
if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $current_dept_name=$$current_dept_LDvar;
else $current_dept_name=$dept_obj->FormalName($encounter['current_dept_nr']);
                
require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;

// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");
	class exec_String {
		var $lower = '
		a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z
		|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ
		|đ
		|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ
		|í|ì|ỉ|ĩ|ị
		|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ
		|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự
		|ý|ỳ|ỷ|ỹ|ỵ';
		var $upper = '
		A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z
		|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ
		|Đ
		|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ
		|Í|Ì|Ỉ|Ĩ|Ị
		|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ
		|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự
		|Ý|Ỳ|Ỷ|Ỹ|Ỵ';
		var $arrayUpper;
		var $arrayLower;
		function BASIC_String(){
			$this->arrayUpper = explode('|',preg_replace("/\n|\t|\r/","",$this->upper));
			$this->arrayLower = explode('|',preg_replace("/\n|\t|\r/","",$this->lower));
		}

		function lower($str){
			return str_replace($this->arrayUpper,$this->arrayLower,$str);
		}
		function upper($str){
			return str_replace($this->arrayLower,$this->arrayUpper,$str);
		}
	}
	$s_obj=new exec_String();

$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$fpdf = new tFPDF('P','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Giay chuyen vien');
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


$fpdf->SetFont('DejaVu','',11);
$fpdf->Ln(); 
$y=$fpdf->GetY();
$fpdf->Cell(0,5,'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM',0,1,'C');
$fpdf->Cell(0,5,'Độc lập - Tự Do - Hạnh Phúc',0,1,'C');
$fpdf->Cell(0,5,str_pad('',26,'__',STR_PAD_RIGHT),0,1,'C');

$fpdf->SetY($y);
$fpdf->SetFont('','B');
$fpdf->Cell(135,5,'SỞ Y TẾ BÌNH DƯƠNG',0,0,'L');
$fpdf->SetFont('','');
$fpdf->Cell(50,5,'MS: 02/BV-01',0,0,'L');
$fpdf->Ln(); 
$fpdf->SetFont('','B');
$fpdf->Cell(135,5,PDF_HOSNAME,0,0,'L');
$fpdf->SetFont('','');
$fpdf->Cell(150,5,'Số lưu trữ:.........................',0,1,'L');

$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','B',18);
$fpdf->Ln(); 
$fpdf->Cell(0,7,'GIẤY CHUYỂN VIỆN',0,0,'C');
$fpdf->Ln(10);

$fpdf->SetFont('','BI',11);
$fpdf->UnderlineText(true);
$fpdf->Cell(60,6,'Kính gửi:',0,0,'R');
$fpdf->SetFont('','');
$sql="SELECT hl.lname FROM care_encounter_notes pn, dfck_hospital_list hl 
            WHERE pn.type_nr = 68
            and pn.notes = hl.sname and pn.encounter_nr = '$enc_nr' order by pn.nr DESC limit 0,1";
            $strbvden = "";
           // echo $sql;
if($result=$db->Execute($sql)){
    if($row=$result->FetchRow()) $strbvden = $row['lname'];    
}
$fpdf->Cell(0,6,'Ban giám đốc '.$strbvden,0,1,'L');
$fpdf->Ln(); 

//Thông tin bệnh nhân
$fpdf->Cell(0,6,'     Bệnh viện chúng tôi trân trọng giới thiệu:',0,1,'L');
if($encounter['sex']=='m'){
    $sex=$LDMale;
}else{
    $sex=$LDFemale;
}
$s_obj->BASIC_String();
$fpdf->Cell(125,5,"- ".$LDFullName_patient.": ".$s_obj->upper($encounter['name_last']." ".$encounter['name_first']).'',0,0,'L');

if($encounter['tuoi']<=6){
    $tuoi=$encounter['thang'].' tháng';
}else{
    $tuoi=$encounter['tuoi'];
}
$fpdf->Cell(0,5,"Tuổi: ".$tuoi."  Giới tính ".$sex.'',0,1,'L');
if(!empty($encounter['dantoc'])){
$fpdf->Cell(85,5,"- Dân tộc: ".$encounter['dantoc'],0,0,'L');
}
 else {
	$fpdf->Cell(85,5,'- Dân tộc:.............................................................',0,0,'L');
}
if(!empty($encounter['ngoaikieu'])){
$fpdf->Cell(0,5,"Ngoại kiều: ".$encounter['ngoaikieu'],0,1,'L');
}else{
$fpdf->Cell(0,5,'Ngoại kiều:..................................................................',0,1,'L');
}
if(!empty($encounter['nghenghiep'])){
$fpdf->Cell(85,5,"- Nghề nghiệp: ...".$encounter['nghenghiep'].'...',0,0,'L');
}else{
$fpdf->Cell(85,5,'- Nghề nghiệp:.....................................................',0,0,'L');
}
if(!empty($encounter['noilamviec'])){
$fpdf->Cell(0,5,"Nơi làm việc: ".$encounter['noilamviec'],0,1,'L');
}else{
$fpdf->Cell(0,5,'Nơi làm việc:...............................................................',0,1,'L');
}
//var_dump($encounter);
if((!empty($encounter['pinsurance_start'])) && (!empty($encounter['pinsurance_exp'])) && ($encounter['pinsurance_start']!='0000-00-00') || ($encounter['pinsurance_exp']!='0000-00-00')){
    $fpdf->Cell(95,5,"- BHYT: giá trị từ: ".formatDate2Local($encounter['pinsurance_start'],$date_format)." đến: ".formatDate2Local($encounter['pinsurance_exp'],$date_format)." Số: ".strtoupper($encounter['pinsurance_nr']).'/'.$encounter['madkbd'],0,0,'L');
    $y=$fpdf->GetY();
    $x=$fpdf->GetX();
    //$fpdf->DrawRect($x,$y,11,5,3);
   // $fpdf->DrawRect($x+36,$y,22,5,2);
    //$fpdf->Cell(0,5,,0,0,'L');
} else{
    $fpdf->Cell(98,5,'- BHYT: giá trị từ:.../.../..... đến:.../.../.....  Số ',0,0,'L');
    $y=$fpdf->GetY();
    $x=$fpdf->GetX();
    $fpdf->DrawRect($x,$y,11,5,3);
    $fpdf->DrawRect($x+36,$y,22,5,2);
}
$fpdf->Ln();
$fpdf->Cell(0,5,"- ".$LDAddress.":"." ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'],0,1,'L');

if($encounter['encounter_class_nr']==1){
$fpdf->Cell(0,5,"- Đã được điều trị/ khám bệnh tại ".$current_dept_name,0,1,'L');
}else{
$fpdf->Cell(0,5,"- Đã được điều trị/ khám bệnh tại ".$current_dept_name,0,1,'L');
}
$fpdf->Cell(0,5,"- Từ ngày: ".formatDate2Local($encounter['encounter_date'],$date_format)." Đến ngày: ".date("d/m/Y",time()),0,1,'L');
$fpdf->Ln();

//Bệnh án
$fpdf->SetFont('','B');
$fpdf->Cell(0,6,'TÓM TẮT BỆNH ÁN',0,1,'C');
$fpdf->SetFont('','');
                 
$column_width = 185;//mm

$fpdf->Cell(0,6,'- Dấu hiệu lâm sàng:',0,1,'L');
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=24");
$totalnum = 3;//chan doan 3 hang
$num_thisline = 0;
if($info1){
    $info_encounter=$info1->FetchRow();
    
    //tinh so dong
    if($info_encounter['notes'] != ''){
    $num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
    $fpdf->Cell(10,5,'',0,0,'L');
    $fpdf->MultiCell(0,5,$info_encounter['notes'],0,'L');
    }
    else $num_thisline = 0;
    
}

$totalnum = 2;//XN 2 hang
$num_thisline = 0;
$fpdf->Cell(0,6,'- Các xét nghiệm:',0,1,'L');
$sql="SELECT DISTINCT(dr.reporting_dept) AS reporting_dept FROM care_encounter_diagnostics_report AS dr 
            WHERE dr.encounter_nr=".$enc_nr." 
            ORDER BY dr.create_time DESC";
if($result=$db->Execute($sql)){
    $rows=$result->RecordCount();
    $i=1;
    $xnstr = "";
    while($row=$result->FetchRow()){
        $deptnr_ok=false;
        if($row['reporting_dept']){
			$xnstr.= $i.'. '.$row['reporting_dept'].', ';
        }
        $i++;
    }     
    if($xnstr != ''){
        $fpdf->Cell(10,5,'',0,0,'L');
     $num_thisline = (ceil( $fpdf->GetStringWidth($xnstr) / ($column_width)));
     $fpdf->MultiCell(0,5,$xnstr,0,'L');
    }
    else $num_thisline = 0;    
}

$totalnum = 1;//CD 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=25");
$fpdf->Cell(0,5,'- Chẩn đoán:',0,1,'L');
if($info1){
    $info_encounter=$info1->FetchRow();
    if($info_encounter['notes'] != ''){
     $num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
     $fpdf->Cell(10,5,'',0,0,'L');
     $fpdf->MultiCell(0,5,$info_encounter['notes'],0,'L');
    }
    else $num_thisline = 0;     
}
$totalnum = 4;//thuoc 4 hang
$num_thisline = 0;
require_once($root_path.'include/care_api_classes/class_prescription.php');
$obj=new Prescription();
$thuoc=$obj->getDetailPrescriptionInfo1($enc_nr);
$fpdf->Cell(0,5,'- Thuốc đã dùng:',0,1,'L');
if($thuoc){
    $i=1;
    $thuoc1=$db->Execute($thuoc);
    $xnstr = "";
    while($info_thuoc=$thuoc1->FetchRow()){
        $xnstr .= $i.". ".$info_thuoc['product_name'].', ';
        $i++;
    }    
    if($xnstr != ''){
        
     $num_thisline = (ceil( $fpdf->GetStringWidth($xnstr) / ($column_width)));
     $fpdf->Cell(10,5,'',0,0,'L');
     $fpdf->MultiCell(0,5,$xnstr,0,'L');
    }
    else $num_thisline = 0;  
   
}
$totalnum = 1;//tt 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=26");
$fpdf->Cell(0,5,'- Tình trạng người bệnh:',0,1,'L');
if($info1){
    $info_encounter=$info1->FetchRow();
     if($info_encounter['notes'] != ''){
     $num_thisline = 1;
     $fpdf->Cell(10,5,'',0,0,'L');
     switch($info_encounter['notes'])   {
        case 1: $kqra = 'Khỏi';break;
        case 2: $kqra = 'Đở, giãm';break;
        case 3: $kqra = 'Không thay đổi';break;
        case 4: $kqra = 'Nặng hơn';break;
        default: $kqra = '';break;
    }               
     $fpdf->MultiCell(0,5,$kqra,0,'L');
    }
    else $num_thisline = 0; 
}
//$fpdf->Cell(0,5,'- Lý do chuyển viện:',0,1,'L');
//$date=$encounter['discharge_date'];
//$discharge_types=&$enc_obj->getDischargeTypesData_2();
//$type_dis=&$enc_obj->getLocation($enc_nr,"AND date_to='$date'");
//if($type_dis){
//	$type_dis_nr=$type_dis['discharge_type_nr'];
//}else{
//	$type_dis_nr='';
//}
//while($dis_type=$discharge_types->FetchRow()){
//			if($dis_type['nr']==$type_dis_nr){
//				if(isset($$dis_type['LD_var'])&&!empty($$dis_type['LD_var'])) $sTemp = $sTemp.$$dis_type['LD_var'];
//					else $sTemp = $sTemp.$dis_type['name'];
//				break;
//			}
//		}
//if($sTemp){
//    $fpdf->Cell(10,5,'',0,0,'L');
//    $fpdf->MultiCell(0,5,' '.$sTemp,0,'L');
//}else{
$totalnum = 2;//CD 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=47");
if($info1){
    $info_encounter=$info1->FetchRow();
    if($info_encounter['notes'] != ''){
     $num_thisline = (ceil( $fpdf->GetStringWidth('- Lý do chuyển viện: '.$info_encounter['notes']) / ($column_width)));
     $fpdf->MultiCell(0,5,'- Lý do chuyển viện: '.$info_encounter['notes'],0,'L');
    }
    else $num_thisline = 0;     
}

$fpdf->Cell(0,5,"- Chuyển viện hồi: ".date("G",strtotime($encounter['discharge_time']))." giờ ".date("i",strtotime($encounter['discharge_time']))." phút, ngày ".date("d",strtotime($encounter['discharge_date']))." tháng ".date("m",strtotime($encounter['discharge_date']))." năm ".date("Y",strtotime($encounter['discharge_date'])),0,1,'L');

$totalnum = 1;//CD 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=27");
$fpdf->Cell(0,5,'- Phương tiện vận chuyển:',0,1,'L');
if($info1){
    $info_encounter=$info1->FetchRow();
    if($info_encounter['notes'] != ''){
     $num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
     $fpdf->Cell(10,5,'',0,0,'L');
     $fpdf->MultiCell(0,5,$info_encounter['notes'],0,'L');
    }
    else $num_thisline = 0;  
}
$totalnum = 1;//CD 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=28");
$fpdf->Cell(0,5,'- Họ tên, chức danh người đưa đi:',0,1,'L');
if($info1){
    $info_encounter=$info1->FetchRow();
    if($info_encounter['notes'] != ''){
     $num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
     $fpdf->Cell(10,5,'',0,0,'L');
     $fpdf->MultiCell(0,5,$info_encounter['notes'],0,'L');
    }
    else $num_thisline = 0;  
}
$fpdf->Ln();

//Ký tên
$fpdf->SetFont('','I');
$fpdf->Cell(0,5,'Ngày '.date('d').' tháng '.date('m').' năm '.date('Y').' ',0,1,'R');
$fpdf->SetFont('','B');
$fpdf->Cell(60,5,'BÁC SĨ ĐIỀU TRỊ',0,0,'C');
$fpdf->Cell(60,5,' ',0,0,'C');
$fpdf->Cell(60,5,'GIÁM ĐỐC BỆNH VIỆN',0,1,'C');
$fpdf->SetFont('','');
$fpdf->Cell(0,25,' ',0,1,'C');
//$fpdf->Cell(60,5,'.....................................',0,0,'C');
$fpdf->Cell(60,5,' ',0,0,'C');
//$fpdf->Cell(60,5,'.....................................',0,1,'C');


//$fpdf->Output();
$fpdf->Output('GiayChuyenVien.pdf', 'I');


?>
