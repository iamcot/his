		<tr>
			<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
		</tr>
		<tr bgcolor="#E6A9EC">
			<td colspan="5" bordercolor="#FFFFFF"><b>{{$LDEncouterNumber}}: {{$encounterId}}</b></td>
		</tr>
				
		<!-- List All Bill of Old Encounter-->
		<tr bgcolor="#FCDFFF">
			<td colspan="5" bordercolor="#FFFFFF"><p><b>{{$LDAllBillInfo}}</b></p></td>
		</tr>		
		{{$ListAllBill}}

		<!-- List All Payment of Old Encounter-->		
		<tr bgcolor="#FCDFFF">
			<td colspan="5" bordercolor="#FFFFFF"><p><b>{{$LDAllPaymentInfo}}</b></p></td>
		</tr>
		{{$ListAllPayment}}
		<tr bgcolor="#FCDFFF">
			<td bordercolor="#FFFFFF"><b>{{$LDTongket}}</b></td>
			<td colspan="4" bordercolor="#FFFFFF"><b>{{$LDTong}}: {{$oldenc_each_totalbill}}
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$LDBHYT}}: {{$oldenc_each_discount}}
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$LDDaThanhToanVaTamUng}}: {{$oldenc_each_paid}}
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$LDConlai}}: {{$oldenc_each_remain}}</b></td>
		</tr>		
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
		</tr>