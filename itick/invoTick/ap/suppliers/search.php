<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");	
	jCnn();
//-------------------------------------------------
// Parametros Client
//-------------------------------------------------
$buscaPor="company";
$strsql='SELECT idSupplier, company, contact, city, phone FROM [suppliers]';
$table='suppliers';
$keyMaster='idSupplier';
//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if (isset($_REQUEST["id"])){
	if(!isset($_FILES["file"])){$_SESSION[$keyMaster]=$_REQUEST["id"];}
	$goTo="tableImg.php";
}
else{
	$goTo="tableSearch.php";
}

require("../tables/".$goTo);
?>
