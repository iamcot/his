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
	
$thisfile=basename(__FILE__);

$sql_item="SELECT * FROM care_test_findings_radio_sub
			WHERE batch_nr='".$batch_nr."' AND item_bill_code='".$item_code."' ";
if($re_item=$db->Execute($sql_item)){
	if($count1=$re_item->RecordCount()){
		$item=$re_item->FetchRow();
	}
}
//echo $batch_nr.' '.$item_code;

if($count1 && $item['img_path']!=''){
	if($item['img_name']!=''){	//just check names in img_name
		$imgname=explode(',',$item['img_name']);
	}
	//show all imgage in folder
	$item['img_path']='../'.$item['img_path'];
	if(is_dir($item['img_path'])){
		$listimage = glob($item['img_path']."/*.jpg");
		echo '<table width="100%"><tr>';
		if ($listimage!=false){
			//$scheme = (isset($_SERVER['HTTPS']) && !strcasecmp($_SERVER['HTTPS'], 'on'))? "https://" : "http://";
			//$url = $scheme . $_SERVER['SERVER_NAME'];
			//$tokens = str_replace('modules/laboratory/sieuam/'.$thisfile,'', $_SERVER['PHP_SELF']);
			//$urldicom = $url.$tokens.str_replace('../','',$item['img_path']);
								
			for($j=0;$j<count($listimage);$j++){
				$listimage[$j] = str_replace($item['img_path'].'/','',$listimage[$j]);
								
				if($j!=1 && ($j % 4 == 1)){
					echo '</tr><tr>';
				}
				echo '<td align="center">';
				echo 	'<img src="'.substr($item['img_path'],3).'/'.$listimage[$j].'" width="160" height="120"><br>';
				echo $listimage[$j].'<br>';
				if (in_array($listimage[$j], $imgname))
					echo '<input type="checkbox" name="gr_monoimg" value="'.$listimage[$j].'" checked> ';
				else
					echo '<input type="checkbox" name="gr_monoimg" value="'.$listimage[$j].'"> ';
				echo '</td>';
			}
		} else echo '<td>'.$LDNoImageHere.'</td>';
		echo '</tr></table>';
	}
}


?>
