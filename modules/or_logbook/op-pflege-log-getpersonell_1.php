<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','or.php');
define('NO_2LEVEL_CHK',1);
$local_user='ck_opdoku_user';

require_once($root_path.'include/core/inc_front_chain_lang.php');
# Create the personell object
require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
require_once($root_path.'include/care_api_classes/class_encounter_op.php');
$enc_op_obj=new OPEncounter();
$title=$LDOpPersonElements[$winid];

$thisfile=basename(__FILE__);
$forwardfile="op-pflege-log-getinfo_1.php?sid=$sid&lang=$lang&mode=save&batch_nr=$batch_nr&dept_nr=$dept_nr&saal=$saal&winid=$winid&date_request=$date_request";
switch($winid)
{
    case 'operator':
                    $element='operator';
                    //$maxelement=10;
                    $quickid='doctor';
                    $function=4;
                    $search=$pers_obj->searchPersonellInfo($inputdata,$function);
                    $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                    $duty=$pers_obj->getDOCDutyplan($dept_nr,$pyear,$pmonth);
                    $a_pnr=unserialize($duty['duty_1_pnr']);
                    $r_pnr=unserialize($duty['duty_2_pnr']);
                    break;
    case 'ana_assist':
                    $element='an_doctor';
                    //$maxelement=10;
                    $quickid='doctor';
                    $function=8;
                    $search=$pers_obj->searchPersonellInfo($inputdata,$function);
                    $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                    $duty=$pers_obj->getDOCDutyplan($dept_nr,$pyear,$pday);
                    $a_pnr=unserialize($duty['duty_1_pnr']);
                    $r_pnr=unserialize($duty['duty_2_pnr']);
                    break;
    case 'assist':
                    $element='assistant';
                    //$maxelement=10;
                    $quickid='doctor';
                    $function=4;//12;
                    $search=$pers_obj->searchPersonellInfo($inputdata,$function);
                    $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                    $duty=$pers_obj->getDOCDutyplan($dept_nr,$pyear,$pday);
                    $a_pnr=unserialize($duty['duty_1_pnr']);
                    $r_pnr=unserialize($duty['duty_2_pnr']);
                    break;
    case 'scrub':
                    $element='scrub_nurse';
                    //$maxelement=10;
                    $quickid='nurse';
                    $function=10;
                    $search=$pers_obj->searchPersonellInfo($inputdata,$function,'','','','');
                    $quicklist=$pers_obj->getNursesOfDept($dept_nr,$function,'5');
                    $duty=$pers_obj->getNOCDutyplan($dept_nr,$pyear,$pday);
                    $a_pnr=unserialize($duty['duty_1_pnr']);
                    $r_pnr=unserialize($duty['duty_2_pnr']);
                    break;
    case 'rotating':
                    $element='rotating_nurse';
                    //$maxelement=10;
                    $quickid='nurse';
                    $function=7;//11;
                    $search=$pers_obj->searchPersonellInfo($inputdata,$function);
                    $quicklist=$pers_obj->getNursesOfDept($dept_nr,$function,'5');
                    $duty=$pers_obj->getNOCDutyplan($dept_nr,$pyear,$pday);
                    $a_pnr=unserialize($duty['duty_1_pnr']);
                    $r_pnr=unserialize($duty['duty_2_pnr']);
                    break;
    case 'ana':
                    $element='anesthesia';
                    //$maxelement=10;
                    $quickid='doctor';
                    $function=5;
                    $search=$pers_obj->searchPersonellInfo($inputdata,$function);
                    $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                    $duty=$pers_obj->getDOCDutyplan($dept_nr,$pyear,$pday);
                    $a_pnr=unserialize($duty['duty_1_pnr']);
                    $r_pnr=unserialize($duty['duty_2_pnr']);
                    break;
    default:{header('Location:'.$root_path.'language/'.$lang.'/lang_'.$lang.'_invalid-access-warning.php'); exit;};
}
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
    function resetinput(){
        document.infoform.reset();
    }

    function pruf(d){
        if(!d.inputdata.value) return false;
        else return true
    }
    function savedata(iln,ifn,inx,ipr,inr,ipnr,time,date_request)
    {
        if(inr==''){
            d=document.infoform;
            d.action="op-pflege-log-getinfo_1.php";
            d.ln.value=iln;            
            d.fn.value=ifn;
            d.pr.value=ipr;            
            d.nx.value=inx;
            d.personell_nr.value=ipnr;          
            d.time_chose.value=time;
            d.date_request.value=date_request;         
            d.submit();
        }else{
            alert("<?php echo $LDNote5?>");
        }
    }
    function savedata_bytime(iln,ifn,inx,ipr,ipnr,time,op_date,check,date_request)
    {
        var answer = confirm ("<?php echo $LDNote4 ?>"+check+"\n"+"<?php echo $LDWarning ?>");
        if (answer){
            d=document.infoform;
            d.action="op-pflege-log-getinfo_1.php";
            d.ln.value=iln;
            d.fn.value=ifn;
            d.pr.value=ipr;
            d.nx.value=inx;
            d.personell_nr.value=ipnr;
            d.date_chose.value=op_date;
            d.time_chose.value=time;
            d.date_request.value=date_request;
            d.submit();
        }
    }
