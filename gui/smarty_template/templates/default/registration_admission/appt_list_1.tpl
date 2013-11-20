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
                        {{$sByDeptHiddenInputs}}
                    </form>
                </td>
	</tr>
	<tr>
		<td colspan="2">
			
			<p></p>
			<table id="result" border=0 cellpadding="3" cellspacing=2>
				<tbody>
					{{$tr}}                                        
					<tr class="wardlistrow1">
                                                <td style="text-align:center;"><font size="-1" face="Arial" color="" >{{$tong}}</font></td>
                                                <td style="text-align:center;">{{$tongbhyt}}</td>
                                                <td style="text-align:center;">{{$tongkbhyt}}</td>
                                                <td colspan="5">
                                                    <table width="100%"> 
                                                        <tbody>
                                                            <tr class="wardlisttitlerow1">
                                                                <td colspan="5" style="text-align:center;">{{$nhi}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Ngoại trú&nbsp;</td>
                                                                <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Nội trú&nbsp;</td>
                                                                <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Nam&nbsp;</td>
                                                                <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Nữ&nbsp;</td>
                                                                <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;<2500 gram&nbsp;</td>
                                                            </tr>
                                                            <tr bgcolor="#FFFAFA">                                                                
                                                                <td style="text-align:center;">{{$nhingoai}}</td> 
                                                                <td style="text-align:center;">{{$nhinoi}}</td>
                                                                <td style="text-align:center;">{{$nhisexm}}</td>
                                                                <td style="text-align:center;">{{$nhisexf}}</td>
                                                                <td style="text-align:center;">{{$nhicann}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>    	
												<td colspan="2"> 
													<table width="100%">
                                                                <tbody>
																	<tr>
																		<td colspan="2" style="text-align:center;">{{$khamthai}}</td>
																	</tr>
																	<tr>
                                                                        <td style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;BHYT&nbsp;</td>
                                                                        <td style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Không BHYT&nbsp;</td>
                                                                    </tr>
                                                                    <tr bgcolor="#FFFAFA">
                                                                        <td style="text-align:center;">{{$khamthaibh}}</td>
                                                                        <td style="text-align:center;">{{$khamthaikbh}}</td>                                                                            
                                                                    </tr>
                                                                </tbody>                                                                    
                                                            </table>
												</td>
												<td colspan="2"> 
													<table width="100%">
                                                                <tbody>
																	<tr>
																		<td colspan="2" style="text-align:center;">{{$khamPkhoa}}</td>
																	</tr>
																	<tr>
																		<td style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;BHYT&nbsp;</td>
                                                                        <td style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Không BHYT&nbsp;</td>
                                                                    </tr>
                                                                    <tr bgcolor="#FFFAFA">
                                                                        <td style="text-align:center;">{{$khamPkhoabh}}</td>
                                                                        <td style="text-align:center;">{{$khamPkhoakbh}}</td>                                                                            
                                                                    </tr>
                                                                </tbody>                                                                    
                                                            </table>
												</td>
					</tr>                                       
                                        <tr class="wardlistrow1">
                                            <td colspan="12">
                                                <table width="100%"> 
                                                    <tbody>		
                                                        <tr class="wardlisttitlerow">
                                                            <td colspan="12" style="text-align:center;color: white;font-weight: bold;">Ngoại trú</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="12" style="text-align:center;"><font size="-1" face="Arial">{{$khamngoai}}</font></td>
                                                        </tr>
                                                        <tr>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;BHYT&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Không BHYT&nbsp;</td>
															<td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Không BHYT&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Đặt vòng&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Thuốc viên&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Thuốc tiêm&nbsp;</td>
															<td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Thuốc cấy&nbsp;</td>
															<td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Triệt sản&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Bao cao su(BCS)&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Nạo thai&nbsp;</td>
															<td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Hút thai&nbsp;</td>
															<td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Phá thai nội khoa&nbsp;</td>
                                                        </tr>                                                    
                                                        <tr bgcolor="#FFFAFA">
                                                            <td style="text-align:center;">{{$khamngoaibh}}</td>
                                                            <td style="text-align:center;">{{$khamngoaikbh}}</td>
															<td style="text-align:center;">{{$datvong}}</td>
                                                            <td style="text-align:center;">{{$thuocvien}}</td>
                                                            <td style="text-align:center;">{{$thuoctiem}}</td>
                                                            <td style="text-align:center;">{{$bcs}}</td>
                                                            <td style="text-align:center;">{{$naohut}}</td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>
															<td style="text-align:center;"></td>
														</tr>
                                                    </tbody>                                                    
                                                </table>
                                            </td>
                                        </tr>
                                        <tr class="wardlistrow1">
                                            <td colspan="12">
                                                <table width="100%"> 
                                                    <tbody>
                                                        <tr class="wardlisttitlerow">
                                                            <td colspan="8" style="text-align:center;color: white;font-weight: bold;">Nội trú</td>
                                                        </tr>
                                                        <tr class="wardlistrow1">
                                                            <td colspan="8" style="text-align:center;"><font size="-1" face="Arial" color="" >{{$khamnoi}}</font></td>
                                                        </tr>
                                                        <tr>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;BHYT&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Không BHYT&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Có tiêm VAT&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Không tiêm VAT&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Có khám thai&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Không khám thai&nbsp;</td>
                                                            <td  style="text-align:center;color: white;font-weight: bold; background: #6B8E23;">&nbsp;Sinh con thứ 3 trở lên&nbsp;</td>
                                                        </tr>                                                    
                                                        <tr bgcolor="#FFFAFA">
                                                            <td style="text-align:center;">{{$khamnoibh}}</td>
                                                            <td style="text-align:center;">{{$khamnoikbh}}</td>
                                                            <td style="text-align:center;">{{$cotiem}}</td>
                                                            <td style="text-align:center;">{{$khongtiem}}</td>
                                                            <td style="text-align:center;">{{$cokhamthai}}</td>
                                                            <td style="text-align:center;">{{$khongkhamthai}}</td>
                                                            <td style="text-align:center;">{{$sinhnlan}}</td>
                                                        </tr>
                                                    </tbody>                                                    
                                                </table>
                                            </td>
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