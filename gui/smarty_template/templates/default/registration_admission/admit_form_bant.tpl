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
					<td  class="adm_item" style="width:20%;">
						{{$LDCaseNr}}
					</td>
					<td  class="adm_input" style="width:30%;">
						{{$encounter_nr}}
						
						
					</td>
					<td class="adm_input" style="width:20%;">
					{{$sEncBarcode}} {{$sHiddenBarcode}}
					</td>
					<td {{$sRowSpan}} align="center" class="photo_id" style="width:30%;">
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
					<td class="adm_item">
						{{$LDTitle}}:&nbsp;{{$title}}
					</td>
					
					
					<td  bgcolor="#ffffee" class="vi_data">
						{{$name_last}}&nbsp;{{$name_first}}&nbsp;{{$sCrossImg}}
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
						{{$addr_str_nr}}&nbsp;{{$addr_str}}  {{$addr_citytown_name}}
						
					</td>
				</tr>
				<tr>
				<td class="adm_item" >
						{{$LDRecBy}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$referrer_name}}
					</td>
					
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDDiagnosis}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$referrer_diagnosis}}
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
						{{$LDKhambenhtt}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$khambenhtoanthan}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDKhambenhbp}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$khambenhbophan}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDKetqualamsang}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$ketquacanlamsang}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDChandoanbenhchinh}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$benhchinh}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDChandoanbenhphu}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$benhphu}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDTinhtrangravien}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$tinhtrangravien}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDHuongdieutri}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$huongdieutritiep}}
					</td>
				</tr>
				{{*********************************************************
				<!-- The insurance class  -->
				<tr>
					<td class="adm_item">
						{{$LDBillType}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sBillTypeInput}}
					</td>
				</tr>

				<tr>
					<td class="adm_item">
						{{$LDInsuranceNr}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$insurance_nr}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDInsuranceStart}}:
					</td>
					<td colspan=2 class="adm_input">
					{{$sInsStartDay}}{{$sInsStartDayInput}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDInsuranceExp}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$sInsExpDay}}{{$sInsExpDayInput}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDNoicap}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$insurance_loca}}
					</td>
				</tr>
				<tr>
					<td class="adm_item">
						{{$LDInsuranceCo}}:
					</td>
					<td colspan=2 class="adm_input">
						{{$insurance_firm_name}}
					</td>
				</tr>
				*********************************************************}}
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
						{{$LDAdmitBy}}:
					</td>
					<td colspan=3 class="adm_input">
						{{$encoder}}
					</td>
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
	<script>
		jQuery(function($){
			$("#f-calendar-field-1").mask("99/99/9999");
			$("#inputisurance").mask("**-*-**-**-***-*****");
			});
	</script>
