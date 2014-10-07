{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><td align="left">
			<table>
				<tr><th align="left"><font size="2" color="#5f88be">{{$LDReportMedicine}} {{$inputby}}</th></tr>
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
<table border="0" bgColor="#999999" cellpadding="3" cellspacing="1" width="1500">
	<tr bgColor="#E1E1E1">
		<th rowspan="2">{{$LDSTT}}</th>
		<th rowspan="2">{{$LDMedicineName}}</th>
		<th rowspan="2">{{$LDUnit}}</th>
		<th rowspan="2">{{$LDLotID}}</th>
		<th rowspan="2">{{$LDExpDate}}</th>
		<th colspan="3">{{$LDTonDau}}</th>
		<th colspan="3">{{$LDNhap}}</th>
		<th colspan="3">{{$LDXuat}}</th>
		<th colspan="3">{{$LDTonCuoi}}</th>
		<th rowspan="2">{{$LDNote}}</th>
	</tr>
	<tr bgColor="#E1E1E1">	
		<th>{{$LDNumberOf}}</th>		
		<th>{{$LDPrice}}</th>
		<th>{{$LDTotalCost}}</th>
		<th>{{$LDNumberOf}}</th>
		<th>{{$LDGiaNhap}}</th>
		<th>{{$LDTotalCost}}</th>
		<th>{{$LDNumberOf}}</th>
		<th>{{$LDGiaXuat}}</th>
		<th>{{$LDTotalCost}}</th>
		<th>{{$LDNumberOf}}</th>	
		<th>{{$LDGiaTonCuoi}}</th>	
		<th>{{$LDTotalCost}}</th>		
	</tr>
	{{$divItem}}
</table>

<p>

{{$sHiddenInputs}}
<p>
<table>
	<tr>
		<td>{{$pbSave}}&nbsp;</td>
		<td>{{$pbPrint}}&nbsp;</td>
        <td>{{$pbExcel}}&nbsp;</td>
		<td>{{$pbCancel}}</td>
	</tr>
</table>
</center>
</form>