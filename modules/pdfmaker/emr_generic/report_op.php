<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

$report_textsize=12;
$report_titlesize=14;
$report_auxtitlesize=10;
$report_authorsize=10;

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
//$lang_tables[]='startframe.php';

$lang_tables[]='emr.php';
define('LANG_FILE','aufnahme.php');
define('NO_2LEVEL_CHK',1);
//define('NO_CHAIN',TRUE);
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
require_once($root_path.'include/care_api_classes/class_department.php');
require_once($root_path.'language/vi/lang_vi_departments.php');
# Get the encouter data
$sql = "SELECT * FROM care_test_request_".$subtarget." WHERE batch_nr='".$batch_nr."'";
$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData($enc)){
    $encounter=$enc_obj->getLoadedEncounterData();
}
if($enc_request_op=$db->Execute($sql)){
    $count=$enc_request_op->RecordCount();
    $stored_request=$enc_request_op->FetchRow();
    //echo $stored_request['encounter_nr'];
}
# Get the report data
$info_1=$enc_obj->AllStatus($enc);
$sql1="SELECT d.LD_var
            FROM care_encounter AS e,
                 care_person AS p,
                 care_department AS d
            WHERE p.pid=".$_SESSION['sess_pid']."
                AND	p.pid=e.pid
                AND e.encounter_nr=".$enc."
                AND e.current_dept_nr=d.nr";
    if($result=$db->Execute($sql1)){
        $rows=$result->FetchRow();
    }
    $current_dept_nr=$rows['LD_var'];
require_once($root_path.'language/vi/lang_vi_departments.php');
    if($current_dept_nr!=''){
        $buf_2=$$current_dept_nr;
    }else{
        $buf_2=$LDNO1;
    }

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
$logo=$root_path.'gui/img/logos/care_logo_print.png';
$pidbarcode=$root_path.'cache/barcodes/pn_'.$encounter['pid'].'.png';
$encbarcode=$root_path.'cache/barcodes/en_'.$enc.'.png';
$opbarcode=$root_path.'cache/barcodes/op_'.$stored_request['batch_nr'].'.png';


$x=$fpdf->GetX();
$temp='Số hồ sơ';
$fpdf->Cell2Col(100,5,$cell,$temp.':'.$enc,0,'C','R');

