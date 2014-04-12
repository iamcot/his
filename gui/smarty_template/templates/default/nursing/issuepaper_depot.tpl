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
        <td align="right">{{$LDDate}}: {{$sCalendar}}</td>
	</tr>
	<tr>
		<td align="right">{{$IssueId}}</td>
	</tr>

</table>

{{if $depot}}

<table border="0" bgColor="#999999" cellpadding="0" cellspacing="1" width="95%">
	<tr>
		<td width="5%">
			<table id="tblSTT" bgColor="#999999" cellpadding="3" cellspacing="1" border="0" width="100%" >
				<tr bgColor="#E1E1E1"><td>&nbsp;</td> <th align="center" height="41">{{$LDSTT}}&nbsp;</th></tr>
				{{$divSTT}}
			</table>
		</td>
		<td width="95%">
			<table id="tblMedicine" bgColor="#999999" cellpadding="3" cellspacing="1" border="0" width="100%">
				<tr bgColor="#E1E1E1" >	
					<th width="120" align="center" rowspan="2">{{$LDPresID}}</th>
					<th width="250" align="center" rowspan="2">{{$LDPresName}}</th>
					<th width="100" align="center" rowspan="2">{{$LDUnit}}</th>
					<th width="100" align="center" rowspan="2">{{$LDInventory}}</th>
					<th width="200" height="20" align="center" colspan="2">{{$LDNumberOf}}</th>
					<th width="100" align="center" rowspan="2">{{$LDNote}}</th>
				</tr>
				<tr bgColor="#E1E1E1" >
					<th width="100" height="20" align="center">{{$LDTotal}}</th>
					<th width="100" height="20" align="center">{{$LDIssue}}</th>
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

{{elseif $sum}}

<table border="0" bgColor="#999999" cellpadding="0" cellspacing="1" width="1000">
	<tr bgColor="#E1E1E1" >
		<th width="50" align="center" rowspan="2">{{$LDSTT}}</th>
		<th width="100" align="center" rowspan="2">{{$LDPresID}}</th>
		<th width="200" align="center" rowspan="2">{{$LDPresName}}</th>
		<th width="100" align="center" rowspan="2">{{$LDUnit}}</th>
		<th width="100" align="center" rowspan="2">{{$LDRequest}}</th>
		<th width="100" align="center" rowspan="2">{{$LDInventory}}</th>
		<th width="100" align="center" rowspan="2">{{$LDPlus}}</th>
		<th width="150" align="center" colspan="2">{{$LDNumberOf}}</th>
		<th width="100" align="center" rowspan="2">{{$LDNote}}</th>
	</tr>
	<tr bgColor="#E1E1E1" >
		<th width="80" align="center">{{$LDTotal}}</th>
		<th width="70" align="center">{{$LDIssue}}</th>
	</tr>
	<tr>
		<td valign="top">
			<table id="tblSTT" cellpadding="3" cellspacing="1" border="0" width="50">
				{{$divSTT}}
			</table>
		</td>
		<td colspan="9">
			<table id="tblMedicine" cellpadding="3" cellspacing="1" border="0" width="950">
				{{$divMedicine}}
			</table>
		</td>
	</tr>
	<tr>
		<td bgColor="#E1E1E1"> <br>&nbsp;</td>
		<th align="left" valign="middle" bgColor="#E1E1E1" colspan="9">{{$AddRow}}</th>
	</tr>
</table>



{{/if}}
<p>
<table cellSpacing="0" cellPadding="3" border="0" width="950">
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