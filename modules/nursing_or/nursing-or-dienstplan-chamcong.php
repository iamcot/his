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
$lang_tables[]='or.php';
$lang_tables[]='departments.php';
define('LANG_FILE','doctors.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

setcookie(username,"");
setcookie(ck_plan,"1");
if($dept=="") $dept="plast";
if($pmonth=="") $pmonth=date('n');
if($pyear=="") $pyear=date('Y');
$thisfile=basename(__FILE__);

require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department;
$dept_obj->preloadDept($dept_nr);

require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
$dutyplan=&$pers_obj->getNChamcong($dept_nr,$pyear,$pmonth);
$nurses=$pers_obj->getNursesOfDept($dept_nr);

$firstday=date("w",mktime(0,0,0,$pmonth,1,$pyear));

$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));


switch($retpath)
{
	case "menu": $rettarget=$root_path.'main/op-doku.php'.URL_APPEND; break;
	case "qview": $rettarget='nursing-or-shift-fastview-chamcong.php'.URL_APPEND.'&hilitedept='.$dept_nr; break;
	default: $rettarget="javascript:window.history.back()";
}

# Prepare page title
 $sTitle = "$LDORNOC::$LDChamcong::";
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
 $smarty->assign('pbHelp',"javascript:gethelp('op_duty.php','show','$rows')");

 # href for close button
 $smarty->assign('breakfile',$rettarget);

 # Window bar title
 $smarty->assign('sWindowTitle',$sTitle);

 # Collect extra javascript

 ob_start();

?>

<script language="javascript">

  var urlholder;
  var infowinflag=0;
 function printOut()
    {
            urlholder="<?php echo $root_path ?>modules/pdfmaker/chamcong/chamcong_nurse.php<?php echo URL_APPEND ?>&dept_nr=<?php echo $dept_nr ?>&pmonth=<?php echo $pmonth ?>&pyear=<?php echo $pyear ?>";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
            //testprintout<?php echo $sid ?>.print();
    }
function popinfo(l)
{
	w=window.screen.width;
	h=window.screen.height;
	ww=400;
	wh=400;
	urlholder="nursing-or-dienstplan-popinfo.php<?php echo URL_REDIRECT_APPEND ?>&nr="+l+"&dept_nr=<?php echo $dept_nr ?>&route=validroute&user=<?php echo $aufnahme_user.'"' ?>;
	
	infowin<?php echo $sid ?>=window.open(urlholder,"infowin<?php echo $sid ?>","width=" + ww + ",height=" + wh +",menubar=no,resizable=yes,scrollbars=yes");
	window.infowin<?php echo $sid ?>.moveTo((w/2)+20,(h/2)-(wh/2));

}
</script>

<?php 

 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->append('JavaScript',$sTemp);
/*
 $smarty->assign('LDSang',$LDSang);
 $smarty->assign('LDChieu',$LDChieu);

# Prepare the month links
# Previous month
$sBuffer = '<a href="'.$thisfile.URL_APPEND.'&retpath='.$retpath.'&dept_nr='.$dept_nr.'&pmonth=';

if ($pmonth==1) $sBuffer = $sBuffer.'12'.'&pyear='.($pyear-1).'">';
	else $sBuffer = $sBuffer.($pmonth-1).'&pyear='.$pyear.'">';
if ($pmonth==1) $sBuffer = $sBuffer.$monat[12];
	else $sBuffer = $sBuffer.$monat[$pmonth-1];
 $smarty->assign('sPrevMonth',$sBuffer.'</a>');

 # This month
$smarty->assign('sThisMonth',ucfirst($monat[$pmonth]).'&nbsp;&nbsp;'.$pyear);

# Next month
$sBuffer ='<a href="'.$thisfile.URL_APPEND.'&retpath='.$retpath.'&dept_nr='.$dept_nr.'&pmonth=';
if ($pmonth==12) $sBuffer = $sBuffer.'1'.'&pyear='.($pyear+1).'">';
	else $sBuffer = $sBuffer.($pmonth+1).'&pyear='.$pyear.'">';
if ($pmonth==12) $sBuffer = $sBuffer.$monat[1];
	else $sBuffer = $sBuffer.$monat[$pmonth+1];

$smarty->assign('sNextMonth',$sBuffer.'</a>');
 
# Assign control links
$smarty->assign('sNewPlan',"<a href=\"nursing-or-main-pass.php".URL_APPEND."&target=chamcong&dept_nr=$dept_nr&pmonth=$pmonth&pyear=$pyear&retpath=$retpath\"><img ".createLDImgSrc($root_path,'newplan.gif','0')."  alt=\"$LDNewPlan\"></a>");
$smarty->assign('sCancel',"<a href=\"$rettarget\"><img ".createLDImgSrc($root_path,'close2.gif','0')." alt=\"$LDClosePlan\"></a>");

 # Buffer form
*/
 ob_start();
 
