<?php 

//-------------------------------------------------
//Salva los Cambios
//-------------------------------------------------
if(isset($_REQUEST["save"])){
	$_SESSION[$keyMaster]=saveForm($table, $keyMaster, $_SESSION[$keyMaster]);
}
//-------------------------------------------------
//Eliminar
//-------------------------------------------------
if(isset($_REQUEST["eliminar"])){
	$strCommand='delete from '.$table.' where '.$keyMaster.'='.$_SESSION[$keyMaster];
	$res = $GLOBALS['db']->exec($strCommand);
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}

if($_SESSION[$keyMaster]=='0'){
	$strsql="INSERT INTO [$table] ($buscaPor) VALUES('-');";
	$results=$GLOBALS['db']->exec($strsql);
 	if(!$results){$err=$GLOBALS['db']->errorInfo(); echo '<hr>'.$strsql.'<hr>Error: '.$err[2]; exit;}  
	$_SESSION[$keyMaster] = $GLOBALS['db']->lastInsertId();
}

if(isset($editsql)){$strsql=$editsql;}
else{$strsql="select * from $table where $keyMaster=".$_SESSION[$keyMaster];}
if(right($strsql,2)=='=0'){$strsql=offLast($strsql).$_SESSION[$keyMaster];};
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../cgi_bin/jss/jssColor.js" type="text/javascript"></script>
<title>tableForm</title>
<base target="_self">
<script type="text/javascript">
var puntero;
jss.Init=function(){
	var strsql='<?php echo urlencode(base64_encode(gzcompress($strsql)))?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=gz&strsql='+strsql).evalDecode();
	if(typeof datos!='object'){document.getElementById('qForm').innerHTML=datos; return;};	
	new jss.Form({
		renderTo: 'qForm',
		style:{width:'100%'},
		title: ' <?php  echo ucwords($table); ?>',
		values: datos['values'],
		columns: datos['columns'],
		types: datos['types']
	})
	puntero=datos['values'][0][0];
	
	<?php if(isset($script)){echo $script;}?>
	
}
function deleteRecord(){
	new jss.Confirm({
		title:'Confirmación',
		msg:'Esta Seguro de Eliminar Este Registro?',
		yes: 'document.location.search=\'?eliminar=1&id='+puntero+'\''
	});
}

</script>
</head>

<body class="jss-Body">

<form method="post" class="jss-NoMargins">
	<table class="jss-TableBorder" style="width: 100%">
		<tr>
			<td id="qForm" colspan="3"></td>
		</tr>
		<tr style="border-top-width: 1px; border-top-style: ridge; border-color: #808080">
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align: center">
			<input class="jss-Boton" name="save" type="submit" value="<?php  echo _SAVE?>"> </td>
			<td style="text-align: center">
			<input class="jss-Boton" name="cancel" type="button" onclick="javascript: document.location.search='';" value="<?php  echo _CANCEL?>"></td>
			<td style="text-align: center">
			<input class="jss-Boton" name="delete" type="button" onclick="javascript: deleteRecord()" value="<?php  echo _DELETE?>"></td>
		</tr>
	</table>
			<input name="id" type="hidden" value="<?php  echo $_SESSION[$keyMaster]?>">
</form>
<?php  if(isset($level2)){echo $level2;}?>
</body>

</html>
