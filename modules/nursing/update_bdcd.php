<?php
    error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

    require ('./roots.php');
    require ($root_path . 'include/core/inc_environment_global.php');
    $lang_tables=array('aufnahme.php');
    define('LANG_FILE', 'nursing.php');
    $local_user='ck_pflege_user';
    require_once ($root_path . 'include/core/inc_front_chain_lang.php');
    include_once ($root_path . 'include/core/inc_date_format_functions.php') ;
    /* Create charts object */
    require_once ($root_path.'include/care_api_classes/class_charts.php');
    $charts_obj=new Charts;
    require($root_path.'include/core/inc_css_a_hilitebu.php');
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    if($mode=='update'){
        $_POST['msr_date']= formatDate2STD($_POST['msr_date'],$date_format); 
        $_POST['msr_time']= @convertTimeToStandard($_POST['msr_time']);
        $info=$charts_obj->updatebdcd($_POST,$nr);
        if($info){
            echo '<script language="javascript">window.close();window.opener.location.href="nursing-getdaily_bdcd.php?sid='.$sid.'&lang='.$lang.'&edit='.$edit.'&winid='.$winid.'&station='.$station.'&pn='.$pn.'&yr='.$yr.'&mo='.$mo.'&yrstart='.$yrstart.'&monstart='.$monstart.'&nofocus=1";</script>';
        }
    }else if($mode=='delete'){
        $info=$charts_obj->DeleteInfoBDCD($nr);
        if($info){
            echo '<script language="javascript">window.close();window.opener.location.href="nursing-getdaily_bdcd.php?sid='.$sid.'&lang='.$lang.'&edit='.$edit.'&winid='.$winid.'&station='.$station.'&pn='.$pn.'&yr='.$yr.'&mo='.$mo.'&yrstart='.$yrstart.'&monstart='.$monstart.'&nofocus=1";</script>';
        }
    }
