<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    
    $lang_tables[]='departments.php';
    $lang_tables[]='or.php';
    define('LANG_FILE','konsil.php');

    // Edit 22/11-Huỳnh ////
    if($user_origin=='op_done'){
        $local_user='ck_opdoku_user';
        $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
    }  elseif ($user_origin=='change') {
        $local_user='ck_opdoku_user';
        $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
    }
    ////edit 27/11-Huỳnh /////////////
    /// điều kiện cho nút "lập hồ sơ mổ"
    elseif($user_origin=='surgery'){
		$local_user='ck_opdoku_user';
		header('Location:'.$root_path.'modules/op_document/op-doku-start_1.php'.URL_APPEND.'&mode=select&pn='.$pn.'&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&target=entry');
		exit;
    }
    ////////////////////////////////////
    require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/

    /*require_once($root_path.'include/care_api_classes/class_access.php');
    $access= & new Access();
    $role= $access->checkNameRole($_SESSION['sess_user_name']);
    if($_SESSION['sess_user_name']!='admin'){
        $role= $access->checkNameRole($_SESSION['sess_user_name']);
        if(strpos($role['role_name'], 'Trưởng khoa')=='' && strpos($role['role_name'], 'Điều dưỡng trưởng')=='' && strpos($role['role_name'], 'Điều dưỡng hành chính')==''){
            header("Location:../../language/".$lang."/lang_".$lang."_invalid-access-warning.php"); 
            exit;
        }
    }*/
    require_once($root_path.'global_conf/inc_global_address.php');
    
    //Ghi log
    require_once($root_path.'include/core/access_log.php');
    $logs = new AccessLog();

    $thisfile= basename(__FILE__);

    $bgc1='#ffffff'; /* The main background color of the form */
    $edit_form=0; /* Set form to non-editable*/
    $read_form=1; /* Set form to read */
    $edit=0; /* Set script mode to no edit*/

    $formtitle=$LDTestRequestOP;

    //$db_request_table=$subtarget;
    $db_request_table='or';
    
    //$db->debug=1;

    /* Here begins the real work */
    require_once($root_path.'include/core/inc_date_format_functions.php');

    require_once ($root_path . 'include/care_api_classes/class_encounter.php');
    $enc_obj = new Encounter ( );

    if(!isset($mode))   $mode='';
    
    switch($mode){	
        case 'done' :
            //status=draff dang trong giai doan phan ra phòng mổ nào
                $sql = "UPDATE care_test_request_" . $subtarget . "
                                SET status = 'done',
                                                history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . ",
                                                modify_id = '" . $_SESSION ['sess_user_name'] . "'
                                WHERE batch_nr = '" . $batch_nr . "'";
                if ($ergebnis = $enc_obj->Transact($sql )) {
                    /// edit 29/11-Huỳnh/////
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                    $sql1="SELECT op_room FROM care_op_med_doc WHERE  encounter_nr='$pn' AND status='pending'";
                    if($op_room=$db->Execute($sql1)){
                        $room_now=$op_room->FetchRow();
                        $temp=$room_now['op_room'];
                    }
                    require_once($root_path.'include/care_api_classes/class_oproom.php');
                    # Create the OR object
                    $OR_obj=& new OPRoom;
                    $sql2="UPDATE care_op_med_doc SET
                                     status = 'done',
                                                history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . ",
                                                modify_id = '" . $_SESSION ['sess_user_name'] . "'
                                    WHERE encounter_nr='$pn' AND status='pending'";
                    $sql3="UPDATE care_encounter_op SET 
                                    status = 'done',
                                    history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . "
                                    WHERE batch_nr = '" . $batch_nr . "' AND status='pending'";
                    if($status=$enc_obj->Transact($sql3)){
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                    }
                    if($status=$enc_obj->Transact($sql2)){
                        $oid=$db->Insert_ID();
                        $docstatus->coretable='care_op_med_doc';
                        $status=$enc_obj->LastInsertPK('nr',$oid);
                        //insert log
                        $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                    }
                    /////////////////////////
                    header ( "location:" . $thisfile . URL_REDIRECT_APPEND . "&subtarget=or&dept_nr=$dept_nr&ward_nr=$ward_nr&noresize=1&user_origin=op_done&checkintern=1" );
                    exit ();
                } else {
                        echo "<p>$sql<p>$LDDbNoSave";
                        $mode = "";
                }
                break;
        //Trường hợp hủy e-kip
        case 'cancel':
            $sql = "UPDATE care_test_request_" . $subtarget . "
                                SET status = 'cancel',
                                                history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . ",
                                                modify_id = '" . $_SESSION ['sess_user_name'] . "'
                                WHERE batch_nr = '" . $batch_nr . "'";
            if ($ergebnis = $enc_obj->Transact($sql )) {
                //insert log
                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                $sql1="SELECT nr FROM care_op_med_doc WHERE  encounter_nr='$pn' AND status='pending'";
                    $op_room=$db->Execute($sql1);
                    if($op_room){  
                        $sql2="UPDATE care_op_med_doc SET
                                     status = 'cancel',
                                                history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . ",
                                                modify_id = '" . $_SESSION ['sess_user_name'] . "'
                                    WHERE encounter_nr='$pn' AND status='pending'";
                        if($status=$enc_obj->Transact($sql2)){
                            $oid=$db->Insert_ID();
                            $docstatus->coretable='care_op_med_doc';
                            $status=$enc_obj->LastInsertPK('nr',$oid);
                            //insert log
                            $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                        }
                    }                
                    $sql3="UPDATE care_encounter_op SET 
                                    status = 'cancel',
                                    history=" . $enc_obj->ConcatHistory ( "Done: " . date ( 'Y-m-d H:i:s' ) . " = " . $_SESSION ['sess_user_name'] . "\n" ) . "
                                    WHERE batch_nr = '" . $batch_nr . "' AND status='pending'";
                    if(!$status=$enc_obj->Transact($sql3)){
                        echo "<p>$sql<p>$LDDbNoSave";
                    }
                    //insert log
                    $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $enc_obj->getLastQuery(), date('Y-m-d H:i:s'));
                    $sql="SELECT medical_codedlist,material_codedlist FROM care_encounter_op
                            WHERE batch_nr='$batch_nr'";
                    if($ergebnis=$db->Execute($sql)){
                        $rows=$ergebnis->Recordcount();
                        if(isset($rows)){
                            $result=$ergebnis->FetchRow();    
                            require_once($root_path.'include/care_api_classes/class_med_dept.php');
                            $med=new Med_Dept;                        
                            $elem_med=explode("~",$result['medical_codedlist']); 
                            $prescription_id=$med->getInfoMedPrescription($batch_nr,$dept_nr,$ward_nr);
                            require_once($root_path.'include/care_api_classes/class_pharma_dept.php');
                            $pharma=new Pharma_Dept();
                            $elem_pharma=explode("~",$result['material_codedlist']); 
                            $prescription_pharma_id=$pharma->getInfoPrescription($batch_nr,$dept_nr,$ward_nr);
                            $sql_tempt="SELECT * FROM care_test_request_or WHERE batch_nr='".$batch_nr."'";
                            if($temp=$db->Execute($sql_tempt)){
                                $temp_1=$temp->FetchRow();
                            }                            
                            for($i=1;$i<=sizeof($elem_med);$i++){
                                if($elem_med[$i]==''){
                                    continue;                                    
                                }
                                $location=explode("#",$elem_med[$i]);
                                $product_encoder=explode("n=",$location[0]);
                                $product_encoder=explode("&u=", $product_encoder[1]);
                                $number_use=explode("+",$location[1]);
                                $detail=$med->getMedInfoDetail($product_encoder[0],$dept_nr,$ward_nr);
                                //echo $number_use[0].'-'.$number_use[1].'-'.$detail['price'].'<br/>';
                                $edit_number=$med->UneditNumberMedInDept($product_encoder[0],'',$prescription_id['prescription_id'],$prescription_id['encounter_nr'],$dept_nr,$ward_nr,$number_use[0],$number_use[1],$detail['price']);
                                //insert log
                                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $med->getLastQuery(), date('Y-m-d H:i:s'));
                                $sql="UPDATE care_med_prescription_info SET total_cost='".($prescription_id[total_cost]-($number_use[1]*$detail['price']))."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$prescription_id['encounter_nr']." AND date_time_create='".$temp_1[date_request]."'";
                                if(!$ergebnis=$db->Execute($sql)){
                                    echo $sql."$LDDbNoSave<br>";
                                }
                                //insert log
                                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));
                            }                            
                            for($t=1;$t<=sizeof($elem_pharma);$t++){
                                if($elem_pharma[$t]==''){                                     
                                    continue;                                    
                                }
                                $location=explode("#",$elem_pharma[$t]);
                                $product_encoder=explode("n=",$location[0]);
                                $product_encoder=explode("&u=", $product_encoder[1]);
                                $number_use=explode("+",$location[1]);
                                $detail=$pharma->getPharmaInfoDetail($product_encoder[0],$dept_nr,$ward_nr);
                                //echo $number_use[0].'-'.$number_use[1].'-'.$detail['price'].'<br/>';
                                $edit_number=$pharma->UneditNumberPharmaInDept($product_encoder[0],'',$prescription_pharma_id['prescription_id'],$prescription_pharma_id['encounter_nr'],$dept_nr,$ward_nr,$number_use[0],$number_use[1],$detail['price']);
                                //insert log
                                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $pharma->getLastQuery(), date('Y-m-d H:i:s'));
                                $sql="UPDATE care_pharma_prescription_info SET total_cost='".($prescription_id[total_cost]-($number_use[1]*$detail['price']))."' WHERE dept_nr='".$dept_nr."' AND ward_nr='".$ward_nr."' AND encounter_nr=".$prescription_id['encounter_nr']." AND date_time_create='".$temp_1[date_request]."'";
                                if(!$ergebnis=$db->Execute($sql)){
                                    echo $sql."$LDDbNoSave<br>";
                                }
                                //insert log
                                $logs->writeline_his($_SESSION['sess_login_userid'], $thisfile, $sql, date('Y-m-d H:i:s'));
                            }
                        }                        
                    }else{
                        echo "$LDDbNoRead<br>";
                    }
            } else {
                echo "<p>$sql<p>$LDDbNoSave";
                $mode = "";
            }            
            header ( "location:" . $thisfile . URL_REDIRECT_APPEND . "&subtarget=or&dept_nr=$dept_nr&ward_nr=$ward_nr&noresize=1&user_origin=op_done&checkintern=1" );
            exit ();
        default: 
            $mode='';
            break;
    }// end of switch($mode)

    $pid='';
    /* Get the pending test requests */
