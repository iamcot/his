{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}

{{if $bSetAsForm}}
	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">
{{/if}}

<table border=0 cellpadding=2 width=100%>
	<tr class="adm_item" bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">1.{{$LDToanthan}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td >{{$LDToanthan}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='toanthan_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sToanthanNotes}}
		{{/if}}

	 </td>
   </tr>
   <tr class="adm_item" bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDCaccoquan}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>{{$LDTuanhoan}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='tuanhoan_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sTuanhoanNotes}}
		{{/if}}

         </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDHohap}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='hohap_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sHohapNotes}}
		{{/if}}


		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDTieuhoa}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='tieuhoa_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sTieuhoaNotes}}
		{{/if}}

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDThantietnieusinhduc}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='thantietnieusinhduc_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sThantietnieusinhducNotes}}
		{{/if}}

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDThankinh}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='thankinh_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sThankinhNotes}}
		{{/if}}

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDCoxuongkhop}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='coxuongkhop_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sCoxuongkhopNotes}}
		{{/if}}

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td>  {{$LDTaimuihong}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='taimuihong_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sTaimuihongNotes}}
		{{/if}}

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td>  {{$LDRanghammat}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='ranghammat_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sRanghammatNotes}}
		{{/if}}

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td>  {{$LDMat}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='mat_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sMatNotes}}
		{{/if}}

		</td>
   </tr>
     <tr bgcolor='#f6f6f6'>
     <td>  {{$LDKhac}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='khac_notes' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sKhacNotes}}
		{{/if}}

		</td>
   </tr>
       <tr bgcolor='#f6f6f6'>
     <td>  {{$LDTongquat}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='tongquat_bp' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sTongquatBp}}
		{{/if}}

		</td>
   </tr>
    <tr class="adm_item" bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDChuyenKhoa}}</font></td>
    
   </tr>
       <tr bgcolor='#f6f6f6'>
     <td>  {{$LDChuyenKhoa}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='chuyenkhoa' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sChuyenKhoa}}
		{{/if}}

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td><FONT  color='red'>*</font>  {{$LDDate}}</td>
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
     <td><FONT  color='red'>*</font>  {{$LDBy}} </td>
     <td>

	 	{{if $bSetAsForm}}
	 		<input type='text' name='personell_name' size=50 maxlength=60 value='{{$TP_user_name}}' readonly>
		{{else}}
			{{$sAuthor}}
		{{/if}}

	 </td>
   </tr>
</table>

{{if $bSetAsForm}}
	{{$sHiddenInputs}}
	</form>
{{/if}}
