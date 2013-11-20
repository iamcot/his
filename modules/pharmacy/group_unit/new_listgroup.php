<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'pharma.php');
define('NO_2LEVEL_CHK',1);
require_once ($root_path.'include/core/inc_front_chain_lang.php');

require_once ($root_path.'include/care_api_classes/class_listgroup.php');
$class_obj=new ListGroup;

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
			case 'pharma':
				if($class_obj->newPharmaGroup($nr, $name)) $saved=1;
				break;
			case 'pharmasub':	
				if($class_obj->newPharmaGroupSub($group_id, $group_id_sub, $name)) $saved=1;
				break;
			case 'medipot':	
				if($class_obj->newMedGroup($typemed, $name)) $saved=1;
				break;
			case 'chemical':	
				if($class_obj->newChemicalGroup($name)) $saved=1;
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
		case 'pharma':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDNhomThuoc.')</b></font> <p>';
			echo '<table bgcolor="#FFC">
					<tr><th>'.$LDGroupID.'</th><th>'.$LDNewName.' </th></tr>			
					<tr>
						<td valign="top"><input type="text" id="nr" name="nr" size=10 maxlength=10 value="" ></td>
						<td valign="top"><input type="text" id="name" name="name" size=30 value="" ></td>
					</tr>
				</table>';
			break;	
			
		case 'pharmasub':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDNhomThuocCon.')</b></font> <p>';
			echo '<table bgcolor="#FFC">
					<tr><th>'.$LDNhomThuoc.' </th><td>';
						echo '<select id="group_id" name="group_id">';					
								$list_gp = $class_obj->listPharmaGroup();
								if(is_object($list_gp)){
									while($tp_item = $list_gp->FetchRow()){
										echo '<option value="'.$tp_item['pharma_group_id'].'">'.$tp_item['pharma_group_id'].'- '.$tp_item['pharma_group_name'].'</option>';
									}
								}
						echo '</select>';						
			echo			'</td></tr>
					<tr><th>'.$LDGroupSubID.' </th><td> <input type="text" id="group_id_sub" name="group_id_sub" size=30 maxlength=10 value="" ></td></tr>
					<tr><th>'.$LDNewName.' </th><td> <input type="text" id="name" name="name" size=90  value="" > </td></tr>								
				</table>';				
			
			break;

		case 'medipot':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDGroupMedipot.')</b></font> <p>';
			echo '<table bgcolor="#FFC">					
					<tr><th>'.$LDTypeMedipot.' </th><td>';
						echo '<select id="typemed" name="typemed">';					
								$list_type = $class_obj->listMedType();
								if(is_object($list_type)){
									while($tp_item = $list_type->FetchRow()){
										echo '<option value="'.$tp_item['type_of_med'].'">'.$tp_item['type_name_of_med'].'</option>';
									}
								}
						echo '</select>';						
			echo			'</td></tr>	
					<tr><th>'.$LDNewName.' </th><td> <input type="text" id="name" name="name" size=30 maxlength=50 value="" ></td></tr>			
				</table>';				
			
			break;			
			
			
		case 'chemical':
			echo '&nbsp;<font size=4 color=maroon><b>'.$LDNew.' ('.$LDGroupChemical.')</b></font> <p>';
			echo '<table bgcolor="#FFC">
					<tr><th>'.$LDNewName.' </th></tr>			
					<tr><td> <input type="text" id="name" name="name" size=30 maxlength=50 value="" ></td></tr>
				</table>';			
			break;		
	}
	

}

?>
<p>
<a href="javascript:newitem();"><img <?php echo createLDImgSrc($root_path,'savedisc.gif','0') ?> title="<?php echo $LDSave ?>"></a>&nbsp;
<a href="javascript:window.close();"><img <?php echo createLDImgSrc($root_path,'close2_BK.gif','0') ?> title="<?php echo $LDClose ?>"></a>

<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="type" value="<?php echo $type ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
</form>

</BODY>
</HTML>