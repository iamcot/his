{{if $typeflag}}
<table id="{{$typeName}}" cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
<tr bgcolor="#FFFF2A">
	<td colspan ="5" height="9" align="center"><b>{{$typeName}}</b></td>
</tr>
{{/if}}
{{if $groupflag}}
<table id="{{$groupName}}"  cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
<tr  bgcolor="#ffffff">
	<td colspan ="5" height="7"><b>{{$groupName}}<b></td>
</tr>
{{/if}}

<tr  bgColor="#eeeeee">

	<input name="update{{$TP_code}}" value="{{$TP_description}}#{{$TP_unit_cost}}#{{$TP_discount_max}}" type="hidden">

	<td height="7" width="846" align="center">{{$TP_code}}</td>
	<td align="center" height="7" width="1014">
		<input type="text" name="{{$itemnmcnt}}" size="50" value="{{$TP_description}}">
	</td>
	<td height="7" width="623" align="center">
		<input type="text" name="{{$itemcscnt}}" size="10" value="{{$TP_unit_cost}}">
	</td>
	<td height="7" width="484" align="center" valign="middle">
		<input type="text" name="{{$itemdccnt}}" size="3" value="{{$TP_discount_max}}">
	</td>
</tr>
