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

/* Load the dept object */
require_once($root_path.'include/care_api_classes/class_department.php');
$dept=new Department;
/* Load the ward object */
require_once($root_path.'include/care_api_classes/class_ward.php');
$ward_obj=new Ward($ward_nr);
$rows=0;
$roomcount = 0;

    /* Load the date formatter */
    include_once($root_path.'include/core/inc_date_format_functions.php');
		
	if($ward=&$ward_obj->getWardInfo($ward_nr)){
		$rooms=&$ward_obj->getAllRoomsInfo();//old getallActiveRoomsInfo
		$rows=true;
		extract($ward);		
	}else{
		header('location:nursing-station-info.php'.URL_REDIRECT_APPEND);
		exit;
	}
	$breakfile='nursing-station-info.php'.URL_APPEND;

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

	$roomtype = $ward_obj->getRoomTypeList(NULL);
	$roomtypelist = '<select name="type_nr">';
	while($row = $roomtype->FetchRow()){
		$roomtypelist .= '<option value="'.$row['nr'].'">'.$$row['LD_var'].'</option>';
	}
	$roomtypelist .= "</select>";
 
# Buffer page output
ob_start();
?>
<script src="<?php echo $root_path;?>js/jquery-1.7.min.js"></script>
<script src="<?php echo $root_path;?>js/jquery-1.7.js"></script>
<style type="text/css" name="formstyle">

td.pblock{ font-family: verdana,arial; font-size: 12; background-color: #ffffff}
td.pv{ font-family: verdana,arial; font-size: 12; color: #0000cc; background-color: #eeeeee}
div.box { border: solid; border-width: thin; width: 100% }
div.pcont{ margin-left: 3; }

</style>
<script language="javascript">
<!-- 
function check(d){
	if(d.name.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		return false;
	}
	if(d.ward_id.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		return false;
	}
	if(d.room_nr_start.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		d.room_nr_start.focus();
		return false;
	}
	if(d.room_nr_end.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		d.room_nr_end.focus();
		return false;
	}
	if(d.room_nr_start.value>d.room_nr_end.value){
		alert("<?php echo $LDAlertRoomNr ?>");
		d.room_nr_start.focus();
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
function checkName(name){
	var dept_nr = document.getElementById("sltdept").value;
	if((name.value != '<?php echo $name; ?>') && (name.value != "")){
		var xmlhttp;
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
				if(xmlhttp.responseText == "1") {
					alert("<?php echo $LDAlertWardNameExist; ?>");
					return false;
				}
			}
		}
		xmlhttp.open("GET","<?php echo $root_path;?>modules/nursing/checkWardNameExist.php?name="+name.value+"&dept_nr="+dept_nr,true);
		xmlhttp.send();
	}
}
function checkWardID(wardid){
	var dept_nr = document.getElementById("sltdept").value;
	if((wardid.value != '<?php echo $ward_id; ?>') && (wardid.value != "")){
		var xmlhttp;
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
				if(xmlhttp.responseText == "1") {
					alert("<?php echo $LDAlertWardIdExist; ?>");
					return false;
				}
			}
		}
		xmlhttp.open("GET","<?php echo $root_path;?>modules/nursing/checkWardIdExist.php?wardid="+wardid.value+"&dept_nr="+dept_nr,true);
		xmlhttp.send();
	}
}
function checkSelectDept(sltdept){
	var wardname = document.getElementById("tbxwardname");
	var wardid = document.getElementById("tbxwardid");
	if(sltdept.value==""){
		alert("<?php echo $LDAlertIncomplete ?>");
		return false;
	}else {
		if((wardname.value != '<?php echo $name; ?>') && (wardname.value != ""))  checkName(wardname);
		if((wardid.value != '<?php echo $ward_id; ?>') && (wardid.value != "")) checkWardID(wardid);
	}
}
//function changefirstNr(){
//	var newprefix = document.getElementById("tbxroomprefix").value;
//	var firstNr = document.getElementById("tbxfirstNr");
//	var secondNr = document.getElementById("tbxsecondNr").value;
//	var roomlist = document.getElementById("tblroomlist");
//	var firstroom = document.getElementById("firstroom");
//		
//	if(Number(firstNr.value) < 1){
//		alert("<?php echo $LDAlertRoomNrOver0; ?>");
//		firstNr.focus();
//		return;
//	}
//	if(Number(firstNr.value) > Number(secondNr)){
//		alert("<?php echo $LDAlertFirstNrLargerSecondNr; ?>");
//		firstNr.focus();
//		return;
//	}
//	if(isNaN(firstNr.value)) {
//		alert("<?php echo $LDAlertRoomNrisNumber; ?>");
//		firstNr.focus();
//		return;
//	}
//		
//	var toggle=0;
//	var trc='#dedede';
//	var roomnr = 0;
//		
//	var oldlist = roomlist.innerHTML.split("</tr>");
//	var roomcount = oldlist.length-2;
//	var newlist = '<tr class="wardlisttitlerow"><td style="width:10%;"><?php echo $LDRoom?></td><td style="width:20%;"><?php echo $LDRoomName?></td><td style="width:15%;"><?php echo $LDRoomType?></td><td style="width:10%;"><?php echo $LDBedNr ?></td><td><?php echo $LDRoomShortDescription ?></td><td style="width:10%;"><?php echo $LDNoPatient ?></td></tr>';
//		
//	if(Number(firstNr.value) < Number(firstroom.value)){
//		var add = Number(firstroom.value) - Number(firstNr.value);
//		for(i=0;i<add;i++){
//			if(toggle)	trc='#dedede';
//			else trc='#efefef';
//			toggle=!toggle;
//			roomnr = Number(firstNr.value) + i;
//			newlist += '<tr bgcolor="'+trc+'">';
//			newlist += '<td id="tdroom_nr_'+roomnr+'">&nbsp;'+ newprefix + " " + roomnr +'</td>';
//			newlist += '<td class=pv style="text-align: center;"><input type="text" name="roomname'+roomnr+'" id="tbxroomname_'+roomnr+'" value="" style="width:98%"/></td>';
//			var roomtypelist = "<select name='type_nr"+roomnr+"'><?php 
                        $roomtype = $ward_obj->getRoomTypeList(NULL);
			while($row = $roomtype->FetchRow()){
                            echo "<option value='".$row['nr']."' ".($room['type_nr']==$row['nr']?"selected":"").">".$$row['LD_var']."</option>";
			}				
                        ?></select>//";
