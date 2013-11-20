<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
$lang_tables[]='departments.php';
define('LANG_FILE','doctors.php');
$local_user='ck_doctors_dienstplan_user';
if ($local_user='ck_doctors_dienstplan_user') define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

if(!isset($dept_nr)||!$dept_nr){
	header('Location:doctors-select-dept-luong.php'.URL_REDIRECT_APPEND.'&retpath='.$retpath);
	exit;
}

//$db->debug=1;

$thisfile=basename(__FILE__);
$breakfile="doctors-dienstplan-luong.php".URL_APPEND."&dept_nr=$dept_nr&pmonth=$pmonth&pyear=$pyear&retpath=$retpath";
$breakfile1=''.$root_path.'modules/timekeeping/tkp-func-mframe.php?ntid=false&lang=vi';
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_obj->preloadDept($dept_nr);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
$pers_obj->useLuongTable();
$doctors=$pers_obj->getDoctorsOfDept($dept_nr);
$pers=$pers_obj->getAllOfDept($dept_nr);
if(is_object($pers)){
$maxpers=$pers->RecordCount();
}
if ($pmonth=='') $pmonth=date('n');
if ($pyear=='') $pyear=date('Y');
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
/* Establish db connection */
if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');

if($dblink_ok)
	{	
		if($mode=='save')
		{ 
					$sql1="SELECT personell_nr from care_luong";
			$temp=$db->Execute($sql1);
			if($temp->RecordCount()){
		
			while($result=$temp->FetchRow()){
			if($result) $buf[]=$result['personell_nr'];
				
			}
			}
			$sql2="SELECT month from care_luong";
			$temp1=$db->Execute($sql2);
			if($temp1->RecordCount()){
	
			while($result1=$temp1->FetchRow()){
			if($result1) $buf1[]=$result1['month'];
					
			}
			
			}
			$sql3="SELECT year from care_luong";
			$temp2=$db->Execute($sql3);
			if($temp2->RecordCount()){
		
			while($result2=$temp2->FetchRow()){
			if($result2) $buf2[]=$result2['year'];
					
			}
			
			}
$data[][]=array();
			for($j=0;$j<($pers->RecordCount());$j++){
				
			 $data[$j]['personell_nr']=$_POST['p'.$j];

		
					$arr_1_txt=array();
					$arr_2_txt=array();
					
                                        //$maxelement=số ngày trong tháng đó
					
					$data[$j]['luong']=$_POST['luong'.$j];
					$data[$j]['heso_luong']=$_POST['hsl'.$j];					
					$data[$j]['heso_chucvu']=$_POST['hscv'.$j];
					$data[$j]['heso_dochai']=$_POST['hsdh'.$j];
					$data[$j]['year']=$pyear;
					$data[$j]['month']=$pmonth;
					$data[$j]['dept_nr']=$dept_nr;
					
					if((in_array($data[$j]['personell_nr'],$buf))&&(in_array($pmonth,$buf1)) &&(in_array($pyear,$buf2))){
					$data[$j]['history']="Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n";
					$pers_obj->setDataArray($data[$j]);
					$sql="UPDATE care_luong SET 
											dept_nr='".$dept_nr."',
											year='".$year."',
											month='".$month."',
											luong='".$data[$j]['luong']."',
											heso_luong='".$data[$j]['heso_luong']."',
											heso_chucvu='".$data[$j]['heso_chucvu']."',
											heso_dochai='".$data[$j]['heso_dochai']."',
											history='".$data[$j]['history']."'";
					
					if($pers_obj->transact($sql))
							
       							{
									//echo $sql." new update <br>";
									//
									//header("location:$thisfile?sid=$sid&saved=1&dept=$dept&pmonth=$pmonth&pyear=$pyear");
								}
								else
								{
									echo "$sql <p>";
									exit;
								}//end of else
					
					}else{
					$data[$j]['history']="Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n";
					$data[$j]['create_id']=$_SESSION['sess_user_name'];
					$pers_obj->setDataArray($data[$j]);
					if(!$pers_obj->insertDataFromInternalArray()) echo "<p>".$sql."<p>$LDDbNoSave"; 
					}
					
					
			}
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pers_obj->getLastQuery(), date('Y-m-d H:i:s'));
			header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=$dept_nr&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
				
		 }// end of if(mode==save)
		 else
		 {
		$rows=0; 
		if(is_object($per)){
				while( $result=$pers->FetchRow())
				{	
					if($result) $content[]=$result;
					 $rows++;
				}
				if($rows)
				{
					mysql_data_seek($pers,0);
					//echo $sql."<br>file found!";
				}
				for($j=0;$j<($pers->RecordCount());$j++){
				$sql="SELECT * FROM care_luong WHERE personell_nr='".$content[$j]['personell_nr']."' and year='".$pyear."' and month='".$pmonth."'";
				$temp=$db->Execute($sql);
				$temp->recordcount();
				$buf=$temp->fetchrow();
				$luong[$j]=$buf['luong'];
				}
				}
	 	}
}
  else { echo "$LDDbNoLink<br>"; } 


