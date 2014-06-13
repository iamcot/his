<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$report_textsize=12;
$report_titlesize=16;
$report_auxtitlesize=10;
$report_authorsize=10;
$sex ='';
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='emr.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$obj=new Encounter($encounter_nr);
$obj->Hoichan();
if($enc_nr){
    $pregs=&$obj->getKiemtuvong($enc_nr,'_ENC',"$nr");
    $status=$obj->loadEncounterData1($enc_nr,1);
}else{
    $pregs=&$obj->getKiemtuvong($pid,'_REG',"$nr");
    require_once($root_path.'include/care_api_classes/class_person.php');
    $person_obj=new Person();
    $list='title,name_first,name_last,name_2,name_3,name_middle,name_maiden,name_others,date_birth,
                            sex,addr_str,addr_str_nr,addr_zip,addr_citytown_nr,addr_quanhuyen_nr,addr_phuongxa_nr,photo_filename,tuoi';

    $person_obj->setPID($pid);
    if($row=&$person_obj->getValueByList($list)) {
        foreach($row AS $k=>$v){
            $status[$k]=$v;
        }      
    }    
}
if($pregs) $pregnancy=$pregs->FetchRow();
if($status){
    $enc_nr=$pregnancy['encounter_nr'];
    $status1=$obj->loadEncounterData1($enc_nr,1);
    if($status1['encounter_date']){
        $ngaynhapvien=substr($status1['encounter_date'],0,10);
        $convert_ngaynhapvien=formatDate2Local($ngaynhapvien,$date_format);
        $timenhapvien=substr($status1['encounter_date'],11,10);
    }
    if($status['encounter_date']){
        $ngaynhapvien=substr($status['encounter_date'],0,10);
        $convert_ngaynhapvien=formatDate2Local($ngaynhapvien,$date_format);
        $timenhapvien=substr($status['encounter_date'],11,10);
    }
}
//Thong tin benh nhan

$encounter=$obj->getLoadedEncounterData();
if($status1['sex']=='m'){
    $sex=$LDMale;
}else if($status1['sex']=='f'){
    $sex=$LDFemale;
}else{
	$sex='Không rõ';
}
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
$fpdf->SetTitle('TRICH BIEN BAN KIEM DIEM TU VONG');
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
$fpdf->Cell(50,5,'MS: 41/BV-01',0,0,'L');
$fpdf->Ln(); 
$fpdf->SetFont('','B');
$fpdf->Cell(135,5,PDF_HOSNAME,0,0,'L');
$fpdf->SetFont('','');
if(!empty($pregnancy['sovaovien_notes']))  {
$fpdf->Cell(150,5,'Số vào viện: ...'.$pregnancy['sovaovien_notes']."...",0,1,'L');
} else  $fpdf->Cell(150,5,'Số vào viện:................................',0,1,'L');

$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','B',18);
$fpdf->Ln(10); 
$fpdf->Cell(0,7,'TRÍCH BIÊN BẢN KIỂM ĐIỂM TỬ VONG ',0,0,'C');
$fpdf->Ln(10);

$fpdf->SetFont('','BI',11);
$fpdf->UnderlineText(true);
$fpdf->SetFont('','');

//Thông tin bệnh nhân
$s_obj->BASIC_String();	
$fpdf->Cell(125,5,"- ".$LDFullName_patient.": ...".$s_obj->upper($status['name_last']." ".$status['name_first']).'...',0,0,'L');

if($status['thang']>0){
    $thang=     $status['thang'];
} else {
    $namsinh=    $status['date_birth'];
    $tuoi=date("Y")-$namsinh;
}
 if($tuoi>6){

$fpdf->Cell(0,5,"Tuổi ...".$tuoi."... Giới tính ...".$sex.'...',0,1,'L');
}    else {
     $fpdf->Cell(0,5," ".$thang." Tháng. Giới tính ...".$sex.'...',0,1,'L');
 }
if($timenhapvien)
    $fpdf->Cell(98,5,"- ".$LDVaovien.": ... ".substr($timenhapvien,0,2).' giờ '.substr($timenhapvien,3,2).' phút ...',0,0,'L');
else
    $fpdf->Cell(98,5,"- ".$LDVaovien.':..................................................................',0,0,'L');

if($convert_ngaynhapvien)
    $fpdf->Cell(0,5,$LDDate.": ... ".$convert_ngaynhapvien.' ...',0,1,'L');
else
    $fpdf->Cell(0,5,$LDDate.':..................................................................',0,1,'L');

if($pregnancy['time_tuvong'])
    $fpdf->Cell(98,5,"- ".$LD['time_tuvong'].": ... ".substr($pregnancy['time_tuvong'],0,2).' giờ '.substr($pregnancy['time_tuvong'],3,2).' phút ...',0,0,'L');
else
    $fpdf->Cell(98,5,"- ".$LD['time_tuvong'].':..................................................................',0,0,'L');

if($pregnancy['date_tuvong'])
    $fpdf->Cell(0,5,$LDDate.": ... ".formatDate2Local($pregnancy['date_tuvong'],$date_format).' ...',0,1,'L');
