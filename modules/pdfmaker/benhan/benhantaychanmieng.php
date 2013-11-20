<?php

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);

require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='person.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
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

$encounter_class=$enc_obj->getEncounterClassInfo($encounter['encounter_class_nr']);
$insurance_class=$enc_obj->getInsuranceClassInfo($encounter['insurance_class_nr']);
# Resolve the encounter class name
if (isset($$encounter_class['LD_var'])&&!empty($$encounter_class['LD_var'])){
	$eclass=$$encounter_class['LD_var'];
}else{
	$eclass= $encounter_class['name'];
} 
# Resolve the insurance class name
if (isset($$insurance_class['LD_var'])&&!empty($$insurance_class['LD_var'])) $insclass=$$insurance_class['LD_var']; 
    else $insclass=$insurance_class['name']; 
require_once($root_path.'include/care_api_classes/class_measurement.php');
$measurement_obj=new Measurement;
# Get ward or department infos
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj = new Department();
$current_dept_LDvar=$dept_obj->LDvar($encounter['current_dept_nr']);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);
require_once($root_path.'include/care_api_classes/class_khambenh.php');
$kb_obj= new Khambenh();
$kb_ck=$kb_obj->getKhambenhTCM($enc);
$kb_ck_1=$kb_ck->fetchrow();

require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj = new Ward();
$wardName = $ward_obj->getWardInfo($enc_obj->encounter['current_ward_nr']);
$roomName = $ward_obj->_getActiveRoomInfo($enc_obj->encounter['current_room_nr'],$enc_obj->encounter['current_ward_nr']);
$roomNumber = $enc_obj->encounter['current_room_nr'];
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
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
$str=$encounter['name_last']." ".$encounter['name_first'];	
$s_obj=new exec_String();
$s=$s_obj->BASIC_String();	
$s=$s_obj->upper($str);
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('BỆNH ÁN TAY CHÂN MIỆNG');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(8, 8, 7);    

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);
    $pdf->AddPage();
// set font
$pdf->SetFont('dejavusans', 'B', 10);

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('dejavusans', '', 10);

// -----------------------------------------------------------------------------

$tbl = '
<table cellspacing="0" cellpadding="1">
    <tr>
		<td width="30%">Sở Y tế:.................................</td>
		<td width="45%"></td>
		<td width="25%">Số lưu trữ:.....................</td>
    </tr>
	<tr>
		<td>Bệnh viện:............................</td>
		<td align="center" style="font-size:150%" rowspan="2">BỆNH ÁN TAY CHÂN MIỆNG</td>
		<td>Mã YT:....../....../....../.......</td>
		
	</tr>
	<tr>
		<td>Khoa:.................Giường:........</td>
		<td></td>
		<td></td>
	</tr>
</table><br/>';

// -----------------------------------------------------------------------------

$pdf->writeHTML($tbl, true, false, false, false, '');
$x=$pdf->GetX();
$y=$pdf->GetY(); 
$pdf->DrawRect(($x+135),($y+6),5,4.5,10); //sinh ngày, tuổi
$pdf->DrawRect(($x+40),($y+12),5,4.5,1); //nam
$pdf->DrawRect(($x+62),($y+12),5,4.5,1); //nữ
$pdf->DrawRect(($x+183),($y+12),5,4.5,2); //nghề nghiệp
$pdf->DrawRect(($x+74),($y+18),5,4.5,2); //dân tộc
$pdf->DrawRect(($x+183),($y+18),5,4.5,2); //ngoại kiều
$pdf->DrawRect(($x+126),($y+35),5,4.5,1); //bhyt
$pdf->DrawRect(($x+150),($y+35),5,4.5,1); //thu phí
$pdf->DrawRect(($x+169),($y+35),5,4.5,1); //miễn
$pdf->DrawRect(($x+189),($y+35),5,4.5,1); //khác

