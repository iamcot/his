<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
$root_path='../../../';
require($root_path.'include/core/inc_environment_global.php');
require_once($root_path.'global_conf/inc_remoteservers_conf.php');
	
include_once($root_path.'classes/transfont/codaukhongdau.php');
include_once($root_path.'include/care_api_classes/class_encounter.php');

require_once($root_path.'include/care_api_classes/class_image.php');
$img=new Image();
$img->useImgDiagnostic();


$nameitem = $_POST['nameitem'];
$date = date('Ymd');
$pid = $_POST['pid'];
$encounter_nr = $_POST['encounter_nr'];
$maxnr= $_POST['maxnr'];
$mode= $_POST['mode'];		//mode = first, middle, last
$suf = $_POST['suf'];	//xq_1, xq_2...
$batch_nr = $_POST['batch_nr'];
$item_code = $_POST['item_code'];
$uid = $_POST['uid'];


//$pid='0001';
if($encounter_nr){
	$Encounter=new Encounter;
	if( $Encounter->loadEncounterData($encounter_nr)) {
		$person_name=$Encounter->encounter['name_last'].' '.$Encounter->encounter['name_first'];
		$birthday=str_replace("-","",$Encounter->encounter['date_birth']);		
		if(strlen($birthday)<8)  $birthday=str_pad($birthday, 8,'1', STR_PAD_RIGHT);
		$sex=$Encounter->encounter['sex'];
	}
}	
$date=date('Ymd');
	

//create folder for file.dcm

$persondir=$root_path.$dicom_img_localpath.$pid;					
if(!is_dir($persondir)){
	# if $d directory not exist create it with CHMOD 777
	mkdir($persondir,0777); 
	# Copy the trap files to this new directory
	copy($root_path.$dicom_img_localpath.'donotremove/index.htm',$persondir.'/index.htm');
	//echo $root_path.$dicom_img_localpath.'donotremove/index.htm'.$persondir.'/index.htm'.'<br>';
	copy($root_path.$dicom_img_localpath.'donotremove/index.php',$persondir.'/index.php');
	//echo $root_path.$dicom_img_localpath.'donotremove/index.php'.$persondir.'/index.php'.'<br>';
}
$encdir=$persondir.'/'.$encounter_nr;
if(!is_dir($encdir)){
	mkdir($encdir,0777);
	copy($root_path.$dicom_img_localpath.'donotremove/donotremove/index.htm',$encdir.'/index.htm');
	copy($root_path.$dicom_img_localpath.'donotremove/donotremove/index.php',$encdir.'/index.php');
}
$datedir=$persondir.'/'.$encounter_nr.'/'.$date;
if(!is_dir($datedir)){
	mkdir($datedir,0777);
	copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/index.htm',$datedir.'/index.htm');
	copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/index.php',$datedir.'/index.php');
}
$imgdir=$persondir.'/'.$encounter_nr.'/'.$date.'/sa';
if($suf!='0')
	$imgdir=$imgdir.'_'.$suf;
if(!is_dir($imgdir)){
	# if $d directory not exist create it with CHMOD 777
	mkdir($imgdir,0777); 
	# Copy the trap files to this new directory
	copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/donotremove/index.htm',$imgdir.'/index.htm');
	copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/donotremove/index.php',$imgdir.'/index.php');	
}



