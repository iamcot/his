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
<font face="Arial" size="4" color="#000000"><b>{{$LDTitleFinalBill}}</b></font><br>
<font face="Arial" size="3" color="#000000">{{$LDLayMuchuong}}</font>
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

        {{$MucHuong}}
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


        </table>
    </td>
</tr>
<tr>
    <td>
        <table border="0" width="100%" cellpadding="0">
            <tr align="center" bgcolor=#dddddd>
                <td width="30%" rowspan="2"><b>{{$LDContent}}</b></td>
                <td rowspan="2"><b>{{$LDUnit}}</b></td>
                <td rowspan="2"><b>{{$LDNumberOf}}</b></td>
                <td rowspan="2"><b>{{$LDEnterPriceUnit}}</b></td>
                <td rowspan="2"><b>{{$LDSumCost}}</b></td>
                <td colspan="3" width="30%"><b>Nguồn thanh toán</td>
            </tr>
            <tr align="center" bgcolor=#dddddd>
                <td ><b>Quỹ BHYT</td>
                <td><b>Khác</td>
                <td><b>Người bệnh</td>
            </tr>

            {{if $ItemPres != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDPrescriptionName}}</b></td>
            </tr>
            {{$ItemPres}}
            {{/if}}

            {{if $ItemDepot != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDDepot}}</b></td>
            </tr>
            {{$ItemDepot}}
            {{/if}}

            {{if $ItemSurgery != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDSurgery}}</b></td>
            </tr>
            {{$ItemSurgery}}
            {{/if}}

            {{if $ItemLDLabor != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDLaboration}}</b></td>
            </tr>
            {{$ItemLDLabor}}
            {{/if}}

            {{if $ItemRadio != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDRadio}}</b></td>
            </tr>
            {{$ItemRadio}}
            {{/if}}

            {{if $ItemUltrasonic != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDUltrasonic}}</b></td>
            </tr>
            {{$ItemUltrasonic}}
            {{/if}}

            {{if $ItemECG != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDECG}}</b></td>
            </tr>
            {{$ItemECG}}
            {{/if}}

            {{if $ItemBlood != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDBlood}}</b></td>
            </tr>
            {{$ItemBlood}}
            {{/if}}

            {{if $ItemBed != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDBed}}</b></td>
            </tr>
            {{$ItemBed}}
            {{/if}}

            {{if $ItemKhac != ''}}
            <tr>
                <td colspan=8 bgcolor=#eeeeee><b>{{$LDKhac}}</b></td>
            </tr>
            {{$ItemKhac}}
            {{/if}}

            <tr>
                <td colspan=8> &nbsp; <td>
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
                <td><b>{{$LDAmountAfterDiscount}}<b></td>
                <td align="right"><b>{{$LDAmountAfterDiscountValue}}<b></td>
                <td colspan=3> &nbsp; <i>{{$money_afterdisc_Reader}}</i><td>
            </tr>
            <tr>
                <td>{{$LDAmountPreviouslyReceived}}</td>
                <td align="right">{{$LDAmountPreviouslyReceivedValue}}</td>
                <td colspan=3> &nbsp; <i>{{$money_receive_Reader}}</i><td>
            </tr>
            <!--
            <tr>
                <td colspan=5> ------------------------------------------------------------------------------------------------------------------------------------<td>
            </tr>
            <tr>  -->
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
{{$sFormTag}}
<p>
    {{$pbPrint}} {{$pbClose}}
<p>
    {{$pbSubmit}} {{$pbCancel}}
<p>
</center>
</div>

</body>
</html>