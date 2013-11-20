{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><td colspan="2" class="prompt"><b>{{$subtitle}}</b></td></tr>
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
		<th width="3%">{{$LDSTT}}</th>
		<th width="30%">{{$LDMedicineName}}</th>
		<th width="7%">{{$LDUnit}}</th>
		<th width="15%">{{$LDSoLuong}}</th>
		<th width="15%">{{$LDDonGia}}</th>
		<th width="15%">{{$LDThanhTien}}</th>
		<th>{{$LDNote}}</th>
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