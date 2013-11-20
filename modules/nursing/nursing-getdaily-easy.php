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
$lang_tables=array('date_time.php');
define('LANG_FILE','nursing.php');
$local_user='ck_pflege_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
//$db->debug=true;
$thisfile=basename(__FILE__);
/* Create charts object */
require_once($root_path.'include/care_api_classes/class_charts.php');
$charts_obj= new Charts;

$title=$LDGhiNhanh;
$temptimenow=date('H.i');
$maxelement=10;

/* Load date formatter */
include_once($root_path.'include/core/inc_date_format_functions.php');
include_once($root_path.'modules/news/includes/inc_editor_fx.php');

?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo "$title $LDInputWin" ?></TITLE>
<?php
require($root_path.'include/core/inc_js_gethelp.php');
require($root_path.'include/core/inc_css_a_hilitebu.php');

?>
<script language="javascript" src="<?php echo $root_path; ?>js/jscalendar/jquery.min.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/jscalendar/jquery.maskedinput-1.3.js"></script>
<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
<script language="javascript">
<!-- 
  function resetinput(){
	document.infoform.reset();
	}

 function pruf(d){
	if(!d.newdata.value) return false;
	else return true
	}
 function parentrefresh(){
	window.opener.location.href="nursing-station-patientdaten-kurve.php?sid=<?php echo "$sid&lang=$lang&edit=$edit&station=$station&pn=$pn&tag=$dystart&monat=$monstart&jahr=$yrstart&tagname=$dyname" ?>&nofocus=1";
	}
function refresh(){
	location.reload(true);
	parentrefresh();
}	
function checkNumberItem(d){
	d.value = d.value.replace(',','.');
	if (d.value!="" && checkIsNumber(d.value)==false){
		alert("<?php echo $LDAlertInputNumber; ?>");
		d.focus();
		return false;
	}
	return true;
}
//-->
</script>

<?php
	if($mode=='save'){	
		$saved=0;
		$data_array=array();
		$data_array['encounter_nr']=$pn;
        $data_array['measured_by']=$_SESSION['sess_user_name'];	
		$data_array['msr_date']=date('Y-m-d',mktime(0,0,0,$mo,$dy,$yr)); 
		
		$note_array=array();
		$note_array['encounter_nr'] =$pn;
		$note_array['date']=date('Y-m-d',mktime(0,0,0,$mo,$dy,$yr));
		$note_array['personell_name']=$_SESSION['sess_user_name'];
 
		// Save the blood pressure data
		for($i=0;$i<$maxelement;$i++)
		{
			$tdx="btime".$i; $mdx="mach".$i; $nddx="nhietdo".$i; $hadx="huyetap".$i; $cndx="cannang".$i; $ntdx="nhiptho".$i;
			$notedx="note".$i; $note5dx="note5".$i;
			
			if(empty($$tdx)) continue;
			
			$data_array['msr_time']=strtr($$tdx,'.,;-/_','::::::');
			$note_array['time']=$data_array['msr_time'];
			
			if($$mdx!=''){		//Mach
				$data_array['value']=$$mdx;
				$data_array['unit_nr']=19;
				if($charts_obj->saveMachFromArray($data_array)) $saved=1;
			}
			if($$nddx!=''){		//Nhiet do
				$data_array['value']=$$nddx;
				$data_array['unit_nr']=15;
				if($charts_obj->saveTemperatureFromArray($data_array)) $saved=1;
			}
			if($$hadx!=''){		//Huyet ap
				$data_array['value']=$$hadx;
				$data_array['unit_nr']=14;
				if($charts_obj->saveHuyetApFromArray($data_array)) $saved=1;
			}
			if($$cndx!=''){		//Can nang
				$data_array['value']=$$cndx;
				$data_array['unit_nr']=6;
				if($charts_obj->saveCanNangFromArray($data_array)) $saved=1;
			}
			if($$ntdx!=''){		//Nhip tho
				$data_array['value']=$$ntdx;
				$data_array['unit_nr']=19;
				if($charts_obj->saveNhipThoFromArray($data_array)) $saved=1;
			}
			if($$notedx!=''){		//Ghi chu 4 
				$note_array['notes']=$$notedx;
				if($charts_obj->saveChartNotesFromArray($note_array,7)) $saved=1;
			}
			if($$note5dx!=''){		//Ghi chu 5 
				$note_array['notes']=$$note5dx;
				if($charts_obj->saveChartNotesFromArray($note_array,41)) $saved=1;
			}
		}
		
		if($saved){
			echo '<script language="javascript">
					parentrefresh();
					window.close();	
				</script>';
		}else{
			echo '<script language="javascript">
					alert("'.$LDCannotUpdate.'");	
				</script>';
		}	
	}else{ // end of if(mode==save)

	}
?>
<STYLE type=text/css>
div.box { border: double; border-width: thin; width: 100%; border-color: black; }
.v12 { font-family:verdana,arial;font-size:12;}
.v13 { font-family:verdana,arial;font-size:13; }
</style>

