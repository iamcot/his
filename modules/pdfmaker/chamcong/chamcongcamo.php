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
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_nr=39;
$dept_obj = new Department();
$current_dept_LDvar=$dept_obj->LDvar($dept_nr);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($dept_nr);
 require_once ($root_path . 'include/care_api_classes/class_encounter_op.php');
    $enc_op_obj = new OPEncounter ( );
    $list1=$enc_op_obj->get_personell_op("","");
	$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));
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
$fpdf->Cell(235,5,'BỆNH VIỆN ĐA KHOA ',0,0,'L');
$fpdf->SetFont('DejaVu','',11);
$fpdf->Cell(50,5,'Mẫu số C01 - YT',0,0,'L');
$fpdf->Ln(); 

$fpdf->SetX($fpdf->lMargin);
$fpdf->SetFont('DejaVu','B',12);
$kt=$deptName;
$s_obj=new exec_String();
$k=$s_obj->BASIC_String();	
$k=$s_obj->upper($kt);
$fpdf->Cell(220,5,$k,0,0,'L');
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
$fpdf->Cell(0,7,"THÁNG ".$pmonth." NĂM ".$pyear,0,0,'C');

$fpdf->Rect(10,55,275,104);
$fpdf->Line(10,75,285,75);
$fpdf->SetX(11);
$fpdf->SetY(62);
$fpdf->SetFont('DejaVu','B',12);
$fpdf->Cell(10,5,'STT',0,0,'L');
$fpdf->Cell(70,5,'      HỌ VÀ TÊN',0,0,'L');
$fpdf->Line(85,65,240,65);
$fpdf->Line(85,70,240,70);
for($i=75;$i<159;$i=$i+7){
$fpdf->Line(10,$i,285,$i);
}
$fpdf->Line(20,55,20,159);
$fpdf->Line(65,55,65,159);
$fpdf->Line(85,55,85,159);
for($i=85;$i<240;$i=$i+5){
$fpdf->Line($i,65,$i,70);
}
for($i=85;$i<240;$i=$i+2.5){
$fpdf->Line($i,70,$i,159);
}
$fpdf->Line(240,55,240,159);
$fpdf->Line(261,62,261,159);
$fpdf->Line(240,62,285,62);
$fpdf->SetY(57);
$fpdf->Cell(232,5,'',0,0,'L');
$fpdf->Cell(0,5,'TỔNG CỘNG CA MỔ',0,0,'L');
$fpdf->SetY(64);
$fpdf->Cell(230,5,'',0,0,'L');
$fpdf->MultiCell(0,5,' CA MỔ        CA MỔ
 LOẠI II       LOẠI III',0,'L');
$fpdf->SetY(60);
$fpdf->Cell(130,5,'',0,0,'L');
$fpdf->Cell(50,5,'NGÀY TRONG THÁNG',0,0,'L');
$fpdf->SetY(57);
$fpdf->Cell(55,5,'',0,0,'L');
$fpdf->MultiCell(0,5,'CẤP BẬC
    MỔ
    CP',0,'L');
$fpdf->SetY(65);
$fpdf->Cell(75,5,'',0,0,'L');
$fpdf->SetFont('DejaVu','',9);
$fpdf->Cell(55,5,' 1   2    3   4    5   6    7   8    9  10  11 12 13  14 15  16  17 18  19 20  21 22  23 24  25 26  27 28  29 30 31',0,0,'L');	
$fpdf->SetY(70);
$fpdf->Cell(74.5,5,'',0,0,'L');
$fpdf->SetFont('DejaVu','',8);
$fpdf->Cell(55,5,'2 3 2 3 2  3 2 3 2 3 2 3 2 3 2 3  2 3 2 3 2 3 2 3 2 3 2 3  2 3 2 3 2 3 2 3 2 3  2 3 2 3 2 3 2 3  2 3 2 3 2 3 2 3 2 3 2 3  2 3 2 3',0,0,'L');	
$fpdf->SetFont('DejaVu','',11);
$sql="SELECT DISTINCT(pno.personell_nr) AS personell_nr
			FROM care_personell_op AS pno
            LEFT JOIN care_encounter_op AS tb ON tb.nr=pno.encounter_op_nr
            LEFT JOIN care_test_request_or AS yc ON yc.batch_nr=tb.batch_nr
            LEFT JOIN care_personell AS pn ON pn.nr=pno.personell_nr 
            LEFT JOIN care_person AS ps ON ps.pid=pn.pid
            WHERE pno.status='chosed'
			ORDER BY pno.personell_nr ASC";
$ergebnis=$db->Execute($sql);
$ergebnis->RecordCount();
$temp1=1;
$y=76;
while($personell_nr=$ergebnis->FetchRow()){
                   
                    $info=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"");
                   $fpdf->SetFont('DejaVu','',11);
                    $flag=$info->RecordCount();
                    if($flag<=1){
                      
                        $info_detail=$enc_op_obj->get_info($personell_nr['personell_nr']);
                        $info_personell=$info_detail->FetchRow();
                      
                        $personell=$info->FetchRow();
                        if( $pmonth==substr($personell["date_request"],5,2) && $pyear==substr($personell["date_request"],0,4)){
							$fpdf->SetY($y);
							$fpdf->Cell(10,5," ".$temp1,0,0,'L');
							$fpdf->Cell(53,5,$info_personell["name_last"]." ".$info_personell["name_first"],0,0,'L');
							$temp1++;
							$y=$y+7;
							if($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật"){
                                $fpdf->Cell(11,5,'C',0,0,'L');
                            }elseif($info_personell["job_function_title"]=="Phụ Mổ"){
                               $fpdf->Cell(11,5,'P',0,0,'L');
                            }else{
                               $fpdf->Cell(11,5,'GV',0,0,'L');
                            }
							  for($i=1;$i<=$maxdays;$i++){
                                if($i==substr($personell['date_request'],8,2)){  
                                    if($personell["level_method"]=='II'){
                                        $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "II", $personell['date_request']);
                                        $number_level=$count_level_2->RecordCount();
										$fpdf->SetFont('DejaVu','',8);
                                        $fpdf->Cell(2.5,5,$number_level,0,0,'L');
                                        $fpdf->Cell(2.5,5,' ',0,0,'L');
                                    }else{
                                        $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "III", $personell['date_request']);
                                        $number_level=$count_level_2->RecordCount();
										$fpdf->SetFont('DejaVu','',8);
                                         $fpdf->Cell(2.5,5,$number_level,0,0,'L');;
                                        $fpdf->Cell(2.5,5,' ',0,0,'L');                                    
                                    }
                                }
								else{  
$fpdf->SetFont('DejaVu','',8);								
                                    $fpdf->Cell(2.5,5,' ',0,0,'L');;
                                        $fpdf->Cell(2.5,5,' ',0,0,'L');     
                                }								
                            }  
							$list2=$enc_op_obj->list_doctor_op($personell["personell_nr"],"II","");
                            $level2=$list2->RecordCount();
                            //Đếm tổng số ca loại 2 tham gia trong tháng
                            $list3=$enc_op_obj->list_doctor_op($personell["personell_nr"],"III","");
                            $level3=$list3->RecordCount();
                            if($personell["level_method"]=='II'){
                               $fpdf->Cell(20,5,"            ".$level2,0,0,'L'); 
                            }
                            if($personell["level_method"]=='III'){
                               $fpdf->Cell(20,5,"            ".$level3,0,0,'L'); 
                            }
                       /*     echo '<tr>';
                            echo '<td bgcolor="white">'.$info_personell["name_last"].' '.$info_personell["name_first"].'</td>';
                            
                            for($i=1;$i<=$maxdays;$i++){
                                if($i==substr($personell['date_request'],8,2)){  
                                    if($personell["level_method"]=='II'){
                                        $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "II", $personell['date_request']);
                                        $number_level=$count_level_2->RecordCount();
                                        echo '<td bgcolor="#5F9EA0" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                    }else{
                                        $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "III", $personell['date_request']);
                                        $number_level=$count_level_2->RecordCount();
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                        echo '<td bgcolor="#B0C4DE" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';                                    
                                    }
                                }else{                                
                                    echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                    echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                }                            
                            }  
                            //Đếm tổng số ca loại 2 tham gia trong tháng
                            $list2=$enc_op_obj->list_doctor_op($personell["personell_nr"],"II","");
                            $level2=$list2->RecordCount();
                            //Đếm tổng số ca loại 2 tham gia trong tháng
                            $list3=$enc_op_obj->list_doctor_op($personell["personell_nr"],"III","");
                            $level3=$list3->RecordCount();
                            if($personell["level_method"]=='II'){
                                echo '<td bgcolor="#5F9EA0" align="center"><font size="2" >'.$level2.'</td>';
                            }else{
                                echo '<td bgcolor="white"></td>';
                            }
                            if($personell["level_method"]=='III'){
                                echo '<td bgcolor="#B0C4DE" align="center"><font size="2">'.$level3.'</td>';
                            }else{
                                echo '<td bgcolor="white"></td>';
                            }
                                echo '</tr>';
                            if($temp==0){
                                echo '<div>';
                                if ($linecount) echo str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
                                else echo str_replace('~nr~','0',$LDSearchFound);
                                echo '<br>'.$pagen->makePrevLink($LDPrevious);
                                echo '<br>'.$pagen->makeNextLink($LDNext);
                                echo '</div>';
                                $temp=1;
                            }
                       */ }
                    }else{
                        $info_detail=$enc_op_obj->get_info($personell_nr['personell_nr']);
                        $info_personell=$info_detail->FetchRow();
                        $personell=$info->FetchRow();
                        if( $pmonth==substr($personell["date_request"],5,2) && $pyear==substr($personell["date_request"],0,4)){
//                        && ($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật" || $info_personell["job_function_title"]=="Phụ Mổ") ){
							$fpdf->SetY($y);
							$fpdf->Cell(10,5," ".$temp1,0,0,'L');
							$fpdf->Cell(53,5,$info_personell["name_last"]." ".$info_personell["name_first"],0,0,'L');
							$temp1++;
							$y=$y+7;
							if($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật"){
                                $fpdf->Cell(11,5,'C',0,0,'L');
                            }elseif($info_personell["job_function_title"]=="Phụ Mổ"){
                               $fpdf->Cell(11,5,'P',0,0,'L');
                            }else{
                               $fpdf->Cell(11,5,'GV',0,0,'L');
                            }
							 $len=1;
                            //Gọi lại hàm lấy thông tin các ca mổ của người này
                            $info=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"");
                            $array_date=array();
                            $array_level=array();
                            while($date_check=$info->FetchRow()){
                                $array_date[$len]=$date_check['date_request'];                                
                                $array_level[$len]=$date_check['level_method'];
                                $len++;
                            }             
							 $count_level_month=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"");
							   for($i=1;$i<=$maxdays;$i++){
                                $temp=$len;
                                for($t=1;$t<=($len-1);$t++){
                                    $temp--;
                                    if($i==substr($array_date[$t],8,2)){
                                        if($array_level[$t]=='II'){
                                            $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "II", $array_date[$t]);
                                            $number_level=$count_level_2->RecordCount();
											$fpdf->SetFont('DejaVu','',8);
                                           $fpdf->Cell(2.5,5,$number_level,0,0,'L');
                                        $fpdf->Cell(2.5,5,' ',0,0,'L');
                                            break;
                                        }else{
                                            $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "III", $array_date[$t]);
                                            $number_level=$count_level_2->RecordCount();
                                           $fpdf->SetFont('DejaVu','',8);
                                        $fpdf->Cell(2.5,5,' ',0,0,'L'); 
										$fpdf->Cell(2.5,5,$number_level,0,0,'L');
                                            break;
                                        }
                                    }elseif($temp==1){ 
									$fpdf->SetFont('DejaVu','',8);
									$fpdf->Cell(2.5,5,' ',0,0,'L');;
                                        $fpdf->Cell(2.5,5,' ',0,0,'L');   
										}										
                                }                                
                            }
							  $temp=$len-1;
                            for($i=1;$i<($len-1);$i++){
                                $temp--;
                                $list2=$enc_op_obj->list_doctor_op_flag($personell["personell_nr"],"II");
                                $level2=$list2->FetchRow();
                                $list3=$enc_op_obj->list_doctor_op_flag($personell["personell_nr"],"III");
                                $level3=$list3->FetchRow();
                                if($temp==1){
                                    if($level2['level_method']!=0 ){
                                        $fpdf->Cell(20,5,"            ".$level2['level_method'],0,0,'L');                                  
                                    }else{

                                        $temp=1;
                                    }
                                    if($level3['level_method']!=0){
                                       $fpdf->Cell(20,5,"             ".$level3['level_method'],0,0,'L');
                                    }else{
                                       
                                        $temp=1;
                                    }
                                }
                            }
							
                         /*    echo '<tr>';
                            echo '<td bgcolor="white">'.$info_personell["name_last"].' '.$info_personell["name_first"].'</td>';
                            if($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật"){
                                echo '<td align="center" bgcolor="white">C</td>';
                            }elseif($info_personell["job_function_title"]=="Phụ Mổ"){
                                echo '<td align="center" bgcolor="white">P</td>';
                            }else{
                                echo '<td align="center" bgcolor="white">GV</td>';
                            }
                            $len=1;
                            //Gọi lại hàm lấy thông tin các ca mổ của người này
                            $info=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"");
                            $array_date=array();
                            $array_level=array();
                            while($date_check=$info->FetchRow()){
                                $array_date[$len]=$date_check['date_request'];                                
                                $array_level[$len]=$date_check['level_method'];
                                $len++;
                            }                            
                            $count_level_month=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"");
                            for($i=1;$i<=$maxdays;$i++){
                                $temp=$len;
                                for($t=1;$t<=($len-1);$t++){
                                    $temp--;
                                    if($i==substr($array_date[$t],8,2)){
                                        if($array_level[$t]=='II'){
                                            $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "II", $array_date[$t]);
                                            $number_level=$count_level_2->RecordCount();
                                            echo '<td bgcolor="#5F9EA0" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';
                                            echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                            break;
                                        }else{
                                            $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "III", $array_date[$t]);
                                            $number_level=$count_level_2->RecordCount();
                                            echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                            echo '<td bgcolor="#B0C4DE" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';
                                            break;
                                        }
                                    }elseif($temp==1){
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                    }
                                }                                
                            }
                            $temp=$len-1;
                            for($i=1;$i<($len-1);$i++){
                                $temp--;
                                $list2=$enc_op_obj->list_doctor_op_flag($personell["personell_nr"],"II");
                                $level2=$list2->FetchRow();
                                $list3=$enc_op_obj->list_doctor_op_flag($personell["personell_nr"],"III");
                                $level3=$list3->FetchRow();
                                if($temp==1){
                                    if($level2['level_method']!=0 ){
                                        echo '<td bgcolor="#5F9EA0" align="center"><font size="2" >'.$level2['level_method'].'</td>';                                    
                                    }else{
                                        echo '<td></td>';
                                        $temp=1;
                                    }
                                    if($level3['level_method']!=0){
                                        echo '<td bgcolor="#B0C4DE" align="center"><font size="2">'.$level3['level_method'].'</td>';
                                    }else{
                                        echo '<td></td>';
                                        $temp=1;
                                    }
                                }
                            }
                            echo '</tr>';
                            if($temp1==0){
                                echo '<div>';
                                if ($linecount) echo str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
                                else echo str_replace('~nr~','0',$LDSearchFound);
                                echo '<br>'.$pagen->makePrevLink($LDPrevious);
                                echo '<br>'.$pagen->makeNextLink($LDNext);
                                echo '</div>';
                                $temp1=1;
                            }
                    */    }
                    }
                }
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