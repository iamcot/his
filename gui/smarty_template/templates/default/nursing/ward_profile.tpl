{{* ward_profile.tpl  Showing ward profile 2004-06-28 Elpidio Latorilla *}}
<table style="width: 90%;" class="propertylist nursingtb">
    <tr>
      <td class="adm_item" style="width:20%;">{{$LDStation}}</td>
      <td class="adm_input" colspan="2">{{$name}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDWard_ID}}</td>
      <td class="adm_input" colspan="2">{{$ward_id}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDDept}}</td>
      <td class="adm_input" colspan="2">{{$dept_name}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDDescription}}</td>
      <td class="adm_input" colspan="2">{{$description}}</td>
    </tr>
    <tr>
      <td class="adm_item">Tổng số phòng </td>
      <td class="adm_input" colspan="2">{{$room_nr_end}}</td>
    </tr>
    <!--
    <tr>
      <td class="adm_item">{{$LDRoom1Nr}}</td>
      <td class="adm_input" colspan="2">{{$room_nr_start}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDRoom2Nr}}</td>
      <td class="adm_input" colspan="2">{{$room_nr_end}}</td>
    </tr>
    -->
    <tr>
      <td class="adm_item">{{$LDRoomPrefix}}</td>
      <td class="adm_input" colspan="2">{{$roomprefix}}</td>
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
      <td class="adm_item">{{$LDIsPatientArea}}</td>
      <td class="adm_input">{{$LDIsPatientAreaRadiobtn}}</td>
    </tr>
	<tr>
      <td class="adm_item" colspan="3">&nbsp;</td>
    </tr>
</table>	
  
<table id="tblroomlist" style="width: 90%;" class="propertylist nursingtb">
   <tr  class="wardlisttitlerow">
      <td style="width:10%;">{{$LDRoomNr}}</td>
	  <td style="width:20%;">{{$LDRoomName}}</td>
	  <td style="width:15%;">{{$LDRoomType}}</td>
      <td style="width:10%;">{{$LDBedNr}}</td>
      <td>{{$LDRoomShortDescription}}</td>
	  <td style="width:10%;">{{$LDNoPatient}}</td>
    </tr>
	{{$sRoomRows}}
</table>
<p>

</p>
<table width="90%">
  <tbody>
    <tr valign="top">
        
      <td>
          <a href="{{$sEdit}}" class="butedit" style="float:left"></a>
{{$sClose}}
      </td>
      <td align="right">{{$sWardClosure}}</td>
    </tr>
  </tbody>
</table>
