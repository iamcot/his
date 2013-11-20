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
    $lang_tables=array('aufnahme.php');
    define('LANG_FILE','nursing.php');
    $local_user='ck_pflege_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    $thisfile=basename(__FILE__);
    /* Create charts object */
    require_once($root_path.'include/care_api_classes/class_charts.php');
    $charts_obj= new Charts;
    require_once($root_path.'include/core/access_log.php');
    require_once($root_path.'include/care_api_classes/class_access.php');
    $logs = new AccessLog();
    if(!isset($allow_update)) $allow_update=FALSE;
    
    $title=$LDGhiNhanh;
    $temptimenow=date('H.i');
    $tempdatenow=date('Y-m-d');
    $maxelement=7;
    /* Load date formatter */
    include_once($root_path.'include/core/inc_date_format_functions.php');
    include_once($root_path.'modules/news/includes/inc_editor_fx.php');
?>
<?php html_rtl($lang); ?>
<HEAD>
    <?php echo setCharSet(); ?>
    <TITLE>
        <?php echo "$title $LDInputWin" ?>
    </TITLE>
<?php
    require($root_path.'include/core/inc_js_gethelp.php');
    require($root_path.'include/core/inc_css_a_hilitebu.php');
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
?>
<script language="javascript">
    function resetinput(){
        document.infoform.reset();
    }
    function pruf(d){
        if(!d.newdata.value) 
            return false;
        else return true
    }
    function parentrefresh(){
	window.opener.location.href="nursing-station-patientdaten-kurve-bdcd.php?sid=<?php echo "$sid&lang=$lang&edit=$edit&station=$station&pn=$pn&tag=$dystart&monat=$monstart&jahr=$yrstart&tagname=$dyname" ?>&nofocus=1";
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
</script>

<?php
	if($mode=='save'){	
            $saved=0;
            $data_array=array();
            $data_array['encounter_nr']=$pn;
            $data_array['bdcd_by']=$_SESSION['sess_user_name'];	
            $data_array['msr_date']=date('Y-m-d',mktime(0,0,0,$mo,$dy,$yr)); 
            for($i=0;$i<$maxelement;$i++)
            {
                $ddx="bdate".$i;
                $tdx="btime".$i; 
                $ntdx="nhiptim".$i; 
                $nodx="nuocoi".$i; 
                $dckdx="dochongkhop".$i; 
                $ctcdx="cotucung".$i;
                $dldx="dolot".$i;
                $sccdx="soconco".$i;
                $timeccdx="sogiay".$i;
                $oxydx="oxytocin".$i;
                $sgdx="sogiot".$i;
                $thdx="thuoc".$i;
                $mdx="mach".$i;
                $hadx="huyetap".$i;
                $nddx="nhietdo".$i;
                $damdx="dam".$i;
                $acedx="acetone".$i; 
                $luongdx="luong".$i;
                $note="ghichu".$i;
                
                if(empty($$tdx) || empty($$ddx)) continue;
                else{
                    $yr=explode('/',$$ddx);
                    $info_bdcd=$charts_obj->getManyDaysInfo($pn,$monstart,$yrstart."-12-31","AND (lanmangthai='' AND lansinh='')");	
                    if($info_bdcd){
                        $info_bdcd=$info_bdcd->FetchRow();
                        $date_query= date('y-m-d',$info_bdcd['msr_date'].' '.$info_bdcd['msr_time']);
                        $date_chose= $$ddx.' '.$$tdx;
//                        if((abs((formatDate2Local($info_bdcd['msr_date'], $date_format)-$$ddx))>1 && $info_bdcd['msr_date']) || abs($$tdx-$info_bdcd['msr_time'])==0){                          
//                            echo '<script language="javascript">
//                                        alert("'.$LDWarningTime.'");	
//                                </script>';
//                        }else{
                            $data_array['msr_date']=formatDate2STD($$ddx,$date_format);
                                $data_array['msr_time']=strtr($$tdx,'.,;-/_','::::::');

                                if($$ntdx!=''){		//Nhip tim
                                    $data_array['nhiptim']=$$ntdx;
                                }else{
                                    $data_array['nhiptim']='';
                                }
                                if($$nodx!=''){		//Nuoc oi
                                    $data_array['nuocoi']=$$nodx;
                                }else{
                                    $data_array['nuocoi']='';
                                }
                                if($$dckdx!=''){	//Do chong khop
                                    $data_array['dochongkhop']=$$dckdx;
                                }else{
                                    $data_array['dochongkhop']='';
                                }
                                if($$ctcdx!=''){	//Co tu cung
                                    $data_array['cotucung']=$$ctcdx;
                                }else{
                                    $data_array['cotucung']='';
                                }
                                if($$dldx!=''){	//Do lot
                                    $data_array['dolot']=$$dldx;                        
                                }
                                if($$sccdx!=''){	//So con co
                                    $data_array['soconco']=$$sccdx;                        
                                }else{
                                    $data_array['soconco']='';
                                }
                                if($$timeccdx!=''){	//So giay
                                    $data_array['sogiay']=$$timeccdx;                        
                                }else{
                                    $data_array['sogiay']='';
                                }
                                if($$oxydx!=''){	//Oxy
                                    $data_array['oxytocin']=$$oxydx;                        
                                }else{
                                    $data_array['oxytocin']='';
                                }
                                if($$sgdx!=''){         //So giot
                                    $data_array['sogiot']=$$sgdx;                        
                                }else{
                                    $data_array['sogiot']='';
                                }
                                if($$thdx!=''){	//thuoc
                                    $data_array['thuoc']=$$thdx;                        
                                }else{
                                    $data_array['thuoc']='';
                                }
                                if($$mdx!=''){          //Mach
                                    $data_array['mach']=$$mdx;                        
                                }   else{
                                    $data_array['mach']='';
                                }         
                                if($$hadx!=''){          //Huyet ap
                                    $data_array['huyetap']=$$hadx;                        
                                }else{
                                    $data_array['huyetap']='';
                                }
                                if($$nddx!=''){          //Nhiet do
                                    $data_array['nhietdo']=$$nddx;                        
                                }else{
                                    $data_array['nhietdo']='';
                                }
                                if($$damdx!=''){         //Dam
                                    $data_array['dam']=$$damdx;                        
                                }else{
                                    $data_array['dam']='';
                                }
                                if($$acedx!=''){         //aceton
                                    $data_array['acetone']=$$acedx;                        
                                }else{
                                    $data_array['acetone']='';
                                }
                                if($$luongdx!=''){       //Luong
                                    $data_array['luong']=$$luongdx;                        
                                }else{
                                    $data_array['luong']='';
                                }
                                if($$note!=''){       //Ghi chu
                                    $data_array['ghichu']=$$note;                        
                                }else{
                                    $data_array['ghichu']='';
                                }
                                if($charts_obj->saveBdcdFromArray($data_array)){
                                    $saved=1;
                                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $charts_obj->getLastQuery(), date('Y-m-d H:i:s'));
                                } else continue;    
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
//                            }
                        }
                        
                    }
                }
                
                	
	}
