<?php
	if(!isset($i))
		$i=$_GET['i'];
	if(!isset($stt_nr))
		$stt_nr=$_GET['stt_nr'];
		
	if(!$rowReport['lotid'])
		$rowReport['lotid']=date('dmY');
	
	if($rowReport['price']!=''){
		if(round($rowReport['price'])==round($rowReport['price'],3))
			$temp_price=number_format($rowReport['price']);
		else $temp_price=number_format($rowReport['price'],3);
		//$temp_price = number_format($rowReport['price'],2);
		$temp_number = number_format($rowReport['number']);
		$temp_total = number_format($rowReport['price']*$rowReport['number']);
	}
	
	echo '<tr style="height:auto" bgColor="#ffffff">
				<td align="center" style="border-bottom: solid 1px #C3C3C3;"><a href="javascript:;" onclick="deleteRow('.$stt_nr.')">[x]</a></td>
                <td align="center" class="cell1">'.($stt_nr-1).'</td>
                <td class="cell1"><input class="input3" type="text" name="medicine'.$i.'" id="medicine'.$i.'" value="'.$rowReport['product_name'].'"  onFocus="Medicine_AutoComplete('.$i.');" >&nbsp;<a href="javascript:searchMedicine('.$i.')"><img src="../../gui/img/common/default/search_radio.jpg"></a><div id="hint"></div></td>
                <td class="cell1"><input class="input2" type="text" name="unit'.$i.'" id="unit'.$i.'" value="'.$rowReport['unit_name_of_medicine'].'"  ></td>
                <td class="cell1"><input class="input2" type="text" name="encoder'.$i.'" id="encoder'.$i.'" value="'.$rowReport['product_encoder'].'"  ></td>
                <td class="cell1"><input class="input2" type="text" name="lotid'.$i.'" id="lotid'.$i.'" value="'.$rowReport['lotid'].'" ></td>
                <td class="cell1"><input class="input2" type="text" name="exp'.$i.'" id="exp'.$i.'"  value="'.$rowReport['exp_date'].'" ></td>
                <td class="cell1"><input class="input2" type="text" name="cost'.$i.'" id="cost'.$i.'" value="'.$temp_price.'"   onkeyup="OnchangeFormat(this)" onBlur="CalCost('.$i.')" ></td>
                <td class="cell1"><input class="input2" type="text" name="number'.$i.'" id="number'.$i.'" value="'.$temp_number.'"   onkeyup="OnchangeFormat(this)" onBlur="CalCost('.$i.')" ></td>
                <td class="cell1">&nbsp;</td>
                <td class="cell1"><input class="input2" type="text" name="totalcost'.$i.'" id="totalcost'.$i.'" value="'.$temp_total.'" ></td>
                <td class="cell1"><input class="input2" type="text" name="note'.$i.'" value="'.$rowReport['note'].'" ></td>
            </tr>';
?>