//			newlist += '<td class=pv>'+roomtypelist+'</td>';
//			newlist += '<td class=pv><input type="text" name="nr_of_beds'+roomnr+'" id="tbxbed_'+roomnr+'" value="4" style="width:96%" onBlur="change_nr_of_beds(<?php echo $dept_nr.', '.$ward_nr;?>, roomnr, this.value, this.id)"/></td>';
//			newlist += '<td class=pv ><input type="text" name="info'+roomnr+'" id="tbxinfo_'+roomnr+'" value="Phòng hoạt động bình thường" style="width:98%"/></td>';
//			newlist += '<td id="tdpatientcount_'+roomnr+'">&nbsp;0&nbsp;</td>';
//			newlist += '</tr>';
//		}
//		for(i=1;i<=roomcount;i++){
//			newlist += oldlist[i] + "</tr>";
//		}
//		roomlist.innerHTML = newlist;
//		firstroom.value = firstNr.value;
//	}else if(Number(firstNr.value) > Number(firstroom.value)){
//		$.ajax({
//			type: "POST",
//			url: "<?php echo $root_path;?>modules/nursing/nursing-station-check-room.php",
//			data: "ward_nr=<?php echo $ward_nr;?>&first_room_id="+firstroom.value+"&last_room_id="+firstNr.value+"&direction=1",
//			success: function(result)
//			{
//				if(result == 'dberror'){
//					alert("<?php echo $LDAlertCountingPatientError; ?>");
//				}else if(!isNaN(result)){
//					alert("<?php echo $LDAlertRoomRangeError.' '.$roomprefix;?> "+result+"!");
//					firstNr.value = result;
//				}
//				var sub = Number(document.getElementById("tbxfirstNr").value) - Number(firstroom.value);
//				for(i= sub + 1;i<=roomcount;i++){
//					newlist += oldlist[i] + "</tr>";
//				}
//				roomlist.innerHTML = newlist;
//				firstroom.value = document.getElementById("tbxfirstNr").value;
//			}
//		});
//	}	
//}
	
