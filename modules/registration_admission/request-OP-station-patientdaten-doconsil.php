<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    /**
    * CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
    * GNU General Public License
    * Copyright 2002,2003,2004,2005 Elpidio Latorilla
    * , elpidio@care2x.org
    *
    * See the file "copy_notice.txt" for the licence notice
    */
    $lang_tables[] = 'departments.php';
    $lang_tables[] = 'or.php';
    $lang_tables [] = 'aufnahme.php';
    define('LANG_FILE','konsil.php');
    
    $local_user='aufnahme_user';
    $breakfile=$root_path."modules/registration_admission/aufnahme_daten_zeigen.php".URL_APPEND."&from=such&encounter_nr=".$_SESSION['sess_en']."&target=search";
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'global_conf/inc_global_address.php');
    
    //Ghi log
    require_once($root_path.'include/core/access_log.php');
    $logs = new AccessLog();
    //$db->debug=1;

    //$thisfile=$root_path."modules/registration_admission/aufnahme_daten_zeigen.php".URL_APPEND."&from=such&encounter_nr=".$_SESSION['sess_en']."&target=search";
    $this_file='request-OP-station-patientdaten-doconsil.php';
    $bgc1='#ffffff';  // entry form's background color

    $abtname=get_meta_tags($root_path."global_conf/$lang/konsil_tag_dept.pid");

    $formtitle=$LDRequestOP;

    $db_request_table='or';
    include_once($root_path.'include/care_api_classes/class_department.php');
    $dept_obj=new Department();
    //Ma noi bo
    $dept=$dept_obj->_getalldata('id=9','nr DESC','_OBJECT');
    if($dept){
        $dept_nr=$dept->FetchRow();
        $dept_nr=$dept_nr['nr'];
    }
    include_once($root_path.'include/care_api_classes/class_ward.php');
    $ward_obj=new Ward();
    //Ma noi bo
    $ward=$ward_obj->getAvaiWardOfDept($dept_nr);
    if($ward){
        while($ward_nr_f=$ward->FetchRow()){
            if($ward_nr_f['type']==2){
                    $ward_nr=$ward_nr_f['nr'];
            }    	
        }
    }
    //mã của phòng mổ là 90000000
    define('_BATCH_NR_INIT_',90000000);
    /*
    *  The following are  batch nr inits for each type of test request
    *   chemlabor = 10000000; patho = 20000000; baclabor = 30000000; blood = 40000000; generic = 50000000; radio = 60000000; dientim= 70000000
    */
    /* Here begins the real work */
    require_once($root_path.'include/core/inc_date_format_functions.php');

    # Create a core object
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    $core = & new Core;
    /* Check for the patient number = $pn. If available get the patients data, otherwise set edit to 0 */
    if(isset($pn) && $pn)
    {		
        include_once($root_path.'include/care_api_classes/class_encounter.php');
        $enc_obj=new Encounter;
        include_once($root_path.'include/care_api_classes/class_personell.php');
        $personell_obj=new Personell;
        $function=$personell_obj->_getDOCPersonell('17',$dept_nr);
        if( $enc_obj->loadEncounterData($pn)) {
                $full_en=$pn;                        
                $result=&$enc_obj->encounter;
                $status=$enc_obj->AllStatus($_SESSION['sess_full_en']);
        }
        else {
            $edit=0;
            $mode="";
            $pn="";
        }
    }
    //Kiểm tra có bảo hiểm hay không
    include_once ($root_path . 'include/care_api_classes/class_ward.php');
    $obj = new Ward ();
    if ($obj->loadEncounterData ( $pn )) {
       $result = &$obj->encounter;
    }
    switch($result['insurance_class_nr']){
        case 1:
            if($result['encounter_class_nr']==1 && !$result['is_cbtc']){
                $loaithanhtoan='s-0398';
            }elseif(!$result['is_cbtc']){
                $loaithanhtoan='s-0400';
            }           
            break;
        default:
            if($result['is_cbtc'] && $result['encounter_class_nr']==1){
                $loaithanhtoan='s-0490';
            }else if($result['is_cbtc'] && $result['encounter_class_nr']==0){
                $loaithanhtoan='s-0491';
            }elseif($result['encounter_class_nr']==1){
                $loaithanhtoan='s-0397';
            }elseif($result['encounter_class_nr']==0){
                $loaithanhtoan='s-0399';
            }else{
                $loaithanhtoan='s-0401';
            }
    }
    
    //Kiểm tra có chọn loại ca mổ chưa
    if(!isset($mode))   $mode="";
    if(($level=="" && $mode=="save") || ($level=="" && $mode=="update")){
        if($mode=="update"){
            $edit_form=1;
            $mode="edit";
        }else{
            $edit_form=1;
            $mode="";
        }
        $clinical_info=$clinical_info;
        $test_request=$test_request;
        $send_doctor=$send_doctor;
        $date_request=$date_request;
        $mo=$mo;
        $te=$te;
        $me=$me;
        $nguoimo=$nguoimo;
        $pn=$pn;
    }
    $check_pharma="SELECT prescription_id FROM care_pharma_prescription_info WHERE dept_nr=".$dept_nr." AND ward_nr=".$ward_nr." AND encounter_nr=".$pn." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
    if($execute_pharma=$db->Execute($check_pharma)){
        $query_pharma=$execute_pharma->RecordCount();
    }
