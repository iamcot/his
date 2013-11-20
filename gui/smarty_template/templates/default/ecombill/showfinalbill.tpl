<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="CONTENT-TYPE" content="text/html; charset=utf-8">
<title>{{$title}}</title>
<style type="text/css">
table {
	font-family: verdana, arial, tahoma;
	font-size: 12px;
	font-weight: normal;
	color: #000000;
	border-collapse: separate;
	border-spacing: 2px;
}
td {
	height: 20px;
}
</style>
</head>
<body bgcolor="#FFFFFF" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
<p>
	<div align="center">
		<center>
			<font face="Arial" size="4" color="#000000"><b>{{$LDTitleFinalBill}}</b></font>
			<table border="0" width="95%">
				<tr>
					<td colspan="2" bgcolor=#dddddd>
						<b>{{$LDWard}}</b>
					</td>
				</tr>				
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0">
							<tr>
								<td>{{$LDPatientNumber}}</td>
								<td>{{$LDPatientType}}</td>
								<td>{{$LDBillNo}}</td>
								<td>{{$LDBillDate}}</td>
							</tr>
							<tr bgcolor=#eeeeee>
								<td>{{$LDPatientName}}</td>
								<td>{{$LDDateofBirth}}</td>
								<td>{{$LDSex}}</td>
								<td>{{$LDPatientAddress}}</td>
							</tr>
							<tr>
								<td>{{$LDRoom}} &nbsp; &nbsp; &nbsp; &nbsp; {{$LDBedNr}}</td>
								<td>{{$LDDateofAdmission}}</td>
								<td>{{$LDDateOfTransfer}}</td>
								<td>{{$LDPaymentTypePayment}}</td>
							</tr>
							<tr bgcolor=#eeeeee>
								<td>{{$LDInsurranceNr}}</td>
								<td>{{$LDInsurranceDate}}</td>
								<td colspan=2>{{$LDInsurrancePlace}}</td>
							</tr>
							<tr>
								<td colspan=3>{{$LDDiagnosis}}</td>
								<td>{{$LDSumOfDate}}</td>
							</tr>							
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0">
								<!-- List past encounter bill (dianogsis + payment) -->	
									{{$PastEnc}}	
								<!-- List past encounter bill (dianogsis + payment) -end -->
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0">
							<tr>
								<td colspan="7"> &nbsp; <td>
							</tr>
							<tr>
								<td colspan="7" bgcolor="#BDEDFF"><b>{{$LDEncouterNumberNow}}: {{$LDPatientNumberData}}</b></td>
							</tr>								
							<tr align="center" bgcolor=#dddddd>
								<td><b>{{$LDNr}}</b></td>
								<td><b>{{$LDPrescriptionName}}</b></td>
								<td><b>{{$LDUnit}}</b></td>
								<td><b>{{$LDDate}}</b></td>
								<td><b>{{$LDSumUnit}}</b></td>
								<td><b>{{$LDEnterPriceUnit}}</b></td>
								<td width="17%"><b>{{$LDSumCost}}</b></td>
							</tr>
								{{$ItemPres}}
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellpadding="0">
							<tr align="center" bgcolor=#dddddd>
								<td width="40%"><b>{{$LDContent}}</b></td>
								<td><b>{{$LDDate}}</b></td>
								<td><b>{{$LDNumberOf}}</b></td>
								<td><b>{{$LDEnterPriceUnit}}</b></td>
								<td width="17%"><b>{{$LDSumCost}}</b></td>
							</tr>
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDDepot}}</b></td>
							</tr>
								{{$ItemDepot}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDSurgery}}</b></td>
							</tr>
								{{$ItemSurgery}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDLaboration}}</b></td>
							</tr>
								{{$ItemLDLabor}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDRadio}}</b></td>
							</tr>
								{{$ItemRadio}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDUltrasonic}}</b></td>
							</tr>
								{{$ItemUltrasonic}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDECG}}</b></td>
							</tr>
								{{$ItemECG}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDBlood}}</b></td>
							</tr>
								{{$ItemBlood}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDBed}}</b></td>
							</tr>
								{{$ItemBed}}
							<tr>
								<td colspan=5 bgcolor=#eeeeee><b>{{$LDKhac}}</b></td>
							</tr>
								{{$ItemKhac}}								
							<tr>
								<td colspan=5> &nbsp; <td>
							</tr>
							<tr>
								<td><b>{{$LDTotal}}</b></td>
								<td align="right"> <b>{{$LDTotalValue}}</b></td>
								<td colspan=3> &nbsp; <i>{{$money_total_Reader}}</i> <td>
							</tr>
							<tr>
								<td>{{$LDDiscountonTotalAmount}}</td>
								<td align="right">{{$LDDiscountonTotalAmountValue}}</td>
								<td colspan=3> &nbsp; <i>{{$money_disc_Reader}}</i><td>
							</tr>
							<tr>
								<td>{{$LDAmountAfterDiscount}}</td>
								<td align="right">{{$LDAmountAfterDiscountValue}}</td>
								<td colspan=3> &nbsp; <i>{{$money_afterdisc_Reader}}</i><td>
							</tr>
							<tr>
								<td>{{$LDAmountPreviouslyReceived}}</td>
								<td align="right">{{$LDAmountPreviouslyReceivedValue}}</td>
								<td colspan=3> &nbsp; <i>{{$money_receive_Reader}}</i><td>
							</tr>
							<tr>
								<td colspan=5> - - - - - - - - - - - - - - - - - - <td>
							</tr>
							<tr>
								<td><b>{{$LDAmountDue}}</b></td>
								<td align="right"><b>{{$LDAmountDueValue}}</b></td>
								<td colspan=3> &nbsp; <i>{{$money_due_Reader}}</i><td>
							</tr>
							<tr>
								<td>{{$LDOldResume}}</td>
								<td align="right">{{$LDOldResumeValue}}</td>
								<td colspan=3> &nbsp; <i>{{$money_oldresume_Reader}}</i><td>
							</tr>
							<tr>
								<td><b>{{$LDCurrentPaidAmount}}</b></td>
								<td align="right"><b>{{$LDCurrentPaidAmountValue}}</b></td>
								<td colspan=3> &nbsp; <i>{{$money_paid_Reader}}</i> <td>
							</tr>
							<tr>
								<td><b>{{$LDPatientPaid}}</b></td>
								<td align="right"><b>{{$LDPatientPaidValue}}</b></td>
								<td colspan=3> &nbsp; <i>{{$money_patientpaid_Reader}}</i><td>
							</tr>
							<tr>
								<td><b>{{$LDAmountDueLast}}</b></td>
								<td align="right"><b>{{$LDAmountDueLastValue}}</b></td>
								<td colspan=3> &nbsp; <i>{{$money_duelast_Reader}}</i><td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			{{$sHiddenInputs}}
			<p>
			{{$pbPrint}} {{$pbClose}}
		</center>
	</div>

</body>
</html>