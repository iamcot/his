
<blockquote>
<div class="prompt">{{$LDOrPatient}}</div>
<TABLE cellSpacing=0 width=600 class="submenu_frame" cellpadding="0">
	<TBODY>
		<TR>
			<TD>
			<TABLE cellSpacing=1 cellPadding=3 width=600>
				<TBODY class="submenu">
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDOrPatientDocument}}</TD>
						<TD>{{$LDOrPatientDocumentTxt}}</TD>
					</TR>
                                        {{include file="common/submenu_row_spacer.tpl"}}
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDOrDocument1}}</TD>
						<TD>{{$LDOrDocumentTxt1}}</TD>
					</TR>
                                        {{include file="common/submenu_row_spacer.tpl"}}
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDOrPersonell}}</TD>
						<TD>{{$LDOrDocumentTxt2}}</TD>
					</TR>
                                        {{include file="common/submenu_row_spacer.tpl"}}
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDOrPharma}}</TD>
						<TD>{{$LDOrDocumentTxt3}}</TD>
					</TR>                                        
                                        {{include file="common/submenu_row_spacer.tpl"}}
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDStast}}</TD>
						<TD>{{$LDOrDocumentTxt4}}</TD>
					</TR>
				</TBODY>
			</TABLE>
			</TD>
		</TR>
	</TBODY>
</TABLE>
<p>
<div class="prompt">{{$LDOrDocs}}</div>
<TABLE cellSpacing=0 width=600 class="submenu_frame" cellpadding="0">
	<TBODY>
		<TR>
			<TD>
			<TABLE cellSpacing=1 cellPadding=3 width=600>
				<TBODY class="submenu">					
					<TR>
						<TD class="submenu_item" width=45%>{{$LDQviewDocManage}}</TD>
						<TD>{{$LDQviewTxtDocManage}}</TD>
					</TR>
                                        <TR>
						<TD colspan="2" class="submenu_item" width=45%>{{$LDOrDocMenu}}</TD>
					</TR>
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDQviewDocs}}</TD>
						<TD>{{$LDQviewTxtDocs}}</TD>
					</TR>

				</TBODY>
			</TABLE>
			</TD>
		</TR>
	</TBODY>
</TABLE>

<p>
<div class="prompt">{{$LDOrNursing}}</div>
<TABLE cellSpacing=0 width=600 class="submenu_frame" cellpadding="0">
	<TBODY>
		<TR>
			<TD>
			<TABLE cellSpacing=1 cellPadding=3 width=600>
				<TBODY class="submenu">
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDORNOCManager}}</TD>
						<TD>{{$LDDutyPlanTxt}}</TD>
					</TR>
                                        <TR>
						<TD colspan="2" class="submenu_item" width=45%>{{$LDOrNurseMenu}}</TD>
					</TR>
					{{include file="common/submenu_row_spacer.tpl"}}
                                        <TR>
						<TD class="submenu_item" width=45%>{{$LDORNOCScheduler}}</TD>
						<TD>{{$LDDutyPlanTxt1}}</TD>
					</TR>
					
					{{include file="common/submenu_row_spacer.tpl"}}
					<TR>
						<TD class="submenu_item" width=45%>{{$LDOrLogBook}}</TD>
						<TD>{{$LDOrLogBookTxt}}</TD>
					</TR>
					{{include file="common/submenu_row_spacer.tpl"}}
					<TR>
						<td colspan="2">{{$LDOrLogBookMenu}}</td>
					</TR>
					{{include file="common/submenu_row_spacer.tpl"}}	
					<TR>
						<TD class="submenu_item" width=45%>{{$LDOncallDuty}}</TD>
						<TD>{{$LDOnCallDutyTxt}}</TD>
					</TR>
					{{include file="common/submenu_row_spacer.tpl"}}
					<TR>
						<td colspan="2">{{$LDOncallManage}}</td>
					</TR>
				</TBODY>
			</TABLE>
			</TD>
		</TR>
	</TBODY>
</TABLE>

<p>

{{$sOnHoverMenu}}

<p><a href="{{$breakfile}}"><img
	{{$gifClose2}} alt="{{$LDCloseAlt}}"{{$dhtml}}></a>
<p>
</blockquote>
