{{$sTab}}
{{$sRegForm}}
<p>
<TABLE border=0 cellpadding="0" cellspacing=0 >
	<TBODY>				
		<TR>
			<TD width=5px>
			</TD>
			<TD>
				<TABLE id="frame_all" border=0 cellpadding="0" cellspacing=0>
					<TBODY>							
						<TR>
							<TD style="font-size:20;font-weight:bold" align="center">
								{{$TitleForm}}
							</TD>
						</TR>
						<TR>
							<TD align="center">
								<TABLE width=500px cellpadding="0" cellspacing=0 border=0>
									<TBODY>										
										<TR height=25px>
											<TD style="font-size:12;" width=100px>
												{{$LDEncoder}}
											</TD>
											<TD width=1px> :
											</TD>
											<TD style="font-size:12;" width=120px>
												{{$sEncoderInput}}
											</TD>
										</TR>
										<TR height=25px>
											<TD style="font-size:12;" valign="bottom">
												{{$LDDate}}
											</TD>
											<TD width=1px>:
											</TD>
											<TD style="font-size:12;" >
												{{$sDateInput}}
											</TD>
										</TR>
										<TR height=25px>
											<TD style="font-size:12;" valign="bottom">
												{{$LDPutInID}}
											</TD>
											<TD width=1px>:
											</TD>
											<TD style="font-size:12;" >
												{{$sPutInIDInput}}
											</TD>
										</TR>
                                                                                <TR height=25px>
											<TD style="font-size:12;" valign="bottom">
												{{$LDTYPE}}
											</TD>
											<TD width=1px>:
											</TD>
                                                                                        <TD style="font-size:12;">
												{{$sTypePut}}
											</TD>
										</TR>
                                                                                <TR height=25px>
											<TD style="font-size:12;" valign="bottom">
												{{$LDVAT}}
											</TD>
											<TD width=1px>:
											</TD>
											<TD style="font-size:12;">
												{{$sPutInVATInput}}
											</TD>
                                                                                        <TD style="font-size:12;">
                                                                                            <FONT color="darkred"><i>{{$sType}}</i></FONT>
                                                                                        </TD>
										</TR>
									</TBODY>
								</TABLE>
							</TD>
						</TR>											
						<TR>
							<TD align="center">
								<TABLE width=1000px cellpadding="0" cellspacing="0" border=0>
									<TBODY>
										<TR height=25px>											
											<TD width=150px style="font-size:12;" valign="bottom">
												{{$LDDeliveryPerson}}:
											</TD>
											<TD  width=300px style="font-size:12;">
												{{$sDeliveryPersonInput}}
											</TD>
											<TD width=100px>
											</TD>
											<TD width=150px style="font-size:12;" valign="bottom">
												{{$LDPutInPerson}}:
											</TD>
											<TD style="font-size:12;">
												{{$sPutInPersonInput}}
											</TD>
										</TR>
										<TR height=25px>											
											<TD style="font-size:12;" valign="bottom">
												{{$LDSupplier}}:
											</TD>
											<TD  style="font-size:12;">
												{{$sSupplierInput}}
											</TD>
											<TD>
											</TD>
											<TD style="font-size:12;" valign="bottom">
												{{$LDTypePutIn}}:
											</TD>
											<TD  style="font-size:12;">
												{{$sTypePutInInput}}
											</TD>
										</TR>		
										
										<TR height=25px>											
											<TD style="font-size:12;" valign="bottom">
												{{$LDPlace}}:
											</TD>
											<TD  style="font-size:12;">
												{{$sPlaceInput}}
											</TD>
											<TD>
											</TD>
											<TD style="font-size:12;" valign="bottom">
												{{$LDTotal}}:
											</TD>
											<TD  style="font-size:12;">
												{{$sTotalInput}}
											</TD>
										</TR>	
										<TR height=25px>											
											<TD width=150px style="font-size:12;" valign="bottom">
												{{$LDNote}}:
											</TD>
											<TD colspan="4" style="font-size:12;">
												{{$sNoteInput}}
											</TD>
										</TR>
										<TR>
											<TD colspan="2">
													{{$sHiddenInputs}}
											</TD>	
											<TD colspan="3" align="right">
													{{$LDSave}}
											</TD>
										</TR>										
									</TBODY>
								</TABLE>
							</TD>
						</TR>						
						<TR>
							<TD>
								<TABLE width=100%>
									<TBODY>

									</TBODY>
								</TABLE>
							</TD>								
						</TR>
						<TR>							
							<TD>
															
								<TABLE cellpadding="0" cellspacing="0" style="width:100%;">
									<TBODY>	
														
										<TR>
											<TD valign="top" align="center" style="width:100%;">
												<div id="scroll_table" style="overflow:auto;">
													<table id="my_table" border="0" cellpadding="0" cellspacing="0" width=100% bgColor="#ffffff" valign="top" style="border-left: solid 1px #C3C3C3;border-right: solid 1px #C3C3C3;">
														<tbody>
															<tr bgColor="#EDF1F4" height=15px>
																<th width=30px  style="font-size:12px; font-family:Tahoma;" rowspan="2"> </th>		
																<th width=30px  class="title1" rowspan="2"> {{$LDSTT}} </th>					
																<th width=250px class="title1"  rowspan="2">{{$LDMedicineName}}</th>
																<th width=85px class="title1"  rowspan="2">{{$LDUnit}}</th>
																<th width=85px class="title1"  rowspan="2">{{$LDMedicineID}}</th>	
																<th width=110px class="title1"  rowspan="2">{{$LDLotID}}</th>
																<th width=100px class="title1"  rowspan="2">{{$LDExpDate}}</th>
																<th width=85px class="title1"  rowspan="2">{{$LDPrice}}</th>
																<th width=200px style="font-size:12px; font-family:Tahoma;border-bottom: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;" colspan="2">{{$LDNumber}}</th>														<th width=120px class="title1"  rowspan="2">{{$LDTotalPrice}}</th>
																<th class="title1" rowspan="2">{{$LDNote}}</th>
															</tr>		
															<tr bgColor="#EDF1F4" height=15px>
																<th width=100px class="title1">{{$LDNumberInPaper}}</th>																							
																<th width=100px class="title1">{{$LDNumberInFact}}</th>	
															</tr>																	
																{{$divMedicine}}
														</tbody>
													</table>	
													<table border="0" cellpadding="0" cellspacing="0" width=100% valign="top" class="title1">
														<tbody>
															<tr bgColor="#EDF1F4" height=20px>							
																<TD>
																		{{$AddRow}}
																</TD>	
															</tr>
														</tbody>
													</table>
												</div>
											</TD>
										</TR>										
									</TBODY>
								</TABLE>

							</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>			
		</TR>
	</TBODY>
</TABLE>
<p>
<center>
<table>
	<tr>
		<td>{{$pbDelete}}</td>
		<td>{{$pbCancel}}</td>
	</tr>
</table>
</center>
</form>