if(!$mode) {
        $sql="SELECT batch_nr,encounter_nr,send_date,date_request FROM care_test_request_".$db_request_table."
                                WHERE status='draff' OR status='received' ORDER BY  date_request DESC";
        if($requests=$db->Execute($sql)){
            $batchrows=$requests->RecordCount();
            if($batchrows && (!isset($batch_nr) || !$batch_nr)){
                    $test_request=$requests->FetchRow();
                        /* Check for the patietn number = $pn. If available get the patients data */
                    $pn=$test_request['encounter_nr'];                      
                    
                    $sql1="SELECT pid, discharged_type FROM care_encounter WHERE encounter_nr=$pn";
                    $requests1=$db->Execute($sql1);
                    $test_request1=$requests1->FetchRow();
                    $pid=$test_request1['pid'];
                    $discharged_type=$test_request1['discharged_type'];
                    $batch_nr=$test_request['batch_nr'];                
                if($flag==1){
                    $pn=$enc_nr;
                }
            }
        }else{
                echo "<p>$sql<p>$LDDbNoRead";
                exit;
        }
        $mode='update';
    }

    /* Check for the patient number = $pn. If available get the patients data */
    if($batchrows && $pn){
        include_once($root_path.'include/care_api_classes/class_encounter.php');
        $enc_obj=new Encounter;
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

                        $sql="SELECT * FROM care_test_request_".$db_request_table." WHERE batch_nr='".$batch_nr."'";
                        if($ergebnis=$db->Execute($sql)){
                                if($editable_rows=$ergebnis->RecordCount()){
                                        $stored_request=$ergebnis->FetchRow();
                                        $edit_form=1;
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
    $sql_tempt="SELECT enc_op.material_codedlist,enc_op.medical_codedlist, test_req.date_request
                        FROM care_encounter_op AS enc_op, care_test_request_or AS test_req
                        WHERE enc_op.batch_nr='$batch_nr' AND test_req.batch_nr='$batch_nr'";
    if($check=$db->Execute($sql_tempt)){
        $info_op=$check->FetchRow();
        $elem=explode("~",$info_op[material_codedlist]);
        $date_request=$info_op['date_request'];        
        $i=0;
        while($i<sizeof($elem)){
            $pharma=explode("&x",$elem[$i]);
            $cut_pharma=explode("n=",$pharma[0]);
            $encoder=  explode("&u=", $cut_pharma[1]);
            if($encoder[0]!='' && $encoder[1]!=0){
                $sql_solo="SELECT product_lot_id 
                    FROM care_pharma_available_product
                    WHERE product_encoder='$encoder[0]'";
                $execute_solo=$db->Execute($sql_solo);
                $query_solo=$execute_solo->FetchRow();
                //$sql_pharma="INSERT care_pharma_department_archive SET dept_nr=$dept_nr, ward_nr=$ward_nr, product_encoder='$encoder[0]', product_lot_id='$query_solo[product_lot_id]', get_use=0, number=$encoder[1], pres_id=$encoder[1], at_date_time='$date_request', user='$_SESSION[sess_login_username]'";
                //$execute_pharma=$db->Execute($sql_pharma);
            }
            $i++;
        }
        $elem1=explode("~",$info_op[medical_codedlist]); 
        $i=0;
        while($i<sizeof($elem1)){
            $med=explode("&x",$elem1[$i]);
            $cut_med=explode("n=",$med[0]);
            $encoder1=  explode("&u=", $cut_med[1]);
            if($encoder1[0]!='' && $encoder1[1]!=0){
                $sql_solo1="SELECT product_lot_id 
                    FROM care_med_available_product
                    WHERE product_encoder='$encoder1[0]'";
                $execute_solo1=$db->Execute($sql_solo1);
                $query_solo1=$execute_solo1->FetchRow();
                //$sql_med="INSERT care_med_department_archive SET dept_nr=$dept_nr, ward_nr=$ward_nr, product_encoder='$encoder1[0]', product_lot_id='$query_solo1[product_lot_id]', get_use=0, number=$encoder1[1], pres_id=$encoder1[1], at_date_time='$date_request', user='$_SESSION[sess_login_username]'";
                //$execute_pharma=$db->Execute($sql_med);
            }
            $i++;
        }        
    }
    # Prepare title
    $sTitle = $LDPendingTestRequest;
    if($batchrows) $sTitle = $sTitle." (".$batch_nr.")";
    # Start Smarty templating here
    /**
    * LOAD Smarty
    */
    # Note: it is advisable to load this after the inc_front_chain_lang.php so
    # that the smarty script can use the user configured template theme
    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('nursing');
    # Title in toolbar
    $smarty->assign('sToolbarTitle',$sTitle);
    # hide back button
    $smarty->assign('pbBack',FALSE);
    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('pending_radio.php')");
    # href for close button
    $smarty->assign('breakfile',$breakfile);
    # Window bar title
    $smarty->assign('sWindowTitle',$sTitle);
    $smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus();"');
    # Collect extra javascript code
    ob_start();
?>
<meta http-equiv='refresh' content='20'>
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
    <?php require($root_path.'include/core/inc_checkdate_lang.php'); ?>
</script>
<?php

    $sTemp = ob_get_contents();

    ob_end_clean();

    $smarty->append('JavaScript',$sTemp);

    ob_start();

    if($batchrows){

?>
<!-- Table for the list index and the form -->
<table border=0>
    <tr valign="top">
        <td>
            <?php
                /* The following routine creates the list of pending requests */
                //Hiện danh sách theo thứ tự ngày
                require($root_path.'modules/laboratory/includes/inc_test_request_lister_fx.php');       
            ?>
        </td>
        <td>
            <form name="form_test_request" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">
                <!-- edit 21/11-Huỳnh -->
                <a href="<?php echo $thisfile.URL_REDIRECT_APPEND.'&subtarget=or&pn='.$pn.'&batch_nr='.$batch_nr.'&user_origin=surgery&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr; ?>">
                    <img <?php echo createLDImgSrc($root_path,'newplanop.gif','0') ?> alt="<?php echo $LDrecordSurgery ?>"/>
                </a>                
                <a href="<?php echo $thisfile.URL_APPEND."&edit=".$edit."&mode=done&target=".$target."&subtarget=or&batch_nr=".$batch_nr."&pn=".$pn."&formtitle=".$formtitle . "&user_origin=" . $user_origin . "&noresize=" . $noresize.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;?>">
                    <img <?php echo createLDImgSrc ( $root_path, 'done.gif', '0' )?> alt="<?php echo $LDDone?>"/>
                </a>                
                <a href="<?php echo $thisfile.URL_APPEND."&edit=".$edit."&mode=cancel&target=".$target."&subtarget=or&batch_nr=".$batch_nr."&pn=".$pn."&formtitle=".$formtitle . "&user_origin=" . $user_origin . "&noresize=" . $noresize.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr;?>">
                    <img <?php echo createLDImgSrc ( $root_path, 'delete.gif', '0' )?> />
                </a>              
                <!--  outermost table creating form border -->
                <table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
                    <tr>
                        <td>
                            <table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
                                <tr>
                                    <td>
                                        <table   cellpadding=0 cellspacing=1 border=0 width=700>
                                            <tr  valign="top">
                                                <td  bgcolor="<?php echo $bgc1 ?>" rowspan=2>
                                                    <?php
                                                        if($edit || $read_form)
                                                        {
                                                            echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
                                                        }
                                                    ?>
                                                </td>
                                                <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10>
                                                    <div   class=fva2_ml10>
                                                        <font size=5 color="#0000ff">
                                                            <b><?php echo $formtitle ?></b>
                                                        </font>
                                                    </div>
                                                <br/>
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
                                                    &nbsp;<br/>
                                                </td>
                                            </tr>
                                            <tr bgcolor="<?php echo $bgc1 ?>">
                                                <td >
                                                    <div class=fva2_ml10>
                                                        <?php echo $LDClinicalInfo ?>:
                                                        <p>
                                                            <img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left"/>
                                                        </p>
                                                    <font face="courier" size=2 color="#000099">&nbsp;&nbsp;
                                                        <?php echo stripslashes($stored_request['clinical_info']) ?>
                                                    </font>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=fva2_ml10><?php echo "$LDDocOP";?>:
                                                        <p>
                                                            <img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left"/>
                                                        </p>
                                                        <font face="courier" size=2 color="#000099">&nbsp;&nbsp;
                                                            <?php if($edit_form || $read_form) echo stripslashes($stored_request['person_surgery']) ?>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>	
                                            <tr bgcolor="<?php echo $bgc1 ?>">
                                                <td>
                                                    <div class=fva2_ml10>
                                                        <?php echo $LDReqTestOP ?>:
                                                        <p>
                                                            <img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left"/>
                                                        </p>
                                                        <font face="courier" size=2 color="#000099">&nbsp;&nbsp;
                                                            <?php echo stripslashes($stored_request['test_request']) ?>
                                                        </font>
                                                    </div>
                                                </td>
                                                <td align="left">
                                                    <div class=fva2_ml10>
                                                        <?php echo $LDMethodOP;?>:
                                                        <p>
                                                            <img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left"/>
                                                        </p>
                                                        <font face="courier" size=2 color="#000099">&nbsp;&nbsp;
                                                            <?php if($edit_form || $read_form){
                                                                    echo stripslashes($stored_request['method_op']);
                                                                  }else{
                                                                    echo $LDNO1;
                                                                  }
                                                            ?>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr bgcolor="<?php echo $bgc1 ?>">
                                                <td colspan=2 align="right"><div class=fva2_ml10>
                                                    <?php echo $LDDate ?>:
                                                    <font face="courier" size=2 color="#000000">&nbsp;
                                                        <?php 
                                                            echo formatDate2Local($stored_request['date_request'],$date_format); 
                                                        ?>
                                                    </font>&nbsp;&nbsp;&nbsp;
                                                    <?php echo $LDRequestingDoc ?>:
                                                <font face="courier" size=2 color="#000000">&nbsp;<?php echo $stored_request['send_doctor'] ?></font>&nbsp;&nbsp;&nbsp;&nbsp;</div><br>
                                                </td>
                                            </tr>	
                                            <tr bgcolor="<?php echo $bgc1 ?>"></tr>
                                        </table> 
                                    </td>
                                </tr>
                            </table>
                        </td>
<!--                        <td bgcolor="#ffffff" valign="top">
                            <a href="<?php echo $thisfile.URL_APPEND."&edit=".$edit."&mode=move&target=".$target."&subtarget=or&batch_nr=".$batch_nr."&pn=".$pn."&formtitle=".$formtitle . "&user_origin=" . $user_origin . "&noresize=" . $noresize; ?>">
                                <img <?php echo createLDImgSrc($root_path,'transfer_sm.gif','0') ?> alt="<?php echo $LDrecordEntry ?>"/>
                            </a>
                        </td>-->
                    </tr>
                </table>                 
            </form>
        </td>
    </tr>
</table>

<?php
    }
    else
    {
?>
    <img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?> align="absmiddle"/>
    <font size=3 face="verdana,arial" color="#990000">
        <b><?php echo $LDNoPendingRequest ?></b>
    </font>
    <p>
        <a href="<?php echo $breakfile ?>">
            <img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>/>
        </a>
    </p>
<?php
    }
    $sTemp = ob_get_contents();
    ob_end_clean();
    # Assign to page template object
    $smarty->assign('sMainFrameBlockData',$sTemp);
     /**
     * show Template
     */
     $smarty->display('common/mainframe.tpl');
 ?>
