<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

$lang_tables=array('departments.php');
define('LANG_FILE','ambulatory.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
// reset all 2nd level lock cookies
require($root_path.'include/core/inc_2level_reset.php');

if(!isset($_SESSION['sess_path_referer'])) $_SESSION['sess_path_referer'] = "";
$breakfile=$root_path.'main/startframe.php'.URL_APPEND;
$_SESSION['sess_path_referer']=$top_dir.basename(__FILE__);
$_SESSION['sess_user_origin']='amb';
$_SESSION['sess_parent_mod']='';
/* Create department object and load all medical depts */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj= new Department;

	$medical_depts=&$dept_obj->getAllMedical() ; // get all depts
# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$LDAmbulatory);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDAmbulatory')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',$LDAmbulatory);

 # Prepare the submenu icons

 $smarty->assign('sTitleIcon','<img '.createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE).'>');
 
 if($cfg['icons'] != 'no_icon') {
	$smarty->assign('sApptIcon','<img '.createComIcon($root_path,'icon-date-hour.gif','0').'>');
	$smarty->assign('sOutPatientIcon','<img '.createComIcon($root_path,'forums.gif','0').'>');
	$smarty->assign('sPendReqIcon','<img '.createComIcon($root_path,'waiting.gif','0').'>');
	$smarty->assign('sNewsIcon','<img '.createComIcon($root_path,'bubble2.gif','0').'>');
}

 # Assign the text

 $smarty->assign('LDSelectDept',$LDSelectDept);
 $smarty->assign('LDAppointmentsTxt',$LDAppointmentsTxt);
 $smarty->assign('LDPWListTxt',$LDPWListTxt);
 $smarty->assign('LDPendingRequestTxt',$LDPendingRequestTxt);
 $smarty->assign('LDNewsTxt',$LDNewsTxt);
 
 # Collect extra javascript

 $sTemp='
<script language="javascript">
<!-- Script Begin
function goDept(t) {
	d=document.dept_select;
	if(d.dept_nr.value!=""){
		d.subtarget.value=d.dept_nr.value;
		d.action=t;
		eval("d.dept.value=d.dname"+d.dept_nr.value+".value;");
		d.submit();
	}
}
//  Script End -->
</script>
';

	$smarty->append('JavaScript',$sTemp);

 # Prepare select options

$TP_SELECT_BLOCK='<select name="dept_nr" size="1"><option value=""></option>';

if(!isset($_SESSION['department_nr']) || $_SESSION['department_nr'] == '') {
    while(list($x,$v)=each($medical_depts)){
    	$subDepts = $dept_obj->getAllSubDepts($v['nr']);
    	$TP_SELECT_BLOCK.='<option value="'.$v['nr'].'" >';
    	$buffer=$v['LD_var'];
    	if(isset($$buffer)&&!empty($$buffer)) $TP_SELECT_BLOCK.=$$buffer;
    	else $TP_SELECT_BLOCK.=$v['name_formal'];
    	$TP_SELECT_BLOCK.='</option>';
    	//add the subdept
    	if($subDepts) {
			while (list($y,$sDept) = each($subDepts)) {
            	$TP_SELECT_BLOCK.='<option value="'.$sDept['nr'].'" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<sup>L</sup>&nbsp;';
            	$buffer=$sDept['LD_var'];
            	if(isset($$buffer)&&!empty($$buffer)) $TP_SELECT_BLOCK.=$$buffer;
            	else $TP_SELECT_BLOCK.=$sDept['name_formal'];
            	$TP_SELECT_BLOCK.='</option>';			}
		}
    }  
} else {
//var_dump($_SESSION['department_nr']); //edit 0310 - cot
    while(list($x,$v)=each($medical_depts)){
    	if(($v['nr'] == $_SESSION['department_nr']))  { 
        	$TP_SELECT_BLOCK.='<option value="'.$v['nr'].'" selected >';
        	$buffer=$v['LD_var'];
        	if(isset($$buffer)&&!empty($$buffer)) $TP_SELECT_BLOCK.=$$buffer;
        	else $TP_SELECT_BLOCK.=$v['name_formal'];
        	$TP_SELECT_BLOCK.='</option>';
    	}
    	else continue;
    }    
}
$TP_SELECT_BLOCK.='</select>';

#Prepare hidden inputs
$TP_HIDDENS='';
reset($medical_depts);
if(!isset($_SESSION['department_nr']) || $_SESSION['department_nr'] == '') {
	while(list($x,$v)=each($medical_depts)){
		$subDepts = $dept_obj->getAllSubDepts($v['nr']);
		$buffer=$v['LD_var'];
		if(isset($$buffer)&&!empty($$buffer)) $dname=$$buffer;
			else $dname= $v['name_formal'];
		$TP_HIDDENS.='
		<input type="hidden" name="dname'.$v['nr'].'" value="'.$dname.'">';
	    	if($subDepts) {
				while (list($y,$sDept) = each($subDepts)) {
					$buffer=$sDept['LD_var'];
					if(isset($$buffer)&&!empty($$buffer)) $dname=$$buffer;
					else $dname= $sDept['name_formal'];
					$TP_HIDDENS.='
					<input type="hidden" name="dname'.$sDept['nr'].'" value="'.$dname.'">';
				}
			}
	}
} else {
	while(list($x,$v)=each($medical_depts)){
		$buffer=$v['LD_var'];
		if(isset($$buffer)&&!empty($$buffer)) $dname=$$buffer;
			else $dname= $v['name_formal'];
		$TP_HIDDENS.='
		<input type="hidden" name="dname'.$v['nr'].'" value="'.$dname.'">';
	}	
}
# hidden
$TP_HINPUTS='<input type="hidden" name="sid" value="'.$sid.'">
   			<input type="hidden" name="lang" value="'.$lang.'">
   			<input type="hidden" name="target" value="generic">
   			<input type="hidden" name="user_origin" value="amb">
   			<input type="hidden" name="subtarget" value="">
   			<input type="hidden" name="dept" value="">';

 # Assign the generic submenu items

 $smarty->assign('TP_SELECT_BLOCK',$TP_SELECT_BLOCK);
 $smarty->assign('TP_HINPUTS',$TP_HINPUTS);
 $smarty->assign('TP_HIDDENS',$TP_HIDDENS);

 $smarty->assign('TP_HREF_APPT1','<a href="javascript:goDept(\''.$root_path.'modules/appointment_scheduler/appt_main_pass.php\')">'.$LDAppointments.'</a>');
 $smarty->assign('TP_HREF_PWL1','<a href="javascript:goDept(\'amb_clinic_patients_pass.php\')">'.$LDOutpatientClinic.'</a>');
 $smarty->assign('TP_HREF_PREQ1','<a href="javascript:goDept(\''.$root_path.'modules/laboratory/labor_test_request_pass.php\')">'.$LDPendingRequest.'</a>');
 $smarty->assign('TP_HREF_NEWS1','<a href="javascript:goDept(\''.$root_path.'modules/news/newscolumns.php\')">'.$LDNews.'</a>');

 # Assign to main template object
	$smarty->assign('sBottomRightSubMenu',$sTemp);

# Assign the submenu to the mainframe center block

 $smarty->assign('sMainBlockIncludeFile','ambulatory/submenu_ambulatory.tpl');

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
