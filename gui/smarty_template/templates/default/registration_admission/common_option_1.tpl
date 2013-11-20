{{* Frame template for displaying admission data *}}
{{* Used by  *}}
{{* Elpidio Latorilla 2004-06-07 *}}

<table width="100%" cellspacing="0" cellpadding="0">
  <tbody>

    <tr>
      <td>
			<table cellspacing="0" cellpadding="0" width="900">
			<tbody>
				<tr valign="top">
					<td width="700">
						{{include file="registration_admission/basic_data_2.tpl"}}

						{{$sOptionBlock}}
					
					</td>
					<td>{{$sOptionsMenu}}</td>
				</tr>
			</tbody>
			</table>
	  </td>
    </tr>

	<tr>
      <td valign="top">
	  	{{$sBottomControls}} {{$pbPersonData}} {{$pbAdmitData}} {{$pbMakeBarcode}} {{$pbMakeWristBands}} {{$pbBottomClose}}
	</td>
    </tr>

    <tr>
      <td>
	  	&nbsp;
		<br>
	  	{{$sAdmitLink}}
		<br>
		{{$sSearchLink}}
		<br>
		{{$sArchiveLink}}
	</td>
    </tr>

  </tbody>
</table>
