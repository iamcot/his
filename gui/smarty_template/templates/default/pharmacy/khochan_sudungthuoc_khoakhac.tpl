{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><td align="left">
			<table>
				<tr><th align="left"><font size="2" color="#5f88be">{{$LDSelect}} {{$inputby}}</th></tr>
				<tr><th align="left"><br>{{$LDMonthReport}} </th></tr>
			</table>
		</td>
		<td align="right" valign="middle">
				<table>
					<tr>
						<td>{{$monthreport}}&nbsp;</td><td>&nbsp;{{$pbSubmit}}</td>
					</tr>
				</table>
		</td>
	</tr>
	
</table>
<table border="0" bgColor="#999999" cellpadding="3" cellspacing="1" width="98%">
	<tr bgColor="#E1E1E1">
		<th rowspan="2">{{$LDSTT}}</th>
		<th rowspan="2">{{$LDMedicineName}}</th>
		<th rowspan="2">{{$LDUnit}}</th>
		<th colspan="3">{{$LDKhoaNgoai}}</th>
		<th colspan="3">{{$LDKhoaSan}}</th>
		<th colspan="3">{{$LDKhoaHSCC}}</th>
		<th colspan="3">{{$LDKhoaNoi}}</th>
		<th colspan="3">{{$LDKhoaDuoc}}</th>
		<th rowspan="2">{{$LDTong}}</th>
		<th rowspan="2">{{$LDNote}}</th>
	</tr>
	<tr bgColor="#E1E1E1">
		<td>{{$LDMonth}} {{$month_1}}</td>
		<td>{{$LDMonth}} {{$month_2}}</td>
		<td>{{$LDMonth}} {{$month_3}}</td>
		<td>{{$LDMonth}} {{$month_1}}</td>
		<td>{{$LDMonth}} {{$month_2}}</td>
		<td>{{$LDMonth}} {{$month_3}}</td>
		<td>{{$LDMonth}} {{$month_1}}</td>
		<td>{{$LDMonth}} {{$month_2}}</td>
		<td>{{$LDMonth}} {{$month_3}}</td>
		<td>{{$LDMonth}} {{$month_1}}</td>
		<td>{{$LDMonth}} {{$month_2}}</td>
		<td>{{$LDMonth}} {{$month_3}}</td>
		<td>{{$LDMonth}} {{$month_1}}</td>
		<td>{{$LDMonth}} {{$month_2}}</td>
		<td>{{$LDMonth}} {{$month_3}}</td>		
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