<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'nursing.php');
$local_user='ck_pflege_user';
require_once ($root_path . 'include/core/inc_front_chain_lang.php');
include_once($root_path.'modules/news/includes/inc_editor_fx.php');
include_once ($root_path . 'include/core/inc_date_format_functions.php') ;
/* Create charts object */
require_once ($root_path.'include/care_api_classes/class_charts.php');
$charts_obj=new Charts;

//ghi log
require_once($root_path.'include/core/access_log.php');
$logs = new AccessLog();

$thisfile=basename(__FILE__);

?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $LDUpdate; ?></TITLE>

<script language="javascript" src="<?php echo $root_path; ?>js/setdatetime.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/chkValidTime.js"></script>
<script>
function saveitem(){
	document.updateform.action="<?php echo $thisfile ?>?mode=update"; 
	document.updateform.submit();
}	
function deleteitem(){
	document.updateform.action="<?php echo $thisfile ?>?mode=delete";
	document.updateform.submit();
}
function sethilite(d){
	d.focus();
	d.value=d.value+"~";
	d.focus();
	}
function endhilite(d){
	d.focus();
	d.value=d.value+"~~";
	d.focus();
	}
function checkTime(d){
    var itemtime= d.value;
    var time= itemtime.split(":");
    if(time[0].length<=2 && time[1].length<=2){
		if(parseInt(time[0])>23){
                    alert("<?php echo $LDWarningHour; ?>");
                    d.focus();                    
                    return false;
		}
		if(parseInt(time[1])>59){
                    alert("<?php echo $LDWarningMinute; ?>");
                    d.focus();                    
                    return false;
		}
		if(time[1].length>2){
                    alert("<?php echo $LDWarningHour; ?>");
                    d.focus();                    
                    return false;
		}
        return true;
    }else{  
        alert("<?php echo $LDWarningTime; ?>");
        d.focus();
        return false;
    }
}
</script>
</HEAD>
<BODY>

<form name="updateform" method="post">

<font size=5 face="arial" color=maroon><?php echo $LDCapNhatPhieuChamSoc; ?></font>
<p>
<?php
	if($mode=='update' && $nr!=''){	
		$saved=0;		
		$note_array=array();
		$note_array['modify_id']=$_SESSION['sess_user_name'];

		if($dateput&&$ttime&&$tdata&&$author){	
			$note_array['date']=formatDate2STD($dateput,$date_format);
			$note_array['time']=$ttime.':00';					
			$note_array['notes']=deactivateHotHtml($tdata);	
			if($warn)
				$note_array['aux_notes']=$warn;
			else $note_array['aux_notes']=' ';
			
			$note_array['morenote']=deactivateHotHtml($tdatamore);
			if($warn_aux)
				$note_array['aux_morenote']=$warn_aux;
			else $note_array['aux_morenote']=' ';	
			$note_array['personell_name']=$author;

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
		echo '<table bgcolor="#FFC">
				<tr><th>'.$LDDate.'</th><th>'.$LDClockTime.'</th><th>'.$LDTheoDoiDienBien.'</th><th>'.$LDThucHienYLenh.'</th><th>'.$LDSignature.'</th></tr>';
		
		echo '<tr><td valign="top">';		
			//gjergji : new calendar
			require_once ('../../js/jscalendar/calendar.php');
			$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
			$calendar->load_files();
			//end : gjergji			
			echo $calendar->show_calendar($calendar,$date_format,'dateput',$item['date']);
			
		echo '</td><td valign="top">';
		echo 	'<input type=text size=4 maxlength=5 name="ttime"  value="'.substr($item['time'],0,5).'" onBlur="checkTime(this)" >';
		
		echo '</td><td>';
		echo 	'<textarea cols="30" rows="4" name="tdata" wrap="physical">'.$item['notes'].'</textarea><br>';
		echo 	'<input type="checkbox" name="warn" ';
				if($item['aux_notes']=='warn') echo " checked ";  
		echo	'value="warn"> <img '.createComIcon($root_path,'warn.gif','0','top',TRUE).'><font size=1 face=arial>'.$LDInsertSymbol.'<br><font size=2><b>&nbsp;
		<a href="javascript:sethilite(document.updateform.tdata)"><img '.createComIcon($root_path,'color_marker_yellow.gif','0','',TRUE).'> '.$LDStart.'</a>
		<a href="javascript:endhilite(document.updateform.tdata)"><img '.createComIcon($root_path,'color_marker_yellow.gif','0','',TRUE).'>'.$LDEnd .'</a>';
		
		echo '</td><td>';
		echo 	'<textarea cols="30" rows="4" name="tdatamore" wrap="physical">'.$item['morenote'].'</textarea><br>';
		echo 	'<input type="checkbox" name="warn_aux" ';
				if($item['aux_morenote']=='warn') echo " checked ";  
		echo	'value="warn"> <img '.createComIcon($root_path,'warn.gif','0','top',TRUE).'><font size=1 face=arial>'.$LDInsertSymbol.'<br><font size=2><b>&nbsp;
		<a href="javascript:sethilite(document.updateform.tdatamore)"><img '.createComIcon($root_path,'color_marker_yellow.gif','0','',TRUE).'> '.$LDStart.'</a>
		<a href="javascript:endhilite(document.updateform.tdatamore)"><img '.createComIcon($root_path,'color_marker_yellow.gif','0','',TRUE).'>'.$LDEnd .'</a>';
		
		echo '</td><td valign="top">';
		echo 	'<textarea rows="4" cols="10" name="author">'.$item['personell_name'].'</textarea>';
	
		echo '</td></tr></table>';
		
	
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
<input type="hidden" name="pn" value="<?php echo $pn ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">

</form>

</BODY>
</HTML>