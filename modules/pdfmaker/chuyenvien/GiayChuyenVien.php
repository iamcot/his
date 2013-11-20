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
$fpdf->Cell(150,5,'Số lưu trữ: '.$encounter['encounter_nr'],0,1,'L');

$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','B',18);
$fpdf->Ln(15); 
$fpdf->Cell(0,7,'GIẤY CHUYỂN VIỆN',0,0,'C');
$fpdf->Ln(15);

$fpdf->SetFont('','BI',11);
$fpdf->UnderlineText(true);
$fpdf->Cell(30,6,'Kính gửi:',0,0,'C');
$fpdf->SetFont('','');
$sql="SELECT hl.lname FROM care_encounter_notes pn, dfck_hospital_list hl 
            WHERE pn.type_nr = 68
            and pn.notes = hl.sname and pn.encounter_nr = '$enc_nr' order by pn.nr DESC limit 0,1";
            $strbvden = "";
           // echo $sql;
if($result=$db->Execute($sql)){
    if($row=$result->FetchRow()) $strbvden = $row['lname'];    
}

if($strbvden){
	$strbvden = $strbvden.str_pad("", (120-strlen('Ban giám đốc '.$strbvden)), ".", STR_PAD_RIGHT);
}else{
	$strbvden = $strbvden.str_pad("", 118, ".", STR_PAD_RIGHT);;
}
$fpdf->Cell(0,6,'Ban giám đốc '.$strbvden,0,1,'L'); 

//Thông tin bệnh nhân
$fpdf->Cell(0,6,'  '.PDF_HOSNAME.' chúng tôi trân trọng giới thiệu:',0,1,'L');
if($encounter['sex']=='m'){
    $sex=$LDMale;
}else{
    $sex=$LDFemale;
}
$s_obj->BASIC_String();
if($encounter['name_last'] || $encounter['name_first']){
	$name = $encounter['name_last']." ".$encounter['name_first'].str_pad("", (95-strlen("- ".$LDFullName_patient.": ".$encounter['name_last']." ".$encounter['name_first'])), ".", STR_PAD_RIGHT);
}else{
	$name = str_pad("", 72, ".", STR_PAD_RIGHT);;
}

$fpdf->Cell(123,5,"- ".$LDFullName_patient.": ".$s_obj->upper($name).'',0,0,'L');

if($encounter['tuoi']<=6){
    $tuoi=$encounter['thang'].' tháng';
}else{
    $tuoi=$encounter['tuoi'];
}
if($tuoi){
	$strtuoi = "Tuổi: ".$tuoi.str_pad("", (20-strlen(" Tuổi: ".$tuoi)), ".", STR_PAD_RIGHT);
}else{
	$strtuoi = "Tuổi: ".str_pad("", 12, ".", STR_PAD_RIGHT);
}
if($sex){
	$strsex = " Giới tính ".$sex.str_pad("", (12-" Giới tính ".$sex), ".", STR_PAD_RIGHT);
}else{
	$strsex = " Giới tính".str_pad("", 21, ".", STR_PAD_RIGHT);
}
$fpdf->Cell(0,5,$strtuoi.$strsex,0,1,'L');

if(!empty($encounter['dantoc'])){
	$fpdf->Cell(85,5,"- Dân tộc: ".$encounter['dantoc'].str_pad("", (72-strlen(" Dân tộc: ".$encounter['dantoc'])), ".", STR_PAD_RIGHT),0,0,'L');
}
 else {
	$fpdf->Cell(85,5,'- Dân tộc: '.str_pad("", 62, ".", STR_PAD_RIGHT),0,0,'L');
}

if(!empty($encounter['ngoaikieu'])){
	$fpdf->Cell(0,5,"  Ngoại kiều: ".$encounter['ngoaikieu'].str_pad("", (64-strlen($encounter['ngoaikieu'])), ".", STR_PAD_RIGHT),0,1,'L');
}else{
	$fpdf->Cell(0,5,'  Ngoại kiều: '.str_pad("", 70, ".", STR_PAD_RIGHT),0,1,'L');
}
 
