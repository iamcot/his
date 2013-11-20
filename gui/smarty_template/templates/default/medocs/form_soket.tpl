{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


{{$sDocsJavaScript}}
<form method="post" name="entryform" onSubmit="return chkForm(this)">


<table border=0 cellpadding=2 width=100%>
	<tr><td colspan="2"><b>{{$LDSoKetBenhAn}}</b></td></tr>
	<tr bgcolor='#f6f6f6'>
		<td width="30%">{{$LDDate}}</td><td colspan="2">{{$sDateMiniCalendar}}</td>
	</tr>
	
   <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDDienBienLamSang}}</td>
     <td>
			<textarea name='text_dienbien' cols="44" rows="3" wrap='physical'>{{$sDienBienLamSang}}</textarea>
	</td>
	<td>{{$sXemQTBL}}</td>
   </tr>

    <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDXetNghiemCLS}}</td>
     <td>
			<textarea name='text_xetnghiemcls' cols="44" rows="3" wrap='physical'>{{$sXetNghiemCLS}}</textarea>
	</td>
	<td>{{$sXemTTKQXN}}</td>
   </tr>
   
    <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDQuaTrinhDieuTri}}</td>
     <td>
			<textarea name='text_quatrinhdieutri' cols="44" rows="3" wrap='physical'>{{$sQuaTrinhDieuTri}}</textarea>
	</td>
	<td>{{$sXemPPDT}}</td>	
   </tr>
   
   <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDDanhGiaKQ}}</td>
     <td colspan="2">
			<textarea name='text_danhgiakq' cols="44" rows="3" wrap='physical'>{{$sDanhGiaKQ}}</textarea>
	</td>
   </tr>

   <tr bgcolor='#f6f6f6'>
     <td valign="top"> {{$LDHuongDieuTri}}</td>
     <td colspan="2">
			<textarea name='text_huongdieutri' cols="44" rows="3" wrap='physical'>{{$sHuongDieuTri}}</textarea>
	</td>
   </tr>

   <tr bgcolor='#f6f6f6'>
     <td valign="top">{{$LDBy}} </td>
     <td colspan="2">
	 		<input type='text' name='personell_name' size=45  value='{{$sAuthor}}' readonly>
	 </td>
   </tr>
</table>


{{$sHiddenInputs}}
</form>

