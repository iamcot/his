<ul>
<div class="prompt">{{$FormTitle}}</div>

{{$sFormTag}}
	<table cellpadding="5"  border="0" cellspacing=1>
		<tr>
			<td colspan="2">
				{{$LDGroup}}:<br><p>

				<select name="combogroup" id="combogroup">
					{{foreach item=con from=$results}}
						<option value="{{$con.nr}}">{{$con.group_name}}</option> 
					{{/foreach}}
				</select>
			</td>
		<tr>
		<tr>
			<td bgcolor=#dddddd >
				{{$LDName}}:<br>
				<input type="text" name="LabTestName" size=30 ><p>
				{{$LDCode}}:<br>
				<input type="text" name="TestCode" size=30 ><br>
			</td>

			<td bgcolor=#dddddd >
				{{$LDPriceUnit}}:<br>
				<input type="text" name="LabPrice" size=30 ><p>
				{{$LDEnterValueDiscount}}:<br>
				<input type="text" name="Discount" size=30 value="0">
			</td>

		</tr>
	</table>
<p>
{{$sHiddenInputs}}
<p>
{{$pbSubmit}} {{$pbCancel}}
</form>

</ul>