//function changesecondNr(){
//	var newprefix = document.getElementById("tbxroomprefix").value;
//	var firstNr = document.getElementById("tbxfirstNr").value;
//	var secondNr = document.getElementById("tbxsecondNr");
//	var roomlist = document.getElementById("tblroomlist");
//	var lastroom = document.getElementById("lastroom");
//	
//	if(Number(secondNr.value) < 1){
//		alert("<?php echo $LDAlertRoomNrOver0; ?>");
//		secondNr.focus();
//		return;
//	}
//	if(Number(firstNr) > Number(secondNr.value)){
//		alert("<?php echo $LDAlertFirstNrLargerSecondNr; ?>");
//		secondNr.focus();
//		return;
//	}
//	if(isNaN(secondNr.value)) {
//		alert("<?php echo $LDAlertRoomNrisNumber; ?>");
//		secondNr.focus();
//		return;
//	}
//		
//	var toggle=0;
//	var trc='#dedede';
//	var roomnr = 0;
//		
//	var oldlist = roomlist.innerHTML.split("</tr>");
//	var roomcount = oldlist.length-2;
//	var newlist = '<tr class="wardlisttitlerow"><td style="width:10%;"><?php echo $LDRoom?></td><td style="width:20%;"><?php echo $LDRoomName?></td><td style="width:15%;"><?php echo $LDRoomType?></td><td style="width:10%;"><?php echo $LDBedNr ?></td><td><?php echo $LDRoomShortDescription ?></td><td style="width:10%;"><?php echo $LDNoPatient ?></td></tr>';
//		
//	if(Number(secondNr.value) > Number(lastroom.value)){
//		for(i=1;i<=roomcount;i++){
//			newlist += oldlist[i] + "</tr>";
//		}
//		var add = Number(secondNr.value) - Number(lastroom.value);
//		for(i=0;i<add;i++){
//			if(toggle)	trc='#dedede';
//			else trc='#efefef';
//			toggle=!toggle;
//			roomnr = Number(lastroom.value) + i + 1;
//			newlist += '<tr bgcolor="'+trc+'">';
//			newlist += '<td id="tdroom_nr_'+roomnr+'">&nbsp;'+ newprefix + " " + roomnr +'</td>';
//			newlist += '<td class=pv style="text-align: center;"><input type="text" name="roomname'+roomnr+'" id="tbxroomname_'+roomnr+'" value="" style="width:98%"/></td>';
//                        var roomtypelist = "<select name='type_nr"+roomnr+"'><?php 
                        $roomtype = $ward_obj->getRoomTypeList(NULL);
			while($row = $roomtype->FetchRow()){
                            echo "<option value='".$row['nr']."' ".($room['type_nr']==$row['nr']?"selected":"").">".$$row['LD_var']."</option>";
			}				
                        ?></select>//";
