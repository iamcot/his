{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}

{{if $bSetAsForm}}
	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">
{{/if}}

<table border=0 cellpadding=2 width=100%>
	<tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDKhambenhYHCT}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item" >{{$LDVongchan}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='vongchan' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sVongchan}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDVanchan}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='vanchan' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sVanchan}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDVanchan1}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='van_chan' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sVanchan1}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDThietchan}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='thietchan' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sThietchan}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDChandoan}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">{{$LDBenhdanh}}</td>
     <td>

	 	{{if $bSetAsForm}}
			<textarea name='benhdanh' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sBenhdanh}}
		{{/if}}

         </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDBatcuong}}</td>
     <td>
	 	{{if $bSetAsForm}}
			<textarea name='batcuong' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sBatcuong}}
		{{/if}}
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDTangphu}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='tangphu' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sTangphu}}
		{{/if}}

		</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDNguyennhan}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='nguyennhan' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sNguyennhan}}
		{{/if}}

		</td>
   </tr>
   <tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDDieutri}}</font></td>
    
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDPhepchua}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='phepchua' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sPhepchua}}
		{{/if}}

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDPhuongthuoc}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='phuongthuoc' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sPhuongthuoc}}
		{{/if}}

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDPhuonghuyet}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='phuonghuyet' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sPhuonghuyet}}
		{{/if}}

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDXoabop}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='xoabop' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sXoabop}}
		{{/if}}

		</td>
   </tr>
     <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDChedoan}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='chedoan' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sChedoan}}
		{{/if}}

		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDChedoholy}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='chedoholy' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sChedoholy}}
		{{/if}}

		</td>
   </tr>
   <tr bgcolor='#adadad'>
     <td colspan=2><font style="font-weight:bold;color:blue;">{{$LDTienluong}}</font></td>
    
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item">  {{$LDTienluong}}</td>
		<td>
		
	 	{{if $bSetAsForm}}
			<textarea name='tienluong' cols=60 rows=2 wrap='physical'></textarea>
		{{else}}
			{{$sTienluong}}
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