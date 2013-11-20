{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


{{$sDocsJavaScript}}
<form method="post" name="entryform" onSubmit="return chkForm(this)">


<table border=0 cellpadding=2 width=100%>
	<tr bgcolor='#f6f6f6'>
		<td ><b>{{$LDTongKetBenhAn}}</b></td><td> {{$cbxTypeMedoc}}</td>
		<td >{{$LDDate}}</td><td>{{$sDateMiniCalendar}}</td>
	</tr>

{{if $bYHCT}}
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDLyDoVaoVien}}</td>
		<td colspan="2">
			<textarea name='yhct_lydovao' cols="44" rows="3" wrap='physical'>{{$sLyDoVaoVien}}</textarea>
	</td>
	<td></td>
   </tr>
{{/if}}	
	
   <tr bgcolor='#f6f6f6'>
     <td valign="top" width="25%">{{$LDQuaTrinhBenhLy}}</td>
     <td colspan="2">
			<textarea name='text_progress' cols="44" rows="3" wrap='physical'>{{$sQuaTrinhBenhLy}}</textarea>
	</td>
	<td>	
		{{$sXemQTBL}}
	 </td>
   </tr>
   
   <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDTomTatKQXN}}</td>
     <td colspan="2">
			<textarea name='text_sumLab' cols="44" rows="3" wrap='physical'>{{$sTomTatKQXN}}</textarea>
	</td>
	<td>
		{{$sXemTTKQXN}}		
     </td>
   </tr>
   
{{if $bYHCT}}
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDKetQuaGPB}}</td>
		<td colspan="2">
			<textarea name='yhct_kqgpb' cols="44" rows="3" wrap='physical'>{{$sKetQuaGPB}}</textarea>
	</td>
	<td></td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDChanDoanVaoVien}}</td>
		<td colspan="2">
			<textarea name='yhct_chandoanvao' cols="44" rows="3" wrap='physical'>{{$sChanDoanVaoVien}}</textarea>
	</td>
	<td></td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDPhapTri}}</td>
		<td colspan="2">
			<textarea name='yhct_phaptri' cols="44" rows="3" wrap='physical'>{{$sPhapTri}}</textarea>
	</td>
	<td></td>
   </tr>   
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDThoiGianDieuTri}}</td>
		<td colspan="2">
			<textarea name='yhct_thoigiantri' cols="44" rows="3" wrap='physical'>{{$sThoiGianDieuTri}}</textarea>
	</td>
	<td></td>
   </tr> 
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDKetQuaDieuTri}}</td>
		<td colspan="2">
			<textarea name='yhct_ketquatri' cols="44" rows="3" wrap='physical'>{{$sKetQuaDieuTri}}</textarea>
	</td>
	<td></td>
   </tr>    
{{/if}}	  
   
   
{{if $bNgoaiTru || $bYHCT}}
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDChanDoanRaVien}}</td>
		<td colspan="2">
			<textarea name='text_outdia' cols="44" rows="3" wrap='physical'>{{$sChanDoanRaVien}}</textarea>
	</td>
	<td></td>
   </tr>
{{/if}}
   
{{if $bNoiTru || $bNgoaiTru || $bKhac}}   
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDTherapy}}</td>
		<td colspan="2">
			<textarea name='text_therapy' cols="44" rows="3" wrap='physical'>{{$sTherapy}}</textarea>
	</td>
	<td>
		{{$sXemPPDT}}	
	</td>
   </tr>
{{/if}}   

{{if $bKhac}}   
   <tr bgcolor='#f6f6f6'>
	<td colspan="4" bgcolor="#EEE">
			<table width="100%" cellpadding="1" cellspacing="1">
				<tr><td colspan="2" align="center">{{$LDPhauThuat}}&nbsp;<input type="checkbox" name="cb_pt" value="1" {{$cb_pt}}></td>
					<td colspan="2">{{$LDThuThuat}}&nbsp;<input type="checkbox" name="cb_tt" value="1" {{$cb_tt}}></td></tr>
					<td></td>
				<tr align="center" >
					<td><i>{{$LDNgayGio}}</i></td><td><i>{{$LDPhauThuatVoCam}}</i></td><td><i>{{$LDBacSyPT}}</i></td><td><i>{{$LDBacSyGM}}</i></td><td></td>				
				</tr>
				{{$sSurgery}}
			</table>
			<br>
	</td>
   </tr>
{{/if}} 
 
{{if $bNoiTru || $bNgoaiTru || $bKhac}}  
   <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDTTRaVien}}</td>
     <td colspan="2">
			<textarea name='text_outhos' cols="44" rows="3" wrap='physical'>{{$sTTRaVien}}</textarea>
	</td>
	<td></td>
   </tr>
{{/if}}    
   
   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDHuongDieuTri}}</td>
     <td colspan="2">
			<textarea name='text_treatment' cols="44" rows="3" wrap='physical'>{{$sHuongDieuTri}}</textarea>
	</td>
	<td></td>
   </tr>

   <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDBy}} </td>
     <td colspan="3">
	 		<input type='text' name='personell_name' size=45  value='{{$TP_user_name}}' readonly>
	 </td>
   </tr>
</table>


{{$sHiddenInputs}}
</form>

