{{* property_create_form.tpl  Form template for creating new property 2012-02-01 Nguyen Thanh Tung *}}
{{* Note: the input elements are written in raw form here to give you the chance to redimension them. *}}
{{* Note: In redimensioning the input elements, be very careful not to change their names nor value tags. *}}
{{* Note: Never change the "maxlength" value *}}

{{$sMascotImg}} {{$sStationExists}} {{$LDEnterAllFields}}
<p>

<form action="#" method="post" name="newsprop" onSubmit="return check(this)">
<table width="100%">
  <tr>
      <td class="adm_item" style="width:15%;">{{$LDPropFormalName}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" style="width:25%;"><input type="text" name="name_formal" style="width:98%;" maxlength=200 value="{{$propformalname}}"></td>
	  <td class="adm_item" style="width:15%;">{{$LDDeptMana}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" style="width:15%;">
          <select class="inputtext" name="dept_mana">                
                {{$dept_mana}}
            </select>
      </td>
	  <td class="adm_item" style="width:15%;">{{$LDPropUnit}}</td>
      <td class="adm_input" style="width:15%;"><input type="text" name="unit" style="width:98%;" maxlength=40 value="{{$propunit}}"></td>
    </tr>
	<tr>
      <td class="adm_item" >{{$LDPropModel}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" ><input type="text" name="model" style="width:98%;" maxlength=40 value="{{$propmodel}}"></td>
	  <td class="adm_item" >{{$LDPropSerieNr}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" ><input type="text" name="serie" style="width:98%;" maxlength=40 value="{{$propserienr}}"></td>
	  <td class="adm_item" >{{$LDPropPower}}</td>
      <td class="adm_input" ><input type="text" name="power" style="width:98%;" maxlength=40 value="{{$proppower}}"></td>
    </tr>
	<tr>
	  <td class="adm_item" >{{$LDPropFunction}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" ><input type="text" name="propfunction" style="width:98%;" maxlength=200 value="{{$profunction}}"></td>
      <td class="adm_item" >{{$LDPropPrice}}</td>
      <td class="adm_input" ><input type="text" name="price" style="width:98%;" maxlength=40 value="{{$propprice}}"></td>
	  <td class="adm_item" >{{$LDPropSource}}</td>
      <td class="adm_input" ><select name="source" style="width:98%;">{{$prosource}}</select></td>
    </tr>
	<tr>
      <td class="adm_item"  rowspan="2">{{$LDPropDescription}}</td>
      <td class="adm_input"  rowspan="2"><textarea name="description" style="width:98%;" rows=3 wrap="physical">{{$prodescription}}</textarea></td>
	  <td class="adm_item" rowspan="2">{{$LDPropNote}}</td>
      <td class="adm_input"  rowspan="2"><textarea name="note" style="width:98%;" rows=3 wrap="physical">{{$propnote}}</textarea></td>
	  <td class="adm_item" >{{$LDPropMaker}}</td>
      <td class="adm_input" ><input type="text" name="factorer" style="width:98%;" maxlength=200 value="{{$propmaker}}"></td>
    </tr>
	<tr>
	  <td class="adm_item">{{$LDPropVendor}}</td>
      <td class="adm_input"><input type="text" name="vender" style="width:98%;" maxlength=200 value="{{$propvendor}}"></td>
	</tr>
	<tr>
	  <td class="adm_item" >{{$LDPropImStatus}}</td>
      <td class="adm_input" ><input type="text" name="importstatus" style="width:98%;" maxlength=200 value="{{$importstatus}}"></td>
	  <td class="adm_item" >{{$LDPropProductionYear}}</td>
      <td class="adm_input" >{{$productionyear}}</td>
	  <td class="adm_item" >{{$LDPropWarranty}}</td>
      <td class="adm_input" >{{$propwarranty}}</td>
    </tr>
	<tr>
	  <td class="adm_item" >{{$LDWorkOrStop}}?</td>
      <td class="adm_input" >{{$propstatus}}</td>
	  <td class="adm_item" >{{$LDPropImDate}}</td>
      <td class="adm_input" >{{$importdate}}</td>
	  <td class="adm_item" >{{$LDPropStartUseDate}}</td>
      <td class="adm_input" >{{$propusedate}}</td>
    </tr>
	<tr>
      <td class="adm_item" colspan="6">&nbsp;</td>
    </tr>
</table>
{{$sSaveButton}}
{{* {{$sCancel}} *}}
</form>

<table width="100%">
	{{$allpropertylist}}
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
		$("#f-calendar-field-3").mask("99/99/9999");
		$("#f-calendar-field-4").mask("99/99/9999");
	});
</script>