$pdf->DrawRect(($x+123),($y+41),12.5,4.5,4); //số thẻ bhyt
$pdf->DrawRect(($x+178),($y+41),16,4.5,1); //số thẻ bhyt\
$pdf->DrawRect(($x+153),($y+68), 5, 4.5, 1); //1.cơ quan y tế
$pdf->DrawRect(($x+173),($y+68), 5, 4.5, 1); //2.tự đến
$pdf->DrawRect(($x+189),($y+68), 5, 4.5, 1); //3.khác
$pdf->DrawRect(($x+47),($y+74), 5, 4.5, 1); //1.cấp cứu
$pdf->DrawRect(($x+64),($y+74), 5, 4.5, 1); //2.KKB
$pdf->DrawRect(($x+94),($y+74), 5, 4.5, 1); //3.Khoa điều trị
$pdf->DrawRect(($x+153),($y+74), 5, 4.5, 1); //vào viện do bệnh này lần thứ
$pdf->DrawRect(($x+24),($y+86), 13, 4.5, 1); //vào khoa
$pdf->DrawRect2(($x+88),($y+86), 5, 4.5, 2); //ngày ĐTr
$pdf->DrawRect(($x+24),($y+91), 13, 4.5, 1); //chuyển
$pdf->DrawRect2(($x+88),($y+91), 5, 4.5, 2); //ngày ĐTr
$pdf->DrawRect(($x+24),($y+96), 13, 4.5, 1); //khoa
$pdf->DrawRect2(($x+88),($y+96), 5, 4.5, 2); //ngày ĐTr
$pdf->DrawRect(($x+148.5),($y+80), 5, 4.5, 1); //1.tuyến trên
$pdf->DrawRect(($x+174),($y+80), 5, 4.5, 1); //2.tuyến dưới
$pdf->DrawRect(($x+188),($y+80), 5, 4.5, 1); //3.ck
$pdf->DrawRect(($x+122),($y+102), 5, 4.5, 1); //1.ra viện
$pdf->DrawRect(($x+143),($y+102), 5, 4.5, 1); //2.xin về
$pdf->DrawRect(($x+163),($y+102), 5, 4.5, 1); //3.bỏ về
$pdf->DrawRect(($x+185),($y+102), 5, 4.5, 1); //4.đưa về
$pdf->DrawRect2(($x+178),($y+107), 5, 4.5, 3); //tổng số ngày điều trị
$pdf->DrawRect2(($x+85),($y+127), 5, 4.5, 4); //20.nơi chuyển đến
$pdf->DrawRect2(($x+85),($y+135), 5, 4.5, 4); //21.KKB, cấp cứu
$pdf->DrawRect2(($x+85),($y+144), 5, 4.5, 4); //22.khi vào khoa điều trị
$pdf->DrawRect2(($x+173),($y+133), 5, 4.5, 4); //bệnh chính
$pdf->DrawRect2(($x+173),($y+144), 5, 4.5, 4); //bệnh kèm theo
$pdf->DrawRect(($x+158),($y+150), 5, 4.5, 1); //tai biến
$pdf->DrawRect(($x+125),($y+150), 5, 4.5, 1); //biến chứng
$pdf->DrawRect(($x+60),($y+150), 5, 4.5, 1); //biến chứng
$pdf->DrawRect(($x+26),($y+150), 5, 4.5, 1); //1.do phẫu thuật
$pdf->DrawRect(($x+31),($y+191), 5, 4.5, 1); //1.khỏi
$pdf->DrawRect(($x+31),($y+196), 5, 4.5, 1); //2.đỡ, giảm
$pdf->DrawRect(($x+31),($y+201), 5, 4.5, 1); //3.không thay đổi
$pdf->DrawRect(($x+60),($y+191), 5, 4.5, 1); //4.nặng hơn
$pdf->DrawRect(($x+60),($y+196), 5, 4.5, 1); //5.tử vong
$pdf->DrawRect(($x+18),($y+212), 5, 4.5, 1); //1.lành tính
$pdf->DrawRect(($x+41),($y+212), 5, 4.5, 1); //2.nghi ngờ
$pdf->DrawRect(($x+61),($y+212), 5, 4.5, 1); //3.ác tính
$pdf->DrawRect(($x+105),($y+191), 5, 4.5, 1); //1.do bệnh
$pdf->DrawRect(($x+146),($y+191), 5, 4.5, 1); //2.do tai biến điều trị
$pdf->DrawRect(($x+188),($y+191), 5, 4.5, 1); //3.khác
$pdf->DrawRect(($x+105),($y+196), 5, 4.5, 1); //1.trong 24 giờ vào viện
$pdf->DrawRect(($x+146),($y+196), 5, 4.5, 1); //2.trong 48 giờ vào viện
$pdf->DrawRect(($x+188),($y+196), 5, 4.5, 1); //3.trong 72 giờ vào viện
$pdf->DrawRect2(($x+173),($y+207), 5, 4.5, 4);
$pdf->DrawRect(($x+108),($y+212), 5, 4.5, 1);
$pdf->DrawRect2(($x+173),($y+217), 5, 4.5, 4);
// -----------------------------------------------------------------------------
//$namsinh=date("Y",strtotime($encounter['date_birth']));
//echo $namsinh;
//$tuoi=date("Y")-$namsinh;
$ngayden=formatDate2Local($encounter['encounter_date'],$date_format);
$gioden=formatDate2Local($encounter['encounter_date'],$date_format,TRUE,TRUE);
//echo $encounter['date_birth'];
$tb1='
	<table cellpadding="2">
		<tr>
			<td style="font-size:110%" width="55%" align="left"><b>I.HÀNH CHÍNH</b></td>
			<td align="right" width="45%">Tuổi&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td>1. Họ và tên <i>(In hoa)</i>   '.str_pad("..".$s, 50, ".", STR_PAD_RIGHT).'</td>
			
			<td align="left">2. Sinh ngày:';
	if($encounter['tuoi']<10){
$tb1.=substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."    ".substr(formatDate2Local($encounter['date_birth'],$date_format),8,1)."   ".substr(formatDate2Local($encounter['date_birth'],$date_format),9,1)."                  ".$tuoi;
}else{
if(strlen($encounter['date_birth'])>4){
$tb1.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."&nbsp;&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."&nbsp;&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),8,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),9,1)."&nbsp;&nbsp;&nbsp;".substr($tuoi,0,1)."&nbsp;&nbsp;&nbsp;".substr($tuoi,1,1);
}else{
$tb1.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),2,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".substr($tuoi,0,1)."&nbsp;&nbsp;&nbsp;".substr($tuoi,1,1);
}
}		
	$tb1.='</td>	</tr>
		<tr>';
		if($encounter['sex']=='m'){
			$tb1.='<td>3. Giới:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.Nam&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Nữ</td>';
			}else{
			$tb1.='<td>3. Giới:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.Nam&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Nữ&nbsp;&nbsp;&nbsp;X</td>';
			}
	$tb1.='<td>4.Nghề nghiệp:..'.str_pad("..".$encounter['nghenghiep'], 40, ".", STR_PAD_RIGHT).'</td>
		</tr>
		<tr>
			<td>5.Dân tộc:...............................................</td>
			<td>6. Ngoại kiều:...'.str_pad("..".$encounter['ngoaikieu'], 40, ".", STR_PAD_RIGHT).'</td>
		</tr>
		<tr>
			<td width="60%">Địa chỉ: Số nhà:'.str_pad("..".$encounter['addr_str_nr'], 10, ".", STR_PAD_RIGHT).'Thôn, phố..'.str_pad("..".$encounter['addr_str'],35, ".", STR_PAD_RIGHT).'</td>
			<td>Xã, phường:..'.str_pad("..".$encounter['phuongxa_name'], 40, ".", STR_PAD_RIGHT).'</td>
		</tr>
		<tr>
			<td>Huyện (Q,Tx)..'.str_pad("..".$encounter['quanhuyen_name'], 44, ".", STR_PAD_RIGHT).'</td>
			<td>Tỉnh, thành phố:.'.str_pad("..".$encounter['citytown_name'], 20, ".", STR_PAD_RIGHT).'</td>
		</tr>
		<tr>
			<td width="45%">8.Nơi làm việc..'.str_pad("..".$encounter['noilamviec'], 44, ".", STR_PAD_RIGHT).'</td>';
			echo $inclass;
	if($insclass=='BHYT'){
		$tb1.='
			<td width="55%">9. Đối tượng: 1.BHYT&nbsp;X&nbsp;&nbsp;&nbsp;2.Thu phí&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Miễn&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.Khác</td>';
	}else if($insclass=='Thu Phi'){
	$tb1.='
			<td width="55%">9. Đối tượng: 1.BHYT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Thu phí&nbsp; X&nbsp;&nbsp;3. Miễn&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.Khác</td>';
	}else if($insclass=='Mien Phi'){
	$tb1.='
			<td width="55%">9. Đối tượng: 1.BHYT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Thu phí&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Miễn&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;4.Khác</td>';
	}else if($insclass=='Khac'){
	$tb1.='
			<td width="55%">9. Đối tượng: 1.BHYT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Thu phí&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Miễn&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.Khác &nbsp; X</td>';
	}
	
	if($encounter['insurance_exp']!='0000-00-00'){
	$insexp=formatDate2STD($encounter['insurance_exp'],$date_format);
		$tb1.='</tr>
		<tr>
			<td width="50%">10. BHYT giá trị đến ngày.'.date("d",strtotime($insexp)).'.tháng.'.date("m",strtotime($insexp)).'.năm.'.date("Y",strtotime($insexp)).'.</td>
			<td width="50%">Số thẻ BHYT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr($encounter['insurance_nr'],0,2).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr($encounter['insurance_nr'],3,1).substr($encounter['insurance_nr'],5,2).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr($encounter['insurance_nr'],8,2).substr($encounter['insurance_nr'],11,3).'&nbsp;&nbsp;'.substr($encounter['insurance_nr'],15,5).'&nbsp;&nbsp;&nbsp;&nbsp;'.str_replace("-","",$encounter['madk_kcbbd']).'</td>
		</tr>';
		}else{
		$tb1.='</tr>
		<tr>
			<td width="50%">10. BHYT giá trị đến ngày.......tháng........năm............</td>
			<td width="50%">Số thẻ BHYT</td>
		</tr>';
		}
	$tb1.='	<tr>
			<td colspan="2">11. Họ tên, địa chỉ người nhà khi cần báo tin:...................................................................................................</td>
		</tr>
		<tr>
			<td width="50%">.....................................................................................</td>
			<td width="50%">Điện thoại số:.............................................................</td>
		</tr>
	</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------
