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

# Get ward or department infos
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj = new Department();
$current_dept_LDvar=$dept_obj->LDvar($encounter['current_dept_nr']);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);
require_once($root_path.'include/care_api_classes/class_measurement.php');
$measurement_obj=new Measurement;
require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj = new Ward();
$wardName = $ward_obj->getWardInfo($enc_obj->encounter['current_ward_nr']);
$roomName = $ward_obj->_getActiveRoomInfo($enc_obj->encounter['current_room_nr'],$enc_obj->encounter['current_ward_nr']);
$roomNumber = $enc_obj->encounter['current_room_nr'];
require_once($root_path.'include/care_api_classes/class_khambenh.php');
$kb_obj= new Khambenh();
$kb_ck=$kb_obj->_getKhambenh('encounter_nr='.$enc.'','');
if($kb_ck){
$kb_ck_1=$kb_ck->fetchrow();
}
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
class exec_String {
var $lower = '
a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z
|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ổ|ẫ|ậ
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


//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('dejavusans', '', 10);

// -----------------------------------------------------------------------------

/////////////////////////////////////////////trang1//////////////////////////////
$tbl = '
<table cellspacing="0" cellpadding="1">
    <tr>
		<td width="30%">Sở Y tế:.................................</td>
		<td width="45%"></td>
		<td width="25%">Số lưu trữ:.....................</td>
    </tr>
	<tr>
		<td>Bệnh viện:............................</td>
		<td align="center" rowspan="2"><h1>PHIẾU KHÁM & ĐIỀU TRỊ</h1><br/>
                NGOẠI TRÚ
            </td>
		
		
	</tr>
	<tr>
		<td>Khoa:.................Giường:........</td>
		
