<?php
	if(!isset($i))
		$i=$_GET['i'];
	if(!isset($stt_nr))
		$stt_nr=$_GET['stt_nr'];
	echo '<tr bgColor="#ffffff" >
		<td width="118" height="20" align="center" bgColor="#ffffff"><input id="encoder'.$i.'" name="encoder'.$i.'" type="text" value="'.$rowIssue['product_encoder'].'" style="text-align:center;border-color:white;border-style:solid;" readonly size="10"></td>
		<td width="253" align="center" bgColor="#ffffff"><input id="medicine'.$i.'"  name="medicine'.$i.'" type="text" size="27" value="'.$rowIssue['product_name'].'" '.$readonly.' '.$style.' onFocus="Medicine_AutoComplete('.$i.')"  >&nbsp;<a href="javascript:searchMedicine('.$i.')"><img src="../../gui/img/common/default/search_radio.jpg"></a><div id="hint"></div></td>
		<td width="97" align="center" bgColor="#ffffff"><input id="units'.$i.'" name="units'.$i.'" type="text" size=7 value="'.$rowIssue['units'].'" '.$readonly.' '.$style.'></td>
		<td width="97" align="center" bgColor="#ffffff"><input id="inventory'.$i.'" name="inventory'.$i.'" type="text" value="'.$inventory.'" size="7" style="text-align:center;border-color:white;border-style:solid;" readonly></td>
		<td width="97" align="center" bgColor="#ffffff"><input name="sum'.$i.'" type="text" size=7 value="'.$rowIssue['number_request'].'" '.$readonly.' '.$style.'></td>
		<td width="100" bgColor="#ffffff"><input id="cost'.$i.'" name="cost'.$i.'" type="hidden" size=7 value="'.$rowIssue['cost'].'" ></td>
		<td width="200" align="center" bgColor="#ffffff"><input name="note'.$i.'" type="text" value="'.$rowIssue['note'].'" size=20></td>
	</tr>';
?>
