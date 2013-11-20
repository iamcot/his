<?php
//============================================================+
// File name   : example_009.php
// Begin       : 2008-03-04
// Last Update : 2010-12-04
//
// Description : Example 009 for TCPDF class
//               Test Image
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               Manor Coach House, Church Hill
//               Aldershot, Hants, GU12 4RQ
//               UK
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Test Image
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 009');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 009', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// -------------------------------------------------------------------

// add a page
$pdf->AddPage();

// set JPEG quality
$pdf->setJPEGQuality(75);

//http://localhost/HISDauTieng/trunk/main/imgcreator/datacurve.php?ntid=false&lang=vi&pn=2012000033&max=15&yr=2012&mo=6&dy=1

//$imagedata = @base64_encode('http://localhost/HISDauTieng/trunk/main/imgcreator/datacurve2.png');

//$imgdata = @base64_decode($imagedata);

	// Temporary file
    $png = "../images/img.png";

    // Calls the rrdtool command to generate the PNG
    //$this->getCPU($png, ... other parameters ...); // 

    // Load, Remove all alpha and save in a new file
    $img =  ImageCreateFromPNG($png);

    imagePNG($img, "../images/demo.png");

    // Load to PDF
    $pdf->Image("../images/demo.png", 30, 50);

    // Remove all temporaries
    unlink($png);
    //unlink("$png.png");


//$pdf->Image('http://localhost/HISDauTieng/trunk/main/imgcreator/datacurve2.png', $x, $y, 250, 120, '', '', '', true, 300, '', false, false, 0, false, false, false);

// -------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_009.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>