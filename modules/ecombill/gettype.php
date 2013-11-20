<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');

$temp=substr($_GET["type"],0,1);

$service=$_GET["service"];
if(is_numeric($temp)){
$type=$_GET["type"];
$sql="select * from care_billing_item_group where item_group='".$type."' and item_type='".$service."'";
$temp=$db->Execute($sql);
$temp->RecordCount();
$sql1="select type_name from care_billing_item_type where item_group='".$type."' and item_type='".$service."'";
$temp1=$db->Execute($sql1);
$temp1->RecordCount();
$result=$temp1->FetchRow();
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
 <table id="'.$result['type_name'].'" cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
<tr bgcolor="#FFFF2A">
	<td colspan ="5" height="9" align="center"><b>'.$result['type_name'].'</b></td>
</tr>';
while($buf=$temp->FetchRow()){
$sql="select * from care_billing_item  where item_group_nr='".$buf['nr']."'";
$temp1=$db->Execute($sql);
$temp1->RecordCount();

echo '<table id="'.$buf['group_name'].'"  cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
 <tr  bgcolor="#ffffff">
	<td colspan ="5" height="7"><b>'.$buf['group_name'].'<b></td>
</tr>';
while($buf1=$temp1->FetchRow()){
 echo '
<tr  bgColor="#eeeeee">

	<input name="update'.$buf1['item_code'].'" value="'.$buf1['item_description'].'#'.$buf1['item_unit_cost'].'#'.$buf1['item_discount_max_allowed'].'" type="hidden">

	<td height="7" width="846" align="center">'.$buf1['item_code'].'</td>
	<td align="center" height="7" width="1014">
		<input type="text" name="itemnm#'.$i.'" size="50" value="'.$buf1['item_description'].'">
	</td>
	<td height="7" width="623" align="center">
		<input type="text" name="itemcs#'.$i.'" size="10" value="'.$buf1['item_unit_cost'].'">
	</td>
	<td height="7" width="484" align="center" valign="middle">
		<input type="text" name="itemdc#'.$i.'" size="3" value="'.$buf1['item_discount_max_allowed'].'">
	</td>
</tr>';
$i++;
}

}
echo'

<p>
<input type="hidden" name="itemcd" value="'. $itemcd .'">
<input type="hidden" name="lang" value="'. $lang .'">
<input type="hidden" name="sid" value="'. $sid .'">
<input type="hidden" name="full_en" value="'. $full_en .'">
<p>

';
}else{
$str=explode("-",$_GET["type"]);
$group=$str[1];
$sql="select * from care_billing_item  where item_group_nr='".$group."'";
$temp=$db->Execute($sql);
$temp->RecordCount();
$i=0;
$sql1="select group_name from care_billing_item_group where nr='".$group."'";
$temp1=$db->Execute($sql1);
$temp1->RecordCount();
$result=$temp1->FetchRow();
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
 <table id="'.$result['group_name'].'" name="'.$result['group_name'].'" cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
 <tr  bgcolor="#ffffff">
	<td colspan ="5" height="7"><b>'.$result['group_name'].'<b></td>
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
}
?>