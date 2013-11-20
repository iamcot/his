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
<TITLE><?php echo $LDNew; ?></TITLE>

<script>
function newitem(){
	var item = document.getElementById("name"); 
	if(item.value==''){
		alert('<?php echo $LDPlsInputName; ?>');
		return false;
	}	
	document.updateform.action="<?php echo $thisfile ?>?mode=new"; 
	document.updateform.submit();
}	

</script>
</HEAD>
<BODY>

<form name="updateform" method="post">


<?php
	$saved=0;
	if($mode=='new' && $name!=''){							
		switch($type){
			case 'health':
				if($class_obj->newHealth($name, $typeht, $address, $tel_number, $note)) $saved=1;
				break;
			case 'type':	
				if($class_obj->newType($name)) $saved=1;
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
	}

if($type!=''){
	switch($type){
		case 'health':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDTramYTe.')</b></font> <p>';
			echo '<table bgcolor="#FFC">
					<tr><th>'.$LDNewName.' </th><td> <input type="text" id="name" name="name" size=30 maxlength=50 value="" ></td></tr>
					<tr><th>'.$LDTypeHealth.' </th><td>';
						echo '<select id="typeht" name="typeht">';					
								$list_type = $class_obj->listType();
								if(is_object($list_type)){
									while($tp_item = $list_type->FetchRow()){
										echo '<option value="'.$tp_item['nr'].'">'.$tp_item['typename'].'</option>';
									}
								}
						echo '</select>';						
			echo			'</td></tr>
					<tr><th>'.$LDAddress.' </th><td> <input type="text" id="address" name="address" size=30 maxlength=150 value="" ></td></tr>
					<tr><th>'.$LDTelNr.' </th><td> <input type="text" id="tel_number" name="tel_number" size=30 maxlength=20 value="" ></td></tr>
					<tr><th>'.$LDNote.' </th><td> <input type="text" id="note" name="note" size=30 maxlength=100 value="" ></td></tr>			
					
				</table>';				
			
			break;
		case 'type':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDTypeHealth.')</b></font> <p>';
			echo '<table bgcolor="#FFC">
					<tr><th>'.$LDNewName.' </th></tr>			
					<tr><td> <input type="text" id="name" name="name" size=30 maxlength=50 value="" ></td></tr>
				</table>';			
			break;		
	}
	

}

?>
<p>&nbsp;
<a href="javascript:newitem();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> title="<?php echo $LDSave ?>"></a>&nbsp;
<a href="javascript:window.close();"><img <?php echo createLDImgSrc($root_path,'close2_BK.gif','0') ?> title="<?php echo $LDClose ?>"></a>

<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="type" value="<?php echo $type ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
</form>

</BODY>
</HTML>