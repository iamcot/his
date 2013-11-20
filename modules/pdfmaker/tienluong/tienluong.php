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

require_once($root_path.'include/care_api_classes/class_insurance.php');
$insurance_obj=new Insurance;
require_once($root_path.'include/care_api_classes/class_measurement.php');
$measurement_obj=new Measurement;
require_once($root_path.'include/care_api_classes/class_ecombill.php');
$ecombill_obj=new eComBill;
// Optionally define the filesystem path to your system fonts
// otherwise tFPDF will use [path to tFPDF]/font/unifont/ directory
// define("_SYSTEM_TTFONTS", "C:/Windows/Fonts/");





$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);

include_once($classpathFPDF.'tfpdf.php');

$fpdf = new tFPDF('L','mm','a4');
$fpdf->AddPage();
$fpdf->SetTitle('Bang Tien Luong');
$fpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$fpdf->SetRightMargin(10);
$fpdf->SetLeftMargin(10);
$fpdf->SetTopMargin(8);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);




$fpdf->SetFont('DejaVu','B',11);
$fpdf->Cell(0,5,'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM',0,0,'C');
$fpdf->SetX($fpdf->lMargin);

$fpdf->Cell(235,5,'SỞ Y TẾ BÌNH DƯƠNG',0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(0,5,'Độc lập - Tự do - Hạnh phúc',0,0,'C');
$fpdf->SetX($fpdf->lMargin);
$fpdf->Cell(220,5,'    BKĐV DẦU TIẾNG',0,0,'L');
$fpdf->Ln(7); 
$fpdf->Cell(0,7,'BẢNG THANH TOÁN TIỀN LƯƠNG CÔNG CHỨC , VIÊN CHỨC HỢP ĐỒNG',0,0,'C');
$fpdf->Ln(); 
$fpdf->Cell(0,7,'THÁNG ... NĂM .....',0,0,'C');
$fpdf->SetX(10);
$fpdf->SetY(37);
$fpdf->Rect(10,37,275,153);
$fpdf->Line(10,57,285,57);
$fpdf->SetX(10);
$fpdf->SetY(45);
$fpdf->SetFont('DejaVu','',9);
$fpdf->Cell(10,5,'STT',0,0,'L');
$fpdf->Cell(40,5,'     HỌ VÀ TÊN',0,0,'L');
$fpdf->Line(17,37,17,190);
$fpdf->Line(52,37,52,190);
$fpdf->Line(52,43,68,43);
$fpdf->Line(60,43,60,190);
$fpdf->Line(68,37,68,190);
$fpdf->Line(82,37,82,190);
$fpdf->Line(94,37,94,190);
$fpdf->Line(94,43,181,43);
$fpdf->Line(106,43,106,190);
$fpdf->Line(118,43,118,190);
$fpdf->Line(134,43,134,190);
$fpdf->Line(144,43,144,190);
$fpdf->Line(154,43,154,190);
$fpdf->Line(164,48,181,48);
$fpdf->Line(164,43,164,190);
$fpdf->Line(169,48,169,190);
$fpdf->Line(181,37,181,190);
$fpdf->Line(201,37,201,190);
$fpdf->Line(221,37,221,190);
$fpdf->Line(241,37,241,190);
$fpdf->Line(261,37,261,190);
for($i=57;$i<190;$i=$i+7){
$fpdf->Line(10,$i,285,$i);
}
$fpdf->SetY(38);
$fpdf->Cell(42,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'NĂM SINH    CHỨC     HÌNH                       NGẠCH BẬC LƯƠNG ĐANG HƯỞNG                           CỘNG           TỔNG              TRỪ               TỔNG
NAM  NỮ      DANH    THỨC    MÃ SỐ  HỆ SỐ   THỜI GIAN P.CẤP  P.CẤP  P.CẤP    ƯU ĐÃI             PHỤ              TIỀN              BHYT               TIỀN                  KÝ
                    CÔNG    TUYỂN                             X.LƯƠNG  CHỨC TRÁCH ĐỘC    TL QUI ĐỔI         CẤP            LƯƠNG           BHXH             LƯƠNG             NHẬN
                     VIỆC     DỤNG   NGẠCH  LƯƠNG  LẦN SAU     VỤ    NHIỆM   HẠI    %   HỆ SỐ                         PC 01THÁNG  BHTN(8,5%)          LĨNH',0,'L');
	  //$fpdf->Output();
$fpdf->Output('tienluong.pdf', 'I');
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
