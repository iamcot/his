<?php
    /**
     * Vo Kim Huynh 
     * Date:13/08/2012
     * care_notes: nr={29,30,31,32}
     */
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
    //Class
    require_once($root_path.'include/care_api_classes/class_notes.php');	
    $obj=new Notes();
    $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=31 AND notes.date='$date_s' AND notes.time='$time_s'", "ORDER BY nr ASC");
    $rows=$pregs->RecordCount();
    if($rows){
        $pregrancy=$pregs->FetchRow();
        $date=$pregrancy['date'];
        $time=$pregrancy['time'];
        $nr_dad=$pregrancy['nr'];
        $pregs1=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=31 AND notes.date='$date' AND notes.time='$time'", "ORDER BY notes.type_nr ASC");
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
	
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(15, 8, 10);    

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);
    $pdf->AddPage();

    $pdf->SetFont('dejavusans', '', 10);
    // -----------------------------------------------------------------------------
    $tbl='<table cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" width="25%">
                        SỞ Y TẾ BÌNH DƯƠNG
                        <br/>'.PDF_HOSNAME.'
                </td>
                <td align="center" width="45%">
                        CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
                        <br/>Độc lập - Tự do - Hạnh phúc                        
                        '.str_pad('',26,'__',STR_PAD_RIGHT).'
                        <br/>
                </td>
                <td align="left" width="30%">
                    MS: 05/BV-99
                    <br/>
                    &nbsp;&nbsp;Số lưu trữ: '.$status['encounter_nr'].'
                </td>
            </tr>
        </table><br/>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
    $pdf->SetFont('dejavusans', 'B', 17);
    $pdf->Write(0, ' GIẤY RA VIỆN ', '', 0, 'C', true, 0, false, false, 0);
    $pdf->SetFont('dejavusans', '', 11);
    $pdf->Ln(7);
	$s_obj->BASIC_String();
    $tbl1='<table cellpadding="0" cellspacing="0">
                <tr>
                    <td width="66%">
                        - Họ và tên người bệnh: '.$s_obj->upper($status['name_last'].' '.$status['name_first']).'
                    </td>
                    <td>
                        Tuổi: '.$status['tuoi'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        if($status['sex']=='f'){
                            $tbl1.='Nam/Nữ: '.$LDFemale;
                        }else if($status['sex']=='m'){
                            $tbl1.='Nam/Nữ: '.$LDMale;
                        }else{
                            $tbl1.='Nam/Nữ: Không rõ';
                        }
    $tbl1.=          '</td>
                </tr>
                <tr>
                    <td width="51%">
                        - Dân tộc: ';
						if($status['dantoc']){
							$tbl1.=$status['dantoc'];
						}else{
							$tbl1.='.........................................................';
						}						
    $tbl1.=          '</td>
                    <td width="49%">Nghề nghiệp:  ';
					if($status['nghenghiep']){
						$tbl1.=$status['nghenghiep'];
					}else{
						$tbl1.='...................................................';
					}
    $tbl1.=         '</td>
                </tr>
                <tr>
                    <td width="57%">
                    ';
    if($status['insurance_class_nr']=='1'){
        $insstart=@formatDate2STD($status['insurance_start'],$date_format);
        $d_s=date("d",strtotime($insstart));
        $m_s=date("m",strtotime($insstart));
        $y_s=date("Y",strtotime($insstart));
        $insexp=@formatDate2STD($status['pinsurance_exp'],$date_format);
        $d_e=date("d",strtotime($insexp));
        $m_e=date("m",strtotime($insexp));
        $y_e=date("Y",strtotime($insexp));
    }    
    $tbl1.="- BHYT: giá trị từ $d_s/$m_s/$y_s đến $d_e/$m_e/$y_e";
    $y=($pdf->GetY()+10);
    $x=($pdf->GetX()+116);
    $pdf->DrawRect($x,$y,10,4.5,4);
    $pdf->DrawRect($x+44,$y,22,4.5,1);
	//.' '.$status['madk_kcbbd'].
    $tbl1.=         '</td>
                     <td>
                        Số:&nbsp;&nbsp;'.substr($status['insurance_nr'],0,2)."  ".substr($status['insurance_nr'],3,1).'&nbsp;&nbsp;&nbsp;&nbsp;'.substr($status['insurance_nr'],5,2)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".substr($status['insurance_nr'],8,2)."&nbsp;&nbsp;&nbsp;".substr($status['insurance_nr'],11,3)."&nbsp;&nbsp;&nbsp;".substr($status['insurance_nr'],15,5).' 
                     </td>
                </tr>
                <tr>
                    <td colspan="2">
                    - '.$LDAddress.':&nbsp;&nbsp;';
    if((!empty($status['addr_str_nr']))&&(!empty($status['phuongxa_name']))&&(!empty($status['addr_str']))){
            $tbl1.=$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name'];
    }else if((empty($status['addr_str_nr']))&&(empty($status['phuongxa_name']))&&(!empty($status['addr_str']))){
            $tbl1.=$encounter['addr_str'];
    }else if((empty($status['addr_str_nr']))&&(!empty($status['phuongxa_name']))&&(empty($status['addr_str']))){
            $tbl1.=$status['phuongxa_name'];
    }else if((!empty($status['addr_str_nr']))&&(empty($status['phuongxa_name']))&&(!empty($status['addr_str']))){
            $tbl1.=$status['addr_str_nr']." ".$status['addr_str'];
    }else if((empty($status['addr_str_nr']))&&(!empty($status['phuongxa_name']))&&(!empty($status['addr_str']))){
            $tbl1.=$status['addr_str']." ".$status['phuongxa_name'];
    }
    else{
            $tbl1.='- Địa chỉ: ..............................................';
    }
    if((!empty($status['quanhuyen_name']))&&(!empty($status['citytown_name']))){
            $tbl1.=" ,huyện(Q,Tx): ".$status['quanhuyen_name']." ,tỉnh/thành phố: ".$status['citytown_name'];
    }else if((!empty($status['quanhuyen_name']))&&(empty($status['citytown_name']))){
            $tbl1.=" ,huyện(Q,Tx): ".$status['quanhuyen_name'].",tỉnh/thành phố:.............................................. ";
    }else if((empty($status['quanhuyen_name']))&&(!empty($status['citytown_name']))){
            $tbl1.=" ,tỉnh/thành phố: ".$status['citytown_name']."....................................";
    }else{
            $tbl1.='.......................................................................';
    }
    $ngayden=@formatDate2Local(substr($status['encounter_date'],0,10),$date_format);    
    $gioden=substr($status['encounter_date'],11,8);
    $ngayra=@formatDate2Local(substr($date_array[33],0,10),$date_format);	
    $giora=substr($date_array[33],11,8);
    $tbl1.=           '</td>
                </tr>
                <tr>
                    <td width="43%">
                        - Vào viện lúc: '.substr($gioden,0,2)." giờ ".substr($gioden,3,2).' phút;
                    </td>
                    <td width="57%">
                        ngày: '.substr($ngayden,0,2)." tháng ".substr($ngayden,3,2)." năm ".substr($ngayden,6,4).'
                    </td>
                </tr>
                <tr>
                    <td width="43%">
                        - Ra viện lúc: '.substr($giora,0,2)." giờ ".substr($giora,3,2).' phút;
                    </td>
                    <td width="57%">
                        ngày: '.substr($ngayra,0,2)." tháng ".substr($ngayra,3,2)." năm ".substr($ngayra,6,4).'
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        - Chẩn đoán: ';
						if($date_array[34]){
							$tbl1.=' '.$date_array[34].' ';
						}else{
							$tbl1.='..............................................................................................................................';
						}
    $tbl1.=        '</td>
                </tr>
                <tr>
                    <td colspan="2">
                        - Lời dặn của thầy thuốc: ';
						if($date_array[35]){
							$tbl1.=' '.$date_array[35].' ';
						}else{
							$tbl1.='...........................................................................................................';
						}
    $tbl1.=        '</td>
                </tr>
                <tr>
                    <td align="right" colspan="2">
                        Ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'
                    </td>
                </tr>                
                <tr>
                    <td width="60%"></td>
                    <td align="right" width="40%">
                        <br/>
                        GIÁM ĐỐC BỆNH VIỆN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td width="60%"></td>
                    <td align="right" width="40%">
                        Họ tên:'.str_pad('',30,'.',STR_PAD_RIGHT).'
                    </td>
                </tr>
            </table>';
    $pdf->writeHTML($tbl1, true, false, false, false, '');
    $pdf->Output('Giaycamdoan.pdf', 'I');
?>