$tb1='<b style="font-size:100%">II. QUẢN LÝ NGƯỜI BỆNH</b>';
$pdf->writeHTML($tb1, true, false, false, false, '');
$pdf->SetFont('dejavusans', '', 9);
$tb1='
	<table cellpadding="2" border="1">
		<tr>
			<td width="51%">
				<table cellpadding="2">
					<tr>
						<td>12. Vào viện:.'.date("H",strtotime($gioden)).'.giờ.'.date("m",strtotime($gioden)).'.ph ngày: .'.substr($ngayden,0,2).'./ .'.substr($ngayden,3,2).'./ .'.substr($ngayden,6,4).'.</td>
					</tr>
					<tr>
						<td>13. Trực tiếp vào: <i stye="font-size:95%">1.Cấp cứu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.KKB &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Khoa điều trị</i></td>
					</tr>
				</table>
			</td>
			<td width="49%">
				<table cellpadding="2">
					<tr>
						<td>14. Nơi giới thiệu: <i style="font-size:95%">1.Cơ quan y tế &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Tự đến &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Khác </i></td>
					</tr>
					<tr>
						<td>- Vào viện do bệnh này lần thứ</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="51%">
				<table border="0" cellpadding="2">
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Khoa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ng/th/ năm Số ngày ĐTr</td>
					</tr>
					<tr>
						<td>14. Vào khoa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.......Giờ.......phút......./......./.......</td>
					</tr>
					<tr>
						<td> </td>
					</tr>
					<tr>
						<td>16. Chuyển &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.......Giờ.......phút......./......./.......</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Khoa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.......Giờ.......phút......./......./.......</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.......Giờ.......phút......./......./.......</td>
					</tr>
				</table>
			</td>
			<td width="49%">
				<table cellpadding="2">
					<tr>
						<td>17. Chuyển viện: <i style="font-size:95%">1.Tuyến trên&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Tuyến dưới&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.CK</i></td>
					</tr>
					<tr>
						<td>-Chuyển đến:.....................................................................</td>
					</tr>
					<tr>
						<td>..........................................................................................</td>
					</tr>
					<tr>
						<td>18. Ra viện:....................giờ...........ngày......../....../..........</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;<i>1.Ra viện&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Xin về&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Bỏ về&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.Đưa về</i></td>
					</tr>
					<tr>
						<td>19.Tổng số ngày điều trị:...................................</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
