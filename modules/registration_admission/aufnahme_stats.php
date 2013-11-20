<?php
/*
 Thống kê ngày

*/
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables=array('date_time.php','departments.php','actions.php','prompt.php');
define('LANG_FILE','aufnahme.php');
$local_user="aufnahme_user";
require($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/core/inc_date_format_functions.php');
require_once($root_path.'include/care_api_classes/class_encounter.php');
$enc_obj=&new Encounter();

if(!isset($currMonth)||!$currMonth) $currMonth=date('m');
if(!isset($currYear)||!$currYear) $currYear=date('Y');
if($currDay){
$sTitle = "Tiếp nhận bệnh::Thống kê::Ngày ".$currDay."/".$currMonth."/".$currYear;
}else{
$sTitle = "Tiếp nhận bệnh::Thống kê::Tháng ".$currMonth."/".$currYear;
}

require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
# Load all medical departments
$med_arr=&$dept_obj->getAllMedical();

# Prepare the html select options
$options='';
$cond_dept=array();
while(list($x,$v)=each($med_arr)){
	if($x==42) continue;
	$buffer=$v['LD_var'];        
	if(isset($$buffer)&&!empty($$buffer)){
            $buf2=$$buffer;
            $cond_dept[$$buffer]=$v['nr'];
        }else{
            $buf2=$v['name_formal'];	
            $cond_dept[$$buffer]=$v['nr'];
        }
	$options.='
	<option value="'.$v['nr'].'"';
	if ($dept_nr==$v['nr']){
		$options.=' selected';
		$curr_dept=$buf2;
	}
	$options.='>'.$buf2.'</option>';
}
		
# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$sTitle);

 # href for help button
 $smarty->assign('pbHelp',"");

 # href for close button
 $smarty->assign('breakfile',$rettarget);

 # Window bar title
 $smarty->assign('sWindowTitle',$sTitle);

 # Collect extra javascript

 ob_start();
 ?>
 <script language="javascript">
 function getbymonth(){
 var xmlhttp;
 var str=document.getElementById("currYear").value;
 var month=document.getElementById("select_month").value;
 if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("result").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","getresultstats.php?year="+str+"&month="+month,true);
	xmlhttp.send();

 }
  function printOut(currYear,currMonth,currDay,url)
  {
            urlholder="<?php echo $root_path ?>modules/pdfmaker/"+url+"<?php echo URL_APPEND ?>&currYear="+currYear+"&currMonth="+currMonth+"&currDay="+currDay+"";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
            //testprintout<?php echo $sid ?>.print();
  }
 function getbyqui(){
 var xmlhttp;
 var str=document.getElementById("currYear").value;
 var month=document.getElementById("select_qui").value;
 if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("result").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","getresultstatsbyqui.php?year="+str+"&qui="+month,true);
	xmlhttp.send();

 }
</script>
 <?php
 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->append('JavaScript',$sTemp);
 
 ob_start();

	/*generate the calendar */
	include($root_path.'classes/calendar_jl/class.calendar.php');
	/** CREATE CALENDAR OBJECT **/
	$Calendar = new Calendar;
	/** WRITE CALENDAR **/
	$Calendar -> mkCalendar ($currYear, $currMonth, $currDay,$dept_nr,$aux);

	$sTemp = ob_get_contents();
ob_end_clean();

$smarty->assign('sMiniCalendar',$sTemp);

$smarty->assign('LDListApptByDept',$LDListApptByDept);
$smarty->assign('sByDeptSelect','<select name="dept_nr">
			<option value="">'.$LD_AllMedicalDept.'</option>'.$options.'
			</select>');
$smarty->assign('sByDeptHiddenInputs','<input type="submit" value="'.$LDShow.'">
			<input type="hidden"  name="currYear" value="'.$currYear.'">
			<input type="hidden"  name="currMonth" value="'.$currMonth.'">
			<input type="hidden"  name="currDay" value="'.$currDay.'">
			<input type="hidden"  name="sid" value="'.$sid.'">
			<input type="hidden"  name="lang" value="'.$lang.'">');
/*
$temp="<select id=\"select_qui\"><option value=\"1\">Quí I</option>
<option value=\"2\">Quí II</option>
<option value=\"3\">Quí III</option>
<option value=\"4\">Quí IV</option>
</select>
<input type=button onclick=\"getbyqui()\" value=xem>";
$smarty->assign('sSelectQui',$temp);


$temp1="<select id=\"select_month\" >";
for ($i=1;$i<13;$i++){
	 $temp1 = $temp1.'<option  value="'.$i.'" ';
	 if (($currMonth)==$i)  $temp1 = $temp1.'selected';
	  $temp1 = $temp1.'>'.$monat[$i].'</option>';
	  $temp1 = $temp1."\n";
}
$temp1.="<input type=button onclick=\"getbymonth()\" value=xem >";
$smarty->assign('sSelectMonth',$temp1);
*/
$dept=$dept_obj->getAllDept('AND nr="'.$dept_nr.'"');
if($dept){
    $id_dept=$dept->FetchRow();
    if($id_dept['id'] && $id_dept['id']!=7){
        $cond="AND current_dept_nr=$dept_nr";
    }else if($id_dept['id']==7){
        $cond="AND e.current_dept_nr=$dept_nr";
        $cond2=$cond.' AND p.tuoi<2 AND p.thang>16';
    }else{
        $cond='';
    }
}
if($id_dept['id']!=7 ){    
    if($currDay){        
        $smarty->assign('tong',$enc_obj->getStatsByDate($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('tongbhyt',$enc_obj->getStatsByDateBHYT($currYear,$currMonth,$currDay,$cond));
        $tongkbhyt=($enc_obj->getStatsByDate($currYear,$currMonth,$currDay,$cond))-($enc_obj->getStatsByDateBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('tongkbhyt',$tongkbhyt);
        $smarty->assign('khamngoai',$enc_obj->getStatsByDateNgoai($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamnoi',$enc_obj->getStatsByDateNoi($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamngoaibh',$enc_obj->getStatsByDateNgoaiBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamnoibh',$enc_obj->getStatsByDateNoiBHYT($currYear,$currMonth,$currDay,$cond));
        $khamngoaikbh=$enc_obj->getStatsByDateNgoai($currYear,$currMonth,$currDay,$cond) - $enc_obj->getStatsByDateNgoaiBHYT($currYear,$currMonth,$currDay,$cond);
        $smarty->assign('khamngoaikbh',$khamngoaikbh);
        $khamnoikbh=$enc_obj->getStatsByDateNoi($currYear,$currMonth,$currDay,$cond) - $enc_obj->getStatsByDateNoiBHYT($currYear,$currMonth,$currDay,$cond);
        $smarty->assign('khamnoikbh',$khamnoikbh);
        $smarty->assign('nhi',$enc_obj->getStatsByDateNhi($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nhi6',$enc_obj->getStatsByDateNhi6($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nguoigia',$enc_obj->getStatsByDateGia($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nhapvien',$enc_obj->getStatsByDateInPatient($currYear,$currMonth,$currDay,$cond));
        if($cond_dept[$LDKN]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKN]";
        }
        $smarty->assign('nhapvienngoai',$enc_obj->getStatsByDateInPatientNgoai($currYear,$currMonth,$currDay,$cond1));
        if($cond_dept[$LDKNNN]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKNNN]";
        }
        $smarty->assign('nhapviennoi',$enc_obj->getStatsByDateInPatientNoi($currYear,$currMonth,$currDay,$cond1));
        if($cond_dept[$LDKHSCC]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKHSCC]";
        }
        $smarty->assign('nhapvienhscc',$enc_obj->getStatsByDateInPatientHSCC($currYear,$currMonth,$currDay,$cond1));
        if($cond_dept[$LDKYHCT]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKYHCT]";
        }
        $smarty->assign('nhapvienyhct',$enc_obj->getStatsByDateInPatientYHCT($currYear,$currMonth,$currDay,$cond1));
        if($cond_dept[$LDKS]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKS]";
        }
        $smarty->assign('nhapviensan',$enc_obj->getStatsByDateInPatientSan($currYear,$currMonth,$currDay,$cond1));
        if($cond_dept[$LDKNh]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKNh]";
        }
        $smarty->assign('nhapviennhiem',$enc_obj->getStatsByDateInPatientNhiem($currYear,$currMonth,$currDay,$cond1));
        $smarty->assign('cum',$enc_obj->getStatsByDateCum($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('tieuchay',$enc_obj->getStatsByDateTieuChay($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('pPrintOut','<a href="javascript:printOut('.$currYear.','.$currMonth.','.$currDay.','."'admission/admitstats.php'".')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="'.$LDPrintOut.'"  align="absmiddle"></a>');
    }else{
        $smarty->assign('tong',$enc_obj->getStatsByMonth($currYear,$currMonth,$cond));
        $smarty->assign('tongbhyt',$enc_obj->getStatsByMonthBHYT($currYear,$currMonth,$cond));
        $tongkbhyt=($enc_obj->getStatsByMonth($currYear,$currMonth,$cond))-($enc_obj->getStatsByMonthBHYT($currYear,$currMonth,$cond));
        $smarty->assign('tongkbhyt',$tongkbhyt);
        $smarty->assign('khamngoai',$enc_obj->getStatsByMonthNgoai($currYear,$currMonth,$cond));
        $smarty->assign('khamnoi',$enc_obj->getStatsByMonthNoi($currYear,$currMonth,$cond));
        $smarty->assign('khamngoaibh',$enc_obj->getStatsByMonthNgoaiBHYT($currYear,$currMonth,$cond));
        $smarty->assign('khamnoibh',$enc_obj->getStatsByMonthNoiBHYT($currYear,$currMonth,$cond));
        $khamngoaikbh=$enc_obj->getStatsByMonthNgoai($currYear,$currMonth,$cond) - $enc_obj->getStatsByMonthNgoaiBHYT($currYear,$currMonth,$cond);
        $smarty->assign('khamngoaikbh',$khamngoaikbh);
        $khamnoikbh=$enc_obj->getStatsByMonthNoi($currYear,$currMonth,$cond) - $enc_obj->getStatsByMonthNoiBHYT($currYear,$currMonth,$cond);
        $smarty->assign('khamnoikbh',$khamnoikbh);
        $smarty->assign('nhi',$enc_obj->getStatsByMonthNhi($currYear,$currMonth,$cond));
        $smarty->assign('nhi6',$enc_obj->getStatsByMonthNhi6($currYear,$currMonth,$cond));
        $smarty->assign('nguoigia',$enc_obj->getStatsByMonthGia($currYear,$currMonth,$cond));
        $smarty->assign('nhapvien',$enc_obj->getStatsByMonthInPatient($currYear,$currMonth,$cond));
        if($cond_dept[$LDKN]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKN]";
        }
        $smarty->assign('nhapvienngoai',$enc_obj->getStatsByMonthInPatientNgoai($currYear,$currMonth,$cond1));
        if($cond_dept[$LDKNNN]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKNNN]";
        }
        $smarty->assign('nhapviennoi',$enc_obj->getStatsByMonthInPatientNoi($currYear,$currMonth,$cond1));
        if($cond_dept[$LDKHSCC]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKHSCC]";
        }
        $smarty->assign('nhapvienhscc',$enc_obj->getStatsByMonthInPatientHSCC($currYear,$currMonth,$cond1));
        if($cond_dept[$LDKYHCT]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKYHCT]";
        }
        $smarty->assign('nhapvienyhct',$enc_obj->getStatsByMonthInPatientYHCT($currYear,$currMonth,$cond1));
        if($cond_dept[$LDKS]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKS]";
        }
        $smarty->assign('nhapviensan',$enc_obj->getStatsByMonthInPatientSan($currYear,$currMonth,$cond1));
        if($cond_dept[$LDKNh]){
            $cond1="AND current_dept_nr=$cond_dept[$LDKNh]";
        }
        $smarty->assign('nhapviennhiem',$enc_obj->getStatsByMonthInPatientNhiem($currYear,$currMonth,$cond1));
        $smarty->assign('cum',$enc_obj->getStatsByMonthCum($currYear,$currMonth,$cond));
        $smarty->assign('tieuchay',$enc_obj->getStatsByMonthTieuChay($currYear,$currMonth,$cond));

        $smarty->assign('pPrintOut','<a href="javascript:printOut('.$currYear.','.$currMonth.','.'\' \','."'admission/admitstats.php'".')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="'.$LDPrintOut.'"  align="absmiddle"></a>');
               
    }
    $smarty->assign('sWeekLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_week.php'.URL_APPEND.'">Thống kê tuần</a>');
    $smarty->assign('sQuiLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_qui.php'.URL_APPEND.'">Thống kê quí</a>');
    $smarty->assign('sMainBlockIncludeFile','registration_admission/appt_list.tpl'); 
}else{
    $temp='<tr class="wardlisttitlerow">
                <td style="text-align:center;">Tổng Bệnh nhân&nbsp;</td>
                <td style="text-align:center;">BHYT</td>
                <td style="text-align:center;">Không BHYT</td>
                <td style="text-align:center;" colspan="5">Trẻ Sơ sinh</td>
				<td style="text-align:center;" colspan="2">Khám thai</td>
				<td style="text-align:center;" colspan="2">Khám phụ khoa</td>
        </tr>';
    $smarty->assign('tr',$temp);
    
    if($currDay){
        $smarty->assign('tong',$enc_obj->getStatsByDateSan($currYear,$currMonth,$currDay,$cond));        
        $smarty->assign('tongbhyt',$enc_obj->getStatsByDateBHYTSan($currYear,$currMonth,$currDay,$cond2));
        $smarty->assign('tongkbhyt',$enc_obj->getStatsByDateKBHYTSan($currYear,$currMonth,$currDay,$cond2));
        
        $smarty->assign('nhi',$enc_obj->getStatsByDateSoSinh($currYear,$currMonth,$currDay,$cond2));
        $smarty->assign('nhinoi',$enc_obj->getStatsByDateSoSinh_noi($currYear,$currMonth,$currDay,$cond2));
        $smarty->assign('nhingoai',$enc_obj->getStatsByDateSoSinh($currYear,$currMonth,$currDay,$cond2)-$enc_obj->getStatsByDateSoSinh_noi($currYear,$currMonth,$currDay,$cond2));
        $smarty->assign('nhisexm',$enc_obj->getStatsByDateSoSinh_GTM($currYear,$currMonth,$currDay,$cond2));
        $smarty->assign('nhisexf',$enc_obj->getStatsByDateSoSinh($currYear,$currMonth,$currDay,$cond2)-$enc_obj->getStatsByDateSoSinh_GTM($currYear,$currMonth,$currDay,$cond2));
        $smarty->assign('nhicann',$enc_obj->getStatsByDateSoSinh_CN($currYear,$currMonth,$currDay,$cond2));
        
        $smarty->assign('khamngoai',$enc_obj->getStatsByDateNgoaitru($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamngoaibh',$enc_obj->getStatsByDateNgoaitruBHYT($currYear,$currMonth,$currDay,$cond2));
        $khamngoaikbh=$enc_obj->getStatsByDateNgoaitru($currYear,$currMonth,$currDay,$cond) - $enc_obj->getStatsByDateNgoaitruBHYT($currYear,$currMonth,$currDay,$cond2);
        
        $smarty->assign('khamthai',$enc_obj->getStatsByDateKhamthai($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamthaibh',$enc_obj->getStatsByDateKhamthaiBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamthaikbh',$enc_obj->getStatsByDateKhamthai($currYear,$currMonth,$currDay,$cond)-$enc_obj->getStatsByDateKhamthaiBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamngoaikbh',$khamngoaikbh);
        
        $smarty->assign('khamPkhoa',$enc_obj->getStatsByDateKhamPkhoa($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamPkhoabh',$enc_obj->getStatsByDateKhamPkhoaBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamPkhoakbh',$enc_obj->getStatsByDateKhamPkhoa($currYear,$currMonth,$currDay,$cond)-$enc_obj->getStatsByDateKhamPkhoaBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('datvong',$enc_obj->getStatsByDateDatvong($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('thuocvien',$enc_obj->getStatsByDateThuocvien($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('thuoctiem',$enc_obj->getStatsByDateThuoctiem($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('bcs',$enc_obj->getStatsByDateBCS($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('naohut',$enc_obj->getStatsByDateNaoHut($currYear,$currMonth,$currDay,$cond));
        
        $khamnoi=$enc_obj->getStatsByDateSan($currYear,$currMonth,$currDay,$cond)-$enc_obj->getStatsByDateNgoaitru($currYear,$currMonth,$currDay,$cond);
        $khamnoibh=$enc_obj->getStatsByDateBHYTSan($currYear,$currMonth,$currDay,$cond2)-$enc_obj->getStatsByDateNgoaitruBHYT($currYear,$currMonth,$currDay,$cond2);
        $smarty->assign('khamnoi',$khamnoi);        
        $smarty->assign('khamnoibh',$khamnoibh);         
        $khamnoikbh=$khamnoi - $khamnoibh;
        $smarty->assign('khamnoikbh',$khamnoikbh); 
        
        $smarty->assign('cotiem',$enc_obj->getStatsByDateCotiem($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khongtiem',$enc_obj->getStatsByDateKhongtiem($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('cokhamthai',$enc_obj->getStatsByDateKhamthaiNoi($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khongkhamthai',$enc_obj->getStatsByDateKKhamthaiNoi($currYear,$currMonth,$currDay,$cond));
        $nr_sanphu=$enc_obj->getPatientInSan($cond);
        $count_sinh=0;
        if($nr_sanphu){
            while($nr=$nr_sanphu->FetchRow()){
                $count_sinh+=$enc_obj->getPatientSinh($nr);
            }
        }
        $smarty->assign('sinhnlan',$count_sinh);        
        $smarty->assign('pPrintOut','<a href="javascript:printOut('.$currYear.','.$currMonth.','.$currDay.','."'BCTT/BM07_PHUKHOA_NAOPHATHAI.php'".')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="'.$LDPrintOut.'"  align="absmiddle"></a>');
    }else{
        $smarty->assign('tong',$enc_obj->getStatsByMonthSan($currYear,$currMonth,$cond));
        $smarty->assign('tongbhyt',$enc_obj->getStatsByMonthBHYTSan($currYear,$currMonth,$cond2));
        $smarty->assign('tongkbhyt',$enc_obj->getStatsByMonthKBHYTSan($currYear,$currMonth,$cond2));
        
        $smarty->assign('nhi',$enc_obj->getStatsByMonthSoSinh($currYear,$currMonth,$cond2));
        $smarty->assign('nhinoi',$enc_obj->getStatsByMonthSoSinh_noi($currYear,$currMonth,$cond2));
        $smarty->assign('nhingoai',$enc_obj->getStatsByMonthSoSinh($currYear,$currMonth,$cond2)-$enc_obj->getStatsByMonthSoSinh_noi($currYear,$currMonth,$cond2));
        $smarty->assign('nhisexm',$enc_obj->getStatsByMonthSoSinh_GTM($currYear,$currMonth,$cond2));
        $smarty->assign('nhisexf',$enc_obj->getStatsByMonthSoSinh($currYear,$currMonth,$cond2)-$enc_obj->getStatsByMonthSoSinh_GTM($currYear,$currMonth,$cond2));
        $smarty->assign('nhicann',$enc_obj->getStatsByMonthSoSinh_CN($currYear,$currMonth,$cond2));
        
        $smarty->assign('khamngoai',$enc_obj->getStatsByMonthNgoaitru($currYear,$currMonth,$cond));
        $smarty->assign('khamngoaibh',$enc_obj->getStatsByMonthNgoaitruBHYT($currYear,$currMonth,$cond2));
        $khamngoaikbh=$enc_obj->getStatsByMonthNgoaitru($currYear,$currMonth,$cond) - $enc_obj->getStatsByMonthNgoaitruBHYT($currYear,$currMonth,$cond2);
        
        $smarty->assign('khamthai',$enc_obj->getStatsByMonthKhamthai($currYear,$currMonth,$cond));
        $smarty->assign('khamthaibh',$enc_obj->getStatsByMonthKhamthaiBHYT($currYear,$currMonth,$cond));
        $smarty->assign('khamthaikbh',$enc_obj->getStatsByMonthKhamthai($currYear,$currMonth,$cond) -$enc_obj->getStatsByMonthKhamthaiBHYT($currYear,$currMonth,$cond));
        $smarty->assign('khamngoaikbh',$khamngoaikbh);
        
        $smarty->assign('khamPkhoa',$enc_obj->getStatsByMonthKhamPkhoa($currYear,$currMonth,$cond));
        $smarty->assign('khamPkhoabh',$enc_obj->getStatsByMonthKhamPkhoaBHYT($currYear,$currMonth,$cond));
        $smarty->assign('khamPkhoakbh',$enc_obj->getStatsByMonthKhamPkhoa($currYear,$currMonth,$cond) - $enc_obj->getStatsByMonthKhamPkhoaBHYT($currYear,$currMonth,$cond));
        $smarty->assign('datvong',$enc_obj->getStatsByMonthDatvong($currYear,$currMonth,$cond));
        $smarty->assign('thuocvien',$enc_obj->getStatsByMonthThuocvien($currYear,$currMonth,$cond));
        $smarty->assign('thuoctiem',$enc_obj->getStatsByMonthThuoctiem($currYear,$currMonth,$cond));
        $smarty->assign('bcs',$enc_obj->getStatsByMonthBCS($currYear,$currMonth,$cond));
        $smarty->assign('naohut',$enc_obj->getStatsByMonthNaoHut($currYear,$currMonth,$cond));
        
        $khamnoi=$enc_obj->getStatsByMonthSan($currYear,$currMonth,$cond)-$enc_obj->getStatsByMonthNgoaitru($currYear,$currMonth,$cond);
        $khamnoibh=$enc_obj->getStatsByMonthBHYTSan($currYear,$currMonth,$cond2)-$enc_obj->getStatsByMonthNgoaitruBHYT($currYear,$currMonth,$cond2);
        $smarty->assign('khamnoi',$khamnoi);        
        $smarty->assign('khamnoibh',$khamnoibh);
        $khamnoikbh=$khamnoi - $khamnoibh;
        $smarty->assign('khamnoikbh',$khamnoikbh); 
        
        $smarty->assign('cotiem',$enc_obj->getStatsByMonthCotiem($currYear,$currMonth,$cond));
        $smarty->assign('khongtiem',$enc_obj->getStatsByMonthKhongtiem($currYear,$currMonth,$cond));
        $smarty->assign('cokhamthai',$enc_obj->getStatsByMonthKhamthaiNoi($currYear,$currMonth,$cond));
        $smarty->assign('khongkhamthai',$enc_obj->getStatsByMonthKKhamthaiNoi($currYear,$currMonth,$cond));
        $nr_sanphu=$enc_obj->getPatientInSan($cond);
        $count_sinh=0;
        if($nr_sanphu){
            while($nr=$nr_sanphu->FetchRow()){
                $count_sinh+=$enc_obj->getPatientSinh($nr);
            }
        }
        $smarty->assign('sinhnlan',$count_sinh);
        
        $smarty->assign('pPrintOut','<a href="javascript:printOut('.$currYear.','.$currMonth.',\' \','."'BCTT/BM07_PHUKHOA_NAOPHATHAI.php'".')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="'.$LDPrintOut.'"  align="absmiddle"></a>');
    }
    $smarty->assign('sWeekLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_week.php'.URL_APPEND.'">Thống kê tuần</a>');
    $smarty->assign('sQuiLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_qui.php'.URL_APPEND.'">Thống kê quí</a>');
    $target='stats';
    $parent_admit = TRUE;
    $smarty->assign('sMainBlockIncludeFile','registration_admission/appt_list_1.tpl'); 
}

# Assign page output to the mainframe template
$smarty->display('common/mainframe.tpl');
?>