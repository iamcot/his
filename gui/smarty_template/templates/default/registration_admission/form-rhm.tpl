{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}

{{if $bSetAsForm}}
	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">
{{/if}}

<table border=0 cellpadding=2 width=100%>
	<tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDKhambenhRHM}}</font></td>
    
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item" >{{$LDTongquan}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sTongquan}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item" >{{$LDThanhquan}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='thanhquan_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sThanhquan}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDHong}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='hong_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sHong}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDConghiengT}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='conghiengtrai' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sConghiengT}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDConghiengP}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='conghiengphai' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sThietchan}}
		{{/if}}

	 </td>
   </tr>
   
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDDate}}</td>
     <td>
	 
	 	{{if $bSetAsForm}}
			<!-- gjergji : not needed anymore, since the new calendar 
				<input type='text' name='date' size=10 maxlength=10 {{$sDateValidateJs}}>-->
			{{$sDateMiniCalendar}}
		{{else}}
			{{$sDate}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDBy}} </td>
     <td>

	 	{{if $bSetAsForm}}
	 		<input type='hidden' name='doctor_nr' value=''>
	 		<input type='text' name='doctor_name' style="width:40%;" maxlength=60 value='' readonly>
			<a href="javascript:popDocPer('doctor_nr')">
				<img width="16" height="16" border="0" src="../../gui/img/common/default/l-arrowgrnlrg.gif">
			</a>	
		{{else}}
			{{$sBy}}
		{{/if}}

	 </td>
   </tr>
</table>

{{if $bSetAsForm}}
	{{$sHiddenInputs}}
	</form>
{{/if}}