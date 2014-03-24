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

# If cache must be deactivated, set $force_no_cache to true
$force_no_cache=1;

$lang_tables[]='departments.php';
$lang_tables[]='prompt.php';
define('LANG_FILE','doctors.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/care_api_classes/class_core.php');
$core=new Core;

//$db->debug=1;

switch($retpath)
{
	case "docs": $breakfile='doctors.php'.URL_APPEND; break;
	case "op": $breakfile=$root_path.'main/op-doku.php'.URL_APPEND; break;
	default: $breakfile='doctors.php'.URL_APPEND; 
}

$thisfile=basename(__FILE__);

$pday=date(j);
$pmonth=date(n);
$pyear=date(Y);
$abtarr=array();
$abtname=array();
$datum=date("d.m.Y");

if(!$hilitedept)
{
	if($dept_nr) $hilitedept=$dept_nr;
}

#
# Prepare the date. We need to consider the early morning hours or until the DOC_CHANGE_TIME value has passed
#

$plan_yesterday=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-1,date('Y')));

if(date('H.i')<DOC_CHANGE_TIME){
	$plan_date=$plan_yesterday;
	$plan_day=date('d',mktime(0,0,0,date('m'),date('d')-1,date('Y')));
}else{
	$plan_date=date('Y-m-d');
	$plan_day=date('d');

	#
	# If plan date is today, attempt to delete the cached plan of yesterday
	#
	$core->deleteDBCache('DOCS_'.$plan_yesterday);
}
//echo "$plan_date $plan_day";
 
	# Get the cached plan

	$cached_plan='';
	if(!$is_cached=$core->getDBCache('DOCS_'.$plan_date,$cached_plan)) $force_no_cache=true;


if($force_no_cache || (!$force_no_cache && !$is_cached)){
	if(!$hilitedept){
		if($dept_nr) $hilitedept=$dept_nr;
	}
	# Load the department list with oncall doctors 
	include_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj=new Department;
	$dept_DOC=$dept_obj->getAllActiveWithDOC();
	include_once($root_path.'include/care_api_classes/class_personell.php');
	$pers_obj=new Personell;
	$quicklist=&$pers_obj->getDOCQuicklist($dept_DOC,$pyear,$pmonth);
}

$role_nr = 15;//bsi role nr = 15

# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$LDDocsOnDuty);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('docs_duty_quickview.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Body onLoad javascript
 $smarty->assign('sOnLoadJs','onUnload="killchild()"');

 # Window bar title
 $smarty->assign('sWindowTitle',$LDDocsOnDuty);

 # Collect extra javascript

 ob_start();

?>

<script language="javascript">
<!-- 
  var urlholder;
function popinfo(l,d)
{
	urlholder="doctors-dienstplan-popinfo.php<?php echo URL_REDIRECT_APPEND ?>&nr="+l+"&dept_nr="+d+"&user=<?php echo $aufnahme_user.'"' ?>;
	
	infowin=window.open(urlholder,"dienstinfo","width=400,height=300,menubar=no,resizable=yes,scrollbars=yes");

}

-->
</script>

<?php 

 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->append('JavaScript',$sTemp);

 # Buffer page output

 ob_start();

?>

	<table  cellpadding="1" cellspacing=0 border="1" >
	<tr class="wardlisttitlerow" align=center>
<?php

for($j=0;$j<sizeof($LDTabElements);$j++)
	echo '<td>&nbsp; '.$LDTabElements[$j].' &nbsp;&nbsp;</td>';
echo '
	</tr>';

