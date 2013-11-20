{{$sTab}}
<br>

{{$sPresForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="3" color="#5f88be">{{$deptname}}</th><td rowspan="2" align="right">{{$calendar}}</td></tr>
	<tr><th align="left"><font size="2" color="#85A4CD">{{$ward}}</th></tr>
</table>
<p><br>
<table cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="95%">
		<tr bgColor="#eeeeee" >
			<th align="center" bgcolor="#CCCCCC" width="40%">{{$LDPatient}}</th>
			<th align="center" bgcolor="#CCCCCC" width="29%">{{$LDMedicine}}</th>
			<th align="center" bgcolor="#CCCCCC" width="10%">{{$LDNhanVeTuThuoc}}</th>
			<th align="center" bgcolor="#CCCCCC" width="9%">{{$LDDaPhat}}</th>
			<th align="center" bgcolor="#CCCCCC" width="9%">{{$LDIssue}}</th>
			<th align="center" bgcolor="#CCCCCC">{{$LDKetThuc}}</th>
		</tr>
		
		{{$ItemLine}}
	
	 </tbody>
</table>

<p>
{{$sHiddenInputs}}
<p></br>
<table>
<tr><td>
{{$pbSubmit}}&nbsp;</td><td> {{$pbCancel}}</td>
</tr>
</table>
</center>
</form>
