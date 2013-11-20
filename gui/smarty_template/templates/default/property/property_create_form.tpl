{{* property_create_form.tpl  Form template for creating new property 2012-02-01 Nguyen Thanh Tung *}}
{{* Note: the input elements are written in raw form here to give you the chance to redimension them. *}}
{{* Note: In redimensioning the input elements, be very careful not to change their names nor value tags. *}}
{{* Note: Never change the "maxlength" value *}}

{{$sMascotImg}} {{$sStationExists}} {{$LDEnterAllFields}}
<p>
<form action="#" method="post">
    <table width="50%" ><tr>
<td class="adm_item">Sản phẩm cùng Mã hiệu</td><td class="adm_input"> <input type="text" name="modelinfo" style="width:80%" value="{{$propmodel}}">
            <input type="hidden" name="mode" value="copy">
            <input type="submit" value="Lấy" name="getmodelinfo"></td>
</tr></table>
</form>
<form action="#" method="post" name="newsprop" enctype="multipart/form-data" onSubmit="return check(this)">   
    
<table width="100%" class="property">
    <tr>
       <td class="adm_item" >{{$LDPropFormalName}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" colspan="3"><input type="text" class="inputtext" name="name_formal" maxlength=200 value="{{$propformalname}}" ></td>
      
        <td class="adm_item" >Phòng quản lý</td>
        <td class="adm_input">
            <!-- Dung ma~ noi bo cua phong de lam key -->
            <select class="inputtext" name="dept_mana">                
                {{$dept_mana}}
            </select>
        </td>        
    </tr>
    <tr>      
       <td class="adm_item" >{{$LDPropModel}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" ><input type="text" name="model"  class="inputtext"  maxlength=40 value="{{$propmodel}}"> 
      <td class="adm_item" >{{$LDPropSerieNr}}<span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" ><input type="text" name="serie"  class="inputtext"  maxlength=40 value="{{$propserienr}}"></td>
      <!--
      <td class="adm_item" >{{$LDPropCount}} <span {{$sColor}}>{{$sRequest}}</span></td>
      <td class="adm_input" ><input type="text" name="pcount" class="inputtext" maxlength=100 value="{{$propcount}}" ></td>-->
      <td class="adm_item" >{{$LDPropType}}</td>
        <td class="adm_input" ><input type="text" name="proptype"  class="inputtext"  maxlength=40 value="{{$proptype}}"></td>
        
    </tr>
	<tr>
           
      </td>
	 
	  <td class="adm_item" >{{$LDPropPower}}</td>
      <td class="adm_input" ><input type="text" name="power"  class="inputtext"  maxlength=40 value="{{$proppower}}"></td>
      <td class="adm_item" >{{$LDPropvolta}}</td>
      <td class="adm_input" ><input type="text" name="volta"  class="inputtext"  maxlength=40 value="{{$volta}}"></td>
      <td class="adm_item" >{{$LDPropUnit}}</td>
        <td class="adm_input" ><input type="text" name="unit"  class="inputtext"  maxlength=40 value="{{$propunit}}"></td>
    </tr>
	<tr>
	  <td class="adm_item" >{{$LDPropFunction}} </td>
      <td class="adm_input" ><input type="text" name="propfunction"  class="inputtext"  maxlength=200 value="{{$profunction}}"></td>
      <td class="adm_item" >{{$LDPropPrice}}</td>
      <td class="adm_input" ><input type="text" name="price" id="price" onblur="this.value=addCommas(this.value)" class="inputtext"  maxlength=40 value="{{$propprice}}"></td>
	  <td class="adm_item"  >{{$LDPropMaker}}</td>
      <td class="adm_input" ><input type="text" name="factorer"  class="inputtext"  maxlength=200 value="{{$propmaker}}"></td>
    </tr>
	<tr>
      <td class="adm_item"  rowspan="2">{{$LDPropDescription}}</td>
      <td class="adm_input"  rowspan="2"><textarea name="description" class="inputtext" rows=3 wrap="physical">{{$prodescription}}</textarea></td>
	 <td class="adm_item"  rowspan="2">{{$LDPropNote}}</td>
      <td class="adm_input"  rowspan="2"><textarea name="note"  class="inputtext"  rows=3 wrap="physical">{{$propnote}}</textarea></td>
      <td class="adm_item" >{{$LDPropCountry}}</td>
            <td class="adm_input"> <input type="text" name="country"  class="inputtext"  maxlength=40 value="{{$propcountry}}"></td> 
	  
    </tr>
	<tr>
           
	  <td class="adm_item">{{$LDPropVendor}}</td>
      <td class="adm_input"><input type="text" name="vender" class="inputtext"  maxlength=200 value="{{$propvendor}}"></td>
	</tr>
	<tr>
            <td class="adm_item" > {{$LDPropImStatus}}</td>
            <td class="adm_input"><input type="text" name="importstatus" width="60%"  maxlength=200 value="{{$importstatus}}"></td>                     
	  <td class="adm_item" >{{$LDPropProductionYear}}</td>
      <td class="adm_input" >{{$productionyear}}</td>
	  <td class="adm_item" >{{$LDPropWarranty}}</td>
      <td class="adm_input" >{{$propwarranty}}</td>
    </tr>
	<tr>
            <td class="adm_item">{{$LDPropUsedStatus}}</td>   
      <td class="adm_input"> <input type="text" name="usepercent" id="usepercent" width="20%"  maxlength=3 value="{{$usepercent}}" onblur="checkpercent('usepercent',this.value)"></td>
	 
	  <td class="adm_item" >{{$LDPropImDate}}</td>
      <td class="adm_input" >{{$importdate}}</td>
	  <td class="adm_item" >{{$LDPropStartUseDate}}</td>
      <td class="adm_input" >{{$propusedate}}</td>
    </tr>
    <tr>
	  <td class="adm_item" >{{$LDPropImage}}:</td>
      <td class="adm_input" >{{$propimage}}
      
      </td>
	  <td class="adm_item" >{{$LDPropMannual}}:</td>
      <td class="adm_input" >{{$propmannual}}
           <td class="adm_item" >{{$LDWorkOrStop}}</td>
      <td class="adm_input" >{{$propstatus}}</td>
      </td>
    </tr>
    <tr>
    <td class="adm_item" >Link Hình:</td><td class="adm_input"><input type="text" readonly="true" name="tmp_img"  value="{{$tmp_img}}"></td>
    <td class="adm_item" >Link Tài liệu</td><td class="adm_input"><input type="text" readonly="true" name="tmp_manual"  value="{{$tmp_manual}}"></td>
    <td class="adm_item" >{{$LDPropSource}}</td>
      <td class="adm_input" ><select name="source"  class="inputtext" >{{$prosource}}</select></td>
    </tr>
	<tr>
      <td class="adm_item" colspan="2" style="text-align: left;">{{$LDinstruction}}<span {{$sColor}}>{{$sRequest}}</span></td>
	  <td class="adm_item" colspan="4" style="text-align: left;"><span {{$sColor}}>{{$actionnotation}}</span></td>
    </tr>
    
</table>
{{$sCancel}}{{$sSaveButton}}
</form>
<table width="99%" class="propertylist">
	{{$allpropertylist}}
</table>
<table width="99%" class="propertylist">
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
        function addCommas(str) {
            str=str.replace(/,/g,"");
            if(isNaN(str)) str=0;
            var amount = new String(str);
            amount = amount.split("").reverse();
   
            var output = "";
            for ( var i = 0; i <= amount.length-1; i++ ){
                output = amount[i] + output;
                if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = ',' + output;
            }
            return output;
        }


        function checkpercent(id,percent){
            if(isNaN(percent)) percent = 0;
            if(percent > 100) 
                $("#"+id).val("100%");
            else if(percent < 0)
                $("#"+id).val("0%");
            else $("#"+id).val(percent+"%");
        }
</script>