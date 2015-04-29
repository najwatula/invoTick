<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");	
	jCnn();
//-------------------------------------------------
// Parametros Series facturas
//-------------------------------------------------
$buscaPor="serie";
$strsql="select * from [invoiceSeries] ";
$table="invoiceSeries";
$keyMaster="idSerie";

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

