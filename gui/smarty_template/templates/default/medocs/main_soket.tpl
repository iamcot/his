{{* Frame template of medocs page *}}
{{* Note: this template uses a template from the /registration_admission/ *}}

<table width="100%" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>{{include file="medocs/tabs.tpl"}}</td>
    </tr>

    <tr>
      <td>

		<table width="900" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td>
					{{include file="registration_admission/basic_data.tpl"}}
				</td>
				<td valign="top">{{$sOptionsMenu}}</td>
			</tr>

			<tr>
				<td>
					{{if $bShowNoRecord}}
						{{include file="registration_admission/common_norecord.tpl"}}
					{{else}}
						{{include file=$sDocsBlockIncludeFile}}
					{{/if}}
	  			</td>
				<td></td>
    		</tr>
		</tbody>
		</table>

	  </td>
    </tr>

	<tr>
      <td>	<br>
			{{$sUpdateLinkIcon}} {{$sUpdateRecLink}}<p>
			{{$sNewLinkIcon}} {{$sNewRecLink}}<p>
			{{$sPdfLinkIcon}} {{$sMakePdfLink}}<p>
			{{$sListLinkIcon}} {{$sListRecLink}}<p>
			{{$pbBottomBack}}{{$pbBottomClose}}
	  </td>
    </tr>

  </tbody>
</table>
