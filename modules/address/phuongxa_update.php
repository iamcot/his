<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','place.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
# Load the insurance object
require_once($root_path.'include/care_api_classes/class_address.php');
$address_obj=new Address;

switch($retpath)
{
	case 'list': $breakfile='phuongxa_list.php'.URL_APPEND; break;
	case 'search': $breakfile='phuongxa_search.php'.URL_APPEND; break;
	default: $breakfile='address_manage.php'.URL_APPEND; 
}

if(isset($nr) && $nr){
	if(isset($mode) && $mode=='update'){
		if($address_obj->updatePhuongXaInfoFromArray($nr,$_POST)){
    		header("location:phuongxa_info.php?sid=$sid&lang=$lang&nr=$nr&mode=show&save_ok=1&retpath=$retpath");
			exit;
		}else{
			echo $address_obj->getLastQuery();
			$mode='bad_data';
		}	
	}elseif($row=$address_obj->getPhuongXaInfo($nr)){
		if(is_object($row)){
			$address=$row->FetchRow();
			# Globalize the array values
			extract($address);
		}
	}
}else{
	// Redirect to search function
}

# Start Smarty templating here
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('system_admin');

# Title in toolbar
 $smarty->assign('sToolbarTitle',"$LDAddress :: $LDUpdateData");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('address_update.php')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDAddress :: $LDUpdateData");

# Buffer page output

ob_start();

?>

<ul>
<?php
if(!empty($mode)){ 
?>
<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0','bottom') ?>></td>
    <td valign="bottom"><br><font class="warnprompt"><b>
<?php 
	switch($mode)
	{
		case 'bad_data':
		{
			echo $LDAlertNoPhuongXaName;
			break;
		}
		case 'quanhuyen_exists':
		{
			echo "$LDPhuongXaExists<br>$LDDataNoSave";
		}
	}
?>
	</b></font><p>
</td>
  </tr>
</table>
<?php 
} 
?>
<script language="javascript">
<!--
function check(d)
{
	if((d.name.value=="")){
		alert("<?php echo "$LDAlertNoPhuongXaName \\n $LDPlsEnterInfo"; ?>");
		d.name.focus();
		return false;
	}else if(d.quanhuyen_id.value=="-1"){
		alert("<?php echo $LDEnterQuanHuyenId.'\n'.$LDEnterQMark; ?>");
		d.quanhuyen_id.focus();
		return false;
	
	}else{
		return true;
	}
}
function showQuanhuyen(){
			str=document.getElementById("citytown_id").value;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				document.getElementById("quanhuyen_id").innerHTML=xmlhttp.responseText;
				}
			  }
			xmlhttp.open("GET","getquanhuyen.php?citytown_id="+str,true);
			xmlhttp.send();

	}
// -->
</script>

<form action="<?php echo $thisfile; ?>" method="post" name="phuongxa"  onSubmit="return check(this)">
<table border=0>
  <tr>
    <td align=right class="adm_item"><?php echo $LDPhuongXaName ?>: </td>
    <td class="adm_input"><input type="text" name="name" size=50 maxlength=60 value="<?php echo $name ?>"><br>
  </tr> 
  <!-- apmuthu added zip code -->
  <?php
   echo '<tr>
    <td align=right class="adm_item">'.$LDCityTown.': </td>';
	echo'<td class="adm_input"><select name="citytown_id" id="citytown_id" onchange="showQuanhuyen()"><option value="-1">Chọn TP</option>';
	$sql="SELECT nr,name FROM care_address_citytown";
	 $buf=$db->Execute($sql);
		if($buf->RecordCount()){
			while($buf2=$buf->FetchRow()){
			echo '<option value="'.$buf2['nr'].'"  selected >' . $buf2['name'].'</option>';
			
			}
		
		}
   echo '</select></td></tr> ';
  echo '<tr>
    <td align=right class="adm_item"><font color=#ff0000><b>*</b></font>'.$LDQuanHuyen.': </td>';
	echo'<td class="adm_input"><select name="quanhuyen_id" id="quanhuyen_id"><option value="-1">Chọn QH</option>';
	
   echo '</select></td></tr> ';
 
   
  ?>
  <!-- end:apmuthu added zip code  -->   
  
  <tr>
    <td><input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>></td>
    <td  align=right><a href="<?php echo $breakfile;?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a></td>
  </tr>
</table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="nr" value="<?php echo $nr ?>">

<input type="hidden" name="retpath" value="<?php echo $retpath ?>">
</form>
<p>

</ul>
<?php

$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
