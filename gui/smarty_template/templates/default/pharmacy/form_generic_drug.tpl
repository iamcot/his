<TABLE id="frame_info" height=100% border=0 cellpadding="0" cellspacing=0 style="border-bottom: solid 1px #C3C3C3;border-top: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;border-right: solid 1px #C3C3C3;" bgcolor=#FDFDFD>
     <TBODY>
        <TR height=70px>
            <TD align="center" valign="top">				
                 <TABLE width=96%  border=0 cellpadding="0" cellspacing=0 style="border-bottom: solid 1px #C3C3C3;" valign="top">
					<TBODY>		
								<TR height=10px>
								</TR>
								<TR>
									<TD align="right">                                       
                                         <table border=0 width=100% cellspacing=0 cellpadding=0  style="overflow:auto;">
                                            <tbody>
                                                <tr>
                                                    <td valign="top" style="font-size:12px;font-weight:bold;"> {{$LDGenericName}}</td>                                                   
                                                </tr>
                                            </tbody>
                                         </table>
                                    </TD>
								</TR>
                                <TR height=30px>									
                                    <TD valign="top" align="center">
                                         <table border=0 height=30px width=98% cellspacing=0 cellpadding=0  style="overflow:auto;" align="top">
                                            <tbody>
                                                <tr> 
													<td width=6%></td>
                                                    <td  width=80% align="center" valign="top"  style="font-size:20px; font-family:Arial;font-weight:bold; color:#567AAA;">														
															{{$sGenericNameInput}}													
													</td>  
													<td align="right" valign="top"> {{$LDSave}}</td>  
                                                    <td align="right" valign="top"> {{$LDCancel}}</td>
													<td align="right" valign="top"> {{$LDRemove}}</td>
													<td align="right" valign="top"> {{$LDEdit}} </td>
                                                </tr>
                                            </tbody>
                                         </table>
                                    </TD>
                                </TR>
								<TR height=5px>
								</TR>
                     </TBODY>
                </TABLE>
            </TD>
        </TR>
        <TR height=100%>
            <TD valign="top" align="center">
				<DIV id="div_content" style="overflow:auto;">
					<table border=0 width=96% cellspacing=3 cellpadding=0  style="overflow:auto;" valign="top" >
						 <tbody>
							<tr>
								<td valign="top" align="right">
									{{$sStatusInput}}                               
								</td>
							</tr>							
							<tr>
								<td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDGroup}}
									<table border=0 width=100% cellspacing=0 cellpadding=0  bgcolor=#EDF1F4>
										<tbody>
											<tr>
												<td height=25px>
													{{$sGroupInput}}
												</td>
											 </tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDGroupSub}}
									<table border=0 width=100% cellspacing=0 cellpadding=0  bgcolor=#EDF1F4>
										<tbody>
											<tr>
												<td height=25px>
													{{$sGroupInputSub}}
												</td>
											 </tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								 <td>
									<table width=100%>
										<tbody>
											 <tr>
												<td valign="top" width=45% style="font-size:12px;font-weight:bold;">
													{{$LDGenericId}}
													<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
														<tbody>
															<tr height=25px>
																<td>
																	{{$sGenericIdInput}}
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width=10%>
												</td>
												<td valign="top" width=45% style="font-size:12px;font-weight:bold;">
													 {{$LDDrugId}}
													 <table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
														<tbody>
															<tr height=25px>
																<td>
																	{{$sDrugIdInput}}
																</td>
															</tr>
														 </tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDUsingType}}
									<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr height=25px>
												<td>
													{{$sUsingTypeInput}}
												</td>
											</tr>
										 </tbody>
									 </table>
								 </td>
							</tr>
							<tr>
								<td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDHospital}}
									 <table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr>
												<td height=25px>
													{{$sHospitalInput}}
												</td>
											 </tr>
										</tbody>
									</table>
								</td>
							 </tr>							 
							 <tr>
								 <td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDEffects}}
									<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr height=25px>
												<td>
													{{$sEffectsInput}}
												</td>
											</tr>
										</tbody>
									</table>
								 </td>
							</tr>
							<tr>
								 <td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDUsing}}
									<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr height=25px>
												<td>
													{{$sUsingInput}}
												</td>
											</tr>
										</tbody>
									</table>
								 </td>
							</tr>
							<tr>
								 <td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDCaution}}
									<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr height=25px>
												<td>
													{{$sCautionInput}}
												</td>
											</tr>
										</tbody>
									</table>
								 </td>
							</tr>
						</tbody>
					</table>
				</DIV>
            </TD>
        </TR>
        <TR id="product_content">
            <TD align="center">
                {{* Note the ff: conditional block must always go together *}}				
				{{if $LDProducts ne ""}}
				<table border=0 height=100px width=96% cellspacing=2 cellpadding=0 >
                    <tbody>
                        <tr>
                            <td style="font-size:12px;font-weight:bold;">
                                {{$LDProducts}}
                                    <table border=0 width=100% height=90px cellspacing=2 cellpadding=0 style="border-bottom: solid 1px #C3C3C3;border-top: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;border-right: solid 1px #C3C3C3;">
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
				{{/if}}
				{{* end of conditional block *}}	
             </TD>
        </TR>
        <TR height=10px>
            <TD>
            </TD>
        </TR>
    </TBODY>
</TABLE>