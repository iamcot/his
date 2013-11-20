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
$lang_tables=array('departments.php');
define('LANG_FILE','nursing.php');

require('./include/inc_admit_station_bridge.php');

require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'modules/news/includes/inc_editor_fx.php'); 
/* Load the data time shifter and create object */
require_once($root_path.'classes/datetimemanager/class.dateTimeManager.php');
$dateshifter=new dateTimeManager();

///$db->debug=true;

$thisfile=basename(__FILE__);
$breakfile="nursing-station-patientdaten.php".URL_APPEND."&station=$station&pn=$pn&edit=$edit";

if(!$kmonat) $kmonat=date('n');

if(!$tag) $tag=date('j');

if(!$jahr) $jahr=date('Y');

if($dayback)
{ 
	if($tag>$dayback)
	{
		$tag-=$dayback;
	}
	else
	{
		for($i=0;$i<$dayback;$i++)
		{
			if($tag>1) $tag--; 
			elseif($kmonat==1)
				{
				$jahr--;
				$kmonat=12;
				$tag=31;
				}
				else
				{
				$kmonat--;
				//$tag=31;
				//while(!checkdate($kmonat,$tag,$jahr)) $tag--;
				$tag=date("t",mktime(0,0,0,$kmonat,1,$jahr));
				}
		//if($tagname) $tagname--; else $tagname=6; 
		}
	}
} else if($dayfwd)
	{
		//if($tagname==7) $tagname=1; else $tagname++;			
	    $tag++;
		if(!checkdate($kmonat,$tag,$jahr))
			{
				$tag=1;
				if($kmonat==12) 
				{
					$kmonat=1; 
					$jahr++;
				}
				else $kmonat++;
			}
 		}
//echo $tagname." day ";
$tagname=date("w",mktime(0,0,0,$kmonat,$tag,$jahr));
$tagnamebuf=$tagname;

$date_start=date('Y-m-d',mktime(0,0,0,$kmonat,$tag,$jahr));
$date_end=$dateshifter->shift_dates($date_start,-6,'d');


	/* Create encounter object */
	include_once($root_path.'include/care_api_classes/class_encounter.php');
	$enc_obj= new Encounter;
	/* Load global configs */
	include_once($root_path.'include/care_api_classes/class_globalconfig.php');
	$GLOBAL_CONFIG=array();
	$glob_obj=new GlobalConfig($GLOBAL_CONFIG);
	$glob_obj->getConfig('patient_%');	

	$enc_obj->where=" encounter_nr=$pn";
	// Preload the patient encounter object
	if( $enc_obj->loadEncounterData($pn)) {

		$full_en=$pn;
		
		if( $enc_obj->is_loaded){
			$result=&$enc_obj->encounter;		
			$rows=$enc_obj->record_count;	
			/* Create charts object */
			include_once($root_path.'include/care_api_classes/class_charts.php');
			$charts_obj= new Charts;
		
			/*get Measurement Record */
			//Huyet ap: type=1
			$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
			//Can nang: type=6
			$cannang=$charts_obj->getManyDaysMeasureByType($pn,6,$date_start,$date_end);
			//Nhip tho: type=10
			$nhiptho=$charts_obj->getManyDaysMeasureByType($pn,10,$date_start,$date_end);
			
			/*get Measurement Nursing*/
			$dieuduong=$charts_obj->getManyDaysNursing($pn,$date_start,$date_end);
			

			// get Diagnosis notes  type = 12    ----------------------------------------------
			$diagnosis=$charts_obj->getChartNotes($pn,12);
			// get daily main notes (4)  ----------------------------------------------
			$main_notes=$charts_obj->getChartDailyMainNotes($pn,$date_start,$date_end);
			// get daily more notes (5)  ----------------------------------------------
			$more_notes_5=$charts_obj->getChartDailyNotes_5($pn,$date_start,$date_end);			
			
			$ward_nr = trim($result['current_ward_nr']);
			$dept_nr=trim($result['current_dept_nr']);
			if ($ward_nr!='' && $ward_nr!='0'){
				require_once($root_path.'include/care_api_classes/class_ward.php');
				$Ward = new Ward;
				if($wardinfo = $Ward->getWardInfo($ward_nr)) {
					$wardname = $wardinfo['name'];
					$deptname = ($$wardinfo['LD_var']);
					$dept_nr = $wardinfo['dept_nr'];
				}
			} elseif ($dept_nr!='' && $dept_nr!='0'){
				require_once($root_path.'include/care_api_classes/class_department.php');
				$Dept = new Department;
				if ($deptinfo = $Dept->getDeptAllInfo($dept_nr)) {
					$deptname = ($$deptinfo['LD_var']);
					$wardname = $LDAllWard;
				}
			}
			
			if($result['sex']=='m') $sex=$LDMale;
			else $sex=$LDFemale;
		}
	}else {echo $enc_obj->getLastQuery()."<p>$LDDbNoRead"; exit;}
	include_once($root_path.'include/core/inc_date_format_functions.php');


