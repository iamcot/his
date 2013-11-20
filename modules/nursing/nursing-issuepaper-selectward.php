<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
 
$lang_tables=array('departments.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

# Init
$thisfile= basename(__FILE__);
$breakfile='nursing.php'.URL_APPEND;
$fileforward='nursing-manage-medicine.php'.URL_APPEND;

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in the toolbar
 $smarty->assign('sToolbarTitle',$LDManageMedicine.' :: '.$LDSelectWard);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDIssuePaper')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDManageMedicine);

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);


//********************************NOI DUNG TRANG: Chon khu phong ******************************

	$sTemp='';
	$toggler=0;
	
	/* Load the department list with oncall doctors */
	require_once($root_path.'include/care_api_classes/class_department.php');
	require_once($root_path.'include/care_api_classes/class_ward.php');
	$dept_obj=new Department;
	$dept_DOC=$dept_obj->getAllActiveWithDOC();
	$ward_obj=new Ward;
	
	$smarty->assign('imgselectDept','<img '.createLDImgSrc($root_path,'ok_small.gif','0','absmiddle').' alt="'.$LDPlsSelectDept.'" >');
	$smarty->assign('imgselect','<img '.createComIcon($root_path,'select_it.gif','0','',TRUE).' alt="'.$LDPlsSelectDept.'" >');
	
	# Buffer department and ward rows output
	while(list($x,$v)=each($dept_DOC)){
		if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) $DeptName = $$v['LD_var'];
		else $DeptName = $v['name_formal'];
		$smarty->assign('DeptName',$DeptName);
		$smarty->assign('forwardDeptNr',$fileforward.'&target=pres&dept_nr='.$v['nr']);		
		if($v['nr']){
			$list_ward = $ward_obj->getAvaiWardOfDept($v['nr']);
			if(is_object($list_ward )){
				$smarty->assign('count',TRUE);
				$smarty->assign('list_ward',$list_ward);
				$smarty->assign('fileforward',$fileforward.'&target=pres&dept_nr='.$v['nr'].'&ward_nr=');				
			}else $smarty->assign('count',FALSE);
		}
		if ($toggler==0){ $smarty->assign('classtr',' class="wardlistrow1" '); $toggler=1;}
		else { $smarty->assign('classtr',' class="wardlistrow2" '); $toggler=0;}
		
		ob_start();
		$smarty->display('nursing/issuepaper_pres_ward.tpl');
		$sTemp = $sTemp.ob_get_contents();
		ob_end_clean();
	}
	
	# Assign the dept rows  to the frame template
	$smarty->assign('sDeptRows',$sTemp);
	$smarty->assign('sMascotImg','<img '.createMascot($root_path,'mascot1_r.gif','0','bottom').' align="absmiddle">');
	$smarty->assign('LDPlsSelectWard',$LDPlsSelectWard);
	$smarty->assign('sBackLink','<a href="'.$breakfile.'"><img '.createLDImgSrc($root_path,'close2.gif','0').' alt="'.$LDCloseAlt.'">');

//*********************************************************************************
 


$sCancel="<a href=";
if($_COOKIE['ck_login_logged'.$sid]) $sCancel.=$breakfile;
	else $sCancel.='aufnahme_pass.php';
$sCancel.=URL_APPEND.'><img '.createLDImgSrc($root_path,'cancel.gif','0').' alt="'.$LDCancelClose.'"></a>';

$smarty->assign('pbCancel',$sCancel);

# Assign the page template to mainframe block
$smarty->assign('sMainBlockIncludeFile','nursing/issuepaper_pres.tpl');

# Show main frame
$smarty->display('common/mainframe.tpl');


?>