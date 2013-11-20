<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/

define('FILE_DISCRIM','.dcm'); # define here the file discrimator string 

$thisfile=basename(__FILE__);
$returnfile='show.php'.URL_REDIRECT_APPEND.'&pid='.$pid.'&nr='.$nr;

///$db->debug=1;

# Load paths and dirs
require_once($root_path.'global_conf/inc_remoteservers_conf.php');
# Create image object
require_once($root_path.'include/care_api_classes/class_image.php');
$img=new Image();
$img->useImgDiagnostic();

//echo 'mode='.$mode.' nr='.$nr.' maxpic='.$maxpic;

if ($nr){
	//get old path
	$a = $img->SelectImgDiagInfo($nr);
	$oldpath = $a['path'];
	$olddate = $a['datefolder'];
	$imgpath=$oldpath;
}

if(($mode=='update') && $maxpic && $nr) {

	# makedir lock flags
		//$persd=true;
		//$imgd=true;
		$notyetsaved=true;
	# Internal counter used for prepending the filename. Do not use zero!
		$icount=1; $iupdate=0;
		$persondir=$root_path.$dicom_img_localpath.$pid;

		//remove all oldpic in folder nr
		//$imgdir = $persondir.'/'.$nr;
		/*if(is_dir($imgdir)){
			$img->deleteAllItemInFolder($imgdir);
			copy($root_path.$dicom_img_localpath.'donotremove/donotremove/index.htm',$imgdir.'/index.htm');
			copy($root_path.$dicom_img_localpath.'donotremove/donotremove/index.php',$imgdir.'/index.php');
			$persd=false;
			$imgd=false;
		}*/

		
		
		# Begin storage of files
		for ($i=0;$i<$maxpic;$i++)
		{
		   $picfile='f'.$i;
		   # Check the image, use 'dcm' as discriminator
		   if(($typeimg=='dcm' && $img->isValidUploadedImage($_FILES[$picfile],'dcm')) || ($typeimg=='jpg' && $img->isValidUploadedImage($_FILES[$picfile],'jpg')))
		   {
				//$data['mime_type']=$picext;
				# Hard code image type to "dicom"
				# Save data into the database
				if($notyetsaved){
					//update
					$encounter_nr = $_POST['encounter_nr'];
					if($_POST['doc_ref_ids'])
						$prep = 'sa';
					else $prep = 'xq';
					$newpath = $root_path.$dicom_img_localpath.$pid.'/'.$encounter_nr.'/'.$olddate.'/'.$prep;
					
					if($oldpath!=$newpath){
						//create folder newpath if not exist
						$encdir=$persondir.'/'.$encounter_nr;
						if(!is_dir($encdir)){
							mkdir($encdir,0777);
							copy($root_path.$dicom_img_localpath.'donotremove/donotremove/index.htm',$encdir.'/index.htm');
							copy($root_path.$dicom_img_localpath.'donotremove/donotremove/index.php',$encdir.'/index.php');
						}
						$datedir=$persondir.'/'.$encounter_nr.'/'.$olddate;
						if(!is_dir($datedir)){
							mkdir($datedir,0777);
							copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/index.htm',$datedir.'/index.htm');
							copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/index.php',$datedir.'/index.php');
						}
						$imgpath=$newpath;		
						if(is_dir($imgpath)){		
							$listfolder=glob($imgpath."*",GLOB_ONLYDIR);
							if ($listfolder!=false){
								$lastname = $listfolder[count($listfolder)-1];
								$lastname = explode($prep.'_',$lastname);
								$suf = intval($lastname[1]) +1;								
								$imgpath= $imgpath.'_'.$suf;		//xq_1, xq_2
								$newpath = $imgpath;
							}
						}
						if(!is_dir($imgpath)){
							mkdir($imgpath,0777);		//sa, xq
							copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/donotremove/index.htm',$imgpath.'/index.htm');
							copy($root_path.$dicom_img_localpath.'donotremove/donotremove/donotremove/donotremove/index.php',$imgpath.'/index.php');
						}	
						
						//move all oldpic from oldpath into newpath
						//copy + unlink
						$listfile = glob($oldpath."/*.*");   //glob($oldpath."/*.dcm");
						for($j=0;$j<count($listfile);$j++){
							$tempname = str_replace($oldpath."/","",$listfile[$j]);
							rename($oldpath.'/'.$tempname, $newpath.'/'.$tempname);	
							//echo 'old='.$oldpath.'/'.$listfile[$j].'    new='.$newpath.'/'.$listfile[$j];
						}
						if (is_dir($oldpath)){
							$cmdremove="rm ".$oldpath." -rf";
							exec($cmdremove);
							//unlink($oldpath."/index.htm");
							//unlink($oldpath."/index.php");
							//rmdir($oldpath);
						}
					}
					
					
					$imgpath=$newpath;
					$data=array('pid'=>$pid,
									'encounter_nr'=>$_POST['encounter_nr'],
									'datefolder'=>$olddate,
									'doc_ref_ids'=>$_POST['doc_ref_ids'],
									'img_type'=>'dicom',
									'notes'=>$_POST['notes'],
									'upload_date'=>date('Y-m-d'),
									'history'=>"Update ".date('Y-m-d H:i:s')." ".$_SESSION['sess_user_name'],
									'modify_id'=>$_SESSION['sess_user_name'],
									'modify_time'=>date('YmdHis'),
									'path'=> $newpath);
					$img->updateImgDiagnosticData($data,$nr);
					$picnr = $nr;
					# Lock save this save routine
					$notyetsaved=false;
									
					
				}
				if(!$notyetsaved && $picnr){
			   		//$picfilename[$i]=$picnr.'.'.$picext;
					
					# Compose the prepend number
					# This will be prepended to filename eg. => 1003_angio.dcm
					# to simplify sorting of the filenames according to order of upload
					//$prep=1000+$icount;	
					$picfilename=$picnr.'_'.$_FILES[$picfile]['name'];

					
					//upload newpic to newpath
					# Store to the newly created directory
					$dir_path=$newpath.'/';				

					//echo $newpath;
					
					if (file_exists($dir_path.$picfilename))
						$iupdate++;
						
					# Save the uploaded image
					if($img->isValidUploadedImage($_FILES[$picfile],'dcm')){
						if($img->saveUploadedImage($_FILES[$picfile],$dir_path,$picfilename)){	
							$dcm_file = $dir_path.$picfilename;
							$dcm_cmd3 = '/usr/local/dcm4che-2.0.19/bin/dcmsnd DCM4CHEE@'.$_SERVER['SERVER_NAME'].':11112 '.$dcm_file;
							exec($dcm_cmd3);						
							# Increse internal count
							$icount++;
						}
					}
					elseif($img->isValidUploadedImage($_FILES[$picfile],'jpg')) {
						if($img->saveUploadedImage($_FILES[$picfile],$dir_path,$picfilename)){	
							$jpg_image = $dir_path.$picfilename;
							$alias = explode('.',$picfilename);
							$cfg_file = $dir_path.'up'.$alias[0].'.cfg';
							$dcm_file = $dir_path.'up'.$alias[0].'.dcm';
							$dcm_file_temp = $dir_path.'up'.$alias[0].'_temp.dcm';
							
							if (!file_exists($dcm_file)){
								$content =	'00100010:'.$namealias. "\n".
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
											'00280004:YBR_FULL_422'."\n".'00280100:8'."\n".'00280101:8'."\n".'00280102:7'."\n".
											'00080016:1.2.840.10008.5.1.4.1.1.7'."\n";	
								$strlength = strlen($content);
								$create = fopen($cfg_file, "w");
								$write = fwrite($create, $content, $strlength);
								$close = fclose($create);
							}
							$dcm_cmd = '/usr/local/dcm4che-2.0.19/bin/jpg2dcm -c '.$cfg_file.' '.$jpg_image.' '.$dcm_file_temp;
							exec($dcm_cmd);
							$dcm_cmd2 = '/usr/local/dcm4che-2.0.19/bin/dcm2dcm '.$dcm_file_temp.' '.$dcm_file;
							exec($dcm_cmd2);
							$dcm_cmd3 = '/usr/local/dcm4che-2.0.19/bin/dcmsnd DCM4CHEE@'.$_SERVER['SERVER_NAME'].':11112 '.$dcm_file;
							exec($dcm_cmd3);		

							sleep(2);
							if (file_exists($dcm_file))
								unlink($dcm_file_temp);
								
							# Increse internal count
							$icount++;
						}					
					}	
					
					# Save the uploaded image (new image)
					/*if($img->saveUploadedImage($_FILES[$picfile],$dir_path,$picfilename)){
						# Increse internal count
						$icount++;
					}*/
					
			   }else{
			   		//echo $img->getLastQuery();
				}
			} # end of if()
		} # End of for() loop
		
	# Now check if data integrity is ok
	# Check if data is stored in the database but image not correctly uploaded
	if(!$notyetsaved&&$picnr&&($icount==1)){
		$img->useImgDiagnostic();
		# delete the record entry from the database
		# Note: __delete is a private method and is used only in this exception case.
		//$img->__delete($picnr);
	}else{
		# Upload is successful, update datase with actual nr of files successfully uploaded
		$img->updateImgMaxNr($picnr,$icount-$iupdate-1);
		# Redirect to fresh mode
		header("location:show.php".URL_REDIRECT_APPEND."&pid=$pid&saved=1&mode=show&nr=$picnr&maxpic=$maxpic");
		exit;
	}
}

$mode='update';

$lang_tables[]='radio.php';
$lang_tables[]='prompt.php';
require('./include/init_show.php');

$page_title=$LDUploadDicom;

if($mode=='show'&&$nr) {
	//$imgpath=$root_path.$dicom_img_localpath.$pid."/$nr";
	$files=&$img->FilesListArray($imgpath,FILE_DISCRIM);
	$rows=$img->LastRecordCount();
}

# Default nr of files
if(!isset($maxpic)||!$maxpic||!is_numeric($maxpic)||$maxpic<0) $maxpic=4;

# Prepare some parameters based on selected dicom viewer module
$pop_only=false;

switch($_SESSION['sess_dicom_viewer']){
	case 'raimjava':
			$pop_only=true;
			break;
	default:
				# Default viewer
}

# Set break file
require('include/inc_breakfile.php');

if($mode=='show') $glob_obj->getConfig('medocs_%');
/* Load GUI page */
require('./gui_bridge/default/gui_show_update.php');
?>
