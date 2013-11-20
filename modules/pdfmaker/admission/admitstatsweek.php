<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$report_textsize=12;
$report_titlesize=16;
$report_auxtitlesize=10;
$report_authorsize=10;

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE 2X Integrated Hospital Information System beta 1.0.09 - 2003-11-25
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@latorilla.com
*
* See the file "copy_notice.txt" for the licence notice
*/
//$lang_tables[]='startframe.php';

$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
//define('NO_2LEVEL_CHK',1);
//define('NO_CHAIN',TRUE);
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');


# Get the encouter data
$enc_obj=&new Encounter();


/*
$classpath=$root_path.'classes/phppdf/';
$fontpath=$classpath.'fonts/';
# Load and create pdf object
include($classpath.'class.ezpdf.php');
$pdf=& new Cezpdf();
*/

//----------------Tuyen
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include($classpathFPDF.'tfpdf.php');
$fpdf= new tFPDF();
$fpdf->AddPage();
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);
$fpdf->SetFont('DejaVu','',12);
$fpdf->Ln();
$fpdf = new tFPDF('P','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Thống kê khoa khám bệnh');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(15);
$fpdf->SetLeftMargin(15);
$fpdf->SetTopMargin(25);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);



$fpdf->Ln(5); 
$fpdf->Ln(5); 
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(130,5,'Sở Y tế:..................................',0,0,'L');
$fpdf->Cell(50,5,'Bệnh viện:............................',0,0,'L');
$fpdf->SetFont('DejaVu','B',16);
$fpdf->Ln(8); 
$fpdf->Cell(0,7,"BÁO CÁO TUẦN TỪ NGÀY ".@formatDate2Local($sday,$date_format)." đến ".@formatDate2Local($eday,$date_format),0,0,'C');
$fpdf->Ln(20); 
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(0,5,"* Tổng  : ".$enc_obj->getStatsByWeek($sday,$eday),0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(0,5,"   BHYT : ".$enc_obj->getStatsByWeekBHYT($sday,$eday),0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(0,5,"   Không BHYT : ".(($enc_obj->getStatsByWeek($sday,$eday))-($enc_obj->getStatsByWeekBHYT($sday,$eday))),0,0,'L');
$fpdf->Ln(); 
$fpdf->Ln(); 
$fpdf->Cell(115,5,"",0,0,'L');
$fpdf->Cell(70,5,"BHYT     Không BHYT",0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(118,5,"* Khám ngoại : ".$enc_obj->getStatsByWeekNgoai($sday,$eday),0,0,'L');
$khamngoaikbh=$enc_obj->getStatsByWeekNgoai($sday,$eday) - $enc_obj->getStatsByWeekNgoaiBHYT($sday,$eday);
$fpdf->Cell(70,5,$enc_obj->getStatsByWeekNgoaiBHYT($sday,$eday)."                 ".$khamngoaikbh,0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(118,5,"   Khám nội : ".$enc_obj->getStatsByWeekNoi($sday,$eday),0,0,'L');
$khamnoikbh=$enc_obj->getStatsByWeekNoi($sday,$eday) - $enc_obj->getStatsByWeekNoiBHYT($sday,$eday);
$fpdf->Cell(70,5,$enc_obj->getStatsByWeekNoiBHYT($sday,$eday)."                 ".$khamnoikbh,0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(118,5,"   Khám nhi : ".$enc_obj->getStatsByWeekNhi($sday,$eday),0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(118,5,"   Khám nhi <6t : ".$enc_obj->getStatsByWeekNhi6($sday,$eday),0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(118,5,"   Khám >60t : ".$enc_obj->getStatsByWeekGia($sday,$eday),0,0,'L');
$fpdf->Ln(); 
$fpdf->Ln();
$fpdf->Cell(118,5,"* Nhập viện : ".$enc_obj->getStatsByWeekInPatient($sday,$eday),0,0,'L');
$fpdf->Ln();
$fpdf->Cell(118,5,"  Nhập viện ngoại: ".$enc_obj->getStatsByWeekInPatientNgoai($sday,$eday),0,0,'L');
$fpdf->Ln();
$fpdf->Cell(118,5,"  Nhập viện nội: ".$enc_obj->getStatsByWeekInPatientNoi($sday,$eday),0,0,'L');
$fpdf->Ln();
$fpdf->Cell(118,5,"  Nhập viện HSCC: ".$enc_obj->getStatsByWeekInPatientHSCC($sday,$eday),0,0,'L');
$fpdf->Ln();
$fpdf->Cell(118,5,"  Nhập viện YHCT: ".$enc_obj->getStatsByWeekInPatientYHCT($sday,$eday),0,0,'L');
$fpdf->Ln();
$fpdf->Cell(118,5,"  Nhập viện Sản: ".$enc_obj->getStatsByWeekInPatientSan($sday,$eday),0,0,'L');
$fpdf->Ln();
$fpdf->Cell(118,5,"  Nhập viện Nhiễm: ".$enc_obj->getStatsByWeekInPatientNhiem($sday,$eday),0,0,'L');
$fpdf->Ln();
$fpdf->Ln();
$fpdf->Cell(0,5,"* Khám sức khỏe: ",0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,"  Tuyển dụng: ",0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,"  Lái xe:      (A1:     ,A2:     ,B1:     ,B2:     ,C:     ,D:     ,E:     )",0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,"  Học sinh: ",0,0,'L');
$fpdf->Ln();
$fpdf->Ln();
$fpdf->Cell(0,5,"*  CBTC:                                                      TNGT/BHYT",0,0,'L');
$fpdf->Ln();
$fpdf->Ln();
$fpdf->Cell(0,5,"*  Cấp giấy giới thiệu chuyển viện: ",0,0,'L');
$fpdf->Ln();
$fpdf->Ln();
$fpdf->Cell(0,5,"*  Cấp giấy nghĩ ốm: ",0,0,'L');
$fpdf->Ln();
$fpdf->Ln();
$x=$fpdf->GetX();$y=$fpdf->GetY();
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,"Ngày ".date("d")." Tháng ".date("m")." Năm ".date("Y"),0,1,'R');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetX(135);
$fpdf->Cell(64,5,'Người báo cáo',0,1,'C');
$fpdf->SetX(135);
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');

$fpdf->SetY($y);
$fpdf->Cell(64,5,' ',0,1,'C');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(64,5,'Trưởng khoa',0,1,'C');
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');



$fpdf->Output();
?>