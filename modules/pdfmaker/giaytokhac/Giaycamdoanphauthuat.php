<?php
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');

define('NO_2LEVEL_CHK',1);
$lang_tables[]='emr.php';
$lang_tables[]='departments.php';
define('LANG_FILE','aufnahme.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
//Class
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=new Encounter($enc_nr);
if($enc_obj->loadEncounterData()){
	$status=$enc_obj->getLoadedEncounterData();
}
$encounter_class=$enc_obj->getEncounterClassInfo($status['encounter_class_nr']);
$insurance_class=$enc_obj->getInsuranceClassInfo($status['insurance_class_nr']);

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
$current_dept_LDvar=$dept_obj->LDvar($status['current_dept_nr']);
	if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
		else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);
		
require_once($root_path.'include/care_api_classes/class_notes.php');	
    $obj=new Notes();
    $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=32 AND notes.date='$date_s' AND notes.time='$time_s'", "ORDER BY nr ASC");
    $rows=$pregs->RecordCount();
    if($rows){
        $pregrancy=$pregs->FetchRow();
        $date=$pregrancy['date'];
        $time=$pregrancy['time'];
        $nr_dad=$pregrancy['nr'];
        $pregs1=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=32 AND notes.date='$date' AND notes.time='$time'", "ORDER BY notes.type_nr ASC");
        $date_array=array();
        if($pregs1){
            while($row1=$pregs1->FetchRow()){
                $nr=$row1['type_nr'];
                $date_array[$nr]=$row1['notes'];
            }
        }  
    }
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
	$s_obj=new exec_String();
	
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
	$pdf->SetMargins(15, 8, 15);    

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);
    $pdf->AddPage();
// set font
$pdf->SetFont('dejavusans', '', 10);

// -----------------------------------------------------------------------------

$tbl1='<table cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" width="26%"><b>SỞ Y TẾ BÌNH DƯƠNG
                        <br>'.PDF_HOSNAME.'</b></td>
                <td align="center" width="50%">
                        CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
                        <br/>Độc lập - Tự do - Hạnh phúc
                        <br/>'.str_pad('',26,'__',STR_PAD_RIGHT).'
                        <br/>
                </td>
				<td>MS:05/BV-99<br/>Số vào viện: '.$status['encounter_nr'].'<br/></td>
            </tr>
        </table><br/>';
    $pdf->writeHTML($tbl1, true, false, false, false, '');
$pdf->SetMargins(30, 8, 7);
$pdf->SetFont('dejavusans', 'B', 15);
$pdf->Write(0, ' GIẤY CAM ĐOAN PHẪU THUẬT ', '', 0, 'C', true, 0, false, false, 0);
$pdf->SetFont('dejavusans', 'B', 10);
$x=$pdf->GetX();
$y=$pdf->GetY(); 
$pdf->DrawRect(($x+3),$y+79,5,4.5,1);
$x=$pdf->GetX();
$y=$pdf->GetY(); 
$pdf->DrawRect(($x+3),$y+69,5,4.5,1);
$pdf->Ln(10);
$pdf->SetFont('dejavusans', '', 11);

$s_obj->BASIC_String();	
$tbl = '
<table cellspacing="0" cellpadding="1">
    <tr>
        <td width="70%">- Tên tôi là: ';
		if($date_array[60]){
			$tbl.=$s_obj->upper($date_array[60]);
		}else{
			$tbl.='............................................................';
		}
$tbl.=' </td>
		<td width="15%">
		';		
		if($date_array[61]){
			$tbl.='Tuổi: '.$date_array[61];
		}else{
			$tbl.='Tuổi:...................';
		}
$tbl.=' </td>
		<td>';
		if($date_array[67]=='2'){
			$tbl.='Nam/Nữ: Nữ';
		}else{
			$tbl.='Nam/Nữ: Nam';
		}
$tbl.=' </td>
	</tr>
	<tr>
		<td width="50%">';
		if($date_array[62]){
			$tbl.='- Dân tộc: '.$date_array[62];
		}else{
			$tbl.='- Dân tộc:..............................................................';
		}
$tbl.=' </td>
		<td colspan="2">';
		if($date_array[63]){
			$tbl.='Ngoại kiều: '.$date_array[63];
		}else{
			$tbl.='Ngoại kiều:..........................................';
		}
$tbl.=' </td>
	</tr>
	<tr>
		<td colspan="3">';
		if($date_array[64]){
			$tbl.='- Nghề nghiệp: '.$date_array[64];
		}else{
			$tbl.='- Nghề nghiệp:..................................................................................................................';
		}
$tbl.=' </td>
	</tr>
	<tr>
		<td colspan="3">';
		if($date_array[65]){
			$tbl.='- Địa chỉ: '.$date_array[65];
		}else{
			$tbl.='- Địa chỉ:...........................................................................................................................';
		}
$tbl.=' </td>
	</tr>
	<tr>
		<td colspan="3">
			- Là người bệnh/đại diện gia đình bệnh/họ tên là:  ';
                if($status['name_last']&&$status['name_first']){
                    $tbl.=$s_obj->upper($status['name_last'].' '.$status['name_first']);
                }else{
                    $tbl.='...........................................................';
                }
$tbl.='	</td>
	</tr>
	<tr>
		<td colspan="3">
			hiện đang được điều trị tại Khoa: '.$deptName;
$tbl.=', '.PDF_HOSNAME.'
		</td>
	</tr>
	<tr>
		<td colspan="3"><br><br></td>
	</tr>
	<tr>
		<td colspan="3">
			Sau khi nghe Bác sĩ cho biết tình trạng bệnh của tôi/của gia đình tôi/những nguy hiểm 
			<br>của bệnh nếu không phẫu thuật và những rủi ro có thể xảy ra khi phẫu thuật;tôi tự nguyện viết giấy cam đoan này:
		</td>
	</tr>
	<tr>
		<td colspan="3">';
		if($date_array[66]=="1"){
			$tbl.='&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;Đồng ý xin phẫu thuật và làm giấy này làm bằng.<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Không đồng ý phẫu thuật và để giấy này làm bằng. ';
		}else{			
			$tbl.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Đồng ý xin phẫu thuật và làm giấy này làm bằng.<br><br>&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;Không đồng ý phẫu thuật và để giấy này làm bằng.';
		}
$tbl.='
		</td>
    </tr>
	<tr align="center">
        <td width="50%"></td>
		<td colspan="2"><br><br>Ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'
			<br><b>NGƯỜI ĐẠI DIỆN GIA ĐÌNH</b>
			<br>
			<br>
			<br>
			<br>
		</td>
	</tr>
	<tr>
        <td width="60%">
            <br>
			<br>
			<br>Hướng dẫn:
            <br>Đánh dấu vào ô thích hợp và xóa ô không thích hợp.			
		</td>
        <td colspan="2">Họ tên:';
				if($date_array[60]){
					$tbl.=' '.$s_obj->upper($date_array[60]);
				}else{
					$tbl.=' ..................................';
				}
$tbl.=' </td>
	</tr>
</table>';
$pdf->writeHTML($tbl, true, false, false, false, '');
ob_clean();
$pdf->Output('GiaycamdoanPT.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+