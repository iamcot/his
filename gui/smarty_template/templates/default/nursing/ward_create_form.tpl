{{* ward_create_form.tpl  Form template for creating new ward 2004-06-28 Elpidio Latorilla *}}
{{* Note: the input elements are written in raw form here to give you the chance to redimension them. *}}
{{* Note: In redimensioning the input elements, be very careful not to change their names nor value tags. *}}
{{* Note: Never change the "maxlength" value *}}

{{$sMascotImg}} {{$sStationExists}} 

<style>
.adm_item{
    text-align:right;
}
</style>
<form action="nursing-station-new.php" method="post" name="newstat" onSubmit="return check(this)">
<table class="propertylist nursingtb">
  <tbody>
    <tr>
      <td class="adm_item" style="width:20%">{{$LDStation}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input">{{$inputwardname}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDWard_ID}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input">{{$inputwardid}} [a-Z,1-0] {{$LDNoSpecChars}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDDept}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input">{{$sDeptSelectBox}} {{$sSelectIcon}} {{$LDPlsSelect}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDDescription}}</td>
      <td class="adm_input"><textarea name="description" style="width:98%;" rows=8 wrap="physical" onblur="checkDescriptionText(this)">{{$description}}</textarea></td>
    </tr>
    <tr>
        <td class="adm_item">Số phòng <span {{$sColor}}>{{$sRequest}}</span></td>
       <td class="adm_input"><input type="text" name="room_nr_end" id="room_count" size=4 maxlength=4 value="{{$room_count}}"></td>
    </tr>
    <!--
    <tr>
      <td class="adm_item">{{$LDRoom1Nr}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input"><input type="text" name="room_nr_start" id="tbx_room_nr_start" size=4 maxlength=4 value="{{$room_nr_start}}" onblur="changefirstNr(this)"></td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDRoom2Nr}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input"><input type="text" name="room_nr_end" id="tbx_room_nr_end" size=4 maxlength=4 value="{{$room_nr_end}}" onblur="changesecondNr(this)"></td>
    </tr>
    -->
    <tr>
      <td class="adm_item">{{$LDRoomPrefix}}</td>
      <td class="adm_input"><input type="text" name="roomprefix" size=4 maxlength=4 value="{{$roomprefix}}"></td>
    </tr>
	<tr>
      <td class="adm_item">{{$LDIsPatientArea}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input">{{$LDIsPatientAreaRadiobtn}}</td>
    </tr>
    <tr><td colspan="2">{{$LDEnterAllFields}}</td></tr>
  </tbody>
</table>
      
{{$sSaveButton}}{{$sCancel}}
</form>
