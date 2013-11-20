<?php
if (!isset($root_path)) {
	$root_path='../../';
	$src_up= 'src="'.$root_path.'gui/img/common/default/arw_up.gif"';
	$src_down= 'src="'.$root_path.'gui/img/common/default/arw_down.gif"';
	$LDMedipotUnit='cái'; $LDMedicineUse='uống'; 
} else {
	$src_up= createComIcon($root_path,'arw_up.gif','0','',TRUE);
	$src_down= createComIcon($root_path,'arw_down.gif','0','',TRUE);
}

		if(!$medicine_pres)
		{
			$medicine_pres['note']=$LDMedipotUnit;
			$medicine_pres['number_of_unit']='3';
			$medicine_pres['time_use']='8h-14h-20h';
		}
		if(!$split_desciption)
		{
			$split_desciption[0]=$LDMedicineUse;
			$split_desciption[1]='1';
			$split_desciption[2]=$LDMedipotUnit;
		}
		if(!isset($i))
			$i=$_GET['i'];
			
					echo '<tr bgcolor="#ffffff">
							<td align="center" bgcolor="#ffffff">
							<input id="encoder'.$i.'" name="encoder'.$i.'" type="hidden" value="'.$medicine_pres['product_encoder'].'" size="5" readonly>
							<a href="javascript:searchMedicine('.$i.')"><img src="'.$root_path.'gui/img/common/default/search_radio.jpg"></a></td>
							<td bgcolor="#ffffff" height="50">';
							//-- Ten VTYT -->
					echo		'<input type="text" id="medicinea'.$i.'" name="medicinea'.$i.'" size="25" value="'.$medicine_pres['product_name'].'" onFocus="Medicine_AutoComplete('.$i.');" >
								<div id="hint"></div>
							</td>';
									
							//-- Ton, So luong, don gia, thanh tien -->
					echo	'<td bgcolor="#ffffff">
								<input id="inventory'.$i.'" name="inventory'.$i.'" type="text" value="'.$inventory.'" size=5 style="text-align:center;border-color:white;border-style:solid;" readonly></td>
							<td align="center" bgcolor="#ffffff">
								<input name="sum'.$i.'" type="text" style="width:35px;" value="'.$medicine_pres['sum_number'].'" onBlur="calcost('.$i.');" >
								<input id="units'.$i.'" name="units'.$i.'" type="text" size=1 value="'.$medicine_pres['note'].'"></td>
							<td align="right" bgcolor="#ffffff">
								<input id="cost'.$i.'"  name="cost'.$i.'" type="text" style="width:70px;text-align:right;border:0px;" value="'.$medicine_pres['cost'].'" ></td>
							<td align="right" bgcolor="#ffffff">
								<input name="totalcost'.$i.'" type="text" style="width:85px;border:0px;text-align:right;" value="'.$totalcostmedicine.'"  readonly></td>';								
							
							//-- Row-up/down -->
					echo	'<td align="center" bgcolor="#ffffff" ><textarea name="morenote'.$i.'" cols="7" rows="1"> </textarea></td>
						</tr>';
?>