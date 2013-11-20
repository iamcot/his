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
$local_user = 'ck_opdoku_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');

require_once ($root_path . 'include/care_api_classes/class_encounter_op.php');
$enc_op_obj = new OPEncounter ( );
$list=$enc_op_obj->get_personell_op();

# Get the encouter data

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
$fpdf->Cell(235,5,$cell,0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(50,5,'Mẫu số C01 - YT',0,0,'L');
$fpdf->Ln(); 

$fpdf->SetX($fpdf->lMargin);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(220,5,'KHOA:.............................',0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->MultiCell(70,5,'(Ban hành theo QĐ số 144 BYT)
  ngày 31/01/1997 của Bộ y tế',0,'L');
  $fpdf->SetX(30);
$fpdf->Cell(50,5,'*********',0,0,'L');
$fpdf->Ln(); 


$fpdf->SetFont('DejaVu','B',18);

$fpdf->Cell(0,7,'BẢNG CHẤM CÔNG CA MỔ ĐƯỢC PHỤ CẤP',0,0,'C');
$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(0,7,'PHẦN I - CHẤM CÔNG',0,0,'C');
$fpdf->Ln(); 
$fpdf->Cell(0,7,'THÁNG  '.$pmonth.' NĂM  '.$pyear.'',0,0,'C');

$fpdf->Rect(10,55,275,104);
$fpdf->Line(10,75,285,75);
$fpdf->SetX(11);
$fpdf->SetY(62);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(10,5,'STT',0,0,'L');
$fpdf->Cell(80,5,'      HỌ VÀ TÊN',0,0,'L');
$fpdf->Line(91,68,235,68);
for($i=75;$i<159;$i=$i+7){
    $fpdf->Line(10,$i,285,$i);
}
$count=1;
$temp=70;
$list3=$enc_op_obj->list_doctor_op("", "III","");
while($personell_nr=$list->FetchRow()){
    $fpdf->SetFont('DejaVu','',11);
    $info=$enc_op_obj->list_doctor_op($personell_nr['personell_nr'], "", "");
    $info_detail=$enc_op_obj->get_info($personell_nr['personell_nr']);
    $info_personell=$info_detail->FetchRow();
    while($personell=$info->FetchRow()){
        if( $pmonth==substr($personell["date_request"],5,2) && $pyear==substr($personell["date_request"],0,4)
            && ($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật" || $info_personell["job_function_title"]=="Phụ Mổ") ){
            $fpdf->SetY($temp);
            $temp=$temp+7;
            $fpdf->Cell(11,18,"  ".$count,0,0,'L');
            $count++;
            $fpdf->Cell(55,18,$info_personell["name_last"].' '.$info_personell["name_first"],0,0,'L');
            if($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật"){
                $fpdf->Cell(120,18," C",0,0,'L');
            }else{
                $fpdf->Cell(85,18," P",0,0,'L');
            }
        }
    }
}

$fpdf->Line(20,55,20,159);
$fpdf->Line(65,55,65,159);
$fpdf->Line(91,55,91,159);
for($i=91;$i<241;$i=$i+6){
    $fpdf->Line($i,68,$i,159);    
}

$fpdf->Line(235,55,235,159);
$fpdf->Line(260,62,260,159);
$fpdf->Line(235,62,285,62);
$fpdf->SetY(57);
$fpdf->Cell(228,5,'',0,0,'L');
$fpdf->Cell(0,5,'TỔNG CỘNG CA MỔ',0,0,'L');
$fpdf->SetY(64);
$fpdf->Cell(228,5,'',0,0,'L');
$fpdf->MultiCell(0,5,' CA MỔ        CA MỔ
 LOẠI II       LOẠI III',0,'L');
$fpdf->SetY(60);
$fpdf->Cell(130,5,'',0,0,'L');
$fpdf->Cell(50,5,'NGÀY TRONG THÁNG',0,0,'L');
$fpdf->SetY(57);
$fpdf->Cell(55,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'  CẤP BẬC
      MỔ
CHÍNH PHỤ',0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetY(53);
$fpdf->Cell(55,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'',0,'L');
$fpdf->SetY(146);

$fpdf->Ln(15);
$fpdf->SetFont('DejaVu','I',11);
$fpdf->Cell(0,5,'Ngày........tháng........năm..........',0,0,'R');
$fpdf->Ln();
$fpdf->SetX(25);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(210,5,'NGƯỜI DUYỆT                                              PHỤ TRÁCH BỘ PHẬN',0,0,'L');
$fpdf->Cell(0,5,'NGƯỜI CHẤM CÔNG',0,0,'L');
$fpdf->Ln(30);
$fpdf->SetFont('DejaVu','',12);
$fpdf->Cell(0,5,'KÝ HIỆU CHẤM CÔNG: (ca mổ loại II: II, ca mổ loại III: III)',0,0,'C');
	  //$fpdf->Output();
$fpdf->Output('chamcong.pdf', 'I');
?>
