<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

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

$lang_tables[]='departments.php';
define('LANG_FILE','nursing.php');
define('NO_CHAIN',1);
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
# Get the encouter data
$enc_obj=& new Encounter($pn);
if($enc_obj->loadEncounterData()){
	$encounter=$enc_obj->getLoadedEncounterData();
	
	if($encounter['sex']=='m') $sex_patient = 'Nam';			//nam hay nu
	else $sex_patient = 'Nữ';
}

require_once($root_path.'modules/news/includes/inc_editor_fx.php');
include_once($root_path.'include/care_api_classes/class_charts.php');
$charts_obj= new Charts;


$sepChars=array('-','.','/',':',',');

//Get info of current department, ward
$ward_nr=$encounter['current_ward_nr'];
$dept_nr=$encounter['current_dept_nr'];
if ($ward_nr!=''){
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$Ward = new Ward;
	if($wardinfo = $Ward->getWardInfo($ward_nr)) {
		$wardname = $wardinfo['name'];
		$deptname = ($$wardinfo['LD_var']);
		$dept_nr = $wardinfo['dept_nr'];
	}
} elseif ($dept_nr!=''){
	require_once($root_path.'include/care_api_classes/class_department.php');
	$Dept = new Department;
	if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
		$deptname = ($$deptinfo['LD_var']);
		$wardname = '';
	}
}

function aligndate(&$ad,&$am,&$ay)
{
	if(!checkdate($am,$ad,$ay))
	{
		if($am==12)
		{
			$am=1;
			$ad=1;
			$ay++;
		}
		else
		{
			$am=$am+1;
    		$ad=1;
		}
	}
}
function getdata($info,$d,$m,$y,$short=0){
	if(is_object($info)){
		$content='';
		$date=date('Y-m-d',mktime(0,0,0,$m,$d,$y));
		while($data=$info->FetchRow()){
			if($data['date']==$date) {
				$temphour = explode(':',$data['time']);
				if($temphour[0]<12){
					if($short) $content=$data['short_notes']."<br>".$content;
						else $content=$data['notes']."<br>".$content;
				}else{
					if($short) $content1=$data['short_notes']."<br>".$content1;
						else $content1=$data['notes']."<br>".$content1;				
				}
			}
		}
		$info->MoveFirst();
		return '<font size="9">'.trim($content).'</font></td><td><font size="9">'.trim($content1).'</font>' ;
	}else{return '</td><td>';}
}
function getdatameasure1($info,$d,$m,$y){			//Ham nay sap xep theo 2 cot, ko liet ke time ghi
	if(is_object($info)){
		$content='';
		$content1='';
		$date=date('Y-m-d',mktime(0,0,0,$m,$d,$y));
		while($data=$info->FetchRow()){
			if($data['msr_date']==$date) {
				$temphour = explode(':',$data['msr_time']);
				if($temphour[0]<12){
					$content .= $data['value']."<br>";
				}else $content1 .= $data['value']."<br>";
			}
		}
		$info->MoveFirst();
		return '<font size="9">'.$content.'</font></td><td><font size="9">'.$content1.'</font>' ;
	}else{return '</td><td>';}
}
function getdatanursing1($info,$d,$m,$y){	
	if(is_object($info)){
		$content='';
		$content1='';
		$date=date('Y-m-d',mktime(0,0,0,$m,$d,$y));
		while($data=$info->FetchRow()){
			if($data['msr_date']==$date) {
				$temphour = explode(':',$data['msr_time']);
				if($temphour[0]<12){
					$content .= $data['measured_by'].", ";
				}else $content1 .= $data['measured_by'].", ";
			}
		}
		$info->MoveFirst();
		
		if(strlen($content)>2)
			$content = substr($content, 0, -2);
		if(strlen($content1)>2)
			$content1 = substr($content1, 0, -2);	
			
		return '<font size="9">'.$content.'</font></td><td><font size="9">'.$content1.'</font>' ;
	}else{return '</td><td>';}
}

require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetAuthor($cell);
$pdf->SetTitle('Phiếu Theo Dõi Chức Năng Sống');
$pdf->SetMargins(3, 8, 3);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);

