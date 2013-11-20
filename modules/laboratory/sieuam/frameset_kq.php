<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
if($root_path=='')
	$root_path='../../../';
require_once($root_path.'include/core/inc_environment_global.php');
define('LANG_FILE','konsil.php');
define('NO_CHAIN',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');

if(!isset($item_code))
	$item_code=$_POST['item_code'];
if(!isset($batch_nr))
	$batch_nr=$_POST['batch_nr'];

$sql_item="SELECT * FROM care_test_findings_radio_sub
			WHERE batch_nr='".$batch_nr."' AND item_bill_code='".$item_code."' ";
if($re_item=$db->Execute($sql_item)){
	if($count1=$re_item->RecordCount()){
		$item=$re_item->FetchRow();
	}
}

if($count1 && $item['kq_sieuam']!=''){
	echo stripslashes($item['kq_sieuam']);
}
else{
	$file=$item_code.'.txt';
	if(file_exists($file)){
		$fh = fopen($file, 'r');
		$theData = fread($fh, filesize($file));
		fclose($fh);
		echo $theData;
	}	
}


?>
