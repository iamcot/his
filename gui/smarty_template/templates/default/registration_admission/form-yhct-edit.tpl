{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">


<table border=0 cellpadding=2 width=100%>
	<tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDKhambenhYHCT}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item" >{{$LDVongchan}}</td>
     <td>
			<textarea name='vongchan' cols=60 rows=2 wrap='physical'>{{$sVongchan}}</textarea>		
	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDVanchan}}</td>
     <td>
		<textarea name='vanchan' cols=60 rows=2 wrap='physical'>{{$sVanchan}}</textarea>
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDVanchan1}}</td>
     <td>
			<textarea name='van_chan' cols=60 rows=2 wrap='physical'>{{$sVanchan1}}</textarea>
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDThietchan}}</td>
     <td>
			<textarea name='thietchan' cols=60 rows=2 wrap='physical'>{{$sThietchan}}</textarea>
	 </td>
   </tr>
   <tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDChandoan}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDBenhdanh}}</td>
     <td>
			<textarea name='benhdanh' cols=60 rows=2 wrap='physical'>{{$sBenhdanh}}</textarea>
   	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDBatcuong}}</td>
     <td>
			<textarea name='batcuong' cols=60 rows=2 wrap='physical'>{{$sBatcuong}}</textarea>
		
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDTangphu}}</td>
		<td>
			<textarea name='tangphu' cols=60 rows=2 wrap='physical'>{{$sTangphu}}</textarea>
		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDNguyennhan}}</td>
		<td>
			<textarea name='nguyennhan' cols=60 rows=2 wrap='physical'>{{$sNguyennhan}}</textarea>
		</td>
   </tr>
   <tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDDieutri}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDPhepchua}}</td>
		<td>
			<textarea name='phepchua' cols=60 rows=2 wrap='physical'>{{$sPhepchua}}</textarea>
		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDPhuongthuoc}}</td>
		<td>
			<textarea name='phuongthuoc' cols=60 rows=2 wrap='physical'{{$sPhuongthuoc}}></textarea>
		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDPhuonghuyet}}</td>
		<td>
			<textarea name='phuonghuyet' cols=60 rows=2 wrap='physical'>{{$sPhuonghuyet}}</textarea>
		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDXoabop}}</td>
		<td>
			<textarea name='xoabop' cols=60 rows=2 wrap='physical'>{{$sXoabop}}</textarea>
		</td>
   </tr>
     <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDChedoan}}</td>
		<td>
			<textarea name='chedoan' cols=60 rows=2 wrap='physical'>{{$sChedoan}}</textarea>
		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDChedoholy}}</td>
		<td>
			<textarea name='chedoholy' cols=60 rows=2 wrap='physical'>{{$sChedoholy}}</textarea>
		</td>
   </tr>
   <tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDTienluong}}</font></td>
    
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDTienluong}}</td>
		<td>
			<textarea name='tienluong' cols=60 rows=2 wrap='physical'>{{$sTienluong}}</textarea>
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