//    $check_issue_pharma="SELECT issue_paper_id FROM care_pharma_issue_paper_info WHERE dept_nr=".$dept_nr." AND ward_nr=".$ward_nr." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
//    if($execute_issue_pharma=$db->Execute(check_issue_pharma)){
//        $issue_pharma=$execute_issue_pharma->RecordCount();
//    }
        
    $check_med="SELECT prescription_id FROM care_med_prescription_info WHERE dept_nr=".$dept_nr." AND ward_nr=".$ward_nr." AND encounter_nr=".$pn." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
    if($execute_issue_med=$db->Execute($check_med)){
        $query_med=$execute_issue_med->RecordCount();
    }
//    $check_issue_med="SELECT issue_paper_id FROM care_med_issue_paper_info WHERE dept_nr=".$dept_nr." AND ward_nr=".$ward_nr." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
//    if($execute_issue_med=$db->Execute($check_issue_med)){
//        $issue_med=$execute_issue_med->RecordCount();
//    }
    
    switch($mode)
    {
        case 'save':
            $sql="SELECT batch_nr FROM care_test_request_".$db_request_table." WHERE encounter_nr=".$pn." AND date_request='".formatDate2STD($date_request,$date_format)."'";
            $query=$db->Execute($sql);
            $check=$query->FetchRow();
            if($check['batch_nr']){
                echo '<script type="text/javascript">';
                echo 'alert("'.$LDWarningOpPatient.' '.$date_request.'");';
                echo '</script>';
                break;
            }else{
                $sql="INSERT INTO care_test_request_".$db_request_table." 
                    (batch_nr, encounter_nr, date_request,										   
                        clinical_info, test_request, send_date,
                        send_doctor, status,
                        history,
                        create_id,
                        method_op,
                        person_surgery,
                        level_method)
                        VALUES
                        (
                        '".$batch_nr."','".$pn."','".formatDate2STD($date_request,$date_format)."',
                     '".htmlspecialchars($clinical_info)."','".htmlspecialchars($test_request)."','".date('Y-m-d')."',
                        '".htmlspecialchars($referrer_name)."', 'pending',
                        'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n',
                        '".$_SESSION['sess_user_name']."',
                        '".htmlspecialchars($mo).' '.htmlspecialchars($te).' '.htmlspecialchars($me)." ',
                        '".htmlspecialchars($person_surgery)."',
                        '".$level."'   
                        )";
                $loaithanhtoan=explode('-',$loaithanhtoan);
                if($query_pharma==0){
                    $sql1="INSERT INTO care_pharma_prescription_info
                        (prescription_type,
                        dept_nr,
                        ward_nr,
                        date_time_create,
                        diagnosis,
                        history,
                        encounter_nr
                        )
                        VALUES
                        ('".$loaithanhtoan[1]."',
                        '".$dept_nr."',
                        '".$ward_nr."',
                        '".formatDate2STD($date_request,$date_format)."',
                        '".htmlspecialchars($clinical_info)."',
                        'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."',
                        '".$pn."')";
                }else{
                    $sql1="UPDATE care_pharma_prescription_info SET prescription_type='".$loaithanhtoan[1]."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$pn." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
                }
                //0-BHYT,1-sự nghiệp,2-CBTC
//                switch($loaithanhtoan[1]){
//                    case '0398':
//                    case '0400':
//                        $type_put=0;
//                        break;
//                    case '0397':
//                    case '0399':
//                    case '0401':
//                        $type_put=1;
//                        break;
//                    default:
//                        $type_put=2;
//                        break;
//                }
//                if($issue_pharma==0){
//                    $sql2="INSERT INTO care_pharma_issue_paper_info
//                        (dept_nr,
//                        ward_nr,
//                        type,
//                        typeput,
//                        date_time_create,
//                        note,
//                        history,
//                        issue_user)
//                        VALUES
//                        ('".$dept_nr."',
//                        '".$ward_nr."',
//                        '1',
//                        '".$type_put."',
//                        '".formatDate2STD($date_request,$date_format)."',
//                        '".htmlspecialchars($clinical_info)."',
//                        'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."',
//                        '".$_SESSION['sess_user_name']."')";
//                }else{
//                    $sql2="UPDATE care_pharma_issue_paper_info SET typeput='$type_put', date_time_create='".formatDate2STD($date_request,$date_format)."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND type=1 AND issue_user='$_SESSION[sess_user_name]'";
//                }
                if($query_med==0){
                    $sql3="INSERT INTO care_med_prescription_info
                        (prescription_type,
                        dept_nr,
                        ward_nr,
                        date_time_create,
                        diagnosis,
                        history,
                        encounter_nr
                        )
                        VALUES
                        ('".$loaithanhtoan[1]."',
                        '".$dept_nr."',
                        '".$ward_nr."',
                        '".formatDate2STD($date_request,$date_format)."',
                        '".htmlspecialchars($clinical_info)."',
                        'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."',
                        '".$pn."')";
                }else{
                    $sql3="UPDATE care_med_prescription_info SET prescription_type='".$loaithanhtoan[1]."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$pn." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
                }
//                if($issue_med==0){
//                    $sql4="INSERT INTO care_med_issue_paper_info
//                        (dept_nr,
//                        ward_nr,
//                        type,
//                        typeput,
//                        date_time_create,
//                        note,
//                        history,
//                        issue_user)
//                        VALUES
//                        ('".$dept_nr."',
//                        '".$ward_nr."',
//                        '1',
//                        '".$type_put."',
//                        '".formatDate2STD($date_request,$date_format)."',
//                        '".htmlspecialchars($clinical_info)."',
//                        'Create: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."',
//                        '".$_SESSION['sess_user_name']."')";
//                }else{
//                    $sql4="UPDATE care_med_issue_paper_info SET typeput='$type_put', date_time_create='".formatDate2STD($date_request,$date_format)."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND type=1 AND issue_user='$_SESSION[sess_user_name]'";
//                }
                if($ergebnis=$core->Transact($sql) && $ergebnis1=$core->Transact($sql1) /*&& $ergebnis2=$core->Transact($sql2)*/ && $ergebnis2=$core->Transact($sql3)/*&& $ergebnis2=$core->Transact($sql4)*/){
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql, date('Y-m-d H:i:s'));
                        $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql1, date('Y-m-d H:i:s'));
//                        $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql2, date('Y-m-d H:i:s'));
                        $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql3, date('Y-m-d H:i:s'));