//			newlist += '<td class=pv>'+roomtypelist+'</td>';
//			newlist += '<td class=pv ><input type="text" name="nr_of_beds'+roomnr+'" id="tbxbed_'+roomnr+'" value="4" style="width:96%" onBlur="change_nr_of_beds(<?php echo $dept_nr.', '.$ward_nr;?>, roomnr, this.value, this.id)"/></td>';
//			newlist += '<td class=pv><input type="text" name="info'+roomnr+'" id="tbxinfo_'+roomnr+'" value="Phòng hoạt động bình thường" style="width:98%"/></td>';
//			newlist += '<td id="tdpatientcount_'+roomnr+'">&nbsp;0&nbsp;</td>';
//			newlist += '</tr>';
//		}
//		roomlist.innerHTML = newlist;
//		lastroom.value = document.getElementById("tbxsecondNr").value;
//	}
//	else if(Number(secondNr.value) < Number(lastroom.value)){
//		$.ajax({
//			type: "POST",
//			url: "<?php echo $root_path;?>modules/nursing/nursing-station-check-room.php",
//			data: "ward_nr=<?php echo $ward_nr;?>&first_room_id="+secondNr.value+"&last_room_id="+lastroom.value+"&direction=2",
//			success: function(result)
//			{
//				if(result == 'dberror'){
//					alert("<?php echo $LDAlertCountingPatientError; ?>");
//					return;
//				}else if(!isNaN(result)){
//					alert("<?php echo $LDAlertRoomRangeError.' '.$roomprefix;?> "+result+"!");
//					secondNr.value = result;
//				}
//				var sub = Number(lastroom.value) - Number(document.getElementById("tbxsecondNr").value);
//				for(i=1;i<=roomcount - sub;i++){
//					newlist += oldlist[i] + "</tr>";
//				}
//				roomlist.innerHTML = newlist;
//				lastroom.value = document.getElementById("tbxsecondNr").value;
//			}
//		});		
//	} 	
//}
function change_nr_of_beds(dept_nr, ward_nr, room_nr, beds, id){
		
	var room = document.getElementById(id);
		
	if(Number(room.value) < 0){
		alert("<?php echo $LDAlertBedNrOver0; ?>");
		return;
	}
	if(isNaN(room.value)) {
		alert("<?php echo $LDAlertBedNrIsNumber; ?>");
		return;
	}
	$.ajax({
		type: "POST",
		url: "<?php echo $root_path;?>modules/nursing/nursing-station-check-bednumber.php",
		data: "dept_nr="+dept_nr+"&ward_nr="+ward_nr+"&room_nr="+room_nr+"&beds="+beds,
		success: function(result)
		{
			if(result == 'dberror')
			{
				alert("<?php echo $LDAlertCountingPatientError; ?>");
			}else if(result != 'ok'){
				alert("<?php echo $LDAlertNumberofBed1; ?> "+result+" <?php echo $LDAlertNumberofBed2; ?>");
				room.value = result;
			}
		}
	});
}
// -->
</script>
<?php
$sTemp = ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$sTemp);

