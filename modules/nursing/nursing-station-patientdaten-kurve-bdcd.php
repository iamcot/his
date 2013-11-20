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
    $lang_tables=array('departments.php','aufnahme.php');
    define('LANG_FILE','nursing.php');

    require('./include/inc_admit_station_bridge.php');
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    require_once($root_path.'modules/news/includes/inc_editor_fx.php'); 
    /* Load the data time shifter and create object */
    require_once($root_path.'classes/datetimemanager/class.dateTimeManager.php');
    $dateshifter=new dateTimeManager();

    $thisfile=basename(__FILE__);
    $breakfile="nursing-station-patientdaten.php".URL_APPEND."&station=$station&pn=$pn&edit=$edit";
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
            /*get bdcd(lanmangthai,lansinh,giooivo) Record */ 
            $date=  explode(' ', $result['encounter_date']);            
            $jahr= date('Y',  strtotime($date[0]));
            if($date[0]==$jahr.'-12-31'){
                $jahr++;
            }
            $info_bdcd=$charts_obj->getManyDaysInfo($pn,$date[0],$jahr."-12-31","AND (lanmangthai<>'' OR lansinh<>'' OR giooivo<>'')");	
            if($info_bdcd){
                $info_bdcd=$info_bdcd->FetchRow();
            }
            $info_bdcd_detail=$charts_obj->getManyDaysInfo($pn,$date[0],$jahr."-12-31","AND ((lanmangthai='' AND lansinh='') OR (lanmangthai=0 AND lansinh=0))");	
            if($info_bdcd_detail){
                $info_array=array(array());
                $i=0;
                $count=$info_bdcd_detail->RecordCount();
                while($i<$count){
                    $j=0;
                    $info_detail=$info_bdcd_detail->FetchRow();
                    while($j<sizeof($info_detail)){
                        $info_array[$i][$charts_obj->fld_bdcd[$j]]=$info_detail[$charts_obj->fld_bdcd[$j]]; 
                        $j++;
                    }
                    $i++;
                }
            }

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
            }elseif ($dept_nr!='' && $dept_nr!='0'){
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
    # Prepare title
    $sTitle = "$LDReportSan $LDWardNr $station";

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
//    $smarty->assign('pbHelp',"javascript:gethelp('nursing_feverchart.php','main','','$station','Fever chart')");

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
    var urlholder="";
    var infowinflag=0;
    var sw=window.screen.width/2;
    var sh=window.screen.height/2;
    var w600=700;
    var h400=400;
    var h600=600;
    function popgetinfowin(winID,patientID,jahrID,monatID,tagID)
    {
	urlholder="nursing-popgetinfo-bdcd.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid=" + winID + "&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID + "&mo=" + monatID;
	infowin=window.open(urlholder,"kurvendaten","width="+w600+",height="+h400+",menubar=no,resizable=yes,scrollbars=yes");
   	window.infowin.moveTo(sw-(w600/2),sh-(h400/2));
   	infowinflag=1;
    }
    function popgetdailybpt(winID,patientID,jahrID,monatID,jahrS,monatS)
    {
	urlholder="nursing-getdaily_bdcd.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID +"&monstart="+monatS ;
	dailybpt=window.open(urlholder,"dailybpt","width=1650,height=300,menubar=no,resizable=yes,scrollbars=yes");
   	//window.dailybpt.moveTo(sw-(w600/2),sh-(h600/2));
   	infowinflag=1;
    }
    function easywrite(winID,patientID,jahrID,monatID,tagID,tagIDX,jahrS,monatS){
	urlholder="nursing-getdaily-easy-bdcd.php?sid=<?php echo "$sid&lang=$lang&edit=$edit" ?>&winid=" + winID + "&station=<?php echo $station ?>&pn=" + patientID + "&yr=" + jahrID + "&mo=" + monatID + "&dy="+ tagID + "&dyidx="+ tagIDX +"&yrstart="+jahrS+"&monstart="+monatS;
	dailymeasure_easy=window.open(urlholder,"dailymeasure_easy","width=1650,height="+h600+",menubar=no,resizable=yes,scrollbars=yes");
   	window.dailymeasure_easy.moveTo(sw-(w600/2),sh-(h600/2));
   	infowinflag=1;
    }
    function printOut(){
	urlholder="<?php echo $root_path; ?>modules/pdfmaker/khoasan/bieudochuyenda.php<?php echo URL_REDIRECT_APPEND.'&pn='.$pn.'&jahr='.$jahr.'&kmonat='.$date['0']; ?>";
	window.open(urlholder,'bieudochuyenda',"width=1000,height=800,menubar=no,resizable=yes,scrollbars=yes");
    }
</script>

<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp);
    ob_start();
