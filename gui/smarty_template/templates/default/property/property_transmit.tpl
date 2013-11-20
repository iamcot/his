{{* property_create_form.tpl  Form template for creating new property 2012-02-01 Nguyen Thanh Tung *}}
{{* Note: the input elements are written in raw form here to give you the chance to redimension them. *}}
{{* Note: In redimensioning the input elements, be very careful not to change their names nor value tags. *}}
{{* Note: Never change the "maxlength" value *}}

{{$sMascotImg}} {{$sStationExists}} {{$LDEnterAllFields}}
<p>

<form action="{{$LDSubmitLink}}" method="post" name="proptransmitting" onSubmit="return check(this)">
<table width="100%">
	<tr>
      <td class="adm_item" style="text-align: left" colspan="4">{{$LDinstruction}}<span {{$sColor}}>{{$sRequest}}</span></td>
    </tr>
    <tr>
      <td class="adm_item" style="width:20%;">{{$LDPropFormalName}}</td>
      <td class="adm_input" style="width:30%;">{{$propformalname}}</td>
	  <td class="adm_item" style="width:20%;">{{$LDNewDept}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" style="width:30%;">{{$sDeptSelectBox}} {{$sSelectIcon}} {{$LDPlsSelectDept}}</td>
    </tr>
	<tr>
      <td class="adm_item">{{$LDPropShortName}}</td>
      <td class="adm_input">{{$propshortname}}</td>
	  <td class="adm_item">{{$LDNewWard}}</td>
      <td class="adm_input">{{$sWardSelectBox}} {{$sSelectIcon}}{{$LDPlsSelectWard}}</td>
    </tr>
    <tr>
	  <td class="adm_item">{{$LDPropModel}}</td>
      <td class="adm_input">{{$propmodel}}</td>
	  <td class="adm_item">{{$LDNewRoom}}</td>
      <td class="adm_input">{{$sRoomSelectBox}} {{$sSelectIcon}} {{$LDPlsSelectRoom}} </td>
    </tr>
	<tr>
	  <td class="adm_item">{{$LDPropSerieNr}}</td>
      <td class="adm_input">{{$propserie}}</td>
	  <td class="adm_item">{{$LDNewManager}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input">{{$newmanager}}</td>
	</tr>
	<tr>
		<td class="adm_item" >{{$LDOldDept}}</td>
      <td class="adm_input">{{$oldept}}</td>
	  <td class="adm_item">{{$LDNewFunction}}</td>
      <td class="adm_input"><input type="text" name="function" style="width:98%;" maxlength=40 value="{{$newfunction}}"></td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDOldWard}}</td>
      <td class="adm_input">{{$oldward}}</td>
	  <td class="adm_item">{{$LDReason}}</td>
      <td class="adm_input"><input type="text" name="reason" style="width:98%;" maxlength=40 value="{{$reason}}"></td>
    </tr>
	<tr>
      <td class="adm_item">{{$LDOldRoom}}</td>
      <td class="adm_input">{{$oldroom}}</td>
	  <td class="adm_item">{{$LDImpStatus}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input"><input type="text" name="im_status" style="width:98%;" maxlength=40 value="{{$impstatus}}"></td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDOldManager}}</td>
      <td class="adm_input">{{$oldmanager}}</td>
	  <td class="adm_item">{{$LDImDate}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input">{{$importdate}}</td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDOldFunction}}</td>
      <td class="adm_input">{{$oldfunction}}</td>
	  <td class="adm_item">{{$LDUsingDate}}</td>
      <td class="adm_input">{{$usingdate}}</td>
    </tr>
	<tr>
	  <td class="adm_item" >{{$LDCurrentStatus}}</td>
      <td class="adm_item" colspan="3"><input type="text" name="current_status" style="width:100%;" maxlength=40 value="{{$currentreason}}"></td>
    </tr>
	<tr>
	  <td class="adm_item" style="text-align: left;height: 20px;" colspan="4"><span {{$sColor}}>{{$actionnotation}}</span></td>
	</tr>
</table>
{{$sCancel}}{{$sSaveButton}}
</form>

<table width="100%">
	{{$propertylis}}
</table>
<table width="100%">
	<tr style="text-align: right;">
		<td>{{$pagelist}}</td>
	</tr>
</table>
<script>
	jQuery(function($){
		$("#f-calendar-field-1").mask("99/99/9999");	
		$("#f-calendar-field-2").mask("99/99/9999");
	});
</script>