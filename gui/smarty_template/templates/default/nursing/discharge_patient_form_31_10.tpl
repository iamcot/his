{{* discharge_patient_form.tpl : Discharge form 2004-06-12 Elpidio Latorilla *}}
{{* Note: never rename the input when redimensioning or repositioning it *}}

<ul>

<div class="prompt">{{$sPrompt}}</div>

<form action="{{$thisfile}}" name="discform" method="post" onSubmit="return pruf(this)">

	<table border=0 cellspacing="1">
		<tr>
			<td colspan=2 class="adm_input">
				{{$sBarcodeLabel}} {{$img_source}}
			</td>
		</tr>
		<tr>
			<td colspan=2>
				<table width="100%">
					<tr>
						<td class="adm_item" width="20%">{{$LDLocation}}</td>
						<td class="adm_item" width="25%">{{$sLocation}}</td>
												
						<td class="adm_item" width="5%">{{$LDDate}}:</td>
						<td class="adm_input" width="35%">
							{{if $released}}
								{{$x_date}}
							{{else}}
								{{$sDateInput}}
							{{/if}}
						</td>
						<td class="adm_item" width="7%">{{$LDClockTime}}:</td>
						<td class="adm_input" valign="top" width="20%">
							{{if $released}}
								{{$x_time}}
							{{else}}
								{{$sTimeInput}}
							{{/if}}
						</td>
					</tr>
				</table>
            </td>
		</tr>
		<tr>
			<td class="adm_item" width="30%">{{$LDType}}:</td>
                        <td class="adm_input">
						{{if $released}}
							{{$type}}
						{{else}}
                          {{$sType}}
                        {{/if}}                                 
			</td>
		</tr>
		{{if $bShowValidator}}
		<tr>
			<td class="adm_item">{{$pbSubmit}}</td>
			<td class="adm_input">{{$sValidatorCheckBox}} {{$LDYesSure}}</td>
		</tr>
	{{/if}}
		<tr>
			<td class="adm_item">{{$LDDauhieu}}:</td>
                        <td class="adm_input">
                            {{if $released}}
                                    {{$lamsang_notes}}
                            {{else}}
                                    {{$lamsang_Input}}
                            {{/if}}
			</td>
		</tr>
                <tr>
			<td class="adm_item">{{$LDChandoan}}:</td>
                        <td class="adm_input">
                            {{if $released}}
                                    {{$chandoan_notes}}
                            {{else}}
                                    {{$chandoan_Input}}
                            {{/if}}
			</td>
		</tr>
                <tr>
			<td class="adm_item">{{$LDTinhtrang}}:</td>
			<td class="adm_input">
                            {{if $released}}
                                    {{$tinhtrangraviennote}}
                            {{else}}
                                    {{$tinhtrang_Input}}
                            {{/if}}
			</td>
		</tr>
		
		{{if ($released && $discharged_type == 2) || (!$released)}}
        <tr  group="cv" >
			<td class="adm_item">{{$LDLydoChuyenVien}}:</td>
			<td class="adm_input">
				 {{if $released}}
				{{$LydoChuyenVien_note}}
				{{else}}
				{{$sLyDoChuyenVien}}
				 {{/if}}
			</td>
		</tr>		
		
		<tr  group="cv" >
			<td class="adm_item">Nơi đến</td>
			<td class="adm_input">
				{{if $released}}
				{{$noichuyen_note}}
				{{else}}
				{{$noichuyen_input}}
				 {{/if}}
			</td>
		</tr>
        <tr  group="cv" >
			<td class="adm_item">{{$LDPhuongtien}}:</td>
			<td class="adm_input">
                            {{if $released}}
                                    {{$phuongtien_notes}}
                            {{else}}
                                    {{$phuongtien_Input}}
                            {{/if}}
			</td>
		</tr>
		<tr  group="cv" >
			<td class="adm_item">{{$LDNotes}}:</td>
			<td class="adm_input">
				{{if $released}}
					{{$info}}
				{{else}}
					{{$Nguoiduadi_Input}}
                                        <br/>
                                        {{$IMG_ADD}}
                                        {{$IMG_CLEAR}}
				{{/if}}
			</td>
		</tr>
		<tr  group="cv" >
			<td class="adm_item">{{$LDNurse}}:</td>
			<td class="adm_input">
				{{if $released}}
					{{$encoder}}
				{{else}}
					<input type="text" name="encoder" size=25 maxlength=30 value="{{$encoder}}">
				{{/if}}
			</td>
		</tr>
	{{/if}}
	
	
	</table>

	{{$sHiddenInputs}}

</form>

{{$pbCancel}}
{{if $released}}
    {{$Print}}
{{/if}}
</ul>

