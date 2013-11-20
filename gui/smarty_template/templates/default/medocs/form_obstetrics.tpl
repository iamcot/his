{{* Template for medocs (medical diagnosis/therapy record) *}}
{{* Note: the input tags are left here in raw form to give the GUI designer freedom to change  the input dimensions *}}
{{* Note: be very careful not to rename nor change the type of the input  *}}

{{if $bSetAsForm}}
	{{$sDocsJavaScript}}
	<form method="post" name="entryform" onSubmit="return chkForm(this)">
{{/if}}

<table border=0 cellpadding=2 width=100%>
    <tr bgcolor='#adadad'>
        <td colspan=2>
            <font style="font-weight:bold;color:blue;">1.{{$LDToanthan}}</font>
        </td>    
    </tr>
    <tr bgcolor='#f6f6f6'>
        <td style="padding-left:15px;">
            <table>
                <tr>
                    <td>
                        {{$LDToantrang}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{$LDPhu}}
                    </td>
                </tr>
            </table>            
        </td>
        <td>

        {{if $bSetAsForm}}
            <textarea name='toanthan_notes' cols=60 rows=2 wrap='physical'></textarea>
        {{else}}
            {{$sToanthanNotes}}
        {{/if}}
        </td>
    </tr>
    <tr bgcolor='#f6f6f6'>
        <td style="padding-left:15px;">{{$LDTuanhoan}}</td>
        <td>

            {{if $bSetAsForm}}
                    <textarea name='tuanhoan_notes' cols=60 rows=2 wrap='physical'></textarea>
            {{else}}
                    {{$sTuanhoanNotes}}
            {{/if}}

        </td>        
    </tr>
    <tr bgcolor='#f6f6f6'>
        <td style="padding-left:15px;">  {{$LDHohap}}</td>
        <td>
            {{if $bSetAsForm}}
                    <textarea name='hohap_notes' cols=60 rows=2 wrap='physical'></textarea>
            {{else}}
                    {{$sHohapNotes}}
            {{/if}}
        </td>
    </tr>
    <tr bgcolor='#f6f6f6'>
        <td style="padding-left:15px;">  {{$LDTieuhoa}}</td>
        <td>
        {{if $bSetAsForm}}
                <textarea name='tieuhoa_notes' cols=60 rows=2 wrap='physical'></textarea>
        {{else}}
                {{$sTieuhoaNotes}}
        {{/if}}
        </td>
    </tr>
    <tr bgcolor='#f6f6f6'>
        <td style="padding-left:15px;">  {{$LDTietNieu}}</td>
        <td>

        {{if $bSetAsForm}}
                <textarea name='thantietnieusinhduc_notes' cols=60 rows=4 wrap='physical'></textarea>
        {{else}}
                {{$sThantietnieusinhducNotes}}
        {{/if}}

        </td>
    </tr>
    <tr bgcolor='#f6f6f6'>
        <td style="padding-left:15px;">  {{$LDKhac}}</td>
        <td>

        {{if $bSetAsForm}}
                <textarea name='khac_notes' cols=60 rows=4 wrap='physical'></textarea>
        {{else}}
                {{$sKhacNotes}}
        {{/if}}

        </td>
    </tr>
   <tr bgcolor='#f6f6f6'>
     <td style="padding-left:15px;">
         <font  color='red'>(*)</font>  {{$LDDate}}</td>
     <td>
	 
	 	{{if $bSetAsForm}}
			<!-- gjergji : not needed anymore, since the new calendar 
				<input type='text' name='date' size=10 maxlength=10 {{$sDateValidateJs}}>-->
			{{$sDateMiniCalendar}}
		{{else}}
			{{$sDate}}
		{{/if}}

	 </td>
   </tr>
   <tr bgcolor='#f6f6f6'>
     <td style="padding-left:15px;">
         <font  color='red'>(*)</font>  {{$LDBy}} </td>
     <td>

	 	{{if $bSetAsForm}}
	 		<input type='text' name='personell_name' size=50 maxlength=60 value='{{$TP_user_name}}' readonly>
		{{else}}
			{{$sAuthor}}
		{{/if}}

	 </td>
   </tr>
</table>

{{if $bSetAsForm}}
	{{$sHiddenInputs}}
	</form>
{{/if}}