// add a page: Trang 1
$pdf->AddPage();

// ----------------------------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);
$header_1='<table><tr>
			<td width="22%">SỞ Y TẾ BÌNH DƯƠNG<br>
				BV: '.$cell.'<br>
				KHOA: '.$deptname.' '.$wardname.'
			</td>
			<td align="center" width="55%"><b><font size="14">PHIẾU THEO DÕI CHỨC NĂNG SỐNG</font></b></td>
			<td>
				MS: 10/BV-01<br>
				Số vào viện: '.$pn.'
			</td>			
		</tr>
		</table>';
$pdf->writeHTML($header_1);
$pdf->Ln();
$pdf->writeHTMLCell(125, 0, '', '', str_pad("Họ tên người bệnh: ...".$encounter['name_last'].' '.$encounter['name_first'], 90, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);   
$pdf->writeHTMLCell(35, 0, '', '', str_pad(" Tuổi: ...".$encounter['tuoi'], 26, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad(" Nam/nữ: ...".$sex_patient, 25, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);

$pdf->writeHTMLCell(120, 0, '', '', str_pad(" Số giường: ...".$encounter['giuong'], 98, ".", STR_PAD_RIGHT), 0, 0, 0, true, 'L', true);
$pdf->writeHTMLCell(0, 0, '', '', str_pad("Buồng: ...".$encounter['current_room_nr'], 63, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'R', true);

//Chan doan
$diagnosis=$charts_obj->getChartNotes($pn,12);
if(is_object($diagnosis)){
	$diagnosis->MoveLast();				//Chi hien ra chan doan sau cung
	$buff=$diagnosis->FetchRow();
	$text_chandoan=str_replace('**','</span>',(nl2br($buff['notes'])));
	$text_chandoan=str_replace('*','<span style="background-color:yellow">',$text_chandoan);
}
$pdf->writeHTMLCell(0, 0, '', '', str_pad("Chẩn đoán: ..".$text_chandoan, 150, ".", STR_PAD_RIGHT), 0, 1, 0, true, 'L', true);
$pdf->Ln();


#Ngay thang nam bat dau
if(!$kmonat) $kmonat=date('n');
if(!$tag) $tag=date('j');
if(!$jahr) $jahr=date('Y');

$date_start=date('Y-m-d',mktime(0,0,0,$kmonat,$tag,$jahr));
$date_end=date('Y-m-d',mktime(0,0,0,$kmonat,$tag+6,$jahr));

$actmonat=$kmonat;
$actjahr=$jahr;
$tagname=date("w",mktime(0,0,0,$kmonat,$tag,$jahr));
$tagnamebuf=$tagname;
$html_table='<table cellpadding="2" cellspacing="0" border="1" width="100%">
					<tr><td colspan="2" align="center" width="60"><font size="9">Ngày, tháng</font></td>';
					
//Do thi Mach, Nhiet do					
for ($i=$tag,$acttag=$tag,$d=0,$tgbuf=$tagname;$i<($tag+7);$i++,$d++,$tgbuf++,$acttag++)
{
	//$html_table .= '<td bgcolor="orange">'.$tgbuf.'</td>';
	
	$html_table .= '<td colspan="2" ';
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	
	switch($tgbuf) 
		{
			case 0: $html_table .=' bgcolor="orange"';break;
			case 6: $html_table .=' bgcolor="#ffffcc"';break;
			case 7: $html_table .=' bgcolor="orange"'; $tgbuf=0;break;
			default: $html_table .=' bgcolor="white"';
		}
	$html_table .=' align="center" width="75">'.$tage[$tgbuf].' . '.formatShortDate2Local($actmonat,$acttag,$date_format);	
	$html_table .='</td>';
}
$html_table .='</tr>
				<tr><td align="center"><font size="9">Mạch<br>L/ph</font></td><td align="center"><font size="9">Nhiệt<br>độ C</font></td>';

for($j=0;$j<7;$j++)				
	$html_table .= '<td><font size="8">0h &nbsp; 6h</font></td><td><font size="8">12h 18h</font></td>';

$imgsrc = $root_path."uploads/photos/datacurve/".$pn.$jahr.$kmonat.$tag.".png";	
if(!file_exists($imgsrc))
	$imgsrc = $root_path.'main/imgcreator/datacurve2.png';

$html_table .=	'</tr>
				<tr>
					<td colspan="2" align="right"><img src="'.$root_path.'gui/img/common/default/scale1.gif'.'" width="41.7"></td>
					<td colspan="14"><img src="'.$imgsrc.'" border="0" /></td>
				</tr>';
			
//$html_image = '<img src="'.$root_path.'main/imgcreator/datacurve.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&mo='.$kmonat.'&dy='.$tag.'" width="250" height="120" border="0" />';	
	
//Huyet ap
$actmonat=$kmonat;
$actjahr=$jahr;
$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
$html_table .='	<tr>
		<td colspan="2"><br>1. Huyết áp<br>(mmHg)</td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	$html_table .= '<td>';

	if($r=getdatameasure1($huyetap,$i,$kmonat,$jahr))  
		$html_table .= $r;

	$html_table .= "</td>";
}
$html_table .= "</tr>";				
				
//Can nang
$actmonat=$kmonat;
$actjahr=$jahr;
$cannang=$charts_obj->getManyDaysMeasureByType($pn,6,$date_start,$date_end);
$html_table .='	<tr>
		<td colspan="2"><br>2. Cân nặng<br>(Kg)</td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	$html_table .= '<td>';

	if($r=getdatameasure1($cannang,$i,$kmonat,$jahr))  
		$html_table .= $r;

	$html_table .= "</td>";
}
$html_table .= "</tr>";				
				
			
//Nhip tho
$actmonat=$kmonat;
$actjahr=$jahr;
$nhiptho=$charts_obj->getManyDaysMeasureByType($pn,10,$date_start,$date_end);	
$html_table .='	<tr>
		<td colspan="2"><br>3. Nhịp thở<br>(L/ph)</td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	$html_table .= '<td>';

	if($r=getdatameasure1($nhiptho,$i,$kmonat,$jahr))  
		$html_table .= $r;

	$html_table .= "</td>";
}
$html_table .= "</tr>";	

//4.
$actmonat=$kmonat;
$actjahr=$jahr;
$main_notes=$charts_obj->getChartDailyMainNotes($pn,$date_start,$date_end);	
$html_table .='	<tr>
		<td colspan="2"><br>4.<br></td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	$html_table .= "<td>";
	if($r=&getdata($main_notes,$i,$kmonat,$jahr))  
		$html_table .= hilite(nl2br($r));	
	$html_table .= "</td>";
}
$html_table .= "</tr>";	

