<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>pickItem</title>
<?php
	require("../../../cgi_bin/phpFun.php");
	jCnn();
?>

<?php 
dim db
db=server.urlencode(mid($_SESSION['database'],instrrev($_SESSION['database'],"\")+1))

if (isset('tabla')) {$strsql="select * from " . tabla . " order by 2";}
if (isset('$strsql') {$strsql=$_REQUEST['$strsql'];}
?>
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">
<script type="text/javascript">
jss.Init=function(){
	var strsql='<?php echo urlencode(base64_encode(gzcompress($strsql)))?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=gz&strsql='+strsql).evalDecode();
	if(typeof datos!='object'){document.getElementById('qForm').innerHTML=datos; return;};	

	new jss.Grid({
		renderTo:'list',
		values: datos['values'],
		columns: datos['columns']
	})
	
}
</script>
</head>

<body class="jss-Body">

<table id="listaItems" class="jss-Table" style="width: 100%">
	<tr>
		<td id="list"></td>
	</tr>
</table>

</body>
</html>