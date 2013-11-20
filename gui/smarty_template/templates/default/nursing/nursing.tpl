		 <blockquote>
			<TABLE cellSpacing=0  width=850 class="submenu_frame" cellpadding="0">
			<TBODY>
			<TR>
				<TD>
					<TABLE cellSpacing=1 cellPadding=3 width=850>
					<TBODY class="submenu">

					{{$LDNursingStations}}

					<TR>
                        {{$tblWardInfo}}

					</TR>
					{{include file="common/submenu_row_spacer.tpl"}}

					{{$LDIssuePaper}}
					
					{{include file="common/submenu_row_spacer.tpl"}}

					{{$LDQuickView}}

					{{include file="common/submenu_row_spacer.tpl"}}

					{{$LDSearchPatient}}

					{{include file="common/submenu_row_spacer.tpl"}}

					{{$LDArchive}}

					{{include file="common/submenu_row_spacer.tpl"}}

					{{$LDNursesList}}

					{{include file="common/submenu_row_spacer.tpl"}}

					{{$LDNews}}

					</TBODY>
					</TABLE>
				</TD>
			</TR>
			</TBODY>
			</TABLE>

			<p>
			<a href="{{$breakfile}}"><img {{$gifClose2}} alt="{{$LDCloseAlt}}" {{$dhtml}}></a>
			<p>
			</blockquote>