# Add the encounter barcode
//if(file_exists($encbarcode)){
//	$imgsize=GetImageSize($encbarcode);
// 	$fpdf->Image($encbarcode,140,15,$imgsize[0]/3,8);
//}
$fpdf->Ln(10);
$fpdf->SetFont('','B',20);
$fpdf->Cell(0,10,"GIẤY BÁO MỔ",0,1,'C',false);
$fpdf->Ln();
$fpdf->SetFont('','',12);
	if($count>0){
                $enc_info_1=$info_1->FetchRow();
                $fpdf->SetX($x+10);
                $fpdf->Cell(25,5,$LDFullName.":",0,'R');
                $fpdf->Cell(5,5,$encounter['name_last']." ".$encounter['name_first'],0,'L');
                $fpdf->SetX($x+140);
                $fpdf->Cell(5,5,$LDBday.": ".formatDate2Local($encounter['date_birth'],$date_format),0,'L');
                $fpdf->Ln(5);
                $fpdf->SetX($x+10);
                $fpdf->Cell(25,10,$LDAddress.":",0,'R');
                $fpdf->Cell(45,10,$encounter['addr_str_nr']." ".$encounter['addr_str'].' '.$encounter['phuongxa_name'].' '.$encounter['quanhuyen_name'].' '.$encounter['citytown_name'],0,'L');
                $fpdf->Ln();
                $fpdf->SetX($x+10);  
                if($stat=$enc_obj->AllStatus($enc)){
                    $enc_status=$stat->FetchRow();
                }                
                $dept_obj=new Department;
                $current_dept_LDvar=$dept_obj->LDvar($enc_status['current_dept_nr']);
                if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);                
                if($deptName!=null){
                    $fpdf->Cell(25,5,$LDDept.":",0,'R');
                    $fpdf->Cell(45,5,$deptName,0,'L');
                    $fpdf->Ln();
                }else{
                    $fpdf->Cell(25,5,$LDDept.":",0,'R');
                    $fpdf->Cell(45,5,$buf_2,0,'L');
                    $fpdf->Ln();
                }
                $fpdf->Ln();
                $fpdf->Rect(10, 70, 190, 66);
                $x=$fpdf->GetX();
                $y=$fpdf->GetY();
                $fpdf->Rect(10, 70, 15, 18);
                    $fpdf->SetX($x+2);
                    $fpdf->Cell(10,9,"STT",0,'C');
                $fpdf->Rect(10, 70, 38, 18);
                    $fpdf->SetX($x+15);
                    $fpdf->Cell(10,9,"NGÀY MỔ",0,'C');
                $fpdf->Rect(10, 70, 120, 18);
                    $fpdf->SetX($x+60);
                    $fpdf->Cell(10,9,"CHẨN ĐOÁN BỆNH",0,'C');
                $fpdf->Rect(10, 70, 157, 18);
                    $fpdf->SetX($x+123);
                    $fpdf->Cell(10,9,"PHƯƠNG PHÁP",0,'C');
                    $fpdf->Rect(130, 79, 14, 9);
                    $fpdf->SetX($x+123);
                    $fpdf->Cell(10,26,"MỔ",0,'C');
                    $fpdf->Rect(144, 79, 11, 9);
                    $fpdf->SetX($x+136);
                    $fpdf->Cell(10,26,"TÊ",0,'C');
                    $fpdf->Rect(155, 79, 12, 9);
                    $fpdf->SetX($x+147);
                    $fpdf->Cell(10,26,"MÊ",0,'C');
                $fpdf->Rect(10, 70, 190, 18);
                    $fpdf->SetX($x+160);
                    $fpdf->Cell(10,9,"NGƯỜI MỔ",0,'C');
                $fpdf->Ln(20);
                $fpdf->Rect(10, 88, 15, 48);
                    $fpdf->SetX($x+7);
                    //$fpdf->MultiCell(30,5,$stored_request['batch_nr'],1,'C');
                    //$fpdf->Cell(0,5,$stored_request['batch_nr'],0,'L');
                $fpdf->Rect(10, 88, 38, 48);
                    $fpdf->SetX($x+15);
                    $fpdf->Cell(0,5,formatDate2Local($stored_request['date_request'],$date_format),0,'L');
                $fpdf->Rect(10, 88, 120, 48);
                    $fpdf->SetX($x+39);
                    $fpdf->MultiCell(50,5,$stored_request['clinical_info'],0,'L');
                    //$fpdf->Cell(0,5,$stored_request['clinical_info'],0,'L');
                $fpdf->Rect(10, 88, 157, 48);                    
                    $fpdf->SetY($y+18);
                    $fpdf->SetX($x+120);
                    $fpdf->Rect(130, 79, 14, 57);
                    $fpdf->Rect(144, 79, 11, 57);
                    $fpdf->Rect(155, 79, 12, 57);
                    $request=explode(' ',$stored_request['method_op']);
                    if($request['0']!=''){
                        $fpdf->SetX($x+121);
                        $fpdf->Cell(10,10,$request['0'],0,'C');
                    }
                    if($request['2']!=''){
                        $fpdf->SetX($x+145);
                        $fpdf->Cell(10,10,$request['2'],0,'C');
                    }
                    if($request['1']!=''){
                        $fpdf->SetX($x+134);
                        $fpdf->Cell(10,10,$request['1'],0,'C');
                    }
                    //$fpdf->Cell(70,70,$stored_request['method_op'],0,'L');
                $fpdf->Rect(10, 88, 190, 48);
                    $fpdf->SetY($y+20);
                    $fpdf->SetX($x+160);
                    $name_pop=explode("-",$stored_request['person_surgery']);
                    $fpdf->MultiCell(27,4,$name_pop['0'],0,'L');
                    //$fpdf->Cell(0,5,$stored_request['send_doctor'],0,'L');
                $fpdf->Ln();
                $fpdf->SetX($x+10);
                $fpdf->SetY($y+70);
                $fpdf->Cell(70,5,"Ban Giám Đốc Duyệt",0,'C');
                $fpdf->Cell2Col(50,5,"Trưởng khoa"." ","Ngày..".date('d')."..tháng..".date('m')."..năm...".date('Y')."...");
                $fpdf->Ln();
                $fpdf->SetX($x+135);
                $fpdf->Cell(0,5,"Hành Chánh Khoa",0,'L');
        }
$fpdf->Output();

?>
