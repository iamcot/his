<?
require('./roots.php');
$lang_tables[]='search.php';

define('LANG_FILE','konsil.php');
define('NO_2LEVEL_CHK',1);

//$sql="select td.* ,fd.results from care_test_request_visinh as td , care_test_findings_visinh as fd where fd.batch_nr=td.batch_nr and fd.status NOT IN ('deleted','hidden','inactive','void') and td.batch_nr='".$batch_nr."' and td.encounter_nr='".$pn."'";
$sql="SELECT fd.*,td.results AS result,td.* FROM care_test_request_visinh AS td, care_test_findings_visinh AS fd
      WHERE fd.batch_nr = td.batch_nr
      AND fd.status NOT IN('deleted','hidden','inactive','void')
      AND td.batch_nr='".$batch_nr."' and td.encounter_nr='".$pn."'
";
//echo $sql;
$temp=$db->execute($sql);
if($temp->recordcount()){
	
	$stored_request=$temp->fetchrow();
}


			//require('includes/inc_test_request_printout_fx.php');
?>
<link href="../../gui/css/themes/default/default.css" rel="stylesheet" type="text/css">
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
  <tr>
    <td>
	
	<table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
   <tr>
     <td>
	
	   <table   cellpadding=2 cellspacing=2 border=0 width=700>
   <tr  valign="top">
   <td  bgcolor="<?php echo $bgc1 ?>" rowspan=2>
 <?php
       
		   echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
		
		?></td>
      <td bgcolor="<?php echo $bgc1 ?>"  class=fva2_ml10><div   class=fva2_ml10><font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
		 
		 </td>
		 </tr>
	 <tr>
      <td bgcolor="<?php echo $bgc1 ?>" align="right" valign="bottom">	 
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
	<?php if($stored_request['lao']==1){
	echo'
      <td width="50%" class="adm_item">Yêu cầu xét nghiệm</td>
	  <td width="50%" class="adm_input">Lao</td>';
	  }elseif($stored_request['kstdr']==1){
	  echo'
      <td class="adm_item">Yêu cầu xét nghiệm</td>
	  <td class="adm_input">KSTDR</td>';
      }else{
	  echo'
      <td class="adm_item">Yêu cầu xét nghiệm</td>
	  <td class="adm_input">Huyết trắng</td>';
	  }
	  
  ?>
	
    <tr>
      <td colspan=6><hr></td>
    </tr>

   
  </table>
  &nbsp;<br>
		
  </td>
</tr>
		 
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10><?php echo $LDClinicalInfo ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
		<font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['clinical_info']) ?></font>
				</td>
		</tr>	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2><div class=fva2_ml10><?php echo $LDReqTest ?>:
		<?php
			/*$note="";
			if (is_object($item_test)){
				for ($i=0;$i<$item_test->RecordCount();$i++){
					$item = $item_test->FetchRow();
					$note=$note."<br>".$item['item_bill_name'];
				}
			}*/
			$note="<br>".$stored_request['test_request'];
		?>
		<font face="courier" size=2 color="#000099"><?php echo $note; ?></font>
				</td>
		</tr>	


	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10>
