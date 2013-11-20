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
$kb_ck=$kb_obj->_getKhambenh('encounter_nr='.$enc.'','');
$kb_ck_1=$kb_ck->fetchrow();
$ck_mat=$kb_obj->getKhamChuyenKhoaMat($enc);
$ck_mat_1=$ck_mat->fetchrow();
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
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    //$pdf->SetMargins(15, 10, 7);    
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

/////////////////////////////////////////////trang1//////////////////////////////
$tbl = '
<table cellspacing="0" cellpadding="1">
    <tr>
		<td width="30%">Sở Y tế:.................................</td>
		<td width="45%"></td>
		<td width="25%">Số lưu trữ:.....................</td>
    </tr>
	<tr>
		<td>Bệnh viện:............................</td>
		<td align="center" rowspan="2"><h1>BỆNH ÁN <BR>TAI - MŨI - HỌNG</h1></td>
		<td>Mã YT:....../....../....../.......</td>
		
	</tr>
	<tr>
		<td>Khoa:.................Giường:........</td>
		<td></td>
	</tr>
</table>';

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
$pdf->DrawRect(($x+92),($y+29),5,4.5,2); //huyện
$pdf->DrawRect(($x+183),($y+29),5,4.5,2); //tỉnh, tp
$pdf->DrawRect(($x+126),($y+35),5,4.5,1); //bhyt
$pdf->DrawRect(($x+150),($y+35),5,4.5,1); //thu phí
$pdf->DrawRect(($x+169),($y+35),5,4.5,1); //miễn
$pdf->DrawRect(($x+189),($y+35),5,4.5,1); //khác
$pdf->DrawRect(($x+123),($y+41),12.5,4.5,4); //số thẻ bhyt
$pdf->DrawRect(($x+178),($y+41),16,4.5,1); //số thẻ bhyt\
$pdf->DrawRect(($x+153),($y+68), 5, 4.5, 1); //1.cơ quan y tế
$pdf->DrawRect(($x+173),($y+68), 5, 4.5, 1); //2.tự đến
$pdf->DrawRect(($x+189),($y+68), 5, 4.5, 1); //3.khác
$pdf->DrawRect(($x+47),($y+74), 5, 4.5, 1); //1.cấp cứu
$pdf->DrawRect(($x+64),($y+74), 5, 4.5, 1); //2.KKB
$pdf->DrawRect(($x+94),($y+74), 5, 4.5, 1); //3.Khoa điều trị
$pdf->DrawRect(($x+153),($y+74), 5, 4.5, 1); //vào viện do bệnh này lần thứ
$pdf->DrawRect(($x+24),($y+86), 13, 4.5, 1); //vào khoa
$pdf->DrawRect2(($x+88),($y+86), 5, 4.5, 2); //ngày ĐTr
$pdf->DrawRect(($x+24),($y+96), 13, 4.5, 1); //chuyển
$pdf->DrawRect2(($x+88),($y+96), 5, 4.5, 2); //ngày ĐTr
$pdf->DrawRect(($x+24),($y+102), 13, 4.5, 1); //khoa
$pdf->DrawRect2(($x+88),($y+102), 5, 4.5, 2); //ngày ĐTr
$pdf->DrawRect(($x+24),($y+108), 13, 4.5, 1); //
$pdf->DrawRect2(($x+88),($y+108), 5, 4.5, 2); //ngày ĐTr
$pdf->DrawRect(($x+148.5),($y+80), 5, 4.5, 1); //1.tuyến trên
$pdf->DrawRect(($x+174),($y+80), 5, 4.5, 1); //2.tuyến dưới
$pdf->DrawRect(($x+188),($y+80), 5, 4.5, 1); //3.ck
$pdf->DrawRect(($x+122),($y+102), 5, 4.5, 1); //1.ra viện
$pdf->DrawRect(($x+143),($y+102), 5, 4.5, 1); //2.xin về
$pdf->DrawRect(($x+163),($y+102), 5, 4.5, 1); //3.bỏ về
$pdf->DrawRect(($x+185),($y+102), 5, 4.5, 1); //4.đưa về
$pdf->DrawRect2(($x+178),($y+107), 5, 4.5, 3); //tổng số ngày điều trị
$pdf->DrawRect2(($x+85),($y+127), 5, 4.5, 4); //20.nơi chuyển đến
$pdf->DrawRect2(($x+85),($y+138), 5, 4.5, 4); //21.KKB, cấp cứu
$pdf->DrawRect2(($x+85),($y+149), 5, 4.5, 4); //22.khi vào khoa điều trị
$pdf->DrawRect2(($x+173),($y+133), 5, 4.5, 4); //bệnh chính
$pdf->DrawRect2(($x+173),($y+138), 5, 4.5, 4); //nguyên nhân
$pdf->DrawRect2(($x+173),($y+149), 5, 4.5, 4); //bệnh kèm theo
$pdf->DrawRect2(($x+173),($y+160), 5, 4.5, 4); //chẩn đoán trước phẫu thuật
$pdf->DrawRect2(($x+173),($y+170), 5, 4.5, 4); //chẩn đoán sau phẫu thuật
$pdf->DrawRect(($x+18),($y+155), 5, 4.5, 1); //tai biến
$pdf->DrawRect(($x+48),($y+155), 5, 4.5, 1); //biến chứng
$pdf->DrawRect(($x+26),($y+160), 5, 4.5, 1); //1.do phẫu thuật
$pdf->DrawRect(($x+50.5),($y+160), 5, 4.5, 1); //2.do gây mê
$pdf->DrawRect(($x+83),($y+160), 5, 4.5, 1); //3.do nhiễm khuẩn
$pdf->DrawRect(($x+100),($y+160), 5, 4.5, 1); //4.khác
$pdf->DrawRect2(($x+90),($y+165), 5, 4.5, 3); //23.tổng số ngày điều trị sau phẫu thuật
$pdf->DrawRect2(($x+95),($y+170.5), 5, 4.5, 2); //24.tổng số lần phẫu thuật
$pdf->DrawRect(($x+31),($y+191), 5, 4.5, 1); //1.khỏi
$pdf->DrawRect(($x+31),($y+196), 5, 4.5, 1); //2.đỡ, giảm
$pdf->DrawRect(($x+31),($y+201), 5, 4.5, 1); //3.không thay đổi
$pdf->DrawRect(($x+60),($y+191), 5, 4.5, 1); //4.nặng hơn
$pdf->DrawRect(($x+60),($y+196), 5, 4.5, 1); //5.tử vong
$pdf->DrawRect(($x+18),($y+212), 5, 4.5, 1); //1.lành tính
$pdf->DrawRect(($x+41),($y+212), 5, 4.5, 1); //2.nghi ngờ
$pdf->DrawRect(($x+61),($y+212), 5, 4.5, 1); //3.ác tính
$pdf->DrawRect(($x+105),($y+191), 5, 4.5, 1); //1.do bệnh
$pdf->DrawRect(($x+146),($y+191), 5, 4.5, 1); //2.do tai biến điều trị
$pdf->DrawRect(($x+188),($y+191), 5, 4.5, 1); //3.khác
$pdf->DrawRect(($x+105),($y+196), 5, 4.5, 1); //1.trong 24 giờ vào viện
$pdf->DrawRect(($x+146),($y+196), 5, 4.5, 1); //2.trong 48 giờ vào viện
$pdf->DrawRect(($x+188),($y+196), 5, 4.5, 1); //3.trong 72 giờ vào viện
$pdf->DrawRect2(($x+173),($y+207), 5, 4.5, 4);
$pdf->DrawRect(($x+108),($y+212), 5, 4.5, 1);
$pdf->DrawRect2(($x+173),($y+217), 5, 4.5, 4);
// -----------------------------------------------------------------------------
$namsinh=date("Y",strtotime($encounter['date_birth']));
$tuoi=date("Y")-$namsinh;
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
	if($tuoi<10){
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
						<td>17. Chuyển viện: <i style="font-size:95%">1.Tuyến trên&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Tuyến dưới&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.CK</i></td>
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
// -----------------------------------------------------------------------------

$pdf->SetFont('dejavusans', '', 10);
$tb1='<b style="font-size:100%">III. CHẨN ĐOÁN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MÃ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MÃ</b>';
$pdf->writeHTML($tb1, true, false, false, false, '');
$pdf->SetFont('dejavusans', '', 9);
$tb1='
<table cellpadding="2" border="1">
<tr>
	<td width="55%">
		<table cellpadding="2">
			<tr>
				<td>20. Nơi chuyển đến:......................................................................</td>
			</tr>
			<tr>
				<td>.................................................................................</td>
			</tr>
			<tr>
				<td>21. KKB, Cấp cứu.........................................................................</td>
			</tr>
			<tr>
				<td>.................................................................................</td>
			</tr>
			<tr>
				<td>22. Khi vào khoa điều trị: ..............................................................</td>
			</tr>
			<tr>
				<td>.................................................................................</td>
			</tr>
			<tr>
				<td>-Tai biến&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-Biến chứng</td>
			</tr>
			<tr>
				<td><i style="font-size:93%">1.Do phẫu thuật&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Do gây mê&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Do nhiễm khuẩn&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.Khác</i></td>
			</tr>
			<tr>
				<td>23. Tổng số ngày điều trị sau phẫu thuật</td>
			</tr>
			<tr>
				<td>24. Tổng số lần phẫu thuật</td>
			</tr>
		</table>
	</td>
	<td width="45%">
		<table cellpadding="2">
			<tr>
				<td>25. Ra viện:</td>
			</tr>
			<tr>
				<td>+ Bệnh chính: <i>(tổn thương)</i>........................................</td>
			</tr>
			<tr>
				<td>..............................................................</td>
			</tr>
			<tr>
				<td><i>(Nguyên nhân)</i>......................................</td>
			</tr>
			<tr>
				<td>+ Bệnh kèm theo: .....................................................</td>
			</tr>
			<tr>
				<td>..............................................................</td>
			</tr>
			<tr>
				<td>+ Chẩn đoán trước phẫu thuật: .................................</td>
			</tr>
			<tr>
				<td>..............................................................</td>
			</tr>
			<tr>
				<td>+ Chẩn đoán sau phẫu thuật: ...................................</td>
			</tr>
			<tr>
				<td>..............................................................</td>
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
					<td>26. Kết quả điều trị:  </td>
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
					<td>27. Giải phẫu bệnh <i>(khi có sinh thiết)</i></td>
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
					<td>28. Tình hinh tử vong:.......giờ.......ph&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ngày........tháng........năm..........</td>
				</tr>
				<tr>
					<td style="font-size:95%"><i>1.Do bệnh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Do tai biến điều trị&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Khác</i></td>
				</tr>
				<tr>
					<td style="font-size:93%"><i>1.Trong 24 giờ vào viện&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.Trong 48 giờ vào viện&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.Trong 72 giờ vào viện</i></td>
				</tr>
				<tr>
					<td>29. Nguyên nhân chính tử vong:.......................................................................</td>
				</tr>
				<tr>
					<td>......................................................................................................</td>
				</tr>
				<tr>
					<td>30.Khám nghiệm tử thi:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;31.Chẩn đoán giải phẫu tử thi:</td>
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
		<td align="center" style="font-size:110%"><b>Giám đốc bệnh viện</b></td>
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
		<td align="center">Họ tên..................................................</td>
		<td align="center">Họ tên..................................................</td>
	</tr>
</table>
<br><br>
';
$pdf->writeHTML($tb1, true, false, false, false, '');
/////////////////////////////////////////////trang2//////////////////////////////

$pdf->SetFont('dejavusans', '', 10);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->DrawRect(($x+35),($y+111),5,4.5,1);// di ứng
$pdf->DrawRect(($x+130),($y+111),5,4.5,1);
$pdf->DrawRect(($x+35),($y+119),5,4.5,1);
$pdf->DrawRect(($x+130),($y+119),5,4.5,1);
$pdf->DrawRect(($x+35),($y+127),5,4.5,1);
$pdf->DrawRect(($x+130),($y+127),5,4.5,1);
$pdf->DrawRect(($x+145),($y+168),43,24,1);


$txt='<p><B>A-BỆNH ÁN</B></p>
	<p><B>I. Lý do vào viện:</B>..'.str_pad("..".$encounter['lidovaovien'], 40, ".", STR_PAD_RIGHT).'.Vào ngày thứ................của bệnh</p>
	<p><B>II. Hỏi bệnh:</B></p>
	<B>1. Quá trình bệnh lý:</B> <I>(Khởi phát, diễn biến, chuẩn đoán, điều trị của tuyến dưới vv...)</I><br>
	'.$encounter['quatrinhbenhly'].'.<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................
	
	<p><B>2. Tiền sử bệnh:</B></p>
	+ Bản thân: <I>(phát triển thể lực từ nhỏ đến lớn, những bệnh đã mắc, phương pháp điều trị, tiêm phòng, ăn uống, sinh hoạt vv...)</I>
	.'.$encounter['tiensubenhcanhan'].'..<br>
	.......................................................................................................................................................................
	<p>Đặc điểm liên quan bệnh:</p>
	<table width="850px" border="1" cellpadding="5">
		<tr>
			<td width="35px" align="center">TT</td>
			<td width="90px" align="right">Ký hiệu</td>
			<td>Thời gian(tính theo tháng)</td>
			<td width="35px" align="center">TT</td>
			<td width="90px" align="right">Ký hiệu</td>
			<td>Thời gian(tính theo tháng)</td>
		</tr>
		<tr>
			<td align="center">01</td>
			<td>- Dị ứng</td>
			<td>(di nguyên)</td>
			<td align="center">04</td>
			<td>-Thuốc lá</td>
			<td></td>
		</tr>
		<tr>
			<td align="center">02</td>
			<td>- Ma túy</td>
			<td></td>
			<td align="center">05</td>
			<td>- Thuốc lào</td>
			<td></td>
		</tr>
		<tr>
			<td align="center">03</td>
			<td>- Rượu bia</td>
			<td></td>
			<td align="center">06</td>
			<td>- Khác</td>
			<td></td>
		</tr>
	</table><p></p>
	+ Gia đình: <I>(Những người trong gia đình: bệnh đã mắc, đời sống, tinh thần, vật chất vv...)</I><br>
	'.$encounter['tiensubenhgiadinh'].'.<br>
	.......................................................................................................................................................................
	<p><B>III. Khám bệnh:</B></p>
	<B>1. Toàn thân:</B> <I>(Ý thức, da niệm mạc. hệ thống hạch, tuyến giáp, vị trí, kích thước, số lượng, di động vv...)</I>
	............................................................................................................................... &nbsp;&nbsp;&nbsp;Mạch:.'.$measurement_obj->getMach($encounter['encounter_nr']).'.lần/phút
	............................................................................................................................... &nbsp;&nbsp;&nbsp;Nhiệt độ:'.$measurement_obj->getTemper($encounter['encounter_nr']).'.&deg;C
	............................................................................................................................... &nbsp;&nbsp;&nbsp;Huyết áp:'.$measurement_obj->getBloodPressure($encounter['encounter_nr']).'.mm/Hg
	............................................................................................................................... &nbsp;&nbsp;&nbsp;nhịp thở:'.$measurement_obj->getNhiptho($encounter['encounter_nr']).'lần/phút
	............................................................................................................................... &nbsp;&nbsp;&nbsp;Cân nặng:.'.$measurement_obj->getWeight($encounter['encounter_nr']).'.kg
	...............................................................................................................................<br>
	<B>2. Bệnh chuyên khoa:</B>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	<font align="center"><B>Hình vẽ mô tả tổn thương khi vào viện</B></font><br><br><br><br><br><br><br><br><br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	<br><br><br><br><br><br><br><br><br>
	<table cellpadding="10">
		<tr>
			<td>Thanh quản</td>
			<td>Họng</td>
			<td>Cổ nghiêng phải</td>
			<td>Cổ nghiêng trái</td>
		</tr>
	</table>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	<B>3. Các cơ quan:</B><br>
	- Tâm thần, thần kinh:....................................................................................................................................<br>
	.'.str_pad("..".$kb_ck_1['thankinh_notes'], 40, ".", STR_PAD_RIGHT).'..<br>
	- Tuần hoàn:...................................................................................................................................................<br>
	.'.str_pad("..".$kb_ck_1['tuanhoan_notes'], 40, ".", STR_PAD_RIGHT).'..<br>
	- Hô hấp:.........................................................................................................................................................<br>
	.'.str_pad("..".$kb_ck_1['hohap_notes'], 40, ".", STR_PAD_RIGHT).'..<br>
	- Tiêu hóa:......................................................................................................................................................<br>
	..'.str_pad("..".$kb_ck_1['tieuhoa_notes'], 40, ".", STR_PAD_RIGHT).'..<br>
	- Da và mô dưới da:........................................................................................................................................<br>
	.'.str_pad("..".$kb_ck_1['da_notes'], 40, ".", STR_PAD_RIGHT).'...<br>
	- Cơ xương khớp:............................................................................................................................................<br>
	.'.str_pad("..".$kb_ck_1['coxuongkhop_notes'], 40, ".", STR_PAD_RIGHT).'..<br>
	.......................................................................................................................................................................<br>
	- Tiết niệu - Sinh dục:.....................................................................................................................................<br>
	..'.str_pad("..".$kb_ck_1['thantietnieusinhduc_notes'], 40, ".", STR_PAD_RIGHT).'...<br>
	- Khác:............................................................................................................................................................<br>
	..'.str_pad("..".$kb_ck_1['khac_notes'], 40, ".", STR_PAD_RIGHT).'..<br>
	<B>4. Các xét nghiệm cận lâm sàng cần làm:</B>..............................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	<B>5. Tóm tắt bệnh án:</B>....................................................................................................................................<br>
	<td>'.nl2br($encounter['tomtat_benhan']).'</td>	<br>
	<br>
	<B>IV. Chuẩn đoán khi vào khoa điều trị:</B><br>
	+ Bệnh chính:.................................................................................................................................................<br>
	+ Bệnh kèm theo (nếu có):...........................................................................................................................<br>
	+ Phân biệt:..................................................................................................................................................<br>
	<br>
	<B>V.Tiên lượng:</B>..............................................................................................................................................<br>
	<td>'.nl2br($encounter['tienluong']).'</td>	.<br>
	<br>
	<B>VI. Hướng điều trị:</B>.....................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	.......................................................................................................................................................................<br>
	<br>
	<table border="0">
		<tr>
			<td></td>
			<td align="center">
				<I>Ngày.........tháng........năm...........</I><br>
				<B>Bác sĩ làm bệnh án</B><br>
				<br><br><br><br><br>
				<I>Họ và tên:.........................................</I>
			</td>
		</tr>
		
	</table>
	<br><br><br>
	<B>B. TỔNG KẾT BỆNH ÁN:</B><br>
	<table border="1" cellpadding="5">
		<tr>
			<td colspan="4">
				<B>1. Quá trình bệnh lý và diển biến lâm sàng:</B><br>
				'.nl2br($enc_obj->getValueNotes($enc,9)).'<br>
				<br>
				<B>2. Tóm tắt kết quả xét nghiệm cận lâm sàn có giá trị chẩn đoán:</B>.................................................<br>
				'.nl2br($enc_obj->getValueNotes($enc,10)).'<br>
				<br>
				<B>3. Phương pháp điều trị:</B>........................................................................................................................<br>
				'.nl2br($enc_obj->getValueNotes($enc,37)).'<br>
				<br>
				<table>	
					<tr>
						<td>- Phẫu thuật: </td>
						<td>-Thủ thuật:</td>
					</tr>
				</table>
				<br>
				<table width="750px" border="1" cellpadding="5">
					<tr>
						<td width="70px" align="center">Giờ, ngày</td>
						<td align="center">Phương pháp phẫu thuật/ vô cảm</td>
						<td width="130px" align="center">Bác sĩ phẫu thuật</td>
						<td width="130px" align="center">Bác sĩ gây mê</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
				<br>
				<B>4. Tình trạng người bệnh ra viện:</B>........................................................................................................<br>
				'.nl2br($enc_obj->getValueNotes($enc,39)).'<br>
				<br>
				<B>5. Hướng điều trị và các chế độ tiếp theo:</B>.........................................................................................<br>
				'.nl2br($enc_obj->getValueNotes($enc,40)).'<br>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><B>Hồ sơ, phim, ảnh</B></td>
			<td rowspan="3" align="center">Người giao hồ sơ:<br><br><br><br><br>Họ tên:.......................</td>
			<td rowspan="4" align="center"><I>Ngày....tháng.....năm.....</I><br><B>Bác sĩ điều trị</B><br><br><br><br><br><br><br><br><br><br><br>Họ tên:..........................</td>
		</tr>
		<tr>
			<td align="center">Loại</td>
			<td  align="center">Số tờ</td>
		</tr>
		<tr>
			<td  rowspan="2" >
				<table cellpadding="3" border="0">
					<tr>
						<td>- X-quang</td>
					</tr>
					<tr>
						<td>- CT Scanner</td>
					</tr>
					<tr>
						<td>- Siêu âm</td>
					</tr>
					<tr>
						<td>-Xét nghiệm</td>
					</tr>
					<tr>
						<td>- Khác..............</td>
					</tr>
					<tr>
						<td>- Toàn bộ hồ sơ </td>
					</tr>
				</table>
			</td>
			<td rowspan="2"></td>
		</tr>
		
		<tr>
			<td align="center"><B>Người nhận hồ sơ:</B><br><br><br><br><br>Họ tên:..........................</td>
		</tr>
	</table>
';

$pdf->writeHTML($txt);
$x=$pdf->GetX();
$y=$pdf->GetY();
$pdf->DrawRect(($x+118),($y-139),5,4.5,1);
$pdf->DrawRect(($x+30),($y-139),5,4.5,1);
// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('Bệnh án ngoại khoa.php', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+