?>
<form name="berichtform">
<?php

//****************************** Encounter number ********************************
echo '<table  bgcolor="#D8D8D8" cellpadding="0" cellspacing=1 border="0" width="90%">
        <tr>
            <td bgcolor="aqua" class=pblock width="35%">
                <font size="3">
                    <b>'.ucfirst($result['name_last']).' '.ucfirst($result['name_first']).'</b></font> 
                    <br/>
                    <i>'.$Sonhapvien.'</i>: <b>'.$full_en.'</b>   
            </td>
            <td bgcolor="aqua" width="4%" align="center">
                <font size="2"><b>'.$LDNhiptim.'</b></font>
            </td>';
//****************************** Giờ ********************************
echo '      <td> 
		<table cellpadding="0" cellspacing="0" border=1 width="100%">
                    <tr>';
                    $i=1;
                    while($i<25){
                        echo '<td align="center">'.$i.'</td>';
                        $i++;
                    }
echo '              </tr>
                    <tr>';
                    $actmonat=$kmonat;	
                    $actjahr=$jahr;		

//****************************** Ghi nhanh ********************************
                    for ($i=$tag,$acttag=$tag,$d=0;$i<($tag+24);$i++,$d++,$acttag++)
                    {
                        echo '<td bgcolor=white align=center class="a12" width="98px">';
                        if($result['is_discharged']=='0'){
                            echo '<a href="javascript:easywrite(\'nhiptimthai\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$acttag.'\',\''.($d+$tagnamebuf).'\',\''.$jahr.'\',\''.$date[0].'\')">'.$LDGhiNhanh.'</a></td>';	
                        }else{
                            echo $LDGhiNhanh.'</td>';
                        }                        
                    }

//**************** Patient personal data ************************************
echo '              </tr>
                </table> 		
            </td>
        </tr>
	<tr valign="top">
            <td bgcolor="#ffffcc" class=pblock width="130" ><font size=2> 
                    <font color=maroon>'.$LDSex.': <b>'.$sex.'</b>
                    <br/>
                    '.$LDBday.': <b>'.formatDate2Local($result['date_birth'],$date_format).'</b></font>
                    <br/>
                    <font size=1>'.$deptname.'<br/>'.$wardname.'<br/>'.$LDRoom.': '.$result['current_room_nr'].'<br/>&nbsp;
            </td>';

//**************** Nhip tim thai ************************************
echo '      <td bgcolor=white rowspan="2"><font face="verdana,arial" size="2" color="red" >
                <table width="100%" cellpadding=0 cellspacing=0>';
                    $i=180;
                    $j=1;
                    while($i>90){
                        if($i!=100){
                            echo '<tr><td style="color:darkblue; font-size:11;" align="right" valign="top" height="'.(20+$j).'px"><b>'.$i.'</b></td></tr>';
                        }else{
                            echo '<tr><td style="color:darkblue; font-size:11;" align="right" valign="top" height="'.(20+$j).'px"><b>'.$i.'</b></td></tr>';
                        }                        
                        $i-=10;
                        $j++;
                    }    
echo '          </table>
            </td>';

