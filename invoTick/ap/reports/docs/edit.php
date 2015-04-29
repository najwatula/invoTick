<?php
	require("../../../../cgi_bin/phpFun.php");
	require("../../languages/language.php");		
	jCnn();
?><?php

$insertImage=''; $strsql=''; $idModel='';
//-------------------------------------------------
//Images
//-------------------------------------------------
if (isset($_REQUEST['idImageDoc'])) {
	$idModel=$_REQUEST['idImageDoc'];
	$insertImage=('<img alt="" border="0" name="logo" src="data:'.$_FILES['file']['type'].';base64,'.base64_encode(file_get_contents($_FILES['file']['tmp_name'])).'">');

	//$db=$GLOBALS['db'];
	//$results =$GLOBALS['db']->prepare('update [docsModel] set content=:img1+content where idModel='.$idModel);
	//$results->bindParam(':img1', $insertImage, PDO::PARAM_LOB);
	//$results->execute();
} 

//-------------------------------------------------
//Salva los Cambios
//-------------------------------------------------
if (isset($_POST["idModel"])){
	$idModel=$_POST["idModel"];
	saveForm ('docsModel', "idModel", $idModel);
	unset($_REQUEST["eliminar"]);
	unset($_REQUEST["insertar"]);	
}

//-------------------------------------------------
//Inserta
//-------------------------------------------------
if (isset($_REQUEST["insertar"])){
	saveForm ('docsModel', "idModel", "0");
	$idModel=$_SESSION['id'];
	unset($_REQUEST["eliminar"]);	
}
//-------------------------------------------------
//Eliminar
//-------------------------------------------------
if (isset($_REQUEST["eliminar"])){
	$strCommand='delete from docsModel where idModel='.$_REQUEST["eliminar"];
	$res = $GLOBALS['db']->exec($strCommand);
}

//-------------------------------------------------
//SQL
//-------------------------------------------------
if ($idModel){$strSql="select * from docsModel where idModel=".$idModel;}
else{$strSql="select * from docsModel limit 1";}

?>
<html>

<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="es" http-equiv="Content-Language">
<link href="../../../../cgi_bin/jss/jss.css" rel="stylesheet" type="text/css">
<link href="../../../../cgi_bin/jss/jssEdit.css" rel="stylesheet" type="text/css">
<script src="../../../../cgi_bin/jss/jss.js" type="text/javascript"></script>
<script src="../../../../cgi_bin/jss/jssEdit.js" type="text/javascript"></script>
<title>Editar HTML</title>
<script type="text/javascript">
var editor;
jss.Init=function(){

	var strsql='<?php echo base64_encode($strSql) ?>';
	var datos=loadAjax('../../../../cgi_bin/phpFun.php?jsgetArray=base64&strsql='+strsql).evalDecode();

	jss.putData(datos['columns'],datos['values']);
	document.getElementById('idImageDoc').value=datos['values'][0][0];		
	editor=new jss.Edit({
		style:{width: (datos['values'][0][4])+'mm', height:'100%'},
		renderTo:'pArea',
		buttons:{save:'salvar()',del:'borraRegistro()',insert:'inserta()',print:'imprime(0)'}
	})
	document.getElementById('content').value+='<?php echo $insertImage ?>';
	editor.putHTML(document.getElementById('content').value);
}

function changeDoc(idDoc, doc){
	document.getElementById('title').value=doc;
	document.getElementById('reference').innerHTML='['+idDoc+']';
	
	var strsql='select content, width, type from [docsModel] where idModel='+idDoc;
	var datos=loadAjax('../../../../cgi_bin/phpFun.php?jsgetArray=1&strsql='+strsql).evalDecode();

	document.getElementById('idImageDoc').value=idDoc;	
	editor.panel.table.style.width=datos['values'][0][1]+'mm';
	document.getElementById('width').value=datos['values'][0][1];
	document.getElementById('type').value=datos['values'][0][2];			
	editor.putHTML(datos['values'][0][0]);
}

function salvar(){
	document.getElementById('content').value=editor.getHTML();
	fData.submit();
}

function borraRegistro (){
	var id=document.getElementById('idModel').value;
	new jss.Confirm({
		title:'Alerta!',
		msg:('Esta Seguro de Eliminar Este Registro? ('+id+')'),
		yes:('document.location.search="?eliminar='+id+'";')
	})
}
function inserta(){
	document.location.search='?insertar=1';
}

function imprime(qt){
	var ref='parsec.php?idModel='+document.getElementById('idModel').value;
	if (qt=='pdf'){
		ref='../../../../cgi_bin/wkhtmltopdf.php?url='+getUrl("db/web/Informes/Remesas/email.asp");
	}
	document.location.href=ref;	
}
function putFields(tbl){
	if (tbl=='-'){return false}	
	var strsql='select * from '+tbl+ ' limit 1';
	var datos=loadAjax('../../../../cgi_bin/phpFun.php?jsgetArray=1&strsql='+strsql).evalDecode();
	if (typeof datos!='object'){document.getElementById('listFields').innerHTML=datos; return;};	
	
	for (n=0; n<datos['columns'].length;n++){
		datos['values'][n]=new Array();
		datos['values'][n][1]=(datos['types'][n]);		
		datos['values'][n][0]=(datos['columns'][n]);
	}
	
	document.getElementById('listFields').innerHTML='';
	new jss.Grid({
		renderTo:'listFields',
		fireEvent: 'putVal',
		values: datos['values'],
		columns: [['fields'],['types']]
	})
}

