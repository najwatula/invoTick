<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");	
	jCnn();
//-------------------------------------------------
// Parametros tipos de pago
//-------------------------------------------------
$buscaPor="payType";
$strsql="select * from [payTypes] ";
$table="payTypes";
$keyMaster="idPayType";

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