if(!empty($encounter['nghenghiep'])){
	$fpdf->Cell(85,5,"- Nghề nghiệp: ".$encounter['nghenghiep'].str_pad(".", (65-strlen("- Nghề nghiệp: ".$encounter['nghenghiep'])), ".", STR_PAD_RIGHT),0,0,'L');
}else{
	$fpdf->Cell(85,5,'- Nghề nghiệp: '.str_pad(".", 54, ".", STR_PAD_RIGHT),0,0,'L');
}
if(!empty($encounter['noilamviec'])){
	$fpdf->Cell(0,5,"  Nơi làm việc: ".$encounter['noilamviec'].str_pad(".", (70-strlen(" Nơi làm việc: ".$encounter['noilamviec'])), ".", STR_PAD_RIGHT),0,1,'L');
}else{
	$fpdf->Cell(0,5,'  Nơi làm việc: '.str_pad(".", 67, ".", STR_PAD_RIGHT),0,1,'L');
}
//var_dump($encounter);
if((!empty($encounter['pinsurance_start'])) && (!empty($encounter['pinsurance_exp'])) && ($encounter['pinsurance_start']!='0000-00-00') || ($encounter['pinsurance_exp']!='0000-00-00')){
    $sbh = str_replace('-', '', $encounter['pinsurance_nr']);
	$fpdf->Cell(95,5,"- BHYT: giá trị từ: ".formatDate2Local($encounter['pinsurance_start'],$date_format)." đến: ".formatDate2Local($encounter['pinsurance_exp'],$date_format)."   Số:     ".substr($sbh, 0,2).'      '.substr($sbh, 2,3).'    '.substr($sbh, 5,4).'    '.substr($sbh, 9,3).'    '.substr($sbh, 12,3).'    '.$encounter['madkbd'],0,0,'L');//strtoupper($encounter['pinsurance_nr']).'/'.$encounter['madkbd']
    //$fpdf->Cell(95,5,"- BHYT: giá trị từ: ".formatDate2Local($encounter['pinsurance_start'],$date_format)." đến: ".formatDate2Local($encounter['pinsurance_exp'],$date_format)." Số:       ".strtoupper(substr($encounter['pinsurance_nr'],'0','2'))."    ".strtoupper(substr($encounter['pinsurance_nr'],'3','2')).'/'.$encounter['pinsurance_nr'].'/'.$encounter['madkbd'],0,0,'L');
    $y=$fpdf->GetY();
    $x=$fpdf->GetX();
    $fpdf->DrawRect($x,$y,11,5,3);
    $fpdf->DrawRect($x+36,$y,22,5,2);
    //$fpdf->Cell(0,5,,0,0,'L');
} else{
    $fpdf->Cell(98,5,'- BHYT: giá trị từ:.../.../..... đến:.../.../.....  Số ',0,0,'L');
    $y=$fpdf->GetY();
    $x=$fpdf->GetX();
    $fpdf->DrawRect($x,$y,11,5,3);
    $fpdf->DrawRect($x+36,$y,22,5,2);
}
$fpdf->Ln();
if($encounter['citytown_name']){
	$fpdf->Cell(0,5,"- ".$LDAddress.": "." ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name'].str_pad(".", 132-strlen($encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name']." ".$encounter['quanhuyen_name']." ".$encounter['citytown_name']), ".", STR_PAD_RIGHT),0,1,'L');
}else{
	$fpdf->Cell(0,5,"- ".$LDAddress.": ".str_pad(".", 152, ".", STR_PAD_RIGHT),0,1,'L');
}

if($current_dept_name){
	$fpdf->Cell(0,5,"- Đã được điều trị/ khám bệnh tại: ".$current_dept_name.str_pad(".", (105-strlen($current_dept_name)), ".", STR_PAD_RIGHT),0,1,'L');
}else{
	$fpdf->Cell(0,5,"- Đã được điều trị/ khám bệnh tại: ".str_pad(".", 113, ".", STR_PAD_RIGHT),0,1,'L');
}
$fpdf->Cell(0,5,"- Từ ngày: ".formatDate2Local($encounter['encounter_date'],$date_format)." Đến ngày: ".date("d/m/Y",time()),0,1,'L');
$fpdf->Ln();

//Bệnh án
$fpdf->SetFont('','B');
$fpdf->Cell(0,6,'TÓM TẮT BỆNH ÁN',0,1,'C');
$fpdf->SetFont('','');
                 
//$column_width = 185;//mm

$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=24");
$totalnum = 3;//chan doan 3 hang
$num_thisline = 0;
if($info1){
    $info_encounter=$info1->FetchRow();    
    //tinh so dong
    if($info_encounter['notes'] != ''){
    //$num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
		if(strlen($info_encounter['notes'])<78){
			$info_encounter['notes'].= str_pad("", 78-strlen($info_encounter['notes']), ".", STR_PAD_RIGHT);
		}
		$fpdf->MultiCell(0,5,"- Dấu hiệu lâm sàng: ".$info_encounter['notes'],0,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
    }
    //else $num_thisline = 0;
    
}else{
	$fpdf->Cell(0,5,"- Dấu hiệu lâm sàng: ".str_pad(".", 128, ".", STR_PAD_RIGHT),0,1,'L');
	$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
	$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'R');
}

$totalnum = 2;//XN 2 hang
$num_thisline = 0;
$sql="SELECT DISTINCT(dr.reporting_dept) AS reporting_dept FROM care_encounter_diagnostics_report AS dr 
            WHERE dr.encounter_nr=".$enc_nr." 
            ORDER BY dr.create_time DESC";
if($result=$db->Execute($sql)){
    $rows=$result->RecordCount();
    $i=1;
    $xnstr = "";
    while($row=$result->FetchRow()){
        $deptnr_ok=false;
		if($i==1){
			$fpdf->Cell(10,6,'- Các xét nghiệm: ',0,1,'L');
		}
        if($row['reporting_dept']){
			$xnstr= $i.'. '.$row['reporting_dept'];
			$fpdf->Cell(10,5,'',0,0,'L');
			$fpdf->Cell(0,5,$xnstr.str_pad(".", (142-strlen($xnstr)), ".", STR_PAD_RIGHT),0,1,'L');
			if($rows<2){
				$fpdf->Cell(0,5,"   ".str_pad(".", 157, ".", STR_PAD_RIGHT),0,1,'L');
			}
        }
        $i++;
    }     
    if(!$xnstr){
		//$num_thisline = 0;  
		$fpdf->Cell(0,5,"- Các xét nghiệm: ".str_pad(".", 132, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
	}
}

$totalnum = 1;//CD 1 hang
//$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=25");
if($info1){
    $info_encounter=$info1->FetchRow();
	$cd = explode('@@',$info_encounter['notes']);
    if($info_encounter['notes']!=''){
		//$num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
		if(strlen($cd['0'].' -- '.$cd['1'])<78){
			$str= str_pad("", 78-strlen($cd['0'].' -- '.$cd['1']), ".", STR_PAD_RIGHT);
		}
		$fpdf->MultiCell(0,5,"- Chẩn đoán: ".$cd['0'].' -- '.$cd['1'].$str,0,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
    }
}else
{
		$fpdf->Cell(0,5,"- Chẩn đoán: ".str_pad(".", 140, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
}
$totalnum = 4;//thuoc 4 hang
$num_thisline = 0;
require_once($root_path.'include/care_api_classes/class_prescription.php');
$obj=new Prescription();
$thuoc=$obj->getDetailPrescriptionInfo1($enc_nr);
if($thuoc){
    $thuoc1=$db->Execute($thuoc);
	$rows=$thuoc1->RecordCount();
    $xnstr = "";
	$i=1;
    while($info_thuoc=$thuoc1->FetchRow()){		
        $xnstr .= $i.". ".$info_thuoc['product_name'].', ';
        $i++;
    }    
	$fpdf->MultiCell(0,5,"- Thuốc đã dùng: ".$xnstr.str_pad(".", 10, ".", STR_PAD_RIGHT),0,'L');
	if($rows<=3){
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
	}
	$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
	$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
    if(!$xnstr){        
		$fpdf->Cell(0,5,"- Thuốc đã dùng: ".str_pad(".", 148, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
    }
    else $num_thisline = 0;  
   
}
$totalnum = 1;//tt 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=26");
if($info1){
    $info_encounter=$info1->FetchRow();
     if($info_encounter['notes'] != ''){
     $num_thisline = 1;
     
     /*switch($info_encounter['notes'])   {
        case 1: $kqra = 'Khỏi';break;
        case 2: $kqra = 'Đở, giãm';break;
        case 3: $kqra = 'Không thay đổi';break;
        case 4: $kqra = 'Nặng hơn';break;
        default: $kqra = '';break;
    }*/   
		if(strlen($info_encounter['notes'])<78){
			$str= str_pad("", 78-strlen($info_encounter['notes']), ".", STR_PAD_RIGHT);
		}
		$fpdf->MultiCell(0,5,"- Tình trạng người bệnh: ".$info_encounter['notes'].$str,0,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
    }
}else{
		$fpdf->Cell(0,5,"- Tình trạng người bệnh: ".str_pad(".", 122, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
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
	 if(strlen($info_encounter['notes'])<78){
			$str= str_pad("", 78-strlen($info_encounter['notes']), ".", STR_PAD_RIGHT);
		}
		$fpdf->MultiCell(0,5,"- Lý do chuyển viện: ".$info_encounter['notes'].$str,0,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
    }    
}else{
		$fpdf->Cell(0,5,"- Lý do chuyển viện: ".str_pad(".", 128, ".", STR_PAD_RIGHT),0,1,'L');
		$fpdf->Cell(0,5,"   ".str_pad(".", 158, ".", STR_PAD_RIGHT),0,1,'L');
	}

$fpdf->Cell(0,5,"- Chuyển viện hồi: ".substr($encounter['discharge_time'],0,2)." giờ ".substr($encounter['discharge_time'],3,2)." phút, ngày ".substr($encounter['discharge_date'],8,2)." tháng ".substr($encounter['discharge_date'],5,2)." năm ".substr($encounter['discharge_date'],0,4),0,1,'L');

$totalnum = 1;//CD 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=27");
if($info1){
    $info_encounter=$info1->FetchRow();
    if($info_encounter['notes'] != ''){
     $num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
     $fpdf->MultiCell(0,5,'- Phương tiện vận chuyển:  '.$info_encounter['notes'],0,'L');
    }  
}else{
		$fpdf->Cell(0,5,"- Phương tiện vận chuyển: ".str_pad(".", 119, ".", STR_PAD_RIGHT),0,1,'L');
	}
$totalnum = 1;//CD 1 hang
$num_thisline = 0;
$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=28");
if($info1){
    $info_encounter=$info1->FetchRow();
    if($info_encounter['notes'] != ''){
     $num_thisline = (ceil( $fpdf->GetStringWidth($info_encounter['notes']) / ($column_width)));
     $fpdf->MultiCell(0,5,'- Họ tên, chức danh người đưa đi:  '.$info_encounter['notes'],0,'L');
    } 
}else{
		$fpdf->Cell(0,5,"- Họ tên, chức danh người đưa đi: ".str_pad(".", 108, ".", STR_PAD_RIGHT),0,1,'L');
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
