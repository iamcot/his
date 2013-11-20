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
//define('NO_2LEVEL_CHK',1);
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
$fpdf->SetFont('DejaVu','',12);
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
	
//----------------Tuyen
#Get the report title
	if(isset($$LD_var)&&!empty($$LD_var)){
		$title=$$LD_var;
	}else{
		# Get the notes type info
		$notestype=$enc_obj->getType($type_nr);
		$title=$notestype['name'];
	}
$fpdf->SetFillColor(229,229,229);
$fpdf->Cell(0,10,$title,0,1,'C',true);

$fpdf->Ln();
	if(is_object($notes)){
		$report=$notes->FetchRow();
		$fpdf->MultiCell(0,5,$LDBy.": ".$report['personell_name']."\n".$LDDate.": ".formatDate2Local($report['date'],$date_format)."\n".$LDTime.": ".$report['time'],0,'L');
		$fpdf->MultiCell(0,5,$LDNotes.": ".$report['notes']."\n",0,'L');
		if(!empty($report['short_notes'])){
			$fpdf->Cell(0,5,$LDShortNotes.": ".$report['short_notes'],0,1,'L');
		}
		if(!empty($report['aux_notes'])){
			$fpdf->Cell(0,5,$LDShortNotes.": ".$report['aux_notes'],0,1,'L');
		}
		}	

$fpdf->Output();

/*
	$data=NULL;
	# make empty line
	$y=$pdf->ezText("\n",14);

	#Get the report title
	if(isset($$LD_var)&&!empty($$LD_var)){
		$title=$$LD_var;
	}else{
		# Get the notes type info
		$notestype=$enc_obj->getType($type_nr);
		$title=$notestype['name'];
	}
		
	$data[]=array($title);
	$pdf->ezTable($data,'','',array('xPos'=>'left','xOrientation'=>'right','showLines'=>0,'fontSize'=>$report_titlesize,'showHeadings'=>0,'shaded'=>2,'shadeCol2'=>array(0.9,0.9,0.9),'width'=>555));


	if(is_object($notes)){
		$report=$notes->FetchRow();
		$y=$pdf->ezText("\n$LDBy: ".$report['personell_name']."   $LDDate: ".formatDate2Local($report['date'],$date_format)."   $LDTime: ".$report['time'],$report_authorsize);
		$y=$pdf->ezText("\n".$report['notes']."\n",$report_textsize);
		if(!empty($report['short_notes'])){
			$y=$pdf->ezText("$LDShortNotes\n",$report_auxtitlesize);
			$y=$pdf->ezText($report['short_notes'],$report_textsize);
		}
		if(!empty($report['aux_notes'])){
			$y=$pdf->ezText($LDShortNotes."\n",$report_auxtitlesize);
			$y=$pdf->ezText($report['aux_notes'],$report_textsize);
		}
		}


$pdf->ezStream();
*/

?>
