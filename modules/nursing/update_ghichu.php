<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'nursing.php');
$local_user='ck_pflege_user';
require_once ($root_path . 'include/core/inc_front_chain_lang.php');
//require_once ($root_path . 'include/core/inc_config_color.php'); // load color preferences
include_once ($root_path . 'include/core/inc_date_format_functions.php') ;
/* Create charts object */
require_once ($root_path.'include/care_api_classes/class_charts.php');
$charts_obj=new Charts;

require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();

$thisfile=basename(__FILE__);

?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $LDUpdate; ?></TITLE>

<script language="javascript" src="<?php echo $root_path; ?>js/jscalendar/jquery.min.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/jscalendar/jquery.maskedinput-1.3.js"></script>
<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
<script>
function saveitem(){
	document.updateform.action="<?php echo $thisfile ?>?mode=update"; 
	document.updateform.submit();
}	
function deleteitem(){
	document.updateform.action="<?php echo $thisfile ?>?mode=delete";
	document.updateform.submit();
}

</script>
</HEAD>
<BODY>

<form name="updateform" method="post">

&nbsp;<font size=4 color=maroon><?php echo $LDUpdate; ?></font>
<br>
<?php
	if($mode=='update' && $nr!=''){	
		$saved=0;		
		$note_array=array();
		$note_array['encounter_nr'] =$pn;
		$note_array['time']=strtr($ttime,'.,;-/_','::::::').':00';
		$note_array['modify_id']=$_SESSION['sess_user_name'];

		if($tdata!=''){		
			$note_array['notes']=$tdata;
			if($charts_obj->updateChartNotes($note_array,$nr)) $saved=1;
			$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $charts_obj->getLastQuery(), date('Y-m-d H:i:s'));
		}
			
		if($saved){
			echo '<script language="javascript">
					window.opener.refresh();
					window.close();	
				</script>';
		}else{
			echo '<script language="javascript">
					alert("'.$LDCannotUpdate.'");	
				</script>';
		}	
	}elseif($mode=='delete' && $nr!=''){ 
		if($charts_obj->deleteChartNotes($nr)) $saved=1;
		$logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $charts_obj->getLastQuery(), date('Y-m-d H:i:s'));
		
		if($saved){
			echo '<script language="javascript">
					window.opener.refresh();
					window.close();	
				</script>';
		}else{
			echo '<script language="javascript">
					alert("'.$LDCannotUpdate.'");	
				</script>';
		}
	}else{
	
	}

if($nr!=''){
	if($item = $charts_obj->getChartNotesByNr($nr)){
		$t_time= strtr(substr($item['time'],0,5),':','.');
		echo '&nbsp;'.$LDDay.'&nbsp; &nbsp;'.@formatDate2Local($item['date'],'dd/mm/yyyy').'<p>'; 
		echo '<table bgcolor="#FFC"><tr><th>'.$LDClockTime.'</th><th>'.$LDExtraNotes.'</th></tr>';
		echo '<tr><td valign="top"><input type="text" id="ttime" name="ttime" size=6 maxlength=5 value="'.$t_time.'" onBlur="checkTime(this)">
					<script language="javascript">
						$(function(){
							$("#ttime").mask("**:**");
						});
					</script>
				</td>';
		echo '<td> <textarea cols="30" rows="5" name="tdata"  wrap = "physical">'.$item['notes'].'</textarea></td>';
		echo '</tr></table>';
	}
}

?>
<p>&nbsp;
<a href="javascript:saveitem();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> title="<?php echo $LDSave ?>"></a>&nbsp;
<a href="javascript:deleteitem();"><img <?php echo createLDImgSrc($root_path,'delete.gif','0') ?> title="<?php echo $LDDelete ?>"></a>&nbsp;
<a href="javascript:window.close();"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> title="<?php echo $LDClose ?>"></a>

<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="nr" value="<?php echo $nr ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="winid" value="<?php echo $winid ?>">
<input type="hidden" name="station" value="<?php echo $station ?>">
<input type="hidden" name="yr" value="<?php echo $yr ?>">
<input type="hidden" name="mo" value="<?php echo $mo ?>">
<input type="hidden" name="dy" value="<?php echo $dy ?>">
<input type="hidden" name="dyidx" value="<?php echo $dyidx ?>">
<input type="hidden" name="dystart" value="<?php echo $dystart ?>">
<input type="hidden" name="monstart" value="<?php echo $monstart ?>">
<input type="hidden" name="yrstart" value="<?php echo $yrstart ?>">
<input type="hidden" name="dyname" value="<?php echo $dyname ?>">
<input type="hidden" name="pn" value="<?php echo $pn ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
</form>

</BODY>
</HTML>