//-----------------------

$pdf->SetFont('dejavusans', '', 10);
$tb1='<b style="font-size:100%">III. CHẨN ĐOÁN </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MÃ ICD10 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MÃ ICD10';
$pdf->writeHTML($tb1, true, false, false, false, '');
$pdf->SetFont('dejavusans', '', 9);
$tb1='
<table cellpadding="2" border="1">
<tr>
	<td width="55%">
		<table cellpadding="2">
			<tr>
				<td>19. Nơi chuyển đến:......................................................................</td>
			</tr>
			<tr>
				<td>.................................................................................</td>
			</tr>
			<tr>
				<td>20. KKB, Cấp cứu......................................................</td>
			</tr>
			<tr>
				<td>21. Khi vào khoa điều trị: ..............................................................</td>
			</tr>
			<tr>
				<td>.................................................................................</td>
			</tr>
			<tr>
				<td>+ Thủ thuật&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+ Phẫu thuật</td>
			</tr>
			
		</table>
	</td>
	<td width="45%">
		<table cellpadding="2">
			<tr>
				<td>22. Ra viện:</td>
			</tr>
			<tr>
				<td>+ Bệnh chính: .......................................................</td>
			</tr>
			<tr>
				<td>..............................................................</td>
			</tr>
			
			<tr>
				<td>+ Bệnh kèm theo: .....................................................</td>
			</tr>
			<tr>
				<td>..............................................................</td>
			</tr>
			<tr>
				<td>-Tai biến&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-Biến chứng</td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table>
	</td>
</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------

$pdf->SetFont('dejavusans', '', 10);
$tb1='<b>IV. TÌNH TRẠNG RA VIỆN</b>';
$pdf->writeHTML($tb1, true, false, false, false, '');
$pdf->SetFont('dejavusans', '', 9);
$tb1='
<table border="1">
	<tr>
		<td width="35%">
			<table cellpadding="2">
				<tr>
					<td>23. Kết quả điều trị:  </td>
				</tr>
				<tr>
					<td><i>1. Khỏi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Nặng hơn</i></td>
				</tr>
				<tr>
					<td><i>2. Đỡ, giảm&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. Tử vong</i></td>
				</tr>
				<tr>
					<td><i>3. Không thay đổi</i></td>
				</tr>
				<tr>
					<td>24. Giải phẫu bệnh <i>(khi có sinh thiết)</i></td>
				</tr>
				<tr>
					<td style="font-size:93%"><i>1.Lành tính&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Nghi ngờ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Ác tính</i></td>
				</tr>
				<tr>
					<td> </td>
				</tr>
			</table>
		</td>
		<td width="65%">
			<table cellpadding="2">
				<tr>
					<td>25. Tình hình tử vong:.......giờ.......ph&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ngày........tháng........năm..........</td>
				</tr>
				<tr>
					<td style="font-size:95%"><i>1.Do bệnh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Do tai biến điều trị&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Khác</i></td>
				</tr>
				<tr>
					<td style="font-size:93%"><i>1.Trong 24 giờ vào viện&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Trong 48 giờ vào viện&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Trong 72 giờ vào viện</i></td>
				</tr>
				<tr>
					<td>26. Nguyên nhân chính tử vong:.......................................................................</td>
				</tr>
				<tr>
					<td>......................................................................................................</td>
				</tr>
				<tr>
					<td>29.Khám nghiệm tử thi:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;31.Chẩn đoán giải phẫu tử thi:</td>
				</tr>
				<tr>
					<td>......................................................................................................</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------
