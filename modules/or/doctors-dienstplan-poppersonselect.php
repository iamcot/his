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
    $lang_tables[]='or.php';
    define('LANG_FILE','doctors.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/core/inc_front_chain_lang.php');

    require_once($root_path.'include/core/inc_date_format_functions.php');
    
    require_once($root_path.'include/care_api_classes/class_personell.php');
    $pers_obj=new Personell;
    $doctors=$pers_obj->getDoctorsOfDept($dept_nr,'','');
    $wkday=date("w",mktime(0,0,0,$month,$elemid+1,$year));
?>

<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE>
    <?php echo $LDInfo4Duty ?>
</TITLE>
<style>
    .submenu_frame {
	background-color: #999999;
    }
    .submenu {
	background-color: #eeeeee;
    }
    .wardlisttitlerow {
	background-color: cyan;
    }
</style>
<?php
    //set the css style for a links
    require($root_path.'include/core/inc_css_a_sublinker_d.php');
?>

<script language="javascript">

    function closethis()
    {
            window.opener.focus();
            window.close();
    }

    function addelem(elem,hid,last,first,b,nr,nr_t,nr_d,nr_s,nr_s_t,nr_s_d,mode)
    {
        if(nr!=nr_s){
            if((nr==nr_t) || (nr==nr_d) || (nr==nr_s_t) || (nr==nr_s_d)){
                var answer = confirm ("<?php echo $LDAlert?>");
                if (answer){
                    eval("window.opener.document.forms[0].elements[elem].value=last+' '+first;");
                    eval("window.opener.document.forms[0].elements[hid].value=nr;");
                }
            }else{
                eval("window.opener.document.forms[0].elements[elem].value=last+' '+first;");
                eval("window.opener.document.forms[0].elements[hid].value=nr;");
            }	        
        }else{
            alert("<?php echo $LDAlert1;?>");
            return false;
        }	
    }

    <?php
        function weekday($daynum,$mon,$yr){
            $jd=gregoriantojd($mon,$daynum,$yr);
            switch(JDDayOfWeek($jd,0)){
                case 0: return "<font color=red>Sonntag</font>";
                case 1: return "Montag";
                case 2: return "Dienstag";
                case 3: return "Mittwoch";
                case 4: return "Donnerstag";
                case 5: return "Freitag";
                case 6: return "Samstag";
            }
        }

    ?>

</script>


</HEAD>
<BODY onLoad="if (window.focus) window.focus();"       