function getlatestdata($info,$d,$m,$y)
{
	if(is_object($info)){
		$ok=false;
		$date=date('Y-m-d',mktime(0,0,0,$m,$d,$y));
		while($data=$info->FetchRow()){
			if($data['date']==$date) {
				$ok=true;
				break;
			}
		}
		$info->MoveFirst();
		if($ok){
			 return $data;
		}else{return false;}
	}else{return false;}
}

function getdata($info,$d,$m,$y,$short=0){
	if(is_object($info)){
		$content='';
		$date=date('Y-m-d',mktime(0,0,0,$m,$d,$y));
		while($data=$info->FetchRow()){
			if($data['date']==$date) {
				if($short) $content=$data['short_notes']."\n".$content;
					else $content=wordwrap($data['notes'],14,"<br/>",TRUE)."\n".$content;
			}
		}
		$info->MoveFirst();
		return trim($content);
	}else{return false;}
}

function getdatameasure($info,$d,$m,$y){
	if(is_object($info)){
		$content='';
		$date=date('Y-m-d',mktime(0,0,0,$m,$d,$y));
		while($data=$info->FetchRow()){
			if($data['msr_date']==$date) {
				$content .= $data['msr_time'].":&nbsp;&nbsp;<b>".$data['value']."</b> <br>";
			}
		}
		$info->MoveFirst();
		return $content;
	}else{return false;}
}
function getdatanursing($info,$d,$m,$y){
	if(is_object($info)){
		$content='';
		$date=date('Y-m-d',mktime(0,0,0,$m,$d,$y));
		while($data=$info->FetchRow()){
			if($data['msr_date']==$date) {
				$content .= $data['measured_by']."<br>";
			}
		}
		$info->MoveFirst();
		return trim($content);
	}else{return false;}
}
function aligndate(&$ad,&$am,&$ay)
{
	if(!checkdate($am,$ad,$ay))
	{
		if($am==12)
		{
			$am=1;
			$ad=1;
			$ay++;
		}
		else
		{
			$am=$am+1;
    		$ad=1;
		}
	}
}

# Prepare title
$sTitle = "$LDFeverCurve $station ($pn";
if($kmonat==12) if($tag>25) $sTitle = $sTitle." - ".($jahr +1);
$sTitle = $sTitle.")";

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',$sTitle);

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('nursing_feverchart.php','main','','$station','Fever chart')");

 # hide return button
 $smarty->assign('pbBack',FALSE);

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',$sTitle);

 # Body Onload js
 if(!$nofocus) $smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus()"');

 # Collect js code

 ob_start();

?>

<style type="text/css" name="2">
.pblock {
	font-family: verdana, arial;
}

div.box {
	border: solid;
	border-width: thin;
	width: 100%
}

div.pcont {
	margin-left: 3;
}

.a12 {
	font-family: verdana, arial;
	font-size: 12;
}

.a10 {
	font-family: arial;
	font-size: 10;
}
</style>

<script language="javascript">
<!-- 
  var urlholder="";
  var infowinflag=0;
  var sw=window.screen.width/2;
  var sh=window.screen.height/2;
  var w600=600;
  var h400=400;
  var h600=600;
function popgetinfowin(winID,patientID,jahrID,monatID,tagID,tagS,tagN)
	{
	urlholder="nursing-popgetinfo.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid=" + winID + "&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID + "&mo=" + monatID + "&dy="+ tagID+ "&dystart="+ tagS + "&dyname="+ tagN;
	infowin=window.open(urlholder,"kurvendaten","width="+w600+",height="+h400+",menubar=no,resizable=yes,scrollbars=yes");
   	window.infowin.moveTo(sw-(w600/2),sh-(h400/2));
   	infowinflag=1;
	}
