{{$sTab}}
<br>

{{$sPresForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="3" color="#5f88be">{{$deptname}}</th></tr>
	<tr><th align="left"><font size="2" color="#85A4CD">{{$ward}}</th></tr>
</table>
<p><br>
<table cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="90%">
		<tr bgColor="#eeeeee">
			<th bgcolor="#CCCCCC">&nbsp;</th>
			<th height="7" align="center" bgcolor="#CCCCCC">{{$LDPresID}}</th>
			<th height="7" align="center" bgcolor="#CCCCCC">{{$LDEncounterID}}</th>
			<th align="center" height="7" bgcolor="#CCCCCC">{{$LDEncounterName}}</th>
			<th align="center" height="7" bgcolor="#CCCCCC">{{$LDEncounterSex}}</th>
			<th align="center" height="7" bgcolor="#CCCCCC">{{$LDEncounterBirth}}</th>
			<th align="center" height="7" bgcolor="#CCCCCC">{{$LDUseFor}}</th>
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
