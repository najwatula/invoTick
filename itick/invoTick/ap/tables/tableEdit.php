<?php 
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");		
	jCnn();
?>

<?php 

//Captura de Parametros
//---------------------------------------------
if(isset($_REQUEST['tabla'])){
	$tabla=$_REQUEST['tabla'];
  	$strsql="select * from [$tabla] order by 2";	
}

if(isset($_REQUEST['$strsql'])){
	$strsql=$_REQUEST['$strsql'];
}

//Abre SQL
//---------------------------------------------
$rst = $GLOBALS['db']->query($strsql);
$meta = $rst->getColumnMeta(0);
$keyMaster=$meta["name"];

//Funciones
//---------------------------------------------
if(isset($_REQUEST["salvar"])){
	saveForm ($tabla, $keyMaster, $_REQUEST["salvar"]);
}
elseif(isset($_REQUEST["eliminar"])){
	$strCommand='delete from '.$tabla.' where '.$keyMaster.'='.$_REQUEST["eliminar"];
	$res = $GLOBALS['db']->exec($strCommand);
}
elseif(isset($_REQUEST["insertar"])){
	$strCommand="insert into [".$tabla."] default values";
	$res = $GLOBALS['db']->exec($strCommand);
}


//Ancho de Columna
//---------------------------------------------
function retLen($col){
	global $rst;
	switch ($rst->columnType($col)){
		case SQLITE3_INTEGER || SQLITE3_FLOAT :
			$valRet=8;
			break;	
		default:
			$valret=textLen;
	};
	return $valRet;
}

function capNames(){
	global $rst;
	$capNames='';
	for ($col = 0; $col < $rst->columnCount(); $col++) {
	  $meta = $rst->getColumnMeta($col);
	
		$capNames=$capNames.'<td>'.$meta["name"].'</td>';
	}
	return $capNames;
}

function contentValues($row){
	global $rst;
	$contentValues='';
	$nCols=$rst->columnCount();
	for ($col = 0; $col < $rst->columnCount(); $col++) {
	  $meta = $rst->getColumnMeta($col);
		if($meta["native_type"] == 'integer' ){
			$contentValues=$contentValues.'<td width="1%"><input size="10" align="right" class="jss-Field" name="'.$meta["name"].'" value="'.$row[$col].'"</td>';		
		}
		else{
			$contentValues=$contentValues.'<td ><input class="jss-FieldAuto" name="'.$meta["name"].'" value="'.$row[$col].'"</td>';
		}
	}
	return $contentValues;
}
?>
<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<title>Tablas</title>
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>

<base target="_self">
<script type="text/javascript">
function deleteRecord(id){
	new jss.Confirm({
		title:'Confirmacin',
		msg:'Esta Seguro de Eliminar Este Registro? ('+id+')',
		yes: 'document.location.search=\'?eliminar='+id+'&tabla=<?php  echo $tabla;?>\''
	});
}
</script>
</head>

<body class="Jss-Body">

<table id="contenedor" class="jss-TablePanel" style="width: 99%">
	<tr>
		<td>
		<table id="cabecera" class="jss-Table" style="width: 100%" >
			<tr class="jss-Caption">
				<td style="width: 99%" >&nbsp;<?php  echo _LIST." ".$tabla;?>&nbsp; [<?php  echo jCount($tabla,''); ?>]</td>
				<td style="width: 1%" >
				<a href="tableEdit.php?insertar=1&tabla=<?php  echo $tabla;?>">
				<img alt="Insertar Nuevo Registro" src="../../images/insert22.gif" style="border-width: 0px; width: 22px; height: 22px;"></a></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table id="lista" class="jss-Table" style="width: 100%" >
			<tr class="Jss-Bar">
				<?php  echo capNames()?>
				<td style="width: 1%"  >-</td>
				<td style="width: 1%"  >-</td>
			</tr>
			<?php  $nr=0?>
			<?php  while( $row = $rst->fetch() ){?>
			<form  method="POST" name="<?php  echo 'line'.$nr; ?>" style="margin-top: 0; margin-bottom: 0">
			<tr>
				<?php  echo contentValues($row)?>					
				<td ><a href="javascript: deleteRecord('<?php  echo $row[0]?>')">
				<img alt="Eliminar"  src="../../images/Delete16.gif" style="border-width: 0px; width: 16px; height: 16px"></a></td>
				<td ><input class="jss-BotonSave" name="salvar" type="submit" value="<?php  echo $row[0]?>"></td>
			</tr>
			<input name="tabla" type="hidden" value="<?php  echo $tabla;?>">
			</form>
			<?php  $nr=$nr+1;?><?php  } ?>
		</table>
		</td>
	</tr>
</table>
</body>