function popgetdailyinfo(winID,patientID,jahrID,monatID,tagID,tagIDX,jahrS,monatS,tagS,tagN)
	{
	urlholder="nursing-getdailyinfo.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid=" + winID + "&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID + "&mo=" + monatID + "&dy="+ tagID + "&dyidx="+ tagIDX+"&yrstart="+jahrS+"&monstart="+monatS+"&dystart="+ tagS + "&dyname="+ tagN ;
	dailywin=window.open(urlholder,"dailydaten","width=600,height=500,menubar=no,resizable=yes,scrollbars=yes");
   	infowinflag=1;
	}
function popgetdailybpt(winID,patientID,jahrID,monatID,tagID,tagIDX,jahrS,monatS,tagS,tagN)
	{
	urlholder="nursing-getdailybp_t.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid=" + winID + "&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID + "&mo=" + monatID + "&dy="+ tagID + "&dyidx="+ tagIDX +"&yrstart="+jahrS+"&monstart="+monatS+"&dystart="+ tagS + "&dyname="+ tagN ;
	dailybpt=window.open(urlholder,"dailybpt","width=600,height=600,menubar=no,resizable=yes,scrollbars=yes");
   	//window.dailybpt.moveTo(sw-(w600/2),sh-(h600/2));
   	infowinflag=1;
	}
function popgetdailymeasure(winID,patientID,jahrID,monatID,tagID,tagIDX,jahrS,monatS,tagS,type)
	{
	urlholder="nursing-getdailymeasure.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid=" + winID + "&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID + "&mo=" + monatID + "&dy="+ tagID + "&dyidx="+ tagIDX +"&yrstart="+jahrS+"&monstart="+monatS+"&dystart="+ tagS +"&type="+type;
	dailymeasure=window.open(urlholder,"dailymeasure","width=400,height=450,menubar=no,resizable=yes,scrollbars=yes");
   	//window.dailymeasure.moveTo(sw-(w600/2),sh-(h600/2));
   	infowinflag=1;
	}
function easywrite(winID,patientID,jahrID,monatID,tagID,tagIDX,jahrS,monatS,tagS){
	urlholder="nursing-getdaily-easy.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid=" + winID + "&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID + "&mo=" + monatID + "&dy="+ tagID + "&dyidx="+ tagIDX +"&yrstart="+jahrS+"&monstart="+monatS+"&dystart="+ tagS;
	dailymeasure_easy=window.open(urlholder,"dailymeasure_easy","width=700,height="+h600+",menubar=no,resizable=yes,scrollbars=yes");
   	window.dailymeasure_easy.moveTo(sw-(w600/2),sh-(h600/2));
   	infowinflag=1;
}	
function setStartDate(winID,patientID,jahrID,monatID,tagID,station,tagN){

<?php
if($cfg['bname']=='msie'){
?>
	if(event.button==2)
		{
		//alert("right click");
		if(winID=="dayback") dayID="<?php echo $LDStartDate ?>";
		if(winID=="dayfwd") dayID="<?php echo $LDEndDate ?>";
		if(confirm("<?php echo $LDConfirmSetDate ?>"))
			{
			urlholder="nursing-station-patientdaten-setstartdate.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid="+winID+"&pn=" + patientID + "&jahr=" + jahrID + "&kmonat=" + monatID + "&tag="+ tagID + "&station="+station+"&tagname="+ tagN ;
			setdatewin=window.open(urlholder,"setdatewin","width=400,height=250,menubar=no,resizable=yes,scrollbars=yes");
   			infowinflag=1;
			}
		}
		else 
<?php
}
?>
		{
		// alert("left click");	
		urlholder="nursing-station-patientdaten-kurve.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&"+winID+"=1&pn=" + patientID + "&jahr=" + jahrID + "&kmonat=" + monatID + "&tag="+ tagID + "&station="+station+"&tagname="+ tagN ;
 		window.location.replace(urlholder);
   		}
}