</HEAD>
<BODY  bgcolor="#99ccff" TEXT="#000000" LINK="#0000FF" VLINK="#800080"   topmargin="0" marginheight="0" 
onLoad="<?php if($saved) echo "parentrefresh();"; ?>if (window.focus) window.focus(); window.focus();" >
<table border=0 width="100%">
  <tr>
    <td><b><font size=3 color=maroon>
<?php 
	echo $title.'</font><p><font size=2>';	
	echo $LDFullDayName[$dyidx].' ('.formatDate2Local(date('Y-m-d',mktime(0,0,0,$mo,$dy,$yr)),$date_format).')</font>';
?>
	</b>
	</td>
    <td align="right" valign="top"><a href="javascript:gethelp('nursing_feverchart_xp.php','<?php echo $winid ?>','','','<?php echo $title ?>')"><img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?>  <?php if($cfg['dhtml']) echo'class="fadeOut" >'; ?> </a> <a href="javascript:window.close()" ><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>  <?php if($cfg['dhtml'])echo'class="fadeOut" >'; ?>  </a></nobr>
</td>
  </tr>
</table>

<font face=verdana,arial size=3 >
<form name="infoform" action="<?php echo $thisfile ?>" method="post" onSubmit="return pruf(this)">
<font face=verdana,arial size=2 >


<table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0>
  <tr>
    <td>
<table border=0 width=100% cellspacing=1 >

  <tr>
	<td  align=center bgcolor="#cfcfcf" class="v13"><font color="#ff0000"><?php echo $LDGhiNhanhInfo; ?></td>
  </tr>
  <tr>
    <td align=center bgcolor="#ffffff">
		<br>
		<table border=0 cellspacing=0 cellpadding=0 width="95%">
			<tr valign="top">
				<td  align=center class="v12"><?php echo $LDClockTime ?></td>
				<td  align=center class="v12"><?php echo $LDMach.'<br>(L/ph)'; ?></td>
				<td  align=center class="v12"><?php echo $LDTemp.'<br>(C)'; ?></td>
				<td  align=center class="v12"><?php echo $LDBp.'<br>(mmHg)'; ?></td>
				<td  align=center class="v12"><?php echo $LDWeight.'<br>(kg)'; ?></td>
				<td  align=center class="v12"><?php echo $LDBreath.'<br>(L/ph)'; ?></td>
				<td  align=center class="v12"><?php echo '4.' ?></td>
				<td  align=center class="v12"><?php echo '5.' ?></td>
			</tr>

<?php 
	$bb=array();
	for($i=0;$i<$maxelement;$i++)
	{
		if($i==0) $tempvalue=$temptimenow;
		else $tempvalue='';
		echo '<tr>
   				<td><input type="text" id="btime'.$i.'" name="btime'.$i.'" size=3 maxlength=5 value="'.$tempvalue.'" onBlur="checkTime(this)">
					<script language="javascript">
						$(function(){
							$("#btime'.$i.'").mask("**:**");
						});
					</script>
				</td>
   				<td><input type="text" name="mach'.$i.'" size=8 maxlength=10 value="" onBlur="checkNumberItem(this)"> </td>
				<td><input type="text" name="nhietdo'.$i.'" size=8 maxlength=7 value="" onBlur="checkNumberItem(this)"> </td>
				<td><input type="text" name="huyetap'.$i.'" size=8 maxlength=7 value="" onBlur="checkNumberItem(this)"></td>
				<td><input type="text" name="cannang'.$i.'" size=8 maxlength=7 value="" onBlur="checkNumberItem(this)"> </td>
				<td><input type="text" name="nhiptho'.$i.'" size=8 maxlength=7 value="" onBlur="checkNumberItem(this)"> </td>
				<td><textarea name="note'.$i.'" cols="15" rows = "1" wrap = "physical"></textarea> </td>
				<td><textarea name="note5'.$i.'" cols="15" rows = "1" wrap = "physical"></textarea> </td>
  			</tr>';
	}
 ?>
		</table>
		<br>
	</td>
  </tr>
</table>
</td>
  </tr>
</table>


<input type="hidden" name="sid" value="<?php echo $sid ?>">
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
<input type="hidden" name="mode" value="save">

</form>
<p>
<center>
<a href="javascript:document.infoform.submit();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> alt="<?php echo $LDSave ?>"></a>
&nbsp;&nbsp;
<!-- <a href="javascript:resetinput()"><img <?php echo createLDImgSrc($root_path,'reset.gif','0') ?> alt="<?php echo $LDReset ?>"></a>
 -->&nbsp;&nbsp;
<?php if($saved)  : ?>
<a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClose ?>"></a>
<?php else : ?>
<a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> alt="<?php echo $LDClose ?>">
</a>
<?php endif ?>
</center>
</BODY>

</HTML>