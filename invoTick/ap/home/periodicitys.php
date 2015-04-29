<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");	
	jCnn();
//-------------------------------------------------
// Parametros tipos de pago
//-------------------------------------------------
$buscaPor="periodicity";
$strsql="select * from [periodicitys] ";
$table="periodicitys";
$keyMaster="idPeriodicity";

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
