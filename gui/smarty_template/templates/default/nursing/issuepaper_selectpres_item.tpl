{{if $roomflag}}
<tr bgcolor="#ffffff">
	<td colspan ="7" height="7"><b>{{$checkgroup}} {{$roomName}}<b></td>
</tr>
{{/if}}
<tr bgcolor="#eeeeee">
	{{if $itemPresID}}
	<td align="right" height="7">
		<input name="{{$roomGroup}}" id="{{$itemID}}" value="ON" type="checkbox">
	</td>
	{{/if}}
	<td align="center" height="7">{{$itemPresID}}</td>
	<td align="center" height="7">{{$itemEncID}}</td>
	<td height="7">{{$itemEncName}}</td>
	<td align="center" height="7">{{$itemEncSex}}</td>
	<td align="center" height="7">{{$itemEncBirthday}}</td>
	<td align="center" height="7">{{$itemUseFor}}</td>
</tr>