{{* new template *}}
<table border=0 cellspacing=0 cellpadding=0 style="width:800px" class="regformshow">
<tbody>
{{if $error}}
<tr>
    <td colspan="4" class="adm_item" style="background-color:red;">
        {{$LDexp}}
    </td>
</tr>
{{/if}}
<tr>
    <td class="adm_item"  >
        {{$LDRegistryNr}}
    </td>
    <td class="adm_input vertop" >
        {{$pid}}
        <br>
        {{$sBarcodeImg}}
    </td>
    <td {{$sPicTdRowSpan}} class="photo_id" align="center" style="width:330px;" colspan=2>
    <a href="#"  onClick="showpic(document.aufnahmeform.photo_filename)"><img {{$img_source}} name="headpic"></a>
    <br>
    {{$LDPhoto}}
    <br>
    {{$sFileBrowserInput}}
    </td>
</tr>

<tr>
    <td  class="adm_item" >

        {{$LDRegDate}}
    </td>
    <td class="adm_input">
        {{$sRegDate}}&nbsp;{{$sRegTime}}
    </td>
</tr>



{{* The following tags contain rows patterned after the  "registration_admission/reg_row.tpl" template *}}

<tr>
    <td class="adm_item">
        {{$LDFullName}}
    </td>
    <td class="adm_input showname">
        {{$sNameLast}}
        {{$sNameFirst}}
    </td>
</tr>

<tr>
    <td class="adm_item">{{$scheckbox}}</td>
    <td class="adm_item">
        {{$sInputTuoi}}{{$sInputThang}}
        {{$LDBday}}
        {{$sBdayInput}}&nbsp;<br>{{$sCrossImg}} {{$sDeathDate}}
    </td>
</tr>




<tr>
    <td class="adm_item">
        {{$LDBloodGroup}}&nbsp;
    </td>
    <td class="adm_input">{{$sBGInput}}</td>
</tr>

<tr>
    <td class="adm_item">
        {{$LDSex}}&nbsp;
    </td>
    <td class="adm_input">{{$sSex}}</td>

    <td class="adm_item"  style="width: 150px;">
        {{$LDCivilStatus}}
    </td>
    <td class="adm_input">
        {{$sCSInput}}
    </td>
</tr>
<tr>
    <td class="adm_item" >
        {{$LDCitizenship}}
    </td>
    <td  class="adm_input" >{{$sCitizenship}}</td>

    <td class="adm_item">
        {{$LDEthnicOrig}}
    </td>
    <td  class="adm_input" >{{$sEthnicOrigInput}} {{$sEthnicOrigMiniCalendar}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDNatIdNr}}
    </td>
    <td class="adm_input">{{$sNatIdNr}}</td>
    <td  class="adm_item" ></td><td  class="adm_item" ></td>
</tr>
<tr>
    <td colspan=4 class="adm_item">
        {{$LDAddress}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDTownCity}}
    </td>
    <td class="adm_input">
        {{$sTownCityInput}}
    </td>
    <td class="adm_item">
        {{$LDZipCode}}
    </td>
    <td class="adm_input">
        {{$sZipCodeInput}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDHuyenxa}}
    </td>
    <td class="adm_input" id="quanhuyen">
        {{$sHuyenxaInput}}
    </td>
    <td class="adm_item" >
        {{$LDThonPhuong}}
    </td>
    <td class="adm_input"id="xaphuong">
        {{$sThonPhuongInput}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDStreet}}
    </td>
    <td class="adm_input">
        {{$sStreetInput}}
    </td>
    <td class="adm_item">
        {{$LDStreetNr}}
    </td>
    <td class="adm_input">
        {{$sStreetNrInput}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDBillType}}:
    </td>
    <td class="adm_input">
        {{$sBillTypeInput}}
    </td>
    {{if $is_bh}}
    <td class="adm_item" id="box">
        {{$LDInsuranceCo}}:
    </td>
    <td class="adm_input" id="box1">
        {{$insurance_firm_name}}
    </td>

</tr>

<tr>
    <td class="adm_item" >
        {{$LDInsuranceNr}}:
    </td>
    <td class="adm_input">
        {{$insurance_nr}}
    </td>
    <td class="adm_item">
        {{$LDInsuranceStart}}:
    </td>
    <td class="adm_input">
        {{$sInsStartDayInput}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDNoicap}}:
    </td>
    <td class="adm_input">
        {{$insurance_loca}}
    </td>

    <td class="adm_item">
        {{$LDInsuranceExp}}:
    </td>
    <td class="adm_input">
        {{$sInsExpDayInput}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDMaDKKCB}}:
    </td>
    <td class="adm_input">
        {{$madk_kcbbd}}
    </td>

    <td class="adm_item">
        {{$LDTinhtrang}}
    </td>
    <td class="adm_input">
        {{$sTinhtrangInput}}
    </td>
    {{/if}}
</tr>

{{* The following tags contain rows patterned after the  "registration_admission/reg_row.tpl" template *}}

<tr>
    <td class="adm_item">
        {{$LDPhone1}}
    </td>
    <td class="adm_input">
        {{$sPhone1}}
    </td>
    <td class="adm_item">
        {{$LDCellPhone1}}
    </td>
    <td class="adm_input">
        {{$sCellPhone1}}
    </td>
</tr>
{{* remove 0410 - cot
<tr>
    <td class="adm_item">
        {{$LDFax}}
    </td>
    <td class="adm_input">
        {{$sFax}}
    </td>
    <td class="adm_item">
        {{$LDEmail}}
    </td>
    <td class="adm_input">
        {{$sEmail}}
    </td>
</tr>
<tr>
    <td class="adm_item" >
        {{$LDSSSNr}}
    </td>
    <td class="adm_input">
        {{$sSSSNr}}
    </td>
    <td class="adm_item">
        {{$LDReligion}}
    </td>
    <td class="adm_input">
        {{$sReligion}}
    </td>
</tr>
*}}
<tr>

    <td class="adm_item">
        {{$LDnghenghiep}}
    </td>
    <td  class="adm_input">
        {{$sNghenghiep}}
    </td>
    <td class="adm_item">
        {{$LDnoilamviec}}
    </td>
    <td class="adm_input">
        {{$sNoilamviec}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDTSBenhCN}}
    </td>
    <td class="adm_input">
        {{$sTSBenhCN}}
    </td>
    <td class="adm_item">
        {{$LDTSBenhGD}}
    </td>
    <td class="adm_input">
        {{$sTSBenhGD}}
    </td>
</tr>
<tr>
    <td class="adm_item">
        {{$LDhotenbaotin}}
    </td>
    <td class="adm_input">
        {{$sHotenbaotin}}
    </td>

    <td class="adm_item">
        {{$LDdtbaotin}}
    </td>
    <td class="adm_input">
        {{$sDTbaotin}}
    </td>
</tr>
{{if $bShowOtherHospNr}}
<tr>
    <td class="adm_item" valign=top class="adm_input">
        {{$LDOtherHospitalNr}}
    </td>
    <td class="adm_input">
        {{$sOtherNr}}
        {{$sOtherNrSelect}}
    </td>
    <td colspan=2 class="adm_input" >
        ({{$LDSelectOtherHospital}} - {{$LDNoNrNoDelete}})
    </td>
</tr>
{{/if}}

<tr>
    <td class="adm_item">
        {{$LDRegBy}}
    </td>
    <td class="adm_input">
        {{$sRegByInput}}
    </td>
</tr>
</tbody>
</table>