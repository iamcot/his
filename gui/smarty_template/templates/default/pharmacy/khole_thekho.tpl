{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><td align="left">
			<table>
				<tr align="left"><th>{{$LDname}}</th><td colspan="3">{{$name}}</td>
					<th>{{$LDBy}}</th><td>{{$inputby}}</td>
					</tr>
				<tr align="left"><th>{{$LDencoder}}</th><th>{{$encoder}}</th>
					<th>{{$LDcontent}}</th><td>{{$content}}</td>
					<th>{{$LDunit}}</th><td>{{$unit}}</td></tr>
				<tr align="left"><th colspan="4"><br>{{$monthreport}}</th></tr>
			</table>
		</td>
		<td align="right" valign="top">
				<table>
					<tr><td>{{$LDFromDate}}</td>
						<td>{{$calendarfrom}}</td>
						<td align="right" rowspan="2">&nbsp;&nbsp;{{$pbSubmit}}</td>
					</tr>
					<tr><td>{{$LDToDate}}</td>
						<td>{{$calendarto}}</td>
					</tr>
				</table>
		</td>
	</tr>
	
</table>

<table border="0" bgColor="#999999" cellpadding="3" cellspacing="1" width="95%">
	<tr bgColor="#E1E1E1">
		<th rowspan="2">{{$LDDayMonth}}</th>
		<th colspan="2">{{$LDVoucher}}</th>

		<th rowspan="2">{{$LDLotID}}</th>
		<th rowspan="2">{{$LDExpDate}}</th>
		<th rowspan="2">{{$LDExplain}}</th>
		<th rowspan="2">{{$LDFirstInventory}}</th>
		<th colspan="3">{{$LDNumberOf}}</th>
		<th rowspan="2">{{$LDNote}}</th>
	</tr>
	<tr bgColor="#E1E1E1">
		<th>{{$LDImport}}</th>
		<th>{{$LDExport}}</th>	
		<th>{{$LDImport}}</th>
		<th>{{$LDExport}}</th>
		<th>{{$LDLastInventory}}</th>		
	</tr>
	{{$divItem}}
</table>

<p>

{{$sHiddenInputs}}
<p>
<table>
	<tr>
		<td>{{$pbPrint}}&nbsp;</td>
		<td>{{$pbCancel}}</td>
	</tr>
</table>
</center>
</form>