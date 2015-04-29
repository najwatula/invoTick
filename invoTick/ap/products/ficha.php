<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");		
	jCnn();
?><?php
//-------------------------------------------------
//Logos
//-------------------------------------------------
/*	if ($_FILES["file"]["error"] > 0) {
	  echo "Error: " . $_FILES["file"]["error"] . "<br>";
	}
	else{
	  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	  echo "Type: " . $_FILES["file"]["type"] . "<br>";
	  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br>";
	  echo "Stored in: " . $_FILES["file"]["tmp_name"];
  	}
*/
if (isset($_FILES["file"])) {
	if($_SESSION['idProduct']==0){
		$_SESSION['idProduct']=saveForm ('products', 'idProduct', $_SESSION['idProduct']);
	}
	$imgFile=file_get_contents($_FILES["file"]["tmp_name"]);
    
    $exist=jGet("select id from images where [tableName]='products' and [id]=".$_SESSION['idProduct']);
    if($exist){
	    $strsql="update [images] set [image]=:img where id=$exist";
	    $results = $GLOBALS['db']->prepare($strsql);
	}
	else{
	    $strsql='insert into [images] ([tableName], [keyName], [image], [id]) values("products", "idProduct", :img, '.$_SESSION['idProduct'].');';
	    $results = $GLOBALS['db']->prepare($strsql);
	}

	$results->bindParam(':img', $imgFile, PDO::PARAM_LOB);
	$results->execute();
}
else{
	//-------------------------------------------------
	//Captura el puntero
	//-------------------------------------------------
	if (isset($_REQUEST['idProduct'])){$_SESSION['idProduct']=$_REQUEST['idProduct'];}
	if (isset($_POST['idProduct'])){$_SESSION['idProduct']=$_POST['idProduct'];}
	if (!$_SESSION['idProduct']){$_SESSION['idProduct']='0';}
}
//-------------------------------------------------
//Eliminar
//-------------------------------------------------
if (isset($_REQUEST["eliminar"])){

	$strCommand='delete from products where idProduct='.$_SESSION['idProduct'];
	$res = $GLOBALS['db']->exec($strCommand);
	
	$strCommand='delete from ratesByProduct where idProduct='.$_SESSION['idProduct'];
	$res = $GLOBALS['db']->exec($strCommand);
	
	header("Location: search.php");
	exit;
}

//-------------------------------------------------
//Salva los Cambios
//-------------------------------------------------
if (isset($_REQUEST["save"])){
	$_SESSION['idProduct']=saveForm ('products', 'idProduct', $_SESSION['idProduct']);
}

//-------------------------------------------------
//Abre el Recordset
//-------------------------------------------------
$strsql="SELECT * from products where idProduct = ".$_SESSION['idProduct'];
$strImg='../../../cgi_bin/phpFun.php?getImg=base64&strsql='.base64_encode('SELECT [image] from [images] WHERE [tableName]=\'products\' and [id] = '.$_SESSION['idProduct']);

?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<title>Productos</title>
<base target="_self">
<script type="text/javascript">
var wWin;
jss.Init=function(){
	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php','jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){window.alert(datos); return;};	
	jss.putData(datos['columns'],datos['values']);
}

function pick(strsql){
	try{wWin.close()} catch(err){};
	var datos=loadAjax('../../../cgi_bin/getArray.php?strsql='+strsql).evalDecode();
	
	wWin= new jss.PickItem({
		width: '450',
		height: '400',
		title:'Seleccionar un Elemento',
		fireEvent: 'getVal',
		values: datos['values'],	
		columns: datos['columns']
	})
}

function getVal(q){
	document.getElementById('idfamily').value=(q.cells[0].innerText || q.cells[0].textContent);
	document.getElementById('family').value=(q.cells[1].innerText || q.cells[1].textContent);
	wWin.window.panel.close();
}

function showImage(objImg){
	try{wWin.close()} catch(err){};
	wWin= new jss.Window({
		style:{width: '450',height: '400'},
		title:'Imagen',
		html:'<table width="100%" height="100%"><tr><td align="center"><div id="marco"></div></td></tr></table>'
	})
	
	objImg.removeAttribute('onload');
	var dupliPhoto=objImg.cloneNode(true);
	dupliPhoto.removeAttribute('onclick');
	dupliPhoto.removeAttribute('style');
	document.getElementById('marco').appendChild(dupliPhoto);

}

