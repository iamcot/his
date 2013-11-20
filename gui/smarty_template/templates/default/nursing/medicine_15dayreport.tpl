{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="3" color="#5f88be">{{$deptname}}</th>
		<td align="right" rowspan="3">
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
	<tr><th align="left" valign="top"><font size="2" color="#85A4CD">{{$ward}}</th></tr>
	<tr><th align="left" >{{$monthreport}}</th></tr>
</table>

<table border="0" bgColor="#999999" cellpadding="3" cellspacing="1" width="95%">
	<tr bgColor="#E1E1E1" align="center">
		<th rowspan="2">{{$LDSTT}}</th>				
		<th rowspan="2">{{$LDPresName}}</th>
		<th rowspan="2">{{$LDUnit}}</th>
		<th rowspan="2">{{$LDStandard}}</th>
		<th colspan="16">{{$LDDay}}</th>
		<th rowspan="2">{{$LDTotalNumber}}</th>
		<th rowspan="2">{{$LDNote}}</th>
	</tr>
	{{$divDay}}
	<tr bgColor="#E1E1E1" align="center">
		<td>A</td> <td>B</td> <td>C</td> <td>D</td>
		<td></td> <td></td> <td></td> <td></td>
		<td></td> <td></td> <td></td> <td></td>
		<td></td> <td></td> <td></td> <td></td>
		<td></td> <td></td> <td></td> <td></td>
		<td>E</td> <td>G</td>
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