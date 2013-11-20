<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    define('MAX_BLOCK_ROWS',30);
    define('REDIRECT_SINGLERESULT',0); # Define to 1 if single result must be redirected to input page   

    $lang_tables=array('actions.php','aufnahme.php','departments.php','doctors.php','search.php','prompt.php','actions.php');
    define('LANG_FILE','or.php');
    $local_user='ck_opdoku_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    # Create encounter object
    require_once($root_path.'include/care_api_classes/class_encounter.php');
    $enc_obj= new Encounter;
    /* Save dept name to session */
    if(!isset($_SESSION['sess_dept_name'])) $_SESSION['sess_dept_name'] = "";
    /* Create dept object and preload dept info */
    require_once($root_path.'include/care_api_classes/class_department.php');
    $dept_obj=new Department;
    $dept_obj->preloadDept($dept_nr);
    $buffer=$dept_obj->LDvar();
    require_once($root_path.'include/care_api_classes/class_encounter_op.php');
    $op_encounter=new OPEncounter;

    if(isset($$buffer)&&!empty($$buffer)) $_SESSION['sess_dept_name']=$$buffer;
    else $_SESSION['sess_dept_name']=$dept_obj->FormalName();
    // edit 28/11-Huỳnh///////////////////
    require_once($root_path.'include/care_api_classes/class_oproom.php');
    # Create the OR object
    $OR_obj=& new OPRoom;
    # Get all OR
    $OR_rooms=$OR_obj->AllORInfo();
    # Get the number or returned ORs
    //chi chon nhung phong thuoc khoa phau thuat va gay me hoi suc
    $select_room=$OR_obj->selectRoom($dept_nr,$ward_nr);

    /* Load global configs */
    include_once($root_path.'include/care_api_classes/class_globalconfig.php');
    $GLOBAL_CONFIG=array();
    $glob_obj=new GlobalConfig($GLOBAL_CONFIG);
    $glob_obj->getConfig('patient_%');

    require_once($root_path.'include/care_api_classes/class_obstetrics.php');
    $request_obj= new Obstetrics;
    //lấy tên người được yêu cầu mổ
    if($request_obj->ProfileSurgery($batch_nr)){
    $request_op=$request_obj->ProfileSurgery($batch_nr);
    }

    if ((substr($matchcode,0,1)=='%')||(substr($matchcode,0,1)=='&')) {header("Location:'.$root_path.'language/".$lang."/lang_".$lang."_invalid-access-warning.php"); exit;};
    $breakfile=$root_path.'modules/op_document/op_test_request_admin_done.php'.URL_REDIRECT_APPEND."&target=".$target."&subtarget=".$subtarget."&noresize=1&&user_origin=op&checkintern=1&batch_nr=$batch_nr&&user_origin=op_done&dept_nr=$dept_nr&ward_nr=$ward_nr&pn=$pn";
    $thisfile=basename(__FILE__);

    /* Set color values for the search mask */
    $searchmask_bgcolor='#f3f3f3';
    $searchprompt=$LDEntryPrompt_1;
    $entry_block_bgcolor='#fff3f3';
    $entry_border_bgcolor='#6666ee';
    $entry_body_bgcolor='#ffffff';

    if(!isset($dept)||empty($dept))
    if($_COOKIE['ck_thispc_dept']) $dept=$_COOKIE['ck_thispc_dept'];
            else $dept='plop'; // default department is plop

    $linecount=0;
    # check date for completeness

    if($mode=='save')
    {
        $err_data=0;
        if(!$op_date) {$err_op_date=1; $err_data=1;}
        if(!$operator) {$err_operator=1;$err_data=1;}
        if(!$localize) {$err_localize=1;$err_data=1;}
        if(!$therapy) {$err_therapy=1;$err_data=1;}
        if(!$special) {$err_special=1;$err_data=1;}
        if(!(($class_s)||($class_m)||($class_l))) {$err_klas=1;$err_data=1;}
        if(!$op_start) {$err_op_start=1;$err_data=1;}
        if(!$op_end) {$err_op_end=1;$err_data=1;}
        if(!$scrub_nurse) {$err_scrub_nurse=1;$err_data=1;}
        if(!$rotating_nurse) {$err_rotating_nurse=1;$err_data=1;}
        if(!$op_room) {$err_op_room=1;$err_data=1;}
        if($err_data) $mode='?';

    }

    /* Load date formatter */
    include_once($root_path.'include/core/inc_date_format_functions.php');

    /* If the patient number is available = $patnum , get the data from the admission table */
    if(isset($pn) && !empty($pn)){
        $enc_obj->where=" encounter_nr=$pn";
    if( $enc_obj->loadEncounterData($pn)) {
        $full_en=$pn;
        if( $enc_obj->is_loaded){
            $result=&$enc_obj->encounter;
            $rows=$enc_obj->record_count;
        }
        if($ergebnis=$op_encounter->getInfo($batch_nr)){
            $opdoc1=$ergebnis->FetchRow();
            if(!isset($update)&&!isset($nr))
            {
                $nr=$opdoc1['nr'];
                $sql="SELECT * FROM care_op_med_doc WHERE encounter_op_nr='$nr'";
                if($ergebnis=$db->Execute($sql))
                {
                    if($opdoc=$ergebnis->FetchRow()){
                        $data=1;
                    }                    
                }
            }
        }
        if($date=$op_encounter->getInfoTest($batch_nr,'draff')){
            $opdoc2=$date->FetchRow();
        }else echo "$sql<br>$LDDbNoRead";
    }else{
            echo "$sql<br>$LDDbNoRead";
            $mode='?';
    }
    }
    if($flag==1){
    $dbtable='care_op_med_doc';
    $op_start=strtr($op_start,'.;,',':::');
    $s_count=substr_count($op_start,':');
    switch($s_count)
    {
        case 0: $op_start.=':00:00';break;
        case 1: $op_start.=':00';break;
        case '': $op_start.=':00:00';
    }
    $op_end=strtr($op_end,'.;,',':::');
    $s_count=substr_count($op_end,':');
    switch($s_count)
    {
        case 0: $op_end.=':00:00';break;
        case 1: $op_end.=':00';break;
        case '': $op_end.=':00:00';
    }
    if($update==1)
    {
        $dbtable='care_op_med_doc';
        $dbtable1='care_room';
        $sql="SELECT * FROM $dbtable WHERE encounter_op_nr='$nr'";
        if($ergebnis=$db->Execute($sql))
        {
            if($rows=$ergebnis->RecordCount())
            {
                $opdoc=$ergebnis->FetchRow();
            }
        }else echo "<font size=4 color=red><b>$LDDbNoRead</b></font>";
    }else{
        if($ergebnis=$op_encounter->getInfo($batch_nr)){
            $opdoc1=$ergebnis->FetchRow();
            $room_now=$opdoc1['op_room'];
        }
        $sql="INSERT INTO $dbtable
        (	encounter_op_nr,
                localize,
                therapy,
                special,
                class_s,
                class_m,
                class_l,
                op_start,
                op_end,
                result,
                history,
                create_id
                 )
        VALUES (
                '$opdoc1[nr]',
                '".htmlspecialchars($localize)."',
                '".htmlspecialchars($therapy)."',
                '".htmlspecialchars($special)."',
                '$class_s',
                '$class_m',
                '$class_l',
                '$op_start',
                '$op_end',
                'done',
                'Create: ".date('Y-m-d H:i:s')." by ".$_SESSION['sess_user_name']."\n',
                '".$_SESSION['sess_user_name']."'
        )";
        
        if($ergebnis=$enc_obj->Transact($sql)){
            $oid=$db->Insert_ID();
            $enc_obj->coretable=$dbtable;
            $nr = $enc_obj->LastInsertPK('nr',$oid);
            header("location:op-doku-start_1.php?sid=$sid&lang=$lang&target=$target&mode=saveok&pn=$pn&nr=$nr&dept_nr=$dept_nr&ward_nr=$ward_nr&batch_nr=$batch_nr");
            exit;
        }else{            
            echo "<font size=4 color=red><b>$sql.$LDDbNoSave</b></font>";            
        }
    }
}
switch($mode){ 
    case 'saveok':
        $dbtable='care_op_med_doc';
            if($opdoc=$op_encounter->getInfoMedoc('',$nr)){            
            }else echo "$sql<br>$LDDbNoRead";
            break;
        case 'update':
            $sql="UPDATE $dbtable SET 
                            localize='".htmlspecialchars($localize)."',
                            therapy='".htmlspecialchars($therapy)."',
                            special='".htmlspecialchars($special)."',
                            class_s='$class_s',
                            class_m='$class_m',
                            class_l='$class_l',
                            op_start='$op_start',
                            op_end='$op_end',
                            history='Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
                            modify_id='".$_SESSION['sess_user_name']."'
                            WHERE nr='$nr'";
            if($ergebnis=$db->Execute($sql))
            {
                header("location:op-doku-start_1.php?sid=$sid&lang=$lang&target=$target&mode=saveok&pn=$pn&nr=$nr&dept_nr=$dept_nr&batch_nr=$batch_nr"); 
            }else echo "$sql<br>$LDDbNoRead";
            break;
        case 'select': break;
        default:
            if($_COOKIE["ck_login_logged".$sid]) $mode="dummy";
    }

    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('nursing');

    # Added for the common header top block

    $smarty->assign('sToolbarTitle',"$LDOrRecord :: (".$_SESSION['sess_dept_name'].")");

    # href for help button
    if(!$mode) $sBuffer ='dummy';
        else $sBuffer = $mode;

    $smarty->assign('pbHelp',"javascript:gethelp('opdoc.php','create','$sBuffer')");

    # hide return button
    $smarty->assign('pbBack',FALSE);

    # href for close button
    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('sWindowTitle',"$LDOrRecord :: (".$_SESSION['sess_dept_name'].")");

    # Prepare Body onLoad javascript code
    if(!isset($mode) || empty($mode) || $mode=='dummy') {
        $smarty->assign('sOnLoadJs','onLoad="document.searchform.searchkey.focus();"');
    }
    /**
    * collect JavaScript for Smarty
    */    
    ob_start();