/*

for ($i=1,$n=0,$wd=$firstday;$i<=$maxdays;$i++,$n++,$wd++){
	//$wd=weekday($i,$pmonth,$pyear);
	switch ($wd){
		//case 6: $backcolor="bgcolor=#ffffcc";break;
		//case 0: $backcolor="bgcolor=#ffff00";break;
		//default: $backcolor="bgcolor=white";
		case 6: $backcolor='class="saturday"';break;
		case 0: $backcolor='class="sunday"';break;
		default: $backcolor='class="weekday"';
	}
	
	$aelems=unserialize($dutyplan['chamcong_1_txt']);
	$relems=unserialize($dutyplan['chamcong_2_txt']);
	$a_pnr=unserialize($dutyplan['chamcong_1_pnr']);
	$r_pnr=unserialize($dutyplan['chamcong_2_pnr']);

	echo '
	<tr >
	<td  height=5 '.$backcolor.'>'.$i.'
	</td>
	<td height=5 '.$backcolor.'>';
	//if (!$wd) echo '<font color=red>';
	echo $LDShortDay[$wd].'
	</td>
	<td height=5 '.$backcolor.'>';
	//echo '&nbsp;<a href="javascript:popinfo(\''.$a_pnr['ha'.$n].'\')">'.$aelems['a'.$n].'</a>
	echo '&nbsp;'.$aelems['a'.$n].'
	</td>
	<td height=5 '.$backcolor.'>';
	//echo '&nbsp;<a href="javascript:popinfo(\''.$r_pnr['hr'.$n].'\')">'.$relems['r'.$n].'</a>
	echo '&nbsp;'.$relems['r'.$n].'
	</td>
	</tr>';
	if ($wd==6)  $wd=-1;
}

$sTemp = ob_get_contents();
 ob_end_clean();

# Assign the duty plan rows to sub frame template

$smarty->assign('sDutyRows',$sTemp);

 $smarty->assign('sMainBlockIncludeFile','common/duty_plan-1.tpl');



 $smarty->display('common/mainframe.tpl');
 */
 ?>
 </HEAD>


<BODY bgcolor="<?php echo $cfg['body_bgcolor'];?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 <?php if (!$cfg['dhtml']){ echo ' link='.$cfg['body_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['body_txtcolor']; } ?>>

<ul>
<table border="0" width="80%">
  <tbody>
    <tr style="font-size:18px">
	<td>
	<?php 

 $sBuffer = '<a href="'.$thisfile.URL_APPEND.'&retpath='.$retpath.'&dept_nr='.$dept_nr.'&pmonth=';

if ($pmonth==1) $sBuffer = $sBuffer.'12'.'&pyear='.($pyear-1).'">';
	else $sBuffer = $sBuffer.($pmonth-1).'&pyear='.$pyear.'">';
if ($pmonth==1) $sBuffer = $sBuffer.$monat[12];
	else $sBuffer = $sBuffer.$monat[$pmonth-1];
	$sBuffer=$sBuffer.'</a>';
	echo $sBuffer;
	?>
	</td>
	<td>
	<?php
	echo ucfirst($monat[$pmonth]).'&nbsp;&nbsp;'.$pyear;
	?>
	</td>
	<td>
	<?php
$sBuffer ='<a href="'.$thisfile.URL_APPEND.'&retpath='.$retpath.'&dept_nr='.$dept_nr.'&pmonth=';

if ($pmonth==12) $sBuffer = $sBuffer.'1'.'&pyear='.($pyear+1).'">';
	else $sBuffer = $sBuffer.($pmonth+1).'&pyear='.$pyear.'">';