//                        $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql4, date('Y-m-d H:i:s'));
                        include_once($root_path.'include/core/inc_visual_signalling_fx.php');
                        setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REQUEST);
                        header("location:".$root_path."modules/registration_admission/op_test_request_aftersave.php".URL_REDIRECT_APPEND."&edit=1&saved=insert&pn=$pn&status=pending&user_origin=op&target=or&noresize=1&batch_nr=$batch_nr&dept_nr=$dept_nr&ward_nr=$ward_nr");
                        exit;
                }else{
                    echo $sql1."<p>$LDDbNoSave</p>";
                        $mode="";
                }
                break; // end of case 'save'
            }
    case 'update':
        if($nguoimo=='Không chọn'){
            $nguoimo=$nguoimo1;
        }
        
        $sql="UPDATE care_test_request_".$db_request_table." SET
                        date_request='".formatDate2STD($date_request,$date_format)."',
                        clinical_info='".htmlspecialchars($clinical_info)."',
                        test_request='".htmlspecialchars($test_request)."',
                        send_doctor='".htmlspecialchars($referrer_name)."',
                        history=".$enc_obj->ConcatHistory("\n Update: ".date('Y-m-d H:i:s')." = ".$_SESSION['sess_user_name']."\n").",
                        modify_id='".$_SESSION['sess_user_name']."',
                        method_op='".htmlspecialchars($mo).' '.htmlspecialchars($te).' '.htmlspecialchars($me)."',
                        person_surgery='".htmlspecialchars($person_surgery)."',
                        level_method='".$level."'    
                    WHERE batch_nr='".$batch_nr."'";
        $loaithanhtoan=explode('-',$loaithanhtoan);
        //0-BHYT,1-sự nghiệp,2-CBTC
        switch($loaithanhtoan[1]){
            case '0398':
            case '0400':
                $type_put=0;
                break;
            case '0397':
            case '0399':
            case '0401':
                $type_put=1;
                break;
            default:
                $type_put=2;
                break;
        }        
        $sql1="UPDATE care_pharma_prescription_info SET prescription_type='".$loaithanhtoan[1]."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$pn." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
        $sql2="UPDATE care_med_prescription_info SET prescription_type='".$loaithanhtoan[1]."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$pn." AND date_time_create='".formatDate2STD($date_request,$date_format)."'";