$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));

$firstday=date("w",mktime(0,0,0,$pmonth,1,$pyear));

function makefwdpath($path,$dpt,$mo,$yr,$saved)
{
	if ($path==1)
	{	
		$fwdpath='doctors-dienstplan-luong.php?';
		if($saved!="1") 
		{  
			if ($mo==1) {$mo=12; $yr--;}
				else $mo--;
		}
		return $fwdpath.'dept='.$dpt.'&pmonth='.$mo.'&pyear='.$yr;
	}
	else return "doctors-dienstplan-checkpoint.php";
}

# Prepare page title
 $sTitle = "$LDMakeLuong :: ";
 $LDvar=$dept_obj->LDvar();
 if(isset($$LDvar) && $$LDvar) $sTitle = $sTitle.$$LDvar;
   else $sTitle = $sTitle.$dept_obj->FormalName();

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
 $smarty->assign('pbHelp',"javascript:gethelp('docs_dutyplan_edit.php','$mode','$rows')");

# href for return button
 $smarty->assign('pbBack','javascript:history.back();killchild();');

 # href for close button
 $smarty->assign('breakfile',$breakfile1);

 # Body onLoad javascript
 //$smarty->assign('sOnLoadJs','onUnload="killchild()"');

 # Window bar title
 $smarty->assign('sWindowTitle',$sTitle);

 # Collect extra javascript

 ob_start();
?>

<script language="javascript">

  var urlholder;
  var infowinflag=0;



function calc(){

	a= Number(document.dienstplanl['luong_co_ban'].value);
	
	b= Number(document.dienstplanl['tru_bhxh'].value);
	var n= document.getElementById('maxpers').value;

	var i;
	for(i=0;i<n;i++){
		c= Number(document.dienstplanl['hsl'+i].value);
		d= Number(document.dienstplanl['hscv'+i].value);
		e= Number(document.dienstplanl['hsdh'+i].value);
		g= Number(document.dienstplanl['tlud'+i].value)
		f=(c+d+e+(g*(c+d)))*a-(c+d)*a*b;
		document.getElementById('luong'+i).value=Math.floor(f);
	}
}

function addelem(elem,str)
{
   
      document.getElementById(elem).value=str;
	
  
}
function killchild()
{
 if (window.popselectwin) if(!window.popselectwin.closed) window.popselectwin.close();
}

function cal_update()
{
	var filename="dienstplan-planen-luong.php?<?php echo "sid=$sid&lang=$lang" ?>&retpath=<?php echo $retpath ?>&dept_nr=<?php echo $dept_nr; ?>&pmonth="+document.dienstplanl.month.value+"&pyear="+document.dienstplanl.jahr.value;
	window.location.replace(filename);
}

</script>

<?php 

 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->append('JavaScript',$sTemp);

 // $smarty->assign('LDSang',$LDSang);
 //$smarty->assign('LDChieu',$LDChieu);

# Prepare the date selectors
//$smarty->assign('LDMonth',$LDMonth);
//$sBuffer = '<select name="month" size="1" onChange="cal_update()">';
/*
for ($i=1;$i<13;$i++){
	 $sBuffer = $sBuffer.'<option  value="'.$i.'" ';
	 if (($pmonth)==$i)  $sBuffer = $sBuffer.'selected';
	  $sBuffer = $sBuffer.'>'.$monat[$i].'</option>';
	  $sBuffer = $sBuffer."\n";
}
$sBuffer = $sBuffer.'</select>';
$smarty->assign('sMonthSelect',$sBuffer);

$smarty->assign('LDYear',$LDYear);
$sBuffer = '<select name="jahr" size="1" onChange="cal_update()">';

for ($i=(date(Y)-11);$i<(date(Y)+100);$i++){
	 $sBuffer = $sBuffer.'<option  value="'.$i.'" ';
	 if ($pyear==$i) $sBuffer = $sBuffer.'selected';
	 $sBuffer = $sBuffer.'>'.$i.'</option>';
  	 $sBuffer = $sBuffer."\n";
}
$sBuffer = $sBuffer.'</select>';
$smarty->assign('sYearSelect',$sBuffer);

$smarty->assign('sFormAction','action="doctors-dienstplan-planen-chamcong.php"');
*/
 # collect hidden inputs

 ob_start();
