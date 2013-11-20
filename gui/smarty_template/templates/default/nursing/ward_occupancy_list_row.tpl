{{* ward_occupancy_list_row.tpl 2004-06-15 Elpidio Latorilla *}}
{{* One row for each occupant or room/bed *}}
{{* This template is used by /modules/nursing/nursing_station.php to populate the ward_occupancy_list.tpl template *}}

 {{if $bToggleRowClass}}
	<tr class="wardlistrow1">
 {{else}}
	<tr class="wardlistrow2">
 {{/if}}
		<td>{{$sMiniColorBars}}</td>
		<td>&nbsp;{{$sRoom}}&nbsp;</td><td>&nbsp;{{$sRoomname}}&nbsp;</td>
		<td>&nbsp;{{$sBed}} &nbsp; {{$sBedIcon}}&nbsp;</td>
		<td>&nbsp;{{$sTitle}} {{$sFamilyName}}{{$cComma}} {{$sName}}&nbsp;</td>
		<td>&nbsp;{{$sBirthDate}}&nbsp;</td>
		<td>&nbsp;{{$sPatNr}}&nbsp;</td>
		<td>&nbsp;{{$sInsuranceType}}&nbsp;</td>
		<td>&nbsp;{{$sYellowPaper}} {{$sTarget}} {{$sChartFolderIcon}}
		{{$sAdmitDataIcon}} {{$sTransferIcon}} {{$sDischargeIcon}}&nbsp;
		</td>
		</tr>
		<tr>
		<td colspan="9" class="thinrow_vspacer">{{$sOnePixel}}</td>
	</tr>
