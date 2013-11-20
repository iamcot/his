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
$local_user='ck_opdoku_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');


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
$fpdf->Cell(235,5,'BỆNH VIỆN ĐA KHOA DẦU TIẾNG',0,0,'L');
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

$fpdf->Cell(0,7,'THANH TOÁN PHỤ CẤP PHẪU THUẬT',0,0,'C');
$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(0,7,'PHẦN II - THANH TOÁN',0,0,'C');
$fpdf->Ln(); 
$fpdf->Cell(0,7,'THÁNG.......NĂM........',0,0,'C');

$fpdf->Rect(10,55,275,104);
$fpdf->Line(10,85,285,85);
$fpdf->SetX(11);
$fpdf->SetY(68);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(20,5,'STT',0,0,'L');
$fpdf->Cell(80,5,'HỌ VÀ TÊN',0,0,'L');

for($i=85;$i<152;$i=$i+7){
$fpdf->Line(10,$i,285,$i);
}
$fpdf->Line(20,55,20,148);
$fpdf->Line(65,55,65,148);
$fpdf->Line(91,55,91,159);

$fpdf->Line(235,62,235,159);
$fpdf->Line(260,62,260,159);
$fpdf->Line(91,62,285,62);
$fpdf->Line(91,68,235,68);
$fpdf->Line(163,62,163,159);
$fpdf->Line(101,68,101,159);
$fpdf->Line(132,68,132,159);
$fpdf->Line(173,68,173,159);
$fpdf->Line(204,68,204,159);
$fpdf->SetY(57);
$fpdf->Cell(150,5,'',0,0,'L');
$fpdf->Cell(0,5,'PHẦN THANH TOÁN',0,0,'L');
$fpdf->SetY(63);
$fpdf->Cell(100,5,'',0,0,'L');
$fpdf->Cell(0,5,'CA MỔ LOẠI II                               CA MỔ LOẠI III',0,0,'L');
$fpdf->SetY(72);
$fpdf->Cell(82,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'SỐ  MỨC PHỤ CẤP                         SỐ  MỨC PHỤ CẤP
CA     (đồng/ca)                              CA     (đồng/ca)',0,'L');
$fpdf->SetY(75);
$fpdf->Cell(123,5,'',0,0,'L');
$fpdf->Cell(0,5,'THÀNH TIỀN                                   THÀNH TIỀN',0,0,'L');
$fpdf->SetY(66);
$fpdf->Cell(224,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'     TỔNG
   SỐ TIỀN 
THỰC LÃNH',0,'L');
$fpdf->SetY(68);
$fpdf->Cell(254,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'   KÝ
NHẬN',0,'L');
$fpdf->SetY(60);
$fpdf->Cell(55,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'  CẤP BẬC
 MỔ CHÍNH 
       MỔ
      PHỤ',0,'L');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetY(152);
$fpdf->Cell(20,5,'',0,0,'L');
$fpdf->Cell(0,5,'TỔNG CỘNG :',0,0,'L');

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetY(162);

$fpdf->Cell(0,5,'Tổng số tiền bằng chữ :(................................................................................................)',0,0,'L');
$fpdf->Ln();$fpdf->SetFont('DejaVu','I',11);
$fpdf->Cell(0,5,'Ngày........tháng........năm..........',0,0,'R');
$fpdf->Ln();
$fpdf->SetX(25);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(218,5,'NGƯỜI LẬP                                              PHÒNG KẾ TOÁN',0,0,'L');
$fpdf->Cell(0,5,'GIÁM ĐỐC',0,0,'L');

	  //$fpdf->Output();
$fpdf->Output('phucapmo.pdf', 'I');
/*
$fpdf = new tFPDF('P','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Benh An Ngoai Tru');
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


$fpdf->SetFont('DejaVu','B',18);
$fpdf->Ln(); 
$fpdf->Cell(0,7,'BỆNH ÁN NGOẠI TRÚ',0,0,'C');

$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(140,5,'Sở Y tế:..................................',0,0,'L');
$fpdf->Cell(50,5,'Số ngoại trú:.......................',0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(140,5,'Bệnh viện:.............................',0,0,'L');
$fpdf->Cell(50,5,'Số lưu trữ:...........................',0,0,'L');
$fpdf->Ln(); 


$fpdf->SetFont('','',11);
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(0,5,'KHOA: .................................................',0,1,'C');
$fpdf->Ln();

//Thông tin bệnh nhân
$fpdf->SetFont('','B',11);
$fpdf->Cell(90,7,'I. HÀNH CHÍNH',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'Tuổi',0,1,'R');

$y=$fpdf->GetY();		
$fpdf->Cell(120,5,'1. Họ và tên: (In hoa)............................................. 2. Sinh ngày',0,0,'L');
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,8);
$fpdf->DrawRect($x+58,$y,5,4.5,2);
$fpdf->Ln();

$y=$fpdf->GetY();
$x=$fpdf->GetX();	
$fpdf->Cell(0,5,'3. Giới:         1.Nam               2.Nữ                          4. Nghề nghiệp:......................................................',0,1,'L');
$fpdf->DrawRect($x+36,$y,5,4.5,1);
$fpdf->DrawRect($x+61,$y,5,4.5,1);
$fpdf->DrawRect($x+178,$y,5,4.5,2);

$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(0,5,'5. Dân tộc:............................................                  6. Ngoại kiều:.........................................................',0,1,'L');
$fpdf->DrawRect($x+70,$y,5,4.5,2);
$fpdf->DrawRect($x+178,$y,5,4.5,2);

$fpdf->Cell(0,5,'7. Địa chỉ: Số nhà:.............. Thôn, phố................................... Xã, phường........................................................',0,1,'L');

$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(0,5,'Huyện (Q,Tx):.......................................                  Tỉnh, thành phố................................................',0,1,'L');
$fpdf->DrawRect($x+70,$y,5,4.5,2);
$fpdf->DrawRect($x+172,$y,5,4.5,3);

$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(0,5,'8. Nơi làm việc:............................................. 9.Đối tượng: 1.BHYT         2.Thu phí         3.Miễn        4.Khác',0,0,'L');
$fpdf->DrawRect($x+116,$y,5,4.5,1);
$fpdf->DrawRect($x+142,$y,5,4.5,1);
$fpdf->DrawRect($x+163,$y,5,4.5,1);
$fpdf->DrawRect($x+184,$y,5,4.5,1);
$fpdf->Ln();

$fpdf->Cell(129,5,'10. BHYT: giá trị đến ngày.......tháng........năm...............    Số thẻ BHYT  ',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,10,4.5,4);
$fpdf->DrawRect($x+44,$y,16,4.5,1);
$fpdf->Ln();
$fpdf->Cell(0,5,'11. Họ tên, địa chỉ người nhà khi cần báo tin:.................................................................................................. ',0,1,'L');
$fpdf->Cell(0,5,'...................................................................................Điện thoại số................................................................. ',0,1,'L');
$fpdf->Cell(0,5,'12. Đến khám bệnh lúc:..............giờ.............phút.............ngày.............tháng.............năm................ ',0,1,'L');
$fpdf->Cell(138,5,'13. Chẩn đoán của nơi giới thiệu:...................................................................... ',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->Cell(40,5,'1. Y tế:             2.Tự đến',0,0,'L');
$fpdf->DrawRect($x+15,$y,5,4.5,1);
$fpdf->DrawRect($x+45,$y,5,4.5,1);
$fpdf->Ln();

//Lý do vào viện
$fpdf->SetFont('','B',11);
$fpdf->Cell(40,7,'II. LÝ DO VÀO VIỆN:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,7,'.....................................................................................................................................',0,1,'L');

//Hỏi bệnh
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,7,'III. HỎI BỆNH:',0,1,'L');
$fpdf->Cell(41,5,'1. Quá trình bệnh lý:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'....................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();

$fpdf->SetFont('','B',11);
$fpdf->Cell(30,5,'2. Tiền sử bệnh:',0,1,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(20,5,'+ Bản thân:',0,0,'L');
$fpdf->Cell(0,5,'.......................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(20,5,'+ Gia đình:',0,0,'L');
$fpdf->Cell(0,5,'.......................................................................................................................................................',0,0,'L');
$fpdf->Ln();

//Khám bệnh
$fpdf->SetFont('','B',11);
$fpdf->Cell(140,7,'IV.KHÁM BỆNH:',0,0,'L');
$y1=$fpdf->GetY()+2;
$x1=$fpdf->GetX();
$fpdf->Ln();
$fpdf->Cell(25,5,'1. Toàn thân:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(70,5,'.....................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'...........................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(32,5,'2. Các bộ phận:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'..............................................................................................',0,0,'L');

$fpdf->Ln();
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->DrawRect($x+110,$y,5,20,1);
$fpdf->Ln();



//Kẻ ô lấy dấu sinh hiệu
$fpdf->SetY($y1);
$fpdf->SetX($x1);
$fpdf->MultiCell(0,5,"Mạch........................lần/ph
Nhiệt độ.........................°C
Huyết áp......./.........mmHg
Nhịp thở...................lần/ph
Cân nặng.......................kg",1,'R');
$fpdf->Ln(15);



$fpdf->SetFont('','B',11);
$fpdf->Cell(65,5,'3. Tóm tắt kết quả cận lâm sàng:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'..............................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(45,5,'4. Chẩn đoán ban đầu:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(23,5,'5. Đã xử lý:',0,0,'L');
$fpdf->SetFont('','I',11);
$fpdf->Cell(33,5,'(thuốc, chăm sóc)',0,0,'L');
$fpdf->Cell(0,5,'......................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,0,'L');
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(50,5,'6. Chẩn đoán khi ra viện:',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(115,5,'..............................................................................................  Mã',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,4);
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(58,5,'7. Điều trị ngoại trú từ ngày: ',0,0,'L');
$fpdf->SetFont('','',11);
$fpdf->Cell(0,5,'........./.........../.......... đến ngày ........../.........../..........',0,0,'L');
$fpdf->Ln(10);



//Ký tên

$x=$fpdf->GetX();$y=$fpdf->GetY();
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,'Ngày........tháng........năm........',0,1,'R');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->SetX(135);
$fpdf->Cell(64,5,'Bác sĩ khám bệnh',0,1,'C');
$fpdf->SetX(135);
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetX(135);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');

$fpdf->SetY($y);
$fpdf->Cell(64,5,' ',0,1,'C');
$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(64,5,'Giám đốc bệnh viện',0,1,'C');
$fpdf->Cell(0,25,' ',0,1,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(60,5,'Họ tên:.....................................',0,1,'R');


//-----------------------Page2-------------------------------------
$fpdf->AddPage();
$fpdf->Ln();
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,10,'TỔNG KẾT BỆNH ÁN',0,1,'L');
$fpdf->Cell(0,5,'1. Quá trình bệnh lý và diễn biến lâm sàng:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<9;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'2. Tóm tắt kết quả xét nghiệm cận lâm sàng có giá trị chẩn đoán:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}
$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'3. Chẩn đoán ra viện:',0,1,'L');
$fpdf->SetFont('','',11);

$fpdf->Cell(165,5,'- Bệnh chính:..............................................................................................................................',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,4);
$fpdf->Ln();

$fpdf->Cell(165,5,'- Bệnh kèm theo (nếu có):.........................................................................................................',0,0,'L');
$y=$fpdf->GetY();
$x=$fpdf->GetX();
$fpdf->DrawRect($x,$y,5,4.5,4);
$fpdf->Ln();

$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'4. Phương pháp điều trị:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}

$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'5. Tình trạng người bệnh ra viện:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}

$fpdf->SetFont('','B',11);
$fpdf->Cell(0,5,'6. Hướng điều trị và các chế độ tiếp theo:',0,1,'L');
$fpdf->SetFont('','',11);
for($i=0;$i<5;$i++)
{
	$fpdf->Cell(0,5,'.........................................................................................................................................................................',0,1,'L');
}
$fpdf->Ln();

$y=$fpdf->GetY();
$fpdf->Cell(75,6,'Hồ sơ, phim, ảnh',1,0,'C');
$x=$fpdf->GetX();
$fpdf->Ln();
$fpdf->Cell(45,6,'Loại',1,0,'C');
$x1=$fpdf->GetX();
$fpdf->Cell(30,6,'Số tờ',1,1,'C');
$y1=$fpdf->GetY();
$fpdf->MultiCell(45,6,"- X-Quang\n- CT Scanner\n- Siêu âm\n- Xét nghiệm\n- Khác\n- Toàn bộ hồ sơ",1,'L');
$fpdf->SetY($y1);$fpdf->SetX($x1);
$fpdf->Rect($x1,$y1,30,36);
$fpdf->SetY($y);$fpdf->SetX($x);
$fpdf->MultiCell(55,6,"Người giao hồ sơ\n\n\nHọ tên:............................",1,'C');
$fpdf->SetX($x);
$fpdf->MultiCell(55,6,"Người nhận hồ sơ\n\n\nHọ tên:............................",1,'C');
$fpdf->SetY($y);$fpdf->SetX($x+55);
$fpdf->MultiCell(58,6,"Ngày.......tháng.......năm............\nBác sĩ điều trị\n\n\n\n\n\nHọ tên:............................",1,'C');




//$fpdf->Output();
$fpdf->Output('BenhAnNgoaiTru.pdf', 'I');
*/

?>