?>
<?php
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    require($root_path.'classes/datetimemanager/checktime.php');
?>
<script  language="javascript">
    var iscat=true;
    var pix=new Image();

    function popselectsum(flag)
    {
        w=window.screen.width;
        h=window.screen.height;
        ww=800;
        wh=600; 
        switch(flag){
            case 'pharma':
                urlholder="<?php echo $root_path?>modules/or_logbook/op-pflege-log-getinfo_pharma.php<?php echo URL_REDIRECT_APPEND.'&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal='.$room_now.'&winid=pharma'?>&flag=op";
                break;
            case 'med':
                urlholder="<?php echo $root_path?>modules/or_logbook/op-pflege-log-getinfo_med.php<?php echo URL_REDIRECT_APPEND.'&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal='.$room_now.'&winid=med'?>&flag=op";
                break;
            case 'chemical':
                urlholder="<?php echo $root_path?>modules/or_logbook/op-pflege-log-getinfo_chemical.php<?php echo URL_REDIRECT_APPEND.'&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&saal='.$room_now.'&winid=chemical'?>&flag=op";
                break;
            case 'service_op':
                urlholder="<?php echo $root_path?>modules/ecombill/patientbill.php<?php echo URL_APPEND.'&full_en='.$full_en.'&patientno='.$full_en.'&update=1&target=nursing&full_en='.$full_en?>&flag=op";
                break;
            default:
                break;
        }        
        popselectwin=window.open(urlholder,"pop","width=" + ww + ",height=" + wh + ",menubar=no,resizable=yes,scrollbars=yes,dependent=yes");
        window.popselectwin.moveTo((w/2)+80,(h/2)-(wh/2));
    }
    function hilite(idx,mode)
    {
        if(mode==1) idx.filters.alpha.opacity=100
        else idx.filters.alpha.opacity=70;
    }
    function lookmatch(d)
    {
        m=d.matchcode.value;
        if(m=="") return false;
        if((m.substr(0,1)=="%")||(m.substr(0,1)=="&"))
        {
            d.matchcode.value="";
            d.matchcode.focus();
            return false;
        }
        window.location.replace("op-doku-start_1.php?sid=<?php echo "$sid&lang=$lang" ?>&mode=match&matchcode="+m);
        return false;
    }
    function printOut() {
		urlholder="<?php echo $root_path.'modules/pdfmaker/phieuphauthuat/PhieuPhauThuat.php'.URL_APPEND.'&enc='.$pn.'&batch_nr='.$batch_nr; ?>";
		testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
    }