</script>
<?php
require($root_path.'include/core/inc_js_gethelp.php');
require($root_path.'include/core/inc_css_a_hilitebu.php');
?>

</HEAD>
<BODY   bgcolor="#cde1ec" TEXT="#000000" LINK="#0000FF" VLINK="#800080" topmargin=2 marginheight=2
onLoad="<?php if($saved) echo "parentrefresh();"; ?>if (window.focus) window.focus(); window.focus();document.infoform.inputdata.focus();" >
    
<a href="javascript:gethelp()"><img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?> alt="<?php echo $LDHelp ?>" align="right"></a>

<form name="infoform" action="<?php echo 'op-pflege-log-getpersonell_1.php?sid='.$sid.'&lang='.$lang.'&inputdata='.$inputdata.'&date_request='.$date_request.'&winid='.$winid; ?>" method="post" onSubmit="return pruf(this)">
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
                        <td align=center bgcolor="#cfcfcf" class="v13_n" colspan=6>
                            <?php echo $LDSearchResult ?>:
                        </td>
                    </tr>
                <tr>
                    <td align=center bgcolor="#ffffff" class="v13_n" >
                        <?php echo $LDLastName ?>
                    </td>
                    <td align=center bgcolor="#ffffff"  colspan="2" class="v13_n" >
                        <?php echo "$LDFunction" ?>
                    </td>
                    <td align=center bgcolor="#ffffff"   class="v13_n" >
                        <?php echo $LDFunction.' '.$LDIn1.' '.$LDOrEkip1; ?>
                    </td>                    
                    <td align=center bgcolor="#ffffff"   class="v13_n">
                        <?php echo $LDDutyPlan; ?>
                    </td>
                </tr>

                <?php if($pers_obj->record_count) : ?>

                <?php 	
                    $counter=0;
                    require_once($root_path.'include/core/inc_date_format_functions.php'); 
					if($search){
						while($qlist=$search->FetchRow())
						{
							$encounter_op=$enc_op_obj->getInfo($batch_nr,'pending');
							$maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));
							echo '
								<tr bgcolor="#ffffff">
								<td class="v13" >';
							$request_op=$enc_op_obj->getInfoTest($batch_nr,'pending');
							$date=$request_op->FetchRow();
							$op_date=$date['date_request'];
							$info=$encounter_op->FetchRow();                            
							$op_nr=$info['nr'];                            
							$op_time=$info['doc_time'];                            
							$check=$enc_op_obj->getStatus($qlist[nr],$op_date,'1','',$info['nr']);
							if($check[doc_time]){
								$time_chose=$check[doc_time];                                
								$check_status=$enc_op_obj->getStatus($qlist[nr],'','','',$info['nr']);
								$status=$check_status[status];
								echo '
									&nbsp;<a href="javascript:savedata_bytime(\''.$qlist[name_last].'\',\''.$qlist[name_first].'\',\''.$counter.'\',\''.$qlist[name].'\',\''.$qlist[nr].'\',\''.$op_time.'\',\''.$time_chose.'\',\''.$status.'\',\''.$date_request.'\')" title="'.str_replace("~tagword~",$title,$LDUseData).'">'.$qlist[name_last].' '.$qlist[name_first].'</a>';
							}else{
								$date=$encounter_op->FetchRow();
								echo '
									&nbsp;<a href="javascript:savedata(\''.$qlist[name_last].'\',\''.$qlist[name_first].'\',\''.$counter.'\',\''.$qlist[name].'\',\'\',\''.$qlist[nr].'\',\''.$op_time.'\',\''.$date_request.'\')" title="'.str_replace("~tagword~",$title,$LDUseData).'">'.$qlist[name_last].' '.$qlist[name_first].'</a>';
							}
							echo '</td> ';
							echo '
							<td class="v13" colspan="2">
							&nbsp;'.$qlist['name'].'
							<select name="f'.$counter.'" style="display:none">';
							if(!$entrycount) $entrycount=1;                            
							for($i=1;$i<=($entrycount);$i++)
							{
								echo '
								<option value="'.$i.'" ';
								if($i==$entrycount) echo "selected";
								echo '>'.$title.'</option>';
							}
							echo '
							</select>

							</td>
							<td   class="v13" >
							&nbsp;<a href="javascript:savedata(\''.$qlist[name_last].'\',\''.$qlist[name_first].'\',\''.$counter.'\',\''.$qlist[name].'\',\''.$status.'\',\''.$qlist[nr].'\',\''.$time_chose.'\',\''.$date_request.'\')"><img '.createComIcon($root_path,'dwnarrowgrnlrg.gif','0').' align=absmiddle>
							'.str_replace("~tagword~",$title,$LDUseData).'..</a>
							</td>';
							$pers_obj->useDutyplanTable();
                            switch($winid){
                                case 'operator':
                                case 'assist':
                                    $dutyplan_doc=&$pers_obj->getDOCDutyplan($dept_nr,substr($date_request, 0,4),substr($date_request, 5,2));
                                    $dutyplan_nur=&$pers_obj->getNOCDutyplan($dept_nr,substr($date_request, 0,4),substr($date_request, 5,2));
                                    $flag=strpos($dutyplan_doc[duty_1_pnr],$qlist[nr]);
                                    $flag_1=strpos($dutyplan_doc[duty_2_pnr],$qlist[nr]);
                                    $flag_2=strpos($dutyplan_doc[duty_3_pnr],$qlist[nr]);
                                    if($flag !== false){
                                        echo '<td>'.$LDTrucNgay.' '.$LDIn1.' '.$LDday1.' '.formatDate2Local($date_request, $date_format).'</td>';
                                    }else if ($flag_1 !== false || $flag_2 !== false) {
                                        echo '<td>'.$LDTrucNgoaigio.' '.$LDIn1.' '.$LDday1.' '.formatDate2Local($date_request, $date_format).'</td>';
                                    }else{
                                        echo '<td>'.$LDNo_truc.' '.$LDIn1.' '.$LDday1.' '.formatDate2Local($date_request, $date_format).'</td>';
                                    }
                                    break;
                                default:
                                    $dutyplan_nur=&$pers_obj->getNOCDutyplan($dept_nr,substr($date_request, 0,4),substr($date_request, 5,2));
                                    $flag=strpos($dutyplan_nur[duty_1_pnr],$qlist[nr]);
                                    $flag_1=strpos($dutyplan_nur[duty_2_pnr],$qlist[nr]);
                                    $flag_2=strpos($dutyplan_nur[duty_3_pnr],$qlist[nr]);
                                    if($flag !== false){
                                        echo '<td>'.$LDTrucNgay.' '.$LDIn1.' '.$LDday1.' '.formatDate2Local($date_request, $date_format).'</td>';
                                    }else if ($flag_1 !== false || $flag_2 !== false) {
                                        echo '<td>'.$LDTrucNgoaigio.' '.$LDIn1.' '.$LDday1.' '.formatDate2Local($date_request, $date_format).'</td>';
                                    }else{
                                        echo '<td>'.$LDNo_truc.' '.$LDIn1.' '.$LDday1.' '.formatDate2Local($date_request, $date_format).'</td>';
                                    }
                                    break;
                            }
                            echo '</tr>';
							$counter++;
						}  
					}else{
						echo '<font size="3" color="red"><b>'.$LDSorryNotFound.'</b></font>';
					}
                ?>

                <?php else : ?>
                <tr>
                <td bgcolor="#ffffff"  colspan=5 align=center>

                <table border=0>
                <tr>
                <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom'); ?>> </td>
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
    <input type="hidden" name="encoder" value="<?php echo $_COOKIE[$local_user.$sid]; ?>"/>
    <input type="hidden" name="sid" value="<?php echo $sid ?>"/>
    <input type="hidden" name="lang" value="<?php echo $lang ?>"/>
    <input type="hidden" name="winid" value="<?php echo $winid ?>"/>
    <input type="hidden" name="pyear" value="<?php echo $pyear ?>"/>
    <input type="hidden" name="pmonth" value="<?php echo $pmonth ?>"/>
    <input type="hidden" name="pday" value="<?php echo $pday ?>"/>
    <input type="hidden" name="dept_nr" value="<?php echo $dept_nr ?>"/>
    <input type="hidden" name="saal" value="<?php echo $saal ?>"/>
    <input type="hidden" name="op_nr" value="<?php echo $op_nr ?>"/>
    <input type="hidden" name="batch_nr" value="<?php echo $batch_nr ?>"/>
    <input type="hidden" name="mode" value="save"/>
    <input type="hidden" name="control" value="<?php echo $control?>"/>
    <input type="hidden" name="ln" value=""/>
    <input type="hidden" name="fn" value=""/>
    <input type="hidden" name="pr" value=""/>
    <input type="hidden" name="nx" value=""/>
    <input type="hidden" name="personell_nr" value=""/>
    <input type="hidden" name="date_chose" value=""/>
    <input type="hidden" name="time_chose" value=""/>
    <input type="hidden" name="date_request" value="<?php echo $date_request; ?>"/>
</form>
<p>
<a href="<?php echo "op-pflege-log-getinfo_1.php?sid=$sid&lang=$lang&dept_nr=$dept_nr&saal=$saal&op_nr=$op_nr&enc_nr=$enc_nr&batch_nr=$batch_nr&pday=$pday&pmonth=$pmonth&pyear=$pyear&winid=$winid&date_request=$date_request";?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0','left'); ?>>
</a>

</BODY>

</HTML>
