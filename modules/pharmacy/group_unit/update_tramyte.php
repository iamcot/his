<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'pharma.php');
define('NO_2LEVEL_CHK',1);
require_once ($root_path . 'include/core/inc_front_chain_lang.php');

require_once ($root_path.'include/care_api_classes/class_health_station.php');
$class_obj=new HealthStation;

//type= type, health

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
	var r=confirm("<?php echo $LDWarningDeleteHealth; ?>");
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
				case 'health':
					if($class_obj->updateHealth($nr, $name, $typeht, $address, $tel_number, $note)) $saved=1;
					break;
				case 'type':	
					if($class_obj->updateType($nr, $name)) $saved=1;
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
			case 'health':
				if($class_obj->deleteHealth($nr)) $saved=1;
				break;
			case 'type':	
				if($class_obj->deleteType($nr)) $saved=1;
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
		case 'health':
			if($item = $class_obj->getDetailHealth($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDTramYTe.')</b></font> <p>';
				echo '<table bgcolor="#FFC">
					<tr><th>'.$LDNewName.' </th><td> <input type="text" id="name" name="name" size=30 maxlength=50 value="'.$item['village'].'" ></td></tr>
					<tr><th>'.$LDTypeHealth.' </th><td>';
						echo '<select id="typeht" name="typeht">';					
								$list_type = $class_obj->listType();
								if(is_object($list_type)){
									while($tp_item = $list_type->FetchRow()){
										if($item['type']==$tp_item['nr'])
											echo '<option value="'.$tp_item['nr'].'" selected>'.$tp_item['typename'].'</option>';
										else
											echo '<option value="'.$tp_item['nr'].'">'.$tp_item['typename'].'</option>';
									}
								}
						echo '</select>';						
				echo			'</td></tr>
					<tr><th>'.$LDAddress.' </th><td> <input type="text" id="address" name="address" size=30 maxlength=150 value="'.$item['address'].'" ></td></tr>
					<tr><th>'.$LDTelNr.' </th><td> <input type="text" id="tel_number" name="tel_number" size=30 maxlength=20 value="'.$item['tel_number'].'" ></td></tr>
					<tr><th>'.$LDNote.' </th><td> <input type="text" id="note" name="note" size=30 maxlength=100 value="'.$item['note'].'" ></td></tr>			
					
				</table>';	
			}
			break;
		case 'type':
			if($item = $class_obj->getDetailType($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDTypeHealth.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUpdateName.' </th></tr>
						<tr>';
				echo '	<td> <input type="text" id="name" name="name" size=30 maxlength=50 value="'.$item['typename'].'" ></td>';
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