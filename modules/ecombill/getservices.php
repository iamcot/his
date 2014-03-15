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
		<th height="7" align="center" width="45" bgcolor="#CCCCCC"></th>
		<th height="7" align="center" width="838" bgcolor="#CCCCCC">Tên kiểm tra</th>
		<th align="center" height="7" width="182" bgcolor="#CCCCCC">Mã số kiểm tra</th>
		<th height="7" align="center" width="91" bgcolor="#CCCCCC">Chi phí</th>
		<th height="7" align="center" valign="middle" bgcolor="#CCCCCC">Số lần</th>
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
	<td align="center" height="7" width="45">
		<input name="'.$buf1['item_description'].'" id="nounits'.$i.'" value="ON" type="checkbox">
	</td>
	<td height="7" width="838">'.$buf1['item_description'].'</td>
	<td align="center" height="7" width="182">'.$buf1['item_code'].'</td>
	<td align="center" height="7" width="91">'.$buf1['item_unit_cost'].'</td>
	<td align="center" height="7"><select size="1" name="nounits' .$i .'" id="nounits' .$i .'"><option selected>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
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
</p>
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
		<th height="7" align="center" width="45" bgcolor="#CCCCCC"></th>
		<th height="7" align="center" width="838" bgcolor="#CCCCCC">Tên kiểm tra</th>
		<th align="center" height="7" width="182" bgcolor="#CCCCCC">Mã số kiểm tra</th>
		<th height="7" align="center" width="91" bgcolor="#CCCCCC">Chi phí</th>
		<th height="7" align="center" valign="middle" bgcolor="#CCCCCC">Số lần</th>
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
	<td align="center" height="7" width="45">
		<input name="'.$buf['item_description'].'" id="nounits'.$i.'" value="ON" type="checkbox">
	</td>
	<td height="7" width="838">'.$buf['item_description'].'</td>
	<td align="center" height="7" width="182">'.$buf['item_code'].'</td>
	<td align="center" height="7" width="91">'.$buf['item_unit_cost'].'</td>
	<td align="center" height="7"><select size="1" name="nounits' .$i .'" id="nounits' .$i .'"><option selected>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
</tr>';
$i++;
}
echo'

<p>
<input type="hidden" name="itemcd" value="'. $itemcd .'">
<input type="hidden" name="lang" value="'. $lang .'">
<input type="hidden" name="sid" value="'. $sid .'">
<input type="hidden" name="full_en" value="'. $full_en .'">
</p>

';
}
echo '<tr><td><input type="submit"  '.createLDImgSrc($root_path,'savedisc.gif','0','middle').'></td></tr>';
?>