{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="99%">
	<tr><td align="center">
		<table><tr>
			<td>{{$LDOnDate}}</td>
			<td>{{$calendartoday}}</td>
			<td rowspan="2">&nbsp;&nbsp;{{$pbSubmit}}</td>
		</tr></table>
	</td></tr>
</table>
<table border="0" bgColor="#999999" cellpadding="3" cellspacing="1" width="1500">
	<tr bgColor="#E1E1E1" align="center">
		<th rowspan="2">{{$LDSTT}}</th>				
		<th rowspan="2">{{$LDMaKham}}</th>
		<th rowspan="2">{{$LDHoTen}}</th>
		<th rowspan="2">{{$LDXetNghiem}}</th>
		<th rowspan="2">{{$LDCDHA}}</th>
		<th rowspan="2">{{$LDThuoc}}</th>
		<th rowspan="2">{{$LDMau}}</th>
		<th rowspan="2">{{$LDPhauThuat}}</th>
		<th rowspan="2">{{$LDVTYT}}</th>
		<th rowspan="2">{{$LDCongKham}}</th>
		<th rowspan="2">{{$LDCPVanChuyen}}</th>
		<th rowspan="2">{{$LDGiuong}}</th>
		<th rowspan="2">{{$LDKhac}}</th>
		<th rowspan="2">{{$LDTongCong}}</th>
		<th colspan="3">{{$LDBHYT}}</th>
		<th rowspan="2">{{$LDThanhToan}}</th>
		<th rowspan="2">{{$LDGhiChu}}</th>
		<th rowspan="2">{{$LDMaHoaDon}}</th>
		<th rowspan="2">{{$LDThoiGian}}</th>
		<th rowspan="2">{{$LDNhanVien}}</th>		
	</tr>
	<tr bgColor="#E1E1E1" align="center">
		<th>{{$LDBHYTTra}}</th>
		<th>{{$LDMaBHYT}}</th>
		<th>{{$LDMaKCB}}</th>
	</tr>
	{{$divItem}}
</table>
</center>

<p>
&nbsp;&nbsp;{{$TongTienThu}}<p>
&nbsp;{{$TongTienThuReader}}
<p>

<center>
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