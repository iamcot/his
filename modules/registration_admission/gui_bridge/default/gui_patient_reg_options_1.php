<?php

function Spacer()
{
/*?>
<TR bgColor=#dddddd height=1>
                <TD colSpan=3><IMG height=1 
                  src="../../gui/img/common/default/pixel.gif" 
                  width=5></TD></TR>
<?php
*/}
?>
<img <?php echo createComIcon($root_path,'angle_left_s.gif',0,'',TRUE); ?>>
<br>
<FONT face="Verdana,Helvetica,Arial" size=2 color="#cc0000">
<?php echo $LDOptsForPerson ?>  <a href="javascript:gethelp('preg_options.php')"><img <?php echo createComIcon($root_path,'frage.gif','0','absmiddle',TRUE) ?>></a>
</font>

<TABLE cellSpacing=0 cellPadding=0 class="submenu_frame" border=0>
        <TBODY>
        <TR>
          <TD>
            <TABLE cellSpacing=1 cellPadding=2 border=0>
              <TBODY class="submenu">
                <!-- edit 16/11-Huỳnh -->
                <?php Spacer(); ?>
				  <TR><td align=center><img <?php echo createComIcon($root_path,'new_address.gif','0','',FALSE) ?>></td>
                <TD vAlign=top width=150><FONT
                  face="Verdana,Helvetica,Arial" size=2>
				  <?php if(!$is_discharged){ ?>
				<a href="<?php echo $root_path."modules/nursing/nursing-station-patientdaten-doconsil-chemlabor.php".URL_APPEND."&pn=".$_SESSION['sess_en'] ?>&edit=1&status=&target=chemlabor&user_origin=lab&noresize=1&mode="><?php echo $LDTestMedLab ?></a>
				 <?php }else{ 
				 echo $LDTestMedLab;
				 } ?>
				  </FONT></TD>
                </TR>

               

                <?php Spacer(); ?>
				  <TR><td align=center><img <?php echo createComIcon($root_path,'new_address.gif','0','',FALSE) ?>></td>
                <TD vAlign=top width=150><FONT
                  face="Verdana,Helvetica,Arial" size=2>
				   <?php if(!$is_discharged){ ?>
				<a href="<?php echo $root_path."modules/nursing/nursing-station-patientdaten-doconsil-visinh.php".URL_APPEND."&pn=".$_SESSION['sess_en'] ?>&edit=1&status=&target=baclabor&user_origin=lab&noresize=1&mode="><?php echo $LDTestBacLab ?></a>
				<?php }else{ 
				 echo $LDTestBacLab;
				 } ?>				  </FONT></TD>
                </TR> 
				<?php Spacer(); ?>
				  <TR><td align=center><img <?php echo createComIcon($root_path,'new_address.gif','0','',FALSE) ?>></td>
                <TD vAlign=top width=150><FONT
                  face="Verdana,Helvetica,Arial" size=2>
				  <?php if(!$is_discharged){ ?>
				<a href="<?php echo $root_path."modules/nursing/nursing-station-patientdaten-doconsil-patho.php".URL_APPEND."&pn=".$_SESSION['sess_en'] ?>&edit=1&status=&target=patho&user_origin=lab&noresize=1&mode="><?php echo $LDTestPathLab ?></a>
				  <?php }else{
				  echo $LDTestPathLab;
				  }?>
				  </FONT></TD>
                </TR>

                <?php Spacer(); ?>
				  <TR><td align=center><img <?php echo createComIcon($root_path,'new_address.gif','0','',FALSE) ?>></td>
                <TD vAlign=top width=150><FONT
                  face="Verdana,Helvetica,Arial" size=2>
				  <?php if(!$is_discharged){ ?>
				<a href="<?php echo $root_path."modules/nursing/nursing-station-patientdaten-doconsil-blood.php".URL_APPEND."&pn=".$_SESSION['sess_en'] ?>&edit=1&status=&target=blood&user_origin=lab&noresize=1&mode="><?php echo $LDTestBloodBank ?></a>
				   <? }else{
					echo $LDTestBloodBank;
					}   ?>
				   </FONT></TD>
                </TR>
				 <?php Spacer(); ?>
				  <TR><td align=center><img <?php echo createComIcon($root_path,'new_address.gif','0','',FALSE) ?>></td>
                <TD vAlign=top width=150><FONT
                  face="Verdana,Helvetica,Arial" size=2>
				  <?php if(!$is_discharged){ ?>
				<a href="<?php echo $root_path."modules/nursing/nursing-station-patientdaten-doconsil-duonghuyet.php".URL_APPEND."&pn=".$_SESSION['sess_en'] ?>&edit=1&status=&target=duonghuyet&user_origin=lab&noresize=1&mode="><?php echo $LDTestDuongHuyet ?></a>
				   <? }else{
					echo $LDTestDuongHuyet;
					}   ?>
				   </FONT></TD>
                </TR>
			<?php Spacer(); ?>
				  <TR><td align=center><img <?php echo createComIcon($root_path,'new_address.gif','0','',FALSE) ?>></td>
                <TD vAlign=top width=150><FONT
                  face="Verdana,Helvetica,Arial" size=2>
				  <?php if(!$is_discharged){ ?>
				<a href="<?php echo $root_path."modules/nursing/nursing-station-patientdaten-doconsil-other.php".URL_APPEND."&pn=".$_SESSION['sess_en'] ?>&edit=1&status=&target=other&user_origin=lab&noresize=1&mode=">Xét nghiệm khác</a>
				   <? }else{
					echo "Xét nghiệm khác";
					}   ?>
				   </FONT></TD>
                </TR>

               
                <?php Spacer(); ?>
				  <TR><td align=center><img <?php echo createComIcon($root_path,'new_address.gif','0','',FALSE) ?>></td>
                <TD vAlign=top width=150><FONT
                  face="Verdana,Helvetica,Arial" size=2>
				   <?php if(!$is_discharged){ ?>
				<a href="<?php echo $root_path."modules/nursing/nursing-station-patientdaten-doconsil-chuyenkhoa.php".URL_APPEND."&pn=".$_SESSION['sess_en'] ?>&edit=1&status=&target=chuyenkhoa&user_origin=lab&noresize=1&mode="><?php echo $LDTestChuyenKhoa ?></a>
				    <? }else{
					echo $LDTestChuyenKhoa;
					}   ?>
				   </FONT></TD>
                </TR>
               

				</TBODY>
		</TABLE>
		</TD></TR>
		</TBODY>
		</TABLE>
