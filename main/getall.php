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
define('LANG_FILE','doctors.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

require_once($root_path.'include/core/inc_date_format_functions.php');
        
require_once($root_path.'include/care_api_classes/class_personell.php');
$pers_obj=new Personell;
$allpersonell=&$pers_obj->getAllOfDept(39);

$wkday=date("w",mktime(0,0,0,$month,$elemid+1,$year));
?>

<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $LDInfo4Duty ?></TITLE>

<script language="javascript">

function closethis()
{
	window.opener.focus();
	window.close();
}

function addelem(elem,last,first)
{
    
       window.opener.document.getElementById(elem).value=last+' '+first;
	
   
}


</script>

<STYLE type=text/css>
div.box { border: double; border-width: thin; width: 100%; border-color: black; }
</style>

</HEAD>
<BODY  LINK="navy" VLINK="navy" onLoad="if (window.focus) window.focus()" >

<font face=verdana,arial size=4 color=maroon>
<b>


</b>
</font>
<p>

<?php
if($pers_obj->record_count){
    echo '<ul>
	    <font face="verdana,arial" size=2>';

    while($row=$allpersonell->FetchRow()){
	    
            if ($mode=="a"){
                echo '
                <a href="#" onClick="addelem(\''.$mode.$elemid.'\',\''.ucfirst($row['name_last']).'\',\''.ucfirst($row['name_first']).'\')">
                <img ';
                echo createComIcon($root_path,'mans-gr.gif','0').'/>';
            }else{
                echo '<a href="#" onClick="addelem(\''.$mode.$elemid.'\',\''.ucfirst($row['name_last']).'\',\''.ucfirst($row['name_first']).'\')">';
                echo  '<img '.createComIcon($root_path,'mans-red.gif','0').'/>';
            }
	    echo ucfirst($row['name_last']).' '.ucfirst($row['name_first']).'</a> ( '.$row['job_function_title'].' )';
		
	   echo' <br>';
    }
    echo '
	</font></ul>';
}
else
{
    echo '<form><font face="verdana,arial" size=2>
    <img '.createMascot($root_path,'mascot1_r.gif','0','left').'  > '.$LDNoPersonList.'
    <p>
    <input type="button" value="'.$LDCreatePersonList.'" onClick="window.opener.location.href=\'nursing-or-dienst-personalliste.php?sid='.$sid.'&lang='.$lang.'&dept_nr='.$dept_nr.'&pmonth='.$month.'&pyear='.$year.'&retpath='.$retpath.'&ipath=plan\';window.opener.focus();window.close();">
    </form>';
}
?>
<p><br>
<a href="javascript:closethis()"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> alt="<?php echo $LDCloseWindow ?>"></a>

</BODY>

</HTML>
