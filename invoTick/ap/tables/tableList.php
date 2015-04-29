<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">
<title>List</title>
<script type="text/javascript">
var tList;
jss.Init=function(){
	var strsql='<?php  echo urlencode(base64_encode(gzcompress($strsql)))?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=gz&strsql='+strsql).evalDecode();
	if(typeof datos!='object'){document.getElementById('sqlString').innerHTML=datos; return;};	
	tList=new jss.Grid({
		renderTo:'list',
		fireEvent: 'getVal',
		values: datos['values'],
		columns: datos['columns']
	})
	tList.table.rows[0].cells[0].style.width='2%';
  
  <?php  if(isset($script)){echo $script;}?>  
}

function getVal(q){
	var id=q.cells[0].innerText || q.cells[0].textContent;
	document.location.search='?id='+id;
}
</script>
</head>

<body class="jss-Body">

<table class="jss-TablePanel" style="width: 100%">
	<tr>
		<td class="jss-Caption" style="text-align: right">
		<input class="jss-Boton" name="insertar" onclick="javascript:document.location.search='?id=0'" type="button" value="<?php  echo _NEW?>"></td>
	</tr>
	<tr>
		<td id="list"></td>
	</tr>
</table>

</body>

</html>
