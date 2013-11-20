	{{if $bShow}}
	<tr {{$sRowClass}}>
		<td colspan="2">
			<table width="100%" cellpadding="1" cellspacing="1">
				<tr><td colspan="2" align="center"><b>{{$LDPhauThuat}}</b>&nbsp;<input type="checkbox" name="cb_pt" {{$cb_pt}} DISABLED></td>
					<td colspan="2"><b>{{$LDThuThuat}}</b>&nbsp;<input type="checkbox" name="cb_tt" {{$cb_tt}} DISABLED></td></tr>
				<tr align="center" >
					<td><i>{{$LDNgayGio}}</i></td><td><i>{{$LDPhauThuatVoCam}}</i></td>
					<td><i>{{$LDBacSyPT}}</i></td><td><i>{{$LDBacSyGM}}</i></td>				
				</tr>
				{{$sSurgery}}
			</table>
			<br>			
		</td>	
	</tr>
	{{/if}}