<!--		 --><?php //echo $LDDate ?><!--:-->
         <?php echo 'Ngày Yêu cầu XN' ?>:
                <font face="courier" size=2 color="#000000">&nbsp;<?php
					  echo @formatDate2Local($stored_request['send_date'],$date_format); 
					
				  ?></font>&nbsp;
  <?php echo $LDRequestingDoc ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php echo $stored_request['send_doctor'] ?></font></div><br>
		</td>
    </tr>
	
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 ><div class=fva2_ml10>
		Kết quả   mp
		 </td>
		
	</tr>
	<tr>
		 <td colspan=2>
		 <?php if($stored_request['kstdr']==1){
		echo'
			 <table>
				 <tbody>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Hồng cầu</td>
				   <td class="adm_input">';				   
		if($stored_request['hongcau']) echo $stored_request['hongcau'];		
		echo'</td>
				   <td class="adm_item">Trứng giun tóc</td>
				   <td class="adm_input">';				   
		if($stored_request['trunggiuntoc']) echo $stored_request['trunggiuntoc'];
		
		echo'</td>
				  </tr>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Bạch cầu</td>
				   <td class="adm_input">';				   
		if($stored_request['bachcau']) echo $stored_request['bachcau'];		
		echo'</td>
				   <td class="adm_item">Trứng giun móc</td>
				   <td class="adm_input">';				   
		if($stored_request['trunggiunmoc']) echo $stored_request['trunggiunmoc'];		
		echo'</td>
				  </tr>
				   <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Trứng giun đũa</td>
				   <td class="adm_input">';				   
		if($stored_request['trunggiundua']) echo $stored_request['trunggiundua'];		
		echo'</td>
				   <td class="adm_item">Sán</td>
				   <td class="adm_input">';				   
		if($stored_request['san']) echo $stored_request['san'];		
		echo'</td>
				  </tr>
				 </tbody>
			 </table>
		 ';	 
		 }elseif($stored_request['huyettrang']==1){
		 echo'
			 <table>
				 <tbody>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Nấm hạt men&nbsp;&nbsp;</td>
				   <td class="adm_input">';				   
		if($stored_request['namhatmen']) echo $stored_request['namhatmen'];		
		echo'</td>
				   <td class="adm_item">Trichomonas&nbsp;&nbsp;</td>
				   <td class="adm_input">';				   
		if($stored_request['trichomonas']) echo $stored_request['trichomonas'];		
		echo'</td>
				  </tr>
				  <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Cocci</td>
				   <td class="adm_input">';				   
		if($stored_request['cocci']) echo $stored_request['cocci'];		
		echo'</td>
				   <td class="adm_item">Baci(-)</td>
				   <td class="adm_input">';				   
		if($stored_request['bacisub']) echo $stored_request['bacisub'];		
		echo'</td>
				  </tr>
				   <tr bgcolor="'.$bgc1.'">
				   <td class="adm_item">Baci(+)</td>
				   <td class="adm_input">';				   
		if($stored_request['baciplus']) echo $stored_request['baciplus'];		
		echo'</td>
				  
				  </tr>
				 </tbody>
			 </table>
		 ';	 
		 }elseif($stored_request['lao']==1){
		 echo'
			 <table width=100% cellspacing="0" cellpadding="0" border="1">
				 <tbody>
				  <tr bgcolor="'.$bgc1.'">
					   <td align=center class="adm_item">Ngày nhận mẫu</td>
					   <td align=center class="adm_item">Mẫu đờm</td>
					   <td align=center class="adm_item">Trạng thái đờm đại thể</td>
					   <td align=center colspan=5>
						   <table width=100% border="1">
							   <tbody>
								<tr>
									<td align=center colspan=5 class="adm_item">Kết quả</td>
								</tr>
								<tr>
									
									<td width=45% align=center class="adm_item">(1-9 AFB)</td>
									<td width=15% align=center class="adm_item">Âm</td>
									<td width=13% align=center class="adm_item">1+</td>
									<td width=13% align=center class="adm_item">2+</td>
									<td width=13% align=center class="adm_item">3+</td>
								</tr>
							   </tbody>
						   </table>
					   </td>
				   </tr>
				   <tr>
					<td >
					  <nobr>
						'.formatDate2Local($stored_request['date_mau_1'],$date_format).'
					  </nobr>
					</td>
					<td align=center>
						1
					</td>
					<td>
						';
					if($stored_request['status_mau_1']) echo $stored_request['status_mau_1'];	
					echo'
					</td>';
					if($stored_request['results_mau_1']=="am"){
					echo'<td class="adm_input" align=center width=29%>						
					</td>
					<td align=center width=14%>
						<input type="radio" value="am" checked name="results_mau_1">
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>';
					}elseif($stored_request['results_mau_1']=="1+"){
					echo'<td class="adm_input" align=center width=29%>						
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" checked name="results_mau_1">
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>';
					}elseif($stored_request['results_mau_1']=="2+"){
					echo'<td class="adm_input" align=center width=29%>
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" checked name="results_mau_1"
					</td>
					<td class="adm_input" align=center width=13%>
					</td>';
					}elseif($stored_request['results_mau_1']=="3+"){
						echo'<td class="adm_input" class="adm_input" align=center width=29%>						
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" checked name="results_mau_1">
					</td>';
					}else{
					echo'<td class="adm_input" align=center width=29%>
						';
						if($stored_request['results_mau_1']) echo $stored_request['results_mau_1'];
					echo'>
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>';
					}
				echo'
				   </tr> 
				   <tr>
					<td ><nobr>';
						echo formatDate2Local($stored_request['date_mau_2'],$date_format);
				echo'	</nobr></td>
					<td align=center>
						2
					</td>
					<td>
						';
					if($stored_request['status_mau_2']) echo $stored_request['status_mau_2'];	
					echo'
					</td>';
					if($stored_request['results_mau_2']=="am"){
					echo'<td class="adm_input" align=center width=29%>						
					</td>
					<td align=center width=14%>
						<input type="radio" value="am" checked name="results_mau_2">
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>';
					}elseif($stored_request['results_mau_2']=="1+"){
					echo'<td class="adm_input" align=center width=29%>						
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" checked name="results_mau_2">
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>';
					}elseif($stored_request['results_mau_2']=="2+"){
					echo'<td class="adm_input" align=center width=29%>
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" checked name="results_mau_2"
					</td>
					<td class="adm_input" align=center width=13%>
					</td>';
					}elseif($stored_request['results_mau_2']=="3+"){
						echo'<td class="adm_input" class="adm_input" align=center width=29%>						
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" checked name="results_mau_2">
					</td>';
					}else{
					echo'<td class="adm_input" align=center width=29%>
						';
						if($stored_request['results_mau_2']) echo $stored_request['results_mau_2'];
					echo'>
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>';
					}
					echo'
				   </tr> 
				   <tr>
					<td style="padding-top:2px;margin-left:2px;">
						<nobr >'.formatDate2Local($stored_request['date_mau_3'],$date_format).'</nobr>
					</td>
					<td align=center>
						3
					</td>
					<td align=left>
						';
					if($stored_request['status_mau_3']) echo $stored_request['status_mau_3'];	
					echo'
					</td>';
					if($stored_request['results_mau_3']=="am"){
					echo'<td class="adm_input" align=center width=29%>						
					</td>
					<td align=center width=14%>
						<input type="radio" value="am" checked name="results_mau_3">
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>';
					}elseif($stored_request['results_mau_3']=="1+"){
					echo'<td class="adm_input" align=center width=29%>						
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="1+" checked name="results_mau_3">
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>
					<td class="adm_input" align=center width=13%>						
					</td>';
					}elseif($stored_request['results_mau_3']=="2+"){
					echo'<td class="adm_input" align=center width=29%>
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="2+" checked name="results_mau_3"
					</td>
					<td class="adm_input" align=center width=13%>
					</td>';
					}elseif($stored_request['results_mau_3']=="3+"){
						echo'<td class="adm_input" class="adm_input" align=center width=29%>						
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td align=center width=13%>
						<input type="radio" value="3+" checked name="results_mau_3">
					</td>';
					}else{
					echo'<td class="adm_input" align=center width=29%>
						';
						if($stored_request['results_mau_3']) echo $stored_request['results_mau_3'];
					echo'>
					</td>
					<td class="adm_input" align=center width=14%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>
					<td class="adm_input" align=center width=13%>
					</td>';
					}
					echo'
				   </tr> 
				   </tbody>
			</table>';
		 }
		 ?>
		 </td>
	</tr>	 
		
		
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2> 
		 <div class=fva2_ml10>&nbsp;<br><font color="#000099"><?php echo $LDNotesTempReport ?></font><br>
         <?php  echo stripslashes($stored_request['result']) ?>
		 </td>
		</tr>	
		
	<tr bgcolor="<?php echo $bgc1 ?>">
		<td colspan=2 align="right"><div class=fva2_ml10><font color="#000099">
<!--		 --><?php //echo $LDDate ?>
         <?php echo 'Ngày Trả kết quả XN' ?>

		<?php
			//gjergji : new calendar
			
			echo @formatDate2Local($stored_request['results_date'],$date_format);
			//end : gjergji	
		?>
				  
 Bác sĩ xét nghiệm :
        <?php  echo $stored_request['results_doctor']; ?>
		</td>
    </tr>
		</table> 
		

	 </td>
   </tr>
 </table>
	
	</td>
  </tr>
 <tr bgcolor="#ffffff">
	<td align="right">
		<a href="" ><img width="80" height="24" border="0" src="../../gui/img/control/default/vi/vi_back2.gif"></a>
	<td>
  </tr>
</table>  <!--  End of the outermost table bordering the form -->