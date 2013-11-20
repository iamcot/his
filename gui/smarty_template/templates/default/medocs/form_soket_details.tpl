{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


{{$sDocsJavaScript}}
<form method="post" name="entryform" onSubmit="return chkForm(this)">


<table border=0 cellpadding=3 width=100%>
	<tr><td colspan="2"><b>{{$LDSoKetBenhAn}}</b></td></tr>
	<tr valign="top" bgcolor='#f6f6f6'>
		<td width="30%"><b>{{$LDDate}}</b><br>&nbsp;</td><td>{{$sDate}}</td>
	</tr>
	
   <tr valign="top" bgcolor='#f6f6f6'>
     <td><b>{{$LDDienBienLamSang}}</b><br>&nbsp;</td>
     <td>
			{{$sDienBienLamSang}}
	</td>
   </tr>

    <tr valign="top" bgcolor='#f6f6f6'>
     <td><b>{{$LDXetNghiemCLS}}</b><br>&nbsp;</td>
     <td>
			{{$sXetNghiemCLS}}
	</td>
   </tr>
   
    <tr valign="top" bgcolor='#f6f6f6'>
     <td><b>{{$LDQuaTrinhDieuTri}}</b><br>&nbsp;</td>
     <td>
			{{$sQuaTrinhDieuTri}}
	</td>
   </tr>
   
   <tr valign="top" bgcolor='#f6f6f6'>
     <td><b>{{$LDDanhGiaKQ}}</b><br>&nbsp;</td>
     <td>
			{{$sDanhGiaKQ}}
	</td>
   </tr>

   <tr valign="top" bgcolor='#f6f6f6'>
     <td><b>{{$LDHuongDieuTri}}</b><br>&nbsp;</td>
     <td>
			{{$sHuongDieuTri}}
	</td>
   </tr>

   <tr valign="top" bgcolor='#f6f6f6'>
     <td><b>{{$LDBy}}</b><br>&nbsp;</td>
     <td>
	 		{{$sAuthor}}
	 </td>
   </tr>
</table>


{{$sHiddenInputs}}
</form>

