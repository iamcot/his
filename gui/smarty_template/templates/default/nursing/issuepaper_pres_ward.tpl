				<tr {{$classtr}}>
					<td colspan="3"><b>{{$DeptName}}</b></td>
					<td>&nbsp;&nbsp;<a href="{{$forwardDeptNr}}">{{$imgselectDept}}</a></td>
				</tr>
			{{if $count}}
			{{foreach item=con from=$list_ward}}
				<tr bgcolor="ffffff">	
					<td>&nbsp;&nbsp;&nbsp;</td>
				    <td>{{$con.ward_id}}&nbsp;&nbsp;</td>
				    <td>{{$con.name}}</td>
					<td align="center">&nbsp;&nbsp;<a href="{{$fileforward}}{{$con.nr}}">{{$imgselect}}</a></td>
				</tr>	
			{{/foreach}}
			{{/if}}