function closeifok()
{
	ok=0;
	if (infowinflag){
		if (window.infowin)
		{ if (window.infowin.closed) ok=1;
			else
			{
	 			window.infowin.focus()
				window.infowin.alert("Ein Eingabefenster ist noch nicht abgeschlossen")	
			}
		}
		else ok=1;
	}	
	else ok=1;
	if(ok)
	{
		window.opener.focus();
		window.close();
	}
}	
	
function returnifok(){
	if (infowinflag){
		if(window.infowin.closed)  history.go(-2)
	window.infowin.focus()
	window.infowin.alert("Ein Eingabefenster ist noch nicht abgeschlossen")	
	}
	else history.back()
	}
function printOut(){
	urlholder="<?php echo $root_path; ?>modules/pdfmaker/dieuduong/phieutheodoichucnangsong.php<?php echo URL_REDIRECT_APPEND.'&pn='.$pn.'&jahr='.$jahr.'&kmonat='.$kmonat.'&tag='.$tag; ?>";
	window.open(urlholder,'PhieuTheoDoiChucNangSong',"width=1000,height=800,menubar=no,resizable=yes,scrollbars=yes");
}	
//-->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

# Buffer page output

ob_start();

?>

<script language="">
<!-- Script Begin
var dblclk=0;
//  Script End -->
</script>

<form name="berichtform">
<?php

//****************************** Encounter number ********************************
echo '
		<table  bgcolor="#D8D8D8" cellpadding="0" cellspacing=1 border="0" >
		<tr  >
		<td bgcolor="aqua" class=pblock><font size="2" ><div class=pcont><b>'.$full_en.'</b></div></td>
		<td bgcolor="white" align="center">';
		
//****************************** Mach/ Nhiet do ********************************
echo '<table border=0 cellpadding=0 cellspacing=0 ><tr align="center"><td><font size="1" color="red">'.$LDMach.'<br>(L/<br>ph)</font></td>
			<td><font size="1" color="blue">'.$LDTemp1.'<br>(C)</font></td></tr></table>
	</td>';

//****************************** Lich (calendar) ********************************

echo '
		<td colspan="7"> 
		<table cellpadding="0"  cellspacing="0" border="1" width="100%"><tr>';

$actmonat=$kmonat;
$actjahr=$jahr;

for ($i=$tag,$acttag=$tag,$d=0,$tgbuf=$tagname;$i<($tag+7);$i++,$d++,$tgbuf++,$acttag++)
	{
	echo '<td';

	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date

	switch($tgbuf) 
		{
			case 0: echo' bgcolor="orange"';break;
			case 6: echo' bgcolor="#ffffcc"';break;
			case 7: echo' bgcolor="orange"'; $tgbuf=0;break;
			default: echo' bgcolor="white"';
		}

	if(!$d) echo' align=left width="98">';else if($d>5) echo' align=right width="98">';else echo' align=center width="98">';
	if(!$d) echo '<a href="#">
		<img '.createComIcon($root_path,'l_arrowgrnsm.gif','0','',TRUE).' title="'.$LDBackDay.'" onClick="setStartDate(\'dayback\',\''.$pn.'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',\''.$station.'\',\''.$tagname.'\');return false;"></a>';
	echo '
	<font face="verdana,arial" size="2" color="#000000" >'.formatShortDate2Local($actmonat,$acttag,$date_format).' . '.$tage[$tgbuf];
	if ($d==6) echo ' <a href="#">
		<img '.createComIcon($root_path,'r_arrowgrnsm.gif','0','',TRUE).' title="'.$LDFwdDay.'" onClick="setStartDate(\'dayfwd\',\''.$pn.'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',\''.$station.'\',\''.$tagname.'\')"></a>';
	
	echo '</td>';
	//$tgbuf++;
	echo "\n";
	}

//$tagname-=7;
$actmonat=$kmonat;	
$actjahr=$jahr;		

//****************************** Ghi nhanh ********************************
echo '</tr><tr>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date (day,month,year)
	
	echo '<td bgcolor=white align=center class="a12" width="98">';
	echo '<a href="javascript:easywrite(\'bp_temp\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\')">'.$LDGhiNhanh.'</a></td>';	
}

