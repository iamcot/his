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
    $thisfile=basename(__FILE__);
    /* Create charts object */
    require_once($root_path.'include/care_api_classes/class_charts.php');
    $charts_obj= new Charts;
    if($mo==12){
        $yr1+=1;
        $mo1=1;
    }else if($mo==1){
        $mo+=1;
        $yr1=$yr;
        $mo1=$mo;
    }else{
        $yr1=$yr;
        $mo1=$mo;
    }
    $maxelement=7;
    /* Load date formatter */
    include_once($root_path.'include/core/inc_date_format_functions.php');
    // get orig data 
    include_once($root_path.'modules/news/includes/inc_editor_fx.php');
    include_once($root_path.'include/core/inc_date_format_functions.php');
    $bpcount=0;
    $tempcount=0;
    $chart_bp=$charts_obj->getManyDaysInfo($pn,$mo,date('Y-m-d'),"AND (lanmangthai='' AND lansinh='')");		
    if(is_object($chart_bp)){
        $bpcount=$chart_bp->RecordCount();
    }
?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo "$title $LDInputWin" ?></TITLE>
<?php
    require($root_path.'include/core/inc_css_a_hilitebu.php');
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<script language="javascript">
    function pruf(d){
        if(!d.newdata.value) return false;
        else return false;//true;
    }
    function parentrefresh(){
        window.opener.location.href="nursing-station-patientdaten-kurve-bdcd.php?sid=<?php echo "$sid&lang=$lang&edit=$edit&station=$station&pn=$pn&tag=$dystart&monat=$monstart&jahr=$yrstart"; ?>&nofocus=1";
    }	
    function updateitem(nr){
	urlholder="update_bdcd.php?nr="+nr+"<?php echo "&sid=$sid&lang=$lang&edit=$edit&station=$station&pn=$pn&winid=$winid&monstart=$monstart&yrstart=$yrstart&yr=$yr&mo=$mo"; ?>";
	updatevalue=window.open(urlholder,"update","width=1650,height=300,menubar=no,resizable=yes,scrollbars=yes");
    } 
	
    function checkNumberItem(d){
	d.value = d.value.replace(',','.');
	if (isNaN(d.value)){
		alert("<?php echo $LDAlertInputNumber; ?>");
		d.focus();
		return false;
	}
	return true;
    }
//-->
</script>
<style type="text/css">
    div.box { border: double; border-width: thin; width: 100%; border-color: black; }
    .v12 { font-family:verdana,arial;font-size:12; }
    .v12 { font-family:verdana,arial;font-size:13; }
</style>

</HEAD>
<BODY  bgcolor="#99ccff" TEXT="#000000" LINK="#0000FF" VLINK="#800080"  topmargin="0" marginheight="0" onLoad="<?php if($saved) echo "parentrefresh();"; ?>if (window.focus) window.focus(); " >
<table border="0" width="100%">
    <tr>
        <td>
        <b>
            <font size=3 color=maroon>
                <?php 
                    echo $title.'</font><p><font size=2>';	
//                    echo $LDFullDayName[$dyidx].' ('.formatDate2Local($date['msr_date'],$date_format).')</font>';
                ?>
            </font>
        </b>
        </td>
        <td align="right" valign="top">
            <a href="javascript:window.close()" ><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>  <?php if($cfg['dhtml'])echo'class="fadeOut" />';?></a></nobr>
        </td>
    </tr>
