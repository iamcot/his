{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="2" color="#5f88be">{{$LDSumAllDept}}</th>
		<td align="right" rowspan="2">
				<table>
					<tr>
						<td>{{$monthreport}}&nbsp;</td><td>&nbsp;{{$pbSubmit}}</td>
					</tr>
				</table>
		</td>
	</tr>
	<tr><th align="left">{{$LDBy}}: {{$inputby}}</th></tr>
	<tr><td align="right"  colspan="2"><i>{{$LDUnitVnd}}</i></td></tr>
</table>

<table border="0" bgColor="#999999" cellpadding="3" cellspacing="1" width="95%">
	<tr bgColor="#E1E1E1">
		<th rowspan="2">{{$LDSTT}}</th>	
		<th rowspan="2">{{$LDMedicineID}}</th>
		<th rowspan="2">{{$LDPresName}}</th>
		<th rowspan="2">{{$LDUnit}}</th>
		<th rowspan="2">{{$LDCost}}</th>
		<th colspan="2">{{$Inpatient}}</th>
		<th colspan="2">{{$Outpatient}}</th>
		<th colspan="2">{{$LDOther}}</th>
		<th colspan="2">{{$LDDestroy}}</th>
		<th colspan="2">{{$LDTotalNumber}}</th>
	</tr>
	<tr bgColor="#E1E1E1">
		<th>{{$LDNumberOf}}</th>
		<th>{{$LDMoney}}</th>
		<th>{{$LDNumberOf}}</th>
		<th>{{$LDMoney}}</th>
		<th>{{$LDNumberOf}}</th>
		<th>{{$LDMoney}}</th>
		<th>{{$LDNumberOf}}</th>
		<th>{{$LDMoney}}</th>
		<th>{{$LDNumberOf}}</th>
		<th>{{$LDMoney}}</th>
	</tr>
	<tr bgColor="#E1E1E1" align="center">
		<td>1</td><td>2</td><td>3</td><td>4</td><td>5</td>
		<td>6</td><td>7</td><td>8</td><td>9</td><td>10</td>
		<td>11</td><td>12</td><td>13</td><td>14</td><td>15</td>
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