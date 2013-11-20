{{* property_create_form.tpl  Form template for creating new property 2012-02-01 Nguyen Thanh Tung *}}
{{* Note: the input elements are written in raw form here to give you the chance to redimension them. *}}
{{* Note: In redimensioning the input elements, be very careful not to change their names nor value tags. *}}
{{* Note: Never change the "maxlength" value *}}

<table style="width:70%;float:left;">
	<tr>
		<td class="adm_item" style="width:15%; padding: 5px 5px 5px 5px;">{{$LDPropFormalName}}</td>
		<td class="adm_input" style="width:40%;background-color:#dedede; padding: 5px 5px 5px 5px;">{{$propformalname}}</td>
		<td rowspan=8 align="center" class="photo_id" > {{$propimage}} </td>
	</tr>
        
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropType}}</td>
		<td class="adm_input" style="background-color:#efefef;padding: 5px;" >{{$proptype}}</td>
	</tr>
        
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropModel}}</td>
		<td class="adm_input" style="background-color:#dedede;padding: 5px;">{{$propmodel}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropSerieNr}}</td>
		<td class="adm_input" style="background-color:#efefef; padding: 5px 5px 5px 5px;">{{$propserie}}</td>
	</tr>       
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropUnit}}</td>
		<td class="adm_input" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$propunit}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropPrice}}</td>
		<td class="adm_input" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$propprice}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropPower}}</td>
		<td class="adm_input" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$proppower}}</td>
	</tr>
        <tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropvolta}}</td>
		<td class="adm_input" style="background-color:#efefef;padding: 5px;" >{{$propvolta}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropSource}}</td>
		<td class="adm_input" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$propsource}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropFunction}}</td>
		<td class="adm_input" colspan="2" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$profunction}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropDescription}}</td>
		<td class="adm_input" colspan="2" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$prodescription}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropNote}}</td>
		<td class="adm_input" colspan="2" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$propnote}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropMaker}}</td>
		<td class="adm_input" colspan="2" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$propmaker}}</td>
	</tr>
        <tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropCountry}}</td>
		<td class="adm_input" colspan="2" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$propcountry}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropVendor}}</td>
		<td class="adm_input" colspan="2" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$propvendor}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropProductionYear}}</td>
		<td class="adm_input" colspan="2" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$productionyear}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropImDate}}</td>
		<td class="adm_input" colspan="2" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$importdate}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropImStatus}}</td>
		<td class="adm_input" colspan="2" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$importstatus}} ({{$usepercent}})</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropStartUseDate}}</td>
		<td class="adm_input" colspan="2" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$propusedate}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropWarranty}}</td>
		<td class="adm_input" colspan="2" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$propwarranty}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDPropMannual}}</td>
		<td class="adm_input" colspan="2" style="background-color:#dedede ;padding: 5px 5px 5px 5px;">{{$propmannual}}</td>
	</tr>
	<tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDWorkOrStop}}?</td>
		<td class="adm_input" colspan="2" style="background-color:#efefef ;padding: 5px 5px 5px 5px;">{{$propstatus}}</td>
	</tr>
         <tr>
		<td class="adm_item" style="padding: 5px 5px 5px 5px;">{{$LDDeptMana}}</td>
		<td class="adm_input" style="background-color:#dedede; padding: 5px 5px 5px 5px;">{{$dept_mana_name}}</td>
	</tr>
	<tr>
		<td>{{$sCancel}}</td>
		<td colspan="2"></td>
	</tr>
</table>
{{$imgLeftFunctionList}}

<table style="width:30%;float:left;" border="0" cellpadding="2" cellspacing="1" bgcolor="#999999">
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconSearch}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDSearchURL}}">{{$LDSearchLink}}</a></td>
	</tr>
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconModify}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDModifyURL}}">{{$LDModifyData}}</a></td>
	</tr>
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconHistory}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDHistoryURL}}">{{$LDUseHistory}}</a></td>
	</tr>
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconHistory}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDRepairHistoryURL}}">{{$LDRepairHistory}}</a></td>
	</tr>
	{{if $propsatustoshowsubmenu ne '2'}}
	<tr>
		
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconTransmite}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDTransmitingURL}}">{{$LDTransmiting}}</a></td>
		
	</tr>
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconReturn}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDReturnURL}}">{{$LDReturn}}</a></td>
	</tr>
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconLiquidation}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDLiquidationURL}}">{{$LDLiquidation}}</a></td>
	</tr>
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconRepair}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDRepairURL}}">{{$LDRepair}}</a></td>
	</tr>
	{{/if}}
	<tr>
		<td class="adm_input" style="text-align:center; width:10%;padding: 5px 5px 5px 5px;">{{$LDIconCreateNew}}</td>
		<td class="adm_input" style="padding: 5px 5px 5px 5px;"><a href="{{$LDCreateURL}}">{{$LDCreateNew}}</a></td>
	</tr>

</table>
<span>{{$notif}}</span>
{{$sSubfunctionArea}}
<script>
	jQuery(function($){
		$("#f-calendar-field-1").mask("99/99/9999");	
	});
</script>