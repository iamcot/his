<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require_once($root_path.'include/core/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System Deployment 2.2 - 2006-07-10
 * GNU General Public License
 * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
 * elpidio@care2x.org, 
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','konsil.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
//require_once($root_path.'include/core/inc_date_format_functions.php');


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<?php
html_rtl ( $lang );
?>
<HEAD>
<?php
echo setCharSet();
?>
<TITLE><?php
	echo $LDAddItems; ?>
</TITLE>

<style type="text/css">
input.text { 
	border-color:white;
	border-style:solid;
}
</style>

<script LANGUAGE="JavaScript">
<!-- Begin
function sendValue(){

	var idparent = window.opener.document.getElementById('cdhaDiv');
	var numi = window.opener.document.getElementById('theValue');
	var total_all= window.opener.document.getElementById('total_all');
	var total=total_all.value;
	var itemid, childdiv;
	var grcheckbox = document.getElementsByName('groupcb');
	for(var i=0; i < grcheckbox.length; i++){
		if(grcheckbox[i].checked){
			itemid = grcheckbox[i].value;	
			childdiv = document.getElementById(itemid);
			childdiv.setAttribute('name','item'+total);
			childdiv.innerHTML= "<a href='#' onclick='removeElement(\"" + itemid + "\")'>[x]</a> " + childdiv.innerHTML;
			idparent.appendChild(childdiv);
			total++;
		}
	}
	//alert(document.getElementsByName('groupcb').length);
	numi.value= numi.value*1 + total - total_all.value;
	total_all.value= total;
	window.close();
	
}
//  End -->
</script>

</HEAD>

<body>
<form name="selectform">
	<table>
<?php
		//item_group = xray, sono(sieu am) , ct, mammograph(nhu anh), mrt, nuclear
		switch ($item_group){
			case 'xray': $item_group_nr = 26;
						break;
			case 'ct': $item_group_nr = 28;
						break;
			case 'mrt': $item_group_nr = 39;
						break;
			case 'sono': $item_group_nr = 27;
						break;
			case 'mammograph': 
			case 'nuclear': $item_group_nr = 26;	//tam thoi
						break;
			default: $item_group_nr = 26;
		}
		
		$group_code = str_replace('_',',',$group_code);
		$group_code = substr($group_code, 1);
		if ($group_code!=''){
			$cond_code = " AND item_code NOT IN (".$group_code.") ";
		}
		else $cond_code='';

		$sql="SELECT * FROM care_billing_item WHERE item_group_nr='".$item_group_nr."' ".$cond_code;
		if($ergebnis=$db->Execute($sql))
       	{
			$n=$ergebnis->RecordCount();
			for($i=0; $i<$n; $i++)
			{
     			$item=$ergebnis->FetchRow();
				echo '<tr><td><input type="checkbox" name="groupcb" value="'.$item['item_code'].'"></td>';
				echo '<td><div id="'.$item['item_code'].'">'.$item['item_description'].'</div></td></tr>';
			}
		}
?>
	</table>
	<p>
	<center>
		<a href='#' onclick="javascript:sendValue();"><img <?php echo createLDImgSrc($root_path,'auswahl2.gif','0','middle'); ?> ></a> 
		<!-- <input type="button" value="OK" onClick="sendValue();">-->
	</center>
</form> 

</body>
</HTML>


