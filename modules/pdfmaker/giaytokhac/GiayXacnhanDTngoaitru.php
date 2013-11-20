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
	require_once($root_path.'include/care_api_classes/class_insurance.php');
	$insurance_obj=new Insurance;
	
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
	$pdf->SetTitle('GIAY XAC NHAN BENH NHAN TU VONG');
	$pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
	$pdf->SetMargins(20, 8, 7);    

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 3);
	$pdf->AddPage();
		
	$pdf->SetFont('dejavusans', '', 11);
	$tbl_tittle='<table cellpadding="3">
		    <tr>
				<td width="40%" align="center">SỞ Y TẾ BÌNH DƯƠNG 
				</td>
				<td width="60%" align="center">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM  
				</td>
			</tr>
			<tr>
				<td width="40%" align="center"><b>'.PDF_HOSNAME.'<br/>'.str_pad('',18,'__',STR_PAD_RIGHT).' </b>
				</td>
				<td width="60%" align="center"><b>Độc lập - Tự do - Hạnh phúc </b><br/>'.str_pad('',18,'__',STR_PAD_RIGHT).'
				</td>
			</tr>
			<tr><td><br/></td></tr>	
			<tr>
				<td width="40%" align="center">Số: '.$status['encounter_nr'].' /XN-BVĐK
				</td>
				<td  width="60%" align="center"> <i> '.PDF_HOSNAME.', Ngày '.date('d').' Tháng '.date('m').' Năm '.date('Y').'</i><br/> 
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><br/><b><font size="13"> GIẤY XÁC NHẬN <br/> ĐIỀU TRỊ NGOẠI TRÚ</font></b><br/><br/>
				'.PDF_HOSNAME.' XÁC NHẬN
				</td>
			</tr>
		</table>';
	$pdf->writeHTML($tbl_tittle, true, false, false, false, '');
	$pdf->ln();
	$s_obj->BASIC_String();	
	$tbl='<table cellpadding="3">
		<tr>
			<td width="5%"></td>
			<td  width="95%">
				<table width="100%">
					<tr>
						<td width="85%">Họ tên bệnh nhân: '.$s_obj->upper($status['name_last'].' '.$status['name_first']).'</td>
						<td width="15%">Tuổi: '.$status['tuoi'].'</td>
					</tr>
				</table>
			</td>			
		</tr>
		<tr>
			<td width="5%"></td>
			<td>';
		if($status['sex']=='f'){
			$tbl.='Giới tính: '.$LDFemale;
		}else if($status['sex']=='m'){
			$tbl.='Giới tính: '.$LDMale;
		}else{
			$tbl.=str_pad('Giới tính: ..', 129, '.', STR_PAD_RIGHT);
		}
	$tbl.='</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td>Địa chỉ: '.$status['addr_str_nr'].'  '.$status['addr_str'].'  '.$status['phuongxa_name'].'  '.$status['quanhuyen_name'].'  '.$status['citytown_name'].'</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td>Chẩn đoán: '.$status['referrer_diagnosis'].'</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td>Điều trị ngoại trú tại khoa: '.$deptName.'</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td>'.'Thời gian: từ '.@formatDate2STD(substr($status['encounter_date'], 0,10),$date_format).' đến '.date('d-m-Y').'</td>
		</tr>
	</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');
	$pdf->ln(2);
	$tbl_footer='<table>
					<tr>
						<td width="40%" align="center"><b>BAN GIÁM ĐỐC</b></td>
						<td width="60%" align="center"><b>TRƯỞNG KHOA</b></td>
					</tr>
					<tr>
						<td><br/><br/><br/><br/><br/></td>
					</tr>
					<tr>
						<td width="40%" align="center">Họ và tên:........................</td>
						<td width="60%" align="center">Họ và tên:........................</td>
					</tr>
				</table>';
	$pdf->writeHTML($tbl_footer, true, false, false, false, '');
	$pdf->Output('giayxacnhandtngoaitru.pdf', 'I');
?>