else
    $fpdf->Cell(0,5,$LDDate.':..................................................................',0,1,'L');

$sql1="SELECT d.LD_var
            FROM care_encounter AS e,
                 care_person AS p,
                 care_department AS d
            WHERE p.pid=e.pid
                AND e.encounter_nr=".$enc_nr."
                AND e.current_dept_nr=d.nr";
if($result1=$db->Execute($sql1)){
    $rows2=$result1->FetchRow();
}
$current_dept_nr=$rows2['LD_var'];
if(!empty($current_dept_nr)){
    $fpdf->Cell(0,5,"- ".$LDDept.":  ... ".$$current_dept_nr.' ...',0,1,'L');
}else{
    $fpdf->Cell(0,5,"- ".$LDDept.':.................................................................',0,1,'L');
}

if($pregnancy['time_kiemtuvong'])
    $fpdf->Cell(85,5,"- ".$LD['time_kiemtuvong'].": ...".substr($pregnancy['time_kiemtuvong'],0,2).' giờ '.substr($pregnancy['time_kiemtuvong'],3,2).' phút ...',0,0,'L');
else
    $fpdf->Cell(85,5,"- ".$LD['time_kiemtuvong'].':..................................................................',0,0,'L');

if($pregnancy['date_kiemtuvong'])
    $fpdf->Cell(0,5,$LDDate.": ...".formatDate2Local($pregnancy['date_kiemtuvong'],$date_format).' ...',0,1,'L');
else
    $fpdf->Cell(0,5,$LDDate.':..................................................................',0,1,'L');

$chutoa=explode(";",$pregnancy['chutoa_notes']);
if(!empty($pregnancy['chutoa_notes'])){
    $fpdf->Cell(85,5,"- ".$LD['chutoa_notes'].": ...".$s_obj->upper($chutoa[0]).' ...',0,0,'L');
}else {
    $fpdf->Cell(85,5,"- ".$LD['chutoa_notes'].": ................................................",0,0,"L");
}

$thuki=explode(";",$pregnancy['thuki_notes']);
if(!empty($pregnancy['thuki_notes'])){
    $fpdf->Cell(0,5,$LD['thuki_notes'].": ...".$s_obj->upper($thuki[0]).' ...',0,1,'L');
}else {
    $fpdf->Cell(0,5,$LD['thuki_notes'].": ................................................",0,1,"L");
}
$thanhvien=explode(";",$pregnancy['thanhvien_notes']);
$fpdf->Cell(0,6,'- '.$LD['thanhvien_notes'].':',0,1,'L');
for($i=1;$i<sizeof($thanhvien);$i++)
{
    if($i!=1){
            $thanhvien1=substr($thanhvien[$i-1],2);
        }else
            $thanhvien1=$thanhvien[$i-1];
	if(!empty($thanhvien1)){
        $fpdf->Cell(10,5," ",0,0,'L');
        $fpdf->Cell(0,5,($i).". ".$s_obj->upper($thanhvien1),0,1,'L');
    }
}

$fpdf->Cell(0,6,'- '.$LD['tomtat_notes'].': ',0,1,'L');

if(!empty($pregnancy['tomtat_notes'])){
    $fpdf->Cell(10,5," ",0,0,'L');
    $fpdf->MultiCell(0,5,$pregnancy['tomtat_notes'],0,'L');
}else {
    for($i=0;$i<3;$i++){
        $fpdf->Cell(0,5,".................................................................................................................................................................",0,1,"L");
    }
}

$fpdf->Cell(0,6,'- '.$LD['ketluan_notes'].': ',0,1,'L');
$fpdf->Cell(10,5," ",0,0,'L');
if(!empty($pregnancy['ketluan_notes'])){
    $fpdf->MultiCell(0,5,$pregnancy['ketluan_notes'],0,'L');
}else {
    for($i=0;$i<3;$i++){
        $fpdf->Cell(10,5," ",0,0,'L');
        $fpdf->Cell(0,5,"....................................................................................................................................................",0,1,"L");
    }
}

$fpdf->Ln();
//$fpdf->SetX(0);
//$fpdf->SetY(250);
//Ký tên
$fpdf->SetFont('','I');
$fpdf->Cell(0,5,'Ngày...'.date('d').'...tháng...'.date('m').'...năm...'.date('Y').'...',0,1,'R');
$fpdf->SetFont('','B');
$fpdf->Cell(60,5,'THƯ KÍ',0,0,'C');
$fpdf->Cell(60,5,' ',0,0,'C');
$fpdf->Cell(60,5,'CHỦ TỌA',0,1,'C');
$fpdf->SetFont('','');
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->Cell(60,5,'Họ tên: ...'.$s_obj->upper($thuki[0]).'...',0,0,'C');
$fpdf->Cell(60,5,' ',0,0,'C');
$fpdf->Cell(60,5,'Họ tên:...'.$s_obj->upper($chutoa[0]).'...',0,1,'C');


//$fpdf->Output();
$fpdf->Output('GiayChuyenVien.pdf', 'I');


?>