//        $sql3="UPDATE care_pharma_issue_paper_info SET typeput='$type_put', date_time_create='".formatDate2STD($date_request,$date_format)."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND type=1 AND issue_user='$_SESSION[sess_user_name]'";
//        $sql4="UPDATE care_med_issue_paper_info SET typeput='$type_put', date_time_create='".formatDate2STD($date_request,$date_format)."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND type=1 AND issue_user='$_SESSION[sess_user_name]'";
        if($ergebnis=$core->Transact($sql) && $ergebnis1=$core->Transact($sql1) && $ergebnis2=$core->Transact($sql2) /*&& $ergebnis2=$core->Transact($sql3)&& $ergebnis2=$core->Transact($sql4)*/){
            //insert log
            $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql, date('Y-m-d H:i:s'));
            $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql1, date('Y-m-d H:i:s'));
            $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql2, date('Y-m-d H:i:s'));
//                        $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql3, date('Y-m-d H:i:s'));
//                        $logs->writeline_his($_SESSION['sess_login_userid'], $this_file, $sql4, date('Y-m-d H:i:s'));
            include_once($root_path.'include/core/inc_visual_signalling_fx.php');
            setEventSignalColor($pn,SIGNAL_COLOR_DIAGNOSTICS_REQUEST);
            header("location:".$root_path."modules/registration_admission/op_test_request_aftersave.php".URL_REDIRECT_APPEND."&edit=1&saved=insert&pn=$pn&status=pending&user_origin=op&target=or&noresize=1&batch_nr=$batch_nr&dept_nr=$dept_nr&ward_nr=$ward_nr");
            exit;
        }
        else
        {
            echo "<p>$LDDbNoSave</p>";
            $mode="";
        }
        break;

        case 'edit':
                $sql="SELECT * FROM care_test_request_".$db_request_table." WHERE batch_nr='".$batch_nr."' AND (status='pending' OR status='draft')";
                if($ergebnis=$db->Execute($sql))
                {
                    if($editable_rows=$ergebnis->RecordCount())
                    {
                        $stored_request=$ergebnis->FetchRow();
                        $date=$stored_request['date_request'];
                        $sql1="SELECT prescription_type AS prescription_type
                                FROM care_pharma_prescription_info AS pharma_prescription_info
                                WHERE dept_nr=$dept_nr AND encounter_nr=".$pn." AND date_time_create='$date'";
                        $ergebnis1=$db->Execute($sql1);
                        $stored_request1=$ergebnis1->FetchRow();
                        $edit_form=1;
                    }
                }
                $mode='update';
                break; ///* End of case 'edit': */
        default: $mode="";

    }// end of switch($mode)*/
    if(!$mode || $mode=='') /* Get a new batch number */
    {
        $sql="SELECT batch_nr FROM care_test_request_".$db_request_table." ORDER BY batch_nr DESC";
        if($ergebnis=$db->SelectLimit($sql,1))
        {
            if($batchrows=$ergebnis->RecordCount())
                {
                    $bnr=$ergebnis->FetchRow();
                    $batch_nr=$bnr['batch_nr'];
                    if(!$batch_nr) $batch_nr=_BATCH_NR_INIT_; else $batch_nr++;
                }
                else
                {
                    $batch_nr=_BATCH_NR_INIT_;
                }
        }else 
        {
            echo "<p>$sql<p>$LDDbNoRead";
        }
        $mode="save";   
    }
    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme
    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('nursing');

    # Title in toolbar
    $smarty->assign('sToolbarTitle', "$LDRequirement :: $formtitle");

    # hide back button
    $smarty->assign('pbBack',FALSE);

    # href for help button
    //$smarty->assign('pbHelp',"javascript:gethelp('notes_router.php','$notestype','".strtr($subtitle,' ','+')."','$mode','$rows')");

    # href for close button
    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('sWindowTitle',"$LDRequirement :: $formtitle");

    # Create start new button if user comes from lab


    # Collect extra javascript code
    require_once ('../../js/jscalendar/calendar.php');
    $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
    $calendar->load_files();
    ob_start();
