<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/core/inc_environment_global.php');
    define('MAX_BLOCK_ROWS',30); 

    $lang_tables[]='search.php';
    define('LANG_FILE','or.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    require_once($root_path.'include/core/inc_config_color.php');

    # Initialization
    $thisfile=basename(__FILE__);
    $breakfile='javascript:window.close()';

    # Workaround: Resolve the search key variables
    if(empty($srcword)&&!empty($searchkey)) $srcword=$searchkey;

    if($srcword!=''||$mode=='paginate'){
        include_once($root_path.'include/core/inc_date_format_functions.php');
        if($mode=='paginate'){
                $sql2=$_SESSION['sess_searchkey'];
        }else{
            # Reset paginator variables
            $pgx=0;
            $totalcount=0;
            $odir='ASC';
            $oitem='name_last';

            if(is_numeric($srcword)){
                $srcword=(int) $srcword;
            }else{
                $srcword=strtr($srcword,'*&','%_');
            }

            # Try converting keyword to DOB
            $DOB = formatDate2STD($srcword,$date_format);

            $select="SELECT  o.*,                                
                            tr.encounter_nr,tr.date_request,
                            p.pid,
                            p.name_last,
                            p.name_first,
                            p.date_birth,
                            p.sex,
                            hs.localize,hs.therapy,hs.result,hs.op_start,hs.op_end";

            $selectfrom= " FROM  care_encounter_op AS o 
                            LEFT JOIN care_test_request_or AS tr ON tr.batch_nr= o.batch_nr                                    
                            LEFT JOIN care_encounter AS e ON e.encounter_nr = tr.encounter_nr
                            LEFT JOIN care_person AS p ON p.pid = e.pid
                            LEFT JOIN care_address_citytown AS t ON t.nr=p.addr_citytown_nr
                            LEFT JOIN care_op_med_doc AS hs ON hs.encounter_op_nr=o.nr";

            # If the search is directed to a single patient
            if($mode=='get'||$mode=='getbypid'||$mode=='getbyenc'){
                if($mode=='get'){
                    $sql2=$selectfrom."	WHERE  o.nr='$nr'
                                        AND hs.result='done'
                                        AND  tr.encounter_nr=e.encounter_nr
                                        AND e.pid=p.pid 
                                        ";
                }elseif($mode=='getbypid'){
                    $sql2=$selectfrom." WHERE p.pid='$nr'
                                        AND  tr.encounter_nr=e.encounter_nr
                                        AND e.pid=p.pid 
                                        AND hs.result='done'";
                }else{
                    $sql2=$selectfrom."	WHERE o.encounter_nr='$nr'
                                        AND tr.encounter_nr=e.encounter_nr
                                        AND e.pid=p.pid 
                                        AND hs.result='done'";
                }
            }else{
                    $sql2=$selectfrom."	WHERE tr.encounter_nr=e.encounter_nr
                                        AND e.pid=p.pid 
                                        AND (p.name_last = '$srcword'
                                        OR p.name_first = '$srcword'";
                    if($DOB) $sql2.=" OR p.date_birth = '$srcword' ";
                    if(is_numeric($srcword)){
                        $sql2.=" OR o.op_room = $srcword OR tr.encounter_nr = $srcword OR tr.batch_nr=$srcword";
                    }
                    $sql2.=")";
            }
        }
        #Load and create paginator object
        include_once($root_path.'include/care_api_classes/class_paginator.php');
        $pagen=& new Paginator($pgx,$thisfile,$_SESSION['sess_searchkey'],$root_path);

        $GLOBAL_CONFIG=array();
        include_once($root_path.'include/care_api_classes/class_globalconfig.php');
        $glob_obj=new GlobalConfig($GLOBAL_CONFIG);	
        # Get the max nr of rows from global config
        $glob_obj->getConfig('pagin_patient_search_max_block_rows');
        if(empty($GLOBAL_CONFIG['pagin_patient_search_max_block_rows'])) $pagen->setMaxCount(MAX_BLOCK_ROWS); # Last resort, use the default defined at the start of this page
        else $pagen->setMaxCount($GLOBAL_CONFIG['pagin_patient_search_max_block_rows']);

        # Detect what type of sort item
        if($oitem=='encounter_nr' || $oitem=='date_request') $tab='tr';
        elseif(stristr($oitem,'op_')) $tab='o';
        else $tab='p';
        
        # If the search is directed to a single patient
        if($mode=='get'||$mode=='getbypid'||$mode=='getbyenc'){	
            $sql=$select.$sql2."ORDER BY tr.date_request DESC";
            if($ergebnis=$db->Execute($sql)){
                if($rows=$ergebnis->RecordCount()){
                    $datafound=1;
                }else{ 
                    echo "$LDDbNoRead<br>$sql"; 
                }
            }
        }else{
            #  Start searching 
            $sql=$select.$sql2." ORDER BY $tab.$oitem $odir";
            if($ergebnis=$db->SelectLimit($sql,$pagen->MaxCount(),$pgx)){
                if($rows=$ergebnis->RecordCount()){
                    if($rows==1) $datafound=1;
                    $_SESSION['sess_searchkey']=$select.$sql2;
                }else{
                    $select="SELECT o.nr,o.op_room,tr.date_request,tr.batch_nr, e.encounter_nr, p.pid, p.name_last, p.name_first, p.date_birth, p.sex";
                    $sql2=" FROM care_encounter_op AS o
                            LEFT JOIN care_test_request_or AS tr ON tr.batch_nr=o.batch_nr
                            LEFT JOIN care_encounter AS e ON tr.encounter_nr=e.encounter_nr
                            LEFT JOIN care_op_med_doc AS hs ON hs.encounter_op_nr=o.nr
                            LEFT JOIN care_person AS p ON p.pid=e.pid
                            WHERE hs.result='done' 
                            AND ( p.name_last $sql_LIKE '$srcword%'
                            OR p.name_first $sql_LIKE '$srcword%'";
                    if(is_numeric($srcword)) $sql2.=" OR  o.op_room $sql_LIKE '$srcword%' OR e.encounter_nr $sql_LIKE '$srcword%' OR tr.batch_nr $sql_LIKE '$srcword%'";
                    if($DOB) $sql2.=" OR p.date_birth $sql_LIKE '$srcword%'";
                    $sql2.=")";
                    $sql=$select.$sql2." ORDER BY $tab.$oitem $odir";
                    if($ergebnis=$db->SelectLimit($sql,$pagen->MaxCount(),$pgx)){
                        $rows=$ergebnis->RecordCount();
                        $_SESSION['sess_searchkey']=$select.$sql2;
                    }else{ echo "$LDDbNoRead<br>$sql"; }
                }
            }else{
                echo "$LDDbNoRead<br>$sql";
            }
            if($rows){
                $pagen->setTotalBlockCount($rows);
                # If count more than the max row count
                if($rows>1){
                    # Count per sql
                    if(isset($totalcount) && $totalcount){
                        $pagen->setTotalDataCount($totalcount);
                    }else{
                        # Count total available data
                        //$sql="$sql $tab.$oitem $odir";
                        $sql = "SELECT COUNT(o.nr) AS maxcount ".$sql2;
                        if($result=$db->Execute($sql)){
                            $row = $result->FetchRow();
                            $totalcount = $row['maxcount'];
                        }
                        $pagen->setTotalDataCount($totalcount);
                    }
                }else{
                        $totalcount=1;
                        $pagen->setTotalDataCount(1);
                }
                # Set the sort parameters
                $pagen->setSortItem($oitem);
                $pagen->setSortDirection($odir);
            }
        } # end of else if mode== get
    }
    require_once($root_path.'gui/smarty_template/smarty_care.class.php');
    $smarty = new smarty_care('nursing');
    # Title in toolbar
    $smarty->assign('sToolbarTitle',"$LDOrLogBook - $LDSearch");
    # hide return button
    $smarty->assign('pbBack',FALSE);
    # href for help button
    $smarty->assign('pbHelp',"javascript:gethelp('oplog.php','search','$mode','$rows','$datafound')");
    # href for close button
    $smarty->assign('breakfile',$breakfile);
    # Window bar title
    $smarty->assign('sWindowTitle',"$LDOrLogBook - $LDSearch");
    # Body Onload js
    $smarty->assign('sOnLoadJs','onLoad="if (window.focus) window.focus();document.suchform.srcword.select();"');
    # Body OnUnload js
    $smarty->assign('sOnUnloadJs','onUnload="if (wwin) wwin.close();"');
    # Collect js code
    ob_start();
?>

<script  language="javascript">
    var wwin;
    var lock=true;
    var nodept=false;

    function pruf(f)
    {
    d=f.srcword.value;
    if(d=="") return false;
    else return true;
    }

    function open_such_editwin(filename,y,m,d,dp,sl)
    {
	url="op-pflege-logbuch-arch-edit.php?mode=edit&fileid="+filename+"&sid=<?php echo "$sid&lang=$lang"; ?>&user=<?php echo str_replace(" ","+",$user); ?>&pyear="+y+"&pmonth="+m+"&pday="+d+"&dept_nr="+dp+"&saal="+sl;
        <?php 
            if($cfg['dhtml'])
                echo 'w=window.parent.screen.width;
                      h=window.parent.screen.height;';
            else echo 'w=800;';
        ?>
	sucheditwin=window.open(url,"sucheditwin","menubar=no,resizable=yes,scrollbars=yes, width=" + (w-15) + ", height=400");
	window.sucheditwin.moveTo(0,0);
    }

    function waitwin()
    {
        wwin=window.open("waitwin.htm","wait","menubar=no,resizable=no,scrollbars=no,width=400,height=200");
    }
    function getinfo(pid,dept,pdata){
            urlholder="<?php echo $root_path; ?>modules/nursing/nursing-station-patientdaten.php<?php echo URL_REDIRECT_APPEND; ?>&pn="+pid+"&patient=" + pdata + "&station="+dept+"&op_shortcut=<?php echo strtr($ck_op_pflegelogbuch_user," ","+") ?>";
            patientwin=window.open(urlholder,pid,"width=700,height=450,menubar=no,resizable=yes,scrollbars=yes");
    }
</script>

<?php
    $sTemp = ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript',$sTemp);
    # Buffer page output
    ob_start();

    if((($mode=='get')||($datafound)) && $rows){
	if($rows>1)
            echo $LDPatLogbookMany;
        else echo $LDPatLogbook;
	echo '<table cellpadding="0" cellspacing="0" border="0" bgcolor="#999999" width="100%">
		<tr>
                    <td>
                        <table  cellpadding="3" cellspacing="1" border="0" width="100%">';	
                            echo '<tr class="wardlisttitlerow">';
                                while(list($x,$v)=each($LDOpMainElements))
                                {
                                    echo '<td>'.$v.'</td>';
                                }
                            echo '</tr>';
	$img_arrow=createComIcon($root_path,'bul_arrowgrnlrg.gif','0','middle'); // Loads the arrow icon image
	$img_info=createComIcon($root_path,'info2.gif','0','middle'); // Loads the arrow icon image
	        	
	while($pdata=$ergebnis->FetchRow()){
            require_once($root_path.'include/care_api_classes/class_encounter_op.php');
            $enc_op_obj=new OPEncounter();
            //$function=array("Bác sĩ phẫu thuật","Phụ mổ","Y tá vòng trong","Y tá vòng ngoài","Bác sĩ gây mê","Bác sĩ gây mê phụ");
			$function=array("4","10","7","5","8");
            for($i=0;$i<5;$i++){
                $personell[$i]=$enc_op_obj->searchPersonell($pdata['nr'],$function[$i],'chosed');                
            }
            if($toggler==0){ 
                echo '<tr bgcolor="#fdfdfd">';
                $toggler=1;
            }else{ 
                echo '<tr bgcolor="#eeeeee">';
                $toggler=0;
            }
                    echo '<a name="'.$pdata['encounter_nr'].'"></a>';
                    echo '<td valign=top>
                            <font size=2 color=red><b>'.$pdata['op_nr'].'</b>
                            </font><hr>'.formatDate2Local($pdata['date_request'],$date_format).'<br>
                                '.$tage[date("w",mktime(0,0,0,$imonth,$iday,$iyear))].'<br>
                          </td>';
	
                    echo '<td valign=top><nobr><font color=blue>';
                    /*require_once($root_path.'include/care_api_classes/class_access.php');
                    $access= & new Access();
                    $role= $access->checkNameRole($_SESSION['sess_login_username']);
                    if(strpos($role['role_name'], 'Trưởng khoa')!='' || strpos($role['role_name'], 'Điều dưỡng trưởng')!='' || strpos($role['role_name'], 'Điều dưỡng hành chính')!=''){
                        echo '<a href="javascript:getinfo(\''.$pdata[encounter_nr].'\',\''.$pdata[dept_nr].'\')">
                                <img '.$img_info.' alt="'.str_replace("~tagword~",$pdata['name_last'],$LDOpenPatientFolder).'">
                              </a>&nbsp;';
                    }*/

            echo ($pdata['encounter_class_nr']==1)?($pdata['encounter_nr']+$GLOBAL_CONFIG['patient_inpatient_nr_adder']) : ($pdata['encounter_nr']+$GLOBAL_CONFIG['patient_outpatient_nr_adder']);
			
            echo '<br><font color=black><b>'.$pdata['name_last'].' '.$pdata['name_first'].'</b><br>'.formatDate2Local($pdata['date_birth'],$date_format).'<p>
                    <font color="#000000">'.$pdata['addr_str'].' '.$pdata['addr_str_nr'].'<br>'.$pdata['addr_zip'].' '.$pdata['citytown_name'].'</font><br></td>';
			
                    echo '<td valign=top><font color="#cc0000">'.nl2br($pdata['batch_nr']).'</font></td>';
                    echo '</td><td valign=top><font color=black>';
                    echo $pdata['localize'].'</font></td>';
                    echo '<td valign=top>'.nl2br($pdata['therapy']).'</td>';
                    echo '<td valign=top><font ><nobr>';
                        echo '<font color="#cc0000">'.$LDDocOP.'</font><br>';
                    if(sizeof($personell[0])>0){
                        for($i=0;$i<sizeof($personell[0]);$i++){
                            if(trim($personell[0][$i],'\x')=='') continue;
                            else echo '&nbsp;&nbsp;&nbsp;'.trim($personell[0][$i],'\x').'<br/>';
                        }
                    }
                    echo '</td>
                          <td valign=top><font >'.$LDAnaTypes[$pdata['anesthesia']].'<p>';
	
                    echo '<font color="#cc0000">'.$LDAnaDoc.'</font><br><font color="#000000">';
                    if(sizeof($personell[3])>0){
                        for($i=0;$i<sizeof($personell[3]);$i++){
                            if(trim($personell[3][$i],'\x')=='') continue;
                            else echo '&nbsp;&nbsp;&nbsp;'.trim($personell[3][$i],'\x').'<br/>';
                        }
                    }
                    echo '</font>';
                    echo '<font color="#cc0000">'.$cbuf[2].'</font><br>';
                    if(sizeof($personell[4])>0){
                        for($i=0;$i<sizeof($personell[4]);$i++){
                            if(trim($personell[4][$i],'\x')=='') continue;
                            else echo '&nbsp;&nbsp;&nbsp;'.trim($personell[4][$i],'\x').'<br/>';
                        }
                    }	
                    echo '</td>
                    <td valign=top><font color="#cc0000">'.$cbuf[3].'</font><br>';
                    if(sizeof($personell[1])>0){
                        for($i=0;$i<sizeof($personell[1]);$i++){
                            if(trim($personell[1][$i],'\x')=='') continue;
                            else echo '&nbsp;&nbsp;&nbsp;'.trim($personell[1][$i],'\x').'<br/>';
                        }
                    }
                    echo '<font color="#cc0000">'.$cbuf[4].'</font><br>';
                    if(sizeof($personell[2])>0){
                        for($i=0;$i<sizeof($personell[2]);$i++){
                            if(trim($personell[2][$i],'\x')=='') continue;
                            else echo '&nbsp;&nbsp;&nbsp;'.trim($personell[2][$i],'\x').'<br/>';
                        }
                    }
                    echo '</td>';
                    echo '<td valign=top><nobr>';
                    switch($pdata['result']){
                        case 'done':
                            echo '<font color=black>'.$LDResult.'</td>';
                            break;
                        case 'move':
                            echo '<font color=black>'.$LDResult1.'</td>';
                            break;
                        default:
                            echo '<font color=black>'.$LDResult2.'</td>';
                            break;            
                    }
                    echo '
                    <td valign=top><font >';        
                    echo '<font size="1" color="#cc0000">'.$LDOpIn.':</font><br>'.$pdata[op_start].'<p>
                    <font color="#cc0000">'.$LDOpOut.':</font><br>'.$pdata[op_end].'</td>';
                echo '</tr>';
	}
            echo '</table>
                </td>
            </tr>
        </table>';
    }elseif($mode=='search'||$mode=='paginate'){
	echo '<ul>
	<table cellpadding=0 cellspacing=0 border=0>';
	if($rows) 
            echo'<tr>
                    <td valign="middle" class="prompt">
                        '.$LDPatientsFound.'
                    </td>
                    <td>
                        <img '.createMascot($root_path,'mascot1_l.gif','0','middle').'/>
                    </td>
                 </tr>';
            echo '<tr>
                    <td valign=top colspan=2>';
                        if ($rows) echo str_replace("~nr~",$totalcount,$LDSearchFound).' '.$LDShowing.' '.$pagen->BlockStartNr().' '.$LDTo.' '.$pagen->BlockEndNr().'.';
                        else echo str_replace('~nr~','0',$LDSearchFound);	
                        echo'<table cellpadding=0 cellspacing=0 border=0 >
				<tr>
                                    <td bgcolor=#999999>
                                    <table cellpadding=2 cellspacing=1 border=0 >';
                                        if($rows){
                                            echo '<tr><td colspan=8 bgcolor=#eeeee0><p>';
                                        if($rows==1) echo " $LDSimilar ";
                                        else echo $LDPlsClk1;
                                        echo '</td></tr>';
            # Loads the arrow icon image
            $img_src='<img '.createComIcon($root_path,'arrow.gif','0','middle').'>';
            # Load the background image
            //$bgc='background="'.$root_path.'gui/img/skin/default/tableHeaderbg3.gif"';
            $img_male=createComIcon($root_path,'spm.gif','0');
            $img_female=createComIcon($root_path,'spf.gif','0');
            $append="&srcword=$srcword";
?>
                                <tr class="wardlisttitlerow">
                                    <td>
                                        <b>
                                            <?php  
                                                $oitem='sex';
                                                echo $pagen->makeSortLink($LDSex,'sex',$oitem,$odir,$append);
                                            ?>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <?php
                                                echo $LDLastName;
                                            ?>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <?php   
                                                $oitem='name_first';
                                                echo $pagen->makeSortLink($LDName,'name_first',$oitem,$odir,$append);
                                            ?>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <?php 
                                                $oitem='date_birth';
                                                echo $pagen->makeSortLink($LDBday,'date_birth',$oitem,$odir,$append);
                                            ?>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <?php 
                                                $oitem='op_room';
                                                echo $pagen->makeSortLink($LDOpRoom,'op_room',$oitem,$odir,$append);  
                                            ?>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            <?php 
                                                $oitem='date_request';
                                                echo $pagen->makeSortLink($LDSrcListElements[5],'date_request',$oitem,$odir,$append);  
                                            ?>
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                        <?php   
                                            $oitem='encounter_nr';
                                            echo $pagen->makeSortLink($LDPatientNr,'encounter_nr',$oitem,$odir,$append); 
                                        ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php
                                    while($pdata=$ergebnis->FetchRow()){
                                        echo '<tr class="submenu">';
                                            echo '<td>';
                                            echo '<a href="javascript:popPic(\''.$pdata['pid'].'\')">';
                                                switch($pdata['sex']){
                                                    case 'f': echo '<img '.$img_female.'>'; break;
                                                    case 'm': echo '<img '.$img_male.'>'; break;
                                                    default: echo '&nbsp;'; break;
                                                }
                                            echo '</a>';
                                            echo "</td><td>";
                                            echo '<a href="javascript:popPic(\''.$pdata['pid'].'\')">';
                                            if($srcword&&stristr($pdata['name_last'],$srcword)) echo '<b><span style="background:yellow">'.$pdata['name_last'].'</span></b>';
                                            else echo $pdata['name_last'];
                                            echo '</a>';
                                            echo '</td>
                                                <td>&nbsp;';
                                            echo '<a href="javascript:popPic(\''.$pdata['pid'].'\')">';
                                                if($srcword&&stristr($pdata['name_first'],$srcword)) echo '<b><span style="background:yellow">'.$pdata['name_first'].'</span></b>';
                                                else echo $pdata['name_first'];
                                            echo '</a>';
                                            echo '</td>
                                                <td align="center" >';
                                            echo '<a href="javascript:popPic(\''.$pdata['pid'].'\')">';
                                                if($srcword&&stristr($pdata['date_birth'],$srcword)) echo '<b><span style="background:yellow">'.formatDate2Local($pdata['date_birth'],$date_format).'</span></b>';
                                                else echo formatDate2Local($pdata['date_birth'],$date_format);
                                            echo '</a>';
                                        echo '</td>
                                                <td align="center">';
                                                    echo "<a href=\"op-pflege-logbuch-xtsuch-start.php?sid=$sid&lang=$lang&mode=get&nr=".$pdata['nr']."&dept_nr=$dept_nr&saal=$saal&srcword=".strtr($srcword," ","+")."\">&nbsp;";
                                                    echo '<b>'.$pdata[op_room].'</b></a>';
                                            echo '</td>
                                                    <td align="center" ><b>';
                                                    echo "<a href=\"op-pflege-logbuch-xtsuch-start.php?sid=$sid&lang=$lang&mode=get&nr=".$pdata['nr']."&dept_nr=$dept_nr&saal=$saal&srcword=".strtr($srcword," ","+")."\">&nbsp;";
                                                    echo '
                                                    '.formatDate2Local($pdata['date_request'],$date_format).'</b></a>';
                                        echo '</td>';
                                        echo '<td align="center" colspan="2">';
                                                echo '<a href="javascript:popPic(\''.$pdata['pid'].'\')">';
                                                    echo $pdata['encounter_nr'];
                                                echo '</a>';            
                                        echo '</td>';
                                    echo '</tr>';	
                                }	

                            }
                            if($totalcount>$pagen->MaxCount())	
                                    echo '<tr bgcolor="#eeeeee">
                                        <td colspan=2>'.$pagen->makePrevLink($LDPrevious,$append).'</td>
                                        <td colspan=4>&nbsp;</td>
                                        <td align=right colspan=2>'.$pagen->makeNextLink($LDNext,$append).'</td>
                                        </tr>';
                            echo '</table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </ul>';
    }
?>
    <ul>
<?php 
    echo $LDPromptSearch;
?>
    <form action="<?php echo $thisfile; ?>" method=post name=suchform onSubmit="return pruf(this)">
        <table border=0 cellspacing=0 cellpadding=1 bgcolor=#999999>
            <tr>
                <td>
                    <table border=0 cellspacing=0 cellpadding=5 bgcolor=#eeeeee>
                        <tr>
                            <td>	
                                <font color=maroon size=2>
                                    <b>
                                        <?php echo $LDKeyword ?>:
                                    </b>
                                </font>
                                <br>
                                <input type="text" name="srcword" size=40 maxlength=100 value="<?php echo $srcword; ?>">
                                <input type="hidden" name="sid" value="<?php echo $sid; ?>"> 
                                <input type="hidden" name="lang" value="<?php echo $lang; ?>"> 
                                <input type="hidden" name="dept_nr" value="<?php echo $dept_nr; ?>"> 
                                <input type="hidden" name="saal" value="<?php echo $saal; ?>">
                                <input type="hidden" name="child" value="<?php echo $child; ?>"> 
                                <input type="hidden" name="user" value="<?php echo str_replace(" ","+",$_COOKIE['ck_op_pflegelogbuch_user'.$sid]); ?>">
                                <input type="hidden" name="mode" value="search">
                            </td>
                        </tr>
                        <tr>
                            <td>	
                                <input type="submit" value="<?php echo $LDSearch ?>" align="right">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
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