if ($pmonth==12) $sBuffer = $sBuffer.$monat[1];
	else $sBuffer = $sBuffer.$monat[$pmonth+1];
	$sBuffer=$sBuffer.'</a>';
	echo $sBuffer;
	
	?></td>
	<td>&nbsp;</td>
	</tr>
	 <tr>
      <td colspan="3" valign="top">
        
		<table border=0 cellpadding=0 cellspacing=1 width="100%" class="frame">
        <tbody>
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
		 <td class="adm_input" style="white-space:nowrap;" >Họ và tên</td>
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
if(is_object($nurses) && $nurses->RecordCount()){

$rows=0; 
				while( $result=$nurses->FetchRow())
				{	
					if($result) $content[]=$result;
					 $rows++;
				}
				
for($j=0;$j<($nurses->RecordCount());$j++){
$sql="SELECT * FROM care_chamcong WHERE personell_nr='".$content[$j]['personell_nr']."' and year='".$pyear."' and month='".$pmonth."'";

$temp=$db->Execute($sql);
$temp->RecordCount();
$dutyplan=$temp->FetchRow();
$aelems=unserialize($dutyplan['chamcong_1_txt']);
$relems=unserialize($dutyplan['chamcong_2_txt']);
	
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
	
if($aelems['a'.$j.$n]==1){$atem='X';}
	elseif($aelems['a'.$j.$n]==2){$atem='CT';}
	elseif($aelems['a'.$j.$n]==3){$atem='TR';}
	elseif($aelems['a'.$j.$n]==4){$atem='NG';}
	elseif($aelems['a'.$j.$n]==5){$atem='P';}
	elseif($aelems['a'.$j.$n]==6){$atem='B';}
	elseif($aelems['a'.$j.$n]==7){$atem='HS';}
	elseif($aelems['a'.$j.$n]==8){$atem='NK';}
	elseif($aelems['a'.$j.$n]==9){$atem='KL';}
	elseif($aelems['a'.$j.$n]==10){$atem='DH';}
	elseif($aelems['a'.$j.$n]==0){$atem=' ';}
	if($relems['r'.$j.$n]==1){$rtem='X';}
	elseif($relems['r'.$j.$n]==2){$rtem='CT';}
	elseif($relems['r'.$j.$n]==3){$rtem='TR';}
	elseif($relems['r'.$j.$n]==4){$rtem='NG';}
	elseif($relems['r'.$j.$n]==5){$rtem='P';}
	elseif($relems['r'.$j.$n]==6){$rtem='B';}
	elseif($relems['r'.$j.$n]==7){$rtem='HS';}
	elseif($relems['r'.$j.$n]==8){$rtem='NK';}
	elseif($relems['r'.$j.$n]==9){$rtem='KL';}
	elseif($relems['r'.$j.$n]==10){$rtem='DH';}
	elseif($relems['r'.$j.$n]==0){$rtem=' ';}
echo '<td class="'.$class.'">		
				Sáng '.$atem.'
				Chiều '.$rtem.'
			</td>';		
}
echo'</tr>';

}
}
?>
</tbody>
        </table>

	  </td>
	   <td valign="top">
        <a href="nursing-or-main-pass.php<?php echo URL_APPEND ?>&target=chamcong&dept_nr=<?php echo$dept_nr?>&pmonth=<?php echo$pmonth?>&pyear=<?php echo$pyear?>&retpath=<?php echo$retpath?>"><img <?php echo createLDImgSrc($root_path ,'newplan.gif','0') ?>  alt="<?php echo$LDNewPlan;?>"></a>
		<p>
		<a href="<?php echo $rettarget ;?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClosePlan?>"></a>
      </td>
	  </tr>
    <tr>
      <td colspan="3">        <a href="nursing-or-main-pass.php<?php echo URL_APPEND ?>&target=chamcong&dept_nr=<?php echo$dept_nr?>&pmonth=<?php echo$pmonth?>&pyear=<?php echo$pyear?>&retpath=<?php echo$retpath?>"><img <?php echo createLDImgSrc($root_path ,'newplan.gif','0') ?>  alt="<?php echo$LDNewPlan;?>"></a>
&nbsp;&nbsp;&nbsp;<a href="<?php echo $rettarget ;?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClosePlan?>"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:printOut()"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="<?php echo $LDPrintOut?>"></a></td>
      <td>&nbsp;</td>
    </tr>  
  </tbody>
</table>
</ul>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);

 $smarty->display('common/mainframe.tpl');
?>
