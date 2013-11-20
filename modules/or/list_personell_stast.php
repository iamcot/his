<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    $lang_tables[] = 'or.php';
    $lang_tables[] = 'aufnahme.php';
    
    /*require_once($root_path.'include/care_api_classes/class_access.php');
    $access= & new Access();
    if($_SESSION['sess_login_username']!='admin'){
        $role= $access->checkNameRole($_SESSION['sess_login_username']);
        if(strpos($role['dept_nr'], '"'.$dept_nr.'"')==0 && $role['location_nr']!=$dept_nr){
            header("Location:../../language/".$lang."/lang_".$lang."_invalid-access-warning.php"); 
            exit;
        }
    }*/
    
    $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
    $thisfile=basename(__FILE__);
    define('NO_2LEVEL_CHK',1);
    //$local_user='ck_op_dienstplan_user';
	//if ($local_user='ck_op_dienstplan_user') define('NO_2LEVEL_CHK',1);

    require_once($root_path.'include/core/inc_front_chain_lang.php');
    
    require_once($root_path.'global_conf/inc_global_address.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    
    require_once ($root_path . 'include/care_api_classes/class_encounter_op.php');
    $enc_op_obj = new OPEncounter ( );
    $list1=$enc_op_obj->get_personell_op("","");
    $row=$list1->RecordCount();
    //số row muốn hiển thị
    $sonews=4;
    //số trang muốn hiển thị
    $pagination=ceil($row/$sonews);
    if(!isset($p)){
        $p = 1 ;
    }
    $x = ($p-1) * $sonews;
    $list=$enc_op_obj->get_personell_op($x,$sonews);
    
    if($mode!='paginate'){
        # Reset paginator variables
        $pgx=0;
        $totalcount=0;
        $odir='';
        $oitem='';
    }
    require_once($root_path.'include/care_api_classes/class_paginator.php');
    $pagen=new Paginator($pgx,$thisfile,$_SESSION['sess_searchkey'],$root_path);

    $GLOBAL_CONFIG=array();

    # Get the max nr of rows from global config
    require_once($root_path.'include/care_api_classes/class_globalconfig.php');
    $glob_obj=new GlobalConfig($GLOBAL_CONFIG);
    $glob_obj->getConfig('pagin_personell_list_max_block_rows');
    if(empty($GLOBAL_CONFIG['pagin_personell_list_max_block_rows'])) $pagen->setMaxCount(MAX_BLOCK_ROWS); # Last resort, use the default defined at the start of this page
            else $pagen->setMaxCount($GLOBAL_CONFIG['pagin_personell_list_max_block_rows']);

    if(empty($odir)) $odir='ASC'; # default, ascending alphabetic
    # Set the sort parameters
    $pagen->setSortItem($oitem);
    $pagen->setSortDirection($odir);
    $toggle=0;
    $sql1="SELECT DISTINCT(pno.personell_nr) AS personell_nr
            FROM care_personell_op AS pno
            LEFT JOIN care_encounter_op AS tb ON tb.nr=pno.encounter_op_nr
            LEFT JOIN care_test_request_or AS yc ON yc.batch_nr=tb.batch_nr
            LEFT JOIN care_personell AS pn ON pn.nr=pno.personell_nr 
            LEFT JOIN care_person AS ps ON ps.pid=pn.pid
            WHERE pno.status='chosed'";
    $sql3=" ORDER BY pno.personell_nr $odir";
    if($ergebnis=$db->SelectLimit($sql1.$sql3,$pagen->MaxCount(),$pagen->BlockStartIndex())){
	if ($linecount=$ergebnis->RecordCount()){
            if(($linecount==1) && $numeric){
                $zeile=$ergebnis->FetchRow();
                header('location:'.$thisfile.'?ntid='.$ntid.'&lang='.$lang.'&pmonth='.$pmonth.'&pyear='.$pyear);
                exit;
            }
	}
	$pagen->setTotalBlockCount($linecount);
					
	# If more than one count all available
	if(isset($totalcount) && $totalcount){
            $pagen->setTotalDataCount($totalcount);
	}else{
            # Count total available data
//            $sql="SELECT DISTINCT(pno.personell_nr) AS personell_nr $sql2";
            $sql=$sql1.$sql3;
            if($result=$db->Execute($sql)){
                if ($rescount=$result->RecordCount()) {
                    $totalcount=$rescount;
                }
            }
            $pagen->setTotalDataCount($totalcount);
	}
    }else{
            echo "<p>$sql<p>$LDDbNoRead";
    }
    
    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('system_admin');
    $smarty->assign('sToolbarTitle', "$LDPersonellStast1");
    $smarty->assign('pbBack',FALSE);
    $smarty->assign('breakfile',$breakfile);
    $smarty->assign('sWindowTitle',"$LDPersonellStast1");
    ob_start();
?>
<style type="text/css">
    div.a {
        width: 100%;
        margin: auto;
        padding: 5;
    }
    div.b {
        float: left;
        width:  20%;
        padding: 2px;
        text-align: right;
    }
    div.c {
        float: left;
        width:  70%;
        padding: 2px;
    }
    .clr{clear: both;}
</style>
<script language="javascript">
    function printOut()
    {
        urlholder="<?php echo $root_path ?>modules/pdfmaker/phieuphauthuat/chamcongcamo.php<?php echo URL_APPEND ?>&ses_en<?php echo $_SESSION['sess_full_en'] ?>&pmonth=<?php echo $pmonth ?>&pyear=<?php echo $pyear ?>";
        testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
    function cal_update()
    {
        var filename="<?php echo $thisfile.'?ntid='.$ntid.'&lang='.$lang ?>&pmonth="+document.change.month.value+"&pyear="+document.change.year.value;
        window.location.replace(filename);
    }
</script>
<?php
    $sTemp = ob_get_contents();

    ob_end_clean();

    $smarty->append('JavaScript',$sTemp);

    ob_start();
?>
<ul>
    <?php
	if ($linecount) {
    ?>
    <form name="change" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)"> 
        <font size=4>
            <?php
                echo $LDMonth;
                echo '<select name="month" onChange="cal_update()">';
                for($i=1;$i<13;$i++){
                    echo '<option value="'.$i.'"';
                    if($pmonth==$i) echo 'selected >';
                    else echo '>';
                    echo $monat[$i];
                    echo '</option>';
                }
                echo '</select>';
                echo $LDYear;
                echo '<select name="year" onChange="cal_update()">';
                for($i=2001;$i<2112;$i++){
                    echo '<option value="'.$i.'"';
                    if($pyear==$i) echo 'selected >';
                    else echo '>';
                    echo $i;
                    echo '</option>';
                }
                echo '</select>';
                $maxdays=date("t",mktime(0,0,0,$pmonth,1,$pyear));
            ?>
        </font>
        <!--<img <?php echo createLDImgSrc($root_path,'printout.gif','0','center')?> onClick="printOut()" />-->
        <table width="100%" class="submenu_frame">
            <tbody class="submenu">
                <tr class="wardlisttitlerow">
                    <td rowspan="3" width="15%" align="center">
                        <font size="2" color="darkblue">
                            <b>
                            <?php 
                                echo $LDNameFull;
                            ?>
                            </b>
                        </font>
                    </td>
                    <td rowspan="3" width="5%" align="center">
                        <font size="2" color="darkblue">
                            <b>
                            <?php 
                                echo $LDFunctionOp;
                            ?>
                            </b>
                        </font>
                    </td>
                    <td colspan="<?php echo $maxdays*2; ?>" width="65%" align="center">
                        <font size="2" color="darkblue">
                            <b><?php echo $LDDateIn; ?></b>
                        </font>
                    </td>
                    <td colspan="3" align="center">
                        <font size="2" color="darkblue">
                            <b><?php echo $LDSumStast; ?></b>
                        </font>
                    </td>
                </tr>
            <tr>
                <?php                                
                    for($i=1;$i<=$maxdays;$i++){
                        echo '<td bgcolor="#33CCCC" align="center" colspan="2">'.$i;
                        echo '</td>';
                    }
                ?>
                <td bgcolor="#5F9EA0" rowspan="2" align="center">
                    <font size="2" >
                        <b><?php echo $LDLevelOp2; ?></b>
                    </font>
                </td>
                <td bgcolor="#B0C4DE" rowspan="2" align="center">
                    <font size="2" >
                        <b><?php echo $LDLevelOp3; ?></b>
                    </font>
                </td>
            </tr>
            <tr>
                <?php                                
                    for($i=1;$i<=$maxdays;$i++){
                        echo '<td bgcolor="#5F9EA0" align="center">II</td>';
                        echo '<td bgcolor="#B0C4DE" align="center">III</td>';
                    }
                ?>
            </tr>
            <?php 
                $temp1=0;
                while($personell_nr=$ergebnis->FetchRow()){
                    //Lấy tất cả các ca mổ của người có mã nr này tham gia
                    $info=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"",$pmonth,$pyear);
                    while($personell=$info->FetchRow()){
                        $flag=$info->RecordCount();
                        if($flag<=1){
                            //Lấy thông tin cá nhân của người có mã nr này
                            $info_detail=$enc_op_obj->get_info($personell_nr['personell_nr']);
                            $info_personell=$info_detail->FetchRow();
                            //Lấy ngày mổ và loại ca mổ
                            echo '<tr>';
                            echo '<td bgcolor="white">'.$info_personell["name_last"].' '.$info_personell["name_first"].'</td>';
                            if($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật"){
                                echo '<td align="center" bgcolor="white">C</td>';
                            }elseif($info_personell["job_function_title"]=="Phụ Mổ"){
                                echo '<td align="center" bgcolor="white">P</td>';
                            }else{
                                echo '<td align="center" bgcolor="white">GV</td>';
                            }
                            for($i=1;$i<=$maxdays;$i++){
                                if($i==substr($personell['date_request'],8,2)){  
                                    if($personell["level_method"]=='II'){
                                        $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "II", $personell['date_request']);
                                        $number_level=$count_level_2->RecordCount();
                                        echo '<td bgcolor="#5F9EA0" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                    }else{
                                        $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "III", $personell['date_request']);
                                        $number_level=$count_level_2->RecordCount();
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                        echo '<td bgcolor="#B0C4DE" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';                                    
                                    }
                                }else{                                
                                    echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                    echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                }                            
                            }  
                            //Đếm tổng số ca loại 2 tham gia trong tháng
                            $list2=$enc_op_obj->list_doctor_op($personell["personell_nr"],"II","");
                            $level2=$list2->RecordCount();
                            //Đếm tổng số ca loại 2 tham gia trong tháng
                            $list3=$enc_op_obj->list_doctor_op($personell["personell_nr"],"III","");
                            $level3=$list3->RecordCount();
                            if($personell["level_method"]=='II'){
                                echo '<td bgcolor="#5F9EA0" align="center"><font size="2" >'.$level2.'</td>';
                            }else{
                                echo '<td bgcolor="white"></td>';
                            }
                            if($personell["level_method"]=='III'){
                                echo '<td bgcolor="#B0C4DE" align="center"><font size="2">'.$level3.'</td>';
                            }else{
                                echo '<td bgcolor="white"></td>';
                            }
                            echo '</tr>';
                        }else{
                            $info_detail=$enc_op_obj->get_info($personell_nr['personell_nr']);
                            $info_personell=$info_detail->FetchRow();
                            $personell=$info->FetchRow();
                            echo '<tr>';
                            echo '<td bgcolor="white">'.$info_personell["name_last"].' '.$info_personell["name_first"].'</td>';
                            if($info_personell["job_function_title"]=="Bác sĩ Phẫu Thuật"){
                                echo '<td align="center" bgcolor="white">C</td>';
                            }elseif($info_personell["job_function_title"]=="Phụ Mổ"){
                                echo '<td align="center" bgcolor="white">P</td>';
                            }else{
                                echo '<td align="center" bgcolor="white">GV</td>';
                            }
                            $len=1;
                            //Gọi lại hàm lấy thông tin các ca mổ của người này
                            $info=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"",$pmonth,$pyear);
                            $array_date=array();
                            $array_level=array();
                            while($date_check=$info->FetchRow()){
                                $array_date[$len]=$date_check['date_request'];                                
                                $array_level[$len]=$date_check['level_method'];
                                $len++;
                            }                            
                            $count_level_month=$enc_op_obj->list_doctor_op_flag($personell_nr['personell_nr'],"",$pmonth,$pyear);
                            for($i=1;$i<=$maxdays;$i++){
                                $temp=$len;
                                for($t=1;$t<=($len-1);$t++){
                                    $temp--;
                                    if($i==substr($array_date[$t],8,2)){
                                        if($array_level[$t]=='II'){
                                            $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "II", $array_date[$t]);
                                            $number_level=$count_level_2->RecordCount();
                                            echo '<td bgcolor="#5F9EA0" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';
                                            echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                            break;
                                        }else{
                                            $count_level_2=$enc_op_obj->list_doctor_op($personell['personell_nr'], "III", $array_date[$t]);
                                            $number_level=$count_level_2->RecordCount();
                                            echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                            echo '<td bgcolor="#B0C4DE" width="'.round($maxdays/2).'" align="center">'.$number_level.'</td>';
                                            break;
                                        }
                                    }elseif($temp==1){
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                        echo '<td bgcolor="white" width="'.round($maxdays/2).'"></td>';
                                    }
                                }                                
                            }
                            $temp=$len-1;
                            for($i=1;$i<($len-1);$i++){
                                $temp--;
                                $list2=$enc_op_obj->list_doctor_op_flag($personell["personell_nr"],"II",$pmonth,$pyear);
                                $level2=$list2->FetchRow();
                                $list3=$enc_op_obj->list_doctor_op_flag($personell["personell_nr"],"III",$pmonth,$pyear);
                                $level3=$list3->FetchRow();
                                if($temp==1){
                                    if($level2['level_method']!=0 ){
                                        echo '<td bgcolor="#5F9EA0" align="center"><font size="2" >'.$level2['level_method'].'</td>';                                    
                                    }else{
                                        echo '<td></td>';
                                        $temp=1;
                                    }
                                    if($level3['level_method']!=0){
                                        echo '<td bgcolor="#B0C4DE" align="center"><font size="2">'.$level3['level_method'].'</td>';
                                    }else{
                                        echo '<td></td>';
                                        $temp=1;
                                    }
                                }
                            }
                            echo '</tr>';
                        }
                        if($temp1==0){
                            echo '<div class="a">';
                            echo '<div>';
                            if ($linecount) echo str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
                            else echo str_replace('~nr~','0',$LDSearchFound);
                            echo '</div>';
                            $var='&pmonth='.$pmonth.'&pyear='.$pyear;
                            echo '<div class="c">'.$pagen->makePrevLink($LDPrevious,$var).'</div>';
                            echo '<div class="b">'.$pagen->makeNextLink($LDNext,$var).'</div>';
                            echo '<div class="clr"></div>';
                            echo '</div>';
                            $temp1=1;
                        }
                    }
                }
            ?>
            </tbody>
        </table>
    </form>
    <?php
        }else{
            echo '<h2><font color="darkred">'.$LDNoRecordYet.'</font></h2>';
        }
    ?>
</ul>

<?php

    $sTemp2 = ob_get_contents();
    ob_end_clean();
    $smarty->assign('sMainFrameBlockData',$sTemp2);
    $smarty->display('common/mainframe.tpl');

?>
