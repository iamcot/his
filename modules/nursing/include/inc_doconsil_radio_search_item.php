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
	echo $LDSearchItems; ?>
</TITLE>

<style type="text/css">
input.text { 
	border-color:white;
	border-style:solid;
}
table {
	font-family: verdana, arial, tahoma;
	font-size: 12px;
	font-weight: normal;
	color: black;
}
</style>

<script LANGUAGE="JavaScript">
<!-- Begin
function sendValue(){

	var idparent = window.opener.document.getElementById('cdhaDiv');
	var numi = window.opener.document.getElementById('theValue');
	var total_all= window.opener.document.getElementById('total_all');
	var total=total_all.value;
	for(var i=0; i < document.selectform.groupcb.length; i++){
		if(document.selectform.groupcb[i].checked){
			itemid = document.selectform.groupcb[i].value;	
			childdiv = document.getElementById(itemid);
			childdiv.setAttribute('name','item'+total);
			childdiv.innerHTML= "<a href='#' onclick='removeElement(\"" + itemid + "\")'>[x]</a> " + childdiv.innerHTML;
			idparent.appendChild(childdiv);
			total++;
		}
	}
	numi.value= numi.value*1 + total - total_all.value;
	total_all.value= total;
	window.close();
	
}

function searchItem(){
	var search_item = document.getElementById('search').value;
	var item_group_nr = document.getElementById('item_group_nr').value;
	var tbl = document.getElementById('tblItem');
 
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			tbl.tBodies[0].innerHTML = '<table id="tblItem" >' + xmlhttp.responseText + '</table>';
		}
	}
	
	xmlhttp.open("GET","inc_doconsil_radio_search_item_getdb.php?item_group_nr="+item_group_nr+"&group_code=<?php echo $group_code; ?>&search="+search_item,true);
	xmlhttp.send();
	
}

//  End -->
</script>

</HEAD>

<body>
<form name="selectform">
	<table cellspacing="3" width="100%">
		<tr>
			<td width="45%">&nbsp;</td>
			<td> <font color="#5f88be"><b><?php echo $LDSearch.': '; ?></b><td>
			<td> <input type="text" id="search" name="search" size="30"></td>
			<td> <a href="javascript:searchItem();"><img <?php echo createComIcon($root_path,'search_icon.png','0','',TRUE); ?>></a></td>
		</tr>
	</table>
	<table id="tblItem">		
	<?php //item_group_nr = 26: Xquang, 27: Sieu am, 28: Dien tim
	switch ($item_group){
			case 'xray':$item_group_nr = 26;
						echo '<tr><td colspan="2"><b>'.$LDXrayTest.'</b></td></tr>';
						break;
			case 'ct': $item_group_nr = 28;
						echo '<tr><td colspan="2"><b>'.$LDCT.'</b></td></tr>';
						break;
			case 'mrt': $item_group_nr = 39;
						echo '<tr><td colspan="2"><b>'.$LDMRT.'</b></td></tr>';
						break;
			case 'sono': $item_group_nr = 27;
						echo '<tr><td colspan="2"><b>'.$LDCytologySa.'</b></td></tr>'; break;
						break;
			case 'mammograph': 
			case 'nuclear': $item_group_nr = 28;	//tam thoi
						echo '<tr><td colspan="2"><b>'.$LDDienTim.'</b></td></tr>'; break;
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
	<input type="hidden" id="item_group_nr" value="<?php echo $item_group_nr; ?>" > 
	<p>
	<center>
		<a href='#' onclick="javascript:sendValue();"><img <?php echo createLDImgSrc($root_path,'auswahl2.gif','0','middle'); ?> ></a> 
		<!-- <input type="button" value="OK" onClick="sendValue();">-->
	</center>
</form> 

</body>
</HTML>