$cfg_filename = $nameitem.'.cfg';
$content =	'00100010:'.convert2Alias($person_name). "\n".
			'00100020:'.$pid. "\n".
			'00100021:'.$encounter_nr."\n".
			'00100030:'.$birthday. "\n".
			'00100040:'.$sex. "\n".
			'00080020:'.date('Ymd'). "\n".'00080021:'.date('Ymd'). "\n".'00080022:'.date('Ymd'). "\n".'00080023:'.date('Ymd'). "\n".
			'00080030:'.date('H:i:s'). "\n".'00080031:'.date('H:i:s'). "\n".'00080032:'.date('H:i:s'). "\n".'00080033:'.date('H:i:s'). "\n".
			'00200010:'.$encounter_nr. "\n".
			'0020000D:1.2.40.0.13.1.127.0.0.1.28541929.'.$uid."\n".
			'0020000E:1.2.40.0.13.1.127.0.0.1.28541929.'.$uid."\n".
			'00080060:OT'."\n".'00200011:'.$batch_nr."\n".
			'00080070:IAMI/VAST'."\n".'00080080:BVDK DT'."\n".'00080064:SI'."\n".'00200013:'.$item_code."\n".
			'00280004:YBR_FULL_422'."\n".'00280010:640'."\n".'00280011:480'."\n".'00280100:8'."\n".'00280101:8'."\n".'00280102:7'."\n".
			'00080016:1.2.840.10008.5.1.4.1.1.7'."\n";	

$strlength = strlen($content);
$create = fopen($imgdir.'/'.$cfg_filename, "w");
$write = fwrite($create, $content, $strlength);
$close = fclose($create);
	
	
	
//$filecount = count(glob($persondir."/*.dcm"));

if($mode=='first'){
	$path = substr($imgdir, 3);
	$data=array('pid'=>$pid,
				'encounter_nr'=>$encounter_nr,
				'datefolder'=>$date,
				'doc_ref_ids'=>'1',
				'img_type'=>'dicom',
				'max_nr'=>$maxnr,
				'notes'=>$_POST['notes'].' vd:'.$batch_nr.' '.$item_code,
				'upload_date'=>date('Y-m-d'),
				'history'=>"Upload ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name']."\n",
				'create_id'=>$_SESSION['sess_user_name'],
				'create_time'=>date('YmdHis'),
				'path'=> $path);
					
	if($oid=$img->saveImgDiagnosticData($data)){
		# Get the primar key of the saved record
		$picnr = $img->LastInsertPK('nr',$oid);
	}
	
	$img->savefindingsradiosub($encounter_nr, $batch_nr, $item_code, $path);
}




//Create img Dicom
$groupname = 'sa'.$pid.'_'.date('YmdHis');

if ($_FILES["fileimage"]["error"] > 0)
{
	echo "Error: " . $_FILES["fileimage"]["error"] . "<br />";
}
else
{
    $jpg_filename= $_FILES["fileimage"]["name"];

    move_uploaded_file($_FILES["fileimage"]["tmp_name"], $imgdir.'/'.$jpg_filename);
    
	  
	$jpg_image = $imgdir.'/'.$jpg_filename;
	$cfg_file = $imgdir.'/'.$cfg_filename;
	$dcm_file_temp = $imgdir.'/'.$groupname.'_'.$nameitem."_temp.dcm";
	$dcm_file = $imgdir.'/'.$groupname.'_'.$nameitem.".dcm";
	
	//$dcm_cmd = 'C:/dcm4che-2.0.19/bin/jpg2dcm.bat -c '.$cfg_file.' '.$jpg_image.' '.$dcm_file_temp;
	//$dcm_cmd2 = 'C:/dcm4che-2.0.19/bin/dcm2dcm.bat '.$dcm_file_temp.' '.$dcm_file;
	$dcm_cmd = '/usr/local/dcm4che-2.0.19/bin/jpg2dcm -c '.$cfg_file.' '.$jpg_image.' '.$dcm_file_temp;
	$dcm_cmd2 = '/usr/local/dcm4che-2.0.19/bin/dcm2dcm '.$dcm_file_temp.' '.$dcm_file;
	$dcm_cmd3 = '/usr/local/dcm4che-2.0.19/bin/dcmsnd DCM4CHEE@'.$_SERVER['SERVER_NAME'].':11112 '.$dcm_file;

	system($dcm_cmd);
	system($dcm_cmd2);
	system($dcm_cmd3);
	
	sleep(2);
	if (file_exists($dcm_file))
		unlink($dcm_file_temp);
	
	//echo $dcm_cmd;
	//echo $dcm_cmd2;

}



?>