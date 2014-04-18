
<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');

    $lang_tables[] = 'or.php';
    $lang_tables[] = 'aufnahme.php';

    $breakfile=$root_path.'main/op-doku_1.php'.URL_APPEND;
    $thisfile=basename(__FILE__);
	define('NO_2LEVEL_CHK',1);
    $local_user='ck_opdoku_user';
    require_once($root_path.'include/core/inc_front_chain_lang.php');
    require_once($root_path.'global_conf/inc_global_address.php');
    require_once($root_path.'include/core/inc_date_format_functions.php');
    
    require_once ($root_path . 'include/care_api_classes/class_encounter_op.php');
    $enc_op_obj = new OPEncounter ( );
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
    $sql1="SELECT enc.encounter_nr,yc.date_request,yc.encounter_nr,hs.special,yc.level_method,ps.name_last,ps.name_first,
    ps.date_birth,t.name AS citytown_name,qh.name AS quanhuyen_name,px.name AS phuongxa_name,ps.addr_str_nr,ps.addr_str";

$sql2=" FROM care_op_med_doc AS hs
            LEFT JOIN care_encounter_op AS tb ON tb.nr=hs.encounter_op_nr
            LEFT JOIN care_test_request_or AS yc ON yc.batch_nr=tb.batch_nr
            LEFT JOIN care_encounter AS enc ON enc.encounter_nr=yc.encounter_nr
            LEFT JOIN care_person AS ps ON ps.pid=enc.pid
			LEFT JOIN care_address_citytown AS t ON ps.addr_citytown_nr=t.nr
			LEFT JOIN care_address_quanhuyen AS qh ON ps.addr_quanhuyen_nr=qh.nr
			LEFT JOIN care_address_phuongxa AS px ON ps.addr_phuongxa_nr=px.nr"
    ;
    $sql3=" ORDER BY ps.name_last $odir";
    if($ergebnis=$db->SelectLimit($sql1.$sql2.$sql3,$pagen->MaxCount(),$pagen->BlockStartIndex())){
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
		$sql="SELECT COUNT(ps.date_birth) AS count $sql2";
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
    
    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('system_admin');
    $smarty->assign('sToolbarTitle', "$LDListPatient");
    $smarty->assign('pbBack',FALSE);
    $smarty->assign('breakfile',$breakfile);
    $smarty->assign('sWindowTitle',"$LDListPatient");
    ob_start();
?>


<script language="javascript">
    function printOut()
    {
            urlholder="<?php echo $root_path ?>modules/pdfmaker/emr_generic/report_op.php<?php echo URL_APPEND ?>&ses_en<?php echo $_SESSION['sess_full_en'] ?>&enc=<?php $pn ?>&recnr=<?php echo $recnr?>";
            testprintout<?php echo $sid ?>=window.open(urlholder,"testprintout<?php echo $sid ?>","width=800,height=600,menubar=no,resizable=yes,scrollbars=yes");
            //testprintout<?php echo $sid ?>.print();
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
            ?>     

            <?php
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
            ?>
        </font>
        
        <table width="75%" class="submenu_frame">
            <tbody class="submenu">
            <tr style="text-align:center;font-weight: bold; background: #A8D4FF;">
                <?php
                    echo '<td width="15%" align="center"><font size="2"><b>'.$LDNameFull.'</b></font></td>';
                    
                    echo '<td align="center" style="background: #C8D7DE;"><font size="2"><b>';
//                    echo $LDTuoi;
                    $var='&pmonth='.$pmonth.'&pyear='.$pyear;
                    if($oitem=='date_birth') $flag=TRUE;
                    else $flag=FALSE; 
                    echo $pagen->SortLink($LDTuoi,'date_birth',$odir,$flag,$var);
                    echo '</b></font></td>';
                    
                    echo '<td width="20%" align="center"><font size="2"><b>'.$LDAddress.'</b></font></td>';
                    echo '<td align="center" style="background: #C8D7DE;"><font size="2"><b>';                    
                    if($oitem=='date_request') $flag=TRUE;
                    else $flag=FALSE;
                    echo $pagen->SortLink($LDPatientNr,'date_request',$odir,$flag,$var);
                    echo '</b></font></td>';
                    
                    echo '<td align="center" style="background: #C8D7DE;"><font size="2"><b>';
                    if($oitem=='date_request') $flag=TRUE;
                    else $flag=FALSE; 
                    echo $pagen->SortLink($LDOpDate,'date_request',$odir,$flag,$var);
                    echo '</b></font></td>';
                    echo '<td width="30%" align="center"><font size="2"><b>'.$LDDiagnosis.'</b></font></td>';
                    echo '<td align="center"><font size="2"><b>'.$LDClassification.'</b></font></td>';
                ?>
            </tr>
            <?php                
                $count=$p;
                $temp=0;
                while($patient=$ergebnis->FetchRow()){
                    if( $pmonth==substr($patient["date_request"],5,2) && $pyear==substr($patient["date_request"],0,4)){
                        echo '<tr>';
                        echo '<td bgcolor="white">'.$patient["name_last"].' '.$patient["name_first"].'</td>';
                        $years=date("Y-m-d")-$patient["date_birth"];
                        echo '<td bgcolor="white" align="center">'.$years.'</td>';
                        echo '<td bgcolor="white">'.$patient["ps.addr_str_nr"].' '.$patient["addr_str"].' '.$patient["phuongxa_name"].', '.$patient["quanhuyen_name"].', '.$patient["citytown_name"].'</td>';
                        echo '<td bgcolor="white" align="center">'.$patient["encounter_nr"].'</td>';
                        echo '<td bgcolor="white" align="center">'.formatDate2Local($patient["date_request"], $date_format).'</td>';
                        echo '<td bgcolor="white">'.$patient["special"].'</td>';
                        echo '<td bgcolor="white" align="center">'.$patient["level_method"].'</td>';
                        echo '</tr>';
                        if($temp==0){
                            echo '<div class="a"><br/>';
                            //if ($linecount) echo str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
                            //else echo str_replace('~nr~','0',$LDSearchFound);
//                            echo '<table width="100%"><tr>';
                            $var='&user_name='.$user_name.'&pmonth='.$pmonth.'&pyear='.$pyear;
                            echo '<div class="c">'.$pagen->makePrevLink($LDPrevious,$var).'</div>';
                            echo '<div class="b">'.$pagen->makeNextLink($LDNext,$var).'</div>';
                            echo '<div class="clr"></div>';
                            echo '</div>';
                            $temp=1;
                        }
                    }
                }
            ?>  
            </tbody>
        </table>       
    </form>

</ul>

<?php
    }else{
            echo '<h2><font color="darkred">'.$LDNoRecordYet.'</font></h2>';
        }
    $sTemp2 = ob_get_contents();
    ob_end_clean();
    $smarty->assign('sMainFrameBlockData',$sTemp2);
    $smarty->display('common/mainframe.tpl');

?>
