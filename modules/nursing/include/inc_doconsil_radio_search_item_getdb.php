<?php	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','konsil.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');

	if(!isset($search))
		$search=$_GET["search"];	
	$item_description = str_replace(' ', '%', $search);
	
	if(!isset($item_group_nr))
		$search=$_GET["item_group_nr"];

	if(!isset($group_code))
		$search=$_GET["group_code"];		

		switch($item_group_nr){
			case 26:	echo '<tr><td colspan="2"><b>'.$LDXrayTest.'</b></td></tr>'; break;
			case 27:	echo '<tr><td colspan="2"><b>'.$LDCytologySa.'</b></td></tr>'; break;
			case 28:	echo '<tr><td colspan="2"><b>'.$LDCT.'</b></td></tr>'; break;
			case 39:	echo '<tr><td colspan="2"><b>'.$LDMRT.'</b></td></tr>'; break;
			default: $item_group_nr=26;
		}	
		
		$group_code = str_replace('_',',',$group_code);
		$group_code = substr($group_code, 1);
		if ($group_code!=''){
			$cond_code = " AND item_code NOT IN (".$group_code.") ";
		}
		else $cond_code='';
		
		$sql="SELECT * FROM care_billing_item 
			WHERE item_group_nr='".$item_group_nr."'   
			AND ((item_description LIKE '".$item_description."%' ) OR (item_description LIKE '% ".$item_description."%' )) ".$cond_code;
		if($ergebnis=$db->Execute($sql))
       	{
			$n=$ergebnis->RecordCount();
			for($i=0; $i<$n; $i++)
			{
     			$item=$ergebnis->FetchRow();
				echo '<tr><td><input type="checkbox" name="groupcb" value="'.$item['item_code'].'"></td>';
				echo '<td><div id="'.$item['item_code'].'">'.$item['item_description'].'</div></td></tr>';
			}
		}
	
	
	
		
?>