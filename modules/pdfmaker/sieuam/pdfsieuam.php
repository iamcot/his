<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','sieuam.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');

$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	
	if($encounter['sex']=='m'){
		$sex=$LDNam;
	}else{
		$sex=$LDNu;
	}
	
	$ngaysinh=formatDate2STD($encounter['date_birth'],$date_format);
}


require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	
    //Page header
    public function Header() {
		$headerfont = $this->getHeaderFont();
		$headerdata = $this->getHeaderData();
		
		$this->SetTextColor(0, 0, 0);
		$this->SetX($this->original_rMargin);
		
        // Title Header & Data Header
		$this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
		$this->writeHTMLCell( 0, 0, '', '', "<b>".$headerdata['title']."</b> - ".$headerdata['string'], 0, 1, 0, true, 'R', true);
		
		// print an ending header line
		$this->SetLineStyle(array('width' => 0.5 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => '1,5', 'color' => array(0, 0, 0)));
		$this->SetY((2 / $this->k) + max($imgy, $this->y));
		if ($this->rtl) {
			$this->SetX($this->original_rMargin);
		} else {
			$this->SetX($this->original_lMargin);
		}
		$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
    }

    // Page footer
    public function Footer() {
        // Position at 5 mm from bottom
        //$this->SetY(-8);
		$this->SetLineStyle(array('width' => 0.5 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => '1,5', 'color' => array(0, 0, 0)));
		$this->Line(5, $this->h-5,($this->w - $this->original_rMargin), $this->h-5);
        // Set font
		$this->SetY(-5);
        $this->SetFont('dejavusans', 'I', 6);
        // Page Footer
        $this->Cell(0, 5, 'Phần mềm Hệ Thống Thông Tin Bệnh Viện', 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor(PDF_HOSNAME);
$pdf->SetTitle('Kết Quả Siêu Âm');
$pdf->SetMargins(5, 8, 5);

// remove default header/footer
//$pdf->setPrintHeader(false);
$pdf->SetHeaderMargin(1);
$pdf->setHeaderFont(Array('dejavusans', '', '8'));
$pdf->SetHeaderData('', '', PDF_HOSNAME, 'Phiếu Siêu Âm Chẩn Đoán');
//$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->AddPage();
//set column in page
$pdf->setEqualColumns(2,600);

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', 'B', 14);

// add a page

$pdf->Write(0, 'KẾT QUẢ SIÊU ÂM', '', 0, 'C', true, 0, false, false, 0);

$pdf->SetFont('dejavusans', '', 10);
$pdf->writeHTMLCell(0, 0, '', '', "<b>Số lưu trữ:</b> ".$batch_nr." - ".date('d/m/Y H:i:G'), 0, 1, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', "<b>Họ tên:</b> ".$encounter['name_last']." ".$encounter['name_first']." &nbsp;&nbsp;&nbsp;&nbsp; <b>Ngày sinh:</b> ".$ngaysinh." &nbsp;&nbsp;&nbsp;<b>Giới tính:</b> ".$sex, 0, 1, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', "<b>Địa chỉ:</b> ".$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['citytown_name'], 0, 1, 0, true, 'L', true);

//Get data of mono result
$sql1="SELECT fi.*, fisub.*, re.clinical_info, re.send_doctor, re.results_doctor    
		FROM care_test_findings_radio AS fi, care_test_findings_radio_sub AS fisub, care_test_request_radio AS re  
		WHERE fi.batch_nr='".$batch_nr."' AND fi.encounter_nr='".$enc."' 
		AND re.batch_nr=fi.batch_nr AND fi.batch_nr=fisub.batch_nr 
		AND fisub.item_bill_code='".$item_code."' " ;
		
if($re_item=$db->Execute($sql1)){
	if($count1=$re_item->RecordCount()){
		$item=$re_item->FetchRow();
		$html =stripslashes($item['kq_sieuam']);
		//$path=$item['img_path'];
		if($item['img_name']!=''){
			$imgname=explode(',',$item['img_name']);
			$pathimg1='../'.$item['img_path'].'/'.$imgname[0];
			$pathimg2='../'.$item['img_path'].'/'.$imgname[1];
		}
	}else{
		$html = 'Không tìm được dữ liệu. Xin lưu lại kết quả trước khi in';
	}
} 

if($count1){
	$pdf->writeHTMLCell(0, 0, '', '', "<b>Lâm sàng:</b> ".$item['clinical_info'], 0, 1, 0, true, 'L', true);
	$pdf->writeHTMLCell(0, 0, '', '', "<b>BS. Chỉ định:</b> ".$item['send_doctor'], 0, 1, 0, true, 'L', true);
}
$pdf->Ln();
//$pdf->setCellPaddings(1,1,1,1);
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->Ln();
$x=$pdf->getX();
$pdf->SetX($x+25);
$pdf->writeHTMLCell(0, 25, '', '', "Ngày ".date('d')." tháng ".date('m')." năm ".date('Y').'<br><b><i>Bác sĩ Siêu Âm</i><b><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br><b>'.$item['results_doctor'].'</b>', 0, 1, 0, true, 'C', true);
//$pdf->SetX($x+20);
//$pdf->writeHTMLCell(0, 5, '', '','<b>'.$item['results_doctor'].'</b>', 0, 1, 0, true, 'C', true);

if($item['img_path']!=''){
	// set JPEG quality
	$pdf->setJPEGQuality(90);
	$w=120;
	$h=90;

	$col=$pdf->getColumn();
	if($col==0){
		$pdf->selectColumn(1);
		$align='R';
	} else{
		$pdf->AddPage();
		$pdf->selectColumn(0);
		$align='C';
	}
		
	// Image with resizing
	$x=$pdf->GetX();
	$y=$pdf->GetY();
	if($imgname[0]!=''){
		$pdf->Image($pathimg1, $x, $y, $w, $h, 'JPG', '', '', true, 150, $align, false, false, 1, false, false, false);

		$y=$pdf->GetY();
		$pdf->SetY($y+$h);
		if($align=='R')
			$pdf->SetX($x+30);
		$pdf->Write(0, 'Hình 1', '', 0, 'C', true, 0, false, false, 0);
	}
	
	$x=$pdf->GetX();
	$y=$pdf->GetY();
	if($imgname[1]!=''){	
		$pdf->Image($pathimg2, $x, $y, $w, $h, 'JPG', '', '', true, 150, $align, false, false, 1, false, false, false);

		$y=$pdf->GetY();
		$pdf->SetY($y+$h);
		if($align=='R')
			$pdf->SetX($x+30);
		$pdf->Write(0, 'Hình 2', '', 0, 'C', true, 0, false, false, 0);
	}
}

// reset pointer to the last page
$pdf->lastPage();

// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('Ketquasieuam.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+