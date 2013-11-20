{{$sTab}}
<br>

{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><th align="left"><font size="3" color="#5f88be">{{$deptname}}</th></tr>
	<tr><th align="left"><font size="2" color="#85A4CD">{{$ward}}</th></tr>
	<tr>
		<td align="center" colspan="7"><font class="prompt"><b>{{$titleForm}}</b></td>
	</tr>
    <tr>
		<th align="left">{{$LDTYPE}}:&nbsp;&nbsp;&nbsp;{{$sTypePut}}</th>
	</tr>	
	<tr>
		<td align="right">{{$IssueId}}</td>
	</tr>

</table>

<table border="0" bgColor="#999999" cellpadding="0" cellspacing="1" width="99%">
	<tr>
		<td width="5%">
			<table id="tblSTT" bgColor="#999999" cellpadding="3" cellspacing="1" border="0" width="100%" >
				<tr bgColor="#E1E1E1"><td>&nbsp;</td> <th align="center" height="41">{{$LDSTT}}&nbsp;</th></tr>
				{{$divSTT}}
			</table>
		</td>
		<td width="98%">
			<table id="tblMedicine" bgColor="#999999" cellpadding="3" cellspacing="1" border="0" width="100%">
				<tr bgColor="#E1E1E1" >	
					<th align="center" rowspan="2">{{$LDPresID}}</th>
					<th align="center" rowspan="2">{{$LDPresName}}</th>
					<th align="center" rowspan="2">{{$LDUnit}}</th>
					<th align="center" rowspan="2">{{$LDLotID}}</th>
					<th align="center" rowspan="2">{{$LDExpDate}}</th>
					<th align="center" rowspan="2">{{$LDCost}}</th>
					<th height="20" align="center" colspan="2">{{$LDDestroyRequest}}</th>
					<th align="center" rowspan="2">{{$LDNote}}</th>
				</tr>
				<tr bgColor="#E1E1E1" >
					<th width="80" height="20" align="center">{{$LDNumberOf}}</th>
					<th width="80" height="20" align="center">{{$LDTotalCost}}</th>
				</tr>
				{{$divMedicine}}
			</table>
		</td>
	</tr>
	<tr>
		<td bgColor="#E1E1E1"> <br>&nbsp;</td>
		<th align="left" valign="middle" bgColor="#E1E1E1" colspan="6">{{$AddRow}}</th>
	</tr>
</table>

<p>
<table cellSpacing="0" cellPadding="3" border="0" width="95%">
	<tr>
		<td valign="top">{{$NoteOfCreator}}</td>
		<td align="right" valign="top">{{$UserName}}</td>
	</tr>
</table>	

{{$sHiddenInputs}}
<p>
<table>
	<tr>
		<td>{{$pbSubmit}}&nbsp;</td>
		<td>{{$pbDelete}}</td>
		<td>{{$pbCancel}}</td>
	</tr>
</table>
</center>
</form>