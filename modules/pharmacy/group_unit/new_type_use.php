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
	if($mode=='new' && $type!=''){					
		if($name!=''){		
			switch($type){
				case 'type':
					if($unit_obj->newPharmaType($name)) $saved=1;
					break;
				case 'use':	
					if($unit_obj->newPharmaUse($name)) $saved=1;
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
	}

if($type!=''){
	switch($type){
		case 'type':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDGroupMedicine.')</b></font> <p>';
			break;
		case 'use':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDDuongDung.')</b></font> <p>';
			break;		
	}
	
	echo '<table bgcolor="#FFC"><tr><th>'.$LDNewName.' </th></tr>			
			<tr>
			<td> <input type="text" id="name" name="name" size=30 maxlength=20 value="" ></td>';
	echo '	</tr>
		</table>';
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