<?php 
    echo '<p align="center">';
    echo '<font face=verdana,arial size=4 color=maroon><b>';
    echo $LDDutyPlan;
    echo '<br>';
    if ($mode=="a") echo '<font color="#006666">'.$LDStandbyPerson.'</font>'; 
    else echo $LDOnCall;
    echo ' '.$LDOn1.'<br>';

    echo '<font color=navy> '.$LDFullDay[$wkday].'  ';
    /* if($month<10) echo '0'.$month; else echo $month;
    */
    echo formatDate2Local($year.'-'.$month.'-'.($elemid+1),$date_format);

    echo '</font> '; 
    echo '</b></font><br></p>';
    if($pers_obj->record_count)
    {
        echo '<ul>
                <font face="verdana,arial" size=2>';
?>
<table border=0 cellpadding=0 cellspacing=1 width="100%" class="submenu_frame">
    <tbody class="submenu">
        <tr class="wardlisttitlerow">
        <td width="5%"></td>
        <td align="center"><font color="darkgreen"><b><?php echo $LDPersonellNr; ?></b></font</td>
        <td align="center"><font color="darkgreen"><b><?php echo $LDNameFull; ?></b></font></td>
        <td align="center"><font color="darkgreen"><b><?php echo $LDJobId; ?></b></font>
    </tr>
<?php
        while($row=$doctors->FetchRow())
        {
                //ucfirst viết hoa chữ đầu
                if ($mode=="a"){
                    echo '<tr>';
                    echo '<td align="center">';
                    echo '
                    <a href="#" onClick="addelem(\''.$mode.$elemid.'\',\'h'.$mode.$elemid.'\',\''.ucfirst($row['name_last']).'\',\''.ucfirst($row['name_first']).'\',\''.$row['date_birth'].'\',\''.$row['personell_nr'].'\',\''.$nr_t.'\',\''.$nr_d.'\',\''.$nr_s.'\',\''.$nr_s_t.'\',\''.$nr_s_d.'\',\''.$mode.'\')">
                    <img ';
                    echo createComIcon($root_path,'mans-gr.gif','0').'/></a></td>';
                }else{
                    echo '<tr>';
                    echo '<td align="center">';
                    echo '<a href="#" onClick="addelem(\''.$mode.$elemid.'\',\'h'.$mode.$elemid.'\',\''.ucfirst($row['name_last']).'\',\''.ucfirst($row['name_first']).'\',\''.$row['date_birth'].'\',\''.$row['personell_nr'].'\',\''.$nr_t.'\',\''.$nr_d.'\',\''.$nr_s.'\',\''.$nr_s_t.'\',\''.$nr_s_d.'\',\''.$mode.'\')">';
                    echo  '<img '.createComIcon($root_path,'mans-red.gif','0').'/></a></td>';
                }
                echo '<td align="center">';
                echo '<a href="#" onClick="addelem(\''.$mode.$elemid.'\',\'h'.$mode.$elemid.'\',\''.ucfirst($row['name_last']).'\',\''.ucfirst($row['name_first']).'\',\''.$row['date_birth'].'\',\''.$row['personell_nr'].'\',\''.$nr_t.'\',\''.$nr_d.'\',\''.$nr_s.'\',\''.$nr_s_t.'\',\''.$nr_s_d.'\',\''.$mode.'\')">';
                echo $row['personell_nr'].'</a></td>';
                
                echo '<td>';
                echo '<a href="#" onClick="addelem(\''.$mode.$elemid.'\',\'h'.$mode.$elemid.'\',\''.ucfirst($row['name_last']).'\',\''.ucfirst($row['name_first']).'\',\''.$row['date_birth'].'\',\''.$row['personell_nr'].'\',\''.$nr_t.'\',\''.$nr_d.'\',\''.$nr_s.'\',\''.$nr_s_t.'\',\''.$nr_s_d.'\',\''.$mode.'\')">';
                echo ucfirst($row['name_last']).' '.ucfirst($row['name_first']).'</a></td>';
                
                echo '<td>';
                echo '<a href="#" onClick="addelem(\''.$mode.$elemid.'\',\'h'.$mode.$elemid.'\',\''.ucfirst($row['name_last']).'\',\''.ucfirst($row['name_first']).'\',\''.$row['date_birth'].'\',\''.$row['personell_nr'].'\',\''.$nr_t.'\',\''.$nr_d.'\',\''.$nr_s.'\',\''.$nr_s_t.'\',\''.$nr_s_d.'\',\''.$mode.'\')">';
                echo $row['job_function_title'].'</a></td>';
                echo '</tr></a>';
        }
        echo '
            </font></ul>';
    }else{
        echo '<form><font face="verdana,arial" size=2>
        <img '.createMascot($root_path,'mascot1_r.gif','0','left').'  > '.$LDNoPersonList.'
        <p></form>';
        //<input type="button" value="'.$LDCreatePersonList.'" onClick="window.opener.location.href=\'doctors-dienst-personalliste.php?sid='.$sid.'&lang='.$lang.'&dept_nr='.$dept_nr.'&pmonth='.$month.'&pyear='.$year.'&retpath='.$retpath.'&ipath=plan\';window.opener.focus();window.close();">
        //</form>';
    }
?>
    </tbody>
</table>
<br>
<a href="javascript:closethis()">
    <img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDCloseWindow ?>">
</a>

</BODY>
</HTML>
