				<tr bgColor="#eeeeee">
					<td bordercolor="#FFFFFF">
					<p>{{$LDBillingId}}: {{$billno}}</p>
					</td>
					<td colspan="4" bordercolor="#FFFFFF">
					<p>{{$date}}</p>
					</td>					
				</tr>		
				<tr>
					<td colspan="5">
						<table width="100%">
							{{$results}}
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3" bordercolor="#FFFFFF" align="right">
						{{$LDTotal}}: <b>{{$amount}}</b>
					</td>
					<td align="right">{{$LDBHYT}}: <b>{{$discountbill}}</b></td>
					<td bordercolor="#FFFFFF" align="center">
						{{$LDOutstanding}}: <b>{{$outstanding}}</b>
					</td>					
				</tr>