function deleteRecord(){
	new jss.Confirm({
		title:'Confirmación',
		msg:'Esta Seguro de Eliminar Este Registro?',
		yes: 'document.location.search=\'?eliminar=1\''
	});
}
</script>
</head>

<body class="jss-Body">

<table id="container" class="jss-TablePanel" style="height: 100%; width: 100%;">
	<tr style="height: 1%">
		<td style="width: 75%;">
		<form method="POST" name="frData" style="margin: 0px">
			<table class="jss-TableBorder" style="width: 100%; height: 146pt">
				<tr class="jss-Caption">
					<td colspan="4">&nbsp;Products [<?php echo $_SESSION['idProduct'];?>]
					</td>
				</tr>
				<tr>
					<td style="text-align: right"><?php echo _ITEM?>:</td>
					<td colspan="3">
					<input class="jss-FieldAuto" maxlength="100" name="item" size="86" value=""></td>
				</tr>
				<tr>
					<td style="text-align: right"><?php echo _REFERENCE?>:</td>
					<td colspan="3" nowrap="nowrap">
					<input class="jss-FieldAuto" maxlength="50" name="reference" size="35" value=""></td>
				</tr>
				<tr>
					<td style="text-align: right">barCode:</td>
					<td style="width: 40%">
					<input class="jss-FieldAuto" maxlength="50" name="barCode" size="35" value=""></td>
					<td style="text-align: right; width: 10%">cc:</td>
					<td style="width: 40%">
					<input class="jss-FieldAuto" maxlength="50" name="cc" size="35" value=""></td>
				</tr>
				<tr>
					<td style="text-align: right"><?php echo _VAT?>:</td>
					<td><select class="jss-FieldAuto" name="idVat" size="1">
					<option>-</option>
					<?php echo putOptions("select idVat, cast(vatValue as text) || '% ' || vat as vat from [vatTypes] order by vat",'');?>
					</select></td>
					<td style="text-align: right"><?php echo _FAMILY?>:</td>
					<td><select class="jss-FieldAuto" name="idFamily" size="1">
					<option>-</option>
					<?php echo putOptions("select idFamily, Family from [Familys] order by Family",'');?>
					</select></td>
				</tr>
				<tr>
					<td style="text-align: right; height: 50%" colspan="4" >&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" style="height: 1%">
					<table style="width: 100%">
						<tr>
							<td style="text-align: center">
							<input class="jss-Boton" name="save" type="submit" value="<?php echo _SAVE?>"></td>
							<td style="text-align: center">
							<input class="jss-Boton" name="cancel" onclick="javascript: document.location.href='search.php';" type="button" value="<?php echo _CANCEL?>"></td>
							<td style="text-align: center">
							<input class="jss-Boton" name="delete" onclick="javascript: deleteRecord()" type="button" value="<?php echo _DELETE?>"></td>
						</tr>
					</table>
					</td>
				</tr>
				</table>
			<input name="idProduct" type="hidden" value="<?php echo $_SESSION['idProduct'];?>">
		</form>
		</td>
		<td style="width: 25%; ">
		<form enctype="multipart/form-data" method="post" name="fLogo" id="fLogo" class="jss-NoMargins">
			<table class="jss-TableBorder" style="height: 146pt; width: 100%">
				<tr style="height: 99%">
					<td style="text-align: center">
					<img alt="" id="logo" onclick="javascript: showImage(this);" style="max-width: 200pt; max-height: 100pt;" src="<?php echo $strImg ?>"></td>
				</tr>
				<tr style="height: 1%">
					<td>
					<input class="jss-FieldAuto" id="file" name="file" size="5" type="file" onchange="javascript: this.form.submit();"></td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr style="height: 99%" >
		<td id="tabs" colspan="2">
		<table id="pTabs" class="jss-Tab" style="width: 100%; height: 100%;">
			<tr>
				<td class="jss-TabInactive" title="iFrame:{id:'iPrices', scroll:'yes', url:'prices.php'}">
				<?php echo _RATE?></td>
				<td class="jss-TabInactive" title="iFrame:{id:'iStocks', scroll:'yes', url:'stocks.php'}">
				Stocks</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>
