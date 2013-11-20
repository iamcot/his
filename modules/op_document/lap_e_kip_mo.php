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
    $lang_tables[]='departments.php';
    $lang_tables[] = 'actions.php';
    define('LANG_FILE','or.php');
    $local_user='ck_op_pflegelogbuch_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');
//    require_once($root_path.'include/care_api_classes/class_access.php');
//    $access= & new Access();
//    $role= $access->checkNameRole($_SESSION['sess_user_name']);
//    if((strpos($role['role_name'], 'Trưởng khoa')!='' && strpos($role['role_name'], 'Trưởng khoa')==0) && $_SESSION['sess_login_username']!='admin'){
//        header("Location:../../language/".$lang."/lang_".$lang."_invalid-access-warning.php"); 
//        exit;
//    }
    require_once($root_path.'include/core/inc_date_format_functions.php');
    $thisfile= basename(__FILE__);
    # Load date shifter class
    require_once($root_path.'classes/datetimemanager/class.dateTimeManager.php');
	
    include_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj=new Encounter();

    require_once($root_path.'include/care_api_classes/class_oproom.php');
    $OR_obj=new OPRoom();	
	
    require_once($root_path.'include/care_api_classes/class_encounter_op.php');
    $enc_op_obj=new OPEncounter();
	
    require_once($root_path.'include/care_api_classes/class_pharma_dept.php');
    $pharma=new Pharma_Dept();
	
    require_once($root_path.'include/care_api_classes/class_med_dept.php');
    $med=new Med_Dept();
    
    //Ghi log
    require_once($root_path.'include/core/access_log.php');
    $logs = new AccessLog();
	
    $room_now='';
	
	$sql_tempt="SELECT * FROM care_test_request_or WHERE batch_nr='".$batch_nr."'";
	if($temp=$db->Execute($sql_tempt)){
		if($editable_rows=$temp->RecordCount()){
			$temp_1=$temp->FetchRow();
			$diagnosis=$temp_1['clinical_info'];
			$test_request=$temp_1['test_request'];
            $person_surgery=explode("-MNV:",$temp_1['person_surgery']);
		}
	}
    if($hour=='' || $gio!=''){
        $hour=$gio;
    }else{
        $hour='00';
    }    
    if($minute=='' || $phut!=''){
        $minute=$phut;
    }else{
        $minute='00';
    }
        
    if(isset($saal)){ 
        $time_save= convertTimeToStandard($hour.':'.$minute.':00');
        if($ergebnis1=$enc_op_obj->getInfo($batch_nr,'pending')){
            $rows=$ergebnis1->Recordcount();
            $check=$enc_op_obj->checkRoom($batch_nr, $saal, $date); 
            if($rows<1){ 
                if($check){
                    while($time=$check->FetchRow()){
                        $room['doc_time']=$time_save;
                        if((($room['doc_time']-$time['time'])<=2 && ($room['doc_time']-$time['time'])>0 ) || ($room['doc_time']-$time['time'])>=(-2) && ($room['doc_time']-$time['time']<0)){                            
                            echo '<script language="javascript">alert("'.$LDWarning2.'");</script>';
                            $flag=1;
                        }                        
                    }
                    if($flag!=1){
                        if($save=$enc_op_obj->InsertInfo($saal,$batch_nr,$time_save)){
                            //insert log
                            $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_op_obj->getLastQuery(), date('Y-m-d H:i:s'));
                            $save_now=1;
                            //chèn bác sĩ phẫu thuật được chọn khi yêu cầu mổ
                            $query=$enc_op_obj->getInfo($batch_nr);                        
                            $nr=$query->FetchRow();
                            $check_op=strpos($operator,",");
                            if($check_op){
                                $operator_save=$enc_op_obj->insertPersonell($operator,$nr['nr'],1);
                            }else{
                                $operator_save=$enc_op_obj->insertPersonell($person_surgery[1],$nr['nr']);
                            }
                            //insert log
                            $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_op_obj->getLastQuery(), date('Y-m-d H:i:s'));
                        }
                    }                    
                }else{       
                    if($save=$enc_op_obj->InsertInfo($saal,$batch_nr,$time_save)){
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_op_obj->getLastQuery(), date('Y-m-d H:i:s'));
                        $save_now=1;
                        //chèn bác sĩ phẫu thuật được chọn khi yêu cầu mổ
                        $query=$enc_op_obj->getInfo($batch_nr);                        
                        $nr=$query->FetchRow();
                        $check_op=strpos($operator,",");
                        if($check_op){
                            $operator_save=$enc_op_obj->insertPersonell($operator,$nr['nr'],1);
                        }else{
                            $operator_save=$enc_op_obj->insertPersonell($operator,$nr['nr']);
                        }    
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $enc_op_obj->getLastQuery(), date('Y-m-d H:i:s'));
                    }
                    $room=$ergebnis1->FetchRow();
                    $room_now=$room['op_room'];
                }                               
            }else{
                $room=$ergebnis1->FetchRow();
                $room_now=$room['op_room'];  
                if($saal!=$room_now){
                    while($time=$check->FetchRow()){
                        if(((($room['doc_time']-$time['time'])>2 && ($room['doc_time']-$time['time'])>0) || ($room['doc_time']-$time['time'])<=(-2)&& ($room['doc_time']-$time['time']<0)) && $room['doc_time']!=$time['time']){
                                $update=$enc_op_obj->updateRoom($saal,$batch_nr);
                                //insert log
                                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_op_obj->getLastQuery(), date('Y-m-d H:i:s')); 
                        }else{
                            echo '<script language="javascript">alert("'.$LDWarning2.'");</script>';
                            $saal=$room_now;
                        }
                    }
                } 
                $save=$enc_op_obj->updateInfo($batch_nr,$time_save);
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_op_obj->getLastQuery(), date('Y-m-d H:i:s'));
            }
        }       
    }
    else {
        # Load the date formatter
        $info=$enc_op_obj->getInfo($batch_nr,'pending');
        if($info){
           $info_de=$info->FetchRow(); 
           $saal=$info_de['op_room'];
        }
    }
    # Create new dateTimeManager object */
