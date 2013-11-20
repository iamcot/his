{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}

{{if $bSetAsForm}}
	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">
{{/if}}
<br/>
<table border=1 cellpadding=2 width=100%>
	
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDThiluckokinhT}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="thiluc_khongkinh_trai" size=10>
		{{else}}
			{{$sThiluckokinhT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDThiluckokinhP}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="thiluc_khongkinh_phai" size=10>
		{{else}}
			{{$sThiluckokinhP}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDThiluccokinhT}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="thiluc_cokinh_trai" size=10>
		{{else}}
			{{$sThiluccokinhT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDThiluccokinhP}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="thiluc_cokinh_phai" size=10>
		{{else}}
			{{$sThiluccokinhP}}
		{{/if}}

	 </td>
   </tr>
    <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDNhanapT}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="nhanap_trai" size=10>
		{{else}}
			{{$sNhanapT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDNhanapP}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="nhapap_phai" size=10>
		{{else}}
			{{$sNhanapP}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDThitruongT}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="thitruong_trai" size=10>
		{{else}}
			{{$sThitruongT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDThitruongP}}</nobr></td>
     <td>

	 	{{if $bSetAsForm}}
			<input type="text" value="" name="thitruong_phai" size=10>
		{{else}}
			{{$sThitruongP}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDLedaoT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="ledao_trai" style="width:100%" >
		{{else}}
			{{$sLedaoT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDLedaoP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="ledao_phai" style="width:100%">
		{{else}}
			{{$sLedaoP}}
		{{/if}}

	 </td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDMimatT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="mimat_trai" style="width:100%" >
		{{else}}
			{{$sMimatT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDMimatP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="mimat_phai" style="width:100%">
		{{else}}
			{{$sMimatP}}
		{{/if}}

	 </td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDKetmacT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="ketmac_trai" style="width:100%" >
		{{else}}
			{{$sKetmacT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDKetmacP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="ketmac_phai" style="width:100%">
		{{else}}
			{{$sKetmacP}}
		{{/if}}

	 </td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDMathotT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="mathot_trai" style="width:100%" >
		{{else}}
			{{$sMathotT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDMathotP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="mathot_phai" style="width:100%">
		{{else}}
			{{$sMathotP}}
		{{/if}}

	 </td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDGiacmacT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="giacmac_trai" style="width:100%" >
		{{else}}
			{{$sGiacmacT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDGiacmacP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="giacmac_phai" style="width:100%">
		{{else}}
			{{$sGiacmacP}}
		{{/if}}

	 </td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDCungmacT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="cungmac_trai" style="width:100%" >
		{{else}}
			{{$sCungmacT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDCungmacP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="cungmac_phai" style="width:100%">
		{{else}}
			{{$sCungmacP}}
		{{/if}}

	 </td>
	
   </tr>
      <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDTienphongT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="tienphong_trai" style="width:100%" >
		{{else}}
			{{$sTienphongT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDTienphongP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="tienphong_phai" style="width:100%">
		{{else}}
			{{$sTienphongP}}
		{{/if}}

	 </td>
	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDMongmatT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="mongmat_trai" style="width:100%" >
		{{else}}
			{{$sMongmatT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDMongmatP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="mongmat_phai" style="width:100%">
		{{else}}
			{{$sMongmatP}}
		{{/if}}

	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDDongtuT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="dongtu_trai" style="width:100%" >
		{{else}}
			{{$sDongtuT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDDongtuP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="dongtu_phai" style="width:100%">
		{{else}}
			{{$sDongtuP}}
		{{/if}}

	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDThuytinhtheT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="thuytinhthe_trai" style="width:100%" >
		{{else}}
			{{$sThuytinhtheT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDThuytinhtheP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="thuytinhthe_phai" style="width:100%">
		{{else}}
			{{$sThuytinhtheP}}
		{{/if}}

	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDThuytinhdichT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="thuytinhdich_trai" style="width:100%" >
		{{else}}
			{{$sThuytinhdichT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDThuytinhdichP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="thuytinhdich_phai" style="width:100%">
		{{else}}
			{{$sThuytinhdichP}}
		{{/if}}

	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDAnhdongtuT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="anhdongtu_trai" style="width:100%" >
		{{else}}
			{{$sAnhdongtuT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDAnhdongtuP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="anhdongtu_phai" style="width:100%">
		{{else}}
			{{$sAnhdongtuP}}
		{{/if}}

	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDNhancauT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="nhancau_trai" style="width:100%" >
		{{else}}
			{{$sNhancauT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDNhancauP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="nhancau_phai" style="width:100%">
		{{else}}
			{{$sNhancauP}}
		{{/if}}

	 </td>	
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><nobr>{{$LDHocmatT}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="hocmat_trai" style="width:100%" >
		{{else}}
			{{$sHocmatT}}
		{{/if}}

	 </td>
	  <td class="adm_item"><nobr>{{$LDHocmatP}}</nobr></td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type="text" maxlength="100" value="" name="hocmat_phai" style="width:100%">
		{{else}}
			{{$sHocmatP}}
		{{/if}}

	 </td>	
   </tr>
 
   <tr bgcolor='#f6f6f6'>
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDDate}}</td>
     <td colspan=3>
	 
	 	{{if $bSetAsForm}}
			<!-- gjergji : not needed anymore, since the new calendar 
				<input type='text' name='date' size=10 maxlength=10 {{$sDateValidateJs}}>-->
			{{$sDateMiniCalendar}}
		{{else}}
			{{$sDate}}
		{{/if}}

	 </td>
  
     <td class="adm_item"><FONT  color='red'>*</font>  {{$LDBy}} </td>
     <td colspan=3>

	 	{{if $bSetAsForm}}
			<input type='hidden' name='doctor_nr' value=''>
	 		<input type='text' name='doctor_name' style="width:90%;" maxlength=60 value='' readonly>
			<a href="javascript:popDocPer('doctor_nr')">
				<img width="16" height="16" border="0" src="../../gui/img/common/default/l-arrowgrnlrg.gif">
			</a>
			
		{{else}}
			{{$sBy}}
		{{/if}}

	
   </tr>
 
</table>

{{if $bSetAsForm}}
	{{$sHiddenInputs}}
	</form>
{{/if}}