# If one station is available, show its profile
$action = 'nursing-station-info.php'.URL_APPEND.'&mode=update&ward_nr='.$ward_nr;
echo '<form name="wardmodification" action="'.$action.'"  method="post" onSubmit="return check(this)">';

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
	$smarty->assign('requestelement',$LDRequestElement);
	$smarty->assign('sRequest',"(*)");
	$smarty->assign('sColor','style="color:red;"');
	
	# Assign input values
	$smarty->assign('name',$name);
	$smarty->assign('ward_id',$ward_id);
	//$smarty->assign('dept_name',$dept_name);
	
	//$depts=&$dept->getAllMedical();
        $depts=&$dept->cot_getAllDept();
        //var_dump($depts);
	# Create department select box
	$sTemp = '<select name="dept_nr" id="sltdept" onblur="checkSelectDept(this)">';
	if($depts&&is_array($depts)){
		//while(list($x,$v)=each($depts)){
            foreach($depts as $row){
                    $opt="";
			$opt ='<option value="'.$row['nr'].'"';
			if($row['nr']==$dept_nr) 
                            $opt .=' selected ';
			$opt .='>';
			if(isset($$row['LD_var']) && $$row['LD_var']) $opt .=$$row['LD_var'];
				else $opt .=$row['name_formal'];
			$opt .='</option>';
                        $sTemp .= $opt;
                        //var_dump($opt); echo '<br>@<br>';
		}
	}
	$sTemp .='</select>';
	$smarty->assign('dept_name',$sTemp);
	
	$smarty->assign('description',$description);
	$smarty->assign('room_nr_start',$room_nr_start);
	$smarty->assign('room_nr_end',$room_nr_end);
	$smarty->assign('roomprefix',$roomprefix);
	$smarty->assign('date_create',formatDate2Local($date_create,$date_format));
	$smarty->assign('create_id',$create_id);
	$wardtype = $ward_obj->getWardTypeList(NULL);
	$wardtypelist = "";
	while($row = $wardtype->FetchRow()){
		$wardtypelist .= "<input type='radio' name='type' value='".$row['nr']."' ".($row['nr']==$ward['type']?"checked='1'":"")."/>".$row['name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
	}
	$smarty->assign('LDIsPatientAreaRadiobtn',$wardtypelist);
	$smarty->assign('olddept',$dept_nr);
	$smarty->assign('oldwardname',$name);
	$smarty->assign('oldwardid',$ward_id);
	# If rooms available, create list and show them
	$smarty->assign('LDRoom',$LDRoomNr);
	$smarty->assign('LDRoomName',$LDRoomName);
	$smarty->assign('LDRoomType',$LDRoomType);
	$smarty->assign('LDBedNr',$LDBedNr);
	$smarty->assign('LDRoomShortDescription',$LDRoomShortDescription);
	$smarty->assign('LDNoPatient',$LDNoPatient);
	
	$roomtypelist = "";
	$sTemp='';
	$roompatient = 0;
	if(is_object($rooms)){
		$toggle=0;
		$roomcount = 1;
		while($room=$rooms->FetchRow()){                        
			if($toggle)	$trc='#dedede';
				else $trc='#efefef';
			$toggle=!$toggle;
			$roompatient = $ward_obj->countPatients($room['room_nr']);
			$roomtype = $ward_obj->getRoomTypeList(NULL);
			$roomtypelist = "<select name='type_nr".$roomcount."' ".(($roompatient>0 && $room['type_nr']>0)?'disabled=\'true\'':'')." >";
			while($row = $roomtype->FetchRow()){
				$roomtypelist .= "<option value='".$row['nr']."' ".($room['type_nr']==$row['nr']?"selected":"").">".$$row['LD_var']."</option>";
			}
			$roomtypelist .= "</select>";
                        if($room['is_temp_closed']==1) $trc = "red";
			$sTemp=$sTemp.'
				<tr bgcolor="'.$trc.'" id="tr'.$roomcount.'" roomnr="'.$room['room_nr'].'">
                                    <td style="padding:3px;width:40px;text-align:center;">';
                        if($room['is_temp_closed']==0)
                        $sTemp.='<a  id="ainfo'.$roomcount.'"  href="javascript:closeroom(\''.$room['nr'].'\',\''.$roomcount.'\')"><img src="../../gui/img/common/default/delete.png"  id="img'.$roomcount.'" title="Đóng phòng này"></a><input type="hidden"  id="iscloseid'.$roomcount.'"  name="isclose'.$roomcount.'" value="0">';
                        else $sTemp.='<a id="ainfo'.$roomcount.'" href="javascript:reopenroom(\''.$room['nr'].'\',\''.$roomcount.'\')"><img title="Phòng này đang đóng. Click để mở phòng" id="img'.$roomcount.'" src="../../gui/img/common/default/infowarning.png"></a><input type="hidden" id="iscloseid'.$roomcount.'" name="isclose'.$roomcount.'" value="1">';
                            $sTemp.='</td>
				<td id="tdroom_nr_'.$roomcount.'">&nbsp;'.strtoupper($ward['roomprefix']).' <input type="text" maxlength=4 style="width:50px" name="room_nr'.$roomcount.'" value="'.$room['room_nr'].'">&nbsp;<input type="hidden" name="roomid'.$roomcount.'" value="'.$room['nr'].'"></td>
				<td class=pv style="text-align: center;"><input type="text" name="roomname'.$roomcount.'" id="tbxroomname_'.$room['room_nr'].'" value="'.$room['roomname'].'" style="width:98%"/></td>
				<td>'.$roomtypelist.'</td>
				<td class=pv style="text-align: center;" ><input type="text" name="nr_of_beds'.$roomcount.'" id="tbxbed_'.$roomcount.'" value="'.$room['nr_of_beds'].'" style="width:50px" onBlur="change_nr_of_beds('.$dept_nr.', '.$ward_nr.', '.$room['room_nr'].', this.value, this.id)" '.($type==1?"readonly=true":"").'/></td>
				<td class=pv style="text-align: center;"><input type="text" name="info'.$roomcount.'" id="tbxinfo_'.$roomcount.'" value="'.$room['info'].'" style="width:98%"/></td>
				<td id="tdpatientcount_'.$roomcount.'">&nbsp;'.($roompatient==-1?0:$roompatient).'&nbsp;</td>
                                <td id="del'.$roomcount.'" style="text-align:center;width:30px;">';
                                if($room['is_temp_closed']==1) $sTemp.='<a href="javascript:deleteroom(\''.$room['nr'].'\',\''.$roomcount.'\')"><img src="../../gui/img/common/default/infodelete.png"></a>';
                           $sTemp.='</td>    
				</tr>';
			$roomcount++;
		}                
	} 
	$smarty->assign('sRoomRows',$sTemp);
	$smarty->assign('sClose','<a href="'.$breakfile.'" class="butcancel"><img '.createLDImgSrc($root_path,'close2.gif','0','absmiddle').' border="0"></a>');

	ob_start();
?>
<input type="hidden" name="roomcount" value="<?php echo $roomcount;?>" id="roomcountid">
<input type="hidden" name="type_nr" value="">
<input type="submit" class="butadd" value="">
</form>
<div id="rangeofroom">
	<input type="hidden" id="firstroom" value="<?php echo $room_nr_start ?>">
	<input type="hidden" id="lastroom" value="<?php echo $room_nr_end ?>">
</div>
<?php
	$sTemp=ob_get_contents();
	ob_end_clean();
	$smarty->assign('sWardClosure',$sTemp);

	$smarty->assign('sMainBlockIncludeFile','nursing/ward_edit.tpl');
}

 /**
 * show Template
 */
 $smarty->display('common/mainframe.tpl');