</table>
<form name="infoform" action="<?php echo $thisfile; ?>" method="post" onSubmit="return pruf(this)">
    <table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0>
        <tr>
            <td>
                <table border=0 width=100% cellspacing=1>
                    <?php
                        if($bpcount||$tempcount){
                            $rcount=($bpcount<$tempcount)?$tempcount:$bpcount;
                    ?>
                    <tr>
                        <td align=center bgcolor="#000000">	
                            <?php
                                $tbg= 'background="'.$root_path.'gui/img/common/'.$theme_com_icon.'/tableHeaderbg3.gif"';
                                $temptimenow=date('H:i');
                            ?>
                            <table border=0 cellpadding=1 cellspacing=1 width=100%>
                                <tr bgcolor="#f6f6f6">
                                    <td <?php echo $tbg; ?>width="7%" align="center" rowspan="2"><font color="red"><b><?php echo $LDDate; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="2%" rowspan="2"><font color="red"><b><?php echo $LDClockTime ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="3%" rowspan="2"><font color="red"><b><?php echo $LDNhiptim; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="3%" rowspan="2"><font color="red"><b><?php echo $LDNuocoi; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="3%" rowspan="2"><font color="red"><b><?php echo $LDDochongkhop; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="3%" rowspan="2"><font color="red"><b><?php echo $LDCTC."<br/>(cm)"; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDDolot; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" colspan="2"><font color="red"><b><?php echo $LDSoCC; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDOxy; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDGiot; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="15%" rowspan="2"><font color="red"><b><?php echo $LDThuocDT; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDMach; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDBp; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDThannhiet; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNuoctieu['1']; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNuoctieu['2']; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNuoctieu['3']; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNotes; ?></b></font></td>
                                    <td align="center" <?php echo $tbg; ?> rowspan="2"><font color="red"><b><?php echo $LDEdit; ?></b></font></td>
                                </tr>
                                <tr>
                                    <td align="center" <?php echo $tbg; ?> width="4%"><font color="red"><b><?php echo $Soconco; ?></b></td>
                                    <td align="center" <?php echo $tbg; ?> width="4%"><font color="red"><b><?php echo $second; ?></b></td>
                                </tr>
                                <?php
                                    $toggle=0;
                                    $bb=array();
                                    $i=1;
                                    while($bb=$chart_bp->FetchRow()){
                                        if($bb[$lanmangthai]!='' || $bb[$lansinh]!='' || $bb[$giooivo]!='') continue;
                                        if($i%2==0) 
                                            $bgc='#FFFAFA';
                                        else $bgc='#f0f0f0';
                                        $toggle=!$toggle;
                                        $i++;
                                ?>
                                <tr bgcolor="<?php echo $bgc; ?>" valign="top">
                                    <td align="center">
                                        <?php
                                            echo formatDate2Local($bb['msr_date'],$date_format);
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php 
                                            if(isset($bb['msr_time'])) echo $bb['msr_time']; 
                                        ?>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['nhiptim']) 
                                                    echo $bb['nhiptim'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['nuocoi']) 
                                                    echo $bb['nuocoi'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['dochongkhop']) 
                                                    echo $bb['dochongkhop'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['cotucung']) 
                                                    echo $bb['cotucung'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['dolot']) 
                                                    echo $bb['dolot'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['soconco']) 
                                                    echo $bb['soconco'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['sogiay']) 
                                                    echo $bb['sogiay'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['oxytocin']) 
                                                    echo $bb['oxytocin'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['sogiot']) 
                                                    echo $bb['sogiot'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['thuoc']) 
                                                    echo $bb['thuoc'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['mach']) 
                                                    echo $bb['mach'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['huyetap']) 
                                                    echo $bb['huyetap'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['nhietdo']) 
                                                    echo $bb['nhietdo'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['dam']) 
                                                    echo $bb['dam'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['acetone']) 
                                                    echo $bb['acetone'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['luong']) 
                                                    echo $bb['luong'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center">
                                        <FONT SIZE=-1  FACE="Arial">
                                            <?php 
                                                if($bb['ghichu']) 
                                                    echo $bb['ghichu'];
                                                else echo '&nbsp;'; 
                                            ?>
                                        </FONT>
                                    </td>
                                    <td align="center" valign="middle">
                                        <?php echo '<a href="javascript:updateitem(\''.$bb['nr'].'\')"><img '.createComIcon($root_path,'pencil.gif','0').' title="'.$LDUpdate.'"/></a>'; ?>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </table>
                        </td>
                </tr>
                <?php
                }
                ?>
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
<input type="hidden" name="pn" value="<?php echo $pn ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="type" value="<?php echo $type; ?>">
</form>
<p>
<center>
<!--<a href="javascript:parentrefresh();window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClose ?>">
</a>-->
</center>
</BODY>

</HTML>