		<td></td>
	</tr>
</table><br/>';

// -----------------------------------------------------------------------------

$pdf->writeHTML($tbl, true, false, false, false, '');
$x=$pdf->GetX();
$y=$pdf->GetY(); 
$pdf->Line(87,118, 192, 118);
$pdf->Line(107,137, 172, 137);
$pdf->Line(139.5,113,139.5, 123);
$pdf->Line(139.5,132,139.5, 142);
$pdf->DrawRect(($x+135),($y+6),5,4.5,10); //sinh ngày, tuổi
$pdf->DrawRect(($x+40),($y+12),5,4.5,1); //nam
$pdf->DrawRect(($x+62),($y+12),5,4.5,1); //nữ
$pdf->DrawRect(($x+183),($y+12),5,4.5,2); //nghề nghiệp
$pdf->DrawRect(($x+74),($y+18),5,4.5,2); //dân tộc
$pdf->DrawRect(($x+183),($y+18),5,4.5,2); //ngoại kiều
$pdf->DrawRect(($x+92),($y+29),5,4.5,2); //huyện
$pdf->DrawRect(($x+183),($y+29),5,4.5,2); //tỉnh, tp
$pdf->DrawRect(($x+126),($y+35),5,4.5,1); //bhyt
$pdf->DrawRect(($x+150),($y+35),5,4.5,1); //thu phí
$pdf->DrawRect(($x+169),($y+35),5,4.5,1); //miễn
$pdf->DrawRect(($x+189),($y+35),5,4.5,1); //khác
$pdf->DrawRect(($x+123),($y+41),12.5,4.5,4); //số thẻ bhyt
$pdf->DrawRect(($x+178),($y+41),16,4.5,1); //số thẻ bhyt\
$pdf->DrawRect(($x+70), ($y+79),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+83.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+88),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+92.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+97),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+101.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+106),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+110.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+115),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+130.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+135),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+149),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+153.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+158),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+170),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+174.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+179),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+183.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+188),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+192.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+197),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+201.5),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+206),5, 4.5,1);
$pdf->DrawRect(($x+70), ($y+216),5, 4.5,1);
$pdf->DrawRect(($x+180), ($y+149),5, 4.5,1);
$pdf->DrawRect(($x+180), ($y+153.5),5, 4.5,1);
// -----------------------------------------------------------------------------


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
if(strlen($encounter['date_birth'])>4){
$tb1.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."&nbsp;&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."&nbsp;&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),8,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),9,1)."&nbsp;&nbsp;&nbsp;".$encounter['tuoi'];
}else{
$tb1.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),2,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."&nbsp;&nbsp;&nbsp;&nbsp;".$encounter['tuoi'];
}
}else{
if(strlen($encounter['date_birth'])>4){
$tb1.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),4,1)."&nbsp;&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),6,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),7,1)."&nbsp;&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),8,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),9,1)."&nbsp;&nbsp;&nbsp;".substr($encounter['tuoi'],0,1)."&nbsp;&nbsp;&nbsp;".substr($encounter['tuoi'],1,1);
}else{
$tb1.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr(formatDate2Local($encounter['date_birth'],$date_format),0,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),1,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),2,1)."&nbsp;&nbsp;&nbsp;".substr(formatDate2Local($encounter['date_birth'],$date_format),3,1)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".substr($encounter['tuoi'],0,1)."&nbsp;&nbsp;&nbsp;".substr($encounter['tuoi'],1,1);
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
	
	if($encounter['pinsurance_start']!='0000-00-00'){
	$insexp=formatDate2STD($encounter['pinsurance_exp'],$date_format);
		$tb1.='</tr>
		<tr>
			<td width="50%">10. BHYT giá trị đến ngày.'.date("d",strtotime($insexp)).'.tháng.'.date("m",strtotime($insexp)).'.năm.'.date("Y",strtotime($insexp)).'.</td>
			<td width="50%">Số thẻ BHYT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr($encounter['pinsurance_nr'],0,2).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr($encounter['pinsurance_nr'],3,1).substr($encounter['pinsurance_nr'],5,2).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.substr($encounter['pinsurance_nr'],8,2).substr($encounter['pinsurance_nr'],11,3).'&nbsp;&nbsp;'.substr($encounter['pinsurance_nr'],15,5).'&nbsp;&nbsp;&nbsp;&nbsp;'.str_replace("-","",$encounter['madkbd']).'</td>
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
                <tr>
                    <td colspan="2">12.Đến khám bệnh lúc.......giờ.......phút,ngày......tháng.......năm.......
                    </td>
                </tr>
                <tr>
                    <td colspan="2">13.Chẩn đoán của nơi giới thiệu:..........................................................................................................
                    </td>
                </tr>
	</table>
';
$pdf->writeHTML($tb1, true, false, false, false, '');

$tb='<table>
    <tr>
    <td width="35%">
        <table>
            <tr>
                <td><b>II.TIỀN SỬ BỆNH</b></td>
            </tr>        
            <tr>
                <td>
                    - Chảy máu lâu
                </td>
            </tr>
            <tr>
                <td>
                    - Phản ứng thuốc
                </td>
            </tr>
            <tr>
                <td>
                    - Bệnh dị ứng
                </td>
            </tr>
            <tr>
                <td>
                    - Bệnh cao huyết áp
                </td>
            </tr>
            <tr>
                <td>
                    - Bệnh tim mạch
                </td>
            </tr>
            <tr>
                <td>
                    - Bệnh tiểu đường
                </td>
            </tr>
            <tr>
                <td>
                    - Bệnh dạ dày, tiêu hóa
                </td>
            </tr>
            <tr>
                <td>
                    - Bệnh phổi (lao,hen)
                </td>
            </tr>
            <tr>
                <td>
                    - Bệnh truyền nhiễm <br/>
                    (HIV, Viêm gan siêu vi)
                </td>
            </tr>
            <br/>
            <tr>
                <td>
                    <b>III.PHIM TIA X</b>
                </td>
            </tr>
            <tr>
                <td>
                    - Phim trong miệng
                </td>
            </tr>
             <tr>
                <td>
                    - Phim ngoài mặt
                </td>
            </tr>
            <br/>
            <tr>
                <td>
                    <b>IV. XÉT NGHIỆM BỔ TÚC</b>
                </td>
            </tr>
            <tr>
                <td>
                    - Công thức máu, TS TC
                </td>
            </tr>
            <tr>
                <td>
                    - Tế bào học - GPB
                </td>
            </tr>
            <tr>
                <td>
                    - Cấy vi khuẩn - Vi nấm
                </td>
            </tr>
            <br/>
            <tr>
                <td>
                    <b>V.KẾ HOẠCH ĐIỀU TRỊ</b>
                </td>
            </tr>
            <tr>
                <td>
                    1.NHA CHU
                </td>
            </tr>
            <tr>
                <td>
                    2.CHỮA RĂNG
                </td>
            </tr>
            <tr>
                <td>
                  3.NHỔ RĂNG-TPT
                </td>
            </tr>
            <tr>
                <td>
                   4.CẮN KHỚP
                </td>
            </tr>
            <tr>
                <td>
                  5.PHỤC HÌNH CỐ ĐỊNH
                </td>
            </tr>
            <tr>
                <td>
                   6.PHỤC HÌNH THÁO LẮP
                </td>
            </tr>
            <tr>
                <td>
                   7.CHỈNH HÌNH RĂNG MẶT
                </td>
            </tr>
            <tr>
                <td>
                  8.RĂNG TRẺ EM
                </td>
            </tr>
            <tr>
                <td>
                   9.PHÒNG NGỪA SÂU RĂNG<br/>
                   (Scalant,Fluoride gel)
                </td>
            </tr>
            <tr>
                <td>
                   10.PHẪU THUẬT RĂNG HÀM MẶT
                </td>
            </tr>
        </table>
    </td>
    <td width="65%">
        <table  cellpadding="1" width="100%">
            <br/>
            <tr>
                <td align="center">
                  8&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8
                 
                </td>
            </tr>
            <tr>
                <td align="center">
                  8&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;6&nbsp;&nbsp;&nbsp;&nbsp;7&nbsp;&nbsp;&nbsp;&nbsp;8
                 
                </td>
            </tr>
            <br/><br/>
            <tr>
                <td align="center">
                  V&nbsp;&nbsp;&nbsp;&nbsp;IV&nbsp;&nbsp;&nbsp;&nbsp;III&nbsp;&nbsp;&nbsp;&nbsp;II&nbsp;&nbsp;&nbsp;&nbsp;I&nbsp;&nbsp;&nbsp;&nbsp;I&nbsp;&nbsp;&nbsp;&nbsp;II&nbsp;&nbsp;&nbsp;&nbsp;III&nbsp;&nbsp;&nbsp;&nbsp;IV&nbsp;&nbsp;&nbsp;&nbsp;V
                  
                </td>
            </tr>
             <tr>
                <td align="center">
                  V&nbsp;&nbsp;&nbsp;&nbsp;IV&nbsp;&nbsp;&nbsp;&nbsp;III&nbsp;&nbsp;&nbsp;&nbsp;II&nbsp;&nbsp;&nbsp;&nbsp;I&nbsp;&nbsp;&nbsp;&nbsp;I&nbsp;&nbsp;&nbsp;&nbsp;II&nbsp;&nbsp;&nbsp;&nbsp;III&nbsp;&nbsp;&nbsp;&nbsp;IV&nbsp;&nbsp;&nbsp;&nbsp;V
                  
                </td>
            </tr>
            <br/><br/><br/>
            <tr>
                <td align="center">
                    NHẬN XÉT: <br/>
                    ...........................................<br/>
                    ...........................................<br/>
                    ...........................................<br/>
                    ...........................................
                    
                </td>
            </tr>
            <br/>
            <tr> 
                <td align="right">PHTL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            <tr> 
                <td align="right">PHCĐ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            <br/>
            <br/>
              <tr>
                <td align="center">
                    CHẨN ĐOÁN: <br/>
                    ...........................................<br/>
                    ...........................................<br/>
                    ...........................................<br/>
                    ...........................................
                    
                </td>
            </tr>
        </table>
    </td>
    </tr>
    <tr>
        <td align="right" colspan="2">
           <b> BÁC SỸ KHÁM</b> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<br/>
            (Ký và ghi rõ họ tên) &nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
    </table>';
$pdf->writeHTML($tb, true, false, false, false, '');
// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('Bệnh án ngoại trú RHM.php', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+