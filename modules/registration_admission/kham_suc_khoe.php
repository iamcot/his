<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System beta 2.0.1 - 2004-07-04
 * GNU General Public License
 * Copyright 2002,2003,2004,2005,2006 Elpidio Latorilla
 * elpidio@care2x.org,
 *
 * See the file "copy_notice.txt" for the licence notice
 */
 define('LANG_FILE','aufnahme.php');
$local_user='aufnahme_user';
require_once($root_path.'include/core/inc_front_chain_lang.php');
$breakfile="patient_register_show.php".URL_REDIRECT_APPEND."&pid=$pid&edit=1&status=&target=search&user_origin=&noresize=1&mode=";
	
	require_once($root_path.'include/care_api_classes/class_person.php');
$pers_obj = & new Person;
$person=$pers_obj->getAllInfoObject($pid);
$pers=$person->FetchRow();
	require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');
if($mode=='save'){
	//$_POST['date_kham']=@formatDate2STD(date("d/m/Y"),$date_format);
	$_POST['modify_id']=$_SESSION['sess_user_name'];
					//$_POST['modify_time']='NULL';
	$_POST['create_id']=$_SESSION['sess_user_name'];
	$_POST['create_time']=date('YmdHis');
	$_POST['history']='Create: '.date('Y-m-d H:i:s').' = '.$_SESSION['sess_user_name'];
	$sql="INSERT INTO care_kham_suc_khoe(pid,mucdichkham,ketqua,date_kham,history,modify_id,create_id,create_time)
	       VALUES ('".$pid."','".$_POST['mucdichkham']."','".$_POST['ketqua']."','".date("Y-m-d")."','".$_POST['history']."','".$_POST['modify_id']."','".$_POST['create_id']."','".$_POST['create_time']."')";
		   if($pers_obj->Transact($sql)){
		   
		  // header("Location:patient_register_show.php".URL_REDIRECT_APPEND."&pid=$pid&edit=1&status=&target=search&user_origin=&noresize=1&mode=");
		 ///  exit;
		   }else
		   {
		   echo  $LDDbNoSave.'<p>'.$sql;
		   }
}
# Title in the toolbar
 $smarty->assign('sToolbarTitle','Khám sức khỏe ('.$pid.')');

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('submenu1.php','$LDPatientRegister')");

 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('title','Khám sức khỏe');

 # Onload Javascript code
 $smarty->assign('sOnLoadJs',"if (window.focus) window.focus();");

 # href for help button
 $smarty->assign('pbHelp',"javascript:gethelp('person_admit.php')");

 # Hide the return button
 $smarty->assign('pbBack',FALSE);
 ob_start();
 ?>
 <script language="javascript">
 function show(){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("mucdichkham").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("POST","show.php",true);
	xmlhttp.send();
 }
 
 </script>
 <?php 

 $sTemp=ob_get_contents();
 ob_end_clean();
 $smarty->append('JavaScript',$sTemp);
 ob_start();
 global $db;
$khamtruoc = '<h2>CÁC LẦN KHÁM TRƯỚC</h2><table cellpadding="10xp"><tr><td class="reg_item">Ngày khám</td><td class="reg_item">Mục đích khám</td><td class="reg_item">Kết quả</td></tr>';
$sql="SELECT * FROM care_kham_suc_khoe where pid = '".$pid."' order by date_kham";
if($rs=$db->Execute($sql)){
	if($rs->RecordCount()){
		while ($row = $rs->FetchRow()) {
			switch ($row['mucdichkham']) {
				case 'tuyendung':
					$mucdichkham = 'Tuyển dụng';
					break;
				case 'hocsinh':
					$mucdichkham = 'Học sinh';
					break;
				case 'laixe':
					$mucdichkham = 'Lái xe';
					break;
				default:
					$mucdichkham = 'Khác';
					break;
			}
			$khamtruoc .= '<tr><td class="reg_input">'.date('d/m/Y',strtotime($row['date_kham'])).'</td><td class="reg_input">'.$mucdichkham.'</td><td class="reg_input">Loại '.$row['ketqua'].'</td></tr>';
		}
	}
}
$khamtruoc.='</table><BR><BR>';

 ?>
 </HEAD>

<BODY bgcolor="<?php echo $cfg['body_bgcolor'];?>" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 <?php if (!$cfg['dhtml']){ echo ' link='.$cfg['body_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['body_txtcolor']; } ?>>

<h2>NHẬP KẾT QUẢ LẦN KHÁM</h2>
<table cellspacing="0" cellpadding="0" border="1">
  <tbody>
  <tr>
  <td>
	  <form name="kham_suc_khoe" enctype="multipart/form-data" onsubmit="return chkform(this)"action="" method="post">
		<table >
			<tbody>
				<tr>
					<td class="reg_item">
						<?php echo $LDRegistryNr ?>
					</td>
					<td class="reg_input"> 
						<?php echo $pers['pid'] ?>
					</td>
				</tr>
				<tr>
					<td class="reg_item">
						Họ & Tên
					</td>
					<td class="reg_input">
						<?php echo $pers['name_last']." ".$pers["name_first"] ?>
					</td>
				</tr>
				<tr>
					<td class="reg_item">
						<?php echo $LDBday?>
					</td>
					<td class="reg_input">
						<?php echo ((strlen($pers["date_birth"])>4)?date("d/m/Y",strtotime($pers["date_birth"])):$pers["date_birth"])?>
					</td>
				</tr>
				<tr>
					<td class="reg_item">
					Mục đích khám
					</td>
					<td class="reg_input" id="mucdichkham">
						<input type="radio" name="mucdichkham" value="tuyendung">Tuyển dụng
						<input type="radio" name="mucdichkham" value="laixe">Lái xe
						<input type="radio" name="mucdichkham" value="hocsinh">Học sinh
						<input type="radio" name="mucdichkham" value="khac">Khác
					</td>
				</tr>
				<tr>
					<td class="reg_item">
						Kết quả
					</td>
					<td>
						<input type="radio" name="ketqua" value="I">Loại I
						<input type="radio" name="ketqua" value="II">Loại II
						<input type="radio" name="ketqua" value="III">Loại III
						<input type="radio" name="ketqua" value="IV">Loại IV
						<input type="radio" name="ketqua" value="V">Loại V
					</td>
				</tr>
				<tr>
					<td>
						<p>
							<input type="hidden" name="pid" value="<?php echo $pers['pid']?>">
							<input type="hidden" name="mode" value="save">
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<input  type="image"<?php echo createLDImgSrc($root_path,'savedisc.gif','0')?> title="<?php echo $LDSaveData ?>" align="absmiddle">	
					</td>
					<td>
						<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0')?>  title="<?php echo $LDCancel ?>"  align="right"></a>
					</td>
				</tr>
				
			</tbody>
		</table>
		
	  </form>
	  </td>
  </tr>
  </tbody>
</table>
<? echo $khamtruoc;?>
</BODY>
<?php
$sTemp = ob_get_contents();
ob_end_clean();

# Assign page output to the mainframe template

$smarty->assign('sMainFrameBlockData',$sTemp);
# Show main frame
$smarty->display('common/mainframe.tpl');
 ?>