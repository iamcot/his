<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'pharma.php');
define('NO_2LEVEL_CHK',1);
require_once ($root_path . 'include/core/inc_front_chain_lang.php');

require_once ($root_path.'include/care_api_classes/class_unit.php');
$unit_obj=new Units;

//type= type, use

$thisfile=basename(__FILE__);

?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $LDEdit; ?></TITLE>

<script>
function saveitem(){
	var item = document.getElementById("name"); 
	if(item.value==''){
		alert('<?php echo $LDPlsInputName; ?>');
		return false;
	}
	document.updateform.action="<?php echo $thisfile ?>?mode=update"; 
	document.updateform.submit();
}	
function deleteitem(){
	var r=confirm("<?php echo $LDWarningDeleteUnit; ?>");
	if (r==true)
	{
		document.updateform.action="<?php echo $thisfile ?>?mode=delete";
		document.updateform.submit();	 
	}
	else
	{
	  return false;
	}
}

</script>
</HEAD>
<BODY>

<form name="updateform" method="post">


<?php
	$saved=0;
	if($mode=='update' && $nr!='' && $type!=''){					
		if($name!=''){		
			switch($type){
				case 'type':
					if($unit_obj->updatePharmaType($nr, $name)) $saved=1;
					break;
				case 'use':	
					if($unit_obj->updatePharmaUse($nr, $name)) $saved=1;
					break;					
			}
		}
			
		if($saved){
			echo '<script language="javascript">
					window.opener.refresh();
					window.close();	
				</script>';
		}else{
			echo '<script language="javascript">
					alert("'.$LDCannotUpdate.'");	
				</script>';
		}	
	}elseif($mode=='delete' && $nr!='' && $type!=''){ 
		switch($type){
			case 'type':
				if($unit_obj->deletePharmaType($nr)) $saved=1;
				break;
			case 'use':	
				if($unit_obj->deletePharmaUse($nr)) $saved=1;
				break;				
		}	
		
		if($saved){
			echo '<script language="javascript">
					window.opener.refresh();
					window.close();	
				</script>';
		}else{
			echo '<script language="javascript">
					alert("'.$LDCannotUpdate.'");	
				</script>';
		}
	}else{
	
	}

if($nr!='' && $type!=''){
	switch($type){
		case 'type':
			if($item = $unit_obj->getPharmaDetailType($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDGroupMedicine.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUpdateName.' </th></tr>
						<tr>';
				echo '	<td> <input type="text" id="name" name="name" size=30 maxlength=20 value="'.$item['type_name_of_medicine'].'" ></td>';
				echo '	</tr>
					</table>';
			}
			break;
		case 'use':
			if($item = $unit_obj->getPharmaDetailUse($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDDuongDung.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUpdateName.' </th></tr>
						<tr>';
				echo '	<td> <input type="text" id="name" name="name" size=30 maxlength=20 value="'.$item['name_use'].'" ></td>';
				echo '	</tr>
					</table>';
			}
			break;			
	}
}

?>
<p>&nbsp;
<a href="javascript:saveitem();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> title="<?php echo $LDSave ?>"></a>&nbsp;
<a href="javascript:deleteitem();"><img <?php echo createLDImgSrc($root_path,'delete.gif','0') ?> title="<?php echo $LDDelete ?>"></a>&nbsp;
<a href="javascript:window.close();"><img <?php echo createLDImgSrc($root_path,'close2_BK.gif','0') ?> title="<?php echo $LDClose ?>"></a>

<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="nr" value="<?php echo $nr ?>">
<input type="hidden" name="type" value="<?php echo $type ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
</form>

</BODY>
</HTML>