//**************** Patient personal data ************************************
echo '</tr></table> 
		
		</td>
		</tr>
		<tr   valign="top">
		<td bgcolor="#ffffcc" class=pblock width="130"><font size=2>
		<div class=pcont><b>'.ucfirst($result['name_last']).', '.ucfirst($result['name_first']).'</b> <br>
		<font color=maroon>'.formatDate2Local($result['date_birth'],$date_format).', '.$sex.'</font> <p>
		<font size=1>'.$deptname.'<p>'.$wardname.'<br>'.$LDRoom.': '.$result['current_room_nr'].'<br>&nbsp;</div></td>';

//**************** Do thi Mach/Nhiet Do ************************************
echo '<td bgcolor=white rowspan="2" ><font face="verdana,arial" size="2" color=red ><img '.createComIcon($root_path,'scale1.gif','0','right').' ></td>';

echo '
	<td bgcolor=white colspan="7" rowspan="2">';
		if($edit) 
		
$actmonat=$kmonat;
$actjahr=$jahr;

if($edit)
{
	echo '
		<MAP NAME="FrontPageMap">';
	for($i=$tag,$acttag=$tag,$d=0,$x0=0,$x1=99;$i<($tag+7);$i++,$d++,$x0+=100,$x1+=100,$acttag++)
	{
		aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	 echo'
		<AREA SHAPE="RECT" COORDS="'.$x0.',0,'.$x1.',259" HREF="javascript:popgetdailybpt(\'bp_temp\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',\''.$tagname.'\')" title="'.str_replace("~tagword~",$LDBpTemp,$LDClk2EnterDaily).'" >';
	}
	echo '
		</MAP>';
}
	
echo '<img';
if($edit) echo ' ismap usemap="#FrontPageMap"';
echo ' src="'.$root_path.'main/imgcreator/datacurve.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&mo='.$kmonat.'&dy='.$tag.'" height=260 width=700 border=0 >
		</td>
		</tr>';
		
//echo $jahr.' '.$kmonat.' '.$tag;		 ngay(y-m-d) dau tien trong calendar

/******************** Chan doan *****************************************/
echo '<tr><td bgcolor="#ffffcc" valign="top" ><font size=1><div class=pcont>';
if($edit){
	echo '
		 <a href="javascript:popgetinfowin(\'diag_ther\',\''.$pn.'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',\''.$tag.'\',\''.$tagname.'\')" title="'.str_replace("~tagword~",$LDDiagnosisTherapy,$LDClk2Enter).'"><b>'.$LDDiagnosisTherapy.'</b>
		<img '.createComIcon($root_path,'clip2.gif','0').' ></a>';
}else{
	echo '<b>'.$LDDiagnosisTherapy.'</b>';
}
if(is_object($diagnosis)){
	$diagnosis->MoveLast();				//Chi hien ra chan doan sau cung
	while($buff=$diagnosis->FetchRow()){
		echo '<br>'.hilite(wordwrap(nl2br($buff['notes']),20,"<br/>",TRUE));
	}
}
echo '</div></td></tr>';

