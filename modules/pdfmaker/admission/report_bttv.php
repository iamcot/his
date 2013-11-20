<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');
define('MAX_ROW_PP',44); //size 8
define('WIDTH_BT',30); //size 8
//$daydiff = date_diff(new DateTime(date("Y-m-d",strtotime($dateto))),new DateTime(date("Y-m-d",strtotime($datefrom))));
class DateDiff{
		var $d,$m,$y;
		function dateDifference($startDate, $endDate)
        {
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
            //var_dump($startDate.'@@'.$endDate);
            if ($startDate === false || $startDate < 0 || $endDate === false || $endDate < 0 || $startDate > $endDate)
                return false;
               
            $years = date('Y', $endDate) - date('Y', $startDate);
          
            $endMonth = date('m', $endDate);
            $startMonth = date('m', $startDate);
           
            // Calculate months
            $months = $endMonth - $startMonth;
            if ($months <= 0 && $years>0)  {
                $months += 12;
                $years--;
            }
            if ($years < 0)
                return false;
           
            // Calculate the days
                        $offsets = array();
                        if ($years > 0)
                            $offsets[] = $years . (($years == 1) ? ' year' : ' years');
                        if ($months > 0)
                            $offsets[] = $months . (($months == 1) ? ' month' : ' months');
                        $offsets = count($offsets) > 0 ? '+' . implode(' ', $offsets) : 'now';

                        $days = $endDate - strtotime($offsets, $startDate);
                        $days = date('z', $days);   
             
           	$this->d = $days;
           	$this->m = $months;
           	$this->y = $years;
        } 
}

$daydiff = new DateDiff();
$daydiff->dateDifference($datefrom,$dateto);
//var_dump($daydiff);
$strdatebc = "";$strshortdate = "";
//var_dump($daydiff);
if($daydiff->d==0 && $daydiff->m==0 && $daydiff->y==0) //trong ngay
	{
		$strdatebc = "BÁO CÁO THỐNG KÊ NGÀY ".date("d/m/Y",strtotime($datefrom));
		$strshortdate = "Ngay_".date("d-m-Y",strtotime($datefrom));
	}
	
	else if((($daydiff->d>25 && $daydiff->m==2)||$daydiff->m==3) && $daydiff->y==0)
		{ 
			$strdatebc="BÁO CÁO THỐNG KÊ 03 THÁNG NĂM ".date("Y",strtotime($datefrom));
			$strshortdate = "3_thang";
		}
	else if((($daydiff->d>25 && $daydiff->m==5)||$daydiff->m==6) && $daydiff->y==0)
		{ 
			$strdatebc="BÁO CÁO THỐNG KÊ 06 THÁNG NĂM ".date("Y",strtotime($datefrom));
			$strshortdate = "6_thang";
		}
	else if((($daydiff->d>25 && $daydiff->m==8)||$daydiff->m==9) && $daydiff->y==0)
		{ 
			$strdatebc="BÁO CÁO THỐNG KÊ 09 THÁNG NĂM ".date("Y",strtotime($datefrom));
			$strshortdate = "9_thang";
		}
	else if(( $daydiff->m>=11 && $daydiff->y==0) || $daydiff->y==1)
		{ 
			$strdatebc="BÁO CÁO THỐNG KÊ NĂM ".date("Y",strtotime($datefrom));
			$strshortdate = "Nam_".date("Y",strtotime($datefrom));
		}
	else{  //khoang ngay
	$strdatebc = "BÁO CÁO THỐNG KÊ NGÀY ".date("d/m/Y",strtotime($datefrom))." - ĐẾN NGÀY ".date("d/m/Y",strtotime($dateto));
	$strshortdate = "Ngay_".date("d-m-Y",strtotime($datefrom))."_".date("d-m-Y",strtotime($dateto));
	}
	//echo $strdatebc;
