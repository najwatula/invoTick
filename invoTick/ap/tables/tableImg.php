<?php 
//-------------------------------------------------
//images
//-------------------------------------------------
if(isset($_FILES["file"])) {
	$imgFile=file_get_contents($_FILES["file"]["tmp_name"]);
    
    $exist=jGet("select id from images where [tableName]='$table' and [id]=".$_SESSION[$keyMaster]);
    if($exist){
	    $strsql="update [images] set [image]=:img where id=$exist";
	    $results = $GLOBALS['db']->prepare($strsql);
	}
	else{
	    $strsql='insert into [images] ([tableName], [keyName], [image], [id]) values("'.$table.'", "'.$keyMaster.'", :img, '.$_SESSION[$keyMaster].');';
	    $results = $GLOBALS['db']->prepare($strsql);
	}

	$results->bindParam(':img', $imgFile, PDO::PARAM_LOB);
	$results->execute();
}

//-------------------------------------------------
//Salva los Cambios
//-------------------------------------------------
if(isset($_POST["save"])){
	$_SESSION[$keyMaster]=saveForm($table, $keyMaster, $_SESSION[$keyMaster]);
}

//-------------------------------------------------
//Eliminar
//-------------------------------------------------
if(isset($_REQUEST["eliminar"])){
	$strsql='delete from '.$table.' where '.$keyMaster.'='.$_SESSION[$keyMaster];
	$results = $GLOBALS['db']->exec($strsql);
	$strsql="delete from images where tableName='$table' and id=".$_SESSION[$keyMaster];
	$results = $GLOBALS['db']->exec($strsql);	
	
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}

if($_SESSION[$keyMaster]=='0'){
	$strsql="INSERT INTO [$table] ($buscaPor) VALUES('-')";
	$results=$GLOBALS['db']->exec($strsql);
 	if(!$results){$err=$GLOBALS['db']->errorInfo(); echo '<hr>'.$strsql.'<hr>Error: '.$err[2];}  
	$_SESSION[$keyMaster] = $GLOBALS['db']->lastInsertId();
	$insert=true;
}

$strsql="select * from ".$table." where ".$keyMaster."=".$_SESSION[$keyMaster];
$strImg='../../../cgi_bin/phpFun.php?getImg=base64&strsql='.base64_encode('SELECT [image] from [images] WHERE [tableName]=\''.$table.'\' and [id] = '.$_SESSION[$keyMaster]);
//echo jGet('SELECT [image] from [images] WHERE [tableName]=\''.$table.'\' and [id] = '.$_SESSION[$keyMaster]);
?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<script src="../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../cgi_bin/jss/jssCalendar.js" type="text/javascript"></script>
<title>tableImg</title>
<base target="_self">
<script type="text/javascript">
var puntero;
jss.Init=function(){
	var strsql='<?php  echo urlencode(base64_encode(gzcompress($strsql)))?>';
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
	<?php  if(isset($script)){echo $script;}?>
}
function deleteRecord(){
	new jss.Confirm({
		title:'Confirmación',
		msg:'Esta Seguro de Eliminar Este Registro?',
		yes: 'location.search=\'?eliminar=1&id='+puntero+'\''
	});
}
function showImage(objImg){
	try{wWin.close()} catch(err){};
	wWin= new window.parent.jss.Window({
		style:{width: '450',height: '400'},
		title:'Imagen',
		html:'<table width="100%" height="100%"><tr><td align="center"><div id="wImage"></div></td></tr></table>'
	})
	
	objImg.removeAttribute('onload');
	var dupliPhoto=objImg.cloneNode(true);
	dupliPhoto.removeAttribute('onclick');
	dupliPhoto.removeAttribute('style');
	window.parent.document.getElementById('wImage').appendChild(dupliPhoto);

}
function fPrint(qt){
	var idModel=document.getElementById('idModel').value;
	if(idModel>'0'){
		var ref='../reports/docs/parsec.php?idKey=<?php echo $keyMaster?>&id=<?php echo $_SESSION[$keyMaster]?>&idModel='+idModel;
		wWin= new window.parent.jss.Window({
			style:{width: '80%',height: '80%'},
			title:'Print',
			html:(loadAjax(ref))
		})
	}
}

function cogeFoto(id){
    var paramImage='<?php echo "?table=$table&keyMaster=$keyMaster&id=".$_SESSION[$keyMaster] ?>';
	wWin=new window.top.jss.Window({
	    title:'Photo',
	    style:{width:430, height: 570},
	    iFrame:{id:'iFoto', scroll:'no', url:('../../cgi_bin/plugins/flash/ciberCAM.php'+paramImage)}
	})
	window.top.frames.iFoto.imgRefresh=document.getElementById('picture');
}

</script>
</head>

<body class="jss-Body">

<table class="jss-TablePanel" style="width: 100%; height: 1%;">
	<tr>
		<td style="width: 75%; vertical-align: top;">
		<form class="jss-NoMargins" method="post" style="width: 100%; height: 100%;">
			<table class="jss-TableBorder" style="width: 100%; height: 100%;">
				<tr>
					<td id="qForm" colspan="5" style="vertical-align: top">&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align: center; width: 20%; height: 1%;">
					<input class="jss-Boton" name="save" type="submit" value="<?php  echo _SAVE?>"></td>
					<td style="text-align: center; width: 20%; height: 1%;">
					<input class="jss-Boton" name="cancel" onclick="javascript: document.location.search='';" type="button" value="<?php  echo _CANCEL?>"></td>
					<td style="text-align: center; width: 20%; height: 1%;">
					<input class="jss-Boton" name="delete" onclick="javascript: deleteRecord()" type="button" value="<?php  echo _DELETE?>"></td>
					<td style="text-align: center; width: 20%; height: 1%;">
					<input class="jss-Boton" name="print" onclick="javascript: fPrint()" type="button" value="<?php  echo _PRINT?>"></td>
					<td style="text-align: center; width: 20%; height: 1%;">
					<select id="idModel" class="jss-FieldAuto" name="D1" size="1">
					<option>-</option>
					<?php  echo putOptions("select idModel, title from [docsModel] where type!='Invoice' order by title","");?>
					</select></td>
				</tr>
			</table>
			<input name="id" type="hidden" value="<?php  echo $_SESSION[$keyMaster]?>">
		</form>
		</td>
		<td style="width: 25%; vertical-align: top;">
		<form id="fLogo" class="jss-NoMargins" enctype="multipart/form-data" method="post" name="fLogo" style="width: 100%; height: 100%;">
			<table class="jss-TableBorder" style="width: 100%; height: 100%;" id="frameImage">
				<tr>
					<td style="text-align: center;" colspan="2">
					<img id="picture" alt="" onclick="javascript: showImage(this);" src="<?php echo $strImg ?>" style="max-height: 60%; max-width: 80%"></td>
				</tr>
				<tr>
					<td style="height: 1%; width: 99%;">
					<input id="file" class="jss-FieldAuto" name="file" onchange="javascript: this.form.submit();" size="5" type="file"></td>
					<td>
					<img alt="Image Capture" class="jss-Cursor" onclick="javascript: cogeFoto();" src="../../images/camera16.png" style="width: 16px; height: 16px"></td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
</table>
<?php  if(isset($level2)){echo $level2;}?>

</body>

</html>
