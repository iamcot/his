<?php
/*
create by: vy
date:30/01/2011
*/
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
define('NO_2LEVEL_CHK',1);
$local_user='ck_opdoku_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once ($root_path . 'include/care_api_classes/class_encounter_op.php');
$enc_op_obj = new OPEncounter ( );
 $sql="SELECT yc.date_request,yc.encounter_nr,yc.clinical_info,yc.level_method,ps.name_last,ps.name_first,ps.date_birth,ps.addr_str_nr,ps.addr_str
     FROM care_op_med_doc AS hs
            LEFT JOIN care_encounter_op AS tb ON tb.nr=hs.encounter_op_nr
            LEFT JOIN care_test_request_or AS yc ON yc.batch_nr=tb.batch_nr
            LEFT JOIN care_encounter AS enc ON enc.encounter_nr=yc.encounter_nr
            LEFT JOIN care_person AS ps ON ps.pid=enc.pid
			";
$list1=$db->Execute($sql);
			
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");

$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$fpdf = new tFPDF('L','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Bang Cham Cong');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(10);
$fpdf->SetLeftMargin(10);
$fpdf->SetTopMargin(15);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);




$fpdf->SetFont('DejaVu','',12);

$fpdf->SetX($fpdf->lMargin);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(185,5,'BỆNH VIỆN ĐA KHOA DẦU TIẾNG',0,0,'L');
$fpdf->SetFont('DejaVu','',12);
$fpdf->Cell(70,5,'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM',0,0,'L');
$fpdf->Ln(); 

$fpdf->SetX(15);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(198,5,'KHOA:.............................',0,0,'L');
$fpdf->SetFont('DejaVu','',12);
$fpdf->Cell(0,5,'Độc lập - Tự do - Hạnh phúc',0,0,'L');

$fpdf->Line(215,20,266,20);
 
  $fpdf->Ln();
  $fpdf->SetX(35);
$fpdf->Cell(50,5,'*********',0,0,'L');
 $fpdf->Ln();


$fpdf->SetFont('DejaVu','B',16);

$fpdf->Cell(0,7,'DANH SÁCH NGƯỜI BỆNH ĐƯỢC THỰC HIỆN PHẨU THUẬT - THỦ THUẬT',0,0,'C');
$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','',12);
$fpdf->Cell(0,7,"Tháng ".$pmonth." Năm ".$pyear,0,0,'C');
$fpdf->SetX(10);
$fpdf->SetY(25);
$fpdf->Rect(10,45,275,97);
$fpdf->Line(10,63,285,63);
$fpdf->SetX(11);
$fpdf->SetY(52);
$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(20,5,'STT',0,0,'L');
$fpdf->Cell(39,5,'HỌ VÀ TÊN',0,0,'L');
$fpdf->Cell(20,5,'TUỔI',0,0,'L');
$fpdf->Cell(100,5,'ĐỊA CHỈ',0,0,'L');
$fpdf->Cell(30,5,'CHẨN ĐOÁN',0,0,'L');
$fpdf->Line(20,45,20,142);
$fpdf->Line(70,45,70,142);
$fpdf->Line(80,45,80,142);
$fpdf->Line(115,45,115,142);
$fpdf->Line(140,45,140,142);
$fpdf->Line(165,45,165,142);
$fpdf->SetY(47);
$fpdf->Cell(110,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'  SỐ
BỆNH
   ÁN',0,'L');
   $fpdf->SetY(50);
$fpdf->Cell(135,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'PHIẾU
  THU',0,'L');
$fpdf->SetY(50);
$fpdf->Cell(232,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'   PHẪU       PHẪU
THUẬT II  THUẬT III',0,'L');
$fpdf->Line(242,45,242,142);
$fpdf->Line(262,45,262,142);
for($i=70;$i<=142;$i=$i+6){
	$fpdf->Line(10,$i,285,$i);
}
$count=1;
$temp=65;
if($list1->RecordCount()){
  while($patient=$list1->FetchRow()){
	$fpdf->SetFont('DejaVu','',12);
    if( $pmonth==substr($patient["date_request"],5,2) && $pyear==substr($patient["date_request"],0,4)){
		$fpdf->SetY($temp);
		$temp=$temp+6;
		$years=date("Y-m-d")-$patient["date_birth"];
        $fpdf->Cell(11,5,"  ".$count,0,0,'L');
		$fpdf->Cell(50,5,$patient["name_last"]." ".$patient["name_first"],0,0,'L');
		$fpdf->Cell(8,5,$years,0,0,'L');
		$fpdf->Cell(35,5,$patient["addr_str"],0,0,'L'); 
		$fpdf->Cell(25,5,$patient["encounter_nr"],0,0,'L'); 
		$fpdf->Cell(25,5,'',0,0,'L'); 
		$fpdf->Cell(85,5,$patient["clinical_info"],0,0,'L');
		if($patient["level_method"]==II){
			$fpdf->Cell(12,5,'X',0,0,'L');
		}else{
			$fpdf->Cell(20,5,'',0,0,'L');
			$fpdf->Cell(12,5,'X',0,0,'L');
		}                   
		$count++;
                    
        }                    
    }
}
$fpdf->Ln();

$fpdf->SetY(145);
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(0,5,'      Bằng chữ :.................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('DejaVu','I',11);
$fpdf->Cell(0,5,'Ngày........tháng........năm..........',0,0,'R');
$fpdf->Ln();
$fpdf->SetX(17);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(227,5,'GIÁM ĐỐC                                                       TRƯỞNG KHOA',0,0,'L');
$fpdf->Cell(0,5,'NGƯỜI LẬP',0,0,'L');


	  //$fpdf->Output();
$fpdf->Output('chamcong.pdf', 'I');


?>