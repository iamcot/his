<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    define('LANG_FILE','or.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    # Create the personell object 
    require_once($root_path.'include/care_api_classes/class_pharma_dept.php');
    $pharma=new Pharma_Dept();
    $title=$LDPharmaDept; 
    if($ln!=''){
       header("Location:op-pflege-log-getinfo_pharma.php?sid=$sid&lang=$lang&dept_nr=$dept_nr&ward_nr=$ward_nr&batch_nr=$batch_nr&op_nr=$op_nr&enc_nr=$enc_nr&pday=$pday&pmonth=$pmonth&pyear=$pyear&winid=$winid&ln=$ln&use=$use&mode=save&dblink_ok=1");
    }
    switch($winid)
    {
        case 'pharma': 
            $search=$pharma->searchPharmaInfo($inputdata,$dept_nr,$ward_nr);
            break;
        default:{header('Location:'.$root_path.'/language/'.$lang.'/lang_'.$lang.'_invalid-access-warning.php'); exit;};
    }

    $thisfile=basename(__FILE__);
    $forwardfile="op-pflege-log-getinfo_pharma.php?sid=$sid&lang=$lang".URL_REDIRECT_APPEND."&internok=".$internok."&mode=save&batch_nr=$batch_nr&dept_nr=$dept_nr&ward_nr=$ward_nr&saal=$saal&winid=$winid";
?>

<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $title ?></TITLE>
<STYLE type=text/css>
    div.box { border: double; border-width: thin; width: 100%; border-color: black; }
    .v12 { font-family:verdana,arial;font-size:12; }
    .v13 { font-family:verdana,arial;font-size:13; }
    .v13_n { font-family:verdana,arial;font-size:13;color:#0000cc }
    .v10 { font-family:verdana,arial;font-size:10; }
</STYLE>
<script language="javascript">
    function pruf(d){
        if(!d.inputdata.value) return false;
        else return true
    }
    function savedata(iln,inr,dept_nr,ward_nr)
    {
        if(inr>0){            
            d=document.infoform;
            d.action="op-pflege-log-getinfo_pharma.php";
            d.ln.value=iln;
            d.dept_nr.value=dept_nr;
            d.ward_nr.value=ward_nr;
            d.submit();            
        }else alert("Thuốc này đã hết");	
    }
    function alertselected(selectobj){        
        var number_use=selectobj.selectedIndex;
        d=document.infoform;
        d.use.value=number_use;
    }
</script>
<?php
    require($root_path.'include/core/inc_js_gethelp.php');
    require($root_path.'include/core/inc_css_a_hilitebu.php');
?>

</HEAD>
<BODY   bgcolor="#cde1ec" TEXT="#000000" LINK="#0000FF" VLINK="#800080" topmargin=2 marginheight=2 
onLoad="if (window.focus) window.focus(); window.focus();document.infoform.inputdata.focus();" >
    
<a href="javascript:gethelp()">
    <img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?> alt="<?php echo $LDHelp ?>" align="right">
</a>

<form name="infoform" action="<?php echo $thisfile;?>" method="post" onSubmit="return pruf(this)">
    <img <?php echo createComIcon($root_path,'magnify.gif','0','absmiddle'); ?>>
    <font face=verdana,arial size=5 color=maroon>
        <b>
        <?php 
            echo str_replace("~tagword~",$title,$LDSearchPerson)."...";
        ?>
        </b>
    </font>

    <table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0 >
        <tr>
            <td>
                <table border=0 width=100% cellspacing=1>
                    <tr>
                        <td  align=center bgcolor="#cfcfcf" class="v13_n" colspan="6">
							<?php echo $LDSearchResult ?>:
                        </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#ffffff" class="v13_n">
                            <?php echo $LDNamePharma ?>
                        </td>
                    <td align=center bgcolor="#ffffff" class="v13_n">
                        <?php echo $LDEffect ?>
                    </td>
                    <td align=center bgcolor="#ffffff" class="v13_n">
                        <?php echo $LDCaution ?>
                    </td>
                    <td align=center bgcolor="#ffffff"   class="v13_n" >
                        <?php echo "$LDNumberNow" ?>
                    </td>
                    <td align=center bgcolor="#ffffff"   class="v13_n" >
                        <?php echo "$LDNumberUse" ?>
                    </td>
                    <td align=center bgcolor="#ffffff" class="v13_n">                            
                    </td>
                    </tr>
                    <?php if($pharma->count) : ?>
                    <?php 	
                        $counter=0;
                        while($result=$search->FetchRow())
                        {                            
                            $issue_paper=$pharma->getPharmaInfo($result['product_encoder'],$dept_nr,$ward_nr);
                            $detail=$pharma->getPharmaInfoDetail($result['product_encoder'],$dept_nr,$ward_nr);
                            echo '
                                <tr bgcolor="#ffffff">
                                <td class="v13" align="center" width=15%>'.$issue_paper['product_name'].'
                                </td>';
                            echo "
                                <td class='v13' width='30%'>".
                                    $detail['effects']."
                                </td>
                                <td class='v13' width='30%'>".
                                    $detail['caution']."
                                </td>";
                            $unit=$pharma->getunitPharma($result['product_encoder']);
                            $numbernow=$pharma->getnumberPharma($result['product_encoder'],$dept_nr,$ward_nr);
                            echo '<td class="v13" align="center">'.$numbernow['number'].'&nbsp;&nbsp;'.$unit['unit_name_of_medicine'].'</td>';
                            //Số lượng cần dùng chỉ cho phép < Số lượng hiện có
                            echo '<td class="v13" align="center">
                                <table width=50%></tr><td width=80%>
                                <select name="number_use" onChange="alertselected(this)"><option value="nothing">Chọn số lượng</option>';
                            if($numbernow['number']>0){
                                for($i=1;$i<=$numbernow['number'];$i++){                                    
                                    echo'<option value="'.$i.'"';
                                    echo '>'.$i.'</option>';
                                }
                            }
                            echo '</select></td><td>
                                </tr>
                                </table>
                                </td>';
                            echo '<td class="v13" align="center">
                                    <a href="javascript:savedata(\''.$result['product_encoder'].'\',\''.$numbernow['number'].'\',\''.$dept_nr.'\',\''.$ward_nr.'\')">
                                        <img '.createLDImgSrc($root_path,'ok_small.gif','0').'/>
                                    </a>                                    
                                 ';
                            echo '</td></tr>';
                            $counter++;
                        }
                    ?>

                    <?php else : ?>
                    <tr>
                        <td bgcolor="#ffffff"  colspan=5 align=center>
                            <table border=0>
                                <tr>
                                    <td>
                                        <img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom'); ?>/>
                                    </td>
                                    <td><font size=3 color=maroon face=verdana,arial>
                                        <?php echo $LDSorryNotFound ?>
                                    </td>
                                </tr>
                            </table>
                        </td> 
                    </tr>	
                    <?php endif ?>
                    <tr>
                        <td  class="v12"  bgcolor="#ffffff" colspan=6 align=center><br><p>
                        <font size=3><b><?php echo str_replace("~tagword~",$title,$LDSearchNewPerson) ?>:</b>
                        <br>
                        <input type="text" name="inputdata" size=25 maxlength=30>
                        <input type="submit" value="<?php echo $LDSearch ?>">
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" name="encoder" value="<?php echo $_COOKIE[$sid]; ?>"/>
    <input type="hidden" name="sid" value="<?php echo $sid ?>"/>
    <input type="hidden" name="lang" value="<?php echo $lang ?>"/>
    <input type="hidden" name="winid" value="<?php echo $winid ?>"/>
    <input type="hidden" name="dept_nr" value="<?php echo $dept_nr ?>"/>
    <input type="hidden" name="ward_nr" value="<?php echo $ward_nr ?>"/>
    <input type="hidden" name="batch_nr" value="<?php echo $batch_nr ?>"/>
    <input type="hidden" name="mode" value="save"/>
    <input type="hidden" name="ln" value=""/>
    <input type="hidden" name="use" value=""/>
</form>
<p>
<p>
<a href="<?php echo "op-pflege-log-getinfo_pharma.php?sid=$sid&lang=$lang&dept_nr=$dept_nr&ward_nr=$ward_nr&saal=$saal&op_nr=$op_nr&enc_nr=$enc_nr&pday=$pday&pmonth=$pmonth&pyear=$pyear&winid=$winid";?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0','left'); ?>>
</a>

</BODY>

</HTML>
