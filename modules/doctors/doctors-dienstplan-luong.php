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
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
///$db->debug=true;
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
$dutyplan=&$pers_obj->getDChamcong($dept_nr,$pyear,$pmonth);
$persons=$pers_obj->getAllOfDept($dept_nr);

$firstday=date("w",mktime(0,0,0,$pmonth,1,$pyear));

$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));

switch($retpath)
{
	case "menu": $rettarget='doctors.php'.URL_APPEND; break;
	case "qview": $rettarget='doctors-shift-fastview-luong.php'.URL_APPEND.'&hilitedept='.$dept_nr; break;
	default: $rettarget=''.$root_path.'modules/timekeeping/tkp-func-mframe.php?ntid=false&lang=vi';
}

# Prepare page title
 $sTitle = "$LDLuong::";
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
 $smarty->assign('pbHelp',"javascript:gethelp('docs_dutyplan.php','show','$rows')");

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

function popinfo(l)
{
	w=window.screen.width;
	h=window.screen.height;
	ww=400;
	wh=400;
	urlholder="doctors-dienstplan-popinfo.php<?php echo URL_REDIRECT_APPEND ?>&nr="+l+"&dept_nr=<?php echo $dept_nr ?>&route=validroute&user=<?php echo $aufnahme_user.'"' ?>;
	
	infowin<?php echo $sid ?>=window.open(urlholder,"infowin<?php echo $sid ?>","width=" + ww + ",height=" + wh +",menubar=no,resizable=yes,scrollbars=yes");
	window.infowin<?php echo $sid ?>.moveTo((w/2)+20,(h/2)-(wh/2));

}
</script>


<?php 

 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->append('JavaScript',$sTemp);
 
 ob_start();

?>
</HEAD>


<BODY bgcolor="<?php echo $cfg['body_bgcolor'];?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 <?php if (!$cfg['dhtml']){ echo ' link='.$cfg['body_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['body_txtcolor']; } ?>>

<ul>
<table border="0" width="80%">
 <tbody>
   <tr style="font-size:18px">
	<td>
	<?php if(is_object($persons) && $persons->RecordCount()){ 

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
		  <td class="adm_input" >Họ & tên</td>
		  <td class="adm_input">Nhiệm vụ</td>
		  <td class="adm_input">Lương</td>
		</tr>
		<?php
		

			$rows=0; 
			while( $result=$persons->FetchRow())
				{	
					if($result) $content[]=$result;
					$rows++;
				}
						
			for($j=0;$j<($persons->RecordCount());$j++){
				$sql="SELECT * FROM care_luong WHERE personell_nr='".$content[$j]['personell_nr']."' and year='".$pyear."' and month='".$pmonth."'";
				$temp=$db->Execute($sql);
				$temp->RecordCount();
				$dutyplan=$temp->FetchRow();	
				//$chuyenmon=$pers_obj->getNameJobFunction($content[$j]['job_function_title']);
				echo'<tr>';
				echo'<td class="adm_item" style="white-space:nowrap;">'.$content[$j]['name_last'].' '.$content[$j]['name_first'].'';
				echo'<td class="weekday">'.$pers_obj->getNameJobFunction($content[$j]['job_function_title']).'</td>';
				echo '<td class="weekday">		
							<p>'.$dutyplan['luong'].'</p>
							</td>';		
				echo'</tr>';
			}
		?>
 </tbody>
    </table>

	  </td>
	   <td valign="top">
        <a href="doctors-main-pass.php<?php echo URL_APPEND ?>&target=luong&dept_nr=<?php echo$dept_nr?>&pmonth=<?php echo$pmonth?>&pyear=<?php echo$pyear?>&retpath=<?php echo$retpath?>"><img <?php echo createLDImgSrc($root_path ,'tinhluong.png','0') ?>  alt="<?php echo$LDNewPlan;?>"></a>
		<p>
		<a href="<?php echo $rettarget ;?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClosePlan?>"></a>
      </td>
	  </tr>
    <tr>
      <td colspan="3">        <a href="doctors-main-pass.php<?php echo URL_APPEND ?>&target=luong&dept_nr=<?php echo$dept_nr?>&pmonth=<?php echo$pmonth?>&pyear=<?php echo$pyear?>&retpath=<?php echo$retpath?>"><img <?php echo createLDImgSrc($root_path ,'tinhluong.png','0') ?>  alt="<?php echo$LDNewPlan;?>"></a>
&nbsp;&nbsp;&nbsp;<a href="<?php echo $rettarget ;?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClosePlan?>"></a></td>
      <td>&nbsp;</td>
    </tr>  

  </tbody>
</table>
<?php	}else{
 echo '<tr>
      <td><img '.createMascot($root_path,'mascot1_r.gif','0','left').'  ></td>
      <td><font face="verdana,arial" size=3><b>'.$LDNoPersonList.'</b></td>
    </tr></tbody>
</table>';
		}?>
</ul>
<?php
 $sTemp = ob_get_contents();
 ob_end_clean();
# Assign page output to the mainframe template
 $smarty->assign('sMainFrameBlockData',$sTemp);
 $smarty->display('common/mainframe.tpl');
?>