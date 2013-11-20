{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">


<table border=0 cellpadding=2 width=100%>
	<tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">1.{{$LDToanthan}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td >{{$LDToanthan}}</td>
     <td>

	 	
			<textarea name='toanthan_notes' cols=60 rows=2 wrap='physical'>{{$sToanthanNotes}}</textarea>
		

	 </td>
   </tr>
   <tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDCaccoquan}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>{{$LDTuanhoan}}</td>
     <td>

	 	
			<textarea name='tuanhoan_notes' cols=60 rows=2 wrap='physical'>{{$sTuanhoanNotes}}</textarea>
		
			
		

         </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDHohap}}</td>
     <td>

	 	
			<textarea name='hohap_notes' cols=60 rows=2 wrap='physical'>{{$sHohapNotes}}</textarea>
		
			


		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDTieuhoa}}</td>
		<td>
		
	 	
			<textarea name='tieuhoa_notes' cols=60 rows=2 wrap='physical'>{{$sTieuhoaNotes}}</textarea>
		
			

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDThantietnieusinhduc}}</td>
		<td>
		
	
			<textarea name='thantietnieusinhduc_notes' cols=60 rows=2 wrap='physical'>{{$sThantietnieusinhducNotes}}</textarea>
		
			
		

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDThankinh}}</td>
		<td>
		
	 	
			<textarea name='thankinh_notes' cols=60 rows=2 wrap='physical'>{{$sThankinhNotes}}</textarea>
		
			
		

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td>  {{$LDCoxuongkhop}}</td>
		<td>
		
	 	
			<textarea name='coxuongkhop_notes' cols=60 rows=2 wrap='physical'>{{$sCoxuongkhopNotes}}</textarea>
	
			
		

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td>  {{$LDTaimuihong}}</td>
		<td>
		
	 	
			<textarea name='taimuihong_notes' cols=60 rows=2 wrap='physical'>{{$sTaimuihongNotes}}</textarea>
	
			
		

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td>  {{$LDRanghammat}}</td>
		<td>
		
	 	
			<textarea name='ranghammat_notes' cols=60 rows=2 wrap='physical'>{{$sRanghammatNotes}}</textarea>
	
	
		

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td>  {{$LDMat}}</td>
		<td>
		
	 	
			<textarea name='mat_notes' cols=60 rows=2 wrap='physical'>{{$sMatNotes}}</textarea>
	
		
		

		</td>
   </tr>
     <tr bgcolor='#f6f6f6'>
     <td>  {{$LDKhac}}</td>
		<td>
		
	 	
			<textarea name='khac_notes' cols=60 rows=2 wrap='physical'>{{$sKhacNotes}}</textarea>
	
		
		

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td><FONT  color='red'>*</font>  {{$LDDate}}</td>
     <td>
	 
	 	
			<!-- gjergji : not needed anymore, since the new calendar 
				<input type='text' name='date' size=10 maxlength=10 {{$sDateValidateJs}}>-->
			
	
			{{$sDate}}
		

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td><FONT  color='red'>*</font>{{$LDBy}}</td>
     <td>

	 	
	 		<input type='text' name='personell_name' size=50 maxlength=60 value='{{$sAuthor}}' readonly>
	
			
		

	 </td>
   </tr>
</table>


	{{$sHiddenInputs}}
	</form>

