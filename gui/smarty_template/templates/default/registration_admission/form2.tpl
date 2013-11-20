{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}


	{{$sDocsJavaScript}}
<form method="post" name="entryform" >

<br/>
<table border=1 cellpadding=2 width=100%>
	
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDThiluckokinhT}}</nobr></td>
     <td>	 	
			<input type="text" value="{{$sThiluckokinhT}}" name="thiluc_khongkinh_trai" size=10>
	 </td>
	  <td class="adm_item"><nobr>{{$LDThiluckokinhP}}</nobr></td>
     <td>
			<input type="text" value="{{$sThiluckokinhP}}" name="thiluc_khongkinh_phai" size=10>		
				
	 </td>
	  <td class="adm_item"><nobr>{{$LDThiluccokinhT}}</nobr></td>
     <td>
			<input type="text" value="{{$sThiluccokinhT}}" name="thiluc_cokinh_trai" size=10>
	</td>
	  <td class="adm_item"><nobr>{{$LDThiluccokinhP}}</nobr></td>
     <td>
			<input type="text" value="{{$sThiluccokinhP}}" name="thiluc_cokinh_phai" size=10>
		</td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDNhanapT}}</nobr></td>
     <td>
		<input type="text" value="{{$sNhanapT}}" name="nhanap_trai" size=10>
	</td>
	  <td class="adm_item"><nobr>{{$LDNhanapP}}</nobr></td>
     <td>
		<input type="text" value="{{$sNhanapP}}" name="nhanap_phai" size=10>
	</td>
	  <td class="adm_item"><nobr>{{$LDThitruongT}}</nobr></td>
     <td>
		<input type="text" value="{{$sThitruongT}}" name="thitruong_trai" size=10>
	</td>
	  <td class="adm_item"><nobr>{{$LDThitruongP}}</nobr></td>
     <td>
			<input type="text" value="{{$sThitruongP}}" name="thitruong_phai" size=10>
	</td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDLedaoT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sLedaoT}}" name="ledao_trai" style="width:100%" >
	 </td>
	  <td class="adm_item"><nobr>{{$LDLedaoP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sLedaoP}}" name="ledao_phai" style="width:100%">
	</td>	
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDMimatT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sMimatT}}" name="mimat_trai" style="width:100%" >
	 </td>
	  <td class="adm_item"><nobr>{{$LDMimatP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sMimatP}}" name="mimat_phai" style="width:100%">
	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDKetmacT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sKetmacT}}" name="ketmac_trai" style="width:100%" >		
	 </td>
	  <td class="adm_item"><nobr>{{$LDKetmacP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sKetmacP}}" name="ketmac_phai" style="width:100%">
	</td>	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDMathotT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sMathotT}}" name="mathot_trai" style="width:100%" >
	</td>
	  <td class="adm_item"><nobr>{{$LDMathotP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sMathotP}}" name="mathot_phai" style="width:100%">
	 </td>	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDGiacmacT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sGiacmacT}}" name="giacmac_trai" style="width:100%" >
	</td>
	  <td class="adm_item"><nobr>{{$LDGiacmacP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sGiacmacP}}" name="giacmac_phai" style="width:100%">
	</td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDCungmacT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sCungmacT}}" name="cungmac_trai" style="width:100%" >
	 </td>
	  <td class="adm_item"><nobr>{{$LDCungmacP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sCungmacP}}" name="cungmac_phai" style="width:100%">
	 </td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDTienphongT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sTienphongT}}" name="tienphong_trai" style="width:100%" >
	 </td>
	  <td class="adm_item"><nobr>{{$LDTienphongP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sTienphongP}}" name="tienphong_phai" style="width:100%">
	</td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDMongmatT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sMongmatT}}" name="mongmat_trai" style="width:100%" >
	 </td>
	  <td class="adm_item"><nobr>{{$LDMongmatP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sMongmatP}}" name="mongmat_phai" style="width:100%">
	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDDongtuT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sDongtuT}}" name="dongtu_trai" style="width:100%" >
	</td>
	  <td class="adm_item"><nobr>{{$LDDongtuP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sDongtuP}}" name="dongtu_phai" style="width:100%">
	</td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDThuytinhtheT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sThuytinhtheT}}" name="thuytinhthe_trai" style="width:100%" >
	</td>
	  <td class="adm_item"><nobr>{{$LDThuytinhtheP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sThuytinhtheP}}" name="thuytinhthe_phai" style="width:100%">
	</td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDThuytinhdichT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sThuytinhdichT}}" name="thuytinhdich_trai" style="width:100%" >
	</td>
	  <td class="adm_item"><nobr>{{$LDThuytinhdichP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sThuytinhdichP}}" name="thuytinhdich_phai" style="width:100%">
	</td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDAnhdongtuT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sAnhdongtuT}}" name="anhdongtu_trai" style="width:100%" >
	</td>
	  <td class="adm_item"><nobr>{{$LDAnhdongtuP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sAnhdongtuP}}" name="anhdongtu_phai" style="width:100%">
	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDNhancauT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sNhancauT}}" name="nhancau_trai" style="width:100%" >
	 </td>
	  <td class="adm_item"><nobr>{{$LDNhancauP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sNhancauP}}" name="nhancau_phai" style="width:100%">
	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDHocmatT}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sHocmatT}}" name="hocmat_trai" style="width:100%" >
	</td>
	  <td class="adm_item"><nobr>{{$LDHocmatP}}</nobr></td>
     <td colspan=3>
			<input type="text" maxlength="100" value="{{$sHocmatP}}" name="hocmat_phai" style="width:100%">
	 </td>	
   </tr>
 
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDDate}}</td>
     <td colspan=3>	 
			<!-- gjergji : not needed anymore, since the new calendar 
				<input type='text' name='date' size=10 maxlength=10 {{$sDateValidateJs}}>-->		
			{{$sDate}}
	 </td>
  
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDBy}} </td>
     <td colspan=3>
	 		<input type='hidden' name='doctor_nr' value=''>
	 		<input type='text' name='doctor_name' style="width:90%;" maxlength=60 value='{{$sBy}}' readonly>
			<a href="javascript:popDocPer('doctor_nr')">
				<img width="16" height="16" border="0" src="../../gui/img/common/default/l-arrowgrnlrg.gif">
			</a>	
	 </td>
   </tr>

</table>


	{{$sHiddenInputs}}
	</form>