//    $tshifter = new dateTimeManager;
//    # Set default date to today
//    if(!isset($thisday)) $thisday=date('Y-m-d');
//    # Shift time back 1 day
//    $yesday = $tshifter->shift_dates($thisday, '1', 'd');
//    # Shift time forward 1 day
//    $tomorow = $tshifter->shift_dates($thisday, '-1', 'd');
//    # Todays date
//    $today=date('Y-m-d');

//    $toggler=0;
//
//    $pdata=array();
//    $template=array();

    # Default is op room #1
    //if(!isset($saal)||empty($saal)) $saal=1;
    # Set first entry flag
    setcookie(firstentry,'1');

    require_once($root_path.'include/care_api_classes/class_department.php');
    $dept_obj=new Department;
    # Preload the deparment info
    $dept_obj->preloadDept($dept_nr);
    # Get list of all the OR room numbers
    //Chỉ hiện những phòng chưa mổ
    $ORNrs=&$dept_obj->getAllActiveORNrs($dept_nr);

//    $surgery_arr=&$dept_obj->getAllActiveWithSurgery();

	
    switch ($mode){
        case 'edit':
            if($requests=$enc_op_obj->getInfoTest($batch_nr,'draff')){
                $batchrows=$requests->RecordCount();
                if($batchrows && (isset($batch_nr) || $batch_nr)){
                    $test_request=$requests->FetchRow();
                    /* Check for the patietn number = $pn. If available get the patients data */
                    $pn=$test_request['encounter_nr'];
                    //lấy thông tin co ban cua benh nhân
                    $test_request1=$enc_obj->getPidByEncnr($pn);
                    $pid=$test_request1['pid'];
                }
            }else{
                echo "<p>$sql<p>$LDDbNoRead";
                exit;
            }
            break;
        case 'done':
            $check=$enc_op_obj->getInfo($batch_nr);
            $check_exit=$check->FetchRow();
            $function=array(4,10);
            if($ergebnis=$enc_op_obj->getInfo($batch_nr)){
                $opdoc1=$ergebnis->FetchRow();
            }else echo "$sql<br>$LDDbNoRead";
            for($i=0;$i<3;$i++){
                $personell[$i]=$enc_op_obj->searchPersonell($opdoc1[nr],$function[$i],'chosed');
            }            
            if(sizeof($personell[0])<=0){                
                if($requests=$enc_op_obj->getInfoTest($batch_nr,'pending')){
                    $batchrows=$requests->RecordCount();
                    if($batchrows && (isset($batch_nr) || $batch_nr)){
                        $test_request=$requests->FetchRow();
                        /* Check for the patietn number = $pn. If available get the patients data */
                        $pn=$test_request['encounter_nr'];
                        //lấy thông tin cơ bản của bệnh nhân
                        $test_request1=$enc_obj->getPidByEncnr($pn);
                        $pid=$test_request1['pid'];
                    }
                }else{
                    echo "<p>$sql<p>$LDDbNoRead";
                }
                echo '<script language="javascript">alert("Một ê-kíp không thể thiếu '.$LDOpPersonElements['operator'].'")</script>';
            }elseif(sizeof($personell[1])<=0){
                if($requests=$enc_op_obj->getInfoTest($batch_nr,'pending')){
                    $batchrows=$requests->RecordCount();
                    if($batchrows && (isset($batch_nr) || $batch_nr)){
                        $test_request=$requests->FetchRow();
                        /* Check for the patietn number = $pn. If available get the patients data */
                        $pn=$test_request['encounter_nr'];
                        //lấy thông tin cơ bản của bệnh nhân
                        $test_request1=$enc_obj->getPidByEncnr($pn);
                        $pid=$test_request1['pid'];
                    }
                }else{
                    echo "<p>$sql<p>$LDDbNoRead";
                }
                echo '<script language="javascript">alert("Một ê-kíp không thể thiếu '.$LDOpPersonElements['scrub'].'")</script>';
            }elseif($check_exit!=''){             
                $update_request=$enc_op_obj->UpdateTestRequest($batch_nr);
                if ($update_request) {        
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile,$enc_op_obj->getLastQuery(), date('Y-m-d H:i:s'));
                    if($flag!=1){
                        echo '<a href="javascript:if(!window.parent.opener.closed)window.parent.opener.focus();window.parent.close();">';
                        echo '<h2>Bạn đã lập thành công ê-kíp mổ</h2>';
                        echo '</a>';
                        exit ();
                    }else{
                        echo '<script language="javascript">window.parent.close();';
                        echo '</script>';
                        exit();
                    }
                }
            }else{
                if($requests=$enc_op_obj->getInfoTest($batch_nr,'pending')){
                    $batchrows=$requests->RecordCount();
                    if($batchrows && (isset($batch_nr) || $batch_nr)){
                        $test_request=$requests->FetchRow();
                        /* Check for the patietn number = $pn. If available get the patients data */
                        $pn=$test_request['encounter_nr'];
                        //lấy thông tin cơ bản của bệnh nhân
                        $test_request1=$enc_obj->getPidByEncnr($pn);
                        $pid=$test_request1['pid'];
                    }
                }else{
                    echo "<p>$sql<p>$LDDbNoRead";
                }
            }
            break;
        default :
            //Lấy thông tin bệnh nhân và các thông tin yêu cầu mổ
            if($requests=$enc_op_obj->getInfoTest($batch_nr,'pending')){
                $batchrows=$requests->RecordCount();
                if($batchrows && (isset($batch_nr) || $batch_nr)){
                    $test_request=$requests->FetchRow();
                    /* Check for the patietn number = $pn. If available get the patients data */
                    $pn=$test_request['encounter_nr'];
                    //lấy thông tin cơ bản của bệnh nhân
                    $test_request1=$enc_obj->getPidByEncnr($pn);
                    $pid=$test_request1['pid'];
                }
            }else{
                echo "<p>$sql<p>$LDDbNoRead";
            }
            break;
    }
	
    if($batchrows && $pn){      
        if( $enc_obj->loadEncounterData($pn)) {
            include_once($root_path.'include/care_api_classes/class_globalconfig.php');
            $GLOBAL_CONFIG=array();
            $glob_obj=new GlobalConfig($GLOBAL_CONFIG);
            $glob_obj->getConfig('patient_%');
            switch ($enc_obj->EncounterClass())
            {
                case '1': $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
                           break;
                case '2': $full_en = ($pn + $GLOBAL_CONFIG['patient_outpatient_nr_adder']);
                                                break;
                default: $full_en = ($pn + $GLOBAL_CONFIG['patient_inpatient_nr_adder']);
            }
            if( $enc_obj->is_loaded){
                $result=&$enc_obj->encounter;
                $sql7="SELECT * FROM care_test_request_or WHERE batch_nr='".$batch_nr."'";
                if($ergebnis=$db->Execute($sql7)){
                        if($editable_rows=$ergebnis->RecordCount()){
                                $edit_form=1;
                                $stored_request=$ergebnis->FetchRow();
                                $str_operator=explode('-MNV:',$stored_request['person_surgery']);
                                $operator=$str_operator['1'];
                        }
                }else{
                        echo "<p>$sql<p>$LDDbNoRead";
                }
            }            
        }else{
            $mode='';
            $pn='';
        }
    }
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" "html.dtd">
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<meta http-equiv='refresh' content='30'>
<style type="text/css">
    div.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10;}
    div.fa2_ml10 {font-family: arial; font-size: 12; margin-left: 10;}
    div.fva2_ml3 {font-family: verdana; font-size: 12; margin-left: 3; }
    div.fa2_ml3 {font-family: arial; font-size: 12; margin-left: 3; }
    .fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva2b_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva0_ml10 {font-family: verdana,arial; font-size: 10; margin-left: 10; color:#000000;}
</style>
<script language="javascript">
    function resetlogdisplays()
    {
        window.parent.LOGINPUT.location.replace('<?php echo $root_path.'modules/op_document/op-pflege-logbuch-start_1.php'.URL_APPEND.'&mode=select&pn='.$pn.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&target=entry'.'&batch_nr='.$batch_nr; ?>');
    }
    function getinfo(m)
    {
        urlholder="<?php echo $root_path.'modules/or_logbook/op-pflege-log-getinfo_1.php'.URL_REDIRECT_APPEND.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal=3&op_nr=1&enc_nr=2011000014&pday=03&pmonth=12&pyear=2011&winid=operator';?>"+m;
        getinfowin=window.open(urlholder,"getinfo","width=800,height=500,menubar=no,resizable=yes,scrollbars=yes");
    }
    function checksubmit()
    {
        xdoc=document.form_test_request;
        xdoc.submit();
        return false;
    }
    function pruf(d){
        if(!d.inputdata.value) return false;
        else return true
    }
    function selecthour(selectobj){
        var gio=selectobj.selectedIndex;
        d=document.chgdept;
        d.hour.value=gio;
    }
    function selectminute(selectobj){ 
        var phut=selectobj.selectedIndex;
        d=document.chgdept;
        d.minute.value=phut;
    }
    function selectroom(selectobj){ 
        var saal=selectobj.selectedIndex;
        d=document.chgdept;
        d.saal.value=saal;        
    }
</script>
<script language=javascript src="<?php echo $root_path; ?>js/syncdeptsaal.js"></script>

<?php
    require($root_path.'include/core/inc_js_gethelp.php');
    require($root_path.'include/core/inc_css_a_hilitebu.php');
?>
 <?php if(!$datafound) { ?>
<script language="javascript" src="<?php echo $root_path; ?>js/showhide-div.js"></script>
<?php } ?>

</HEAD>
<BODY BACKGROUND="#ffffff" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 bgcolor="silver" alink="navy" vlink="navy"
onload="if (window.focus) window.focus();window.focus();document.infoform.inputdata.focus();">

<CENTER>    
<?php
    $opabt=get_meta_tags($root_path.'global_conf/'.$lang.'/op_tag_dept.pid');
    ///Hiển thị title dept-room-...
    echo '<table  cellpadding="3" cellspacing="1" border="0" width="100%">';
    echo '<tr class="wardlisttitlerow">
            <td colspan=1 align=center><FONT  SIZE=+1>
                <b>';
    $ergebnis1=$enc_op_obj->getInfo($batch_nr,'pending');
    $room=$ergebnis1->FetchRow();
    $room_now=$room['op_room'];
    $buffer=$dept_obj->LDvar();
    if(isset($$buffer)&&!empty($$buffer)) echo $$buffer;
            else echo $dept_obj->FormalName();
    if($room_now!=0){
        $room=$ergebnis1->FetchRow();
        echo '<p>'.$LDRoom.'-'.$room_now;
    }else echo '<p>'.$LDNOTE;
    echo '</b></FONT></td>';
?>
        <td colspan=6 align=center>
            <nobr>
                <table cellpadding=0 cellspacing=0 width="100%">
                    <form name="chgdept" action="<?php echo $root_path ?>modules/op_document/lap_e_kip_mo.php<?php echo URL_APPEND.'&batch_nr='.$batch_nr.'&mode='.$mode.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr ?>" method="post" onSubmit="return pruf(this)">
                    <tr class="wardlisttitlerow">
                        <td>
                        <?php
                            $ergebnis1=$enc_op_obj->getInfo($batch_nr,'pending');
                            echo $LDOpDate.": ";
                            if($test_request['date_request']){
                                echo formatDate2Local($test_request['date_request'], $date_format);
                            }
                            $date=$test_request['date_request'];
                         ?>
                        </td>
                        <td>
                            <?php
                                echo $LDOpStart1.": ";
                                $ergebnis1=$enc_op_obj->getInfo($batch_nr,'pending');
                                $room=$ergebnis1->FetchRow();
                                $time=explode(":",$room['doc_time']);
//                                if($room['doc_time'] && $room['doc_time']!='00:00:00'){
//                                    echo $room['doc_time'];
//                                }else{
                                    echo '<br/><select name="gio" size=1 onChange="selecthour(this)">';
                                        for($i=0;$i<24;$i++){
                                            if($i<10){
                                                $i='0'.$i;
                                            }
                                            echo'<option value="'.$i.'"';
                                            if($time['0']==$i){                                                
                                                echo ' selected>'.$time['0'];
                                            }else{
                                                echo '>'.$i;
                                            } 
                                            echo '</option>';
                                        }
                                    echo '</select>';
                                    echo $LDHour;
                                    echo '<select name="phut" size=1 onChange="selectminute(this)">';
                                    for($i=0;$i<60;$i++){
                                        if($i<10){
                                            $i='0'.$i;
                                        }
                                        echo'<option value="'.$i.'"';
                                        if($time['1']==$i){                                                
                                            echo '" selected>'.$time['1'];
                                        }else{
                                            echo '>'.$i;
                                        } 
                                        echo '</option>'; 
                                    }
                                    echo '</select>';
                                    echo $LDMinute;
//                                }
                            ?>
                        </td>
                        <td >                            
                            <input type="hidden" name="date" value="<?php  echo $date; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                            <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                        </td>
                        <td>
                            <input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>">
                            <input type="hidden" name="ward_nr" value="<?php echo $ward_nr; ?>">
                            <?php
                                    echo $LDOr.": ";
                            ?>
                                <select name="saal" size=1>
                                <?php
                                if(is_object($ORNrs)){
                                    while($ORnr=$ORNrs->FetchRow())
                                    {
                                        echo'<option value="'.$ORnr['room_nr'].'"';
                                        if ($ORnr['room_nr']==$saal) echo ' selected';
                                        echo '> '.$ORnr['room_nr'].'</option>';
                                        $t=$ORnr['room_nr'];
                                    }
                                }
                                ?>
                                </select>
                        </td>                    
                    <td align="center">&nbsp;
                    <input type="submit" value="<?php echo $LDChange; ?>" >
                    </td>
                </tr>
                <input type="hidden" name="hour" value=""/>
                <input type="hidden" name="minute" value=""/>
                <input type="hidden" name="batch_nr" value="<?php echo $batch_nr?>"/>
                <input type="hidden" name="operator" value="<?php echo $operator?>"/>
                <input type="hidden" name="internok" value="<?php echo $internok?>"/>
                <input type="hidden" name="enc_nr" value="<?php echo $enc_nr?>"/>
                <input type="hidden" name="date" value="<?php echo $date?>"/>
                </form>
            </table>
        </nobr>
    </td>
<?php
    echo '
        <td colspan=1><br/>
        <form>        
        <input type="button" value="'.$LDRefreshWindow.'" title="'.$LDRefreshWindow.'" onclick="window.location.reload()"></form>
        </td>';
        echo '<td colspan=1 align=middle><a href="';     
        echo $thisfile.'?sid='.$sid.'&lang='.$lang.'&internok='.$internok.'&mode=done'.'&enc_nr='.$pn.'&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;
        if($mode=='edit'){
            echo '&flag=1 ">';
        }else{
            echo '">';
        }
        echo '    
            <img '.createLDImgSrc($root_path,'savedisc.gif','0','absmiddle').'
            alt="'.$LDClose.'?>"></a></td>';
echo '</tr>';
//////
if($stored_request){
    echo '<tr bgcolor="#fdfdfd">
    <td valign=top width=200>';
    echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=250 height=178><br>'.$LDSurgeryNr.':&nbsp;';
    echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
    echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0></td>";
    echo '
    <td valign=top width=200><font face="verdana,arial" size="2" >';
    echo '
    <font color="#cc0000">'.$LDOpMainElements['diagnosis'].':</font><br>';
    echo nl2br($stored_request['clinical_info']);
    echo '
    </td><td valign=top width=200><font color="#cc0000">'.$LDMethodOP.':</font><br>';
    echo nl2br($stored_request['test_request']);
    echo '</td><td valign=top colspan=3>';
?>
        <form name="form_test_request" method="post" action="<?php echo $root_path ?>modules/op_document/lap_e_kip_mo.php<?php echo URL_APPEND.'&mode=save&enc_nr='.$enc_nr.'&pn='.$pn.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&batch_nr='.$batch_nr?>" onSubmit="return chkForm(this)">
        <table border=0 cellpadding=1 cellspacing=0>
            <?php
                $ebuf=array("person_surgery","assistant","scrub_nurse","rotating_nurse");
                $jbuf=array("operator", "scrub", "rotating", "ana", "ana_assist");//"assist",
                $function=array("4","10","7","5","8");//"12",10,11
                for($i=0;$i<5;$i++){
                    $personell=$enc_op_obj->searchPersonell($room['nr'],$function[$i],'chosed');
                    $personell_info[$i]=$personell;
                }
                for($n=0;$n<sizeof($jbuf);$n++){
            ?>
            <tr>
                <td align="left"><font face="verdana,arial" size="2"><nobr>
                <?php
                    if($n==0){
						echo '<font color="#cc0000">'.$LDDocOP.':</font><br>';
					}else{
						echo '<font color="#cc0000">'.$LDOpPersonElements[$jbuf[$n]].':</font><br>';
					}  
                    //List chứa tên của các bác sĩ,y tá,gây mê,...
                    if($personell_info[$n]){
                        for($i=0;$i<sizeof($personell_info[$n]);$i++){
                            if(trim($personell_info[$n][$i],'\x')=='') continue;
                            else echo trim($personell_info[$n][$i],'\x').'<br/>';
                        }
                    }
                    if($room_now){
                        /*if($jbuf[$n]=='operator'){
                            echo $person_surgery[0].'<br/>';
                        }*/                        
                        echo '<a href="'.$root_path.'modules/or_logbook/op-pflege-log-getinfo_1.php'.URL_REDIRECT_APPEND.'&date_request='.$date.'&control='.$mode.'&batch_nr='.$stored_request['batch_nr'].'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal='.$room_now.'&op_nr='.$room_now.'&winid='.$jbuf[$n].'"
                                target="OPLOGIMGBAR">
                            <font color="darkblue"><i><img '.createComIcon($root_path,"dwnarrowgrnlrg.gif","0").'\>'.$LDChange.'</i></font>
                          </a><br/>';
                    }                    
                ?>
                </td>
            </tr>
                <?php } ?>
            <tr>
            </tr>
           </table>
           </form>
        </td>
        <td valign=top width=250>
            <table width="100%">
                <tr>
                    <td width="70%">
                        <font color="#cc0000"><?php echo $LDMed; ?></font>
                    </td>
                    <td>
                        <font color="#cc0000"><?php echo $LDNumberPharma; ?></font>
                    </td>
                </tr>
                <tr>
                    <td width="100%" colspan="2">
                        <?php
                            echo '<a href="'.$root_path.'modules/or_logbook/op-pflege-log-getinfo_med.php'.URL_REDIRECT_APPEND.'&batch_nr='.$stored_request['batch_nr'].'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal='.$room_now.'&winid=med"
                                    target="OPLOGIMGBAR">
                                    <img '.createComIcon($root_path,"dwnarrowgrnlrg.gif","0").'\>'.$LDChange.'
                                </a><p></p>';
                            if(isset($batch_nr)){
                                $result=$enc_op_obj->getInfo($batch_nr);
                            }else{
                                $result=$enc_op_obj->getInfo($stored_request['batch_nr']);
                            }                        
                            $med_op=$result->FetchRow();
                            if($med_op[medical_codedlist]){
                                $dbuf=explode("~",$med_op[medical_codedlist]);
                                $nbuf=explode("u=",trim($med_op[medical_codedlist]));
                                echo '<table class="submenu_frame" width="100%">';
                                    for($i=1;$i<sizeof($dbuf);$i++){
                                    echo '<tbody class="submenu">
                                            <tr>
                                                <td width="70%" bgcolor="white">';
                                                    parse_str(trim($dbuf[$i]),$elems);
                                                    if($elems[n]=='') continue;
                                                    else{ 
                                                        $issue_paper=$med->getMedInfo($elems[n],$dept_nr,$ward_nr);
                                                        echo $issue_paper["product_name"];
                                                    }                                                
                                        echo '  </td>
                                                <td align="center" bgcolor="white">';
                                                    $number=explode("&x=",$nbuf[$i]);
                                                    echo $number[0];
                                        echo '  </td>
                                            </tr>
                                        </tbody>';
                                    }
                                echo '</table>';
                            }
                    ?>
                    </td>                
                </tr>
            </table>
        </td>
        <td valign=top colspan=3>
            <table width="100%">
                <tr>
                    <td width="70%">
                        <font color="#cc0000"><?php echo $LDPharma; ?></font>
                    </td>
                    <td>
                        <font color="#cc0000"><?php echo $LDNumberPharma; ?></font>
                    </td>
                </tr>
                <tr>
                    <td width="100%" colspan="2">
                        <?php
                            echo '<a href="'.$root_path.'modules/or_logbook/op-pflege-log-getinfo_pharma.php'.URL_REDIRECT_APPEND.'&batch_nr='.$stored_request['batch_nr'].'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal='.$room_now.'&winid=pharma"
                                    target="OPLOGIMGBAR">
                                    <img '.createComIcon($root_path,"dwnarrowgrnlrg.gif","0").'\>'.$LDChange.'
                                </a><p></p>';
                            if(isset($batch_nr)){
                                $result=$enc_op_obj->getInfo($batch_nr);
                            }else{
                                $result=$enc_op_obj->getInfo($stored_request['batch_nr']);
                            }                        
                            $pharma_op=$result->FetchRow();
                            if($pharma_op[material_codedlist]){
                                $dbuf=explode("~",$pharma_op[material_codedlist]);
                                $nbuf=explode("u=",trim($pharma_op[material_codedlist]));
                                echo '<table class="submenu_frame" width="100%">';
                                    for($i=1;$i<sizeof($dbuf);$i++){
                                    echo '<tbody class="submenu">
                                            <tr>
                                                <td width="70%" bgcolor="white">';
                                                    parse_str(trim($dbuf[$i]),$elems);
                                                    if($elems[n]=='') continue;
                                                    else{ 
                                                        $issue_paper=$pharma->getPharmaInfo($elems[n],$dept_nr,$ward_nr);
                                                        echo $issue_paper["product_name"];
                                                    }                                                
                                        echo '  </td>
                                                <td align="center" bgcolor="white">';
                                                    $number=explode("&x=",$nbuf[$i]);
                                                    echo $number[0];
                                        echo '  </td>
                                            </tr>
                                        </tbody>';
                                    }
                                echo '</table>';
                            }
                    ?>
                    </td>                
                </tr>
            </table>
        </td>
    </tr>
    <?php }
?>
</table>

<a name="bot"></a>
</BODY>
</HTML>
