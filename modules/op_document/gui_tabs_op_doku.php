<!-- Creates the tabs for the patient registration module  -->
<?php
if(!isset($notabs)||!$notabs){
?>
<!-- Tabs  -->
<tr>
<td colspan=3><?php if($target=="entry")  $img='document-blue.gif'; //echo '<img '.createLDImgSrc($root_path,'admit-blue.gif','0').' alt="'.$LDAdmit.'">';
								else{ $img='document-gray.gif';}
                                                                // edit 16/11-Huỳnh /////////////////
							echo'<a href="op-doku-start.php'.URL_APPEND.'&target=entry&dept_nr='.$dept_nr.'"><img '.createLDImgSrc($root_path,$img,'0').' alt="'.$LDDocument.'"'; if($cfg['dhtml'])echo'class="fadeOut" '; echo '></a>';
							if($target=="search") $img='such-b.gif'; //echo '<img '.createLDImgSrc($root_path,'such-b.gif','0').' alt="'.$LDSearch.'">';
								else{ $img='such-gray.gif'; }
							echo '<a href="op-doku-search.php'.URL_APPEND.'&target=search&dept_nr='.$dept_nr.'&all_depts='.$all_depts.'"><img '.createLDImgSrc($root_path,$img,'0').' alt="'.$LDSearch.'" ';if($cfg['dhtml'])echo'class="fadeOut" '; echo '></a>';
							if($target=="archiv") $img='arch-blu.gif'; //echo '<img '.createLDImgSrc($root_path,'arch-blu.gif','0').'  alt="'.$LDArchive.'">';
								else{$img='arch-gray.gif'; }
							echo '<a href="op-doku-archiv.php'.URL_APPEND.'&target=archiv&dept_nr='.$dept_nr.'"><img '.createLDImgSrc($root_path,$img,'0').' alt="'.$LDArchive.'" ';if($cfg['dhtml'])echo'class="fadeOut" '; echo '></a>';
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

