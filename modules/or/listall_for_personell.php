<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    define('MAX_BLOCK_ROWS',30);
    $lang_tables=array('or.php');
    define('LANG_FILE','aufnahme.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');
//    require_once($root_path.'include/care_api_classes/class_access.php');
//    $access= & new Access();
//    if($_SESSION['sess_login_username']!='admin'){
//        $role= $access->checkNameRole($_SESSION['sess_login_username']);
//        if(strpos($role['dept_nr'], '"'.$dept_nr.'"')==0 && $role['location_nr']!=$dept_nr){
//            header("Location:../../language/".$lang."/lang_".$lang."_invalid-access-warning.php"); 
//            exit;
//        }
//    }
    require_once($root_path.'include/core/inc_date_format_functions.php');

    $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;

    $thisfile=basename(__FILE__);

    # Initialize page�s control variables
    if($mode!='paginate'){
            # Reset paginator variables
            $pgx=0;
            $totalcount=0;
            $odir='';
            $oitem='';
    }
    #Load and create paginator object
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
    require_once ($root_path . 'include/care_api_classes/class_encounter_op.php');
    $enc_op_obj = new OPEncounter ( );
    $personell_nr=$enc_op_obj->personell_nr($_SESSION['sess_login_username']);

    $toggle=0;
    $sql1="SELECT encounter_op_nr";
    $sql2=" FROM care_personell_op WHERE personell_nr='$personell_nr[personell_nr]'";
    $sql3=" ORDER BY encounter_op_nr $odir";
    if($ergebnis=$db->SelectLimit($sql1.$sql2.$sql3,$pagen->MaxCount(),$pagen->BlockStartIndex())){
        if ($linecount=$ergebnis->RecordCount()){ 
            if(($linecount==1) && $numeric){
                $zeile=$ergebnis->FetchRow();
                header('location:'.$thisfile.'?ntid='.$ntid.'&lang='.$lang.'&user_name='.$user_name.'&target='.$target);
                exit;
            }
        }
        $pagen->setTotalBlockCount($linecount);

        # If more than one count all available
        if(isset($totalcount) && $totalcount){
                $pagen->setTotalDataCount($totalcount);
        }else{
                # Count total available data
                $sql="SELECT COUNT(ps.op_room) AS count $sql2";
                if($result=$db->Execute($sql)){
                        if ($result->RecordCount()) {
                                $rescount=$result->FetchRow();
                        $totalcount=$rescount['count'];
                }
                }
                $pagen->setTotalDataCount($totalcount);
        }
    }else{
            echo "<p>$sql<p>$LDDbNoRead";
    }

    //$temp=$enc_op_obj->encounter_op_nr($personell_nr[personell_nr]);


    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('system_admin');

    # Title in toolbar
    $smarty->assign('sToolbarTitle', "$LDInfo");

    # hide return button
    $smarty->assign('pbBack',FALSE);

    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('employee_all.php')");

    # href for close button
    $smarty->assign('breakfile',$breakfile);

    # Window bar title
    $smarty->assign('sWindowTitle',"$LDInfo");

    # Colllect javascript code

    ob_start();

?>

<table width=100% border=0 cellspacing="0" cellpadding=0>

<!-- Load tabs -->
<?php

$target='personell_listall';
 include('./gui_bridge/default/gui_tabs_personell_reg.php') 

?>

</table>
<ul>
    <?php
	if ($linecount) {
    ?>
    <form name="date" action="<?php echo $root_path.'modules/or/listall_for_personell.php?ntid='.$ntid.'&lang='.$lang.'&target=personell_listall&user_name='.$user_name.'';?>" method="POST">
    <?php
        echo '<font color="darkblue" size="2"><b>'.$LDDateChange.'</b></font>';
        if(isset($date_request) && $date_request!=""){
            echo ':  '.$date_request.'<br/>';
        }else{
            echo ':  '.date('d/m/Y').'<br/>';
        }
        //gjergji : new calendar
        echo '<input type="submit" value="'.$LDChange.' '.$LDDate1.'"/>';
        require_once ('../../js/jscalendar/calendar.php');
        $calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
        $calendar->load_files();
        echo $calendar->show_calendar($calendar,$date_format,'date_request',$stored_request['send_date']);       
    ?>
        <input type="submit" name="view" value="<?php echo $LDViewall; ?>"/>
    </form>
    <table width="50%">
        <tr class="wardlisttitlerow">
            <td align="center">
                <font color="darkblue" size="2">
                    <b>
                        <?php 
                            echo $LDRoom;
                        ?>
                    </b>
                </font>
            </td>
            <td align="center"><font color="darkblue" size="2"><b><?php echo $LDTime;?></b></font></td>
            <td align="center"><font color="darkblue" size="2"><b><?php echo $LDNr;?></b></font></td>
            <td align="center"><font color="darkblue" size="2"><b><?php echo $LDOpDate;?></b></font></td>
        </tr>
<?php
	while($nr=$ergebnis->FetchRow()){            
            if(isset($date_request)){
                $day= substr($date_request,0,2);
                $month= substr($date_request,3,2);
                $year= substr($date_request,6,4);
                $info=$enc_op_obj->info_for_personell($nr[encounter_op_nr],$year.'-'.$month.'-'.$day);
            }else{
                $info=$enc_op_obj->info_for_personell($nr[encounter_op_nr],date('Y-m-d'));
            }
            if(isset($view)){
                $info=$enc_op_obj->info_for_personell($nr[encounter_op_nr],"");
            }
            if($info[op_room]!=""){
                echo '<tr class="wardlistrow1">';
                echo '<td align="center">'.$info[op_room].'</td>';
                echo '<td align="center">'.$info[doc_time].'</td>';
                echo '<td align="center">'.$info[batch_nr].'</td>';
                echo '<td align="center">'.date('d/m/Y',strtotime($info[date_request])).'</td>';
                echo '</tr>';
            }
        }
?>
    </table>
<p>
    <?php
        }else{
            echo '<h2><font color="darkred">'.$LDNoRecordYet.'</font></h2>';
        }
    ?>
</ul>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