?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $LDUpdate; ?></TITLE>
<script language="javascript">
    function pruf(flag){
        if(flag){
            document.getElementById('mode').value=flag;
            return true;
        }else
            return false;        
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
    function parentrefresh(){
        window.opener.location.href="nursing-getdaily_bdcd.php?sid=<?php echo "$sid&lang=$lang&edit=$edit&winid=$winid&station=$station&pn=$pn&yr=$yr&mo=$mo&yrstart=$yrstart&monstart=$monstart"; ?>&nofocus=1";
    }
    $(function(){
        $("#msr_time").mask("**:**");
        $("#f-calendar-field-1").mask("**/**/****");
    });
</script>

</HEAD>
<BODY>

<form name="updateform" method="post">
    <font face="verdana,arial" size=4 color=maroon><b><?php echo $LDUpdate; ?></b></font>
    <br/>
<?php
    if($nr!=''){
        $item_query = $charts_obj->getInfoCondition('*',$pn,'','','nr='.$nr);
	if($item_query){
                $item=$item_query->FetchRow();
		echo '&nbsp;'.$LDDay.'&nbsp; &nbsp;'.@formatDate2Local($item['msr_date'],'dd/mm/yyyy').'<p>'; 
                $tbg= 'background="'.$root_path.'gui/img/common/'.$theme_com_icon.'/tableHeaderbg3.gif"';
?>
                <table border=0 cellpadding=1 cellspacing=1 width=100%>
                    <tr bgcolor="#f6f6f6">
                        <td <?php echo $tbg; ?>width="9%" align="center" rowspan="2"><font color="red"><b><?php echo $LDDate; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="2%" rowspan="2"><font color="red"><b><?php echo $LDClockTime ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="2%" rowspan="2"><font color="red"><b><?php echo $LDNhiptim; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="3%" rowspan="2"><font color="red"><b><?php echo $LDNuocoi; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="3%" rowspan="2"><font color="red"><b><?php echo $LDDochongkhop; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="3%" rowspan="2"><font color="red"><b><?php echo $LDCTC."<br/>(cm)"; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDDolot; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" colspan="2"><font color="red"><?php echo $LDSoCC; ?></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDOxy; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDGiot; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="10%" rowspan="2"><font color="red"><b><?php echo $LDThuocDT; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDMach; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDBp; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDThannhiet; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNuoctieu['1']; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNuoctieu['2']; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNuoctieu['3']; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%" rowspan="2"><font color="red"><b><?php echo $LDNotes; ?></b></font></td>
                    </tr>
                    <tr>
                        <td align="center" <?php echo $tbg; ?> width="4%"><font color="red"><b><?php echo $Soconco; ?></b></font></td>
                        <td align="center" <?php echo $tbg; ?> width="4%"><font color="red"><b><?php echo $second; ?></b></font></td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <?php
                                echo $calendar->show_calendar($calendar,$date_format,'msr_date',$item['msr_date']);
                            ?>
                        </td>
                        <td valign="top">
                            <input type="text" name="msr_time" id="msr_time" size=3 maxlength=5 value="<?php echo substr($item['msr_time'],0,5);?>">
                        </td>
                        <td valign="top">
                            <input type="text" name="nhiptim" id="nhiptim" size=2 maxlength=7 value="<?php echo $item['nhiptim'];?>" onBlur="checkNumberItem(this)">
                        </td>
                        <td align="center" valign="top">
                            <?php
                                echo '<select name="nuocoi">
                                            <option value="'.$oi[0].'"';
                                            if($item['nuocoi']==$oi[0]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$oi[0].'</option>
                                                <option value="'.$oi[1].'"';
                                            if($item['nuocoi']==$oi[1]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$oi[1].'</option>
                                                <option value="'.$oi[2].'"';
                                            if($item['nuocoi']==$oi[2]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$oi[2].'</option>
                                                <option value="'.$oi[3].'"';
                                            if($item['nuocoi']==$oi[3]){
                                                echo ' selected='."'selected'";
                                            }                
                                            echo '>'.$oi[3].'</option>
                                                <option value="'.$oi[4].'"';
                                            if($item['nuocoi']==$oi[4]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$oi[4].'</option></select>';
                            ?>
                        </td>
                        <td align="center" valign="top">
                        <?php
                            echo '<select name="dochongkhop">
                                        <option value="'.$LDYes1.'"';
                                            if($item['dochongkhop']==$LDYes1){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$LDYes1.'</option>
                                                <option value="'.$LDNo.'"';  
                                            if($item['dochongkhop']==$LDNo){
                                                echo ' selected="selected"';
                                            } 
                                            echo '>'.$LDNo.'</option></select>';
                        ?>
                        </td>
                        <td align="center" valign="top">
                            <input type="text" name="cotucung" size=2 maxlength=5 value="<?php echo $item['cotucung'];?>" onBlur="checkNumberItem(this)" />
                        </td>
                        <td align="center" valign="top">
                            <input type="text" name="dolot" size=2 maxlength=5 value="<?php echo $item['dolot'];?>" onBlur="checkNumberItem(this)" />
                        </td>
                        <td align="center" valign="top">
                            <input type="text" name="soconco" size=2 maxlength=5 value="<?php echo $item['soconco'];?>" onBlur="checkNumberItem(this)" />
                        </td> 
                        <td align="center" valign="top">
                            <input type="text" name="sogiay" size=2 maxlength=5 value="<?php echo $item['sogiay'];?>" onBlur="checkNumberItem(this)" />
                        </td>
                        <td align="center" valign="top">
                            <input type="text" name="oxytocin" size=2 maxlength=5 value="<?php echo $item['oxytocin'];?>" />
                        </td> 
                        <td align="center" valign="top">
                            <input type="text" name="sogiot" size=2 maxlength=5 value="<?php echo $item['sogiot'];?>" />
                        </td>  
                        <td align="center" valign="top">
                            <textarea name="thuoc" cols="15" rows = "4" wrap = "physical"><?php echo $item['thuoc'];?></textarea>
                        </td>
                        <td align="center" valign="top">
                            <input type="text" name="mach" size=2 maxlength=5 value="<?php echo $item['mach'];?>" onBlur="checkNumberItem(this)" />
                        </td>
                        <td align="center" valign="top">
                            <input type="text" name="huyetap" size=4 maxlength=6 value="<?php echo $item['huyetap'];?>" onBlur="checkNumberItem(this)" />
                        </td>
                        <td align="center" valign="top">
                            <input type="text" name="nhietdo" size=2 maxlength=5 value="<?php echo $item['nhietdo'];?>" onBlur="checkNumberItem(this)" />
                        </td>
                        <td align="center" valign="top">
                            <?php
                                echo '<select name="dam">
											<option value=""></option>
                                            <option value="'.$tinhchat[0].'"';
                                            if($item['dam']==$tinhchat[0]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[0].'</option>
                                                <option value="'.$tinhchat[1].'"';
                                            if($item['dam']==$tinhchat[1]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[1].'</option>
                                                <option value="'.$tinhchat[2].'"';
                                            if($item['dam']==$tinhchat[2]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[2].'</option>
                                                <option value="'.$tinhchat[3].'"';
                                            if($item['dam']==$tinhchat[3]){
                                                echo ' selected='."'selected'";
                                            }                
                                            echo '>'.$tinhchat[3].'</option></select>';
                            ?>
                        </td>
                        <td align="center" valign="top">
                            <?php
                                echo '<select name="acetone">
											<option value=""></option>
                                            <option value="'.$tinhchat[0].'"';
                                            if($item['acetone']==$tinhchat[0]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[0].'</option>
                                                <option value="'.$tinhchat[1].'"';
                                            if($item['acetone']==$tinhchat[1]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[1].'</option>
                                                <option value="'.$tinhchat[2].'"';
                                            if($item['acetone']==$tinhchat[2]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[2].'</option>
                                                <option value="'.$tinhchat[3].'"';
                                            if($item['acetone']==$tinhchat[3]){
                                                echo ' selected='."'selected'";
                                            }                
                                            echo '>'.$tinhchat[3].'</option></select>';
                            ?>
                        </td>
                        <td align="center" valign="top">
                            <?php
                                echo '<select name="luong">
											<option value=""></option>
                                            <option value="'.$tinhchat[0].'"';
                                            if($item['luong']==$tinhchat[0]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[0].'</option>
                                                <option value="'.$tinhchat[1].'"';
                                            if($item['luong']==$tinhchat[1]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[1].'</option>
                                                <option value="'.$tinhchat[2].'"';
                                            if($item['luong']==$tinhchat[2]){
                                                echo ' selected="selected"';
                                            }                
                                            echo '>'.$tinhchat[2].'</option>
                                                <option value="'.$tinhchat[3].'"';
                                            if($item['luong']==$tinhchat[3]){
                                                echo ' selected='."'selected'";
                                            }                
                                            echo '>'.$tinhchat[3].'</option></select>';
                            ?>
                        </td>
                        <td align="center" valign="top">
                            <textarea name="ghichu" cols="15" rows = "4" wrap = "physical"><?php echo $item['ghichu'];?></textarea>
                        </td>
                    </tr>
                </table>
    <?php
            }
        }
    ?>
    <p>&nbsp;
    <p>
    &nbsp;
    <input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> onclick="pruf('update')">
<!--    <a href="javascript:saveitem();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> title="<?php echo $LDSave ?>"></a>&nbsp;-->
    <input type="image" <?php echo createLDImgSrc($root_path,'delete.gif','0') ?> onclick="pruf('delete')">
<!--    <a href="javascript:deleteitem();"><img <?php echo createLDImgSrc($root_path,'delete.gif','0') ?> title="<?php echo $LDDelete ?>"></a>&nbsp;-->
    <a href="javascript:window.close();"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> title="<?php echo $LDClose ?>"></a>
    <input type="hidden" name="winid" id="winid" value="<?php echo $winid;?>" />
    <input type="hidden" name="station" value="<?php echo $station ?>">
    <input type="hidden" name="yrstart" value="<?php echo $yrstart ?>">
    <input type="hidden" name="monstart" value="<?php echo $monstart ?>">
    <input type="hidden" name="yr" value="<?php echo $yr ?>">
    <input type="hidden" name="mo" value="<?php echo $mo ?>">
    <input type="hidden" name="nr" value="<?php echo $nr ?>">
    <input type="hidden" name="mode" id="mode" value="">
    <input type="hidden" name="edit" value="<?php echo $edit ?>" />
</form>

</BODY>
</HTML>