<TABLE width=100% height=100% border=0 cellpadding="0" cellspacing=0>
	<TBODY>			
		<TR height=30px>
			<TD bgcolor=#5F88BE>
				<TABLE border=0 cellpadding="0" cellspacing=0 bgcolor=#5F88BE >
					<TBODY>	
						<TR>
							<TD width=100px>
								{{$sToolbarTitle}}
							</TD>
							<TD style="color:#FFF;">
								>>
							</TD>
							<TD>
								{{$LDDropDownCatalogue}}
							</TD>
							<TD style="color:#FFF;">
								>>
							</TD>
							<TD>
								{{$LDOption}} 
							</TD>
						</TR>
					</TBODY>
				</TABLE>					
			</TD>			
		</TR>
		<TR height=10px>
			<TD>
			</TD>
		</TR>
		<TR height=100%>
			<TD>
				<TABLE id="frame_all" border=0 cellpadding="0" cellspacing=0>
					<TBODY>	
						<TR height=100%>
							<TD width=10px>
							</TD>
							<TD width=520px align="center" valign="top">
								<TABLE width=520px height=100% border=0 cellpadding="0" cellspacing=0>
									<TBODY>	
										<TR height=80px>											
											<TD valign="top">
												<TABLE width=520px height=80px border=0 cellpadding="0" cellspacing=0   bgcolor=#FDFDFD style="border-bottom: solid 1px #C3C3C3;border-top: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;border-right: solid 1px #C3C3C3;"> 
													<TBODY>	
														<TR>															
															<TD align="center">	
																<TABLE width=100% border=0 cellpadding="0" cellspacing=0> 
																	<TBODY>	
																		<TR>
																			<TD width=2%>
																			</TD>
																			<TD width=15%>																				
																					{{$LDSearchBy}}: 																				
																			</TD>
																			<TD align="left">
																				{{$LDDropdownSearch}}
																			</TD>
																		</TR>
																		</TBODY>
																	</TABLE>		
															</TD>
															<TD align="center">																
															</TD>
														</TR>														
														<TR>
															<TD>
																<TABLE width=100% border=0>
																	<TBODY>
																		<TR>
																			<TD width=1%>
																			</TD>
																			<TD width=90% align="center">
																				{{$LDSearchInput}} 
																			</TD>
																			<TD width=1%>
																			</TD>
																			<TD>
																				{{$LDSearchButton}}
																			</TD>
																		</TR>
																	</TBODY>
																</TABLE>
															</TD>
														</TR>	
														<TR height=2px>
														</TR>														
													</TBODY>
												</TABLE>
											</TD>
										</TR>
										<TR height=10px>
										</TR>
										<TR height=100%>
											<TD valign="top">
												<TABLE id="frame_result" width=520px border=0 cellpadding="0" cellspacing=0   valign="top" bgcolor=#FDFDFD style="border-bottom: solid 1px #C3C3C3;border-top: solid 1px #C3C3C3;border-left: solid 1px #C3C3C3;border-right: solid 1px #C3C3C3;">
													<TBODY>	
														<TR height=45px>
															<TD align="center" valign="top">
																<TABLE width=96%  border=0 cellpadding="0" cellspacing=0 style="border-bottom: solid 1px #C3C3C3;" valign="top">
																	<TBODY>	
																		<TR height=10px>
																		</TR>
																		<TR height=30px>
																			<TD width=1%>
																			</TD>
																			<TD>		
																				<b>{{$LDResult}}</b>
																			</TD>
																			<TD align="right">		
																				{{$LDNew}}
																			</TD>
																		</TR>
																	</TBODY>
																</TABLE>
															</TD>
														</TR>
														<TR>															
															<TD valign="top" align="center">
																<div id="catalogue">
																	{{$LDCatalogueInfo}}
																</div>	
															</TD>
														</TR>														
														<TR height=20px>
															<TD align="center">
																<font color=#00209B>
																	{{$LDFirst}} {{$LDPrev5}} {{$LDPrev4}} {{$LDPrev3}} {{$LDPrev2}} {{$LDPrev}} {{$LDCurr}} {{$LDNext}} {{$LDNext2}} {{$LDNext3}} {{$LDNext4}} {{$LDNext5}} {{$LDLast}} 
																</font>
															</TD>
														</TR>
														<TR height=5px>															
														</TR>
													</TBODY>
												</TABLE>
											</TD>
										<TR>
									</TBODY>
								</TABLE>
							</TD>
							<TD width=10px>
							</TD>
							<TD valign="top" align="left" id="td_frame_info">
								{{* Note the ff: conditional block must always go together *}}
								{{if $sSubBlockIncludeFile ne ""}}
									{{include file=$sSubBlockIncludeFile}}
								{{/if}}
								{{if $sSubFrameBlockData ne ""}}
									{{$sSubFrameBlockData}}
								{{/if}}
								{{* end of conditional block *}}	
							</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
	</TBODY>
</TABLE>
{{$sHiddenInput}}
