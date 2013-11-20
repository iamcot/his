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
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
///$db->debug=true;
setcookie(username,"");
setcookie(ck_plan,"1");
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj = new Department();
$current_dept_LDvar=$dept_obj->LDvar($dept_nr);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($dept_nr);
require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;

$doctors=$pers_obj->getAllPersonellOfDept($dept_nr);
$firstday=date("w",mktime(0,0,0,$pmonth,1,$pyear));

$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));
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
$fpdf->SetTopMargin(8);
$fpdf->SetAutoPageBreak('true','5');


// Add a Unicode font (uses UTF-8)
$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
$fpdf->AddFont('DejaVu','IB','DejaVuSansCondensed-BoldOblique.ttf',true);




$fpdf->SetFont('DejaVu','',12);
$fpdf->Cell(0,5,'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM',0,0,'C');
$fpdf->SetX($fpdf->lMargin);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(235,5,'BỆNH VIỆN ĐA KHOA ',0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(50,5,'Mẫu số C01 - YT',0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(0,5,'Độc lập - Tự do - Hạnh phúc',0,0,'C');
$fpdf->SetX($fpdf->lMargin);
$kt=$deptName;
$s_obj=new exec_String();
$k=$s_obj->BASIC_String();	
$k=$s_obj->upper($kt);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(220,5,$k,0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->MultiCell(70,5,'(Ban hành theo QĐ số 144 BYT)
  ngày 31/01/1997 của Bộ y tế',0,'L');
  $fpdf->SetX(30);
$fpdf->Cell(50,5,'***********',0,0,'L');
$fpdf->Ln(); 

$fpdf->Line(125,20,172,20);
$fpdf->SetFont('DejaVu','B',24);

$fpdf->Cell(0,7,'BẢNG CHẤM CÔNG',0,0,'C');
$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(0,7,"Tháng ".$pmonth." Năm ".$pyear,0,0,'C');
$fpdf->SetX(10);
$fpdf->SetY(25);
$fpdf->Rect(10,45,275,108);
$fpdf->Line(10,63,285,63);
$fpdf->SetX(11);
$fpdf->SetY(52);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(10,5,'STT',0,0,'L');
$fpdf->Cell(40,5,'      HỌ VÀ TÊN',0,0,'L');
$fpdf->Line(65,53,285,53);
for($i=70;$i<143;$i=$i+6){
$fpdf->Line(10,$i,285,$i);
}
$fpdf->Line(20,45,20,153);
$fpdf->Line(65,45,65,153);
for($i=71;$i<251;$i=$i+6){
$fpdf->Line($i,53,$i,153);
}
$fpdf->Line(251,45,251,153);


$fpdf->SetY(47);
$fpdf->Cell(0,5,'NGÀY TRONG THÁNG',0,0,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetY(53);

$fpdf->Cell(55,5,'',0,0,'L');
$fpdf->MultiCell(0,5,' 1        3         5         7         9        11      13       15       17       19       21       23       25       27      29       31             GHI 
      2         4         6         8       10       12       14       16       18       20       22      24       26       28       30                 CHÚ',0,'L');
if(is_object($doctors) && $doctors->RecordCount()){
if(($doctors->RecordCount())<=12){
$rows=0; 
				while( $result=$doctors->FetchRow())
				{	
					if($result) $content[]=$result;
					 $rows++;
				}
$count=1;	
$temp=65;			
for($j=0;$j<($doctors->RecordCount());$j++){
//$chamcong=$pers_obj->getChamCongOfDept($dept_nr,$pmonth,$pyear,$content[$j]['personell_nr']);
$sql="SELECT * FROM care_chamcong WHERE dept_nr='".$dept_nr."' AND month='".$pmonth."' AND year='".$pyear."' AND personell_nr='".$content[$j]['personell_nr']."' ";
//echo $sql;
$chamcong=$db->Execute($sql);
$chamcong->RecordCount();
$dutyplan=$chamcong->FetchRow();
$aelems=unserialize($dutyplan['chamcong_1_txt']);


$fpdf->SetY($temp);
$temp=$temp+6;
$fpdf->SetFont('DejaVu','',11);
if($count<=9) $count='0'.$count;
$fpdf->Cell(54.5,5," ".$count."    ".$content[$j]['name_last']." ".$content[$j]['name_first'],0,0,'L');

$count++;
for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
	{ 
	  if($aelems['a'.$j.'_'.$n]==1){$atem=' X';}
		elseif($aelems['a'.$j.'_'.$n]==2){$atem='CT';}
		elseif($aelems['a'.$j.'_'.$n]==3){$atem='TR';}
		elseif($aelems['a'.$j.'_'.$n]==4){$atem='NG';}
		elseif($aelems['a'.$j.'_'.$n]==5){$atem=' P';}
		elseif($aelems['a'.$j.'_'.$n]==6){$atem=' B';}
		elseif($aelems['a'.$j.'_'.$n]==7){$atem='HS';}
		elseif($aelems['a'.$j.'_'.$n]==8){$atem='NK';}
		elseif($aelems['a'.$j.'_'.$n]==9){$atem='KL';}
		elseif($aelems['a'.$j.'_'.$n]==10){$atem='DH';}
		elseif($aelems['a'.$j.'_'.$n]==0){$atem='  ';}		
		$fpdf->SetFont('DejaVu','',10);
		$fpdf->Cell(6,5,$atem,0,0,'L');
		
	}

}
$fpdf->SetY(146);
$fpdf->Cell(25,5,'',0,0,'L');
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(0,5,'CỘNG',0,0,'L');
$fpdf->Ln(8);
$fpdf->SetFont('DejaVu','I',11);
$fpdf->Cell(0,5,'Ngày........tháng........năm..........',0,0,'R');
$fpdf->Ln();
$fpdf->SetX(45);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(190,5,'PHỤ TRÁCH BỘ PHẬN',0,0,'L');
$fpdf->Cell(0,5,'NGƯỜI CHẤM CÔNG',0,0,'L');
$fpdf->Ln(22);
$fpdf->Cell(0,5,'Chú thích:',0,0,'L');
$fpdf->Line(12,186,32,186);
$fpdf->SetFont('DejaVu','',10);
$fpdf->Ln();
$fpdf->Cell(0,5,'             X : CÓ MẶT                                    CT : CÔNG TÁC                                                  TR : TRỰC                                   NG : NGOÀI GIỜ',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'             P : PHÉP                                         B : BỆNH                                                           HS : HẬU SẢN',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'             NK : NGHỈ KHÁC                            KL : NGHỈ KHÔNG HƯỞNG LƯƠNG                   ĐH : ĐI HỌC',0,0,'L');
	  //$fpdf->Output();
$fpdf->Output('chamcong.pdf', 'I');
}else{
$rows=0; 
				while( $result=$doctors->FetchRow())
				{	
					if($result) $content[]=$result;
					 $rows++;
				}
$count=1;	
$temp=65;			
for($j=0;$j<13;$j++){
//$chamcong=$pers_obj->getChamCongOfDept($dept_nr,$pmonth,$pyear,$content[$j]['personell_nr']);
$sql="SELECT * FROM care_chamcong WHERE dept_nr='".$dept_nr."' AND month='".$pmonth."' AND year='".$pyear."' AND personell_nr='".$content[$j]['personell_nr']."' ";
//echo $sql;
$chamcong=$db->Execute($sql);
$chamcong->RecordCount();
$dutyplan=$chamcong->FetchRow();
$aelems=unserialize($dutyplan['chamcong_1_txt']);


$fpdf->SetY($temp);
$temp=$temp+6;
$fpdf->SetFont('DejaVu','',11);
if($count<=9) $count='0'.$count;
$fpdf->Cell(54.5,5," ".$count."    ".$content[$j]['name_last']." ".$content[$j]['name_first'],0,0,'L');

$count++;
for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
	{ 
	  if($aelems['a'.$j.'_'.$n]==1){$atem=' X';}
		elseif($aelems['a'.$j.'_'.$n]==2){$atem='CT';}
		elseif($aelems['a'.$j.'_'.$n]==3){$atem='TR';}
		elseif($aelems['a'.$j.'_'.$n]==4){$atem='NG';}
		elseif($aelems['a'.$j.'_'.$n]==5){$atem=' P';}
		elseif($aelems['a'.$j.'_'.$n]==6){$atem=' B';}
		elseif($aelems['a'.$j.'_'.$n]==7){$atem='HS';}
		elseif($aelems['a'.$j.'_'.$n]==8){$atem='NK';}
		elseif($aelems['a'.$j.'_'.$n]==9){$atem='KL';}
		elseif($aelems['a'.$j.'_'.$n]==10){$atem='DH';}
		elseif($aelems['a'.$j.'_'.$n]==0){$atem='  ';}		
		$fpdf->SetFont('DejaVu','',10);
		$fpdf->Cell(6,5,$atem,0,0,'L');
		
	}

}
$fpdf->SetY(146);
$fpdf->Cell(25,5,'',0,0,'L');
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(0,5,'CỘNG',0,0,'L');
$fpdf->Ln(8);
$fpdf->SetFont('DejaVu','I',11);
$fpdf->Cell(0,5,'Ngày........tháng........năm..........',0,0,'R');
$fpdf->Ln();
$fpdf->SetX(45);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(190,5,'PHỤ TRÁCH BỘ PHẬN',0,0,'L');
$fpdf->Cell(0,5,'NGƯỜI CHẤM CÔNG',0,0,'L');
$fpdf->Ln(22);
$fpdf->Cell(0,5,'Chú thích:',0,0,'L');
$fpdf->Line(12,186,32,186);
$fpdf->SetFont('DejaVu','',10);
$fpdf->Ln();
$fpdf->Cell(0,5,'             X : CÓ MẶT                                    CT : CÔNG TÁC                                                  TR : TRỰC                                   NG : NGOÀI GIỜ',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'             P : PHÉP                                         B : BỆNH                                                           HS : HẬU SẢN',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'             NK : NGHỈ KHÁC                            KL : NGHỈ KHÔNG HƯỞNG LƯƠNG                   ĐH : ĐI HỌC',0,0,'L');
	  //$fpdf->Output();

$fpdf->AddPage();
$fpdf->SetFont('DejaVu','',12);
$fpdf->Cell(0,5,'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM',0,0,'C');
$fpdf->SetX($fpdf->lMargin);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(235,5,'BỆNH VIỆN ĐA KHOA DẦU TIẾNG',0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(50,5,'Mẫu số C01 - YT',0,0,'L');
$fpdf->Ln(); 
$fpdf->Cell(0,5,'Độc lập - Tự do - Hạnh phúc',0,0,'C');
$fpdf->SetX($fpdf->lMargin);
$kt=$deptName;
$s_obj=new exec_String();
$k=$s_obj->BASIC_String();	
$k=$s_obj->upper($kt);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(220,5,$k,0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->MultiCell(70,5,'(Ban hành theo QĐ số 144 BYT)
  ngày 31/01/1997 của Bộ y tế',0,'L');
  $fpdf->SetX(30);
$fpdf->Cell(50,5,'***********',0,0,'L');
$fpdf->Ln(); 
$fpdf->Line(125,20,172,20);
$fpdf->SetFont('DejaVu','B',24);

$fpdf->Cell(0,7,'BẢNG CHẤM CÔNG',0,0,'C');
$fpdf->Ln(); 
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(0,7,"Tháng ".$pmonth." Năm ".$pyear,0,0,'C');
$fpdf->SetX(10);
$fpdf->SetY(25);
$fpdf->Rect(10,45,275,108);
$fpdf->Line(10,63,285,63);
$fpdf->SetX(11);
$fpdf->SetY(52);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(10,5,'STT',0,0,'L');
$fpdf->Cell(40,5,'      HỌ VÀ TÊN',0,0,'L');
$fpdf->Line(65,53,285,53);
for($i=70;$i<143;$i=$i+6){
$fpdf->Line(10,$i,285,$i);
}
$fpdf->Line(20,45,20,153);
$fpdf->Line(65,45,65,153);
for($i=71;$i<251;$i=$i+6){
$fpdf->Line($i,53,$i,153);
}
$fpdf->Line(251,45,251,153);


$fpdf->SetY(47);
$fpdf->Cell(0,5,'NGÀY TRONG THÁNG',0,0,'C');
$fpdf->SetFont('DejaVu','',11);
$fpdf->SetY(53);

$fpdf->Cell(55,5,'',0,0,'L');
$fpdf->MultiCell(0,5,' 1        3         5         7         9        11      13       15       17       19       21       23       25       27      29       31             GHI 
      2         4         6         8       10       12       14       16       18       20       22      24       26       28       30                 CHÚ',0,'L');
	  $temp=65;
for($j=13;$j<($doctors->RecordCount());$j++){
//$chamcong=$pers_obj->getChamCongOfDept($dept_nr,$pmonth,$pyear,$content[$j]['personell_nr']);
$sql="SELECT * FROM care_chamcong WHERE dept_nr='".$dept_nr."' AND month='".$pmonth."' AND year='".$pyear."' AND personell_nr='".$content[$j]['personell_nr']."' ";
//echo $sql;
$chamcong=$db->Execute($sql);
$chamcong->RecordCount();
$dutyplan=$chamcong->FetchRow();
$aelems=unserialize($dutyplan['chamcong_1_txt']);


$fpdf->SetY($temp);
$temp=$temp+6;
$fpdf->SetFont('DejaVu','',11);
if($count<=9) $count='0'.$count;
$fpdf->Cell(54.5,5," ".$count."    ".$content[$j]['name_last']." ".$content[$j]['name_first'],0,0,'L');

$count++;
for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
	{ 
	  if($aelems['a'.$j.'_'.$n]==1){$atem=' X';}
		elseif($aelems['a'.$j.'_'.$n]==2){$atem='CT';}
		elseif($aelems['a'.$j.'_'.$n]==3){$atem='TR';}
		elseif($aelems['a'.$j.'_'.$n]==4){$atem='NG';}
		elseif($aelems['a'.$j.'_'.$n]==5){$atem=' P';}
		elseif($aelems['a'.$j.'_'.$n]==6){$atem=' B';}
		elseif($aelems['a'.$j.'_'.$n]==7){$atem='HS';}
		elseif($aelems['a'.$j.'_'.$n]==8){$atem='NK';}
		elseif($aelems['a'.$j.'_'.$n]==9){$atem='KL';}
		elseif($aelems['a'.$j.'_'.$n]==10){$atem='DH';}
		elseif($aelems['a'.$j.'_'.$n]==0){$atem='  ';}		
		$fpdf->SetFont('DejaVu','',10);
		$fpdf->Cell(6,5,$atem,0,0,'L');
		
	}

}

}
$fpdf->SetY(146);
$fpdf->Cell(25,5,'',0,0,'L');
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(0,5,'CỘNG',0,0,'L');
$fpdf->Ln(8);
$fpdf->SetFont('DejaVu','I',11);
$fpdf->Cell(0,5,'Ngày........tháng........năm..........',0,0,'R');
$fpdf->Ln();
$fpdf->SetX(45);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(190,5,'PHỤ TRÁCH BỘ PHẬN',0,0,'L');
$fpdf->Cell(0,5,'NGƯỜI CHẤM CÔNG',0,0,'L');
$fpdf->Ln(22);
$fpdf->Cell(0,5,'Chú thích:',0,0,'L');
$fpdf->Line(12,186,32,186);
$fpdf->SetFont('DejaVu','',10);
$fpdf->Ln();
$fpdf->Cell(0,5,'             X : CÓ MẶT                                    CT : CÔNG TÁC                                                  TR : TRỰC                                   NG : NGOÀI GIỜ',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'             P : PHÉP                                         B : BỆNH                                                           HS : HẬU SẢN',0,0,'L');
$fpdf->Ln();
$fpdf->Cell(0,5,'             NK : NGHỈ KHÁC                            KL : NGHỈ KHÔNG HƯỞNG LƯƠNG                   ĐH : ĐI HỌC',0,0,'L');
$fpdf->Output('chamcong.pdf', 'I');
}



?>
