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
$lang_tables[]='departments.php';
define('LANG_FILE','nursing.php');
$local_user='ck_edv_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/core/inc_front_chain_lang.php');
///$db->debug = true;
$thisfile=basename(__FILE__);
/* Load the ward object */
require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj=new Ward($ward_nr);
/* Load the dept object */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept=new Department;

$rows=0;

//$db->debug=1;

    /* Load the date formatter */
    include_once($root_path.'include/core/inc_date_format_functions.php');
	
	switch($mode){	
		
		case 'show': 
		{
			if($ward=&$ward_obj->getWardInfo($ward_nr)){
				//$rooms=&$ward_obj->getAllActiveRoomsInfo();
                                $rooms = $ward_obj->getAllRoomsInfo();
				$rows=true;
				extract($ward);
				// Get all medical departments
				/* Load the dept object */
/*				if($edit){
					include_once($root_path.'include/care_api_classes/class_department.php');
					$dept=new Department;							
					$depts=&$dept->getAllMedical();
				}
*/							
			}else{
				header('location:nursing-station-info.php'.URL_REDIRECT_APPEND);
				exit;
			}
			$breakfile='nursing-station-info.php'.URL_APPEND;
			break;
		}
		
		case 'update':
		{
			$_POST['nr']=$_GET['ward_nr'];
                        if((!isset($_POST['name']) && ($_POST['name']=="")) || (!isset($_POST['ward_id']) && ($_POST['ward_id']=="")) || (!isset($_POST['dept_nr']) && ($_POST['dept_nr']=="")) ) {
				echo $LDAlertInvalidAction;
				exit;
			}
//			if((!isset($_POST['name']) && ($_POST['name']=="")) || (!isset($_POST['ward_id']) && ($_POST['ward_id']=="")) || (!isset($_POST['dept_nr']) && ($_POST['dept_nr']=="")) || (!isset($_POST['room_nr_start']) && ($_POST['room_nr_start']=="")) || (!isset($_POST['room_nr_end']) && ($_POST['room_nr_end']==""))) {
//				echo $LDAlertInvalidAction;
//				exit;
//			}
//			if($_POST['room_nr_start'] > $_POST['room_nr_end']){
//				echo $LDAlertInvalidAction;
//				exit;
//			}
			if($_POST['dept_nr'] != $_POST['olddept']){
				if($ward_obj->checkWardExist($dept_nr, $name, $ward_id)){
					echo "$LDAlertWardExist";
					exit;
				}
			}
			else if(($_POST['ward_id'] != $_POST['oldwardid']) && ($_POST['name'] != $_POST['oldwardname'])){
				if($ward_obj->checkWardExist($dept_nr, $name, $ward_id)){
					echo "$LDAlertWardExist";
					exit;
				}
			}
			else if($_POST['ward_id'] != $_POST['oldwardid']){
				if($ward_obj->checkWardExist($dept_nr, "#@NoName@#", $ward_id)){
					echo "$LDAlertWardExist";
					exit;
				}
			}
			else if($_POST['name'] != $_POST['oldwardname']){
				if($ward_obj->checkWardExist($dept_nr, $name,  "#@NoID@#")){
					echo "$LDAlertWardExist";
					exit;
				}
			}
			$ward=&$ward_obj->getWardInfo($ward_nr);
			extract($ward);
//			$room_start_checked = $room_nr_start;
//			$room_end_checked = $room_nr_end;

//			for($i=$room_nr_start;$i<=$room_nr_end;$i++){
//				$room_start_checked = $i;
//				if($ward_obj->RoomExists($i)){
//					$patients = $ward_obj->countPatients($i);
//					if($patients == false) {
//						$room_start_checked = -1;
//						break;
//					}
//					else if($patients > 0) {
//						break;
//					}
//				}else {
//					$room_start_checked = -1;
//					break;
//				}	
//			}
//			
//			for($i=$room_nr_end;$i>=$room_nr_start;$i--){
//				$room_end_checked = $i;
//				if($ward_obj->RoomExists($i)){
//					$patients = $ward_obj->countPatients($i);
//					if($patients == false) {
//						$room_end_checked = -1;
//						break;
//					}
//					else if($patients > 0) {
//						break;
//					}
//				}else {
//					$room_end_checked = -1;
//					break;
//				}
//			}
			$is_ok = '1';
			$bedid = "";
//			if(($_POST['room_nr_start']<= $room_start_checked) && ($_POST['room_nr_end']>= $room_end_checked)){
//				for($i=$_POST['room_nr_start'];$i<=$_POST['room_nr_end'];$i++){
//					if($ward_obj->RoomExists($i)){
//						$bedid = 'nr_of_beds'.$i;
//						$patients = $ward_obj->countPatients($i);
//						if($patients == false) $is_ok = 'dberror';
//						else if($_POST[$bedid] < $patients) $is_ok = '0';
//					}
//				}
//			} else {
//				echo $LDAlertInvalidAction;
//				exit;
//			}
//			if($is_ok == '1'){
//				$final_room_start;
//				$final_room_end;
//				if($_POST['room_nr_start'] < $room_nr_start) $final_room_start = $_POST['room_nr_start'];
//				else $final_room_start = $room_nr_start;
//				if($_POST['room_nr_end'] > $room_nr_end) $final_room_end = $_POST['room_nr_end'];
//				else $final_room_end = $room_nr_end;
				for($i=0;$i<=$_POST['roomcount'];$i++){
                                    if(isset($_POST['room_nr'.$i]) && $_POST['room_nr'.$i]!=""){
                                        if(!$ward_obj->RoomExists($_POST['room_nr'.$i]) && $_POST['roomid'.$i]== 0)
                                                {
							$bedid = 'nr_of_beds'.$i;
							$roomnameid = 'roomname'.$i;
							$beds_info_name = 'info'.$i;
                                                        $type_nr = $_POST['type_nr'.$i];
							$ward_obj->createRoom($dept_nr, $_POST['room_nr'.$i], $_POST[$bedid], $_POST[$roomnameid], $_POST[$beds_info_name],$type_nr);
						}
						else{       
                                                        
							$bedid = 'nr_of_beds'.$i;
							$roomnameid = 'roomname'.$i;
							$beds_info_name = 'info'.$i;
                                                        $type_nr = $_POST['type_nr'.$i];
							$ward_obj->updateRoomBeds($dept_nr, $_POST['room_nr'.$i],$_POST[$bedid], $_POST[$roomnameid], $_POST[$beds_info_name],$type_nr,$_POST['roomid'.$i],$_POST['isclose'.$i]);
						} 
                                    }
                                }
//				for($i=$final_room_start;$i<=$final_room_end;$i++){
//					if(($i < $_POST['room_nr_start']) || ($i > $_POST['room_nr_end'])){
//						$ward_obj->tempcloseRoom($i, $dept_nr);
//					} else{
//                                            $_POST['type_nr']= $_POST['type_nr'.$i];
//                                                if(!$ward_obj->RoomExists($i))
//                                                {
//							$bedid = 'nr_of_beds'.$i;
//							$roomnameid = 'roomname'.$i;
//							$beds_info_name = 'info'.$i;
//							$ward_obj->createRoom($dept_nr, $i, $_POST[$bedid], $_POST[$roomnameid], $_POST[$beds_info_name],$_POST['type_nr']);
//						}
//						else{                                                        
//							$bedid = 'nr_of_beds'.$i;
//							$roomnameid = 'roomname'.$i;
//							$beds_info_name = 'info'.$i;
//							$ward_obj->updateRoomBeds($dept_nr, $i,$_POST[$bedid], $_POST[$roomnameid], $_POST[$beds_info_name],$_POST['type_nr']);
//						} 
//                                                
//					}
//				}
//			}else {
//				echo $LDAlertInvalidAction;
//				exit;
//			}
						
			if($ward_obj->updateWard($ward_nr,$_POST)){
                                $ward_obj->cot_updateWardCountRoom($ward_nr);
				header("location:nursing-station-info.php".URL_REDIRECT_APPEND."&edit=0&mode=show&ward_id=$station&ward_nr=$ward_nr");
				exit;
			}else{
				echo $LDAlertInvalidAction;
				exit;
			}
			break;
		}
		
		case 'close_ward':
		{
			if($ward_obj->hasPatient($ward_nr)){
				header("location:nursing-station-noclose.php".URL_REDIRECT_APPEND."&ward_id=$ward_id&ward_nr=$ward_nr");
				exit;
			}else{
				switch($close_type)
				{
					case 'temporary':		
					{
						$ward_obj->closeWardTemporary($ward_nr);
						break;
					}
					
					case 'nonreversible':	
					{
						$ward_obj->closeWardNonReversible($ward_nr);
						break;
					}
					
					case 're_open':	
					{	
						$ward_obj->reOpenWard($ward_nr);
					}
				}
				
				header("location:nursing-station-info.php".URL_REDIRECT_APPEND);
				exit;
			}
		}
							
		default:					
		{
			if($wards=&$ward_obj->getAllActiveWards()){
				# Count wards
				$rows=$wards->RecordCount();

				if($rows==1){
					# If only one ward, fetch the ward
					$ward=$wards->FetchRow();
					# globalize ward values
					extract($ward);
					# Get info on active rooms in ward
					$rooms=&$ward_obj->getAllActiveRoomsInfo($ward['nr']);
                                        
				}else{
					$rooms=$ward_obj->countCreatedRooms();
                                        
				}
			}else{
			 	//echo $ward_obj->getLastQuery()."<br>$LDDbNoRead";
			}
							
			$breakfile='nursing-station-manage.php?sid='.$sid.'&lang='.$lang;
		}
	} # End of switch($mode)
	
