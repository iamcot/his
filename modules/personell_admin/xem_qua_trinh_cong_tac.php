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
$lang_tables[]='personell.php';
$lang_tables[]='prompt.php';
$lang_tables[]='person.php';
define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/core/inc_date_format_functions.php');
$thisfile=basename(__FILE__);
$breakfile='personell_register_show.php'.URL_APPEND.'&from=such&&personell_nr='.$nr.'&target=personell_search';
	
$admissionfile='aufnahme_start.php'.URL_APPEND;
require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj= new Personell();

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle', "$LDPersonnelManagement :: $LDQuatrinhcongtac ($nr)");

 # hide return button
 $smarty->assign('pbBack',FALSE);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('employee_show.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDPersonnelManagement :: $LDQuatrinhcongtac ($nr)");

# Buffer page output

ob_start();
?>

<table width=100% border=0 cellspacing="0"  cellpadding=0 >



<tr>
<td colspan=3>
<?php
$sql="select date_end from care_personell_assignment where personell_nr='".$nr."' ";
$buf=$db->Execute($sql);
if(($buf->RecordCount())>=1){

	while($buf2=$buf->FetchRow()){
		if($buf2['date_end'] != '0000-00-00'){
			$sql="Select d.LD_var,pa.chucvu_nr,pa.date_start,pa.date_end 
					from care_department as d,care_personell_assignment as pa
					where d.nr=(select location_nr from care_personell_assignment where personell_nr='".$nr."' and date_end='".$buf2['date_end']."')
							and pa.date_end='".$buf2['date_end']."'";
							
			$temp=$db->Execute($sql);
			$temp->RecordCount();
			$temp2=$temp->FetchRow();
			echo '<tr bgcolor="white">
			<td class="adm_item"> &nbsp;'.$LDDepartmentPast.' :
			</td>';
			echo'<td colspan=2 class="adm_input">';
			echo $$temp2['LD_var'];
			echo '</td></tr>';
			echo '<tr bgcolor="white">
			<td class="adm_item"> &nbsp;-&nbsp;'.$LDDateJoinPast.' :
			</td>';

			echo'<td colspan=2 class="adm_input">'.@formatDate2Local($temp2['date_start'],$date_format).'';
			echo '</td></tr>';
			echo '<tr bgcolor="white">
			<td class="adm_item"> &nbsp;-&nbsp;'.$LDDateExitPast.' :
			</td>';
			echo'<td colspan=2 class="adm_input">'.@formatDate2Local($temp2['date_end'],$date_format).'';
			echo '</td></tr>';
			$sql1="select LD_var from care_type_chucvu where nr='".$temp2['chucvu_nr']."'";
			$tem=$db->Execute($sql1);
			echo '<tr bgcolor="white">
			<td class="adm_item"> &nbsp;-&nbsp;'.$LDChucVu.' :
			</td>';
			if($tem->RecordCount()){
				$tem2=$tem->FetchRow();
				echo'<td colspan=2 class="adm_input">'.$$tem2['LD_var'].'';
			}else{
				echo'<td colspan=2 class="adm_input">Không';
			}
				echo '</td></tr>';
		}else{
			$sql="Select d.LD_var,pa.chucvu_nr,pa.date_start,pa.date_end 
					from care_department as d,care_personell_assignment as pa
					where d.nr=(select location_nr from care_personell_assignment where personell_nr='".$nr."' and date_end='".$buf2['date_end']."')
							and pa.date_end='".$buf2['date_end']."'";
							
			$temp=$db->Execute($sql);
			$temp->RecordCount();
			$temp2=$temp->FetchRow();
			echo '<tr bgcolor="white">
			<td class="adm_item"> &nbsp;'.$LDDepartmentNow.' :
			</td>';
			echo'<td colspan=2 class="adm_input">';
			echo $$temp2['LD_var'];
			echo '</td></tr>';
			echo '<tr bgcolor="white">
			<td class="adm_item"> &nbsp;-&nbsp;'.$LDDateJoinNow.' :
			</td>';

			echo'<td colspan=2 class="adm_input">'.@formatDate2Local($temp2['date_start'],$date_format).'';
			echo '</td></tr>';
		
			$sql1="select LD_var from care_type_chucvu where nr='".$temp2['chucvu_nr']."'";
			$tem=$db->Execute($sql1);
			echo '<tr bgcolor="white">
			<td class="adm_item"> &nbsp;-&nbsp;'.$LDChucVu.' :
			</td>';
			if($tem->RecordCount()){
				$tem2=$tem->FetchRow();
				echo'<td colspan=2 class="adm_input">'.$$tem2['LD_var'].'';
			}else{
				echo'<td colspan=2 class="adm_input">Không';
			}
				echo '</td></tr>';
		}
	}

} else{
	echo 'Chưa phân công công tác cho người này';
}
?>

</ul>

<p>
</td>
</tr>
</table>        
<p>

<ul>
<a href="<?php echo $breakfile;?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> alt="<?php echo $LDCancelClose ?>"></a>
</ul>

<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>