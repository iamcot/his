{{* Template for reports (notes) *}}

<table border=0 cellpadding=4 cellspacing=1 width=100%>
	<tr><td colspan="2" align="center"><b>{{$LDTongKetBenhAn}}</b></td></tr>
	<tr class="wardlistrow2">
		<td width="30%"><b>{{$LDDate}}</b></td>	
		<td>{{$sDate}}</td>	
	</tr>
	
	{{$sDocsListRows}}
	{{$sDocsListRows_sur}}
	{{$sDocsListRows1}}
	
	<tr>	
		<td><br><b>{{$LDBy}}</b></td>
		<td><br>{{$sAuthor}}</td>
	</tr>

</table>
