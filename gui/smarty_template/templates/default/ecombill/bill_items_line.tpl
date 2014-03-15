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
<tr bgcolor="#eeeeee">
	{{if $itemID}}
	<td align="center" height="7" width="45">
		<input name="{{$itemName}}" id="{{$itemID}}" value="ON" type="checkbox">
	</td>
	{{/if}}
	<td height="7" width="65%">{{$itemName}}</td>
	<td align="center" height="7" width="10%">{{$itemCode}}</td>
	<td align="center" height="7" width="15%">{{$itemPrice}}</td>
	<td align="center" height="7">{{$quantity}}</td>
</tr>
