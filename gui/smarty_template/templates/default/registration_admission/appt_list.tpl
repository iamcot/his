{{* appt_list.tpl  Appointment list template 2004-06-13 Elpidio Latorilla *}}

<table width=100% border=0 cellpadding="0" cellspacing=0>
  <tbody>
	<tr>
		<td width=20%>
			{{$sMiniCalendar}}
		</td>
                <td valign="top">
                    <form name="bydept">
                        {{$LDListApptByDept}}<br>
                        <nobr>
                        {{$sByDeptSelect}} {{$pbByDeptGo}}
                        </nobr>
                        {{* Do not move the $sByDeptHiddenInputs outside of the form *}}
                        {{$LDListApptByDept}}<br>
                        <nobr>
                            {{$sByDeptSelect}} {{$pbByDeptGo}}
                        </nobr>
                    </form>
                    <form name="bydoc">
                        {{$LDListApptByDoc}}<br>
                        <nobr>
                            {{$sByDeptSelect1}} {{$pbByDeptGo}}
                        </nobr>
                        {{* Do not move the $sByDeptHiddenInputs outside of the form *}}
                        {{$LDListApptByDoc1}}<br>
                        <nobr>
                            {{$sByDeptSelect1}} {{$pbByDeptGo}}
                        </nobr>
                    </form>
                </td>
	</tr>
	<tr>
		<td colspan="2">
			
			<p></p>
			<table id="result" border=0 cellpadding="3" cellspacing=2>
				<tbody>
					<tr class="wardlisttitlerow">
						<td style="text-align:center;">Tổng Bệnh nhân&nbsp;</td>
						<td style="text-align:center;">BHYT</td>
						<td style="text-align:center;">Không BHYT</td>
						<td style="text-align:center;">Khám Ngoại</td>						
						<td style="text-align:center;">Khám Nội</td>
						<td style="text-align:center;">Nhi</td>
						<td style="text-align:center;">Nhi < 6t</td>
						<td style="text-align:center;" colspan=2> > 60t </td>
					</tr>
					<tr class="wardlistrow1">
						<td rowspan=2 style="text-align:center;"><font size="-1" face="Arial" color="" >{{$tong}}</font></td>
						<td rowspan=2 style="text-align:center;">{{$tongbhyt}}</td>
						<td rowspan=2 style="text-align:center;">{{$tongkbhyt}}</td>
						<td style="text-align:center;">{{$khamngoai}}</td>
						<td style="text-align:center;">{{$khamnoi}}</td>
						<td rowspan=2 style="text-align:center;">{{$nhi}}</td>
						<td rowspan=2 style="text-align:center;">{{$nhi6}}</td>
						<td rowspan=2 style="text-align:center;" colspan=2>{{$nguoigia}}</td>
					</tr>
					<tr class="wardlistrow1">						
						<td>
							<table width=100% border=0 cellpadding="0" cellspacing=1>
							  <tbody>
								<tr class="wardlisttitlerow">
									<td  style="text-align:center;">&nbsp;BHYT&nbsp;</td>
									<td  style="text-align:center;">&nbsp;Không BHYT&nbsp;</td>
								</tr>
								<tr class="wardlistrow2">
									<td style="text-align:center;">{{$khamngoaibh}}</td>
									<td style="text-align:center;">{{$khamngoaikbh}}</td>
								</tr>
							  </tbody>
							</table>
						</td>
						<td>
							<table width=100% border=0 cellpadding="0" cellspacing=1>
								<tbody>
									<tr class="wardlisttitlerow">
										<td  style="text-align:center;">&nbsp;BHYT&nbsp;</td>
										<td  style="text-align:center;">&nbsp;Không BHYT&nbsp;</td>
									</tr>
									<tr class="wardlistrow2">
										<td style="text-align:center;">{{$khamnoibh}}</td>
										<td style="text-align:center;">{{$khamnoikbh}}</td>
									</tr>
								</tbody>
							</table>
						</td>							
					</tr>
					
					<tr class="wardlisttitlerow">
						<td style="text-align:center;">Nhập viện</td>
						<td style="text-align:center;">Khoa Ngoại</td>
						<td style="text-align:center;">Khoa Nội nhi</td>
						<td style="text-align:center;">HSCC</td>						
						<td style="text-align:center;">Khoa YHCT</td>
						<td style="text-align:center;">Khoa Sản</td>
						<td style="text-align:center;">Khoa Nhiểm</td>
						<td style="text-align:center;"> Cúm </td>
						<td style="text-align:center;"> Tiêu chảy </td>
					</tr>
					<tr class="wardlistrow1">
						<td style="text-align:center;">{{$nhapvien}}</td>
						<td style="text-align:center;">{{$nhapvienngoai}}</td>
						<td style="text-align:center;">{{$nhapviennoi}}</td>
						<td style="text-align:center;">{{$nhapvienhscc}}</td>
						<td style="text-align:center;">{{$nhapvienyhct}}</td>
						<td style="text-align:center;">{{$nhapviensan}}</td>
						<td style="text-align:center;">{{$nhapviennhiem}}</td>
						<td style="text-align:center;">{{$cum}}</td>
						<td style="text-align:center;">{{$tieuchay}}</td>
					</tr>
					<tr>
						<td>
						{{$pPrintOut}}
						</td>
					</tr>
				</tbody>
			</table>		
		</td>		
	</tr>

	</tbody>
</table>
<p>
{{$sWeekLink}}<br/>
{{$sQuiLink}}
</p>