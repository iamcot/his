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
		<th>{{$LDSTT}}</th>
		<th>{{$LDMedicineName}}</th>
		<th>{{$LDUnit}}</th>
		<th>{{$LDCost}}</th>
		<th colspan="2">{{$LDTonDau}}</th>
		<th colspan="2">{{$LDNhap}}</th>
		<th colspan="2">{{$LDXuat}}</th>
		<th colspan="2">{{$LDHong}}</th>
		<th colspan="2">{{$LDTonCuoi}}</th>
		<th>{{$LDNote}}</th>
	</tr>
	<tr bgColor="#E1E1E1">
		<td></td><td></td><td></td><td></td>
		<td align="center">SL</td>
		<td align="center">TT</td>
		<td align="center">SL</td>
		<td align="center">TT</td>
		<td align="center">SL</td>
		<td align="center">TT</td>
		<td align="center">SL</td>
		<td align="center">TT</td>
		<td align="center">SL</td>
		<td align="center">TT</td>	
		<td align="center"></td>		
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