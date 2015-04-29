<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");	
	jCnn();
//-------------------------------------------------
// Parametros impresoras
//-------------------------------------------------
$buscaPor="name";
$strsql="select * from [printers] ";
$table="printers";
$keyMaster="idPrint";

//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if (isset($_REQUEST["id"])){
	$_SESSION[$keyMaster]=$_REQUEST["id"];
	$goTo="tableForm.php";
}
else{
	$goTo="tableList.php";
}

require("../tables/".$goTo);
?>