$pdf->SetFont('dejavusans', '', 10);
$tb1='
<table>
	<tr>
		<td align="center"> </td>
		<td align="center">Ngày...........tháng........năm...........</td>
	</tr>
	<tr>
		<td align="center" style="font-size:110%"><b>Giám đốc bệnh viện</b></td>
		<td align="center" style="font-size:110%"><b>Trưởng khoa</b></td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td> </td>
		<td> </td>
	</tr>
	<tr>
		<td align="center">Họ tên..................................................</td>
		<td align="center">Họ tên..................................................</td>
	</tr>
</table>
';

$pdf->writeHTML($tb1, true, false, false, false, '');

// -----------------------------------------------------------------------------T2


$pdf->AddPage();
$x=$pdf->GetX();
$y=$pdf->GetY();

$pdf->SetFont('dejavusans', '', 10);
$pdf->DrawRect(($x),($y+29),3,3.2,1);//sốt
$pdf->DrawRect(($x+13),($y+29),3,3.2,1);//phát ban
$pdf->DrawRect(($x+34),($y+29),3,3.2,1);//bỏ ăn
$pdf->DrawRect(($x+70),($y+29),3,3.2,1);//giật mình
$pdf->DrawRect(($x+129),($y+29),3,3.2,1);//nôn ói
$pdf->DrawRect(($x+145),($y+29),3,3.2,1);//Co giật
$pdf->DrawRect(($x+163),($y+29),3,3.2,1);//Run chi
$pdf->DrawRect(($x+2),($y+45),3,3.2,1);//Đi học
$pdf->DrawRect(($x),($y+57),3,3.2,1);//trẻ chung nhà
$pdf->DrawRect(($x+62),($y+57),3,3.2,1);//trẻ gần nhà
$pdf->DrawRect(($x+119),($y+57),3,3.2,1);//trẻ cùng trường
$pdf->DrawRect(($x+69),($y+142),5,5,4);//PARA
$pdf->DrawRect(($x+40),($y+154),2,2.2,1);//Đẻ thường
$pdf->DrawRect(($x+62),($y+154),2,2.2,1);//forrceps
$pdf->DrawRect(($x+87),($y+154),2,2.2,1);//Giác hút
$pdf->DrawRect(($x+115),($y+154),2,2.2,1);//đẻ phẫu thuật
$pdf->DrawRect(($x+148),($y+154),2,2.2,1);//Dẻ chỉ huy
$pdf->DrawRect(($x+177),($y+154),2,2.2,1);// Khác
$pdf->DrawRect(($x+89),($y+159),5,5,1);//dị tật bẩm sinh
$pdf->DrawRect(($x+45),($y+188),5,5,1);//Sữa mẹ
$pdf->DrawRect(($x+82),($y+188),5,5,1);//nuôi nhân tạo
$pdf->DrawRect(($x+114),($y+188),5,5,1);//Hôn hợp
$pdf->DrawRect(($x+40),($y+194),5,5,1);//Tại vườn trẻ
$pdf->DrawRect(($x+85),($y+194),5,5,1);//Tại nhà
$pdf->DrawRect(($x+41),($y+202),2,2.2,1);//Lao
$pdf->DrawRect(($x+59),($y+202),2,2.2,1);//Bại liệt
$pdf->DrawRect(($x+74),($y+202),2,2.2,1);//Sởi
$pdf->DrawRect(($x+91),($y+202),2,2.2,1);//Ho gà
$pdf->DrawRect(($x+112),($y+202),2,2.2,1);//Uốn ván
$pdf->DrawRect(($x+133),($y+202),2,2.2,1);//Bạch hầu
$pdf->DrawRect(($x+150),($y+202),2,2.2,1);//Khác
$pdf->DrawRect(($x+6),($y+239),2,2.2,1);//Tím
$pdf->DrawRect(($x+87),($y+239),2,2.2,1);//Tỉnh
$pdf->DrawRect(($x+109),($y+239),2,2.2,1);//Li bì
$pdf->DrawRect(($x+128),($y+239),2,2.2,1);//hôn mê
$pdf->DrawRect(($x+5),($y+245),2,2.2,1);// Lóet miệng
$pdf->DrawRect(($x+32),($y+245),2,2.2,1);//Phát ban
$tb1='
<table>
	<tr>
		<td> <b>A- BỆNH ÁN</b>
		</td>
	</tr>
	<tr>
		<td><b>I. Lý do vào viện:</b>................................................................................... Vào ngày thứ ............... của bệnh
		</td>
	</tr>
	<tr>
		<td><b>II. Hỏi bệnh:</b>
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------
$pdf->SetFont('dejavusans', '', 10);
$tb1='
<table cellpadding="2">
	<tr>
		<td style="font-size:90%"> <b>1. Quá trình bệnh lý</b>
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">- Triệu chứng khởi phát:
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">
		&nbsp;&nbsp;&nbsp;&nbsp;Sốt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Phát ban &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bỏ ăn, loét miệng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Giật mình - Số lần/24 giờ: ..... lần &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nôn ói &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Co giật &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Run chi
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">Dấu hiệu khác.....................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">Dịch tễ ................................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Đi học <small>( Nơi chăm sóc trẻ tập trung, nhà trẻ, mẫu giáo, phổ thông)</small>
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">Ghi rõ địa chỉ trường ...........................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">&nbsp;&nbsp;&nbsp; Có trẻ ở chung nhà mắc bệnh &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Có trẻ ở gần nhà mắc bệnh  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Có trẻ ở cùng trường mắc bệnh
		</td>
	</tr>
	<tr>
		<td style="font-size:90%"> Điều trị tuyến trước ( nếu có ): ...........................................................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:90%"> Khác: ..................................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
		</td>
	</tr>
	<tr>
		<td><b>2. Tiền sử bệnh</b>
		</td>
	</tr>
	<tr>
		<td>+ Bản thân: ......................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>+ Gia đình: .......................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td><b> 3. Quá trình sinh trưởng:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; S &nbsp; &nbsp;S ;&nbsp;&nbsp;S &nbsp;&nbsp; S
		</td>
	</tr>
	<tr>
		<td> - Con thứ mấy ........ Tiền thai ( Para )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ( Sinh ( đủ tháng ), Sớm( đẻ non ), Sấy ( nạo, hút ), Sống ) 
		</td>
	</tr>
	<tr>
		<td>- Tình trạng khi sinh: <small style="font-size:20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Đẻ thường &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Forrceps &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3. Giác hút &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 4. Đẻ phẫu thuật &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 5. Đẻ chỉ huy &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6. Khác </small>
		</td>
	</tr>
	<tr>
		<td>- Cân nặng lúc sinh:..........kg &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dị tật bẩm sinh: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cụ thể bẩm sinh:.......................................................
		</td>
	</tr>
	<tr>
		<td>..........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>Phát triển về tinh thần:.....................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>Phát triển về vận động:.....................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>Các bệnh lý khác:..............................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>- Nuôi dưỡng: 1. Sữa mẹ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Nuôi nhân tạo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3. Hỗn hợp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Cai sữa tháng thứ:.............................
		</td>
	</tr>
	<tr>
		<td>- Chăm sóc: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. Tại vườn trẻ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Tại nhà
		</td>
	</tr>
	<tr>
		<td>- Đã tiêm chủng: <small style="font-size:20px"> 1. Lao &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Bại liệt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3. Sởi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 4. Ho gà &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 5. Uốn ván &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 6. Bạch hầu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7. Khác </small>
		</td>
	</tr>
	<tr>
		<td>- Cụ thể những bệnh khác được tiêm chủng: ....................................................................................................
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------
$tb1='
<table cellpadding="3">
	<tr>
		<td> <b>III. Khám bệnh:</b>
		</td>
	</tr>
	<tr>
		<td>
			<b>1. Toàn thân:</b>
		</td>
	</tr>
	<tr>
		<td>
			-Chiều cao: ..............cm;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Vòng ngực: ..............cm; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Vòng đầu: .................cm;
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tím &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SpO<sub>2</sub>: ..............% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tri giác: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1- Tỉnh  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2-Li bì &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3-Hôn mê
		</td>
	</tr>
	<tr>
		<td style="font-size:90%">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Loét miệng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phát ban <small>( Ghi rõ ký hiệu in đậm vào vị trí phát ban, bóng nước theo hình sau) </small>
		</td>
	</tr>
	<tr>
		<td>
			- ........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			- ........................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			- ........................................................................................................................................................................
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------Tr3
$pdf->AddPage();
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->DrawRect(($x+40),($y+8),3,3.2,1);// Rõ
$pdf->DrawRect(($x+51),($y+8),3,3.2,1);//Mờ
$pdf->DrawRect(($x+62),($y+8),3,3.2,1);//gallop
$pdf->DrawRect(($x+84),($y+8),3,3.2,1);//Âm thổi
$pdf->DrawRect(($x+2),($y+14),3,3.2,1);//Tĩnh mạch cổ
$pdf->DrawRect(($x+48),($y+14),3,3.2,1);//TG đổ đầy mm
$pdf->DrawRect(($x+119),($y+14),3,3.2,1);//Vã mồ hôi
$pdf->DrawRect(($x+140),($y+14),3,3.2,1);//Da nổi bông
$pdf->DrawRect(($x+18),($y+27),3,3.2,1);//Cơn ngưng thở
$pdf->DrawRect(($x+48),($y+27),3,3.2,1);//Thở bụng
$pdf->DrawRect(($x+84),($y+27),3,3.2,1);//Thở nông
$pdf->DrawRect(($x+18),($y+31),3,3.2,1);//Khò khè
$pdf->DrawRect(($x+46),($y+31),3,3.2,1);//Thở rít thanh quản
$pdf->DrawRect(($x+84),($y+31),3,3.2,1);//Rút lõm ngực
$pdf->DrawRect(($x+3),($y+37),3,3.2,1);//Ran phổi
$pdf->DrawRect(($x+23),($y+57),3,3.2,1);//Gan to
$pdf->DrawRect(($x+83),($y+90),3,3.2,1);//Cổ gượng
$pdf->DrawRect(($x+103),($y+90),3,3.2,1);//Giật mình lúc khám
$pdf->DrawRect(($x+4),($y+97),3,3.2,1);//Thất diều
$pdf->DrawRect(($x+85),($y+97),3,3.2,1);//Rung nhãn cầu
$pdf->DrawRect(($x+4),($y+103),3,3.2,1);//Lé
$pdf->DrawRect(($x+16),($y+103),3,3.2,1);//yếu chi
$pdf->DrawRect(($x+37),($y+103),3,3.2,1);//Liệt TK
$pdf->DrawRect(($x+4),($y+110),3,3.2,1);//Ngủ gà
$pdf->DrawRect(($x+73),($y+144),3,3.2,1);//Suy hh
$pdf->DrawRect(($x+101),($y+144),3,3.2,1);//Sốc
$pdf->DrawRect(($x+119),($y+144),3,3.2,1);//phù nổi
$pdf->DrawRect(($x+150),($y+144),3,3.2,1);//Rối loạn hh
$pdf->DrawRect(($x+2),($y+150),3,3.2,1);//mạch nhanh
$pdf->DrawRect(($x+42),($y+150),3,3.2,1);//Tăng HA
$pdf->DrawRect(($x+148),($y+150),3,3.2,1);//Gồng chi
$pdf->DrawRect(($x+6),($y+157),3,3.2,1);//Vã mồ hôi toàn thân
$pdf->DrawRect(($x+62),($y+157),3,3.2,1);//Thất điều
$pdf->DrawRect(($x+83),($y+157),3,3.2,1);//Rung nhãn cầu
$pdf->DrawRect(($x+117),($y+157),3,3.2,1);//yếu chi
$pdf->DrawRect(($x+151),($y+157),3,3.2,1);//Liệt TK
$pdf->DrawRect(($x+3),($y+163),3,3.2,1);//Giật mình lúc khám
$pdf->DrawRect(($x+38),($y+163),3,3.2,1);//Bệnh sử giật mình
$pdf->DrawRect(($x+37),($y+213),3,3.2,1);//Oxy
$pdf->DrawRect(($x+66),($y+213),3,3.2,1);//Chống sốc
$pdf->DrawRect(($x+88),($y+213),3,3.2,1);//Điều trị cao HA
$pdf->DrawRect(($x+118),($y+213),3,3.2,1);//An thần
$pdf->DrawRect(($x+136),($y+213),3,3.2,1);//globulin
$pdf->DrawRect(($x+158),($y+213),3,3.2,1);//nhập ICU
$pdf->SetFont('dejavusans', '', 9);
$tb1='
<table cellpadding="4">
	<tr>
		<td>
			<b>
				2. Các cơ quan:
			</b>
		</td>
	</tr>
	<tr>
		<td>
			+ Tuần hoàn: 
			<small style="font-size:23px">Tiếng tim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rõ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mờ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; gallop &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Âm thổi (Ghi rõ):</small>
			.............................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dấu hiệu tĩnh mạch cổ nổi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thời gian đổ đầy mao mạch: ................giây &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Vã mồ hôi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Da nỗi bông
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Dấu hiệu khác: ..................................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			+ Hô hấp:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <small style="font-size:23px"> Cơn ngưng thở &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thở bụng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Thở nông
			<br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Khò khè &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thở rít thanh quản &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rút lõm ngực </small>  
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ran phổi ( Ghi rõ ):..............................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			Dấu hiệu khác: ..........................................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
		...................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			+Tiêu hóa: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <small style="font-size:23px">Gan to (ghi rõ):.................. cm DBS, đặc điểm:.............................................. </small>
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			Dấu hiệu khác: ..........................................................................................................................	
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">		...................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			+ Thận, tiết liệu, sinh dục:........................................................................................... 
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">		...................................................................................................................................................	
		</td>
	</tr>
	<tr>
		<td>
			+ Thần kinh: <small style="font-size:23px">&nbsp; Đồng tử: ........... mm &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PXAS: ........... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cổ gượng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Giật mình lúc khám </small>
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thất điều <small> ( run chi, run người, đứng không vững, đi loạng choạng ) </small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rung giật nhãn cầu
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lé &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Yếu chi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Liệt TK / Liệt mềm cấp ( nuốt sặc, thay đổi giọng nói,... )
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ngủ gà &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dấu hiệu khác ............................................................................................................................................................			
		</td>
	</tr>
	<tr>
		<td>
			+ Cơ - xương - khớp:	..........................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			+ Tai - Mũi - Họng, Răng - Hàm - Mặt, Dinh dưỡng và các cơ quan khác: .......................................................................... 
		</td>
	</tr>
	<tr>
		<td>
			<b>3. Các xét nghiệm cận lâm sàng cần làm: </b>
			.................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			............................................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			<b>4. Tóm tắt bệnh án: </b>
			<small style="font-size:23px">
			&nbsp;&nbsp;&nbsp;Ngày bệnh: .......... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Suy hô hấp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sốc &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Phù thổi cấp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rối loạn hô hấp </small>
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">		
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mạch nhanh &lt; 170 l/p &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tăng HA <small style="font-size:18px">( Trẻ &lt; 1T: &gt; 100mmHg; Trẻ 1 - 2T: &gt; 110 mmHg; Trẻ &gt; 2T: &gt,115 mmHg)</small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Gồng chi / Hôn mê
		</td>
	</tr><tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vã mồ hôi toàn thân hay khu trú &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thất điều &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Rung giật nhãn cầu &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Yếu chi / Liệt mềm &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Liệt thần kinh sọ
		</td>
	</tr><tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Giật mình lúc khám &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bệnh sử giật mình: &gt;= 2 lần/30 phút, kèm một dấu hiệu: ngủ gà, M &gt; 130 l/p, sốt cao khó hạ
		</td>
	</tr>
	<tr>
		<td>
			Biểu hiện khác: ..................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td style="font-size:28px">
			<b>IV. Chẩn đoán khi vào khoa điều trị:</b>
		</td>
	</tr>
	<tr>
		<td style="font-size:28px">
			+ Bệnh chính <small style="font-size:23px">( Lưu ý phân độ : ) ...............................................................................................Mã ICD 10:......................................</small>
		</td>
	</tr>
	<tr>
		<td style="font-size:28px">
			+ Bệnh Kèm theo <small style="font-size:23px">( Nếu có ) ...............................................................................................Mã ICD 10 (Bệnh kèm):.........................</small>
		</td>
	</tr>
	<tr>
		<td>
			+ Phân biệt: .......................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
		<b> V. Tiên lượng: </b>
		................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td>
			<b>VI. Hướng điều trị:</b>
			<small style="font-size:23px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Oxy / Fiups thở &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Chống sốc &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Điều trị cao HA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; An thần &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &#221;-globulin &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nhập ICU</small>
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;Khác:.................................................................................................................................................................................................... 
		</td>
	</tr>
	<tr>
		<td style="font-size:23px">
			&nbsp;&nbsp;&nbsp;............................................................................................................................................................................................................. 
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');

