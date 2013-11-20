<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
$lang_tables[]='departments.php';
require($root_path.'include/core/inc_front_chain_lang.php');
define('MAX_ROW_PP',40); //size 8
define('WIDTH_BT',10); //size 8



$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
include($classpathFPDF.'tfpdf.php');
$tpdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8',false);
    $tpdf->SetTitle("Thống kê bệnh viên");
    $tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $tpdf->SetMargins(5, 8, 3);    
    // remove default header/footer
    $tpdf->setPrintHeader(false);
    $tpdf->setPrintFooter(false);

    //set auto page breaks
    $tpdf->SetAutoPageBreak(FALSE);
    $tpdf->AddPage('L','A4');
    $tpdf->SetFont('dejavusans', '', 10);

switch ($id) {
	case 'vaokhoa':
		# code...
	$khoa = "VÀO KHOA";

		break;
	case 'rakhoa':
		# code...
	$khoa = "RA KHOA";
		break;
	case 'vaovien': $khoa="VÀO VIỆN";break;
	case 'ravien':
		# code...
	$khoa = "RA VIỆN";
		break;
		case 'chuyenvien':
		# code...
	$khoa = "CHUYỂN VIỆN";
		break;
	default:
		# code...
		break;
}
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;
$allMeDept = $dept_obj->getAllMedical();

$strdatebc = "BÁO CÁO THỐNG KÊ NGÀY ".date("d/m/Y",strtotime($datefrom))." - ĐẾN NGÀY ".date("d/m/Y",strtotime($dateto));
$header_1='<table  >
                <tr>
                    <td width="30%">
                            SỞ Y TẾ BÌNH DƯƠNG<br>
                            '.PDF_HOSNAME.'
                    </td>
                    <td align="center" width="50%">
                    	<b><font size="15">DANH SÁCH BỆNH NHÂN '.$khoa.'</font></b><br><br>
                        <i>('.$strdatebc.')</i>
                    </td>
                    <td align="right" width="18%"></td>
                </tr>
                
                </table>';
    $tpdf->writeHTML($header_1);
    $tpdf->SetFont('dejavusans', '', 8);
 
 
