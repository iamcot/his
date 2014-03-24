﻿<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    define('LANG_FILE','or.php');
    $local_user='ck_opdoku_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    /* Create the personell object */
    require_once($root_path.'include/care_api_classes/class_personell.php');
    $pers_obj=new Personell;
    $search=$pers_obj->searchPersonellBasicInfo($inputdata);
    require_once($root_path.'include/care_api_classes/class_encounter_op.php');
    $enc_op_obj=new OPEncounter();
    $encounter_op=$enc_op_obj->getInfo($batch_nr,'pending'); 
    $title=$LDOpPersonElements[$winid];
    $pyear=date('Y');
    $pmonth=date('m');


    switch($winid)
    {
        case 'operator':
                        $element='operator';
                        //$maxelement=10;
                        $quickid='doctor';
                        $function='Bác sĩ phẫu thuật';
                        $role_nr = ROLE_NR_DOCTOR;
                        $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                        $duty=$pers_obj->getDOCDutyplan($dept_nr,$role_nr,$pyear,$pmonth);
                        $a_pnr=unserialize($duty['duty_1_pnr']);
                        $r_pnr=unserialize($duty['duty_2_pnr']);
                        break;
        case 'assist':
                        $role_nr = ROLE_NR_DOCTOR;
                        $element='assistant';
                        //$maxelement=10;
                        $quickid='doctor';
                        $function='Phụ mổ';
                        $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                        $duty=$pers_obj->getDOCDutyplan($dept_nr,$role_nr,$pyear,$pmonth);
                        $a_pnr=unserialize($duty['duty_1_pnr']);
                        $r_pnr=unserialize($duty['duty_2_pnr']);
                        break;
        case 'scrub':
                        $role_nr=ROLE_NR_NURSER;
                        $element='scrub_nurse';
                        //$maxelement=10;
                        $quickid='nurse';
                        $function='Y tá vòng trong';
                        $quicklist=$pers_obj->getNursesOfDept($dept_nr,$function,'5');
                        $duty=$pers_obj->getNOCDutyplan($dept_nr,$role_nr,$pyear,$pmonth);
                        $a_pnr=unserialize($duty['duty_1_pnr']);
                        $r_pnr=unserialize($duty['duty_2_pnr']);
                        break;
        case 'rotating':
            $role_nr=ROLE_NR_NURSER;
                        $element='rotating_nurse';
                        //$maxelement=10;
                        $quickid='nurse';
                        $function='Y tá vòng ngoài';
                        $quicklist=$pers_obj->getNursesOfDept($dept_nr,$function,'5');
                        $duty=$pers_obj->getNOCDutyplan($dept_nr,$role_nr,$pyear,$pmonth);
                        $a_pnr=unserialize($duty['duty_1_pnr']);
                        $r_pnr=unserialize($duty['duty_2_pnr']);
                        break;
        case 'ana':
            $role_nr = ROLE_NR_NURSER;
                        $element='anesthesia';
                        //$maxelement=10;
                        $quickid='doctor';
                        $function='Bác sĩ gây mê';
                        $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                        $duty=$pers_obj->getDOCDutyplan($dept_nr,$role_nr,$pyear,$pmonth);
                        $a_pnr=unserialize($duty['duty_1_pnr']);
                        $r_pnr=unserialize($duty['duty_2_pnr']);
                        break;
        case 'ana_assist':
            $role_nr = ROLE_NR_NURSER;
                        $element='an_doctor';
                        //$maxelement=10;
                        $quickid='doctor';
                        $function='Bác sĩ gây mê phụ';
                        $quicklist=$pers_obj->getDoctorsOfDept($dept_nr,$function,'5');
                        $duty=$pers_obj->getDOCDutyplan($dept_nr,$role_nr,$pyear,$pmonth);
                        $a_pnr=unserialize($duty['duty_1_pnr']);
                        $r_pnr=unserialize($duty['duty_2_pnr']);
                        break;
        default:{header('Location:'.$root_path.'language/'.$lang.'/lang_'.$lang.'_invalid-access-warning.php'); exit;};
    }

    if($pers_obj->record_count) $quickexist=true;

    $thisfile=basename(__FILE__);

    /* Establish db connection */
    if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
    if($dblink_ok){
        // get data if exists
        $dbtable='care_encounter_op';
        $sql="SELECT encoding,nr FROM $dbtable
                 WHERE batch_nr='$batch_nr'
                 AND op_room='$saal'";
        if($ergebnis=$db->Execute($sql)){
            $rows=$ergebnis->Recordcount();
            if(isset($rows)){
            $result=$ergebnis->FetchRow();
            $fileexist=1;
        }
        $personell=$enc_op_obj->searchPersonell($result['nr'],$function,'chosed');
        $nr_personell=$enc_op_obj->checkNr($result['nr'],$function,'chosed');
    }else{
        echo "$LDDbNoRead<br>";
    }

    if($mode=='save')
    {
        $dbtable='care_encounter_op';
        $dbtable1='care_personell_op';
        if($fileexist)
        {
            //bi?n $delitem c� gi� tr? khi nh?n n�t delete
            if($delitem!="")
            {
                $personell_op=$pers_obj->insertPersonelltemp($personell_nr,$result['nr'],'');
            }else{
                $personell_op=$pers_obj->insertPersonelltemp($personell_nr,$result['nr'],'chosed');
            }
            $user=$_SESSION [sess_user_name];
            $saveok=1;
        } // else create new entry
        else{
        }//end of else
        if($saveok)
        {
            header("location:$thisfile?sid=$sid&lang=$lang&mode=saveok&winid=$winid&batch_nr=$batch_nr&dept_nr=$dept_nr&saal=$saal&op_nr=$op_nr");
        }
    }// end of if(mode==save)
    else $saved=0;
    }
    else { echo "$LDDbNoLink<br>"; } 
