{{* Template for admission input and data display *}}
{{* Files using this: *}}
{{* - /modules/registration_admission/aufnahme_start.php *}}
{{* - /modules/registration_admission/aufnahme_daten_zeigen.php *}}

	{{if $bSetAsForm}}
	<form method="post" action="{{$thisfile}}" name="aufnahmeform" onSubmit="return chkform(this)"  ENCTYPE="multipart/form-data">
	{{/if}}
		
		<table border="0" cellspacing=1 cellpadding=0 width="100%">

		{{if $error}}
				<tr>
					<td colspan=4 class="warnprompt">
						<center>
						{{$sMascotImg}}
						{{$LDError}}
						</center>
					</td>
				</tr>
		{{/if}}

		{{if $is_discharged}}
				<tr>
					<td bgcolor="red" colspan="3">
						&nbsp;
						{{$sWarnIcon}}
						<font color="#ffffff">
						<b>
						{{$sDischarged}}
						</b>
						</font>
					</td>
				</tr>
		{{/if}}

				<tr>
					<td  class="adm_item" >
						{{$LDCaseNr}}
					</td>
					<td colspan=2 class="adm_input">
						{{$encounter_nr}}&nbsp;&nbsp;
						
						{{$sEncBarcode}} {{$sHiddenBarcode}}
					</td>
					
					<td {{$sRowSpan}} align="center" class="photo_id">
						{{$img_source}}	
						<!--  gjergji -->
						<br> 
						{{$sFileBrowserInput}}
						<!--  end : gjergji -->	
					</td>
				</tr>

				<tr>
					<td  class="adm_item">
						{{$LDAdmitDate}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sAdmitDate}}&nbsp;{{$sAdmitTime}}
					</td>
				</tr>
			
				<tr>
                    <td  class="adm_item">
                        {{$LDInDate}}:
                    </td>
                    <td colspan=2 class="adm_input">
                        {{$sInDate}} &nbsp; {{ $sInTime}}
                    </td>

                   {{*
                    <td  class="adm_item" >

                    {{$LDInputDate}}
                    </td>
                    <td class="adm_input">
                    {{$sInputDate}}&nbsp;{{$sInputTime}}
                    </td>    *}}

				</tr>
				

				<tr>
					<td class="adm_item">
						{{$LDTitle}}:&nbsp;{{$title}}
					</td>
					
					
					<td  bgcolor="#ffffee" class="vi_data"><b>
						{{$name_last}}</b> &nbsp;{{$name_first}} &nbsp;{{$sCrossImg}}
					</td>
					{{if $LDBloodGroup}}
				
					
					<td bgcolor="#ffffee" class="vi_data" >
						{{$LDBloodGroup}}:&nbsp;{{$blood_group}}
					</td>
				
			{{/if}}
				</tr>

				

				

			{{if $name_2}}
				<tr>
					<td class="adm_item">
						{{$LDName2}}:
					</td>
					<td bgcolor="#ffffee">
						{{$name_2}}
					</td>
				</tr>
			{{/if}}

			{{if $name_3}}
				<tr>
					<td class="adm_item">
						{{$LDName3}}:
					</td>
					<td bgcolor="#ffffee">
						{{$name_3}}
					</td>
				</tr>
			{{/if}}

			{{if $name_middle}}
				<tr>
					<td class="adm_item">
						{{$LDNameMid}}:
					</td>
					<td bgcolor="#ffffee">
						{{$name_middle}}
					</td>
				</tr>
			{{/if}}

				<tr>
					<td class="adm_item">
						{{$LDBday}}:
					</td>
					<td bgcolor="#ffffee" class="vi_data">
						{{$sBdayDate}} &nbsp; {{$sCrossImg}} &nbsp; <font color="black">{{$sDeathDate}}</font>
					</td>
					<td bgcolor="#ffffee" class="vi_data">
						{{$LDSex}}: {{$sSexType}}
					</td>
				</tr>

			

				<tr colspan=4>
					<td class="adm_item">
						{{$LDAddress}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$addr_str_nr}}&nbsp;{{$addr_str}} {{$addr_phuongxa}} {{$addr_quanhuyen}} {{$addr_citytown}}
						
					</td>
				</tr>
				<tr>					
					<td class="adm_item">
						<font color="red">{{$LDAdmitClass}}</font>:
					</td>
					<td class="adm_input">
						{{$sAdmitClassInput}}
					</td>
					<td class="adm_input">
						{{$sSelectKham}}
					</td>
				</tr>
				{{*
				<tr colspan=4>
					<td class="adm_item">
						<font color="red">{{$LDAdmitShowTypeInput}}</font>:
					</td>
					<td class="adm_input">
						{{$sAdmitShowTypeInput}}
					</td>
					<td colspan=2 class="adm_input">
					{{if $LDShowTriageData}}
						
						{{$sAdmitTriage}}
					{{else}}
						<label class="triageWhite"><input type = 'radio' name ='triage' value='white'>{{$sAdmitTriageWhite}}</label>
						<label class="triageGreen"><input type = 'radio' name ='triage' value='green'>{{$sAdmitTriageGreen}}</label>
						<label class="triageYellow"><input type = 'radio' name ='triage' value='yellow'>{{$sAdmitTriageYellow}}</label>
						<label class="triageRed"><input type = 'radio' name ='triage' value='red'>{{$sAdmitTriageRed}}</label>
					{{/if}}					
					</td>
					
				</tr>
				*}}
			
				
				<tr >
					<td class="adm_item" style="width:20%;">
						<font color="red">{{$LDDepartment}}</font>:
					</td>
					<td class="adm_input" style="width:30%;">
						{{$sDeptInput}}
					</td>
					<td class="adm_item" style="width:20%;">
						<font color="red">{{$LDWard}}</font>:
					</td>
					<td class="adm_input" id="ward" style="width:30%;">
						{{$sWardInput}}
					</td>
				</tr>
					
				<tr>
					<td class="adm_item">
						{{$LDSpecials}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$referrer_notes}}
					</td>
				</tr>		
				<tr>
					<td class="adm_item">
						{{$LDLidovaovien}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$lidovaovien}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDQuatrinhbenhly}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$quatrinhbenhly}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDDiagnosis}}:
					</td>
					<td  colspan=3 class="adm_input">
						{{$referrer_diagnosis}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDBenhphu}}:
					</td>
					<td  colspan=3 class="adm_input">
						{{$benhphu}}
					</td>
				</tr>			
				<tr>
					<td class="adm_item">
						{{$LDTienluong}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$tienluong}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDTTBA}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$ttba}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDTherapy}}:
					</td>
					<td colspan=3 class="adm_input">
						{{ $referrer_recom_therapy}}
					</td>
				</tr>	
				<!-- The insurance class  -->
				
				
				
			{{if $LDCareServiceClass}}
				<tr>
					<td class="adm_item">
						{{$LDCareServiceClass}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sCareServiceInput}} {{$LDFrom}} {{$sCSFromInput}} {{$LDTo}} {{$sCSToInput}} {{$sCSHidden}}
					</td>
				</tr>
			{{/if}}

			{{if $LDRoomServiceClass}}
				<tr>
					<td class="adm_item">
						{{$LDRoomServiceClass}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sCareRoomInput}} {{$LDFrom}} {{$sRSFromInput}} {{$LDTo}} {{$sRSToInput}} {{$sRSHidden}}
					</td>
				</tr>
			{{/if}}
			
			{{if $LDAttDrServiceClass}}
				<tr>
					<td class="adm_item">
						{{$LDAttDrServiceClass}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sCareDrInput}} {{$LDFrom}} {{$sDSFromInput}} {{$LDTo}} {{$sDSToInput}} {{$sDSHidden}}
					</td>
				</tr>
			{{/if}}

			{{if $LDAdmitBillItem}}
				<tr>
					<td class="adm_item">
						{{$LDAdmitBillItem}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sAdmitBillItem}} {{$sBIFromInput}} {{$sBIHidden}} 
					</td>
				</tr>
			{{/if}}
			
			{{if $LDAdmitDoctorRefered}}
				<tr>
					<td class="adm_item">
						{{$LDAdmitDoctorRefered}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sAdmitDoctorRefered}} {{$sRefDrFromInput}} {{$sRefDrHidden}} 
					</td>
				</tr>
			{{/if}}
				<tr>
					<td class="adm_item">
						{{$LDRecBy}}:
					</td>
					<td class="adm_input">
						{{$referrer_name}}
					</td>
				
					<td class="adm_item">
						{{$LDReferrDoc}}:
					</td>
					<td class="adm_input">
						{{$doctor_name}}
					</td>
				</tr>
				<tr>				
					<td class="adm_input" >
						{{$sCanBoTrungCao}}
					</td>
					<td class="adm_input">
						{{$sTNGT}}
					</td>
					<td class="adm_item">
						{{$LDAdmitBy}}:
					</td>
					<td class="adm_input">
						{{$encoder}}
					</td>
				</tr>
				<tr>
                    <td class="adm_input">
                        {{$LDCbtcinsur}}
                    </td>
                    <td class='adm_input'>
                        {{$cbtcinsur}}
                </tr>
				{{$sHiddenInputs}}

				<tr>
					<td colspan="3">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td>
						{{$pbSave}}
					</td>
					<td align="right">
						{{$pbRefresh}} {{$pbRegData}}
					</td>
					<td align="right">
						{{$pbCancel}}
					</td>
				</tr>

		</table>
	
			{{$sErrorHidInputs}}
			{{$sUpdateHidInputs}}

	{{if $bSetAsForm}}
	</form>
	{{/if}}

	{{$sNewDataForm}}
	<p>
	