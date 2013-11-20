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
# Get the encouter data
$enc_obj=& new Encounter($enc);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	//extract($encounter);
}

# Get the report data
$notes=$enc_obj->getEncounterNotes($recnr);
//////// edit 11/11-Huỳnh ////////////////
$info_1=$enc_obj->AllStatus($ses_en);
/////////////////////////////////////////
/*
$classpath=$root_path.'classes/phppdf/';
$fontpath=$classpath.'fonts/';

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
$fpdf->SetFont('DejaVu','',11);
$fpdf->Ln();
//----------------

$logo=$root_path.'gui/img/logos/care_logo_print.png';
$pidbarcode=$root_path.'cache/barcodes/pn_'.$encounter['pid'].'.png';
$encbarcode=$root_path.'cache/barcodes/en_'.$enc.'.png';

# Patch for empty file names 2004-05-2 EL
if(empty($encounter['photo_filename'])){
	$idpic=$root_path.'uploads/photos/registration/_nothing_';
 }else{
	$idpic=$root_path.'uploads/photos/registration/'.$encounter['photo_filename'];
}


	# Load the page header #1
	require('../std_plates/pageheader.php');
	# Load the patient data plate #1
	require('../std_plates/patientdata.php');
	
$fpdf->Ln(10);

#Get the report title
	if(isset($$LD_var)&&!empty($$LD_var)){
		$title=$$LD_var;
	}else{
		# Get the notes type info
		$notestype=$enc_obj->getType($type_nr);
		$title=$notestype['name'];
	}
$fpdf->SetFont('','B',14);
$fpdf->SetFillColor(229,229,229);
$fpdf->Cell(0,10,$title,0,1,'L',true);

//$fpdf->Cell(0,5,"",0,1,'L');
//$fpdf->Ln();
$fpdf->SetFont('','',12);
	if(is_object($notes)){
		$report=$notes->FetchRow();
                ////////////////// edit 11/11-Huỳnh ////////////////////////////
		$fpdf->MultiCell(0,5,$LDDate.": ".formatDate2Local($report['date'],$date_format)."  ".$LDTime.": ".$report['time'],0,'R');
		//////////// edit 11/11-Huỳnh ///////////////////////////
                $enc_info=$info_1->FetchRow();
                if($enc_info['current_room_nr']!=0 && $enc_info['current_room_nr']!=null){
                    $fpdf->Cell2Col(60,5,$LDBed.":",$enc_info['current_room_nr']);
                    $fpdf->Ln();
                }else{
                    $fpdf->Cell2Col(60,5,$LDBed.":",$LDNO1);
                    $fpdf->Ln();
                }
                if($enc_info['current_ward_nr']!=0 && $enc_info['current_ward_nr']!=null){
                    $fpdf->Cell2Col(60,5,$LDWardNr.":",$enc_info['current_ward_nr']);
                    $fpdf->Ln();
                }else{
                    $fpdf->Cell2Col(60,5,$LDWardNr.":",$LDNO1);
                    $fpdf->Ln();
                }
                $current_dept_nr='LD'.str_replace(' ','',$buf_2);
                require_once($root_path.'language/vi/lang_vi_departments.php');
                if($buf_2!=null){
                    $fpdf->Cell2Col(60,5,$LDDept.":",$$current_dept_nr);
                    $fpdf->Ln();
                }else{
                    $fpdf->Cell2Col(60,5,$LDDept.":",$LDNO1);
                    $fpdf->Ln();
                }
                /////////////////////////////////////////////////////////
                if(!empty($report['notes'])){
			$fpdf->Cell(60,5,$LDNotes1.":",0,0,'L');
			$fpdf->MultiCell(0,5,"\n".$report['notes']."\n",0,'L');
			$fpdf->Ln();
		}
		if(!empty($report['short_notes'])){
			$fpdf->Cell2Col(60,5,$LDShortNotes1.":",$report['short_notes']);
			$fpdf->Ln();
		}
		if(!empty($report['aux_notes'])){
			$fpdf->Cell2Col(60,5,$LDShortNotes1.":",$report['aux_notes']);
		}
                if($report['person_decision']!=null){
			$fpdf->Cell(60,5,$LDBy2.":",0,0,'L');
			$fpdf->MultiCell(0,5,$report['person_decision']."\n",0,'L');
			$fpdf->Ln();
		}
                if($report['person_decision']!=null){
			$fpdf->Cell(60,5,$LDBy1.":",0,0,'L');
			$fpdf->MultiCell(0,5,$report['personell_name']."\n",0,'L');
			$fpdf->Ln();
		}
                if($report['list_member']!=null){
			$fpdf->Cell(60,5,$LDMember.":",0,0,'L');
			$fpdf->MultiCell(0,5,$report['list_member']."\n",0,'L');
			$fpdf->Ln();
		}
                $fpdf->Ln(50);
                $fpdf->Cell2Col(100,5,"","Ngày.............tháng..............năm.............");
                $fpdf->SetX($x+30);
                $fpdf->Cell(120,10,"THƯ KÍ",0,'R');
                $fpdf->SetX($x+125);
                $fpdf->Cell(0,10,"CHỦ TỌA",0,'L');
                $fpdf->Ln(20);
                $fpdf->SetX($x+10);
                $fpdf->Cell(0,10,"Họ tên:.................................",0,'R');
                $fpdf->SetX($x+110);
                $fpdf->Cell(0,10,"Họ tên:.................................",0,'R');
		}
$fpdf->Output();
?>