//5.
$actmonat=$kmonat;
$actjahr=$jahr;
$more_notes_5=$charts_obj->getChartDailyNotes_5($pn,$date_start,$date_end);		
$html_table .='	<tr>
		<td colspan="2"><br>5.<br></td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	$html_table .= "<td>";
	if($r=&getdata($more_notes_5,$i,$kmonat,$jahr))  
		$html_table .= hilite(nl2br($r));	
	$html_table .= "</td>";
}
$html_table .= "</tr>";	
				
//Dieu duong
$actmonat=$kmonat;
$actjahr=$jahr;
$dieuduong=$charts_obj->getManyDaysNursingWithBlockTime($pn,$date_start,$date_end);
$html_table .='	<tr><td colspan="2">Y tá (ĐD)<br><br><i>Ký và ghi tên</i></td>';

for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	$html_table .= "<td>";
	if($r=getdatanursing1($dieuduong,$i,$kmonat,$jahr))  
		$html_table .= hilite(nl2br($r));	
	$html_table .= "</td>";
}
$html_table .= "</tr>";	


					
$html_table .='</table>';

$pdf->writeHTML($html_table);


$pdf->setJPEGQuality(90);
$x=$pdf->GetX();
$y=$pdf->GetY();




// ----------------------------------------------------------------------------

$pdf->lastPage();

//Close and output PDF document
$pdf->Output('PhieuTheoDoiChucNagSong.pdf', 'I');


?>
