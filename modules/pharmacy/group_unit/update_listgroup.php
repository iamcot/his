<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'pharma.php');
define('NO_2LEVEL_CHK',1);
require_once ($root_path . 'include/core/inc_front_chain_lang.php');

require_once ($root_path.'include/care_api_classes/class_listgroup.php');
$class_obj=new ListGroup;

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
	var r=confirm("<?php echo $LDWarningDeleteGroup; ?>");
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
				case 'pharma':
					if($class_obj->updatePharmaGroup($nr, $newnr, $name)) $saved=1;
					break;
				case 'pharmasub':	
					if($class_obj->updatePharmaGroupSub($nr, $group_id, $group_id_sub, $name)) $saved=1;
					break;	
				case 'medipot':	
					if($class_obj->updateMedGroup($nr, $typemed, $name)) $saved=1;
					break;	
				case 'chemical':	
					if($class_obj->updateChemicalGroup($nr, $name)) $saved=1;
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
			case 'pharma':
				if($class_obj->deletePharmaGroup($nr)) $saved=1;
				break;
			case 'pharmasub':
				if($class_obj->deletePharmaGroupSub($nr)) $saved=1;
				break;				
			case 'medipot':	
				if($class_obj->deleteMedGroup($nr)) $saved=1;
				break;
			case 'chemical':
				if($class_obj->deleteChemicalGroup($nr)) $saved=1;
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
		case 'pharma':
			if($item = $class_obj->getPharmaDetailGroup($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDNhomThuoc.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUpdateID.' </th><th>'.$LDUpdateName.' </th></tr>
						<tr>';
				echo '		<td> <input type="text" id="newnr" name="newnr" size=30 maxlength=50 value="'.$item['pharma_group_id'].'" ></td>
							<td> <input type="text" id="name" name="name" size=30 maxlength=50 value="'.$item['pharma_group_name'].'" ></td>';
				echo '	</tr>
					</table>';
			}
			break;			
		case 'pharmasub':
			if($item = $class_obj->getPharmaDetailGroupSub($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDNhomThuocCon.')</b></font> <p>';
				echo '<table bgcolor="#FFC">					
					<tr><th>'.$LDNhomThuoc.' </th><td>';
						echo '<select id="group_id" name="group_id">';					
								$list_gp = $class_obj->listPharmaGroup();
								if(is_object($list_gp)){
									while($tp_item = $list_gp->FetchRow()){
										if($item['pharma_group_id']==$tp_item['pharma_group_id'])
											echo '<option value="'.$tp_item['pharma_group_id'].'" selected>'.$tp_item['pharma_group_id'].'- '.$tp_item['pharma_group_name'].'</option>';
										else
											echo '<option value="'.$tp_item['pharma_group_id'].'">'.$tp_item['pharma_group_id'].'- '.$tp_item['pharma_group_name'].'</option>';
									}
								}
						echo '</select>';						
				echo			'</td></tr>
					<tr><th>'.$LDUpdateID.' </th><td> <input type="text" id="group_id_sub" name="group_id_sub" size=30 maxlength=10 value="'.$item['pharma_group_id_sub'].'" ></td></tr>
					<tr><th>'.$LDUpdateName.' </th><td> <input type="text" id="name" name="name" size=30 value="'.$item['pharma_group_name_sub'].'" ></td></tr>
				</table>';	
			}
			break;
			
		case 'medipot':
			if($item = $class_obj->getMedDetailGroup($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDGroupMedipot.')</b></font> <p>';
				echo '<table bgcolor="#FFC">					
					<tr><th>'.$LDTypeMedipot.' </th><td>';
						echo '<select id="typemed" name="typemed">';					
								$list_type = $class_obj->listMedType();
								if(is_object($list_type)){
									while($tp_item = $list_type->FetchRow()){
										if($item['type_of_med']==$tp_item['type_of_med'])
											echo '<option value="'.$tp_item['type_of_med'].'" selected>'.$tp_item['type_name_of_med'].'</option>';
										else
											echo '<option value="'.$tp_item['type_of_med'].'">'.$tp_item['type_name_of_med'].'</option>';
									}
								}
						echo '</select>';						
				echo			'</td></tr>
					<tr><th>'.$LDUpdateName.' </th><td> <input type="text" id="name" name="name" size=30 maxlength=100 value="'.$item['name_sub'].'" ></td></tr>					
				</table>';	
			}
			break;	
		case 'chemical':
			if($item = $class_obj->getChemicalDetailGroup($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDGroupChemical.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUpdateName.' </th></tr>
						<tr>';
				echo '	<td> <input type="text" id="name" name="name" size=30 maxlength=50 value="'.$item['chemical_group_name'].'" ></td>';
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