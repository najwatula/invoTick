<?php
//-------------------------------------------------
// Captura de Parametros
//-------------------------------------------------
$sep="'";
$bus='>=';
$orden=' COLLATE NOCASE ASC';

if(isset($_REQUEST["buscaPor"])){
	$buscaPor=$_REQUEST["buscaPor"];
}

$valorBuscar="";
if(isset($_REQUEST["valorBuscar"])){
	$valorBuscar=$_REQUEST["valorBuscar"];
}

if(isset($_REQUEST["like"])){
  	$bus=" like ";
  	$valorBuscar="%".$valorBuscar."%";
}

if(substr($buscaPor,0,2)=="id"){
  $sep='';
  if(!is_numeric($valorBuscar)){
  	$valorBuscar="0";
    $orden=' DESC';
  }
}


//-------------------------------------------------
// Abre el Recordset
//-------------------------------------------------
$strbase=$strsql;

if($valorBuscar>''){
  	$strsql=$strsql." WHERE [$buscaPor] $bus $sep$valorBuscar$sep COLLATE NOCASE";
}
$strsql=$strsql." ORDER BY [$buscaPor] $orden";
$strExport='../../../cgi_bin/phpFun.php?toXls='.base64_encode('select * from '.$table.' order by '.$buscaPor);
$strsql=$strsql.' LIMIT 50';

?>
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<base target="_self">
<title>tableSearch</title>
<script type="text/javascript">
var tList;
jss.Init=function(){
	new jss.azBar({
		putTo:'valorBuscar',
		fireEvent:'document.getElementById(\'frOpciones\').submit()',
		renderTo:'azBar'
	})
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
		<td>
		<form id="frOpciones" method="POST" name="frOpciones" class="jss-NoMargins">
			<table id="cabecera" class="jss-TableBorder" style="width: 100%" >
				<tr>
					<td class="jss-Caption" colspan="2" >&nbsp;<?php  echo _SEARCH." ".ucwords($table).' ['.jCount($table,'').']';?></td>
					<td rowspan="4" style="width: 1%">
					<a href="<?php echo $strExport;?>">
					<img alt=""  src="../../images/excel16.jpg" style="border-width: 0px; width: 16px; height: 16px"></a></td>
					<td rowspan="2" style="width: 1%">
					<input class="jss-Boton" name="buscar" type="submit" value="<?php  echo _SEARCH?>"></td>
				</tr>
				<tr>
					<td style="width: 10%" class="jss-Bar" ><?php  echo _SEARCH?></td>
					<td class="jss-Bar"><?php  echo _VALUE?> (<?php  echo _LIKE?><input name="like" type="checkbox" value="1">)</td>
				</tr>
				<tr>
					<td>
					<select class="jss-Field" name="buscaPor" size="1" style="width: 120px">
					<?php  echo fieldList($strbase, $buscaPor);?></select> </td>
					<td>
					<input id="valorBuscar" class="jss-FieldAuto" name="valorBuscar" size="45" value="<?php  echo $valorBuscar;?>">
					</td>
					<td rowspan="2">
					<input class="jss-Boton" name="insertar" onclick="javascript:document.location.search='?id=0'" type="button" value="<?php  echo _NEW?>"></td>
				</tr>
				<tr>
					<td id="azBar" colspan="2" ></td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
	<tr>
		<td id="sqlString" style="font-size: 6pt"><?php echo $strsql?></td>
	</tr>
	<tr>
		<td id="list"></td>
	</tr>
</table>

</body>
</html>