?>

<script language="javascript">
function changeRoomPrefix(newprefix){
	var firstNr = document.getElementById("tbxfirstNr").value;
	var roomcount = <?php echo $roomcount ?>; 
	var roomnr = 0;
	for(i=0;i<roomcount;i++){
		roomnr = Number(firstNr) + i;
		document.getElementById("tdroom_nr_"+i).innerHTML = "&nbsp;" + newprefix + " " + roomnr;
	}
}
function reopenroom(nr,roomcount){
    $.ajax({
        url:"<?php echo $root_path;?>modules/nursing/nursing-station-check-room.php",
        type:"post",
        data:"nr="+nr+"&direction=6",
        success: function(msg){
            if(msg=="1"){
                $("#tr"+roomcount).attr("bgcolor","#efefef");
                $("#img"+roomcount).attr("src","../../gui/img/common/default/delete.png")
                $("#del"+roomcount).html('');
                $("#iscloseid"+roomcount).val("0");
                $("#ainfo"+roomcount).attr("href","javascript:closeroom('"+nr+"','"+roomcount+"')");
            }
            else{
                alert("Không thể MỞ phòng này.");
            }
        }
        });
}
function deleteroom(nr,roomcount){
    $.ajax({
        url:"<?php echo $root_path;?>modules/nursing/nursing-station-check-room.php",
        type:"post",
        data:"room_nr="+nr+"&ward_nr="+<?php echo $ward_nr;?>+"&direction=5",
        success: function(msg){
            if(msg=="1"){
                $("#tr"+roomcount).remove();
                $("#tbxroomcount").val($("#tbxroomcount").val()*1-1);
                $("#roomcountid").val($("#roomcountid").val()*1-1);
            }
            else{
                alert("Không thể XÓA phòng này.");
            }
        }
        });
}
function closeroom(nr,roomcount){
    var havepatien = $("#tdpatientcount_"+roomcount).html();
    havepatien = havepatien.replace(/&nbsp;/g,"");
    //alert(havepatien);
    if(havepatien>0){
        alert("Phòng đang có bệnh nhân không thể đóng. Vui lòng chuyển BN đi trước.");
        return;
    }
    else{
        $.ajax({
        url:"<?php echo $root_path;?>modules/nursing/nursing-station-check-room.php",
        type:"post",
        data:"nr="+nr+"&direction=4",
        success: function(msg){
            if(msg=="1"){
                $("#tr"+roomcount).attr("bgcolor","red");
                $("#img"+roomcount).attr("src","../../gui/img/common/default/infowarning.png")
                $("#del"+roomcount).html('<a href="javascript:deleteroom(\''+nr+'\',\''+roomcount+'\')"><img src="../../gui/img/common/default/infodelete.png"></a>');
                $("#iscloseid"+roomcount).val("1");
                $("#ainfo"+roomcount).attr("href","javascript:reopenroom('"+nr+"','"+roomcount+"')");
            }
            else{
                alert("Không thể ĐÓNG phòng này.");
            }
        }
        });
        }
}
function removeroom(id){
    $("#"+id).remove();
    $("#tbxroomcount").val($("#tbxroomcount").val()*1-1);
    $("#roomcountid").val($("#roomcountid").val()*1-1);
}
function addroom(){
    var table = $("#tblroomlist");
    var trc='#dedede';
    var roomnr = 0;
    var newprefix = $("#tbxroomprefix").val();
         roomnr = $("#tblroomlist tr:last").attr("roomnr")*1+1;  
         var roomcount = ($("#roomcountid").val())*1;
         
           var newlist = "";
            newlist += '<tr bgcolor="'+trc+'" id="tr'+roomnr+'" roomnr="'+roomnr+'"><td style="padding:3px;width:40px;text-align:center;"><a href="javascript:removeroom(\'tr'+roomnr+'\')" href=""><img title="Phòng mới tạo. Click vào để bỏ phòng."  src="../../gui/img/common/default/infonew.png"></a><input type="hidden"  id="iscloseid'+roomcount+'"  name="isclose'+roomcount+'" value="0"></td>';
            newlist += '<td id="tdroom_nr_'+roomcount+'">&nbsp;'+ newprefix + ' <input type="text" <input type="text" maxlength=4 style="width:50px" name="room_nr'+roomcount+'" value="' + roomnr +'"><input type="hidden" name="roomid'+roomcount+'" value="0"></td>';
            newlist += '<td class=pv style="text-align: center;"><input type="text" name="roomname'+roomcount+'" id="tbxroomname_'+roomcount+'" value="" style="width:98%"/></td>';
            var roomtypelist = "<select name='type_nr"+roomcount+"'><?php 
            $roomtype = $ward_obj->getRoomTypeList(NULL);
            while($row = $roomtype->FetchRow()){
                echo "<option value='".$row['nr']."' ".($room['type_nr']==$row['nr']?"selected":"").">".$$row['LD_var']."</option>";
            }				
            ?></select>";
            newlist += '<td class=pv>'+roomtypelist+'</td>';
            newlist += '<td class=pv style="text-align: center;" ><input type="text" name="nr_of_beds'+roomcount+'" id="tbxbed_'+roomcount+'" value="4" style="width:50px" onBlur="change_nr_of_beds(<?php echo $dept_nr.', '.$ward_nr;?>, roomnr, this.value, this.id)"/></td>';
            newlist += '<td class=pv><input type="text" name="info'+roomcount+'" id="tbxinfo_'+roomcount+'" value="" style="width:98%"/></td>';
            newlist += '<td id="tdpatientcount_'+roomcount+'">&nbsp;0&nbsp;</td><td></td>';
            newlist += '</tr>';
            table.append(newlist);
            $("#tbxroomcount").val($("#tbxroomcount").val()*1+1);
            $("#roomcountid").val($("#roomcountid").val()*1+1);
    
}
</script>
