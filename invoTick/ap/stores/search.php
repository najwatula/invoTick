<?php
	require("../../../cgi_bin/phpFun.php");
	require("../languages/language.php");	
	jCnn();
//-------------------------------------------------
// Parametros Store
//-------------------------------------------------
$buscaPor="store";
$strsql='SELECT * FROM [stores]';
$table="stores";
$keyMaster="idStore";

//-------------------------------------------------
//recoge el puntero
//-------------------------------------------------
if (isset($_REQUEST["id"])){
	$_SESSION[$keyMaster]=$_REQUEST["id"];
	$goTo="tableForm.php";
}
else{
	$goTo="tableSearch.php";
}

require("../tables/".$goTo);
?>
