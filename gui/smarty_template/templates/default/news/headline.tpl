{{* Headline page encapsulating frame *}}

<TABLE CELLSPACING=0 cellpadding=0 border="0" width="{{$news_normal_display_width}}">

	<tr>
		<td VALIGN="top" width="80%">
			{{include file="news/headline_titleblock.tpl"}}
			{{* include file="news/headline_newslist.tpl" *}}
			{{* remove 0410 - cot
			<table width=100%>
				<tr>
					<td>
						{{include file="news/headline_titleblock.tpl"}}
					</td>
				</tr>

				<tr valign=top>
					<td>
						{{$sNews_1}}
						
					</td>
				</tr>

				<tr valign=top>
				<td>
						{{$sNews_2}}
						
					</td>
				</tr>

				<tr valign=top>
					<td>
						{{$sNews_3}}
						
					</td>
				</tr>
				
			</table>
			*}}
			{{* add 0410 cot *}}
			{{$sNewsshow}}
			{{$sJS}}
			<div id="footer">
			Bản quyền &copy {{$LDBVName}}.<br>
			Một sản phẩm của Viện Cơ học và Tin học ứng dụng.
			</div>
			{{* end of headline_newslist.tpl *}}
		</td>
		<!--      Vertical spacer column      -->
		<TD   width=1  background="../../gui/img/common/biju/vert_reuna_20b.gif"></TD>

		<TD VALIGN="top">

			{{$sSubMenuBlock}}

		</TD>
	</tr>
</table>
