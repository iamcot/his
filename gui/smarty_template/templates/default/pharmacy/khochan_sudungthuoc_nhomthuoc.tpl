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
		<th>{{$LDMedicineID}}</th>
		<th>{{$LDMedicineName}}</th>
		<th>{{$LDUnit}}</th>
		<th>{{$LDSoLuong}}</th>
		<th>{{$LDDonGia}}</th>
		<th>{{$LDThanhTien}}</th>
		<th>{{$LDNote}}</th>
	</tr>
	<tr bgColor="#E1E1E1"><th colspan="8">{{$LDCorti}}</th></tr>
	<tr bgColor="#FFFFFF">
		<td colspan="8" align="center">{{$LDNoi}}</td>
	</tr>
	{{$divItem_corti_noi}}
	<tr bgColor="#FFFFFF">
		<td colspan="8" align="center">{{$LDNgoai}}</td>
	</tr>
	{{$divItem_corti_ngoai}}
	
	<tr bgColor="#E1E1E1"><th colspan="8">{{$LDDichTruyen}}</th></tr>
	<tr bgColor="#FFFFFF">
		<td colspan="8" align="center">{{$LDNoi}}</td>
	</tr>
	{{$divItem_dichtruyen_noi}}
	<tr bgColor="#FFFFFF">
		<td colspan="8" align="center">{{$LDNgoai}}</td>
	</tr>
	{{$divItem_dichtruyen_ngoai}}

	<tr bgColor="#E1E1E1"><th colspan="8">{{$LDHoaChat}}</th></tr>
	<tr bgColor="#FFFFFF">
		<td colspan="8" align="center">{{$LDNoi}}</td>
	</tr>
	{{$divItem_hoachat_noi}}
	<tr bgColor="#FFFFFF">
		<td colspan="8" align="center">{{$LDNgoai}}</td>
	</tr>
	{{$divItem_hoachat_ngoai}}	
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