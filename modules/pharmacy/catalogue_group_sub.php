<?php	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require ('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang='vi';
define('NO_CHAIN',1);
define('LANG_FILE','pharma.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');

if(!isset($groupid))
	$groupid=$_GET["groupid"];

require_once ($root_path.'include/care_api_classes/class_product.php');
$class_obj=new Product;

$selected_box_sub='<select id="pharma_group_name_sub_input" name="pharma_group_id_sub" style="width:100%">';
if($result = $class_obj->ListPharmaGroupNameSub($groupid)){
	while($name_sub=$result->FetchRow())
    {
		$selected_box_sub=$selected_box_sub.'<option value="'.$name_sub["pharma_group_id_sub"].'">'.$name_sub["pharma_group_name_sub"].'</option>';
    }
}
$selected_box_sub .= '</select>';
	
echo $selected_box_sub;
	
		
?>