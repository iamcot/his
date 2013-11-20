{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">


<table border=0 cellpadding=2 width=100%>
	<tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDKhambenhRHM}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item" >{{$LDTongquan}}</td>
     <td>
			<textarea name='notes' cols=60 rows=2 wrap='physical'>{{$sTongquan}}</textarea>		
	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDThanhquan}}</td>
     <td>
		<textarea name='thanhquan_notes' cols=60 rows=2 wrap='physical'>{{$sThanhquan}}</textarea>
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDHong}}</td>
     <td>
			<textarea name='hong_notes' cols=60 rows=2 wrap='physical'>{{$sHong}}</textarea>
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDConghiengT}}</td>
     <td>
			<textarea name='conghiengtrai' cols=60 rows=2 wrap='physical'>{{$sConghiengT}}</textarea>
	 </td>
   </tr>
   
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDConghiengP}}</td>
     <td>
			<textarea name='conghiengphai' cols=60 rows=2 wrap='physical'>{{$sConghiengP}}</textarea>
   	 </td>
   </tr>
  
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDDate}}</td>
     <td>	 	
			{{$sDate}}
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDBy}} </td>
     <td>	 	
	 		<input type='hidden' name='doctor_nr' value='{{$sDocNr}}'>
	 		<input type='text' name='doctor_name' style="width:40%;" maxlength=60 value='{{$sBy}}' readonly>
			<a href="javascript:popDocPer('doctor_nr')">
				<img width="16" height="16" border="0" src="../../gui/img/common/default/l-arrowgrnlrg.gif">
			</a>	
		
			
		

	 </td>
   </tr>
</table>

	{{$sHiddenInputs}}
	</form>
