<?php
    /**
     * Vo Kim Huynh 
     * Date:13/08/2012
     * care_notes: nr={29,30,31,32}
     */
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    
    define('NO_2LEVEL_CHK',1);  
    $lang_tables[]='emr.php';
    $lang_tables[]='departments.php';
    define('LANG_FILE','aufnahme.php');
    //Class
    require_once($root_path.'include/care_api_classes/class_notes.php');	
    $obj=new Notes();
    $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=29 AND notes.date='$date_s' AND notes.time='$time_s'", "ORDER BY nr ASC");
    $rows=$pregs->RecordCount();
    if($rows){
        $pregrancy=$pregs->FetchRow();
        $date=$pregrancy['date'];
        $time=$pregrancy['time'];
        $nr_dad=$pregrancy['nr'];
        $pregs1=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=29 AND notes.date='$date' AND notes.time='$time'", "ORDER BY notes.type_nr ASC");
        $date_array=array();
        if($pregs1){
            while($row1=$pregs1->FetchRow()){
                $nr=$row1['type_nr'];
                $date_array[$nr]=$row1['notes'];
            }
        }  
    }
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter($enc_nr);
    if($enc_obj->loadEncounterData()){
            $status=$enc_obj->getLoadedEncounterData();
    }
    # Get ward or department infos
    require_once($root_path.'include/care_api_classes/class_department.php');
    $dept_obj = new Department();
    $current_dept_LDvar=$dept_obj->LDvar($status['current_dept_nr']);
            if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
                    else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);
    
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
	
	$classpathFPDF=$root_path.'classes/fpdf/';
    $fontpathFPDF=$classpathFPDF.'font/unifont/';
    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');
	
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

    $pdf->SetFont('dejavusans', '', 11);

    // -----------------------------------------------------------------------------
    $tbl='<table cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" width="30%">
                        SỞ Y TẾ BÌNH DƯƠNG
                        <br/>'.PDF_HOSNAME.'
                </td>
                <td align="center" width="70%">
                        CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
                        <br/>Độc lập - Tự do - Hạnh phúc
                        <br/>
                        <br/>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right" width="75%">
                    <br/>
                    '.PDF_HOSNAME.', ngày '.date('d').'
                    tháng '.date('m').'
                    năm '.date('Y').'
                </td>
            </tr>
        </table><br/>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
    $pdf->SetFont('dejavusans', 'B', 17);
    $pdf->Write(0, ' GIẤY CAM ĐOAN ', '', 0, 'C', true, 0, false, false, 0);
    $pdf->SetFont('dejavusans', '', 11);
    $pdf->Ln(7);
	$s_obj->BASIC_String();	
    $tbl1='<table cellpadding="0" cellspacing="0">
                <tr>
                    <td width="88%">
                        - Tôi tên: '.$s_obj->upper($date_array[29]).'
                    </td>
                    <td>
                        Tuổi: '.$date_array[30].'
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        - Là (Cha, mẹ, chồng, thân nhân): '.$date_array[31].'
                    </td>
                </tr>
                <tr>
                    <td width="69%">
                        - Của bệnh nhân: '.$s_obj->upper($status['name_last'].' '.$status['name_first']).'
                    </td>
                    <td>
                        Số bệnh án: '.$status['encounter_nr'].'
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        - Vào viện: ngày '.date('d').'
                        tháng '.date('m').'
                        năm '.date('Y').'
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        - Tại khoa: '.$deptName.'
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        - Chẩn đoán: '.$status['referrer_diagnosis'].'
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Khi được biết tình trạng của người bệnh và một số qui định của Bệnh viện, tôi xin
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        cam đoan như sau : '.$date_array[32].'
                    </td>
                </tr>  
                <tr>
                    <td colspan="2">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nếu có gì xảy ra tôi hoàn toàn chịu trách nhiệm.
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br/>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td align="center">
                        NGƯỜI CAM ĐOAN
                    </td>
                </tr>
                <tr>
                    <td width="45%" align="center">
                    </td>
                    <td width="5%"></td>
                    <td align="center">
                        (Phải trên 18 tuổi)<br/><br/><br/><br/><br/>
                    </td>
                </tr>
				<tr>
					<td width="45%" align="center">
                    </td>
                    <td width="5%"></td>
					<td align="center">
					'.$s_obj->upper($date_array[29]).'
					</td>
				</tr>
            </table>';
    $pdf->writeHTML($tbl1, true, false, false, false, '');
    $pdf->Output('Giaycamdoan.pdf', 'I');
?>