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
		<td valign=top width="25%"><b>{{$LDPatientNumberData}}</b></td>
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
	
	
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5" bgcolor="#BDEDFF" bordercolor="#FFFFFF">
			<p><b>{{$LDPaymentInformation}}</b></p>
		</td>
	</tr>
<!-- payment listing section  -->
{{if $LDPaymentList}}	
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
	</tr>
	<tr>
			<td align="left" bgColor="#eeeeee">{{$LDPaymentType}}</td>
			<td align="right" bgColor="#eeeeee">{{$LDPaymentTypeData}}</td>					
			<td colspan="3"></td>
	</tr>
	<tr>
			<td align="left" bgColor="#eeeeee">{{$LDModeofPayment}}</td>
			<td align="right" bgColor="#eeeeee">{{$LDModeofPaymentData}}</td>					
			<td colspan="3"></td>
	</tr>
	<tr>
			<td align="left" bgColor="#eeeeee">{{$LDAmount}}</td>
			<td align="right" bgColor="#eeeeee">{{$LDAmountData}}</td>					
			<td colspan="3"></td>
	</tr>
{{/if}}	
{{$sFormTag}}
<!-- bills listing section  -->
{{if $LDBillList}}	
	<tr>
		<td colspan="5">
			<table border="0" width="100%" bordercolor="#000000" cellspacing="1">
				<tr>
					<th width="30%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDDescription}}</th>
					<th width="15%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDCostPerUnit}}</th>
					<th width="5%" valign="middle" align="center"  bgcolor="#CCCCCC">{{$LDUnits}}</th>
					<th width="18%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDTotalCost}}</th>
					<th width="12%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDItemDate}}</th>
					<th width="12%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDItemType}}</th>
				</tr>
				
				{{$ItemLine}}
			
			</table>	
		</td>
	</tr>
{{/if}}

<!-- payment method listing -->
{{if $LDEnterPayment}}
	<tr>
		<td colspan="5">
			{{include file="ecombill/bill_payment_header_payment.tpl"}}
		</td>
	</tr>
{{/if}}	
<!--  show selected payment -->
{{if $LDShowPayment}}
	<tr>
		<td colspan="5">
		{{if $PaymentCash}}
			{{include file="ecombill/bill_payment_header_payment_cash.tpl"}}
		{{/if}}

		{{if $PaymentCreditCard}}
			{{include file="ecombill/bill_payment_header_payment_creditcard.tpl"}}
		{{/if}}		
		
		{{if $PaymentCheck}}
			{{include file="ecombill/bill_payment_header_payment_check.tpl"}}
		{{/if}}		
		</td>
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