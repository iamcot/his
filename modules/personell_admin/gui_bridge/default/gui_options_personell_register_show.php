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
$sql="select role_nr from care_personell_assignment where personell_nr='".$personell_nr."' and date_end='0000-00-00' and date_end < NOW() and status <> 'delete' ";

$temp=$db->Execute($sql);

?>
<img <?php echo createComIcon($root_path,'angle_left_s.gif',0); ?>>
<br>
<FONT color="#cc0000">
<?php echo $LDOptions4Employee; ?>
</font>

<TABLE cellSpacing=0 cellPadding=0 bgColor=#999999 border=0>
        <TBODY>
        <TR>
          <TD>
            <TABLE cellSpacing=1 cellPadding=2 bgColor=#999999 
            border=0>
              <TBODY>
				<?php 
				if($temp->RecordCount()){
					$buf=$temp->FetchRow();
					if($buf['role_nr']==17){
					echo'    <TR bgColor=#eeeeee> <td align=center><img '.createComIcon($root_path,'man-whi.gif','0').' ></td>
                <TD vAlign=top >						
						'.$LDAssignDoctorDept.'
				   </FONT></TD>
                </TR>';			   
          Spacer(); 
					}else{
				echo'	 <TR bgColor=#eeeeee><td align=center><img '.createComIcon($root_path,'nurse.gif','0').'></td>
                <TD vAlign=top width=150>                    
				'.$LDAssignNurseDept.'
				   </FONT></TD>
                </TR>';
			   
           Spacer();
					}
				
				} else{
		
              echo'    <TR bgColor=#eeeeee> <td align=center><img '.createComIcon($root_path,'man-whi.gif','0').' ></td>
                <TD vAlign=top >
						<a href="'.$root_path.'modules/doctors/doctors-select-dept.php'.URL_APPEND.'&target=plist&nr='.$personell_nr.'&user_origin=personell_admin">'.$LDAssignDoctorDept.'</a>
				   </FONT></TD>
                </TR>';			   
          Spacer(); 
				  
            echo'	 <TR bgColor=#eeeeee><td align=center><img '.createComIcon($root_path,'nurse.gif','0').'></td>
                <TD vAlign=top width=150> 
                   
				<a href="'.$root_path.'modules/nursing_or/nursing-or-select-dept.php'.URL_APPEND.'&target=plist&nr='.$personell_nr.'&user_origin=personell_admin">'.$LDAssignNurseDept.'</a>
				   </FONT></TD>
                </TR>';
			   
           Spacer();
		   } ?>
			 <TR bgColor=#eeeeee><td align=center><img <?php echo createComIcon($root_path,'man-whi.gif','0') ?>></td>
                <TD vAlign=top width=150> 
                   
				<a href="<?php echo $root_path; ?>modules/personell_admin/xem_qua_trinh_cong_tac.php<?php echo URL_APPEND."&nr=$personell_nr"; ?>"><?php echo $LDXemquatrinhcongtac; ?></a>
				   </FONT></TD>
                </TR>
				
			<?php 
			$sql="select role_nr from care_personell_assignment where personell_nr='".$personell_nr."'";
			$temp=$db->Execute($sql);
			if($temp->RecordCount()){
			$row=$temp->FetchRow();
			if($row['role_nr']==17){
			Spacer();
			echo'
			 <TR bgColor=#eeeeee><td align=center><img  '.createComIcon($root_path,'man-whi.gif','0').'></td>
                <TD vAlign=top width=150> 
                   
				<a href="'.$root_path.'modules/doctors/xem_cham_cong.php'.URL_APPEND.'&nr='.$personell_nr.'";">Xem chấm công</a>
				   </FONT></TD>
                </TR>
			';
			} else{
			Spacer();
			echo'
			 <TR bgColor=#eeeeee><td align=center><img  '.createComIcon($root_path,'man-whi.gif','0').'></td>
                <TD vAlign=top width=150> 
                   
				<a href="'.$root_path.'modules/nursing_or/xem_cham_cong.php'.URL_APPEND.'&nr='.$personell_nr.'";">Xem chấm công</a>
				   </FONT></TD>
                </TR>
			';
			}
			}else{
			Spacer();
			echo'
			 <TR bgColor=#eeeeee><td align=center><img  '.createComIcon($root_path,'man-whi.gif','0').'></td>
                <TD vAlign=top width=150>                    
				Xem chấm công
				   </FONT></TD>
                </TR>
			';
			}
			?> 
			<?php 			
			Spacer();
			echo'
			 <TR bgColor=#eeeeee><td align=center><img  '.createComIcon($root_path,'man-whi.gif','0').'></td>
                <TD vAlign=top width=150> 
                   
				<a href="'.$root_path.'modules/doctors/xem_luong.php'.URL_APPEND.'&nr='.$personell_nr.'";">Xem lương</a>
				   </FONT></TD>
                </TR>
			';
			
			?> 
           <?php Spacer(); ?>
				  
              <TR bgColor=#eeeeee>  <td align=center><img <?php echo createComIcon($root_path,'violet_phone.gif','0') ?>></td>
                <TD vAlign=top > 
                   
			 <a href="<?php echo $root_path.'modules/phone_directory/phone_edit.php'.URL_APPEND.'&user_origin=pers&nr='.$personell_nr; ?>"><?php echo $LDAddPhoneInfo ?></a>
				   </FONT></TD>
                </TR>				 
			   
<!--  			   
           <?php Spacer(); ?>
				  
               <TR bgColor=#eeeeee><td align=center><img <?php echo createComIcon($root_path,'disc_repl.gif','0') ?>></td>
                <TD vAlign=top > 
                   
				  <a href="javascript:alert('Function not  available yet')"><?php echo $LDPayrollOptions ?></a>
				   </FONT></TD>
                </TR>
			   
           <?php Spacer(); ?>
				  
				  <TR bgColor=#eeeeee><td align=center><img <?php echo createComIcon($root_path,'document.gif','0') ?>></td>
                <TD vAlign=top > 
                   <nobr>
				 <a href="javascript:alert('Function not  available yet')"><?php echo $LDLegalDocuments ?></a>
				  </nobr> </FONT></TD>
                </TR>
 -->			   
           <?php Spacer(); ?>
				  
				  <TR bgColor=#eeeeee><td align=center><img <?php echo createComIcon($root_path,'bn.gif','0') ?>></td>
                <TD vAlign=top > 
                   <nobr>
				 <a href="<?php echo "person_register_show.php".URL_REDIRECT_APPEND."&pid=$pid&from=$from"; ?>"><?php echo $LDShowPersonalData ?></a>
				  </nobr> </FONT></TD>
                </TR>
    							</TBODY>
		</TABLE>
		</TD></TR>
		</TBODY>
		</TABLE>
