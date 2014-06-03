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

require_once($root_path.'include/care_api_classes/class_notes.php');	
$obj=new Notes();
$pregs=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=33 AND notes.date='$date_s' AND notes.time='$time_s'", "ORDER BY nr ASC");
$rows=$pregs->RecordCount();
if($rows){
    $pregrancy=$pregs->FetchRow();
    $date=$pregrancy['date'];
    $time=$pregrancy['time'];
    $nr_dad=$pregrancy['nr'];
    $pregs1=&$obj->_getNotesKhac("notes.encounter_nr='$enc_nr' AND notes.type_nr=types.nr AND types.sort_nr=33 AND notes.date='$date' AND notes.time='$time'", "ORDER BY notes.type_nr ASC");
    $date_array=array();
    if($pregs1){
        while($row1=$pregs1->FetchRow()){
            $nr=$row1['type_nr'];
            $date_array[$nr]=$row1['notes'];
        }
    }  
}    
$sql="SELECT m.value
		FROM 	care_encounter AS e, 
                        care_person AS p, 
                        care_encounter_measurement AS m
		WHERE p.pid=".$_SESSION['sess_pid']." 
			AND p.pid=e.pid 
			AND e.encounter_nr=m.encounter_nr  
			AND (m.msr_type_nr=1 OR m.msr_type_nr=2 OR m.msr_type_nr=3)
                        AND m.msr_date='".$date_array['90']."'
		ORDER BY m.msr_date ";
	if($result=$db->Execute($sql)){
            $i=1;
            while($msr_row=$result->FetchRow()){
                if($i<4){
                    $sinhhieu[$i]=$msr_row['value'];
                    $i++;
                }else{
                    break;
                }
            }            
        }

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);
$pdf->SetTitle('');
$pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
$pdf->SetMargins(10, 10, 5);    

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 3);
$pdf->AddPage();

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('dejavusans', '', 12);

// Trang 1-----------------------------------------------------------------------------

$tbl='<table>
		<tr>
			<td width="40%" align="center"><b>Sở Y tế tỉnh Bình Dương</b></td>
                        <td width="60%" align="center"><b>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</b></td>
		</tr>
		<tr>
			<td width="40%" align="center"><b>'.PDF_HOSNAME.'</b><br/>'.str_pad('',15,'__',STR_PAD_RIGHT).'</td>
			<td width="60%" align="center"><b>Độc Lập - Tự Do - Hạnh Phúc</b><br/>'.str_pad('',15,'__',STR_PAD_RIGHT).'</td>
		</tr>
                <tr>
                    <td colspan="2"><br/></td>
                </tr>
                <tr>
                    <td width="40%" align="center">Số: '.$status['encounter_nr'].' /BVĐK-BB</td>
                    <td width="60%"  align="right">Tân Uyên, Ngày '.date('d').' tháng '.date('m').' năm '.date('Y').'</td>
                </tr>
	</table>
	<p>
	<b align="center">
            <font size="17">
            BIÊN BẢN
            </font>
            <br/>(Về việc bệnh nhân chết trước khi vào viện)<br/><br/>
	</b>	
	<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Khoa Cấp cứu hồi sức '.PDF_HOSNAME.' có nhận trường hợp bệnh nhân chết trước khi vào viện như sau:</b><br/><br/>
        <table width=100%>
            <tr>
                <td width="3%"></td>
                <td width="75%">- Họ và tên bệnh nhân: '.$status['name_last'].' '.$status['name_first'].' </td>
                <td width="27%">Tuổi: '.$status['tuoi'].'</td>';
//                <td width="22%">';
//                if($status['sex']=='f'){
//                        $tbl.='Nam/Nữ: ...'.$LDFemale.'......';
//                }else if($status['sex']=='m'){
//                        $tbl.='Nam/Nữ: ...'.$LDMale.'...';
//                }else{
//                        $tbl.='Nam/Nữ: Không rõ';
//                }</td>
$time=explode(' ', $status['encounter_date']);
$tbl.='     </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Địa chỉ: '.$status['addr_str_nr'].' '.$status['addr_str'].' '.$status['phuongxa_name'].' '.$status['quanhuyen_name'].' '.$status['citytown_name'].' </td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Được đưa đến bệnh viện vào lúc: '.substr($time['1'],0,2).' giờ '.substr($time['1'],3,2).' 
                ngày '.substr($status['encounter_date'],8,2).' tháng '.substr($status['encounter_date'],5,2).' năm '.substr($status['encounter_date'],0,4).'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Theo giấy giới thiệu: ';
                $hinhthuc=explode("@",$date_array['68']);
                $pp=explode("@",$date_array['75']);
                $benh=explode("@",$date_array['89']);                
                if($hinhthuc['0']=='2'){
                    $tbl.=' Tự đến và '.$hinhthuc['1'];
                }else{
                    $tbl.=$hinhthuc['1'];
                }                   
