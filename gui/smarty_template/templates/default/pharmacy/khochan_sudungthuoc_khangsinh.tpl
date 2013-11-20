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
		<th width="3%">{{$LDTTHoatChat}}</th>
		<th>{{$LDTenHoatChat}}</th>
		<th>{{$LDMaATC}}</th>
		<th width="3%">{{$LDTTBietDuoc}}</th>
		<th>{{$LDTenBietDuoc}}</th>
		<th width="8%">{{$LDNuocsx}}</th>
		<th width="8%">{{$LDNongDoHamLuong}}</th>
		<th>{{$LDUnit}}</th>
		<th>{{$LDDuongDung}}</th>
		<th>{{$LDSoLuong}}</th>
		<th>{{$LDDonGia}}</th>
		<th>{{$LDThanhTien}}</th>
	</tr>
	<tr bgColor="#E1E1E1">
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5</th>
		<th>6</th>
		<th>7</th>
		<th>8</th>
		<th>9</th>
		<th>10</th>
		<th>11</th>
		<th>12</th>		
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