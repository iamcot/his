<center>
<div class="prompt">{{$FormTitle}}</div>
<p></p>
<table cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
 <tbody>
	<tr bgColor="#eeeeee">
		<th width="40%" height="7" align="center" bgcolor="#CCCCCC">{{$LDReceiptNumber}}</th>
		<th align="center" height="7" bgcolor="#CCCCCC">{{$LDReceiptDateTime}}</th>
		<th align="center" height="7" bgcolor="#CCCCCC">{{$LDReceiptUser}}</th>
	</tr>
	
	{{$ItemLine}}

 </tbody>
</table>
<p><p>
<table cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
 <tbody>
	<tr bgColor="#eeeeee">
		<th width="40%" height="7" align="center">{{$LDTotalBillAmount}}</th>
		<td colspan="2" align="center" height="7" >{{$LDTotalBillAmountValue}}</th>
	</tr>
	<tr bgcolor="#eeeeee">
		<th align="center" height="7" >{{$LDOutstandingAmount}}</td>
		<td colspan="2" align="center" height="7" >{{$LDOutstandingAmountValue}}</td>
	</tr>
	<tr bgcolor="#eeeeee">
		<th align="center" height="7" >{{$LDAmountDue}}</td>
		<td colspan="2" align="center" height="7" >{{$LDAmountDueValue}}</td>
	</tr>
 </tbody>
</table>
<p><p>
{{$sFormTag}}
{{$sHiddenInputs}}
</form>
<p>
{{$pbCancel}}
</center>
</ul>
