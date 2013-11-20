<?
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang_tables[]='search.php';

define('LANG_FILE','konsil.php');
define('NO_2LEVEL_CHK',1);

$sql="select td.* from care_test_request_duonghuyet as td , care_test_findings_duonghuyet as fd where fd.batch_nr=td.batch_nr and fd.status NOT IN ('deleted','hidden','inactive','void') and td.batch_nr='".$batch_nr."' and td.encounter_nr='".$pn."'";
//echo $sql;
$temp=$db->execute($sql);
if($temp->recordcount()){
	
	$stored_request=$temp->fetchrow();
}
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/core/inc_date_format_functions.php');
//require($root_path.'include/core/inc_checkdate_lang.php');
require_once ('../../js/jscalendar/calendar.php');
			$calendar = new DHTML_Calendar('../../js/jscalendar/', $lang, 'calendar-system', true);
			$calendar->load_files();
?>
<table border=0 bgcolor="#000000" cellpadding=1 cellspacing=0>
  <tr>
    <td>
	
	<table border=0 bgcolor="#ffffff" cellpadding=0 cellspacing=0>
   <tr>
     <td>
	
	   <table   cellpadding=0 cellspacing=1 border=0 width=700>
   <tr  valign="top">
   <td  bgcolor="#ffffff" rowspan=2>
 <?php
       
		   echo '<img src="'.$root_path.'main/imgcreator/barcode_label_single_large.php?sid='.$sid.'&lang='.$lang.'&fen='.$full_en.'&en='.$pn.'" width=282 height=178>';
		
		?></td>
      <td bgcolor="#ffffff"  class=fva2_ml10><div   class=fva2_ml10><font size=5 color="#0000ff"><b><?php echo $formtitle ?></b></font>
		 <br>
		 </td>
		 </tr>
	 <tr>
      <td bgcolor="#ffffff" align="right" valign="bottom">	 
	  <?php
		    echo '<font size=1 color="#990000" face="verdana,arial">'.$batch_nr.'</font>&nbsp;&nbsp;<br>';
			  echo "<img src='".$root_path."classes/barcode/image.php?code=".$batch_nr."&style=68&type=I25&width=145&height=40&xres=2&font=5' border=0>";
     ?>
	     </td>
		 </tr>
		 	
		<tr bgcolor="#ffffff">
		<td  valign="top" colspan=2 >
		
		<table border=0 cellpadding=1 cellspacing=1 width=100%>
    
	
    <tr>
      <td colspan=4><hr></td>
    </tr>

    
  </table>
  &nbsp;<br>
		
  </td>
</tr>
		 
	<tr bgcolor="#ffffff">
		<td colspan=2><div class=fva2_ml10><?php echo $LDChandoan ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
		<font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['clinical_info']) ?></font>
				</td>
		</tr>	
	<tr bgcolor="#ffffff">
		<td colspan=2><div class=fva2_ml10><?php echo $LDReqTestTim ?>:<p><img src="../../gui/img/common/default/pixel.gif" border=0 width=20 height=45 align="left">
		<font face="courier" size=2 color="#000099">&nbsp;&nbsp;<?php echo stripslashes($stored_request['test_request']) ?></font>
				</td>
		</tr>	


	
	<tr bgcolor="#ffffff">
		<td colspan=2 align="right"><div class=fva2_ml10>
		 <?php echo $LDDate ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php 
		
		            
					  echo @formatDate2Local($stored_request['send_date'],$date_format); 
					
				  ?></font>&nbsp;
  <?php echo $LDRequestingDoc ?>:
		<font face="courier" size=2 color="#000000">&nbsp;<?php echo $stored_request['send_doctor'] ?></font></div><br>
		</td>
    </tr>
	<tr bgcolor="#ffffff">
		
    </tr>	
	<tr bgcolor="#ffffff">
		<td colspan=2> 
		 <div class=fva2_ml10>&nbsp;<br><font color="#000099"><?php echo $LDNotesTempReport ?></font><br>
        <?php  echo stripslashes($stored_request['results']) ?>			
		 </td>
		</tr>	
		
	<tr bgcolor="#ffffff">
		<td colspan=2 align="right"><div class=fva2_ml10><font color="#000099">
		 <?php echo $LDDate ?>
		
		<?php
		//gjergji : new calendar
		
			//gjergji : new calendar
			echo @formatDate2Local($stored_request['results_date'],$date_format); 
			//end : gjergji	
		?>
				  
  <?php echo $LDReportLab ?>
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
		<a href="" ><img width="80" height="24" border="0" src="../../gui/img/control/default/vi/vi_cancel.gif"></a>
	<td>
  </tr>
</table> <!--  End of the outermost table bordering the form -->