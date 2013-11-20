<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    $classpathFPDF=$root_path.'classes/fpdf/';
    $fontpathFPDF=$classpathFPDF.'font/unifont/';
    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');
    define('LANG_FILE','aufnahme.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=&new Encounter();
    $kpk=$enc_obj->getStatsByMonthKhamPkhoa($currYear,$currMonth,'AND e.current_dept_nr=7');
    $cpk=$enc_obj->getStatsByMonthKhamPkhoa($currYear,$currMonth,'AND e.current_dept_nr=7','1');
    $sql="SELECT * FROM hdkcphukhoa_naophathai";
    if($sum=$db->Execute($sql)){
        $sum_pn=$sum->FetchRow();
    }
    //$sum_pn=$ebc_obj->count_sum_bcphunu();
    
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8',false);
    $pdf->SetTitle('bao cao trang thiet bi - dung cu y te');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(10, 10, 10);    

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);
    $pdf->AddPage();
    // set font
    $pdf->SetFont('dejavusans', '', 10);
    
    $tbl='<table cellspacing="0" border="0" cellpadding="2" width=100%>
            <tr>
                <td colspan="11">Biểu: 07/BCH</td>
            </tr>
            <tr>
                <td colspan="11" align="center"><font size="12"><b>HOẠT ĐỘNG KHÁM CHỮA PHỤ KHOA VÀ NẠO PHÁ THAI</b></font>
                <br/>
                Báo cáo 3,6,9,12 tháng
                </td>
            </tr>
         </table>
         <table cellspacing="0" border="1" cellpadding="2" width=100%>
            <tr>
                <td rowspan="3" align="center" width="30px">STT</td>
                <td rowspan="3" align="center" width="20%">Tên cơ sở</td>
                <td rowspan="3" align="center" width="10%">Tổng số phụ nữ >=15 tuổi</td>
                <td rowspan="3" align="center" width="10%">Tổng số lượt khám phụ khoa</td>
                <td rowspan="3" align="center" width="10%">Tổng số lượt chữa phụ khoa</td>
                <td colspan="4" align="center" width="30%">Phá thai</td>
                <td colspan="2" align="center" width="18%">Tai biến do nạo phá thai</td>
            </tr>
            <tr>
                <td colspan="3" align="center">Số phá thai theo tuần</td>
                <td rowspan="2" align="center">Trđ: vị thành niên</td>
                <td rowspan="2" align="center">Số mắc</td>
                <td rowspan="2" align="center">Số chết</td>
            </tr>
            <tr>
                <td align="center"> 7 tuần trở xuống</td>
                <td align="center">Trên 7 tuần đến 12 tuần trở xuống</td>
                <td align="center"> Trên 12 tuần</td>
            </tr>
            <tr>
                ';
                for($i=1;$i<12;$i++){
                    $tbl.='<td align="center"><i>'.$i.'</i></td>';
                }
$tbl.='     </tr>
            <tr>';
                for($i=1;$i<12;$i++){
                    switch($i){
                        case '1':
                            $tbl.='<td align="center"><b>A</b></td>';
                            break;
                        case '2':
                            $tbl.='<td><b>Cơ sở y tế công</b></td>';
                            break;
                        default :
                            $tbl.='<td></td>';
                            break;
                    }                    
                }
$tbl.='     </tr>
            <tr>
                <td align="center"><b>I</b></td>
                <td><b>Cơ sở y tế huyện</b></td>
                <td></td>
                <td align="right">'.$kpk.'</td>
                <td align="right">'.$sum_pn['count_phunu'].'</td>
                <td align="right">'.$cpk.'</td>
                <td align="right">??</td>
                <td align="right">??</td>
                <td align="right">??</td>
                <td align="right">??</td>
                <td align="right">??</td>
            </tr>';
            for($i=1;$i<7;$i++){ 
                    $tbl.='<tr>';
                    for($j=1;$j<12;$j++){
                        if($j==3){
                            $tbl.='<td bgcolor="gray"></td>';
                        }else{
                            $tbl.='<td></td>';
                        }                        
                    }
                    $tbl.='</tr>';
                }
$tbl.='     <tr>
                <td align="center"><b>II</b></td>
                <td><b>Trạm y tế</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
                for($i=1;$i<10;$i++){ 
                    $tbl.='<tr>';
                    for($j=1;$j<12;$j++){
                        if($j==1 && $i<9){
                            $tbl.='<td align="center">'.$i.'</td>';
                        }else{
                            $tbl.='<td></td>';
                        }
                    }
                    $tbl.='</tr>';
                }
$tbl.='     <tr>
                <td align="center"><b>B</b></td>
                <td><b>Tư nhân</b></td>
                <td bgcolor="gray"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';
                for($i=1;$i<7;$i++){ 
                    $tbl.='<tr>';
                    for($j=1;$j<12;$j++){
                        if($j==3){
                            $tbl.='<td bgcolor="gray"></td>';
                        }else if($j==1 && $i==5){
                            $tbl.='<td align="center">...</td>';
                        }else{
                            $tbl.='<td></td>';
                        }
                    }
                    $tbl.='</tr>';
                }
$tbl.='
          </table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');
    $pdf->Output('hdkcphukhoa_naophathai.pdf', 'I');
?>