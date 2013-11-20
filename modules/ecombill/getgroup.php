<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');

$group=$_GET["group"];
$sql="select * from care_billing_item  where item_group_nr=(select nr from care_billing_item_group where group_name='".$group."')";
$temp=$db->Execute($sql);
$temp->RecordCount();
$i=0;
echo '
<table width="80%" cellspacing="1" cellpadding="3" border="0" bgcolor="#999999">
	<tbody>
		<tr bgcolor="#eeeeee">
		<th align="center" height="7" bgcolor="#CCCCCC">Mã</th>
		<th align="center" height="7" bgcolor="#CCCCCC">Tên dịch vụ y tế </th>
		<th align="center" height="7" bgcolor="#CCCCCC">Chi phí</th>
		<th align="center" valign="middle" height="7" bgcolor="#CCCCCC">Giảm</th>
		</tr>
	</tbody>
</table>
 <table id="'.$group.'"  cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
 <tr  bgcolor="#ffffff">
	<td colspan ="5" height="7"><b>'.$group.'<b></td>
</tr>';
while($buf=$temp->FetchRow()){

 echo '
<tr  bgColor="#eeeeee">

	<input name="update'.$buf['item_code'].'" value="'.$buf['item_description'].'#'.$buf['item_unit_cost'].'#'.$buf['item_discount_max_allowed'].'" type="hidden">

	<td height="7" width="846" align="center">'.$buf['item_code'].'</td>
	<td align="center" height="7" width="1014">
		<input type="text" name="itemnm#'.$i.'" size="50" value="'.$buf['item_description'].'">
	</td>
	<td height="7" width="623" align="center">
		<input type="text" name="itemcs#'.$i.'" size="10" value="'.$buf['item_unit_cost'].'">
	</td>
	<td height="7" width="484" align="center" valign="middle">
		<input type="text" name="itemdc#'.$i.'" size="3" value="'.$buf['item_discount_max_allowed'].'">
	</td>
</tr>';
$i++;
}
echo'

<p>
<input type="hidden" name="itemcd" value="'. $itemcd .'">
<input type="hidden" name="lang" value="'. $lang .'">
<input type="hidden" name="sid" value="'. $sid .'">
<input type="hidden" name="full_en" value="'. $full_en .'">
<p>

';
?>