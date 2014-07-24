<script  language="javascript">
    function checkTonTu(textbox)
    {
        var ton='<?php echo $rowReport['number'];?>';
        if(textbox.value>ton)

        {
            alert('Số lượng thuốc trả quá số lượng tồn tủ');
//        alert('Số lượng thuốc trả quá số lượng tồn tủ');
            textbox.focus();
        }
    }

</script>

<?php
if(!isset($i))
    $i=$_GET['i'];
if(!isset($stt_nr))
    $stt_nr=$_GET['stt_nr'];

echo '<tr bgColor="#ffffff" >';
//Ma thuoc
echo 	'
			<td align="center" bgColor="#ffffff"><input id="encoder'.$i.'" name="encoder'.$i.'" type="text" value="'.$rowReport['product_encoder'].'" style="text-align:center;border-color:white;border-style:solid;" readonly size="8"><input type="hidden" name="available_product_id' . $i . '" value="' . $rowReport['available_product_id'] . '"></td>';
//Ten thuoc
echo	'
			<td align="center" bgColor="#ffffff"><input id="medicine'.$i.'"  name="medicine'.$i.'" type="text" size=30 value="'.$rowReport['product_name'].'" '.$readonly.' '.$style.' onFocus="Medicine_AutoComplete('.$i.')" > &nbsp;<a href="javascript:searchMedicine('.$i.')"><img src="../../../gui/img/common/default/search_radio.jpg"></a><div id="hint"></div></td>';
//Don vi
echo 	'
			<td align="center" bgColor="#ffffff"><input name="unit'.$i.'" id="unit'.$i.'" type="text" size=7 value="'.$rowReport['unit_name_of_medicine'].'" '.$readonly.' '.$style.' readonly></td>';
//So lo sx
echo	'
			<td align="center" bgColor="#ffffff"><input name="lotid'.$i.'" id="lotid'.$i.'" type="text" value="'.$rowReport['lotid'].'" size=7 '.$readonly.' '.$style.' readonly ></td>';
//So luong
echo
    '<td align="center" bgColor="#ffffff"><input onchange="checkTonTu(this)" id="number'.$i.'" name="number'.$i.'" type="text" value="'.$rowReport['number'].'" size=5 '.$readonly.' '.$style.' onBlur="CalCost('.$i.')" ></td>';
//Don gia
echo	'
			<td bgColor="#ffffff"><input id="cost'.$i.'" name="cost'.$i.'" type="text" value="'.$rowReport['cost'].'" style="text-align:center;border-color:white;border-style:solid;" readonly size="8"></td>';
//Thanh tien
$totalcost = $rowReport['cost']*$rowReport['number'];
echo 	'
			<td bgColor="#ffffff"><input id="totalcost'.$i.'" name="totalcost'.$i.'" type="text" value="'.$totalcost.'"  style="text-align:center;border-color:white;border-style:solid;" readonly size="8"></td>';
//note
echo	'
			<td align="center" bgColor="#ffffff"><input name="note'.$i.'" type="text" value="'.$rowReport['note'].'" size=10></td>
		</tr>';
?>
