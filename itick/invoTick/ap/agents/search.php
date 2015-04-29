<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");
	jCnn();

//-------------------------------------------------
// Parametros Agents
//-------------------------------------------------
$buscaPor="name";
$strsql="SELECT idAgent, departament, name, city, phone FROM [agents] ";
$table="agents";
$keyMaster="idAgent";
//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if (isset($_REQUEST["id"])){
	if(!isset($_FILES["file"])){$_SESSION[$keyMaster]=$_REQUEST["id"];}
	$goTo="tableImg.php";
	
    $script="var tdPos=document.getElementsByName('idPos')[0].parentNode;\n".
    "var valPos=document.getElementsByName('idPos')[0].value;\n".
    'var objSelect=\'<select class="jss-FieldAuto" name="idPos" size="1"><option>-</option>'.
    putOptions('select idPos, posName from [pos] order by posName','')."</select>';\n".
    "tdPos.innerHTML=objSelect;\n".
    "document.getElementsByName('idPos')[0].value=valPos;\n".
    "var tdPos=document.getElementsByName('level')[0].parentNode;\n".
    "var valPos=document.getElementsByName('level')[0].value;\n".
    'var objSelect=\'<select class="jss-FieldAuto" name="level" size="1"><option>-</option>'.
    putOptions('select value, level from [levels] order by level','')."</select>';\n".
    "tdPos.innerHTML=objSelect;\n".
    "document.getElementsByName('level')[0].value=valPos;\n";
}
else{
	$goTo="tableSearch.php";
}

require("../tables/".$goTo);
?>
