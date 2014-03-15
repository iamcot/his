<center>
<div class="prompt">{{$FormTitle}}</div>
<div style="float:left;margin-left:160px;">
{{if $service}}
<select  id="item" style="color:blue;" onclick="show()">
	{{$OptionLine}}
</select>
{{/if}}
</div><br>
<p></p>
{{$sFormTag}}
	<table cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
	 <tbody>
		<tr bgColor="#eeeeee">
		{{if $itemID}}
			<th height="7" align="center" width="5%" bgcolor="#CCCCCC"></th>
		{{/if}}
			<th height="7" align="center" width="65%" bgcolor="#CCCCCC">{{$LDTestName}}</th>
			<th align="center" height="7" width="10%" bgcolor="#CCCCCC">{{$LDTestCode}}</th>
			<th height="7" align="center" width="15%" bgcolor="#CCCCCC">{{$LDCostperunit}}</th>
			<th height="7" align="center" valign="middle" bgcolor="#CCCCCC">{{$LDNumberofUnits}}</th>
		</tr>
		
		{{$ItemLine}}
			
	 </tbody>
	 <tr><td><center>
	 	{{$sHiddenInputs}}
{{$pbSubmit}} {{$pbCancel}}
</center></td>
<p>
</tr>
	</table>



</center>
</form>
</ul>