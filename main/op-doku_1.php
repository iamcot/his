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
$lang_tables=array("nursing.php");
define('LANG_FILE','or.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
//quyen truy cap trang(2 trang có link tới nhau thì phải cug quyen truy cap)
$local_user = 'ck_opdoku_user';
$thisfile=basename(__FILE__);

setcookie(firstentry,''); // The cookie "firsentry" is used for switching the cat image

/* Check the start script as break destination*/
if (!empty($_SESSION['sess_path_referer'])&&($_SESSION['sess_path_referer']!=$top_dir.$thisfile)){
	if(file_exists($root_path.$_SESSION['sess_path_referer'])){
		$breakfile=$root_path.$_SESSION['sess_path_referer'].URL_APPEND;
	}else {
		/* default startpage */
		$breakfile = $root_path.'main/startframe.php'.URL_APPEND;
	}
} else {
	/* default startpage */
	$breakfile = $root_path.'main/startframe.php'.URL_APPEND;
}
//$breakfile=$root_path.$breakfile.URL_APPEND;
//////////// edit 17/11-Huỳnh //////////////
$user=$_SESSION['sess_user_name'];
include_once($root_path.'include/care_api_classes/class_department.php');
$dept_obj=new Department();
//Ma noi bo
$dept=$dept_obj->_getalldata('id=9','nr DESC','_OBJECT');
if($dept){
    $dept_nr=$dept->FetchRow();
    $dept_nr=$dept_nr['nr'];
}
include_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj=new Ward();
//Ma noi bo
$ward=$ward_obj->getAvaiWardOfDept($dept_nr);
if($ward){
    while($ward_nr_f=$ward->FetchRow()){
    	if($ward_nr_f['type']==2){
    		$ward_nr=$ward_nr_f['nr'];
    	}    	
    }    
}
///////////////////////////////////////////////

// reset all 2nd level lock cookies
require($root_path.'include/core/inc_2level_reset.php');

$_SESSION['sess_path_referer']=$top_dir.$thisfile;

# Start Smarty templating here
/**
 * LOAD Smarty
 */

# Note: it is advisable to load this after the inc_front_chain_lang.php so
# that the smarty script can use the user configured template theme

require_once($root_path.'gui/smarty_template/smarty_care.class.php');
$smarty = new smarty_care('common');

# Module title in the toolbar

$smarty->assign('sToolbarTitle',$LDOr);

# Help button href
$smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDOr')");

$smarty->assign('breakfile',$breakfile);

# Window bar title
$smarty->assign('Name',$LDOr);

# Append javascript code to javascript block

$smarty->append('JavaScript',$sTemp);

# Create the submenu blocks

/////////////////// edit 12/11-Huỳnh //////////////////////////////////
//cách tạo file hih dag này?????????????
$smarty->assign('LDOrPatient',$LDPatient);
//$smarty->assign('LDOrPatientDocument',"<a href=\"".$root_path."modules/op_document/op-doku-pass_1.php".URL_APPEND."\">$LDOrPatientDocument</a>");
//xem lại phân quyền để vào từng chức năng như thế nào
//$user là quyền vào 
$smarty->assign('LDOrPatientDocument',"<a href=\"".$root_path."modules/op_document/op_test_request_pass.php".URL_REDIRECT_APPEND."&target=".$user."&subtarget=or&user_origin=op&dept_nr=$dept_nr&ward_nr=$ward_nr&temp=0\">$LDOrPatientDocument</a>");
$smarty->assign('LDOrPatientDocumentTxt',$LDOrPatientDocumentTxt);
$smarty->assign('LDOrDocument1',"<a href=\"".$root_path."modules/op_document/op_test_request_pass.php".URL_REDIRECT_APPEND."&target=".$user."&subtarget=or&user_origin=op_e_kip&dept_nr=$dept_nr&ward_nr=$ward_nr&temp=1\">$LDOrDocument1</a>");
$smarty->assign('LDOrDocumentTxt1',$LDOrDocumentTxt1);
$smarty->assign('LDOrPersonell',"<a href=\"".$root_path."modules/or/personell_listall.php".URL_APPEND."&target=personell_listall&dept_nr=$dept_nr&ward_nr=$ward_nr\">$LDOrPersonell</a>");
$smarty->assign('LDOrDocumentTxt2',$LDOrDocumentTxt2);
$smarty->assign('LDOrPharma',"<a href=\"".$root_path."modules/nursing/nursing-manage-medicine.php".URL_APPEND."&target=pres&dept_nr=$dept_nr&ward_nr=$ward_nr\">$LDCabinetMedicine</a>");
$smarty->assign('LDOrDocumentTxt3',$LDOrDocumentTxt3);
$smarty->assign('LDStast',"<a href=\"".$root_path."modules/or/listall_for_personell.php".URL_APPEND."&target=personell_listall&user_name=$user&dept_nr=$dept_nr&ward_nr=$ward_nr\">$LDStast</a>");
$smarty->assign('LDOrDocumentTxt4',$LDOrDocumentTxt4);
///////////////////////////////////////////////////////////////////////

# OR Surgeons submenu block
$smarty->assign('LDOrDocs',"<img ".createLDImgSrc($root_path,'arzt2.gif','0','absmiddle')."  alt=\"$LDDoctor\">");
////Bác sĩ trưởng khoa
//$smarty->assign('LDQviewDocManage',"<font color=darkblue>$LDDOCManager</font>");
//$smarty->assign('LDQviewTxtDocManage',$LDOnCallDutyTxt);
//$smarty->assign('LDOrDocMenu',
//  		'<TABLE cellSpacing=1 cellPadding=5 width="100%" bgColor=#dddddd border=0>
//			<TR>
//				<TD bgColor=#ffffff><font face=arial,verdana size=2><nobr>
//				 <A href="' . $root_path . 'modules/doctors/doctors-dienstplan.php'.URL_APPEND.'&dept_nr='.$dept_nr.'&retpath=qview";>'.  $LDScheduler1 .'</A>
//				</font></TD>
//			</TR>
//		</TABLE>');
//Bác sĩ trong khoa
$smarty->assign('LDQviewDocs',"<a href=\"".$root_path."modules/doctors/doctors-dienstplan.php".URL_APPEND."&dept_nr=$dept_nr&retpath=qview\">$LDDoc</a>");
$smarty->assign('LDQviewTxtDocs',$LDQviewTxtDocs1);
# OR Nursing submenu block

$smarty->assign('LDOrNursing',"<img ".createLDImgSrc($root_path,'pflege2.gif','0','absmiddle')."  alt=\"$LDNursing\">");
//PLog
//Điều dưỡng trưởng
$month=date('m');
$year=date('Y');
$smarty->assign('LDORNOCManager',"<font color=darkblue>$LDORNOCManager</font>");
$smarty->assign('LDDutyPlanTxt',$LDDutyPlanTxt);
$smarty->assign('LDOrNurseMenu',
  		'<TABLE cellSpacing=1 cellPadding=5 width="100%" bgColor=#dddddd border=0>
			<TR>
                                <TD bgColor=#ffffff><font face=arial,verdana size=2><nobr>
                                <A href="'.$root_path.'modules/or/list_patient_op.php'.URL_APPEND.'&pmonth='.$month.'&pyear='.$year.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDListPatient1.'</A>
                                </font></TD>
                                <TD bgColor=#ffffff><font face=arial,verdana size=2><nobr>
                                <A href="'.$root_path.'modules/or/list_personell_stast.php'.URL_APPEND.'&pmonth='.$month.'&pyear='.$year.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">'.$LDPersonellStast.'</A>
                                </font></TD>
			</TR>
		</TABLE>');
//Y tá trong khoa
$smarty->assign('LDORNOCScheduler',"<a href=\"".$root_path."modules/nursing_or/nursing-or-dienstplan.php".URL_APPEND."&retpath=menu&dept_nr=$dept_nr&ward_nr=$ward_nr\">$LDORNOC</a>");
$smarty->assign('LDDutyPlanTxt1',$LDQviewTxtDocs1);
//Phòng hậu phẫu
$smarty->assign('LDOncallDuty',"<font color=darkblue>$LDOnCallDuty</font>");
$smarty->assign('LDOncallManage',
		'<TABLE cellSpacing=1 cellPadding=5 width="100%" bgColor=#dddddd border=0>
			<TR>
				<TD bgColor=#ffffff><font face=arial,verdana size=2><nobr> 
					<a href="spediens-bdienst-zeit-erfassung.php'.URL_REDIRECT_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&retpath=op&encoder='.$_COOKIE['ck_login_username'.$sid].'">'.$LDScheduler1.'</a>
					&nbsp;&nbsp;&nbsp;&nbsp; 
                                <A href="search-oncall-duty.php'.URL_APPEND.'">' .  $LDSearch . '</A> 
				</font></TD>
			</TR>
		</TABLE>');
$smarty->assign('LDOnCallDutyTxt',$LDOnCallDutyTxt2);
//Nhật ký điều dưỡng
$smarty->assign('LDOrLogBook',"<font color=darkblue>$LDOrLogBook</font>");
$smarty->assign('LDOrLogBookTxt',$LDOrLogBookTxt);
$smarty->assign('LDOrLogBookMenu',
  		'<TABLE cellSpacing=1 cellPadding=5 width="100%" bgColor=#dddddd border=0>
			<TR>
				<TD bgColor=#ffffff><font face=arial,verdana size=2><nobr> 
				<A href="' . $root_path . 'modules/op_document/op_test_request_pass.php'.URL_REDIRECT_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&target='.$user.'&subtarget=or&user_origin=op_done&temp=1\";>'.  $LDNewDocu .'</A>
				&nbsp;&nbsp;&nbsp;&nbsp; 
                                <A href="'. $root_path .'modules/or_logbook/op-pflege-logbuch-xtsuch-start.php' .  URL_REDIRECT_APPEND .'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'">' .  $LDSearch . '</A> 
				</font></TD>
			</TR>
		</TABLE>');

# Collect div codes for  on-mouse-hover pop-up menu windows

$sTemp='';
ob_start();

$sTemp = ob_get_contents();

ob_end_clean();

$smarty->assign('sOnHoverMenu',$sTemp);
$smarty->assign('root_path',$root_path);
# Assign the submenu to the mainframe center block

$smarty->assign('sMainBlockIncludeFile','or/submenu_or_1.tpl');

/**
 * show  Mainframe Template
 */

$smarty->display('common/mainframe.tpl');
?>