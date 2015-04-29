<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");				
	jCnn();
?>

<?php
//-------------------------------------------------
// Captura de Parametros
//-------------------------------------------------
//on error resume next
$encadenador=" WHERE ";
$ordenar="ASC";
$sep='"'; 
$bus=" >= ";

$buscaPor="Item";
if (isset($_REQUEST["buscaPor"])){
	$buscaPor=$_REQUEST["buscaPor"];
}

$valorBuscar="";
if (isset($_REQUEST["valorBuscar"])){
	$valorBuscar=$_REQUEST["valorBuscar"];
}

$family="-";
if (isset($_REQUEST["family"])){
	$family=$_REQUEST["family"];
}

$rate="-";
if (isset($_REQUEST["rate"])){
	$rate=$_REQUEST["rate"];
}

if ($buscaPor=="idProduct"){
  $sep="";
  if (!is_numeric($valorBuscar)){
  	$valorBuscar="0";
    $ordenar=" DESC";
  } 
} 

if (isset($_REQUEST["like"])){
  	$bus=" like ";
  	$valorBuscar="%".$valorBuscar."%";
}

//-------------------------------------------------
// Abre el Recordset
//-------------------------------------------------
$strsql="SELECT idProduct, item, family, vat, rate, price, vatprice from [prices]";
$strbase=$strsql;

if ($valorBuscar>""){
  $strsql=$strsql.$encadenador." [$buscaPor] ".$bus.$sep.$valorBuscar.$sep;
  $encadenador=" AND ";
} 

if ($family!="-"){
  $strsql=$strsql.$encadenador." family=".$sep.$family.$sep;
  $encadenador=" AND ";
} 

if ($rate!="-"){
  $strsql=$strsql.$encadenador." rate=".$sep.$rate.$sep;
} 
//COLLATE NOCASE
$strsql=$strsql." ORDER BY [$buscaPor] COLLATE NOCASE $ordenar LIMIT 50;";
$strExport='../../../cgi_bin/phpFun.php?toXls='.base64_encode('select * from prices order by family, item');

?>

<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../../cgi_bin/jss/jss.js"></script>
<base target="_self">
<title>Buscar un Producto</title>
<script type="text/javascript">
jss.Init=function(){
	new jss.azBar({
		putTo:'valorBuscar',
		fireEvent:'document.getElementById(\'frOpciones\').submit()',
		renderTo:'azBar'
	})

	var strsql='<?php echo base64_encode($strsql)?>';
	var datos=loadAjax('../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('list').innerHTML=datos; return;};

	var t=new jss.Grid({
		renderTo:'list',
		fireEvent: 'goTarget',
		values: datos['values'],	
		columns: datos['columns']
	})
	t.table.rows[0].cells[0].style.width='2%';	
}

function goTarget(q){
	var id=q.cells[0].innerText || q.cells[0].textContent;
	document.location.href='ficha.php?idProduct='+id;
}
</script>
</head>

<body class="jss-Body">

<table class="jss-TablePanel" style="width: 100%">
	<tr>
		<td>
		<form id="frOpciones" method="POST" name="frOpciones" target="_self" class="jss-NoMargins">
			<table id="cabecera" class="jss-Table" style="width: 100%" >
				<tr>
					<td class="jss-Caption" colspan="4">&nbsp;<?php echo _SEARCH.' '._PRODUCTS;?>: (<?php echo jCount("Products","");?>)
					</td>
					<td style="text-align: center" rowspan="4">
					<a href="../tables/<?php echo $strExport;?>" style="width: 1%">
					<img alt=""  src="../../images/excel16.jpg" style="border-width: 0px; width: 16px; height: 16px"></a></td>
					<td style="width: 1%" rowspan="2">
					<input class="jss-Boton" name="buscar" type="submit" value="<?php echo _SEARCH;?>"></td>
				</tr>
				<tr>
					<td class="jss-Bar" style="width: 20%"><?php echo _SEARCH;?></td>
					<td  class="jss-Bar" style="width: 50%"><?php echo _VALUE;?> (Like<input name="like" type="checkbox" value="1">)</td>
					<td style="text-align: right; width: 10%;" class="jss-Bar"><?php echo _FAMILY;?>:</td>
					<td style="width: 20%">
					<select class="jss-FieldAuto" name="family" onchange="javascript: frOpciones.submit()" size="1">
					<option>-</option>
					<?php echo putOptions("select family from [familys] order by family",$family);?>
					</select></td>
				</tr>
				<tr>
					<td style="text-align: left">
					<select class="jss-FieldAuto" name="buscaPor" size="1">
					<?php echo fieldList($strbase, $buscaPor);?>
					</select> </td>
					<td style="text-align: left">
					<input id="valorBuscar" class="jss-FieldAuto" name="valorBuscar" size="45" value="<?php echo $valorBuscar;?>">
					</td>
					<td style="text-align: right" class="jss-Bar"><?php echo _RATE;?>:</td>
					<td style="text-align: left">
					<select class="jss-FieldAuto" name="rate" onchange="javascript: frOpciones.submit()" size="1">
					<option>-</option>
					<?php echo putOptions("select Rate from Rates order by Rate",$rate);?>
					</select></td>
					<td style="text-align: center" rowspan="2">
					<input class="jss-Boton" name="insertar" onclick="javascript:document.location.replace('ficha.php?idProduct=0')" type="button" value="<?php echo _NEW;?>"></td>
				</tr>
				<tr>
					<td id="azBar" colspan="4" ></td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt"><?php echo $strsql?></td>
	</tr>
	<tr>
		<td id="list"></td>
	</tr>
</table>
</body>

</html>