$i=1;
if($id=='vaokhoa'){
	if($dept!=0){ $wheredept = " and dept_to=$dept ";
 
	}
 else $wheredept = "";
 //chi lay noi tru
 $sql="SELECT t.* FROM dfck_admit_inout_dept t 
 	where t.`dept_to` >0 and t.type_encounter=1 $wheredept 
 	AND DATE_FORMAT(t.`datein`,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
	AND DATE_FORMAT(t.`datein`,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' 
	ORDER BY t.`dept_to`, t.`datein`
 	";
 	$headerall = '<tr>
 	<td align="center" width="3%"><b>STT</b></td>
 	<td align="center"><b>Mã BN</b></td>
 	<td align="center"><b>Họ Tên</b></td>
 	<td align="center" width="5%"><b>Năm Sinh</b></td>
 	<td align="center" width="'.WIDTH_BT.'%"><b>Địa chỉ</b></td>
 	<td align="center"><b>Nghề nghiệp</b></td>
 	<td align="center"><b>Ngày</b></td>
 	<td align="center" width="'.WIDTH_BT.'%"><b>Chẩn đoán</b></td>
 	<td align="center"><b>Số vào viện</b></td>
 	<td align="center"><b>Nhận từ</b></td>
 	<td align="center"><b>Đến từ</b></td>
 	<td align="center"><b>Nơi Chuyển</b></td>
 </tr>';
 }
 else if($id== 'vaovien'){
 	if($dept!=0){ $wheredept = " and dept_to=$dept ";
 
	}
 else $wheredept = "";
 //chi lay noi tru
 $sql="SELECT t.* FROM dfck_admit_inout_dept t 
 	where t.`dept_to` >0 and t.`dept_to`= t.`dept_from` and t.type_encounter=1 $wheredept 
 	AND DATE_FORMAT(t.`datein`,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
	AND DATE_FORMAT(t.`datein`,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' 
	ORDER BY t.`dept_to`, t.`datein`
 	";
 	$headerall = '<tr>
	 	<td align="center" width="3%"><b>STT</b></td>
	 	<td align="center"><b>Mã BN</b></td>
	 	<td align="center"><b>Họ Tên</b></td>
	 	<td align="center" width="5%"><b>Năm Sinh</b></td>
	 	<td align="center" width="'.WIDTH_BT.'%"><b>Địa chỉ</b></td>
	 	<td align="center"><b>Nghề nghiệp</b></td>
	 	<td align="center"><b>Ngày</b></td>
	 	<td align="center" width="'.WIDTH_BT.'%"><b>Chẩn đoán</b></td>
	 	<td align="center"><b>Số vào viện</b></td>
	 	<td align="center"><b>Nhận từ</b></td>
	 	<td align="center"><b>Đến từ</b></td>
 	</tr>';
 }
 else if($id=='rakhoa'){
 	if($dept!=0){ 
 		$wheredept = " and dept_from=$dept ";
 	}
 	else $wheredept = "";
 	$sql="SELECT t.* , 
 	(select en.notes
     from care_encounter_notes en 
     where en.encounter_nr= t.encounter_nr 
     and en.type_nr=26 order by en.nr DESC limit 0,1) tinhtrangra,
	(SELECT t3.datein
	FROM dfck_admit_inout_dept t3 
	WHERE t3.encounter_nr=t.encounter_nr 
	AND t3.`status`=1 and t3.dept_to >0
	ORDER BY t3.nr DESC LIMIT 0,1) tdatein 
 	FROM dfck_admit_inout_dept t 
 	WHERE  
 	DATE_FORMAT(t.`datein`,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
	AND DATE_FORMAT(t.`datein`,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' 
	and t.type_encounter=1 
	$wheredept
	AND t.dept_from != t.dept_to
	ORDER BY t.`dept_from`, t.`datein`
 	";
 	//echo $sql;
 	$headerall = '<tr>
 	<td align="center" width="3%"><b>STT</b></td>
 	<td align="center"><b>Mã BN</b></td>
 	<td align="center"><b>Họ Tên</b></td>
 	<td align="center" width="5%"><b>Năm Sinh</b></td>
 	<td align="center" width="'.WIDTH_BT.'%"><b>Địa chỉ</b></td>
 	<td align="center"><b>Nghề nghiệp</b></td>
 	<td align="center"><b>Ngày vào khoa</b></td>
 	<td align="center"><b>Ngày ra khoa</b></td>
 	<td align="center" width="'.WIDTH_BT.'%"><b>Chẩn đoán</b></td>
 	<td align="center"><b>Số vào viện</b></td>
 	<td align="center"><b>Kết quả</b></td>
 	<td align="center"><b>Tình trạng</b></td> 	
 </tr>';
 }
 else if($id=='ravien'){
 	if($dept!=0){ 
 		$wheredept = " and dept_from=$dept ";
 	}
 	else $wheredept = "";
 	$sql="SELECT t.* , 
 	(select en.notes
     from care_encounter_notes en 
     where en.encounter_nr= t.encounter_nr 
     and en.type_nr=26 order by en.nr DESC limit 0,1) tinhtrangra,
	(SELECT t3.datein
	FROM dfck_admit_inout_dept t3 
	WHERE t3.encounter_nr=t.encounter_nr 
	AND t3.`status`=1 and t3.dept_to >0
	ORDER BY t3.nr ASC LIMIT 0,1) tdatein 
 	FROM dfck_admit_inout_dept t 
 	WHERE  
 	DATE_FORMAT(t.`datein`,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
	AND DATE_FORMAT(t.`datein`,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' 
	and t.type_encounter=1 
	$wheredept
	AND t.dept_to < 0
	ORDER BY t.`dept_from`, t.`datein`
 	";
 	$headerall = '<tr>
	 	<td align="center" width="3%"><b>STT</b></td>
	 	<td align="center"><b>Mã BN</b></td>
	 	<td align="center"><b>Họ Tên</b></td>
	 	<td align="center" width="5%"><b>Năm Sinh</b></td>
	 	<td align="center" width="'.WIDTH_BT.'%"><b>Địa chỉ</b></td>
	 	<td align="center"><b>Nghề nghiệp</b></td>
	 	<td align="center"><b>Ngày vào khoa</b></td>
	 	<td align="center"><b>Ngày xuất khoa</b></td>
	 	<td align="center" width="'.WIDTH_BT.'%"><b>Chẩn đoán</b></td>
	 	<td align="center"><b>Số vào viện</b></td>
	 	<td align="center"><b>Kết quả</b></td>
	 	<td align="center"><b>Tình trạng</b></td> 	
 	</tr>';
 }
 else if($id=='chuyenvien'){
 	$sql="SELECT t.* ,  	
	(SELECT t3.datein
	FROM dfck_admit_inout_dept t3 
	WHERE t3.encounter_nr=t.encounter_nr 
	AND t3.`status`=1 and t3.dept_to >0
	ORDER BY t3.nr DESC LIMIT 0,1) tdatein, 
	hl.sname, hl.lname,hl.order hlorder,
	p.contact_person,
	(select en2.notes from care_encounter_notes en2 where en2.encounter_nr = t.encounter_nr and en2.type_nr = 48) icd10,
	(select en3.notes from care_encounter_notes en3 where en3.encounter_nr = t.encounter_nr and en3.type_nr = 25) cdrv
 	FROM dfck_admit_inout_dept t, care_encounter_notes en, dfck_hospital_list hl, care_person p 
 	WHERE  
 	DATE_FORMAT(t.`datein`,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
	AND DATE_FORMAT(t.`datein`,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."' 
	and en.encounter_nr = t.encounter_nr and hl.sname = en.notes and en.type_nr = 68 
	and t.type_encounter=1 
	and p.pid = t.pid
	AND t.dept_to = '-2'
	ORDER BY hlorder,t.`datein`
 	";
 	//echo $sql;
 	$headerall = '<tr>
	 	<td align="center" width="3%"><b>STT</b></td>
	 	<td align="center"><b>Mã BN</b></td>
	 	<td align="center"><b>Số vào viện</b></td>
	 	<td align="center"><b>Họ Tên</b></td>
	 	<td align="center" width="5%"><b>Năm Sinh</b></td>
	 	<td align="center"><b>Người nhà</b></td>
	 	<td align="center" width="'.WIDTH_BT.'%"><b>Địa chỉ</b></td>	 	
	 	<td align="center" width="'.WIDTH_BT.'%"><b>Chẩn đoán</b></td>
	 	<td align="center"><b>Mã ICD10</b></td>
	 	<td align="center"><b>Ngày vào</b></td>
	 	<td align="center"><b>Ngày ra</b></td>	
 	</tr>';
 }
 //echo $sql;
 $currdept = 0;
 if($id=='chuyenvien') $currdept = '';
if($rs = $db->Execute($sql)){
			if($rs->RecordCount()){		
				$numline = 1;
				$nowpage = 1;	
				if($nowpage==1) $numline = 4;
				$content = "";
				$i=1;
				while($row=$rs->FetchRow()){
					//var_dump($row['lname']);
					if( ($row['dept_to']!=$currdept && ($id=='vaokhoa'||$id=='vaovien')) ||( ($row['dept_from']!=$currdept)&&($id=='rakhoa'||$id=='ravien')) ||  ($row['lname']!= $currdept && $id=='chuyenvien') )
					{ //tieu de cua report vao khoa
						$numline ++;
						if ( $numline >= MAX_ROW_PP ) {  
							$tpdf->writeHTML('<table border="1"  cellpadding="3">'.$headerall.$content.'</table><div align="right">Trang '.$nowpage.'</div>');
					  		$tpdf->AddPage(); // page break.
					  		$content = "";
					  		$numline = $num_thisline;
					  		$nowpage ++;
					  	}		
						if($id=='vaokhoa'){ 
							$currdept = $row['dept_to'];
							$deptLDvar=$row['LDdeptin'];
							$content.= '<tr><td colspan="12">'.$$deptLDvar.'</td></tr>';
						}
						else if($id=='vaovien'){
							$currdept = $row['dept_to'];
							$deptLDvar=$row['LDdeptin'];
							$content.= '<tr><td colspan="11">'.$$deptLDvar.'</td></tr>';
						}
						else if($id=='rakhoa' || $id=='ravien'){ //tieu de cua report ra khoa
							$currdept = $row['dept_from'];
							$deptLDvar= $row['LDdeptout'];
							$content.= '<tr><td colspan="12">'.$$deptLDvar.'</td></tr>';
						}
						else if($id=='chuyenvien'){							
							$currdept = $row['lname'];							
							//$deptLDvar=$row['LDdeptin'];
							$content.= '<tr><td colspan="11">'.$currdept.'</td></tr>';
						}
					}
						$tmp1 = $tpdf->GetStringWidth(trim($row['referrer_diagnosis']));
						$tmp2 = $tpdf->GetStringWidth(trim($row['address']));
						
						//$crrsec = $row['sname'];
						//$sec = $arrsection[$crrsec];					
						//$description = $sec[1];
						$column_width = 270*WIDTH_BT/100;//mm
						if($tmp1>$tmp2)
							$num_thisline = (ceil( $tmp1 / $column_width)) ;	
							else $num_thisline = (ceil( $tmp2 / $column_width));					  
						$numline += $num_thisline;
						if ( $numline >= MAX_ROW_PP ) {  
							$tpdf->writeHTML('<table border="1"  cellpadding="3">'.$headerall.$content.'</table><div align="right">Trang '.$nowpage.'</div>');
					  		$tpdf->AddPage(); // page break.
					  		$content = "";
					  		$numline = $num_thisline;
					  		$nowpage ++;
					  	}		
					//$deptfromld = $dept_obj->LDvar($row['dept_from']);
					if($id=='vaokhoa' || $id=='vaovien'){ //noi dung cua vao khoa
					$content .= '<tr>
							<td align="center" width="3%">'.$i.'</td>
						 	<td align="center">'.$row['pid'].'</td>
						 	<td align="center">'.$row['fname'].'</td>
						 	<td align="center">'.$row['yearbirth'].'</td>
						 	<td align="left">'.trim($row['address']).'</td>
						 	<td align="center">'.trim($row['nghenghiep']).'</td>
						 	<td align="center">'.date("d/m/Y",strtotime($row['datein'])).'</td>
						 	<td align="center">'.trim($row['referrer_diagnosis']).'</td>
						 	<td align="center">'.$row['encounter_nr'].'</td>
						 	<td align="center">'.$$row['LDdeptin'].'</td>
						 	<td align="center">Tự đến</td>
						 	'.(($id=='vaokhoa')?'<td align="center">'.$$row['LDdeptout'].'</td>':'').'
					</tr>';
					}
					else if($id=='rakhoa' || $id=='ravien'){ //noi dung cua ra khoa
						switch ($row['dept_to']) {
							case '-1':
								$tinhtrang = 'Ra viện';
								break;
							case '-2':
								$tinhtrang = 'Chuyển viện'; break;
							case '-3':
								$tinhtrang = 'Trốn viện'; break;
							case '-4':
								$tinhtrang = 'Xin về'; break;
							case '-5':
								$tinhtrang = 'Đưa về'; break;
							case '-6':
								$tinhtrang = 'Tử vong'; break;
							
							default:
								$tinhtrang = 'Chuyển khoa'; break;
								break;
						}	
						switch($row['tinhtrangra'])	{
							case 1: $kqra = 'Khỏi';break;
							case 2: $kqra = 'Đở, giãm';break;
							case 3: $kqra = 'Không thay đổi';break;
							case 4: $kqra = 'Nặng hơn';break;
							default: $kqra = '';break;
						}				
						$content .= '<tr>
							<td align="center" width="3%">'.$i.'</td>
						 	<td align="center">'.$row['pid'].'</td>
						 	<td align="center">'.$row['fname'].'</td>
						 	<td align="center">'.$row['yearbirth'].'</td>
						 	<td align="left">'.trim($row['address']).'</td>
						 	<td align="center">'.trim($row['nghenghiep']).'</td>
						 	<td align="center">'.date("d/m/Y H:i:s",strtotime($row['tdatein'])).'</td>
						 	<td align="center">'.date("d/m/Y H:i:s",strtotime($row['datein'])).'</td>
						 	<td align="center">'.trim($row['referrer_diagnosis']).'</td>
						 	<td align="center">'.$row['encounter_nr'].'</td>
						 	<td align="center">'.$kqra.'</td>
						 	<td align="center">'.$tinhtrang.'</td>						 	
					</tr>';
					}
					else if($id=='chuyenvien'){
						$content .= '<tr>
							<td align="center" width="3%">'.$i.'</td>
						 	<td align="center">'.$row['pid'].'</td>
						 	<td align="center">'.$row['encounter_nr'].'</td>
						 	<td align="center">'.$row['fname'].'</td>
						 	<td align="center">'.$row['yearbirth'].'</td>
						 	<td align="left">'.trim($row['contact_person']).'</td>
						 	<td align="left">'.trim($row['address']).'</td>						 							 	
						 	<td align="center">'.trim($row['cdrv']).'</td>						 	
						 	<td align="center">'.$row['icd10'].'</td>
						 	<td align="center">'.date("d/m/Y H:i:s",strtotime($row['tdatein'])).'</td>
						 	<td align="center">'.date("d/m/Y H:i:s",strtotime($row['datein'])).'</td>					 
					</tr>';
					}
					$i++;
				}
			}
	}
	 $tpdf->writeHTML('<table border="1"  cellpadding="3">'.$headerall.$content.'</table>
 	 	');
	$numline+=4;
	if ( $numline > MAX_ROW_PP ) { 
		$tpdf->AddPage();
	}

 	 $tpdf->writeHTML('<table><tr><td width="60%"></td><td align="center"><br><br><b>Tổng cộng: '.($i-1).'<br>Ngày '.date("d").' Tháng '.date("m").' Năm '.date("Y").'</b></td></tr></table>');
   $tpdf->Output('thongkebenhvien.pdf','I');