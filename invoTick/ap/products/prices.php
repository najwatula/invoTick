<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");			
	jCnn();
?><?php
if (isset($_REQUEST["eliminar"])){
	$strsql="delete from [ratesByProduct] where id=".$_REQUEST["eliminar"];
	$results = $GLOBALS['db']->exec($strsql);
}

if (isset($_REQUEST["insertar"])){
	$strsql="insert into [ratesByProduct] (idProduct) values(".$_SESSION['idProduct'].");";
	$results = $GLOBALS['db']->exec($strsql);
}

if (isset($_REQUEST["salvar"])){
	saveForm ("ratesByProduct", "id", $_REQUEST["salvar"]);
}

//-------------------------------------------------
//Establece las Condiciones y Abre el Recordset
//-------------------------------------------------
$strsql="select idBy, idRate, rate, price, vatValue, vatPrice from [prices] where idProduct=".$_SESSION['idProduct']." order by idRate limit 50";
$results = $GLOBALS['db']->query($strsql);
$gv=$results->fetch();

if(!$gv['idBy'] && $_SESSION['idProduct']>'0'){

    $strsqlRates="select idRate from [rates] order by rate limit 50";
    $resultsRate = $GLOBALS['db']->query($strsqlRates);
    while ($gvRate = $resultsRate->fetch()){
	    $strsqlins='INSERT INTO [ratesByProduct] (idProduct, idRate) VALUES('.$_SESSION['idProduct'].','.$gvRate['idRate'].')';
	    $results=$GLOBALS['db']->exec($strsqlins);
 	    if (!$strsqlins){$err=$GLOBALS['db']->errorInfo(); echo '<hr>'.$strsqlins.'<hr>Error: '.$err[2];}
 	} 
	$_SESSION['id'] = $GLOBALS['db']->lastInsertId();
}
$results = $GLOBALS['db']->query($strsql);
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<title>Tabla de Precios</title>
<base target="_self">
<script type="text/javascript">
function setPrice(q, qfrm){
    var vatValue=parseInt(document.getElementById('vatValue').value);
    if(q.name=='price'){qfrm.vatPrice.value=(parseFloat(q.value) + parseFloat(q.value) * vatValue / 100).toFix(3);}
    else{qfrm.price.value=(parseFloat(q.value) / parseFloat('1.' + vatValue)).toFix(3);}
    q.value=q.value.toFix(3);
}
</script>

</head>

<body class="Jss-Body">

<table class="jss-Table" style="width: 100%">
	<tr class="jss-Caption">
		<td colspan="5">&nbsp;Prices [<?php echo jCount('RatesByProduct','idProduct='.$_SESSION['idProduct']); ?>]</td>
		<td colspan="2" style="text-align: right; width: 1%;">
		<a href="prices.php?insertar=1">
		<img alt="" src="../../images/insert20.gif"></a></td>
	</tr>
	<tr class="jss-Bar">
		<td style="width: 10%">Id</td>
		<td style="width: 80%">Rate</td>
		<td style="width: 1%">Price</td>
		<td style="width: 1%">vat</td>
		<td style="width: 1%">vatPrice</td>
		<td  colspan="2" style="width: 1%">&nbsp;</td>
	</tr>
	<?php $nr=0; ?><?php while ($gv = $results->fetch()) {?>
	<form action="prices.php" class="NoMargenes" method="POST" name="<?php echo 'frmPrices'.$nr ?>">
		<tr>
			<td style="height: 25px">
			<input class="jss-FieldAuto" name="idBy" size="5" style="text-align: right" value="<?php echo $gv["idBy"]; ?>"></td>
			<td style="height: 25px"><select class="jss-FieldAuto" name="idRate" size="1">
			<option>-</option>
			<?php echo putOptions("select idRate, rate from [rates] order by rate",$gv["idRate"]);?>
			</select></td>
			<td style="height: 25px">
			<input onblur="javascript: setPrice(this, this.form);" class="jss-FieldNum" name="price" id="price" size="12" value="<?php echo number_format($gv["price"],3); ?>"></td>
			<td style="height: 25px" >
			<input class="jss-FieldNum" style="text-align: center" size="3" name="vatValue" id="vatValue" value="<?php echo $gv["vatValue"];?>"></td>
			<td style="height: 25px">
			<input onblur="javascript: setPrice(this, this.form);" class="jss-FieldNum" name="vatPrice" id="vatPrice" size="12" value="<?php echo number_format($gv["vatPrice"],3); ?>"></td>
			<td style="height: 25px">
			<a href="prices.php?eliminar=<?php echo $gv['idBy']; ?>">
			<img alt="" src="../../images/delete16.gif" style="border-width: 0px; width: 16px; height: 16px"></a></td>
			<td style="height: 25px" >
			<input class="jss-BotonSave" name="salvar" type="submit" value="<?php echo $gv['idBy']; ?>"></td>
		</tr>
	</form>
	<?php $nr+=1; ?><?php };?>
</table>

</body>

</html>