?>
    <style type="text/css">
        div.box { border: double; border-width: thin; width: 100%; border-color: black; }
        .v12 { font-family:verdana,arial;font-size:12;}
        .v13 { font-family:verdana,arial;font-size:13; }
    </style>
</HEAD>
<BODY  bgcolor="#99ccff" TEXT="#000000" LINK="#0000FF" VLINK="#800080" topmargin="0" marginheight="0" onLoad="<?php if($saved) echo "parentrefresh();"; ?>if (window.focus) window.focus(); window.focus();" >    
    <table border="0" width="100%">
        <tr>
            <td>
                <b><font size=3 color=maroon>
                <?php 
                    echo $title.'</font><p><font size=2>';	
                    echo $LDDate.' '.date('d/m/Y').'</font>';
                ?>
                </b>
            </td>
            <td align="right" valign="top">
                <a href="javascript:window.close()" >
                    <img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>  <?php if($cfg['dhtml'])echo'class="fadeOut" >'; ?>
                </a>
                </nobr>
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
                                    <td align=center bgcolor="#cfcfcf" class="v13">
                                        <font color="#ff0000">
                                            <b>
                                                <?php echo $LDGhiNhanhInfo_bdcd; ?>
                                            </b>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td align=center bgcolor="#ffffff">
                                        <br>
                                        <table border="1" cellspacing="0" cellpadding="0" width="100%">
                                            <tr valign="top">
                                                <td align="center" class="v12" rowspan="2"><?php echo $LDDate; ?></td>
                                                <td align="center" class="v12" width="3%" rowspan="2"><?php echo $LDClockTime; ?></td>
                                                <td align="center" class="v12" width="3%" rowspan="2"><?php echo $LDNhiptim; ?></td>
                                                <td align="center" class="v12" width="3%" rowspan="2"><?php echo $LDNuocoi; ?></td>
                                                <td align="center" class="v12" width="3%" rowspan="2"><?php echo $LDDochongkhop; ?></td>
                                                <td align="center" class="v12" width="3%" rowspan="2"><?php echo $LDCTC."<br/>(cm)"; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDDolot; ?></td>
                                                <td align="center" class="v12" width="3%" colspan="2"><?php echo $LDSoCC; ?></td>
                                                <td align="center" class="v12" width="3%" rowspan="2"><?php echo $LDOxy; ?></td>
                                                <td align="center" class="v12" width="3%" rowspan="2"><?php echo $LDGiot; ?></td>
                                                <td align="center" class="v12" width="10%" rowspan="2"><?php echo $LDThuocDT; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDMach; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDBp; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDThannhiet; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDNuoctieu['1']; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDNuoctieu['2']; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDNuoctieu['3']; ?></td>
                                                <td align="center" class="v12" width="4%" rowspan="2"><?php echo $LDNotes; ?></td>
                                            </tr>
                                            <tr>
                                                <td align="center" class="v12" width="4%"><?php echo $Soconco; ?></td>
                                                <td align="center" class="v12" width="4%"><?php echo $second; ?></td>
                                            </tr>
                        <?php 
                            $bb=array();
                            for($i=0;$i<$maxelement;$i++){
                                if($i==0){ 
                                    $tempvalue=$temptimenow;
                                    $tempdatevalue=$tempdatenow;
                                }
                                else{
                                    $tempvalue='';
                                    $tempdatevalue='';
                                }
                                    echo '  <tr>
                                                <td align="center">
                                                '.$calendar->show_calendar($calendar, $date_format, 'bdate'.$i, $tempdatevalue).'
                                                </td>
                                                <td align="center">
                                                    <input type="text" id="btime'.$i.'" name="btime'.$i.'" size=5 maxlength=5 value="'.$tempvalue.'" onBlur="checkTime(this)">
                                                        <script language="javascript">
                                                            $(function(){
                                                                $("#btime'.$i.'").mask("**:**");
                                                            });
                                                        </script>
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="nhiptim'.$i.'" size=4 maxlength=10 value="" onBlur="checkNumberItem(this)" />
                                                </td>
                                                <td align="center">
                                                    <select name="nuocoi'.$i.'">
                                                        <option value=""></option>
                                                        <option value="'.$oi[0].'">'.$oi[0].'</option>
                                                        <option value="'.$oi[1].'">'.$oi[1].'</option>
                                                        <option value="'.$oi[2].'">'.$oi[2].'</option>
                                                        <option value="'.$oi[3].'">'.$oi[3].'</option>
                                                        <option value="'.$oi[4].'">'.$oi[4].'</option>
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <select name="dochongkhop'.$i.'">
                                                        <option value=""></option>
                                                        <option value="'.$LDYes1.'">'.$LDYes1.'</option>
                                                        <option value="'.$LDNo.'">'.$LDNo.'</option>
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="cotucung'.$i.'" size=2 maxlength=5 value="" onBlur="checkNumberItem(this)" />
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="dolot'.$i.'" size=2 maxlength=5 value="" onBlur="checkNumberItem(this)" />
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="soconco'.$i.'" size=2 maxlength=5 value="" onBlur="checkNumberItem(this)" />
                                                </td> 
                                                <td align="center">
                                                    <input type="text" name="sogiay'.$i.'" size=2 maxlength=5 value="" onBlur="checkNumberItem(this)" />
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="oxytocin'.$i.'" size=2 maxlength=5 value="" />
                                                </td> 
                                                <td align="center">
                                                    <input type="text" name="sogiot'.$i.'" size=2 maxlength=5 value="" />
                                                </td>  
                                                <td align="center">
                                                    <textarea name="thuoc'.$i.'" cols="18" rows="1" wrap="physical"></textarea>
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="mach'.$i.'" size=2 maxlength=5 value="" onBlur="checkNumberItem(this)" />
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="huyetap'.$i.'" size=5 maxlength=6 value="" onBlur="checkNumberItem(this)" />
                                                </td>
                                                <td align="center">
                                                    <input type="text" name="nhietdo'.$i.'" size=2 maxlength=5 value="" onBlur="checkNumberItem(this)" />
                                                </td>
                                                <td align="center">
                                                    <select name="dam'.$i.'">
                                                        <option value=""></option>
                                                        <option value="'.$tinhchat[0].'">'.$tinhchat[0].'</option>
                                                        <option value="'.$tinhchat[1].'">'.$tinhchat[1].'</option>
                                                        <option value="'.$tinhchat[2].'">'.$tinhchat[2].'</option>
                                                        <option value="'.$tinhchat[3].'">'.$tinhchat[3].'</option>
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <select name="acetone'.$i.'">
                                                        <option value=""></option>
                                                        <option value="'.$tinhchat[0].'">'.$tinhchat[0].'</option>
                                                        <option value="'.$tinhchat[1].'">'.$tinhchat[1].'</option>
                                                        <option value="'.$tinhchat[2].'">'.$tinhchat[2].'</option>
                                                        <option value="'.$tinhchat[3].'">'.$tinhchat[3].'</option>
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <select name="luong'.$i.'">
                                                        <option value=""></option>
                                                        <option value="'.$tinhchat[0].'">'.$tinhchat[0].'</option>
                                                        <option value="'.$tinhchat[1].'">'.$tinhchat[1].'</option>
                                                        <option value="'.$tinhchat[2].'">'.$tinhchat[2].'</option>
                                                        <option value="'.$tinhchat[3].'">'.$tinhchat[3].'</option>
                                                    </select>
                                                </td>
                                                <td align="center">
                                                    <textarea name="ghichu'.$i.'" cols="18" rows = "1" wrap = "physical"></textarea>
                                                </td>
                                            </tr>';
                            }
                    ?>
                                        </table>
                                        <br/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </font>
            <input type="hidden" name="sid" value="<?php echo $sid ?>" />
            <input type="hidden" name="lang" value="<?php echo $lang ?>" />
            <input type="hidden" name="winid" value="<?php echo $winid ?>" />
            <input type="hidden" name="station" value="<?php echo $station ?>" />
            <input type="hidden" name="yr" value="<?php echo $yr ?>" />
            <input type="hidden" name="mo" value="<?php echo $mo ?>" />
            <input type="hidden" name="dy" value="<?php echo $dy ?>" />
            <input type="hidden" name="dyidx" value="<?php echo $dyidx ?>" />
            <input type="hidden" name="dystart" value="<?php echo $dystart ?>" />
            <input type="hidden" name="monstart" value="<?php echo $monstart ?>" />
            <input type="hidden" name="yrstart" value="<?php echo $yrstart ?>" />
            <input type="hidden" name="dyname" value="<?php echo $dyname ?>" />
            <input type="hidden" name="pn" value="<?php echo $pn ?>" />
            <input type="hidden" name="edit" value="<?php echo $edit ?>" />
            <input type="hidden" name="mode" value="save" />
        </form>
    </font>
    <center>
        <p>
            <a href="javascript:document.infoform.submit();">
                <img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> alt="<?php echo $LDSave ?>">
            </a>    
        <?php if($saved)  : ?>
            <a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDClose ?>"></a>
        <?php else : ?>
            <a href="javascript:window.close()"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?> alt="<?php echo $LDClose ?>"></a>
        <?php endif ?>
        </p>
    </center>
</BODY>

</HTML>