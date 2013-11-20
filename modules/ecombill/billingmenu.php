<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
/**
 * eComBill 1.0.04 for Care2002 beta 1.0.04
 * (2003-04-30)
 * adapted from eComBill beta 0.2
 * developed by ecomscience.com http://www.ecomscience.com
 * Dilip Bharatee
 * Abrar Hazarika
 * Prantar Deka
 * GPL License
 */
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');

define('LANG_FILE','billing.php');
define('NO_CHAIN',1);

require_once($root_path.'include/core/inc_front_chain_lang.php');

$breakfile=$root_path.'main/spediens.php'.URL_APPEND;
$returnfile=$root_path.'main/spediens.php'.URL_APPEND;


# Start Smarty templating here
 /**
 * LOAD Smarty
 */

 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 # Create a helper smarty object without reinitializing the GUI
 $smarty2 = new smarty_care('common', FALSE);

# Added for the common header top block

 $smarty->assign('sToolbarTitle',$LDBilling);

 # Added for the common header top block
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDBilling')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title',$LDBilling);

 
 # Collect extra javascript code

 ob_start();


$sTemp = ob_get_contents();
ob_end_clean();


/* Kiem tra quyen han cua nguoi truy cap */
$user_id= $_SESSION['sess_login_userid'];

$qry="SELECT personell_nr FROM care_users WHERE login_id='".$user_id."'";
$result=$db->Execute($qry);
if(is_object($result))
{
	$buffer = $result->FetchRow();
	$personell_nr = $buffer['personell_nr'];
}

$qry1="SELECT * FROM care_personell_assignment WHERE personell_nr='".$personell_nr."'";
$result1=$db->Execute($qry1);
if(is_object($result1))
{
	$buffer1 = $result1->FetchRow();
	$dept_nr = $buffer1['location_nr'];
}


//TCKT: dept_nr=3; KHTH: dept_nr=2

if($dept_nr=='2' || $dept_nr=='3'){

	 # Prepare the submenu icons

	 $aSubMenuIcon=array(createComIcon($root_path,'settings_tree.gif','0'),
						createComIcon($root_path,'settings_tree.gif','0'),
						createComIcon($root_path,'sitemap_animator.gif','0'),
						createComIcon($root_path,'sitemap_animator.gif','0'),
						createComIcon($root_path,'findnew.gif','0'),
						createComIcon($root_path,'bestell.gif','0'),
						createComIcon($root_path,'bestell.gif','0'),
					);

	 # Prepare the submenu item descriptions

	 $aSubMenuText=array($LDCreateHospitalServiceItemTxt,
						$LDCreateLabTestItemTxt,
						$LDEditHospitalServiceItemsTxt,
						$LDEditLabTestItemsTxt,
						$LDSearchPatientTxt,
						$LDReport15Txt,
						$LDReportMonthTxt,
						);

	# Prepare the submenu item links indexed by their template tags

	$aSubMenuItem=array('LDPharmaOrder' => "<a href=\"enter_hospital_services.php".URL_APPEND."\">$LDCreateHospitalServiceItem</a>",
						'LDHow2Order' => "<a href=\"enter_laboratory_tests.php".URL_APPEND."\">$LDCreateLabTestItem</a>",
						'LDOrderCat' => "<a href=\"edit_hospital_services.php".URL_APPEND."&service=HS\">$LDEditHospitalServiceItems</a>",
						'LDOrderArchive' => "<a href=\"edit_hospital_services.php".URL_APPEND."&service=LT\">$LDEditLabTestItems</a>",
						'LDPharmaDb' => "<a href=\"search.php".URL_APPEND."\">$LDSearchPatient</a>",
						'LDReport15' => "<a href=\"report15day.php".URL_APPEND."\">$LDReport15</a>",
						'LDReportMonth' => "<a href=\"reportmonth.php".URL_APPEND."\">$LDReportMonth</a>",
						);

	# Create the submenu rows

	$iRunner = 0;

	while(list($x,$v)=each($aSubMenuItem)){
		$sTemp='';
		ob_start();
			if($cfg['icons'] != 'no_icon') $smarty2->assign('sIconImg','<img '.$aSubMenuIcon[$iRunner].'>');
			$smarty2->assign('sSubMenuItem',$v);
			$smarty2->assign('sSubMenuText',$aSubMenuText[$iRunner]);
			$smarty2->display('common/submenu_row.tpl');
			$sTemp = ob_get_contents();
		ob_end_clean();
		$iRunner++;
		$smarty->assign($x,$sTemp);
	}


	# Assign the submenu to the mainframe center block

	$smarty->assign('sMainBlockIncludeFile','ecombill/submenu_ecombill.tpl');

} 
else {

ob_start();
?>

<center>
&nbsp;
<p>
<?php echo $LDQuyenHanTruyXuat ?>

<p>&nbsp;<p>
<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>></a>
</center>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp);

}


  /**
 * show Template
 */

$smarty->display('common/mainframe.tpl');
?>