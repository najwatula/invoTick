<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");			
	jCnn();
?><?php
if (isset($_REQUEST["eliminar"])){
	$strsql="delete from [fees] where idFee=".$_REQUEST["eliminar"];
	$results = $GLOBALS['db']->exec($strsql);
}

if (isset($_REQUEST["insertar"])){
	$strsql="insert into [fees] (idMember) values(".$_SESSION['idMember'].");";
	$results = $GLOBALS['db']->exec($strsql);
}

if (isset($_REQUEST["salvar"])){
	saveForm ("fees", "idFee", $_REQUEST["salvar"]);
}

//-------------------------------------------------
//Establece las Condiciones y Abre el Recordset
//-------------------------------------------------
$strsql="select * from [feesBymembersByproducts] where idMember=".$_SESSION['idMember']." order by periodicity limit 50";
$results = $GLOBALS['db']->query($strsql);
//$gv = $results->fetch();

?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../cgi_bin/jss/jssCalendar.js" type="text/javascript"></script>
<title>Tabla de Cuotas</title>
<base target="_self">
<script type="text/javascript">
jss.Init=function(){}

function pickProduct(){
	try{wWin.close()} catch(err){};
	strsql=strsql='<?php echo base64_encode("select idProduct, item, family, rate from prices order by family, item")?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){alert(datos); return;};	
	
	wWin= new window.parent.jss.PickItem({
		style:{width: '600',height: '400'},
		title:'Pick Item',
		fireEvent: 'window.frames.ifTab.getVal',
		values: datos['values'],
		columns: datos['columns']
	})
}

function getVal(q){
	document.getElementById('idProduct').value=(q.cells[0].innerText || q.cells[0].textContent);
	document.getElementById('item').value=(q.cells[1].innerText || q.cells[1].textContent);
	wWin.window.panel.close();
}
</script>
</head>

<body class="Jss-Body">

<table class="jss-Table" style="width: 100%">
	<tr class="jss-Caption">
		<td colspan="7">&nbsp;Fees [<?php echo jCount('fees','idMember='.$_SESSION['idMember']); ?>]</td>
		<td colspan="2" style="width: 1%; text-align: center;">
		<a href="fees.php?insertar=1">
		<img alt="" src="../../images/insert20.gif" style="border-width: 0px; width: 20px; height: 20px"></a></td>
	</tr>
	<tr class="jss-Bar">
		<td style="width: 5%">Id</td>
		<td style="width: 1%">.</td>
		<td style="width: 40%"><?php echo _PRODUCTS;?></td>
		<td style="width: 15%"><?php echo _RATE;?></td>
		<td style="width: 15%"><?php echo _PERIODICITY;?></td>
		<td style="width: 5%">Dias</td>
		<td style="width: 15%">Vencimiento</td>
		<td colspan="2" style="width: 1%">&nbsp;</td>
	</tr>
	<?php $nr=0; ?><?php while ($gv = $results->fetch()) {?>
	<form action="fees.php" class="NoMargenes" method="POST" name="<?php echo 'frmPrices'.$nr ?>">
		<tr>
			<td>
			<input class="jss-FieldAuto" name="idFee" size="5" style="text-align: right" value="<?php echo $gv["idFee"]; ?>"></td>
			<td>
			<img alt="" onclick="javascript: pickProduct();" src="../../images/list16.gif" style="width: 16px; height: 16px"></td>
			<td>
			<input id="item" class="jss-FieldAuto" name="item" size="50" value="<?php echo $gv["item"]; ?>"></td>
			<td><select class="jss-FieldAuto" name="idRate" size="1">
			<option>-</option>
			<?php echo putOptions("select idRate, rate from [rates] order by rate",$gv["idRate"]);?>
			</select></td>
			<td>
			<select id="periodicity" class="jss-FieldAuto" name="periodicity" size="1">
			<option <?php echo ($gv['periodicity']=='DAILY' ? 'selected' :'')?> value="DAILY"><?php echo _DAILY ?></option>
			<option <?php echo ($gv['periodicity']=='WEEKLY' ? 'selected' :'')?> value="WEEKLY"><?php echo _WEEKLY ?></option>
			<option <?php echo ($gv['periodicity']=='MONTHLY' ? 'selected' :'')?> value="MONTHLY"><?php echo _MONTHLY ?></option>
			<option <?php echo ($gv['periodicity']=='YEARLY' ? 'selected' :'')?> value="YEARLY"><?php echo _YEARLY ?></option>
			</select></td>
			<td>
			<input id="days" class="jss-FieldAuto" name="days" size="50" value="<?php echo $gv["days"]; ?>"></td>
			<td>
			<input id="nextDate<?php echo $nr;?>" class="jss-DatePicker" name="nextDate" size="12" value="<?php echo strftime('%Y-%m-%d',strtotime($gv["nextDate"])); ?>" maxlength="12" style="width: 100%"></td>
			<td><a href="fees.php?eliminar=<?php echo $gv['idFee']; ?>">
			<img  src="../../images/delete16.gif" style="border-width: 0px; width: 16px; height: 16px" alt="delete"></a></td>
			<td>
			<input class="jss-BotonSave" name="salvar" type="submit" value="<?php echo $gv['idFee']; ?>">
		    <input id="idProduct" name="idProduct" type="hidden" value="<?php echo $gv['idProduct']?>"></td>
		</tr>
	</form>
	<?php $nr+=1; ?><?php };?>
</table>

</body>

</html>
