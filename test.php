<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<script language="javascript">
function doc(){
  str=document.getElementById("access_file").value;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("show").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","get.php?file="+str,true);
	xmlhttp.send();

}
</script>
</head><input type="file" value="" id="access_file"><input type="button" value="Đọc" onclick="doc()">
<p><p>
<table border="1" id="show">
<tbody>

</tbody>
</table>

<body>
</body>


 </html>