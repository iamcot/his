<?php
	if(!isset($i))
		$i=$_GET['i'];
	if(!isset($stt_nr))
		$stt_nr=$_GET['stt_nr'];
		
	echo '<tr bgColor="#ffffff" >';
	echo 	'<td align="center" bgColor="#ffffff"><input id="encoder'.$i.'" name="encoder'.$i.'" type="text" value="'.$rowReport['product_encoder'].'" style="text-align:center;border-color:white;border-style:solid;" readonly size="8"></td>';
	echo	'<td align="center" bgColor="#ffffff"><input id="chemical'.$i.'"  name="chemical'.$i.'" type="text" size="22" value="'.$rowReport['product_name'].'" '.$readonly.' '.$style.' onFocus="Chemical_AutoComplete('.$i.')">&nbsp;<img src="../../../gui/img/common/default/search_radio.jpg" OnClick="searchChemical('.$i.')"><div id="hint"></div> </td>';
			//Don vi
	echo 	'<td align="center" bgColor="#ffffff"><input name="unit'.$i.'" id="unit'.$i.'"  type="text" size=7 value="'.$rowReport['unit_name_of_chemical'].'" '.$readonly.' '.$style.'></td>';
			//So lo sx
	echo	'<td align="center" bgColor="#ffffff"><input name="lotid'.$i.'" id="lotid'.$i.'" type="text" value="'.$rowReport['product_lot_id'].'" size=7 '.$readonly.' '.$style.'></td>';
			//HSD
	echo	'<td align="center" bgColor="#ffffff"><input id="exp'.$i.'" name="exp'.$i.'" type="text" size=7 value="'.$rowReport['exp_date'].'" style="text-align:center;border-color:white;border-style:solid;" readonly size="8"></td>';
			//Don gia
	echo	'<td bgColor="#ffffff"><input id="cost'.$i.'" name="cost'.$i.'" type="text" value="'.$rowReport['cost'].'" style="text-align:center;border-color:white;border-style:solid;" readonly size="8"></td>';
			//So luong
	echo    '<td align="center" bgColor="#ffffff"><input id="number'.$i.'" name="number'.$i.'" type="text" value="'.$rowReport['number'].'" size=5 '.$readonly.' '.$style.' onBlur="CalCost('.$i.')" ></td>';
			//Thanh tien
	$totalcost = $rowReport['cost']*$rowReport['number'];
	echo 	'<td bgColor="#ffffff"><input id="totalcost'.$i.'" name="totalcost'.$i.'" type="text" value="'.$totalcost.'"  style="text-align:center;border-color:white;border-style:solid;" readonly size="8"></td>';
			//note
	echo	'<td align="center" bgColor="#ffffff"><input name="note'.$i.'" type="text" value="'.$rowReport['note'].'" size="8"></td>';
        echo '<input type="hidden" id="ward_nr'.$i.'" name="ward_nr'.$i.'" value="';
        if($list_info[1]!=0 || $list_info[1]){
            echo $list_info[1];
        }else echo $rowReport['ward_nr'];
        echo '" /></tr>';
?>
