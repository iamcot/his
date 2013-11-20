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
while(list($x,$v)=each($med_arr)){
	if($x==42) continue;
	$buffer=$v['LD_var'];
	if(isset($$buffer)&&!empty($$buffer)) $buf2=$$buffer;
		else $buf2=$v['name_formal'];	
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
  function printOut(currYear,currMonth,currDay)
    {
            urlholder="<?php echo $root_path ?>modules/pdfmaker/admission/admitstats.php<?php echo URL_APPEND ?>&currYear="+currYear+"&currMonth="+currMonth+"&currDay="+currDay+"";
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
    if($id_dept['id']){
        $cond="AND current_dept_nr=$dept_nr";
    }else{
        $cond='';
    }
}
if($id_dept['id']!=7 ){
    $temp='<tr class="wardlisttitlerow">
						<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Tổng Bệnh nhân&nbsp;</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">BHYT</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Không BHYT</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khám Ngoại</td>						
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khám Nội</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nhi</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nhi < 6t</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;" colspan=2> > 60t </td>
					</tr>';
    $smarty->assign('tr',$temp);
    $temp1='<tr class="wardlisttitlerow">
						<td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Nhập viện</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khoa Ngoại</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khoa Nội nhi</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">HSCC</td>						
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khoa YHCT</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khoa Sản</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Khoa Nhiểm</td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;"> Cúm </td>
						<td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;"> Tiêu chảy </td>
					</tr>';
    $smarty->assign('tr1',$temp1);
    if($currDay){        
        $smarty->assign('tong',$enc_obj->getStatsByDate($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('tongbhyt',$enc_obj->getStatsByDateBHYT($currYear,$currMonth,$currDay,$cond));
        $tongkbhyt=($enc_obj->getStatsByDate($currYear,$currMonth,$currDay))-($enc_obj->getStatsByDateBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('tongkbhyt',$tongkbhyt);
        $smarty->assign('khamngoai',$enc_obj->getStatsByDateNgoai($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamnoi',$enc_obj->getStatsByDateNoi($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamngoaibh',$enc_obj->getStatsByDateNgoaiBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamnoibh',$enc_obj->getStatsByDateNoiBHYT($currYear,$currMonth,$currDay,$cond));
        $khamngoaikbh=$enc_obj->getStatsByDateNgoai($currYear,$currMonth,$currDay) - $enc_obj->getStatsByDateNgoaiBHYT($currYear,$currMonth,$currDay,$cond);
        $smarty->assign('khamngoaikbh',$khamngoaikbh);
        $khamnoikbh=$enc_obj->getStatsByDateNoi($currYear,$currMonth,$currDay) - $enc_obj->getStatsByDateNoiBHYT($currYear,$currMonth,$currDay,$cond);
        $smarty->assign('khamnoikbh',$khamnoikbh,$cond);
        $smarty->assign('nhi',$enc_obj->getStatsByDateNhi($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nhi6',$enc_obj->getStatsByDateNhi6($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nguoigia',$enc_obj->getStatsByDateGia($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nhapvien',$enc_obj->getStatsByDateInPatient($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nhapvienngoai',$enc_obj->getStatsByDateInPatientNgoai($currYear,$currMonth,$currDay));
        $smarty->assign('nhapviennoi',$enc_obj->getStatsByDateInPatientNoi($currYear,$currMonth,$currDay));
        $smarty->assign('nhapvienhscc',$enc_obj->getStatsByDateInPatientHSCC($currYear,$currMonth,$currDay));
        $smarty->assign('nhapvienyhct',$enc_obj->getStatsByDateInPatientYHCT($currYear,$currMonth,$currDay));
        $smarty->assign('nhapviensan',$enc_obj->getStatsByDateInPatientSan($currYear,$currMonth,$currDay));
        $smarty->assign('nhapviennhiem',$enc_obj->getStatsByDateInPatientNhiem($currYear,$currMonth,$currDay));
        $smarty->assign('cum',$enc_obj->getStatsByDateCum($currYear,$currMonth,$currDay));
        $smarty->assign('tieuchay',$enc_obj->getStatsByDateTieuChay($currYear,$currMonth,$currDay));
        $smarty->assign('pPrintOut','<a href="javascript:printOut('.$currYear.','.$currMonth.','.$currDay.')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="'.$LDPrintOut.'"  align="absmiddle"></a>');
    }else{
        $smarty->assign('tong',$enc_obj->getStatsByMonth($currYear,$currMonth));
        $smarty->assign('tongbhyt',$enc_obj->getStatsByMonthBHYT($currYear,$currMonth));
        $tongkbhyt=($enc_obj->getStatsByMonth($currYear,$currMonth))-($enc_obj->getStatsByMonthBHYT($currYear,$currMonth));
        $smarty->assign('tongkbhyt',$tongkbhyt);
        $smarty->assign('khamngoai',$enc_obj->getStatsByMonthNgoai($currYear,$currMonth));
        $smarty->assign('khamnoi',$enc_obj->getStatsByMonthNoi($currYear,$currMonth));
        $smarty->assign('khamngoaibh',$enc_obj->getStatsByMonthNgoaiBHYT($currYear,$currMonth));
        $smarty->assign('khamnoibh',$enc_obj->getStatsByMonthNoiBHYT($currYear,$currMonth));
        $khamngoaikbh=$enc_obj->getStatsByMonthNgoai($currYear,$currMonth) - $enc_obj->getStatsByMonthNgoaiBHYT($currYear,$currMonth);
        $smarty->assign('khamngoaikbh',$khamngoaikbh);
        $khamnoikbh=$enc_obj->getStatsByMonthNoi($currYear,$currMonth) - $enc_obj->getStatsByMonthNoiBHYT($currYear,$currMonth);
        $smarty->assign('khamnoikbh',$khamnoikbh);
        $smarty->assign('nhi',$enc_obj->getStatsByMonthNhi($currYear,$currMonth));
        $smarty->assign('nhi6',$enc_obj->getStatsByMonthNhi6($currYear,$currMonth));
        $smarty->assign('nguoigia',$enc_obj->getStatsByMonthGia($currYear,$currMonth));
        $smarty->assign('nhapvien',$enc_obj->getStatsByMonthInPatient($currYear,$currMonth,$cond));
        $smarty->assign('nhapvienngoai',$enc_obj->getStatsByMonthInPatientNgoai($currYear,$currMonth));
        $smarty->assign('nhapviennoi',$enc_obj->getStatsByMonthInPatientNoi($currYear,$currMonth));
        $smarty->assign('nhapvienhscc',$enc_obj->getStatsByMonthInPatientHSCC($currYear,$currMonth));
        $smarty->assign('nhapvienyhct',$enc_obj->getStatsByMonthInPatientYHCT($currYear,$currMonth));
        $smarty->assign('nhapviensan',$enc_obj->getStatsByMonthInPatientSan($currYear,$currMonth));
        $smarty->assign('nhapviennhiem',$enc_obj->getStatsByMonthInPatientNhiem($currYear,$currMonth));
        $smarty->assign('cum',$enc_obj->getStatsByMonthCum($currYear,$currMonth));
        $smarty->assign('tieuchay',$enc_obj->getStatsByMonthTieuChay($currYear,$currMonth));

        $smarty->assign('pPrintOut','<a href="javascript:printOut('.$currYear.','.$currMonth.',\' \')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="'.$LDPrintOut.'"  align="absmiddle"></a>');
               
    }
    $smarty->assign('sWeekLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_week.php'.URL_APPEND.'">Thống kê tuần</a>');
    $smarty->assign('sQuiLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_qui.php'.URL_APPEND.'">Thống kê quí</a>');
    $smarty->assign('sMainBlockIncludeFile','registration_admission/appt_list_1.tpl'); 
}else{
    $temp='<tr class="wardlisttitlerow">
                <td  background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Tổng Bệnh nhân&nbsp;</td>
                <td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">BHYT</td>
                <td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Không BHYT</td>
                <td background="../../gui/img/common/default/tableHeader_gr.gif" style="text-align:center;">Trẻ Sơ sinh</td>
        </tr>';
    $smarty->assign('tr',$temp);
    
    if($currDay){
        $smarty->assign('tong',$enc_obj->getStatsByDate($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('tongbhyt',$enc_obj->getStatsByDateBHYT($currYear,$currMonth,$currDay,$cond));
        $tongkbhyt=($enc_obj->getStatsByDate($currYear,$currMonth,$currDay))-($enc_obj->getStatsByDateBHYT($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('tongkbhyt',$tongkbhyt,$cond);
        
        $smarty->assign('nhi',$enc_obj->getStatsByDateSoSinh($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nhinoi',$enc_obj->getStatsByDateSoSinh_noi($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('nhingoai',$enc_obj->getStatsByDateSoSinh_ngoai($currYear,$currMonth,$currDay,$cond));
        
        $smarty->assign('khamngoai',$enc_obj->getStatsByDateNgoai($currYear,$currMonth,$currDay,$cond));
        $smarty->assign('khamngoaibh',$enc_obj->getStatsByDateNgoaiBHYT($currYear,$currMonth,$currDay,$cond));
        $khamngoaikbh=$enc_obj->getStatsByDateNgoai($currYear,$currMonth,$currDay,$cond) - $enc_obj->getStatsByDateNgoaiBHYT($currYear,$currMonth,$currDay,$cond);
        
        $smarty->assign('khamnoi',$enc_obj->getStatsByDateNoi($currYear,$currMonth,$currDay,$cond));        
        $smarty->assign('khamnoibh',$enc_obj->getStatsByDateNoiBHYT($currYear,$currMonth,$currDay,$cond));        
        $smarty->assign('khamngoaikbh',$khamngoaikbh);
        $khamnoikbh=$enc_obj->getStatsByDateNoi($currYear,$currMonth,$currDay,$cond) - $enc_obj->getStatsByDateNoiBHYT($currYear,$currMonth,$currDay,$cond);
        $smarty->assign('khamnoikbh',$khamnoikbh);    
        
        $smarty->assign('pPrintOut','<a href="javascript:printOut('.$currYear.','.$currMonth.','.$currDay.')"><img '.createLDImgSrc($root_path,'printout.gif','0').'  title="'.$LDPrintOut.'"  align="absmiddle"></a>');
    }
    $smarty->assign('sWeekLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_week.php'.URL_APPEND.'">Thống kê tuần</a>');
    $smarty->assign('sQuiLink','<img '.createComIcon($root_path,'varrow.gif','0').'> <a href="aufnahme_stats_qui.php'.URL_APPEND.'">Thống kê quí</a>');
    $smarty->assign('sMainBlockIncludeFile','registration_admission/appt_list_1.tpl'); 
}

# Assign page output to the mainframe template
$smarty->display('common/mainframe.tpl');
?>