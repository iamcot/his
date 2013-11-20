{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


	{{$sDocsJavaScript}}
<form method="post" name="entryform" onSubmit="return chkForm(this)">


<table border=0 cellpadding=5 width=100%>
	<tr>
		<td colspan="2" align="center">
			<br>
			<font size="3" color="#5F88BE"><b>{{$LDKhamYHCT}}</b></font>
			<br>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			{{$sContent}}
			<br>
		</td>
	</tr>
	<tr bgcolor="#EDF1F4">
     <td valign="top" colspan="2"><font color="#039">{{$LDBienChungLuanTri}}</font></td></tr>
	<tr>
     <td colspan="2">			
			{{$sBienChung}}
			<br>&nbsp;
	</td>
   </tr>
	<tr bgcolor="#EDF1F4" colspan="2">
     <td valign="top" colspan="2"><font color="#039">{{$LDChanDoan}} </font><FONT  color='red'>*</font></td></tr>
	<tr> 
     <td colspan="2">	
			{{$sChanDoan}}
			<br>&nbsp;
	</td>
   </tr>	
   <tr bgcolor='#f6f6f6'>
     <td>{{$LDDate}} <FONT  color='red'>*</font></td>
     <td>	
			{{$sDateMiniCalendar}}
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>{{$LDBy}} <FONT  color='red'>*</font></td>
     <td>
	 		<input type='text' name='personell_name' size=50 maxlength=60 value='{{$sAuthor}}' readonly>
	 </td>
   </tr>
</table>


	{{$sHiddenInputs}}
</form>

