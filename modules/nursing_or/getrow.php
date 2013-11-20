<?php
$mode=$_GET['mode'];
$i=$_GET['i'];
$num=$_GET["num"];
echo '<tr>
		<td>
		<nobr>
		
		  <input type="hidden" value="" name="h'.$mode.($i-1).'_'.$num.'">
		<input type="text" value="" name="'.$mode.($i-1).'_'.$num.'">
		<a href="javascript:popselect(\''.($i-1).'_'.$num.'\',\''.$mode.'\')">
		<img src="../../gui/img/common/default/search_radio.jpg"></a>
		<a alt="Thêm người" href="javascript:;" onclick="insertRow(\''.$mode.'\',\''.$i.'\',\''.($num+1).'\');">&nbsp;[+]</a>
		<a alt="Thêm người" href="javascript:;" onclick="delRow(\''.$mode.'\',\''.$i.'\',\''.($num+1).'\');">[-]&nbsp;</a>
		</nobr>
		</td>
		</tr>';
?>