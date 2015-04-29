<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");	
	jCnn();
//-------------------------------------------------
// Parametros tipos de pago
//-------------------------------------------------
$buscaPor="VAT";
$strsql="select * from [vatTypes] ";
$table="vatTypes";
$keyMaster="idVat";

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