echo '      <td bgcolor=white rowspan="2">';
$actmonat=$kmonat;
$actjahr=$jahr;
#************************ vẽ hình **********************************
                echo '<img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=1" height=200 width=1100 border=0 />
            </td>
        </tr>
        <tr valign="top">
            <td bgcolor="#ffffcc" valign="top" >
                <div class=pcont>
                    <a href="javascript:popgetinfowin(\'lanmangthai\',\''.$pn.'\',\''.$jahr.'\',\''.$date[0].'\')" title="'.str_replace("~tagword~",$Lanmangthai,$LDClk2Enter).'">
                        <b>'.$Lanmangthai.': '.$info_bdcd['lanmangthai'].'<b>
                        <img '.createComIcon($root_path,'clip2.gif','0').' />
                    </a>
                    <a href="javascript:popgetinfowin(\'lansinh\',\''.$pn.'\',\''.$jahr.'\',\''.$date[0].'\')" title="'.str_replace("~tagword~",$Lansinh,$LDClk2Enter).'">
                        <p>
                            <b>'.$Lansinh.': '.$info_bdcd['lansinh'].'<b>
                            <img '.createComIcon($root_path,'clip2.gif','0').' />
                        </p>
                    </a>
                    <a href="javascript:popgetinfowin(\'giooivo\',\''.$pn.'\',\''.$jahr.'\',\''.$date[0].'\')" title="'.str_replace("~tagword~",$Mangoivo,$LDClk2Enter).'">
                        <p>
                            <b>'.$Mangoivo.': '.$info_bdcd['giooivo'].'<b>
                            <img '.createComIcon($root_path,'clip2.gif','0').' />
                        </p>
                    </a>
                </div>
            </td>
        </tr>';