?>

<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $title ?></TITLE>
<STYLE type=text/css>
    div.box { border: double; border-width: thin; width: 100%; border-color: black; }
    .v12 { font-family:verdana,arial;font-size:12; }
    .v13 { font-family:verdana,arial;font-size:13; }
    .v13_n { font-family:verdana,arial;font-size:13; color:#0000cc}
    .v10 { font-family:verdana,arial;font-size:10; }
</STYLE>

<script language="javascript">
    function pruf(d){
    if(!d.inputdata.value) return false;
    else return true
    }

    function delete_item(i,nr)
    {
    d=document.infoform;
    d.action="<?php echo $thisfile ?>";
    d.delitem.value=i;
    d.personell_nr.value=nr;
    d.submit();
    }
    function savedata(iln,ifn,inx,ipr,inr,ipnr,time)
    {
        if(inr==''){
            d=document.quickselect;
            d.ln.value=iln;
            d.fn.value=ifn;
            d.pr.value=ipr;
            d.nx.value=inx;
            d.personell_nr.value=ipnr;
            d.time_chose.value=time;
            d.submit();
        }else{
            alert("<?php echo $LDNote5?>");
        }
    }
    function savedata_bytime(iln,ifn,inx,ipr,ipnr,time,check,status)
    {
        //khi chua delete th� status=chosed s? th�ng b�o cho ngu?i d�ng bi?t ngu?i d� c� l?ch l�m �-k�p n�o d? ngu?i ta xem x�t
        if(status!=''){
            var answer = confirm ("<?php echo $LDNote4 ?>"+check+"\n"+"<?php echo $LDWarning ?>");
            if (answer){
                d=document.quickselect;
                d.ln.value=iln;
                d.fn.value=ifn;
                d.pr.value=ipr;
                d.nx.value=inx;
                d.personell_nr.value=ipnr;
                d.time_chose.value=time;
                d.submit();
            }
        //khi ch?n r?i delete di th� kh�ng c?n th�ng b�o cho ngu?i ta ch?n l?i li?n
        }else{
            d=document.quickselect;
            d.ln.value=iln;
            d.fn.value=ifn;
            d.pr.value=ipr;
            d.nx.value=inx;
            d.personell_nr.value=ipnr;
            d.time_chose.value=time;
            d.submit();
        }
        
    }

</script>


<?php
    require($root_path.'include/core/inc_js_gethelp.php');
    require($root_path.'include/core/inc_css_a_hilitebu.php');
?>

</HEAD>
<BODY   bgcolor="#cde1ec" TEXT="#000000" LINK="#0000FF" VLINK="#800080"  topmargin=2 marginheight=2 
onLoad="<?php if($mode=='saveok') echo 'window.focus();'; ?>if (window.focus) window.focus();
window.focus();document.infoform.inputdata.focus();" >
    
<font face=verdana,arial size=5 color=maroon>
<b>
    <?php
        echo $title;
    ?>
</b>
</font>
<div align="right">
<a href="javascript:gethelp('oplog.php','person','<?php echo $winid ?>')"><img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?>>
</a>
</div>
<?php if($quickexist) : ?>
<form name="quickselect" action="<?php echo $thisfile ?>" method="post">

    <table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0 >
        <tr>
            <td>
                <table border=0 width=100% cellspacing=1>
                    <tr>
                        <td bgcolor="#cfcfcf" class="v13_n" colspan=6>&nbsp;<font color="#ff0000"><b><?php echo $LDQuickSelectList ?>:</b></td>
                    </tr>
                    <tr>
                        <td align=center bgcolor="#ffffff" class="v13_n">
                            <?php echo $LDNameFull ?>
                        </td>
                        <td align=center bgcolor="#ffffff" class="v13_n" colspan="2">
                            <?php echo $LDJobId ?>
                        </td>
                        <td align=center bgcolor="#ffffff"   class="v13_n" >
                            <?php echo "$LDFunction $LDIn $LDOrEkip1" ?>
                        </td>
<!--                        <td align=center bgcolor="#ffffff"   class="v13_n">
                            <?php echo $LDDutyPlan ?>
                        </td>-->
                        <td align=center bgcolor="#ffffff"   class="v13_n">
                            <?php echo $LDWorkDay ?>
                        </td>
                    </tr>
                    <?php
                        $counter=0;
                        require_once($root_path.'include/core/inc_date_format_functions.php');                        
                        while($qlist=$quicklist->FetchRow())
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
                            $check=$enc_op_obj->getStatus($qlist[personell_nr],$op_date,'1','',$info['nr']);
                            if($check[doc_time]){
                                $time_chose=$check[doc_time];                                
                                $check_status=$enc_op_obj->getStatus($qlist[personell_nr],'','','',$info['nr']);
                                $status=$check_status[status];
                                echo '
                                    &nbsp;<a href="javascript:savedata_bytime(\''.$qlist[name_last].'\',\''.$qlist[name_first].'\',\''.$counter.'\',\''.$qlist[jof_function_title].'\',\''.$qlist[personell_nr].'\',\''.$op_time.'\',\''.$time_chose.'\',\''.$status.'\')" title="'.str_replace("~tagword~",$title,$LDUseData).'">'.$qlist[name_last].' '.$qlist[name_first].'</a>';
                            }else{
                                $date=$encounter_op->FetchRow();
                                echo '
                                    &nbsp;<a href="javascript:savedata(\''.$qlist[name_last].'\',\''.$qlist[name_first].'\',\''.$counter.'\',\''.$qlist[jof_function_title].'\',\'\',\''.$qlist[personell_nr].'\',\''.$op_time.'\')" title="'.str_replace("~tagword~",$title,$LDUseData).'">'.$qlist[name_last].' '.$qlist[name_first].'</a>';
                            }
                            echo '</td> ';
                            echo '
                            <td class="v13" colspan="2">
                            &nbsp;'.$qlist['job_function_title'].'
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
                            &nbsp;<a href="javascript:savedata(\''.$qlist[name_last].'\',\''.$qlist[name_first].'\',\''.$counter.'\',\''.$qlist[jof_function_title].'\',\''.$status.'\',\''.$qlist[personell_nr].'\',\''.$date_chose.'\',\''.$time_chose.'\')"><img '.createComIcon($root_path,'dwnarrowgrnlrg.gif','0').' align=absmiddle>
                            '.str_replace("~tagword~",$title,$LDUseData).'..</a>
                            </td>';
//                            $day=explode("-",$op_date);
//                            for($i=0;$i<$maxdays;$i++){
//                                if(($qlist[personell_nr] == $a_pnr['ha'.$i] )|| ($qlist[personell_nr] == $r_pnr['hr'.$i] )){
//                                    echo '<td>'.$LDYes.'</td>';
//                                }else{
//                                    echo '<td>'.$LDNo.'</td>';
//                                }
//                                break;
//                            }
                            echo '<td align=center>'.$qlist[nr_workday];
                            echo '</td></tr>';
                            $counter++;
                            
                        }
                    ?>
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
    <input type="hidden" name="time_chose" value=""/>
