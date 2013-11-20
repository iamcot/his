<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    $lang_tables[]='departments.php';
    $lang_tables[]='or.php';
    define('LANG_FILE','konsil.php');

    // Edit 22/11-Huỳnh ////
    if($user_origin=='op_e_kip'){
        $local_user='ck_opdoku_user';
        $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
    }
    require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/
//    require_once($root_path.'include/care_api_classes/class_access.php');
//    $access= & new Access();
//    $role= $access->checkNameRole($_SESSION['sess_user_name']);

    require_once($root_path.'global_conf/inc_global_address.php');

    $thisfile= basename(__FILE__);

    $bgc1='#ffffff'; /* The main background color of the form */
    $edit_form=0; /* Set form to non-editable*/
    $read_form=1; /* Set form to read */
    $edit=0; /* Set script mode to no edit*/

    $formtitle=$LDEkipMo;

    require_once($root_path.'include/core/inc_date_format_functions.php');

    require_once ($root_path . 'include/care_api_classes/class_encounter.php');
    $enc_obj = new Encounter ( );
    require_once ($root_path . 'include/care_api_classes/class_encounter_op.php');
    $enc_op_obj = new OPEncounter ( );
    require_once ($root_path . 'include/care_api_classes/class_pharma_dept.php');
    $pharma = new Pharma_Dept();
    require_once($root_path.'include/care_api_classes/class_med_dept.php');
    $med=new Med_Dept();
    
    if(!isset($mode))   $mode='';

    $pid='';
