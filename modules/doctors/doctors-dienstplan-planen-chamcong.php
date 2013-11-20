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
	header('Location:doctors-select-dept-chamcong.php'.URL_REDIRECT_APPEND.'&retpath='.$retpath);
	exit;
}

//$db->debug=1;

$thisfile=basename(__FILE__);
$breakfile="doctors-dienstplan-chamcong.php".URL_APPEND."&dept_nr=$dept_nr&pmonth=$pmonth&pyear=$pyear&retpath=$retpath";
$breakfile1=''.$root_path.'modules/timekeeping/tkp-func-mframe.php?ntid=false&lang=vi';
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_obj->preloadDept($dept_nr);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
$pers_obj->useChamcongTable();
$doctors=$pers_obj->getAllPersonellOfDept($dept_nr);
require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
if ($pmonth=='') $pmonth=date('n');
if ($pyear=='') $pyear=date('Y');

/* Establish db connection */
if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');

if($dblink_ok)
	{	
		if($mode=='save')
		{ 
		
		
			$sql1="SELECT personell_nr from care_chamcong where dept_nr='".$dept_nr."'";
			$temp=$db->Execute($sql1);
			if($temp->RecordCount()){		
				while($result=$temp->FetchRow()){
					if($result) $buf[]=$result['personell_nr'];			
				}			
			}
		
			$sql2="SELECT month from care_chamcong where dept_nr='".$dept_nr."'";
			$temp1=$db->Execute($sql2);
			if($temp1->RecordCount()){		
				while($result1=$temp1->FetchRow()){
					if($result1) $buf1[]=$result1['month'];			
				}			
			}		
			$sql3="SELECT year from care_chamcong where dept_nr='".$dept_nr."'";
			$temp2=$db->Execute($sql3);
			if($temp2->RecordCount()){		
				while($result2=$temp2->FetchRow()){
					if($result2) $buf2[]=$result2['year'];			
				}			
			}	
            $data[][]=array();			
			for($j=0;$j<($doctors->RecordCount());$j++){
				
			 $data[$j]['personell_nr']=$_POST['p'.$j];
			
		
					$arr_1_txt=array();
					$arr_2_txt=array();
					
                                       
					for($i=0;$i<$maxelement;$i++)
					{
						
						$ax="a".$j."_".$i;//trực suốt
					
						$rx="r".$j."_".$i;//trực sẵn sàng
						
						if(!empty($$ax)) $arr_1_txt[$ax]=$_POST[$ax];
						//var_dump($arr_1_txt[$ax]);
						if(!empty($$rx)) $arr_2_txt[$rx]=$_POST[$rx];
						
						$data[$j]['chamcong_1_txt']=serialize($arr_1_txt);
						
					$data[$j]['chamcong_2_txt']=serialize($arr_2_txt);
					}
					$data[$j]['dept_nr']=$dept_nr;
					$data[$j]['month']=$pmonth;
					$data[$j]['year']=$pyear;
					$data[$j]['role_nr']=15;
					$data[$j]['modify_id']=$_SESSION['sess_user_name'];
			
					if((in_array($data[$j]['personell_nr'],$buf))&&(in_array($pmonth,$buf1)) &&(in_array($pyear,$buf2)))
					{
					$data[$j]['history']="Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n";
					$sql4="select nr from care_chamcong where personell_nr='".$data[$j]['personell_nr']."' and year='".$pyear."' and month='".$pmonth."'";
					$temp4=$db->Execute($sql4);
					$temp4->Recordcount();
					$buf4=$temp4->fetchrow();
					$data[$j]['nr']=$buf4['nr'];
					$pers_obj->setDataArray($data[$j]);
							if($pers_obj->updateDataFromInternalArray($data[$j]['nr']))
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
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, str_replace("\"","\'",$pers_obj->getLastQuery()), date('Y-m-d H:i:s'));
			header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=$dept_nr&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
				/*	$arr_1_txt=array();
					$arr_2_txt=array();
					$arr_1_pnr=array();
					$arr_2_pnr=array();
                                        //$maxelement=số ngày trong tháng đó
					for($i=0;$i<$maxelement;$i++)
					{
						$tdx="ha".$i;//Mã những người trực suốt
						$ddx="hr".$i;//Mã những người trực sẵn sàng
						$ax="a".$i;//trực suốt
						$rx="r".$i;//trực sẵn sàng
						
						if(!empty($$ax)) $arr_1_txt[$ax]=$$ax;
						if(!empty($$rx)) $arr_2_txt[$rx]=$$rx;
						if(!empty($$tdx)) $arr_1_pnr[$tdx]=$$tdx;
						if(!empty($$ddx)) $arr_2_pnr[$ddx]=$$ddx;
						
					}
					
					$ref_buffer=array();
					// Serialize the data
					$ref_buffer['chamcong_1_txt']=serialize($arr_1_txt);
					$ref_buffer['chamcong_2_txt']=serialize($arr_2_txt);
					$ref_buffer['chamcong_1_pnr']=serialize($arr_1_pnr);
					$ref_buffer['chamcong_2_pnr']=serialize($arr_2_pnr);
					$ref_buffer['personell_nr']=$row['personell_nr'];
					$ref_buffer['dept_nr']=$dept_nr;
					$ref_buffer['role_nr']=15;//bác sĩ trực sẵn sàng
					$ref_buffer['year']=$pyear;
					$ref_buffer['month']=$pmonth;
					$ref_buffer['modify_id']=$_SESSION['sess_user_name'];

					if($chamcong_nr=$pers_obj->DChamcongExists($dept_nr,$pyear,$pmonth)){
						$ref_buffer['history']=$pers_obj->ConcatHistory("Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n");
						$ref_buffer['modify_time']=date('YmdHis');
						// Point to the internal data array
						$pers_obj->setDataArray($ref_buffer);
                                                //$dpoc_nr= mã nhân viên
						if($pers_obj->updateDataFromInternalArray($chamcong_nr)){

							# Remove the cache plan
							if(date('Yn')=="$pyear$pmonth"){
								$pers_obj->deleteDBCache('DOCS_'.date('Y-m-d'));
							}
							header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=$dept_nr&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
							exit;
						}else echo "<p>".$pers_obj->getLastQuery."<p>$LDDbNoSave"; 
					} // else create new entry
					else
					{
						$ref_buffer['history']="Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n";
						$ref_buffer['create_id']=$_SESSION['sess_user_name'];
						$ref_buffer['create_time']=date('YmdHis');
						// Point to the internal data array
						$pers_obj->setDataArray($ref_buffer);

						//echo "create";

							if($pers_obj->insertDataFromInternalArray()){
								# Remove the cache plan
								if(date('Yn')=="$pyear$pmonth"){
									$pers_obj->deleteDBCache('DOCS_'.date('Y-m-d'));
								}
								header("location:$thisfile?sid=$sid&lang=$lang&saved=1&dept_nr=$dept_nr&pyear=$pyear&pmonth=$pmonth&retpath=$retpath");
								exit;
							}else{
								echo "<p>".$pers_obj->getLastQuery."<p>$LDDbNoSave";
							} 
					}//end of else
			*/
		 }// end of if(mode==save)
		 else
		 { 
			$rows=0; 
				if(is_object($doctors))
				{
					while( $result=$doctors->FetchRow())
					{	
						if($result) $content[]=$result;
						 $rows++;
					}
					
				for($j=0;$j<($doctors->RecordCount());$j++){
					$sql="SELECT * FROM care_chamcong WHERE personell_nr='".$content[$j]['personell_nr']."' and year='".$pyear."' and month='".$pmonth."'";
					
					$temp=$db->Execute($sql);
					$temp->RecordCount();
					$dutyplan=$temp->FetchRow();
					$aelems[$j]=unserialize($dutyplan['chamcong_1_txt']);
					//var_dump($aelems[$j]);
					$relems[$j]=unserialize($dutyplan['chamcong_2_txt']);
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
		$fwdpath='doctors-dienstplan-chamcong.php?';
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
 $sTitle = "$LDMakeChamcong :: ";
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

function popselect(elem,mode,nr_t,nr_d,nr_s,nr_s_t,nr_s_d)
{
    w=window.screen.width;
    h=window.screen.height;
    ww=700;
    wh=500;
    var tmonth=document.dienstplancc.month.value;
    var tyear=document.dienstplancc.jahr.value;
    //nếu thếm $sid sẽ bị lỗi URI quá dài
    urlholder="doctors-dienstplan-poppersonselect-chamcong.php?elemid="+elem + "&dept_nr=<?php echo $dept_nr ?>&month="+tmonth+"&year="+tyear+ "&mode=" + mode+ "&nr_t="+ nr_t + "&nr_d="+ nr_d + "&nr_s="+ nr_s + "&nr_s_t="+ nr_s_t + "&nr_s_d="+ nr_s_d + "&retpath=qview<?php echo "&lang=$lang"; ?>";
    popselectwin=window.open(urlholder,"pop","width=" + ww + ",height=" + wh + ",menubar=no,resizable=yes,scrollbars=yes,dependent=yes");
    window.popselectwin.moveTo((w/2)+80,(h/2)-(wh/2));
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
	var filename="doctors-dienstplan-planen-chamcong.php?<?php echo "sid=$sid&lang=$lang" ?>&retpath=<?php echo $retpath ?>&dept_nr=<?php echo $dept_nr; ?>&pmonth="+document.dienstplancc.month.value+"&pyear="+document.dienstplancc.jahr.value;
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
<table width=100% border=0 cellspacing="0" cellpadding=0>

<form name="dienstplancc" action="doctors-dienstplan-planen-chamcong.php?sid=<?php echo $sid?>&lang=<?php echo$lang?>&saved=1&dept_nr=<?php echo$dept_nr?>&pyear=<?php echo$pyear?>&pmonth=<?php echo$pmonth?>&retpath=<?php echo$retpath?>" method="post">
<ul>
<font size=4>
<?php
if(is_object($doctors) && $doctors->RecordCount()){
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
}
?>
</font>
<table border="0">
  <tbody>
    <tr>
      <td  valign="top">
   
		<table border=0 cellpadding=0 cellspacing=1 width="100%" class="frame">
        <tbody>
		<?php     if(is_object($doctors) && $doctors->RecordCount()){ ?>
          <tr> 
		 <td class="adm_input"  style="text-align:right;white-space:nowrap;" >Ngày&nbsp;</td>
		 <?php for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
{    
	switch ($wd){
		//case 6: $backcolor="bgcolor=#ffffcc";break;
		//case 0: $backcolor="bgcolor=#ffff00";break;
		//default: $backcolor="bgcolor=white";
		case 6: $class="saturday";break;
		case 0: $class="sunday";break;
		default: $class="weekday";
		}

		echo '<td class="'.$class.'" >'.$i.'</td>';
		if($wd==6) $wd=-1;
	
}?>
</tr>
<tr> 
		 <td class="adm_input" style="white-space:nowrap;" >Họ & tên</td>
		 <?php for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
{    
	switch ($wd){
		//case 6: $backcolor="bgcolor=#ffffcc";break;
		//case 0: $backcolor="bgcolor=#ffff00";break;
		//default: $backcolor="bgcolor=white";
		case 6: $class="saturday";break;
		case 0: $class="sunday";break;
		default: $class="weekday";
		}

		echo '<td class="'.$class.'">'.$LDShortDay[$wd].'</td>';
		if($wd==6) $wd=-1;
	
}?>
</tr>

<?php


$rows=0; 
				while( $result=$doctors->FetchRow())
				{	
					if($result) $content[]=$result;
					 $rows++;
				}
				if($rows)
				{
					mysql_data_seek($doctors,0);
					//echo $sql."<br>file found!";
				}
				
for($j=0;$j<($doctors->RecordCount());$j++){

echo'<tr>';
echo'<td class="adm_item" style="white-space:nowrap;">'.$content[$j]['name_last'].' '.$content[$j]['name_first'].'';
echo '<input type="hidden" value="'.$content[$j]['personell_nr'].'" name="p'.$j.'"></td>';
for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++)
{  
$pday=date('d');$pday=$pday-1;
if(($n<=6)&& ($n!=$pday)){$class="week1";}
	elseif(($n>6)&&($n<=13)&& ($n!=$pday)){$class="week2";}
	elseif(($n>13)&&($n<=20)&& ($n!=$pday)){$class="week3";}
	elseif(($n>20)&&($n<=27)&& ($n!=$pday)){$class="week4";}
	elseif(($n>27)){$class="week1";}
	else{$class="now";}
	
echo '<td class="'.$class.'" >		
				Sáng<select name="a'.$j.'_'.$n.'"><option value="0"></option>';
	$typecc=$pers_obj->getTypeChamcong();

while($result=$typecc->FetchRow()){

if($aelems[$j]['a'.$j.'_'.$n]==$result['nr']) 
{$selected='selected';}
else{$selected=' ';	}
echo '<option value="'.$result['nr'].'" '.$selected.' >'.$result['name'].'</option>
						';}
	echo'	</select>';
				
echo'				Chiều<select name="r'.$j.'_'.$n.'"><option value="0"></option>';
$typecc=$pers_obj->getTypeChamcong();
				while($result=$typecc->FetchRow()){
if($result['nr']==$relems[$j]['r'.$j.'_'.$n]){ $selected=' selected ';}else{$selected=' ';}
echo '<option value="'.$result['nr'].'" '.$selected.' >'.$result['name'].'</option>
						';}
	echo'	</select>';
				
	echo		'</td>';		
}
echo'</tr>';

}

	echo'	</tbody>
     </table>
 </td>
      <td valign="top">';
	   echo '<input type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').' >';
	 echo' <p>';
	 
	  if($saved) $sBuffer = createLDImgSrc($root_path,'close2.gif','0');
 	else $sBuffer = createLDImgSrc($root_path,'cancel.gif','0');
	  echo '<a href="'.$breakfile.'" onUnload="killchild()"><img '.$sBuffer.' alt="'.$LDClosePlan.'"></a>';
	echo'  </td>
   </tr>
    <tr>
      <td >';
	  echo '<input type="image" '.createLDImgSrc($root_path,'savedisc.gif','0').'">';
	  echo'&nbsp;&nbsp;&nbsp;';
	  echo '<a href="'.$breakfile.'" onUnload="killchild()"><img '.$sBuffer.' alt="'.$LDClosePlan.'"></a>';
	  echo'</td>
      <td>&nbsp;</td>
    </tr>  
	<tr>
      <td > Chú thích : <br>&nbsp;&nbsp;X - Có mặt <br> &nbsp;&nbsp;CT - Công tác <br> &nbsp;&nbsp;TR - Trực <br> &nbsp;&nbsp;NG - Ngoài giờ <br> &nbsp;&nbsp;P - Phép <br> &nbsp;&nbsp;B - Bù <br> &nbsp;&nbsp;HS - Hậu sản  
		<br>&nbsp;&nbsp;NK - Nghĩ khác <br>&nbsp;&nbsp;KL - Nghĩ không hưởng lương <br>&nbsp;&nbsp;ĐH - Đi học <br> &nbsp;&nbspO - Ốm<br> &nbsp;&nbspCO - Con Ốm;
	  </td>
    </tr>  
  </tbody>
</table>';
} else{

 echo '<tr>
      <td><img '.createMascot($root_path,'mascot1_r.gif','0','left').'  ></td>
      <td><font face="verdana,arial" size=3><b>'.$LDNoPersonList.'</b></td>
    </tr></tbody>
</table>';

}
?>
</ul>
<input type="hidden" name="mode" value="save">
<input type="hidden" name="dept" value="<?php echo $dept_obj->ID(); ?>">
<input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
<input type="hidden" name="pmonth" value="<?php echo $pmonth; ?>">
<input type="hidden" name="pyear" value="<?php echo $pyear; ?>">
<input type="hidden" name="planid" value="<?php echo $ck_plan; ?>">
<input type="hidden" name="maxelement" value="<?php echo $maxdays; ?>">
<input type="hidden" name="maxdoc" value="<?php echo $doctors->RecordCount(); ?>">
<input type="hidden" name="encoder" value="<?php echo $ck_doctors_dienstplan_user; ?>">
<input type="hidden" name="retpath" value="<?php echo $retpath; ?>">
<input type="hidden" name="lang" value="<?php echo $lang; ?>">
<input type="hidden" name="sid" value="<?php echo $sid; ?>">
</form>
</table>
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