{{* ward_occupancy_list.tpl  2004-05-15 Elpidio Latorilla *}}
{{* Table frame for the occupancy list *}}
<form method="post" action="">
<input type="radio" name="typedate" value="currentdate"> Ngày hiện tại <input type="radio" name="typedate" value="encdate"> Ngày nhập viện <input type="submit" name="DischargSelect" value="Xuất viện">
<table cellspacing="0" width="100%">
<thead>
	<tr>
		<td class="adm_item"><input type="checkbox" name="checkall" onclick="checkAll(this)"></td>
		<td class="adm_item">{{$LDTime}}</td>
		<td class="adm_item">&nbsp;</td>
		<td class="adm_item">&nbsp;</td>
		<td class="adm_item">{{$LDFamilyName}} {{$LDName}}</td>
		<td class="adm_item">{{$LDBirthDate}}</td>
		<td class="adm_item">{{$LDPatNr}}</td>
		<td class="adm_item">{{$LDInsuranceType}}</td>
		<td class="adm_item">{{$LDOptions}}</td>
	</tr>
</thead>
<tbody id="tbody">
	{{$sOccListRows}}

 </tbody>
</table>
</form>
