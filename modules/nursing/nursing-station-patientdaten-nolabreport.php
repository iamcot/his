<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','nursing.php');
$lang_tables=array('departments.php');

define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

$sql="SELECT dr.*, e.encounter_class_nr 
		FROM care_encounter AS e, care_encounter_diagnostics_report AS dr 
		WHERE e.encounter_nr='".$pn."'
		AND e.encounter_nr=dr.encounter_nr 
		ORDER BY dr.create_time DESC";

if($result=$db->Execute($sql)){
	$rows=$result->RecordCount();
	include_once($root_path.'include/care_api_classes/class_department.php');
	$dept_obj=new Department();
	$depts_array=&$dept_obj->getAll();
}else{
	echo $sql;
}

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('nursing');

# Title in toolbar
 $smarty->assign('sToolbarTitle',"$LDPatDataFolder $station");

 # hide return button
 $smarty->assign('pbBack',FALSE);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('patient_folder.php',' $nodoc','','$station','Main folder')");

 # href for close button
 $smarty->assign('breakfile','javascript:document.retform.submit()');

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDPatDataFolder $station");
 
 ob_start();
 
?>
<center>
<img <?php echo createMascot($root_path,'mascot1_r.gif','0','absmiddle'); ?>> &nbsp;
<form method="post" action="<?php echo $root_path.'modules/nursing/nursing-station-patientdaten.php'; ?>" name="retform">
<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
  <tr bgcolor="#f6f6f6">
    <th <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDXem; ?></th>
    <th <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDReportNr; ?></th>
    <th <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDReportingDept; ?></th>
    <th <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDEncounterID; ?></th>
    <th <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDDate; ?></th>
    <th <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDTime; ?></th>
  </tr>
  
<?php
while($row=$result->FetchRow()){

	$buf=$root_path.'modules/laboratory/'.(str_replace('?',URL_APPEND.'&',$row['script_call'])).'&pn='.$row['encounter_nr'];
	if($row['encounter_class_nr']==1) $full_en=$row['encounter_nr']+$GLOBAL_CONFIG['patient_inpatient_nr_adder']; // inpatient admission
		else $full_en=$row['encounter_nr']+$GLOBAL_CONFIG['patient_outpatient_nr_adder']; // outpatient admission
?>

  <tr bgcolor="#fefefe">
    <td><a href="<?php echo $buf; ?>&user_origin=patreg" target="_new"><img <?php echo createComIcon($root_path,'info3.gif','0','',TRUE); ?>></a></td>
    <td><?php echo $row['report_nr']; ?></td>
    <td><FONT color="#006600"><b>
	<?php 
		$deptnr_ok=false;
		while(list($x,$v)=each($depts_array)){
			if($v['nr']==$row['reporting_dept_nr']){
				$deptnr_ok=true;
				break;
			}
		}
		reset($depts_array);
		if($deptnr_ok){
			if(isset($$v['LD_var'])&&!empty($$v['LD_var'])) echo $$v['LD_var'];
				else echo $v['name_formal'];
		}else{
			echo $row['reporting_dept'];
		}
	 ?></b>
	</td>
    <td><?php echo $full_en; ?></td>
    <td><?php echo @formatDate2Local($row['report_date'],$date_format); ?></td>
    <td><?php echo $row['report_time']; ?></td>
  </tr>

<?php
}
?>
</table> 
	<input type="hidden" name="sid" value="<?php echo $sid; ?>">
 	<input type="hidden" name="lang" value="<?php echo $lang; ?>">
	<input type="hidden" name="pn" value="<?php echo $pn; ?>">
	<input type="hidden" name="edit" value="<?php echo $edit; ?>">
	<input type="hidden" name="station" value="<?php echo $station; ?>">   
	<p>
	<input type="submit" value=" OK "> 
</form>
</center>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

/*
$sTemp= '<ul><p><br>
	<center><FONT class="warnprompt"><p><br>
	<img '.createMascot($root_path,'mascot1_r.gif','0','absmiddle').'> &nbsp;
	<b>'.$LDNoLabReport.'</b><p>
		<form method="post" action="'.$root_path.'modules/nursing/nursing-station-patientdaten.php" name="retform">

    </form>
	</center>
	<p>
</ul>';
*/

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