?> 
</HEAD>


<BODY bgcolor="<?php echo $cfg['body_bgcolor'];?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 <?php if (!$cfg['dhtml']){ echo ' link='.$cfg['body_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['body_txtcolor']; } ?>>
<form name="dienstplanl" action="dienstplan-planen-luong.php?sid=<?php echo $sid?>&lang=<?php echo$lang?>&saved=1&dept_nr=<?php echo$dept_nr?>&pyear=<?php echo$pyear?>&pmonth=<?php echo$pmonth?>&retpath=<?php echo$retpath?>" method="post">
<table width=100% border=0 cellspacing="0" cellpadding=0>


<ul>
<font size=4>
<?php 
if(is_object($pers) && $pers->RecordCount()){
echo $LDMonth ;
echo '<select name="month" size="1" onChange="cal_update()">';
for ($i=1;$i<13;$i++){
	 $sBuffer = $sBuffer.'<option  value="'.$i.'" ';
	 if (($pmonth)==$i)  $sBuffer = $sBuffer.'selected';
	  $sBuffer = $sBuffer.'>'.$monat[$i].'</option>';
	  $sBuffer = $sBuffer."\n";
}
$sBuffer = $sBuffer.'</select>';
echo $sBuffer;
echo '&nbsp'; 
echo $LDYear; 
echo'<select name="jahr" size="1" onChange="cal_update()">';
for ($i=(date(Y)-11);$i<(date(Y)+100);$i++){
	 $sBuffer1 = $sBuffer1.'<option  value="'.$i.'" ';
	 if ($pyear==$i) $sBuffer1 = $sBuffer1.'selected';
	 $sBuffer1 = $sBuffer1.'>'.$i.'</option>';
  	 $sBuffer1 = $sBuffer1."\n";
}
$sBuffer1 = $sBuffer1.'</select>';
echo $sBuffer1;
?>
</font>
<table border="0">
  <tbody>
    <tr>
      <td  valign="top">
        
		<table border=0 cellpadding=0 cellspacing=1 width="100%" class="frame">
        <tbody>
      <tr> 
		 <td class="adm_input" style="white-space:nowrap;" >Lương cơ bản</td>
		 <td class="adm_input" ><input type="text" name="luong_co_ban" id="luong_co_ban" onBlur="javascript:calc()" value="830000"></td>
		 <td class="adm_input" style="white-space:nowrap;" >Trừ BHXH</td>
		 <td class="adm_input" ><input name="tru_bhxh" id="tru_bhxh" value="0.085" onBlur="javascript:calc()"></td>
		 <td colspan=3></td>
		
</tr>   
<tr> 
		 <td class="adm_input" style="white-space:nowrap;" >Họ & tên</td>
		 <td class="weekday">Chuyên môn</td>
		<td class="weekday">Hệ số lương</td>
		<td class="weekday">Phụ cấp chức vụ</td>
		<td class="weekday">Phụ cấp độc hại</td>
		<td class="weekday">Tỉ lệ ưu đãi</td>
		<td class="weekday">Lương</td>
</tr>
<?php } ?>
<?php
if(is_object($pers) && $pers->RecordCount()){

$rows=0; 
				while( $result=$pers->FetchRow())
				{	
					if($result) $content[]=$result;
					 $rows++;
				}
				if($rows)
				{
					mysql_data_seek($pers,0);
					//echo $sql."<br>file found!";
				}
				
for($j=0;$j<($pers->RecordCount());$j++){

echo'<tr>';
echo'<td class="adm_item" style="white-space:nowrap;">'.$content[$j]['name_last'].' '.$content[$j]['name_first'].'';
echo '<input type="hidden" value="'.$content[$j]['personell_nr'].'" name="p'.$j.'"></td>';

	echo '<td class="weekday">'.$pers_obj->getNameJobFunction($content[$j]['job_function_title']).'</td>';
$sql="SELECT * FROM care_personell WHERE nr='".$content[$j]['personell_nr']."'";
$temp=$db->Execute($sql);
$temp->RecordCount();
$buf=$temp->FetchRow();
echo '<td class="weekday" ><input type="text"  name="hsl'.$j.'" id="hsl'.$j.'" value="'.$buf['salary_grading'].'" readonly></td>';
echo '<td class="weekday" ><input type="text"  name="hscv'.$j.'" id="hscv'.$j.'" value="'.$buf['heso_chucvu'].'" readonly></td>';
echo '<td class="weekday"><input type="text"  name="hsdh'.$j.'"  id="hsdh'.$j.'" value="'.$buf['heso_dochai'].'" readonly></td>';
echo '<td class="weekday"><input type="text"  name="tlud'.$j.'"  id="tlud'.$j.'" value="'.$buf['tlud'].'" readonly></td>';
if(!empty($luong[$j])){
echo '<td class="weekday" ><input  name="luong'.$j.'" id="luong'.$j.'" style="border:1px;" value="'.$luong[$j].'"></td>';
}else{
$luong=($buf['salary_grading']+$buf['heso_chucvu']+$buf['heso_dochai']+$buf['tlud']*($buf['salary_grading']+$buf['heso_chucvu']))*830000-830000*0.085*($buf['salary_grading']+$buf['heso_chucvu']);
echo '<td class="weekday" ><input  name="luong'.$j.'" id="luong'.$j.'" style="border:1px;" value="'.$luong.'"></td>';
}
echo'</tr>';

}

?>
		</tbody>
     </table>
 </td>
      <td valign="top">
	  <?php echo '<input type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').' >' ?>
	  <p>
	  <?php 
	  if($saved) $sBuffer = createLDImgSrc($root_path,'close2.gif','0');
 	else $sBuffer = createLDImgSrc($root_path,'cancel.gif','0');
	  echo '<a href="'.$breakfile.'" onUnload="killchild()"><img '.$sBuffer.' alt="'.$LDClosePlan.'"></a>' ?>
	  </td>
   </tr>
    <tr>
      <td ><?php echo '<input type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').'">';
	  echo'&nbsp;&nbsp;&nbsp;';
	  echo '<a href="'.$breakfile.'" onUnload="killchild()"><img '.$sBuffer.' alt="'.$LDClosePlan.'"></a>';?> </td>
      <td>&nbsp;</td>
    </tr>  
  </tbody>
