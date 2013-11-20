<?php
#Get care logo
$fpdf->Image($logo,1,1,26,8);

# Attach logo
$diff=array(199=>'Ccedilla',208=>'Gbreve',
             221=>'Idotaccent',214=>'Odieresis',
             222=>'Scedilla',220=>'Udieresis',
             231=>'ccedilla',240=>'gbreve',
             253=>'dotlessi',246=>'odieresis',
	     	 254=>'scedilla',252=>'udieresis',
             226=>'acircumflex');
$fpdf->AddFont('DejaVu','',$fontpathFPDF.'DejaVuSansCondensed.ttf',true);
$fpdf->SetFont('DejaVu','',12);

# Get the main informations
if(!isset($GLOBAL_CONFIG)) $GLOBAL_CONFIG=array();
include_once($root_path.'include/care_api_classes/class_globalconfig.php');
$glob=& new GlobalConfig($GLOBAL_CONFIG);
# Get all config items starting with "main_"
$glob->getConfig('main_%');
$addr[]=array($GLOBAL_CONFIG['main_info_address'],
						"$LDPhone:\n$LDFax:\n$LDEmail:",
						$GLOBAL_CONFIG['main_info_phone']."\n".$GLOBAL_CONFIG['main_info_fax']."\n".$GLOBAL_CONFIG['main_info_email']."\n"
						);
$x=$fpdf->GetX();
$y=$fpdf->GetY();
$fpdf->SetX($x+20);
$fpdf->MultiCell(70,5,$GLOBAL_CONFIG['main_info_address'],0,'C');
$fpdf->SetY($y);
$fpdf->SetX($x+90);		
$fpdf->MultiCell(30,5,$LDPhone.":\n".$LDFax.":\n".$LDEmail.":",0,'R');
$fpdf->SetY($y);
$fpdf->SetX($x+120);	
$fpdf->MultiCell(60,5,$GLOBAL_CONFIG['main_info_phone']."\n".$GLOBAL_CONFIG['main_info_fax']."\n".$GLOBAL_CONFIG['main_info_email']."\n",0,'L');
$fpdf->SetY($y+15);

/*
#Get care logo
$pdf->addPngFromFile($logo,20,780,140,23);

# Attach logo
$diff=array(199=>'Ccedilla',208=>'Gbreve',
             221=>'Idotaccent',214=>'Odieresis',
             222=>'Scedilla',220=>'Udieresis',
             231=>'ccedilla',240=>'gbreve',
             253=>'dotlessi',246=>'odieresis',
	     	 254=>'scedilla',252=>'udieresis',
             226=>'acircumflex');
$pdf->selectFont($fontpath.'Helvetica.afm');
$pdf->ezStartPageNumbers(550,25,8);
# Get the main informations
if(!isset($GLOBAL_CONFIG)) $GLOBAL_CONFIG=array();
include_once($root_path.'include/care_api_classes/class_globalconfig.php');
$glob=& new GlobalConfig($GLOBAL_CONFIG);
# Get all config items starting with "main_"
$glob->getConfig('main_%');
$addr[]=array($GLOBAL_CONFIG['main_info_address'],
						"$LDPhone:\n$LDFax:\n$LDEmail:",
						$GLOBAL_CONFIG['main_info_phone']."\n".$GLOBAL_CONFIG['main_info_fax']."\n".$GLOBAL_CONFIG['main_info_email']."\n"
						);
$pdf->ezTable($addr,'','',array('xPos'=>165,'xOrientation'=>'right','showLines'=>0,'showHeadings'=>0,'shaded'=>0,'fontsize'=>6,'cols'=>array(1=>array('justification'=>'right'))));
*/
?>