$tbl.='         </td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="65%">- Phương tiện: ';
                $xe=explode("@",$date_array['69']);
                switch ($xe['0']){
                    case "1":
                        $tbl.=' '.$LDPhuongtienden['1'].' </td><td width="30%"> Số xe: '.$xe['1'];
                        break;
                    case "2":
                        $tbl.=' '.$LDPhuongtienden['2'].' </td><td width="30%"> Số xe: '.$xe['1'];
                        break;
                    default:
                        $tbl.=' '.$LDPhuongtienden['3'].' </td><td width="30%"> Số xe: '.$xe['1'];
                        break;
                }
$tbl.='
                </td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Họ và tên người đưa bệnh nhân: '.$date_array['70'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="65%">- Địa chỉ: '.$date_array['73'].'</td>
                <td width="30%"> Điện thoại: '.$date_array['74'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Bệnh sử theo lời khai của người đưa BN: '.$benh[0].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Đã xử trí (nếu có): '.$pp['0'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Bệnh nhân chết lúc nào: '.substr($date_array['76'],0,2).' giờ '.substr($date_array['76'],3,2).' phút</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Nhận xét trên thi thể bệnh nhân: '.$pp['1'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Chẩn đoán(nếu được): '.$date_array['82'].'</td>
            </tr>     
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Đã xử trí cấp cứu gì: '.$date_array['83'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">- Tài sản bệnh nhân gồm: '.$benh['1'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2"><br/><br/>Cách giải quyết của BVĐK:</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">+ Giữ xác lại vì liên quan đến pháp y: '.$date_array['84'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">+ Thân nhân xin mang xác về không khiếu nại về sau:</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td width="70%">+ Họ tên người xin mang về: '.$date_array['85'].'</td>
                <td width="25%"> Ký nhận: </td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2">+ Địa chỉ: '.$date_array['88'].'</td>
            </tr>
            <tr>
                <td width="3%"></td>
                <td colspan="2"><br/><br/>Biên bản này được lập để lưu hồ sơ tử vong tại BVĐK (Phòng KHTH).</td>
            </tr>
            <tr>
                <td colspan="3"><br/><br/><br/><br/><br/></td>
            </tr>
            <tr>
                <td width="30%" align="center"><font size="13"><b>LÃNH ĐẠO TRỰC</b></font></td>
                <td width="35%" align="center"><font size="13"><b>NGƯỜI ĐƯA BN</b></font></td>
                <td width="35%" align="center"><font size="13"><b>YBS TIẾP NHẬN</b></font></td>
            </tr>
        </table>';
//<tr>
//                <td width="3%"></td>
//                <td width="33%">Tri giác: ...'.$date_array['77'].'...</td>
//                <td width="33%">Da niêm: ...'.$date_array['78'].'...</td>
//                <td width="33%">Đồng tử: ...'.$date_array['79'].'...</td>
//            </tr>
//            <tr>
//                <td width="33%">Mạch: ...'.$sinhhieu['2'].'  L/ph...</td>
//                <td width="33%">Huyết áp: ...'.$sinhhieu['1'].'  mmg/Hg...</td>
//                <td width="33%">Nhiệt độ: ...'.$sinhhieu['3'].'  C...</td>
//            </tr>
//            <tr>
//                <td colspan="3">Tim mạch: ...'.$date_array['80'].' ...</td>
//            </tr>
//            <tr>
//                <td colspan="3">Hô hấp: ... '.str_pad($date_array['81'], 118, ".", STR_PAD_RIGHT).'</td>
//            </tr>
//            <tr>
//                <td colspan="3">Các thương tổn và bệnh lí chính (vị trí, tính chất, mức độ): ... '.str_pad($date_array['82'], 40, ".", STR_PAD_RIGHT).'  
//                </td>
//            </tr>
$pdf->writeHTML($tbl, true, false, false, false, '');
// Trang 2-----------------------------------------------------------------------------
//
//$pdf->AddPage();
//$pdf->SetFont('dejavusans', '', 11);
//$x=$pdf->GetX();
//$y=$pdf->GetY(); 
//$pdf->DrawRect(($x),($y+10),160,40,1);
//$tb1='<table>
//        <tr>
//            <td><b>Điện tâm đồ</b>
//                <br/>
//                <br/>
//                <br/>
//                <br/>
//                <br/>
//                <br/>
//                <br/>
//                <br/>
//                <br/>		
//                <br/>
//                <br/>
//            </td>
//        </tr>
//        <tr>
//            <td><b style="font-size:110%">V. CHẨN ĐOÁN SƠ BỘ (NẾU ĐƯỢC):</b> ...'.str_pad($status['referrer_diagnosis'], 40, ".", STR_PAD_RIGHT).'</td>
//        </tr>
//        <tr>
//            <td><b style="font-size:110%">VI. XỬ TRÍ:</b>
//            </td>
//        </tr>
//        <tr>
//            <td>* Cấp cứu: ...'.$date_array['83'].'....<br/>          
//            </td>
//        </tr>
//        <tr>
//            <td>* Can thiệp khác: ...'.str_pad($date_array['84'], 108, ".", STR_PAD_RIGHT).'...<br/>
//            </td>
//        </tr>
//        <tr>
//            <td><b style="font-size:110%">VII. CÁCH GIẢI QUYẾT:</b>
//            </td>
//        </tr>
//        <tr>
//            <td>  '.$LDGiaiquyet['0'].'.
//            </td>
//        </tr>
//        <tr>
//            <td>  '.$LDGiaiquyet['1'].'.
//            </td>
//        </tr>
//        <tr>
//            <td width="3%"></td>
//            <td width="65%">'.$LDGiaiquyet['2'].': ...'.$date_array['85'].'...  </td>
//                <td width="13%">Tuổi: ...'.$date_array['86'].'...</td>
//                <td width="22%">';
//                if($date_array['87']==1){
//                        $tb1.='Nam/Nữ: ...'.$LDFemale.'......';
//                }else if($date_array['87']==2){
//                        $tb1.='Nam/Nữ: ...'.$LDMale.'...';
//                }else{
//                        $tb1.='Nam/Nữ: Không rõ';
//                }
//$tb1.='     </td>
//        </tr>
//        <tr>
//            <td width="3%"></td>
//            <td colspan="3">'.$LDGiaiquyet['5'].': ...'.str_pad($date_array['88'], 38, ".", STR_PAD_RIGHT).'...Ký tên:............................................</td>
//        </tr>
//        <tr>
//            <td colspan="4"><b>VIII. TÀI SẢN CỦA BỆNH NHÂN: </b>(có, không)';
//            if($benh['1']==1){
//                $tb1.='...'.$LDYes1.'...';
//            }else{
//                $tb1.='...'.$LDNo.'...';
//            }
//$tb1.='     <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
//            (có bản kèm theo)
//            <br/>
//            <br/>
//            <br/>
//            </td>
//        </tr>
//      </table>
//	<table align="center">
//		<tr>
//			<td><b style="font-size:110%">BÁC SĨ TRỰC</b></td>
//			<td><b style="font-size:110%">NGƯỜI ĐƯA BỆNH</b></td>
//			<td><b style="font-size:110%">NV TIẾP NHẬN BỆNH</b></td>
//		</tr>
//		<tr>
//			<td></td>
//			<td></td>
//			<td></td>
//		</tr>
//		<tr>
//			<td></td>
//			<td></td>
//			<td></td>
//		</tr>
//		<tr>
//			<td></td>
//			<td></td>
//			<td></td>
//		</tr>
//		<tr>
//			<td></td>
//			<td></td>
//			<td></td>
//		</tr>
//		<tr>
//			<td></td>
//			<td></td>
//			<td></td>
//		</tr>
//		<tr>
//			<td>Họ và tên:.......................</td>
//			<td>Họ và tên:.......................</td>
//			<td>Họ và tên:.......................</td>
//		</tr>
//	</table>
//';
$pdf->writeHTML($tb1, true, false, false, false, '');
// -----------------------------------------------------------------------------
$tb1 = $tb1.ob_get_contents();
ob_clean();
$pdf->Output('tuvongtruocvaovien.pdf', 'I');
//============================================================+
// END OF FILE                                                
//============================================================+