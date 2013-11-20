{{* Smarty Template - mainframe.tpl 2004-06-11 Elpidio Latorilla *}}
{{* This is the main template that frames the main work page *}}

{{include file="common/header.tpl"}}

<table width=100% border=0 cellspacing=0 height=100%>
<tbody class="main">
	<tr>
		<td bgcolor={{$body_bgcolor}} valign=top>
		
			{{* Note the ff: conditional block must always go together *}}
			{{if $sMainBlockIncludeFile ne ""}}
				{{include file=$sMainBlockIncludeFile}}
			{{/if}}
			{{if $sMainFrameBlockData ne ""}}
				{{$sMainFrameBlockData}}
			{{/if}}
			{{* end of conditional block *}}

		</td>
	</tr>
	


	</tbody>
 </table>


