<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    $classpathFPDF=$root_path.'classes/fpdf/';
    $fontpathFPDF=$classpathFPDF.'font/unifont/';
    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');
    /**
    * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
    * GNU General Public License
    * Copyright 2002,2003,2004,2005 Elpidio Latorilla
    * elpidio@care2x.org, 
    *
    * See the file "copy_notice.txt" for the licence notice
    */

    $lang_tables[]='departments.php';
    $lang_tables[]='aufnahme.php';
    define('LANG_FILE','nursing.php');
    define('NO_CHAIN',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    # Get the encouter data
    $enc_obj=& new Encounter($pn);
    if($enc_obj->loadEncounterData()){
            $encounter=$enc_obj->getLoadedEncounterData();
            if($encounter['sex']=='m') 
                $sex_patient = $LDMale;			//nam hay nu
            else $sex_patient = $LDFemale;
    }

    require_once($root_path.'modules/news/includes/inc_editor_fx.php');
    include_once($root_path.'include/care_api_classes/class_charts.php');
    $charts_obj= new Charts;
    $info=$charts_obj->getManyDaysInfo($encounter['encounter_nr'],$kmonat,$jahr.'-12-31',"AND (lanmangthai<>'' OR lansinh<>'' OR giooivo<>'')");
    if($info){
        $info_detail=$info->FetchRow();
    }

    //Get info of current department, ward
    $ward_nr=$encounter['current_ward_nr'];
    $dept_nr=$encounter['current_dept_nr'];
    if ($ward_nr!=''){
        require_once($root_path.'include/care_api_classes/class_ward.php');
        $Ward = new Ward;
        if($wardinfo = $Ward->getWardInfo($ward_nr)) {
            $wardname = $wardinfo['name'];
            $deptname = ($$wardinfo['LD_var']);
            $dept_nr = $wardinfo['dept_nr'];
        }
    } elseif ($dept_nr!=''){
        require_once($root_path.'include/care_api_classes/class_department.php');
        $Dept = new Department;
        if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
            $deptname = ($$deptinfo['LD_var']);
            $wardname = '';
        }
    }

    require_once($root_path.'classes/tcpdf/config/lang/eng.php');
    require_once($root_path.'classes/tcpdf/tcpdf.php');

    // create new PDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8',false);

    // set document information
    $pdf->SetAuthor(PDF_HOSNAME);
    $pdf->SetTitle('Bieu Đo Chuyen Da');
    $pdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $pdf->SetMargins(5, 8, 5);

    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 3);

    // add a page: Trang 1
    $pdf->AddPage();
    
    // set font
    $pdf->SetFont('dejavusans', '', 10);
    $header='<table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
			<td><b>SỞ Y TẾ BÌNH DƯƠNG</b><br>
				'.PDF_HOSNAME.'<br>
				KHOA: '.$deptname.'
			</td>
                    </tr>
		</table>';//'.$LDReportSan.'
    $pdf->writeHTML($header);
    $pdf->SetFont('dejavusans', 'B', 16);
    $pdf->writeHTMLCell(0, 0, '', '', 'BIỂU ĐỒ CHUYỂN DẠ', 0, 0, 0, true, 'C', true);
    $pdf->Ln();
    $pdf->SetFont('dejavusans', '', 10);
    $array=array("width"=>"0.25");
    $info_patient='<table cellpadding="0" cellspacing="0" width="100%" border="0">
                        <tr>
                            <td height="18px" colspan="4">'.str_pad("", 113, "_", STR_PAD_RIGHT).'</td>
                        </tr>
                        <tr>
                            <td width="40%">
                                '.$LDFullName.': '.$encounter['name_last'].' '.$encounter['name_first'].'
                            </td>
                            <td width="20%">
                                '.$Lanmangthai.': '.$info_detail['lanmangthai'].'
                            </td>
                            <td width="15%">
                                '.$Lansinh.': '.$info_detail['lansinh'].'
                            </td>
                            <td width="25%">
                                Số nhập viện: '.$encounter['encounter_nr'].'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">'.str_pad("", 113, "_", STR_PAD_RIGHT).'</td>
                        </tr>
                        <tr>
                            <td width="40%">
                                '.$LDElements['5'].': '.formatDate2Local($kmonat, $date_format).'
                            </td>
                            <td width="35%">
                                Thời gian nhập viện: '.substr($encounter['encounter_date'], 11, 2).' giờ '.substr($encounter['encounter_date'], 14, 2).' phút
                            </td>
                            <td colspan="2">
                                '.$Mangoivo.':  '.$info_detail['giooivo'].'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">'.str_pad("", 113, "_", STR_PAD_RIGHT).'</td>
                        </tr>
                 </table>';
    $pdf->writeHTML($info_patient);
    $tbl='<table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr>
                <td width="6%" align="center"><font size="9">'.$LDNhiptim.'</font></td>
                <td bgcolor=white width="4%">
                    <table width="100%" cellpadding=0 cellspacing=0>';
                        $i=180;
                        $j=1;
                        while($i>90){
                            if($i!=100){
                                $tbl.='<tr><td style="color:darkblue; font-size:7;" align="right" valign="top" height="'.(4+$j).'px">'.$i.'</td></tr>';
                            }else{
                                $tbl.='<tr><td style="color:darkblue; font-size:7;" align="right" valign="top" height="'.(4+$j).'px">'.$i.'</td></tr>';
                            }                        
                            $i-=10;
                            $j++;
                        }
  $tbl.='           </table>
                </td>
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'1.png" height=140 width=1100 border=0 />
                </td>
            </tr>
            <tr>
                <td bgcolor=white colspan="2" align="center"><font size="7.5">'.$LDNuocoi.'<br/><br/>'.$LDDochongkhop.'</font>
                </td>
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'2.png" border=0 />
                </td>
            </tr>
            <tr>
                <td width="7%" valign="middle">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'11.png" height="235" width="50" border=0 />
                </td>
                <td width="3%">
                    <table>';
                        $i=10;
                        while($i>-2){
                            if($i==(-1)){
                                $tbl.='<tr valign="top"><td align="right" valign="bottom" height="8px"></td></tr>';
                            }else{
                                $tbl.='<tr><td height="16px" style="color:darkblue; font-size:7;" align="right" valign="top">'.$i.'</td></tr>';
                            }
                            $i--;
                        }
