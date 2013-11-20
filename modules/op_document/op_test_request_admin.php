<?php
	error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
	require('./roots.php');
	require($root_path.'include/core/inc_environment_global.php');
	/**
	* CARE2X Integrated Hospital Information System version deployment 1.1 (mysql) 2004-01-11
	* GNU General Public License
	* Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
	* , elpidio@care2x.org
	*
	* See the file "copy_notice.txt" for the licence notice
	*/

	/* Start initializations */ 
	$lang_tables=array('departments.php', 'or.php', 'stdpass.php');
	define('LANG_FILE','konsil.php');

	/* We need to differentiate from where the user is coming: 
	*  $user_origin != lab ;  from patient charts folder
	*  $user_origin == lab ;  from the laboratory
	*  and set the user cookie name and break or return filename
	*/
	$local_user='ck_opdoku_user';
	$breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
	require_once($root_path.'include/core/inc_front_chain_lang.php'); ///* invoke the script lock*/

	if($user_origin=='op'){
		$breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
	}elseif ($user_origin=='record'){
//		header('Location:'.$root_path.'modules/registration_admission/show_notes_2.php'.URL_APPEND.'&pid='.$pid.'&enc_nr='.$pn);
                header('Location:'.$root_path.'modules/registration_admission/aufnahme_daten_zeigen.php'.URL_APPEND.'&from=such&encounter_nr='.$pn.'&target=search');
		exit;
	}
	
	require_once($root_path.'include/care_api_classes/class_access.php');
	$access= & new Access();
	$role= $access->checkNameRole($_SESSION['sess_user_name']);
	
	$bgc1='#ffffff'; /* The main background color of the form */
	$edit_form=0; /* Set form to non-editable*/
	$read_form=1; /* Set form to read */
	$edit=0; /* Set script mode to no edit*/

	$formtitle=$LDTestRequestOP;

	$db_request_table='or';

	//$db->debug=1;

	/* Here begins the real work */
	require_once($root_path.'include/core/inc_date_format_functions.php');

	require_once ($root_path . 'include/care_api_classes/class_encounter.php');
	$enc_obj = new Encounter ( );
	require_once($root_path.'include/care_api_classes/class_encounter_op.php');
	$enc_op_obj=new OPEncounter();

	if(!isset($mode))   $mode='';

	$pid='';
	/* Get the pending test requests */
	if(!$mode) {
		$requests=$enc_op_obj->list_request();
		if($requests){
			$batchrows=$requests->RecordCount();
			if($batchrows && (!isset($batch_nr) || !$batch_nr)){
					$test_request=$requests->FetchRow();
					/* Check for the patietn number = $pn. If available get the patients data */
					$pn=$test_request['encounter_nr'];
					$requests1=$enc_op_obj->serch_pid($pn);
					$test_request1=$requests1->FetchRow();
					$pid=$test_request1['pid'];
					$batch_nr=$test_request['batch_nr'];
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
<!-- tự động refresh lại trang trong 10s-->
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
            <!-- link trỏ tới hiển thị các ê-kíp mổ  
            -->
            <?php
                //if((strpos($role['role_name'], 'Trưởng khoa')!='' || strpos($role['role_name'], 'Trưởng khoa')!=0) || $_SESSION['sess_user_name']=='admin'){
				
            ?>
                <a href="<?php echo $root_path.'modules/or_logbook/op-pflege-logbuch-pass.php'.URL_REDIRECT_APPEND.'&target=e_kip&enc_nr='.$pn.'&batch_nr='.$batch_nr.'&dept_nr='.$dept_nr.'&ward_nr='.$ward_nr; ?>">
                    <img <?php echo createLDImgSrc($root_path,'newgroupop.gif','0') ?> alt="<?php echo $LDrecordSurgery ?>">
                </a>
            <?php
                //}
            ?>
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
                                    ?></td>
                                    <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10>
                                        <div   class=fva2_ml10>
                                            <font size=5 color="#0000ff">
                                                <b>
                                                    <?php echo $formtitle ?>
                                                </b>
                                            </font>
                                        </div>
                                        <br>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td bgcolor="<?php echo $bgc1 ?>" align="right" valign="bottom">
                                        <p>
                                        <?php
                                            //Lấy ngày mổ từ phiếu yêu cầu mổ
                                            $date=$enc_op_obj->getInfoTest($batch_nr,'');
                                            $date_request=$date->FetchRow();
                                            $op_date=$date_request['date_request'];
                                            $level_method=$date_request['level_method'];
                                            echo '<font color="blue"><b>'.$LDLevelMethodOP.": ".$level_method.'</b></font>';
                                            echo '</br>';
                                            echo '<font color="blue"><b>'.$LDOpDate.": ".formatDate2Local($op_date, $date_format).'</b></font>';
                                        ?>
                                        </p>
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
                                    &nbsp;<br>

                                    </td>
                                    </tr>

                                        <tr bgcolor="<?php echo $bgc1 ?>">
                                            <td >
                                                <div class=fva2_ml10><?php echo $LDClinicalInfo ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
                                                <font face="verdana,arial" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['clinical_info']) ?></font>
                                            </td>
                                            <td>
                                                <div class=fva2_ml10><?php echo "$LDDocOP";?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
                                                <font face="verdana,arial" size=2 color="#000099">&nbsp;&nbsp;<?php if($edit_form || $read_form) echo stripslashes($stored_request['person_surgery']) ?>
                                                </font>
                                                </div>
                                            </td>
                                        </tr>	
                                        <tr bgcolor="<?php echo $bgc1 ?>">
                                            <td>
                                                <div class=fva2_ml10><?php echo $LDReqTestOP ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
                                                <font face="verdana,arial" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['test_request']) ?></font>
                                            </td>
                                            <td align="left">
                                                <div class=fva2_ml10>
                                                    <?php echo $LDMethodOP;?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
                                                    <font face="verdana,arial" size=2 color="#000099">&nbsp;&nbsp;<?php if($edit_form || $read_form){
                                                    echo stripslashes($stored_request['method_op']);
                                                    }else{
                                                      echo $LDNO1;
                                                    }
                                                    ?></font>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr bgcolor="<?php echo $bgc1 ?>">
                                            <td colspan=2 align="right"><div class=fva2_ml10>                                           
                                            <?php echo $LDRequestingDoc ?>:
                                            <font face="verdana,arial" size=2 color="#000099">&nbsp;<?php echo $stored_request['send_doctor'] ?></font>&nbsp;&nbsp;&nbsp;&nbsp;</div><br>
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
                    <a href="<?php echo $thisfile.URL_REDIRECT_APPEND.'&subtarget=or&user_origin=record&pid='.$pid.'&pn='.$pn; ?>">
                        <img <?php echo createLDImgSrc($root_path,'admission_data.gif','0') ?> alt="<?php // echo $LDrecordEntry ?>"/>
                    </a>
                </p>
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