$classpathFPDF=$root_path.'classes/fpdf/';
$fontpathFPDF=$classpathFPDF.'font/unifont/';
define("_SYSTEM_TTFONTS",$fontpathFPDF);
require_once($root_path.'classes/tcpdf/config/lang/eng.php');
require_once($root_path.'classes/tcpdf/tcpdf.php');
include($classpathFPDF.'tfpdf.php');
$tpdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8',false);
    $tpdf->SetTitle($strdatebc);
    $tpdf->SetAuthor('Vien KH-CN VN - Phong CN Tinh Toan & CN Tri Thuc');
    $tpdf->SetMargins(5, 8, 3);    
    // remove default header/footer
    $tpdf->setPrintHeader(false);
    $tpdf->setPrintFooter(false);

    //set auto page breaks
    $tpdf->SetAutoPageBreak(FALSE);
    $tpdf->AddPage('L','A4');
    $tpdf->SetFont('dejavusans', '', 10);


$header_1='<table  >
                <tr>
                    <td width="30%">
                            SỞ Y TẾ BÌNH DƯƠNG<br>
                            '.PDF_HOSNAME.'
                    </td>
                    <td align="center" width="50%">
                    	<b><font size="15">TÌNH HÌNH BỆNH TẬT TỬ VONG</font></b><br><br>
                        <i>('.$strdatebc.')</i>
                    </td>
                    <td align="right" width="18%">Biểu 15-BCH</td>
                </tr>
                
                </table>';
    $tpdf->writeHTML($header_1);
    $tpdf->SetFont('dejavusans', '', 8);
   $header2 = '
   <tr>
	   <td rowspan="4" align="center" width="3%"><br><br><br><b>Số<br>TT</b></td>
	   <td rowspan="4" align="center" width="'.WIDTH_BT.'%"><br><br><br><br><b>TÊN BỆNH</b></td>
	   <td rowspan="4" align="center" width="6%"><br><br><br><b>Mã<br>ICD<br>10</b></td>
	   <td colspan="4" rowspan="2" align="center" width="20%"><br><br><b>Tại khoa khám bệnh</b></td>
	   <td colspan="8" align="center" width="40%"><b>Điều trị nội trú</b></td>
   </tr>
   <tr>
	   <td colspan="4" align="center" width="20%"><b>Tổng số</b></td>
	   <td colspan="4" align="center" width="20%"><b>Trong đó TE dưới 15 tuổi</b></td>
   </tr>
   <tr>
	   	<td rowspan="2" align="center" width="5%"><br><br><b>Tổng số</b></td>
	   	<td colspan="3" align="center"  width="15%"><b>Trong đó</b></td>
	   	<td colspan="2" align="center"  width="10%"><b>Mắc</b></td>
	   	<td colspan="2" align="center"  width="10%"><b>Tử vong</b></td>
	   	<td colspan="2" align="center"  width="10%"><b>Mắc</b></td>
	   	<td colspan="2" align="center" width="10%"><b>Tử vong</b></td>
   </tr>
   <tr>
	    <td  align="center" width="5%"><b>Nữ</b></td>
	    <td  align="center" width="5%"><b>TE dưới 15 tuổi</b></td>
	    <td  align="center" width="5%"><b>Tử vong</b></td>
	    <td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Nữ</b></td>
		<td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Nữ</b></td>
		<td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Dưới 5 Tuổi</b></td>
		<td  align="center" width="5%"><b>TS</b></td>
		<td  align="center" width="5%"><b>Dưới 5 Tuổi</b></td>
   </tr>';
   // $tpdf->writeHTML($header2);
    $header3 = '<tr>
	    <td align="center"  width="3%">VN</td>
	    <td align="center" width="'.WIDTH_BT.'%"></td>
	    <td align="center" width="6%">QT</td>
	    <td align="center" width="5%">4</td><td align="center" width="5%">5</td><td align="center" width="5%">6</td><td align="center" width="5%">7</td>
	    <td align="center" width="5%">8</td><td align="center" width="5%">9</td><td align="center" width="5%">10</td><td align="center" width="5%">11</td>
	    <td align="center" width="5%">12</td><td align="center" width="5%">13</td><td align="center" width="5%">14</td><td align="center" width="5%">15</td>
    </tr>';
    // $tpdf->writeHTML($header2);

    //content
    $content = '';
    global $db;
    $arrsection = array();
    $sql = "SELECT * FROM dfck_icd10_vi_section";
    if($rs = $db->Execute($sql)){
			if($rs->RecordCount()){
				while($row=$rs->FetchRow()){
					$arrsection[$row['shortname']] = array($row['vncode'],'<b>Chương '.$row['shortname'].': '.$row['info'].'<br>Chapter '.$row['shortname'].': '.$row['info_en'].'</b>',$row['cbegin'],$row['cend']);
				}
			}
		}
		//var_dump($id);
		if($id=='kkb') $where ="AND v.encounter_class_nr = 2 AND v.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)";
		else if($id=='dtnt') $where ="AND v.encounter_class_nr = 1";
		else $where = "AND ((v.encounter_class_nr = 2 AND v.current_dept_nr = (SELECT nr FROM care_department WHERE id=5))OR v.encounter_class_nr = 1)";
	$sql="SELECT DISTINCT(v.vncode), v.info,v.icd10, v.icd10more, v.sname,
				(SELECT COUNT(v2.encounter_nr) FROM dfck_bttv_view v2 
					WHERE v2.vncode = v.vncode AND v2.encounter_class_nr = 2
					AND DATE_FORMAT(v2.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
					AND DATE_FORMAT(v2.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
					AND v2.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)) sumkb,
				(SELECT COUNT(v3.encounter_nr) FROM dfck_bttv_view v3 
					WHERE v3.vncode = v.vncode 
					AND DATE_FORMAT(v3.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
				AND DATE_FORMAT(v3.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
					AND v3.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)
					AND ((DATE_FORMAT(NOW(),'%Y') - SUBSTR(v3.birthyear,1,4)) < 15 )  AND v3.encounter_class_nr = 2) sumkid,
				(SELECT COUNT(v4.encounter_nr) FROM dfck_bttv_view v4 
					WHERE v4.vncode = v.vncode AND v4.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)
					AND DATE_FORMAT(v4.death_date,'%Y-%m-%d')>= '".date('Y-m-d',strtotime($datefrom))."' 
					AND DATE_FORMAT(v4.death_date,'%Y-%m-%d')<= '".date('Y-m-d',strtotime($dateto))."' 
					 AND v4.encounter_class_nr = 2) sumdead,
				(SELECT COUNT(v5.encounter_nr) FROM dfck_bttv_view v5 WHERE v5.vncode = v.vncode 
					AND DATE_FORMAT(v5.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
				AND DATE_FORMAT(v5.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
					AND v5.sex ='f' AND v5.current_dept_nr = (SELECT nr FROM care_department WHERE id=5)
					 AND v5.encounter_class_nr = 2
					) sumf,
				(SELECT COUNT(v6.encounter_nr) FROM dfck_bttv_view v6 WHERE v6.vncode = v.vncode 
					AND DATE_FORMAT(v6.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
				AND DATE_FORMAT(v6.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
					AND v6.encounter_class_nr = 1) sumpain,
				(SELECT COUNT(v7.encounter_nr) FROM dfck_bttv_view v7 WHERE v7.vncode = v.vncode 
					AND DATE_FORMAT(v7.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
				AND DATE_FORMAT(v7.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
					AND v7.encounter_class_nr = 1 AND v7.sex='f') sumpainf,
				(SELECT COUNT(v8.encounter_nr) FROM dfck_bttv_view v8 WHERE v8.vncode = v.vncode AND v8.encounter_class_nr = 1 
					AND DATE_FORMAT(v8.death_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."'
					AND DATE_FORMAT(v8.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."') sumpaindead,
				(SELECT COUNT(v9.encounter_nr) FROM dfck_bttv_view v9 WHERE v9.vncode = v.vncode AND v9.encounter_class_nr = 1 
					AND DATE_FORMAT(v9.death_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."'
					AND DATE_FORMAT(v9.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' AND v9.sex='f') sumpaindeadf,
				(SELECT COUNT(v10.encounter_nr) FROM dfck_bttv_view v10 WHERE v10.vncode = v.vncode 
					AND DATE_FORMAT(v10.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
				AND DATE_FORMAT(v10.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
					AND v10.encounter_class_nr = 1
					AND ((DATE_FORMAT(NOW(),'%Y') - SUBSTR(v10.birthyear,1,4)) < 15 ) ) sumpainkid,
				(SELECT COUNT(v11.encounter_nr) FROM dfck_bttv_view v11 WHERE v11.vncode = v.vncode 
					AND DATE_FORMAT(v11.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
				AND DATE_FORMAT(v11.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
					AND v11.encounter_class_nr = 1
					AND ((DATE_FORMAT(NOW(),'%Y') - SUBSTR(v11.birthyear,1,4)) < 5 )) sumpainkid5,
				(SELECT COUNT(v12.encounter_nr) FROM dfck_bttv_view v12 WHERE v12.vncode = v.vncode AND v12.encounter_class_nr = 1
					AND ((DATE_FORMAT(NOW(),'%Y') - SUBSTR(v12.birthyear,1,4)) < 15 ) 
					AND DATE_FORMAT(v12.death_date,'%Y-%m-%d')>= ".date("Y-m-d",strtotime($datefrom))."
					AND DATE_FORMAT(v12.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."') sumpainkiddead,
				(SELECT COUNT(v13.encounter_nr) FROM dfck_bttv_view v13 WHERE v13.vncode = v.vncode AND v13.encounter_class_nr = 1
					AND ((DATE_FORMAT(NOW(),'%Y') - SUBSTR(v13.birthyear,1,4)) < 5 ) 
					AND DATE_FORMAT(v13.death_date,'%Y-%m-%d')>= ".date("Y-m-d",strtotime($datefrom))."
					AND DATE_FORMAT(v13.death_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' ) sumpainkiddead5
				FROM dfck_bttv_view v
				WHERE v.vncode!='' and v.vncode!='NULL'
				AND DATE_FORMAT(v.encounter_date,'%Y-%m-%d')>= '".date("Y-m-d",strtotime($datefrom))."' 
				AND DATE_FORMAT(v.encounter_date,'%Y-%m-%d')<= '".date("Y-m-d",strtotime($dateto))."' 
				$where 				
				ORDER BY v.vncode";
	
			//echo $sql;
		$crrsec = "";

		if($rs = $db->Execute($sql)){
			if($rs->RecordCount()){
				$i=1;			
				$numline = 1;
				$nowpage = 1;	
				if($nowpage==1) $numline = 8;
				while($row=$rs->FetchRow()){	
					if($row['sname']!=$crrsec){	
						$crrsec = $row['sname'];
						$sec = $arrsection[$crrsec];					
						$description = $sec[1];
						$column_width = 270*WIDTH_BT/100;//mm
						$num_thisline = (ceil( $tpdf->GetStringWidth($description) / ($column_width))) + 1;					  
						$numline += $num_thisline;
						if ( $numline >= MAX_ROW_PP ) {  
							if($nowpage==1)  $tpdf->writeHTML('<table border="1"  cellpadding="3">'.$header2.$header3.$content.'</table><div align="right">Trang '.$nowpage.'</div>');                    
					     	else $tpdf->writeHTML('<table border="1"  cellpadding="3">'.$header3.$content.'</table><div align="right">Trang '.$nowpage.'</div>');
					  		$tpdf->AddPage(); // page break.
					  		$content = "";
					  		$numline = $num_thisline;
					  		$nowpage ++;
					  	}		
						
						$content .= '<tr>
							<td align="center"><b>'.$sec[0].'</b></td><td>'.$sec[1].'</td><td align="center"><b>'.$sec[2].' - '.$sec[3].'</b></td>
							<td></td><td></td>
							<td></td><td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td><td></td>
						</tr>';		
					}
					$description = $row['info'].(($row['icd10more']!='')?' ('.$row['icd10more'].') ':'');
					$column_width = 270*WIDTH_BT/100;//mm
					$num_thisline = ceil( $tpdf->GetStringWidth($description) / ($column_width) );					  
					$numline += $num_thisline;
					  if ( $numline >= MAX_ROW_PP ) {                       
					     	if($nowpage==1)  $tpdf->writeHTML('<table border="1"  cellpadding="3">'.$header2.$header3.$content.'</table><div align="right">Trang '.$nowpage.'</div>');                    
					     	else $tpdf->writeHTML('<table border="1"  cellpadding="3">'.$header3.$content.'</table><div align="right">Trang '.$nowpage.'</div>');
					  		$tpdf->AddPage(); // page break.
					  		$content = "";
					  		$numline = $num_thisline;
					  		$nowpage ++;
					  }
					$content .='<tr>
						<td align="center">'.$row['vncode'].'</td><td> '.$row['info'].(($row['icd10more']!='')?' ('.$row['icd10more'].') ':'').'</td>
						<td align="center">'.$row['icd10'].'</td>
						<td align="center">'.(($row['sumkb']>0 && ($id=='kkb' || $id=='all'))?$row['sumkb']:'').'</td>
						<td align="center">'.(($row['sumf']>0 && ($id=='kkb' || $id=='all'))?$row['sumf']:'').'</td>						
						<td align="center">'.(($row['sumkid']>0 && ($id=='kkb' || $id=='all'))?$row['sumkid']:'').'</td>
						<td align="center">'.(($row['sumdead']>0 && ($id=='kkb' || $id=='all'))?$row['sumdead']:'').'</td>
						<td align="center">'.(($row['sumpain']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpain']:'').'</td>
						<td align="center">'.(($row['sumpainf']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpainf']:'').'</td>
						<td align="center">'.(($row['sumpaindead']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpaindead']:'').'</td>
						<td align="center">'.(($row['sumpaindeadf']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpaindeadf']:'').'</td>
						<td align="center">'.(($row['sumpainkid']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpainkid']:'').'</td>
						<td align="center">'.(($row['sumpainkid5']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpainkid5']:'').'</td>
						<td align="center">'.(($row['sumpainkiddead']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpainkiddead']:'').'</td>
						<td align="center">'.(($row['sumpainkiddead5']>0 && ($id=='dtnt' || $id=='all'))?$row['sumpainkiddead5']:'').'</td>
					</tr>';						
				}
			}
		}
	if($nowpage==1)  $tpdf->writeHTML('<table border="1"  cellpadding="3">'.$header2.$header3.$content.'</table><div align="right">Trang '.$nowpage.'</div>');     
    else $tpdf->writeHTML('<table border="1"  cellpadding="3">'.$header3.$content.'</table>');
    $tpdf->SetFont('dejavusans', '', 10);
$footer = '<table width=100%>
			<tr>
			<td width="30%" align="center"></td>
                <td width="30%" align="center"></td>
                <td width="38%" align="center">Ngày ... tháng ... năm ...</td>
			</tr>
            <tr>
                <td width="30%" align="center"><b>NGƯỜI LẬP BIỂU</b><br><i>(Chức danh, ký tên)</i></td>
                <td width="30%" align="center"><b>TRƯỞNG PHÒNG KHTH</b><br><i>(Chức danh, ký tên)</i><br><br><br></td>
                <td width="38%" align="center"><b>GIÁM ĐỐC</b><br><i>(Ký tên, đóng dấu)</i><br><br><br></td>
            </tr>            
            </table>';
            if ( ($numline+8) >= MAX_ROW_PP ) {                       
					     	//$tpdf->writeHTML($header3.$content.'</table><div align="right">Trang '.$nowpage.'</div>');
					  		$tpdf->AddPage(); // page break.
					  		//$content = "";
					  		//$numline = 1;
					  		//$nowpage ++;
					  }
            $tpdf->writeHTML($footer);

$tpdf->Output($id.'_bttv_'.$strshortdate.'.pdf','I');