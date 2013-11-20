<?php
$pataddress=$encounter['addr_str']."\n".$encounter['addr_zip']." ".$encounter['citytown_name'];

$fpdf->Ln();
$fpdf->Rect(10, 28, 190, 38);

$x=$fpdf->GetX();
$y=$fpdf->GetY();
$fpdf->MultiCell(30,5,$LDLastName.":\n".$LDFirstName.":\n".$LDBday.":\n\nPID:",0,'R');
$fpdf->SetY($y);
$fpdf->SetX($x+31);
$fpdf->MultiCell(50,5,$encounter['name_last']."\n".$encounter['name_first']."\n".formatDate2Local($encounter['date_birth'],$date_format)."\n\n".$encounter['pid'],0,'L');
$fpdf->SetY($y);
$fpdf->SetX($x+80);
$fpdf->MultiCell(70,5,$LDAddress.": ".$encounter['addr_str_nr']."\n".$pataddress,0,'L');
$fpdf->SetY($y+40);
$fpdf->SetX($x+80);
$fpdf->Cell2Col(40,5,$LDEncounterNr.':',$enc);

# Add the PID barcode
if(file_exists($pidbarcode)){
	$imgsize=GetImageSize($pidbarcode);
 	$fpdf->Image($pidbarcode,22,57,$imgsize[0]/3,8);
}

# Add the person id picture
if(file_exists($idpic)){
	$imgsize=GetImageSize($idpic);
 	$fpdf->Image($idpic,160,29,36,36);
}

# Add the encounter barcode
if(file_exists($encbarcode)){
	$imgsize=GetImageSize($encbarcode);
 	$fpdf->Image($encbarcode,125,75,$imgsize[0]/3,8);
}



//$pdf->Line(20,750,550,750);
/*
$pdf->ezText("\n",6);
$data[]=array("$LDLastName:\n$LDFirstName:\n$LDBday:\n\nPID:",$encounter['name_last']."\n".$encounter['name_first']."\n".formatDate2Local($encounter['date_birth'],$date_format)."\n\n".$encounter['pid'],'    ',"$LDAddress:",$pataddress);

$pdf->ezTable($data,'','',array('xPos'=>'left','xOrientation'=>'right','showLines'=>0,'fontSize'=>12,'showHeadings'=>0,'shaded'=>0,'cols'=>array(0=>array('justification'=>'right'))));
# Add the PID barcode
if(file_exists($pidbarcode)){
	$imgsize=GetImageSize($pidbarcode);
 	$pdf->addPngFromFile($pidbarcode,40,650,$imgsize[0],25);
}
$y=$pdf->ezText("\n",18);
$pdf->addText(280,$y,14,"$LDEncounterNr:");
$pdf->addText(280,$y-14,14,$enc);

# Add the encounter barcode
if(file_exists($encbarcode)){
	$imgsize=GetImageSize($encbarcode);
 	$pdf->addPngFromFile($encbarcode,400,600,$imgsize[0],50);
}

$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(2);
$pdf->rectangle(20,650,555,110);

# Add the person id picture
if(file_exists($idpic)){
	//$imgsize=GetImageSize($idpic);
 	$pdf->addPngFromFile($idpic,450,655,88);
}
*/
?>
