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
    $lang_tables=array('date_time.php','actions.php');
    define('LANG_FILE','nursing.php');
    define('NO_CHAIN',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    $thisfile=basename(__FILE__);
    include_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj= new Encounter;
    $enc_obj->where=" encounter_nr=$pn";
    if( $enc_obj->loadEncounterData($pn)) {
        $full_en=$pn;
        if( $enc_obj->is_loaded){
            $result=&$enc_obj->encounter;
        }
    }
    
    /* Create charts object */
    require_once($root_path.'include/care_api_classes/class_charts.php');
    $charts_obj= new Charts;
    include_once($root_path.'include/core/inc_date_format_functions.php');
    switch($winid)
    {
        case 'lanmangthai': 
            $title=$Lanmangthai;
            break;
        case 'lansinh': 
            $title=$Lansinh;
            break;
        case 'giooivo': 
            $title=$Mangoivo;
            break;
        default:
            break;
    }
    if($mode=='save'){
        $_POST['encounter_nr']=$pn;
        $_POST['bdcd_by']=$_SESSION['sess_user_name'];
        $_POST['msr_date']=@formatDate2STD($_POST['msr_date'], $date_format);
        $info=$charts_obj->saveBdcdFromArray($_POST);
        if($info){
            // Load the visual signalling functions
            include_once($root_path.'include/core/inc_visual_signalling_fx.php');	
            // Set the visual signal 
            setEventSignalColor($pn,SIGNAL_COLOR_ANTICOAG);
            header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=1&pn=$pn&station=$station&winid=$winid&yr=$yr&mo=$mo&dy=$dy&mode=''");
        }
    }else if($mode=='edit'){
        if($created_by!=$_SESSION['sess_user_name']){
            echo '<csript language="javascript">alert("'.$AlertUser.'")</script>';
        }else{
            $_POST['msr_date']=@formatDate2STD($_POST['msr_date'], $date_format);
            $info=$charts_obj->updatebdcd($_POST,$nr);
            if($info){
                // Load the visual signalling functions
                include_once($root_path.'include/core/inc_visual_signalling_fx.php');	
                // Set the visual signal 
                setEventSignalColor($pn,SIGNAL_COLOR_ANTICOAG);
                header("location:$thisfile?sid=$sid&lang=$lang&edit=$edit&saved=1&pn=$pn&station=$station&winid=$winid&yr=$yr&mo=$mo&dy=$dy&mode=''");
            }
        }        
    }else{
        $count=0;
        $info=$charts_obj->getManyDaysInfo($pn,$mo,date('Y-m-d'));		
        if(is_object($info)){
            $count=$info->RecordCount();
            include_once($root_path.'modules/news/includes/inc_editor_fx.php');
            include_once($root_path.'include/core/inc_date_format_functions.php');
        }	
    }
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>

<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo "$title - $LDInputWin" ?></TITLE>
<script language="javascript">
    function resetinput(){
//        document.infoform.reset();
        window.location.href="nursing-popgetinfo-bdcd.php?sid=<?php echo $sid.'&lang='.$lang.'&edit='.$edit.'&winid='.$winid.'&station='.$station.'&pn='.$pn.'&yr='.$yr.'&mo='.$mo.'&dy='.$dy; ?>";
    }

    function checkNumber(d,name){
        if(isNaN(d.value)){ 
            alert(name+" <?php echo $LDWarning;?>");
            d.value="";
            d.focus();
        }else 
            return true;
    }	
    function parentrefresh(){
        window.opener.location.href="nursing-station-patientdaten-kurve-bdcd.php?sid=<?php echo "$sid&lang=$lang&edit=$edit&station=$station&pn=$pn&monat=$mo&jahr=$yr"; ?>&nofocus=1";
    }
    
    $(function(){
        $("#msr_time").mask("**:**");
    });
</script>

    <style type="text/css">
        div.box { border: double; border-width: thin; width: 100%; border-color: Purple; }
    </style>

</HEAD>
<BODY  bgcolor="#99ccff" TEXT="#000000" LINK="#0000FF" VLINK="#800080" topmargin="0" marginheight="0" 
onLoad="<?php if($saved) echo "parentrefresh();"; ?>if (window.focus) window.focus(); window.focus();" >

<table border=0 width="100%">
    <tr>
        <td>
            <b><font face=verdana,arial size=5 color=maroon>
            <?php 
                echo $title; 
            ?>
            </font></b>
        </td>
        <td align="right">
            <a href="javascript:window.close()" ><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>  <?php if($cfg['dhtml'])echo'class="fadeOut" >';?></a></nobr>
        </td>
    </tr>
</table>
<form name="infoform" action="<?php echo $thisfile ?>" method="post">
    <?php
            $tbg= 'background="'.$root_path.'gui/img/common/'.$theme_com_icon.'/tableHeaderbg3.gif"';
    ?>
        <table border=0 cellpadding=4 cellspacing=1 width=100%>
            <tr bgcolor="#f6f6f6">
                <td <?php echo $tbg; ?> align="center"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDDate; ?></td>
                <td <?php echo $tbg; ?> align="center"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDTime; ?></td>
                <td <?php echo $tbg; ?> align="center"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $Lanmangthai; ?></td>
                <td <?php echo $tbg; ?> align="center"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $Lansinh; ?></td>
                <td <?php echo $tbg; ?> align="center"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $Mangoivo; ?></td>
                <td <?php echo $tbg; ?> align="center"><FONT SIZE=-1  FACE="Arial" color="#000066"><?php echo $LDCreatedBy; ?></td>
            </tr>
            <?php
                $toggle=0;
                $flag=0;
                while($row=$info->FetchRow()){
                    $date=$row['msr_date'];
                    if($row['lanmangthai'] || $row['lansinh'] || $row['giooivo']){
                        $nr=$row['nr'];
                        $created_by=$row['bdcd_by'];
                        $flag=1;
                        if($toggle) $bgc='#efefef';
                        else $bgc='#f0f0f0';
                        $toggle=!$toggle;
            ?>
            <tr bgcolor="<?php echo $bgc; ?>"  valign="top">                
                <td align="center">
                    <FONT SIZE=-1  FACE="Arial">
                        <?php echo @formatDate2Local($row['msr_date'],$date_format); ?>
                    </FONT>
                </td>
                <td align="center">
                    <FONT SIZE=-1  FACE="Arial">
                        <?php echo $row['msr_time']; ?>
                    </FONT>
                </td>
                <td align="center">
                    <FONT SIZE=-1  FACE="Arial" color="#000033">
                        <?php echo $row['lanmangthai']; ?>
                    </FONT>
                </td>
                <td align="center">
                    <FONT SIZE=-1  FACE="Arial" color="#000033">
                        <?php echo $row['lansinh']; ?>
                    </FONT>
                </td>
                <td align="center">
                    <FONT SIZE=-1  FACE="Arial" color="#000033">
                        <?php echo $row['giooivo']; ?>
                    </FONT>
                </td>
                <td align="center">
                    <FONT SIZE=-1  FACE="Arial">
                        <?php echo $row['bdcd_by']; ?>
                    </FONT>
                </td>
            </tr>
        <?php
                    break;
                }
            }
            if($result['is_discharged']=='0'){
        ?>
            <tr>
                <td colspan="7">
                    <font color="Blue"><b>
                    <?php echo $LDEntryPrompt ?>:<br></b>
                    </font>
                </td>
            </tr>
            <tr>            
                <td colspan="7"> 
                    <table width="100%">
                        <tr>
                            <td align="center" width="20%">
                                <?php 
                                    if($flag!=0){
                                        $tempdatevalue=$row['msr_date'];
                                    }else{
                                        $tempdatevalue=date('Y-m-d');
                                    }
                                    echo $LDDate.'<br/>'.$calendar->show_calendar($calendar, $date_format, 'msr_date', $tempdatevalue);
                                ?>
                            </td>
                            <td align="center" width="10%" >
                                <?php
                                    echo $LDTime.'<br/>';
                                ?>
                                <input type="text" name="msr_time" id="msr_time" size="4" maxlength="5" value='<?php if($flag!=0) echo $row['msr_time']; else echo date('H.i');?>'/>
                            </td>
                            <td align="center" width="20%">
                                <?php
                                    echo $Lanmangthai.'<br/>';
                                ?>
                                <input type="text" name="lanmangthai" size="5" maxlength="5" value='<?php if($flag!=0) echo $row['lanmangthai'];?>' onchange="checkNumber(this,'Số lần mang thai')"/>
                            </td>
                            <td align="center" width="20%">
                                <?php
                                    echo $Lansinh.'<br/>';
                                ?>
                                <input type="text" name="lansinh" size="5" maxlength="5" value='<?php if($flag!=0) echo $row['lansinh'];?>' onchange="checkNumber(this,'Số lần sinh')"/>
                            </td>
                            <td align="center" width="20%">
                                <?php
                                    echo $Mangoivo.'<br/>';
                                ?>
                                <input type="text" name="giooivo" id="giooivo" size="9" value='<?php if($flag!=0) echo $row['giooivo'];?>'/>
                                <br/>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </td>                
            </tr>            
            <tr>
                <td colspan="4">
                    <?php
                        if($flag!=0){
                    ?>
                            <input type="image" <?php echo createLDImgSrc($root_path,'edit.gif','0') ?>>
                    <?php
                            $mode='edit';
                        }else{
                    ?>
                            <input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>>
                            &nbsp;&nbsp;
                            <a href="javascript:resetinput()"><img <?php echo createLDImgSrc($root_path,'reset.gif','0') ?> alt="<?php echo $LDReset ?>"></a>
                            &nbsp;&nbsp;
                    <?php
                            $mode='save';
                        }
                    ?>
                    <?php if($saved){  ?>
                    <a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClose ?>"></a>
                    <?php }else{?>
                    <a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> border="0" alt="<?php echo $LDClose ?>">
                    </a>
                    <?php
                        }
                    }
                    ?>
                </td>
            </tr>            
        </table>
    <input type="hidden" name="sid" value="<?php echo $sid ?>" />
    <input type="hidden" name="lang" value="<?php echo $lang ?>" />
    <input type="hidden" name="winid" value="<?php echo $winid ?>" />
    <input type="hidden" name="station" value="<?php echo $station ?>" />
    <input type="hidden" name="yr" value="<?php echo $yr ?>" />
    <input type="hidden" name="mo" value="<?php echo $mo ?>" />
    <input type="hidden" name="dy" value="<?php echo $dy ?>" />
    <input type="hidden" name="pn" value="<?php echo $pn ?>" />
    <input type="hidden" name="edit" value="<?php echo $edit ?>" />
    <input type="hidden" name="mode" value="<?php echo $mode;?>" />    
    <input type="hidden" name="nr" value="<?php echo $nr;?>" />
    <input type="hidden" name="created_by" value="<?php echo $created_by;?>" />
</BODY>
</HTML>