</table>
<?php } else{
 echo '<tr>
      <td><img '.createMascot($root_path,'mascot1_r.gif','0','left').'  ></td>
      <td><font face="verdana,arial" size=3><b>'.$LDNoPersonList.'</b></td>
    </tr></tbody>
</table>';

} ?>
</ul>
<input type="hidden" name="mode" value="save">
<input type="hidden" name="dept" value="<?php echo $dept_obj->ID(); ?>">
<input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
<input type="hidden" name="pmonth" value="<?php echo $pmonth; ?>">
<input type="hidden" name="pyear" value="<?php echo $pyear; ?>">
<input type="hidden" name="planid" value="<?php echo $ck_plan; ?>">
<input type="hidden" name="maxelement" value="<?php echo $maxdays; ?>">
<input type="hidden" name="maxpers" id="maxpers" value="<?php echo $maxpers?>" >
<input type="hidden" name="encoder" value="<?php echo $ck_doctors_dienstplan_user; ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath; ?>">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="sid" value="<?php echo $sid; ?>">

</table>
</form>
<?php
/*
 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->assign('sHiddenInputs',$sTemp);

 if($saved) $sBuffer = createLDImgSrc($root_path,'close2.gif','0');
 	else $sBuffer = createLDImgSrc($root_path,'cancel.gif','0');

 # Assign control links
$smarty->assign('sSave','<input type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').'">');
$smarty->assign('sClose',"<a href=\"$breakfile\" onUnload=\"killchild()\"><img ".$sBuffer." alt=\"$LDClosePlan\"></a>");

$stemp2='';
if(is_object($doctors) && $doctors->RecordCount()){

while($row=$doctors->FetchRow()){
$smarty->assign('name',$row['name_last'].' '.$row['name_first']);
$sTemp='';
$sTemp1='';
$sTemp3='';
for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
{
	switch ($wd){
		//case 6: $backcolor="bgcolor=#ffffcc";break;
		//case 0: $backcolor="bgcolor=#ffff00";break;
		//default: $backcolor="bgcolor=white";
		case 6: $smarty->assign('sRowClass','class="saturday"');break;
		case 0: $smarty->assign('sRowClass','class="sunday"');break;
		default: $smarty->assign('sRowClass','class="weekday"');
		}

	$smarty->assign('iDayNr',$i);
	$smarty->assign('LDShortDay',$LDShortDay[$wd]);        
	$smarty->assign('sInput1','<input type="checkbox" name="ha'.$n.'" value="'.$a_pnr['ha'.$n].'">');
	//if ($aelems['a'.$n]=="") $smarty->assign('sIcon1','<img '.createComIcon($root_path,'warn.gif','0').'>');
       // else $smarty->assign('sIcon1','<img '.createComIcon($root_path,'mans-gr.gif','0').'>');
      //  $smarty->assign('sInput1','<input type="hidden" name="ha'.$n.'" value="'.$a_pnr['ha'.$n].'">
              //  <input type="text" name="a'.$n.'" size="50" onFocus=this.select() value="'.$aelems['a'.$n].'">');
        //$n là mã của các ngày trong tháng từ 0-31
	//$smarty->assign('sPopWin1','<a href="javascript:popselect(\''.$n.'\',\'a\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\',\''.$r_pnr['hr'.$n].'\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\')">
        //        <button onclick="javascript:popselect(\''.$n.'\',\'a\',\''.$n.'\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\',\''.$r_pnr['hr'.$n].'\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\')"><img '.createComIcon($root_path,'patdata.gif','0').' alt="'.$LDClk2Plan.'"></button></a>');
        
	if ($relems['r'.$n]=="") $smarty->assign('sIcon2','<img '.createComIcon($root_path,'warn.gif','0').'>');
		else $smarty->assign('sIcon2','<img '.createComIcon($root_path,'mans-red.gif','0').'>');
	$smarty->assign('sInput2','<input type="hidden" name="hr'.$n.'" value="'.$r_pnr['hr'.$n].'">
                <input type="text" name="r'.$n.'" size="50"onFocus=this.select() value="'.$relems['r'.$n].'">');
	$smarty->assign('sPopWin2','<a href="javascript:popselect(\''.$n.'\',\'r\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\',\''.$a_pnr['ha'.$n].'\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\')">
                <button onclick="javascript:popselect(\''.$n.'\',\'r\',\''.$r_pnr['hr'.($n-1)].'\',\''.$r_pnr['hr'.($n+1)].'\',\''.$a_pnr['ha'.$n].'\',\''.$a_pnr['ha'.($n-1)].'\',\''.$a_pnr['ha'.($n+1)].'\')"><img '.createComIcon($root_path,'patdata.gif','0').' alt="'.$LDClk2Plan.'"></button></a>');
        if($wd==6) $wd=-1;
	
	# Buffer each row and collect to a string
	
	ob_start();
		$smarty->display('common/duty_plan_entry_row-1.tpl');
		$sTemp = $sTemp.ob_get_contents();
	ob_end_clean();
	ob_start();
		$smarty->display('common/duty_plan_entry_row-2.tpl');
		$sTemp1 = $sTemp1.ob_get_contents();
	ob_end_clean();
	ob_start();
		$smarty->display('common/input.tpl');
		$sTemp3 = $sTemp3.ob_get_contents();
	ob_end_clean();
}
$smarty->assign('sInput',$sTemp3);
ob_start();
		$smarty->display('common/name.tpl');
	$sTemp2.= $sTemp3;	$sTemp2 = $sTemp2.ob_get_contents();
		
	ob_end_clean();
}
}
$smarty->assign('sName',$sTemp2);
# Assign the duty entry rows to the subframe template

 $smarty->assign('sDutyRows1',$sTemp);
$smarty->assign('sDutyRows2',$sTemp1);

$smarty->assign('sMainBlockIncludeFile','common/duty_plan_entry_frame-1.tpl');

 $smarty->display('common/mainframe.tpl');
*/
?>
<?php

 $sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);

 $smarty->display('common/mainframe.tpl');

?>