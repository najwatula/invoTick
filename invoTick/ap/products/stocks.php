<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");			
	jCnn();
?><?php
if (isset($_REQUEST["eliminar"])){
	$strsql="delete from [stocks] where id=".$_REQUEST["eliminar"];
	$results = $GLOBALS['db']->exec($strsql);
}

if (isset($_REQUEST["insertar"])){
	$strsql="insert into [stocks] (idProduct) values(".$_SESSION['idProduct'].");";
	$results = $GLOBALS['db']->exec($strsql);
}

if (isset($_REQUEST["salvar"])){
	saveForm ("stocks", "id", $_REQUEST["salvar"]);
}

//-------------------------------------------------
//Establece las Condiciones y Abre el Recordset
//-------------------------------------------------
$strsql="select * from [stocks] where idProduct=".$_SESSION['idProduct']." order by idStore limit 50";
$GLOBALS['rst'] = $GLOBALS['db']->query($strsql);
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<title>Tabla de Precios</title>
<base target="_self">
</head>

<body class="Jss-Body">

<table class="jss-Table" style="width: 100%">
	<tr class="jss-Caption">
		<td colspan="3">&nbsp;Stocks [<?php echo jSum('stocks','idProduct='.$_SESSION['idProduct'],'amount'); ?>]</td>
		<td align="right" colspan="2" width="5%">
		<a href="stocks.php?insertar=1">
		<img border="0" src="../../images/insert20.gif" style="width: 20px; height: 20px"></a></td>
	</tr>
	<tr class="jss-Bar">
		<td style="width: 10%">Id</td>
		<td style="width: 80%">Store</td>
		<td style="width: 1%">Amount</td>
		<td  colspan="2" style="width: 1%">&nbsp;</td>
	</tr>
	<?php $nr=0; ?><?php while ($gv = $GLOBALS['rst']->fetch()) {?>
	<form action="stocks.php" class="NoMargenes" method="POST" name="<?php echo 'frmPrices'.$nr ?>">
		<tr>
			<td>
			<input class="jss-FieldAuto" name="id" size="5" style="text-align: right" value="<?php echo $gv["id"]; ?>"></td>
			<td><select class="jss-FieldAuto" name="idStore" size="1">
			<option>-</option>
			<?php echo putOptions("select idStore, store from [stores] order by store",$gv["idStore"]);?>
			</select></td>
			<td>
			<input class="jss-FieldNum" name="amount" size="12" value="<?php echo $gv["amount"]; ?>"></td>
			<td align="center">
			<a href="stocks.php?eliminar=<?php echo $gv['id']; ?>">
			<img border="0" src="../../images/Delete16.gif" style="width: 16px; height: 16px"></a></td>
			<td align="center">
			<input class="jss-BotonSave" name="salvar" type="submit" value="<?php echo $gv['id']; ?>"></td>
		</tr>
	</form>
	<?php $nr+=1; ?><?php };?>
</table>

</body>

</html>
