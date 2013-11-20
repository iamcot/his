{{$sTab}}
<br>

{{$sRegForm}}
<center>
<table cellSpacing="0" cellPadding="3" border="0" width="90%">
	<tr><th align="left" width="50%"><font size="3" color="#5f88be">{{$deptname}}</th>
		<td rowspan="2" align="right"><input type="text" id="search" value=""></td>
		<td rowspan="2" align="right" width="3%">{{$pbSearch}}</td>
	</tr>
	<tr>
		<th align="left" width="20%"><font size="2" color="#85A4CD">{{$ward}}</th>	
	</tr>
	<tr>
		<td align="right" colspan="3"><FONT size=1>{{$LDsearchGuide}}</td>
	</tr>
</table>
<p>
<table border="0" cellpadding="0" cellspacing="0" width="90%" height="300">
	<tr>
		<td align="center" valign="top">
			<table border="0" bgColor="#999999" cellpadding="3" cellspacing="1" width="100%" >
				<tr bgColor="#E1E1E1" >
					<th width="5%" align="center">{{$LDDetail}}</th>
					<th width="5%" align="center">{{$LDIssueId}}</th>
					<th width="15%" align="center">{{$LDDatetime}}</th>
					<th width="10%" align="center">{{$LDCreatorName}}</th>
					<th width="15%" align="center">{{$LDWard}}</th>
					<th width="10%" align="center">{{$LDUseFor}}</th>
					<th width="10%" align="center">{{$LDType}}</th>
					<th width="15%" align="center">{{$LDStatus}}</th>
					<th width="15%" align="center">{{$LDEdit}}</th>
				</tr>
					{{$listItem}}
			</table>
		</td>
	</tr>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="90%">
	<tr>
		<td align="center">{{$splitPage}}</td>
	</tr>
</table>

{{$sHiddenInputs}}
<p>
<table>
	<tr>
		<td>{{$pbSubmit}}&nbsp;</td>
		<td>{{$pbCancel}}</td>
	</tr>
</table>
</center>
</form>