?>

<style type="text/css">
    div.fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10;}
    div.fa2_ml10 {font-family: arial; font-size: 12; margin-left: 10;}
    div.fva2_ml3 {font-family: verdana; font-size: 12; margin-left: 3; }
    div.fa2_ml3 {font-family: arial; font-size: 12; margin-left: 3; }
    .fva2_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva2b_ml10 {font-family: verdana,arial; font-size: 12; margin-left: 10; color:#000000;}
    .fva0_ml10 {font-family: verdana,arial; font-size: 10; margin-left: 10; color:#000000;}
</style>
<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
<script language="javascript">
    //chỉ có tác dụng với thẻ text còn textarea và select thì không????
    function chkForm(d){
        if(!d.mo.checked && !d.te.checked && !d.me.checked)
        {
                alert("<?php echo $LDPlsEntermethodOp ?>");
                d.mo.focus();
                d.te.focus();
                d.me.focus();
                return false;
        }        
        else if((d.clinical_info.value=='')||(d.clinical_info.value==' '))
        {
                alert("<?php echo $LDPlsEnterDiagnosisQuiry ?>");
                d.clinical_info.focus();
                return false;
        }
        else if((d.test_request.value=='')||(d.test_request.value==' '))
        {
                alert("<?php echo $LDPlsEnterDiagnosisQuiry1 ?>");
                d.test_request.focus();
                return false;
        }
        else if((d.referrer_name.value=='')||(d.referrer_name.value==' '))
        {
                alert("<?php echo $LDPlsEnterDoctorName1 ?>");
                d.referrer_name.focus();
                return false;
        }
        else if((d.send_date.value=='')||(d.send_date.value==' '))
        {
                alert("<?php echo $LDPlsEnterDate ?>");
                d.send_date.focus();
                return false;
        }
        else if((d.creater.value=='')||(d.creater.value==' '))
        {
                alert("<?php echo $LDPlsEnterNurseName ?>");
                d.creater.focus();
                return false;
        }
        else if((d.nguoimo.value=='')||(d.nguoimo.value==' '))
        {
                alert("<?php echo $LDPlsEnterDoctorSurgery ?>");
                d.nguoimo.focus();
                return false;
        }else return true;
    }
    function printOut()
    {
            urlholder="<?php echo $root_path ?>modules/pdfmaker/emr_generic/report_op.php<?php echo URL_APPEND ?>&ses_en<?php echo $_SESSION['sess_full_en'] ?>&enc=<?php $pn ?>&recnr=<?php echo $recnr?>";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
            //testprintout<?php echo $sid ?>.print();
    }
    function popSearchPer(target,obj_val,obj_name,obj_id){
            urlholder="./personell_search_op.php<?php echo URL_REDIRECT_APPEND; ?>&target="+target+"&obj_val="+obj_val+"&obj_name="+obj_name+"&obj_id="+obj_id;
            DSWIN<?php echo $sid ?>=window.open(urlholder,"wblabel<?php echo $sid ?>","menubar=no,width=400,height=550,resizable=yes,scrollbars=yes");
    }
    function alertselected(selectobj){ 
        var number_use=selectobj.selectedIndex;
        switch(number_use){
            case 1:
                d=document.aufnahmeform;
                d.level.value='II';
                
                break;
            case 2:
                d=document.aufnahmeform;
                d.level.value='III';
                break;
            case '0397':
                d=document.aufnahmeform;
                d.loaithanhtoan.value='s-0397';
                break;
            case '0398':
                d=document.aufnahmeform;
                d.loaithanhtoan.value='s-0398';
                break;
        }    
    }
    
    $(function(){
        $("#f-calendar-field-1").mask("**/**/****");
    });
<?php require($root_path.'include/core/inc_checkdate_lang.php');
?>

</script>
<?php

$sTemp = ob_get_contents();

ob_end_clean();

$smarty->append('JavaScript',$sTemp);

ob_start();

?>

<ul>

<?php
if($edit){

?>
<form name="aufnahmeform" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">

<?php
/* If in edit mode display the control buttons */

$controls_table_width=700;

//require($root_path.'modules/laboratory/includes/inc_test_request_controls.php');

}
elseif(!$read_form && !$no_proc_assist)
{
?>

<table border=0>
  <tr>
    <td valign="bottom"><img <?php echo createComIcon($root_path,'angle_down_l.gif','0') ?>></td>
    <td><font color="#000099" SIZE=3  FACE="verdana,Arial"> <b><?php echo $LDPlsSelectPatientFirst ?></b></font></td>
    <td><img <?php echo createMascot($root_path,'mascot1_l.gif','0','absmiddle') ?>></td>
  </tr>
</table>
<?php
}
?>
   
   <!--  outermost table creating form border -->
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
    <tr>
        <td>
            <table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
                <tr>
                    <td>
                        <table  cellpadding=0 cellspacing=1 border=0 width=700>
                            <tr  valign="top">
                                <td  bgcolor="#ffffff" rowspan=2>
                                <?php
                                    if($edit)
                                    {
                                        echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
                                    }
                                ?>
                                </td>
                                <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10>
                                    <div  class=fva2_ml10>
                                        <font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
                                    </div>
                                <br>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="<?php echo $bgc1 ?>" align="right" valign="bottom">
                                <?php
                                    echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
                                    echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>";
                                ?>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td  valign="top" colspan=2 >
                                    <table border=0 cellpadding=1 cellspacing=1 width=100%>
                                        <tr>
                                            <td colspan=4><hr></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td>
                                    <div class=fva2_ml10>
                                        <font color="#000099">
                                            <b>
                                                <?php echo $LDClinicalInfo ?>:
                                            </b>
                                            <br>
                                        </font>
                                        <?php
                                            echo '<textarea name="clinical_info" cols=40 rows=4 wrap="physical">';
                                            if($edit_form || $read_form){
                                                if($stored_request['clinical_info']==''){
                                                    echo $clinical_info;
                                                }else{
                                                    echo stripslashes($stored_request['clinical_info']);
                                                }
                                            }
                                            echo '</textarea>';
                                        ?>
                                    </div>
                                </td>
                                <td valign='top'>
                                    <div class=fva2_ml10>
                                        <font color="#000099">
                                            <b>
                                            <?php 
                                                echo $LDSurgeryOP."&nbsp;";
                                                if($edit_form || $read_form){
                                                    if($stored_request['person_surgery']!=''){
                                                        echo $LDNote1.':&nbsp;'.stripslashes($stored_request['person_surgery']);
                                                        echo '<input type="hidden" name="nguoimo1" value="'.$stored_request['person_surgery'].'"/>';
                                                        echo '<br><br>'.$LDNote3.'<br>';
                                                    }
                                                }
                                            ?>
                                            </b>
                                        </font>
                                            <?php    
                                                //Ten nguoi mo
                                                echo '<input type="hidden" name="referrer_dr" id="referrer_dr" size=40 maxlength=40 value="'; 
                                                echo $referrer_dr;
                                                echo '">';
                                                echo '<input type="text" name="person_surgery" id="person_surgery" size=40 maxlength=40 value="';
                                                if($edit_form || $read_form){
                                                    if($stored_request['person_surgery']){
                                                        echo $stored_request['person_surgery'];
                                                    }else{
                                                        echo $person_surgery;
                                                    }
                                                }                                            
                                                echo '" readonly>';
                                                echo "<a href=\"javascript:popSearchPer('referrer_dr','','person_surgery','person_surgery')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
                                            ?>
                                    </div>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td>
                                    <div class=fva2_ml10>
                                        <font color="#000099">
                                            <b>
                                                <?php echo $LDReqTestOP ?>:
                                            </b>  
                                            <br>
                                        </font>
                                            <?php 
                                                echo '<textarea name="test_request" cols=40 rows=2 wrap="physical">';
                                                if($edit_form || $read_form){
                                                    if($stored_request['test_request']==''){
                                                        echo $test_request;
                                                    }else{
                                                        echo stripslashes($stored_request['test_request']);
                                                    }
                                                }
                                                echo '</textarea>'; 
                                            ?>
                                    </div>
                                </td>
                                <td valign='top'>
                                    <div class=fva2_ml10>
                                        <font color="#000099">
                                            <b>
                                                <?php echo $LDRequestor.":";?>
                                            </b>
                                            <br/>
                                        </font>
                                        <?php                                            
                                            echo '<input type="hidden" name="referrer_dr" id="referrer_dr" size=40 maxlength=40 value="'; 
                                            echo $referrer_dr;
                                            echo '">';
                                            echo '<input type="text" name="referrer_name" id="referrer_name" size=40 maxlength=40 value="';
                                            if($edit_form || $read_form){
                                                if($stored_request['send_doctor']){
                                                    echo $stored_request['send_doctor'];
                                                }else{
                                                    echo $referrer_name;
                                                }                                              
                                            }                                            
                                            echo '" readonly>';
                                            echo "<a href=\"javascript:popSearchPer('referrer_dr_1','','referrer_name','referrer_dr')\"><img ".createComIcon($root_path,'l-arrowgrnlrg.gif','0','',TRUE)."></a>";
                                        ?>
                                    </div>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class=fva2_ml10 align="left">
                                        <font color="#000099">
                                        <?php                                            
                                            if(($edit_form || $read_form)){
                                                if($stored_request['level_method']!=''){
                                                    echo '<b>'.$LDLevelMethodOP."&nbsp;";
                                                    echo $LDNote1.':&nbsp;<font color="darkred">'.$stored_request['level_method'].'</font>';
                                                    echo '<input type="hidden" name="level" value="'.$stored_request['level_method'].'"/>';
                                                    echo '<br>'.$LDNote.'</b><br>';
                                                }else{
                                                    echo '<font size="3" color="red">'.$LDWarninglevel.'</font><br>';
                                                    echo '<input type="hidden" name="level" value=""/>';
                                                }
                                            }else{
                                                echo '<b>'.$LDLevelMethodOP."&nbsp;</b>";
                                                echo '<input type="hidden" name="level" value=""/>';
                                            }
                                        ?>
                                        </font>
                                        <select name="loaimo" onChange="alertselected(this)">
                                        <?php                                            
                                            for($i=0;$i<=2;$i++)
                                            {   
                                                switch ($i){
                                                    case 1:
                                                        echo'<option value="'.$i.'">'.II.'</option>';
                                                        break;
                                                    case 2:
                                                        echo'<option value="'.$i.'">'.III.'</option>';
                                                        break;
                                                    default:
                                                        echo'<option value="'.$i.'">Chọn loại</option>';
                                                        break;
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class=fva2_ml10 align="left">
                                        <font color="#000099">
                                        <?php
                                            echo '<b>'.$LDMethodOP."&nbsp;</b>";
                                            if($edit_form || $read_form){
                                                if($stored_request['method_op']==''){
                                                    if($mo!=''){
                                                        echo '<label><input type="checkbox" name="mo" value="Mổ" checked="checked">Mổ,</label>';
                                                    }else{
                                                        echo '<label><input type="checkbox" name="mo" value="Mổ">Mổ,</label>';
                                                    }
                                                    if($te!=''){
                                                        echo '<label><input type="checkbox" name="te" value="Tê"  checked="checked"checked>Tê,</label>';
                                                    }else{
                                                        echo '<label><input type="checkbox" name="te" value="Tê">Tê, </label>';
                                                    }
                                                    if($me!=''){
                                                        echo '<label><input type="checkbox" name="me" value="Mê" checked="checked">Mê</label>';
                                                    }else{
                                                        echo '<label><input type="checkbox" name="me" value="Mê">Mê</label>';
                                                    }
                                                }else{
                                                    $method=explode(' ',$stored_request['method_op']);
                                                    echo '<label><input type="checkbox" name="mo" value="';
                                                    for($i=0; $i<sizeof($method); $i++){
                                                        if($method[$i]=='Mổ'){
                                                            echo 'Mổ"  checked="checked" />Mổ,</label>';
                                                            break;
                                                        }
                                                        if(($i=sizeof($method)-1) && $method[$i]!= 'Mổ'){
                                                            echo 'Mổ" />Mổ,</label>';
                                                            break;
                                                        }
                                                    }
                                                    echo '<label><input type="checkbox" name="te" value="';
                                                    for($j=0; $j<sizeof($method); $j++){
                                                        if($method[$j]=='Tê'){
                                                            echo 'Tê"  checked="checked" />Tê,</label>';
                                                            break;
                                                        }
                                                        if(($j==sizeof($method)-1) && $method[$j]!= 'Tê'){
                                                            echo 'Tê" />Tê,</label>';
                                                            break;
                                                        }
                                                    }
                                                    echo '<label><input type="checkbox" name="me" value="';
                                                    for($k=0; $k<sizeof($method); $k++){
                                                        if($method[$k]=='Mê'){
                                                            echo 'Mê"  checked="checked" />Mê,</label>';
                                                            break;
                                                        }
                                                        if(($k==sizeof($method)-1) && $method[$k]!= 'Mê'){
                                                            echo 'Mê" />Mê</label>';
                                                            break;
                                                        }
                                                    }
                                                }
                                            }else{
                                        ?>
                                                </font>
                                                <label><input type="checkbox" name="mo" value="Mổ">Mổ,</label>
                                                <label><input type="checkbox" name="te" value="Tê">Tê,</label>
                                                <label><input type="checkbox" name="me" value="Mê">Mê</label>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $bgc1 ?>">
                                <td colspan=2 align="right">
                                    <div class=fva2_ml10>
                                        <font color="#000099">
                                        <?php 
                                            echo "<br/><b>".$LDDate .":</b>";
                                            //gjergji : new calendar                                            
                                            if($date_request==''){
                                                echo $calendar->show_calendar($calendar,$date_format,'date_request',$stored_request['date_request']);
                                            }else{
                                                $date=explode('/',$date_request);
                                                echo $calendar->show_calendar($calendar,$date_format,'date_request',$date[2].'-'.$date[1].'-'.$date[0]);
                                            }
                                            echo '<b>'.$LDCreater.'</b>';
                                            //require_once($root_path.'include/care_api_classes/class_oproom.php');
                                        ?>:
                                        </font>
                                        <?php
                                            echo '<input type="text" name="creater" size=40 maxlength=40 value="';
                                                if($edit_form || $read_form){
                                                    if($stored_request['send_doctor']!=''){
                                                        echo $stored_request['create_id'];
                                                    }else{
                                                        echo $_SESSION['sess_user_name'];
                                                    }
                                                }else{
                                                    echo $_SESSION['sess_user_name'];
                                                } 
                                            echo '">';
                                        ?>
                                    </div>
                                    <br>                                    
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<p>

<?php
if($edit)
{
    echo '<input type="image"'.createLDImgSrc($root_path,'abschic.gif').'alt="'.$LDSend.' />';
    require($root_path.'modules/laboratory/includes/inc_test_request_hiddenvars.php');
?>
    <input type="hidden" name="flag1" value="<?php $flag1;?>" />
    <input type="hidden" name=dept_nr" value="<?php $dept_nr;?>" />
    <input type="hidden" name="ward_nr" value="<?php $ward_nr;?>" />
</form>

<?php
}
?>

</ul>

<?php

$sTemp2 = ob_get_contents();
ob_end_clean();

# Assign to page template object
$smarty->assign('sMainFrameBlockData',$sTemp2);

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

 ?>