/******************** Huyet ap *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr   valign="top" >
		<td bgcolor=white colspan="2"><br><b>1. '.$LDBp.'</b><br>(mmHg)</td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	echo '<td bgcolor="white" height="120" width="98"><font face="verdana,arial" size="1" color="#000000">';
	
	if($edit) echo '
		<a href="javascript:popgetdailymeasure(\'win_huyetap\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',1)" title="'.str_replace("~tagword~",$LDBp,$LDClk2EnterDaily).'">';

	if($r=getdatameasure($huyetap,$i,$kmonat,$jahr))  echo $r;
	else if($edit) echo '<img src="'.$root_path.'gui/img/common/default/pixel.gif" width="97" height="118"  border=0 >';
	
	if($edit) echo "</a>";
	echo "</td>";
}
echo "</tr>";

/******************** Can nang *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;

echo '	<tr   valign="top" >
		<td bgcolor=white colspan="2"><br><b>2. '.$LDWeight.'</b><br>(kg)</td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	echo '<td bgcolor="white" height="120" width="98"><font face="verdana,arial" size="1" color="#000000">';
	
	if($edit) echo '
		<a href="javascript:popgetdailymeasure(\'win_cannang\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',6)" title="'.str_replace("~tagword~",$LDWeight,$LDClk2EnterDaily).'" >';

	if($r=getdatameasure($cannang,$i,$kmonat,$jahr))  echo $r;
	else if($edit) echo '<img src="'.$root_path.'gui/img/common/default/pixel.gif" width="97" height="118"  border=0 >';
	
	if($edit) echo "</a>";
	echo "</td>";
}
echo "</tr>";

/******************** Nhip tho *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;

echo '	<tr   valign="top" >
		<td bgcolor=white colspan="2"><br><b>3. '.$LDBreath.'</b><br>(L/ph)</td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	echo '<td bgcolor="white" height="120" width="98"><font face="verdana,arial" size="1" color="#000000">';
	
	if($edit) echo '
		<a href="javascript:popgetdailymeasure(\'win_nhiptho\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',10)" title="'.str_replace("~tagword~",$LDBreath,$LDClk2EnterDaily).'" >';

	if($r=getdatameasure($nhiptho,$i,$kmonat,$jahr))  echo $r;
	else if($edit) echo '<img src="'.$root_path.'gui/img/common/default/pixel.gif" width="97" height="118"  border=0 >';
	
	if($edit) echo "</a>";
	echo "</td>";
}
echo "</tr>";

/******************** Ghi chu 4 *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
echo '	<tr   valign="top" >
		<td bgcolor=white colspan="2"><br><b> 4.</b></td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	echo '
		<td bgcolor=white  height="80" width="98"><font face="verdana,arial" size="1" color="#000000">';
	if($edit) echo '
		<a href="javascript:popgetdailyinfo(\'diag_ther_dailyreport\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',\''.$tagname.'\')" title="'.str_replace("~tagword~",$LDNotes,$LDClk2EnterDaily).'"  >';

	if($r=&getdata($main_notes,$i,$kmonat,$jahr))  echo hilite(nl2br($r));
	else 
	  if($edit) echo '<img src="'.$root_path.'gui/img/common/default/pixel.gif" width="97" height="78"  border=0 alt="'.str_replace("~tagword~",$LDDiagnosisTherapy,$LDClk2EnterDaily).'" >';
	if($edit) echo "</a>";
	echo "</td>";
}
echo '</tr>';

/******************** Ghi chu 5 *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
echo '	<tr   valign="top" >
		<td bgcolor=white colspan="2"><br><b> 5.</b></td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	echo '
		<td bgcolor=white  height="80" width="98"><font face="verdana,arial" size="1" color="#000000">';
	if($edit) echo '
		<a href="javascript:popgetdailyinfo(\'daily_chart_5\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$kmonat.'\',\''.$tag.'\',\''.$tagname.'\')" title="'.str_replace("~tagword~",$LDNotes,$LDClk2EnterDaily).'"  >';

	if($r=&getdata($more_notes_5,$i,$kmonat,$jahr))  echo hilite(nl2br($r));
	else 
	  if($edit) echo '<img src="'.$root_path.'gui/img/common/default/pixel.gif" width="97" height="78"  border=0 alt="'.str_replace("~tagword~",$LDDiagnosisTherapy,$LDClk2EnterDaily).'" >';
	if($edit) echo "</a>";
	echo "</td>";
}
echo '</tr>';
/******************** Dieu duong ky ten *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;

echo '	<tr   valign="top" >
		<td bgcolor=white colspan="2"><br><b>'.$LDNursing1.'</b><br>('.$LDCreatedBy.')</td>';
for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+7);$i++,$d++,$acttag++)
{
	aligndate(&$acttag,&$actmonat,&$actjahr); // function to align the date
	echo '<td bgcolor="white" height="60" width="98"><font face="verdana,arial" size="1" color="#000000">';
	
	if($r=getdatanursing($dieuduong,$i,$kmonat,$jahr))  echo nl2br($r);
	else if($edit) echo '<img src="'.$root_path.'gui/img/common/default/pixel.gif" width="97" height="58"  border=0 >';
	
	echo "</td>";
}

echo 
'</tr>
	</table>
';

?>
</form>

<p>
<center>
	<a href="<?php echo "$breakfile" ?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>></a>&nbsp;
	<a href="javascript:printOut();"><img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?>></a>
	</FONT>
</center>

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
