<?php
	if(!isset($i))
		$i=$_GET['i'];
	if(!isset($stt_nr))
		$stt_nr=$_GET['stt_nr'];
	echo '<tr bgColor="#ffffff" >
		<td width="93" align="center" bgColor="#ffffff"><input name="encoder'.$i.'" type="text" value="'.$rowIssue['product_encoder'].'" '.$readonly.' '.$style.' size=8></td>
		<td width="195" align="center" bgColor="#ffffff"><input id="chemical'.$i.'"  name="chemical'.$i.'" type="text" size=20 value="'.$rowIssue['product_name'].'" '.$readonly.' '.$style.'></td>
		<td width="94" align="center" bgColor="#ffffff"><input name="units'.$i.'" type="text" size=7 value="'.$rowIssue['units'].'" '.$readonly.' '.$style.'></td>
		<td width="94" align="center" bgColor="#ffffff"><input name="sumpres'.$i.'" type="text" size=7 value="'.$rowIssue['sumpres'].'" '.$readonly.' '.$style.'></td>
		<td width="93" align="center" bgColor="#ffffff">'.$inventory.'</td>
		<td width="93" align="center" bgColor="#ffffff"><input name="plus'.$i.'" type="text" size=7 value="'.$rowIssue['plus'].'" onFocus="startCalc('.$i.');" onBlur="stopCalc();"></td>
		<td width="73" align="center" bgColor="#ffffff"><input name="sum'.$i.'" id="sum'.$i.'" type="text" size=3 value="'.$rowIssue['number_request'].'" '.$readonly.' '.$style.'></td>
		<td width="66" bgColor="#ffffff">&nbsp;</td>
		<td width="93" align="center" bgColor="#ffffff"><input name="note'.$i.'" type="text" value="'.$rowIssue['note'].'" size=8></td>
	</tr>';
?>
