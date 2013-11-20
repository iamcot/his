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



$firstday=date("w",mktime(0,0,0,$pmonth,1,$pyear));

$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));


# Prepare page title
 $sTitle = "$LDDoctors::$LDLuong::";
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
	<?php 

 $sBuffer = '<a href="'.$thisfile.URL_APPEND.'&nr='.$nr.'&pmonth=';

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
$sBuffer ='<a href="'.$thisfile.URL_APPEND.'&nr='.$nr.'&pmonth=';

if ($pmonth==12) $sBuffer = $sBuffer.'1'.'&pyear='.($pyear+1).'">';
	else $sBuffer = $sBuffer.($pmonth+1).'&pyear='.$pyear.'">';
if ($pmonth==12) $sBuffer = $sBuffer.$monat[1];
	else $sBuffer = $sBuffer.$monat[$pmonth+1];
	$sBuffer=$sBuffer.'</a>';
	echo $sBuffer;	
	?>
	</td>
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
$sql="SELECT l.*,p.name_last,p.name_first,cp.job_function_title FROM care_luong as l, care_person as p , care_personell as cp
 WHERE l.personell_nr='".$nr."' and l.year='".$pyear."' and l.month='".$pmonth."'
and cp.nr='".$nr."' and p.pid=cp.pid";
$temp=$db->Execute($sql);
$temp->RecordCount();
$luong=$temp->FetchRow();
echo'<tr>';
echo'<td class="adm_item" style="white-space:nowrap;">'.$luong['name_last'].' '.$luong['name_first'].'';
echo'<td class="weekday">'.$luong['job_function_title'].'</td>';

echo '<td class="weekday">		
			<p>'.$luong['luong'].'</p>
			</td>';		

echo'</tr>';
?>
		</tbody>
        </table>
	  </td>	  
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