# Start the smarty templating
 /**
 * LOAD Smarty
 */
 # Note: it is advisable to load this after the inc_front_chain_lang.php so
 # that the smarty script can use the user configured template theme

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('nursing');

# Added for the common header top block

 $smarty->assign('sToolbarTitle',"$LDNursing $LDStation - $LDProfile");

 $smarty->assign('pbHelp',"javascript:gethelp('nursing_ward_mng.php','$mode','$edit')");

 # href for close button
 $smarty->assign('breakfile',$breakfile);

 # Window bar title
 $smarty->assign('sWindowTitle',"$LDNursing $LDStation - $LDProfile");

# Buffer page output

ob_start();
?>

<style type="text/css" name="formstyle">

td.pblock{ font-family: verdana,arial; font-size: 12; background-color: #ffffff}
td.pv{ font-family: verdana,arial; font-size: 12; color: #0000cc; background-color: #eeeeee}
div.box { border: solid; border-width: thin; width: 100% }
div.pcont{ margin-left: 3; }

</style>

<script language="javascript">
<!-- 
function check(d){
	if((d.description.value=="")||(d.roomprefix.value=="")){
		alert("<?php echo $LDAlertIncomplete ?>");
		return false;
	}
	if(d.room_nr_start.value>=d.room_nr_end.value){
		alert("<?php echo $LDAlertRoomNr ?>");
		return false;
	}
}
function checkTempClose(){
	if(confirm("<?php echo $LDSureTemporaryClose ?>")) return true;
		else return false;
}
function checkReopen(){
	if(confirm("<?php echo $LDSureReopenWard ?>")) return true;
		else return false;
}
function checkClose(f){
	if(confirm("<?php echo $LDSureIrreversibleClose ?>")){
		f.close_type.value="nonreversible";
		f.submit();
		return true;
	}else{
		return false;
	}
}
// -->
</script>

<?php

$sTemp = ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript',$sTemp);

# If one station is available, show its profile

if($rows==1) {
	# Assign table items
	$smarty->assign('LDStation',$LDStation);
	$smarty->assign('LDWard_ID',$LDWard_ID);
	$smarty->assign('LDDept',$LDDept);
	$smarty->assign('LDDescription',$LDDescription);
	$smarty->assign('LDRoom1Nr',$LDRoom1Nr);
	$smarty->assign('LDRoom2Nr',$LDRoom2Nr);
	$smarty->assign('LDRoomPrefix',$LDRoomPrefix);
	$smarty->assign('LDCreatedOn',$LDCreatedOn);
	$smarty->assign('LDCreatedBy',$LDCreatedBy);
	$smarty->assign('LDIsPatientArea',$LDIsPatientArea);
	# Assign input values
	$deptinfo = &$dept->getDeptAllInfo($dept_nr);
        //var_dump();
	$smarty->assign('name',$name);
	$smarty->assign('ward_id',$ward_id);
	$smarty->assign('dept_name',($$deptinfo['LD_var'])?$$deptinfo['LD_var']:$ward['dept_name']);
	$smarty->assign('description',$description);
	$smarty->assign('room_nr_start',$room_nr_start);
	$smarty->assign('room_nr_end',$room_nr_end);
	$smarty->assign('roomprefix',$roomprefix);
	$smarty->assign('date_create',formatDate2Local($date_create,$date_format));
	$smarty->assign('create_id',$create_id);
	$wardtype = $ward_obj->getWardTypeList(NULL);
	$wardtypelist = "";
	while($row = $wardtype->FetchRow()){
		$wardtypelist .= "<input type='radio' name='type' value='".$row['nr']."' ".($row['nr']==$ward['type']?"checked='true'":"")."/>".$row['name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
	}
	$smarty->assign('LDIsPatientAreaRadiobtn',$wardtypelist);
	# If rooms available, create list and show them
	$smarty->assign('LDRoomNr',$LDRoomNr);
	$smarty->assign('LDRoomName',$LDRoomName);
	$smarty->assign('LDRoomType',$LDRoomType);
	$smarty->assign('LDBedNr',$LDBedNr);
	$smarty->assign('LDRoomShortDescription',$LDRoomShortDescription);
	$smarty->assign('LDNoPatient',$LDNoPatient);
	$sTemp='';
	$roompatient = 0;
	if(is_object($rooms)){
		$toggle=0;
                //var_dump($rooms->FetchRow());
		while($room=$rooms->FetchRow()){
			if($toggle)	$trc='#dedede';
				else $trc='#efefef';
			$toggle=!$toggle;
                        //var_dump($room);
			$roompatient = $ward_obj->countPatients($room['room_nr']);
	//		var_dump($room);
			if($roomtype = $ward_obj->getRoomTypeList($room['type_nr']))
				$roomtype = $roomtype->FetchRow();
			else $roomtype = $ward_obj->getRoomTypeList()->FetchRow();
                        if($room['is_temp_closed']==1) $trc = "red";
			$sTemp=$sTemp.'
				<tr bgcolor="'.$trc.'">
				<td>&nbsp;'.strtoupper($ward['roomprefix']).' '.$room['room_nr'].'&nbsp;</td>
				<td  >&nbsp;'.$room['roomname'].'</td>
				<td>'.$$roomtype['LD_var'].'</td>
				<td  >&nbsp;<font color="#ff0000">&nbsp;'.$room['nr_of_beds'].'</td>
				<td  >&nbsp;'.$room['info'].'</td>
				<td  >&nbsp;'.($roompatient==-1?0:$roompatient).'</td>
				</tr>';
		}
	}
	$smarty->assign('sRoomRows',$sTemp);
	$smarty->assign('sClose','<a href="'.$breakfile.'" style="padding:5px 5px 5px 10px;float:left"><img '.createLDImgSrc($root_path,'close2.gif','0','absmiddle').' border="0"></a>');
        $smarty->assign('sEdit','nursing-station-modify.php'.URL_APPEND.'&ward_nr='.$ward['nr']);
	if($ward['is_temp_closed']){

	ob_start();
?>
		<form name="closer" method="post" action="<?php echo $thisfile ?>" onSubmit="return checkReopen()" onReset="return checkClose(this)">
			<input type="hidden" name="ward_nr" value="<?php echo $ward['nr'] ?>">
			<input type="hidden" name="mode" value="close_ward">
			<input type="hidden" name="close_type" value="re_open">
			<input type="hidden" name="sid" value="<?php echo $sid ?>">
			<input type="hidden" name="lang" value="<?php echo $lang ?>">
			<input type="hidden" name="ward_id" value="<?php echo $ward['ward_id'] ?>">
			<input type="submit" value="<?php echo $LDReopenWard ?>">
			<input type="reset" value="<?php echo $LDIrreversiblyCloseWard ?>">
		</form>
<?php

		$sTemp=ob_get_contents();
		ob_end_clean();

		$smarty->assign('sWardClosure',$sTemp);

	}else{
		ob_start();
?>
			<form name="closer" method="post" action="<?php echo $thisfile ?>" onSubmit="return checkTempClose()" onReset="return checkClose(this)">
				<input type="hidden" name="ward_nr" value="<?php echo $ward['nr'] ?>">
				<input type="hidden" name="mode" value="close_ward">
				<input type="hidden" name="close_type" value="temporary">
				<input type="hidden" name="sid" value="<?php echo $sid ?>">
				<input type="hidden" name="lang" value="<?php echo $lang ?>">
				<input type="hidden" name="ward_id" value="<?php echo $ward['ward_id'] ?>">
				<input type="submit" value="<?php echo $LDTemporaryCloseWard ?>">
				<input type="reset" value="<?php echo $LDIrreversiblyCloseWard ?>">
			</form>
<?php

		$sTemp=ob_get_contents();
		ob_end_clean();

		$smarty->assign('sWardClosure',$sTemp);
	
	}

}elseif($rows){
	
	# If more than one station available, create list and show

	ob_start();

?>
	
	<font class="prompt"><?php echo $LDExistStations ?></font>
	<table border=0 cellpadding=4 cellspacing=1>

<?php 
	echo '<tr class="wardlisttitlerow">
			<td><font face="verdana,arial" size="2" ><b>&nbsp;'.$LDOrder.'</b></td>
			<td><font face="verdana,arial" size="2" ><b>&nbsp;'.$LDWardModify.'</b></td>
			<td><font face="verdana,arial" size="2" ><b>&nbsp;'.$LDStation.'</b></td>
			<td><font face="verdana,arial" size="2" ><nobr><b>&nbsp;'.$LDWard_ID.'</b></nobr></td>
			<td><font face="verdana,arial" size="2" ><b>&nbsp;'.$LDDescription.'&nbsp;</b></td>
			<td><font face="verdana,arial" size="2" ><b>&nbsp;'.$LDDept.'&nbsp;</b></td>
			<td><font face="verdana,arial" size="2" ><b>&nbsp;'.$LDStatus.'&nbsp;</b></td>
			</tr>';
	$order = 0;
	$toggle=0;
	$room=array();
	# Align the nr of rooms to their respective ward numbers
	if(is_object($rooms)){
		while($room=$rooms->FetchRow()){
			$wbuf[$room['nr']]=$room['nr_rooms'];
		}
	}
	while($result=$wards->FetchRow()){
		if($toggle)	$trc='wardlistrow2';
			else $trc='wardlistrow1';
		$toggle=!$toggle;
		$order++;
		$buf='nursing-station-info.php'.URL_APPEND.'&mode=show&station='.$result['name'].'&ward_nr='.$result['nr'];
		$editbuf='nursing-station-modify.php'.URL_APPEND.'&station='.$result['name'].'&ward_nr='.$result['nr'];
		//$dept_info = &$dept->getDeptAllInfo($result['dept_nr']);
		echo '
	<tr class="'.$trc.'">
	<td>'.$order.'&nbsp;</td>
    <td style="text-align: center;">&nbsp;<a href="'.$editbuf.'"><img '.createComIcon($root_path,'25_edit_notes_blue.png','0','absmiddle').'></a></td> 
	<td><a href="'.$buf.'">'.ucfirst($result['name']).'</a> &nbsp;</td>
	<td>&nbsp;<a href="'.$buf.'" style="text-transform:uppercase;">'.ucfirst($result['ward_id']).'</a> &nbsp;</td>
	<td>'.ucfirst($result['description']).'&nbsp;</td>
	<td>'.$result['dept_name'].'&nbsp;</td>
	<td>';
                //&nbsp;&nbsp;<font face="Verdana, Arial" size=2>'.strtoupper($result[station]).'</a>
	if($result['is_temp_closed']){
		echo '<font  color="red">'.$LDTemporaryClosed.'</font>';
	}elseif(empty($wbuf[$result['nr']])){
		echo $LDRoomNotCreated.'<a href="nursing-station-new-createbeds.php'.URL_APPEND.'&ward_nr='.$result['nr'].'"> '.$LDCreate.'>></a>';
	}else{
		echo $wbuf[$result['nr']].' '.$LDRoom;
	}
	echo '&nbsp;</td>  
	</tr>';
	}
?>
	</table>
	
	<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'close2.gif','0','absmiddle') ?> border="0"></a>
	
<?php

	$sTemp = ob_get_contents();
	ob_end_clean();

}else{

	# If no wards available, prompt no ward
	
	$sTemp = '<p><font size=2 face="verdana,arial,helvetica">'.$LDNoWardsYet.'<br><img '.createComIcon($root_path,'redpfeil.gif','0','absmiddle').'> <a href="nursing-station-new.php'.URL_APPEND.'">'.$LDClk2CreateWard.'</a></font>';

}

if($rows==1){
		$smarty->assign('sMainBlockIncludeFile','nursing/ward_profile.tpl');
}else{
	# Assign the page output to main frame template
	$smarty->assign('sMainFrameBlockData',$sTemp);
}

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');

?>
