<?php
    /**
     * CoT 
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
   // $pregs=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=31 AND notes.date='$date_s' AND notes.time='$time_s'", "ORDER BY nr ASC");
    //$rows=$pregs->RecordCount();
 
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter($enc_nr);
    /*    
    if($enc_obj->loadEncounterData()){
            $status=$enc_obj->getLoadedEncounterData();
    }
    # Get ward or department infos
    require_once($root_path.'include/care_api_classes/class_department.php');
    $dept_obj = new Department();
    $current_dept_LDvar=$dept_obj->LDvar($status['current_dept_nr']);
            if(isset($$current_dept_LDvar)&&!empty($$current_dept_LDvar)) $deptName=$$current_dept_LDvar;
                    else $deptName=$dept_obj->FormalName($encounter['current_dept_nr']);
    */
    $encounter=$enc_obj->loadEncounterData1($enc_nr,1);
    if($enc_obj->loadEncounterData()){
            $encounter=$enc_obj->getLoadedEncounterData();
    }

    # Fetch insurance and encounter classes
    $encounter_class=$enc_obj->getEncounterClassInfo($encounter['encounter_class_nr']);
    $insurance_class=$enc_obj->getInsuranceClassInfo($encounter['insurance_class_nr']);
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
                    &nbsp;&nbsp;Số lưu trữ:..'.str_pad('',17,'.',STR_PAD_RIGHT).'
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
                        Họ và tên người bệnh: '.$s_obj->upper($encounter['name_last'].' '.$encounter['name_first']).'
                    </td>
                    <td>
                        Tuổi: '.$encounter['tuoi'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        if($encounter['sex']=='f'){
                            $tbl1.='Nam/Nữ: '.$LDFemale.'';
                        }else if($encounter['sex']=='m'){
                            $tbl1.='Nam/Nữ: '.$LDMale.'';
                        }else{
                            $tbl1.='Nam/Nữ: Không rõ';
                        }
    $tbl1.=          '</td>
                </tr>
                <tr>
                    <td width="51%">
                        Dân tộc: ';
						if($encounter['dantoc']){
							$tbl1.=$encounter['dantoc'];
						}else{
							$tbl1.='.........................................................';
						}						
    $tbl1.=          '</td>
                    <td width="49%">Nghề nghiệp:  ';
					if($encounter['nghenghiep']){
						$tbl1.=$encounter['nghenghiep'];
					}else{
						$tbl1.='...................................................';
					}
    $tbl1.=         '</td>
                </tr>
                <tr>
                    <td width="57%">
                    ';
    if($encounter['insurance_class_nr']=='1'){
        $insstart=@formatDate2STD($encounter['pinsurance_start'],$date_format);
        $d_s=date("d",strtotime($insstart));
        $m_s=date("m",strtotime($insstart));
        $y_s=date("Y",strtotime($insstart));
        $insexp=@formatDate2STD($encounter['pinsurance_exp'],$date_format);
        $d_e=date("d",strtotime($insexp));
        $m_e=date("m",strtotime($insexp));
        $y_e=date("Y",strtotime($insexp));
    }    
    $tbl1.=str_pad("BHYT: giá trị từ ".$d_s,28,' ',STR_PAD_RIGHT)." / ".str_pad($m_s,3,' ',STR_PAD_RIGHT)." / ".str_pad($y_s,6,' ',STR_PAD_RIGHT)."đến ".str_pad($d_e,3,' ',STR_PAD_RIGHT)." / ".str_pad($m_e,3,' ',STR_PAD_RIGHT)." / ".str_pad($y_e,6,' ',STR_PAD_RIGHT);
    $y=($pdf->GetY()+10);
    $x=($pdf->GetX()+116);
    $pdf->DrawRect($x,$y,10,4.5,4);
    $pdf->DrawRect($x+44,$y,22,4.5,1);
	//.' '.$status['madk_kcbbd'].
    $tbl1.=         '</td>
                     <td>
                        Số:&nbsp;&nbsp;'.substr($encounter['pinsurance_nr'],0,2)."  ".substr($encounter['pinsurance_nr'],3,1).'&nbsp;&nbsp;&nbsp;&nbsp;'.substr($encounter['pinsurance_nr'],5,2)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".substr($encounter['pinsurance_nr'],8,2)."&nbsp;&nbsp;&nbsp;".substr($encounter['pinsurance_nr'],11,3)."&nbsp;&nbsp;&nbsp;".substr($encounter['pinsurance_nr'],15,5).' 
                     </td>
                </tr>
                <tr>
                    <td colspan="2">
                    '.$LDAddress.':&nbsp;&nbsp;';
    if((!empty($encounter['addr_str_nr']))&&(!empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
            $tbl1.=$encounter['addr_str_nr']." ".$encounter['addr_str']." ".$encounter['phuongxa_name'];
    }else if((empty($encounter['addr_str_nr']))&&(empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
            $tbl1.=$encounter['addr_str'];
    }else if((empty($encounter['addr_str_nr']))&&(!empty($encounter['phuongxa_name']))&&(empty($encounter['addr_str']))){
            $tbl1.=$encounter['phuongxa_name'];
    }else if((!empty($encounter['addr_str_nr']))&&(empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
            $tbl1.=$encounter['addr_str_nr']." ".$encounter['addr_str'];
    }else if((empty($encounter['addr_str_nr']))&&(!empty($encounter['phuongxa_name']))&&(!empty($encounter['addr_str']))){
            $tbl1.=$encounter['addr_str']." ".$encounter['phuongxa_name'];
    }
    else{
            $tbl1.='Địa chỉ: ..............................................';
    }
    if((!empty($encounter['quanhuyen_name']))&&(!empty($encounter['citytown_name']))){
            $tbl1.=", Huyện(Q,Tx): ".$encounter['quanhuyen_name'].", Tỉnh/Thành phố: ".$encounter['citytown_name'];
    }else if((!empty($encounter['quanhuyen_name']))&&(empty($encounter['citytown_name']))){
            $tbl1.=", Huyện(Q,Tx): ".$encounter['quanhuyen_name'].", Tỉnh/Thành phố:.............................................. ";
    }else if((empty($encounter['quanhuyen_name']))&&(!empty($encounter['citytown_name']))){
            $tbl1.=", Tỉnh/Thành phố: ".$encounter['citytown_name']."";
    }else{
            $tbl1.='.......................................................................';
    }
    $ngayden=@formatDate2Local(substr($encounter['encounter_date'],0,10),$date_format);    
    $gioden=substr($encounter['encounter_date'],11,8);
    $ngayra=@formatDate2Local(substr($encounter['discharge_date'],0,10),$date_format);   
    $giora=$encounter['discharge_time'];
    $tbl1.=           '</td>
                </tr>
                <tr>
                    <td width="43%">
                        Vào viện lúc: '.str_pad(date("H",strtotime($gioden)),6,' ',STR_PAD_RIGHT).str_pad("giờ ".date("m",strtotime($gioden)),13,' ',STR_PAD_RIGHT).'phút;
                    </td>
                    <td width="57%">
                        ngày '.str_pad(substr($ngayden,0,2),10,' ',STR_PAD_RIGHT).str_pad("tháng ".substr($ngayden,3,2),18,' ',STR_PAD_RIGHT).str_pad("năm ".substr($ngayden,6,4),29,' ',STR_PAD_RIGHT).'
                    </td>
                </tr>
                <tr>
                    <td width="43%">
                        Ra viện lúc: '.str_pad(date("H",strtotime($giora)),6,' ',STR_PAD_RIGHT).str_pad("giờ ".date("m",strtotime($giora)),13,' ',STR_PAD_RIGHT).'phút;
                    </td>
                    <td width="57%">
                        ngày '.str_pad(substr($ngayra,0,2),10,' ',STR_PAD_RIGHT).str_pad("tháng ".substr($ngayra,3,2),18,' ',STR_PAD_RIGHT).str_pad("năm ".substr($ngayra,6,4),29,' ',STR_PAD_RIGHT).'
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Chẩn đoán: ';
                        $info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=25");
						if($info1){
                            $info_encounter=$info1->FetchRow();
							$tbl1.=' '.$info_encounter['notes'];
						}else{
							$tbl1.='..............................................................................................................................';
						}
    $tbl1.=        '</td>
                </tr>
                <tr>
                    <td colspan="2">
                        Lời dặn của thầy thuốc: ';
						//$info1=$enc_obj->_getNotes("encounter_nr=$enc_nr AND type_nr=48");
                       // if($info1){
                        //    $info_encounter=$info1->FetchRow();
                        //    $tbl1.=' '.$info_encounter['notes'];
						//}else{
							$tbl1.='...........................................................................................................';
						//}
    $tbl1.=        '</td>
                </tr>
                             
                <tr>
                    <td width="60%"></td>
                    <td align="center" width="40%">
                    <br><br>
                    Ngày '.str_pad(date('d'),5,' ',STR_PAD_RIGHT).'tháng '.str_pad(date('m'),5,' ',STR_PAD_RIGHT).'năm '.str_pad(date('Y'),10,' ',STR_PAD_RIGHT).'
                        <br/>
                        GIÁM ĐỐC BỆNH VIỆN
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
                    <td align="center" width="40%">
                        
                    </td>
                </tr>
            </table>';
    ob_clean();
    $pdf->writeHTML($tbl1, true, false, false, false, '');
    $pdf->Output('Giay_ra_vien.pdf', 'I');
?>