</form>

 <form name="infoform" action="<?php echo 'op-pflege-log-getpersonell_1.php?sid='.$sid.'&lang='.$lang ?>" method="post" onSubmit="return pruf(this)">			
    <table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0 >
        <tr>
            <td>
                <table border=0 width=100% cellspacing=1 cellpadding=0>
                    <tr>
                        <td  bgcolor="#cfcfcf" class="v13" colspan=6>&nbsp;<b><?php echo $LDCurrentEntries ?>:</b></td>
                    </tr>
                    <tr  class="v13_n">
                        <td align=center bgcolor="#ffffff">
                        </td>
                        <td align=center bgcolor="#ffffff">
                            <?php echo "$LDNameFull" ?>
                        </td>
                        <td align=center bgcolor="#ffffff" colspan="4">
                            <?php echo $LDFunction ?>
                        </td>
                    </tr>
                    
                    <?php
                        if($personell)
                        {
                            $entrycount=sizeof($personell);
                            for($i=0;$i<$entrycount;$i++)
                            {
                                if(trim($personell[$i],'\x')=="") continue;
                                $nr=trim($nr_personell[$i],'\x');
                                echo '
                                <tr bgcolor="#ffffff">
                                <td   class="v13" >
                                &nbsp;<a href="javascript:delete_item(\''.$i.'\',\''.$nr.'\')"><img '.createComIcon($root_path,'delete2.gif','0').' alt="'.$LDDeleteEntry.'"></a>
                                </td>
                                <td   class="v13" >
                                &nbsp;'.trim($personell[$i],'\x').'
                                </td>
                                <td class="v13" colspan="4">
                                &nbsp;'.$title.'
                                </td>
                                </tr>';
                            }
                        }
                    ?>
                    <tr>
                        <td  class="v12"  bgcolor="#cfcfcf" colspan=6>&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td  class="v12"  bgcolor="#ffffff" colspan=4 align=center>
                            <div>
                            <font size=3><b><?php echo str_replace("~tagword~",$title,$LDSearchNewPerson.':</b></font><br/>('.$LDPromptSearch.')') ?>
                            <br>
                            <input type="text" name="inputdata" size=25 maxlength=30 />
                            <br>
                            <input type="submit" value="OK" />
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
    <input type="hidden" name="pyear" value="<?php echo $pyear ?>"/>
    <input type="hidden" name="pmonth" value="<?php echo $pmonth ?>"/>
    <input type="hidden" name="pday" value="<?php echo $pday ?>"/>
    <input type="hidden" name="dept_nr" value="<?php echo $dept_nr ?>"/>
    <input type="hidden" name="saal" value="<?php echo $saal ?>"/>
    <input type="hidden" name="op_nr" value="<?php echo $op_nr ?>"/>
    <input type="hidden" name="batch_nr" value="<?php echo $batch_nr ?>"/>
    <input type="hidden" name="entrycount" value="<?php if(!$entrycount) echo "1"; else echo $entrycount; ?>"/>
    <input type="hidden" name="mode" value="save"/>
    <input type="hidden" name="delitem" value=""/>
    <input type="hidden" name="status" value="chose"/>
    <input type="hidden" name="personell_nr" value=""/>
</form>

<?php endif ?>

</BODY>

</HTML>
