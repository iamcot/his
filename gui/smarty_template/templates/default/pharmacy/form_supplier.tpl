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
                                                    <td valign="top" style="font-size:12px; font-weight:bold;">{{$LDSupplier}}</td>                                                    
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
															{{$sSupplierInput}}														
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
        <TR>
            <TD valign="top" align="center">
				<DIV id="div_content" style="overflow:auto;">
					<table border=0 width=96% style="height:auto;" cellspacing=3 cellpadding=0  style="overflow:auto;" valign="top" >
						 <tbody>
							<tr>
								<td valign="top" align="right" style="font-size:12px;">
									{{$sStatusInput}}                               
								</td>
							</tr>
							<tr>
								<td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDSupplierName}}
									<table border=0 width=100% cellspacing=0 cellpadding=0  bgcolor=#EDF1F4>
										<tbody>
											<tr>
												<td height=25px style="font-size:13px;font-family=Arial;">
													{{$sSupplierNameInput}}
												</td>
											 </tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDType}}
									<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr height=25px>
												<td>
													{{$sTypeInput}}
												</td>
											</tr>
										 </tbody>
									 </table>
								 </td>
							</tr>
							<tr>
								<td valign="top" style="font-size:12px;font-weight:bold;">
									{{$LDAddress}}
									 <table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr>
												<td height=25px>
													{{$sAddressInput}}
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
													{{$LDTel}}
													<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
														<tbody>
															<tr height=25px>
																<td>
																	{{$sTelInput}}
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td width=10%>
												</td>
												<td valign="top" width=45% style="font-size:12px;font-weight:bold;">
													 {{$LDFax}}
													 <table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
														<tbody>
															<tr height=25px>
																<td>
																	{{$sFaxInput}}
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
									{{$LDNote}}
									<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=#EDF1F4>
										<tbody>
											<tr height=25px>
												<td>
													{{$sNoteInput}}
												</td>
											</tr>
										</tbody>
									</table>
								 </td>
							</tr>
							<tr>
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