function putVal(q){
	GetSelectedText ();
	var qVal=q.cells[0].innerText || q.cells[0].textContent;
	var family=document.getElementById('qTables');
	family=family[family.selectedIndex].text;
	qVal=family+'->'+qVal;
	if(window.clipboardData){clipboardData.setData("Text", qVal);}
	editor.execCommand('paste');	
}

        function GetSelectedText () {
            if (editor.editArea.getSelection) {  // all browsers, except IE before version 9
                var range = editor.editArea.getSelection ();                                        
                alert (range.toString ());
            } 
            else {
                if (editor.editArea.selection.createRange) { // Internet Explorer
                    var range = editor.editArea.selection.createRange ();
                    alert (range.text);
                }
            }
        }

</script>
</head>

<body class="Jss-Body">

<table id="container" style="height: 100%; width: 100%;">
	<tr>
		<td id="pArea" style="vertical-align: top; width: 1%;"></td>
		<td style="vertical-align: top">
		<table id="panel" style="width: 50mm; height: 100%;">
			<tr>
				<td style="height: 1%">
				<form class="jss-NoMargins" method="post" name="fData">
					<table class="jss-Table" style="width: 100%">
						<tr>
							<td class="jss-Caption"><?php echo _OPEN?>:</td>
							<td class="jss-Caption" id="reference" style="text-align: right"></td>
						</tr>
						<tr>
							<td colspan="2">
							<select id="idModel" class="jss-FieldAuto" name="idModel" onchange="javascript: changeDoc(this.value, this.options[this.selectedIndex].text);" size="1">
							<?php echo putOptions("select idModel, title from [docsModel] order by title ","");?>
							</select></td>
						</tr>
						<tr>
							<td class="jss-Bar" colspan="2"><?php echo _TITLE?>:</td>
						</tr>
						<tr>
							<td colspan="2">
							<input id="title" class="jss-FieldAuto" name="title" size="80" type="text"></td>
						</tr>
						<tr>
							<td class="jss-Bar" colspan="2"><?php echo _TYPE?>:</td>
						</tr>
						<tr>
							<td colspan="2">
							<select id="type" class="jss-FieldAuto" name="type" size="1">
							<option>Invoice</option>
							<option>List</option>
							<option>Unique</option>
							</select></td>
						</tr>
						<tr>
							<td class="jss-Bar" colspan="2"><?php echo _SIZE?>:</td>
						</tr>
						<tr>
							<td colspan="2">
							<select id="width" class="jss-FieldAuto" name="width" onchange="javascript: editor.panel.table.style.width=(this.value)+'mm';" size="1">
							<option selected="" value="210">A4 297 x 210 mm
							</option>
							<option value="148">A5 210 X 148 mm</option>
							<option value="105">A6 148 x 105 mm</option>
							<option value="74">A7 105 x 74 mm</option>
							<option value="52">A8 74 x 52 mm</option>
							<option value="37">A9 52 x 37 mm</option>
							</select></td>
						</tr>
						</table>
					<textarea id="content" name="content" style="border-width: 0px; display: none"></textarea>
				</form>
				</td>
			</tr>
			<tr>
				<td style="height: 1%">
				<form class="jss-NoMargins" enctype="multipart/form-data" method="post" name="fLogo">
					<table id="tImage" class="jss-Table" style="width: 100%">
						<tr>
							<td class="jss-Bar"><?php echo _INSERT._._IMAGE?></td>
						</tr>
						<tr>
							<td>
							<input name="file" class="jss-FieldAuto" size="5" type="file">
							</td>
						</tr>
						<tr>
							<td style="text-align: center">
							<input class="jss-Boton" id="sendImage" name="sendImage" type="submit" value="Send"></td>
						</tr>
					</table>
					<input id="idImageDoc" name="idImageDoc" type="hidden" value="0">
				</form>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; height: 1%;">
				<table id="tFields" class="jss-Table" style="width: 100%; height: 1%;">
					<tr>
						<td class="jss-Bar">&nbsp;Tables</td>
					</tr>
					<tr>
						<td>
						<select class="jss-FieldAuto" name="qTables" onchange="javascript: putFields(this.options[this.selectedIndex].text);" size="1">
						<option selected="" value="-">-</option>
						<?php echo tableList('all','') ;?>
						</select></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td id="listFields" style="overflow: auto; vertical-align: top;">
				</td>
			</tr>
		</table>
		</td>
	</tr>
	</table>
</body>

</html>