<?php
    if($mode!='saveok'){
?>
//chỉ có tác dụng với text còn textarea và select thì chưa được
        function chkForm(d){
            if(d.localize.value==""){
                    alert("<?php echo $LDPlsEnterLocalization; ?>");
                    d.localize.focus();
                    return false;
            }else if(d.therapy.value==""){
                    alert("<?php echo $LDPlsEnterTherapy1; ?>");
                    d.therapy.focus();
                    return false;
            }else if(d.special.value==""){
                    alert("<?php echo $LDPlsEnterNotes1; ?>");
                    d.special.focus();
                    return false;
            }else if(d.op_start.value==""){
                    alert("<?php echo $LDPlsEnterStartTime; ?>");
                    d.op_start.focus();
                    return false;
            }else if(d.op_end.value==""){
                    alert("<?php echo $LDPlsEnterEndTime; ?>");
                    d.op_end.focus();
                    return false;
            }else if(d.op_end.value<=d.op_start.value){
                alert("<?php echo $LDWarningTimeCom; ?>");
                    d.op_end.focus();
                    return false;
            }else{
                    return true;
            }
        }       
        
<?php
    }
?>
<?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>
//-->
</script>
<?php

    $sTemp = ob_get_contents();
    ob_end_clean();

    $smarty->append('JavaScript',$sTemp);

    # Buffer page output

    ob_start();
    function createElement($item,$err, $f_size=7, $mx=5)
    {
    global $mode, $err_data, $result, $lang, $isTimeElement,$opdoc;
	
    if($mode=='saveok')
    {
        $ret_str= '<font color="#800000">'.$opdoc[$item].' &nbsp;</font>';
    } 
    else
    {
        $ret_str= '<input name="'.$item.'" id="'.$item.'" type="text" size="'.$f_size.'"   maxlength='.$mx.' value="';
        if($err_data){
            $ret_str.=$err;
        }else{
            $ret_str.=$opdoc[$item];
        }	  

            if($mode=='') $ret_str.='" ';
                else $ret_str.='"';

            if($isTimeElement)  $ret_str.= ' onblur="checkTime(this)">';
                else $ret_str.='>';		 
        }
        return $ret_str;
    }
