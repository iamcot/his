<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
define('LANG_FILE','pharma.php');
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'include/core/inc_front_chain_lang.php');
include_once($root_path.'include/care_api_classes/class_pharma.php');
include_once($root_path.'include/core/inc_date_format_functions.php');

//extract($_POST);

$patmenu="khochan_baocaothuoc.php".URL_REDIRECT_APPEND."&pid=".$_SESSION['sess_pid'];
if(!isset($Pharma)) $Pharma=new Pharma;
		
switch($target){
		case 'new':
		case 'create':
		case 'save':
				//report					
				$monthreport='00/'.$month.'/'.$year;
				$monthreport = formatDate2STD($date_time[0],'dd/mm/yyyy'); 
				
				$user_report = $_SESSION['sess_user_name'];
				$n = $_POST['maxid'];
				for ($j=1; $j<=$n; $j++) {
					if($_POST['encoder'.$j]){
						$Pharma->Khochan_hoachat_luubaocao($_POST['encoder'.$i], $monthreport,  str_replace(',','',$_POST['tondau'.$i]),  str_replace(',','',$_POST['nhap'.$i]),  str_replace(',','',$_POST['xuat'.$i]),  str_replace(',','',$_POST['toncuoi'.$i]),  str_replace(',','',$_POST['gia'.$i]), $user_report, $_POST['typeput']);
					}
				}
				//echo $Pharma->getLastQuery()
				header("Location:".$patmenu);
				exit;
 
			break;
		
}



?>