$pdf->SetFont('dejavusans', '', 10);
$tb1='
<table cellpadding="2">
	<tr>
		<td >
		</td>
		<td  align="center">
			<i>Ngày ........... tháng ........... năm ...............</i>
			<br/><b style="font-size:23px">Bác sỹ làm bệnh án</b>
			<br/><br/><br/><br/><br/><i style="font-size:23px">Họ và tên: ........................................</i>
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
//-----------------------------------------------------------------------------Tr4
$pdf->AddPage();
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->DrawRect(($x),($y+5),195,253,1);
$pdf->SetFont('dejavusans', '', 10);
$html = <<<EOD
	<b>TỔNG KẾT BỆNH ÁN</b>
EOD;
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

$pdf->SetFont('dejavusans', '', 10);
$tb1='
<table cellpadding="4">
	<tr>
		<td >
		<br/>
		<br/>
			&nbsp;&nbsp;1. Quá trình bệnh lý và diễn biến lâm sàng:...............................................................................................
		</td>
	</tr>
	<tr>
		<td >			&nbsp;&nbsp;....................................................................................................................................................................
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;2. Tóm tắt kết quả xét nghiệm cận lâm sàng có giá trị chẩn đoán: ...........................................................		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;3.Phương pháp điều trị: .............................................................................................................................
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;4. Tình trạng người bệnh khi ra viện:
		.........................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;Hướng điều trị và các chế độ tiếp theo: 
		....................................................................................................
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
	<tr>
		<td >&nbsp;&nbsp;....................................................................................................................................................................
		
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
$tb1='
<table cellpadding="4" border="1">
	<tr>
		<td width="40%" colspan="2" align="center"><b> Hồ sơ, phim ảnh </b>
		</td>
		<td width="25%" rowspan="4" align="center"><b> Người giao hồ sơ </b> <br/><br/><br/><br/>
		Họ tên: ..................
		</td>
		<td width="35%" rowspan="8" align="center">Ngày ......... tháng ........ năm ............
		<br/><br/><b> Bác sỹ điều trị </b><br/><br/><br/><br/><br/><br/><br/><br/><br/>
		Họ tên: ..................
		</td>
	</tr>
	<tr>
		<td style="font-size:23px" align="center"><b>Loại</b>
		</td>
		<td style="font-size:23px"align="center">
		<b>Số tờ</b>
		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp; - X - quang
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp; - CT Scaner / RMI
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp; - Siêu âm
		</td>
		<td>
		</td>
		<td width="25%" rowspan="4" align="center"><b> Người nhận hồ sơ </b> <br/><br/><br/><br/>
		Họ tên: ..................
		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp; - Xét nghiệm
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp; - Khác: ..............
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp; - Toàn bộ hồ sơ
		</td>
		<td>
		</td>
	</tr>
</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------
$pdf->Output('demo.pdf', 'I');
//============================================================+
// END OF FILE                                                
//============================================================+