<center>
<div class="prompt">{{$FormTitle}}</div>
<div style="float:left;margin-left:160px;">
<select  id="item" style="color:blue;" onclick="show()">
    <option value="*">All </option>
	{{$OptionLine}}
</select>
</div><br>
<p></p>


{{$sFormTag}}
	<table cellSpacing="1" cellPadding="3" bgColor="#999999" border="0" width="80%">
	 <tbody>
		<tr bgColor="#eeeeee">
			<th height="7" align="center" bgcolor="#CCCCCC">{{$LDCode}}</th>
			<th align="center" height="7" bgcolor="#CCCCCC">{{$LDName}}</th>
			<th height="7" align="center" bgcolor="#CCCCCC">{{$LDCostPerUnit}}</th>
			<th height="7" align="center" valign="middle" bgcolor="#CCCCCC">{{$LDDiscount}}</th>
		</tr>
		
		{{$ItemLine}}
		</table>
	 </tbody>
	</table>
<p>
{{$sHiddenInputs}}
<p>
{{$pbSubmit}} {{$pbCancel}}
</center>
</form>
</ul>

