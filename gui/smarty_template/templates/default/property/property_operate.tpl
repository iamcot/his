{{* property_create_form.tpl  Form template for creating new property 2012-02-01 Nguyen Thanh Tung *}}
{{* Note: the input elements are written in raw form here to give you the chance to redimension them. *}}
{{* Note: In redimensioning the input elements, be very careful not to change their names nor value tags. *}}
{{* Note: Never change the "maxlength" value *}}

{{$sMascotImg}} {{$sStationExists}} {{$LDEnterAllFields}}
<p>

<form action="{{$LDSubmitLink}}" method="post" name="proptransmitting" onSubmit="return check(this)">
<table width="100%">
  <tbody>
    <tr>
      <td class="adm_item" style="width:15%;">{{$LDOperation}}</td>
      <td class="adm_input" style="width:35%;"><input type="text" name="operation" style="width:98%;" maxlength=40 value="{{$operation}}"></td>
	  <td class="adm_item" style="width:15%;">{{$LDTime}}</td>
      <td class="adm_input">{{$time}}</td>
    </tr>
	<tr>
      <td class="adm_item">{{$LDOperator}}</td>
      <td class="adm_input"><input type="text" name="operator" style="width:98%;" maxlength=40 value="{{$operator}}"></td>
	  <td class="adm_item">{{$LDResult}}</td>
      <td class="adm_input"><input type="text" name="result" style="width:98%;" maxlength=40 value="{{$result}}"></td>
    </tr>
    <tr>
      <td class="adm_item">{{$LDReason}}</td>
      <td class="adm_input"><input type="text" name="reason" style="width:98%;" maxlength=40 value="{{$reason}}"></td>
	  <td class="adm_item">{{$LDBeforeStatus}}</td>
      <td class="adm_input"><input type="text" name="before_status" style="width:98%;" maxlength=40 value="{{$beforestatus}}"></td>
    </tr>
	<tr>
	  <td class="adm_item" >{{$LDManager}}</td>
      <td class="adm_input">{{$manager}}</td>
	  <td class="adm_item">{{$LDAfterStatus}}</td>
      <td class="adm_input"><input type="text" name="after_status" style="width:98%;" maxlength=40 value="{{$afterstatus}}"></td>
    </tr>
  </tbody>
</table>
{{$sCancel}}{{$sSaveButton}}
</form>
<script>
	jQuery(function($){
		$("#f-calendar-field-1").mask("99/99/9999");	
	});
</script>