if(!$force_no_cache&&$is_cached){
	
/*	echo '<tr>
	<td colspan=6><font face="verdana,arial" size=2> <img '.createComIcon($root_path,'warn.gif','0').'> <font color=red>'.$LDCachedInfo.'</font> <a href="'.$thisfile.URL_APPEND.'&force_no_cache=1&retpath='.$retpath.'">'.$LDClkNoCache.'</a>
	</td>
	</tr>';
*/	
	$cached_plan=str_replace('URLAPPEND',URL_APPEND,$cached_plan);
	$cached_plan=str_replace('IMGALT',$LDShowActualPlan,$cached_plan);
	$cached_plan=str_replace('SHOWBUTTON',$LDShow,$cached_plan);
	echo str_replace('URLREDIRECTAPPEND',URL_REDIRECT_APPEND,$cached_plan);

}else{
	
	
	$toggler=0;

	# Start generating the DOC list

	$temp_out='';

	while(list($x,$v)=each($dept_DOC)){
	
	if(in_array($v['nr'],$quicklist)){
		if($dutyplan=$pers_obj->getDOCDutyplan($v['nr'],$role_nr,$pyear,$pmonth)){
	
			$a=unserialize($dutyplan['duty_1_txt']);	
			$ao=unserialize($dutyplan['duty_3_txt']);
			//var_dump($a);
			$r=unserialize($dutyplan['duty_2_txt']);
			$ha=unserialize($dutyplan['duty_1_pnr']);	
			$hr=unserialize($dutyplan['duty_2_pnr']);
			$hao=unserialize($dutyplan['duty_3_pnr']);
				
			if($ha['ha'.($plan_day-1)]) $DOC_1=$pers_obj->getPersonellInfo($ha['ha'.($plan_day-1)]);
			if($hr['hr'.($plan_day-1)]) $DOC_2=$pers_obj->getPersonellInfo($hr['hr'.($plan_day-1)]);
			if($hao['hao'.($plan_day-1)]) $DOC_3=$pers_obj->getPersonellInfo($hr['hao'.($plan_day-1)]);
		}
	
	}else{
		if(isset($a)) unset($a);
		if(isset($r)) unset($r);
		if (isset($ao)) unset($ao);
		if(isset($ha)) unset($ha);
		if(isset($hr)) unset($hr);
		if(isset($hao)) unset($hao);
		if(isset($DOC_1)) unset($DOC_1);
		if(isset($DOC_2)) unset($DOC_2);
		if(isset($DOC_3)) unset($DOC_3);
	}

	
	$bold='';
	$boldx='';
	if($hilitedept==$v['nr']){ 
		$temp_out.='<tr class="hilite">'; $bold="<font color=\"red\" size=2><b>";$boldx="</b></font>";
	} 
	elseif ($toggler==0) {
		$temp_out.='<tr class="wardlistrow1">'; $toggler=1;
	}else{
		$temp_out.='<tr class="wardlistrow2">'; $toggler=0;
	}
$aday=unserialize($a['a'.($pday-1)]);
//var_dump($aday);
	$temp_out.='<td><font size="1" >&nbsp;'.$bold;
	$buff= $v['LD_var'];
	if(isset($$buff)&&!empty($$buff)) $temp_out.=$$buff;
	 	else $temp_out.=$v['name_formal'];
	$temp_out.=$boldx.'&nbsp;</td><td>';
	for($i=0;$i<5;$i++){
		if($aday['a'.($pday-1).'_'.$i]!=''){
		$temp_out.=$aday['a'.($pday-1).'_'.$i].'&nbsp;<br/>';
		}
	}
	
	//if ($aelems[l]!="") echo $aelems[l].', ';
	//echo $aelems[f].'</b></a></td>';
	$temp_out.='</td>';
	$aoday=unserialize($ao['ao'.($pday-1)]);
	$temp_out.='<td>';
	for($i=0;$i<5;$i++){
		if($aoday['ao'.($pday-1).'_'.$i]!=''){
		$temp_out.=$aoday['ao'.($pday-1).'_'.$i].'&nbsp;<br/>';
		}
	}
	$temp_out.='</td><td>';
	$rday=unserialize($r['r'.($pday-1)]);
	
	for($i=0;$i<5;$i++){
		if($rday['r'.($pday-1).'_'.$i]!=''){
		$temp_out.=$rday['r'.($pday-1).'_'.$i].'&nbsp;<br/>';
		}
	}
	$temp_out.='
	</td>
	<td>&nbsp; <a href="doctors-dienstplan.phpURLAPPEND&dept_nr='.$v['nr'].'&retpath=qview">
	<button onClick="javascript:window.location.href=\'doctors-dienstplan.phpURLREDIRECTAPPEND&dept_nr='.$v['nr'].'&retpath=qview\'"><img '.createComIcon($root_path,'new_address.gif','0','absmiddle',FALSE).' alt="IMGALT" ><font size=1> SHOWBUTTON </font></button></a> 
	</td>
	</tr>';
	
}
# Save in cache 
if(!$force_no_cache || ($force_no_cache && !$is_cached)) $dept_obj->saveDBCache('DOCS_'.date('Y-m-d'),addslashes($temp_out));
# Display list
$temp_out=str_replace('URLAPPEND',URL_APPEND,$temp_out);
$temp_out=str_replace('IMGALT',$LDShowActualPlan,$temp_out);
$temp_out=str_replace('SHOWBUTTON',$LDShow,$temp_out);
echo str_replace('URLREDIRECTAPPEND',URL_REDIRECT_APPEND,$temp_out);
}
?>
</table>
<p>
<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDCloseAlt ?>">
</a>

<?php

$sTemp = ob_get_contents();
 ob_end_clean();

# Assign the buffer output  to main frame template

$smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
