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
<!-- Show Diagnostic for Final Bill -->	
{{if $LDFinalBillShow||$LDConfirmBill}}
		<!-- <tr>
			<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">
			<p><b>{{$LDDianosticsInformation}}</b></p>
			</td>
		</tr>
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" bordercolor="#FFFFFF" bgColor="#eeeeee">{{$LDReportId}}&nbsp;</td>
		</tr>
		{{$LDItemDiag}}  -->
		
		<!-- List past encounter bill (dianogsis + payment) -->	
			{{$PastEnc}}	
		<!-- List past encounter bill (dianogsis + payment) -end -->
		
		<tr>
			<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" bgcolor="#BDEDFF">
			<p><b>{{$LDEncouterNumberNow}}: {{$LDPatientNumberData}}</b></p>
			</td>
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
			<td colspan="5" bordercolor="#FFFFFF" bgColor="#eeeeee">{{$LDPaymentId}}&nbsp;</td>
		</tr>
		{{$LDListAllPayment}}
		<tr>
		<tr>
			<td colspan="2" bordercolor="#FFFFFF" align="right">{{$LDTotalPayment}}</td>
			<td bordercolor="#FFFFFF">&nbsp;</td>
			<td colspan="2" bordercolor="#FFFFFF"><b>{{$LDTotalPaymentValue}}</b></td>
		</tr>
		<tr><td colspan="5">&nbsp;</td></tr>
		<tr>
			<td bordercolor="#FFFFFF" valign="top"><b>{{$LDTongket}}</b></td>
			<td colspan="2" bordercolor="#FFFFFF"><b>{{$LDTong}}: {{$totalbill}}<p>
			{{$LDBHYT}}: {{$alldiscountbill}}</b></td>
			<td colspan="2" bordercolor="#FFFFFF"><b>{{$LDDaThanhToanVaTamUng}}: {{$totpayment}}<p>
			{{$LDConlai}}: {{$totalremain}}</b></td>
		</tr>	
{{/if}}	
	
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
					<th width="12%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDItemCheck}}</th>
				</tr>
				
				{{$ItemLine}}
			
				<tr><td colspan="6" bgcolor="#cccccc"></td></tr>
				<tr>
					<td colspan="3"><strong>{{$LDTotal}}</strong></td>
					<td align="right"><strong>{{$LDTotalBillAmountData}}</strong></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="right"><i>{{$LDConvertMoney}}:</i></td>
					<td colspan="4"><i>{{$money_total_Reader}}</i></td>
				</tr>
				<tr>
					<td colspan="3"><strong>{{$LDDiscountonTotalAmount}}</strong></td>
					<td align="right"><strong>{{$discount}}</strong></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="right"><i>{{$LDConvertMoney}}:</i></td>
					<td colspan="4"><i>{{$money_discount_Reader}}</i></td>
				</tr>

				{{if $LDCurrentBill}}
					<tr>
						<td colspan="3"><strong>{{$LDOutstandingAmount}}</strong></td>		
						<td align="right">{{$outstd}} </td>
						<td>&nbsp;</td>
					</tr>
				{{/if}}	
				{{if $LDOldBill}}
					<tr>
						<td colspan="3"><strong>{{$LDOutstandingAmount}}</strong></td>	
						<td align="right"><strong>{{$outstd}}</strong></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right"><i>{{$LDConvertMoney}}:</i></td>
						<td colspan="4"><i>{{$money_outstd_Reader}}</i></td>
					</tr>
					<tr>
						<td colspan="3"><strong>{{$LDAmountDue}}</strong></td>
						<td align="right"><strong>{{$LDAmountDueData}}</strong></td>	
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right"><i>{{$LDConvertMoney}}:</i></td>
						<td colspan="4"><i>{{$money_due_Reader}}</i></td>
					</tr>
				{{/if}}

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
<!--  Final bill -->
{{if $LDFinalBillShow}}
	<tr bgcolor="#eeeeee">
		<td colspan="3" align="left">
			{{$LDTotal}}
		</td>
		<td align="right">
			<b>{{$last_totalbill}}</b>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_total_Reader}}</i></td>
	</tr>
	
{{/if}}
{{if $LDConfirmBill}}
	<tr bgcolor="#eeeeee">
		<td colspan="3" align="left">
			{{$LDTotal}}
		</td>
		<td align="right">
			<b>{{$last_totalbill}}</b>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><i>{{$LDConvertMoney}}:</i></td>
		<td colspan="4"><i>&nbsp;{{$money_total_Reader}}</i></td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			{{$LDCurrentPaidAmount}}
		</td>
		<td align="right">
			{{$currentamt}}
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