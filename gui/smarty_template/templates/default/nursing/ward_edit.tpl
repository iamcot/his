{{* ward_profile.tpl  Showing ward profile 2004-06-28 Elpidio Latorilla *}}

<style>
    #tblroomlist td{
     padding-left:5px;    
    }
    #tblroomlist .wardlisttitlerow td{
    text-align:center;    
    }
</style>
<ul>
<table style="width: 90%;">
	<tr>            
      <td class="adm_item" colspan="3">{{$requestelement}}<span {{$sColor}}>{{$sRequest}}</span></td>
    </tr>
    <tr>
      <td class="adm_item" style="width: 30%;">{{$LDStation}}<span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" colspan="2"><input type="text" name="name" id="tbxwardname" value="{{$name}}" style="width:98%" onblur='checkName(this)'/></td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDWard_ID}}<span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" colspan="2"><input type="text" name="ward_id" id="tbxwardid" value="{{$ward_id}}" style="width:98%;text-transform: uppercase;" onblur='checkWardID(this)'/></td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDDept}}<span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" name="dept_nr" colspan="2">{{$dept_name}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDDescription}}</td>
      <td class="adm_input" colspan="2"><textarea name="description" style="width:98%" rows=4 wrap="physical">{{$description}}</textarea></td>
    </tr>
    <tr>
      <td class="adm_item">Số phòng<span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" colspan="2"><input type="text" name="room_nr_end" id="tbxroomcount" value="{{$room_nr_end}}" style="width:98%" readonly="true"/></td>
    </tr>
     <!--
    <tr>
      <td class="adm_item">{{$LDRoom1Nr}}<span {{$sColor}}>{{$sRequest}}</span></td>
	  <td class="adm_input" colspan="2"><input type="text" name="room_nr_start" id="tbxfirstNr" value="{{$room_nr_start}}" style="width:98%" onblur="changefirstNr()"/></td>
    </tr>
   
    <tr>
      <td class="adm_item">{{$LDRoom2Nr}}<span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" colspan="2"><input type="text" name="room_nr_end" id="tbxsecondNr" value="{{$room_nr_end}}" style="width:98%" onblur="changesecondNr()"/></td>
    </tr>
    -->
    <tr>
      <td class="adm_item">{{$LDRoomPrefix}}</td>
      <td class="adm_input" colspan="2"><input type="text" name="roomprefix" id="tbxroomprefix" value="{{$roomprefix}}" style="width:98%;text-transform: uppercase;" onblur="changeRoomPrefix(this.value)"/></td>
    </tr>
   <tr>
      <td class="adm_item">{{$LDCreatedOn}}</td>
      <td class="adm_input" colspan="2">{{$date_create}}</td>
    </tr>
   <tr>
      <td class="adm_item">{{$LDCreatedBy}}</td>
      <td class="adm_input" colspan="2">{{$create_id}}</td>
    </tr>
	<tr>
      <td class="adm_item">{{$LDIsPatientArea}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" colspan="2">{{$LDIsPatientAreaRadiobtn}}</td>
    </tr>
	<tr>
      <td ><input type="hidden" name="olddept" value="{{$olddept}}" style="width:98%"/></td>
      <td ><input type="hidden" name="oldwardname" value="{{$oldwardname}}" style="width:98%"/></td>
	  <td ><input type="hidden" name="oldwardid" value="{{$oldwardid}}" style="width:98%"/></td>
    </tr>
	<tr>
      <td class="adm_item" colspan="3">&nbsp;</td>
    </tr>
</table>

<table id="tblroomlist" style="width: 90%;">
    <tr class="wardlisttitlerow">
        <td></td>
      <td style="width:10%;">{{$LDRoom}}</td>
	  <td style="width:20%;">{{$LDRoomName}}</td>
	  <td style="width:15%;">{{$LDRoomType}}</td>
      <td style="width:10%;">{{$LDBedNr}}</td>
      <td>{{$LDRoomShortDescription}}</td>
	  <td style="width:10%;">{{$LDNoPatient}}</td>
    </tr>
    <td></td>
	{{$sRoomRows}}
</table>
        <div style="padding:5px;width:90%;text-align: center;"><a title="Thêm phòng" href="javascript:addroom()"><img src="../../gui/img/common/default/add.png"></a></div>
{{$sClose}}
{{$sWardClosure}}
