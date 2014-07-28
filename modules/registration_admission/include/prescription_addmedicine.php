<?php
if (!isset($root_path)) {
	$root_path='../../';
	$src_up= 'src="'.$root_path.'gui/img/common/default/arw_up.gif"';
	$src_down= 'src="'.$root_path.'gui/img/common/default/arw_down.gif"';
	$LDDate='Ngày'; $LDEachTime='Mỗi lần'; $LDUseTimes='lần'; $LDMedicineUnit='viên'; $LDMedicineUse='uống'; $LDAtTime='Vào lúc';
} else {
	$src_up= createComIcon($root_path,'arw_up.gif','0','',TRUE);
	$src_down= createComIcon($root_path,'arw_down.gif','0','',TRUE);
}
$LDCaution='Chú ý';
$LDComponent='Thành phần';
if(!isset($type))
	$type=$_GET['type'];
			
		if(!$medicine_pres)
		{
			$medicine_pres['note']=$LDMedicineUnit;
			$medicine_pres['number_of_unit']='3';
			if($type=='sheet')
				$medicine_pres['time_use']='8h-14h-20h';
			else $medicine_pres['time_use']='sáng-trưa-tối';	
			$medicine_pres['sum_number']='3';
		}
	
		if(!$split_desciption)
		{
			$split_desciption[0]=$LDMedicineUse;
			$split_desciption[1]='1';
			$split_desciption[2]=$LDMedicineUnit;
		}
		if(!isset($i))
			$i=$_GET['i'];
			
					echo '<tr bgcolor="#ffffff">
							<td valign="top" bgcolor="#ffffff"><table><tr><td><input type="text" id="warning'.$i.'" name="warning'.$i.'" style="width:20px;" readonly >
							<input id="encoder'.$i.'" name="encoder'.$i.'" type="hidden" value="'.$medicine_pres['product_encoder'].'" >
							<input type="hidden" id="avai_id'.$i.'" name="avai_id'.$i.'" value="'.$medicine_pres['avai_pro_id'].'" ></td>
						</tr>
						<tr><td align="center"><a href="javascript:searchMedicine('.$i.')"><img src="'.$root_path.'gui/img/common/default/search_radio.jpg"></a></td></tr>
							</table></td>
							<td bgcolor="#ffffff" height="130" valign="top">
							<table width="100%" border="0"><tr><td>';
							//-- Ten thuoc / lieu luong-->
					echo		'<input type="text" id="medicinea'.$i.'" name="medicinea'.$i.'" size="30" value="'.$medicine_pres['product_name'].'" onFocus="Medicine_AutoComplete('.$i.');" onBlur="Fill_Data('.$i.')">
								<div id="hint"></div>
							</td></tr><tr><td>';
							//-- Ngay uong a lan-->
					echo		$LDDate.' '.' <input name="howtouse'.$i.'" type="text" size=3 value="'.$split_desciption[0].'">';
						
					echo		'<select name="times'.$i.'" id="times'.$i.'" onChange="ChangeAtTime('.$i.')">';
								for ($tempi=1; $tempi<=6; $tempi++){
									if ($tempi==$medicine_pres['number_of_unit'])
										echo '<option value="'.$tempi.'" selected >'.$tempi.'</option>';
									else echo '<option value="'.$tempi.'">'.$tempi.'</option>';	
								}
					echo		'</select> '.$LDUseTimes.'<br>';
							//-- Moi lan b vien-->
					echo		$LDEachTime.' <input type="text" name="count'.$i.'" id="count'.$i.'" value="'.$split_desciption[1].'" size="1">';
								/*'<select name="count'.$i.'" id="count'.$i.'" onChange="">';
								$array_moilanuong=array('1','2','3','4','5','1/2','1/3','1/4','2/3','3/4','6','7','8','9','10');

								foreach ($array_moilanuong as $tempi) {
									if ($tempi==$split_desciption[1])
										echo '<option value="'.$tempi.'" selected >'.$tempi.'</option>';
									else echo '<option value="'.$tempi.'">'.$tempi.'</option>';
								}	
					echo		'</select> ';		*/			
					echo		'<input id="totalunits'.$i.'" name="totalunits'.$i.'" type="text" size="2" value="'.$split_desciption[2].'"><br> ';
							//-- Vao luc -->
								//ngay uong ($medicine_pres['number_of_unit']) lan
					echo		$LDAtTime.' ';
								
					echo 		'<div id="vaoluc'.$i.'">';
								if($type=='sheet'){
									$defaulttime = explode('-',$medicine_pres['time_use'].'-');
									for($tempi=1; $tempi<=$medicine_pres['number_of_unit']; $tempi++){
										/*echo '<select name="attime_'.$i.'_'.$tempi.'" id="attime_'.$i.'_'.$tempi.'" >';
										for ($tempj=0; $tempj<=23; $tempj++){
											if ($tempj==$defaulttime[$tempi-1])
												echo '<option value="'.$tempj.'h" selected >'.$tempj.'h</option>';
											else echo '<option value="'.$tempj.'h">'.$tempj.'h</option>';	
										}
										echo '</select> &nbsp;';*/
										echo '<input type="text" name="attime_'.$i.'_'.$tempi.'" id="attime_'.$i.'_'.$tempi.'" value="'.$defaulttime[$tempi-1].'" style="width:60px;"> ';
										
									}
									
								}else{
									$defaulttime = explode('-',$medicine_pres['time_use'].'-');
									$array_vaoluc=array('sáng','trưa','chiều','tối');
									for($tempi=1; $tempi<=$medicine_pres['number_of_unit']; $tempi++){
										echo '<select name="attime_'.$i.'_'.$tempi.'" id="attime_'.$i.'_'.$tempi.'" >';
										foreach ($array_vaoluc as $tempj) {
											if ($tempj==$defaulttime[$tempi-1])
												echo '<option value="'.$tempj.'" selected >'.$tempj.'</option>';
											else echo '<option value="'.$tempj.'">'.$tempj.'</option>';	
										}
										echo '</select> &nbsp;';
									}									
								}
								//'<input name="attime'.$i.'" type="text" size="10" value="'.$medicine_pres['time_use'].'">'; 
					echo		'</div></td> 
							</td></tr></table>';
									
							//-- Ton, So luong, don gia, thanh tien -->
					echo	'<td bgcolor="#ffffff" colspan="4" valign="top">
							<table width="100%" border="0"><tr>
							<td bgcolor="#ffffff">
								<input id="inventory'.$i.'" name="inventory'.$i.'" type="text" value="'.$inventory.'" size=5 style="text-align:center;border-color:white;border-style:solid;" readonly></td>
							<td align="center" bgcolor="#ffffff">
								<input name="sum'.$i.'" id="sum'.$i.'" type="text" size="1" value="'.$medicine_pres['sum_number'].'" onBlur="calcost('.$i.');CheckNumberRequest('.$i.');checkPhat('.$i.');" >
								<input id="units'.$i.'" name="units'.$i.'" type="text" size=1 value="'.$medicine_pres['note'].'"></td>
							<td align="right" bgcolor="#ffffff">
								<input id="cost'.$i.'"  name="cost'.$i.'" type="text" style="width:70px;text-align:right;border:0px;" value="'.$medicine_pres['cost'].'" ></td>
							<td align="right" bgcolor="#ffffff">
								<input id="totalcost'.$i.'" name="totalcost'.$i.'" type="text" style="width:85px;border:0px;text-align:right;" value="'.$totalcostmedicine.'"  readonly></td></tr>';								
							//-- Thanh phan, Chu y-->
					echo	'<tr>
							<td bgcolor="#ffffff" colspan="2" width="50%"><font size="1" color="#000066"><u>'.$LDComponent.':</u><br><textarea id="component'.$i.'" cols="22" rows="4" style="border:0px;font-size:11px;" readonly></textarea></td>
							<td bgcolor="#ffffff" colspan="2" ><font size="1" color="#000066"><u>'.$LDCaution.':</u><br><textarea id="caution'.$i.'" cols="22" rows="4" style="border:0px;font-size:11px;" readonly></textarea></td>
							</tr></table>';
							
							//-- Row-up/down -->
					echo	'<td align="center" bgcolor="#ffffff" valign="top"><textarea name="morenote'.$i.'" cols="7" rows="6">'.$medicine_pres['morenote'].'</textarea></td>
							<td align="center" bgcolor="#ffffff">
								<div id="divUpDown'.$i.'">
								<p><a href="javascript:rowUp('.$i.')"> 
									<img '.$src_up.'>
								</a></p>										
								<p><a href="javascript:rowDown('.$i.')"> 
									<img '.$src_down.'>
								</a></p>
							</td>
							
						</tr>';
?>