/* Get the pending test requests */
    if(!$mode || $mode=='edit') {
        $requests=$enc_op_obj->test_ekip('');
        if($requests){
            $batchrows=$requests->RecordCount();
            if($batchrows && (!isset($batch_nr) || !$batch_nr)){
                    $test_request=$requests->FetchRow();
                        /* Check for the patietn number = $pn. If available get the patients data */
                    $pn=$test_request['encounter_nr'];                        
                    $batch_nr=$test_request['batch_nr'];
                    $diagnosis=$test_request['clinical_info'];
                    $operator=$test_request['person_surgery'];
                    $test_request_or=$test_request['test_request'];
                    $op_date=$test_request['date_request'];
//                    $sql1="SELECT pid FROM care_encounter WHERE encounter_nr=$pn";
//                    $requests1=$db->Execute($sql1);
                    $requests1=$enc_op_obj->serch_pid($pn);
                    $test_request1=$requests1->FetchRow();
                    $pid=$test_request1['pid'];
                    $batch_nr=$test_request['batch_nr'];                
                if($flag==1){
                    $pn=$enc_nr;
                }
            }
            if($batchrows && (isset($batch_nr) || $batch_nr)){
                if(isset($enc_nr)){
                    $pn=$enc_nr;
                }else{
                    $requests1=$enc_op_obj->test_ekip($batch_nr);
                    $test_request=$requests1->FetchRow();
                    /* Check for the patient number = $pn. If available get the patients data */
                    $pn=$test_request['encounter_nr'];
                    $diagnosis=$test_request['clinical_info'];
                    $operator=$test_request['person_surgery'];
                    $test_request_or=$test_request['test_request'];
                    $op_date=$test_request['date_request'];
                }
//                $sql1="SELECT pid FROM care_encounter WHERE encounter_nr=$pn";
//                $requests1=$db->Execute($sql1);
                $requests1=$enc_op_obj->serch_pid($pn);
                $test_request1=$requests1->FetchRow();
                $pid=$test_request1['pid'];
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
                           break;
            }
            if( $enc_obj->is_loaded){
                $result=&$enc_obj->encounter;
                if($ergebnis=$enc_op_obj->getInfo($batch_nr)){
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
    # Prepare title
    $sTitle = $LDPendingTestRequest;
    if($batchrows) $sTitle = $sTitle." (".$batch_nr.")";

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
                require($root_path.'modules/laboratory/includes/inc_test_request_lister_fx.php');       
            ?>
        </td>
        <td>
            <form name="form_test_request" method="post" action="<?php echo $thisfile ?>" onSubmit="return chkForm(this)">
                <?php
                    $temp= $enc_op_obj->getInfo($batch_nr,'done');
                    $status=$temp->RecordCount();
//                    if((strpos($role['role_name'], 'Trưởng khoa')!='' || strpos($role['role_name'], 'Trưởng khoa')!=0) || $_SESSION['sess_user_name']=='admin' && $status<1){
                ?>
                        <a href="<?php echo $root_path.'modules/or_logbook/op-pflege-logbuch-pass.php'.URL_REDIRECT_APPEND.'&target=e_kip&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr.'&enc_nr='.$pn.'&mode=edit';?>">
                            <img <?php echo createLDImgSrc($root_path,'edit_sm.gif','0') ?> alt="<?php echo $LDrecordSurgery ?>"/>
                        </a>
                <?php                 
//                } 
                ?>
                <table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
                    <tr>
                        <td>
                        <table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
                            <tr>
                                <td>
                                    <table  cellpadding=0 cellspacing=1 border=0 width=800px>
                                        <tr  valign="top">
                                            <td  bgcolor="<?php echo $bgc1 ?>" rowspan=2>
                                                <?php
                                                if($edit || $read_form)
                                                {
                                                    echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
                                                }
                                                ?>
                                            </td>                                            
                                        </tr>
                                    <tr>
                                        <td bgcolor="<?php echo $bgc1 ?>" class=fva2_ml10 valign="top" width=360>
                                            <div class=fva2_ml10>
                                                <font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
                                            </div>
                                            </br>
                                        </td>
                                        <td bgcolor="<?php echo $bgc1 ?>" align="center" valign="bottom">
                                            <?php
                                                //Lấy ngày mổ từ phiếu yêu cầu mổ
                                                $date=$enc_op_obj->getInfoTest($batch_nr,'draff');
                                                $date_request=$date->FetchRow();
                                                $op_date=$date_request['date_request'];
                                                $level_method=$date_request['level_method'];                                                
                                                echo '</br>';
                                                echo '<font color="blue"><b>'.$LDLevelMethodOP.": ".$level_method.'</b></font>';
                                                echo '</br>';
                                                echo '<font color="blue"><b>'.$LDOpDate.": ".formatDate2Local($op_date, $date_format).'</b></font>';
                                                echo '</br>';
                                                echo '<font color="blue"><b>'.$LDOpStart1.": ".$stored_request['doc_time'].'</b></font>';
                                            ?>
                                            </p>
                                            <?php
                                            echo '<font color="#990000" face="verdana,arial"><b>'.$LDNr.' '.'</b></font>';
                                            echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
                                            echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=140&height=40&xres=2&font=5' border=0>";
                                            ?>
                                        </td>
                                    </tr>
                                    <tr bgcolor="<?php echo $bgc1 ?>">
                                        <table rules="all" width="100%">
                                            <?php
                                                $ebuf=array("assistant","scrub_nurse","rotating_nurse", "anesthesia", "an_doctor");
                                                $jbuf=array("operator", "assist", "scrub", "rotating", "ana", "ana_assist");
                                                $function=array(4,12,10,7,5,8);
                                                for($i=0;$i<6;$i++){
                                                    $personell=$enc_op_obj->searchPersonell($stored_request['nr'],$function[$i],'chosed');
                                                    $personell_info[$i]=$personell;
                                                }
                                                for($n=0;$n<sizeof($jbuf);$n++)
                                                {
                                                    echo '<tr>
                                                            <td width="35%">
                                                                
                                                                <font face="arial" size=3">'.$LDOpPersonElements[$jbuf[$n]].':         
                                                                    <img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=15 align="left">
                                                                </font>
                                                            </td>';
                                                    $elem=$LDOpPersonElements[$jbuf[$n]];
                                                    if($personell_info[$n]){
                                                        echo '<td><table>';
                                                        for($i=0;$i<sizeof($personell_info[$n]);$i++){
                                                            if(trim($personell_info[$n][$i],'\x')=='') continue;
                                                            echo '<tr><td><font face="arial" size=3">'.trim($personell_info[$n][$i],'\x').'</font>&nbsp;&nbsp;</td></tr>';
                                                        }
                                                        echo '</table></td></tr>';
                                                    }else{
                                                        echo '<td></td>';
                                                    }
                                                }
                                                echo '<tr>
                                                            <td width="35%">
                                                                <font face="arial" size=3">'.$LDPharma1.':
                                                                <img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=15 align="left">
                                                                </font>
                                                            </td>';
                                                $pharma_encoder=explode("~",trim($stored_request[material_codedlist]));                                                
                                                $entrycount=sizeof($pharma_encoder);
                                                echo '<td><table width="100%" class="submenu_frame"><tbody class="submenu">';
                                                echo '<tr><td bgcolor="white" width=70% align="center"><font color="darkgreen" size="2"><b>'.$LDName_Pharma;
                                                echo '</b></font></td><td bgcolor="white" align="center"><font color="darkgreen" size="2"><b>'.$LDNumber.'</b></font></td></tr>';
                                                for($i=0;$i<$entrycount;$i++){                                                    
                                                    if(trim($pharma_encoder[$i])=="") continue;   
                                                    $pharma_number=explode("+",trim($pharma_encoder[$i]));                                                 
                                                    $number=$pharma_number[1];
                                                    parse_str($pharma_encoder[$i],$elems);                            
                                                    $pharma_name=$pharma->getPharmaInfo($elems[n],$dept_nr,$ward_nr);
                                                    $unit_pharma=$pharma->getunitPharma($elems[n]);
                                                    
                                                    echo '<tr><td bgcolor="white"><font face="arial" size=3">';
                                                    echo $pharma_name[product_name].'</font></td>';
                                                    echo '<td bgcolor="white" align="center"><font face="arial" size=3" align="center">'.$number.' '.$unit_pharma['unit_name_of_medicine'].'</font></td></tr></td>';
                                                }
                                                echo '</tbody></table></td></tr>';
                                                echo '<tr>
                                                            <td width="35%">
                                                                <font face="arial" size=3">'.$LDmed1.':
                                                                <img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=15 align="left" />
                                                                </font>
                                                            </td>';
                                                $med_encoder=explode("~",trim($stored_request[medical_codedlist]));                                               
                                                $entrycount=sizeof($med_encoder);
                                                echo '<td><table width="100%" class="submenu_frame"><tbody class="submenu">';
                                                echo '<tr><td bgcolor="white" width=70%" align="center"><font color="darkgreen" size="2"><b>'.$LDName_Med;
                                                echo '</b></font></td><td bgcolor="white" align="center"><font color="darkgreen" size="2"><b>'.$LDNumber.'</b></font></td></tr>';
                                                for($i=0;$i<$entrycount;$i++){                                                    
                                                    if(trim($med_encoder[$i])=="") continue;
                                                    $med_number=explode("+",trim($med_encoder[$i]));
                                                    $number=$med_number[1];
                                                    parse_str($med_encoder[$i],$elems);                                                    
                                                    $med_name=$med->getMedInfo($elems[n],$dept_nr,$ward_nr);
                                                    $unit_med=$med->getunitMed($elems[n]);
                                                    
                                                    echo '<tr><td bgcolor="white"><font face="arial" size=3">';
                                                    echo $med_name[product_name].'</font></td>';
                                                    echo '<td bgcolor="white" align="center"><font face="arial" size=3">'.$number.' '.$unit_med['unit_name_of_medicine'].'</font></td>';
                                                    echo '</tr></td>';
                                                }
                                                echo '</tbody></table></td></tr>';
                                        ?>       
                                        </table>
                                    <tr bgcolor="<?php echo $bgc1 ?>">
                                    </table> 
                                </td>
                            </tr>
                            
                        </table>

                        </td>
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
    <img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?> align="absmiddle">
    <font size=3 face="verdana,arial" color="#990000">
        <b>
            <?php echo $LDNoPendingRequest ?>
        </b>
    </font>
        <p>
        <a href="<?php echo $breakfile ?>">
            <img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>>
        </a>
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
