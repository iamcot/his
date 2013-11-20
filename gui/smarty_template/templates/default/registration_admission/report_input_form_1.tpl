{{* Template for the new report entry form *}}

{{if $bSetAsForm}}
<form method="post" name="notes_form" onSubmit="return chkform(this)">
{{/if}}

<table border=0 cellpadding=2 width=100%>
	<tr>
     <td>{{$LDDate}}</td>
     <td>
	 	{{$sDateInput}} {{$sDateMiniCalendar}}
	 </td>
   </tr>

   <tr>
     <td>{{$LDNotes1}}</td>
     <td>{{$sNotesInput}}</td>
   </tr>
   <tr>
     <td>{{$LDShortNotes1}}</td>
     <td>{{$sShortNotesInput}}</td>
   </tr>
   <tr>
     <td>{{$LDNextTreatment}}</td>
     <td>{{$sNextTreatmentInput}}</td>
   </tr>
   <tr>
     <td>{{$LDBy2}}</td>
     <td>{{$sPersonDecisionInput}}</td>
   </tr>
   <tr>
     <td>{{$LDMember}}</td>
     <td>{{$sListMemberInput}}</td>
   </tr>
   <tr>
     <td>{{$LDBy1}}</td>
     <td>{{$sAuthorInput}}</td>
   </tr>
 </table>

{{if $bSetAsForm}}
	{{$sHiddenInputs}}
	{{$pbSubmit}}
</form>
{{/if}}