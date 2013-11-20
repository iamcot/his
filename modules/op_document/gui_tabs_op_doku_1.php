<!-- Creates the tabs for the patient registration module  -->
<?php
if(!isset($notabs)||!$notabs){
?>
<!-- Tabs  -->
<tr>
<td colspan=2><?php // edit 16/11-Huỳnh /////////////////
                    if($target=="entry")  $img='surgery_blue.gif'; //echo '<img '.createLDImgSrc($root_path,'admit-blue.gif','0').' alt="'.$LDAdmit.'">';
                    else{ $img='surgery_gray.gif';}
                    echo'<a href="op-doku-start_1.php'.URL_APPEND.'&mode=select&pn='.$pn.'&dept_nr=39&target=entry&batch_nr='.$batch_nr.'"><img '.createLDImgSrc($root_path,$img,'0').' alt="'.$LDOrSurgery.'"'; if($cfg['dhtml'])echo'class="fadeOut" '; echo '></a>';
//                    if($target=="anesthesia") $img='anesthesia_blue.gif'; //echo '<img '.createLDImgSrc($root_path,'such-b.gif','0').' alt="'.$LDSearch.'">';
//                            else{ $img='anesthesia_gray.gif'; }
//                    echo '<a href="op-doku-start_2.php'.URL_APPEND.'&mode=select&pn='.$pn.'&dept_nr=39&target=anesthesia&batch_nr='.$batch_nr.'"><img '.createLDImgSrc($root_path,$img,'0').' alt="'.$LDSearch.'" ';if($cfg['dhtml'])echo'class="fadeOut" '; echo '></a>';
                    //echo '<a href="op-pflege-logbuch-start.php'.URL_APPEND.'&internok=&dept_nr=39&saal=1"><img '.createLDImgSrc($root_path,$img,'0').' alt="'.$LDSearch.'" ';if($cfg['dhtml'])echo'class="fadeOut" '; echo '></a>';
                    //echo '<a href="oploginput.php'.URL_REDIRECT_APPEND.'&mode=get&enc_nr='.$pn.'&dept_nr=39&saal=1"><img '.createLDImgSrc($root_path,$img,'0').' alt="'.$LDSearch.'" ';if($cfg['dhtml'])echo'class="fadeOut" '; echo '></a>';
                    
                    //////////////////////////////////////////////
                    ?></td>
</tr>
<?php
}
?>
<!--  Horizontal blue line below the tabs -->
<tr>
<td colspan=3  bgcolor=#00009c><img src="../../../gui/img/common/default/p.gif" border=0 width=1 height=5><?php
if(!empty($subtitle)) echo '<font color="#fefefe" SIZE=3  FACE="verdana,Arial"><b>:: '.$subtitle.'</b>';
?></td>
</tr>

