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

switch($type){
	case 1: 	$title=$LDBp;			//Huyet ap
				$unit='mmHg';
				$data_array['unit_nr']=14;
					break;
	case 2:		$title=$LDMach;			//Mach
				$unit='L/ph';
				$data_array['unit_nr']=19;
					break;
	case 3:		$title=$LDTemp;			//Nhiet do
				$unit='C';
				$data_array['unit_nr']=15;
					break;
	case 6: 	$title=$LDWeight;		//Can nang
				$unit='kg';
				$data_array['unit_nr']=6;
					break;
	case 10: 	$title=$LDBreath;		//Nhip tho
				$unit='L/ph';
				$data_array['unit_nr']=19;
					break;	
	default:  	$title=$LDBp; $unit='mmHg';	
				$data_array['unit_nr']=14;
					break;
}
$maxelement=10;

	/* Load date formatter */
      include_once($root_path.'include/core/inc_date_format_functions.php');
	// get orig data
	if($mode=='save'){
		$saved=0;
		$data_array=array();
		$data_array['encounter_nr']=$pn;
        $data_array['measured_by']=$_SESSION['sess_user_name'];
		$data_array['msr_date']=date('Y-m-d',mktime(0,0,0,$mo,$dy,$yr)); 
		// Save the blood pressure data
		for($i=0;$i<$maxelement;$i++)
		{
			$tdx="btime".$i;$ddx="bdata".$i;
			if(empty($$tdx) || empty($$ddx)) continue;
			$data_array['msr_time']=strtr($$tdx,'.,;-/_','::::::');
			$data_array['value']=$$ddx;
			if($type==1){									//Huyet ap
				if($charts_obj->saveHuyetApFromArray($data_array)) $saved=1;
			} else if ($type==2){							//Mach
				if($charts_obj->saveMachFromArray($data_array)) $saved=1;									
			} else if ($type==3){							//Nhiet do
				if($charts_obj->saveTemperatureFromArray($data_array)) $saved=1;									
			} else if ($type==6){							//Can nang
				if($charts_obj->saveCanNangFromArray($data_array)) $saved=1;									
			} else if ($type==10){							//Nhip tho
				if($charts_obj->saveNhipThoFromArray($data_array)) $saved=1;
			}	
		}
		
		//if($saved){
			header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=1&pn=$pn&station=$station&winid=$winid&yr=$yr&mo=$mo&dy=$dy&dyidx=$dyidx&yrstart=$yrstart&monstart=$monstart&dystart=$dystart&dyname=$dyname&type=$type");
			exit;
		//}	
	// end of if(mode==save)
	}else{ 
		include_once($root_path.'modules/news/includes/inc_editor_fx.php');
		include_once($root_path.'include/core/inc_date_format_functions.php');
		$mscount=0;
		$tempcount=0;
		$chart_measure=$charts_obj->getDayMeasure($pn,$type,date('Y-m-d',mktime(0,0,0,$mo,$dy,$yr)));		
		if(is_object($chart_measure)){
			$mscount=$chart_measure->RecordCount();
		}	
		
	 }
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
 function updateitem(nr){
	urlholder="update_machnhietdo.php?nr="+nr+"<?php echo "&sid=$sid&lang=$lang&edit=$edit&station=$station&pn=$pn"; ?>";
	updatevalue=window.open(urlholder,"update","width=400,height=250,menubar=no,resizable=yes,scrollbars=yes");
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

<STYLE type=text/css>
div.box { border: double; border-width: thin; width: 100%; border-color: black; }
.v12 { font-family:verdana,arial;font-size:12; }
.v12 { font-family:verdana,arial;font-size:13; }
</style>

</HEAD>
<BODY  bgcolor="#99ccff" TEXT="#000000" LINK="#0000FF" VLINK="#800080"   topmargin="0" marginheight="0" 
onLoad="<?php if($saved) echo "parentrefresh();"; ?>if (window.focus) window.focus(); window.focus();" >
<table border=0 width="100%">
  <tr>
    <td><b><font size="4" color=maroon>
<?php 
	echo $title.'</font><p><font size="2">';	
	echo $LDFullDayName[$dyidx].' ('.formatDate2Local(date('Y-m-d',mktime(0,0,0,$mo,$dy,$yr)),$date_format).')</font>';
?>
	</b>
	</td>
    <td align="right" valign="top"><a href="javascript:gethelp('nursing_feverchart_xp.php','<?php echo $winid ?>','','','<?php echo $title ?>')"><img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?>  <?php if($cfg['dhtml'])echo'class="fadeOut" >';?></a><a href="javascript:window.close()" ><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>  <?php if($cfg['dhtml'])echo'class="fadeOut" >';?></a></nobr>
</td>
  </tr>
</table>

<font face=verdana,arial size=3 >
<form name="infoform" action="<?php echo $thisfile ?>" method="post" onSubmit="return pruf(this)">
<font face=verdana,arial size=2 >


<table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0>
  <tr>
    <td>
<table border=0 width=100% cellspacing=1>
<?php
if($mscount||$tempcount){
	$rcount=($mscount<$tempcount)?$tempcount:$mscount;
?>
  <tr>
    <td align=center bgcolor="#ffffff">
	
<?php
	$tbg= 'background="'.$root_path.'gui/img/common/'.$theme_com_icon.'/tableHeaderbg3.gif"';
	$temptimenow=date('H.i');
?>
 <table border=0 cellpadding=1 cellspacing=1 width=100%>
  <tr bgcolor="#f6f6f6">
    <td <?php echo $tbg; ?> width="30%"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTime; ?></td>
    <td <?php echo $tbg; ?> width="40%"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDCurrentEntry; ?></td>
	<td <?php echo $tbg; ?>><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDUpdate; ?></td>
  </tr>
<?php
	$toggle=0;
	$bb=array();
	for($i=0;$i<$rcount;$i++){
		if($mscount) $bb=$chart_measure->FetchRow();	
		if($toggle) $bgc='#efefef';
			else $bgc='#f0f0f0';
		$toggle=!$toggle;	
?>

  <tr  bgcolor="<?php echo $bgc; ?>"  valign="top">
    <td><FONT SIZE=1  FACE="Arial" color="#000033">
	<?php 
		if(!empty($bb['msr_time'])) echo $bb['msr_time']; 
	?>
	</td>
    <td><FONT SIZE=-1  FACE="Arial"><?php if($bb['value']) echo $bb['value']; else echo '&nbsp;'; ?></td>
	<td align="right"><?php if($bb['value']) echo '<a href="javascript:updateitem(\''.$bb['nr'].'\')"><img '.createComIcon($root_path,'pencil.gif','0').' title="'.$LDUpdate.'" ></a>'; ?></td>
  </tr>

<?php
	}
?>
</table>
  </tr>
<?php
}
?>

  <tr>
    <td  align=center bgcolor="#cfcfcf" class="v13"><font color="#ff0000"><?php echo $title ?></td>
  </tr>


  <tr>
    <td align=center bgcolor="#ffffff">
	
		<table border=0 border=0 cellspacing=0 cellpadding=0>
			<tr>
   			 <td  align=center class="v12"><?php echo $LDClockTime ?>:</td>
   			 <td  align=center class="v12"><?php echo $LDCurrentEntry; ?>: </td>
			 <td></td>
		  </tr>
			<?php 
			$bb=array();
			//$chart_measure->MoveFirst();
			for($i=0;$i<$maxelement;$i++)
			{
				//if($mscount) $bb=$chart_measure->FetchRow();
				if($i==0) $tempvalue=$temptimenow;
				else $tempvalue='';
				echo '
 						 <tr>
   						 <td ><input type="text" id="btime'.$i.'" name="btime'.$i.'" size=6 maxlength=5 value="'.$tempvalue.'" onBlur="checkTime(this)">
								<script language="javascript">
									$(function(){
										$("#btime'.$i.'").mask("**:**");
									});
								</script>
        				</td>
   						 <td class="v12"><input type="text" name="bdata'.$i.'" size=8 maxlength=7 value="" onBlur="checkNumberItem(this)"> </td><td>&nbsp;('.$unit.')</td>
  						</tr>
 						 ';
				}
 			?>
		</table>
	
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
<input type="hidden" name="type" value="<?php echo $type; ?>"> 
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