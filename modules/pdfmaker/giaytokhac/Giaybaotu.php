<?php
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    $classpathFPDF=$root_path.'classes/fpdf/';
    $fontpathFPDF=$classpathFPDF.'font/unifont/';
    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');    
    define('NO_2LEVEL_CHK',1);
    define('LANG_FILE','aufnahme.php');
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
//    # Get ward or department infos
//    require_once($root_path.'include/care_api_classes/class_department.php');
//    $dept_obj = new Department();
//    $current_dept_LDvar=$dept_obj->LDvar($status['current_dept_nr']);
//            if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
//                    else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);
    //Class
    require_once($root_path.'include/care_api_classes/class_notes.php');	
    $obj=new Notes();
    $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=34 AND notes.date='$date_s' AND notes.time='$time_s'", "ORDER BY nr ASC");
    $rows=$pregs->RecordCount();
    if($rows){
        $pregrancy=$pregs->FetchRow();
        $date=$pregrancy['date'];
        $time=$pregrancy['time'];
        $nr_dad=$pregrancy['nr'];
        $pregs1=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=34 AND notes.date='$date' AND notes.time='$time'", "ORDER BY notes.type_nr ASC");
        $data_array=array();
        if($pregs1){
            while($row1=$pregs1->FetchRow()){
                $nr=$row1['type_nr'];
                $data_array[$nr]=$row1['notes'];
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
    $pdf->SetTitle('GIAY BAO TU');
    $pdf->SetAuthor('Vien HL-KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(2, 8, 3); 
    
    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);
    $pdf->AddPage();

    $pdf->SetFont('dejavusans', '', 11);
    $tb_head='<table width="90%">
                <tr>
                    <td width="35%"><b>SỞ Y TẾ BÌNH DƯƠNG</b></td>
                    <td width="50%" align="center">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</td>
                    <td align="center">MS: 04/BV-99</td>
                </tr>
                <tr>
                    <td>BV: '.PDF_HOSNAME.'</td>
                    <td align="center">Độc lập - Tự Do - Hạnh Phúc</td>
                </tr>
                <tr>
                    <td>Số: '.$status['encounter_nr'].' /BV</td>
                    <td align="center" valign="top">'.str_pad('',15,'__',STR_PAD_RIGHT).'</td>
                </tr>
                <tr>
                    <td colspan="3"><br/><br/><br/><br/></td>
                </tr>
                <tr>
                    <td colspan="3" align="center"><font size="18"><b>GIẤY BÁO TỬ</b></font></td>
                </tr>
                <tr>
                    <td colspan="3"><br/></td>
                </tr>
                <tr>
                    <td colspan="3" align="center">Kính gửi: '.$s_obj->upper($data_array['56']).'</td>
                </tr>
              </table>';
    $pdf->writeHTML($tb_head, true, false, false, false, '');
    $pdf->ln(10);
    $time_tv=explode(' ',$data_array['53']);
    $date_tv=@formatDate2Local($time_tv['0'], $date_format);
    $noitv=explode('@',$data_array['57']);

    if($status['thang']>0){
        $namsinh=date("Y",strtotime($status['date_birth']));
    } else {
        $namsinh=    $status['date_birth'];
    }
    $tuoi=date("Y")-$namsinh;

        $tb_body='<table>
                <tr>
                    <td width="7%"></td>
                    <td width="65%">- Họ tên người bệnh: '.$status['name_last'].' '.$status['name_first'].'</td>
                    <td width="15%">Tuổi: '.$tuoi.'</td>
                    <td>Nam/Nữ: ';
                    if($status['sex']=='f'){
                        $tb_body.=$LDFemale;
                    }else{
                        $tb_body.=$LDMale;
                    }
	$time=explode(' ', $status['encounter_date']);
    $tb_body.='                
                    </td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td width="50%">- Dân tộc: '.$status['dantoc'].'</td>
                    <td colspan="2"> Ngoại kiều: '.$status['ngoaikieu'].'</td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td width="50%">- Nghề nghiệp: '.$status['nghenghiep'].'</td>
                    <td colspan="2"> Nơi làm việc: '.$status['noilamviec'].'</td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td colspan="3">- Địa chỉ: '.$status['addr_str_nr'].' '.$status['addr_str'].' '.$status['phuongxa_name'].' '.$status['quanhuyen_name'].' '.$status['citytown_name'].'</td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td colspan="3">- Số vào viện: '.$status['encounter_nr'].'</td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td width="50%">- Số CMND/Hộ chiếu: '.$data_array['50'].'</td>
                    <td colspan="2"> Ngày và nơi cấp: '.@formatDate2Local($data_array['51'], $date_format).' tại '.$data_array['52'].'</td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td width="50%">- Vào viện lúc: '.substr($time['1'],0,2).' giờ '.substr($time['1'],3,2).' phút,</td>
                    <td colspan="2"> Ngày '.substr($status['encounter_date'],8,2).' tháng '.substr($status['encounter_date'],5,2).' năm '.substr($status['encounter_date'],0,4).'</td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td width="50%">- Tử vong lúc: '.substr($time_tv[1],0,2).' giờ '.substr($time_tv[1],3,2).' phút,</td>
                    <td colspan="2"> Ngày '.substr($date_tv,0,2).' tháng '.substr($date_tv,3,2).' năm '.substr($date_tv,6,4).'</td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td width="50%">- Tại khoa: '.$noitv['0'].'</td>
                    <td colspan="2"> Bệnh viện: '.$noitv['1'].'</td>
                </tr>
                <tr>
                    <td colspan="4"><br/></td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td colspan="3">- Nguyên nhân tử vong: '.$data_array['55'].'</td>
                </tr>
              </table>';
    $pdf->writeHTML($tb_body, true, false, false, false, '');    
    $tb_foot='<table width="87%">
                <tr>
                    <td width="70%"></td>
                    <td align="center">
                        Ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'
                    </td>
                </tr>
                <tr>
                    <td width="70%"></td>
                    <td align="center">
                        <b>Giám đốc</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br/><br/><br/><br/><br/><br/></td>
                </tr>
                <tr>
                    <td width="70%"></td>
                    <td align="center">
                        Họ tên:......................................
                    </td>
                </tr>
              </table>';    
    $pdf->writeHTML($tb_foot, true, false, false, false, '');
    ob_clean();
    $pdf->Output('Giaybaotu.pdf', 'I');
?>