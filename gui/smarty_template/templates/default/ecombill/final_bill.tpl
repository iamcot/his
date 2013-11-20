<center>
<table border="0" width="80%" bordercolor="#000000">
	<tr>
		<td colspan=5 valign="top" height=30 bordercolor="#FFFFFF"><b>{{$LDGeneralInfo}}</b></td>
	</tr>
	<tr bgColor="#eeeeee">
		<td valign=top width="25%">{{$LDPatientName}}</td>
		<td valign=top width="25%">{{$LDPatientNameData}}</td>
		<td valign=top width="5%">&nbsp;</td>
		<td valign=top width="15%">{{$LDReceiptNumber}}</td>
		<td valign=top width="30%" align="right"><strong>{{$LDReceiptNumberData}}</strong></td>
	</tr>
	<tr>
		<td valign=top width="25%">{{$LDPatientAddress}}</td>
		<td valign=top width="25%">{{$LDPatientAddressData}}</td>
		<td valign=top width="5%">&nbsp;</td>
		<td valign=top width="15%">{{$LDPaymentDate}}</td>
		<td valign=top width="30%" align="right"><strong>{{$LDPaymentDateData}}</strong></td>
	</tr>
	<tr bgColor="#eeeeee">
		<td valign=top width="25%">{{$LDPatientType}}</td>
		<td valign=top width="25%">{{$LDPatientTypeData}}</td>
		<td valign=top width="5%">&nbsp;</td>
		<td valign=top width="15%">{{$LDInsurance}}</td>
		<td valign=top width="30%">{{$Insurance}}</td>
	</tr>
	<tr>
		<td valign=top width="25%">{{$LDDateofBirth}}</td>
		<td valign=top width="25%">{{$LDDateofBirthData}}</td>
		<td valign=top width="5%">&nbsp;</td>
		<td valign=top width="15%">{{$LDInsurance_start}}</td>
		<td valign=top width="30%">{{$Insurance_start}}</td>
	</tr>
	<tr bgColor="#eeeeee">
		<td valign=top width="25%">{{$LDSex}}</td>
		<td valign=top width="25%">{{$LDSexData}}</td>
		<td valign=top width="5%">&nbsp;</td>
		<td valign=top width="15%">{{$LDInsurance_exp}}</td>
		<td valign=top width="30%">{{$Insurance_exp}}</td>
	</tr>
	<tr>
		<td valign=top width="25%">{{$LDPatientNumber}}</td>
		<td valign=top width="25%">{{$LDPatientNumberData}}</td>
		<td valign=top width="5%">&nbsp;</td>
		<td valign=top width="15%">{{$LDMaKCB}}</td>
		<td valign=top width="30%">{{$makcb}}</td>
	</tr>
	<tr bgColor="#eeeeee">
		<td valign=top width="25%">{{$LDDateofAdmission}}</td>
		<td valign=top width="25%">{{$LDDateofAdmissionData}}</td>
		<td valign=top width="5%">&nbsp;</td>
		<td valign=top width="15%">&nbsp;</td>
		<td valign=top width="30%">&nbsp;</td>
	</tr>
	
<!-- List past encounter bill (dianogsis + payment) -->	
	{{$PastEnc}}	
<!-- List past encounter bill (dianogsis + payment) -end -->		
	
	
<!-- Show Diagnostic for Final Bill -->	
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">
			<p><b>{{$LDAllBillInformation}}</b></p>
			</td>
		</tr>		
		{{$LDItemAllBill}}
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">
			<p><b>{{$LDAllPaymentInfo}}</b></p>
			</td>
		</tr>
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" bordercolor="#FFFFFF" bgColor="#eeeeee">{{$LDPaymentId}}&nbsp;</td>
		</tr>
		{{$LDListAllPayment}}
		<tr>
		<tr>
			<td colspan="2" bordercolor="#FFFFFF" align="right">{{$LDTotalPayment}}</td>
			<td bordercolor="#FFFFFF">&nbsp;</td>
			<td colspan="2" bordercolor="#FFFFFF"><b>{{$LDTotalPaymentValue}}</b></td>
		</tr>
<!-- Show Diagnostic for Final Bill -end -->
	
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">
			<p><b>{{$LDPaymentInformation}}</b></p>
		</td>
	</tr>
	
{{$sFormTag}}
<!--  Final bill -->
{{if $LDFinalBillShow}}
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td colspan="3" align="left">
			{{$LDTotal}}
		</td>
		<td align="right">
			<b>{{$totalbill}}</b>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_total_Reader}}</i></td>
	</tr>

	<tr>
		<td colspan="3" align="left">
			{{$LDDiscountonTotalAmount}}
		</td>
		<td align="right">
			{{$discount}}
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td colspan="3" align="left">
			{{$LDAmountAfterDiscount}}
		</td>
		<td align="right">
			{{$totpayment}}
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_payment_Reader}}</i></td>
	</tr>
{{/if}}

{{if $LDConfirmBill}}
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td colspan="3" align="left">
			{{$LDTotal}}
		</td>
		<td align="right">
			{{$totalbill}}
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_total_Reader}}</i></td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			{{$LDDiscountonTotalAmount}}
		</td>
		<td align="right">
			{{$discount}}
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td colspan="3" align="left">
			{{$LDAmountAfterDiscount}}
		</td>
		<td align="right">
			<b>{{$totpayment}}</b>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_afterdisc_Reader}}</i></td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			{{$LDAmountPreviouslyReceived}}
		</td>
		<td align="right">
			<b>{{$paidamt}}</b>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_amount_Reader}}</i></td>
	</tr>
	<tr bgcolor="#eeeeee">
		<td colspan="3" align="left">
			{{$LDAmountDue}}
		</td>
		<td align="right">
			<b>{{$amtdue}}</b>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_due_Reader}}</i></td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			{{$LDCurrentPaidAmount}}
		</td>
		<td align="right">
			<input type="text" name="currentamt" size="10">
		</td>
		<td>&nbsp;</td>
	</tr>
{{/if}}

</table>
<p>
{{$sHiddenInputs}}
</form>
<p>
<p>
{{$pbSubmit}} {{$pbCancel}}
<p>
</center>