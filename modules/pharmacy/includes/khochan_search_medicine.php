<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('./roots.php');
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
define('LANG_FILE','pharma.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
//require_once($root_path.'include/core/inc_date_format_functions.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<?php
html_rtl ( $lang );
?>
<HEAD>
<?php
echo setCharSet ();
?>
<TITLE><?php
	echo $LDMoreSearch; ?>
</TITLE>

<style type="text/css">
table {
	font-family: verdana, arial, tahoma;
	font-size: 12px;
	font-weight: normal;
	color: black;
}
.mybutton {
	background:url('../../../gui/img/common/default/select_it.png');
	width:32px;
	height:32px;
	cursor:pointer;
}
</style>

<script LANGUAGE="JavaScript">
<!-- Begin
function sendValue(name,encoder){
	window.opener.document.getElementById('medicine'+'<?php echo $id_number; ?>').value = name;
	window.opener.document.getElementById('encoder'+'<?php echo $id_number; ?>').value = encoder;
	window.close();
	window.opener.Fill_Data(<?php echo $id_number; ?>);
}

function searchItem(){
	
	//alert('ok');
	var name_med = document.getElementById('name_med').value;
	var name_ger_med = document.getElementById('name_ger_med').value;
	var group_med = document.getElementById('group_med').value;
	var supplier = document.getElementById('supplier').value;

    var xmlhttp;
    if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var tbl = document.getElementById('tblItem');
			tbl.tBodies[0].innerHTML = '<table id="tblItem">' + xmlhttp.responseText + '</table>';
		}
	}
	
	xmlhttp.open("GET","khochan_search_medicine_getdb.php?name_med="+name_med+"&name_ger_med="+name_ger_med+"&group_med="+group_med+"&supplier="+supplier,true);
	
	xmlhttp.send();
	
}

//  End -->
</script>

</HEAD>

<body>
<form name="selectform">
	<table width="100%" cellpadding="2" bgcolor="#EEEEEE">
		<tr bgcolor="#ffffff"><td><font size="4" color="#5f88be"><b><?php echo $LDMoreSearch; ?></b><br>&nbsp;</td></tr>
		<tr><td>
			<table cellspacing="3">
				<tr><td width="20%"><b><?php echo $LDNameMedicine; ?></b></td>
					<td><input type="text" id="name_med" name="name_med" size="40"></td>
					<td><font size="1"><?php echo $LDNameMedicineEx; ?></td>
					<td rowspan="4" valign="top" ><a href="javascript:searchItem();"><img <?php echo createComIcon($root_path,'search_icon.png','0','',TRUE); ?> title="<?php echo $LDSearch; ?>"></a> </td></tr>
				<tr><td><b><?php echo $LDNameGeneralMedicine; ?></b></td>
					<td><input type="text" id="name_ger_med" size="40"></td>
					<td><font size="1"><?php echo $LDNameGeneralMedicineEx; ?></td></tr>
				<tr><td><b><?php echo $LDGroupMedicine; ?></b></td>
					<td><input type="text" id="group_med" size="40"></td>
					<td><font size="1"><?php echo $LDGroupMedicineEx; ?></td></tr>
				<tr><td><b><?php echo $LDSupplier; ?></b></td>
					<td><input type="text" id="supplier" size="40"></td>
					<td><font size="1"><?php echo $LDSupplierEx; ?></td></tr>
			</table>			
		</td></tr>
		
		<tr bgcolor="#ffffff"><td>
			<table id="tblItem">
				<tr><td>
				</td></tr>
			</table>
		</td></tr>
	</table>
</form> 

</body>
</HTML>