?>
<?php echo setCharSet(); ?>
<table width=100% cellspacing=0 cellpadding=0>
    <?php require('./gui_tabs_op_doku_1.php'); ?>
    <tr>
        <td colspan=2><p>
            <ul>
                <?php
                if(!$rows && !$err_data) {
                ?>
                <table border="0">
                    <tr>
                        <td><img <?php echo createComIcon($root_path,'angle_down_l.gif','0','absmiddle',TRUE) ?>></td>
                        <td class="prompt">
                            <?php
                                if($mode=='search') echo '<font color=maroon>'.$LDSorryNotFound.'</font>';
                                else echo $LDPlsSelectPatientFirst;
                            ?>
                        </td>
                        <td valign="top">
                            <img <?php echo createMascot($root_path,'mascot1_l.gif','0','absmiddle') ?>>
                        </td>
                    </tr>
                </table>

                <table border=0 cellpadding=10 width="100%" bgcolor="<?php echo $entry_border_bgcolor ?>">
                    <tr>
                        <td>
                            <?php
                            include($root_path.'include/core/inc_patient_searchmask.php');
                            ?>
                        </td>
                    </tr>
                </table>
                <?php
                }
                ?>

                <?php
                if($rows || $err_data){
                    $bg_img=$root_path.'gui/img/common/default/tableHeaderbg3.gif';
                    if($err_data){
                    ?>
                    <table border="0">
                        <tr>
                            <td> <img <?php echo createMascot($root_path,'mascot2_r.gif','0','absmiddle') ?>></td>
                            <td class="prompt"><?php echo $LDPlsFillInfo ?></td>
                        </tr>
                    </table>
                    <?php
                    }
                        $function=array("4","5","8","10","7");//,"12"
                        for($i=0;$i<6;$i++){
                            $personell=$op_encounter->searchPersonell($opdoc1[nr],$function[$i],'chosed');
                            if($personell){
                                $personell_info[$i]=$personell;
                            }                                
                        }
                    ?>
                    <table border=0 cellpadding=3 width="100%">
                        <form action="">
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td width="13%" background="<?php echo $bg_img; ?>">
                                    <font size=2 color="yellow">
                                        <b>
                                        <?php
                                            echo $LDSum;
                                        ?>
                                        </b>
                                    </font>
                                </td>
                                <td>
                                    <table width="40%">
                                        <tr>
                                            <td width="50%">
                                                <?php                                                    
                                                    echo '<a href="javascript:popselectsum(\'pharma\')">';
                                                ?>                                                
                                                    <font size=2 color="darkred">
                                                        <b>
                                                            <image src="<?php echo $root_path.'gui/img/common/default/b-write_addr.gif';?>" />
                                                            <?php echo $LDPaymentPharma;?>
                                                        </b>
                                                    </font>
                                                </a>
                                            </td>
                                            <td width="50%">
                                                    <?php
                                                        echo '<a href="javascript:popselectsum(\'med\')">';
                                                    ?>
                                                    <font size=2 color="darkred">
                                                        <b>
                                                            <image src="<?php echo $root_path.'gui/img/common/default/b-write_addr.gif';?>" />
                                                            <?php echo $LDPaymentMed;?>
                                                        </b>
                                                    </font>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td width="13%" background="<?php echo $bg_img; ?>">
                                    <font size=2 color="yellow">
                                        <b>
                                        <?php
                                            echo $LDSumservices;
                                        ?>
                                        </b>
                                    </font>
                                </td>
                                <td>
                                    <table width="40%">
                                        <tr>
                                            <td width="30%">
                                                <?php 
                                                    echo '<a href="javascript:popselectsum(\'service_op\')">';
                                                ?>
                                                <font size=2 color="darkred">
                                                    <b>
                                                        <image src="<?php echo $root_path.'gui/img/common/default/b-write_addr.gif';?>" />
                                                        <?php echo $LDPaymentService;?>
                                                    </b>
                                                </font>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>                                    
                                </td>
                            </tr>
                        </form>
                    </table>                    
                    <table border=0 cellpadding=3 width="100%">
                        <form method="post" action="op-doku-start_1.php" name="opdoc" <?php if($mode!='saveok') echo 'onSubmit="return chkForm(this)"'; ?>>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td width="13%" background="<?php echo $bg_img; ?>">
                                    <?php if($err_op_date) echo '*'; ?>
                                    <b>
                                        <?php echo $LDOpDate ?>:
                                    </b>
                                    </br>
                                </td>                                
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td width="13%" align="left">
                                                <font color="#000099">
                                                <?php
                                                    if($mode=='saveok'){
                                                        echo formatDate2Local($opdoc2["date_request"], $date_format);
                                                    }else{
                                                        echo formatDate2Local($opdoc2["date_request"], $date_format);
                                                    }
                                                ?>
                                                </font>
                                            </td>                                            
                                            <td width="12.5%" background="<?php echo $bg_img; ?>">
                                                <font size=2 >
                                                    <b>
                                                        <?php echo $LDOperator1 ?>:
                                                    </b>
                                                </font>
                                            </td>
                                            <td>
                                                <?php
                                                if($personell_info[0]){
                                                    for($i=0;$i<sizeof($personell_info[0]);$i++){
                                                        if(trim($personell_info[0][$i],'\x')=='') continue;
                                                        if($mode=='saveok'){
                                                            echo '<font color="#800000">'.trim($personell_info[0][$i],'\x').'</font>';
                                                        }else{
                                                                echo '<font color="#000099">'.trim($personell_info[0][$i],'\x').'</font><br/>';
                                                            }
                                                    }                                                    
                                                }                                                    
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>"><p>
                                    <font <?php if($err_patnum) echo 'color=#cc0000'; ?>>
                                        <b>
                                            <?php echo $LDPatientNr ?>:
                                        </b>
                                    </font>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td width="13%" align="left">
                                                <font color="#000099">
                                                <?php
                                                    echo '<b>'.$full_en.'</b>';
                                                ?>
                                                </font>
                                            </td>                                            
                                            <td width="12.5%" background="<?php echo $bg_img; ?>">
                                                <font size=2>
                                                    <b>
                                                        <?php echo $LDORANES ?>:
                                                    </b>                                                
                                                </font>
                                            </td>
                                            <td>
                                                <?php
                                                    if($personell_info[1]){
                                                        for($i=0;$i<sizeof($personell_info[1]);$i++){
                                                            if(trim($personell_info[1][$i],'\x')=='') continue;
                                                            if($mode=='saveok'){
                                                                echo '<font color="#800000">'.trim($personell_info[1][$i],'\x').'</font>';
                                                            }else{
                                                                echo '<font color="#000099">'.trim($personell_info[1][$i],'\x').'</font><br/>';
                                                            }
                                                        }   
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>">
                                    <font <?php if($err_name) echo 'color=#cc0000'; ?>>
                                        <b>
                                            <?php echo $LDLastName ?>:
                                        </b>                                        
                                    </font>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td width="13%" align="left">
                                                <font color="#000099">
                                                    <?php
                                                        echo '<b>'.$result['name_last'].'</b>';
                                                    ?>
                                                </font>
                                            </td>
                                            <td width="12.5%" background="<?php echo $bg_img; ?>">
                                                <font size=2>
                                                    <b>
                                                        <?php echo $LDORANESASSIST ?>:
                                                    </b>                                                
                                                </font>
                                            </td>
                                            <td>
                                                <?php
                                                    if($personell_info[2]){
                                                        for($i=0;$i<sizeof($personell_info[2]);$i++){
                                                            if(trim($personell_info[2][$i],'\x')=='') continue;
                                                            if($mode=='saveok'){
                                                                echo '<font color="#800000">'.trim($personell_info[2][$i],'\x').'</font>';
                                                            }else{
                                                                echo '<font color="#000099">'.trim($personell_info[2][$i],'\x').'</font><br/>';
                                                            }
                                                        }   
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>">
                                    <font <?php if($err_vorname) echo 'color=#cc0000'; ?>>
                                        <b>
                                            <?php echo $LDName ?>:
                                        </b>                                        
                                    </font>
                                </td>
                                <td>
                                    <table cellpadding=3 width="100%">
                                        <tr>
                                            <td width="13%" align="left">
                                                <font color="#000099">
                                                <?php
                                                    echo '<b>'.$result['name_first'].'</b>';
                                                ?>
                                                </font>
                                            </td>
                                            <td width="12.5%" background="<?php echo $bg_img; ?>">
                                                <font size=2>
                                                    <b>
                                                        <?php echo $LDScrubNurse ?>:
                                                    </b>                                               
                                                </font>
                                            </td>
                                            <td>
                                                <?php
                                                    if($personell_info[3]){
                                                        for($i=0;$i<sizeof($personell_info[3]);$i++){
                                                            if(trim($personell_info[3][$i],'\x')=='') continue;
                                                            if($mode=='saveok'){
                                                                echo '<font color="#800000">'.trim($personell_info[3][$i],'\x').'</font>';
                                                            }else{
                                                                echo '<font color="#000099">'.trim($personell_info[3][$i],'\x').'</font><br/>';
                                                            }
                                                        }   
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>">
                                    <font <?php if($err_gebdatum) echo 'color=#cc0000'; ?>>
                                        <b>
                                            <?php echo $LDBday ?>:
                                        </b>
                                    </font>
                                </td>
                                <td>
                                <table cellpadding=3 width="100%">
                                        <tr>
                                            <td width="13%" align="left">
                                                <font color="#000099">
                                                <?php
                                                    echo @formatDate2Local($result['date_birth'],$date_format);
                                                ?>
                                                </font>
                                            </td>
                                            <td width="12.5%" background="<?php echo $bg_img; ?>">
                                                        <font size=2>
                                                            <b>
                                                                <?php echo $LDRotatingNurse ?>:
                                                            </b>                                                        
                                                        </font>
                                            </td>
                                                    <td>
                                                        <?php
                                                            if($personell_info[4]){
                                                                for($i=0;$i<sizeof($personell_info[4]);$i++){
                                                                    if(trim($personell_info[4][$i],'\x')=='') continue;
                                                                    if($mode=='saveok'){
                                                                        echo '<font color="#800000">'.trim($personell_info[4][$i],'\x').'</font>';
                                                                    }else{
                                                                        echo '<font color="#000099">'.trim($personell_info[4][$i],'\x').'</font></br>';
                                                                    }
                                                                }   
                                                            }
                                                        ?>
                                                    </td>
                                                </td>        
                                        </tr>
                                    </table> 
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>">
                                    <font size=2 color="yellow">
                                        <b>
                                            <?php 
                                                if($err_localize) echo '*';
                                                echo $LDMethodSurgery;
                                            ?>:
                                        </b>
                                    </font>
                                </td>
                                <td>
                                     <?php
                                        if($mode=='saveok'){
                                            echo '<font color="#800000">'.$opdoc['localize'].'</font>';
                                        }else{
                                            echo '<textarea name="localize" rows=4 cols=65';
                                            if($isTimeElement)  echo ' onKeyUp="setTime(this,\''.$lang.'\')">';
                                            else echo '>';
                                            if($err_data){
                                                echo $localize.'</textarea>';
                                            }else{
                                                echo $opdoc['localize'].'</textarea>';
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>">
                                    <font size=2 color="yellow">
                                    <b>
                                        <?php 
                                            if($err_therapy) echo '*';
                                            echo $LDTherapy1;
                                        ?>:
                                    </b>
                                    </font>
                                </td>
                                <td>
                                    <?php
                                        echo createElement('therapy',$therapy,85,100);
                                    ?>
                                </td>
                            </tr >
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>">
                                    <font size=2 color="yellow">
                                    <b>
                                        <?php 
                                            if($err_special) echo '*';
                                                echo $LDMethodDiagnosis;
                                        ?>:
                                    </b>
                                    </font>
                                </td>
                                <td>
                                    <?php
                                        echo createElement('special',$special,85,100);
                                    ?>
                                </td>
                            </tr>
                            <tr <?php if($mode=='saveok') echo "bgcolor=#ffffff"; ?>>
                                <td background="<?php echo $bg_img; ?>">
                                    <font size=2 color="yellow">
                                    <b>
                                    <?php 
                                        if($err_klas) echo '*';
                                        echo $LDClassification; 
                                    ?>:
                                    </b>
                                    </font>
                                </td>
                                <td>
                                    <font color="#000099">
                                    <?php if($mode=='saveok')
                                    {
                                        if($opdoc[class_s]) echo "$opdoc[class_s] $LDMinor  &nbsp; ";
                                        if($opdoc[class_m]) echo "$opdoc[class_m] $LDMiddle &nbsp; ";
                                        if($opdoc[class_l]) echo "$opdoc[class_l] $LDMajor";
                                        echo " $LDOperation";
                                    }
                                    else
                                    {
                                    ?>
                                    <select name="class_s">
                                    <option value="0"> </option>
                                    <?php
                                        for($i=1;$i<9;$i++){
                                            echo "<option value=\"$i\"";
                                            if($err_data) $buf= $class_s; else $buf = $opdoc['class_s'];
                                            if($i == $buf) echo 'selected';
                                            echo ">$i</option>";
                                        }
                                    ?>
                                    </select>
                                    <?php echo $LDMinor ?>&nbsp;
                                    <select name="class_m">
                                        <option value="0"> </option>
                                        <?php
                                            for($i=1;$i<9;$i++){
                                                echo "<option value=\"$i\"";
                                                if($err_data) $buf= class_m; else $buf = $opdoc['class_m'];
                                                if($i == $buf) echo 'selected';
                                                echo ">$i</option>";
                                            }
                                        ?>
                                    ?>
                                    </select>
                                    <?php echo $LDMiddle ?>&nbsp;
                                    <select name="class_l">
                                        <option value="0"></option>
                                        <?php
                                            for($i=1;$i<9;$i++){
                                                echo "<option value=\"$i\"";
                                                if($err_data) $buf= class_l; else $buf = $opdoc['class_l'];
                                                if($i == $buf) echo 'selected';
                                                echo ">$i</option>";
                                            }
                                        ?>
                                    </select>
                                    <?php echo "$LDMajor $LDOperation" ?>
                                    <?php } ?>
                                    </font>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <?php
                        if($rows || $err_data){
                    ?>

                    <p>
                    <font color="darkred">
                        <?php 
                            if($err_op_start) echo '*'; 
                            /* Set the global $isTimeElement to 1 to cause the function to insert the setTime Code in the form input code */
                            $isTimeElement=1;
                            echo '<font color="darkred">';if($err_op_start) echo '<font color="*">'; ?> &nbsp; <?php echo $LDOpStart1.':';
                            echo createElement('op_start',$op_start);
                            echo '<font color="darkred">';if($err_op_end) echo '<font color="*">'; ?> &nbsp; <?php echo $LDOpEnd1.':';
                            echo createElement('op_end',$op_end);
                            /* Reset the global $isTimeElement to 1 to disable the setTime code insertion*/
                            $isTimeElement=0;
                            echo '<font color="darkred">';if($err_op_room) echo '<font color="*">'; ?>  &nbsp; <?php echo $LDOpRoom.':';                            
                       ?>
                    </font>
                    <font color="#800000">
                    <?php 
                        if($mode=='saveok'){
                            if($opdoc1['op_room']) echo '<font color="#000099">'.$opdoc1['op_room'].'</font>';
                        }
                        else
                        {
                            echo '<font color="#000099">'.$opdoc1[op_room].'</font>';
                        }
                    ?>
                    </font>
                    <p>
                    <?php if($mode=='saveok' || $data==1) : ?>
                        <a href="javascript:printOut()">
                            <img <?php echo createLDImgSrc($root_path,'printout.gif','0') ?> alt="Print"/>
                        </a>
                        <a href="<?php echo 'op-doku-start_1.php?sid='.$sid.'&lang='.$lang.'&target='.$target.'$mode=&update=1&pn='.$pn.'&nr='.$nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&batch_nr='.$batch_nr.'' ?>">
                            <img <?php echo createLDImgSrc($root_path,'update_data.gif','0') ?> alt="Update"/>
                        </a>
                    <?php else : ?>
                    <input  type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?>  alt="<?php echo $LDSave ?>"/>
                    
                    <a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?>  alt="<?php echo $LDClose ?>"></a>
                    <?php endif ?>
                    <input type="hidden" name="op_date" value="<?php echo $opdoc1['op_date']?>"/>
                    <input type="hidden" name="mode" value="<?php if($update=='1') echo 'update'; else echo 'save' ?>">
                    <input type="hidden" name="flag" value="1"/>
                    <input type="hidden" name="update" value="<?php if ($mode=='dummy') echo '1' ?>">
                    <input type="hidden" name="dept_nr" value="<?php echo $dept_nr ?>"/>
					<input type="hidden" name="ward_nr" value="<?php echo $ward_nr ?>"/>
                    <input type="hidden" name="sid" value="<?php echo $sid ?>"/>
                    <input type="hidden" name="lang" value="<?php echo $lang ?>"/>
                    <input type="hidden" name="pn" value="<?php echo $pn ?>"/>
                    <input type="hidden" name="nr" value="<?php echo $nr ?>"/>
                    <input type="hidden" name="batch_nr" value="<?php echo $batch_nr ?>"/>
                    <input type="hidden" name="op_room" value="<?php echo $opdoc1[op_room]?>"/>
                    </form>
                <?php } ?>
                <p>
            </ul>
        </td>
    </tr>
</table>
<script>
   $(function(){
        $("#op_start").mask("**:**");
        $("#op_end").mask("**:**");
    });
</script>                        
<?php

$sTemp = ob_get_contents();
 ob_end_clean();
# Assign the page output to main frame template

 $smarty->assign('sMainFrameBlockData',$sTemp);
 $smarty->display('common/mainframe.tpl');

?>