/******************** nuoc oi + do chong khop *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr valign="middle" >
            <td bgcolor=white colspan="2" height="22px" align="center">
                <b>'.$LDNuocoi.'</b>
                </td>';
echo '      <td bgcolor=white rowspan="2">';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=2" height=44 width=1100 border=0 >
            </td>
        </tr>
        <tr>
            <td bgcolor=white colspan="2" height="19px " align="center">
                <b>'.$LDDochongkhop.'</b>
            </td>
        </tr>';
/******************** co tu cung + do lot *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr>
            <td colspan="3" bgcolor="white"><br/></td>
        </tr>
        <tr valign="top">
            <td bgcolor=white colspan="2">
               <table width="100%" cellpadding=0 cellspacing=0>
                        <td bgcolor=white align="center" rowspan="13" width="60px"><b>'.$LDCTC.'</b>
                            <br/>
                            (đánh dấu X)';
                        echo '          <img';
                        if($edit) echo ' ismap usemap="#FrontPageMap"';
                        echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=11" height=0 width=0>    
                        </td>
                        <td bgcolor=white align="center" rowspan="13" width="60px">
                            <b>'.$LDDolot.'</b>
                            <br>(khoanh 0)
                        </td>
                    </tr>';
                        $i=10;
                        while($i>-2){
                            if($i==(-1)){
                                echo '<tr valign="top"><td align="right" valign="bottom" height="8px"></td></tr>';
                            }else{
                                echo '<tr><td height="24px" style="color:darkblue; font-size:11;" align="right" valign="top"><b>'.$i.'</b></td></tr>';
                            }
                            $i--;
                        }
echo '          </table>
            </td>';
echo '      <td bgcolor=white valign="top">';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=3" height=270 width=1100 border=0 >
            </td>
        </tr>';
/******************** So con co *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr>
            <td colspan="3" bgcolor="white"><br/></td>
        </tr>
        <tr valign="middle" >
            <td bgcolor=white colspan="2" height="90" align="center">
                <b>'.$LDSoCC.'</b>';
                echo '<img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=12" height=0 width=0>
                </td>';
echo '      <td bgcolor=white>';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=4" height=90 width=1100 border=0 >
            </td>
        </tr>';
/******************** oxytoxin + so giot *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr>
            <td colspan="3" bgcolor="white"><br/></td>
        </tr>
        <tr valign="middle" >
            <td bgcolor=white colspan="2" height="19" align="center">
                <b>'.$LDOxy.'</b>
                </td>';
echo '      <td bgcolor=white rowspan=2>';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=9" height=36 width=1100 border=0 >
            </td>
        </tr>
        <tr>
            <td bgcolor=white colspan="2" align="center"><b>'.$LDGiot.'</b></td>
        </tr>';
/******************** cac thuoc + mach huyet ap *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr><td colspan="3" bgcolor="white"><br/></td></tr>
        <tr valign="middle" >
            <td bgcolor=white colspan="2" height="216" align="center">
                <b>'.$LDThuocDT.'</b>';
                echo '<img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=13" height=0 width=0>
            </td>
            <td>';
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=6" height=223 width=1100 border=0 >
            </td>
        </tr>
        <tr>
            <td bgcolor=white colspan="2" height="216" align="center">
                <table width="100%" cellpadding=0 cellspacing=0>
                    <tr>
                        <td rowspan="14"><b>'.$LDMach.' và '.$LDBp.'</b>';
                            echo '<img';
                            if($edit) echo ' ismap usemap="#FrontPageMap"';
                            echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'" height=0 width=0>
                        </td>                  
                    </tr>';
                    $i=180;
                    while($i>50){
                        echo '<tr><td height="17px" style="color:darkblue; font-size:10;" align="right"><b>'.$i.'</b></td></tr>';
                        $i-=10;
                    }
echo '          </table>
            </td>
            <td bgcolor=white valign="top">';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=5" height=223 width=1100 border=0 >
            </td>
        </tr>';
/******************** Than nhiet *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr><td colspan="3" bgcolor="white"><br/></td></tr>
        <tr valign="middle" >
            <td bgcolor=white colspan="2" height="19" align="center">
                <b>'.$LDThannhiet.'</b>
                </td>';
echo '      <td bgcolor=white>';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=7" height=36 width=1100 border=0 >
            </td>
        </tr>';
/******************** Nuoc tieu *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr valign="middle" >
            <td bgcolor="white" rowspan="3" align="center">
                <b>'.$LDNuoctieu['0'].'</b>
            </td>
            <td bgcolor="white" height="20" align="center">
                <b>'.$LDNuoctieu['1'].'</b>
            </td>';
echo '      <td bgcolor=white rowspan="3">';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=10" height=60 width=1100 border=0 >
            </td>
        </tr>
        <tr>
            <td bgcolor="white" height="20" align="center">
                <b>'.$LDNuoctieu['2'].'</b>
            </td>
        </tr>
        <tr>
            <td bgcolor="white" height="20" align="center">
                <b>'.$LDNuoctieu['3'].'</b>
            </td>
        </tr>';
/******************** Dieu duong ky ten *****************************************/
$actmonat=$kmonat;
$actjahr=$jahr;
//$huyetap=$charts_obj->getManyDaysMeasureByType($pn,1,$date_start,$date_end);
echo '	<tr><td colspan="3" bgcolor="white"><br/></td></tr>
        <tr valign="middle" >
            <td bgcolor=white colspan="2" height="19" align="center">
                <b>'.$LDNursing1.'<br>('.$LDCreatedBy.')</b>
                </td>';
echo '      <td bgcolor=white>';
#************************ vẽ hình **********************************
echo '          <img';
                if($edit) echo ' ismap usemap="#FrontPageMap"';
                echo ' src="'.$root_path.'main/imgcreator/datacurve_1.php'.URL_APPEND.'&pn='.$pn.'&max=15&yr='.$jahr.'&date='.$date[0].'&flag=8" height=223 width=1100 border=0 >
            </td>
        </tr>
    </table>';
                
if($edit && $result['is_discharged']=='0')
{
	echo '<MAP NAME="FrontPageMap">';
        
	for($i=1,$x0=0,$x1=50;$i<25;$i++,$x0+=50,$x1+=50)
	{
            echo'<AREA SHAPE="RECT" COORDS="'.$x0.',0,'.$x1.',1100" HREF="javascript:popgetdailybpt(\'\',\''.$pn.'\',\''.$actjahr.'\',\''.$actmonat.'\',\''.$jahr.'\',\''.$date[0].'\')" title="'.str_replace("~tagword~",$LDNhiptim,$LDClk2EnterDaily).'" >';
	}
	echo '</MAP>';
}
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
