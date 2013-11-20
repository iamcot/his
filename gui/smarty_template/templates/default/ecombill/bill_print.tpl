<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="CONTENT-TYPE" content="text/html; charset=utf-8">
<title>{{$title}}</title>
</head>
<body onload="window.print();">
<table border="0" width="595.28" bordercolor="#000000">
	<tr>
		<td colspan=5 bgColor="#eeeeee" height=30 align="center"><font face="Arial" size="5" color="#000000">{{$LDFinalBill}}</font></td>
	</tr>
	<tr>
		<td colspan=5 valign="bottom" height=40 bordercolor="#FFFFFF"><b>{{$LDGeneralInfo}}</b></td>
	</tr>
	<tr bgColor="#eeeeee">
		<td valign=top width="20%">{{$LDPatientName}}</td>
		<td valign=top width="30%" align="right">{{$LDPatientNameData}}</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="10%">{{$LDReceiptNumber}}</td>
		<td valign=top width="30%" align="right"><strong>{{$LDReceiptNumberData}}</strong></td>
	</tr>
	<tr>
		<td valign=top width="20%">{{$LDPatientAddress}}</td>
		<td valign=top width="30%" align="right">{{$LDPatientAddressData}}</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="10%">{{$LDPaymentDate}}</td>
		<td valign=top width="30%" align="right"><strong>{{$LDPaymentDateData}}</strong></td>
	</tr>
	<tr>
		<td valign=top width="20%">{{$LDDateofBirth}}</td>
		<td valign=top width="30%" align="right">{{$LDDateofBirthData}}</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="30%">&nbsp;</td>
	</tr>
	<tr bgColor="#eeeeee">
		<td valign=top width="20%">{{$LDSex}}</td>
		<td valign=top width="30%" align="right">{{$LDSexData}}</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="30%">&nbsp;</td>
	</tr>
	<tr>
		<td valign=top width="20%">{{$LDPatientNumber}}</td>
		<td valign=top width="30%" align="right">{{$LDPatientNumberData}}</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="30%">&nbsp;</td>
	</tr>
	<tr bgColor="#eeeeee">
		<td valign=top width="20%">{{$LDDateofAdmission}}</td>
		<td valign=top width="30%" align="right">{{$LDDateofAdmissionData}}</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="10%">&nbsp;</td>
		<td valign=top width="30%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">
			<p><b>{{$LDPaymentInformation}}</b></p>
		</td>
	</tr>
<!-- payment listing section  -->
{{if $LDPaymentList}}	
	<tr>
		<td colspan="5" bordercolor="#FFFFFF">&nbsp;</td>
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
<!-- bills listing section  -->
{{if $LDBillList}}	
	<tr>
		<td colspan="5">
			<table border="0" width="100%" bordercolor="#000000" cellspacing="1">
				<tr>
					<th width="30%" valign="middle" align="left" bgcolor="#CCCCCC">{{$LDDescription}}</th>
					<th width="15%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDCostPerUnit}}</th>
					<th width="3%" valign="middle" align="center"  bgcolor="#CCCCCC">{{$LDUnits}}</th>
					<th width="20%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDTotalCost}}</th>
					<th width="25%" valign="middle" align="center" bgcolor="#CCCCCC">{{$LDItemType}}</th>
				</tr>
				
				<!--{{include file="ecombillbill_items_line.tpl"}}-->
				{{$ItemLine}}
			
				<tr><td colspan="5" bgcolor="#cccccc"></td></tr>
				<tr>
					<td colspan="4"><strong>{{$LDTotal}}</strong></td>
					<td><strong>{{$LDTotalData}}</strong></td>				
				</tr>
				<tr>
					<td colspan="4"><strong>{{$LDOutstandingAmount}}</strong></td>
					<td><strong>{{$LDOutstandingAmountData}}</strong></td>				
				</tr>
				<tr>
					<td colspan="4"><strong>{{$LDAmountDue}}</strong></td>
					<td><strong>{{$LDAmountDueData}}</strong></td>				
				</tr>
				<tr>
				{{$sHiddenInputs}}
				</tr>
			</table>	
		</td>
	</tr>
{{/if}}



</table>
</body>
</html>