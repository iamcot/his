<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    define('LANG_FILE','or.php');

    $local_user='ck_opdoku_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    /* Create the personell object */

    require_once($root_path.'include/care_api_classes/class_med_dept.php');
    $med=new Med_Dept;
    
    //Ghi log
    require_once($root_path.'include/core/access_log.php');
    $logs = new AccessLog();
    
    $title=$LDMedDept;
    $pyear=date(Y);
    $pday=date(d);
    if($flag=='close'){
        echo '<script language="javascript">window.close();</script>';
    }   
    $thisfile=basename(__FILE__);
    if($keyword==1){
        header("Location:op-pflege-log-getmed.php?sid=$sid&lang=$lang&winid=$winid&keyword=$inputdata&batch_nr=$batch_nr&dept_nr=$dept_nr&ward_nr=$ward_nr");
        exit();
    }
    switch($winid)
    {
        case 'med':
            $element='medical_codedlist';
            //$maxelement=10;
            $quickid='med';
            //Lấy thuốc trong khoa (mã thuốc)
            $med_info=$med->getAllMedInDept($dept_nr,$ward_nr,ASC)." LIMIT 5";
            $quicklist=$db->Execute($med_info);
            break;
        default:{header('Location:'.$root_path.'language/'.$lang.'/lang_'.$lang.'_invalid-access-warning.php'); exit;};
    }
    if(!isset($db)||!$db) include($root_path.'include/core/inc_db_makelink.php');
    if($dblink_ok || $dblink_ok==1){
        // get data if exists
        $sql="SELECT $element,encoding FROM care_encounter_op
                 WHERE batch_nr='$batch_nr'";
        if($ergebnis=$db->Execute($sql)){
            $rows=$ergebnis->Recordcount();
            if(isset($rows)){
                $result=$ergebnis->FetchRow();
                $fileexist=1;
            }
        }else{
            echo "$LDDbNoRead<br>";
        }

    if($mode=='save')
    {
        if($fileexist)
        {
            $prescription_id=$med->getInfoMedPrescription($batch_nr,$dept_nr,$ward_nr);
            $result[encoding].="~e=".$encoder."&d=".date("d.m.Y")."&t=".date("H.i")."&a=".$element."\n"; 
            $sql_tempt="SELECT * FROM care_test_request_or WHERE batch_nr='".$batch_nr."'";
            if($temp=$db->Execute($sql_tempt)){
                $temp_1=$temp->FetchRow();
            }
            if($use=='&' || $use==''){
                echo '<p align="center"><font size="3" color="red"><b>'.$LDWarningNumberMed.'<br>'.$LDDbNoSave.'</b></font></p>';
            }else{
                if($delitem!="")
                {
                    $elem=explode("~",$result[$element]);   
                    $location=explode("#",$elem[$delitem]);
                    if((sizeof($location)-1)<=1){
                        $number_use=explode("+",$location[1]);    
                        $edit_number=$med->UneditNumberMedInDept($ln,$use,$prescription_id['prescription_id'],$prescription_id['encounter_nr'],$dept_nr,$ward_nr,$number_use[0],$number_use[1],$price);  
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $med->getLastQuery(), date('Y-m-d H:i:s'));                    
                        $sql="UPDATE care_med_prescription_info SET total_cost='".($prescription_id[total_cost]-($use*$price))."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$prescription_id['encounter_nr']." AND date_time_create='".$temp_1[date_request]."'";
                        if(!$ergebnis=$db->Execute($sql)){
                            echo $sql."$LDDbNoSave<br>";
                        }
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));                    
                    }else{
                        $i=1;
                        while($i<sizeof($location)){
                            $number_use=explode("+",$location[$i]);
                            $edit_number=$med->UneditNumberMedInDept($ln,$use,$prescription_id['prescription_id'],$prescription_id['encounter_nr'],$dept_nr,$ward_nr,$number_use[0],$number_use[1],$price);
                            //insert log
                            $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $med->getLastQuery(), date('Y-m-d H:i:s')); 
                            $sql="UPDATE care_med_prescription_info SET total_cost='".($prescription_id[total_cost]-($use*$price))."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$prescription_id['encounter_nr']." AND date_time_create='".$temp_1[date_request]."'";
                            if(!$ergebnis=$db->Execute($sql)){
                                echo $sql."$LDDbNoSave<br>";
                            }
                            //insert log
                            $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));  
                            $i++;
                        }
                    }                                                      
                    array_splice($elem,$delitem,1);
                    sort($elem,SORT_REGULAR);
                    $result[$element]=implode("~",$elem);
                }else{
                    $dbuf="n=".$ln."&u=".$use."&x=".$nx;
                    $result[$element]=$result[$element]."~".$dbuf;
                    $edit_number=$med->editNumberMedInDept($ln,$use,$prescription_id['prescription_id'],$prescription_id['encounter_nr'],$dept_nr,$ward_nr,$price);
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $med->getLastQuery(), date('Y-m-d H:i:s'));                     
                    $t=0;
                    while($t<sizeof($edit_number)){
                        $cost=explode("-",$edit_number[$t]);
                        $result[$element].='#'.$cost[0];      
                        $sql="UPDATE care_med_prescription_info SET total_cost='".($prescription_id[total_cost]+$cost[1])."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$prescription_id['encounter_nr']." AND date_time_create='".$temp_1[date_request]."'";
                        if(!$ergebnis=$db->Execute($sql)){
                            echo $sql."$LDDbNoSave<br>";
                        }
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));  
                        $t++;
                    }
                }
                $user=$_SESSION [sess_user_name];
                //chưa xử lý xong
                $sql="UPDATE care_encounter_op SET $element='".$result[$element]."',encoding='$result[encoding]', modify_id='$user'
                                WHERE batch_nr='$batch_nr'";
                if($ergebnis=$db->Execute($sql))
                {
                    $saveok=1;
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s')); 
                }else { echo "$LDDbNoSave<br>"; }
            }
            
        } // else create new entry
        else{
        }//end of else
        if($saveok)
        {         
            header("location:$thisfile?sid=$sid&lang=$lang&mode=saveok&winid=$winid&batch_nr=$batch_nr&dept_nr=$dept_nr&ward_nr=$ward_nr");
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

<?php
    require($root_path.'include/core/inc_js_gethelp.php');
    //định dạng css cho toàn bộ khung
    require($root_path.'include/core/inc_css_a_hilitebu.php');
?>

</HEAD>
<BODY   bgcolor="#cde1ec" TEXT="#000000" LINK="#0000FF" VLINK="#800080"  topmargin=2 marginheight=2 
onLoad="<?php if($mode=='saveok') echo 'window.focus();'; ?>" >
    
<font face='verdana,arial' size='5' color='maroon'>
    <b>
        <?php
            echo $title;
        ?>
    </b>
</font>
<!--<div align="right">
    <a href="javascript:gethelp('oplog.php','person','<?php echo $winid ?>')">
        <img <?php echo createLDImgSrc($root_path,'hilfe-r.gif','0') ?>/>
    </a>
    <?php
        if(!$flag || $flag==''){
    ?>
        <a href="javascript:window.location.close();">        
            <img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>/>
        </a>
    <?php
        }else{
    ?>
        <a href="<?php echo $root_path.'modules/or_logbook/op-pflege-log-getinfo_med.php'.URL_REDIRECT_APPEND.'&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal='.$room_now.'&winid=medpharma&flag=close'?>">        
            <img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>/>
        </a>
    <?php
        }
    ?>    
</div>-->
<form name="quickselect" action="<?php echo $thisfile ?>" method="post">
    <table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0 >
        <tr>
            <td></td>
            <td>
                <table border=0 width=100% cellspacing=1>
                    <tr>
                        <td bgcolor="#cfcfcf" class="v13_n" colspan=7>&nbsp;
                            <font color="#ff0000">
                            <b>
                                <?php echo $LDQuickSelectList ?>:
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td align=center bgcolor="#ffffff" class="v13_n">
                            <?php echo $LDGroupMed; ?>
                        </td>
                        <td align="center" bgcolor="#ffffff" class="v13_n">
                            <?php echo $LDNameMed ?>
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
                        <td align=center bgcolor="#ffffff"   class="v13_n" >
                        </td>
                    </tr>
                    <?php
                        $counter=0;
                        while($qlist=$quicklist->FetchRow())
                        {
                            $issue_paper=$med->getMedInfo($qlist['product_encoder'],$dept_nr,$ward_nr);
                            $detail=$med->getMedInfoDetail($qlist['product_encoder'],$dept_nr,$ward_nr);
                            echo '
                                <tr bgcolor="#ffffff">
                                <td class="v13" width=10%>'.$detail['name_sub'].'
                                </td>
                                <td class="v13" width=20%>'.$issue_paper['product_name'].'
                                </td>';
                            echo "                                
                                <td class='v13' width='25%'>".
                                    $detail['effects']."
                                </td>
                                <td class='v13' width='25%'>".
                                    $detail['caution']."
                                </td>";
                            $unit=$med->getunitMed($qlist['product_encoder']);
                            $numbernow=$med->getnumberMed($qlist['product_encoder'],$dept_nr,$ward_nr);
                            echo '<td class="v13" align="center">'.$numbernow['number'].'&nbsp;&nbsp;'.$unit['unit_name_of_medicine'].'</td>';
                            //Số lượng cần dùng chỉ cho phép < Số lượng hiện có
                            echo '<td class="v13" align="center">
                                <table width=100%></tr><td width=80%>
                                <select name="number_use" onChange="alertselected(this)"><option value="nothing">Chọn số lượng</option>';
                            if($numbernow['number']>0){
                                for($i=1;$i<=$numbernow['number'];$i++){                                    
                                    echo'<option value="'.$i.'"';
                                    echo '>'.$i.'</option>';
                                }
                            }
                            echo '</select></td><td></td>                                
                                </tr>
                                </table>
                                </td>';
                            echo '<td class="v13" align="center">
                                    <a href="javascript:savedata(\''.$qlist['product_encoder'].'\',\''.$numbernow['number'].'\',\''.$count.'\',\''.$detail['price'].'\')">
                                        <img '.createLDImgSrc($root_path,'ok_small.gif','0').'/>
                                    </a>                                    
                                 ';
                            echo '</td></tr>';
                            $counter++;
                        }
                    ?>
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
    <input type="hidden" name="nx" value=""/>
    <input type="hidden" name="use" value=""/>
    <input type="hidden" name="price" value=""/>
</form>
<form name="infoform" action="<?php echo 'op-pflege-log-getmed.php?sid='.$sid.'&lang='.$lang.'&winid='.$winid.'&inputdata='.$inputdata.'&dept_nr='.$dept_nr.'$ward_nr'.$ward_nr;?>" method="post" onSubmit="return pruf(this)">
    <table border=0 width=100% bgcolor="#6f6f6f" cellspacing=0 cellpadding=0 >
        <tr>
            <td>
                <table border=0 width=100% cellspacing=1 cellpadding=0>
                    <tr>
                        <td  bgcolor="#cfcfcf" class="v13" colspan=6>&nbsp;<b><?php echo $LDCurrentEntries ?>:</b></td>
                    </tr>
                    <tr>
                        <td align=center bgcolor="#ffffff" width="5%">
                        </td>
                        <td align=center bgcolor="#ffffff" class="v13_n">
                            <?php echo $LDGroupMed; ?>
                        </td>
                        <td align=center bgcolor="#ffffff" class="v13_n">
                            <?php echo $LDNameMed; ?>
                        </td>
                        <td align=center bgcolor="#ffffff" class="v13_n">
                            <?php echo $LDNumberUse; ?>
                        </td>
                    </tr>
                    <?php
                        if($result[$element]!="")
                        {    
                            $dbuf=explode("~",trim($result[$element]));
                            $nbuf=explode("u=",trim($result[$element]));
                            $entrycount=sizeof($dbuf);
                            $elems=array();
                            for($i=0;$i<$entrycount;$i++)
                            {
                                if(trim($dbuf[$i])=="") continue;
                                parse_str(trim($dbuf[$i]),$elems);
                                $number=explode("&x=",$nbuf[$i]);
                                $use=explode("+",$nbuf[$i]);
//                                $check_used=$med->getnumberMed($elems[n],$dept_nr,$ward_nr);
                                $issue_paper=$med->getMedInfo($elems[n],$dept_nr,$ward_nr);
                                $detail=$med->getMedInfoDetail($elems[n],$dept_nr,$ward_nr);
                                $name=$issue_paper["product_name"];
//                                $number_use=$check_used['number_used'];
                                echo '
                                <tr bgcolor="#ffffff">
                                <td class="v13" >
                                &nbsp;<a href="javascript:delete_item(\''.$i.'\',\''.$use[1].'\',\''.$elems[n].'\',\''.$detail['price'].'\')"><img '.createComIcon($root_path,'delete2.gif','0').' alt="'.$LDDeleteEntry.'"></a>
                                </td>
                                <td class="v13">
                                &nbsp;'.$detail["name_sub"].'
                                </td>
                                <td class="v13" >
                                &nbsp;'.$issue_paper["product_name"].'
                                </td>
                                <td class="v13" colspan="4" align="center">
                                &nbsp;'.$number[0].'
                                </td>
                                </tr>';
                            }
                        }
                    ?>
                </table>
            </td>
        </tr>
        <tr>
                        <td  class="v12"  bgcolor="#cfcfcf" colspan=6>&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td  class="v12"  bgcolor="#ffffff" colspan=4 align=center>
                            <div>
                                <font size=3>
                                    <b><?php echo str_replace("~tagword~",$title,$LDSearchNewPerson.":</b></font><br/>(".$LDNoteSearch1.")") ?>
                                <br/>
                                <input type="text" name="inputdata" size=25 maxlength=30>
                                <br>
                                <input type="submit" value="OK">
                            </div>
                        </td>

                    </tr>
    </table>
    <input type="hidden" name="encoder" value="<?php echo $_COOKIE[$sid]; ?>"/>
    <input type="hidden" name="sid" value="<?php echo $sid ?>"/>
    <input type="hidden" name="lang" value="<?php echo $lang ?>"/>
    <input type="hidden" name="winid" value="<?php echo $winid ?>"/>
    <input type="hidden" name="dept_nr" value="<?php echo $dept_nr ?>"/>
	<input type="hidden" name="ward_nr" value="<?php echo $ward_nr ?>"/>
    <input type="hidden" name="batch_nr" value="<?php echo $batch_nr ?>">
    <input type="hidden" name="mode" value="save"/>
    <input type="hidden" name="ln" value=""/>
    <input type="hidden" name="delitem" value=""/>
    <input type="hidden" name="use" value=""/>
    <input type="hidden" name="price" value=""/>
</form>
<script language="javascript">
    function pruf(d){
        if(!d.inputdata.value) 
            return false;
        else return true;
    }
    function delete_item(i,use,ln,price)
    {
        d=document.infoform;
        d.action="<?php echo $thisfile.'?sid='.$sid.'&lang='.$lang.'&winid='.$winid.'&dept_nr='.$dept_nr.'$ward_nr'.$ward_nr; ?>";
        d.delitem.value=i;
        d.ln.value=ln;
        d.use.value=use;
        d.price.value=price;
        d.submit();
    }
    //truyền tên thuốc và số lượng hiện có trong khoa
    function savedata(iln,inr,nx,price)
    {
        if(inr>0){            
            d=document.quickselect;
            d.ln.value=iln;
            d.nx.value=nx;
            d.price.value=price;
            d.submit();            
        }else alert("Thuốc này đã hết");
    }
    //hàm truyền số lượng muốn dùng đã được chọn
    function alertselected(selectobj){        
        var number_use=selectobj.selectedIndex;
        d=document.quickselect;
        d.use.value=number_use;
    }
</script>
</BODY>

</HTML>
