<?php	
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$lang='vi';
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
require_once($root_path.'include/care_api_classes/class_product.php');
$product = new Product();

	if(!isset($catalogue))
		$catalogue=$_GET["catalogue"];
	if(!isset($obj))
		$obj=$_GET["obj"];		//product_encoder


	switch ($catalogue){
		case 'supplier':
			require_once($root_path.'include/care_api_classes/class_supplier.php');
			$supplier = new Supplier();
			if($supplier->DeleteSupplier($obj))
					echo 'ok';
				else echo 'fail';
				break;
		case 'generic_drug': 
				if($product->DeleteGenericDrug($obj))
					echo 'ok';
				else echo 'fail';
				break;
				
		case 'vnmedicine':
		case 'medicine':
				if($product->DeleteMedicine($obj))
					echo 'ok';
				else echo 'fail';		
				break;
		case 'medipot':
				if($product->DeleteMedipot($obj))
					echo 'ok';
				else echo 'fail';		
				break;
		case 'chemical':
				if($product->DeleteChemical($obj))
					echo $product->getLastQuery();
				else echo 'fail';		
				break;
	
		default: 
			echo 'fail';
			break;
	}
	
	
	
		
?>