$tbl.='             </table>
                </td>   
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'3.png" height="350" width="1000" border=0 />
                </td>
           </tr>
           <tr>
                <td colspan="2" >
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'12.png" border=0 />
                </td>
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'4.png" border=0 />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><font size="7.5">'.$LDOxy.'<br/>'.$LDGiot.'</font>
                </td>
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'9.png" border=0 />
                </td>
            </tr>
            <tr>
                <td align="right" colspan="2" width="10%">
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'13.png" height="82" width="30" border=0 />
                </td>
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'6.png" height="180" width="1100" border=0 />                    
                </td>
            </tr>
            <tr>
                <td width="7%">
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'.png" height="82" width="40" border=0 />
                </td>
                <td width="3%">
                    <table width="100%" cellpadding=0 cellspacing=0>';
                        $i=180;
                        $j=1;
                        while($i>50){
                            if($i!=100){
                                $tbl.='<tr><td style="color:darkblue; font-size:5.3;" align="right" valign="top" >'.$i.'</td></tr>';
                            }else{
                                $tbl.='<tr><td style="color:darkblue; font-size:5.3;" align="right" valign="top" >'.$i.'</td></tr>';
                            }                        
                            $i-=10;
                            $j++;
                        }
  $tbl.='           </table>
                </td>
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'5.png" height="190" width="1200" border=0 />
                </td>
            </tr>
            <tr>
                <td width="10%" align="right"><font size="7.5">'.$LDThannhiet.'</font></td>
                <td width="90%">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'7.png" height="40" width="1100" border=0 />
                    <br/>
                </td>
            </tr>
            <tr>
                <td width="4%" align="center" rowspan="3"><font size="7.5">'.$LDNuoctieu['0'].'</font></td>
                <td width="6%"><font size="7.5">'.$LDNuoctieu['1'].'</font></td>
                <td width="90%"  rowspan="3">
                    <br/>
                    <img src="'.$root_path.'uploads/photos/datacurve/'.$encounter['encounter_nr'].substr($kmonat,0,4).substr($kmonat,5,2).substr($kmonat,8,2).'10.png" height="70" width="1100" border=0 />
                </td>
            </tr>
            <tr>
                <td width="7%"><font size="7.5">'.$LDNuoctieu['2'].'</font></td>
            </tr>
            <tr>
                <td><font size="7.5">'.$LDNuoctieu['3'].'</font></td>
            </tr>
       </table>';
    $pdf->writeHTML($tbl);
    $pdf->setJPEGQuality(90);
    $x=$pdf->GetX();
    $y=$pdf->GetY();
    // ----------------------------------------------------------------------------
    $pdf->lastPage();
    //Close and output PDF document
    $pdf->Output('Bieudochuyenda.pdf', 'I');
?>
