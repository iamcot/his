<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'nursing.php');
$local_user='ck_pflege_user';
require_once ($root_path . 'include/core/inc_front_chain_lang.php');
//require_once ($root_path . 'include/core/inc_config_color.php'); // load color preferences
include_once ($root_path . 'include/core/inc_date_format_functions.php') ;
/* Create charts object */
require_once ($root_path.'include/care_api_classes/class_charts.php');
$charts_obj=new Charts;


?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $LDUpdate; ?></TITLE>

<script language="javascript" src="<?php echo $root_path; ?>js/jscalendar/jquery.min.js"></script>
<script language="javascript" src="<?php echo $root_path; ?>js/jscalendar/jquery.maskedinput-1.3.js"></script>
<?php require($root_path.'classes/datetimemanager/checktime.php'); ?>
<script>
function saveitem(){
	var ttime = document.getElementById('ttime').value;
	var tdata = document.getElementById('tdata').value;
	tdata = tdata.replace(',','.');

	if (checkIsNumber(tdata)==false){
		alert("<?php echo $LDAlertInputNumber; ?>");
		document.getElementById('tdata').focus();
		return false;
	}
	
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
			
			if(xmlhttp.responseText=='ok'){
				//window.opener.parentrefresh();				
				window.opener.refresh();	
				window.close();
			}else{
				alert('<?php echo $LDCannotUpdate; ?>');
				return false;
			}
		}
	}
	xmlhttp.open("GET","include/update_machnhietdo_getdb.php?mode=update&ttime="+ttime+"&tdata="+tdata+"&nr="+'<?php echo $nr; ?>',true);
	xmlhttp.send();
}
function deleteitem(){
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
			
			if(xmlhttp.responseText=='ok'){
				window.opener.refresh();
				window.close();			
			}else{
				alert('<?php echo $LDCannotUpdate; ?>');
				return false;
			}
		}
	}
	xmlhttp.open("GET","include/update_machnhietdo_getdb.php?mode=delete&nr="+'<?php echo $nr; ?>',true);
	xmlhttp.send();
}
function checkNumberItem(d){
	d.value = d.value.replace(',','.');
	if (d.value!="" && checkIsNumber(d.value)==false){
		alert("<?php echo $LDAlertInputNumber; ?>");
		d.focus();
		return false;
	}
	return true;
}
</script>

</HEAD>
<BODY>

<form name="updateform" method="post">

&nbsp;<font face="verdana,arial" size=4 color=maroon><?php echo $LDUpdate; ?></font>
<br>
<?php

if($nr!=''){
	if($item = $charts_obj->getMeasureByNr($nr)){
		switch($item['msr_type_nr']){
			case 1: 	$title=$LDBp;			//Huyet ap
						$unit='mmHg';
						break;
			case 2:		$title=$LDMach;			//Mach
						$unit='L/ph';
						break;
			case 3:		$title=$LDTemp;			//Nhiet do
						$unit='C';
						break;
			case 6: 	$title=$LDWeight;		//Can nang
						$unit='kg';
						break;
			case 10: 	$title=$LDBreath;		//Nhip tho
						$unit='L/ph';
						break;	
			default:  	$title=$LDBp; $unit='mmHg';			
						break;
		}
		echo '&nbsp;'.$LDDay.'&nbsp; &nbsp;'.@formatDate2Local($item['msr_date'],'dd/mm/yyyy').'<p>'; 
		echo '<table bgcolor="#FFC"><tr><th>'.$LDClockTime.'</th><th>'.$title.'</th><th></th></tr>';
		echo '<tr><td><input type="text" id="ttime" size=6 maxlength=5 value="'.$item['msr_time'].'" onBlur="checkTime(this)">
				<script language="javascript">
					$(function(){
						$("#ttime'.$i.'").mask("**:**");
					});
				</script>
				</td>';
		echo '<td><input type="text" id="tdata" size=8 maxlength=7 value="'.$item['value'].'" onBlur="checkNumberItem(this)"></td>';
		echo '<td>('.$unit.')</td></tr></table>';
	}
}

?>
<p>&nbsp;
<p>
&nbsp;
<a href="javascript:saveitem();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> title="<?php echo $LDSave ?>"></a>&nbsp;
<a href="javascript:deleteitem();"><img <?php echo createLDImgSrc($root_path,'delete.gif','0') ?> title="<?php echo $LDDelete ?>"></a>&nbsp;
<a href="javascript:window.close();"><img <?php echo createLDImgSrc($root_path,'close2.gif','0') ?> title="<?php echo $LDClose ?>"></a>
</form>

</BODY>
</HTML>