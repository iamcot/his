<?php
error_reporting (E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

require ('./roots.php');
require ($root_path . 'include/core/inc_environment_global.php');
define('LANG_FILE', 'pharma.php');
define('NO_2LEVEL_CHK',1);
require_once ($root_path . 'include/core/inc_front_chain_lang.php');

require_once ($root_path.'include/care_api_classes/class_unit.php');
$unit_obj=new Units;

//type=pharma, med, chemical

$thisfile=basename(__FILE__);

?>
<?php html_rtl($lang); ?>
<HEAD>
<?php echo setCharSet(); ?>
<TITLE><?php echo $LDEdit; ?></TITLE>

<script>
function saveitem(){
	var item = document.getElementById("name"); 
	var nr = document.getElementById("nr"); 
	if(item.value=='' || nr.value==''){
		alert('<?php echo $LDPlsInputAll; ?>');
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
				case 'pharma':
					if($unit_obj->updatePharmaUnit($oldnr, $nr, $name)) $saved=1;
					break;
				case 'med':	
					if($unit_obj->updateMedUnit($oldnr, $nr, $name)) $saved=1;
					break;
				case 'chemical':	
					if($unit_obj->updateChemicalUnit($oldnr, $nr, $name)) $saved=1;
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
	}elseif($mode=='delete' && $oldnr!='' && $type!=''){ 
		switch($type){
			case 'pharma':
				if($unit_obj->deletePharmaUnit($oldnr)) $saved=1;
				break;
			case 'med':	
				if($unit_obj->deleteMedUnit($oldnr)) $saved=1;
				break;
			case 'chemical':	
				if($unit_obj->deleteChemicalUnit($oldnr)) $saved=1;
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
			if($item = $unit_obj->getPharmaDetailUnit($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDMedicine.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUnitID.'</th><th>'.$LDUnitName.' </th></tr>
						<tr>
						<td valign="top"><input type="text" id="nr" name="nr" size=10 maxlength=20 value="'.$nr.'" ></td>';
				echo '	<td> <input type="text" id="name" name="name" size=30 maxlength=20 value="'.$item['unit_name_of_medicine'].'" ></td>';
				echo '	</tr>
					</table>';
			}
			break;
		case 'med':
			if($item = $unit_obj->getMedDetailUnit($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDMedipot.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUnitID.'</th><th>'.$LDUnitName.' </th></tr>
						<tr>
						<td valign="top"><input type="text" id="nr" name="nr" size=10 maxlength=20 value="'.$nr.'" ></td>';
				echo '	<td> <input type="text" id="name" name="name" size=30 maxlength=20 value="'.$item['unit_name_of_medicine'].'" ></td>';
				echo '	</tr>
					</table>';
			}
			break;
		case 'chemical':
			if($item = $unit_obj->getChemicalDetailUnit($nr)){
				echo '&nbsp;<font size=4 color=maroon><b>'.$LDEdit.' ('.$LDChemical.')</b></font> <p>
					<table bgcolor="#FFC"><tr><th>'.$LDUnitID.'</th><th>'.$LDUnitName.' </th></tr>
						<tr>
						<td valign="top"><input type="text" id="nr" name="nr" size=10 maxlength=20 value="'.$nr.'" ></td>';
				echo '	<td> <input type="text" id="name" name="name" size=30 maxlength=20 value="'.$item['unit_name_of_chemical'].'" ></td>';
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
<input type="hidden" name="oldnr" value="<?php echo $nr ?>">
<input type="hidden" name="type" value="<?php echo $type ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="edit" value="<?php echo $edit ?>">
</form>

</BODY>
</HTML>